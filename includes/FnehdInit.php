<?php

/**
* Plugin initialiation class. 
* Registers all admin and frontend hooks.
*
* @since    1.0.0
* @package  Fnehousing
*/

namespace Fnehousing;

defined('ABSPATH') || exit;

final class FnehdInit {

	/* 
	*Store all classes in an array
	*@return array list of all classes
	*since  1.0.0
	*/
	public static function getServices() {
	
		return[ 
				Database\DBMigration::class,
				Base\OptionsManager::class,
				Base\CronManager::class,
				Base\Shortcodes::class,
				Email\EmailManager::class,
				AdminScreenOptions::class,
				AdminPluginLinks::class,
				AdminMenu::class,
				AdminEnqueue::class,	
				UsersActions::class,
				SheltersActions::class,	
				Api\RestApi\RestApiActions::class,
				Api\DataTable\DataTableActions::class,
				NotificationActions::class,
				DBBackupActions::class,	
				PublicEnqueue::class,
				Base\I18n::class,				
			  
			];
 
	}



	/* 
	*Initialize the class
	*@param   $class             from the services array
	*@return  class instance     new instance of the class
	*/
	private static function instantiate($class){
	
		$service = new $class();
		return $service;
	}
	


	/* 
	*Loop through the classes, initialize, and 
	*call the register() *method if it exist
	*@return void
	*/
	public static function registerServices() {
	
		foreach (self::getServices() as $class) {
		
			$service = self::instantiate($class);
		
			if ( method_exists($service, 'register' ) ) {
			   
				$service->register(); 
				
			}
		}
		
		//Define Plugin Interaction Mode - intentionally delayed
		add_action('plugins_loaded', function() {
			$interactionMode = fnehd_is_front_user() 
				? FNEHD_PLUGIN_INTERACTION_MODE_FRONTEND 
				: FNEHD_PLUGIN_INTERACTION_MODE_ADMIN;

			if (!defined('FNEHD_INTERACTION_MODE')) {
				define('FNEHD_INTERACTION_MODE', $interactionMode);
			}
		});
	}
	

}
