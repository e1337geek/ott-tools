<?php

function ottt_settings_init() {
    register_setting( 'ottt_settings', 'ottt_api_key' );
    register_setting( 'ottt_settings', 'ottt_product_id' );

    add_settings_section(
        'ottt_settings_section',
        'Vimeo OTT API ' . __( 'Settings', 'ott-tools' ),
        'ottt_settings_section_cb',
        'ottt_settings'
    );

    add_settings_field(
        'ottt_field_api_key',
        'OTT API Key',
        'ottt_field_api_key_cb',
        'ottt_settings',
        'ottt_settings_section'
    );

    add_settings_field(
        'ottt_field_product_id',
        'OTT Product ID',
        'ottt_field_product_id_cb',
        'ottt_settings',
        'ottt_settings_section'
    );

}
add_action( 'admin_init', 'ottt_settings_init' );

function ottt_settings_section_cb( $args ) {

}

function ottt_field_api_key_cb( $args ) {
    $api_key = get_option( 'ottt_api_key' );
    echo '<input type="text" id="ottt_api_key" name="ottt_api_key" value="' . $api_key . '" />';
}

function ottt_field_product_id_cb( $args ) {
    $product_id = get_option( 'ottt_product_id' );
    echo '<input type="text" id="ottt_product_id" name="ottt_product_id" value="' . $product_id . '" />';
}

function ottt_settings_page() {
    add_submenu_page(
        'edit.php?post_type=ottt_customer',
        'OTT Tools Settings',
        'Settings',
        'manage_options',
        'ottt_settings',
        'ottt_settings_template'
    );
}
add_action( 'admin_menu', 'ottt_settings_page' );

function ottt_settings_template() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    ob_start();
    include ( plugin_dir_path( __FILE__ ) . 'templates/admin/ottt-settings.php' );
    echo ob_get_clean();
}