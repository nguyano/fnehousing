<?php

/**
 * Fired during plugin activation.
 * Handles all tasks necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Fnehousing
 */

namespace Fnehousing\Base;

defined('ABSPATH') || exit;

class Activator extends optionFields {

    /**
     * Fired during plugin activation.
     *
     * @return void
     */
    
	public static function activate() {
		self::checkPhpVersion();
        self::createCustomRoles();
		do_action('fnehd_db_migration');
	    !get_option('fnehousing_options') && do_action('fnehd_default_options');
		if (!file_exists(FNEHD_SHELTER_JSON_DIR)) {//create editable json directory
			mkdir(FNEHD_SHELTER_JSON_DIR, 0755, true);
		}
    }
	
	public static function checkPhpVersion() {
        // Check PHP version compatibility
        $required_php_version = '7.4.0';
        if (version_compare(PHP_VERSION, $required_php_version, '<')) {
            add_action('admin_notices', function () use ($required_php_version) {
                printf(
                    '<div class="notice notice-error"><p>%s</p></div>',
                    esc_html__(
                        "Fnehousing requires PHP version {$required_php_version} or higher. Please update your PHP version.",
                        'fnehousing'
                    )
                );
            });

            // Exit to prevent activation if PHP version is incompatible
            deactivate_plugins(plugin_basename(__FILE__));
            return;
        }
	 }	


    private static function createCustomRoles() {
        // Add custom roles
        if (!get_role('fnehousing_user')) {
            add_role('fnehousing_user', 'Fnehousing User', get_role('subscriber')->capabilities);
        }
    }
	
	
}
