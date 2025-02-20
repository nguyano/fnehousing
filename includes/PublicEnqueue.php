<?php

/**
 * Enqueues all the public-facing scripts of the plugin.
 * @since      1.0.0
 *
 * @package    Fnehousing
 */

namespace Fnehousing;

defined('ABSPATH') || exit;

/**
 * Class PublicEnqueue
 */
class PublicEnqueue {

    /**
     * Register all frontend hooks.
     */
    public function register() {
        add_action('wp_enqueue_scripts', [$this, 'enqueueStyles']);
        add_action('wp_enqueue_scripts', [$this, 'enqueueScripts']);
    }

    /**
     * Register frontend stylesheets.
     */
    public function enqueueStyles() {
        $styles = [
            'fnehd-md-pub-css'   => 'lib/bootstrap/css/m-design.min.css',
			'pagination-css'     => 'lib/jquery/css/pagination.min.css',
			'fnehd-choices-pub-css' => 'lib/bootstrap/css/choices.min.css',
            'fnehd-dt-pub-css'   => 'lib/jquery/css/jquery.dataTables.min.css',
            'fnehd-chat-pub-css' => 'assets/css/fnehd-chat.css',
            'fnehd-fa-pub-css'   => 'lib/fontawesome/css/all.min.css',
            'fnehd-pub-css'      => 'assets/css/fnehd-public.css'
        ];

        foreach ($styles as $handle => $path) {
            wp_enqueue_style($handle, FNEHD_PLUGIN_URL . $path, [], FNEHD_VERSION, 'all');
        }

        $this->addCustomCss();
    }

    /**
     * Adds custom inline CSS.
     */
    public static function addCustomCss() {
        $customColors = "
            :root {
                --fnehd-primary-color: " . FNEHD_PRIMARY_COLOR . ";
                --fnehd-secondary-color: " . FNEHD_SECONDARY_COLOR . ";
                --fnehd-font-family: inherit, jost, Roboto, Helvetica, Arial, tahoma;
            }";

        $customCss = is_user_logged_in()
            ? "@media only screen and (max-width: 600px) { .fnehd-logged-out { display: none !important; } }"
            : "@media only screen and (max-width: 600px) { .fnehd-logged-in { display: none !important; } }";

        wp_add_inline_style('fnehd-pub-css', $customColors . $customCss . FNEHD_CUSTOM_CSS);
    }

    /**
     * Register frontend JavaScripts.
     */
    public function enqueueScripts() {
        $scripts = [
            'jquery',
            'jquery-form',
            'fnehd-popr-pup-js'   => 'lib/jquery/js/popper.min.js',
			'pagination-js'       => 'lib/jquery/js/pagination.min.js',
			'fnehd-choices-pub-js'=> 'lib/bootstrap/js/choices.min.js',
            'fnehd-bt-pub-js'     => 'lib/bootstrap/js/m-design.min.js',
            'fnehd-dt-pub-js'     => 'lib/jquery/js/jquery.dataTables.min.js',
            'fnehd-swal-pub-js'   => 'lib/jquery/js/sweetalert2.min.js',
            'fnehd-jny-pub-js'    => 'lib/bootstrap/js/jasny-bootstrap.min.js',
            'fnehd-wiz-pub-js'    => 'lib/bootstrap/js/jquery.bootstrap-wizard.js',
            'fnehd-shelter-pub-js'=> 'assets/js/shelter-script.js',
            'fnehd-user-pub-js'   => 'assets/js/users-script.js',
            'fnehd-noty-pub-js'   => 'assets/js/notification-script.js',
			'fnehd-fxns-pub-js'   => 'assets/js/fnehd-functions.js',
			'fnehd-pub-js'        => 'assets/js/fnehd-public.js'
			
        ];
		
		

        foreach ($scripts as $handle => $path) {
           if (is_int($handle)) {
				wp_enqueue_script($path);
			} else {
				wp_enqueue_script($handle, FNEHD_PLUGIN_URL . $path, ['jquery'], FNEHD_VERSION, true);
			}
		}
		
		wp_enqueue_script(
			'fnehd-gmap-js', 'https://maps.googleapis.com/maps/api/js?key='.FNEHD_GOOGLE_MAP_API_KEY.'&callback=initMap&libraries=places', 
			array('jquery'), // Dependencies (jQuery in this case)
			null, // Version (null disables versioning)
			true // Load script in the footer
		);

        // Localize script parameters
        $params = [
            'ajaxurl'       => admin_url('admin-ajax.php'),
			'interaction_mode' => FNEHD_INTERACTION_MODE,
            'is_front_user' => fnehd_is_front_user() ? true : false,
			'all_shelters_rest_url' => esc_url_raw(rest_url(FNEHD_PLUGIN_NAME.'/v1/listings')),
			'total_shelter_count' => fnehd_shelter_count().__(' results', 'fnehousing'),
			'available_shelter_count' => fnehd_shelter_availability_count('Available').__(' results', 'fnehousing'), 
			'unavailable_shelter_count' => fnehd_shelter_availability_count('Unavailable').__(' results', 'fnehousing'),
			'available_shelters_rest_url' => esc_url_raw(rest_url(FNEHD_PLUGIN_NAME.'/v1/available-listings')),
			'unavailable_shelters_rest_url' => esc_url_raw(rest_url(FNEHD_PLUGIN_NAME.'/v1/unavailable-listings')),
			'shelters_search_rest_url' => esc_url_raw(rest_url(FNEHD_PLUGIN_NAME.'/v1/shelter-search-listings')),
			'default_shelter_img' => FNEHD_PLUGIN_URL."assets/img/fne-default-home.webp",
			'fneIconUrl' => FNEHD_PLUGIN_URL . 'assets/img/fnehousing-icon.png',
			'fneShelterUrl' => home_url() . '?endpoint=view_shelter',
			'swal'  =>[

				'success' => [
					'delete_success' => __('deleted successfully', 'fnehousing'),
					'export_excel_success' => __('Excel Sheet Generated Successfully', 'fnehousing'),
					'checkbox_select_title' => __('Celected', 'fnehousing'),
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
					'logout_confirm' => __('Yes, logout', 'fnehousing'),
					'update_pass_confirm' => __('Yes, update password', 'fnehousing'),
					'reset_pass_confirm' => __('Yes, reset password', 'fnehousing'),
					'update_pass_text' => __('You will need to login again', 'fnehousing'),
					'user_singular' => __('user', 'fnehousing'),
					'user_singular_deleted' => __('user deleted', 'fnehousing'),
					'user_plural' => __('users', 'fnehousing'),
					'user_plural_deleted' => __('users deleted', 'fnehousing')
				 ],
				 'dispute' => []
			]
        ];
        wp_localize_script('fnehd-pub-js', 'fnehd', $params);
    }

    
}
                                                                        