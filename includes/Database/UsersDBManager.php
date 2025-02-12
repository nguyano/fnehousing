<?php
/**
 * The Users Database Interaction class of the plugin.
 * Handles all database interactions for user-related actions.
 *
 * @since      1.0.0
 * @package    Fnehousing
 */

namespace Fnehousing\Database;

defined('ABSPATH') || exit;

class UsersDBManager
{
    protected $tables;
    private   $db;

    /**
     * Constructor to initialize table names with the WordPress table prefix.
     */
    public function __construct() {
		
		$this->db = new DBHelper();

        $this->tables = (object)[
            'activityLog'       => $this->db->tbl_pfx . 'fnehousing_activity_log',
            'notifications'     => $this->db->tbl_pfx . 'fnehousing_notifications',
        ];
    }
	
	
	/**
     * Count total number of fnehousing users.
     *
     * @return int Total user count.
     */
	
	public function getTotalUserCount() {
		$user_counts = count_users();

		// Check if the role exists in the counts
		if (isset($user_counts['avail_roles']['fnehousing_user'])) {
			return $user_counts['avail_roles']['fnehousing_user'];
		}

		// If no users with the role exist, return 0
		return 0;
	}
	
	
	/**
	 * Fetch all WordPress users with the specified role, including their meta data.
	 *
	 * Combines user data and meta data fields into a single array for each user.
	 * This method retrieves all users with the role 'fnehousing_user' and merges
	 * their user data with their meta data.
	 *
	 * @return array List of users with combined data and meta fields.
	 */
	public function getAllUsers(){
		$args = [
			'role'    => 'fnehousing_user',
			'orderby' => 'ID',      
			'order'   => 'ASC',  
		];

		$user_query = new \WP_User_Query($args);

		// Check if there are any results.
		if (!empty($user_query->get_results())) {
			// Get the results as an array of WP_User objects.
			$users = $user_query->get_results();
			$users_with_data_and_meta = [];

			foreach ($users as $user) {
				// Get user meta data as an associative array.
				$user_meta = get_user_meta($user->ID);

				// Flatten meta data (remove nested arrays).
				$flattened_meta = [];
				foreach ($user_meta as $key => $value) {
					$flattened_meta[$key] = maybe_unserialize($value[0]);
				}

				// Combine user data and meta data.
				$combined_data = array_merge(
					[
						'ID'              => $user->ID,
						'user_login'      => $user->user_login,
						'user_email'      => $user->user_email,
						'first_name'      => $user->first_name,
						'last_name'       => $user->last_name,
						'display_name'    => $user->display_name,
						'user_registered' => $user->user_registered,
						'role'            => 'fnehousing_user'
					],
					$flattened_meta
				);

				$users_with_data_and_meta[] = $combined_data;
			}

			return $users_with_data_and_meta;
		} else {
			return []; // Return an empty array if no users are found.
		}
	}



    /**
     * Insert a user.
     *
     * @param array $data Associative array of user data.
     * @param array $meta_data Associative array of user meta data.
     * @return int|false Inserted row ID or false on failure.
     */
    public function insertUser($user_data, $meta_data){
			
		$user_id = wp_insert_user($user_data);

		if (is_wp_error($user_id)) {
			wp_send_json_error(['message'=> $user_id->get_error_message()]);
		}	
		//User Meta Data
		foreach ( $meta_data as $key => $value ) {
			update_user_meta( $user_id, $key, $value );
		}
    }


    /**
     * Fetch user info by ID.
     *
     * @param int $id User ID.
     * @return array|null User data or null if not found.
     */
    public function getUserById($user_id){
    
		$user_id = absint($user_id); // Ensure the user ID is an integer.
		$user = get_userdata($user_id);

		if (!$user) {
			return null; // User does not exist.
		}

		// Retrieve user data.
		$user_data = [
			'ID'              => $user->ID,
			'user_login'      => $user->user_login,
			'user_email'      => $user->user_email,
			'first_name'      => $user->first_name,
			'last_name'       => $user->last_name,
			'user_url'        => $user->user_url,
			'user_registered' => $user->user_registered,
			'display_name'    => $user->display_name
			
		];

		// Retrieve all user meta.
		$user_meta = get_user_meta($user_id);

		// Flatten meta (to handle single and multiple values properly).
		foreach ($user_meta as $key => $value) {
			$user_meta[$key] = maybe_unserialize($value[0]);
		}

		// Combine user data and meta into one array.
		$combined_data = array_merge($user_data, $user_meta);

		return $combined_data;
	
    }


    /**
	 * Update a user's data and meta.
	 *
	 * @param int   $user_id The ID of the user to update.
	 * @param array $user_data Associative array of user data to update.
	 * @param array $meta_data Associative array of user meta fields to update.
	 * @return bool|WP_Error True on success, WP_Error on failure.
	 */
    public function updateUser($user_id, $user_data = [], $meta_data = []) {
		$user_id = absint($user_id); // Ensure the user ID is valid.

		if (!$user_id || !get_userdata($user_id)) {
			return;
		}

		// Update user data.
		$user_data['ID'] = $user_id; // ID is required for wp_update_user().
		$result = wp_update_user($user_data);

		if (is_wp_error($result)) {
			return $result; // Return WP_Error if user update fails.
		}

		// Update user meta data.
		foreach ($meta_data as $meta_key => $meta_value) {
			update_user_meta($user_id, $meta_key, $meta_value);
		}

		return true;
    }

    /**
     * Delete a user.
     *
     * @param int $userId User ID to delete.
     * @return void
     */
    public function deleteUser($user_id){

        $user_id = absint($user_id);
		$username = get_user_by( 'ID', $user_id )->user_login;
		
		wp_delete_user($user_id);
        $this->db->fnehdDelete($this->tables->shelters, ['payer' => $username]);
		$this->db->fnehdDelete($this->tables->shelters, ['earner' => $username]);
        $this->db->fnehdDelete($this->tables->notifications, ['subject_id' => $user_id]);
    }

    /**
     * Delete multiple users.
     *
     * @param string $userIds Comma-separated list of user IDs.
     * @return void
     */
    public function deleteUsers($user_ids)
    {
        $ids = array_map('absint', explode(',', $user_ids));

        foreach ($ids as $id) {
            $this->deleteUser($id);
        }
    }

    /**
     * Verify if user already.
     *
     * @param string $field Indentifier(user_login||user_email) to check.
     * @return string "taken" or "not_taken".
     */
    public function verifyUser($field){
		$user_id = username_exists($field);
		if(is_email($field)){
			$user_id = email_exists($field);
		} 

		if ($user_id) {
			echo 'taken';
		} else {
			echo 'not_taken';
		}
    }

	
	/**
	 * Retrieves the transaction log for the current logged-in user.
	 *
	 * @return array An array of transaction log records for the user.
	 */
	public function getUserActivityLog(){
		$sql = "SELECT * FROM {$this->tables->activityLog} WHERE user_id = %d ORDER BY log_id DESC";
		return $this->db->fnehdQuery($sql, [get_current_user_id()]);
	}

	
	/**
	 * Counts the total number of transaction log records for the current logged-in user.
	 *
	 * @return int The total count of transaction log records.
	 */
	public function getUserActivityLogCount(){
		$sql = "SELECT COUNT(*) FROM {$this->tables->activityLog} WHERE user_id = %d";
		return $this->db->fnehdQuery($sql, [get_current_user_id()], 'var');
	}



}
