<?
class Oberig_Addproduct_CartController extends Mage_Core_Controller_Front_Action
{
    public function addAction(){
        $get = Mage::app()->getRequest()->getParams();
        $cart = Mage::getModel("checkout/cart");
        $params = array(
            'product' => $get['product'],
            'related_product' => null,
            'options' =>  $get['options'],
            'qty' => $get['qty'],
        );
        $p =Mage::getModel('catalog/product')->load($get['product']);
        $cart->addProduct($p, $params);
        $cart->save();
    }
}