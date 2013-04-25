<?php
class DevNick_Offlinepay_Model_Order extends Mage_Sales_Model_Order
{
    public function sendNewOrderEmail()
    {
        if ($_REQUEST['payment']['method'] == 'offpaypal') {
            $this->setData('offpaytype', $_REQUEST['payment']['offtype']);
                
            $storeId = $this->getStore()->getId();
            $data = $this->getData();

            if (!Mage::helper('sales')->canSendNewOrderEmail($storeId)) {
                return $this;
            }
            // Get the destination email addresses to send copies to
            $copyTo = $this->_getEmails(self::XML_PATH_EMAIL_COPY_TO);
            $copyMethod = Mage::getStoreConfig(self::XML_PATH_EMAIL_COPY_METHOD, $storeId);
    
            // Start store emulation process
            $appEmulation = Mage::getSingleton('core/app_emulation');
            $initialEnvironmentInfo = $appEmulation->startEnvironmentEmulation($storeId);
    
            try {
                // Retrieve specified view block from appropriate design package (depends on emulated store)
                $paymentBlock = Mage::helper('payment')->getInfoBlock($this->getPayment())
                    ->setIsSecureMode(true);
                $paymentBlock->getMethod()->setStore($storeId);
                $paymentBlockHtml = $paymentBlock->toHtml();
            } catch (Exception $exception) {
                // Stop store emulation process
                $appEmulation->stopEnvironmentEmulation($initialEnvironmentInfo);
                throw $exception;
            }
    
            // Stop store emulation process
            $appEmulation->stopEnvironmentEmulation($initialEnvironmentInfo);
            $customerName = $this->getCustomerName();
    
            $mailer = Mage::getModel('core/email_template_mailer');
            $emailInfo = Mage::getModel('core/email_info');
            $emailInfo->addTo($this->getCustomerEmail(), $customerName);
            if ($copyTo && $copyMethod == 'bcc') {
                // Add bcc to customer email
                foreach ($copyTo as $email) {
                    $emailInfo->addBcc($email);
                }
            }
            $mailer->addEmailInfo($emailInfo);
    
            // Email copies are sent as separated emails if their copy method is 'copy'
            if ($copyTo && $copyMethod == 'copy') {
                foreach ($copyTo as $email) {
                    $emailInfo = Mage::getModel('core/email_info');
                    $emailInfo->addTo($email);
                    $mailer->addEmailInfo($emailInfo);
                }
            }
            $templateId = 22;
            // Set all required params and send emails
            $mailer->setSender(Mage::getStoreConfig(self::XML_PATH_EMAIL_IDENTITY, $storeId));
            $mailer->setStoreId($storeId);
            $mailer->setTemplateId($templateId);
            $mailer->setTemplateParams(array(
                    'order'        => $this,
                    'billing'      => $this->getBillingAddress(),
                    'payment_html' => $paymentBlockHtml,
                    'paypalurl' => Mage::getUrl('offlinepay/redirect/index', array('orderid' => $data['increment_id']))
                )
            );
            $mailer->send();
    
            $this->setEmailSent(true);
            $this->_getResource()->saveAttribute($this, 'email_sent');
            return $this;
        } else if ($_REQUEST['payment']['method'] == 'offsagepay') {
            //Do nothing
        } else {
            return parent::sendNewOrderEmail();
        }
    }
    
    public function setState($state, $status = false, $comment = '', $isCustomerNotified = null)
    {
        if (!empty($_REQUEST['payment']['method']) && $_REQUEST['payment']['method'] == 'offpaypal') {
            return $this->_setState(self::STATE_PENDING_PAYMENT, self::STATE_PENDING_PAYMENT, $comment, 0, true);
        }
        else {
            return $this->_setState($state, $status, $comment, $isCustomerNotified, true);
        }
    }
}