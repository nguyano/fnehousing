<?php
/**
 * The Users manager class of the plugin.
 * Defines all Users Actions.

 * @since      1.0.0
 * @package    Fnehousing
 */
	
namespace Fnehousing; 

use Fnehousing\Database\UsersDBManager; 

defined('ABSPATH') || exit;
	
class UsersActions extends UsersDBManager {
		
		
	  public function register() {
        $ajax_actions = [
            'fnehd_users' => 'actionDisplayUsers',
            'fnehd_users_tbl' => 'actionReloadUsers',
            'fnehd_user_profile' => 'actionDisplayUserProfile',
            'fnehd_insert_user' => 'actionInsertUser',
            'fnehd_update_user' => 'actionUpdateUser',
            'fnehd_user_data' => 'actionGetUserById',
            'fnehd_del_user' => 'actionDeleteUser',
            'fnehd_del_users' => 'actionDeleteUsers',
            'fnehd_verify_user' => 'actionVerifyUser',
            'fnehd_export_user_excel' => 'exportUsersToExcel',
            'fnehd_reload_user_shelter_tbl' => 'actionReloadUserShelters',
            'fnehd_user_login' => 'userLogin',
            'fnehd_user_signup' => 'userSignup',
            'fnehd_user_logout' => 'userLogout',
            'fnehd_change_user_pass' => 'changeUserPass',
            'fnehd_reload_user_account' => 'reloadUserAccount',
			'fnehd_reset_password' => 'passwordReset',
			'fnehd_password_reset_link' => 'sendPasswordResetLink',
        ];

        foreach ($ajax_actions as $action => $method) {
            add_action("wp_ajax_$action", [$this, $method]);
            add_action("wp_ajax_nopriv_$action", [$this, $method]);
        }

        // Disable admin bar for non-admin users
        if (fnehd_is_front_user()) {
            add_filter('show_admin_bar', '__return_false');
        } else {
			add_action('wp_logout', [$this, 'customLogoutRedirect']);
		}
		add_action('init', [$this, 'redirectPublicTo404']);
    }
	
	
    //Prevent backend access
	public function redirectPublicTo404() {
		if ( is_admin() && !is_user_logged_in() && !( defined('DOING_AJAX') && DOING_AJAX ) ) {
			wp_redirect( home_url('/404') ); // Redirect to the 404 page URL
			exit;
		}
	}
	
		
	//Insert new user (Admin)	
	public function actionInsertUser() {
		
		fnehd_verify_permissions('manage_options');//Admin permission
		fnehd_validate_ajax_nonce('fnehd_user_nonce', 'nonce');
		
		//User Data
		$user_data = ['user_login', 'user_pass', 'first_name', 'last_name', 'user_email', 'user_url'];
		$user_data = fnehd_sanitize_form_data($user_data);
		$user_data['display_name'] = $user_data["first_name"].' '.$user_data["last_name"];
		$user_data['role'] = 'fnehousing_user';
		
		// Ensure email and username do not already exist.
		if ( email_exists( $user_data['user_email'] ) ) {
			wp_send_json_error([
				'message' => __( 'An account is already registered with this email address.', 'fnehousing' ),
			]);
		}

		if ( username_exists( $user_data['user_login'] ) ) {
			wp_send_json_error([
				'message' => __( 'An account is already registered with that username. Please choose another.', 'fnehousing' ),
			]);
		}
		
		//User Meta Data
		$meta_data = ['phone', 'address', 'country', 'affiliation', 'bio', 'user_image', 'status', 'front_role'];
		$meta_data = fnehd_sanitize_form_data($meta_data);
		
		$this->insertUser($user_data, $meta_data);
		
		wp_send_json_success(['message'=>__('User Added successfully', 'fnehousing')]);
  
	}


	/**
	 *Display Users
	 */
	public function actionDisplayUsers() {	
	    ob_start();
		include_once FNEHD_PLUGIN_PATH."templates/admin/users/users.php";		
		wp_send_json(['data' => ob_get_clean()]);
	}
	
