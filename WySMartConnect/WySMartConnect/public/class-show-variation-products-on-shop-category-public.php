<?php



/**

 * The public-facing functionality of the plugin.

 *

 * @link       https://https://codingkart.com/

 * @since      1.0.0

 *

 * @package    Show_Variation_Products_On_Shop_Category

 * @subpackage Show_Variation_Products_On_Shop_Category/public

 */



/**

 * The public-facing functionality of the plugin.

 *

 * Defines the plugin name, version, and two examples hooks for how to

 * enqueue the public-facing stylesheet and JavaScript.

 *

 * @package    Show_Variation_Products_On_Shop_Category

 * @subpackage Show_Variation_Products_On_Shop_Category/public

 * @author     Codingkart <info@codingkart.com>

 */

class Show_Variation_Products_On_Shop_Category_Public

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



	/**

	 * Initialize the class and set its properties.

	 *

	 * @since    1.0.0

	 * @param      string    $plugin_name       The name of the plugin.

	 * @param      string    $version    The version of this plugin.

	 */

	public function __construct($plugin_name, $version)

	{



		$this->plugin_name = $plugin_name;

		$this->version = $version;



		//modify product query

		add_action('woocommerce_product_query', array($this, 'modify_woocommerce_product_query'), 999);



		add_filter('posts_clauses', array($this, 'modify_post_clauses'), 999, 2);



		//modify widget attribute count query

		add_filter('woocommerce_get_filtered_term_product_counts_query', [$this, 'modify_wc_widget_attribute_count_query'], 99);

		add_action('init', [$this, 'init']);

		// Function to print the SQL query

		// add_filter('posts_request', [$this, 'print_query'], 999, 1);

	}

	public static function init()

	{
		global $wpdb;
		// $args = array(

		// 	'post_type' => 'product_variation',

		// 	'posts_per_page' => 100,

		// 	'meta_query' => array(

		// 		array(

		// 			'key' => 'ck_filters_',

		// 			'value' => 'yes',

		// 			'compare' => 'NOT EXISTS'

		// 		)

		// 	),

		// );

		// $the_query = new WP_Query($args);

		// if ($the_query->have_posts()) {

		// 	while ($the_query->have_posts()) {



		// 		$the_query->the_post();

		// 		global $post;

		// 		$variation_id = $post->ID;

		// 		$producta = wc_get_product($variation_id);

		// 		foreach ($producta->get_variation_attributes() as $taxonomya => $terms_sluga) {

		// 			wp_set_post_terms($variation_id, $terms_sluga, ltrim($taxonomya, 'attribute_'));
		// 		}

		// 		$parent_product_id = wp_get_post_parent_id($variation_id);

		// 		if ($parent_product_id) {



		// 			$taxonomies = array(

		// 				'product_cat',

		// 				'product_tag'

		// 			);

		// 			foreach ($taxonomies as $taxonomy) {

		// 				$terms = (array) wp_get_post_terms($parent_product_id, $taxonomy, array("fields" => "ids"));

		// 				wp_set_post_terms($variation_id, $terms, $taxonomy);
		// 			}

		// 			if (!metadata_exists('post', $variation_id, 'ck_filters_')) {

		// 				update_post_meta($variation_id, 'ck_filters_', 'no');
		// 			}
		// 		}
		// 	}
		// }

		$query = "
			SELECT {$wpdb->prefix}posts.ID 
			FROM {$wpdb->prefix}posts 
			WHERE {$wpdb->prefix}posts.post_type = 'product_variation' 
			AND ({$wpdb->prefix}posts.post_status = 'publish' OR {$wpdb->prefix}posts.post_status = 'private') 
			AND NOT EXISTS (
				SELECT 1 
				FROM {$wpdb->prefix}postmeta 
				WHERE {$wpdb->prefix}postmeta.post_id = {$wpdb->prefix}posts.ID 
				AND {$wpdb->prefix}postmeta.meta_key = 'ck_filters_'
			) 
			ORDER BY {$wpdb->prefix}posts.post_date DESC 
			LIMIT 0, 100
		";

		$variation_ids = $wpdb->get_col($query);

		if (!empty($variation_ids)) {
			foreach ($variation_ids as $variation_id) {
				$producta = wc_get_product($variation_id);

				foreach ($producta->get_variation_attributes() as $taxonomya => $terms_sluga) {
					wp_set_post_terms($variation_id, $terms_sluga, ltrim($taxonomya, 'attribute_'));
				}

				$parent_product_id = wp_get_post_parent_id($variation_id);

				if ($parent_product_id) {
					$taxonomies = array('product_cat', 'product_tag');
					foreach ($taxonomies as $taxonomy) {
						$terms = (array) wp_get_post_terms($parent_product_id, $taxonomy, array("fields" => "ids"));
						wp_set_post_terms($variation_id, $terms, $taxonomy);
					}

					if (!metadata_exists('post', $variation_id, 'ck_filters_')) {
						update_post_meta($variation_id, 'ck_filters_', 'no');
					}
				}
			}
		}
	}

	/**

	 * Register the stylesheets for the public-facing side of the site.

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



		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/show-variation-products-on-shop-category-public.css', array(), $this->version, 'all');
	}



	/**

	 * Register the JavaScript for the public-facing side of the site.

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



		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/show-variation-products-on-shop-category-public.js', array('jquery'), $this->version, false);
	}

	/**

	 * Filters the WooCommerce product query based on the 'filter_color' query variable.

	 *

	 * @param WP_Query $q The WP_Query object representing the current query.

	 * @return WP_Query The modified WP_Query object.

	 */

	public function modify_woocommerce_product_query($q)

	{



		if (isset($q->query_vars['filter_color']) || isset($q->query_vars['filter_size'])) {



			$q->set('post_type', array('product', 'product_variation'));

			//set post status publish



		}

		return $q;
	}



	/**

	 * Modifies the post clauses based on the given query variables.

	 *

	 * @param array $clauses The original post clauses.

	 * @param object $query The WP_Query object.

	 * @return array The modified post clauses.

	 */

	public function modify_post_clauses($clauses, $query)

	{

		global $wpdb;

		if (isset($query->query_vars['filter_color']) || isset($query->query_vars['filter_size'])) {



			$clauses['where'] .= " AND  0 = (select count(*) as totalpart from {$wpdb->posts} as oc_posttb where oc_posttb.post_parent = {$wpdb->posts}.ID and oc_posttb.post_type= 'product_variation') ";

			// $clauses['where'] .= " ";

			$clauses['join'] .= " LEFT JOIN {$wpdb->postmeta} as  oc_posttba ON ({$wpdb->posts}.post_parent = oc_posttba.post_id )";





			$clauses['where'] .= " AND (

				{$wpdb->posts}.post_parent IN (

				  SELECT ID 

				  FROM {$wpdb->posts} 

				  WHERE post_type = 'product' 

				  AND post_status = 'publish'

				)

			  ) ";

			$clauses['groupby'] = "{$wpdb->term_relationships}.term_taxonomy_id, {$wpdb->posts}.post_parent";
		}



		return $clauses;
	}

	/**

	 * Modify WooCommerce attribute widget to include product variations count.

	 */

	function modify_wc_widget_attribute_count_query($query_args)

	{

		global $wpdb;

		$query_args['where'] = str_replace(

			"post_type IN ( 'product' )",

			"post_type IN ( 'product_variation')",

			$query_args['where']

		);

		$query_args['select'] = str_replace(

			"DISTINCT {$wpdb->posts}.ID",

			"DISTINCT {$wpdb->posts}.post_parent",

			$query_args['select']

		);



		$query_args['where'] .= " AND (

			{$wpdb->posts}.post_parent IN (

			  SELECT ID 

			  FROM {$wpdb->posts} 

			  WHERE post_type = 'product' 

			  AND post_status = 'publish'

			)

		  ) ";



		return $query_args;
	}

	function print_query($request)

	{

		global $wp_query;



		// Check if 'filter_color' is set in query vars

		if (isset($wp_query->query_vars['filter_color']) || isset($wp_query->query_vars['filter_size'])) {

			echo "<pre>";

			print_r($request);

			echo "</pre>";
		}



		return $request;
	}
}
