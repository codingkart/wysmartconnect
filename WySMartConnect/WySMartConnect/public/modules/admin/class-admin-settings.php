<?php
/**
 * @class    global-moduel
 * @category Class
 * @author   Ganesh pawar
 * */

class adminModule
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
        add_action('pa_color_add_form_fields', [$this, 'add_color_code_field_to_pa_color'], 10, 2);
        add_action('edited_pa_color', [$this, 'save_color_code_field'], 10, 2);
        add_action('create_pa_color', [$this, 'save_color_code_field'], 10, 2);
        add_action('pa_color_edit_form_fields', [$this, 'display_color_code_field'], 10, 2);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_custom_admin_scripts']);
    }
    // Add color field to attribute term form
    function enqueue_custom_admin_scripts()
    {
        wp_enqueue_script('admin-script', plugin_dir_url(__FILE__).'/js/custom-script.js', array('jquery'), '1.0', true);
    }
    // Add custom field to taxonomy term
    function add_color_code_field_to_pa_color()
    {
?>
        <div class="form-field">
            <label for="term_meta[color_code]"><?php _e('Color Code', 'text_domain'); ?></label>
            <input type="color" class="color_preview" id="term_meta[color_code]" value="">
            <input type="text"  name="term_meta[color_code]"  class="color_code_text" value="">
            <p class="description"><?php _e('Enter the color code for this term (e.g., #FF0000)', 'text_domain'); ?></p>
        </div>
    <?php
    }
    // Save custom field
    function save_color_code_field($term_id)
    {
        if (isset($_POST['term_meta'])) {
            $term_meta = get_option("taxonomy_term_$term_id");
            $cat_keys = array_keys($_POST['term_meta']);
            foreach ($cat_keys as $key) {
                if (isset($_POST['term_meta'][$key])) {
                    $term_meta[$key] = $_POST['term_meta'][$key];
                }
            }
            // Save the option array.
            update_option("taxonomy_term_$term_id", $term_meta);
        }
    }
    // Display custom field value
    function display_color_code_field($term)
    {
        $term_id = $term->term_id;
        $term_meta = get_option("taxonomy_term_$term_id");
        $color_code = isset($term_meta['color_code']) ? $term_meta['color_code'] : '';
    ?>
        <tr class="form-field">
            <th scope="row" valign="top"><label for="term_meta[color_code]"><?php _e('Color Code', 'text_domain'); ?></label></th>
            <td>
                <input type="color" class="color_preview"id="term_meta[color_code]" value="<?php echo esc_attr($color_code); ?>">
                <input type="text" class="color_code_text"  name="term_meta[color_code]"  value="<?php echo esc_attr($color_code); ?>">
                <p class="description"><?php _e('Color code for this term (e.g., #FF0000)', 'text_domain'); ?></p>
            </td>
        </tr>
<?php
    }
} //end class

function admin_module_obj()
{
    return adminModule::get_instance();
}

admin_module_obj();
