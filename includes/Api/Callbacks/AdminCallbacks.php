<?php

/**
 * Admin Callback class_alias
 * Defines all admin callbacks  
 * @since      1.0.0
 * @package    Fnehousing
 */
	
	namespace Fnehousing\Api\callbacks;
	
	defined('ABSPATH') || exit;
 
 
    class AdminCallbacks {
	
	    //General Admin Template 
	    public function adminGenTemplate() {
           return require_once ( FNEHD_PLUGIN_PATH."templates/admin/admin-template.php" );
	    }
		
		 //Settings Panel Template 
	    public function settingsTemplate() {
			if(FNEHD_INTERACTION_MODE == "modal" && !wp_is_mobile()){//redirect to dashboard if setting panel mode is modal
			    echo"<script>window.location = 'admin.php?page=fnehousing-dashboard'</script>";
			} else {
		        return require_once ( FNEHD_PLUGIN_PATH."templates/admin/admin-template.php" );
			}
	    } 

   
   
    }
	
