jQuery(document).ready(function($) {
    // Update settings
    $("body").on("click", ".fnehd-submit-settings", function() {
		$("#fnehd-loader").css("background-color", "#010e31d1");
        $('#SpinLoaderText').html(fnehd.swal.warning.save_sett_loader_text);
    });

    $('#fnehd-options-form').submit(function() {
        $(this).ajaxSubmit({
            success: function() {
                Swal.fire({
                    icon: 'success',
                    title: fnehd.swal.success.save_sett_success,
                    showConfirmButton: false,
                    timer: 1500
                });
                $('#SpinLoaderText').html("");
                $("#fnehd-sett-modal").modal('hide');
                location.reload();
            },
            timeout: 5000
        });
        return false;
    });

	// Export settings
	$("body").on("click", "#fnehd-export-settings", function () {
		var data = { action: "fnehd_exp_options" };

		// Send AJAX request to export options
		$.post(ajaxurl, data, function (response) {
			if (response.success) {
				// Create a downloadable JSON file
				var blob = new Blob([response.data.options], { type: "application/json" });
				var downloadUrl = URL.createObjectURL(blob);
				var a = document.createElement("a");
				a.href = downloadUrl;
				a.download = "fnehousing-options-" + Date.now() + ".json";
				document.body.appendChild(a);
				a.click();

				// Show success message
				Swal.fire({
					icon: "success",
					title: response.data.message,
				});
			} else {
				// Show error message if the export fails
				Swal.fire({
					icon: "error",
					title: fnehd.swal.warning.error_title,
					text: response.data.message,
				});
			}
		}).fail(function (jqXHR, textStatus, errorThrown) {
			// Handle AJAX failure
			Swal.fire({
				icon: "error",
				title: fnehd.swal.warning.error_title,
				text: `Error: ${textStatus}. ${errorThrown}`,
			});
		});
	});


    // Import settings
    $("body").on("click", "#imp_options_btn", function() {
        $('#SpinLoaderText').html("<strong>"+fnehd.swal.warning.import_sett_loader_text+"</strong>");
    });

    $("#fnehd-options-imp-form").on('submit', function(e) {
        e.preventDefault();
        swal.fire({
            title: fnehd.swal.warning.title,
            text: fnehd.swal.warning.import_sett_text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: fnehd.swal.warning.import_sett_confirm,
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: response.data,
                                showConfirmButton: false,
                                timer: 1500,
                            });
                            $('#SpinLoaderText').html("");
                            $("#DisplayOptionsImportForm").collapse('hide');
                            $("#fnehd-options-import-modal").modal('hide');
                            $("#fnehd-options-imp-form")[0].reset();
                            location.reload();
                        } else {
                            Swal.fire({
                                icon: 'warning',
                                title: response.data,
                            });
                        }
                    }
                });
            }
        });
    });

    // Restore settings defaults
    $("body").on("click", ".fnehd-reset-settings", function() {
        swal.fire({
            title: fnehd.swal.warning.title,
			text: fnehd.swal.warning.text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: fnehd.swal.warning.reset_sett_confirm,
        }).then((result) => {
            if (result.value) {
                var data = { 'action': 'fnehd_reset_options' };
                $.post(ajaxurl, data, function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: response.data,
                        });
                        location.reload();
                    }
                });
            } else {
				// Show error message if the export fails
				Swal.fire({
					icon: "error",
					title: fnehd.swal.warning.error_title,
					text: response.data,
				});
			}
        });
    });

    // Generate REST API Key & URL
    const ids = ["rest_api_key", "rest_api_enpoint_url"];
    ids.forEach(ID => {
        $("body").on("click", "#fnehd_" + ID + "_generator", function(e) {
            e.preventDefault();
            var data = { 'action': 'generate_fnehd_' + ID };

            $.post(ajaxurl, data, function(response) {
                if (response.success) {
                    if (ID === 'rest_api_enpoint_url') {
                        let api_key = $('#rest_api_key').val();
                        $('#' + ID).val(response.data.url); // Append API key to the URL
                    } else {
                        $('#' + ID).val(response.data.key); // Set the generated API key
                    }
                    Swal.fire({
                        icon: 'success',
                        title: response.data.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    // Handle server-side errors
                    Swal.fire({
                        icon: 'error',
                        title: fnehd.swal.warning.error_title,
                        text: response.data.message,
                        showConfirmButton: true
                    });
                }
            }).fail(function(jqXHR, textStatus, errorThrown) {
                // Handle AJAX request failures
                Swal.fire({
                    icon: 'error',
                    title: 'Request Failed',
                    text: `Error: ${textStatus}. ${errorThrown}`,
                    showConfirmButton: true
                });
            });
        });
    }); 


	// Bootstrap Choices - Multiple Select Input ( multChoiceSelect() is defined in fnehd-admin.js )
	multChoiceSelect({ selectID: 'rest_api_data', data: fnehd.rest_api_data});
	multChoiceSelect({ selectID: 'dispute_evidence_file_types', data: fnehd.dispute_evidence_file_types});

    // Special settings options
    var data = { 'action': 'fnehd_options' };
    $.post(ajaxurl, data, function(response) { 
        // Set variable for current Company Logo
        var image_path = response.company_logo;

        // If company logo, replace default avatar with it, then add "Change" & "Remove" buttons
        if (image_path !== "") {
            $(".company_logo-FilePrv").attr("class", "fileinput-new thumbnail");
            $(".company_logo-PrevUpload").attr("src", image_path);
            $(".company_logo-AddFile").css("display", "none");
            $(".company_logo-ChangeFile").css("display", "inline");
            $(".company_logo-dismissPic").css("display", "inline");
        }

        // Check if setting is "ON" and perform other actions
		fnehdUpdateToggle(response.smtp_protocol, 'smtp_protocol', 'fnehd-smtp-settings-options');
		fnehdUpdateToggle(response.auto_dbackup, 'auto_dbackup', 'fnehd_auto_dbackup_freq_option');
		
    });
	
	 // Checkbox settings update toggle function
	function fnehdUpdateToggle(responseValue, inputId, optionsContainer) {
		const toggleTextId = `${inputId}_on_off`; // Derive toggleTextId from inputId

		if (responseValue === true) {
			$(`#${inputId}`).prop('checked', true);
			$(`#${toggleTextId}`).html('<b class="toggleOn">'+fnehd.swal.success.checkbox_on_text+'</b>');
			$(`#${optionsContainer}`).slideDown(); // Show the options
		} else {
			$(`#${inputId}`).prop('checked', false);
			$(`#${toggleTextId}`).html('<b class="toggleOff">'+fnehd.swal.success.checkbox_off_text+'</b>');
			$(`#${optionsContainer}`).slideUp(); // Hide the options
		}
	}
	
	
    // Validate Email
    function isEmail(email) {
        var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }

    // Validate Company Email
    $('body').on('blur', '#company_email', function() {
        var email = $('#company_email').val();
        if (email !== "" && !isEmail(email)) {
            $('#company_email').val("");
            $('#company_email_notice').html("Please Enter a valid email");
        } else {
            $('#company_email_notice').html("");
        }
    });

    // Force "Bitcoin Payment option to false if no API Key is Provided
    $('body').on('click', '#enable_bitcoin_payment', function() {
        if ($('#blockonomics_api_key').val() === "") {
            Swal.fire({
                icon: 'warning',
                title: 'Blockonomics API Key Required!',
                text: "Blockonomics API Key is Required to Enable this feature. Please fill in the Required Field to Continue",
            });
            $('#enable_bitcoin_payment').prop('checked', false);
            $('#enable_bitcoin_payment_on_off').html('<b class="toggleOff">'+fnehd.swal.success.checkbox_off_text+'</b>');
        } else {
            if ($('#enable_bitcoin_payment')[0].checked === true) {
                $('#enable_bitcoin_payment_on_off').html('<b class="toggleOn">'+fnehd.swal.success.checkbox_on_text+'</b>');
            } else {
                $('#enable_bitcoin_payment_on_off').html('<b class="toggleOff">'+fnehd.swal.success.checkbox_off_text+'</b>');
            }
        }
    });

    $('#blockonomics_api_key').on('blur', function() {
        if ($('#blockonomics_api_key').val() === '' && $('#enable_bitcoin_payment')[0].checked === true) {
            $('#enable_bitcoin_payment').prop('checked', false);
            $('#enable_bitcoin_payment_on_off').html('<b class="toggleOff">'+fnehd.swal.success.checkbox_off_text+'</b>');
        }
    });

    // Show/hide SMTP password
    $('#toggleSmtpPass').on('click', function() {
        var passInput = $("#SMTPPass");

        if (passInput.attr('type') === 'password') {
            passInput.attr('type', 'text');
            $("#toggleSmtpPass").attr("class", "fas fa-eye sett-icon");
        } else {
            passInput.attr('type', 'password');
            $("#toggleSmtpPass").attr("class", "fas fa-eye-slash sett-icon");
        }
    });

    // If switch is on (On click), add toggleOn class, else add toggleOff class
    var options = [
        'user_new_shelter_email', 'user_new_milestone_email', 'admin_new_shelter_email', 'admin_new_milestone_email', 'notify_admin_by_email', 'fold_wp_menu', 'fold_fnehd_menu', 'theme_class', 'dbackup_log', 'enable_paypal_payment', 'enable_rest_api', 'enable_rest_api_key', 'enable_dispute_evidence', 'enable_commissions_tax', 'enable_max_commission', 'enable_min_commission', 'enable_commissions_transparency', 'enable_commissions', 'enable_invoice_logo'
    ];

    for (var i = 0; i < options.length; i++) {
        $("#fnehd-settings-panel").bind("click", { msg: options[i] }, function(e) {
            if ($("#" + e.data.msg)[0].checked === true) {
                $("#" + e.data.msg + "_on_off").html('<b class="toggleOn">'+fnehd.swal.success.checkbox_on_text+'</b>');
            } else {
                $("#" + e.data.msg + "_on_off").html('<b class="toggleOff">'+fnehd.swal.success.checkbox_off_text+'</b>');
            }
        });
    }

    // Checkbox toggle function
	function fnehdCheckToggle({inputId, optionsContainerId}) {
		$("body").on("click", `#${inputId}`, function() {	
			const toggleTextId = `${inputId}_on_off`; // Derive toggleTextId from inputId

			if ($(`#${inputId}`)[0].checked === true) {
				$(`#${toggleTextId}`).html('<b class="toggleOn">'+fnehd.swal.success.checkbox_on_text+'</b>');
				$(`#${optionsContainerId}`).slideDown(); // Show the options
			} else {
				$(`#${toggleTextId}`).html('<b class="toggleOff">'+fnehd.swal.success.checkbox_off_text+'</b>');
				$(`#${optionsContainerId}`).slideUp(); // Hide the options
			}
		});
	}
    //show auto backup settings if on
	fnehdCheckToggle({inputId: 'smtp_protocol', optionsContainerId: 'fnehd-smtp-settings-options'});
	//show smtp protocol settings if on
	fnehdCheckToggle({inputId: 'auto_dbackup', optionsContainerId: 'fnehd_auto_dbackup_freq_option'});
	

    // Company Logo File uploader
    $(document).on('click', '.company_logo', function(e) {
        e.preventDefault();
        var $button = $(this);

        var file_frame = wp.media.frames.file_frame = wp.media({
            title: 'Select or Upload an Image',
            library: {
                type: 'image' // mime type
            },
            button: {
                text: 'Select Image'
            },
            multiple: false
        });

        file_frame.on('select', function() {
            var attachment = file_frame.state().get('selection').first().toJSON();
            $(".company_logo-FileInput").val(attachment.url);
            $(".company_logo-FilePrv").removeClass("img-circle");
            $(".company_logo-PrevUpload").attr("src", "" + attachment.url + "");
            $(".company_logo-AddFile").css("display", "none");
            $(".company_logo-ChangeFile").css("display", "inline");
            $(".company_logo-dismissPic").css("display", "inline");
        });

        file_frame.open();
    });

    // Reset upload field
    $("body").on("click", ".company_logo-dismissPic", function() {
        $(".company_logo-ChangeFile").css("display", "none");
        $(".company_logo-AddFile").css("display", "inline");
        $(".company_logo-dismissPic").css("display", "none");
        $(".company_logo-PrevUpload").attr("src", '');
    });

    // Color Picker
    const IDs = ["primary_color", "secondary_color"];

    IDs.forEach(id => {
        const colorPicker = document.getElementById(id);
        const textInput = document.getElementById(id + '_val');

        // Sync text input with color picker
        colorPicker.addEventListener('input', function() {
            textInput.value = colorPicker.value;
        });

        // Update color picker when text input changes
        textInput.addEventListener('input', function() {
            const color = textInput.value;
            if (/^#([0-9A-F]{3}){1,2}$/i.test(color)) { // Validate hex color code
                colorPicker.value = color;
            }
        });
    });

});
