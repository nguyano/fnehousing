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
function fnehd_new_shelter_email($ref_id, $shelter_name, $shelter_organization, $phone, $website, $email, $address) {
    $trans = array(
        '{ref-id}'       => $ref_id,
		'{shelter_name}' => $shelter_name,
		'{shelter_organization}' => $shelter_organization,
		'{shelter_phone}' => $phone,
        '{shelter_website}' => $website,
		'{shelter_email}' => $email,
		'{shelter_address}' => $address,
        '{current-year}' => date('Y'),
        '{site-title}'   => get_bloginfo('name'),
        '{company-address}' => FNEHD_COMPANY_ADDRESS,
    );

    $emails = array(
        "admin" => array(//notify admin
            "to"      => FNEHD_COMPANY_EMAIL,
            "subject" => FNEHD_ADMIN_NEW_SHELTER_EMAIL_SUBJECT,
            "body"    => FNEHD_ADMIN_NEW_SHELTER_EMAIL_BODY,
            "is_on"   => FNEHD_ADMIN_NEW_SHELTER_EMAIL,
        ),
        "user" => array(//notify shelter
            "to"      => $email,
            "subject" => FNEHD_USER_NEW_SHELTER_EMAIL_SUBJECT,
            "body"    => FNEHD_USER_NEW_SHELTER_EMAIL_BODY,
            "is_on"   => FNEHD_USER_NEW_SHELTER_EMAIL,
        ),
    );

    fnehd_process_emails($emails, $trans);
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