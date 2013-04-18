<?php
class Oberig_Lens_TermsController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        echo '<style>
                a:link    {text-decoration:none;}
                a:visited {text-decoration:none;}
                a:hover   {color:red; text-decoration:none;}
                a:active  {text-decoration:none;}
            </style>';
        echo '<div style="text-align:right"><a href="javascript:" onClick="window.close();" >Close window <img src="' . Mage::getBaseUrl('skin').'frontend/default/likedigital/images/close_modal.gif' . '" hspace="3" height="15" border="0" /></a></div>';
        $terms = Mage::getModel('cms/page')->getCollection()->addFilter('identifier', 'terms')->getFirstItem();
        echo $terms->getContent();
        //echo 'I confirm that I\'ve read and agree to the Terms and Conditions. I certify that I am over 16 years old and that I am not registered blind or partially sighted. I also confirm that the prescription details above have been entered correctly and I am happy that no errors have been made.';
    }
}