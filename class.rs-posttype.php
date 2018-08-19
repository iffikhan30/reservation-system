<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Rs_Posttype{

    public function __construct($posttype, $posttypename, $posttypesingular){
        $this->post_type            =   strtolower(str_replace(' ', '_', $posttype));
        $this->post_type_name       =   $posttypename;
        $this->post_type_singular   =   $posttypesingular;

        if (!post_type_exists($this->post_type)) {
            add_action('init', array(&$this, 'custom_post_type'));
        }
    }

    public function custom_post_type()
    {
        register_post_type($this->post_type,
            array(
                'labels'      => array(
                    'name'          => __($this->post_type_name),
                    'singular_name' => __($this->post_type_singular),
                ),
                'public'      => true,
                'has_archive' => true,
                'supports' => array('title','editor','custom-fields'),
            )
        );
    }

}