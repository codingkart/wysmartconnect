<?php

/**
 * @class    admin_module
 * @category Class
 * @author   CodingKart
 **/

class admin_module
{
    protected static $_instance = null;

    public static function get_instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct()
    {
        $this->hooks();
    }

    public function hooks()
    {

        //add_action('init', array($this, 'demo'));


        // Enqueue styles and scripts
        add_action('admin_enqueue_scripts', array($this, 'enqueue_styles_scripts'));

        // Add meta box
        add_action('add_meta_boxes', array($this, 'add_order_meta_box'),10,2);

        // Save meta box
        // add_action('save_post', array($this, 'save_order_meta_box'));

        // AJAX handler
        add_action('wp_ajax_save_order_details', array($this, 'ajax_save_order_details'));

      // add_action('admin_menu', array($this, 'add_admin_menu'));

        
        add_action('admin_notices', array($this, 'ftp_admin_notices'));
        
        
        add_action('wp_ajax_delete_ftp_data', array($this, 'ajax_delete_ftp_data'));
        
        add_action('wp_ajax_load_ftp_data', array($this, 'ajax_load_ftp_data'));
      
        add_action('admin_post_save_ftp_settings', array($this, 'handle_ftp_settings_save'));
        add_action('admin_post_update_ftp_settings', array($this, 'handle_ftp_settings_update'));

        add_action(CRON_UPDATE_TRAKING_NUMBER, array($this, 'cron_update_traking_number_write_log'));

        add_filter('cron_schedules', array($this, 'cron_update_traking_number_add_cron_interval'));

        // add_action('wp', array($this, 'cron_update_traking_number_write_log'));
		add_action('wp_ajax_auto_complete_toggle_functionality', [$this, 'auto_complete_toggle_functionality']);

    }




