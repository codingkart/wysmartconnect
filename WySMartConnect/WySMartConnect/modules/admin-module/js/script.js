jQuery(document).ready(function ($) {
	// Get the input fields and button

	$(".save_order_details").on("click", function () {
		var button = $(this);
		var manufacturerID = button.data("manufacturer_id");

		var asinNumber = $("#asin_number_" + manufacturerID).val();
		var orderID = $("#order_id").val(); // Ensure order ID is passed in the template
		var nonce = ajax_object.nonce; // Get nonce from localized script

		// Disable button and show loading state
		button.prop("disabled", true).text("Saving...");

		$.ajax({
			url: ajax_object.ajax_url,
			type: "POST",
			dataType: "json",
			data: {
				action: "save_order_details",
				manufacturer_id: manufacturerID,
				asin_number: asinNumber,
				order_id: orderID,
				nonce: nonce,
			},
			success: function (response) {
				if (response.success) {
					button
						.text("Saved!")
						.removeClass("button-primary")
						.addClass("button-success");
					setTimeout(() => {
						window.location.reload();
					}, 1000);
				} else {
					alert(response.data.message);
					button.prop("disabled", false).text("Save");
				}
			},
			error: function () {
				alert("Failed to save details. Please try again.");
				button.prop("disabled", false).text("Save");
			},
		});
	});

	//delete ftp details

	$(document).on("click", ".delete-button", function () {
		const id = $(this).data("id");

		if (confirm("Are you sure you want to delete this entry?")) {
			$.ajax({
				url: ajaxurl,

				type: "POST",

				data: {
					action: "delete_ftp_data",

					id: id,
				},

				success: function (response) {
					if (response.success) {
						alert(response.data.message);

						location.reload();
					} else {
						alert(response.data.message);
					}
				},

				error: function () {
					alert("Failed to delete FTP data.");
				},
			});
		}
	});

	//edit ftp details

	// Handle Edit button click

	// $(document).on("click", ".edit-button", function () {

	//   const id = $(this).data("id"); // Get the ID from the button's data attribute

	//   // Fetch data for the selected row

	//   $.ajax({

	//     url: ajaxurl,

	//     type: "POST",

	//     data: {

	//       action: "load_ftp_data", // WordPress AJAX action

	//       id: id,

	//     },

	//     success: function (response) {

	//       if (response.success) {

	//         // Populate the form with the fetched data

	//         $("#manufacturer_name").val(response.data.manufacturer_name);

	//         $("#ftp_server").val(response.data.ftp_server);

	//         $("#ftp_username").val(response.data.ftp_username);

	//         $("#ftp_password").val(response.data.ftp_password); // Decode the base64 password

	//         $('input[name="id"]').val(response.data.id); // Set the hidden ID field

	//         // Update the action value for the form to 'update_ftp_data'

	//         $('input[name="action"]').val("update_ftp_settings");

	//         // Ensure the hidden ID field exists (append it if missing)

	//         if ($('input[name="id"]').length === 0) {

	//           $("<input>")

	//             .attr({

	//               type: "hidden",

	//               name: "id",

	//               value: response.data.id,

	//             })

	//             .appendTo("#ftp-settings-form");

	//         }

	//         $(".form-container").slideDown();

	//       } else {

	//         alert(response.data.message); // Show error message

	//       }

	//     },

	//     error: function () {

	//       alert("Failed to fetch FTP data.");

	//     },

	//   });

	// });

	// Handle Edit button click

	$(document).on("click", ".edit-button", function () {
		const id = $(this).data("id"); // Get the ID from the button's data attribute

		// Fetch data for the selected row

		$.ajax({
			url: ajaxurl, // WordPress AJAX URL

			type: "POST",

			data: {
				action: "load_ftp_data", // WordPress AJAX action

				id: id, // Send the selected row's ID
			},

			success: function (response) {
				console.log("load ftp data edit button");

				// Ensure response is valid and success is true

				if (response && response.success) {
					const data = response.data;

					// Populate the form fields with the fetched data

					$("#manufacturer_name").val(data.manufacturer_name || "");

					$("#ftp_server").val(data.ftp_server || "");

					$("#ftp_username").val(data.ftp_username || "");

					$("#ftp_password").val(data.ftp_password || ""); // Handle decoded password

					$("#file_path").val(data.file_path || ""); // Populate file path

					$("#port").val(data.port || ""); // Populate port number

					$('input[name="id"]').val(data.id); // Set the hidden ID field

					// Update the form action to 'update_ftp_settings'

					$('input[name="action"]').val("update_ftp_settings");

					// Ensure the hidden ID field exists (append it if missing)

					if ($('input[name="id"]').length === 0) {
						$("<input>")
							.attr({
								type: "hidden",

								name: "id",

								value: data.id,
							})

							.appendTo("#ftp-settings-form");
					}

					// Show the form container

					$(".form-container").slideDown();
				} else {
					// Handle errors from the server

					alert(
						response && response.data && response.data.message
							? response.data.message
							: "Error loading FTP data."
					);
				}
			},

			error: function (xhr, status, error) {
				// Log and display AJAX errors

				console.error("AJAX Error:", status, error);

				alert("Failed to fetch FTP data. Please try again.");
			},
		});
	});

	// Manufacturer add form

	$(".form-container").hide();

	$("#add-manufacturer").on("click", function () {
		$(".form-container").slideDown();
	});

	$(".close").on("click", function () {
		$(".form-container").slideUp();
	});

	$("#enable_auto_complete_toggle").change(function () {
		var status = $(this).is(":checked") ? "on" : "off";
		$.post(
			ajax_object.ajax_url,
			{
				action: "auto_complete_toggle_functionality",
				status: status,
				security: ajax_object.nonce,
			},
			function (response) {
				$("#auto_complete_toggle_status").text(
					response.data.status === "on" ? "Enabled" : "Disabled"
				);
			}
		);
	});
});
