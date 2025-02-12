<?php

/**
 * Fired when the plugin is uninstalled.
 * @since      1.0.0
 * @package    Fnehousing
 */

	//if uninstall not called from WordPress, then exit.
	if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
		exit;
	}


	function fnehdDeletePlugin() {
		
		global $wpdb;

		delete_option( 'fnehousing_options' );
		
		$fnehd_tbls = $wpdb->get_col("SHOW TABLES like '{$wpdb->prefix}fnehousing%'");
		
		foreach ($fnehd_tbls as $table) {
			$wpdb->query( "DROP TABLE IF EXISTS ".$table );
		}
	}

	if ( ! defined( 'FNEHD_VERSION' ) ) {
		fnehdDeletePlugin();
	}

