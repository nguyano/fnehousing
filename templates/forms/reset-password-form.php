<?php
/**
 * New Password Form Template
 *
 * @version   1.0.0
 * @package   Fnehousing
 */
 
 defined('ABSPATH') || exit;
 
?> 
<form id="fnehd-pass-reset-form" enctype="multipart/form-data" class="card p-4 shadow-lg rounded border-0" style="max-width: 400px; margin: auto;">
    <h4 class="text-center mb-3"><?= __('Set a New Password', 'fnehousing'); ?></h4>

    <input type="hidden" name="action" value="fnehd_reset_password">
    <input type="hidden" name="reset_key" value="<?php '';//echo esc_attr($_GET['key']); ?>">
    <input type="hidden" name="user_login" value="<?php '';//echo esc_attr($_GET['login']); ?>">

    <div class="form-group">
        <label for="new_password"><?= __('New Password', 'fnehousing'); ?></label>
        <input type="password" class="form-control" name="new_password" id="new_password" placeholder="Enter new password" required>
    </div>

    <div class="form-group">
        <label for="confirm_password"><?= __('Confirm Password', 'fnehousing'); ?></label>
        <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Confirm new password" required>
    </div>

    <button type="submit" name="reset_password" class="btn btn-primary btn-block">Reset Password</button>

    <div class="text-center mt-3">
        <a href="/login" class="text-muted"><?= __('Back to Login', 'fnehousing'); ?></a>
    </div>
</form>
