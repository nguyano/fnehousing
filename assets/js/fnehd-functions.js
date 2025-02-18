
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


const gridContainer = jQuery('#fnehd-housing-grid');
const paginationContainer = jQuery('#pagination');
const perPage = 6; // items per page

// Function to fetch data from a given REST API URL
async function fetchListings(restUrl, postParams = {}) {
	try {
		const response = await fetch(restUrl, {
			method: 'POST',
			headers: { 'Content-Type': 'application/json' },
			body: JSON.stringify(postParams)
		});

		if (!response.ok) {
			throw new Error(`HTTP error! Status: ${response.status}`);
		}

		return await response.json();
	} catch (error) {
		console.error('Error fetching listings:', error);
		return [];
	}
}

// Render items based on view type
function renderItems(items, page, view = "grid") {
	const listingsContainer = gridContainer.find(".listings-container");
	listingsContainer.empty(); // Clear existing items
	const start = (page - 1) * perPage;
	const end = start + perPage;
	const shelter_url = jQuery("#fnehd-listing-wrapper").data("shelter-url");

	if (view === "grid") {
		jQuery('#shelter-table-view').hide();
		jQuery('#fnehd-listing-wrapper').show();
		items.slice(start, end).forEach((item) => {
			const availabilityBadge = item.availability === 'Available'
				? `<span class="availability-badge bg-green-500">Available (${item.available_beds})</span>`
				: `<span class="availability-badge bg-red-500">Unavailable</span>`;

			const template = `
				<div class="listing-card border rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300">
					<div class="relative">
						<img src="${item.main_image ? item.main_image : fnehd.default_shelter_img}" alt="${item.shelter_name}" class="w-full h-48 object-cover">
						${availabilityBadge}
					</div>
					<div class="p-4">
						<h3 class="text-lg font-bold">${item.shelter_name}</h3>
						<p class="text-gray-700"><b>Organization:</b> ${item.shelter_organization}</p>
						<p class="text-gray-700"><b>Bed Capacity:</b> ${item.bed_capacity}</p>
						<p class="text-gray-500"><b>Location:</b> ${item.address}</p>
						<div class="mt-4">
							<a href="${shelter_url}&shelter_id=${item.shelter_id}" class="btn btn-outline-primary btn-sm btn-round text-blue hover:text-blue-700">
								View Details
							</a>
							<a href="#" class="btn btn-outline-success btn-sm btn-round text-blue hover:text-blue-700">
								Quick Update
							</a>
						</div>
					</div>
				</div>`;
			listingsContainer.append(template);
		});
	} else if (view === "list") {
		jQuery('#shelter-table-view').hide();
		jQuery('#fnehd-listing-wrapper').show();
		items.slice(start, end).forEach((item) => {
			const availabilityBadge = item.availability === 'Available'
				? `<span class="availability-badge bg-green-500">Available (${item.available_beds})</span>`
				: `<span class="availability-badge bg-red-500">Unavailable</span>`;
	
			const template = `
				<div class="listing-card flex items-center border rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300 p-4">
					<div class="relative">
						<img src="${item.main_image ? item.main_image : fnehd.default_shelter_img}" alt="${item.shelter_name}" class="w-full h-48 object-cover">
						${availabilityBadge}
					</div>
					<div class="details ml-4">
						<h3 class="text-lg font-bold">${item.shelter_name}</h3>
						<p class="text-gray-700"><b>Organization:</b> ${item.shelter_organization}</p>
						<p class="text-gray-700"><b>Bed Capacity:</b> ${item.bed_capacity}</p>
						<p class="text-gray-500"><b>Location:</b> ${item.address}</p>
						<div class="mt-2">
							<a href="${shelter_url}&shelter_id=${item.shelter_id}" class="btn btn-outline-primary btn-sm btn-round text-blue hover:text-blue-700">
								View Details
							</a>
							<a href="#" class="btn btn-outline-success btn-sm btn-round text-blue hover:text-blue-700">
								Quick Update
							</a>
						</div>
					</div>
				</div>`;
			listingsContainer.append(template);
		});
	} else if (view === "table") {
	   jQuery('#shelter-table-view').show();
	   jQuery('#fnehd-listing-wrapper').hide();
	}
}

