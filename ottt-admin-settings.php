<?php

function ottt_settings_init() {
    register_setting( 'ottt_settings', 'ottt_api_key' );
    register_setting( 'ottt_settings', 'ottt_product_id' );
    register_setting( 'ottt_settings', 'ottt_success_redirect' );

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

    add_settings_field(
        'ottt_field_success_redirect',
        'Redirect on Success',
        'ottt_field_success_redirect_cb',
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

function ottt_field_success_redirect_cb( $args ) {
    $success_redirect = get_option( 'ottt_success_redirect' );
    echo '<input type="text" id="ottt_success_redirect" name="ottt_success_redirect" value="' . $success_redirect . '" />';
}

function ottt_settings_page() {
    add_menu_page(
        'OTT Customers',
        'OTT Customers',
        'manage_options',
        'ottt_customers',
        'ottt_customers_template',
        'dashicons-groups'
    );
    add_submenu_page(
        'ottt_customers',
        'OTT Activity Report',
        'Activity Report',
        'manage_options',
        'ottt_activity',
        'ottt_activity_template'
    );
    add_submenu_page(
        'ottt_customers',
        'OTT Disable Inactive',
        'Disable Inactive',
        'manage_options',
        'ottt_disable_inactive',
        'ottt_disable_inactive_template'
    );
    add_submenu_page(
        'ottt_customers',
        'OTT Tools Settings',
        'Settings',
        'manage_options',
        'ottt_settings',
        'ottt_settings_template'
    );
}
add_action( 'admin_menu', 'ottt_settings_page' );

function ottt_customers_template() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    ob_start();
    include ( plugin_dir_path( __FILE__ ) . 'templates/admin/ottt-customers.php' );
    echo ob_get_clean();
}

function ottt_activity_template() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    ob_start();
    include ( plugin_dir_path( __FILE__ ) . 'templates/admin/ottt-activity-report.php' );
    echo ob_get_clean();
}

function ottt_disable_inactive_template() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    ob_start();
    include ( plugin_dir_path( __FILE__ ) . 'templates/admin/ottt-disable-inactive.php' );
    echo ob_get_clean();
}

function ottt_settings_template() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    ob_start();
    include ( plugin_dir_path( __FILE__ ) . 'templates/admin/ottt-settings.php' );
    echo ob_get_clean();
}

class OTTT_Customers_List extends WP_List_Table {

	/** Class constructor */
	public function __construct() {

		parent::__construct( [
			'singular' => __( 'Customer', 'sp' ), //singular name of the listed records
			'plural'   => __( 'Customers', 'sp' ), //plural name of the listed records
			'ajax'     => false //should this table support ajax?

		] );

    }

    public static function get_customers( $per_page = 5, $page_number = 1 ) {

        global $wpdb;
        $sql = "SELECT * FROM ottt_customers";
      
        if ( ! empty( $_REQUEST['orderby'] ) ) {
          $sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
          $sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
        }
      
        $sql .= " LIMIT $per_page";
        $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;
        $result = $wpdb->get_results( $sql, 'ARRAY_A' );

        return $result;
    }

    public static function delete_customer( $id ) {
        //This is where we will disable the customer in VHX
        global $wpdb;
        $sql = "SELECT * FROM ottt_customers WHERE ";
    }

    public static function record_count() {
        global $wpdb;
        $sql = "SELECT COUNT(*) FROM ottt_customers";
        return $wpdb->get_var( $sql );
    }

    function column_name( $item ) {

        $delete_nonce = wp_create_nonce( 'ottt_disable_customer' );
        $title = '<strong>' . $item['name'] . '</strong>';
        $actions = [
          'delete' => sprintf( '<a href="?page=%s&action=%s&customer=%s&_wpnonce=%s">Disable</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['ID'] ), $delete_nonce )
        ];
      
        return $title . $this->row_actions( $actions );
    }

    public function column_default( $item, $column_name ) {
        switch ( $column_name ) {
          case 'address':
          case 'city':
            return $item[ $column_name ];
          default:
            return print_r( $item, true ); //Show the whole array for troubleshooting purposes
        }
    }
    
    function column_cb( $item ) {
        return sprintf(
          '<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['ID']
        );
    }

    function get_columns() {
        $columns = [
          'ottt_customer_fname'    => __( 'First Name', 'ott-tools' ),
          'ottt_customer_lname' => __( 'Last Name', 'ott-tools' ),
          'ottt_customer_email'    => __( 'Email Address', 'ott-tools' )
        ];
      
        return $columns;
    }

    public function get_sortable_columns() {
        $sortable_columns = array(
          'ottt_customer_fname' => array( 'name', true ),
          'ottt_customer_lname' => array( 'city', false ),
          'ottt_customer_email' => array( 'city', false ),
        );
      
        return $sortable_columns;
    }

    public function prepare_items() {

        $this->_column_headers = $this->get_column_info();
      
        /** Process bulk action */
        $this->process_bulk_action();
      
        $per_page     = $this->get_items_per_page( 'customers_per_page', 5 );
        $current_page = $this->get_pagenum();
        $total_items  = self::record_count();
      
        $this->set_pagination_args( [
          'total_items' => $total_items, //WE have to calculate the total number of items
          'per_page'    => $per_page //WE have to determine how many items to show on a page
        ] );
      
        $this->items = self::get_customers( $per_page, $current_page );
    }
}