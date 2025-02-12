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
function fnehd_log_notify_new_shelter($ref_id, $payer, $earner, $amount, $bal, $user_id, $subject_id, $shelter_ref) {
    $log = [
        "admin" => [
            "ref_id" => $ref_id,
            "user_id" => 0,
            "subject" => __("New Shelter", "fnehousing"),
            "details" => $payer . ' ' . __("Created Shelter for", "fnehousing") . ' ' . $earner,
            "amount" => $amount,
            "balance" => $bal
        ],
        "user" => [
            "ref_id" => $ref_id,
            "user_id" => $user_id,
            "subject" => __("New Shelter", "fnehousing"),
            "details" => __("Shelter created for", "fnehousing") . ' ' . $earner,
            "amount" => $amount,
            "balance" => $bal
        ]
    ];

    $notification = [
        "admin" => [
            "subject_id" => $subject_id,
            "user_id" => 0,
            "subject" => __("New Shelter Created, ID: ", "fnehousing") . $subject_id,
            "message" => __("New Shelter with Ref#", "fnehousing") . " <strong>" . $shelter_ref . "</strong> " . __("was Added by", "fnehousing") . " <strong>" . $payer . "</strong>",
            "status" => 0
        ],
        "user" => [
            "subject_id" => $subject_id,
            "user_id" => $user_id,
            "subject" => __("New Shelter Created, ID: ", "fnehousing") . $subject_id,
            "message" => __("New Shelter with Ref#", "fnehousing") . " <strong>" . $shelter_ref . "</strong> " . __("was Added by", "fnehousing") . " <strong>" . $payer . "</strong>",
            "status" => 0
        ]
    ];

    fnehd_process_log_and_notify($log, $notification);
}

// New Shelter Milestone
function fnehd_log_notify_new_milestone($ref_id, $payer, $earner, $amount, $bal, $user_id, $subject_id, $shelter_ref) {
    $log = [
        "admin" => [
            "ref_id" => $ref_id,
            "user_id" => 0,
            "subject" => __("New Milestone", "fnehousing"),
            "details" => $payer . ' ' . __("Created Shelter Amount for", "fnehousing") . ' ' . $earner,
            "amount" => $amount,
            "balance" => $bal
        ],
        "user" => [
            "ref_id" => $ref_id,
            "user_id" => $user_id,
            "subject" => __("New Milestone", "fnehousing"),
            "details" => __("Shelter Amount created for", "fnehousing") . ' ' . $earner,
            "amount" => $amount,
            "balance" => $bal
        ]
    ];

    $notification = [
        "admin" => [
            "subject_id" => $subject_id,
            "user_id" => 0,
            "subject" => __("New Shelter Milestone Created, ID: ", "fnehousing") . $subject_id,
            "message" => __("New Milestone for Shelter with Ref# ", "fnehousing") . " <strong>" . $shelter_ref . "</strong>" . __("was Added by", "fnehousing") . " <strong>" . $payer . "</strong>",
            "status" => 0
        ],
        "user" => [
            "subject_id" => $subject_id,
            "user_id" => $user_id,
            "subject" => __("New Shelter Milestone Created, ID: ", "fnehousing") . $subject_id,
            "message" => __("New Milestone for Shelter with Ref#", "fnehousing") . " <strong>" . $shelter_ref . "</strong>" . __("was Added by", "fnehousing") . " <strong>Admin</strong>",
            "status" => 0
        ]
    ];

    fnehd_process_log_and_notify($log, $notification);
}

