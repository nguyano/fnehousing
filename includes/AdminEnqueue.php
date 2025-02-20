<?php

/**
 * Handles admin-specific styles and scripts for Fnehousing.
 *
 * @package Fnehousing
 * @since   1.0.0
 */

namespace Fnehousing;

defined('ABSPATH') || exit;

/**
 * Class AdminEnqueue
 */
class AdminEnqueue {

    /**
     * Registers hooks for enqueueing styles and scripts.
     */
    public function register() {
        add_action('admin_enqueue_scripts', [$this, 'enqueueStyles']);
        add_action('admin_enqueue_scripts', [$this, 'enqueueScripts']);
    }

    /**
     * Checks if the current page matches any of the specified pages.
     *
     * @param array|string $pages Page slug(s) to check.
     * @return bool True if the current page matches, false otherwise.
     */
    private function isFnehdUniquePage($pages) {
        if (!isset($_GET['page'])) {
            return false;
        }

        $currentPage = sanitize_text_field($_GET['page']);
        if (is_array($pages)) {
            return in_array($currentPage, $pages, true);
        }

        return $currentPage === $pages;
    }
	
	
	/**
	 * Check if the current URL is an Fnehousing page.
	 *
	 * @param string $page The page identifier to check.
	 * @return bool True if the current URL matches the specified page, otherwise false.
	 */
	public function isFnehdPage($page) {
		if (isset($_GET['page'])) {
			$current_page = sanitize_text_field($_GET['page']);
			$url_array = explode('-', $current_page);

			return in_array($page, $url_array, true);
		}

		return false;
	}


    /**
     * Enqueues styles for admin pages.
     */
    public function enqueueStyles() {
        // Styles for all Fnehousing admin pages.
        if ($this->isFnehdPage('fnehousing')) {
            $styles = [
                'fnehd-mdb-admin-css'     => 'lib/bootstrap/css/m-dash.min.css',
                'fnehd-choices-admin-css' => 'lib/bootstrap/css/choices.min.css',
                'fnehd-chat-admin-css'    => 'assets/css/fnehd-chat.css',
                'fnehd-fa-admin-css'      => 'lib/fontawesome/css/all.min.css',
                'fnehd-datatb-admin-css'  => 'lib/jquery/css/jquery.dataTables.min.css',
                'fnehd-admin-css'         => 'assets/css/fnehd-admin.css',
            ];

            foreach ($styles as $handle => $path) {
                wp_enqueue_style($handle, FNEHD_PLUGIN_URL . $path, [], FNEHD_VERSION);
            }

            // Add inline CSS for top navigation if applicable.
            if (defined('FNEHD_ADMIN_NAV_STYLE') && FNEHD_ADMIN_NAV_STYLE !== 'sidebar') {
                wp_add_inline_style('fnehd-admin-css', $this->getInlineAdminCss());
            }
        }

        // General WordPress admin styles for all pages.
        wp_enqueue_style('fnehd-wp-admin', FNEHD_PLUGIN_URL . 'assets/css/fnehd-wp-admin-styles.css', [], FNEHD_VERSION);
    }

