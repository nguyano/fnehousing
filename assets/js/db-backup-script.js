 jQuery(document).ready(function($) {

    // Function - Initialize DataTable Options Button
    function setDataTableOptionsBtn() {
        $('#fnehd-option1').show();
        $('#fnehd-list-actions').change(function() {
            $('.fnehd-apply-btn').hide(); // Re-hide all buttons
            $(`#${$(this).val()}`).show(); // Unhide the selected button
        });
    }

    
    // Function - DataTable Initialization
    function initDataTable(selector, action, columns, orderCol, frontHiddenCol, extraData = []) {
        extraData = Array.isArray(extraData) ? extraData : [extraData];

        const table = $(selector).DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: fnehd.ajaxurl,
                type: 'POST',
                data: function(d) {
                    d.action = action;
                    d.extraData = extraData;
                },
                dataSrc: function(json) {
                    const noRecordsMessage = json.no_records_message || fnehd.swal.warning.datatable_no_data_text;
                    table.settings()[0].oLanguage.sZeroRecords = noRecordsMessage;
                    return json.data;
                }
            },
            language: {
                zeroRecords: fnehd.swal.warning.datatable_no_data_text
            },
            rowId: row => `${row[0].row_id}`,
            columns: columns,
            columnDefs: [{
                targets: frontHiddenCol,
                visible: fnehd.dbbackup_log_state,
                searchable: false,
            }],
            order: [[orderCol, 'desc']],
        });
    }

    // Function - Setup DataTable
    function setDataTable(extraData = []) {
        const columns = [
            { data: 0, orderable: false, render: (data, type, row) => row[0].check_box },
            { data: null, orderable: false, render: (data, type, row, meta) => meta.row + 1 + meta.settings._iDisplayStart },
            { data: 2 },
            { data: 3 },
            { data: 4 },
            { data: 5 },
            { data: 6, orderable: false, className: 'dt-center', render: (data, type, row) => row[6].actions }
        ];
        initDataTable('#fnehd-dbbackup-table', 'fnehd_dbbackup_datatable', columns, 5, 2, extraData);
        setDataTableOptionsBtn();
    }

    // Display Backups
	if (new URLSearchParams(window.location.search).get('page') === "fnehousing-db-backups") {
        displayBackups();
    }
	// Function - Display Backups
    function displayBackups() {
        const data = { action: 'fnehd_display_dbbackups' };
        $.post(ajaxurl, data, function(response) {
            $("#fnehd-admin-container").html(response);
            setDataTable();
        });
    }

    // Function - Reload Backups Table
    function reloadDbBackups() {
        const data = { action: 'fnehd_dbbackups_tbl' };
        $.post(ajaxurl, data, function(response) {
            $("#tableDataDBBackup").html(response);
            setDataTable();
        });
    }

    // Function - Generic SweetAlert Prompt
    function showAlert({ title, text, icon, confirmButtonText, confirmCallback }) {
        Swal.fire({
            title,
            text,
            icon,
            showCancelButton: true,
            confirmButtonColor: icon === 'warning' ? '#d33' : '#4caf50',
            cancelButtonColor: '#3085d6',
            confirmButtonText
        }).then(result => {
            if (result.value && typeof confirmCallback === 'function') {
                confirmCallback();
            }
        });
    }

    // Event - Backup Database
    $("body").on("click", "#BackupDB", function(e) {
        e.preventDefault();
        showAlert({
            title: fnehd.swal.warning.title,
            text: fnehd.swal.dbbackup.backup_text,
            icon: 'success',
            confirmButtonText: fnehd.swal.dbbackup.backup_confirm,
            confirmCallback: function() {
                const data = { action: 'fnehd_dbbackup' };
                $.post(ajaxurl, data, function(response) {
                    if (response.success) {
                        Swal.fire({ icon: 'success', title: response.data.message, showConfirmButton: false, timer: 1500 });
                        reloadDbBackups();
                    }
                });
            }
        });
    });
	
	
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
						console.error( fnehd.swal.dbbackup.restore_fail_title, response);
					}
				},
				error: function () {
					console.error(fnehd.swal.dbbackup.restore_poll_fail_text);
				},
			});
		}

		requestAnimationFrame(update);
	}

    // Event - Restore Database
	$("body").on("click", ".restoreDB", function (e) {
		e.preventDefault();
		var BkUpFile = $(this).attr("id");

		swal.fire({
			title: fnehd.swal.warning.title,
			text: fnehd.swal.warning.db_restore_text,
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#d33",
			cancelButtonColor: "#3085d6",
			confirmButtonText: fnehd.swal.dbbackup.restore_confirm,
		}).then((result) => {
			if (result.value) {
				$.ajax({
					type: "POST",
					url: fnehd.ajaxurl,
					data: { action: "fnehd_dbrestore", BkupfileName: BkUpFile },
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




    // Event - Delete Single Backup
    $("body").on("click", ".deleteDB", function(e) {
        e.preventDefault();
        const backupId = $(this).attr('id');
        const backupPath = $(this).data('bkup-path');
        const row = $(this).closest('tr');

        showAlert({
            title: fnehd.swal.warning.title,
            text: fnehd.swal.warning.text,
            icon: 'warning',
            confirmButtonText: fnehd.swal.dbbackup.delete_confirm,
            confirmCallback: function() {
                const data = { action: 'fnehd_del_dbbackup', deleteDBid: backupId, DBpath: backupPath };
                $.post(ajaxurl, data, function(response) {
                    row.css('background-color', '#ff6565');
                    Swal.fire({ icon: 'success', title: response.data.message, showConfirmButton: false, timer: 1500 });
                    reloadDbBackups();
                });
            }
        });
    });

    // Event - Delete Multiple Backups
    $(document).on('click', '.fnehd-mult-delete-btn', function() {
        const selectedBackups = $(".fnehd-checkbox:checked").map((_, el) => $(el).data('fnehd-row-id')).get();
        const selectedPaths = $(".fnehd-checkbox:checked").map((_, el) => $(el).data('bkup-path')).get();

        if (selectedBackups.length === 0) {
            Swal.fire({ icon: 'warning', title: fnehd.swal.warning.no_records_title, text: fnehd.swal.warning.no_records_text });
        } else {
            showAlert({
                title: fnehd.swal.warning.title,
                text: fnehd.swal.warning.text,
                icon: 'warning',
                confirmButtonText: `${selectedBackups.length > 1 ? fnehd.swal.warning.delete_records_confirm : fnehd.swal.warning.delete_record_confirm}`,
                confirmCallback: function() {
                    const data = { action: 'fnehd_del_dbbackups', multDBid: selectedBackups.join(","), multDBpath: selectedPaths.join(",") };
                    $.post(ajaxurl, data, function() {
                        selectedBackups.forEach(id => $(`#${id}`).css('background-color', '#ff6565'));
                        Swal.fire({ 
						icon: 'success', 
						title: `${selectedBackups.length > 1 ? fnehd.swal.dbbackup.backup_plural : fnehd.swal.dbbackup.backup_singular} `+fnehd.swal.success.delete_success,
						text: `${selectedBackups.length > 1 ? fnehd.swal.dbbackup.backup_plural_deleted : fnehd.swal.dbbackup.backup_singular_deleted} `,
						showConfirmButton: false, timer: 1500 });
                        reloadDbBackups();
                    });
                }
            });
        }
    });
    
	
});
