<?php
class DevNick_Offlinepay_Model_Standard extends Mage_Paypal_Model_Standard
{
    public function getStandardCheckoutFormFields()
    {
        $orderIncrementId = Mage::app()->getRequest()->getParam('orderid');

        $order = Mage::getModel('sales/order')->loadByIncrementId($orderIncrementId);
        $api = Mage::getModel('paypal/api_standard')->setConfigObject($this->getConfig());
        $api->setOrderId($orderIncrementId)
            ->setCurrencyCode($order->getBaseCurrencyCode())
            ->setOrder($order)
            ->setNotifyUrl(Mage::getUrl('paypal/ipn/'))
            ->setReturnUrl(Mage::getUrl('paypal/standard/success'))
            ->setCancelUrl(Mage::getUrl('paypal/standard/cancel'));

        $isOrderVirtual = $order->getIsVirtual();
        $address = $isOrderVirtual ? $order->getBillingAddress() : $order->getShippingAddress();
        if ($isOrderVirtual) {
            $api->setNoShipping(true);
        } elseif ($address->validate()) {
            $api->setAddress($address);
        }

        $api->setPaypalCart(Mage::getModel('paypal/cart', array($order)))
            ->setIsLineItemsEnabled($this->_config->lineItemsEnabled)
        ;
        $api->setCartSummary($this->_getAggregatedCartSummary());

        $result = $api->getStandardCheckoutRequest();
        return $result;
    }
    
    private function _getAggregatedCartSummary()
    {
        if ($this->_config->lineItemsSummary) {
            return $this->_config->lineItemsSummary;
        }
        return Mage::app()->getStore($this->getStore())->getFrontendName();
    }
}