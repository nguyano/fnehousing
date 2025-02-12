<?php
/**
 * Frontend User Account Form Template
 *
 * @version   1.0.0
 * @package   Fnehousing
 */
 
 
// Check if the user's profile is complete
$user_info = [];
$info_list = ['phone', 'address', 'country', 'company', 'user_url', 'bio', 'user_image'];
foreach ($info_list as $info) {
    $user_info[$info] = $user_data[$info] ?? '';
}

// Calculate completion percentage
$completed_count = count( array_filter( $user_info, function ($value) { return !empty($value); } ) );
$percent_complete = round( ( $completed_count / count($user_info) ) * 100 );

// Format the user registration date
$date = new \DateTime($user_data['user_registered']);
$joined_date = $date->format('F j, Y');


// Define fields for user account form
$fields = [
    [
        'label' => __('Username', 'fnehousing'),
        'type' => 'text',
        'name' => 'user_login',
        'placeholder' => '',
        'value' => $user_data['user_login'],
        'readonly' => true,
        'class' => 'col-md-4',
    ],
    [
        'label' => __('First Name', 'fnehousing'),
        'type' => 'text',
        'name' => 'first_name',
        'placeholder' => __('John Smith', 'fnehousing'),
        'value' => $user_data['first_name'],
        'class' => 'col-md-4',
    ],
    [
        'label' => __('Last Name', 'fnehousing'),
        'type' => 'text',
        'name' => 'last_name',
        'placeholder' => __('Enter Last Name', 'fnehousing'),
        'value' => $user_data['last_name'],
        'class' => 'col-md-4',
    ],
    [
        'label' => __('Email', 'fnehousing'),
        'type' => 'text',
        'name' => 'user_email',
        'placeholder' => __('Enter Email', 'fnehousing'),
        'value' => $user_data['user_email'],
        'class' => 'col-md-4',
    ],
    [
        'label' => __('Phone', 'fnehousing'),
        'type' => 'text',
        'name' => 'phone',
        'placeholder' => __('Enter Phone No.', 'fnehousing'),
        'value' => $user_data['phone'],
        'class' => 'col-md-4',
    ],
    [
        'label' => __('Country', 'fnehousing'),
        'type' => 'select',
        'name' => 'country',
        'options' => fnehd_countries(),
        'selected' => $user_data['country'],
        'class' => 'col-md-4',
    ],
    [
        'label' => __('Affiliation', 'fnehousing'),
        'type' => 'select',
        'name' => 'affiliation',
		'options' => ['UCHealth - UC Hospital' => 'UCHealth - UC Hospital', 'UCHealth - Highlands Ranch' => 'UCHealth - Highlands Ranch'],
        'placeholder' => __('Enter affiliation', 'fnehousing'),
        'selected' => $user_data['affiliation'],
        'class' => 'col-md-6',
    ],
    [
        'label' => __('Website', 'fnehousing'),
        'type' => 'text',
        'name' => 'user_url',
        'placeholder' => __('Enter Website', 'fnehousing'),
        'value' => $user_data['user_url'],
        'class' => 'col-md-6',
    ],
    [
        'label' => __('Address', 'fnehousing'),
        'type' => 'text',
        'name' => 'address',
        'placeholder' => __('Enter Address', 'fnehousing'),
        'value' => $user_data['address'],
        'class' => 'col-md-12',
    ],
    [
        'label' => __('Short Bio', 'fnehousing'),
        'type' => 'textarea',
        'name' => 'bio',
        'placeholder' => __('Enter a short bio (max 200 characters)', 'fnehousing'),
        'value' => $user_data['bio'],
        'class' => 'col-md-12',
    ],
];
?>

