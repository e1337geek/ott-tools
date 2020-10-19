<?php

function ottt_enroll_customer_form_handler() {
    global $wpdb;
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
                $ottResponseBody = json_decode( $ott_response['body'], true );
                $vhxID = $ottResponseBody['id'];
            } else {
                $ott_error = 'ott';
            }
        } else {
            $ott_error = 'fields';
        }

        $wpdb->insert( 'ottt_customers', array(
            'ottt_vhx_customer_id' => $vhxID,
            'ottt_customer_fname' => $fname,
            'ottt_customer_lname' => $lname,
            'ottt_customer_email' => $email,
            'ottt_customer_source' => $employer,
            'ottt_customer_success' => $ott_success,
            'ottt_customer_error' => $ott_error,
            'ottt_customer_last_viewed' => time(),
            'ottt_customer_disabled' => null,
        ) );

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
    $lookerTable = 'ottt_looker_report';

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

            while( ( $csvData = fgetcsv( $lookerReportCSV ) ) !== FALSE ){
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
            }

            $activityReportSQL = "SELECT DISTINCT c.ottt_customer_fname, c.ottt_customer_lname, c.ottt_customer_email, c.ottt_customer_source, a.user_id, a.video_id, a.title, a.platform, a.start_date, a.min_watched
            FROM `ottt_customers` c
            INNER JOIN `ottt_looker_report` a
                ON c.ottt_customer_email = a.email;";

            $filename = 'ottt-activity-report';
            $date = date("Y-m-d H:i:s");
            $output = fopen('php://output', 'w');
            $result = $wpdb->get_results($activityReportSQL, ARRAY_A);
            fputcsv( $output, array('First Name', 'Last Name', 'Email', 'Source','User ID', 'Video ID', 'Video Title', 'Platform', 'Date', 'Min Watched'));
            
            foreach ( $result as $key => $value ) {
                error_log(print_r("Hello World",true));
                ottt_update_last_viewed( $value['ottt_customer_email'], $value['start_date'] );
                $modified_values = array(
                    $value['ottt_customer_fname'],
                    $value['ottt_customer_lname'],
                    $value['ottt_customer_email'],
                    $value['ottt_customer_source'],
                    $value['user_id'],
                    $value['video_id'],
                    $value['title'],
                    $value['platform'],
                    $value['start_date'],
                    $value['min_watched'],
                );
                fputcsv( $output, $modified_values );
            }
            
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Cache-Control: private", false);
            header('Content-Type: text/csv; charset=utf-8');
            header("Content-Disposition: attachment; filename=\"" . $filename . " " . $date . ".csv\";" );
            header("Content-Transfer-Encoding: binary");exit;

        } else {
            echo "<h3 style='color: red;'>Invalid Extension</h3>";
        }

    }
}
add_action( 'admin_post_ottt_activity_report', 'ottt_activity_report_form_handler' );

function ottt_update_last_viewed ( $customer_email, $start_date ) {
    global $wpdb;
    $customersTable = "ottt_customers";
    $formattedDate = strtotime( $start_date );
    $sql = "UPDATE `$customersTable` SET `ottt_customer_last_viewed` = $formattedDate WHERE `ottt_customer_email` = '$customer_email' AND `ottt_customer_last_viewed` < $formattedDate;";
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta( $sql );
}

function ottt_disable_inactive_form_handler() {

    global $wpdb;
    $customerTable = "ottt_customers";
    $currentTimestamp = time();
    $gracePeriodSec = 2419200;
    $minLastViewed = $currentTimestamp - $gracePeriodSec;
    $sqlSelInactive = "SELECT * FROM `$customerTable` WHERE `ottt_customer_last_viewed` < $minLastViewed;";
    $customers = $wpdb->get_results( $sqlSelInactive, 'ARRAY_A' );

    foreach ( $customers as $customer ) { 
        $disableResult = ottt_disable_customer( $customer );
        if( $disableResult['response']['code'] === 200 || $disableResult['response']['code'] === 201 ) {
            $customer_email = $customer['ottt_customer_email'];
            $sqlUpdateDisabled = "UPDATE `$customerTable` SET `ottt_customer_disabled` = $currentTimestamp WHERE `ottt_customer_email` = '$customer_email'";
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
            dbDelta( $sqlUpdateDisabled );
        }
    }

}
add_action( 'admin_post_ottt_disable_inactive', 'ottt_disable_inactive_form_handler' );

function ottt_disable_customer ( $customer ) {

    if ( $customer['ottt_vhx_customer_id'] ) {
        $url = 'https://api.vhx.tv/customers/' . $customer['ottt_vhx_customer_id'] . '/products';
        $ott_response = wp_remote_post( $url, array(
            'method' => 'DELETE',
            'headers' => array(
                'Authorization' => 'Basic ' . base64_encode( get_option( 'ottt_api_key' ) ),
            ),
            'body' => array(
                'product' => 'https://api.vhx.tv/products/' . get_option( 'ottt_product_id' ),
            ),
        ));

        return $ott_response;
    } else {
        return false;
    }
}

function ottt_get_customer_source( WP_REST_Request $request ) {
    global $wpdb;
    $email = $request['email'];
    $response = new WP_REST_Response();
    if( is_email( $email ) ) {
        $getCustomersSQL = "SELECT * FROM `ottt_customers` WHERE `ottt_customer_email` = '$email';";
        $result = $wpdb->get_row( $getCustomersSQL );

        if( empty( $result ) ) {
            return false;
        }

        return $result->ottt_customer_source;
    }
    return false;
}

add_action( 'rest_api_init', function () {
    register_rest_route( 'ottt/v1', '/getCustomerSource', array(
        'methods' => 'GET',
        'callback' => 'ottt_get_customer_source',
    ) );
} );