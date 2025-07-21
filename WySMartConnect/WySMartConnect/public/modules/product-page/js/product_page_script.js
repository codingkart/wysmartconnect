jQuery(document).ready(function ($) {
    // Function to handle color and size selection
    function handleSelection() {
        // Get selected color and size values
        var selectedColor = $('#pa_color').val();
        var selectedSize = $('#pa_size').val();
        var product_id = $('input[name="product_id"]').val();
        var formData = {
            'attribute_pa_color': selectedColor,
            'attribute_pa_size': selectedSize,
            'action': 'update_related_and_upsell_product_section',
            'product_id': product_id
        };
        // Call ajaxHanlder function to update the products related section and usells section
        var successCallback = function (response) {
            // Update the products related section and usells section
            updateProductRelatedProducts(product_id, '.upsells-wrapper', response.upsell_products_html);
            updateProductRelatedProducts(product_id, '.related-products-wrapper', response.related_products_html);
        }
        var beforeSendCallback = function () {

        }
        ajaxHanlder(formData, "ck_ajax_loader", successCallback, beforeSendCallback, "GET");
    }

    // Call handleSelection function when color or size changes
    $('#pa_color, #pa_size').change(function () {
        handleSelection();
    });

    // Function to update the products related section and usells section
    function updateProductRelatedProducts(product_id, wrapper_selector, html_content) {
        // Check if the container contains the related-products-wrapper
        if ($(`#product-${product_id} .container`).find(wrapper_selector).length === 0) {
            // If related-products-wrapper does not exist, add it
            $(`#product-${product_id} .container`).append(html_content);
        } else {
            // If related-products-wrapper exists, replace it
            $(`#product-${product_id} ${wrapper_selector}`).replaceWith(html_content);
        }
    }
});