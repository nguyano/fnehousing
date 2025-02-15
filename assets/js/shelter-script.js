jQuery(document).ready(function ($) {
    // Toggle Shelter Screen options
    const screen_options = ["fnehd_so_shelter_ref", "fnehd_so_shelter_name", "fnehd_so_shelter_email", "fnehd_so_shelter_phone", "fnehd_so_shelter_availability", "fnehd_so_shelter_created"];

    // Bind change event directly on each checkbox
    screen_options.forEach((id) => {
        $("#" + id).on("change", function () {
            if ($(this).is(":checked")) {
                $("." + id).show();
            } else {
                $("." + id).hide();
            }
        });
    });

    // Function - Dropdown Toggle for Table Options
    function fnehdDropdownToggle({ dropdownId, buttonClass }) {
        $(`#${dropdownId}`).change(function () {
            $(`.${buttonClass}`).hide(); // Re-hide all buttons
            $(`#${$(this).val()}`).show(); // Unhide the selected button
        });
    }

    // Function - Show Table Option Buttons
    function setDataTableOptionsBtn() {
        $("#fnehd-option1").show();
        fnehdDropdownToggle({ dropdownId: "fnehd-list-actions", buttonClass: "fnehd-apply-btn" });
    }

    // Default DataTable Columns
    const defaultDTColumns = [
        {
            data: 0, // Checkbox column
            orderable: false,
            render: function (data, type, row) {
                return row[0]?.check_box || "";
            },
        },
        {
            data: null, // Continuous row index
            orderable: false,
            render: function (data, type, row, meta) {
                return meta.row + 1 + meta.settings._iDisplayStart;
            },
        },
        { data: 2, className: "fnehd_so_ref" },
        { data: 3, className: "fnehd_so_shelter_name" },
        { data: 4, className: "fnehd_so_shelter_email" },
        { data: 5, className: "fnehd_so_shelter_phone" },
        { data: 6, className: "fnehd_so_shelter_availability" },
		{ data: 7, className: "fnehd_so_shelter_created" },
        {
            data: 8, // Action column
            orderable: false,
            className: "dt-center",
            render: function (data, type, row) {
                return row[8]?.actions || "";
            },
        },
    ];

    // Function - Initialize DataTables
    function setDataTable(extraData = []) {
        const tbls = ["fnehd-shelter-table", "fnehd-log-table"];

        tbls.forEach((tableId) => {
            const $table = $("#" + tableId);
            const ajax_action = $table.data("ajax-action");
            const orderCol = $table.data("order-col");
            const frontHiddenCol = $table.data("front-hidden-col");

            initDataTable("#" + tableId, ajax_action, defaultDTColumns, orderCol, frontHiddenCol, extraData);
        });

        // Initialize table option buttons
        setDataTableOptionsBtn();
    }

    // Function - DataTable Initializer
    function initDataTable(selector, action, columns, orderCol, frontHiddenCol, extraData = []) {
        if (!Array.isArray(extraData)) {
            extraData = [extraData];
        }

        $(selector).DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: fnehd.ajaxurl,
                type: "POST",
                data: function (d) {
                    d.action = action;
                    d.extraData = extraData;
                },
                dataSrc: function (json) {
                    return json.data || [];
                },
            },
            language: {
                zeroRecords: fnehd.swal.warning.datatable_no_data_text,
            },
            rowId: function (row) {
                return row[0]?.row_id || "";
            },
            columns: columns,
            columnDefs: [
                {
                    targets: frontHiddenCol,
                    visible: !fnehd.is_front_user,
                    searchable: false,
                },
            ],
            order: [[orderCol, "desc"]],
        });
    }
	

    // Get URL Token value for GET Response
    const urlParams = new URLSearchParams(window.location.search);

    // Load frontend datatables
    if (fnehd.is_front_user) {
        const shelterUrl = $("#fnehd-shelter-table").data("shelter-url");
        setDataTable([{ shelter_url: shelterUrl }]);
		
		const filterDropdown = $('#shelter-filter'); 
		
		// Function to load listings based on selected filter
		function filteredListings() {
			const selectedValue = filterDropdown.val(); // Get selected option

			// Determine which API URL to use
			if (selectedValue === 'available') {
				$('#fnehd-shelter-table').DataTable().destroy();
				setDataTable([{ shelter_url: shelterUrl, availability: 'Available' }]);
			} else if (selectedValue === 'unavailable') {
				$('#fnehd-shelter-table').DataTable().destroy();
				setDataTable([{ shelter_url: shelterUrl, availability: 'Unavailable' }]);
			} else {
				$('#fnehd-shelter-table').DataTable().destroy();
				setDataTable([{ shelter_url: shelterUrl }]);
			}

		}

		// Attach event listener to dropdown
		filterDropdown.on('change', filteredListings);
		
		initMaterialWizard();
    }

    // Display Dashboard Shelters
    if (urlParams.get("page") === "fnehousing-dashboard") {
        fetchRecentShelters();
    }
    function fetchRecentShelters() {
        const data = { action: "fnehd_dash_shelters" };
        $.post(fnehd.ajaxurl, data, function (response) {
            $("#tableDataDB").html(response);
            initMaterialWizard();
            setTimeout(function () {
                $(".card.card-wizard").addClass("active");
            }, 600);
        });
    }

    // Display Shelters
    if (urlParams.get("page") === "fnehousing-shelters") {
        fetchAllShelters();
    }
    function fetchAllShelters() {
        const data = { action: "fnehd_shelters" };
        $.post(fnehd.ajaxurl, data, function (response) {
            $("#fnehd-admin-container").html(response);
            setDataTable();
            initMaterialWizard();
            setTimeout(function () {
                $(".card.card-wizard").addClass("active");
            }, 600);
        });
    }

    // Display Log
    if (urlParams.get("page") === "fnehousing-activity-log") {
        loadActivityLogs();
    }
    function loadActivityLogs() {
        const data = { action: "fnehd_activity_log" };
        $.post(fnehd.ajaxurl, data, function (response) {
            $("#fnehd-admin-container").html(response);
            setDataTable();
        });
    }

    // Reload Shelters (shelters table)
    function reloadShelters() {
        const shelter_url = $("#fnehd-shelter-table").data("shelter-url");
        const data = { action: "fnehd_reload_shelter_tbl", shelter_url: shelter_url };
        $.post(fnehd.ajaxurl, data, function (response) {
            $("#fnehd-shelter-table-wrapper").html(response);
            setDataTable();
            initMaterialWizard();
        });
    }

    // Reload Shelter Table
    $("body").on("click", "#reloadTable", function (e) {
        reloadShelters();
        Swal.fire({
            icon: "success",
            title: fnehd.swal.shelter.table_reload_success,
            showConfirmButton: false,
            timer: 1500,
        });
    });

    // Move to top of dashboard add shelter form.
    $("body").on("click", "#addShelterDash", function (e) {
        $("html, .fnehd-main-panel").animate(
            { scrollTop: $(".fnehd-main-panel").offset().top },
            "slow"
        );
    });

    // Add shelter
    $("body").on("submit", "#AddShelterForm", function (e) {
        e.preventDefault();
        Swal.fire({
            title: fnehd.swal.warning.title,
            text: fnehd.swal.warning.text,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: fnehd.swal.shelter.add_shelter_confirm,
        }).then((result) => {
            if (result.isConfirmed) {
                const form_data = $(this).serialize();
                let view_shelter_url = "admin.php?page=fnehousing-view-shelter";
                if (fnehd.is_front_user) {
                    view_shelter_url = $("#fnehd-listing-wrapper").data("shelter-url");
                }

                $.post(fnehd.ajaxurl, form_data, function (response) {
                    if (response.success) {
                        const url = fnehd.is_front_user
                            ? `<a class="btn btn-outline-info" href="${view_shelter_url}&shelter_id=${response.data.shelter_id}">${response.data.redirect_text}</a>`
                            : "";
                        Swal.fire({
                            icon: "success",
                            title: response.data.message,
                            html: url,
                            showConfirmButton: true,
                        });
                        $("#fnehd-add-shelter-modal").modal("hide");
                        $("#AddShelterForm")[0].reset();
                        $("#fnehd-add-shelter-form-dialog").collapse("hide");
                        if (urlParams.get("page") === "fnehousing-dashboard") {
                            fetchRecentShelters();
                        } else {
                            reloadShelters();
                        }
                        $("html, .fnehd-main-panel").animate(
                            { scrollTop: $(".fnehd-main-panel").offset().top },
                            "slow"
                        );
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: response.data.message,
                            showConfirmButton: true,
                        });
                    }
                });
            }
        });
    });

    // Update Shelter
    $("body").on("submit", "#EditShelterForm", function (e) {
        e.preventDefault();
        Swal.fire({
            title: fnehd.swal.warning.title,
            text: fnehd.swal.warning.text,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: fnehd.swal.shelter.shelter_update_confirm,
        }).then((result) => {
            if (result.isConfirmed) {
                const form_data = $(this).serialize();
                $.post(fnehd.ajaxurl, form_data, function (response) {
                    if (response.success) {
                        Swal.fire({
                            icon: "success",
                            title: response.data.message,
                            showConfirmButton: false,
                            timer: 1500,
                        });
                        $("#fnehd-edit-shelter-modal").modal("hide");
                        $("#fnehd-edit-shelter-form-dialog").collapse("hide");
						$("#EditShelterForm")[0].reset();
						reloadShelters();
                        $("html, .fnehd-main-panel").animate(
                            { scrollTop: $(".fnehd-main-panel").offset().top },
                            "slow"
                        );
                    }
                });
            }
        });
    });
	
	// Edit Shelter Record (pull existing data into form)
	$("body").on("click", ".fnehd-edit-shelter-btn", function (e) {
		e.preventDefault();

		let shelterId = $(this).attr("id");
		let data = {
			action: "fnehd_shelter_data",
			ShelterId: shelterId,
		};

		$.post(fnehd.ajaxurl, data, function (response) {
			const fields = {
				"#EditFnehdShelterAjaxNonce": "nonce",
				"#EditFnehdShelterID": "shelter_id",
				"#EditFnehdShelterName": "shelter_name",
				"#EditFnehdShelterOrganization": "shelter_organization",
				"#EditFnehdShelterEmail": "email",
				"#EditFnehdShelterPhone": "phone",
				"#EditFnehdShelterFax": "fax",
				"#EditFnehdShelterWebsite": "website",
				"#EditFnehdShelterAddress": "address",
				"#EditFnehdShelterManager": "manager",
				"#EditFnehdShelterProjectType": "project_type",
				"#EditFnehdShelterDescription": "description",
				"#EditFnehdShelterMainImage": "main_image",
				"#EditFnehdShelterGallery": "gallery",
				"#Editfnehd_eligible_individuals": "eligible_individuals[]",
				"#Editfnehd_accepted_ages": "accepted_ages[]",
				"#Editfnehd_specific_services": "specific_services",
				"#Editfnehd_pet_policy": "pet_policy",
				"#Editfnehd_shelter_availabity": "availability",
				"#Editfnehd_bed_capacity": "bed_capacity",
				"#Editfnehd_available_bed": "available_beds"

			};

			// Dynamically populate the fields
			for (const [fieldSelector, key] of Object.entries(fields)) {
				$(fieldSelector).val(response.data[key]);
			}
			
			//Populate Working Hours data
			let workingHours = response.data.working_hours;
			$.each(workingHours, function (day, data) {
				let dayKey = day.trim();
				let startSelect = $("select[name='start[" + dayKey + "]']");
				let endSelect = $("select[name='end[" + dayKey + "]']");
				let offCheckbox = $("input[name='off[" + dayKey + "]']");

				if (data.off) {
					offCheckbox.prop("checked", true);
					startSelect.prop("disabled", true);
					endSelect.prop("disabled", true);
				} else {
					startSelect.val(data.start);
					endSelect.val(data.end);
				}
			});

			// Update the form title with the current user email
			$("#CrtEditShelterID").html(response.data.shelter_name);

			// display available main image
			let imagePath = response.data.main_image;
			if (imagePath) {
				$(".EditFnehdShelterMainImage-FilePrv").html("<img src='"+imagePath+"' alt='...'>");
				$(".EditFnehdShelterMainImage-FilePrv").addClass("thumbnail");
				$(".EditFnehdShelterMainImage-AddFile").hide();
				$(".EditFnehdShelterMainImage-ChangeFile, .EditFnehdShelterMainImage-dismissPic").show();
			}
			
			// Display available shelter gallery
			const galleryData = response.data.gallery;

			// Ensure galleryData is valid and not empty
			if (galleryData && typeof galleryData === "string" && galleryData.trim() !== "") {
				const selection = galleryData.split(',')
					.map(item => item.trim())
					.filter(item => item.length > 0); // Removes empty strings

				if (selection.length > 0) {
					selection.forEach((attachment) => {
						$(".EditFnehdShelterGallery-preview-container").append(`
							<div class="image-preview thumbnail" style="display: inline-block; margin: 5px; position: relative;">
								<img src="${attachment}" class="EditFnehdShelterGallery-PrevUpload"
									style="width: 100px; height: 100px; object-fit: cover; border-radius: 5px;">
								<button type="button" class="remove-image" 
									style="position: absolute; top: 5px; right: 5px; background: red; color: white; border: none; cursor: pointer;">
									X
								</button>
							</div>
						`);
					});
				}
				
				// $(".EditFnehdShelterGallery-upload-button").hide();
				$(".EditFnehdShelterGallery-remove-all").show();
				$(".EditFnehdShelterGallery-add-more").show();
				$(".EditFnehdShelterGallery-upload-button").hide();
			}
			
			
			// Multiple Select Inputs
			multChoiceSelect({ selectID: 'Editfnehd_eligible_individuals', data: response.data.eligible_individuals.split(',') });
			multChoiceSelect({ selectID: 'Editfnehd_accepted_ages', data: response.data.accepted_ages.split(',') });
			multChoiceSelect({ selectID: 'Editfnehd_specific_services', data: response.data.specific_services.split(',') });
			

		});

		// Scroll to the form
		$("html, .fnehd-main-panel").animate(
			{ scrollTop: $(".fnehd-admin-forms").offset().top },
			"slow"
		);
	});
	
	
	// Cleanup when collapse is hidden or modal is closed
	$('body').on('hidden.bs.collapse hidden.bs.modal', function () {
		// Optionally reset or clear image previews
		$(".FnehdShelterGallery-preview-container, .EditFnehdShelterGallery-preview-container").empty();
	});
	

    // Define an array of page-function mappings
    const pageFunctionMap = [
        { page: "fnehousing-dashboard", fn: fetchRecentShelters },
        { page: "fnehousing-shelters", fn: reloadShelters },
        { page: "fnehousing-activity-log", fn: loadActivityLogs },
    ];

    // Function to execute based on the current page
    function fnehdExecutePageFunction() {
        const currentPage = new URLSearchParams(window.location.search).get("page");
        pageFunctionMap.forEach((mapping) => {
            if (currentPage === mapping.page) {
                mapping.fn();
            }
        });
    }

    // Delete Single data
    $("body").on("click", ".fnehd-delete-btn", function (e) {
        e.preventDefault();
        const $tr = $(this).closest("tr");
        const delID = $(this).attr("id");
        const action = $(this).data("action");

        Swal.fire({
            title: fnehd.swal.warning.title,
            text: fnehd.swal.warning.text,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: fnehd.swal.shelter.delete_confirm,
        }).then((result) => {
            if (result.isConfirmed) {
                const data = { action: action, delID: delID };
                $.post(fnehd.ajaxurl, data, function (response) {
                    $tr.css("background-color", "#ff6565");
                    Swal.fire({
                        icon: "success",
                        title: response.label + " " + fnehd.swal.success.delete_success,
                        showConfirmButton: false,
                        timer: 1500,
                    });
                    fnehdExecutePageFunction();
                });
            }
        });
    });

    /**
     * Handle batch actions for selected rows.
     *
     * @param {string} checkboxClass - The class of checkboxes for selecting rows.
     * @param {string} action - The action to perform (passed to the AJAX request).
     * @param {function} callback - A callback function to handle additional logic after a successful action.
     */
    function fnehdHandleBatchAction(checkboxClass, action, callback) {
        const selectedRows = [];

        $(`${checkboxClass}:checked`).each(function () {
            selectedRows.push($(this).data("fnehd-row-id"));
        });

        if (selectedRows.length <= 0) {
            Swal.fire({
                icon: "warning",
                title: fnehd.swal.warning.no_records_title,
                text: fnehd.swal.warning.no_records_text,
            });
        } else {
            Swal.fire({
                title: fnehd.swal.warning.title,
                text: fnehd.swal.warning.text,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText:
                    selectedRows.length > 1
                        ? fnehd.swal.warning.delete_records_confirm
                        : fnehd.swal.warning.delete_record_confirm,
            }).then((result) => {
                if (result.isConfirmed) {
                    const selectedValues = selectedRows.join(",");
                    const data = { action, multID: selectedValues };
                    $.post(fnehd.ajaxurl, data, function (response) {
                        selectedRows.forEach((id) => {
                            $("#" + id).css("background-color", "#ff6565");
                        });
                        Swal.fire({
                            icon: "success",
                            title:
                                (selectedRows.length > 1 ? response.label + "s" : response.label) +
                                " " +
                                fnehd.swal.success.delete_success,
                            text:
                                selectedRows.length +
                                " " +
                                (selectedRows.length > 1 ? response.label + "s" : response.label) +
                                " Deleted!",
                            showConfirmButton: false,
                            timer: 1500,
                        });
                        if (typeof callback === "function") {
                            callback(selectedRows, response);
                        }
                        $("html, .fnehd-main-panel").animate(
                            { scrollTop: $(".fnehd-main-panel").offset().top },
                            "slow"
                        );
                    });
                }
            });
        }
    }

    // Multi delete shelter, shelter meta & logs
    $(document).on("click", ".fnehd-mult-delete-btn", function () {
        const action = $(this).data("action");
        fnehdHandleBatchAction(".fnehd-checkbox", action, function () {
            fnehdExecutePageFunction();
        });
    });
    // Multi delete user earnings
    $(document).on("click", ".fnehd-earnings-mult-delete-btn", function () {
        const action = $(this).data("action");
        fnehdHandleBatchAction(".fnehd-checkbox", action, function () {
            reloadUserEarnings();
        });
    });
    // Multi delete shelter search data
    $(document).on("click", ".fnehd-search-mult-delete-btn", function () {
        const action = $(this).data("action");
        fnehdHandleBatchAction(".fnehd-checkbox2", action, function () {
            reloadSearchResults();
        });
    });
	

    // Shelter gallery upload - Works for both collapsible elements & modals
	$('body').on('shown.bs.collapse shown.bs.modal', function () {
		FnehdWPMultFileUpload("FnehdShelterGallery");
		FnehdWPMultFileUpload("EditFnehdShelterGallery");
	});

	
	// Shelter main image upload
    FnehdWPFileUpload("FnehdShelterMainImage");
	FnehdWPFileUpload("EditFnehdShelterMainImage");
	

    /******************************* Shelter Search ***********************************/

    // Shelter Initialize Search DataTable
    function setSearchDataTable(extraData = []) {
        initDataTable(
            "#fnehd-shelter-search-table",
            "fnehd_shelter_search_datatable",
            defaultDTColumns,
            6,
            0,
            extraData
        );
        setDataTableOptionsBtn();
    }

    // Animate Shelter Search Input
    $("body").on("focus", "#fnehd-shelter-search-input", function () {
        if ($("#fnehd-shelter-search-input").val() === "") {
            $("#fnehd-shelter-search-input").animate({ width: "+=100px" }, 1000);
        }
    });
    $("body").on("blur", "#fnehd-shelter-search-input", function () {
        if ($("#fnehd-shelter-search-input").val() === "") {
            $("#fnehd-shelter-search-input").animate({ width: "-=100px" }, 1000);
        }
    });

    // Display Shelter Search Results
    $("body").on("submit", "#fnehd-shelter-search-form", function (e) {
        e.preventDefault();
        const search_text = $("#fnehd-shelter-search-input").val();
        const form_data = $(this).serialize();

        $.post(fnehd.ajaxurl, form_data, function (response) {
            if (fnehd.interaction_mode === "modal") {
                $("#fnehd-shelter-search-results-modal-body").html(response);
                $("#fnehd-shelter-search-modal").modal("show");
            } else {
                $("#fnehd-shelter-search-results-dialog-wrap").html(response);
                $("#fnehd-search-results-dialog").collapse("show");
                $("#fnehd-search-results-dialog").animate(
                    { scrollTop: $("#fnehd-search-results-dialog")[0].scrollHeight },
                    "1000"
                );
            }
            setSearchDataTable([{ search: search_text }]);
        });
    });

    // Reload Shelter Search Results
    function reloadSearchResults() {
        const search_text = $("#fnehd-shelter-search-input").val();
        const data = { action: "fnehd_reload_shelter_search", search_text: search_text };
        $.post(fnehd.ajaxurl, data, function (response) {
            if (fnehd.interaction_mode === "modal") {
                $("#fnehd-shelter-search-results-modal-body").html(response);
            } else {
                $("#fnehd-shelter-search-results-dialog-wrap").html(response);
            }
            setSearchDataTable([{ search: search_text }]);
        });
    }

    // Delete Search Shelter
    $("body").on("click", ".fnehd-shelter-search-del-btn", function (e) {
        e.preventDefault();
        const $tr = $(this).closest("tr");
        const delID = $(this).attr("id");

        Swal.fire({
            title: fnehd.swal.warning.title,
            text: fnehd.swal.warning.text,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: fnehd.swal.shelter.delete_confirm,
        }).then((result) => {
            if (result.isConfirmed) {
                const data = { action: "fnehd_del_shelter", delID: delID };
                $.post(fnehd.ajaxurl, data, function (response) {
                    $tr.css("background-color", "#ff6565");
                    Swal.fire({
                        icon: "success",
                        title: response.label + fnehd.swal.success.delete_success,
                        showConfirmButton: false,
                        timer: 1500,
                    });
                    reloadSearchResults();
                });
            }
        });
    });
	
});
