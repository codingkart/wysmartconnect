<?php



/**

 * @class    global-moduel

 * @category Class

 * @author   Ganesh pawar

 * */

class globalModuel

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

        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles_scripts'));

        add_shortcode('shop_color_shortcode', array($this, 'get_shop_color_popup_html'));

        // Register the shortcode

        add_shortcode('WySMart_shop_by_color_section', [$this, 'shop_by_color_section_callback']);

    }

    function shop_by_color_section_callback()

    {

        // Retrieve shortcode text option

        $shortcode_text = (get_option('WySMartConnect_shortcode_text')) ? get_option('WySMartConnect_shortcode_text') : "Shop By Color";

        // Retrieve icon URL option

        $icon_url = get_option('WySMartConnect_icon_url') ? get_option('WySMartConnect_icon_url') : plugin_dir_url(__FILE__) . 'images/ShopByColor_icon.jpg';

        // HTML content to be displayed

        // if (taxonomy_exists('pa_size') || taxonomy_exists('pa_color')) {

        $html = '<div class="shopByColorContainer"><a class="shopByColor" href="javascript:void(0);">

        <img src="' . $icon_url . '" alt="">

        <span class="shop-by-color-title">' . $shortcode_text . '</span><i class="sci-arrow-drop-down"></i></a>' . do_shortcode('[shop_color_shortcode]') . '</div>';

        // }

        // Output the HTML content

        echo $html;

    }

    /* ----------------------------------------------------- */

    /* Enqueue stylesheets javascript only for this module only */

    /* ----------------------------------------------------- */

    public function enqueue_styles_scripts()

    {

        /*  Style Sheets */

        wp_enqueue_style('global_module_style', plugin_dir_url(__FILE__) . '/css/style.css', time()."00");

        wp_enqueue_style('global_module_responsive_style', plugin_dir_url(__FILE__) . '/css/responsive-style.css', time());

        wp_enqueue_style('magnific_popup_style', plugin_dir_url(__FILE__) . '/css/magnific-popup.css', time());

        /*  Java Scripts */

        wp_enqueue_script('magnific_popup_script', plugin_dir_url(__FILE__) . '/js/jquery.magnific-popup.min.js', array('jquery'));

        wp_enqueue_script('global_script', plugin_dir_url(__FILE__) . '/js/script.js', array('jquery'),time(), true);

        //Localize the scripts

        wp_localize_script('global_script', 'globalModuleObj', ['ajaxurl' => admin_url('admin-ajax.php'), 'top_colors' => get_option('popup_colors', array())]);

    }

    /**

     * Shortcode function for displaying Pricing section.

     *

     * @return string

     */

    public function get_shop_color_popup_html()

    {

        if (taxonomy_exists('pa_color')) {

            $other_colors = $this->get_color_attributes_excluding_top_colors();

            $top_colors = $this->get_top_color_attributes();

        } else {

            $other_colors = [];

            $top_colors = [];

        }

        // $color_array = array_merge($top_colors, $other_colors);
        $color_array = $top_colors;

        
        $shortcode_header_bg_color = (get_option('WySMartConnect_popup_border_color')) ? get_option('WySMartConnect_popup_border_color') : "#000000";
        
        $shortcode_text = (get_option('WySMartConnect_shortcode_text')) ? get_option('WySMartConnect_shortcode_text') : "Shop By Color";
        $shortcode_text_color = (get_option('WySMartConnect_shortcode_color')) ? get_option('WySMartConnect_shortcode_color') : "#000000";
        $shortcode_text_size = (get_option('WySMartConnect_shortcode_text_font_size')) ? get_option('WySMartConnect_shortcode_text_font_size') : "18";
        $shop_page_shortcode_title_text_size = (get_option('WySMartConnect_shop_page_shortcode_title_text_size')) ? get_option('WySMartConnect_shop_page_shortcode_title_text_size') : "18";

        $pro_tip_text = (get_option('WySMartConnect_pro_tip_text')) ? get_option('WySMartConnect_pro_tip_text') : "These are universal colors, and to be sure that your items match we recommend ordering from the same Collection and Brand to make certain your scrubs match.";
        $pro_tip_text_color = (get_option('WySMartConnect_pro_tip_text_color')) ? get_option('WySMartConnect_pro_tip_text_color') : "#000000";
        $pro_tip_text_size = (get_option('WySMartConnect_pro_tip_text_size')) ? get_option('WySMartConnect_pro_tip_text_size') : "16";

        $WySMartConnect_shortcode_text_color = (get_option('WySMartConnect_shortcode_text_color')) ? get_option('WySMartConnect_shortcode_text_color') : "#000000";
        $WySMartConnect_shortcode_options_color = (get_option('WySMartConnect_shortcode_options_color')) ? get_option('WySMartConnect_shortcode_options_color') : "#000000";

        ob_start();

        echo ck_helper_object()->ck_get_template('shop-by-color.php', 'global-module', array('colors' => $color_array, 'top_colors' => $top_colors, 'shortcode_text' => $shortcode_text, 'shortcode_header_bg_color' => $shortcode_header_bg_color,'shortcode_text_color' => $shortcode_text_color,'shortcode_text_size' => $shortcode_text_size, 'shop_page_shortcode_title_text_size' => $shop_page_shortcode_title_text_size, 'pro_tip_text_color'=>$pro_tip_text_color,'pro_tip_text_size'=>$pro_tip_text_size,'pro_tip_text'=>$pro_tip_text,'shortcode_options_color'=>$WySMartConnect_shortcode_options_color, 'WySMartConnect_shortcode_text_color'=>$WySMartConnect_shortcode_text_color) ,true);

        return ob_get_clean();

    }

    function get_top_color_attributes()

    {

        // Get the terms with their IDs

        $top_colors = get_option('popup_colors', array());

        $hide_empty = true;
        if (empty($top_colors)) {
            $top_colors = array('black', 'caribbean-blue', 'ceil-blue', 'galaxy-blue', 'hunter', 'navy', 'pewter', 'quiet-grey', 'royal-blue', 'teal', 'white', 'wine');
            $hide_empty = false;
        }

        $terms = get_terms(array(

            'taxonomy' => 'pa_color',

            'slug' => $top_colors,

            'hide_empty' => $hide_empty,

        ));

        return $this->get_colors_for_display($terms);

    }

    function get_filtered_terms()

    {

        // Define the slugs of the colors you want to exclude

        $exclude_slugs = get_option('popup_colors', array());

        // Get the terms with their IDs

        $terms_to_exclude = get_terms(array(

            'taxonomy' => 'pa_color',

            'slug' => $exclude_slugs,

            'fields' => 'ids',

            'hide_empty' => false,

        ));

        // Get the terms excluding the specified terms by IDs

        $terms = get_terms(array(

            'taxonomy' => 'pa_color',

            'hide_empty' => true, // Set to true if you want to exclude empty terms

            'exclude' => $terms_to_exclude,

        ));

        return $terms;

    }

    /**

     * Retrieves the color attributes from the 'pa_color' taxonomy.

     *

     * This function queries the 'pa_color' taxonomy and retrieves the color attributes

     * associated with it. Each color attribute is represented as an array containing

     * the name, slug, and color code of the attribute.

     *

     * @return array An array of color attributes.

     */

    function get_color_attributes_excluding_top_colors()

    {

        $terms = $this->get_filtered_terms();

        return $this->get_colors_for_display($terms);

    }

    function get_colors_for_display($terms)

    {

        $color_attributes = array();

        $include_all = get_option('WySMartConnect_include_colors_without_color_code', 0);

        foreach ($terms as $term) {

            $term_meta = get_option("taxonomy_term_" . $term->term_id);

            $color_code = isset($term_meta['color_code']) ? $term_meta['color_code'] : '';



            if ($include_all || $color_code) {

                $color_attributes[] = array(

                    'name' => $term->name,

                    'slug' => $term->slug,

                    'color_code' => $color_code,

                );

            }

        }

        return $color_attributes;

    }

} //end class

function global_moduel_object()

{

    return globalModuel::get_instance();

}

global_moduel_object();