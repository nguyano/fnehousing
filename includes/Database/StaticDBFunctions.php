<?php

/**
* Database Static Helper functions
* Plugin-wide Specific database helper functionalities
*
* @since      1.0.0
* @package    Fnehousing
*/

defined('ABSPATH') || exit;

//Get User Data
function fnehd_get_user_data($user_id) {
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


//Get Sinle User Data
function fnehd_single_user_meta($username, $meta_key) {
	$user = get_user_by('login', $username);
	if (!$user) { return; }
	return get_user_meta($user->ID, $meta_key, true)?? "";
}


//Get Shelter Data from id
function fnehd_get_shelter_data($id) {
	global $wpdb;
    $sql = "SELECT * FROM {$wpdb->prefix}fnehousing_shelters WHERE shelter_id = %d";
    return $wpdb->get_row($wpdb->prepare($sql, $id), ARRAY_A);
}



//Get Shelter Data from Ref_id
function fnehd_get_shelter_by_ref($ref_id) {
	global $wpdb;
    $sql = "SELECT * FROM {$wpdb->prefix}fnehousing_shelters WHERE ref_id = %d";
    return $wpdb->get_row($wpdb->prepare($sql, $ref_id), ARRAY_A);
}

//Check if Shelter Exist
function fnehd_shelter_exist($col, $val) {
	global $wpdb;
    $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}fnehousing_shelters WHERE {$col} = %d";
    $rowCount = $wpdb->get_var($wpdb->prepare($sql, $val));
    if($rowCount == 1){
	   return true;
    } else {
	  return false; 
    }
}


//Get Shelters count
function fnehd_shelter_count() {
	global $wpdb;
    $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}fnehousing_shelters";
    return $wpdb->get_var($sql);
}


//Availability Shelter Count
function fnehd_shelter_availability_count($availability) {
	global $wpdb;
    $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}fnehousing_shelters WHERE availability = %s";
    return $wpdb->get_var($wpdb->prepare($sql, $availability));
  
}


//Get current users list
function fnehd_users(){
	$users = get_users(['role' => 'fnehousing_user']);
	$data = [];
	foreach ( $users as $user ) {
		$data[$user->user_login] = $user->user_login;
	}
	return $data;
}

//Get current wordpress pages list
function fnehd_wp_pages(){
	 $pages = get_pages(); 
	 $data = [];
	foreach ( $pages as $page ) {
		$data[$page->ID] = $page->post_title;
	}
	return $data;
}




