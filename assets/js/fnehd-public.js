jQuery(document).ready(function($){
	
	//Ajax Loader
	$(document).ajaxStart(function() {
	  $("#fnehd-loader").show();
	});
	$(document).ajaxStop(function() {
	  $("#fnehd-loader").hide();
	});
	
	//Collapse DIvs Scripts
	$('body').on('shown.bs.collapse', function () {
		$(function () {
			$('[data-toggle="popover"]').popover({
				container: 'body'
			});
		});
		//Multiple Select Inputs
		multChoiceSelect({ selectID: 'fnehd_eligible_individuals'});
		multChoiceSelect({ selectID: 'fnehd_accepted_ages'});
		multChoiceSelect({ selectID: 'fnehd_specific_services'});
	});
	
	//Point to collapsable dialogs
	var dialogs = ['fnehd-bitcoin-deposit-form-dialog', 'fnehd-bitcoin-withdraw-form-dialog'];

	$("body").on("click", "#fnehd-bitcoin-pay", function(e) {
		e.preventDefault();
		for(i=0;i<dialogs.length;i++){ 
			$("html, #escot_front_wrapper").animate({scrollTop: $('#'+dialogs[i]).offset().top}, 'slow');   
		}
	});
	
	
	//User Signin
	$("#fnehd-user-login-form").on('submit', function(e){
		e.preventDefault();
		var datatopost = $(this).serializeArray();
		$.ajax({
			url: fnehd.ajaxurl,
			type: "POST",
			data: datatopost,
			success: function(response) {
				if (response.success) {
					Swal.fire({
					  icon: 'success',
					  title: response.data.message,
					  showConfirmButton: false,
					  timer: 1500
					});
					window.location.replace(response.data.redirect);
				} else {
				    Swal.fire({
					    icon: 'error',
					    title: response.data.message,
					    showConfirmButton: false,
					    timer: 3000
					});
				}
			}
		});
	});
	
	
	
	//User Signup
	$("#fnehd-user-signup-form").on('submit', function(e){
		e.preventDefault();
		var datatopost = $(this).serializeArray();

		$.ajax({
			url: fnehd.ajaxurl,
			type: "POST",
			data: datatopost,
			success: function(response) {
				if (response.success) {
					Swal.fire({
					  icon: 'success',
					  title: response.data.message,
					  showConfirmButton: false,
					  timer: 1500
					});
					window.location.replace(response.data.redirect);
				} else {
				    Swal.fire({
					    icon: 'error',
					    title: response.data.message,
					    showConfirmButton: false,
					    timer: 1500
					});
				}
			}
		});
	});
	
	
	//User Logout
	$("#FnehdLogOutFrontNavItem").on("click", function(e){	
		e.preventDefault();
		swal.fire({
		    title: fnehd.swal.warning.title,
			text: fnehd.swal.warning.text,
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: fnehd.swal.user.logout_confirm,
		}).then((result) => {
			if (result.value){
				$.ajax({
					url: fnehd.ajaxurl,
					type: "POST",
					data: {'action':'fnehd_user_logout',},
					success: function(response) {
						if (response.success) {
							Swal.fire({
								icon: 'success',
								title: response.data.message,
								showConfirmButton: false, 
							    timer: 1500,
							});
							window.location = response.data.redirect;
						}
					}	
				});
			}
		});//then
	});
	
	
	//Reload User Account
	function reloadUser(){
		$.ajax({	
			url: fnehd.ajaxurl,
			type: "POST",
			data: {'action':'fnehd_reload_user_account',},
			success: function(response) {
				$("#fnehd-edit-account").html(response.data);
			}
		});
	}
	
	// Send password reset link
	$("body").on("submit", "#fnehd-pass-reset-link-form", function (e) {
		e.preventDefault();
		var form_data = new FormData(this);

		$.ajax({
			url: fnehd.ajaxurl,
			type: "POST",
			data: form_data,
			contentType: false,
			cache: false,
			processData: false,
			success: function (response) {
				if (response.success) { // Proper conditional check
					Swal.fire({
						icon: "success",
						title: response.data.message,
						showConfirmButton: true,
					});
				} else {
					Swal.fire({
						icon: "error",
						title: response.data.message,
						showConfirmButton: false,
						timer: 1500,
					});
				}
			},
			error: function () { // Handle AJAX errors
				Swal.fire({
					icon: "error",
					title: "Something went wrong!",
					showConfirmButton: false,
					timer: 1500,
				});
			},
		});
	});

	
	// Reset password form submission
	$("body").on("submit", "#fnehd-pass-reset-form", function (e) {
		e.preventDefault();

		Swal.fire({
			title: fnehd.swal.warning.title,
			text: fnehd.swal.warning.text,
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: fnehd.swal.user.reset_pass_confirm,
		}).then((result) => {
			if (result.isConfirmed) { 
				var form_data = new FormData(this);

				$.ajax({
					url: fnehd.ajaxurl,
					type: "POST",
					data: form_data,
					contentType: false,
					cache: false,
					processData: false,
					success: function (response) {
						if (response.success) { 
							Swal.fire({
								icon: "success",
								title: response.data.message,
								showConfirmButton: true,
							});
						} else {
							Swal.fire({
								icon: "error",
								title: response.data.message,
								showConfirmButton: false,
								timer: 1500,
							});
						}
					},
					error: function () { // Handle AJAX errors
						Swal.fire({
							icon: "error",
							title: "Something went wrong!",
							text: "Please try again later.",
							showConfirmButton: false,
							timer: 2000,
						});
					},
				});
			}
		});
	});

	//update User	
	$("body").on('submit', '#fnehd-edit-user-form', function(e){
		e.preventDefault();
			  swal.fire({
			  title: fnehd.swal.warning.title,
				text: fnehd.swal.warning.text,
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: fnehd.swal.user.update_confirm,
			}).then((result) => {
			   if (result.value){
			   var form_data = new FormData( this );
				 $.ajax({
					url: fnehd.ajaxurl,
					type: "POST",
					data: form_data,
					contentType: false,
					cache: false,
					processData: false,
					success: function(response) { 
						if(response.success){
							Swal.fire({
							   icon: 'success',
							   title: response.data.message,
							   showConfirmButton: false, 
							   timer: 1500,
							});
							reloadUser();
							$("html, .fnehd-main-panel").animate({scrollTop: $('.fnehd-main-panel').offset().top}, 'slow'); 
						} else{
							Swal.fire({
							   icon: 'error',
							   title: response.response.status,
							   showConfirmButton: false, 
							   timer: 1500,
							});
						}
					}	
			   });
			}
		});//then
	});
		
	//update user password	
	$("body").on('submit', '#EditFnehdUserPassForm', function(e){
		e.preventDefault();
			  swal.fire({
			  title: fnehd.swal.warning.title,
				text: fnehd.swal.user.update_pass_text,
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: fnehd.swal.user.update_pass_confirm,
			}).then((result) => {
			   if (result.value){
		   
			   var form_data = $(this).serialize();
				 $.ajax({
					url: fnehd.ajaxurl,
					type: "POST",
					data: form_data,
					success: function(response) { 
						if(response.success){
							Swal.fire({
							   icon: 'success',
							   title: response.data.message,
							   showConfirmButton: false, 
							   timer: 1500,
							});
							location.reload();
						} else{
							Swal.fire({
								icon: 'error',
								title: response.data.message,
							});
						}
					}	
			   });
			}
		});//then
	});
	
	
	
    const gridContainer = $('#fnehd-housing-grid');
	const paginationContainer = $('#pagination');
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
		const shelter_url = $("#fnehd-listing-wrapper").data("shelter-url");

		if (view === "grid") {
			$('#shelter-table-view').hide();
			$('#fnehd-listing-wrapper').show();
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
			$('#shelter-table-view').hide();
			$('#fnehd-listing-wrapper').show();
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
		   $('#shelter-table-view').show();
		   $('#fnehd-listing-wrapper').hide();
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
			$('#fnehd-grid-view').on('click', function () {
				currentView = 'grid';
				gridContainer.removeClass('list-view').addClass('grid-view');
				$(this).addClass('bg-secondary text-white').removeClass('bg-gray-200 text-gray-700');
				$('#fnehd-list-view, #fnehd-table-view').removeClass('bg-secondary text-white').addClass('bg-gray-200 text-gray-700');
				renderItems(items, 1, currentView);
				setupPagination(items, currentView);
			});

			$('#fnehd-list-view').on('click', function () {
				currentView = 'list';
				gridContainer.removeClass('grid-view').addClass('list-view');
				$(this).addClass('bg-secondary text-white').removeClass('bg-gray-200 text-gray-700');
				$('#fnehd-grid-view, #fnehd-table-view').removeClass('bg-secondary text-white').addClass('bg-gray-200 text-gray-700');
				renderItems(items, 1, currentView);
				setupPagination(items, currentView);
			});

			$('#fnehd-table-view').on('click', function () {
				currentView = 'table';
				$(this).addClass('bg-secondary text-white').removeClass('bg-gray-200 text-gray-700');
				$('#fnehd-grid-view, #fnehd-list-view').removeClass('bg-secondary text-white').addClass('bg-gray-200 text-gray-700');
				renderItems(items, 1, currentView);
			});
		});
	}
	

    // Load listings based on selected filter
	const filterDropdown = $('#shelter-filter'); 
    function loadFilteredListings() {
        const selectedValue = filterDropdown.val(); // Get selected option
        let apiUrl;

        // Determine which API URL to use
        if (selectedValue === 'available') {
            apiUrl = fnehd.available_shelters_rest_url;
			$("#fne-shelter-count").text(fnehd.available_shelter_count);
        } else if (selectedValue === 'unavailable') {
			$("#fne-shelter-count").text(fnehd.unavailable_shelter_count);
            apiUrl = fnehd.unavailable_shelters_rest_url;
        } else {
			$("#fne-shelter-count").text(fnehd.total_shelter_count);
            apiUrl = fnehd.all_shelters_rest_url;
        }

        // Reload the listings with the new API URL
        initializeListingGrid(apiUrl);
    }

    // Attach event listener to dropdown
    filterDropdown.on('change', loadFilteredListings);

    // Initial load with all shelters
    initializeListingGrid(fnehd.all_shelters_rest_url);
	
	

	// Listing Search
	let lastValidSearchParams = null; // Store last valid search state

	function fetchSearchListings() {
		let searchParams = {
			search: $('#search').val().trim() || null,
			gender: getCheckedValues('gender'),
			age: getCheckedValues('age'),
			pets: getCheckedValues('pets'),
			assistance: getCheckedValues('assistance'),
			shelter_type: getCheckedValues('shelter_type'),
			beds: $('#beds').val().trim() || null
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
		let values = $(`input[name="${category}"]:checked`).map(function () {
			return this.value;
		}).get();
		
		return values.length > 0 ? values : null; // Return null if nothing is selected
	}

	// Event listeners to handle progressive filtering
	$('#search, #beds').on('input', fetchSearchListings);
	$('input[type=checkbox]').on('change', function () {
		fetchSearchListings();
	});
		
	

	
	
    //Shelter gallery 
	const mainImage = document.getElementById('mainImage');
	const thumbnails = document.querySelectorAll('.thumbnail');
	const prevBtn = document.querySelector('.prev-btn');
	const nextBtn = document.querySelector('.next-btn');
	const thumbnailsContainer = document.querySelector('.thumbnails');

	let currentIndex = 0; // Track current main image index

	// Update main image and active thumbnail
	function updateMainImage(index) {
		currentIndex = index;
		mainImage.src = thumbnails[index].getAttribute('data-full');

		// Update active thumbnail
		thumbnails.forEach(tn => tn.classList.remove('active'));
		thumbnails[index].classList.add('active');
	}

	// Handle thumbnail click
	thumbnails.forEach((thumbnail, index) => {
		thumbnail.addEventListener('click', () => {
			updateMainImage(index);
		});
	});

	// Handle next button click
	nextBtn.addEventListener('click', () => {
		let nextIndex = (currentIndex + 1) % thumbnails.length; // Loop to start
		updateMainImage(nextIndex);
	});

	// Handle previous button click
	prevBtn.addEventListener('click', () => {
		let prevIndex = (currentIndex - 1 + thumbnails.length) % thumbnails.length; // Loop to end
		updateMainImage(prevIndex);
	});
   

       
});


