jQuery(document).ready(function($){
	
	if( ("#fnehd-settings-panel").length ){
		//remove WP table wrap arround setting options
		$("#fnehd-settings-panel tr").not('.fnehd-tr').replaceWith(function() { return $(this).contents();});
		$("#fnehd-settings-panel td").not('.fnehd-td').replaceWith(function() { return $(this).contents();});
		$("#fnehd-settings-panel th").not('.fnehd-th').replaceWith(function() { return $(this).contents();});
		$("#fnehd-settings-panel table").not('.fnehd-tbl').replaceWith(function() { return $(this).contents();});
		$("#fnehd-settings-panel tbody").not('.fnehd-tbody').replaceWith(function() { return $(this).contents();});
		$("#fnehd-settings-panel h2").not('.fnehd-h2').replaceWith(function() { return $(this).contents();});
	};
	
	//Collapse DIvs & Modals Scripts
	$('body').on('shown.bs.collapse shown.bs.modal', function () {
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

	//Ajax Loader
	let activeAjaxCalls = 0;
	let isTabActive = true;// Track page/tab visibility
	document.addEventListener('visibilitychange', function() {//Detect when the tab is active or inactive
		isTabActive = !document.hidden;
	});
	$(document).ajaxStart(function() {
		activeAjaxCalls++;
		if (activeAjaxCalls === 1 && isTabActive) {
			$('#fnehd-loader').show();
		}
	});
	$(document).ajaxStop(function() {
		activeAjaxCalls = 0; 
		if (isTabActive) {
			$('#fnehd-loader').hide();
		}
	});

	//Optional: Ensure the loader is hidden if the user returns after the event is completed
	document.addEventListener('visibilitychange', function() {
		if (isTabActive && activeAjaxCalls === 0) {
			$('#fnehd-loader').hide();
		}
	});
		
	//Focus on DB Restore Form
	$('body').on('click', '#FnehdQuikResDB', function () {
		$("html, .fnehd-main-panel").animate({scrollTop: $('.fnehd-admin-forms').offset().top}, 'slow'); 
	});	 
	
	

	//Remove Wp Default Form Style
	$("body").removeClass("wp-core-ui select"); 


	//Highlight Active Navbar Link
	$('.fnehd-nav-link').each(function(){
		if ($(this).prop('href') == window.location.href) {
			$(this).addClass('active'); 
			$(this).parents('li').addClass('active');
		} else 
		{ 
		  $(this).removeClass('active'); 
		}
	});
	//Get URL Token value for user_id & SA_id (GET Response)
	var urlParams = new URLSearchParams(window.location.search);
	if(urlParams.get('user_id')) { $("#FnehdUserMenuItem").addClass('active'); }
	if(urlParams.get('shelter_id')) { $("#FnehdShelterMenuItem").addClass('active'); }
	if(urlParams.get('dispute_id')) { $("#FnehdSupportMenuItem").addClass('active'); }
	
	
	//Minimize Sidebar 	
	$("body").on('click', '#minimizeSidebar', function() {
	 $("#bodyWrapper").toggleClass("sidebar-mini"); 
	}); 


	//Minimize Sidebar 	
	$("body").on('click', '.navbar-toggler', function() {	  
	 $("html").toggleClass("nav-open"); 
	}); 

	//Theme toggle button 		
	$("#bodyWrapper").on('click', '#ToggleLightMode', function() {
		$("#bodyWrapper").removeClass("dark-edition");
		$("#ToggleLightMode").attr("id", "ToggleDarkMode");
		$("#ToggleDarkMode").html('<i class="text-dark fnehd-nav-icon fa fa-moon"></i>');
	});
	$("#bodyWrapper").on('click', '#ToggleDarkMode', function() {
		$("#bodyWrapper").addClass("dark-edition");
		$("#ToggleDarkMode").attr("id", "ToggleLightMode");
		$("#ToggleLightMode").html('<span class="text-light">'+fnehd.light_svg+'</span>');
	});
	
	 $("#bodyWrapper").on('click', '#ToggleLightModeMbl', function() {
		$("#bodyWrapper").removeClass("dark-edition");
		$("#ToggleLightModeMbl").attr("id", "ToggleDarkModeMbl");
		$("#ToggleDarkModeMbl").html('<i class="text-dark fnehd-nav-icon fa fa-moon"></i>');
	});
	$("#bodyWrapper").on('click', '#ToggleDarkModeMbl', function() {
		$("#bodyWrapper").addClass("dark-edition");
		$("#ToggleDarkModeMbl").attr("id", "ToggleLightModeMbl");
		$("#ToggleLightModeMbl").html('<span class="text-light">'+fnehd.light_svg+'</span>');
	});


	//Initiate Bootstrap Popover
	$(function () {
		$('[data-toggle="popover"]').popover({
			container: 'body'
		});
	});
		
	
	
	//DataTable Checkbox multiselect	
	function fnehdMultiSelect({
		checkAllId,
		checkAllAltId,
		checkboxClass,
		selectedCountClass,
	}) {
		// Handle "Select All" checkbox click
		$('body').on('click', `#${checkAllId}, #${checkAllAltId}`, function () {
			const isChecked = this.checked;
			$(`.${checkboxClass}`).prop('checked', isChecked);
			$(`#${checkAllId}, #${checkAllAltId}`).prop('checked', isChecked);
			$(`.${selectedCountClass}`).html($(`input.${checkboxClass}:checked`).length +' '+ fnehd.swal.success.checkbox_select_title);
		});

		// Handle individual checkbox click
		$('body').on('click', `.${checkboxClass}`, function () {
			const allChecked = $(`.${checkboxClass}:checked`).length === $(`.${checkboxClass}`).length;
			$(`#${checkAllId}, #${checkAllAltId}`).prop('checked', allChecked);
			$(`.${selectedCountClass}`).html($(`input.${checkboxClass}:checked`).length +' '+ fnehd.swal.success.checkbox_select_title);
		});
	}
	fnehdMultiSelect({
		checkAllId: 'fnehd-select-all',
		checkAllAltId: 'fnehd-select-all2',
		checkboxClass: 'fnehd-checkbox',
		selectedCountClass: 'fnehd-selected',
	});
	fnehdMultiSelect({
		checkAllId: 'fnehd-select-all3',
		checkAllAltId: 'fnehd-select-all4',
		checkboxClass: 'fnehd-checkbox2',
		selectedCountClass: 'fnehd-selected2',
	});
	
	//fold wp menu
    if(fnehd.wpfold){
		document.body.className+=' folded';
	}
	
	
	// Function - Restore Progress Poll
	function fnehdRestorePollProgress() {
		function update() {
			$.ajax({
				type: "POST",
				url: fnehd.ajaxurl,
				data: { action: "fnehd_dbrestore_progress" },
				success: function (response) {
					if (response.success) {
						const { percent_complete, message, completed } = response.data;

						$("#ProgressBar").css("width", `${percent_complete}%`).attr("aria-valuenow", percent_complete);
						$(".percent").text(`${percent_complete}%`);
						$("#progress-tle").text(message);

						if (!completed) {
							requestAnimationFrame(update); // Schedule the next update
						} else {
							Swal.fire({
							   icon: 'success',
							   title: response.data.message,
							   showConfirmButton: false, 
							});
							location.reload();
						}
					} else {
						console.error(fnehd.swal.dbbackup.restore_fail_title, response);
					}
				},
				error: function () {
					console.error(fnehd.swal.dbbackup.restore_poll_fail_text);
				},
			});
		}

		requestAnimationFrame(update);
	}

    // Event - Restore Database from uploaded file (Quick Restore)
	$("body").on('submit', "#RestoreDBForm", function(e){
		e.preventDefault();
		var BkUpFile = $(this).attr("id");

		swal.fire({
			title: fnehd.swal.warning.title,
			text: fnehd.swal.warning.db_file_restore_text,
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#d33",
			cancelButtonColor: "#3085d6",
			confirmButtonText: fnehd.swal.dbbackup.restore_confirm,
		}).then((result) => {
			if (result.value) {
				$.ajax({
					type: 'POST',
					url: fnehd.ajaxurl,
					data: new FormData(this),
					contentType: false,
					cache: false,
					processData:false,
					beforeSend: function () {
						$("#fnehd-loader").css("background-color", "#010e31d1");
						$("#fnehd-loader").html(`
							<h4 class="text-center text-success" id="progress-tle">`+fnehd.swal.dbbackup.restore_init_text+`</h4>
							<div class="progress" id="progressAjax">
								<div class="progress-bar progress-bar-success" id="ProgressBar"></div>
								<div class="percent text-dark">0%</div>
							</div>
						`);
					},
					success: function (response) {
						if (response.success) {
							fnehdRestorePollProgress(); // Begin polling for progress
						} else {
							Swal.fire(fnehd.swal.warning.error_title, response.data.message, "error");
						}
					},
					error: function () {
						Swal.fire({
							icon: "error",
							title: fnehd.swal.dbbackup.restore_fail_title,
							text: fnehd.swal.dbbackup.restore_fail_text,
						});
					},
				});
			}
		});
	});
	
	
	//Export to Excel
	$("body").on("click", "#fnehd-export-to-excel", function(e){
		e.preventDefault();
		var shelter_id = $('#fnehd-admin-area-title').data('shelter-id'); 
		var action = $(this).data('action');
		swal.fire({
			title: fnehd.swal.warning.export_excel_title,
			text: fnehd.swal.warning.export_excel_text,
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			cancelButtonColor: '#3085d6',
			confirmButtonText: fnehd.swal.warning.export_excel_confirm,
		}).then((result) => {
			if (result.value){
				
				var data = {'action':action, 'shelter_id':shelter_id,};
	
				$.post(fnehd.ajaxurl, data, function(response) {
					var blob = new Blob([response.data], {type: 'application/vnd.ms-excel'});
					var downloadUrl = URL.createObjectURL(blob);
					var a = document.createElement("a");
					a.href = downloadUrl;
					a.download = "fnehousing-"+response.label+"-"+Date.now()+".xls";
					document.body.appendChild(a);
					a.click();
		   
					Swal.fire({
						icon: 'success', 
						title: fnehd.swal.success.export_excel_success, 
						showConfirmButton: false, 
						timer: 1500,
					});
				 
				});	
			}
		})
	});

	
});

