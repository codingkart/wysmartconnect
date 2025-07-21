(function ($) {
	'use strict';
	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	$(document).ready(function () {
		console.log("testing admin js");
		// Uploading files
		var file_frame;
		$(document).on('click', '.upload-icon-button', function (e) {
			e.preventDefault();
			// If the media frame already exists, reopen it.
			if (file_frame) {
				file_frame.open();
				return;
			}
			// Create the media frame.
			file_frame = wp.media.frames.file_frame = wp.media({
				title: 'Select or Upload Icon',
				button: {
					text: 'Use this icon'
				},
				multiple: false
			});
			// When an image is selected, run a callback.
			file_frame.on('select', function () {
				var attachment = file_frame.state().get('selection').first().toJSON();
				$('.icon-url').val(attachment.url);
			});
			// Open the media uploader.
			file_frame.open();
		});
		// Function to update color preview and input value
		function updateColorPreview() {
			var colorCode = $('.color_code_text').val();
			//var colorCode = $('.color_preview').val();
			$('.color_preview').val(colorCode);
			//$('.color_code_text').val(colorCode);
		}
		function updateColorPreview2() {
			var colorCode = $('.color_code_text2').val();
			//var colorCode = $('.color_preview').val();
			$('.color_preview2').val(colorCode);
			//$('.color_code_text').val(colorCode);
		}
		function updateColorPreview3() {
			var colorCode = $('.color_code_text3').val();
			//var colorCode = $('.color_preview').val();
			$('.color_preview3').val(colorCode);
			//$('.color_code_text').val(colorCode);
		}
        function updateColorPreview4() {
			var colorCode = $('.color_code_text4').val();
			//var colorCode = $('.color_preview').val();
			$('.color_preview4').val(colorCode);
			//$('.color_code_text').val(colorCode);
		}
        function updateColorPreview5() {
			var colorCode = $('.color_code_text5').val();
			//var colorCode = $('.color_preview').val();
			$('.color_preview5').val(colorCode);
			//$('.color_code_text').val(colorCode);
		}
		// Initial update
		updateColorPreview();
		updateColorPreview2();
		updateColorPreview3();
        updateColorPreview4();
        updateColorPreview5();
		// Event listener for input change
		$('.color_code_text').on('input', function () {
			updateColorPreview();
		});
		$('.color_code_text2').on('input', function () {
			updateColorPreview2();
		});
		$('.color_code_text3').on('input', function () {
			updateColorPreview3();
		});
		// Event listener for color preview click
		$('.color_preview').on('change', function () {
			var colorCode = $(this).val();
			$('.color_code_text').val(colorCode);
			// updateColorPreview();
		});
		$('.color_preview2').on('change', function () {
			var colorCode = $(this).val();
			$('.color_code_text2').val(colorCode);
			// updateColorPreview();
		});
		$('.color_preview3').on('change', function () {
			var colorCode = $(this).val();
			$('.color_code_text3').val(colorCode);
			// updateColorPreview();
		});
	});
	jQuery(document).ready(function ($) {
		$('#add-colors').on('click', function () {
			var button = $(this);
			var selectedColors = [];
			$('input[name="color_checkbox[]"]:checked').each(function () {
				selectedColors.push($(this).val());
			});
			if (selectedColors.length === 0) {
				alert('Please select at least one color.');
				return;
			}
			$.ajax({
				url: WyAdminObj.ajax_url,
				type: 'POST',
				data: {
					action: 'add_product_colors',
					colors: selectedColors,
				},
				beforeSend: function () {
					button.text("Adding");
				},
				error: function () {
					button.text("Add Selected Colors to WooCommerce");
				},
				success: function (response) {
					$(this).text("Added");
					alert(response.data.message);
					if (response.data.errors) {
						response.data.errors.forEach(function (error) {
							console.log(error);
						});
					}
					window.location.reload()
				},
			});
		});
		$('#show-available-colors').on('click', function (e) {
			e.preventDefault();
			$('#available-colors-list').toggle();
		});
		$( document ).tooltip();
		// $('.info-icon').tooltip({
		// 	show: null, // Show immediately
		// 	hide: null,  // Hide immediately
		// 	position: {
		// 		my: "center top+10",
		// 		at: "center bottom",
		// 		collision: "flipfit"
		// 	}
		// });
		console.log("testing")
	});
})(jQuery);