<?php

function ottt_enroll_customer_shortcode( $atts ) {
    ob_start;
    include plugin_dir_path( __FILE__ ) . 'templates/ottt-enroll-customer.php';
    return ob_get_clean();
}
add_shortcode( 'ottt_enroll_customer', 'ottt_enroll_customer_shortcode' );