<?php

class Magestore_Giftvoucher_Model_Observer
{
	public function couponPostAction($observer){
		$action = $observer->getEvent()->getControllerAction();
		$code = trim($action->getRequest()->getParam('coupon_code'));
		if (!$code) return $this;
		
		if(!Mage::helper('magenotification')->checkLicenseKey('Giftvoucher')){return;}
		
		if (!Mage::helper('giftvoucher')->isAvailableToAddCode()){
			$session = Mage::getSingleton('checkout/session');
			$session->addError(Mage::helper('giftvoucher')->__('The maximum number of times to enter Gift Voucher code is %d!',Mage::helper('giftvoucher')->getGeneralConfig('maximum')));
			$action->setFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
			$action->getResponse()->setRedirect(Mage::getUrl('checkout/cart'));
			return $this;
		}
		
		$giftVoucher = Mage::getModel('giftvoucher/giftvoucher')->loadByCode($code);
		if ($giftVoucher->getId() && $giftVoucher->getStatus() == Magestore_Giftvoucher_Model_Status::STATUS_ACTIVE && $giftVoucher->getBaseBalance() > 0){
			$session = Mage::getSingleton('checkout/session');
			$giftVoucher->addToSession($session);
			$session->addSuccess(Mage::helper('giftvoucher')->__('Gift voucher "%s" was applied to your order.',Mage::helper('giftvoucher')->getHiddenCode($giftVoucher->getGiftCode())));
			$action->setFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
			$action->getResponse()->setRedirect(Mage::getUrl('checkout/cart'));
		}
		return $this;
	}
	
	public function collectTotalsAfter($observer){
		if ($code = trim(Mage::app()->getRequest()->getParam('coupon_code'))){
			$quote = $observer->getEvent()->getQuote();
			if ($code != $quote->getCouponCode()){
				$codes = Mage::getSingleton('giftvoucher/session')->getCodes();
				$codes[] = $code;
				$codes = array_unique($codes);
				Mage::getSingleton('giftvoucher/session')->setCodes($codes);
			}
		}
	}
	
	public function collectTotalsBefore($observer){
		$session = Mage::getSingleton('checkout/session');
		if ($codes = $session->getGiftCodes()){
			$codesArray = array_unique(explode(',',$codes));
			foreach ($codesArray as $key=>$value)
				$codesArray[$key] = 0;
			$session->setBaseAmountUsed(implode(',',$codesArray));
		}else{
			$session->setBaseAmountUsed(null);
		}
		$session->setBaseGiftVoucherDiscount(0);
		$session->setGiftVoucherDiscount(0);
	}
	
	public function orderPlaceBefore($observer){
		$session = Mage::getSingleton('checkout/session');
		if ($codes = $session->getGiftCodes()){
			$codesArray = explode(',',$codes);
			$baseSessionAmountUsed = explode(',',$session->getBaseAmountUsed());
			$baseAmountUsed = array_combine($codesArray,$baseSessionAmountUsed);
			
			foreach ($baseAmountUsed as $code=>$amount){
				$model = Mage::getModel('giftvoucher/giftvoucher')->loadByCode($code);
				if (!$model
					|| $model->getStatus() != Magestore_Giftvoucher_Model_Status::STATUS_ACTIVE
					|| $model->getBaseBalance() < $amount){
					Mage::app()->getResponse()
						->setHeader('HTTP/1.1', '403 Session Expired')
						->setHeader('Login-Required', 'true')
						->sendResponse();
					exit;
				}
			}
		}
	}
	
