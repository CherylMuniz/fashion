<?php

/* ++++++++++++++++ notes ++++++++++
 * catalog_product_index_price - only through saving from adminpanel :(
 * and than 
 * catalog_category_product_index need restart reindex
 * 
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!              First need start lens attributes creating script           !!!!!!!!!!!!!!!!!!!!!!!!!!!
 * 
 */
ini_set("memory_limit","-1");
echo date("\nY-d-m H:i:s\n");
require '../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

class Lenses{

    public $typeId;
    public $categoryName = 'Contact Lenses';
    public $attributeSetName = 'Lenses';
    public $storeId = 1;
    public $keys = array(
                'sku',
                'name',
                'description',
                'short_description',
                'price',
                'weight',
                'qty',
                'lens_thickness',
                'lens_coating',
                'lens_transitions',
                'lens_fully_rimmed',
                'lens_rimmless_supra',
                'lens_designer_frames',
                'lens_prescription_sunglasses',
                'lens_wrapped_frame',
                'lens_oakley_prescription',
                'lens_type',
            );

    public $data = array();
    public $values = array();
    
    public $option_header_keys = array('title','type','is_require','sort_order','values','sku',);
    public $option_body_keys = array('title','price','price_type','sku','sort_order',);

    /*
    public $header_lens_thickness = array('Lens Thickness','drop_down','1','0',array(),);
    public $header_lens_varifocal_type = array('Varifocal Type','drop_down','1','1',array(),);
    public $header_lens_coating = array('Lens Coating','drop_down','1','2',array(),);
    public $header_lens_tint = array('Lens Tint','drop_down','1','3',array(),);
     public $values_lens_thickness = array(
         array('1.5 - Standard', '', 'fixed', '', '0'),
         array('1.6 - Thin', '45.00', 'fixed', '', '1'),
         array('1.67 - Thinner', '71.00', 'fixed', '', '2'),
         array('1.74 - Thinnest', '135.00', 'fixed', '', '3'),
     );
     public $values_lens_varifocal_type = array(
         array('Basic', '44.00', 'fixed', '', '0'),
         array('Advanced', '94.00', 'fixed', '', '1'),
         array('Premium', '144.00', 'fixed', '', '2'),
         array('Elite', '194.00', 'fixed', '', '3'),
     );
     public $values_lens_coating = array(
         array('Scratch Resistant', '25.00', 'fixed', '', '0'),
         array('Anti-scratch/Anti-glare', '25.00', 'fixed', '', '1'),
         array('Premium Coatings', '35.00', 'fixed', '', '2'),
         array('Elite Coatings', '55.00', 'fixed', '', '3'),
     );
     public $values_lens_tint = array(
         array('None', '', 'fixed', '', '0'),
         array('Brown Transitions', '45.00', 'fixed', '', '1'),
         array('Grey Transitions', '45.00', 'fixed', '', '2'),
     );
    */

    // ++++++++++++++++++++++++ prescription options +++++++++++++++++++//
    public $header_sphere_left = array('SPH (Sphere) left','drop_down','1','10',array(),'sphere_left',);
    public $header_sphere_right = array('SPH (Sphere) right','drop_down','1','11',array(),'sphere_right',);
        public $header_cylinder_left = array('CYL (Cylinder) left','drop_down','0','12',array(),'cylinder_left',);
        public $header_cylinder_right = array('CYL (Cylinder) right','drop_down','0','13',array(),'cylinder_right',);
    public $header_axis_left = array('Axis left','drop_down','0','14',array(),'axis_left',);
    public $header_axis_right = array('Axis right','drop_down','0','15',array(),'axis_right',);
        public $header_nearadd_left = array('Near/Add left','drop_down','0','16',array(),'nearadd_left',);
        public $header_nearadd_right = array('Near/Add right','drop_down','0','17',array(),'nearadd_right',);
    public $header_pd = array('PD (Pupil Distance)','drop_down','0','18',array(),'pupil_distance',);
    
    //left eye values
    public $values_sphere_left = array();
    public $values_cylinder_left = array();
    public $values_axis_left = array();
    public $values_nearadd_left = array();
    //right eye values
    public $values_sphere_right = array();
    public $values_cylinder_right = array();
    public $values_axis_right = array();
    public $values_nearadd_right = array();
    
    public $values_pd = array();
    
