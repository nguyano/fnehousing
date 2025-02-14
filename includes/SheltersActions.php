<?php
/**
 * The Shelter actions class of the plugin.
 * Defines and hook all Shelter Actions.
 *
 * @since 1.0.0
 * @package Fnehousing
 */
 
namespace Fnehousing;

use Fnehousing\Database\ShelterDBManager;

defined('ABSPATH') || exit;

/**
 * Class SheltersActions
 * Manages all actions related to shelters within the Fnehousing plugin.
 */
class SheltersActions extends ShelterDBManager {

    /**
     * Register hooks for AJAX and other actions.
     */
    public function register() {
        // Map of AJAX actions to their corresponding methods.
        $ajax_actions = [
            'fnehd_shelters' => 'actionDisplayShelters',
            'fnehd_activity_log' => 'actionDisplayActivityLog',
            'fnehd_reload_shelter_tbl' => 'actionReloadShelters',
            'fnehd_insert_shelter' => 'actionInsertShelter',
			'fnehd_update_shelter' => 'actionUpdateShelter',
            'fnehd_shelter_data' => 'actionGetShelterDataById',
            'fnehd_del_shelter' => 'actionDeleteShelter',
            'fnehd_del_shelters' => 'actionDeleteShelters',
            'fnehd_del_log' => 'actionDeleteLog',
            'fnehd_del_logs' => 'actionDeleteLogs',
            'fnehd_export_shelters_excel' => 'exportSheltersToExcel',
            'fnehd_export_log_excel' => 'exportLogToExcel',
            'fnehd_shelter_search' => 'actionShelterSearch',
            'fnehd_reload_shelter_search' => 'reloadSearchResults',
			'fnehd_dash_shelters' => 'actionDisplayDashShelters'
        ];

        // Register AJAX actions for logged-in users.
        foreach ($ajax_actions as $action => $method) {
            add_action("wp_ajax_$action", [$this, $method]);
        }

        // Register specific public-facing AJAX actions.
        add_action('wp_ajax_nopriv_fnehd_insert_shelter', [$this, 'actionInsertShelter']);

        // Custom hooks for logging activity.
        add_action('fnehd_log_activity', [$this, 'logActivity']);
    }
	

    /**
     * Load template for displaying data.
     *
     * @param string $template_path Relative path to the template.
     * @param array  $data          Data to pass to the template.
     */
    private function loadTemplate($template_path, $data = [], $die = true) {
        extract($data); // Extract array to variables for template usage.
        include_once FNEHD_PLUGIN_PATH . "templates/shelters/$template_path.php";
        if($die) wp_die(); // Terminate script after template is loaded.
		
    }


    /**
     * Display Shelters Table.
     */
    public function actionDisplayShelters() {
        $data = ['data_count' => $this->getTotalShelterCount()];
        $this->loadTemplate('shelters', $data);
    }


    /**
     * Display Activity Log Table.
     */
    public function actionDisplayActivityLog() {
        $data = ['log_count' => $this->getAdminActivityLogCount()];
        $this->loadTemplate('activity-log', $data);
    }

    /**
     * Reload Shelters Table.
     */
    public function actionReloadShelters() {
        $data = [];

        if (fnehd_is_front_user()) {
            // For front-end users, filter shelters by username.
            $user_id = get_current_user_id();
            $username = get_user_by("ID", $user_id)->user_login;
            $data['data_count'] = $this->getUserSheltersCount($username);
        } else {
            // For back-end users, load all shelters.
            $data['data_count'] = $this->getTotalShelterCount();
        }

        $this->loadTemplate('shelters-table', $data);
    }

    

	/**
     * Display Dashboard Shelters Table.
     */
	public function actionDisplayDashShelters() {
		include FNEHD_PLUGIN_PATH."templates/admin/dashboard/dashboard-shelters-table.php";  
		wp_die();
	}
	
	
	/**
	 * Add a new shelter entry.
	 */
	public function actionInsertShelter() {
		// Verify the nonce for security.
		fnehd_validate_ajax_nonce('fnehd_shelter_nonce', 'nonce');

		// Sanitize and prepare form data.
		$form_fields = ['shelter_name', 'shelter_organization', 'email', 'phone', 'address', 'manager', 'project_type', 'description', 'main_image', 'gallery', 'hours', 'pet_policy', 'availability', 'bed_capacity', 'available_beds'];
		$multiple_select_fields = ['eligible_individuals', 'accepted_ages', 'specific_services'];
		
		$multiple_select_data = fnehd_sanitize_mult_select_form_data($multiple_select_fields);
		
		$data = fnehd_sanitize_form_data($form_fields);
		$data['ref_id'] = fnehd_unique_id();
		$data['user_id'] = get_current_user_id();
		$data['referrals'] = 0;
	
	    $data = array_merge($data, $multiple_select_data);

		//Ensure admin user has permission
		if (!fnehd_is_front_user()) {
			fnehd_verify_permissions('manage_options');
		}

		// Insert shelter and related meta data.
		$this->insertData($this->tables->shelters, $data);

		// Log and notify.
		/* fnehd_log_notify_new_shelter(
			fnehd_unique_id(),
			$data['payer'],
			$data['earner'],
			$meta_data['amount'],
			$new_balance,
			$user_id,
			$shelter_id,
			$data['ref_id']
		);

		fnehd_new_shelter_email(
			$data['ref_id'],
			$meta_data['status'],
			$data['earner'],
			$amount,
			$user->user_email,
			$meta_data['title'],
			$meta_data['details']
		);
		*/
		// Send success response.
		wp_send_json_success(['message' => __('Shelter added successfully', 'fnehousing'), 'shelter_id' => $this->getLastInsertedShelterID(), 'data' => $data['eligible_individuals']]);
	}


