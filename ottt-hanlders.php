<?php

function ottt_enroll_customer_form_handler() {

    /* Initialize Variables */
    $fname = sanitize_text_field( $_POST['fname'] );
    $lname = sanitize_text_field( $_POST['lname'] );
    $employer = sanitize_text_field( $_POST['employer'] );
    $email = sanitize_email( $_POST['email'] );
    $password = $_POST['password'];

    $ott_response = wp_remote_post( $url, array(
        'method' => 'POST',
        'headers' => array(
            'Authorization' => 'Basic ' . base64_encode( get_option( 'ottt_api_key' ) ),
        ),
        'body' => array(
            'name' => $fname . ' ' . $lname,
            'email' => $email,
            'password' => $password,
            'product' => 'https://api.vhx.tv/products/' . get_option( 'ottt_product_id' ),
        ),
    ));

    $ottt_customer_details = array(
        'post_type' => 'ottt_customer',
        'post_status' => 'private',
        'comment_status' => 'closed',
        'ping_status' => 'closed',
        'meta_input' => array(
            'ottt_customer_fname' => $fname,
            'ottt_customer_lname' => $lname,
            'ottt_customer_email' => $email,
            'ottt_customer_employer' => $employer,
        ),
    );

    $ottt_customer_id = wp_insert_post( $ottt_customer_details );

}
add_action( 'admin_post_nopriv_ottt_enroll_customer', 'ottt_enroll_customer_form_handler' );
add_action( 'admin_post_ottt_enroll_customer', 'ottt_enroll_customer_form_handler' );