    /*
     * Enqueue stylesheets and JavaScript
     */
    public function enqueue_styles_scripts()
    {

        wp_enqueue_style('admin-modual', plugin_dir_url(__FILE__) . '/css/style.css');


        wp_enqueue_script(
            'admin_script',
            plugin_dir_url(__FILE__) . '/js/script.js',
            array('jquery'),
            rand(),
            true
        );


        wp_localize_script('admin_script', 'ajax_object', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('demo_nonce')
        ));
    }


    /**
     * Register the admin menu for the settings page.
     */
    // public function add_admin_menu()
    // {
    //     add_menu_page(
    //         'FTP Settings',               // Page title
    //         'FTP Settings',               // Menu title
    //         'manage_options',             // Capability
    //         'ftp-settings',               // Menu slug
    //         array($this, 'render_settings_page'), // Callback function


    //     );

    //     // Submenu
    //     add_submenu_page(
    //         'ftp-settings',                  // Parent slug
    //         'NodeJS Config',                   // Page title
    //         'NodeJS Config',                   // Menu title
    //         'manage_options',                // Capability
    //         'ftp-url',                   // Submenu slug
    //         array($this, 'render_url_page') // Callback function
    //     );
    // }


    /**
     * Render the settings page.
     */
    public function render_settings_page()
    {

        $ftpdata = $this->get_manufacturer_ftp_data();

        $data = array(
            'ftpdata' => $ftpdata,
        );


        echo ck_ftphelper_object()->ck_get_template('custom_ftp_data_save.php', 'admin-module', $data, true);
    }


    // Render the submenu page
    public function render_url_page()
    {
        // Check if the form is submitted
        if (isset($_POST['ftp_url_nonce']) && wp_verify_nonce($_POST['ftp_url_nonce'], 'save_ftp_url')) {
            if (isset($_POST['ftp_url_endpoint'])) {
                $ftp_url = sanitize_text_field($_POST['ftp_url_endpoint']);
                update_option('ftp_url_endpoint', $ftp_url); // Save the URL to options table
                echo '<div class="updated"><p>FTP URL Saved!</p></div>';
            }
        }

        // Get the current FTP URL
        $current_url = get_option('ftp_url_endpoint', '');

        $data = array(
            'current_url' => $current_url,
        );

        echo ck_ftphelper_object()->ck_get_template('ftp_url_save.php', 'admin-module', $data);
    }




    public function handle_ftp_settings_save()
    {
        
        global $wpdb;

        // Check nonce for security
        if (!isset($_POST['ftp_settings_nonce']) || !wp_verify_nonce($_POST['ftp_settings_nonce'], 'save_ftp_settings')) {
            set_transient('ftp_admin_notice', ['type' => 'error', 'message' => 'Security check failed.'], 30);
            wp_redirect(wp_get_referer());
            exit;
        }
        
        // Verify user permissions
        if (!current_user_can('manage_options')) {
            set_transient('ftp_admin_notice', ['type' => 'error', 'message' => 'You are not authorized to perform this action.'], 30);
            wp_redirect(wp_get_referer());
            exit;
        }
        
        // Sanitize and fetch the input values
        $manufacturer_name = sanitize_text_field($_POST['manufacturer_name']);
        $ftp_server = sanitize_text_field($_POST['ftp_server']);
        $ftp_username = sanitize_text_field($_POST['ftp_username']);
        $ftp_password = sanitize_text_field($_POST['ftp_password']);
        $file_path = sanitize_text_field($_POST['file_path']);
        $port = absint($_POST['port']);

        $secure = strpos($ftp_server, 'ftps') === 0;
        //$secure = false;
        // Validate port
        if ($port <= 0 || $port > 65535) {
            set_transient('ftp_admin_notice', ['type' => 'error', 'message' => 'Invalid port number.'], 30);
            wp_redirect(wp_get_referer());
            exit;
        }
        
        
        // Check FTP connection
        $ftpConnection = ck_fetch_data_object()->checkFTPConnection($ftp_server, $ftp_username, $ftp_password, $port, $secure);
        
        if ($ftpConnection['success']) {
            // FTP connection is successful; insert data into the database table
            $table_name = $wpdb->prefix . 'manufacturer_ftp';

            $wpdb->insert(
                $table_name,
                [
                    'manufacturer_name' => $manufacturer_name,
                    'ftp_server'        => $ftp_server,
                    'ftp_username'      => $ftp_username,
                    'ftp_password'      => $ftp_password,
                    'file_path'         => $file_path,
                    'port'              => $port,
                    'created'           => current_time('mysql'),
                    'updated'           => current_time('mysql'),
                ],
                [
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%d',
                    '%s',
                    '%s'
                ]
            );

            // Set success message
            set_transient('ftp_admin_notice', ['type' => 'success', 'message' => 'FTP settings saved successfully, and connection was successful.'], 30);
        } else {
            // Set error message
            set_transient('ftp_admin_notice', ['type' => 'error', 'message' => $ftpConnection['message'], 'ftp_response' => $ftpConnection], 30);
        }

        // Redirect back to the settings page
        wp_redirect(wp_get_referer());
        exit;
    }




    public function ftp_admin_notices()
    {
        // Get the notice data from the transient
        $notice = get_transient('ftp_admin_notice');

        // If there's a notice, display it
        if ($notice) {
            $class = $notice['type'] === 'success' ? 'notice-success' : 'notice-error';
            // $notice_message = (isset($notice['ftp_response'])) ? (json_decode($notice['ftp_response']))['error'] : $notice['message'];
            $notice_message = $notice['message'];
            if (isset($notice['ftp_response']['raw_response'])) {
                $raw_response = json_decode($notice['ftp_response']['raw_response'], true);
                if (isset($raw_response['error'])) {
                    $notice_message .= '<br>' . $raw_response['error'];
                }
                if (isset($raw_response['details'])) {
                    $notice_message .= ' : ' . $raw_response['details'];
                }
            }
            
            echo "<div class='notice $class is-dismissible'><p>{$notice_message}</p></div>";

            // Delete the transient so the notice only shows once
            delete_transient('ftp_admin_notice');
        }
    }


    /**
     * get data 
     */

    public function get_manufacturer_ftp_data()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'manufacturer_ftp';

        // Fetch data from the database
        $results = $wpdb->get_results("SELECT * FROM {$table_name}", ARRAY_A);


        // Return the data
        return $results;
    }

    /**
     * delete ftp setting page ftp data
     */

    public function ajax_delete_ftp_data()
    {
        global $wpdb;

        $id = intval($_POST['id']);
        $table_name = $wpdb->prefix . 'manufacturer_ftp';

        // Perform the delete operation
        $deleted = $wpdb->delete($table_name, ['id' => $id], ['%d']);

        // Return the appropriate response
        if ($deleted) {
            wp_send_json_success(['message' => 'FTP data deleted successfully.']);
        } else {
            wp_send_json_error(['message' => 'Failed to delete FTP data.']);
        }
    }

    /**
     * edit ftp setting page ftp data
     */



    public function ajax_load_ftp_data()
    {
        global $wpdb;

        $id = intval($_POST['id']);
        $table_name = $wpdb->prefix . 'manufacturer_ftp';

        // Fetch the data
        $data = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$table_name} WHERE id = %d", $id), ARRAY_A);

        if ($data) {
            wp_send_json_success($data);
        } else {
            wp_send_json_error(['message' => 'Failed to fetch FTP data.']);
        }
    }



    public function handle_ftp_settings_update()
    {

       
        global $wpdb;

        // Validate and sanitize input
        $id = intval($_POST['id']);
        $manufacturer_name = sanitize_text_field($_POST['manufacturer_name']);
        $ftp_server = sanitize_text_field($_POST['ftp_server']);
        $ftp_username = sanitize_text_field($_POST['ftp_username']);
        $ftp_password = sanitize_text_field($_POST['ftp_password']); // Handle plain password input
        $file_path = sanitize_text_field($_POST['file_path']);
        $port = absint($_POST['port']);

        $secure = strpos($ftp_server, 'ftps') === 0;


        //$secure = false;

        // Validate port
        if ($port <= 0 || $port > 65535) {
            set_transient('ftp_admin_notice', ['type' => 'error', 'message' => 'Invalid port number.'], 30);
            wp_redirect(wp_get_referer());
            exit;
        }

        // Check FTP connection
        $ftpConnection = ck_fetch_data_object()->checkFTPConnection($ftp_server, $ftp_username, $ftp_password, $port, $secure);
    
        if ($ftpConnection['success']) {
            $table_name = $wpdb->prefix . 'manufacturer_ftp';

            // Update the data
            $updated = $wpdb->update(
                $table_name,
                [
                    'manufacturer_name' => $manufacturer_name,
                    'ftp_server'        => $ftp_server,
                    'ftp_username'      => $ftp_username,
                    'ftp_password'      => $ftp_password,
                    'file_path'         => $file_path,
                    'port'              => $port,
                    'updated'           => current_time('mysql'),
                ],
                ['id' => $id],
                ['%s', '%s', '%s', '%s', '%s', '%d', '%s'],
                ['%d']
            );

            if ($updated !== false) {
                set_transient('ftp_admin_notice', ['type' => 'success', 'message' => 'FTP data updated successfully.'], 30);
            } else {
                set_transient('ftp_admin_notice', ['type' => 'error', 'message' => 'No changes were made to the FTP data or update failed.'], 30);
            }
        } else {
            // Set error message
            set_transient('ftp_admin_notice', ['type' => 'error', 'message' => $ftpConnection['message']], 30);
        }

        // Redirect back to the settings page
        wp_redirect(wp_get_referer());
        exit;
    }


    /*
     * Add the meta box to the WooCommerce order details page
     */
    public function add_order_meta_box($post_type, $post)
    {
        
        add_meta_box(
            'order_assignment_tracking',
            __('Manufacturer’s PO and Tracking Details', 'woocommerce'),
            array($this, 'render_order_meta_box'),
            'woocommerce_page_wc-orders',
            'advanced',
            'core'
        );

    }

    /*
     * Render the meta box content
     */
    public function render_order_meta_box($post)
    {        

        // Retrieve saved tracking details
        $tracking_details = get_post_meta($post->ID, '_tracking_details', true);
        // Get the order status
        $order = wc_get_order($post->ID);
        $order_status = $order ? $order->get_status() : '';
        // $this->generate_log("Cron schedules: ".json_encode(wp_get_schedules()));
        // Add nonce field for security

        $data = array(
            'post' =>  $post,
            'order' =>  $order,
            'order_status' =>  $order_status,
            'manufacturer_details' => $this->get_manufacturer_ftp_data(),
            'tracking_details' => $tracking_details
        );
        // print_r($data); die;        
        
        echo ck_ftphelper_object()->ck_get_template('custom_data_order_detalis_page.php', 'admin-module', $data, true);
    }

    /*
     * Save the meta box data
     */
    public function save_order_meta_box($post_id)
    {
        // Verify nonce
        if (!isset($_POST['order_meta_box_nonce']) || !wp_verify_nonce($_POST['order_meta_box_nonce'], 'save_order_meta_box')) {
            return $post_id;
        }

        // Verify user permissions
        if (!current_user_can('edit_post', $post_id)) {
            return $post_id;
        }

        // Save meta fields
        if (isset($_POST['asin_number'])) {
            update_post_meta($post_id, '_asin_number', sanitize_text_field($_POST['asin_number']));
        }

        if (isset($_POST['tracking_code'])) {
            update_post_meta($post_id, '_tracking_code', sanitize_text_field($_POST['tracking_code']));
        }
    }

    /*
     * AJAX handler for saving order details
     */
    // public function ajax_save_order_details()
    // {
    //     check_ajax_referer('demo_nonce', 'nonce');
    //     $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
    //     $asin_number = isset($_POST['asin_number']) ? sanitize_text_field($_POST['asin_number']) : '';
    //     $manufacturer_details = isset($_POST['manufacturer_id']) ? sanitize_text_field($_POST['manufacturer_id']) : '';

    //     // Need validation here 
    //     if (!$order_id) {
    //         wp_send_json_error(array('message' => 'All fields are required. Order id not found'));
    //     }
    //     if (empty($asin_number)) {
    //         wp_send_json_error(array('message' => 'All fields are required. asin not found'));
    //     }
    //     if (empty($manufacturer_details)) {
    //         wp_send_json_error(array('message' => 'All fields are required. Manufacture details not found'));
    //     }

    //     // Save meta fields
    //     update_post_meta($order_id, '_asin_number', $asin_number);
    //     update_post_meta($order_id, '_manufacturer_id', $manufacturer_details);


    //     wp_send_json_success(array('message' => 'Order details saved successfully.'));
    // }

    public function ajax_save_order_details()
    {
        // print_r($_POST); die;
        check_ajax_referer('demo_nonce', 'nonce');

        $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
        $asin_number = isset($_POST['asin_number']) ? sanitize_text_field($_POST['asin_number']) : '';
        $manufacturer_id = isset($_POST['manufacturer_id']) ? sanitize_text_field($_POST['manufacturer_id']) : 0;

        // Validation
        if (!$order_id) {
            wp_send_json_error(array('message' => 'All fields are required. Order ID not found.'));
        }
        if (empty($asin_number)) {
            wp_send_json_error(array('message' => 'All fields are required. ASIN not found.'));
        }
        if (empty($manufacturer_id)) {
            wp_send_json_error(array('message' => 'All fields are required. Manufacturer details not found.'));
        }

        // Get existing tracking details
        $tracking_details = get_post_meta($order_id, '_tracking_details', true);
        if (!is_array($tracking_details)) {
            $tracking_details = [];
        }

        // Update tracking details array
        $updated = false;
        foreach ($tracking_details as &$detail) {
            if ($detail['_manufacturer_id'] == $manufacturer_id) {
                $detail['_asin_number'] = $asin_number;
                $updated = true;
                break;
            }
        }

        // If manufacturer entry doesn't exist, add a new entry
        if (!$updated) {
            $tracking_details[] = [
                '_asin_number' => $asin_number,
                '_manufacturer_id' => $manufacturer_id,
                'tracking_code' => '', // Default tracking code if not set
            ];
        }

        // Save the updated tracking details
        update_post_meta($order_id, '_tracking_details', $tracking_details);


        // Add order note with user and formatted timestamp
        $current_user = wp_get_current_user();
        $username = $current_user->display_name ? $current_user->display_name : $current_user->user_login;
        // $formatted_date = date('F j, Y \a\t g:i a');
        $note = sprintf(
            'Manufacturer’s PO added by %s.',
            $username,
        );

        // Check if WooCommerce is active for order note functionality
        if (class_exists('WC_Order')) {
            $order = wc_get_order($order_id);
            if ($order) {
                $order->add_order_note($note);
            }
        } else {
            // Fallback for custom implementation: Add as a custom meta key for notes
            add_post_meta($order_id, '_order_notes', $note);
        }

        wp_send_json_success(array('message' => 'Order details saved successfully.'));
    }




    public function complete_order_status($order_id)
    {
        $order = wc_get_order($order_id);
        if ($order) {
            // Check if the order is not already completed
            if ($order->get_status() !== 'completed') {
                // Update the order status to completed
                $order->update_status('completed', 'Order completed From Scrub of Evans WySMartConnect plugin after getting tracking id');
            }
        }
    }

    public function shipment_tracking_data($tracking_data)
    {

        $tracking_number = isset($tracking_data['trackingNumbers'][0]) ? $tracking_data['trackingNumbers'][0] : '';

        $args = array(
            'tracking_provider' => 'UPS',
            'tracking_number' => sanitize_text_field($tracking_number),
            'date_shipped' => date_i18n('Y-m-d'),
        );

        return $args;
    }


    /**
     * Function that executes the cron job
     */

    function cron_update_traking_number_write_log()
    {
        $current_time = current_time('Y-m-d H:i:s');
        $this->generate_log("Cron ran at: $current_time");

        $order_ids = $this->get_untrak_order();

        $this->generate_log('Untracked orders: ' . json_encode($order_ids));

        if (empty($order_ids)) {
            $this->generate_log("No orders found.");
            return;
        }

        foreach ($order_ids as $order_id) {
            $this->generate_log('order ID: ' . $order_id);

            // Retrieve saved tracking details array
            $tracking_details = get_post_meta($order_id, '_tracking_details', true);

            if (!empty($tracking_details) && is_array($tracking_details)) {
                $manufacturer_details = $this->get_manufacturer_ftp_data();
                foreach ($tracking_details as $index => $detail) {
                    $asin_number = isset($detail['_asin_number']) ? sanitize_text_field($detail['_asin_number']) : '';
                    $manufacturer_id = isset($detail['_manufacturer_id']) ? intval($detail['_manufacturer_id']) : 0;
                    $this->generate_log('ASIN number: ' . $asin_number . ', Manufacturer ID: ' . $manufacturer_id);
        
                    // Skip if tracking code already exists
                    if (!empty($detail['tracking_code'])) {
                        $this->generate_log("Tracking code already exists for order $order_id. Skipping...");
                        continue;
                    }

                    // Validate manufacturer_id exists in manufacturers list
                    $manufacturer_exists = false;
                    foreach ($manufacturer_details as $manufacturer) {
                        if ((int) $manufacturer['id'] === $manufacturer_id) {
                            $manufacturer_exists = true;
                            break;
                        }
                    }

                    if (!$manufacturer_exists) {
                        $this->generate_log("Manufacturer ID $manufacturer_id not found for order $order_id. Skipping...");
                        continue;
                    }
                    
                    if ($asin_number && $manufacturer_id) {
                        // Fetch tracking data
                        $tracking_data = ck_fetch_data_object()->process_ftp_and_excel($asin_number, $manufacturer_id);
        
                        $this->generate_log('Tracking data response: ' . json_encode($tracking_data));
        
                        if (isset($tracking_data['success']) && $tracking_data['success'] && !empty($tracking_data['data'])) {
                            $tracking_numbers = array_column($tracking_data['data'], 'TrackingNumber');
        
                            if (!empty($tracking_numbers)) {
                                // Save the primary tracking number (first tracking number)
                                $primary_tracking_code = sanitize_text_field($tracking_numbers[0]);
                                $tracking_details[$index]['tracking_code'] = $primary_tracking_code;

                                // Save updated tracking details array
                                update_post_meta($order_id, '_tracking_details', $tracking_details);
        
                                // Save all tracking details in another meta field
                                update_post_meta($order_id, '_tracking_details_'.$manufacturer_id, $tracking_data['data']);
                                
                                $args = $this->shipment_tracking_data($tracking_data);
                                $shipment_tracking  = WC_Shipment_Tracking_Actions::get_instance();
                                $tracking_items = ($shipment_tracking->get_tracking_items($order_id));
                                $is_duplicate = false;
        
                                // Ensure tracking data exists and is an array
                                if (!empty($tracking_items) && is_array($tracking_items)) {
                                    foreach ($tracking_items as $tracking_item) {
                                        if ($tracking_item['tracking_number'] === $primary_tracking_code) {
                                            $is_duplicate = true;
                                            break; // Stop loop early if a duplicate is found
                                        }
                                    }
                                }
        
                                // If the tracking number doesn't exist, add a new tracking entry
                                if (!$is_duplicate) {
                                    $shipment_tracking->add_tracking_item($order_id, $args);
                                }
        
                                $ftp_enabled = get_option('WySMartConnect_enable_auto_complete_order_status', 'off');
                                if($ftp_enabled == 'on'){
                                    // Complete the order status
                                    $this->complete_order_status($order_id);
                                }
        
                                // Add order note for completed status
                                $order = wc_get_order($order_id);
                                if ($order && $ftp_enabled == 'on') {
                                    $order->add_order_note('Order completed by WySMartConnect. Cron job completed, and shipment tracking data has been inserted.');
                                } else {
                                    $order->add_order_note('Order processed by WySMartConnect. Cron job completed, and shipment tracking data has been inserted.');
        
                                }
        
                                $this->generate_log('Tracking number saved for order ' . $order_id . ': ' . $primary_tracking_code);
                            } else {
                                $this->generate_log('No tracking numbers found in the response for order ' . $order_id);
                            }
                        } else {
                            $this->generate_log('Failed to fetch tracking data for order ' . $order_id . '. Response: ' . json_encode($tracking_data));
                        }
                    } else {
                        $this->generate_log('ASIN number or Manufacturer ID not found for order ' . $order_id);
                    }
                }
            }else{
                $this->generate_log("No tracking details found for order $order_id.");
            }
    
        }
    
    }


    private function generate_log($string)
    {
        $log_file = plugin_dir_path(__FILE__) . 'cronjob.log';
        $message = " $string\n";
        // Append the current time to the log file
        file_put_contents($log_file, $message, FILE_APPEND | LOCK_EX);
    }

    /**
     * Add a custom cron interval for one minute
     */
    function cron_update_traking_number_add_cron_interval($schedules)
    {
        $schedules['every_minute'] = array(
            'interval' => 60, // 6 seconds
            'display'  => __('Every 1 minute'),
        );
        return $schedules;
    }

    public function get_untrak_order()
    {
        $all_statuses = wc_get_order_statuses();
        $exclude_statuses = array('wc-completed', 'wc-cancelled', 'wc-failed', 'wc-checkout-draft');

        // Filter out the excluded ones
        $statuses_to_include = array_diff(array_keys($all_statuses), $exclude_statuses);
        $this->generate_log("order status : ".json_encode($statuses_to_include));
        $args = array(
            'status' => $statuses_to_include,
            'limit' => -1,
            'type' => 'shop_order',
            'return' => 'ids',
        );
        $orders = wc_get_orders($args);
        return $orders;
    }

    public function auto_complete_toggle_functionality()
	{
		check_ajax_referer('demo_nonce', 'security');
		$status = isset($_POST['status']) && $_POST['status'] === 'on' ? 'on' : 'off';
		update_option('WySMartConnect_enable_auto_complete_order_status', $status);
		wp_send_json_success(['status' => $status]);
	}
}



// Initialize the class
function ck_admin_module_obj()
{
    return admin_module::get_instance();
}
ck_admin_module_obj();
