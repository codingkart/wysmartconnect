<?php wp_nonce_field('save_order_meta_box', 'order_meta_box_nonce'); ?>

<style>
    .meta-box-container { padding: 10px; }
    .meta-box-container label { font-weight: bold; display: block; margin-bottom: 5px; }
    .meta-box-container input { width: 100%; padding: 5px; margin-bottom: 10px; }
    .meta-box-container button { width: 100%; margin-top: 10px; }
    .tracking-code { background: #f5f5f5; padding: 5px; border-radius: 3px; display: block; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 8px; border: 1px solid #ddd; text-align: left; }
    th { background-color: #f4f4f4; }
</style>

<div class="meta-box-container">
    <table>
        <thead>
            <tr>
                <th>Manufacturer</th>
                <th>PO Number</th>
                <th>Tracking Code</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if (!empty($manufacturer_details)) {
                foreach ($manufacturer_details as $manufacturer) :
                    $manufacturer_id = $manufacturer['id'];
                    $manufacturer_name = esc_html($manufacturer['manufacturer_name']);
                    
                    // Default values if no tracking details exist
                    $asin_number = '';
                    $tracking_code = '';

                    // Find matching tracking details
                    if (!empty($tracking_details) && is_array($tracking_details)) {
                        foreach ($tracking_details as $detail) {
                            if ($detail['_manufacturer_id'] == $manufacturer_id) {
                                $asin_number = esc_attr($detail['_asin_number']);
                                $tracking_code = esc_attr($detail['tracking_code']);
                                break;
                            }
                        }
                    }
            ?>
                <tr>
                    <td><?php echo $manufacturer_name; ?></td>
                    <td>
                        <input type="text" id="asin_number_<?php echo esc_attr($manufacturer_id); ?>" 
                               name="tracking_details[<?php echo esc_attr($manufacturer_id); ?>][_asin_number]" 
                               value="<?php echo $asin_number; ?>">
                    </td>
                    <td>
                       <?php echo ($tracking_code)? $tracking_code : '-' ; ?>
                    </td>
                    <td><input type="hidden" name="order_id" id="order_id" value="<?php echo esc_attr($post->ID); ?>">
                        <button type="button" class="button button-primary save_order_details" 
                                data-manufacturer_id="<?php echo esc_attr($manufacturer_id); ?>">
                            <?php _e('Save', 'woocommerce'); ?>
                        </button>
                    </td>
                </tr>
            <?php endforeach;
            } else {
                echo '<tr><td colspan="4">No manufacturers found.</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>
