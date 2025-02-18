<?php 
/**
 * Custom REST API Action class for the plugin
 * Defines REST API Actions
 *
 * @since      1.0.0
 * @package    Fnehousing
 */

namespace Fnehousing\Api\RestApi;

use Fnehousing\Api\RestApi\RestApiEndpoints;

defined('ABSPATH') || exit;

class RestApiActions extends RestApiEndpoints{
	
	private $endpoints = ['allShelterListingEndpoint', 'availableShelterListingEndpoint', 'unavailableShelterListingEndpoint', 'searchShelterListingEndpoint'];

    /**
     * Register hooks and REST API routes
     */
    public function register() {
        // Check if REST API is enabled
        if (defined('FNEHD_ENABLE_REST_API') && FNEHD_ENABLE_REST_API) {
            // Register REST API routes
            add_action('rest_api_init', [$this, 'registerRoutes']);
        }
		
		foreach($this->endpoints as $endpoint){		
			add_action('rest_api_init', [$this, $endpoint]);
		}

        // Register AJAX actions for API key generation if API Key is enabled
        if (defined('FNEHD_ENABLE_REST_API_KEY') && FNEHD_ENABLE_REST_API_KEY) {
            add_action('wp_ajax_generate_fnehd_rest_api_key', [$this, 'generateRestApiKey']);
            add_action('wp_ajax_generate_fnehd_rest_api_enpoint_url', [$this, 'generateRestApiUrl']);
        }
    }
	


}