// Setup pagination
function setupPagination(items, view) {
	const totalPages = Math.ceil(items.length / perPage);

	if (totalPages === 0) {
		paginationContainer.empty();
		return;
	}

	paginationContainer.pagination({
		dataSource: Array.from({ length: totalPages }, (_, i) => i + 1),
		pageSize: 1,
		callback: function (data, options) {
			const currentPage = options.pageNumber || 1;
			renderItems(items, currentPage, view);
		},
	});
}

// Function to initialize the listing grid
function initializeListingGrid(restUrl, postParams = {}) {
	fetchListings(restUrl, postParams).then(items => {

		if (items.length === 0) {
			gridContainer.html('<p>No listings available.</p>');
			return;
		}

		let currentView = 'grid'; // Default view
		renderItems(items, 1, currentView);
		setupPagination(items, currentView);

		// Event listeners for view toggle buttons
		jQuery('#fnehd-grid-view').on('click', function () {
			currentView = 'grid';
			gridContainer.removeClass('list-view').addClass('grid-view');
			jQuery(this).addClass('bg-secondary text-white').removeClass('bg-gray-200 text-gray-700');
			jQuery('#fnehd-list-view, #fnehd-table-view').removeClass('bg-secondary text-white').addClass('bg-gray-200 text-gray-700');
			renderItems(items, 1, currentView);
			setupPagination(items, currentView);
		});

		jQuery('#fnehd-list-view').on('click', function () {
			currentView = 'list';
			gridContainer.removeClass('grid-view').addClass('list-view');
			jQuery(this).addClass('bg-secondary text-white').removeClass('bg-gray-200 text-gray-700');
			jQuery('#fnehd-grid-view, #fnehd-table-view').removeClass('bg-secondary text-white').addClass('bg-gray-200 text-gray-700');
			renderItems(items, 1, currentView);
			setupPagination(items, currentView);
		});

		jQuery('#fnehd-table-view').on('click', function () {
			currentView = 'table';
			jQuery(this).addClass('bg-secondary text-white').removeClass('bg-gray-200 text-gray-700');
			jQuery('#fnehd-grid-view, #fnehd-list-view').removeClass('bg-secondary text-white').addClass('bg-gray-200 text-gray-700');
			renderItems(items, 1, currentView);
		});
	});
}


// Load listings based on selected filter
function loadFilteredListings() {
	const selectedValue = jQuery('#shelter-filter').val(); // Get selected option
	let apiUrl;

	// Determine which API URL to use
	if (selectedValue === 'available') {
		apiUrl = fnehd.available_shelters_rest_url;
		jQuery("#fne-shelter-count").text(fnehd.available_shelter_count);
	} else if (selectedValue === 'unavailable') {
		jQuery("#fne-shelter-count").text(fnehd.unavailable_shelter_count);
		apiUrl = fnehd.unavailable_shelters_rest_url;
	} else {
		jQuery("#fne-shelter-count").text(fnehd.total_shelter_count);
		apiUrl = fnehd.all_shelters_rest_url;
	}

	// Reload the listings with the new API URL
	initializeListingGrid(apiUrl);
}


// Listing Search
function fetchSearchListings() {
	let lastValidSearchParams = null; // Store last valid search state
	let searchParams = {
		search: jQuery('#search').val().trim() || null,
		gender: getCheckedValues('gender'),
		age: getCheckedValues('age'),
		pets: getCheckedValues('pets'),
		assistance: getCheckedValues('assistance'),
		shelter_type: getCheckedValues('shelter_type'),
		beds: jQuery('#beds').val().trim() || null
	};

	// Remove empty filters
	Object.keys(searchParams).forEach(key => {
		if (!searchParams[key] || (Array.isArray(searchParams[key]) && searchParams[key].length === 0)) {
			delete searchParams[key];
		}
	});

	// If no filters are applied, reset to all listings
	if (Object.keys(searchParams).length === 0) {
		lastValidSearchParams = null;
		initializeListingGrid(fnehd.all_shelters_rest_url); // Show all listings
		return;
	}

	// Fetch filtered listings
	fetchListings(fnehd.shelters_search_rest_url, searchParams).then(listings => {
		if (Array.isArray(listings) && listings.length > 0) {
			lastValidSearchParams = { ...searchParams }; // Store the last valid search
			initializeListingGrid(fnehd.shelters_search_rest_url, searchParams); // Show results
		} else {
			// If no results, check if we have a previous valid search
			if (lastValidSearchParams && JSON.stringify(searchParams) !== JSON.stringify(lastValidSearchParams)) {
				initializeListingGrid(fnehd.shelters_search_rest_url, lastValidSearchParams);
			} else {
				initializeListingGrid(fnehd.all_shelters_rest_url); // Show all listings
			}
		}
	}).catch(error => {
		console.error('Error fetching filtered listings:', error);
		initializeListingGrid(fnehd.all_shelters_rest_url); // Fallback to all listings in case of error
	});
}