    public function __construct(){
        $this->typeId = Mage::getModel('eav/entity')->setType('catalog_product')->getTypeId();
        $this->_prepareDara();
        
        
        $this->values = array(
        //array(
            //'single_vision',
            //'Single Vision',
            //'',
            //'',
            //'100.00',
            //'4.0000',
            //'100',
            //
        //),
        //array('bifocal',
            //'Bifocal',
            //'',
            //'',
            //'100.00',
            //'4.0000',
            //'100',
            //),
        //array('varifocal',
            //'Varifocal',
            //'',
            //'',
            //'100.00',
            //'4.0000',
            //'100',
            //),
                                                // 1.5 //
        array(
            'sku'                               => '15_sg',
            'name'                              => '1.5 SCRATCH/GLARE',
            'price'                             => '0.00',
            'weight'                            => '4.0000',
            'qty'                               => '100',
            'lens_type'                        => $this->getAttributeOptionIdByName('lens_type', 'SV'),
            'lens_thickness'                   => $this->getAttributeOptionIdByName('lens_thickness', '1.5'),
            'lens_transitions'                 => $this->getAttributeOptionIdByName('lens_transitions', 'No'),
            'lens_coating'                     => $this->getAttributeOptionIdByName('lens_coating', 'Anti-Skratch/Anti-Glare'),
            'lens_fully_rimmed'                => $this->getAttributeOptionIdByName('lens_fully_rimmed', 'No'),
            'lens_rimmless_supra'              => $this->getAttributeOptionIdByName('lens_rimmless_supra', 'No'),
            'lens_designer_frames'             => $this->getAttributeOptionIdByName('lens_designer_frames', 'No'),
            'lens_prescription_sunglasses'     => $this->getAttributeOptionIdByName('lens_prescription_sunglasses', 'No'),
            'lens_wrapped_frame'               => $this->getAttributeOptionIdByName('lens_wrapped_frame', 'No'),
            'lens_oakley_prescription'         => $this->getAttributeOptionIdByName('lens_oakley_prescription', 'No'),
            ),
        array(
            'sku'                               => '15_pc',
            'name'                              => '1.5 PREMIUM COATINGS',
            'price'                             => '25.00',
            'weight'                            => '4.0000',
            'qty'                               => '100',
            'lens_type'                        => $this->getAttributeOptionIdByName('lens_type', 'SV'),
            'lens_thickness'                   => $this->getAttributeOptionIdByName('lens_thickness', '1.5'),
            'lens_transitions'                 => $this->getAttributeOptionIdByName('lens_transitions', 'No'),
            'lens_coating'                     => $this->getAttributeOptionIdByName('lens_coating', 'Premium'),
            'lens_fully_rimmed'                => $this->getAttributeOptionIdByName('lens_fully_rimmed', 'No'),
            'lens_rimmless_supra'              => $this->getAttributeOptionIdByName('lens_rimmless_supra', 'No'),
            'lens_designer_frames'             => $this->getAttributeOptionIdByName('lens_designer_frames', 'No'),
            'lens_prescription_sunglasses'     => $this->getAttributeOptionIdByName('lens_prescription_sunglasses', 'No'),
            'lens_wrapped_frame'               => $this->getAttributeOptionIdByName('lens_wrapped_frame', 'No'),
            'lens_oakley_prescription'         => $this->getAttributeOptionIdByName('lens_oakley_prescription', 'No'),
            ),
        array(
            'sku'                               => '15_ec',
            'name'                              => '1.5 ELITE COATINGS',
            'price'                             => '50.00',
            'weight'                            => '4.0000',
            'qty'                               => '100',
            'lens_type'                        => $this->getAttributeOptionIdByName('lens_type', 'SV'),
            'lens_thickness'                   => $this->getAttributeOptionIdByName('lens_thickness', '1.5'),
            'lens_transitions'                 => $this->getAttributeOptionIdByName('lens_transitions', 'No'),
            'lens_coating'                     => $this->getAttributeOptionIdByName('lens_coating', 'Elite'),
            'lens_fully_rimmed'                => $this->getAttributeOptionIdByName('lens_fully_rimmed', 'No'),
            'lens_rimmless_supra'              => $this->getAttributeOptionIdByName('lens_rimmless_supra', 'No'),
            'lens_designer_frames'             => $this->getAttributeOptionIdByName('lens_designer_frames', 'No'),
            'lens_prescription_sunglasses'     => $this->getAttributeOptionIdByName('lens_prescription_sunglasses', 'No'),
            'lens_wrapped_frame'               => $this->getAttributeOptionIdByName('lens_wrapped_frame', 'No'),
            'lens_oakley_prescription'         => $this->getAttributeOptionIdByName('lens_oakley_prescription', 'No'),
            ),
            
                                                // 1.5 photo //
        array(
            'sku'                               => '15_photo_sg',
            'name'                              => '1.5 Photochromic',
            'price'                             => '40.00',
            'weight'                            => '4.0000',
            'qty'                               => '100',
            'lens_type'                        => $this->getAttributeOptionIdByName('lens_type', 'SV'),
            'lens_thickness'                   => $this->getAttributeOptionIdByName('lens_thickness', '1.5'),
            'lens_transitions'                 => $this->getAttributeOptionIdByName('lens_transitions', 'Yes'),
            'lens_coating'                     => $this->getAttributeOptionIdByName('lens_coating', 'Anti-Skratch/Anti-Glare'),
            'lens_fully_rimmed'                => $this->getAttributeOptionIdByName('lens_fully_rimmed', 'No'),
            'lens_rimmless_supra'              => $this->getAttributeOptionIdByName('lens_rimmless_supra', 'No'),
            'lens_designer_frames'             => $this->getAttributeOptionIdByName('lens_designer_frames', 'No'),
            'lens_prescription_sunglasses'     => $this->getAttributeOptionIdByName('lens_prescription_sunglasses', 'No'),
            'lens_wrapped_frame'               => $this->getAttributeOptionIdByName('lens_wrapped_frame', 'No'),
            'lens_oakley_prescription'         => $this->getAttributeOptionIdByName('lens_oakley_prescription', 'No'),
            ),
            
            
                                                // 1.5 trans //
        array(
            'sku'                               => '15_trans_sg',
            'name'                              => '1.5 TRANS SCRATCH/GLARE',
            'price'                             => '45.00',
            'weight'                            => '4.0000',
            'qty'                               => '100',
            'lens_type'                        => $this->getAttributeOptionIdByName('lens_type', 'SV'),
            'lens_thickness'                   => $this->getAttributeOptionIdByName('lens_thickness', '1.5'),
            'lens_transitions'                 => $this->getAttributeOptionIdByName('lens_transitions', 'Yes'),
            'lens_coating'                     => $this->getAttributeOptionIdByName('lens_coating', 'Anti-Skratch/Anti-Glare'),
            'lens_fully_rimmed'                => $this->getAttributeOptionIdByName('lens_fully_rimmed', 'No'),
            'lens_rimmless_supra'              => $this->getAttributeOptionIdByName('lens_rimmless_supra', 'No'),
            'lens_designer_frames'             => $this->getAttributeOptionIdByName('lens_designer_frames', 'No'),
            'lens_prescription_sunglasses'     => $this->getAttributeOptionIdByName('lens_prescription_sunglasses', 'No'),
            'lens_wrapped_frame'               => $this->getAttributeOptionIdByName('lens_wrapped_frame', 'No'),
            'lens_oakley_prescription'         => $this->getAttributeOptionIdByName('lens_oakley_prescription', 'No'),
            ),
        array(
            'sku'                               => '15_trans_pc',
            'name'                              => '1.5 TRANS PREMIUM COATINGS',
            'price'                             => '70.00',
            'weight'                            => '4.0000',
            'qty'                               => '100',
            'lens_type'                        => $this->getAttributeOptionIdByName('lens_type', 'SV'),
            'lens_thickness'                   => $this->getAttributeOptionIdByName('lens_thickness', '1.5'),
            'lens_transitions'                 => $this->getAttributeOptionIdByName('lens_transitions', 'Yes'),
            'lens_coating'                     => $this->getAttributeOptionIdByName('lens_coating', 'Premium'),
            'lens_fully_rimmed'                => $this->getAttributeOptionIdByName('lens_fully_rimmed', 'No'),
            'lens_rimmless_supra'              => $this->getAttributeOptionIdByName('lens_rimmless_supra', 'No'),
            'lens_designer_frames'             => $this->getAttributeOptionIdByName('lens_designer_frames', 'No'),
            'lens_prescription_sunglasses'     => $this->getAttributeOptionIdByName('lens_prescription_sunglasses', 'No'),
            'lens_wrapped_frame'               => $this->getAttributeOptionIdByName('lens_wrapped_frame', 'No'),
            'lens_oakley_prescription'         => $this->getAttributeOptionIdByName('lens_oakley_prescription', 'No'),
            ),
        array(
            'sku'                               => '15_trans_ec',
            'name'                              => '1.5 TRANS ELITE COATINGS',
            'price'                             => '95.00',
            'weight'                            => '4.0000',
            'qty'                               => '100',
            'lens_type'                        => $this->getAttributeOptionIdByName('lens_type', 'SV'),
            'lens_thickness'                   => $this->getAttributeOptionIdByName('lens_thickness', '1.5'),
            'lens_transitions'                 => $this->getAttributeOptionIdByName('lens_transitions', 'Yes'),
            'lens_coating'                     => $this->getAttributeOptionIdByName('lens_coating', 'Elite'),
            'lens_fully_rimmed'                => $this->getAttributeOptionIdByName('lens_fully_rimmed', 'No'),
            'lens_rimmless_supra'              => $this->getAttributeOptionIdByName('lens_rimmless_supra', 'No'),
            'lens_designer_frames'             => $this->getAttributeOptionIdByName('lens_designer_frames', 'No'),
            'lens_prescription_sunglasses'     => $this->getAttributeOptionIdByName('lens_prescription_sunglasses', 'No'),
            'lens_wrapped_frame'               => $this->getAttributeOptionIdByName('lens_wrapped_frame', 'No'),
            'lens_oakley_prescription'         => $this->getAttributeOptionIdByName('lens_oakley_prescription', 'No'),
            ),
            
            
                                                // 1.6 //
        array(
            'sku'                               => '16_sg',
            'name'                              => '1.6 SCRATCH/GLARE',
            'price'                             => '39.00',
            'weight'                            => '4.0000',
            'qty'                               => '100',
            'lens_thickness'                   => $this->getAttributeOptionIdByName('lens_thickness', '1.6'),
            'lens_coating'                     => $this->getAttributeOptionIdByName('lens_coating', 'Elite'),
            'lens_transitions'                 => $this->getAttributeOptionIdByName('lens_transitions', 'No'),
            'lens_rimmless_supra'              => $this->getAttributeOptionIdByName('lens_rimmless_supra', 'Yes'),
            'lens_fully_rimmed'                => $this->getAttributeOptionIdByName('lens_fully_rimmed', 'No'),
            'lens_designer_frames'             => $this->getAttributeOptionIdByName('lens_designer_frames', 'No'),
            'lens_prescription_sunglasses'     => $this->getAttributeOptionIdByName('lens_prescription_sunglasses', 'No'),
            'lens_wrapped_frame'               => $this->getAttributeOptionIdByName('lens_wrapped_frame', 'No'),
            'lens_oakley_prescription'         => $this->getAttributeOptionIdByName('lens_oakley_prescription', 'No'),
            'lens_type'                        => $this->getAttributeOptionIdByName('lens_type', 'SV'),
            ),
        array(
            'sku'                               => '16_pc',
            'name'                              => '1.6 PREMIUM COATINGS',
            'price'                             => '64.00',
            'weight'                            => '4.0000',
            'qty'                               => '100',
            'lens_coating'                     => $this->getAttributeOptionIdByName('lens_coating', 'Premium'),
            'lens_thickness'                   => $this->getAttributeOptionIdByName('lens_thickness', '1.6'),
            'lens_transitions'                 => $this->getAttributeOptionIdByName('lens_transitions', 'No'),
            'lens_rimmless_supra'              => $this->getAttributeOptionIdByName('lens_rimmless_supra', 'Yes'),
            'lens_fully_rimmed'                => $this->getAttributeOptionIdByName('lens_fully_rimmed', 'No'),
            'lens_designer_frames'             => $this->getAttributeOptionIdByName('lens_designer_frames', 'No'),
            'lens_prescription_sunglasses'     => $this->getAttributeOptionIdByName('lens_prescription_sunglasses', 'No'),
            'lens_wrapped_frame'               => $this->getAttributeOptionIdByName('lens_wrapped_frame', 'No'),
            'lens_oakley_prescription'         => $this->getAttributeOptionIdByName('lens_oakley_prescription', 'No'),
            'lens_type'                        => $this->getAttributeOptionIdByName('lens_type', 'SV'),
            ),
        array(
            'sku'                               => '16_ec',
            'name'                              => '1.6 ELITE COATINGS',
            'price'                             => '89.00',
            'weight'                            => '4.0000',
            'qty'                               => '100',
            'lens_coating'                     => $this->getAttributeOptionIdByName('lens_coating', 'Elite'),
            'lens_thickness'                   => $this->getAttributeOptionIdByName('lens_thickness', '1.6'),
            'lens_transitions'                 => $this->getAttributeOptionIdByName('lens_transitions', 'No'),
            'lens_rimmless_supra'              => $this->getAttributeOptionIdByName('lens_rimmless_supra', 'Yes'),
            'lens_fully_rimmed'                => $this->getAttributeOptionIdByName('lens_fully_rimmed', 'No'),
            'lens_designer_frames'             => $this->getAttributeOptionIdByName('lens_designer_frames', 'No'),
            'lens_prescription_sunglasses'     => $this->getAttributeOptionIdByName('lens_prescription_sunglasses', 'No'),
            'lens_wrapped_frame'               => $this->getAttributeOptionIdByName('lens_wrapped_frame', 'No'),
            'lens_oakley_prescription'         => $this->getAttributeOptionIdByName('lens_oakley_prescription', 'No'),
            'lens_type'                        => $this->getAttributeOptionIdByName('lens_type', 'SV'),
            ),
        array(
            'sku'                               => '16_ecc',
            'name'                              => '1.6 ELITE COMPUTER COATING',
            'price'                             => '114.00',
            'weight'                            => '4.0000',
            'qty'                               => '100',
            'lens_coating'                     => $this->getAttributeOptionIdByName('lens_coating', 'Elite computer coatings'),
            'lens_thickness'                   => $this->getAttributeOptionIdByName('lens_thickness', '1.6'),
            'lens_transitions'                 => $this->getAttributeOptionIdByName('lens_transitions', 'No'),
            'lens_rimmless_supra'              => $this->getAttributeOptionIdByName('lens_rimmless_supra', 'Yes'),
            'lens_fully_rimmed'                => $this->getAttributeOptionIdByName('lens_fully_rimmed', 'No'),
            'lens_designer_frames'             => $this->getAttributeOptionIdByName('lens_designer_frames', 'No'),
            'lens_prescription_sunglasses'     => $this->getAttributeOptionIdByName('lens_prescription_sunglasses', 'No'),
            'lens_wrapped_frame'               => $this->getAttributeOptionIdByName('lens_wrapped_frame', 'No'),
            'lens_oakley_prescription'         => $this->getAttributeOptionIdByName('lens_oakley_prescription', 'No'),
            'lens_type'                        => $this->getAttributeOptionIdByName('lens_type', 'SV'),
            ),
            
            
            
                                                // 1.6 trans //
        array(
            'sku'                               => '16_trans_sg',
            'name'                              => '1.6 SCRATCH/GLARE',
            'price'                             => '84.00',
            'weight'                            => '4.0000',
            'qty'                               => '100',
            'lens_coating'                     => $this->getAttributeOptionIdByName('lens_coating', 'Anti-Scratch/Anti-Glare'),
            'lens_transitions'                 => $this->getAttributeOptionIdByName('lens_transitions', 'Yes'),
            'lens_thickness'                   => $this->getAttributeOptionIdByName('lens_thickness', '1.6'),
            'lens_rimmless_supra'              => $this->getAttributeOptionIdByName('lens_rimmless_supra', 'Yes'),
            'lens_fully_rimmed'                => $this->getAttributeOptionIdByName('lens_fully_rimmed', 'No'),
            'lens_designer_frames'             => $this->getAttributeOptionIdByName('lens_designer_frames', 'No'),
            'lens_prescription_sunglasses'     => $this->getAttributeOptionIdByName('lens_prescription_sunglasses', 'No'),
            'lens_wrapped_frame'               => $this->getAttributeOptionIdByName('lens_wrapped_frame', 'No'),
            'lens_oakley_prescription'         => $this->getAttributeOptionIdByName('lens_oakley_prescription', 'No'),
            'lens_type'                        => $this->getAttributeOptionIdByName('lens_type', 'SV'),
            ),
        array(
            'sku'                               => '16_trans_pc',
            'name'                              => '1.6 TRANS PREMIUM COATINGS',
            'price'                             => '109.00',
            'weight'                            => '4.0000',
            'qty'                               => '100',
            'lens_coating'                     => $this->getAttributeOptionIdByName('lens_coating', 'Premium'),
            'lens_thickness'                   => $this->getAttributeOptionIdByName('lens_thickness', '1.6'),
            'lens_transitions'                 => $this->getAttributeOptionIdByName('lens_transitions', 'Yes'),
            'lens_rimmless_supra'              => $this->getAttributeOptionIdByName('lens_rimmless_supra', 'Yes'),
            'lens_fully_rimmed'                => $this->getAttributeOptionIdByName('lens_fully_rimmed', 'No'),
            'lens_designer_frames'             => $this->getAttributeOptionIdByName('lens_designer_frames', 'No'),
            'lens_prescription_sunglasses'     => $this->getAttributeOptionIdByName('lens_prescription_sunglasses', 'No'),
            'lens_wrapped_frame'               => $this->getAttributeOptionIdByName('lens_wrapped_frame', 'No'),
            'lens_oakley_prescription'         => $this->getAttributeOptionIdByName('lens_oakley_prescription', 'No'),
            'lens_type'                        => $this->getAttributeOptionIdByName('lens_type', 'SV'),
            ),
        array(
            'sku'                               => '16_trans_ec',
            'name'                              => '1.6 TRANS ELITE COMPUTER COATING',
            'price'                             => '134.00',
            'weight'                            => '4.0000',
            'qty'                               => '100',
            'lens_coating'                     => $this->getAttributeOptionIdByName('lens_coating', 'Elite computer coatings'),
            'lens_thickness'                   => $this->getAttributeOptionIdByName('lens_thickness', '1.6'),
            'lens_transitions'                 => $this->getAttributeOptionIdByName('lens_transitions', 'No'),
            'lens_rimmless_supra'              => $this->getAttributeOptionIdByName('lens_rimmless_supra', 'Yes'),
            'lens_fully_rimmed'                => $this->getAttributeOptionIdByName('lens_fully_rimmed', 'No'),
            'lens_designer_frames'             => $this->getAttributeOptionIdByName('lens_designer_frames', 'No'),
            'lens_prescription_sunglasses'     => $this->getAttributeOptionIdByName('lens_prescription_sunglasses', 'No'),
            'lens_wrapped_frame'               => $this->getAttributeOptionIdByName('lens_wrapped_frame', 'No'),
            'lens_oakley_prescription'         => $this->getAttributeOptionIdByName('lens_oakley_prescription', 'No'),
            'lens_type'                        => $this->getAttributeOptionIdByName('lens_type', 'SV'),
            ),
            
            
            
                                                // 1.67 //
        array(
            'sku'                               => '167_sg',
            'name'                              => '1.67 SCRATCH/GLARE',
            'price'                             => '59.00',
            'weight'                            => '4.0000',
            'qty'                               => '100',
            'lens_coating'                     => $this->getAttributeOptionIdByName('lens_coating', 'Anti-Scratch/Anti-Glare'),
            'lens_transitions'                 => $this->getAttributeOptionIdByName('lens_transitions', 'No'),
            'lens_thickness'                   => $this->getAttributeOptionIdByName('lens_thickness', '1.67'),
            'lens_rimmless_supra'              => $this->getAttributeOptionIdByName('lens_rimmless_supra', 'Yes'),
            'lens_fully_rimmed'                => $this->getAttributeOptionIdByName('lens_fully_rimmed', 'No'),
            'lens_designer_frames'             => $this->getAttributeOptionIdByName('lens_designer_frames', 'No'),
            'lens_prescription_sunglasses'     => $this->getAttributeOptionIdByName('lens_prescription_sunglasses', 'No'),
            'lens_wrapped_frame'               => $this->getAttributeOptionIdByName('lens_wrapped_frame', 'No'),
            'lens_oakley_prescription'         => $this->getAttributeOptionIdByName('lens_oakley_prescription', 'No'),
            'lens_type'                        => $this->getAttributeOptionIdByName('lens_type', 'SV'),
            ),
        array(
            'sku'                               => '167_pc',
            'name'                              => '1.67 TRANS PREMIUM COATINGS',
            'price'                             => '84.00',
            'weight'                            => '4.0000',
            'qty'                               => '100',
            'lens_coating'                     => $this->getAttributeOptionIdByName('lens_coating', 'Premium'),
            'lens_thickness'                   => $this->getAttributeOptionIdByName('lens_thickness', '1.67'),
            'lens_transitions'                 => $this->getAttributeOptionIdByName('lens_transitions', 'No'),
            'lens_rimmless_supra'              => $this->getAttributeOptionIdByName('lens_rimmless_supra', 'Yes'),
            'lens_fully_rimmed'                => $this->getAttributeOptionIdByName('lens_fully_rimmed', 'No'),
            'lens_designer_frames'             => $this->getAttributeOptionIdByName('lens_designer_frames', 'No'),
            'lens_prescription_sunglasses'     => $this->getAttributeOptionIdByName('lens_prescription_sunglasses', 'No'),
            'lens_wrapped_frame'               => $this->getAttributeOptionIdByName('lens_wrapped_frame', 'No'),
            'lens_oakley_prescription'         => $this->getAttributeOptionIdByName('lens_oakley_prescription', 'No'),
            'lens_type'                        => $this->getAttributeOptionIdByName('lens_type', 'SV'),
            ),
        array(
            'sku'                               => '167_ec',
            'name'                              => '1.67 ELITE COATING',
            'price'                             => '109.00',
            'weight'                            => '4.0000',
            'qty'                               => '100',
            'lens_coating'                     => $this->getAttributeOptionIdByName('lens_coating', 'Elite'),
            'lens_thickness'                   => $this->getAttributeOptionIdByName('lens_thickness', '1.67'),
            'lens_transitions'                 => $this->getAttributeOptionIdByName('lens_transitions', 'No'),
            'lens_rimmless_supra'              => $this->getAttributeOptionIdByName('lens_rimmless_supra', 'Yes'),
            'lens_fully_rimmed'                => $this->getAttributeOptionIdByName('lens_fully_rimmed', 'No'),
            'lens_designer_frames'             => $this->getAttributeOptionIdByName('lens_designer_frames', 'No'),
            'lens_prescription_sunglasses'     => $this->getAttributeOptionIdByName('lens_prescription_sunglasses', 'No'),
            'lens_wrapped_frame'               => $this->getAttributeOptionIdByName('lens_wrapped_frame', 'No'),
            'lens_oakley_prescription'         => $this->getAttributeOptionIdByName('lens_oakley_prescription', 'No'),
            'lens_type'                        => $this->getAttributeOptionIdByName('lens_type', 'SV'),
            ),
        array(
            'sku'                               => '167_ecc',
            'name'                              => '1.67 ELITE COMPUTER COATING',
            'price'                             => '134.00',
            'weight'                            => '4.0000',
            'qty'                               => '100',
            'lens_coating'                     => $this->getAttributeOptionIdByName('lens_coating', 'Elite computer coatings'),
            'lens_thickness'                   => $this->getAttributeOptionIdByName('lens_thickness', '1.67'),
            'lens_transitions'                 => $this->getAttributeOptionIdByName('lens_transitions', 'No'),
            'lens_rimmless_supra'              => $this->getAttributeOptionIdByName('lens_rimmless_supra', 'Yes'),
            'lens_fully_rimmed'                => $this->getAttributeOptionIdByName('lens_fully_rimmed', 'No'),
            'lens_designer_frames'             => $this->getAttributeOptionIdByName('lens_designer_frames', 'No'),
            'lens_prescription_sunglasses'     => $this->getAttributeOptionIdByName('lens_prescription_sunglasses', 'No'),
            'lens_wrapped_frame'               => $this->getAttributeOptionIdByName('lens_wrapped_frame', 'No'),
            'lens_oakley_prescription'         => $this->getAttributeOptionIdByName('lens_oakley_prescription', 'No'),
            'lens_type'                        => $this->getAttributeOptionIdByName('lens_type', 'SV'),
            ),
            
            
            
                                                // 1.67 TRANS //
        array(
            'sku'                               => '167_trans_sg',
            'name'                              => '1.67 SCRATCH/GLARE',
            'price'                             => '104.00',
            'weight'                            => '4.0000',
            'qty'                               => '100',
            'lens_coating'                     => $this->getAttributeOptionIdByName('lens_coating', 'Anti-Scratch/Anti-Glare'),
            'lens_transitions'                 => $this->getAttributeOptionIdByName('lens_transitions', 'Yes'),
            'lens_thickness'                   => $this->getAttributeOptionIdByName('lens_thickness', '1.67'),
            'lens_rimmless_supra'              => $this->getAttributeOptionIdByName('lens_rimmless_supra', 'Yes'),
            'lens_fully_rimmed'                => $this->getAttributeOptionIdByName('lens_fully_rimmed', 'No'),
            'lens_designer_frames'             => $this->getAttributeOptionIdByName('lens_designer_frames', 'No'),
            'lens_prescription_sunglasses'     => $this->getAttributeOptionIdByName('lens_prescription_sunglasses', 'No'),
            'lens_wrapped_frame'               => $this->getAttributeOptionIdByName('lens_wrapped_frame', 'No'),
            'lens_oakley_prescription'         => $this->getAttributeOptionIdByName('lens_oakley_prescription', 'No'),
            'lens_type'                        => $this->getAttributeOptionIdByName('lens_type', 'SV'),
            ),
        array(
            'sku'                               => '167_trans_pc',
            'name'                              => '1.67 TRANS PREMIUM COATINGS',
            'price'                             => '129.00',
            'weight'                            => '4.0000',
            'qty'                               => '100',
            'lens_coating'                     => $this->getAttributeOptionIdByName('lens_coating', 'Premium'),
            'lens_thickness'                   => $this->getAttributeOptionIdByName('lens_thickness', '1.67'),
            'lens_transitions'                 => $this->getAttributeOptionIdByName('lens_transitions', 'Yes'),
            'lens_rimmless_supra'              => $this->getAttributeOptionIdByName('lens_rimmless_supra', 'Yes'),
            'lens_fully_rimmed'                => $this->getAttributeOptionIdByName('lens_fully_rimmed', 'No'),
            'lens_designer_frames'             => $this->getAttributeOptionIdByName('lens_designer_frames', 'No'),
            'lens_prescription_sunglasses'     => $this->getAttributeOptionIdByName('lens_prescription_sunglasses', 'No'),
            'lens_wrapped_frame'               => $this->getAttributeOptionIdByName('lens_wrapped_frame', 'No'),
            'lens_oakley_prescription'         => $this->getAttributeOptionIdByName('lens_oakley_prescription', 'No'),
            'lens_type'                        => $this->getAttributeOptionIdByName('lens_type', 'SV'),
            ),
        array(
            'sku'                               => '167_trans_ec',
            'name'                              => '1.67 ELITE COMPUTER COATING',
            'price'                             => '154.00',
            'weight'                            => '4.0000',
            'qty'                               => '100',
            'lens_coating'                     => $this->getAttributeOptionIdByName('lens_coating', 'Elite'),
            'lens_thickness'                   => $this->getAttributeOptionIdByName('lens_thickness', '1.67'),
            'lens_transitions'                 => $this->getAttributeOptionIdByName('lens_transitions', 'No'),
            'lens_rimmless_supra'              => $this->getAttributeOptionIdByName('lens_rimmless_supra', 'Yes'),
            'lens_fully_rimmed'                => $this->getAttributeOptionIdByName('lens_fully_rimmed', 'No'),
            'lens_designer_frames'             => $this->getAttributeOptionIdByName('lens_designer_frames', 'No'),
            'lens_prescription_sunglasses'     => $this->getAttributeOptionIdByName('lens_prescription_sunglasses', 'No'),
            'lens_wrapped_frame'               => $this->getAttributeOptionIdByName('lens_wrapped_frame', 'No'),
            'lens_oakley_prescription'         => $this->getAttributeOptionIdByName('lens_oakley_prescription', 'No'),
            'lens_type'                        => $this->getAttributeOptionIdByName('lens_type', 'SV'),
            ),
            
            
                                                // 1.74 //
        array(
            'sku'                               => '174_sg',
            'name'                              => '1.74 SCRATCH/GLARE',
            'price'                             => '99.00',
            'weight'                            => '4.0000',
            'qty'                               => '100',
            'lens_coating'                     => $this->getAttributeOptionIdByName('lens_coating', 'Anti-Scratch/Anti-Glare'),
            'lens_transitions'                 => $this->getAttributeOptionIdByName('lens_transitions', 'No'),
            'lens_thickness'                   => $this->getAttributeOptionIdByName('lens_thickness', '1.74'),
            'lens_rimmless_supra'              => $this->getAttributeOptionIdByName('lens_rimmless_supra', 'Yes'),
            'lens_fully_rimmed'                => $this->getAttributeOptionIdByName('lens_fully_rimmed', 'No'),
            'lens_designer_frames'             => $this->getAttributeOptionIdByName('lens_designer_frames', 'No'),
            'lens_prescription_sunglasses'     => $this->getAttributeOptionIdByName('lens_prescription_sunglasses', 'No'),
            'lens_wrapped_frame'               => $this->getAttributeOptionIdByName('lens_wrapped_frame', 'No'),
            'lens_oakley_prescription'         => $this->getAttributeOptionIdByName('lens_oakley_prescription', 'No'),
            'lens_type'                        => $this->getAttributeOptionIdByName('lens_type', 'SV'),
            ),
        array(
            'sku'                               => '174_pc',
            'name'                              => '1.74 TRANS PREMIUM COATINGS',
            'price'                             => '124.00',
            'weight'                            => '4.0000',
            'qty'                               => '100',
            'lens_coating'                     => $this->getAttributeOptionIdByName('lens_coating', 'Premium'),
            'lens_thickness'                   => $this->getAttributeOptionIdByName('lens_thickness', '1.74'),
            'lens_transitions'                 => $this->getAttributeOptionIdByName('lens_transitions', 'No'),
            'lens_rimmless_supra'              => $this->getAttributeOptionIdByName('lens_rimmless_supra', 'Yes'),
            'lens_fully_rimmed'                => $this->getAttributeOptionIdByName('lens_fully_rimmed', 'No'),
            'lens_designer_frames'             => $this->getAttributeOptionIdByName('lens_designer_frames', 'No'),
            'lens_prescription_sunglasses'     => $this->getAttributeOptionIdByName('lens_prescription_sunglasses', 'No'),
            'lens_wrapped_frame'               => $this->getAttributeOptionIdByName('lens_wrapped_frame', 'No'),
            'lens_oakley_prescription'         => $this->getAttributeOptionIdByName('lens_oakley_prescription', 'No'),
            'lens_type'                        => $this->getAttributeOptionIdByName('lens_type', 'SV'),
            ),
        array(
            'sku'                               => '174_ec',
            'name'                              => '1.74 ELITE COATING',
            'price'                             => '149.00',
            'weight'                            => '4.0000',
            'qty'                               => '100',
            'lens_coating'                     => $this->getAttributeOptionIdByName('lens_coating', 'Elite'),
            'lens_thickness'                   => $this->getAttributeOptionIdByName('lens_thickness', '1.74'),
            'lens_transitions'                 => $this->getAttributeOptionIdByName('lens_transitions', 'No'),
            'lens_rimmless_supra'              => $this->getAttributeOptionIdByName('lens_rimmless_supra', 'Yes'),
            'lens_fully_rimmed'                => $this->getAttributeOptionIdByName('lens_fully_rimmed', 'No'),
            'lens_designer_frames'             => $this->getAttributeOptionIdByName('lens_designer_frames', 'No'),
            'lens_prescription_sunglasses'     => $this->getAttributeOptionIdByName('lens_prescription_sunglasses', 'No'),
            'lens_wrapped_frame'               => $this->getAttributeOptionIdByName('lens_wrapped_frame', 'No'),
            'lens_oakley_prescription'         => $this->getAttributeOptionIdByName('lens_oakley_prescription', 'No'),
            'lens_type'                        => $this->getAttributeOptionIdByName('lens_type', 'SV'),
            ),
        array(
            'sku'                               => '174_ecc',
            'name'                              => '1.74 ELITE COMPUTER COATING',
            'price'                             => '174.00',
            'weight'                            => '4.0000',
            'qty'                               => '100',
            'lens_coating'                     => $this->getAttributeOptionIdByName('lens_coating', 'Elite computer coatings'),
            'lens_thickness'                   => $this->getAttributeOptionIdByName('lens_thickness', '1.74'),
            'lens_transitions'                 => $this->getAttributeOptionIdByName('lens_transitions', 'No'),
            'lens_rimmless_supra'              => $this->getAttributeOptionIdByName('lens_rimmless_supra', 'Yes'),
            'lens_fully_rimmed'                => $this->getAttributeOptionIdByName('lens_fully_rimmed', 'No'),
            'lens_designer_frames'             => $this->getAttributeOptionIdByName('lens_designer_frames', 'No'),
            'lens_prescription_sunglasses'     => $this->getAttributeOptionIdByName('lens_prescription_sunglasses', 'No'),
            'lens_wrapped_frame'               => $this->getAttributeOptionIdByName('lens_wrapped_frame', 'No'),
            'lens_oakley_prescription'         => $this->getAttributeOptionIdByName('lens_oakley_prescription', 'No'),
            'lens_type'                        => $this->getAttributeOptionIdByName('lens_type', 'SV'),
            ),
    );
        
        $this->data = $this->values;
        //foreach($this->values as $val){
            //$this->data[] = array_combine($this->keys, $val);
        //}
    }
    public function _prepareDara(){
        // prepare sphere option
        $sphere_values = array();
        $j=0; 
        for( $i=8; $i <= 8, $i >= -8; $i=$i-0.25, $j++ ){
            $plus =  ( $i > 0 ) ? "+" : null;
            $sphere_values[] = array($plus.number_format($i, 2, '.', ''),'','fixed','',$j,);
        }
        $sphere_values[] = array('Infinity','','fixed','',$j++);
        $sphere_values[] = array('~','','fixed','',$j++);
        $this->values_sphere_left = $this->values_sphere_right = $sphere_values;
        
        // prepare cylinder option
        $values_cylinder = array();
        $j=0; 
        for( $i=8; $i <= 6, $i >= -6; $i=$i-0.25, $j++ ){
            $plus =  ( $i > 0 ) ? "+" : null;
            $values_cylinder[] = ( abs($i) > 2 ) ? array($plus.number_format($i, 2, '.', ''). "+$15",'','fixed','',$j,) : array($plus.number_format($i, 2, '.', ''),'','fixed','',$j,);
        }
        $values_cylinder[] = array('None','','fixed','',$j++);
        $values_cylinder[] = array('DS','','fixed','',$j++);
        $values_cylinder[] = array('Plano','','fixed','',$j++);
        $values_cylinder[] = array('Infinity','','fixed','',$j++);
        $values_cylinder[] = array('~','','fixed','',$j++);
        $this->values_cylinder_left = $this->values_cylinder_right = $values_cylinder;
        
        // prepare axis option
        $values_axis = array();
        for( $i=0; $i <= 180; $i++){
            $values_axis[] = array($i,'','fixed','',$i);
        }
        $this->values_axis_left = $this->values_axis_right = $values_axis;
        
        // prepare near/add option
        $values_nearadd = array();
        $j=0; 
        for( $i=0; $i <= 4; $i=$i+0.25, $j++ ){
            $values_nearadd[] = array("+".number_format($i, 2, '.', ''),'','fixed','',$j);
        }
        $this->values_nearadd_left = $this->values_nearadd_right = $values_nearadd;
        
        // prepare near/add option
        $values_pd = array();
        for( $i=52; $i>=52, $i <= 75; $i++){
            $values_pd[] =  array($i,'','fixed','',$i);
        }
        $this->values_pd = $values_pd;
    }
    
