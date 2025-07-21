<?php
$enabled = get_option('WySMartConnect_enable_auto_complete_order_status', 'off'); // Default: off
?>

<table class="form-table">
    <tr>
        <th scope="row"><label for="enable_auto_complete_toggle">Enable Auto Complete Order Status:</label></th>
        <td>
            <label class="wp-toggle">
                <input type="checkbox" id="enable_auto_complete_toggle" <?php checked($enabled, 'on'); ?>>
                <span class="wp-toggle-slider"></span>
            </label>
            <span id="auto_complete_toggle_status" class="auto-complete-toggle-text"><?php echo $enabled === 'on' ? 'Enabled' : 'Disabled'; ?></span>
        </td>
    </tr>
</table>

<style>
    /* Toggle Button - Matches WP Admin Style */
    .wp-toggle {
        position: relative;
        display: inline-block;
        width: 48px;
        height: 24px;
        margin-right: 10px;
    }

    .wp-toggle input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .wp-toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 24px;
    }

    .wp-toggle-slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    /* Toggle ON State */
    input:checked + .wp-toggle-slider {
        background-color: #2C783A; /* WP Green */
    }

    input:checked + .wp-toggle-slider:before {
        transform: translateX(24px);
    }

    /* FTP Status Text */
    .auto-complete-toggle-text {
        font-weight: bold;
        color: #555;
    }
</style>



<button class="add-manufacturer fsdfds" id="add-manufacturer">Create Manufacturer</button>



<div class="form-container">

    <h2 style="position: relative;">

        FTP Server Details

        <span class="close" style="position: absolute; top: 0; right: 0; cursor: pointer;">X</span>

    </h2>

    

    <form class="test" method="post" id="ftp-settings-form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">

        <?php wp_nonce_field('save_ftp_settings', 'ftp_settings_nonce'); ?>

        <input type="hidden" name="action" value="save_ftp_settings">

        <div class="form-group">

            <label for="manufacturer_name">Manufacturer Name</label>

            <input type="text" id="manufacturer_name" name="manufacturer_name" value="" placeholder="e.g., XYZ Manufacturing" required>

        </div>

        <div class="form-group">

            <label for="ftp_server">FTP Server</label>

            <input type="text" id="ftp_server" name="ftp_server" value="" placeholder="e.g., ftps.example.com" required>

        </div>

        <div class="form-group">

            <label for="ftp_username">Username</label>

            <input type="text" id="ftp_username" name="ftp_username" value="" placeholder="e.g., user123" required>

        </div>

        <div class="form-group">

            <label for="ftp_password">Password</label>

            <input type="password" id="ftp_password" name="ftp_password" value="" placeholder="Enter your password" required>

        </div>

        <div class="form-group">

            <label for="file_path">File Path</label>

            <input type="text" id="file_path" name="file_path" value="" placeholder="e.g., /asn/ASN29116.csv" required>

           

        </div>

        <div class="form-group">

            <label for="port">Port</label>

            <input type="number" id="port" name="port" value="21" placeholder="e.g., 21" required>

        </div>

        <div class="form-group">

            <input type="submit" name="save_ftp_settings" value="Save Settings">

        </div>

    </form>

</div>



<br><br>





<table id="ftpTable" border="1" cellspacing="0" cellpadding="8" style="width:100%; border-collapse: collapse;">

    <thead>

        <tr>

            <th style="text-align: left;">S.No</th>

            <th style="text-align: left;">Manufacturer Name</th>

            <th style="text-align: left;">FTP Server</th>

            <th style="text-align: left;">Username</th>

            <th style="text-align: left;">Password</th>

            <th style="text-align: left;">File Path</th>

            <th style="text-align: left;">Port</th>

            <th style="text-align: left;">Action</th>

        </tr>

    </thead>

    <tbody>

        <?php if (!empty($ftpdata)): ?>

            <?php foreach ($ftpdata as $index => $row): ?>

                <tr>

                    <td><?php echo $index + 1; ?></td>

                    <td><?php echo esc_html($row['manufacturer_name']); ?></td>

                    <td><?php echo esc_html($row['ftp_server']); ?></td>

                    <td><?php echo esc_html($row['ftp_username']); ?></td>

                    <td><?php echo esc_html($row['ftp_password']); ?></td>

                    <td><?php echo esc_html($row['file_path']); ?></td>

                    <td><?php echo esc_html($row['port']); ?></td>

                    <td>

                        <button type="button" class="edit-button" data-id="<?php echo esc_attr($row['id']); ?>">Edit</button>

                        <button type="button" class="delete-button" data-id="<?php echo esc_attr($row['id']); ?>">Delete</button>

                    </td>

                </tr>

            <?php endforeach; ?>

        <?php else: ?>

            <tr>

                <td colspan="8">No entries found.</td>

            </tr>

        <?php endif; ?>

    </tbody>

</table>