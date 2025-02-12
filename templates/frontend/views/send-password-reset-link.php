<?php
/*template to generates a reset link
 *
 *@since  1.0.0
 *@packag FneHousing
 *
 */

defined('ABSPATH') || exit;

ob_start();
?>
<div style="height: 450px;" class="mt-5 mb-5 pt-5">
   <?php include_once FNEHD_PLUGIN_PATH . 'templates/forms/send-password-reset-link-form.php'; ?>
</div>
<?= ob_get_clean();