	 /**
     * Reloads the current user's account form(Frontend)
     */
    public function reloadUserAccount() {
        $user_id = get_current_user_id();
        $user_data = $this->getUserById($user_id);

        ob_start();
        include FNEHD_PLUGIN_PATH . 'templates/forms/user-account-form.php';
        wp_send_json(['data' => ob_get_clean()]);
    }
	
	 /**
     * Reloads the user table (Admin)
     */
    public function actionReloadUsers() {
        ob_start();
        include_once FNEHD_PLUGIN_PATH . 'templates/admin/users/users-table.php';
        wp_send_json(['data' => ob_get_clean()]);
    }


	/**
	 *Reload User Shelter Table (Admin)
	 */
	public function actionReloadUserShelters() {
		if(isset($_POST['user_id'])){
			$user_id = $_POST['user_id'];	
			$username = get_user_by( 'ID', $user_id)->user_login;
			$data_count = $this->getUserSheltersCount($username);
			
			ob_start();
			include FNEHD_PLUGIN_PATH."templates/shelters/shelters-table.php";
			wp_send_json(['data' => ob_get_clean()]);
		}
	}
	
	
	//Display User Profile - Backend
	public function actionDisplayUserProfile() {
        ob_start();		
		include_once FNEHD_PLUGIN_PATH."templates/admin/users/user-profile.php";
		wp_send_json(['data' => ob_get_clean()]);
	}


	//Edit User Record (pull existing data into form) 
	public function actionGetUserById() {	
	  fnehd_verify_permissions('manage_options');
	  if(isset($_POST['UserId'])) {
		$user_id = $_POST['UserId'];
		$row = $this->getUserById($user_id);
		wp_send_json(['data' => $row]);
	  }
	}
	
	
	//Get User Name 
	public function actionGetUserName($id) {	
		$user = $this->getUserById($id);
		return $user["firstname"].' '.$user["lastname"]; 
	}


	//Update User Account
	public function actionUpdateUser() {	
		if(!fnehd_is_front_user()){ fnehd_verify_permissions('manage_options'); }
		fnehd_validate_ajax_nonce('fnehd_user_nonce', 'nonce');
		
		//User Data
		$user_data = ['ID', 'first_name','last_name', 'user_email', 'user_url'];
		
		$user_data = fnehd_sanitize_form_data($user_data);
		$user_id = $user_data["ID"];
		
		//User Meta Data
		$meta_data = ['phone', 'address', 'country', 'affiliation', 'bio', 'user_image', 'status'];
		$meta_data = fnehd_sanitize_form_data($meta_data);
		
		if(fnehd_is_front_user()){
			$user_id = get_current_user_id(); 
			$meta_data["user_image"] = fnehd_uploader('file');
			if(empty($meta_data["user_image"])) unset($meta_data["user_image"]);
		} 
		
		$user_data = [
			'user_url'     => $user_data["user_url"],
			'user_email'   => $user_data["user_email"],
			'first_name'   => $user_data["first_name"],
			'last_name'    => $user_data["last_name"],
			'display_name' => $user_data["first_name"].' '.$user_data["last_name"]
			
		];
	 
		$result = $this->updateUser($user_id, $user_data, $meta_data);
		
		if (is_wp_error($result)) {
			wp_send_json_error(['message' => $result->get_error_message()]);
		} else {
			wp_send_json_success(['message' => __('User Updated successfully', 'fnehousing')]);
		}
		
	}


	//Deletet Record  
	public function actionDeleteUser() {			
	   if (isset($_POST['UserID'])) {
		  $UserID = $_POST['UserID'];
		  $this->deleteUser($UserID);
		  wp_send_json_success(['message' => __('User deleted successfully', 'fnehousing')]);
	   }
	}   

	//Deletet Multiple Records  
	public function actionDeleteUsers() {		
	   if (isset($_POST['multUserid'])) {
		  $multUserid = $_POST['multUserid'];
		  $this->deleteUsers($multUserid);
		  wp_die();
	   }
	}   

