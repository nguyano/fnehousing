<?php

/**
 * The admin pages class of the plugin.
 * Adds all admin menu pages and subpages
 *
 * @since      1.0.0
 * @package    Fnehousing
 */
 
	namespace Fnehousing;
	
	defined('ABSPATH') || exit;
	 
	use Fnehousing\Api\SettingsApi;
	use Fnehousing\Api\Callbacks\AdminCallbacks;
	
	 
	class AdminMenu {
		
		public $settings;

		public $pages = array();

		public $subpages = array();
		
		public $callbacks;
		
		
		
		public function register() {
			
			$this->settings = new SettingsApi();

			$this->callbacks = new AdminCallbacks();
            
			$this->setPages(); 

			$this->setSubpages();
			
			$this->settings->addPages($this->pages)->withSubPage('Dashboard')->addSubPages($this->subpages)->register();
			
			add_action('admin_menu', array($this, 'removeFnehdExtraTopMenu'));
			
		} 
		

		public function capability() {
			
			$capability = "";
			
			include_once(ABSPATH . 'wp-includes/pluggable.php');
   
            $user = wp_get_current_user();
			$user_roles = $user->roles;
			
			$plugin_role = FNEHD_ACCESS_ROLE;
			
			if(in_array("administrator", $user_roles)){
				$capability = "administrator"; 
			} elseif (in_array($plugin_role, $user_roles)){
				$capability = $plugin_role; 
			}
		
			return $capability;
			
		}	
		
			
		public function setPages() {
			
			$this->pages = array(
				array(
					'page_title' => 'Fnehousing Plugin', 
					'menu_title' => 'FNE Housing', 
					'capability' => $this->capability(), 
					'menu_slug' => 'fnehousing-dashboard', 
					'callback' => array( $this->callbacks, 'adminGenTemplate' ), 
					'icon_url' => FNEHD_PLUGIN_URL. 'assets/img/fnehousing-icon.png', 
					'position' => 20
				),
				array(
					'page_title' => 'Fnehousing Shelters', 
					'menu_title' => 'Shelters', 
					'capability' => $this->capability(), 
					'menu_slug' => 'fnehousing-shelters', 
					'callback' => '', 
					'icon_url' => '', 
					'position' => 0
				)
			);
			
		}

		public function setSubpages(){
			
			$this->subpages = array(
				array(
					'parent_slug' => 'fnehousing-dashboard', 
					'page_title' => 'Fnehousing Shelters', 
					'menu_title' => 'Shelters', 
					'capability' => $this->capability(), 
					'menu_slug' => 'fnehousing-shelters', 
					'callback' => array( $this->callbacks, 'adminGenTemplate' ), 
				),
				array(
					'parent_slug' => 'fnehousing-dashboard', 
					'page_title' => 'Fnehousing Deposits', 
					'menu_title' => 'Deposits', 
					'capability' => $this->capability(), 
					'menu_slug' => 'fnehousing-deposits', 
					'callback' => array( $this->callbacks, 'adminGenTemplate' ), 
				),
				array(
					'parent_slug' => 'fnehousing-dashboard', 
					'page_title' => 'Fnehousing Withdrawals', 
					'menu_title' => 'Withdrawals', 
					'capability' => $this->capability(), 
					'menu_slug' => 'fnehousing-withdrawals', 
					'callback' => array( $this->callbacks, 'adminGenTemplate' ), 
				),
				array(
					'parent_slug' => 'fnehousing-dashboard', 
					'page_title' => 'Fnehousing Transaction Log', 
					'menu_title' => 'Transaction Log', 
					'capability' => $this->capability(), 
					'menu_slug' => 'fnehousing-transaction-log', 
					'callback' => array( $this->callbacks, 'adminGenTemplate' ), 
				),
				array(
					'parent_slug' => 'fnehousing-dashboard', 
					'page_title' => 'Fnehousing Users', 
					'menu_title' => 'Users', 
					'capability' => $this->capability(), 
					'menu_slug' => 'fnehousing-users', 
					'callback' => array( $this->callbacks, 'adminGenTemplate' ), 
				),
				array(
					'parent_slug' => 'fnehousing-dashboard', 
					'page_title' => 'Fnehousing Statistics', 
					'menu_title' => 'Statistics', 
					'capability' => $this->capability(), 
					'menu_slug' => 'fnehousing-stats', 
					'callback' => array( $this->callbacks, 'adminGenTemplate' ), 
				),
				array(
					'parent_slug' => 'fnehousing-dashboard', 
					'page_title' => 'Fnehousing Database Backups', 
					'menu_title' => 'DB Backups', 
					'capability' => $this->capability(), 
					'menu_slug' => 'fnehousing-db-backups', 
					'callback' => array( $this->callbacks, 'adminGenTemplate' ), 
				),
				array(
					'parent_slug' => 'fnehousing-dashboard', 
					'page_title' => 'Fnehousing Disputes', 
					'menu_title' => 'Disputes', 
					'capability' => $this->capability(), 
					'menu_slug' => 'fnehousing-disputes', 
					'callback' => array( $this->callbacks, 'adminGenTemplate' ), 
				),
				array(
					'parent_slug' => 'fnehousing-dashboard', 
					'page_title' => 'Fnehousing Settings', 
					'menu_title' => 'Settings', 
					'capability' => $this->capability(), 
					'menu_slug' => 'fnehousing-settings', 
					'callback' => array( $this->callbacks, 'adminGenTemplate' ), 
				),
				array(
					'parent_slug' => 'fnehousing-dashboard', 
					'page_title' => 'Fnehousing User Profile', 
					'menu_title' => '', 
					'capability' => $this->capability(), 
					'menu_slug' => 'fnehousing-user-profile', 
					'callback' => array( $this->callbacks, 'adminGenTemplate' ), 
				),
				array(
					'parent_slug' => 'fnehousing-dashboard', 
					'page_title' => 'Fnehousing View Shelter', 
					'menu_title' => '', 
					'capability' => $this->capability(), 
					'menu_slug' => 'fnehousing-view-shelter', 
					'callback' => array( $this->callbacks, 'adminGenTemplate' ), 
				),
				array(
					'parent_slug' => 'fnehousing-dashboard', 
					'page_title' => 'Fnehousing View Dispute', 
					'menu_title' => '', 
					'capability' => $this->capability(), 
					'menu_slug' => 'fnehousing-view-dispute', 
					'callback' => array( $this->callbacks, 'adminGenTemplate' ), 
				)
			);
		}
		
		
		//Hide Fnehousing Extra Top menus
		public  function removeFnehdExtraTopMenu() {
			remove_menu_page('fnehousing-shelters');
	    }
		
		
		
	}
		
		
		
		
