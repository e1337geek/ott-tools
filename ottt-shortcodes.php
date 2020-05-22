<?php

function ottt_enroll_customer_shortcode( $atts ) {
    ob_start;
    return ob_get_clean();
}
add_shortcode( 'ottt_enroll_customer', 'ottt_enroll_customer_shortcode' );