<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://https://codingkart.com/
 * @since      1.0.0
 *
 * @package    Show_Variation_Products_On_Shop_Category
 * @subpackage Show_Variation_Products_On_Shop_Category/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Show_Variation_Products_On_Shop_Category
 * @subpackage Show_Variation_Products_On_Shop_Category/includes
 * @author     Codingkart <info@codingkart.com>
 */
class Show_Variation_Products_On_Shop_Category_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'show-variation-products-on-shop-category',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
