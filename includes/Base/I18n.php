<?php

/**
 * Define the internationalization functionality
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 * @since      1.0.0
 * @package    Fnehousing
 */
	
	namespace Fnehousing\Base;
	
	defined('ABSPATH') || exit;
	
	
    class I18n {


        public function register() {
           //Register hooks 
	       add_action( 'plugins_loaded',  array( $this, 'loadTextDomain') );
	    }
		

        //Load the plugin text domain for translation.
	    public function loadTextDomain() {
		    load_plugin_textdomain('fnehousing', false, plugin_basename( dirname( __FILE__ ) ) . '/languages/');
	    }
		
	
	}    




