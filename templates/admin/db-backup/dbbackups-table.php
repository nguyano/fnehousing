<?php
 /**
 * The DB Backups Table.
 * List all available DB Backups.
 * 
 * @since      1.0.0
 */ 
           
/* $swalfire = 'Swal.fire("'.__("No Action Selected!", "fnehousing").'", "'.__("Please select an action to continue!", "fnehousing").'", "warning");';


$output = "";

$backups = $this->displayDBBackups();
$rowCnt = $this->totalDBBackups();

if ($rowCnt > 0) {
 
	$output .="

	<select id='track-list-actions'>
	<option value='option1'>".__('Select action', 'fnehousing')."</option>
	<option value='option2'>".__('Delete', 'fnehousing')."</option>
	</select>

	<div id='option1' style='display: none;' class='btndiv'><a type='button' class='btn fnehd-tbl-action-btn' 
	onclick='".$swalfire."'><i class='fas fa-check' ></i> ".__('Apply', 'fnehousing')."</a></div>

	<div id='option2' style='display: none;' class='btndiv'><a type='button' id='delete_db_records' class='btn btn-danger fnehd-tbl-action-btn'> ".__('Delete', 'fnehousing')."</a> <span class='fnehd-selected' id='fnehd-select-count'><b>0</b> 
	".__('Selected', 'fnehousing')."</span></div>


	<table class='table border fnehd-data-table stripe hover order-column' id='fnehd-db-backup-table' width='100%' cellspacing='0' style='font-size:14px;'>
		<thead class='fnehd-th'>
				<tr>
					<th data-orderable='false' style='color:#06accd !important;'><center><input style='border: solid gold !important;' type='checkbox' id='select_all'></center></th>
					<th class='fnehd-th'>".__('No.', 'fnehousing')."</th>
					<th class='fnehd-th'>".__('Creation Date', 'fnehousing')."</th>";
					
					
		if(FNEHD_DBACKUP_LOG){ $output .=" <th class='fnehd-th'>".__('Backup Logs', 'fnehousing')."</th>"; }
				
		$output .="	<th class='fnehd-th'>".__('Backup File', 'fnehousing')."</th>
					<th class='fnehd-th'>".__('File Size', 'fnehousing')."</th>
					<th class='fnehd-th'><center>".__('Action', 'fnehousing')."</center></th>
				</tr>
			</thead>
		<tbody>";
		
	foreach ($backups as $backup) {
	 
		if (file_exists($backup['backup_path'])) {	 
		 
			$output.="<tr id='".$backup['backup_id']."'>  ";  
				
				$output.="			
						<td><center><input type='checkbox' class='fnehd-checkbox' data-bkup-path='".$backup['backup_path']."' data-fnehd-row-id='".$backup['backup_id']."'></center></td>
						<td></td>
						<td>".$backup['creation_date']."</td>";
						
				if(FNEHD_DBACKUP_LOG){	
				  $output .="<td><a target='_blank' href='".str_replace('.sql', '-log.txt', $backup['backup_url'])."' id='".$backup[ 'backup_id']."' class='btn btn-icon-text fnehd-btn-sm btn-success seeDBLog'>
					<i class='fas fa-eye'></i> <spa class='d-none d-md-inline'>
					".__('View Log', 'fnehousing')."</span></a></td>";
				}
				
				$output .="	<td><a class='btn btn-icon-text fnehd-btn-sm btn-info' data-toggle='tooltip' title='Download Backup' href='". 
						  $backup['backup_url']."'><i class='fas fa-download'></i> <spa class='d-none d-md-inline'>
						  ".__('Download', 'fnehousing')."</span></a></td>
						<td>".round(filesize($backup['backup_path'])/1000, 2)." kb</td>
						
						<td><center>
						<a href='#' data-toggle='tooltip' title='Restore Backup' id='".$backup['backup_path']."' class='btn btn-icon-text fnehd-btn-sm restoreDB fnehd-btn-behance' >
						<i class='fas fa-sync'></i> <spa class='d-none d-md-inline'>
						".__('Restore', 'fnehousing')."</span>
						</a>
						
						<a href='#' data-toggle='tooltip' title='".__('Delete', 'fnehousing')."' id='".$backup['backup_id']."' data-bkup-path='".$backup['backup_path']."' class='btn btn-icon-text fnehd-btn-sm btn-danger deleteDB'>
						<i class='fas fa-trash'></i>
						</a>
						
						</a></center>
						</td>
					  </tr>
					"; 	
				

		} else{
			$this->deleteDB($backup['backup_id']);//delete DB info if no backup file exist
		} 
	}

	$output .= "
		</tbody>
		<thead class='fnehd-th'>
				<tr>
					<th data-orderable='false'><center><input type='checkbox' id='select_all2'></center></th>
					<th class='fnehd-th'>".__('No.', 'fnehousing')."</th>
					<th class='fnehd-th'>".__('Creation Date', 'fnehousing')."</th>";
		if(FNEHD_DBACKUP_LOG){ $output .=" <th class='fnehd-th'>".__('Backup Logs', 'fnehousing')."</th>"; }
		$output .="	<th class='fnehd-th'>".__('Backup File', 'fnehousing')."</th>
					<th class='fnehd-th'>".__('File Size', 'fnehousing')."</th>
		
					<th class='fnehd-th'><center>".__('Action', 'fnehousing')."</center></th>
				</tr>
			</thead>
		</table>
	<br>
	<select id='track-list-actions2'>
	<option value='option1a'>".__('Select action', 'fnehousing')."</option>
	<option value='option2b'>".__('Delete', 'fnehousing')."</option>
	</select>

	<div id='option1a' style='display: none;' class='btndiv2'><a type='button' class='btn fnehd-tbl-action-btn' onclick='".$swalfire."'><i class='fas fa-check'></i> ".__('Apply', 'fnehousing')."</a></div>

	<div id='option2b' style='display: none;' class='btndiv2'><a type='button' id='delete_db_records' class='btn btn-danger fnehd-tbl-action-btn'> ".__('Delete', 'fnehousing')."</a> <span class='fnehd-selected' id='fnehd-select-count'><b>0</b> 
	".__('Selected', 'fnehousing')."</span></div>";

	echo $output;  
	
} else{
	echo '<h3 class="text-light text-center mt-5">'.__("No database backup found", "fnehousing").'</h3>';
}


 */


 use Fnehousing\Api\DataTable\DataTableSkeleton;