	/**
	 * Add a new shelter entry.
	 */
	public function actionUpdateShelter() {
		// Verify the nonce for security.
		//fnehd_validate_ajax_nonce('fnehd_shelter_update_nonce', 'update_nonce');

		// Sanitize and prepare form data.
		$form_fields = ['shelter_id', 'shelter_name', 'shelter_organization', 'email', 'phone', 'address', 'manager', 'project_type', 'description', 'main_image', 'gallery', 'hours', 'pet_policy', 'availability', 'bed_capacity', 'available_beds'];
		$data = fnehd_sanitize_form_data($form_fields);
		
		$multiple_select_fields = ['eligible_individuals', 'accepted_ages', 'specific_services'];
		$multiple_select_data = fnehd_sanitize_mult_select_form_data($multiple_select_fields);
		
		$data = array_merge($data, $multiple_select_data);
		

		//Ensure admin user has permission
		if (!fnehd_is_front_user()) {
			fnehd_verify_permissions('manage_options');
		}

		// Update shelter data.
		$this->updateData($this->tables->shelters, $data, ['shelter_id' => $data['shelter_id']]);
		
		// Send success response.
		wp_send_json_success(['message' => __('Shelter updated successfully', 'fnehousing')]);
	}
	
	
	  /**
     * Get shelter by ID.
     */
    public function actionGetShelterDataById() {
		
        if (!isset($_POST['ShelterId'])) {
			wp_send_json_success(['data' => []]);
		}

        $shelter_id = intval($_POST['ShelterId'] ?? 0);
        $row = $this->getShelterById($shelter_id);

        wp_send_json_success($row);
    }

	
	//Delete Shelter 
	public function actionDeleteShelter() {		
	  if(isset($_POST['delID'])) {
		 $shelter_id = $_POST['delID'];
		 $this->deleteShelter($shelter_id);
		 wp_send_json(['label' => 'Shelter']);
	  }
	}  

	//Deletet Multiple Shelters
	public function actionDeleteShelters() {		
	  if(isset($_POST['multID'])) {
		 $multID = $_POST['multID'];
		 $this->deleteShelters($multID);
		 wp_send_json(['label' => 'Shelter']);
	  }
	} 
	
	//Delete activity log
	public function actionDeleteLog() {		
	  if(isset($_POST['delID'])) {
		 $log_id = $_POST['delID'];
		 $this->deleteLog($log_id);
		 wp_send_json(['label' => 'Log']);
	  }
	}  


    //View Shelter Details
	public function actionViewMilestoneDetail() {		
	   if(isset($_POST['meta_id'])) {
		 $shelter_id = $_POST['meta_id'];
		 $detail = $this->getMetaById($shelter_id)['details'];
		 wp_send_json(['data' => $detail, 'mode' => FNEHD_PLUGIN_INTERACTION_MODE]);
	  }
	}
   

	/**
	 * Export Shelters to Excel
	 */
	public function exportSheltersToExcel() {
		$data = $this->fetchAllShelters();
		$columns = [
			'ID.' => 'shelter_id',
			'Payer' => 'payer',
			'Earner' => 'earner'
		];
		fnehd_excel_table($data, $columns, 'shelters');
	}


	/**
	 * Export Activity Log to Excel
	 */
	public function exportLogToExcel() {
		$data = $this->fetchAdminActivityLogs();
		$columns = [
			'ID.' => 'id',
			'Activity ID' => 'ref_id',
			'Details' => 'details',
			'Amount' => 'amount',
			'Date Created' => 'creation_date'
		];
		fnehd_excel_table($data, $columns, 'activity-log');
	}
	

	/**
	 * Shelter search 
	 */
	public function reloadSearchResults() { 
	
	    $text = isset($_POST["search"])? sanitize_text_field($_POST["search"]) : "";
		$data = [
			'text' => $text,
			'data_count' => $this->shelterSearchCount($text)
		];
        $this->loadTemplate('shelter-search-results', $data);
	} 
	
	public function actionShelterSearch() { 
	
		fnehd_validate_ajax_nonce('fnehd_shelter_search_nonce', 'nonce');
        $this->reloadSearchResults();
	}
	


} 