    public function prepare($header, $values){
        //$sku_arr = array($this->values[0][0], $this->values[1][0], $this->values[2][0]);
        $sku_arr = null;
        foreach($this->values as $prod){
            $sku_arr[] = $prod['sku'];
        }
        foreach($sku_arr as $sku){
            $options = array();
            $options[$sku] = array_combine($this->option_header_keys, $header);
            foreach($values as $val){
                $options[$sku]['values'][] = array_combine($this->option_body_keys, $val);
            }
            
            foreach($options as $sku => $option) {
                $id = Mage::getModel('catalog/product')->getIdBySku($sku);
                $product = Mage::getModel('catalog/product')->load($id);
             
                if(!$product->getOptionsReadonly()) {
                    $product->setProductOptions(array($option));
                    $product->setCanSaveCustomOptions(true);
                    try{ $product->save(); }catch(Exception $e){ echo $e->getMessage(); }
                }
                Mage::getSingleton('catalog/product_option')->unsetOptions();
            }
        }
    }

    public function deleteOptions(){
        $sku_arr = null;
        foreach($this->values as $prod){
            $sku_arr[] = $prod['sku'];
        }
        foreach($sku_arr as $sku){
            $id = Mage::getModel('catalog/product')->getIdBySku($sku);
            $product = Mage::getModel('catalog/product')->load($id);
            $items = $product->getProductOptionsCollection()->getItems();
            foreach($items as $item){
                //mage::d($item->getData());
                $item->delete();
            }
        }
    }





