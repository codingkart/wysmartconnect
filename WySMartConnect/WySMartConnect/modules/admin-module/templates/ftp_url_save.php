<div class="wrap">
    <!-- <h1>NodeJS Config Settings</h1> -->
    <form method="post" action="">
        <?php wp_nonce_field('save_ftp_url', 'ftp_url_nonce'); ?>
        <table class="form-table">
            <tr valign="top">
                <!-- <th scope="row">NodeJS Config</th> -->
                <td>
                    <input type="text" name="ftp_url_endpoint" value="<?php echo esc_attr($current_url); ?>" class="regular-text" />
                </td>
            </tr>
        </table>
        <?php submit_button('Save'); ?>
    </form>
</div>
