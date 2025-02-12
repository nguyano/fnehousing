<?php
/**
 * New Password Form Template
 *
 * @version   1.0.0
 * @package   Fnehousing
 */
 
 defined('ABSPATH') || exit;
 
?> 
<form id="fnehd-pass-reset-link-form" class="card p-4 shadow-lg rounded border-0" style="max-width: 400px; margin: auto;">
    <h4 class="text-center mb-3">Reset Your Password</h4>
    <input type="hidden" name="action" value="fnehd_password_reset_link">
    <div class="form-group">
        <label for="user_login">Enter Your Email or Username</label>
        <input type="text" class="form-control" id="user_login" name="user_login" placeholder="example@example.com" required>
    </div>

    <button type="submit" class="btn btn-primary btn-block">Send Reset Link</button>

    <div class="text-center mt-3">
        <a href="<?= home_url(); ?>" class="text-muted">Back to Login</a>
    </div>
</form>