    public function addProduct($data){
        $product = new Mage_Catalog_Model_Product();

        // Build the product
        $setId = Mage::getResourceModel('eav/entity_attribute_set_collection')->setEntityTypeFilter($this->typeId)->addFilter('attribute_set_name', $this->attributeSetName)->getFirstItem()->getId(); // firstItem becouse of filter ($this->typeId) and filter ('attribute_set_name') design single attribute set.
        
        $product->setSku($data['sku']);
        $product->setAttributeSetId( $setId );# 9 is for default
        $product->setTypeId('simple');
        $product->setName($data['name']);
        $product->setWebsiteIDs(array(1)); //only array!!!!!  # Website id, 1 is default
        $product->setStoreIDs(array(0,1));
        $product->setDescription($data['description']);
        $product->setShortDescription($data['short_description']);
        $product->setPrice($data['price']); # Set some price
        $product->setWeight($data['weight']);
        $product->setStatus( Mage_Catalog_Model_Product_Status::STATUS_ENABLED );
        $product->setVisibility( Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH );
        $product->setTaxClassId(0); # default tax class
        if( $catId = $this->getCategoryIdByName($this->categoryName) ){
            $product->setCategoryIds(array($catId)); # some cat id's,
        }
        $product->setStockData(array(
            'is_in_stock' => 1,
            'qty' => $data['qty']
        ));

        $product->setCreatedAt(strtotime('now'));

        //set lens attributes
            $product->setData('lens_thickness', $data['lens_thickness']);
            $product->setData('lens_coating', $data['lens_coating']);
            $product->setData('lens_transitions', $data['lens_transitions']);
            $product->setData('lens_fully_rimmed', $data['lens_fully_rimmed']);
            $product->setData('lens_rimmless_supra', $data['lens_rimmless_supra']);
            $product->setData('lens_designer_frames', $data['lens_designer_frames']);
            $product->setData('lens_prescription_sunglasses', $data['lens_prescription_sunglasses']);
            $product->setData('lens_wrapped_frame', $data['lens_wrapped_frame']);
            $product->setData('lens_oakley_prescription', $data['lens_oakley_prescription']);
            $product->setData('lens_type', $data['lens_type']);
        try {
            $product->save();
            echo "product created. ID: {$product->getId()} \n";
        }
        catch (Exception $ex) {
            zend_debug::dump($ex->getMessage());
        }
    }
    
