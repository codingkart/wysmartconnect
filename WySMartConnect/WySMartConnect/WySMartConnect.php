<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://https://codingkart.com/
 * @since             1.1
 * @package           Show_Variation_Products_On_Shop_Category
 *
 * @wordpress-plugin
 * Plugin Name:       WySMartConnect 
 * Plugin URI:        https://https://codingkart.com/
 * Description:       Display product variations on shop and category pages
 * Version:           1.0.0
 * Author:            Codingkart
 * Author URI:        https://https://codingkart.com//
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       show-variation-products-on-shop-category
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */


define('SHOW_VARIATION_PRODUCTS_ON_SHOP_CATEGORY_VERSION', '1.0.0');

// Define a unique hook for the cron job
if (!defined('CRON_UPDATE_TRAKING_NUMBER')) {
	define('CRON_UPDATE_TRAKING_NUMBER', 'cron_update_traking_number');
}

define('WYSMart_plugin_path', plugin_dir_path( __FILE__ ));




//check dependency
// Hook into admin initialization

add_action('admin_init', 'custom_plugin_check_dependencies');

// Function to check if WooCommerce and Flatsome theme are activated

function custom_plugin_check_dependencies()
{
	// Check if WooCommerce plugin is active
	$woocommerce_active = is_plugin_active('woocommerce/woocommerce.php');

	// Check if Flatsome theme is active
	$active_theme = wp_get_theme();
	$flatsome_active = ($active_theme->get('Name') === 'Flatsome');
	$flatsome_child_active = ($active_theme->get('Name') === 'Flatsome Child');
	// var_dump($active_theme->get('Name'));
	// die();

	if (!$woocommerce_active) {
		// Deactivate the custom plugin to prevent further issues
		deactivate_plugins(plugin_basename(__FILE__));
		unset($_GET['activate']);
		// Add admin notice
		add_action('admin_notices', 'custom_plugin_admin_notice');
	}
}


// Function to display admin notice
function custom_plugin_admin_notice()
{
?>


	<div class="error">


		<p><?php _e('WySMartConnect requires WooCommerce plugin to be activated. Please activate WooCommerce and try again.', 'custom-plugin'); ?></p>


	</div>


<?php
}

//end


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-show-variation-products-on-shop-category-activator.php
 */


function activate_show_variation_products_on_shop_category()
{


	// Call the function to create the table
	create_manufacturer_ftp_table();
	// Schedule the cron job
	cron_update_traking_number_activate();

	require_once plugin_dir_path(__FILE__) . 'includes/class-show-variation-products-on-shop-category-activator.php';
	Show_Variation_Products_On_Shop_Category_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-show-variation-products-on-shop-category-deactivator.php
 */


function deactivate_show_variation_products_on_shop_category()
{
	cron_update_traking_number_deactivate();
	require_once plugin_dir_path(__FILE__) . 'includes/class-show-variation-products-on-shop-category-deactivator.php';
	Show_Variation_Products_On_Shop_Category_Deactivator::deactivate();
}
register_activation_hook(__FILE__, 'activate_show_variation_products_on_shop_category');
register_deactivation_hook(__FILE__, 'deactivate_show_variation_products_on_shop_category');





function create_manufacturer_ftp_table()
{
	global $wpdb;

	// Table name
	$table_name = $wpdb->prefix . 'manufacturer_ftp';

	// Check if the table already exists
	if ($wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") != $table_name) {
		// Table does not exist, so create it
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE {$table_name} (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            manufacturer_name VARCHAR(255) NOT NULL,
            ftp_server VARCHAR(255) NOT NULL,
            ftp_username VARCHAR(255) NOT NULL,
            ftp_password VARCHAR(255) NOT NULL,
            file_path VARCHAR(255) NOT NULL,
            port INT(5) NOT NULL,
            created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) {$charset_collate};";

		// Include the required file for dbDelta
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		// Create the table
		dbDelta($sql);
	}
}


/**
 * Schedule the cron job on plugin activation
 */
function cron_update_traking_number_activate()
{

	// Schedule the cron event only if it's not already scheduled
	if (!wp_next_scheduled(CRON_UPDATE_TRAKING_NUMBER)) {
		wp_schedule_event(time(), 'every_minute', CRON_UPDATE_TRAKING_NUMBER);
	}
}


/**
 * Unschedule the cron job on plugin deactivation
 */
function cron_update_traking_number_deactivate()
{
	$timestamp = wp_next_scheduled(CRON_UPDATE_TRAKING_NUMBER);
	if ($timestamp) {
		wp_unschedule_event($timestamp, CRON_UPDATE_TRAKING_NUMBER);
	}
}



/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */


require plugin_dir_path(__FILE__) . 'includes/class-show-variation-products-on-shop-category.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */

function run_show_variation_products_on_shop_category()
{
	$plugin = new Show_Variation_Products_On_Shop_Category();


	$plugin->run();
}


add_action('init', 'ensure_cron_update_traking_number_scheduled');
function ensure_cron_update_traking_number_scheduled() {
    if (!wp_next_scheduled('cron_update_traking_number')) {
        wp_schedule_event(time(), 'every_minute', 'cron_update_traking_number');
    }
}

register_deactivation_hook(__FILE__, 'cron_update_traking_number_deactivate2');
function cron_update_traking_number_deactivate2() {
    wp_clear_scheduled_hook('cron_update_traking_number');
}


run_show_variation_products_on_shop_category();


require_once 'modules/included_files.php';