<div class="pt-5 fnehd-rounded e-profile">
    <form id="fnehd-edit-user-form" class="form" enctype="multipart/form-data">
        <div style="color: red;" id="fnehd_user_error"></div>
        <div class="row">
            <div class="col-12 col-sm-auto mb-3">
                <div class="mx-auto" style="width: 140px;">
                    <div class="d-flex justify-content-center align-items-center rounded" style="height: 140px; background-color: rgb(233, 236, 239);">
                        <?= fnehd_image($user_data['user_image'], 100, 'rounded-circle'); ?>
                    </div>
                </div>
            </div>
            <div class="col d-flex flex-column flex-sm-row justify-content-between mb-3">
                <div class="text-center text-sm-left mb-2 mb-sm-0">
                    <h4 class="pt-sm-2 pb-1 mb-0 text-nowrap">
                        <?= esc_html($user_data['first_name'] . ' ' . $user_data['last_name']); ?>
                    </h4>
                    <p class="mb-0">@<?= esc_html($user_data['user_login']); ?></p>
					
					<!-- Image Upload-->
					<div class="mt-2">
						<div class="fileinput fileinput-new text-center" data-provides="fileinput">
							<div class="fileinput-new thumbnail img-circle"></div>
							<div class="fileinput-preview fileinput-exists thumbnail img-circle"></div>
							<div>
								<span class="btn btn-round btn-primary btn-file btn-sm">
									<span class="fileinput-new">
										<i class="fa fa-fw fa-camera"></i><?= __('Update User Image', 'fnehousing'); ?>
									</span>
									<span class="fileinput-exists">
										<i class="fa fa-fw fa-camera"></i><?= __('Change Image', 'fnehousing'); ?>
									</span>
									<input type="file" name="file" accept="image/jpg,image/jpeg,image/png,image/webp"/>
								</span><br>
								<a href="javascript:;" class="btn btn-danger btn-round fileinput-exists btn-sm" data-dismiss="fileinput">
								<i class="fa fa-times"></i> <?= __('Remove', 'fnehousing'); ?></a>
							</div>
						</div>
					</div>
					
                </div>
                <div class="text-center text-sm-right">
                    <span class="badge badge-secondary"><?= __('User', 'fnehousing'); ?></span>
                    <div class="text-muted">
                        <small>
                            <?= sprintf(__('Joined: %s', 'fnehousing'), esc_html($joined_date)); ?><br>
                            <?= sprintf(__('Profile Completion: %d%%', 'fnehousing'), $percent_complete); ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-content pt-3">
            <div class="tab-pane active">
                <input type="hidden" name="action" value="fnehd_update_user">
                <?php wp_nonce_field('fnehd_user_nonce', 'nonce'); ?>

                <div class="row">
                    <?php foreach ($fields as $field): ?>
                        <div class="<?= esc_attr($field['class']); ?>">
                            <div class="form-group">
                                <label><b><?= esc_html($field['label']); ?></b></label>
                                <?php if ($field['type'] === 'select'): ?>
                                    <select class="form-control" name="<?= esc_attr($field['name']); ?>">
                                        <?php foreach ($field['options'] as $value => $title): ?>
                                            <option value="<?= esc_attr($value); ?>" <?= selected($value, $field['selected'], false); ?>>
                                                <?= esc_html($title); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php elseif ($field['type'] === 'textarea'): ?>
                                    <textarea class="form-control" name="<?= esc_attr($field['name']); ?>" 
                                              placeholder="<?= esc_attr($field['placeholder']); ?>" 
                                              rows="5" maxlength="200"><?= esc_html($field['value']); ?></textarea>
                                <?php else: ?>
                                    <input type="<?= esc_attr($field['type']); ?>" 
                                           class="form-control" 
                                           name="<?= esc_attr($field['name']); ?>" 
                                           placeholder="<?= esc_attr($field['placeholder']); ?>" 
                                           value="<?= esc_attr($field['value']); ?>" 
                                           <?= !empty($field['readonly']) ? 'readonly' : ''; ?>>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="col d-flex justify-content-end">
                    <button class="btn text-light fnehd-btn-primary fnehd-rounded" type="submit">
						<?= __('Save Changes', 'fnehousing'); ?>
					</button>
                </div>
            </div>
        </div>
    </form>
</div>
