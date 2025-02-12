
//Print Function
function printDiv(divName){
	var toPrint;
	toPrint = window.open();
	toPrint.document.write(document.getElementById(divName).innerHTML);
	toPrint.print();
	toPrint.close();
}


// Bootstrap Choices - Multiple Select Input
function multChoiceSelect({ selectID }) {
	// Ensure the dynamic ID is properly interpolated
	var multipleCancelButton = new Choices(`#${selectID}`, {
		removeItemButton: true,
		renderChoiceLimit: 15
	});
	// Retrieve REST API Data Options
	var selectedValues = fnehd[selectID]; 
	// Safeguard against null or undefined API data
	if (Array.isArray(selectedValues)) {
		selectedValues.forEach(function(value) {
			multipleCancelButton.setChoiceByValue(value);
		});
	} else {
		console.error(`Invalid data for selectID: ${selectID}`);
	}
}


// Function to handle image file selection and upload
function FnehdWPFileUpload(buttonClass, fileInputClass, filePrvClass, addFileClass, changeFileClass, dismissPicClass) {
	jQuery("body").on("click", buttonClass, function (e) {
		e.preventDefault();

		const file_frame = wp.media.frames.file_frame = wp.media({
			title: "Select or Upload an Image",
			library: { type: "image" }, // MIME type
			button: { text: "Select Image" },
			multiple: false,
		});

		file_frame.on("select", function () {
			const attachment = file_frame.state().get("selection").first().toJSON();
			jQuery(fileInputClass).val(attachment.url);
			jQuery(filePrvClass).addClass("thumbnail");
			jQuery(filePrvClass).html("<img src='"+attachment.url+"' alt='...'>");
			jQuery(addFileClass).hide();
			jQuery(changeFileClass).show();
			jQuery(dismissPicClass).show();
		});

		file_frame.open();
	});

	// Reset upload field
	jQuery("body").on("click", dismissPicClass, function () {
		jQuery(changeFileClass).hide();
		jQuery(addFileClass).show();
		jQuery(dismissPicClass).hide();
		jQuery(fileInputClass).val("");
		jQuery(filePrvClass).removeClass("thumbnail");
		jQuery(filePrvClass).html("");
	});
}

// Function to handle multiple image upload
function FnehdWPMultFileUpload(
	buttonClass,
	fileInputClass,
	filePrvContainerClass,
	prevUploadClass,
	addFileClass,
	changeFileClass,
	dismissPicClass
) {
	jQuery(document).on("click", buttonClass, function (e) {
		e.preventDefault();

		const file_frame = wp.media({
			title: "Select or Upload Images",
			library: { type: "image" },
			button: { text: "Select Images" },
			multiple: true,
		});

		file_frame.on("select", function () {
			const selection = file_frame.state().get("selection").toJSON();
			let imageUrls = [];

			// Clear previous images before adding new ones
			jQuery(filePrvContainerClass).empty();

			selection.forEach((attachment) => {
				imageUrls.push(attachment.url);
				jQuery(filePrvContainerClass).append(`
					<div class="image-preview thumbnail" style="display: inline-block; margin: 5px; position: relative;">
						<img src="jQuery{attachment.url}" class="jQuery{prevUploadClass.replace(".", "")}" style="width: 100px; height: 100px; object-fit: cover; border-radius: 5px;">
						<button class="remove-image" style="position: absolute; top: 5px; right: 5px; background: red; color: white; border: none; cursor: pointer;">X</button>
					</div>
				`);
			});

			// Store image URLs in the hidden input field
			jQuery(fileInputClass).val(imageUrls.join(","));

			jQuery(addFileClass).hide();
			jQuery(changeFileClass).show();
			jQuery(dismissPicClass).show();
		});

		file_frame.open();
	});

	// Remove individual images
	jQuery(document).on("click", ".remove-image", function () {
		jQuery(this).parent().remove();
		let remainingImages = [];
		jQuery(filePrvContainerClass)
			.find("img")
			.each(function () {
				remainingImages.push(jQuery(this).attr("src"));
			});
		jQuery(fileInputClass).val(remainingImages.join(","));
		if (remainingImages.length === 0) {
			jQuery(addFileClass).show();
			jQuery(changeFileClass).hide();
			jQuery(dismissPicClass).hide();
		}
	});

	// Reset upload field (Remove all images)
	jQuery("body").on("click", dismissPicClass, function () {
		jQuery(changeFileClass).hide();
		jQuery(addFileClass).show();
		jQuery(dismissPicClass).hide();
		jQuery(fileInputClass).val("");
		jQuery(filePrvContainerClass).empty();
	});
}



