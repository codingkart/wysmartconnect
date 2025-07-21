<!-- <h2>Shortcode Settings</h2> -->

<?php

// var_dump($color_code);

?>

<form method="post" action="options.php">

    <div valign="top">

        <div scope="row">

            <div style="padding: 20px 10px 20px 0;">

                <strong>Enable Plugin: <span class="info-icon" title="uncheck this checkbox to disable the plugin functionality from website(by default it will be checked and enabled)">ℹ️</span></strong>

                <input type="checkbox" style="margin-left: 20px;" name="is_WySMartConnect_active" value="yes" <?php checked(get_option('is_WySMartConnect_active', 'yes'), 'yes'); ?> />

            </div>

        </div>


    </div>

    <p><strong>Shortcode: </strong><strong>[WySMart_shop_by_color_section]</strong></p>

    <?php settings_fields('WySMartConnect-settings-group'); ?>

    <?php do_settings_sections('WySMartConnect-settings-group'); ?>

    <table class="form-table">

        <tr valign="top">

            <th scope="row">Shortcode Text Title <span class="info-icon" title="This will appear after the shortcode icon.">ℹ️</span></th>

            <td>

                <input type="text" name="WySMartConnect_shortcode_text" value="<?php echo esc_attr(get_option('WySMartConnect_shortcode_text', 'Shop by Color')); ?>" />

            </td>

        </tr>

        <tr>
            <th scope="row">Shortcode Text Title Color (popup title) <span class="info-icon" title="This will be the shortcode text color. (popup title)">ℹ️</span></th>

            <td>

                <input type="color" class="color_preview2" name="WySMartConnect_shortcode_color" value="<?php echo esc_attr(get_option('WySMartConnect_shortcode_color', '#000')); ?>" />
                <input type="text" class="color_code_text2" value="<?php echo esc_attr(get_option('WySMartConnect_shortcode_color', '#000')); ?>">

            </td>
        </tr>

        <tr valign="top">

            <th scope="row">Shortcode Text Title Color (shop filter)<span class="info-icon" title="This will be shortcode text title color.">ℹ️</span></th>

            <td>

                <!-- <input type="text" name="WySMartConnect_shortcode_text_color" value="<?php //echo esc_attr(get_option('WySMartConnect_shortcode_text_color')); ?>" /> -->

                <input type="color" class="color_preview5" name="WySMartConnect_shortcode_text_color" value="<?php echo esc_attr(get_option('WySMartConnect_shortcode_text_color', '#000')); ?>" />
                <input type="text" class="color_code_text5" value="<?php echo esc_attr(get_option('WySMartConnect_shortcode_text_color', '#000')); ?>">

            </td>

        </tr>
        
        <tr>
            <th scope="row">Shortcode Text Font Size (shop filter) <span class="info-icon" title="This will be the shortcode text font size in pixels(px).">ℹ️</span>
            </th>

            <td>

                <input type="number" name="WySMartConnect_shop_page_shortcode_title_text_size" value="<?php echo esc_attr(get_option('WySMartConnect_shop_page_shortcode_title_text_size', '')); ?>" />px

                

            </td>
        </tr>

        <tr valign="top">

            <th scope="row">Shortcode options font color (shortcode)<span class="info-icon" title="This will be options font color.">ℹ️</span></th>

            <td>

                <!-- <input type="text" name="WySMartConnect_shortcode_options_color" value="<?php //echo esc_attr(get_option('WySMartConnect_shortcode_options_color')); ?>" /> -->

                <input type="color" class="color_preview4" name="WySMartConnect_shortcode_options_color" value="<?php echo esc_attr(get_option('WySMartConnect_shortcode_options_color', '#000')); ?>" />
                <input type="text" class="color_code_text4" value="<?php echo esc_attr(get_option('WySMartConnect_shortcode_options_color', '#000')); ?>">

            </td>

        </tr>

        <tr>
            <th scope="row">Shortcode Text Font Size <span class="info-icon" title="This will be the shortcode text font size in pixels(px).">ℹ️</span>
            </th>

            <td>

                <input type="number" name="WySMartConnect_shortcode_text_font_size" value="<?php //echo esc_attr(get_option('WySMartConnect_shortcode_text_font_size', '')); ?>" />px

                

            </td>
        </tr>

        <tr valign="top">

            <th scope="row">Pro Tip Text <span class="info-icon" title="This will appear after the shortcode text in the popup.">ℹ️</span></th>

            <td>

                <textarea style="height: 75px; width: 50%;" type="text" name="WySMartConnect_pro_tip_text" value="<?php echo esc_attr(get_option('WySMartConnect_pro_tip_text', 'These are universal colors, and to be sure that your items match we recommend ordering from the same Collection and Brand to make certain your scrubs match.')); ?>" >
                <?php echo (get_option('WySMartConnect_pro_tip_text', 'These are universal colors, and to be sure that your items match we recommend ordering from the same Collection and Brand to make certain your scrubs match.')); ?>
                </textarea>

            </td>

        </tr>

        <tr>
            <th scope="row">Pro Tip Text Color <span class="info-icon" title="This will be the Pro Tip Text text color.">ℹ️</span></th>

            <td>

                <input type="color" class="color_preview3" name="WySMartConnect_pro_tip_text_color" value="<?php echo esc_attr(get_option('WySMartConnect_pro_tip_text_color', '#000')); ?>" />

                <input type="text" class="color_code_text3" value="<?php echo esc_attr(get_option('WySMartConnect_pro_tip_text_color', '#000')); ?>">

            </td>
        </tr>

        <tr>
            <th scope="row">Pro Tip Text Font Size <span class="info-icon" title="This will be the Pro Tip Text text font size in pixels(px).">ℹ️</span>
            </th>

            <td>

                <input type="number" name="WySMartConnect_pro_tip_text_size" value="<?php echo esc_attr(get_option('WySMartConnect_pro_tip_text_size', '')); ?>" />px

            </td>
        </tr>

        <tr valign="top">



            <th scope="row">Shortcode Icon <span class="info-icon" title="This shortcode icon displays a popup with a list of colors when clicked.">ℹ️</span></th>

            <td>

                <input type="text" name="WySMartConnect_icon_url" value="<?php echo esc_attr(get_option('WySMartConnect_icon_url', plugin_dir_url(__DIR__) . 'images/ShopByColor_icon.jpg')); ?>" class="icon-url" />

                <button class="upload-icon-button button">Select Icon</button>

            </td>

        </tr>

        <tr valign="top">

            <th scope="row">Color Popup Border Color <span class="info-icon" title="Select a border color for the popup with a list of colors.">ℹ️</span></th>

            <td>

                <input type="color" class="color_preview" name="WySMartConnect_popup_border_color" value="<?php echo esc_attr(get_option('WySMartConnect_popup_border_color', class_exists('Flatsome_Default') ? Flatsome_Default::COLOR_PRIMARY : '#000000')); ?>">

                <input type="text" class="color_code_text" value="<?php echo esc_attr(get_option('WySMartConnect_popup_border_color', class_exists('Flatsome_Default') ? Flatsome_Default::COLOR_PRIMARY : '#000000')); ?>">

            </td>
            

        </tr>

        <tr valign="top">

            <th scope="row">Include colors without color code <span class="info-icon" title="Select this checkbox to include colors in the popup which are not having color codes. ">ℹ️</span></th>

            <td>

                <input type="checkbox" class="" value="1" name="WySMartConnect_include_colors_without_color_code" <?php checked(get_option('WySMartConnect_include_colors_without_color_code', '0'), '1'); ?>>

            </td>

        </tr>

    </table>

    <?php submit_button(); ?>

</form>