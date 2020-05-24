<?php

function ottt_settings_init() {
    register_setting( 'ottt_api_key', 'ottt_settings' );
    register_setting( 'ottt_product_id', 'ottt_settings' );

    add_settings_section(
        'ottt_settings_section',
        'OTT Tools ' . __( 'Settings', 'ott-tools' ),
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

}

function ottt_field_product_id_cb( $args ) {

}

function ottt_settings_page() {
    add_menu_page(
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
    ob_start;
    include ( plugin_dir_path( __FILE__ ) . 'templates/admin/ottt-admin-settings.php' );
    echo ob_get_clean();
}