<?php
/**
 * Change Password Form Template
 *
 * @version   1.0.0
 * @package   Fnehousing
 */
 

// Define form fields
$password_fields = [
    [
        'label' => __('Current Password', 'fnehousing'),
        'type' => 'password',
        'name' => 'old_pass',
        'placeholder' => '••••••',
        'class' => 'form-control',
        'col_class' => 'col',
        'required' => true,
    ],
    [
        'label' => __('New Password', 'fnehousing'),
        'type' => 'password',
        'name' => 'user_pass',
        'placeholder' => '••••••',
        'class' => 'form-control',
        'col_class' => 'col',
        'required' => true,
    ],
    [
        'label' => __('Confirm Password', 'fnehousing'),
        'type' => 'password',
        'name' => 'confirm_pass',
        'placeholder' => '••••••',
        'class' => 'form-control',
        'col_class' => 'col',
        'required' => true,
    ],
];
?>

<div class="p-5 fnehd-rounded e-profile">
    <!-- Error Message -->
    <div style="color: red;" id="fnehd_user_pass_error"></div>
    <br><br>
    <div class="tab-content pt-3">
        <div class="tab-pane active">
            <form id="EditFnehdUserPassForm" class="form">
                <input type="hidden" name="action" value="fnehd_change_user_pass">
                <?php wp_nonce_field('fnehd_user_pass_nonce', 'nonce'); ?>

                <!-- Form Title -->
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <div class="mb-2 h3"><b><?= esc_html(__('Change Password', 'fnehousing')); ?></b></div>
                        </div>
                    </div>
                </div>
                <br><br>

                <!-- Render Password Fields -->
                <div class="row">
                    <?php foreach ($password_fields as $field): ?>
                        <div class="<?= esc_attr($field['col_class']); ?>">
                            <div class="form-group">
                                <label><?= esc_html($field['label']); ?></label>
                                <input 
                                    type="<?= esc_attr($field['type']); ?>" 
                                    name="<?= esc_attr($field['name']); ?>" 
                                    placeholder="<?= esc_attr($field['placeholder']); ?>" 
                                    class="<?= esc_attr($field['class']); ?>" 
                                    <?= $field['required'] ? 'required' : ''; ?>>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <br><br>

                <!-- Submit Button -->
                <div class="row">
                    <div class="col d-flex justify-content-end">
                        <button class="btn text-light fnehd-btn-primary fnehd-rounded" type="submit">
                            <?= esc_html(__('Save Changes', 'fnehousing')); ?>
                        </button>
                    </div>
                </div>
                <br><br>
            </form>
        </div>
    </div>
</div>
