<?php
/**
 * Plugin Name: ByteIT OTT Tools
 * Description: A set of tools to assist in integrating with the Vimeo OTT API
 * Version: 1.0.2
 * Author: ByteIT
 * Author URI: https://byteitsystems.com
 * Text Domain: ott-tools
 */

function ottt_activate() {
    global $wpdb;
    $customersTable = 'ottt_customers';
    $charset_collate = $wpdb->get_charset_collate();
    $createSQL = "CREATE TABLE IF NOT EXISTS `$customersTable` (
        customer_id int UNSIGNED NOT NULL AUTO_INCREMENT,
        ottt_vhx_customer_id int UNSIGNED,
        ottt_customer_fname varchar(50),
        ottt_customer_lname varchar(50),
        ottt_customer_email varchar(70),
        ottt_customer_source varchar(70),
        ottt_customer_success smallint,
        ottt_customer_error varchar(20),
        PRIMARY KEY (customer_id)
        ) $charset_collate;";
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta( $createSQL );
}
register_activation_hook( __FILE__, 'ottt_activate' );

function ottt_decrypt_string( $encrypted, $passphrase ) {
    $encrypted = base64_decode($encrypted);
    $salted = substr($encrypted, 0, 8) == 'Salted__';

    if (!$salted) {
        return null;
    }

    $salt = substr($encrypted, 8, 8);
    $encrypted = substr($encrypted, 16);

    $salted = $dx = '';
    while (strlen($salted) < 48) {
        $dx = md5($dx . $passphrase . $salt, true);
        $salted .= $dx;
    }

    $key = substr($salted, 0, 32);
    $iv = substr($salted, 32, 16);

    return openssl_decrypt($encrypted, 'aes-256-cbc', $key, true, $iv);
}

require_once( plugin_dir_path( __FILE__ ) . 'ottt-init.php' );