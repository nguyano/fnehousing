<?php
/**
 * Email Notification Functions
 * Defines all notification emails for Fnehousing
 *
 * @since      1.0.0
 * @package    Fnehousing
 */

defined('ABSPATH') || exit;

/**
 * Generates a consistent email footer for all emails.
 *
 * @return string
 */
function fnehd_get_email_footer() {
    $year = date('Y');
    $site_name = get_bloginfo('name');
    $home_url = get_home_url();

    return sprintf(
        '<div class="footer" style="margin-top: 30px;">
            <div class="copyright" style="margin-top: 20px; padding: 5px; border-top: 1px solid #000;">
                <p>%s</p>
                <p>© %s | <a href="%s">%s</a></p>
            </div>
        </div>',
        esc_html__(
            'This message is intended solely for the use of the individual or organization to whom it is addressed. It may contain privileged or confidential information. If you have received this message in error, please notify the originator immediately. If you are not the intended recipient, you should not use, copy, alter, or disclose the contents of this message. All information or opinions expressed in this message and/or any attachments are those of the author and are not necessarily those of',
            'fnehousing'
        ) . ' ' . esc_html($site_name),
        esc_html($year),
        esc_url($home_url),
        esc_html($site_name)
    );
}

/**
 * Sends an email notification.
 *
 * @param string $to Recipient email address.
 * @param string $subject Email subject.
 * @param string $body Email body.
 * @param bool   $is_enabled Flag to determine if the email should be sent.
 */
function fnehd_send_email($to, $subject, $body, $is_enabled = true) {
    if ($is_enabled && !empty($to) && !empty($subject) && !empty($body)) {
        $headers = ['Content-Type: text/html; charset=UTF-8'];
        wp_mail(sanitize_email($to), esc_html($subject), $body, $headers);
    }
}

/**
 * Processes email notifications for different contexts.
 *
 * @param array $emails Array containing email configurations for admin and user.
 * @param array $trans Placeholder replacements.
 */
function fnehd_process_emails($emails, $trans) {
    foreach ($emails as $context => $email) {
        $subject = strtr($email['subject'], $trans);
        $body = strtr($email['body'] . fnehd_get_email_footer(), $trans);
        fnehd_send_email($email['to'], $subject, $body, $email['is_on']);
    }
}

/**
 * New Shelter Notification Email.
 */
function fnehd_new_shelter_email($ref_id, $status, $earner, $amount, $earner_email, $title, $details) {
    $trans = array(
        '{ref-id}'       => $ref_id,
        '{status}'       => $status,
        '{earner}'       => $earner,
        '{amount}'       => $amount,
        '{earner_email}' => $earner_email,
        '{title}'        => $title,
        '{details}'      => $details,
        '{current-year}' => date('Y'),
        '{site-title}'   => get_bloginfo('name'),
        '{company-address}' => FNEHD_COMPANY_ADDRESS,
    );

    $emails = array(
        "admin" => array(
            "to"      => FNEHD_COMPANY_EMAIL,
            "subject" => FNEHD_ADMIN_NEW_SHELTER_EMAIL_SUBJECT,
            "body"    => FNEHD_ADMIN_NEW_SHELTER_EMAIL_BODY,
            "is_on"   => FNEHD_ADMIN_NEW_SHELTER_EMAIL,
        ),
        "user" => array(
            "to"      => $earner_email,
            "subject" => FNEHD_USER_NEW_SHELTER_EMAIL_SUBJECT,
            "body"    => FNEHD_USER_NEW_SHELTER_EMAIL_BODY,
            "is_on"   => FNEHD_USER_NEW_SHELTER_EMAIL,
        ),
    );

    fnehd_process_emails($emails, $trans);
}


/**
 * New Milestone Notification Email.
 */
function fnehd_new_milestone_email($ref_id, $status, $earner, $amount, $earner_email, $title, $details) {
    $trans = array(
        '{ref-id}'       => $ref_id,
        '{status}'       => $status,
        '{earner}'       => $earner,
        '{amount}'       => $amount,
        '{earner_email}' => $earner_email,
        '{title}'        => $title,
        '{details}'      => $details,
        '{current-year}' => date('Y'),
        '{site-title}'   => get_bloginfo('name'),
        '{company-address}' => FNEHD_COMPANY_ADDRESS,
    );

    $emails = array(
        "admin" => array(
            "to"      => FNEHD_COMPANY_EMAIL,
            "subject" => FNEHD_ADMIN_NEW_MILESTONE_EMAIL_SUBJECT,
            "body"    => FNEHD_ADMIN_NEW_MILESTONE_EMAIL_BODY,
            "is_on"   => FNEHD_ADMIN_NEW_MILESTONE_EMAIL,
        ),
        "user" => array(
            "to"      => $earner_email,
            "subject" => FNEHD_USER_NEW_MILESTONE_EMAIL_SUBJECT,
            "body"    => FNEHD_USER_NEW_MILESTONE_EMAIL_BODY,
            "is_on"   => FNEHD_USER_NEW_MILESTONE_EMAIL,
        ),
    );

    fnehd_process_emails($emails, $trans);
}



