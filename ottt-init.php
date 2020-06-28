<?php
if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
require_once( plugin_dir_path( __FILE__ ) . 'ottt-handlers.php' );
require_once( plugin_dir_path( __FILE__ ) . 'ottt-shortcodes.php' );
require_once( plugin_dir_path( __FILE__ ) . 'ottt-admin-settings.php' );

/*
add_action( 'init', 'create_ottt_customer' );
function create_ottt_customer() {
    register_post_type( 'ottt_customer' , array(
        'labels' => array(
            'name' => 'OTT Customers',
            'singular_name' => 'OTT Customer',
        ),
        'description' => 'Customers enrolled in OTT through the Enroll Customer form.',
        'show_ui' => true,
        'menu_icon' => 'dashicons-groups',
    ));
}

add_filter( 'manage_ottt_customer_posts_columns', 'ottt_customer_posts_columns' );
function ottt_customer_posts_columns( $columns ) {
    return array(
        'submitted' => __( 'Submitted', 'ott-tools' ),
        'fname' => __( 'First Name', 'ott-tools' ),
        'lname' => __( 'Last Name', 'ott-tools' ),
        'email' => __( 'Email', 'ott-tools' ),
        'employer' => __( 'Employer', 'ott-tools' ),
    );
}

add_action( 'manage_ottt_customer_posts_custom_column', 'ottt_customer_column', 10, 2 );
function ottt_customer_column( $column, $post_id ) {
    if( 'submitted' === $column ) {
        echo get_the_date( '', $post_id );
    }
    if( 'fname' === $column ) {
        echo get_post_meta( $post_id, 'ottt_customer_fname' , true );
    }
    if( 'lname' === $column ) {
        echo get_post_meta( $post_id, 'ottt_customer_lname' , true );
    }
    if( 'email' === $column ) {
        echo get_post_meta( $post_id, 'ottt_customer_email' , true );
    }
    if( 'employer' === $column ) {
        echo get_post_meta( $post_id, 'ottt_customer_employer' , true );
    }
}
*/

add_filter( 'acf/settings/remove_wp_meta_box', '__return_false' );