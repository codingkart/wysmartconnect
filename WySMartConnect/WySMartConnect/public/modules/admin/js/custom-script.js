jQuery(document).ready(function ($) {
    // Function to update color preview and input value
    function updateColorPreview() {
        var colorCode = $('.color_code_text').val();
        //var colorCode = $('.color_preview').val();
        $('.color_preview').val(colorCode);
        //$('.color_code_text').val(colorCode);
    }

    // Initial update
    updateColorPreview();

    // Event listener for input change
    $('.color_code_text').on('input', function () {
        updateColorPreview();
    });

    // Event listener for color preview click
    $('.color_preview').on('change', function () {
        var colorCode = $(this).val();
        $('.color_code_text').val(colorCode);
        // updateColorPreview();
    });
});
