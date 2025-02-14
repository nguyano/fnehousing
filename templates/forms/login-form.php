<?php
/**
 * User Login Form Template
 *
 * @version   1.0.0
 * @package   Fnehousing
 */
 

// Define form fields
$login_fields = [
    [
        'label' => __('Username', 'fnehousing'),
        'type' => 'text',
        'name' => 'user_login',
        'placeholder' => __('Username', 'fnehousing'),
        'class' => 'form-control text-dark',
		'icon' => '<i class="fa-solid fa-user-doctor"></i>'
    ],
    [
        'label' => __('Password', 'fnehousing'),
        'type' => 'password',
        'name' => 'user_pass',
        'placeholder' => __('Password', 'fnehousing'),
        'class' => 'form-control text-dark',
		'icon' => '<i class="fa-solid fa-unlock-keyhole"></i>'
    ],
];
?>

<div class="fnehd-form-wrap d-md-flex rounded bg-transparent">
    <!-- Left Section -->
    <div class="fnehd-text-wrap fnehd-rounded-left text-center d-flex align-items-center shelter-md-last">
        <div class="text w-100">
            <h2 class="fnehd-h2-text"><?= esc_html(__('FNE at UCHealth ?', 'fnehousing')); ?></h2>
            <p class="text-light"><?= esc_html(__('Signup for your access to FNE Shelters!', 'fnehousing')); ?></p>
            <a href="<?= esc_url(add_query_arg(['endpoint' => 'user_signup'], fnehd_current_url())); ?>" 
               class="btn btn-white btn-round btn-outline-white">
               <?= esc_html(__('FNE Sign Up', 'fnehousing')); ?>
            </a>
        </div>
    </div>
    
    <!-- Login Form Section -->
    <div class="fnehd-login-wrap fnehd-rounded-right p-3 pr-5 pl-5">
        <div class="d-flex">
            <div class="w-100">
                <h3 class="text-dark mb-4"><?= esc_html(FNEHD_LOGIN_FORM_LABEL); ?></h3>
            </div>
        </div>
        <form enctype="multi-part/form-data" id="fnehd-user-login-form">
            <input type="hidden" name="action" value="fnehd_user_login">
            <input type="hidden" name="redirect" value="<?= esc_url(fnehd_current_url()); ?>">
            <?php wp_nonce_field('fnehd_user_login_nonce', 'nonce'); ?>
            <div id="fnehd_user_login_error"></div>
            
            <!-- Render Login Fields -->
            <?php foreach ($login_fields as $field): ?>
                <div class="form-group mb-3">
				    
                    <label class="label">
						<?= esc_html($field['label']); ?>
					</label> &nbsp;<?= $field['icon']; ?>
                    <input type="<?= esc_attr($field['type']); ?>" 
                           name="<?= esc_attr($field['name']); ?>" 
                           placeholder="<?= esc_attr($field['placeholder']); ?>" 
                           class="<?= esc_attr($field['class']); ?>" />
                </div>
            <?php endforeach; ?>
            
            <!-- Submit Button -->
            <div class="form-group">
                <button type="submit" class="w-100 fnehd-rounded text-light btn fnehd-btn-primary submit px-3">
                    <?= esc_html(__('Sign In', 'fnehousing')); ?>
                </button>
            </div>
            
            <!-- Additional Options -->
            <div class="form-group d-md-flex">
                <div class="w-50 text-left">
                    <label class="fnehd-text-sm fnehd-checkbox-wrap checkbox-primary mb-0">
                        <?= esc_html(__('Remember Me', 'fnehousing')); ?>
                        <input type="checkbox" name="remember" />
                        <span class="fnehd-checkbox-checkmark"></span>
                    </label>
                </div>
                <div class="w-50 text-md-right">
                    <a class="fnehd-text-sm text-danger" href="<?= add_query_arg(['endpoint' => 'send_password_reset_link'], home_url()); ?>">
                        <?= esc_html(__('Forgot Password', 'fnehousing')); ?>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
