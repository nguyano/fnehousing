<?php
/*This template generates a searchable map
 *
 *@since  1.0.0
 *@packag FneHousing
 *
 */

defined('ABSPATH') || exit;

if ( isset($_GET['key']) && isset($_GET['login']) ) {
	$user = check_password_reset_key( sanitize_text_field($_GET['key']), sanitize_text_field($_GET['login']) );
	if ( ! $user || is_wp_error($user) ) {
		echo '<p class="text-danger p-5">'. __('Invalid or expired reset link.', 'fnehousing').'</p>';
	}
	ob_start();
	include_once FNEHD_PLUGIN_PATH . 'templates/forms/reset-password-form.php';
	echo ob_get_clean();
} else {
	echo '<p class="text-danger p-5">'.__('Invalid request.', 'fnehousing').'</p>';
}