// Function to retrieve checked checkbox values dynamically
function getCheckedValues(category) {
	let values = jQuery(`input[name="${category}"]:checked`).map(function () {
		return this.value;
	}).get();
	
	return values.length > 0 ? values : null; // Return null if nothing is selected
}



//Maps Function
async function initMap() {
    const map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: 39.7392, lng: -104.9903 }, // Centered at Denver, CO
        zoom: 10
    });

    const customIcon = {
        url: fneIconUrl,
        scaledSize: new google.maps.Size(32, 32),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(25, 50)
    };

    const defaultIcon = null; // Uses Google's default marker icon
    const geocoder = new google.maps.Geocoder();

    let listingMarkers = new Map();

    try {
        const listings = await fetchListings(fnehd.all_shelters_rest_url);

        for (const listing of listings) {
            if (!listing.address) continue; // Skip if no address

            // Convert address to coordinates using Geocoding API
            const position = await geocodeAddress(geocoder, listing.address);

            if (position) {
                const marker = new google.maps.Marker({
                    position,
                    map: map,
                    title: listing.shelter_name,
                    icon: customIcon
                });

                const infoWindow = new google.maps.InfoWindow({
                    content: `
                        <div class="listing-card flex items-center border rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300 p-4">
                            <div>
                                <img src="${listing.main_image}" alt="House" width="300" class="w-full h-48 object-cover">
                            </div>
                            <div class="details ml-4">
                                <h3 class="text-lg font-bold">${listing.shelter_name}</h3>
                                <p class="text-gray-700">Location: ${listing.address}</p>
                                <div class="mt-2">
                                    <a href="`+fneShelterUrl+`&shelter_id=${listing.shelter_id}" class="text-blue-500 hover:text-blue-700">View Details</a>
                                </div>
                            </div>
                        </div>
                    `
                });

                marker.addListener('click', () => {
                    infoWindow.open(map, marker);
                });

                listingMarkers.set(listing.address.toLowerCase(), marker);
            }
        }
    } catch (error) {
        console.error('Error initializing map:', error);
    }
	
	const input = document.createElement('input');
	input.type = 'text';
	input.id = 'search-bar';
	input.placeholder = 'Search location...';
	input.style.cssText = `
		position: absolute;
		margin-top: 15px;
		margin-left: 550px;
		transform: translateX(-50%);
		width: 550px;
		padding: 15px;
		font-size: 14px;
		z-index: 9999;
		background-color: white;
		border: 1px solid rgb(204, 204, 204);
		border-radius: 30px;
		height: 40px;
		border: solid #d1d1d1;
	`;
	document.body.appendChild(input);

	const searchBox = new google.maps.places.SearchBox(input);
	map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);

	// Update search bounds when map moves
	map.addListener('bounds_changed', () => {
		searchBox.setBounds(map.getBounds());
	});

	// Handle search box input
	searchBox.addListener('places_changed', () => {
		const places = searchBox.getPlaces();

		if (places.length === 0) return;

		const bounds = new google.maps.LatLngBounds();
		places.forEach((place) => {
			if (!place.geometry || !place.geometry.location) return;
			bounds.extend(place.geometry.location);
		});

		map.fitBounds(bounds);
	});

}

/**
 * Convert an address to lat/lng using Google Geocoding API
 */
function geocodeAddress(geocoder, address) {
    return new Promise((resolve, reject) => {
        geocoder.geocode({ address: address }, (results, status) => {
            if (status === "OK" && results[0]) {
                resolve(results[0].geometry.location);
            } else {
                console.warn(`Geocoding failed for address: ${address}, Status: ${status}`);
                resolve(null);
            }
        });
    });
}