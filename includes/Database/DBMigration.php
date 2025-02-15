<?php
/**
 * Database Migration class of the plugin.
 * Handles database table creation and setup on activation.
 *
 * @since 1.0.0
 * @package Fnehousing
 */

namespace Fnehousing\Database;

defined('ABSPATH') || exit;

class DBMigration {
	
    private $tables;

    /**
     * Constructor to initialize table names.
     */
    public function __construct()
    {
        global $wpdb;

        $this->tables = [
            'sheltersTable'          => $wpdb->prefix . 'fnehousing_shelters',
			'activityLogTable'  	 => $wpdb->prefix . 'fnehousing_activity_log',
            'dbbackupTable'          => $wpdb->prefix . 'fnehousing_dbbackups',
            'notificationsTable'     => $wpdb->prefix . 'fnehousing_notifications',
        ];
    }

    /**
     * Registers hooks for database migration.
     */
    public function register()
    {
        add_action('fnehd_db_migration', [$this, 'createDBTables']);
    }

    /**
     * Creates database tables using dbDelta.
     */
    public function createDBTables(){
		
        global $wpdb;
        
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $sqls = [];
		
		$charset_collate = $wpdb->get_charset_collate();

        // Define the SQL for each table
        $sqls[] = "CREATE TABLE {$this->tables['sheltersTable']} (
					shelter_id INT(30) NOT NULL AUTO_INCREMENT,
					ref_id VARCHAR(30) NOT NULL,
					user_id VARCHAR(30) NOT NULL,
					shelter_name VARCHAR(500) NOT NULL,
					shelter_organization VARCHAR(500) NOT NULL,
					email VARCHAR(500) NOT NULL,			
					phone VARCHAR(200) NOT NULL,
					fax VARCHAR(200) NOT NULL,
					website VARCHAR(200) NOT NULL,
					address LONGTEXT NOT NULL,
					manager VARCHAR(500) NOT NULL,
					project_type VARCHAR(500) NOT NULL,
					description LONGTEXT NOT NULL,
					main_image VARCHAR(500) NOT NULL,
					gallery LONGTEXT NOT NULL,
					eligible_individuals LONGTEXT NOT NULL,
					accepted_ages LONGTEXT NOT NULL,
					specific_services LONGTEXT NOT NULL,
					hours VARCHAR(200) NOT NULL,
					pet_policy VARCHAR(200) NOT NULL,
					availability VARCHAR(200) NOT NULL,
					bed_capacity VARCHAR(200) NOT NULL,
					available_beds VARCHAR(200) NOT NULL,
					referrals INT(30) NOT NULL,
					creation_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
					PRIMARY KEY (shelter_id)
				) $charset_collate;";
				
		$sqls[] = "CREATE TABLE {$this->tables['activityLogTable']} (
					log_id INT(30) NOT NULL AUTO_INCREMENT,
					ref_id VARCHAR(30) NOT NULL,
					user_id VARCHAR(30) NOT NULL,
					subject VARCHAR(30) NOT NULL,
					details LONGTEXT NOT NULL,
					amount VARCHAR(500) NOT NULL,
					balance VARCHAR(500) NOT NULL,
					creation_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
					PRIMARY KEY (log_id)
				) $charset_collate;"; 		
				
		$sqls[] = "CREATE TABLE {$this->tables['dbbackupTable']} (
					backup_id INT(30) NOT NULL AUTO_INCREMENT,
					creation_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
					backup_url VARCHAR(500) NOT NULL,
					backup_path VARCHAR(1000) NOT NULL,
					mode VARCHAR(30) NOT NULL,
					PRIMARY KEY (backup_id)
				) $charset_collate;";

        $sqls[] = "CREATE TABLE {$this->tables['notificationsTable']} (
					notification_id INT(30) NOT NULL AUTO_INCREMENT,
					user_id VARCHAR(30) NOT NULL,
					subject_id VARCHAR(30) NOT NULL,
					subject VARCHAR(250) NOT NULL,
					message VARCHAR(1000) NOT NULL,
					status INT(30) NOT NULL,
					date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
					PRIMARY KEY (notification_id)
				) $charset_collate;";

        
        foreach ($sqls as $sql) {
            dbDelta($sql); //create table
        }
		
		
    }
	
}