    public function addLenses(){
        //mage::d($this->data); die;
        foreach($this->data as $d){
            mage::d($d);
            $this->addProduct($d);
        }
    }
    
    
    public function addOptions(){
        foreach($this->data as $d){
            mage::D($d['sku']);
        }
    }
    
    public function createCategory(){
        $catId = $this->getCategoryIdByName($this->categoryName);
        if ($catId) return $catId;
        
        $parentId = Mage::app()->getStore( $this->storeId )->getRootCategoryId();

        $category = new Mage_Catalog_Model_Category();
        $category->setName($this->categoryName);
        $category->setUrlKey('contact-lenses');
        $category->setIsActive(1);
        $category->setDisplayMode('PRODUCTS');
        $category->setIsAnchor(0);
         
        $parentCategory = Mage::getModel('catalog/category')->load($parentId);
        $category->setPath($parentCategory->getPath());               
         
        $category->save();
        //unset($category);
        return $category->getId();
    }
    
    public function getCategoryIdByName($name){
        $parentId = $catId = null;
        $collection = Mage::getModel('catalog/category')->getCollection()
            ->setStoreId('0')
            ->addAttributeToSelect('name'); //->addAttributeToSelect('is_active');
        foreach ($collection as $cat) {
            if ($cat->getName() == $name) {
                $catId = $cat->getId();
                break;
            }
        }
        return $catId;
    }
    
