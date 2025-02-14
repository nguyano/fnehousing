
//Print Function
function printDiv(divName){
	var toPrint;
	toPrint = window.open();
	toPrint.document.write(document.getElementById(divName).innerHTML);
	toPrint.print();
	toPrint.close();
}


// Bootstrap Choices - Multiple Select Input
function multChoiceSelect({ selectID, data = [] }) {
    const selectElement = document.getElementById(selectID);
    if (!selectElement) return;

    // Prevent duplicate initialization
    if (selectElement.dataset.choicesInitialized === "true") return;

    // Initialize Choices.js
    const multipleCancelButton = new Choices(selectElement, {
        removeItemButton: true,
        renderChoiceLimit: 15
    });

    // Store the instance in dataset to prevent re-initialization
    selectElement.dataset.choicesInitialized = "true";

    // Populate choices with existing data
    if (Array.isArray(data)) {
        data.forEach(function(value) {
            multipleCancelButton.setChoiceByValue(value);
        });
    }
}



// Function to handle image file selection and upload
function FnehdWPFileUpload(id) {
    const buttonClass = `.${id}-AddFile, .${id}-ChangeFile`;
    const fileInputClass = `.${id}-FileInput`;
    const filePrvClass = `.${id}-FilePrv`;
    const addFileClass = `.${id}-AddFile`;
    const changeFileClass = `.${id}-ChangeFile`;
    const dismissPicClass = `.${id}-dismissPic`;

    // Open WordPress Media Uploader
    jQuery("body").on("click", buttonClass, function (e) {
        e.preventDefault();

        const file_frame = wp.media({
            title: "Select or Upload an Image",
            library: { type: "image" }, // Allow only images
            button: { text: "Select Image" },
            multiple: false,
        });

        file_frame.on("select", function () {
            const attachment = file_frame.state().get("selection").first().toJSON();
            jQuery(fileInputClass).val(attachment.url);
            jQuery(filePrvClass).addClass("thumbnail").html(`<img src="${attachment.url}" alt="Selected Image">`);
            jQuery(addFileClass).hide();
            jQuery(changeFileClass).show();
            jQuery(dismissPicClass).show();
        });

        file_frame.open();
    });

    // Remove image and reset fields
    jQuery("body").on("click", dismissPicClass, function () {
        jQuery(changeFileClass).hide();
        jQuery(addFileClass).show();
        jQuery(dismissPicClass).hide();
        jQuery(fileInputClass).val("");
        jQuery(filePrvClass).removeClass("thumbnail").html("");
    });
}


// Function to handle multiple image upload
function FnehdWPMultFileUpload(id) {
    const inputField = jQuery(`.${id}-input-field`);
    
    // Ensure the correct preview container is selected dynamically
    function getPreviewContainer() {
        return jQuery(`.${id}-preview-container:visible`);
    }

    // Function to open WordPress Media Uploader
    function openMediaUploader(existingImages = []) {
        const file_frame = wp.media({
            title: "Select or Upload Images",
            library: { type: "image" },
            button: { text: "Select Images" },
            multiple: true,
        });

        file_frame.on("select", function () {
            const selection = file_frame.state().get("selection").toJSON();
            let newImages = [];
            const previewContainer = getPreviewContainer();

            selection.forEach((attachment) => {
                if (!existingImages.includes(attachment.url)) {
                    newImages.push(attachment.url);
                    previewContainer.append(`
                        <div class="flex image-preview thumbnail" style="display: inline-block; margin: 5px; position: relative;">
                            <img src="${attachment.url}" class="${id}-prev-upload" 
                                 style="width: 100px; height: 100px; object-fit: cover; border-radius: 5px;">
                            <button type="button" class="remove-image" style="position: absolute; top: 5px; right: 5px; background: red; color: white; border: none; cursor: pointer;">
                                X
                            </button>
                        </div>
                    `);
                }
            });

            let updatedImages = [...existingImages, ...newImages];
            inputField.val(updatedImages.join(",")); // Correctly updating the hidden field

            // Show/hide buttons based on image count
            jQuery(`.${id}-upload-button`).hide();
            jQuery(`.${id}-add-more`).show();
            jQuery(`.${id}-remove-all`).show();
        });

        file_frame.open();
    }

    // Handle Upload button click
    jQuery("body").on("click", `.${id}-upload-button`, function (e) {
        e.preventDefault();
        openMediaUploader();
    });

    // Handle Add More button click
    jQuery("body").on("click", `.${id}-add-more`, function (e) {
        e.preventDefault();
        let existingImages = inputField.val() ? inputField.val().split(",") : [];
        openMediaUploader(existingImages);
    });

    // Handle Remove Image click
    jQuery("body").on("click", `.${id}-preview-container .remove-image`, function (e) {
        e.preventDefault();
        jQuery(this).parent().remove();

        let remainingImages = [];
        getPreviewContainer().find("img").each(function () {
            remainingImages.push(jQuery(this).attr("src"));
        });

        inputField.val(remainingImages.join(",")); // update the hidden field

        // Toggle button visibility
        if (remainingImages.length === 0) {
            jQuery(`.${id}-upload-button`).show();
            jQuery(`.${id}-add-more`).hide();
            jQuery(`.${id}-remove-all`).hide();
        }
    });

    // Handle Remove All button click
    jQuery("body").on("click", `.${id}-remove-all`, function (e) {
        e.preventDefault();
        getPreviewContainer().empty();
        inputField.val(""); //Ensure input field is cleared
        jQuery(`.${id}-upload-button`).show();
        jQuery(`.${id}-add-more`).hide();
        jQuery(`.${id}-remove-all`).hide();
    });

    //Ensure buttons inside collapsible divs work when shown
    jQuery("body").on("shown.bs.collapse", function () {
        jQuery(`.${id}-upload-button, .${id}-add-more, .${id}-remove-all`).prop("disabled", false);
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



