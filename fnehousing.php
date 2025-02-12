<?php

/*
 * Plugin Name: FNE Housing
 * Plugin URI: https://fnehousing.lechtech.net
 * Description: FNE Housing Dashboard Custom Plugin.
 * Author: Ngunyi yannick
 * Author URI: https://www.lechtech.net
 * Text Domain: fnehousing
 * Domain Path: /languages
 * Version: 1.0.0
 * Requires at least: 5.6
 * Requires PHP: 7.4
 */

defined('ABSPATH') || exit; 


//Include Composer Autoload
if(file_exists(dirname( __FILE__ ).'/vendor/autoload.php')){
	require_once dirname( __FILE__ ).'/vendor/autoload.php';
}

	
//Define General plugin constants
defined('FNEHD_TEXTDOMAIN') || define('FNEHD_TEXTDOMAIN', 'fnehousing');
defined('FNEHD_PLUGIN_NAME') || define('FNEHD_PLUGIN_NAME', 'fnehousing');
defined('FNEHD_VERSION') || define('FNEHD_VERSION', '1.0.0');
defined('FNEHD_PLUGIN_URL') || define('FNEHD_PLUGIN_URL', plugin_dir_url(__FILE__));
defined('FNEHD_PLUGIN_PATH') || define('FNEHD_PLUGIN_PATH', plugin_dir_path(__FILE__));
defined('FNEHD_SHELTER_JSON_DIR') || define('FNEHD_SHELTER_JSON_DIR', plugin_dir_path(__FILE__) . 'shelter-data/');
defined('FNEHD_PLUGIN_SLUG_PATH') || define('FNEHD_PLUGIN_SLUG_PATH', plugin_basename(__FILE__));
 


// Register Activation & Deactivation Hooks
if (class_exists('Fnehousing\\base\\Activator')) {
    register_activation_hook(__FILE__, ['Fnehousing\\Base\\Activator', 'activate']);
} 

if (class_exists('Fnehousing\\base\\Deactivator')) {
    register_deactivation_hook(__FILE__, ['Fnehousing\\Base\\Deactivator', 'deactivate']);
} 

//Register all Services & Start the plugin 
if(class_exists('Fnehousing\\FnehdInit')){
  Fnehousing\FnehdInit::registerServices();
}