	// Verify User Email
	public function actionVerifyUser() {		
	   if(isset($_POST['user_field'])) {
		 $field = $_POST['user_field'];
		 $this->verifyUser($field);
		 wp_die();
	   }
	}   
	
	
	/**
	 * Export Users to Excel
	 */
	public function exportUsersToExcel() {
		$data = $this->getAllUsers();
		$columns = [
			'ID.' => 'ID',
			'Username' => 'user_login',
			'Email' => 'user_email',
			'First Name' => 'first_name',
			'Last Name' => 'last_name',
			'Phone' => 'phone',
			'Company' => 'company',
			'Website' => 'website',
			'Country' => 'country',
			'Address' => 'address',
			'Bio' => 'bio',
			'User Image' => 'user_image',
			'Date' => 'user_registered'
		];
		fnehd_excel_table($data, $columns, 'users');
	}

	

	/**
	 *Redirect front users away from WP Admin
	 */
	public function redirectFromWPAdmin() {
		wp_safe_redirect(home_url(), 302);
		exit();
	}
	
	/**
	 *Redirect front users away from WP Admin
	 */
	public function redirectFromWPLogin() {
		global $pagenow;
		if($pagenow == "wp-login.php"){
			wp_safe_redirect(home_url(), 302);
			exit();
		}
	}	
	
	
	/**
	 * Handle user login via AJAX.
	 *
	 * This method processes the AJAX login request, validates input, authenticates the user,
	 * and returns a JSON response with the login status and optional redirect URL.
	 *
	 * @return void
	 */
	public function userlogin() {
		// Bail early if there is no nonce in the request.
		if ( ! isset( $_POST['nonce'] ) ) {
			wp_send_json_error(['message' => __( 'Nonce is required.', 'fnehousing' )]);
		}

		try {
			// Verify the nonce.
			fnehd_validate_ajax_nonce('fnehd_user_login_nonce', 'nonce');

			// Sanitize and validate input fields.
			$user_data = fnehd_sanitize_form_data( [ 'user_login', 'user_pass', 'remember', 'redirect' ] );
			$validate = fnehd_validate_form( $user_data );

			if ( $validate->has_errors() ) {
				wp_send_json_error(['message' => $validate->get_error_message()]);
			}

			// Prepare login credentials.
			$credentials = [
				'user_login'    => $user_data['user_login'],
				'user_password' => $user_data['user_pass'],
				'remember'      => 'yes' === $user_data['remember'],
			];

			// Check if the username is an email, and resolve the actual username if it exists.
			if ( is_email( $user_data['user_login'] ) ) {
				$user = get_user_by( 'user_email', $user_data['user_login'] );
				if ( ! $user ) {
					wp_send_json_error(['message' => __( 'No user found with the given email address.', 'fnehousing' )]);
				}
				$credentials['user_login'] = $user->user_login;
			} else {
				$user = get_user_by( 'login', $user_data['user_login'] );
				if ( ! $user ) {
					wp_send_json_error(['message' => __( 'No user found with the given username.', 'fnehousing' )]);
				}
			}

			// Ensure the user has the appropriate role or capability.
			if ( ! fnehd_is_front_user( $user->ID ) && ! user_can($user->ID, 'manage_options') ) {
				wp_send_json_error(['message' => __( 'You are not authorized to log in using this account.', 'fnehousing' )]);
			}

			// Attempt to sign the user in.
			$user = wp_signon( $credentials, is_ssl() );

			if ( is_wp_error( $user ) ) {
				if ( 'incorrect_password' === $user->get_error_code() ) {
					wp_send_json_error(['message' => __( 'Incorrect password. Please try again.', 'fnehousing' )]);
				}

				wp_send_json_error(['message' => $user->get_error_message()]);
			}

			// Generate the login redirect URL.
			if ( fnehd_is_front_user( $user->ID ) ) {
				$login_redirect = add_query_arg(
					[ 'endpoint' => 'dashboard' ],
					! empty( $user_data['redirect'] ) ? $user_data['redirect'] : home_url()
				); 
			} else {
				$login_redirect = 'admin.php?page=fnehousing-dashboard';
			} 

			// Send success response.
			wp_send_json_success([
				'message'  => __( 'Signed in successfully.', 'fnehousing' ),
				'redirect' => fnehd_redirect_url( $user, $login_redirect ),
			]);

		} catch ( Exception $e ) {
			wp_send_json_error(['message' => $e->getMessage()]);
		}
	}

	
	
	
	/**
	 * Handle user signup via AJAX.
	 *
	 * This method processes the AJAX signup request, validates input, registers a new user,
	 * and returns a JSON response with the signup status and optional redirect URL.
	 *
	 * @return void
	 */
	public function userSignup() {
		// Bail early if there is no nonce in the request.
		if ( ! isset( $_POST['nonce'] ) ) {
			wp_send_json_error([
				'status'  => 'error',
				'message' => __( 'Nonce is required.', 'fnehousing' ),
			]);
		}

		try {
			
			// Verify the nonce.
			fnehd_validate_ajax_nonce('fnehd_signup_nonce', 'nonce');

			// Sanitize and validate input fields.
			$user_data = ['user_login', 'first_name', 'last_name', 'user_email', 'user_pass', 'confirm_pass', 'user_url', 'redirect'];
			$user_data = fnehd_sanitize_form_data( $user_data);

			$user_data['display_name'] = trim( $user_data['first_name'] . ' ' . $user_data['last_name'] );
			$user_data['role']         = 'fnehousing_user';

			$validate = fnehd_validate_form( $user_data );

			if ( $validate->has_errors() ) {
				wp_send_json_error([
					'status'  => 'error',
					'message' => $validate->get_error_message(),
				]);
			}
			
			$redirect = $user_data["redirect"];//keep redirect url, will be removed prior to adding user

			// Ensure email and username do not already exist.
			if ( email_exists( $user_data['user_email'] ) ) {
				wp_send_json_error([
					'message' => __( 'An account is already registered with this email address.', 'fnehousing' ),
				]);
			}

			if ( username_exists( $user_data['user_login'] ) ) {
				wp_send_json_error([
					'message' => __( 'An account is already registered with that username. Please choose another.', 'fnehousing' ),
				]);
			}

			// Remove unnecessary fields before inserting the user.
			unset( $user_data['confirm_pass'], $user_data['redirect'] );

			// Insert the user and validate the result.
			$new_user_id = wp_insert_user( $user_data );

			if ( is_wp_error( $new_user_id ) ) {
				wp_send_json_error(['message' => $new_user_id->get_error_message()]);
			}

			// Prepare and update user meta data.
			$meta_data = fnehd_sanitize_form_data( ['phone', 'address', 'country', 'affiliation', 'bio', 'user_image'] );
			$meta_data['status'] = 0;
			foreach ( $meta_data as $key => $value ) {
				update_user_meta( $new_user_id, $key, $value );
			}

			// Send notifications and email alerts.
			fnehd_notify_new_user( $new_user_id, $user_data['user_login'] );
			fnehd_new_user_email( $user_data['user_email'] ); 

			// Automatically log in the new user.
			wp_signon(
				[
					'user_login'    => $user_data['user_login'],
					'user_password' => $user_data['user_pass'],
				],
				is_ssl()
			);

			// Generate the redirect URL.
			$login_redirect = ! empty( $redirect ) ? $redirect : home_url();
			$login_redirect = add_query_arg( [ 'endpoint' => 'dashboard' ], explode( '?', $login_redirect )[0] );

			// Send success response.
			wp_send_json_success([
				'message'  => __( 'User Account created successfully.', 'fnehousing' ),
				'redirect' => fnehd_redirect_url( get_userdata( $new_user_id ), $login_redirect ),
			]);

		} catch ( Exception $e ) {
			wp_send_json_error(['message' => $e->getMessage()]);
		}
	}
	


