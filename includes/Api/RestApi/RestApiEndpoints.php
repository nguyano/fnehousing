<?php 
/**
 * Custom REST API Endpoint class for the plugin
 * Defines REST API endpoints to export specific data
 *
 * @since      1.0.0
 * @package    Fnehousing
 */

namespace Fnehousing\Api\RestApi;

use Fnehousing\Database\ShelterDBManager;

use WP_REST_Request; 

defined('ABSPATH') || exit;

class RestApiEndpoints {


	private   $shelter;
	
	 public function __construct() {
        $this->shelter = new ShelterDBManager();
    }
	
     
    /**
     * Register REST API routes
     */
    public function pluginBasicDataEndpoint() {
        register_rest_route(FNEHD_PLUGIN_NAME.'/v1', '/basic-plugin-data', [
            'methods'  => 'GET',
            'callback' => [$this, 'getFnehousingEndpointData'], // Specify class method
            'permission_callback' => function() {
				return $this->checkApiAccess('plugin_basics'); //valid permission & authentication
			},
        ]);
    }
	public function allShelterListingEndpoint() {
        register_rest_route(FNEHD_PLUGIN_NAME.'/v1', '/listings', [
            'methods' => 'POST',
            'callback' => [$this, 'getAllShelterListings'], 
			'permission_callback' => [$this, 'checkApiAccess']
        ]);
    }
	public function availableShelterListingEndpoint() {
        register_rest_route(FNEHD_PLUGIN_NAME.'/v1', '/available-listings', [
            'methods' => 'POST',
            'callback' => [$this, 'getAvailableShelterListings'],
			'permission_callback' => [$this, 'checkApiAccess']
        ]);
    }
	public function unavailableShelterListingEndpoint() {
        register_rest_route(FNEHD_PLUGIN_NAME.'/v1', '/unavailable-listings', [
            'methods' => 'POST',
            'callback' => [$this, 'getUnavailableShelterListings'], 
			'permission_callback' => [$this, 'checkApiAccess']
        ]);
    }
	
	public function searchShelterListingEndpoint() {
		register_rest_route(FNEHD_PLUGIN_NAME . '/v1', '/shelter-search-listings', [
			'methods' => 'POST',
			'callback' => [$this, 'getSearchShelterListings'],
			'permission_callback' => [$this, 'checkApiAccess']
		]);
	}
	
	
	/**
	 * Checks if API key validation is required or bypassed, and if requested data is restricted.
	 *
	 * @param string $data_type The type of data being accessed.
	 * @return bool|\WP_Error True if access is granted, otherwise WP_Error.
	 */
	public function checkApiAccess($data_type = 'shelters') {
		// Allow internal requests
		if ($this->isInternalRequest()) {
			return true;
		}

		// Filter allowed data types
		$data_types = FNEHD_REST_API_DATA;
		if (!in_array($data_type, $data_types)) {
			return new \WP_Error('missing_api_key', __('Access to requested data has been restricted.', 'fnehousing'), ['status' => 403]);
		}

		// Allow open access if API key feature is disabled
		if (!defined('FNEHD_ENABLE_REST_API_KEY') || !FNEHD_ENABLE_REST_API_KEY) {
			return true;
		}

		return $this->validateApiKey();
	}


	/**
	 * Check if the request is coming from the same domain (internal request).
	 *
	 * @return bool True if internal request, false otherwise.
	 */
	protected function isInternalRequest() {
		// Get the server's hostname
		$server_host = parse_url(home_url(), PHP_URL_HOST);

		// Get the requesting host
		$request_host = $_SERVER['HTTP_HOST'] ?? '';

		// Check if request comes from the same domain
		return $server_host === $request_host;
	}

		
	
	/**
	 *get shelter listings based availability state
	 */
    public function getAllShelterListings() {
        $listings = $this->shelter->fetchAllShelters();
        return rest_ensure_response($listings);
    }

	public function getAvailableShelterListings() {
        $listings = $this->shelter->fetchSheltersByAvailability('Available');
        return rest_ensure_response($listings);
    }
	
