<?php

function ottt_enroll_customer_shortcode( $atts ) {
    extract(shortcode_atts(array(
        'employer' => null,
        'expiration' => null,
    ), $atts));

    ob_start;
    include (locate_template('ottt-enroll-customer.php'));
    return ob_get_clean();
}
add_shortcode( 'ottt_enroll_customer', 'ottt_enroll_customer_shortcode' );