	/**
     * Logout the current user
     */
	public function UserLogout() {
		wp_logout();
		wp_send_json_success(['message' => 'Successfully signed out', 'redirect' => home_url()]);
	} 
	
	// Redirect to home page after admin logout
	public function customLogoutRedirect() {
		wp_redirect(home_url()); // Redirect to home page
		exit; 
	}
	
	
	/**
	 *Update User Passwrod.
	 */
	public function ChangeUserPass() {
		
		// Bail early if there is no nonce in the request.
		if ( ! isset( $_POST['nonce'] ) ) {
			wp_send_json_error([
				'status'  => 'error',
				'message' => __( 'Nonce is required.', 'fnehousing' ),
			]);
		}
		
		//Verify Nounce
		fnehd_validate_ajax_nonce('fnehd_user_pass_nonce', 'nonce');
		
		$form_data = ['old_pass', 'user_pass', 'confirm_pass'];
		$data = fnehd_sanitize_form_data($form_data);
		
		
		$user_id = get_current_user_id();
		
		$user = get_user_by( 'ID', get_current_user_id() );
		
		if ( !wp_check_password( $data['old_pass'], $user->data->user_pass, $user_id) ) {
			wp_send_json_error(['message' => __( 'Current password is wrong', 'fnehousing' )]);
		} 
	
		if ( $data['user_pass'] !== $data['confirm_pass'] ) {
			wp_send_json_error(['message' => __( 'Passwords do not match.', 'fnehousing' )]);
		} 
		
		$user_data = [ 
			'ID' => $user_id, 
			'user_pass'=> $data["user_pass"]
		];

		$user_update = wp_update_user($user_data);
		
		if ( is_wp_error( $user_update ) ) {
			wp_send_json_error(['message' => $wp_update->get_error_message()]);
		}
		
		wp_logout();
		
		wp_send_json_success(['message' => __('Password updated successfully', 'fnehousing')]);
			
		
	} 

