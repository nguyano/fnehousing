<?php
/**
 * User Profile Dashboard.
 * Displays user profile data.
 *
 * @since 1.0.0
 * @package fnehousing
 */
 
 defined('ABSPATH') || exit;

// Redirect to dashboard if the endpoint is not set
if (!$fnehd_endpoint) {
    // Add the 'dashboard' endpoint to the current URL and redirect
    $redirect = add_query_arg(['endpoint' => 'dashboard'], fnehd_current_url());
    echo '<meta http-equiv="refresh" content="0;url=' . $redirect . '">';
}

// User profile data for display
$bio_data1 = [
    __('First Name', 'fnehousing') => $user_data["first_name"],
    __('Last Name', 'fnehousing')  => $user_data["last_name"],
    __('Main Role', 'fnehousing') => "FNE"
];

$bio_data2 = [
    __('Phone', 'fnehousing')   => $user_data["phone"],
    __('Country', 'fnehousing') => $user_data["country"],
    __('Email', 'fnehousing')   => $user_data["user_email"]
];


// SweetAlert messages for profile completion
$approved_swal = "Swal.fire('" . __('Approved Account', 'fnehousing') . "', '', 'success');";
$pending_swal = "Swal.fire('" . __('Pending Approval', 'fnehousing') . "', '', 'warning');";

// Determine user status and display corresponding icon
$user_status = $user_data["status"] == 1
    ? '<a onclick="' . $approved_swal . '"><i class="text-success fa fa-check-circle"></i></a>'
    : '<a onclick="' . $pending_swal . '"><i class="text-warning fa fa-exclamation-circle"></i></a>';
?>

<div class="section fnehd-user-dashboard gray-bg">
    <div class="row p-4">
        <div class="col-md-12">
            <div class="border row <?= wp_is_mobile() ? 'p-3' : 'p-5'; ?> align-items-center flex-row-reverse">
                <div class="col-lg-7">
                    <div class="about-text go-to">
                        <!-- Display user's full name and status -->
                        <h3 class="fnehd-label"><?= $user_data["first_name"] . ' ' . $user_data["last_name"]; ?> 
                            <span class="h3"><?= $user_status; ?></span>
                        </h3>

                        <p class="lead text-secondary">
                            <!-- Display user role based on escrow and earning counts -->
                            <span class="badge badge-secondary">
                                <?= __('FNE User', 'fnehousing');?>
                            </span> <b>::</b> 
                            <a href="<?= (empty($user_data["user_url"]) ? '#' : esc_url($user_data["user_url"])); ?>">
                                <?= $user_data["affiliation"]; ?>
                            </a> <b>::</b> 
                            <small><i class="fa fa-clock"></i> <?= $user_data["user_registered"]; ?></small>
                        </p>

                        <p><?= $user_data["bio"]; ?></p><hr/>

                        <!-- Display user bio data -->
                        <div class="row about-list">
                            <div class="col-md-6">
                                <?php foreach ($bio_data1 as $tle => $val) { ?>
                                    <div class="media">
                                        <label><?= $tle; ?></label>
                                        <p><?= $val; ?></p>
                                    </div>
                                <?php } ?>     
                            </div>
                            <div class="col-md-6">
                                <?php foreach ($bio_data2 as $tle => $val) { ?>
                                    <div class="media">
                                        <label><?= $tle; ?></label>
                                        <p><?= $val; ?></p>
                                    </div>
                                <?php } ?>     
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Display user avatar -->
                <div class="col-lg-5">
                    <div class="about-avatar">
                        <?= fnehd_image($user_data["user_image"], '350', 'fnehd-rounded'); ?>
                    </div>
                </div>
            </div>
        </div>    
    </div>
</div>
