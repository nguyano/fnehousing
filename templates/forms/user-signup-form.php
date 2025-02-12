<?php
/**
 * User Signup Form Template
 *
 * @version   1.0.0
 * @package   Fnehousing
 */
 

// Define form fields
$signup_fields = [
    [
        'label' => __('First Name', 'fnehousing'),
        'type' => 'text',
        'name' => 'first_name',
        'placeholder' => __('Enter First Name', 'fnehousing'),
        'class' => 'form-control text-dark',
        'col_class' => 'col-md-6',
    ],
    [
        'label' => __('Last Name', 'fnehousing'),
        'type' => 'text',
        'name' => 'last_name',
        'placeholder' => __('Enter Last Name', 'fnehousing'),
        'class' => 'form-control text-dark',
        'col_class' => 'col-md-6',
    ],
    [
        'label' => __('Username', 'fnehousing'),
        'type' => 'text',
        'name' => 'user_login',
        'placeholder' => __('Enter Username', 'fnehousing'),
        'class' => 'form-control text-dark',
        'col_class' => 'col-md-6',
    ],
    [
        'label' => __('Email Address', 'fnehousing'),
        'type' => 'text',
        'name' => 'user_email',
        'placeholder' => __('Enter Email', 'fnehousing'),
        'class' => 'form-control text-dark',
        'col_class' => 'col-md-6',
    ],
    [
        'label' => __('Password', 'fnehousing'),
        'type' => 'password',
        'name' => 'user_pass',
        'placeholder' => __('Enter Password', 'fnehousing'),
        'class' => 'form-control text-dark',
        'col_class' => 'col-md-6',
    ],
    [
        'label' => __('Confirm Password', 'fnehousing'),
        'type' => 'password',
        'name' => 'confirm_pass',
        'placeholder' => __('Enter Password', 'fnehousing'),
        'class' => 'form-control text-dark',
        'col_class' => 'col-md-6',
    ],
];
?>

<div class="fnehd-form-wrap p-5">
    <form enctype="multi-part/form-data" id="fnehd-user-signup-form">
        <input type="hidden" name="form_type" value="signup form">
        <input type="hidden" name="user" value="user">
        <input type="hidden" name="action" value="fnehd_user_signup">
        <input type="hidden" name="redirect" value="<?= esc_url(fnehd_current_url()); ?>">
        <?php wp_nonce_field('fnehd_signup_nonce', 'nonce'); ?>

        <!-- Form Title -->
        <div class="w-100">
            <h2 class="pb-3 text-center"><?= esc_html(FNEHD_SIGNUP_FORM_LABEL); ?></h2>
        </div>
        <div class="w-100" id="fnehd_signup_error"></div>

        <!-- Render Form Fields -->
        <div class="p-2 row">
            <?php foreach ($signup_fields as $field): ?>
                <div class="<?= esc_attr($field['col_class']); ?> form-group mb-3">
                    <label class="label"><?= esc_html($field['label']); ?></label>
                    <input type="<?= esc_attr($field['type']); ?>" 
                           name="<?= esc_attr($field['name']); ?>" 
                           placeholder="<?= esc_attr($field['placeholder']); ?>" 
                           class="<?= esc_attr($field['class']); ?>" />
                </div>
            <?php endforeach; ?>

            <!-- Submit Button -->
            <div class="p-2 form-group">
                <button type="submit" class="fnehd-rounded text-light btn fnehd-btn-primary submit">
                    <?= esc_html(__('Sign Up', 'fnehousing')); ?>
                </button>
				<span class="sm">
					<?= __('Already got an account?..', 'fnehousing'); ?>
					<a class="text-primary" href="<?= esc_url(home_url()); ?>">
					<?= __('Login', 'fnehousing'); ?>
					</a>
				</span>
            </div>
        </div>
    </form>
</div>
