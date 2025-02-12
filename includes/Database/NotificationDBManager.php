<?php
 /**
 * The Admin Notification Database Interaction class of the plugin.
 * Defines all DB interraction for Admin Notification Actions.
 * @since      1.0.0
 * @package    Fnehousing
 */
    namespace Fnehousing\Database; 
	
	defined('ABSPATH') || exit;
	  
	class NotificationDBManager {
		 
		public $notificationsTable;
		  
        public function __construct() {
          global $wpdb;
          $this->notificationsTable = $wpdb->prefix."fnehousing_notifications";		
        }
  
	  
        //Fetch notiftcations from notiftcations table
        public function getNotifications(){
		  global $wpdb; 
		   if(fnehd_is_front_user()){
			   $sql= "SELECT * FROM $this->notificationsTable WHERE user_id = %d ORDER BY notification_id DESC LIMIT 0, 5";
			   $data = $wpdb->get_results($wpdb->prepare($sql, get_current_user_id()), ARRAY_A);
		   } else {
			   $sql  = "SELECT * FROM $this->notificationsTable WHERE user_id = %d ORDER BY notification_id DESC LIMIT 0, 5";
			   $data = $wpdb->get_results($wpdb->prepare($sql, 0), ARRAY_A);
		   }
           return $data;
        }
	  

	    //Count all Notifications
        public function totalNotyCount() {
			global $wpdb;
            if(fnehd_is_front_user()){		  
			    $sql = "SELECT COUNT(*) FROM $this->notificationsTable WHERE user_id = %d";
			    $rowCount = $wpdb->get_var($wpdb->prepare($sql, get_current_user_id()));
		    } else {
			    $sql = "SELECT COUNT(*) FROM $this->notificationsTable WHERE user_id = %d";
			    $rowCount = $wpdb->get_var($wpdb->prepare($sql, 0));
		    }
            return $rowCount;
        }
		
        //Count unseen Notifications
        public function unseenNotyCount(){
		    global $wpdb; 
			if(fnehd_is_front_user()){	
				$sql = "SELECT COUNT(*) FROM $this->notificationsTable WHERE user_id = %d AND status = %d";
				$rowCount = $wpdb->get_var($wpdb->prepare($sql, get_current_user_id(), 0));
			} else {
				$sql = "SELECT COUNT(*) FROM $this->notificationsTable WHERE user_id = %d AND status = %d";
				$rowCount = $wpdb->get_var($wpdb->prepare($sql, 0, 0));
			}	
            return $rowCount;
        }		
		
		
        //Update Notification status
        public function updateNotyStatus() {
			global $wpdb; 	 
			$data = array('status' => 1);
			$where = array('status' => 0);
			$wpdb->update($this->notificationsTable, $data, $where);
		  
	    }
	   
	   
		//Add Notification
		public function notify(array $data){
            global $wpdb; 
            $wpdb->insert($this->notificationsTable, $data);
		}

	   
    }
