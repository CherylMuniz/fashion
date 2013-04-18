<?php
class DevNick_Offlinepay_Block_Redirect extends Mage_Core_Block_Abstract
{
    protected function _toHtml()
    {
        $standard = Mage::getModel('offlinepay/standard');

        $form = new Varien_Data_Form();
        $form->setAction($standard->getConfig()->getPaypalUrl())
            ->setId('paypal_standard_checkout')
            ->setName('paypal_standard_checkout')
            ->setMethod('POST')
            ->setUseContainer(true);
        foreach ($standard->getStandardCheckoutFormFields() as $field=>$value) {
            $form->addField($field, 'hidden', array('name'=>$field, 'value'=>$value));
        }
        $html = '<html><body>';
        $html.= $this->__('You will be redirected to the PayPal website in a few seconds.');
        $html.= $form->toHtml();
        $html.= '<script type="text/javascript">document.getElementById("paypal_standard_checkout").submit();</script>';
        $html.= '</body></html>';

        return $html;
    }
}