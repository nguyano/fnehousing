<?php
/**
 * Logs & Notification functions
 * Defines & add Transaction Logs & Notifications
 
 * @Since  1.0.0.
 * @package  Fnehousing
 */

// Common function to process logs and notifications
function fnehd_process_log_and_notify($log_data, $notification_data) {
    foreach ($log_data as $log) {
        do_action('fnehd_log_transaction', $log);
    }
    foreach ($notification_data as $notification) {
        do_action('fnehd_notify', $notification);
    }
}

// Common function to process notifications
function fnehd_process_notify($notification_data) {
    foreach ($notification_data as $notification) {
        do_action('fnehd_notify', $notification);
    }
}

// New Shelter
function fnehd_log_notify_new_shelter($ref_id, $shelter_id) {
  $username = fnehd_is_front_user()? wp_get_current_user()->user_login : 'Amin';
  $user_id = fnehd_is_front_user()? get_current_user_id() : 0;
  $log = [
        "admin" => [
            "ref_id" => $ref_id,
            "user_id" => $user_id,
            "subject" => __("New Shelter", "fnehousing"),
            "details" => __("New Shelter added by", "fnehousing") . ' ' . $username
        ]
	];	

    $notification = [
        "admin" => [
            "subject_id" => $subject_id,
            "user_id" => $user_id,
            "subject" => __("New Shelter Created, ID: ", "fnehousing") . $shelter_id,
            "message" => __("New Shelter with Ref#", "fnehousing") . " <strong>" . $ref_id. "</strong> " . __("was Added by", "fnehousing") . " <strong>" . $username . "</strong>",
            "status" => 0
        ]
    ];

    fnehd_process_log_and_notify($log, $notification);
}


//New User
function  fnehd_notify_new_user($subject_id, $username) {  
    $user_id = fnehd_is_front_user()? get_current_user_id() : 0;
	$notification = [
	   "admin" => [
				"subject_id" => $subject_id, 
				"user_id" => $user_id, 
				"subject" => __("New User Signup, ID: ", "fnehousing").$subject_id,
				"message" => __("New User with username", "fnehousing")." <strong>".$username."</strong> ".__("created an account.", "fnehousing"),  
				"status" => 0
			]
	];
	fnehd_process_notify($notification);
}    		