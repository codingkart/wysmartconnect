<?php



/**

 * @class    global-moduel

 * @category Class

 * @author   Ganesh pawar

 * */

class ProductPageModule

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

        // Hook into WordPress initialization to remove WooCommerce actions

        add_action('init', array($this, 'remove_woocommerce_actions'), 99); // Use a high priority to ensure it runs after WooCommerce initializes

        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles_scripts'));

        add_action('wp_ajax_update_related_and_upsell_product_section', array($this, 'update_related_and_upsell_product_section'));

        add_action('wp_ajax_nopriv_update_related_and_upsell_product_section', array($this, 'update_related_and_upsell_product_section'));



        // Remove actions from woocommerce_after_single_product_summary hook

        add_action('woocommerce_after_single_product_summary', [$this, 'custom_woocommerce_upsell_and_related'], 99);

    }

    public function remove_woocommerce_actions()

    {

        // Remove actions from woocommerce_after_single_product_summary hook

        // remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);

        remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);

        remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);

    }

    function custom_woocommerce_upsell_and_related()

    {

        echo ProductPageModuleObj()->woocommerce_upsell_display_custom();

        echo ProductPageModuleObj()->woocommerce_related_products_custom();

    }



    /* ----------------------------------------------------- */

    /* Enqueue stylesheets javascript only for this module only */

    /* ----------------------------------------------------- */

    public function enqueue_styles_scripts()

    {

        if (is_product()) {



            /*  Style Sheets */

            wp_enqueue_style('product_page_style', plugin_dir_url(__FILE__) . '/css/product_page_style.css', time());



            /*  Java Scripts */

            wp_enqueue_script('product_page_script', plugin_dir_url(__FILE__) . '/js/product_page_script.js', array('jquery'));

        }

    }

    function update_related_and_upsell_product_section()

    {

        $product_id = $_GET['product_id'];

        $related_products = $this->woocommerce_related_products_custom($product_id);

        wp_send_json(['related_products_html' => $related_products, 'upsell_products_html' => $this->woocommerce_upsell_display_custom($product_id)]);

        wp_die();

    }

    function woocommerce_upsell_display_custom($product_id = 0)

    {

        $limit = '-1';

        $columns = 4;

        $orderby = 'rand';

        $order = 'desc';



        if ($product_id) {



            $product = wc_get_product($product_id);

        } else {

            global $product;

        }



        if (!$product) {

            return;

        }



        // Get URL parameters for color and size

        $color = isset($_GET['attribute_pa_color']) ? sanitize_text_field($_GET['attribute_pa_color']) : '';

        $size = isset($_GET['attribute_pa_size']) ? sanitize_text_field($_GET['attribute_pa_size']) : '';

        if (!empty($color) && !empty($size)) {



            // Get upsell product IDs

            $upsell_ids = $product->get_upsell_ids();



            // Initialize array to store upsell variations

            $upsells = array();



            // Loop through upsell product IDs

            foreach ($upsell_ids as $upsell_id) {

                $upsell_product = wc_get_product($upsell_id);



                // Check if upsell product is variable

                if ($upsell_product && $upsell_product->is_type('variable')) {

                    // Get variation ID based on selected attributes

                    $variation_id = $upsell_product->get_matching_variation(array(

                        'attribute_pa_color' => $color,

                        'attribute_pa_size' => $size,

                    ));



                    // If variation ID is found, get variation product data

                    if ($variation_id) {

                        $variation_product = wc_get_product($variation_id);



                        // Add variation product to upsells array

                        if ($variation_product) {

                            $upsells[] = $variation_product;

                        }

                    }

                }

            }



            // Handle orderby

            $upsells = wc_products_array_orderby($upsells, $orderby, $order);



            // Limit result set

            $upsells = $limit > 0 ? array_slice($upsells, 0, $limit) : $upsells;



            // Set global loop values

            wc_set_loop_prop('name', 'up-sells');

            wc_set_loop_prop('columns', apply_filters('woocommerce_upsells_columns', $columns));

            // Start output buffering

            ob_start();

            // Display upsell variations

            wc_get_template(

                'single-product/up-sells.php',

                array(

                    'upsells'        => $upsells,

                    'posts_per_page' => $limit,

                    'orderby'        => $orderby,

                    'columns'        => $columns,

                )

            );

            // Get the contents of the output buffer and store it in a variable

            $output_html = ob_get_clean();

            // return the HTML

            return $output_html;

        } else {

            woocommerce_upsell_display();

        }

    }



    /**

     * Retrieve related product variations based on selected color and size.

     *

     * @param object $product The current product object.

     * @param string $color The selected color attribute.

     * @param string $size The selected size attribute.

     * @param array $args Additional arguments for related products.

     * @return array An array of related product variations.

     */

    function get_related_variations($product, $color, $size, $args)

    {

        // Get related product IDs

        $related_ids = wc_get_related_products($product->get_id(), $args['posts_per_page'], $product->get_upsell_ids());



        // Initialize array to store related product variations

        $related_variations = array();



        // Loop through related product IDs

        foreach ($related_ids as $related_id) {

            $related_product = wc_get_product($related_id);



            // Check if related product is variable

            if ($related_product && $related_product->is_type('variable')) {

                // Get variation ID based on selected attributes

                $variation_id = $related_product->get_matching_variation(array(

                    'attribute_pa_color' => $color,

                    'attribute_pa_size' => $size,

                ));



                // If variation ID is found, get variation product data

                if ($variation_id) {

                    $variation_product = wc_get_product($variation_id);



                    // Add variation product to related variations array

                    if ($variation_product) {

                        $related_variations[] = $variation_product;

                    }

                }

            }

        }



        return $related_variations;

    }



    /**

     * Output related product variations HTML.

     *

     * @param array $related_variations An array of related product variations.

     * @param array $args Additional arguments for related products.

     */

    function output_related_variations_html($related_variations, $args)

    {

        // Handle orderby

        shuffle($related_variations); // Shuffle the related variations



        // Set global loop values

        wc_set_loop_prop('name', 'related');

        wc_set_loop_prop('columns', apply_filters('woocommerce_related_products_columns', $args['columns']));



        // Start output buffering

        ob_start();



        // Include template and pass variables

        wc_get_template('single-product/related.php', array('related_products' => $related_variations));



        // Get the contents of the output buffer and store it in a variable

        $output_html = ob_get_clean();



        // Output the HTML

        return $output_html;

    }



    /**

     * Main function to retrieve and display related product variations.

     *

     * @param array $args Additional arguments for related products.

     */

    function woocommerce_related_products_custom($product_id = 0)

    {

        $args = array(
            'posts_per_page' => 9,
        );



        if ($product_id) {



            $product = wc_get_product($product_id);

        } else {



            global $product;

        }

        if (!$product || !is_object($product)) {

            return;

        }



        // Get URL parameters for color and size

        $color = isset($_GET['attribute_pa_color']) ? sanitize_text_field($_GET['attribute_pa_color']) : '';

        $size = isset($_GET['attribute_pa_size']) ? sanitize_text_field($_GET['attribute_pa_size']) : '';



        // Check if both color and size are provided

        if (!empty($color) && !empty($size)) {

            // Retrieve related product variations

            $related_variations = $this->get_related_variations($product, $color, $size, $args);



            // Output related product variations HTML

            return $this->output_related_variations_html($related_variations, $args);

        } else {
            $defaults = array(
                'columns'        => 4,
                'orderby'        => 'rand', // @codingStandardsIgnoreLine.
                'order'          => 'desc',
            );
            $args = wp_parse_args( $args, $defaults );
            // If color or size is not provided, fallback to default related products

            woocommerce_related_products($args);

        }

    }

} //end class



function ProductPageModuleObj()

{

    return ProductPageModule::get_instance();

}



ProductPageModuleObj();

