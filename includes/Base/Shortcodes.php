<?php

/**
 * The Shortcode manager class of the plugin.
 * DEfines all plugin Shortcodes.
 * @since      1.0.0
 * @package  Fnehousing
 */
 
    namespace Fnehousing\Base;
	
	defined('ABSPATH') || exit;
 
    class Shortcodes {
	
	    public function register() {
			add_shortcode( 'fnehd_account', array($this, 'fnehdAccount' ));
	    }
		
		
		public function fnehdAccount(){
		    ob_start();
		    include FNEHD_PLUGIN_PATH."templates/frontend/account.php";
		    return ob_get_clean();
        }
	
    }
	
	
	
	
