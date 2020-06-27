<?php
/**
 * Plugin Name: ByteIT OTT Tools
 * Description: A set of tools to assist in integrating with the Vimeo OTT API
 * Version: 1.0.2
 * Author: ByteIT
 * Author URI: https://byteitsystems.com
 * Text Domain: ott-tools
 */

require_once( plugin_dir_path( __FILE__ ) . 'ottt-init.php' );

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