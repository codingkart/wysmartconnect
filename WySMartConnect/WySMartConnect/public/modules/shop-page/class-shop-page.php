<?php



/**

 * @class    global-moduel

 * @category Class

 * @author   Ganesh pawar

 * */

class ShopModule

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

        add_action('woocommerce_after_shop_loop_item', [$this, 'show_woocommerce_brands_loop'], 10);
    }



    function show_woocommerce_brands_loop()

    {

        global $post;

        if (function_exists('get_brands')) {
            if ($this->get_parent_or_variation_id($post->ID)) {
                echo get_brands($this->get_parent_or_variation_id($post->ID));
            }
        }
    }

    function get_parent_or_variation_id($product_id)

    {

        // Check if the product is a variation

        if (function_exists('wc_get_product') && wc_get_product($product_id) && wc_get_product($product_id)->is_type('variation')) {

            // If it's a variation, return the parent product ID

            return wc_get_product($product_id)->get_parent_id();
        }
    }
} //end class



function shop_module_object()

{

    return ShopModule::get_instance();
}



shop_module_object();
