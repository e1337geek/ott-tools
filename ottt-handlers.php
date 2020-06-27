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

function ottt_activity_report_form_handler() {
    global $wpdb;
    $lookerTable = 'ottt-looker-report';

    echo "<h3>" . var_dump( $_FILES ) . "</h3";

    if( isset( $_POST['activity_import'] ) ) {
        $extension = pathinfo( $_FILES['looker_report']['name'], PATHINFO_EXTENSION );

        if( !empty( $_FILES['looker_report']['name'] ) && $extension == 'csv' ) {
            $dropSQL = "DROP TABLE IF EXISTS `$lookerTable`;";
            $wpdb->query( $dropSQL );
            $charset_collate = $wpdb->get_charset_collate();
            $createSQL = "CREATE TABLE `$lookerTable` (
                activity_id int UNSIGNED NOT NULL AUTO_INCREMENT,
                user_id int UNSIGNED,
                email varchar(70) NOT NULL,
                video_id int UNSIGNED,
                title varchar(255),
                platform varchar(10),
                start_date varchar(10),
                min_watched FLOAT(5,2),
                PRIMARY KEY (activity_id)
                ) $charset_collate;";
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
            dbDelta( $createSQL );

            $totalInserted = 0;
            $lookerReportCSV = fopen( $_FILES['looker_report']['tmp_name'], 'r' );
            fgetcsv( $lookerReportCSV );

            while( ( $csvData = fgetcsv( $lookerReportCSV ) ) !== FALSE ) {
                $csvData = array_map( "utf8_encode", $csvData );
                $dataLen = count( $csvData );

                if( !( $dataLen == 7 ) ) continue;

                $userID = trim( $csvData[0] );
                $email = trim( $csvData[1] );
                $videoID = trim( $csvData[2] );
                $title = trim( $csvData[3] );
                $platform = trim( $csvData[4] );
                $startDate = trim( $csvData[5] );
                $minWatched = trim( $csvData[6] );
            }

            if( !empty( $email ) ) {
                
                $wpdb->insert( $lookerTable, array(
                    'user_id' => $userID,
                    'email' => $email,
                    'video_id' => $videoID,
                    'title' => $title,
                    'platform' => $platform,
                    'start_date' => $startDate,
                    'min_watched' => $minWatched,
                ) );

                if( $wpdb->insert_id > 0 ) {
                    $totalInserted++;
                }

            }

            echo "<h3 style='color: green;'>Total Lines : ".$totalInserted."</h3>";

        } else {
            echo "<h3 style='color: red;'>Invalid Extension</h3>";
        }

    }
}

add_action( 'admin_post_ottt_activity_report', 'ottt_activity_report_form_handler' );