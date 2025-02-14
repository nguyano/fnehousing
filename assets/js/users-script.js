
jQuery(document).ready(function($){
	
	 // Function - Table options Button
	function setDataTableOptionsBtn(){
		$('#fnehd-option1').show();
		function fnehdDropdownToggle({ dropdownId, buttonClass,}) { // listen to dropdown for change
			$(`#${dropdownId}`).change(function () {
				$(`.${buttonClass}`).hide(); // Re-hide all buttons
				$(`#${$(this).val()}`).show(); // Unhide the selected button
			});
		}
		fnehdDropdownToggle({dropdownId: 'fnehd-list-actions',   buttonClass: 'fnehd-apply-btn',});
	}
	
	
	
	//Function - Users DataTable Setup & Initialization
	function setUsersDataTable(){
		
		const usersColumns = [
			{
				data: 0, // Checkbox column
				orderable: false,
				render: function (data, type, row) {
					return row[0].check_box;
				},
			},
			{
				data: null, // Continuous row index
				orderable: false,
				render: function (data, type, row, meta) {
					return meta.row + 1 + meta.settings._iDisplayStart;
				},
			},
			{
				data: 2, 
				orderable: false,
				render: function (data, type, row) {
					return row[2].user_img;
				},
			}, 
			{ data: 3 }, 
			{ data: 4 },
			{ data: 5 }, 
			{ data: 6 }, 
			{ data: 7 }, 
			{
				data: 8, // Action column
				orderable: false,
				className: 'dt-center',
				render: function (data, type, row) {
					return row[8].actions;
				},
			},
		];
		
		// Initialize tables
		initDataTable('#fnehd-user-table','fnehd_users_datatable', 7, usersColumns);
		
		//table options buttons
        setDataTableOptionsBtn();		
	}
	
	
	
	// Function - DataTable Initializer
	function initDataTable(selector, action, orderCol, columns, extraData = []) {
		// Ensure extraData is an array
		if (!Array.isArray(extraData)) {
			extraData = [extraData];
		}

		const table = $(selector).DataTable({
			processing: true,
			serverSide: true,
			ajax: {
				url: fnehd.ajaxurl,
				type: 'POST',
				data: function (d) {
					d.action = action;
					d.extraData = extraData; // Append custom data to the request payload
				},
				dataSrc: function (json) {
					// Update the emptyTable message based on server response
					const noRecordsMessage = json.no_records_message || fnehd.swal.warning.datatable_no_data_text;
					table.settings()[0].oLanguage.sZeroRecords = noRecordsMessage;
					return json.data; // Return the data for the table
				}
			},
			rowId: function (row) {
				// Set unique ID for each row
				return `${row[0].row_id}`;
			},
			columns: columns, // Use the passed columns
			columnDefs: [
				{
					targets: 5, // column to hide at frontend
					visible: false, // Hide if the user is a frontend user
					searchable: false, // Ensure hidden column doesn't affect search
				},
			],
			order: [[orderCol, 'desc']],
		});

		return table; // Return the table instance for further use
	}

	 
	//Get URL Token value for user_id (GET Response) 
	var urlParams = new URLSearchParams(window.location.search); 
	 
	
	//Display Users Table
    getAllUsers();
    function getAllUsers(){
	    var data = {'action':'fnehd_users',};
		$.post(fnehd.ajaxurl, data, function(response) {
		    $("#fnehd-admin-container").html(response.data);
		    setUsersDataTable();
		});
    }
	
	
	//Reload Users Table
    function reloadUsers(){
	    if(fnehd.is_user == 'true'){ 
			var data = {'action':'fnehd_reload_user_account',};
			$.post(fnehd.ajaxurl, data, function(response) {
				$("#fnehd-edit-account").html(response.data); 
			});
		} else { 
			var data = {'action':'fnehd_users_tbl',}; 
			$.post(fnehd.ajaxurl, data, function(response) {
				$("#fnehd-user-table-wrapper").html(response.data);
				setUsersDataTable();
			});
		}
		
    }
	
	//Display User Profile
	if(urlParams.get('user_id')){ showUserProfile(); } 
    function showUserProfile(){
		var user_id = urlParams.get('user_id');
	    var data = {
			'action':'fnehd_user_profile',
			'user_id':user_id, 
		};
		jQuery.post(fnehd.ajaxurl, data, function(response) {
		   $("#fnehd-profile-container").html(response.data); 
		});
    }
	//Show User Profile Title
	$("body").on("click", ".fnehd-user-profile-btn", function(e){ 
	    $(".fnehd-user-profile-title").show();
		$(".fnehd-user-shelter-tbl-title").hide();
		$(".fnehd-user-earnings-tbl-title").hide();
	});



   
	/**
	 * Reusable function to verify input fields for uniqueness
	 * @param {string} fieldSelector - The selector for the input field to watch
	 * @param {string} action - The AJAX action to trigger
	 * @param {string} warningText - The warning text to display if the value is taken
	 */
	function fnehdVerifyUserField(fieldSelector, warningText) {
		$("body").on("blur", fieldSelector, function () {
			const fieldValue = $(this).val().trim();
			const data = {
				'action': 'fnehd_verify_user',
				'user_field': fieldValue, // Generalized key
			};

			$.post(fnehd.ajaxurl, data, function (response) {
				if (response === "taken") {
					Swal.fire({
						icon: "error",
						title: fnehd.swal.warning.error_title,
						text: warningText,
						showConfirmButton: true,
					});
				}
			});
		});
	}

	// Check if username & email already exist
	fnehdVerifyUserField("#FnehdUserEmail", fnehd.swal.user.email_already_exist);
	fnehdVerifyUserField("#FnehdUserUsername", fnehd.swal.user.user_already_exist);

	

	// Insert/Add User
	$("body").on("submit", "#UserAddForm", function (e) {
		e.preventDefault();

		swal
			.fire({
				title: fnehd.swal.warning.title,
				text: fnehd.swal.warning.text,
				icon: "warning",
				showCancelButton: true,
				confirmButtonColor: "#3085d6",
				cancelButtonColor: "#d33",
				confirmButtonText: fnehd.swal.user.add_user_confirm,
			})
			.then((result) => {
				if (result.value) {
					
					const formData = $(this).serialize();

					// Submit the form data
					$.post(fnehd.ajaxurl, formData, function (response) {
						if (response.success) {
							Swal.fire({
								icon: "success",
								title: response.data.message,
								showConfirmButton: false,
								timer: 1500,
							});
							$("#fnehd-add-user-modal").modal("hide");
							$("#fnehd-add-user-form-dialog").collapse("hide");
							$("#UserAddForm")[0].reset();
							reloadUsers();
							$("html, .fnehd-main-panel").animate({ scrollTop: $(".fnehd-main-panel").offset().top }, "slow" );
						} else {
							Swal.fire({
								icon: "error",
								title: fnehd.swal.warning.error_title,
								text: response.data.message,
								showConfirmButton: true,
							});
						}
					});
				}
			});
	});

	

    // Edit User Record (pull existing data into form)
	$("body").on("click", ".fnehd-user-edit-btn", function (e) {
		e.preventDefault();

		let userId = $(this).attr("id");
		let data = {
			action: "fnehd_user_data",
			UserId: userId,
		};

		$.post(fnehd.ajaxurl, data, function (response) {
			const fields = {
				"#EditFnehdUserID": "ID",
				"#EditFnehdUserFirstName": "first_name",
				"#EditFnehdUserLastName": "last_name",
				"#EditFnehdUserEmail": "user_email",
				"#EditFnehdUserPhone": "phone",
				"#EditFnehdUserCountry": "country",
				"#EditFnehdUserCompany": "company",
				"#EditFnehdUserSA": "sa_id",
				"#EditFnehdUserUsername": "user_login",
				"#EditFnehdUserBio": "bio",
				"#EditFnehdUserAddress": "address",
				"#EditFnehdUserUrl": "user_url",
				"#EditFnehdUserImg": "user_image",
				"#EditFnehdStatus": "status"
			};

			// Dynamically populate the fields
			for (const [fieldSelector, key] of Object.entries(fields)) {
				$(fieldSelector).val(response.data[key]);
			}

			// Update the form title with the current user email
			$("#CrtEditUserID").html(response.data.user_login);

			// Handle user image logic
			let imagePath = response.data.user_image;
			if (imagePath) {
				$(".EditFnehdUserImg-FilePrv").html("<img src='"+imagePath+"' alt='...'>");
				$(".EditFnehdUserImg-FilePrv").addClass("thumbnail");
				$(".EditFnehdUserImg-AddFile").hide();
				$(".EditFnehdUserImg-ChangeFile, .EditFnehdUserImg-dismissPic").show();
			}
		});

		// Scroll to the form
		$("html, .fnehd-main-panel").animate(
			{ scrollTop: $(".fnehd-admin-forms").offset().top },
			"slow"
		);
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
				var form_data = $(this).serialize();
				$.post(fnehd.ajaxurl, form_data, function(response) { 
				    if(response.success){
						Swal.fire({
						   icon: 'success',
						   title:response.data.message,
						   showConfirmButton: false, 
						   timer: 1500,
						});
						$("#fnehd-edit-user-modal").modal('hide');
						$("#fnehd-edit-user-form-dialog").collapse('hide');
						$("#fnehd-edit-user-form")[0].reset();
						if(urlParams.get('user_id') !== null){
							  location.reload();
						} else { 
							reloadUsers();
							$("html, .fnehd-main-panel").animate({scrollTop: $('.fnehd-main-panel').offset().top}, 'slow');
						}
						
					}
				});
		   }
	   });//then
    });


    

    //Delete User Record
    $("body").on("click", ".fnehd-delete-user-btn", function(e){
         e.preventDefault();
		 var tr = $(this).closest('tr');
		 var UserID = $(this).attr('id');
		 
		swal.fire({
			title: fnehd.swal.warning.title,
			text: fnehd.swal.user.delete_user_text,
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			cancelButtonColor: '#3085d6',
			confirmButtonText: fnehd.swal.user.delete_user_confirm,
		}).then((result) => {
		   if (result.value){
			   
				var data = {
					'action':'fnehd_del_user',
					'UserID':UserID,
				};
				$.post(fnehd.ajaxurl, data, function(response) {
					if(response.success){
						tr.css('background-color','#ff6565');
						Swal.fire({
						   icon: 'success',
						   title: response.data.message,
						   showConfirmButton: false, 
						   timer: 1500,
						});
						if(urlParams.get('user_id') !== null){
							  var url = $('#fnehd-user-account').data('users-url');
							  window.location = url;
						} else { 
							reloadUsers();
							$("html, .fnehd-main-panel").animate({scrollTop: $('.fnehd-main-panel').offset().top}, 'slow');
						}
					}
				});
			}
     
    	})
     
    });
	
	
  
	// Delete Multiple Assigned Users
	$(document).on("click", ".fnehd-mult-delete-user-btn", function (e) {
		e.preventDefault();

		let users = $(".fnehd-checkbox:checked")
			.map(function () {
				return $(this).data("fnehd-row-id");
			})
			.get();

		if (users.length <= 0) {
			Swal.fire({
				icon: "warning",
				title: fnehd.swal.warning.no_records_title,
				text: fnehd.swal.warning.no_records_text,
			});
		} else {
			const userText = users.length > 1 ? "Users" : "User";
			swal.fire({
				title: fnehd.swal.warning.title,
				text: fnehd.swal.user.delete_user_text,
				icon: "warning",
				showCancelButton: true,
				confirmButtonColor: "#d33",
				cancelButtonColor: "#3085d6",
				confirmButtonText: `${users.length > 1 ? fnehd.swal.warning.delete_records_confirm : fnehd.swal.warning.delete_record_confirm}`,
			})
			.then((result) => {
				if (result.value) {
					let selectedValues = users.join(",");

					let data = {
						action: "fnehd_del_users",
						multUserid: selectedValues,
					};

					$.post(fnehd.ajaxurl, data, function (response) {
						// Highlight deleted rows
						users.forEach((userId) => {
							$(`#${userId}`).css("background-color", "#ff6565");
						});
						Swal.fire({
							icon: "success",
							title: `${users.length > 1 ? fnehd.swal.user.user_plural : fnehd.swal.user.user_singular} `+fnehd.swal.success.delete_success,
							text: `${users.length > 1 ? fnehd.swal.user.user_plural_deleted : fnehd.swal.user.backup_singular_deleted} `,
							showConfirmButton: false,
							timer: 1500,
						});
						reloadUsers();
					});
				}
			});
		}
	});

	// Initialize upload functionality for Add and Edit User Image
	FnehdWPFileUpload("FnehdUserImg");
	FnehdWPFileUpload("EditFnehdUserImg");


    //Scroll to Users Orders
	$("body").on('click', '#viewtrkgs', function() {	
	      $("html, .fnehd-main-panel").animate({scrollTop: $('#UserOrders').offset().top}, 'slow'); 
    });
	

});
	