    /**
     * Enqueues scripts for admin pages.
     */
    public function enqueueScripts() {
        // Shared scripts for all Fnehousing admin pages.
        if ($this->isFnehdPage('fnehousing')) {// Load scripts only if Fnehousing admin area
            $sharedScripts = [
                'jquery',
                'jquery-form',
                'media-upload',
                'fnehd-popper-js'    => 'lib/jquery/js/popper.min.js',
                'fnehd-bootstrap-js' => 'lib/bootstrap/js/m-design.min.js',
                'fnehd-choices-js'   => 'lib/bootstrap/js/choices.min.js',
                'fnehd-swal-js'      => 'lib/jquery/js/sweetalert2.min.js',
                'fnehd-noty-js'      => 'assets/js/notification-script.js',
                'fnehd-settings-js'  => 'assets/js/settings-script.js',
                'fnehd-jasny-js'     => 'lib/bootstrap/js/jasny-bootstrap.min.js',
                'fnehd-wizard-js'    => 'lib/bootstrap/js/jquery.bootstrap-wizard.js',
                'fnehd-bs-colpkr-js' => 'lib/bootstrap/js/bootstrap-colorpicker.min.js',
				'fnehd-datatables-js'=> 'lib/jquery/js/jquery.dataTables.min.js',
				'fnehd-shelter-js'   => 'assets/js/shelter-script.js',
				'fnehd-admin-js'     => 'assets/js/fnehd-admin.js',
				'fnehd-fxns-js'      => 'assets/js/fnehd-functions.js',
            ];
			

            foreach ($sharedScripts as $handle => $path) {
                if (is_int($handle)) {
                    wp_enqueue_script($path);
                } else {
                    wp_enqueue_script($handle, FNEHD_PLUGIN_URL . $path, ['jquery'], FNEHD_VERSION, true);
                }
            }
			
			// Load WP media scripts
			wp_enqueue_media();

			// Page-specific scripts with multiple page support.
			$pageScripts = [
				['pages' => ['fnehousing-users', 'fnehousing-user-profile'], 'scripts' => [
					'fnehd-user-js' => 'assets/js/users-script.js',
				]],
				['pages' => ['fnehousing-db-backups'], 'scripts' => [
					'fnehd-db-backup-js' => 'assets/js/db-backup-script.js',
				]],
				['pages' => ['fnehousing-dashboard', 'fnehousing-stats', 'fnehousing-user-profile'], 'scripts' => [
					'canvas-js' => 'lib/jquery/js/canvasjs.min.js',
				]],
			];

			foreach ($pageScripts as $group) {
				if ($this->isFnehdUniquePage($group['pages'])) {
					foreach ($group['scripts'] as $handle => $path) {
						wp_enqueue_script($handle, FNEHD_PLUGIN_URL . $path, ['jquery'], FNEHD_VERSION, true);
					}
				}
			}

			//localize
			$this->LocalizeScripts();
			
		}	
    }

