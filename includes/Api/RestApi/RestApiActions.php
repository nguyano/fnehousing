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
	
	private $endpoints = ['pluginBasicDataEndpoint', 'allShelterListingEndpoint', 'availableShelterListingEndpoint', 'unavailableShelterListingEndpoint', 'searchShelterListingEndpoint'];

    /**
     * Register hooks and REST API routes
     */
    public function register() {
      
        // Always allow internal requests, else REST API must be ON for external requests
		if ($this->isInternalRequest() || (defined('FNEHD_ENABLE_REST_API') && FNEHD_ENABLE_REST_API)) {
			// Register REST API routes
			foreach ($this->endpoints as $endpoint) {        
				add_action('rest_api_init', [$this, $endpoint]);
			}
		}
		
        // Register AJAX actions for REST API key & URLs generation
        add_action('wp_ajax_generate_fnehd_rest_api_key', [$this, 'generateRestApiKey']);
        add_action('wp_ajax_generate_fnehd_rest_api_endpoint_urls', [$this, 'generateRestApiUrl']);
    }
	


}
