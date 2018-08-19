<?php
/*
Plugin Name: Reservation System
Plugin URI: http://xtremesolx.com/
Description: Used this plugin for you wordpress its a great support to your post for reservation system. Activate this plugin then go to setting page.
Version: 1.0
Author: Irfan
Author URI: http://xtremesolx.com/iffi
License: GPLv2 or later
Text Domain: xtremesolx
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2018-2018 Xtremesolx, Inc.
*/
if ( ! defined( 'ABSPATH' ) ) exit;

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

define( 'RESERVATIONSYSTEM_VERSION', '1.0.0' );
define( 'RESERVATIONSYSTEM__MINIMUM_WP_VERSION', '4.0' );
define( 'RESERVATIONSYSTEM__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

register_activation_hook( __FILE__, array( 'Reservationsystem', 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( 'Reservationsystem', 'plugin_deactivation' ) );
global $post;


/*Reservation post type*/
require_once( RESERVATIONSYSTEM__PLUGIN_DIR . 'class.rs-posttype.php' );
new Rs_Posttype('Reservation','Reservations','Reservation');


/*Form Shortcode*/
require_once( RESERVATIONSYSTEM__PLUGIN_DIR . 'class.rs-formshortcode.php' );
new Rs_Formshortcode();


/*Add Scripts and Styles*/
function add_my_script() {
    /*Use wordpress ui core*/
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_style('jquery-ui-css',plugin_dir_url( __FILE__ ) .'css/jquery-ui.css',false,"",false);
    /*Add Simple calendar Jquery*/
    wp_enqueue_style( 'style-simplecalendar', plugin_dir_url( __FILE__ ) . 'css/style.css');
    wp_enqueue_script( 'jquery-simplecalendar', plugin_dir_url( __FILE__ ) . 'js/simplecalendar.js',false,'' ,false);
    /*Event form ajax call*/
    wp_register_script('ajax-form-script', plugin_dir_url( __FILE__ ) . 'js/ajax-form-script.js', array('jquery'),'1.0.0', true );
    wp_enqueue_script('ajax-form-script');
    wp_localize_script( 'ajax-form-script', 'ajax_form_object', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'redirecturl' => home_url(),
            'loadingmessage' => __('Sending user info, please wait...')
        ));
}
add_action('wp_enqueue_scripts', 'add_my_script');

add_action('wp_ajax_reservation_system_insert_form', 'reservation_system_insert_form');
add_action('wp_ajax_nopriv_reservation_system_insert_form', 'reservation_system_insert_form');

function reservation_system_insert_form(){
    if(isset($_POST['r_security_nonce'])) {
        $nonce = sanitize_text_field($_POST['r_security_nonce']);

        if (!wp_verify_nonce($nonce, 'r_security_nonce')) {
            echo '3';
        }
        $check_query = new WP_Query(array('post_type' => 'reservation', 'post_status' => 'draft',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'reservation_postid', // name of custom field
                    'value' => sanitize_text_field($_POST['postid']),
                    'compare' => '='
                ),
                array(
                    'key' => 'reservation_date', // name of custom field
                    'value' => sanitize_text_field($_POST['date']),
                    'compare' => '='
                )
            )));
        if ($check_query->have_posts()) :

            while ($check_query->have_posts()) : $check_query->the_post();
                echo '2';
            endwhile;
            wp_reset_query();

        else :

            $my_post = array(
                'post_title' => sanitize_text_field($_POST['eventname']),
                'post_status' => 'draft',
                'post_type' => 'reservation',
                'post_content' => sanitize_textarea_field($_POST['eventdetail'])
            );
            $newpostid = wp_insert_post($my_post);
            if ($newpostid) {
                update_post_meta($newpostid, 'reservation_name', sanitize_text_field($_POST['name']));
                update_post_meta($newpostid, 'reservation_number', sanitize_text_field($_POST['number']));
                update_post_meta($newpostid, 'reservation_date', sanitize_text_field($_POST['date']));
                update_post_meta($newpostid, 'reservation_postid', sanitize_text_field($_POST['postid']));

                echo '1';
            }
        endif;
        exit;
    }
}

function datepicker_in_plugin(){
    ?>
    <script type="text/javascript">
        jQuery( function() {
            jQuery( ".datepicker" ).datepicker();
        } );
    </script>
<?php
}

add_action('wp_head','datepicker_in_plugin');

