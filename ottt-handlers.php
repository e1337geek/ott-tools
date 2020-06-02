<?php

function ottt_enroll_customer_form_handler() {

    $ott_success = 0;
    $ott_error = '';

    if ( ! empty( $_POST['_wp_http_referer'] ) ) {
        $form_url = esc_url_raw( wp_unslash( $_POST['_wp_http_referer'] ) );
    } else {
        $form_url = home_url( '/' );
    }

    if( 
        ( isset( $_POST['fname'] ) || isset( $_POST['lname'] ) ) &&
        isset( $_POST['email'] ) &&
        isset( $_POST['password'] )
    ) {

        /* Initialize Variables */
        $fname = sanitize_text_field( $_POST['fname'] );
        $lname = sanitize_text_field( $_POST['lname'] );
        $employer = sanitize_text_field( $_POST['employer'] );
        $email = sanitize_email( $_POST['email'] );
        $password = $_POST['password'];

        if (
            ( strlen( $fname ) || strlen( $lname ) ) &&
            strlen( $email ) &&
            strlen( $password )
        ) {

            $url = 'https://api.vhx.tv/customers';
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

            if( is_wp_error( $ott_response ) ) {
                $ott_error = 'wp';
            } elseif ( $ott_response['response']['code'] === 200 || $ott_response['response']['code'] === 201 ) {
                $ott_success = 1;
            } else {
                $ott_error = 'ott';
            }
        } else {
            $ott_error = 'fields';
        }

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
                'ottt_customer_success' => $ott_success,
                'ottt_customer_error' => $ott_error,
            ),
        );
        $ottt_customer_id = wp_insert_post( $ottt_customer_details );

    } else {
        $ott_error = 'fields';
    }

    if( $ott_success == '1' && get_option( 'ottt_success_redirect' ) ) {
        wp_redirect(
            esc_url( get_option( 'ottt_success_redirect' ) )
        );
    } else {

        wp_safe_redirect(
            esc_url_raw(
                add_query_arg( 
                    array(
                        'success' => $ott_success,
                        'ott-error' => $ott_error,
                    ),
                    $form_url
                )
            )
        );
    }

}
add_action( 'admin_post_nopriv_ottt_enroll_customer', 'ottt_enroll_customer_form_handler' );
add_action( 'admin_post_ottt_enroll_customer', 'ottt_enroll_customer_form_handler' );