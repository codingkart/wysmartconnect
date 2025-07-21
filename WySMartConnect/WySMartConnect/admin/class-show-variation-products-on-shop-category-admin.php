<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://https://codingkart.com/
 * @since      1.0.0
 *
 * @package    Show_Variation_Products_On_Shop_Category
 * @subpackage Show_Variation_Products_On_Shop_Category/admin
 */
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Show_Variation_Products_On_Shop_Category
 * @subpackage Show_Variation_Products_On_Shop_Category/admin
 * @author     Codingkart <info@codingkart.com>
 */

class Show_Variation_Products_On_Shop_Category_Admin
{
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;
	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	const DEFAULT_COLORS = array(
		'Black' => '#2e2e2e',
		'Caribbean Blue' => '#2a4761',
		'Ceil Blue' => '#8b99d4',
		'Galaxy Blue' => '#2b3a78',
		'Hunter' => '#1a5e5a',
		'Navy' => '#363a5b',
		'Pewter' => '#48474e',
		'Quiet Grey' => '#c0c0c2',
		'Royal Blue' => '#2950a3',
		'Teal' => '#18899e',
		'White' => '#f4f4f4',
		'Wine' => '#612d4c',
	);
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		// Hook to register and initialize settings
		add_action('admin_init', [$this, 'plugin_register_settings']);
		// Register the settings page
		add_action('admin_menu', [$this, 'my_settings_page']);
		add_action('admin_init', [$this, 'my_add_settings_fields']);
		// AJAX handler for adding colors
		add_action('wp_ajax_add_product_colors', [$this, 'add_product_colors']);
		add_action('admin_enqueue_scripts', [$this, 'enqueue_jquery_ui_tooltip']);

	}

	// Enqueue jQuery UI tooltip in your admin dashboard
	function enqueue_jquery_ui_tooltip()
	{
		wp_enqueue_script('jquery-ui-tooltip');
	}
	function add_product_colors()
	{
		if (!isset($_POST['colors']) || !is_array($_POST['colors'])) {
			wp_send_json_error(array('message' => 'Invalid data.'));
		}
		$colors = $_POST['colors'];
		$default_colors = self::DEFAULT_COLORS;
		if (!taxonomy_exists('pa_color')) {
			wp_send_json_error(array('message' => 'Color attribute does not exist.'));
		}
		$errors = [];
		$added = 0;
		foreach ($colors as $color_name) {
			if (isset($default_colors[$color_name])) {
				$color_hex = $default_colors[$color_name];
				if (term_exists($color_name, 'pa_color')) {
					$errors[] = "Color $color_name already exists.";
					continue;
				}
				$result = wp_insert_term($color_name, 'pa_color', array('slug' => sanitize_title($color_name)));
				if (is_wp_error($result)) {
					$errors[] = "Failed to add color $color_name.";
				} else {
					$term_id  = $result['term_id'];
					update_option("taxonomy_term_$term_id", ['color_code' => $color_hex]);
					$added++;
				}
			}
		}
		if ($added > 0) {
			wp_send_json_success(array('message' => "$added colors added successfully.", 'errors' => $errors));
		} else {
			wp_send_json_error(array('message' => 'No colors were added.', 'errors' => $errors));
		}
	}
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Show_Variation_Products_On_Shop_Category_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Show_Variation_Products_On_Shop_Category_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/show-variation-products-on-shop-category-admin.css', array(), $this->version, 'all');
		wp_enqueue_style('jq-ui-css', "https://code.jquery.com/ui/1.13.3/themes/base/jquery-ui.css", array(), $this->version, 'all');
	}
	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Show_Variation_Products_On_Shop_Category_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Show_Variation_Products_On_Shop_Category_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_media();
		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/show-variation-products-on-shop-category-admin.js', array('jquery'), $this->version . "0.8", false);
		wp_localize_script($this->plugin_name, 'WyAdminObj', array(
			'ajax_url' => admin_url('admin-ajax.php'),
		));
	}
	// Function to register and initialize settings
	function plugin_register_settings()
	{
		register_setting('WySMartConnect-settings-group', 'is_WySMartConnect_active');
		register_setting('WySMartConnect-settings-group', 'WySMartConnect_shortcode_text');
		register_setting('WySMartConnect-settings-group', 'WySMartConnect_shortcode_color');
		register_setting('WySMartConnect-settings-group', 'WySMartConnect_shortcode_text_font_size');
		register_setting('WySMartConnect-settings-group', 'WySMartConnect_shop_page_shortcode_title_text_size');
		register_setting('WySMartConnect-settings-group', 'WySMartConnect_icon_url');
		register_setting('WySMartConnect-settings-group', 'WySMartConnect_popup_border_color');
		register_setting('WySMartConnect-settings-group', 'WySMartConnect_include_colors_without_color_code');

		register_setting('WySMartConnect-settings-group', 'WySMartConnect_pro_tip_text');
		register_setting('WySMartConnect-settings-group', 'WySMartConnect_pro_tip_text_color');
		register_setting('WySMartConnect-settings-group', 'WySMartConnect_pro_tip_text_size');

		register_setting('WySMartConnect-settings-group', 'WySMartConnect_shortcode_text_color');
		register_setting('WySMartConnect-settings-group', 'WySMartConnect_shortcode_options_color');

	}
	function my_settings_page()
	{
		add_menu_page('WySMartConnect Settings', 'WySMartConnect', 'manage_options', 'WySMartConnect-setting-page', [$this, 'my_settings_page_html']);
	}
	// Settings page HTML
	function my_settings_page_html()
	{
		$current_tab  = $this->get_current_tab();
		$action  = $current_tab == 'shortcode_settings' ? 'options.php' : ''; ?>
		<div class="wrap">
			<h1>WySMartConnect Settings</h1>
			<h2 class="nav-tab-wrapper">
				<a href="?page=WySMartConnect-setting-page&tab=shortcode_settings" class="nav-tab <?php echo $this->get_current_tab() == 'shortcode_settings' ? 'nav-tab-active' : ''; ?>">Shortcode Settings</a>
				<a href="?page=WySMartConnect-setting-page&tab=product_colors" class="nav-tab <?php echo $this->get_current_tab() == 'product_colors' ? 'nav-tab-active' : ''; ?>">Manage Product Colors </a>
				<a href="?page=WySMartConnect-setting-page&tab=ftp_setting" class="nav-tab <?php echo $this->get_current_tab() == 'ftp_setting' ? 'nav-tab-active' : ''; ?>">FTP Setting </a>
				<a href="?page=WySMartConnect-setting-page&tab=nodejs_config" class="nav-tab <?php echo $this->get_current_tab() == 'nodejs_config' ? 'nav-tab-active' : ''; ?>">NodeJs Config </a>
			</h2>
			<form action="<?= $this->get_current_tab() == 'ftp_setting' ? esc_url(admin_url('admin-post.php'))  : $action ?>" method="post"  <?= $this->get_current_tab() == 'ftp_setting' ? 'id="ftp-settings-form"' : '' ?>>
				<style>
					.info-icon {
						display: inline-block;
						cursor: pointer;
						margin-left: 10px;
						font-size: 12px;
						color: #0073aa;
						width: 15px;
						height: 15px;
						line-height: 13px;
						text-align: center;
						border-radius: 50%;
						display: inline-block;
						border: 1px solid #0073aa;
					}

					.info-icon:hover {
						color: #00a0d2;
						/* WordPress admin blue hover */
						border-color: #00a0d2;
					}
				</style>
				<?php
				// settings_fields('my_settings_group');
				do_settings_sections('WySMartConnect-setting-page');
				// submit_button();
				?>
			</form>
		</div>
		<!-- <script>
			jQuery(document).tooltip();
		</script> -->
<?php }
	// Get current tab
	public  function get_current_tab()
	{
		return isset($_GET['tab']) ? $_GET['tab'] : 'shortcode_settings';
	}
	// Add settings sections and fields
	//
	function my_add_settings_fields()
	{
		$current_tab = $this->get_current_tab();
		if ($current_tab == 'shortcode_settings') {

			add_settings_section('shortcode_settings_section', 'Shortcode Settings', null, 'WySMartConnect-setting-page');

			add_settings_field('shortcode_option', 'Shortcode Options', [$this, 'shortcode_option_callback'], 'WySMartConnect-setting-page', 'shortcode_settings_section');
		} elseif ($current_tab == 'product_colors') {

			add_settings_section('product_colors_section', 'Manage Product Colors ', null, 'WySMartConnect-setting-page');

			add_settings_field('product_colors', 'Product Colors <span class="info-icon" title="These are the default colors, you can add selected colors to the your website (if you want to remove these, you can remove theme from Products -> Attributes -> Color section)">ℹ️</span>', [$this, 'product_colors_callback'], 'WySMartConnect-setting-page', 'product_colors_section');

			add_settings_field('display_popup_colors', 'Select Top Colors <span class="info-icon" title="These selected colors will be displayed first in the color listing popup.">ℹ️</span>', [$this, 'display_popup_colors_callback'], 'WySMartConnect-setting-page', 'product_colors_section');
		} else if ($current_tab == 'ftp_setting') {
			add_settings_section('ftp_setting_section', 'FTP Settings', [$this, 'ftp_setting_callback'], 'WySMartConnect-setting-page');
		} else if ($current_tab == 'nodejs_config') {
			add_settings_section('nodejs_config_section', 'NodeJs Config', [$this, 'nodejs_config_callback'], 'WySMartConnect-setting-page');
		}
	}

	function ftp_setting_callback()
	{
		// include(WYSMart_plugin_path . "/modules/admin-module/templates/custom_ftp_data_save.php");
		$ftpdata = ck_admin_module_obj()->get_manufacturer_ftp_data();

		$data = array(
			'ftpdata' => $ftpdata,
		);
		echo ck_ftphelper_object()->ck_get_template('custom_ftp_data_save.php', 'admin-module', $data, true);
	}


	function nodejs_config_callback()
	{

		// Check if the form is submitted
		if (isset($_POST['ftp_url_nonce']) && wp_verify_nonce($_POST['ftp_url_nonce'], 'save_ftp_url')) {
			if (isset($_POST['ftp_url_endpoint'])) {
				$ftp_url = sanitize_text_field($_POST['ftp_url_endpoint']);
				update_option('ftp_url_endpoint', $ftp_url); // Save the URL to options table
				echo '<div class="updated"><p>FTP URL Saved!</p></div>';
			}
		}
		//include(WYSMart_plugin_path . "/modules/admin-module/templates/ftp_url_save.php");
		// Get the current FTP URL
		$current_url = get_option('ftp_url_endpoint', '');

		$data = array(
			'current_url' => $current_url,
		);

		echo ck_ftphelper_object()->ck_get_template('ftp_url_save.php', 'admin-module', $data);
	}

	function handle_colors_setting_sumit()
	{
		if (isset($_POST['save_popup_colors'])) {
			$popup_colors  = $_POST['popup_colors'];
			// print_r($popup_colors);
			update_option('popup_colors', $popup_colors);
		}
	}
	function display_popup_colors_callback()
	{
		$this->handle_colors_setting_sumit();
		$existing_terms = get_terms(array(
			'taxonomy' => 'pa_color',
			'hide_empty' => true,
		));
		if (!empty($existing_terms)) {
			$popup_colors  = get_option('popup_colors', array());
			echo '<table>';
			foreach ($existing_terms as $color) {
				$term_meta = get_option("taxonomy_term_$color->term_id");
				$color_code = isset($term_meta['color_code']) ? $term_meta['color_code'] : '';
				if ($color_code) {
					$checked = in_array($color->slug, $popup_colors) ? 'checked' : '';
					echo '<tr>';
					echo '<td><input type="checkbox" name="popup_colors[]" value="' . esc_attr($color->slug) . '"' . $checked . ' /></td>';
					echo '<td>' . $color->name . ' - ' . $color_code . '</td>';
					echo '</tr>';
				}
			}
			echo '</table>';
			echo '<button type="submit" class="button" name="save_popup_colors" id="save_popup_colors">Save</button>';
		} else {
			echo '<p>No colors found.</p>';
		}
	}
	// Shortcode option callback
	function shortcode_option_callback()
	{
		include(plugin_dir_path(__FILE__) . "/partials/WySMartConnect_settings_page_template.php");
	}
	// Product colors callback
	public function product_colors_callback()
	{
		// Get existing terms in the color attribute
		$existing_terms = get_terms(array(
			'taxonomy' => 'pa_color',
			'hide_empty' => false,
		));
		$existing_colors = array();
		if (!is_wp_error($existing_terms)) {
			foreach ($existing_terms as $term) {
				$existing_colors[strtolower($term->name)] = true;
			}
		}
		$all_disabled = true; // Assume all checkboxes are disabled
		echo '<table>';
		foreach (self::DEFAULT_COLORS as $color_name => $hex) {
			$disabled = isset($existing_colors[strtolower($color_name)]) ? 'disabled' : '';
			if ($disabled === '') {
				$all_disabled = false; // At least one checkbox is not disabled
			}
			echo '<tr>';
			echo '<td><input type="checkbox" id="' . strtolower(str_replace(' ', '_', $color_name)) . '" name="color_checkbox[]" value="' . esc_attr($color_name) . '" ' . $disabled . ' /></td>';
			echo '<td>' . $color_name . ' - ' . $hex . '</td>';
			echo '</tr>';
		}
		echo '</table>';
		$button_disabled = $all_disabled ? 'disabled' : '';
		echo '<button type="button" class="button" id="add-colors"' . $button_disabled . '>Add Selected Colors to WooCommerce</button>';
		echo '<div id="show-available-colors-section"><a href="#" id="show-available-colors">Show Available Colors on Website</a> <span class="info-icon" title=" This is a view only option, it will show a list of all colors in website/admin (you can edit them from the Products -> Attributes -> Color section) ">ℹ️</span></div>';
		echo '<div id="available-colors-list" style="display: none;">';
		echo '<h3>Available Colors:</h3>';
		// Call function to get available colors and display them
		echo $this->get_available_colors_html();
		echo '</div>';
	}
	// Call function to get available colors and display them
	function get_available_colors_html()
	{
		// Get all terms under the 'pa_color' taxonomy
		$colors = get_terms(array(
			'taxonomy' => 'pa_color',
			'hide_empty' => false, // Include terms with no posts
		));
		// Check if any terms were found
		if (!empty($colors)) {
			// Create HTML for the table of available colors
			$html = '<table>';
			$html .= '<tr><th>Color Name</th><th>Color Code</th></tr>';
			foreach ($colors as $color) {
				$term_id = $color->term_id;
				$term_meta = get_option("taxonomy_term_$term_id");
				$color_code = isset($term_meta['color_code']) ? $term_meta['color_code'] : '';
				$html .= '<tr>';
				$html .= '<td>' . $color->name . '</td>';
				$html .= '<td>' . $color_code . '</td>';
				$html .= '</tr>';
			}
			$html .= '</table>';
			// Return the HTML
			return $html;
		} else {
			return '<p>No colors found.</p>';
		}
	}
}