// Initialize Material Wizard Multi-step Form
function initMaterialWizard() {
	jQuery(".card-wizard").bootstrapWizard({
		tabClass: "nav nav-pills",
		nextSelector: ".btn-next",
		previousSelector: ".btn-previous",

		onInit: function (tab, navigation, index) {
			const total = navigation.find("li").length;
			const jQuerywizard = navigation.closest(".card-wizard");

			const first_li = navigation.find("li:first-child a").html();
			const jQuerymoving_div = jQuery('<div class="moving-tab">' + first_li + "</div>");
			jQuery(".card-wizard .wizard-navigation").append(jQuerymoving_div);

			refreshAnimation(jQuerywizard, index);

			jQuery(".moving-tab").css("transition", "transform 0s");
		},

		onTabClick: function (tab, navigation, index) {
			const isValid = jQuery(".card-wizard form").valid();
			return isValid;
		},

		onTabShow: function (tab, navigation, index) {
			const total = navigation.find("li").length;
			const current = index + 1;
			const jQuerywizard = navigation.closest(".card-wizard");

			if (current >= total) {
				jQuerywizard.find(".btn-next").hide();
				jQuerywizard.find(".btn-finish").show();
			} else {
				jQuerywizard.find(".btn-next").show();
				jQuerywizard.find(".btn-finish").hide();
			}

			const button_text = navigation.find("li:nth-child(" + current + ") a").html();
			setTimeout(function () {
				jQuery(".moving-tab").text(button_text);
			}, 150);

			const jQuerycheckbox = jQuery(".footer-checkbox");

			if (index !== 0) {
				jQuerycheckbox.css({
					opacity: "0",
					visibility: "hidden",
					position: "absolute",
				});
			} else {
				jQuerycheckbox.css({
					opacity: "1",
					visibility: "visible",
				});
			}

			refreshAnimation(jQuerywizard, index);
		},
	});

	jQuery(window).resize(function () {
		jQuery(".card-wizard").each(function () {
			const jQuerywizard = jQuery(this);
			const index = jQuerywizard.bootstrapWizard("currentIndex");
			refreshAnimation(jQuerywizard, index);
			jQuery(".moving-tab").css({
				transition: "transform 0s",
			});
		});
	});

	function refreshAnimation(jQuerywizard, index) {
		const total = jQuerywizard.find(".nav li").length;
		let li_width = 100 / total;
		let total_steps = total;
		let move_distance = jQuerywizard.width() / total_steps;
		let index_temp = index;
		let vertical_level = 0;
		const mobile_device = jQuery(document).width() < 600 && total > 3;

		if (mobile_device) {
			move_distance = jQuerywizard.width() / 2;
			index_temp = index % 2;
			li_width = 50;
		}

		jQuerywizard.find(".nav li").css("width", li_width + "%");

		const step_width = move_distance;
		move_distance = move_distance * index_temp;

		const current = index + 1;

		if (current === 1 || (mobile_device && index % 2 === 0)) {
			move_distance -= 8;
		} else if (current === total_steps || (mobile_device && index % 2 === 1)) {
			move_distance += 8;
		}

		if (mobile_device) {
			vertical_level = parseInt(index / 2, 10) * 38;
		}

		jQuerywizard.find(".moving-tab").css("width", step_width);
		jQuery(".moving-tab").css({
			transform: "translate3d(" + move_distance + "px, " + vertical_level + "px, 0)",
			transition: "all 0.5s cubic-bezier(0.29, 1.42, 0.79, 1)",
		});
	}
}