	public function orderPlaceAfter($observer){
		if(!Mage::helper('magenotification')->checkLicenseKey('Giftvoucher')){return;}
		$order = $observer->getEvent()->getOrder();
		if ($codes = $order->getGiftCodes()){
			$codesArray = explode(',',$codes);
			$codesBaseDiscount = explode(',',$order->getCodesBaseDiscount());
			$codesDiscount = explode(',',$order->getCodesDiscount());
			
			$baseDiscount = array_combine($codesArray,$codesBaseDiscount);
			$discount = array_combine($codesArray,$codesDiscount);
			foreach ($codesArray as $code){
				if (!$baseDiscount[$code] || Mage::app()->getStore()->roundPrice($baseDiscount[$code])==0) continue;
				$giftVoucher = Mage::getModel('giftvoucher/giftvoucher')->loadByCode($code);
				
				$baseCurrencyCode = $order->getBaseCurrencyCode();
				$codeDiscount = Mage::helper('directory')->currencyConvert($baseDiscount[$code],$baseCurrencyCode,$giftVoucher->getData('currency'));
				$balance = $giftVoucher->getBalance() - $codeDiscount;
				
				$giftVoucher->setData('balance',$balance)->save();
				
				$history = Mage::getModel('giftvoucher/history')->setData(array(
					'order_increment_id'	=> $order->getIncrementId(),
					'giftvoucher_id'		=> $giftVoucher->getId(),
					'created_at'			=> now(),
					'action'				=> Magestore_Giftvoucher_Model_Actions::ACTIONS_SPEND_ORDER,
					'amount'				=> $baseDiscount[$code],
					'currency'				=> $baseCurrencyCode,
					'status'				=> $giftVoucher->getStatus(),
					'order_amount'			=> $discount[$code],
					'comments'				=> Mage::helper('giftvoucher')->__('Spend for order %s',$order->getIncrementId()),
					'extra_content'			=> Mage::helper('giftvoucher')->__('Used by %s %s',$order->getData('customer_firstname'),$order->getData('customer_lastname'))//$order->getCustomerFirstname(),$order->getCustomerLastname())
				))->save();
			}
			// Create invoice for Order payed by Giftvoucher
			if (Mage::app()->getStore()->roundPrice($order->getGrandTotal()) == 0
				&& ($order->getPayment()->getMethod() == 'giftvoucher'
					|| $order->getPayment()->getMethod() == 'free')
				&& Mage::getStoreConfigFlag('payment/giftvoucher/invoice')
				&& $order->canInvoice()){
				try {
					$itemQtys = array();
					$invoice = Mage::getModel('sales/service_order',$order)->prepareInvoice($itemQtys);
					$invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::CAPTURE_ONLINE);
					$invoice->register();
					
					$invoice->getOrder()->setIsInProcess(true);
					$transactionSave = Mage::getModel('core/resource_transaction')
						->addObject($invoice)
						->addObject($invoice->getOrder())
						->save();
				} catch (Exception $e){}
			}
		}
		$this->_addGiftVoucherForOrder($order);
		$session = Mage::getSingleton('checkout/session');
		if ($session->getGiftCodes())
			$session->setGiftCodes(null)
				->setBaseAmountUsed(null)
				->setBaseGiftVoucherDiscount(null)
				->setGiftVoucherDiscount(null)
				->setCodesBaseDiscount(null)
				->setCodesDiscount(null);
	}
	
	protected function _loadOrderData($order){
		$giftVouchers = Mage::getModel('giftvoucher/history')->getCollection()->joinGiftVoucher()
			->addFieldToFilter('main_table.order_increment_id',$order->getIncrementId());
		$codesArray = array();
		$baseDiscount = 0;
		$discount = 0;
		foreach ($giftVouchers as $giftVoucher){
			$codesArray[] = $giftVoucher->getGiftCode();
			$baseDiscount += $giftVoucher->getAmount();
			$discount += $giftVoucher->getOrderAmount();
		}
		if ($baseDiscount){
			$order->setGiftCodes(implode(',',$codesArray));
			$order->setBaseGiftVoucherDiscount($baseDiscount);
			$order->setGiftVoucherDiscount($discount);
		}
		return $this;
	}
	
	public function paypalPrepareItems($observer){
		if (version_compare(Mage::getVersion(),'1.4.2','>=')){
			$paypalCart = $observer->getEvent()->getPaypalCart();
			if ($paypalCart){
				$salesEntity = $paypalCart->getSalesEntity();
				if ($salesEntity->getBaseGiftVoucherDiscount())
					$paypalCart->updateTotal(Mage_Paypal_Model_Cart::TOTAL_DISCOUNT,abs((float)$salesEntity->getBaseGiftVoucherDiscount()),Mage::helper('giftvoucher')->__('Gift Voucher Discount'));
			}
		}else{
			$salesEntity = $observer->getSalesEntity();
			$additional = $observer->getAdditional();
			if ($salesEntity && $additional){
				$items = $additional->getItems();
				$items[] = new Varien_Object(array(
					'name'	=> Mage::helper('giftvoucher')->__('Gift Voucher Discount'),
					'qty'	=> 1,
					'amount'	=> -(abs((float)$salesEntity->getBaseGiftVoucherDiscount())),
				));
				$additional->setItems($items);
			}
		}
	}
	
	public function orderLoadAfter($observer){
		$order = $observer->getEvent()->getOrder();
		return $this->_loadOrderData($order);
	}
	
	public function creditmemoSaveAfter($observer){
		$creditmemo = $observer->getEvent()->getCreditmemo();
		$baseGrandTotal = $creditmemo->getBaseGrandTotal();
		$order = $creditmemo->getOrder();
		if ($order->getPayment()->getMethod() == 'giftvoucher'){
			$this->_refundOffline($order,$baseGrandTotal);
		}
	}
	
	protected function _refundOffline($order,$baseGrandTotal){
		if ($codes = $order->getGiftCodes()){
			$codesArray = explode(',',$codes);
			foreach ($codesArray as $code){
				if (Mage::app()->getStore()->roundPrice($baseGrandTotal) == 0) return $this;
				
				$giftVoucher = Mage::getModel('giftvoucher/giftvoucher')->loadByCode($code);
				$history = Mage::getModel('giftvoucher/history');
				
				$availableDiscount = $history->getTotalSpent($giftVoucher,$order) - $history->getTotalRefund($giftVoucher,$order);
				if (Mage::app()->getStore()->roundPrice($availableDiscount) == 0) continue;
				
				if ($availableDiscount < $baseGrandTotal){
					$baseGrandTotal = $baseGrandTotal - $availableDiscount;
				}else{
					$availableDiscount = $baseGrandTotal;
					$baseGrandTotal = 0;
				}
				$baseCurrencyCode = $order->getBaseCurrencyCode();
				$discountRefund = Mage::helper('directory')->currencyConvert($availableDiscount,$baseCurrencyCode,$giftVoucher->getData('currency'));
				
				$balance = $giftVoucher->getBalance() + $discountRefund;
				if ($giftVoucher->getStatus() == Magestore_Giftvoucher_Model_Status::STATUS_USED)
					$giftVoucher->setStatus(Magestore_Giftvoucher_Model_Status::STATUS_ACTIVE);
				$giftVoucher->setData('balance',$balance)->save();
				
				$history->setData(array(
					'order_increment_id'	=> $order->getIncrementId(),
					'giftvoucher_id'		=> $giftVoucher->getId(),
					'created_at'			=> now(),
					'action'				=> Magestore_Giftvoucher_Model_Actions::ACTIONS_REFUND,
					'amount'				=> $availableDiscount,
					'currency'				=> $baseCurrencyCode,
					'status'				=> $giftVoucher->getStatus(),
					'comments'				=> Mage::helper('giftvoucher')->__('Refund from order %s',$order->getIncrementId()),
				))->save();
			}
		}
		return $this;
	}
	
	public function orderSaveAfter($observer){
		$order = $observer->getEvent()->getOrder();
		if ($order->getStatus() == Mage_Sales_Model_Order::STATE_COMPLETE){
			$this->_addGiftVoucherForOrder($order);
		}
		$refundState = explode(',',Mage::helper('giftvoucher')->getGeneralConfig('refund_orderstatus'));
		if (in_array($order->getStatus(),$refundState)){
			$this->_refundOffline($order,$order->getBaseGiftVoucherDiscount());
		}
		if (Mage::helper('giftvoucher')->getGeneralConfig('autochange')){
			$expireState = explode(',',Mage::helper('giftvoucher')->getGeneralConfig('expire_orderstatus'));
			if (in_array($order->getStatus(),$expireState)){
				$this->_expireGiftVoucherOfOrder($order);
			}
		}
	}
	
	protected function _addGiftVoucherForOrder($order){
		foreach ($order->getAllItems() as $item){
			if ($item->getProductType() != 'giftvoucher') continue;
			
			$giftVouchers = Mage::getModel('giftvoucher/giftvoucher')->getCollection()->addItemFilter($item->getId());
			if ($order->getStatus() == Mage_Sales_Model_Order::STATE_COMPLETE
				&& Mage::helper('giftvoucher')->getGeneralConfig('autochange')){
				foreach ($giftVouchers as $giftVoucher){
					if ($giftVoucher->getStatus() == Magestore_Giftvoucher_Model_Status::STATUS_PENDING){
						$giftVoucher->addData(array(
							'status'	=> Magestore_Giftvoucher_Model_Status::STATUS_ACTIVE,
							'comments'	=> Mage::helper('giftvoucher')->__('Active when order complete'),
							'amount'	=> $giftVoucher->getBalance(),
							'action'	=> Magestore_Giftvoucher_Model_Actions::ACTIONS_UPDATE,
						))->setIncludeHistory(true);
						try{
							$giftVoucher->save();
							if (Mage::helper('giftvoucher')->getEmailConfig('enable'))
								$giftVoucher->sendEmail();
						}catch(Exception $e){}
					}
				}
			}
			
			for ($i = 0; $i < $item->getQtyOrdered() - $giftVouchers->getSize() ; $i++){
				$giftVoucher = Mage::getModel('giftvoucher/giftvoucher');
				
				$options = $item->getProductOptions();
				$buyRequest = $options['info_buyRequest'];
				
				//$amount = isset($buyRequest['amount']) ? $buyRequest['amount'] : $item->getPrice();
				if (isset($buyRequest['amount'])){
					$amount = $buyRequest['amount'];
					$includeTax = ( Mage::getStoreConfig('tax/display/type') != 1 );
					$amount = $amount * 100 / Mage::helper('tax')->getPrice($item->getProduct(), 100, $includeTax);
				}else
					$amount = $item->getPrice();
				
				$giftVoucher->setBalance($amount)->setAmount($amount);
				$giftVoucher->setOrderAmount($item->getBasePrice());
				
				$giftVoucher->setRecipientName($buyRequest['recipient_name'])
					->setRecipientEmail($buyRequest['recipient_email'])
					->setMessage($buyRequest['message']);
				
				if ($buyRequest['recipient_ship']
					&& $address = $order->getShippingAddress())
					$giftVoucher->setRecipientAddress($address->getFormated());
				
				$giftVoucher->setCurrency($order->getOrderCurrencyCode());
				
				if ($order->getStatus() == Mage_Sales_Model_Order::STATE_COMPLETE
					&& Mage::helper('giftvoucher')->getGeneralConfig('autochange'))
					$giftVoucher->setStatus(Magestore_Giftvoucher_Model_Status::STATUS_ACTIVE);
				else
					$giftVoucher->setStatus(Mage::helper('giftvoucher')->getGeneralConfig('status'));
				
				if ($timeLife = Mage::helper('giftvoucher')->getGeneralConfig('expire')){
					//$currentDate = Mage::getModel('core/date')->gmtDate();
					$expire = new Zend_Date();//$currentDate);
					$expire->addDay($timeLife);
					$giftVoucher->setExpiredAt($expire->toString('YYYY-MM-dd HH:mm:ss'));
				}
				
				$giftVoucher->setCustomerId($order->getCustomerId())
					->setCustomerName($order->getData('customer_firstname').' '.$order->getData('customer_lastname'))
					->setCustomerEmail($order->getCustomerEmail())
					->setStoreId($order->getStoreId());
				
				$giftVoucher->setAction(Magestore_Giftvoucher_Model_Actions::ACTIONS_CREATE)
					->setComments(Mage::helper('giftvoucher')->__('Created for order %s',$order->getIncrementId()))
					->setOrderIncrementId($order->getIncrementId())
					->setOrderItemId($item->getId())
					->setExtraContent(Mage::helper('giftvoucher')->__('Created by customer %s',$order->getData('customer_firstname')))
					->setIncludeHistory(true);
				try{
					$giftVoucher->save();
					if (Mage::helper('giftvoucher')->getEmailConfig('enable'))
						$giftVoucher->sendEmail();
				}catch(Exception $e){}
			}
		}
		return $this;
	}
	
	protected function _expireGiftVoucherOfOrder($order){
		foreach ($order->getAllItems() as $item){
			if ($item->getProductType() != 'giftvoucher') continue;
			
			$giftVouchers = Mage::getModel('giftvoucher/giftvoucher')->getCollection()->addItemFilter($item->getId());
			foreach ($giftVouchers as $giftVoucher){
				if ($giftVoucher->getStatus() == Magestore_Giftvoucher_Model_Status::STATUS_PENDING
					|| $giftVoucher->getStatus() == Magestore_Giftvoucher_Model_Status::STATUS_ACTIVE){
					$giftVoucher->setStatus(Magestore_Giftvoucher_Model_Status::STATUS_DISABLED);
					try{
						$giftVoucher->save();
					}catch (Exception $e){}
				}
			}
		}
		return $this;
	}
	
	public function autoSendMail(){
		if (Mage::helper('giftvoucher')->getEmailConfig('autosend')){
			$giftVouchers = Mage::getModel('giftvoucher/giftvoucher')->getCollection()
				->addFieldToFilter('status',array('neq' => Magestore_Giftvoucher_Model_Status::STATUS_DELETED))
				->addExpireAfterDaysFilter(Mage::helper('giftvoucher')->getEmailConfig('daybefore'));
			foreach ($giftVouchers as $giftVoucher)
				$giftVoucher->sendEmail();
		}
	}
}