// Reject Payment
function fnehd_log_notify_pay_rejected($ref_id, $earner, $shelter_title, $payer, $amount, $bal, $user_id, $subject_id) {
    $log = [
        "admin" => [
            "ref_id" => $ref_id,
            "user_id" => 0,
            "subject" => __("Payment Rejected", "fnehousing"),
            "details" => $earner . ' ' . __("Rejected Payment for", "fnehousing") . ' ' . $shelter_title . ' ' . __("made by", "fnehousing") . ' ' . $payer,
            "amount" => $amount,
            "balance" => $bal
        ],
        "user" => [
            "ref_id" => $ref_id,
            "user_id" => $user_id,
            "subject" => __("Payment Rejected", "fnehousing"),
            "details" => $shelter_title . ' ' . __("Rejected By", "fnehousing") . ' ' . $earner,
            "amount" => $amount,
            "balance" => $bal
        ]
    ];

    $notification = [
        "admin" => [
            "subject_id" => $subject_id,
            "user_id" => 0,
            "subject" => __("Shelter Payment Rejected, ID: ", "fnehousing") . $subject_id,
            "message" => "<strong>" . $earner . "</strong> " . __("Rejected Payment for", "fnehousing") . " " . $shelter_title . " " . __("made by", "fnehousing") . " <strong>" . $payer . "</strong>",
            "status" => 0
        ],
        "user" => [
            "subject_id" => $subject_id,
            "user_id" => $user_id,
            "subject" => __("Shelter Payment Rejected, ID: ", "fnehousing") . $subject_id,
            "message" => $shelter_title . " " . __("Rejected by", "fnehousing") . " <strong> " . $earner . "</strong>",
            "status" => 0
        ]
    ];

    fnehd_process_log_and_notify($log, $notification);
}

// Release Payment
function fnehd_log_notify_pay_released($ref_id, $payer, $shelter_title, $earner, $amount, $bal, $user_id, $subject_id) {
    $log = [
        "admin" => [
            "ref_id" => $ref_id,
            "user_id" => 0,
            "subject" => __("Payment Released", "fnehousing"),
            "details" => $payer . ' ' . __("Released Payment for", "fnehousing") . ' ' . $shelter_title . ' ' . __("to", "fnehousing") . ' ' . $earner,
            "amount" => $amount,
            "balance" => $bal
        ],
        "user" => [
            "ref_id" => $ref_id,
            "user_id" => $user_id,
            "subject" => __("Payment Released", "fnehousing"),
            "details" => __("Shelter Amount released from", "fnehousing") . ' ' . $payer,
            "amount" => $amount,
            "balance" => $bal
        ]
    ];

    $notification = [
        "admin" => [
            "subject_id" => $subject_id,
            "user_id" => 0,
            "subject" => __("Shelter Payment Released, ID: ", "fnehousing") . $subject_id,
            "message" => "<strong>" . $payer . "</strong> " . __(" Released Payment for ", "fnehousing") . " " . $shelter_title . " " . __("to", "fnehousing") . " <strong>" . $earner . "</strong>",
            "status" => 0
        ],
        "user" => [
            "subject_id" => $subject_id,
            "user_id" => $user_id,
            "subject" => __("Shelter Payment Released, ID: ", "fnehousing") . $subject_id,
            "message" => __("Shelter Amount released from ", "fnehousing") . " <strong> " . $payer . "</strong>",
            "status" => 0
        ]
    ];

    fnehd_process_log_and_notify($log, $notification);
}

// User Deposit
function fnehd_log_notify_user_deposit($ref_id, $username, $amount, $bal, $user_id, $subject_id) {
    $log = [
        "admin" => [
            "ref_id" => $ref_id,
            "user_id" => 0,
            "subject" => __("User Deposit", "fnehousing"),
            "details" => $username . ' ' . __("Made a deposit of", "fnehousing") . ' ' . FNEHD_CURRENCY . $amount,
            "amount" => $amount,
            "balance" => $bal
        ],
        "user" => [
            "ref_id" => $ref_id,
            "user_id" => $user_id,
            "subject" => __("User Deposit", "fnehousing"),
            "details" => __("You Deposited", "fnehousing") . ' ' . FNEHD_CURRENCY . $amount,
            "amount" => $amount,
            "balance" => $bal
        ]
    ];

    $notification = [
        "admin" => [
            "subject_id" => $subject_id,
            "user_id" => 0,
            "subject" => __("New User Deposit, ID: ", "fnehousing") . $subject_id,
            "message" => "<strong>" . $username . "</strong> " . __(" Made a deposit of ", "fnehousing") . FNEHD_CURRENCY . $amount,
            "status" => 0
        ],
        "user" => [
            "subject_id" => $subject_id,
            "user_id" => $user_id,
            "subject" => __("Deposit Successful, ID: ", "fnehousing") . $subject_id,
            "message" => __("You have successfully deposited ", "fnehousing") . FNEHD_CURRENCY . $amount,
            "status" => 0
        ]
    ];

    fnehd_process_log_and_notify($log, $notification);
}

