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


    // Attach event listener to dropdown
	const filterDropdown = jQuery('#shelter-filter'); 
    filterDropdown.on('change', loadFilteredListings);

    // Initial load with all shelters
    initializeListingGrid(fnehd.all_shelters_rest_url);
	
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


