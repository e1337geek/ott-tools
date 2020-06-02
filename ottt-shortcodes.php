<?php

function ottt_enroll_customer_shortcode( $atts ) {
    $args = shortcode_atts(
        array(
            'employer' => null,
            'expiration' => null,
            'label_fname' => __( 'First Name', 'ott-tools' ),
            'label_lname' => __( 'Last Name', 'ott-tools' ),
            'label_employer' => __( 'Employer', 'ott-tools' ),
            'label_email' => __( 'Email Address', 'ott-tools' ),
            'label_password' => __( 'Password', 'ott-tools' ),
            'label_submit' => __( 'Submit', 'ott-tools' ),
        ), 
        $atts
    );
    $employer = $args['employer'];
    $expiration = $args['expiration'];
    $label_fname = $args['label_fname'];
    $label_lname = $args['label_lname'];
    $label_employer = $args['label_employer'];
    $label_email = $args['label_email'];
    $label_password = $args['label_password'];
    $label_submit = $args['label_submit'];

    ob_start();
    include ( plugin_dir_path( __FILE__ ) . 'templates/ottt-enroll-customer.php' );
    return ob_get_clean();
}
add_shortcode( 'ottt_enroll_customer', 'ottt_enroll_customer_shortcode' );