	public function getUnavailableShelterListings() {
        $listings = $this->shelter->fetchSheltersByAvailability('Unavailable');
        return rest_ensure_response($listings);
    }
	
	
	/**
	 * Shelter search frontend
	 */
	public function getSearchShelterListings(WP_REST_Request $request) {

		$search = sanitize_text_field($request->get_param('search'));
		$gender = (array) $request->get_param('gender'); // Convert to array if not already
		$age = (array) $request->get_param('age');
		$pets = (array) $request->get_param('pets');
		$assistance = (array) $request->get_param('assistance');
		$shelter_type = (array) $request->get_param('shelter_type');
		$beds = intval($request->get_param('beds')) ?? 0;

		$conditions = ["1=1"]; // Default condition (always true)
		$params = [];

		// Search keyword (applies to multiple fields)
		if (!empty($search)) {
			$conditions[] = "(shelter_name LIKE %s OR shelter_organization LIKE %s OR description LIKE %s)";
			$params[] = "%{$search}%";
			$params[] = "%{$search}%";
			$params[] = "%{$search}%";
		}

		// Use LIKE instead of IN for flexible searches
		foreach (['gender' => 'eligible_individuals', 'age' => 'accepted_ages', 'pets' => 'pet_policy', 'assistance' => 'specific_services', 'shelter_type' => 'project_type'] as $param => $column) {
			if (!empty($$param)) {
				$subConditions = [];
				foreach ($$param as $value) {
					$subConditions[] = "$column LIKE %s";
					$params[] = "%{$value}%"; 
				}
				$conditions[] = '(' . implode(' AND ', $subConditions) . ')';
			}
		}

		// Beds availability condition
		if ($beds > 0) {
			$conditions[] = "available_beds >= %d";
			$params[] = $beds;
		}
		
		$results = $this->shelter->shelterSearchListings($conditions, $params);

		return rest_ensure_response($results);
	}

	
	

    /**
     * Retrieve basic plugin data
     * 
     * @param \WP_REST_Request $request The REST API request object.
     * @return array|\WP_Error The response data or an error.
     */
    public function getBasicPluginData(\WP_REST_Request $request) {
        $data = [
            'plugin_name'    => FNEHD_PLUGIN_NAME,
            'plugin_version' => FNEHD_VERSION,
            'plugin_cat'     => 'Ngunyi Yannick'
        ];
        return rest_ensure_response($data);
    }
	

    /**
     * Generate a new REST API key for the current user
     */
    public function generateRestApiKey() {
		// Check user's permission
		fnehd_verify_permissions('manage_options');
		
        // Ensure REST API functionality is enabled
        if (!(defined('FNEHD_ENABLE_REST_API') && FNEHD_ENABLE_REST_API)) {
            wp_send_json_error(['message' => __('REST API functionality is disabled. Make sure "Share Data via REST API" is enabled.', 'fnehousing')]);
        }

        // Ensure the request is valid
        if (!current_user_can('edit_user', get_current_user_id())) {
            wp_send_json_error(['message' => __('Unauthorized', 'fnehousing')], 403);
        }

        $api_key = wp_generate_password(32, false); // Generate a secure API key
        update_user_meta(get_current_user_id(), 'api_key', $api_key); // Store the API key in user meta

        wp_send_json_success([
            'message' => __('Key generated successfully.', 'fnehousing'),
            'key'     => $api_key,
        ]);
    }
	

    /**
     * Generate a REST API endpoint URL
     */
    public function generateRestApiUrl() {
		// Check user's permission
		fnehd_verify_permissions('manage_options');
		
        // Ensure REST API functionality is enabled
        if (!(defined('FNEHD_ENABLE_REST_API') && FNEHD_ENABLE_REST_API)) {
            wp_send_json_error(['message' => __('REST API functionality is disabled. Make sure "Share Data via REST API" is enabled.', 'fnehousing')]);
        }
		
		$urls = "";
		$rest_urls = [
			'Basic Plugin data' => 'basic-plugin-data', 
			'All Shelter Data' => 'listings', 
			'Available Shelter Data' => 'available-listings', 
			'Unavailable Shelter Data' => 'unavailable-listings', 
			'User Data' => 'user-data'
		];
		
		foreach($rest_urls as $title => $url){		
			$url_data .= $title.': '. home_url('/wp-json/' . FNEHD_PLUGIN_NAME . '/v1/'.$url).', '. PHP_EOL/*next line*/;
		}
        wp_send_json_success([
            'message' => __('URLs generated successfully.', 'fnehousing'),
            'url'  => $url_data,
        ]);
    }
	

   /**
	 * Validate the API key for REST API requests.
	 *
	 * @param \WP_REST_Request $request The REST API request object.
	 * @return bool|\WP_Error True if valid, or WP_Error otherwise.
	 */
	public function validateApiKey(\WP_REST_Request $request) {
		
		// Retrieve API key from the request headers
		$api_key = $request->get_header('X-API-Key');

		if (empty($api_key) || $api_key !== FNEHD_REST_API_KEY) {
			return new \WP_Error('invalid_api_key', __('Invalid API key.', 'fnehousing'), ['status' => 403]);
		}

		return true;
	}

	


}