    public function addProductsToCategory( $catId ){
        $sku_arr = null;
        foreach($this->values as $prod){
            $sku_arr[] = $prod['sku'];
        }
        //$sku_arr = array($this->values[][0], $this->values[1][0], $this->values[2][0]);
        foreach($sku_arr as $sku){
            $id = Mage::getModel('catalog/product')->getCollection()->getItemByColumnValue('sku', $sku)->getId();
            Mage::getModel('catalog/category_api')->assignProduct($catId, $id);
        }
    }
    
    public function setRelatedProducts(){
        echo date("\nY-d-m H:i:s")." - Adding related products\n";
        $sku_arr = array();
        foreach($this->values as $lenses){
            $sku_arr[] = $lenses['sku'];
        }
        $reltedIds = null;
        foreach($sku_arr as $sku){
            $relatedIds[] =  Mage::getModel('catalog/product')->getCollection()->getItemByColumnValue('sku', $sku)->getId();
        }
        
        $allProducts = Mage::getModel('catalog/product')->getCollection()->load()->getAllIds();                 #$products = Mage::getModel('catalog/category_api')->assignedProducts($this->getCategoryIdByName($this->categoryName));
        $products = array_diff($allProducts, $relatedIds);                             // eject lens ids from array of all products ids.

        foreach($products as $productId){
            $this->setRelatedProduct($productId, $relatedIds);
        }
        echo date("\nY-d-m H:i:s")." - Related products added\n";
    }
    