    /**
     * Returns inline CSS for top navigation.
     *
     * @return string CSS styles.
     */
    private function getInlineAdminCss() {
        return "
            @media (min-width: 991px) { .main-panel { width: 100% !important; } }
            .main-panel > .content { margin-top: 10px; }
			.dark-edition .fnehd-top-nav-item :hover {color: #fff !important;}
			.navbar.bg-dark .fnehd-top-nav-item .active {color: #2271b1 !important; border: 1px solid  #2271b1;!important; margin-left: 0px !important; border-radius: 0px !important; }
			.navbar.bg-dark .fnehd-top-nav-item .active .fa {color: #2271b1 !important;}
			.dark-edition .navbar.bg-dark .fnehd-top-nav-item .active {background-color:#223663; color: #fff !important; border: 1px solid #fff; !important;}
			.dark-edition .navbar.bg-dark .fnehd-top-nav-item .active .fa {color: #fff !important;}
			#screen-options-wrap { width: 100% !important; }
			.navbar .collapse .navbar-nav .nav-item .nav-link { font-size: .78rem; }
			.footer { left: -0.5%; }
			@media (min-width: 1920px) { #navbarSupportedContent{ padding-left: 6.5% !important;} .navbar.navbar-transparent { left: 0; } }";
    }
	
	
	
	// Localize script parameters for general admin use.
	 private function LocalizeScripts() {
			$params = [
				'ajaxurl'                    => admin_url('admin-ajax.php'),
				'is_front_user'              => fnehd_is_front_user() ? true : false,
				'light_svg'                  => fnehd_light_svg(),
				'rest_api_data'              => FNEHD_REST_API_DATA,
				'wpfold'                     => defined('FNEHD_FOLD_WP_MENU') && FNEHD_FOLD_WP_MENU,
				'checkUsersMessage'          => esc_html__('Payer and Earner cannot be the same user. Go back!', 'fnehousing'),
				'home_url'                   => home_url(),
				'interaction_mode'           => FNEHD_INTERACTION_MODE,
				'dbbackup_log_state'         => FNEHD_DBACKUP_LOG,
				'swal'  =>[
				
					'success' => [
						'delete_success' => __('deleted successfully', 'fnehousing'),
						'export_excel_success' => __('Excel Sheet Generated Successfully', 'fnehousing'),
						'checkbox_select_title' => __('Selected', 'fnehousing'),
						'save_sett_success' => __('Settings updated successfully', 'fnehousing'),
						'checkbox_on_text' => __('ON', 'fnehousing'),
						'checkbox_off_text' => __('OFF', 'fnehousing'),
					 ],	
					 'warning' => [
						'title' => __('Are you sure?', 'fnehousing'),
						'text' => __('Cancel if you are not sure!', 'fnehousing'),
						'export_excel_title' => __('Export to Excel?', 'fnehousing'),
						'export_excel_text' => __('An Excel Sheet of your table will be created', 'fnehousing'),
						'export_excel_confirm' => __('Yes, export excel', 'fnehousing'),
						'error_title' => __('Error!', 'fnehousing'),
						'datatable_no_data_text' => __('No data available', 'fnehousing'),	
						'no_records_title' => __('No Record Selected', 'fnehousing'),	
						'no_records_text' => __('Please, select at least 1 record to continue', 'fnehousing'),
						'db_restore_text' => __('Make sure you have a backup copy of the current database before proceeding', 'fnehousing'),
						'db_file_restore_text' => __('Please make sure you choose the right file (use only backup files that were generated here), restoring a wrong file or interrupting the restore process will completely break things and lead to complete lost of data. Please note that already existing database tables will be destroyed. Cancel if you are not sure', 'fnehousing'),
						'delete_records_confirm' => __('Yes, delete records', 'fnehousing'),
						'delete_record_confirm' => __('Yes, delete record', 'fnehousing'),
						'form_error_text' => __('Please fix the errors in the form first', 'fnehousing'),
						'reset_sett_confirm' => __('Yes, restore default settings', 'fnehousing'),
						'import_sett_confirm' => __('Yes, import settings', 'fnehousing'),
						'import_sett_loader_text' => __('Importing Options..Please Wait!', 'fnehousing'),
						'save_sett_loader_text' => __('Saving...Please Wait!', 'fnehousing'),
						'import_sett_text' => __("Please make sure you choose the right file (use only export files that were generated here). Importing a wrong file will lead to complete loss of data. Cancel if you're not sure", 'fnehousing'),
					 ],
					 'shelter' => [
						'add_shelter_confirm' => __('Yes, add shelter', 'fnehousing'),
						'delete_confirm' => __('Yes, delete', 'fnehousing'),
						'shelter_update_confirm' => __('Yes, update shelter', 'fnehousing'),
						'table_reload_success' => __('Table Reloaded successfully', 'fnehousing'),
						'form_error' => __('Please fix the errors in the form first (User does not exist )', 'fnehousing')
					 ],
					 'user' => [
						'user_not_found' => __('is not a valid user, user not found!!', 'fnehousing'),
						'email_already_exist' => __('User with the provided email already exists', 'fnehousing'),
						'user_already_exist' => __('User with the provided username already exists', 'fnehousing'),
						'add_user_confirm' => __('Yes, add user', 'fnehousing'),
						'delete_user_confirm' => __('Yes, delete User', 'fnehousing'),
						'delete_user_text' => __("You won't be able to revert this! All data linked to this User(s) (shelters, notifications..etc) will also be deleted. Do you still want to continue?", 'fnehousing'),
						'update_confirm' => __('Yes, update user', 'fnehousing'),
						'user_singular' => __('user', 'fnehousing'),
						'user_singular_deleted' => __('user deleted', 'fnehousing'),
						'user_plural' => __('users', 'fnehousing'),
						'user_plural_deleted' => __('users deleted', 'fnehousing')
					 ],
					 'dbbackup' => [
						'restore_confirm' => __('Yes, restore database', 'fnehousing'),
						'restore_init_text' => __('Initializing Restore...', 'fnehousing'),
						'restore_fail_title' => __('Restore Failed', 'fnehousing'),
						'restore_poll_fail_text' => __('AJAX polling failed', 'fnehousing'),
						'restore_fail_text' => __('An unexpected error occurred during the restore process', 'fnehousing'),
						'delete_confirm' => __('Yes, delete backup', 'fnehousing'),
						'backup_text' => __('A backup of your database will be created. Note: only plugin tables will be included', 'fnehousing'),
						'backup_confirm' => __('Yes, create backup', 'fnehousing'),
						'backup_singular' => __('backup', 'fnehousing'),
						'backup_singular_deleted' => __('backup deleted', 'fnehousing'),
						'backup_plural' => __('backups', 'fnehousing'),
						'backup_plural_deleted' => __('backups deleted', 'fnehousing')
					 ],
					 'dispute' => []
				]	 
													
			];

			wp_localize_script('fnehd-admin-js', 'fnehd', $params);
			
	   }	
	   
	   
}
