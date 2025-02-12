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

defined('ABSPATH') || exit;

class RestApiEndpoints {


	private   $shelter;
	
	 public function __construct() {
        $this->shelter = new ShelterDBManager();
    }
     
    /**
     * Register REST API routes
     */
    public function registerRoutes() {
        register_rest_route(FNEHD_PLUGIN_NAME, '/fnehousing-endpoint-data', [
            'methods'  => \WP_REST_Server::READABLE,
            'callback' => [$this, 'getFnehousingEndpointData'],
            'permission_callback' => [$this, 'validateApiKey'], // Validate API key
        ]);
    }
	
	public function allShelterListingEndpoint() {
        register_rest_route('fnehousing/v1', '/listings', array(
            'methods' => 'GET',
            'callback' => array($this, 'getAllShelterListings'), // Specify class method
			'permission_callback' => '__return_true'
        ));
    }
	
	public function availableShelterListingEndpoint() {
        register_rest_route('fnehousing/v1', '/available-listings', array(
            'methods' => 'GET',
            'callback' => array($this, 'getAvailableShelterListings'), // Specify class method
			'permission_callback' => '__return_true'
        ));
    }
	
	public function unavailableShelterListingEndpoint() {
        register_rest_route('fnehousing/v1', '/unavailable-listings', array(
            'methods' => 'GET',
            'callback' => array($this, 'getUnavailableShelterListings'), // Specify class method
			'permission_callback' => '__return_true'
        ));
    }
	
	//get shelter listings based availability state
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
     * Retrieve plugin data for the REST API endpoint
     * 
     * @param \WP_REST_Request $request The REST API request object.
     * @return array|\WP_Error The response data or an error.
     */
    public function getFnehousingEndpointData(\WP_REST_Request $request) {
        $data = [
            'plugin_name'    => FNEHD_PLUGIN_NAME,
            'plugin_version' => FNEHD_VERSION,
            'plugin_cat'     => 'Housing & Real Estate',
            'downloads'      => 500,
        ];

        // Filter allowed data keys
        $allowed_keys = FNEHD_REST_API_DATA;
        $data = array_filter($data, function ($key) use ($allowed_keys) {
            return in_array($key, $allowed_keys, true);
        }, ARRAY_FILTER_USE_KEY);

        return rest_ensure_response($data);
    }
	

    /**
     * Generate a new REST API key for the current user
     */
    public function generateRestApiKey() {
        // Ensure API Key functionality is enabled
        if (!(defined('FNEHD_ENABLE_REST_API_KEY') && FNEHD_ENABLE_REST_API_KEY)) {
            wp_send_json_error(['message' => __('API Key functionality is disabled.', 'fnehousing')], 403);
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
        // Ensure API Key functionality is enabled
        if (!(defined('FNEHD_ENABLE_REST_API_KEY') && FNEHD_ENABLE_REST_API_KEY)) {
            wp_send_json_error(['message' => __('API Key functionality is disabled.', 'fnehousing')], 403);
        }

        // Ensure the request is valid
        if (!current_user_can('edit_user', get_current_user_id())) {
            wp_send_json_error(['message' => __('Unauthorized', 'fnehousing')], 403);
        }

        $url = home_url('/wp-json/' . FNEHD_PLUGIN_NAME . '/fnehousing-endpoint-data');
        wp_send_json_success([
            'message' => __('URL generated successfully.', 'fnehousing'),
            'url'     => $url,
        ]);
    }

    /**
     * Validate the API key for REST API requests
     * 
     * @param \WP_REST_Request $request The REST API request object.
     * @return bool|\WP_Error True if valid, or WP_Error otherwise.
     */
    public function validateApiKey(\WP_REST_Request $request) {
        // Ensure API Key functionality is enabled
        if (!(defined('FNEHD_ENABLE_REST_API_KEY') && FNEHD_ENABLE_REST_API_KEY)) {
            return new \WP_Error('api_key_disabled', __('API key functionality is disabled.', 'fnehousing'), ['status' => 403]);
        }

        $api_key = $request->get_header('X-API-Key');

        if (empty($api_key)) {
            return new \WP_Error('missing_api_key', __('API key is required.', 'fnehousing'), ['status' => 403]);
        }

        $user_query = get_users([
            'meta_key'   => 'api_key',
            'meta_value' => $api_key,
            'number'     => 1,
            'fields'     => 'ID',
        ]);

        if (empty($user_query)) {
            return new \WP_Error('invalid_api_key', __('Invalid API key.', 'fnehousing'), ['status' => 403]);
        }

        return true;
    }

	


}
