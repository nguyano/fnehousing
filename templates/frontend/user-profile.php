<?php

/**
 * Front User Profile
 * This template renders the profile page for logged-in users, 
 * view relevant shelters & managing various user-related views based on the URL endpoint.
 *
 * @since      1.0.0
 * @package    Fnehousing
 */

use Fnehousing\Database\UsersDBManager;

defined('ABSPATH') || exit; // Exit if accessed directly

// Check if the URL contains the "endpoint" parameter.
$fnehd_endpoint = isset($_GET['endpoint']);

// Initialize the UsersDBManager class for database operations.
$user = new UsersDBManager();

// Get the current logged-in user's ID and username.
$user_id = get_current_user_id();
$username = get_user_by('ID', $user_id)->user_login;

// Retrieve user data and counts from the database.
$user_data = $user->getUserById($user_id);

?>

<div class="pb-5">

    <?php
    include_once FNEHD_PLUGIN_PATH . 'templates/frontend/user-routes.php';
    if (FNEHD_PLUGIN_INTERACTION_MODE === "modal") {
        include_once FNEHD_PLUGIN_PATH . "templates/frontend/front-modals.php";
    } else {
	    include_once FNEHD_PLUGIN_PATH . 'templates/frontend/user-dialogs.php';// Render collapsible dialogs
    }
   ?>

    <div>
		<?php
		/**
		 * Load the appropriate view based on the "endpoint" URL parameter.
		 * If no endpoint is specified, load the default user dashboard.
		 */
		if ($fnehd_endpoint) {
			$action = sanitize_text_field($_GET['endpoint']); // Sanitize endpoint parameter.

			// Determine the action and include the corresponding template.
			switch ($action) {
				
				case 'view_shelter':
					include_once FNEHD_PLUGIN_PATH . 'templates/frontend/views/view-shelter.php';
					break;

				case 'user_profile':
					include_once FNEHD_PLUGIN_PATH . 'templates/frontend/views/edit-profile.php';
					break;
				case 'map_view':
					include_once FNEHD_PLUGIN_PATH . 'templates/frontend/views/map-view.php';
					break;				
				default:
					include_once FNEHD_PLUGIN_PATH . 'templates/frontend/views/shelters.php';
			}
		} else {
			// Load the default dashboard view if no endpoint is specified.
			include_once FNEHD_PLUGIN_PATH . 'templates/frontend/views/shelters.php';
		}
		?>
    </div>
</div>