const listings = [
	{ name: 'House 1', address: '1690 Paris Street, Aurora, CO', lat: 40.7128, lng: -74.0060, 	id: 1 },
	{ name: 'House 2', address: 'Boston, MA', lat: 42.3601, lng: -71.0589, id: 3 },
	{ name: 'House 3', address: 'San Francisco, CA', lat: 37.7749, lng: -122.4194, id: 3 }
];

function initMap() {
    const map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: 40.7128, lng: -74.0060 },
        zoom: 5
    });

    const customIcon = {
        url: "http://localhost/fne-housing/wp-content/plugins/fne-housing/assets/img/fnehousing-icon.png",
        scaledSize: new google.maps.Size(32, 32),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(25, 50)
    };

    const defaultIcon = null; // Uses Google's default marker icon

    // Store markers for listings
    const listingMarkers = new Map();

    // Convert listings addresses to a Set for quick lookup
    const listingAddresses = new Map();
    listings.forEach((listing) => {
        listingAddresses.set(listing.address.toLowerCase(), listing);

        const marker = new google.maps.Marker({
            position: { lat: listing.lat, lng: listing.lng },
            map: map,
            title: listing.name,
            icon: customIcon
        });

        const infoWindow = new google.maps.InfoWindow({
            content: `
                <div class="listing-card flex items-center border rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300 p-4">
                    <div>
                        <img src="http://localhost/fne-housing/wp-content/plugins/fne-housing/assets/img/anschutz-campus.jpg" alt="House" width="300" class="w-full h-48 object-cover">
                    </div>
                    <div class="details ml-4">
                        <h3 class="text-lg font-bold">${listing.name}</h3>
                        <p class="text-gray-700">Bed Capacity: ${listing.address}</p>
                        <p class="text-gray-500">Location: ${listing.address}</p>
                        <div class="mt-2">
                            <a href="http://localhost/fne-housing/?endpoint=view_shelter&shelter_id=1" class="text-blue-500 hover:text-blue-700">View Details</a>
                        </div>
                    </div>
                </div>
            `
        });

        marker.addListener('click', () => {
            infoWindow.open(map, marker);
        });

        listingMarkers.set(listing.address.toLowerCase(), marker);
    });

    // Create and style the search bar
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

    // Attach the SearchBox to the input
    const searchBox = new google.maps.places.SearchBox(input);
    map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);

    // Bias the search box results towards the map's current viewport
    map.addListener('bounds_changed', () => {
        searchBox.setBounds(map.getBounds());
    });

    // Store search result markers separately
    let searchMarkers = [];

    // Handle search results
    searchBox.addListener('places_changed', () => {
        const places = searchBox.getPlaces();

        if (places.length === 0) {
            return;
        }

        // Clear out old search markers
        searchMarkers.forEach((marker) => marker.setMap(null));
        searchMarkers = [];

        const bounds = new google.maps.LatLngBounds();

        places.forEach((place) => {
            if (!place.geometry || !place.geometry.location) {
                console.log("Returned place contains no geometry");
                return;
            }

            const address = place.formatted_address.toLowerCase();

            // If the searched location is in `listings`, center the map and do not add a new marker
            if (listingAddresses.has(address)) {
                const existingMarker = listingMarkers.get(address);
                if (existingMarker) {
                    map.setCenter(existingMarker.getPosition());
                    map.setZoom(12);
                }
                return;
            }

            // Use default marker for any other location
            const marker = new google.maps.Marker({
                map: map,
                position: place.geometry.location,
                title: place.name,
                icon: defaultIcon
            });

            const infoWindow = new google.maps.InfoWindow({
                content: `<h4>${place.name}</h4><p>${place.formatted_address || ''}</p>`
            });

            marker.addListener('click', () => {
                infoWindow.open(map, marker);
            });

            searchMarkers.push(marker);

            if (place.geometry.viewport) {
                bounds.union(place.geometry.viewport);
            } else {
                bounds.extend(place.geometry.location);
            }
        });

        map.fitBounds(bounds);
    });
}