    /**
	 *Generate password reset link
	 */
	function sendPasswordResetLink() {
		if ( isset($_POST['user_login']) ) {
			$user_login = sanitize_text_field($_POST['user_login']);
			$user = get_user_by('login', $user_login) ?: get_user_by('email', $user_login);

			if ( !$user ) {
				wp_send_json_error(['message' => __('No user found with that username or email.', 'fnehousing')]);
			} else {
				$reset_key = get_password_reset_key($user);
				$reset_url = add_query_arg(
					[
						'endpoint' => 'reset_password',
						'key'      => $reset_key,
						'login'    => rawurlencode($user->user_login)
					],
					home_url()
				);
				wp_mail($user->user_email, __('Password Reset', 'fnehousing'),  __('Click here to reset your password: ', 'fnehousing') . $reset_url);
				wp_send_json_success(['message' => __('Check your email for the password reset link', 'fnehousing')]);
			}
		}
	}


	/**
	 *Reset password
	 */
	public function passwordReset() {
		if ( isset($_POST['reset_password']) ) {
			if ( ! isset($_POST['reset_key'], $_POST['user_login'], $_POST['new_password'], $_POST['confirm_password']) ) {
				wp_send_json_error(['message' => __('Error: Missing required fields.', 'fnehousing')]);
			}

			$user = check_password_reset_key( sanitize_text_field($_POST['reset_key']), sanitize_text_field($_POST['user_login']) );
			if ( ! $user || is_wp_error($user) ) {
				wp_send_json_error(['message' => __('Invalid or expired reset link', 'fnehousing')]);
			}

			if ( $_POST['new_password'] !== $_POST['confirm_password'] ) {
				wp_send_json_error(['message' => __('Passwords do not match.', 'fnehousing')]);
			}

			reset_password($user, sanitize_text_field($_POST['new_password']));
			wp_send_json_success(['message' => __('<p>Password reset successfully! You can now <a href="' . home_url() . '">log in</a>.</p>', 'fnehousing')]);
		}
	}

}