defined('ABSPATH') || exit;


$table_id = 'fnehd-dbbackup-table';

$js_data = [];

$actions = [
    'select_id' => 'fnehd-list-actions',
    'options' => [
        'fnehd-option1' => 'Select action',
        'fnehd-option2' => 'Delete',
    ],
    'buttons' => [
        'fnehd-option1' => [
            'class' => 'btn fnehd-tbl-action-btn',
            'icon' => 'fas fa-check',
            'label' => __('Apply', 'fnehousing')
        ],
        'fnehd-option2' => [
            'class' => 'btn btn-danger fnehd-tbl-action-btn fnehd-mult-delete-btn',
            'icon' => 'fas fa-trash',
            'label' => __('Delete', 'fnehousing'),
            'delete_action' => 'delete_db_records',
            'selected_class' => 'fnehd-selected'
        ],
    ],
];

$headers = [
    ['data-orderable' => 'false', 'content' => '<input type="checkbox" id="fnehd-select-all">'],
    ['content' => __('No.', 'fnehousing')],
    ['content' => __('Backup Log', 'fnehousing')],
    ['content' => __('Backup File', 'fnehousing')],
    ['content' => __('File Size', 'fnehousing')],
    ['content' => __('Date', 'fnehousing')]
];

// Render DataTable
$data_table = new DataTableSkeleton($table_id, $headers, $actions, $js_data, $data_count);
echo $data_table->render();