// Payment Rejection Email
function fnehd_pay_rejected_email($ref_id, $earner, $shelter_title, $payer, $amount, $payer_email) {
    $footer = fnehd_get_email_footer();

    $admin_body = sprintf(
        '%s %s %s %s %s: %s.',
        esc_html($earner),
        esc_html__('rejected payment for', 'fnehousing'),
        esc_html($shelter_title),
        esc_html__('made by', 'fnehousing'),
        esc_html($payer),
        esc_html($amount)
    ) . $footer;

    $user_body = sprintf(
        '%s %s %s: %s.',
        esc_html($shelter_title),
        esc_html__('was rejected by', 'fnehousing'),
        esc_html($earner),
        esc_html($amount)
    ) . $footer;

    fnehd_send_email(FNEHD_COMPANY_EMAIL, __('Shelter Payment Rejected', 'fnehousing') . " ({$ref_id})", $admin_body);
    fnehd_send_email($payer_email, __('Shelter Payment Rejected', 'fnehousing') . " ({$ref_id})", $user_body);
}

// Payment Released Email
function fnehd_pay_released_email($ref_id, $payer, $shelter_title, $earner, $amount, $earner_email) {
    $footer = fnehd_get_email_footer();

    $admin_body = sprintf(
        '%s %s %s %s %s: %s.',
        esc_html($payer),
        esc_html__('released payment for', 'fnehousing'),
        esc_html($shelter_title),
        esc_html__('to', 'fnehousing'),
        esc_html($earner),
        esc_html($amount)
    ) . $footer;

    $user_body = sprintf(
        '%s %s %s: %s.',
        esc_html__('Shelter amount released by', 'fnehousing'),
        esc_html($payer),
        esc_html__('Released amount', 'fnehousing'),
        esc_html($amount)
    ) . $footer;

    fnehd_send_email(FNEHD_COMPANY_EMAIL, __('Shelter Payment Released', 'fnehousing') . " ({$ref_id})", $admin_body);
    fnehd_send_email($earner_email, __('Shelter Payment Released', 'fnehousing') . " ({$ref_id})", $user_body);
}

// Deposit Notification Email
function fnehd_user_deposit_email($ref_id, $username, $amount, $user_email) {
    $footer = fnehd_get_email_footer();

    $admin_body = sprintf(
        '%s %s %s.',
        esc_html($username),
        esc_html__('made a deposit of', 'fnehousing'),
        esc_html($amount)
    ) . $footer;

    $user_body = sprintf(
        '%s %s.',
        esc_html__('You deposited ', 'fnehousing'),
        esc_html($amount)
    ) . $footer;

    fnehd_send_email(FNEHD_COMPANY_EMAIL, __('User Deposit', 'fnehousing') . " ({$ref_id})", $admin_body);
    fnehd_send_email($user_email, __('User Deposit', 'fnehousing') . " ({$ref_id})", $user_body);
}

// User Withdrawal Notification Email
function fnehd_user_withdrawal_email($ref_id, $username, $amount, $user_email) {
    $footer = fnehd_get_email_footer();

    $admin_body = sprintf(
        '%s %s %s.',
        esc_html($username),
        esc_html__('made a withdrawal of ', 'fnehousing'),
        esc_html($amount)
    ) . $footer;

    $user_body = sprintf(
        '%s %s.',
        esc_html__('You made a withdrawal of ', 'fnehousing'),
        esc_html($amount)
    ) . $footer;

    fnehd_send_email(FNEHD_COMPANY_EMAIL, __('User Withdrawal', 'fnehousing') . " ({$ref_id})", $admin_body);
    fnehd_send_email($user_email, __('User Withdrawal', 'fnehousing') . " ({$ref_id})", $user_body);
}


//New User Notification Email
function fnehd_new_user_email($username){
	
	$footer = fnehd_get_email_footer();

    $admin_body = sprintf(
        '%s %s %s.',
        esc_html__("New User with username ", "fnehousing"),
        '<strong>'.$username.'</strong>',
		esc_html__(" created an account.", "fnehousing"),
    ) . $footer;
  
	fnehd_send_email(FNEHD_COMPANY_EMAIL, __("New User Signup", "fnehousing")." - ".get_bloginfo('name'), $admin_body);

}	