    public function setRelatedProduct($prodId, $relatedIds){
        if( is_integer($relatedIds) ) $relatedIds = array($relatedIds);
        $product = Mage::getModel('catalog/product')->load($prodId);
        $param = array(); $i=0;
        foreach($relatedIds as $relId){
            $param[$relId] = array('position' => $i++);
        }
        $product->setRelatedLinkData($param);
        $product->save();
    }
    
    
    
    public function addImages(){
        foreach($this->getLensIds() as $id){
            $product = Mage::getModel('catalog/product')->load($id);
            
            //delete old images
            //if ($product->getId()){
                //$mediaApi = Mage::getModel("catalog/product_attribute_media_api");
                //$items = $mediaApi->items($product->getId());
                //foreach($items as $item)
                    //$mediaApi->remove($product->getId(), $item['file']);
            //}
            
            $this->addImagesToProduct($product);
            unset($product);
        }
    }
    
    public function addImagesToProduct($product){
            // Add three image sizes to media gallery
            $putPathHere = 'thickness_standard.jpg';

            $product->setMediaGallery (array('images'=>array (), 'values'=>array ()));
            
            // Remove unset images, add image to gallery if exists
            $importDir = Mage::getBaseDir('media') . DS . 'import/';

            $filePath = $importDir.$putPathHere;
            if ( file_exists($filePath) ) {
                try {
                    $product->addImageToMediaGallery($filePath,  array ('image','small_image','thumbnail'), false, false);
                    $product->save();
                } catch (Exception $e) { echo $e->getMessage(); }
            } else { echo "Product does not have an image or the path is incorrect. Path was: {$filePath}<br/>"; }
    }
    public function getLensIds(){
        $items = Mage::getModel('catalog/product')->getCollection()->addAttributeToFilter('sku', array('bifocal','varifocal', 'single_vision'));
        $arrIds = null;
        foreach($items as $item){
            $arrIds[] = $item->getId();
        }
        return $arrIds;
    }
    
    
    public function getAttributeOptionIdByName($attribute_code, $attribute_value_name){
        $attribute = Mage::getModel('eav/config')->getAttribute('catalog_product', $attribute_code);
        $array = array();
            foreach ($attribute->getSource()->getAllOptions(false) as $option){
                if($option['label'] == $attribute_value_name){
                    return $option['value'];
            }
        }
    }
    
}
echo date("\nY-d-m H:i:s")." - LENS START\n";
// # Mage::getModel('catalog/product')->getCollection()->delete();
$l = new Lenses();
$catId = $l->createCategory();
//$l->addImages();
$l->deleteOptions();
//die;
//$l->addLenses();

    //add options 
    //$l->prepare($l->header_lens_thickness, $l->values_lens_thickness);
    //$l->prepare($l->header_lens_varifocal_type, $l->values_lens_varifocal_type);
    //$l->prepare($l->header_lens_coating, $l->values_lens_coating);

    //add prescripted options 
    $l->prepare($l->header_sphere_left, $l->values_sphere_left);
    $l->prepare($l->header_cylinder_left, $l->values_cylinder_left);
    $l->prepare($l->header_axis_left, $l->values_axis_left);
    $l->prepare($l->header_nearadd_left, $l->values_nearadd_left);
    $l->prepare($l->header_sphere_right, $l->values_sphere_right);
    $l->prepare($l->header_cylinder_right, $l->values_cylinder_right);
    $l->prepare($l->header_axis_right, $l->values_axis_right);
    $l->prepare($l->header_nearadd_right, $l->values_nearadd_right);
    $l->prepare($l->header_pd, $l->values_pd);

