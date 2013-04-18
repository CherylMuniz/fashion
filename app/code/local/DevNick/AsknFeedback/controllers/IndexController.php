<?php
class DevNick_AsknFeedback_IndexController extends Mage_Core_Controller_Front_Action
{
    public function askAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }
    
    public function feedbackAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }
    
    public function askpostAction()
    {
        $post = $this->getRequest()->getPost();
        try {
            $mail = new Zend_Mail();
            $mail->setType(Zend_Mime::MULTIPART_RELATED);
            $mail->setBodyHtml($post['question']);
            $mail->setFrom($post['email'], $post['name']);
            $mail->addTo('infofashioneye@gmail.com', '');
            $mail->setSubject(Mage::helper('contacts')->__('New question'));
            $mail->send();
            Mage::getSingleton('customer/session')->addSuccess(Mage::helper('contacts')->__('Your question was submitted and will be responded to as soon as possible. Thank you for asking us.'));
            $this->_redirectReferer();
            return;
        } catch (Exception $e) {
            Mage::getSingleton('customer/session')->addSuccess(Mage::helper('contacts')->__('Unable to submit your request. Please, try again later.'));
            $this->_redirectReferer();
            return;
        }
    }
    
    public function postAction()
    {
        $post = $this->getRequest()->getPost();
        try {
            $mail = new Zend_Mail();
            $mail->setType(Zend_Mime::MULTIPART_RELATED);
            $mail->setBodyHtml('email: '.$post['email'].'<br />name: '.$post['name'].'<br />message:<br />'.$post['feedback']);
            $mail->setFrom($post['email'], $post['name']);
            #$mail->setFrom('no-reply@fashioneyewear.co.uk', 'fashioneyewear');
            $mail->addTo('nathan@fashioneyewear.co.uk', '');
            $mail->addTo('tejjohal@gmail.com', '');
            $mail->setSubject(Mage::helper('contacts')->__('New feedback'));
            $mail->send();
            Mage::getSingleton('customer/session')->addSuccess(Mage::helper('contacts')->__('Your feedback was submitted.'));
            $this->_redirectReferer();
            return;
        } catch (Exception $e) {
            Mage::getSingleton('customer/session')->addSuccess(Mage::helper('contacts')->__('Unable to submit your request. Please, try again later.'));
            $this->_redirectReferer();
            return;
        }
    }
}