// User Withdrawal
function fnehd_log_notify_user_withdrawal($ref_id, $username, $amount, $bal, $user_id, $subject_id) {
    $log = [
        "admin" => [
            "ref_id" => $ref_id,
            "user_id" => 0,
            "subject" => __("User Withdrawal", "fnehousing"),
            "details" => $username . ' ' . __("Requested a withdrawal of", "fnehousing") . ' ' . FNEHD_CURRENCY . $amount,
            "amount" => $amount,
            "balance" => $bal
        ],
        "user" => [
            "ref_id" => $ref_id,
            "user_id" => $user_id,
            "subject" => __("Withdrawal Request", "fnehousing"),
            "details" => __("You Requested a withdrawal of", "fnehousing") . ' ' . FNEHD_CURRENCY . $amount,
            "amount" => $amount,
            "balance" => $bal
        ]
    ];

    $notification = [
        "admin" => [
            "subject_id" => $subject_id,
            "user_id" => 0,
            "subject" => __("New Withdrawal Request, ID: ", "fnehousing") . $subject_id,
            "message" => "<strong>" . $username . "</strong> " . __(" Requested a withdrawal of ", "fnehousing") . FNEHD_CURRENCY . $amount,
            "status" => 0
        ],
        "user" => [
            "subject_id" => $subject_id,
            "user_id" => $user_id,
            "subject" => __("Withdrawal Request Received, ID: ", "fnehousing") . $subject_id,
            "message" => __("Your withdrawal request of ", "fnehousing") . FNEHD_CURRENCY . $amount . __(" has been received.", "fnehousing"),
            "status" => 0
        ]
    ];

    fnehd_process_log_and_notify($log, $notification);
}


//New Dispute
function  fnehd_notify_new_dispute($dispute_id, $ref_id, $complainant, $accused,  $accused_id, $complainant_id) {  

    if(fnehd_is_front_user()){
		$notification = [
		   "admin" => [
				"subject_id" => $dispute_id, 
				"user_id" => 0, 
				"subject" => __("New Dispute Opened, ID: ", "fnehousing").' #'.$ref_id,
				"message" => __("New Dispute Opened by", "fnehousing")." <strong>".$complainant."</strong>".__("against", "fnehousing")."  <strong>".$accused."</strong>",  
				"status" => 0
			],
			"accused" => [
				"subject_id" => $dispute_id, 
				"user_id" => $accused_id, 
				"subject" => __("New Dispute Opened, ID: ", "fnehousing").' #'.$ref_id,
				"message" => __("New Dispute Opened against you by", "fnehousing")." <strong>".$complainant."</strong>",  
				"status" => 0
			]			
		];
	} else {
		$notification = [
			"complainant" => [
				"subject_id" => $dispute_id, 
				"user_id" => $complainant_id, 
				"subject" => __("New Dispute Opened, ID: ", "fnehousing").' #'.$ref_id,
				"message" => __("New Dispute Opened by admin, for you against", "fnehousing")." <strong>".$accused."</strong>",  
				"status" => 0
						],
			"accused" =>   [
				"subject_id" => $dispute_id, 
				"user_id" => $accused_id, 
				"subject" => __("New Dispute Opened, ID: ", "fnehousing").' #'.$ref_id,
				"message" => __("New Dispute Opened against you by admin, Complainant: ", "fnehousing")." <strong>".$complainant."</strong>",  
				"status" => 0
			]				
		];
	}
	fnehd_process_notify($notification);
}


//New User
function  fnehd_notify_new_user($subject_id, $username) {  
	$notification = [
	   "admin" => [
						"subject_id" => $subject_id, 
						"user_id" => 0, 
						"subject" => __("New User Signup, ID: ", "fnehousing").$subject_id,
						"message" => __("New User with username", "fnehousing")." <strong>".$username."</strong> ".__("created an account.", "fnehousing"),  
						"status" => 0
					]
	];
	fnehd_process_notify($notification);
}    		