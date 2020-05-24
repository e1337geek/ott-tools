<?php
require_once( plugin_dir_path( __FILE__ ) . 'ottt-handlers.php' );
require_once( plugin_dir_path( __FILE__ ) . 'ottt-shortcodes.php' );

add_action( 'init', 'create_ottt_customer' );
function create_ottt_customer() {
    register_post_type( 'ottt_customer' , array(
        'labels' => array(
            'name' => 'OTT Customers',
            'singular_name' => 'OTT Customer',
        ),
        'description' => 'Customers enrolled in OTT through the Enroll Customer form.',
        'show_ui' => true,
        'menu_icon' => 'dashicons-groups',
    ));
}