<?php
 /**
 * The Stats Database Interaction class of the plugin.
 * Defines all DB interraction for Stats variables.
 * @since      1.0.0
 * @package    Fnehousing
 */
	defined('ABSPATH') || exit;
	
	class StatsDBManager {
		
		public $sheltersTable;
		public $shelterMetaTable;
		public $activityLogTable;
		public $dbbackupTable;
		public $notificationsTable;
		public $usersTable;
		
        public function __construct()
        {
            global $wpdb; 
			$this->sheltersTable          = $wpdb->prefix."fnehousing_shelters";
			$this->activityLogTable  = $wpdb->prefix."fnehousing_activity_log";
			$this->dbbackupTable         = $wpdb->prefix."fnehousing_dbbackups";
			$this->notificationsTable    = $wpdb->prefix."fnehousing_notifications";
		
        }
	  
		//Count shelters
		public function getTotalShelterCount(){
			 global $wpdb; 
			 $sql = "SELECT COUNT(*) FROM $this->sheltersTable";
			 $rowCount = $wpdb->get_var($sql);
			 return $rowCount;
		}
		  
		  
		//Count last updated shelters (within 24hrs)
		public function recentShelterCount(){
			 global $wpdb;  
			 $oneday = date('Y-m-d H:i:s', strtotime( "-1 day" ));  
			 $sql = "SELECT COUNT(*) FROM $this->sheltersTable WHERE creation_date > %d";
			 $rowCount = $wpdb->get_var($wpdb->prepare($sql, $oneday));
			 return $rowCount;
		}


		// Get top 10 Shelter according to bed capacity
		public function topShelterTopBeds(){
			 global $wpdb;	
			 $sql = "SELECT shelter_name, SUM(bed_capacity) AS total_beds 
					 FROM $this->sheltersTable 
					 GROUP BY shelter_name 
					 ORDER BY total_beds DESC 
					 LIMIT 10";
			 $data = $wpdb->get_results($sql, ARRAY_A);
			 return $data;
		}
		
		
		// Get top 10 Shelter in terms of referrals
		public function topShelterReferrals(){
			 global $wpdb;	
			 $sql = "SELECT shelter_name, SUM(referrals) AS referrals 
					 FROM $this->sheltersTable 
					 GROUP BY shelter_name 
					 ORDER BY referrals DESC 
					 LIMIT 10";
			 $data = $wpdb->get_results($sql, ARRAY_A);
			 return $data;
		}
		  
		  
		//Count Shelters per Month for a Specific Year
		public function monthlySheltersPerYr($month, $yr){
			 global $wpdb;		   
			 $sql = "SELECT COUNT(*) FROM $this->sheltersTable WHERE EXTRACT(MONTH FROM creation_date) = %d AND EXTRACT(YEAR FROM creation_date) = %d";
			 $rowCount =  $wpdb->get_var($wpdb->prepare($sql, $month, $yr));
			 return $rowCount;
		}
		  
		  
		  
		// Get total number of Users avaible
		public function getTotalUserCount() {
			$user_counts = count_users();

			// Check if the role exists in the counts
			if (isset($user_counts['avail_roles']['fnehousing_user'])) {
				return $user_counts['avail_roles']['fnehousing_user'];
			}

			// If no users with the role exist, return 0
			return 0;
		}
			
		
		// Get total amount in active shelter activity 
		public function totalAvailableShelters(){
			if($this->getTotalShelterCount() > 0){
				global $wpdb; 
				$sql = "SELECT COUNT(*) FROM $this->sheltersTable WHERE availability = 'Available'";
				return $wpdb->get_var($sql);
			} else {
				return 0;
			}
			 
		}
		
	  
	   //Count Database Backups
        public function dbBackupsCount(){
		    global $wpdb; 
            $sql = "SELECT COUNT(*) FROM $this->dbbackupTable";
		    $rowCount = $wpdb->get_var($sql);
            return $rowCount;
        }
	    
	   
	   
   }