//$l->addProductsToCategory($catId);

//$l->setRelatedProducts();



#Mage::getModel('catalog/product')->load( Mage::getModel('catalog/product')->getIdBySku('15_scratch_glare') )->delete();
#array('+8.00','','fixed','','0',),array('+7.75','','fixed','','0',),array('+7.50','','fixed','','0',),array('+7.25','','fixed','','0',),array('+7.00','','fixed','','0',),array('+6.75','','fixed','','0',),array('+6.50','','fixed','','0',),array('+6.25','','fixed','','0',),array('+6.00','','fixed','','0',),array('+5.75','','fixed','','0',),array('+5.50','','fixed','','0',),array('+5.25','','fixed','','0',),array('+5.00','','fixed','','0',),array('+4.75','','fixed','','0',),array('+4.50','','fixed','','0',),array('+4.25','','fixed','','0',),array('+4.00','','fixed','','0',),array('+3.75','','fixed','','0',),array('+3.50','','fixed','','0',),array('+3.25','','fixed','','0',),array('+3.00','','fixed','','0',),array('+2.75','','fixed','','0',),array('+2.50','','fixed','','0',),array('+2.25','','fixed','','0',),array('+2.00','','fixed','','0',),array('+1.75','','fixed','','0',),array('+1.50','','fixed','','0',),array('+1.25','','fixed','','0',),array('+1.00','','fixed','','0',),array('+0.75','','fixed','','0',),array('+0.50','','fixed','','0',),array('+0.25','','fixed','','0',),array('0.00','','fixed','','0',),array('Plano','','fixed','','0',),array('Infinity','','fixed','','0',),array('~','','fixed','','0',),array('-0.25','','fixed','','0',),array('-0.50','','fixed','','0',),array('-0.75','','fixed','','0',),array('-1.00','','fixed','','0',),array('-1.25','','fixed','','0',),array('-1.50','','fixed','','0',),array('-1.75','','fixed','','0',),array('-2.00','','fixed','','0',),array('-2.25','','fixed','','0',),array('-2.50','','fixed','','0',),array('-2.75','','fixed','','0',),array('-3.00','','fixed','','0',),array('-3.25','','fixed','','0',),array('-3.50','','fixed','','0',),array('-3.75','','fixed','','0',),array('-4.00','','fixed','','0',),array('-4.25','','fixed','','0',),array('-4.50','','fixed','','0',),array('-4.75','','fixed','','0',),array('-5.00','','fixed','','0',),array('-5.25','','fixed','','0',),array('-5.50','','fixed','','0',),array('-5.75','','fixed','','0',),array('-6.00','','fixed','','0',),array('-6.25','','fixed','','0',),array('-6.50','','fixed','','0',),array('-6.75','','fixed','','0',),array('-7.00','','fixed','','0',),array('-7.25','','fixed','','0',),array('-7.50','','fixed','','0',),array('-7.75','','fixed','','0',),array('-8.00','','fixed','','0',),