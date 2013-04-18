<?php
class DevNick_Offlinepay_Model_Observer extends Mage_Sales_Model_Order {
    
    const XML_PATH_EMAIL_COPY_TO                = 'sales_email/order/copy_to';
    const XML_PATH_EMAIL_COPY_METHOD            = 'sales_email/order/copy_method';
    const XML_PATH_EMAIL_IDENTITY               = 'sales_email/order/identity';
    
    public function offpayOption($observer) {
        $order = $observer->getEvent()->getOrder();
        if ($_REQUEST['payment']['method'] == 'offlinepay') {
            $order->setData('offpaytype', $_REQUEST['payment']['offtype']);
            if ($_REQUEST['payment']['offtype'] == 1) {
                
                $storeId = $order->getStore()->getId();
                $data = $order->getData();

                if (!Mage::helper('sales')->canSendNewOrderEmail($storeId)) {
                    return $order;
                }
                // Get the destination email addresses to send copies to
                $copyTo = $order->_getEmails(self::XML_PATH_EMAIL_COPY_TO);
                $copyMethod = Mage::getStoreConfig(self::XML_PATH_EMAIL_COPY_METHOD, $storeId);
        
                // Start store emulation process
                $appEmulation = Mage::getSingleton('core/app_emulation');
                $initialEnvironmentInfo = $appEmulation->startEnvironmentEmulation($storeId);
        
                try {
                    // Retrieve specified view block from appropriate design package (depends on emulated store)
                    $paymentBlock = Mage::helper('payment')->getInfoBlock($order->getPayment())
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
                $customerName = $order->getCustomerName();
        
                $mailer = Mage::getModel('core/email_template_mailer');
                $emailInfo = Mage::getModel('core/email_info');
                $emailInfo->addTo($order->getCustomerEmail(), $customerName);
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
                        'order'        => $order,
                        'billing'      => $order->getBillingAddress(),
                        'payment_html' => $paymentBlockHtml,
                        'paypalurl' => Mage::getUrl('offlinepay/redirect/index', array('orderid' => $data['increment_id']))
                    )
                );
                $mailer->send();
        
                $order->setEmailSent(true);
                $order->_getResource()->saveAttribute($order, 'email_sent');
            }
        }
    }
}
