<?php
/**
 * View Shelter DataTable Class
 * Handles server-side processing for viewing shelter meta data in DataTable.
 *
 * @since      1.0.0
 * @package    Fnehousing
 */

namespace Fnehousing\Api\DataTable;

use Fnehousing\Api\DataTable\SSP;

defined('ABSPATH') || exit;


class DBBackupDataTable {

    /**
     * Fetches database backup data for DataTable.
     *
     * Handles data retrieval, formatting, and response for DataTable.
     * @return void Outputs JSON response for DataTable.
     */
    public static function dbBackupTableData() {
		global $wpdb;

		// Table primary key
		$primaryKey = 'backup_id';

		// Table name
		$table = $wpdb->prefix . "fnehousing_dbbackups";

		// Backup folder path
		$upload_dir = wp_upload_dir();
        $backup_folder = trailingslashit($upload_dir['basedir']) . 'fnehousing-dbbackups/';
		
		// Synchronize database and file system
		self::syncBackups($backup_folder, $table);

		// Columns to retrieve and send back to DataTable
		$columns = [
			[
				'db' => 'backup_id',
				'dt' => 0,
				'formatter' => function ($d, $row) {
					return [
						'check_box' => '<input type="checkbox" class="fnehd-checkbox" data-fnehd-row-id="' . esc_attr($d) . '">',
						'row_id' => esc_attr($d),
					];
				},
			],
			[
				'db' => 'backup_id',
				'dt' => 1,
				'formatter' => function ($d, $row) {
					static $rowIndex = 0;
					return ++$rowIndex;
				},
			],
			[
				'db' => 'backup_url',
				'dt' => 2,
				'formatter' => function ($d, $row) {
					return "<a target='_blank' href='" . str_replace('.sql', '-log.txt', $d) . "' 
							   id='" . $row['backup_id'] . "' class='btn btn-icon-text fnehd-btn-sm btn-success seeDBLog'>
								<i class='fas fa-eye'></i> 
								<span class='d-none d-md-inline'>" . __('View Log', 'fnehousing') . "</span>
							</a>";
				},
			],
			[
				'db' => 'backup_url',
				'dt' => 3,
				'formatter' => function ($d, $row) {
					return "<a class='btn btn-icon-text fnehd-btn-sm btn-info' data-toggle='tooltip' title='Download Backup' 	
							href='" . $d . "'>
								<i class='fas fa-download'></i> 
								<span class='d-none d-md-inline'>" . __('Download', 'fnehousing') . "</span>
							</a>";
				},
			],
			[
				'db' => 'backup_path',
				'dt' => 4,
				'formatter' => function ($d, $row) {
					return round(filesize($d) / 1000, 2) . "kb";
				},
			],
			[
				'db' => 'creation_date',
				'dt' => 5,
				'formatter' => function ($d, $row) {
					return date_i18n('jS M Y', strtotime($d));
				},
			],
			[
				'db' => 'backup_id',
				'dt' => 6,
				'formatter' => function ($d, $row) {
					return [
						'actions' => self::dbBackupTableActions($d, $row['backup_path']),
					];
				},
			],
		];

		// Use the SSP class for processing
		$response = SSP::simple($_POST, $table, $primaryKey, $columns);

		// Send JSON response
		wp_send_json($response);
	}

	/**
	 * Synchronizes the database table and backup folder.
	 *
	 * @param string $backup_folder Path to the backup folder.
	 * @param string $table Database table name.
	 * @return void
	 */
	public static function syncBackups($backup_folder, $table) {
		global $wpdb;

		// Get all rows in the database table
		$db_rows = $wpdb->get_results("SELECT backup_path FROM $table", ARRAY_A);

		// Collect valid backup paths from the database
		$valid_paths = [];
		foreach ($db_rows as $row) {
			$backup_path = $row['backup_path'];
			if (!file_exists($backup_path)) {
				// Delete row if file is missing
				$wpdb->delete($table, ['backup_path' => $backup_path], ['%s']);
			} else {
				$valid_paths[] = $backup_path;
			}
		}

		// Get all files in the backup folder
		$files = glob($backup_folder . '*.sql');

		// Delete files not in the database
		foreach ($files as $file) {
			if (!in_array($file, $valid_paths)) {
				unlink($file);
			}
		}
	}



    /**
     * Generates action buttons for an database backup row.
     *
     * @param int    $backup_id The backup ID.
     * @param string $backup_path The file path of the database backup.
     * @return string HTML string for the action buttons.
     */
    public static function dbBackupTableActions($backup_id, $backup_path) {
       ob_start();
	   ?>
		<center>
			<a href="#" data-toggle='tooltip' title="Restore Backup" id="<?= $backup_path; ?>" class="btn btn-icon-text fnehd-btn-sm restoreDB fnehd-btn-behance" >
				<i class="fas fa-sync"></i> 
				<span class="d-none d-md-inline"><?= __('Restore', 'fnehousing'); ?></span>
			</a>
			<a href="#" data-toggle="tooltip" title="<?= __('Delete', 'fnehousing'); ?> " id="<?= $backup_id; ?>" 
			   data-bkup-path="<?= $backup_path; ?> " class="btn btn-icon-text fnehd-btn-sm btn-danger deleteDB">
				 <i class='fas fa-trash'></i>
			</a>
			
			</a>
		</center>
        <?php return ob_get_clean();
    }
}
