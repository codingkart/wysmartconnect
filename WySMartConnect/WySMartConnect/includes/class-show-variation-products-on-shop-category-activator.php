<?php

/**
 * Fired during plugin activation
 *
 * @link       https://https://codingkart.com/
 * @since      1.0.0
 *
 * @package    Show_Variation_Products_On_Shop_Category
 * @subpackage Show_Variation_Products_On_Shop_Category/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Show_Variation_Products_On_Shop_Category
 * @subpackage Show_Variation_Products_On_Shop_Category/includes
 * @author     Codingkart <info@codingkart.com>
 */
class Show_Variation_Products_On_Shop_Category_Activator
{
	static $missing_item;

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate()
	{
		// Check if the product attribute for color exists
		self::check_required_attributes();
	}
	public static function check_required_attributes()
	{
		// Check if the product attribute for color exists
		$taxonomy_exists = taxonomy_exists('pa_size');

		if (!$taxonomy_exists) {
			// Create the product attribute for color
			$attribute_args = array(
				'name' => 'Size',
				'slug' => 'pa_size',
				'type' => 'select',
				'order_by' => 'name',
				'has_archives' => true,
			);

			// Register the product attribute
			wc_create_attribute($attribute_args);
		}
		$taxonomy_exists = taxonomy_exists('pa_color');

		if (!$taxonomy_exists) {
			// Create the product attribute for color
			$attribute_args = array(
				'name' => 'Color',
				'slug' => 'pa_color',
				'type' => 'select',
				'order_by' => 'name',
				'has_archives' => true,
			);

			// Register the product attribute
			wc_create_attribute($attribute_args);
		}
	}
}
