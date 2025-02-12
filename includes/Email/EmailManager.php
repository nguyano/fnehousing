<?php

/**
 * Mailer class
 * Defines and hook email actions of the plugin.
 *
 * @since      1.0.0
 * @package    Fnehousing
 */
	
	namespace Fnehousing\Email;
	
	defined('ABSPATH') || exit;
 
 
    class EmailManager {
 
 
 	    public function register() {
		
            //Register hooks 
	        if(FNEHD_SMTP_PROTOCOL){ add_action('phpmailer_init', array($this, 'phpMailerConfig' )); }
		
	    }
		
		//Configure PHPMailer custom SMTP 
        public function phpMailerConfig($mail){
          
          $mail->IsSMTP();
          $mail->SMTPAuth = true;
          $mail->Host = FNEHD_SMTP_HOST;
          $mail->Port = FNEHD_SMTP_PORT;
	      $mail->Username = FNEHD_SMTP_USER;
	      $mail->Password = FNEHD_SMTP_PASS;
	      $mail->SMTPSecure = 'tls';
	      $mail->From = FNEHD_COMPANY_EMAIL;
          $mail->FromName = get_bloginfo('name');
	  
        }
	
	
   }	 