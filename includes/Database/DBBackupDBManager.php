<?php
/**
 * The DB Backup Database Interaction class of the plugin.
 * Handles all database interactions for backup actions.
 *
 * @since      1.0.0
 * @package    Fnehousing
 */

namespace Fnehousing\Database;

defined('ABSPATH') || exit;

class DBBackupDBManager {

    private $dbbackup_table;

    /**
     * Constructor: Initializes the class and sets up the table name.
     */
    public function __construct() {
        global $wpdb;
        $this->dbbackup_table = $wpdb->prefix . 'fnehousing_dbbackups';
    }

    /**
     * Creates a database backup.
     *
     * @return array Contains the backup SQL script and a log.
     */
    public function dbBackup() {
        global $wpdb;

        $response = ['backup' => '', 'log' => ''];
        $log = "#--------------------------------------------------------\n";
        $log .= " Database Table Backup (Row Count)\n";
        $log .= "#--------------------------------------------------------\n";

        // Fetch all Fnehousing-related tables.
        $tables = $wpdb->get_col($wpdb->prepare("SHOW TABLES LIKE %s", "{$wpdb->prefix}fnehousing%"));
        $output = '';

        foreach ($tables as $table) {
            $log .= "\n$table";

            // Drop and recreate the table for restore operations.
            $output .= "DROP TABLE IF EXISTS `$table`;";
            $create_table_query = $wpdb->get_row("SHOW CREATE TABLE `$table`", ARRAY_N);
            $output .= "\n\n" . $create_table_query[1] . ";\n\n";

            // Retrieve and log table data.
            $rows = $wpdb->get_results("SELECT * FROM `$table`", ARRAY_N);
            $log .= " (" . count($rows) . " rows)";

            foreach ($rows as $row) {
                $escaped_row = array_map([$wpdb, '_real_escape'], $row);
                $values = '"' . implode('","', $escaped_row) . '"';
                $output .= "INSERT INTO `$table` VALUES($values);\n";
            }
            $output .= "\n";
        }

        $wpdb->flush();

        $log .= "\n#--------------------------------------------------------\n";
        $response['backup'] = $output;
        $response['log'] = $log;

        return $response;
    }
	
	
	// Scheduled restore event (cron)
	public function dbRestore($file_name) {
		$progress_file = $this->getProgressFilePath();

		global $wpdb;
		$sql = '';
		$lines = file($file_name, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		$total_lines = count($lines);
		$executed_queries = 0;
		$errors = [];

		foreach ($lines as $index => $line) {
			$trimmed_line = trim($line);
			if (substr($trimmed_line, 0, 2) === '--' || $trimmed_line === '') {
				continue;
			}

			$sql .= $trimmed_line;

			if (substr($trimmed_line, -1) === ';') {
				try {
					$wpdb->query($sql);
					$executed_queries++;
				} catch (Exception $e) {
					$errors[] = ['query' => $sql, 'error' => $e->getMessage()];
				}
				$sql = '';
			}

			// Update progress every 100 lines or at the end
			if ($index % 100 === 0 || $index === $total_lines - 1) {
				$progress = [
					'percent_complete' => round(($index / $total_lines) * 100, 2),
					'message' => __('Restoring database...', 'fnehousing'),
					'completed' => $index === $total_lines - 1,
				];
				file_put_contents($progress_file, json_encode($progress));
			}
		}

		// Final update
		$progress = [
			'percent_complete' => 100,
			'message' => __('Restore completed.', 'fnehousing'),
			'completed' => true,
		];
		file_put_contents($progress_file, json_encode($progress));
	}
	
	
	/**
     * Gets the path to the restore progress file.
     *
     * @return string The progress file path.
     */
    public function getProgressFilePath() {
		return wp_upload_dir()['basedir'] . '/db_restore_progress.json';
	}
	
	

    /**
     * Inserts a new backup record into the database.
     *
     * @param string $backup_url  The URL of the backup file.
     * @param string $backup_path The file path of the backup file.
     */
    public function insertDBBackup($backup_url, $backup_path) {
        global $wpdb;

        $wpdb->insert(
            $this->dbbackup_table,
            [
                'backup_url' => esc_url($backup_url),
                'backup_path' => sanitize_text_field($backup_path),
            ]
        );
    }

    /**
     * Retrieves all backup records.
     *
     * @return array List of backup records.
     */
    public function displayDBBackups() {
        global $wpdb;

        $sql = "SELECT * FROM {$this->dbbackup_table} ORDER BY backup_id DESC";
        return $wpdb->get_results($sql, ARRAY_A);
    }

    /**
     * Counts the total number of backups.
     *
     * @return int The total count of backups.
     */
    public function totalDBBackups() {
        global $wpdb;

        return (int) $wpdb->get_var("SELECT COUNT(*) FROM {$this->dbbackup_table}");
    }

    /**
     * Deletes a single backup record and associated files.
     *
     * @param int $backup_id The ID of the backup to delete.
     */
    public function deleteDB($backup_id) {
        global $wpdb;

        $backup_id = (int) $backup_id;
        $wpdb->delete($this->dbbackup_table, ['backup_id' => $backup_id], ['%d']);
    }

    /**
     * Deletes multiple backup records and associated files.
     *
     * @param array $backup_ids List of backup IDs to delete.
     */
    public function deleteMultDBs($backup_ids) {
        global $wpdb;

		$ids = array_map('absint', explode(',', $backup_ids));
        foreach ($ids as $id) {
            $this->deleteDB($id);
        }
    }
}
