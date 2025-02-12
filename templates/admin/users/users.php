<?php

/**
 * Admin Template for Shelter Users
 * Display all frontend users
 * 
 * @Since   1.0.0
 * @package Fnehousing
 */

defined('ABSPATH') || exit;


// Define dialogs for user management
$dialogs = [
    [
        'id' => 'fnehd-add-user-form-dialog',
        'data_id' => '',
        'header' => '',
        'title' => __("Add User", "fnehousing"),
        'callback' => 'add-user-form.php',
        'type' => 'add-form'
    ],
    [
        'id' => 'fnehd-edit-user-form-dialog',
        'data_id' => '',
        'header' => '',
        'title' => __("Edit User Account", "fnehousing") . ' [<span class="small" id="CrtEditUserID"></span>]',
        'callback' => 'edit-user-form.php',
        'type' => 'edit-form'
    ]
];


?>

<div class="d-sm-flex align-items-center justify-content-between mb-4" id="fnehd-admin-area-title">

    <!-- Add User Button -->
    <button type="button" class="btn btn-icon-text shadow-lg fnehd-btn-white" 
        <?php if (FNEHD_PLUGIN_INTERACTION_MODE == "modal") { ?>
            data-toggle="modal" data-target="#fnehd-add-user-modal" 
        <?php } else { ?>
            data-toggle="collapse" data-target="#fnehd-add-user-form-dialog" 
        <?php } ?>>
        <i class="fas fa-plus"></i> <?= __("Add User", "fnehousing"); ?>
    </button>

    <!-- Desktop Header -->
    <div class="card-header d-none d-md-block">
        <h3>
            <i class="fas fa-user-group tbl-icon"></i>
            <span class="fnehd-tbl-header"><?= __("Users", "fnehousing"); ?></span>
        </h3>
    </div>

    <!-- Export Button -->
    <button id="fnehd-export-to-excel" data-action="fnehd_export_user_excel" class="btn m-1 float-right shadow-lg fnehd-btn-white">
        <i class="fa fa-download"></i> <?= __("Export To Excel", "fnehousing"); ?>
    </button>

    <!-- Mobile Header -->
    <div class="card-header d-block d-md-none text-center">
        <h3>
            <i class="fas fa-user-group tbl-icon"></i>
            <span class="fnehd-tbl-header"><?= __("Users", "fnehousing"); ?></span>
        </h3>
    </div>

</div>

<?php fnehd_callapsable_dialogs($dialogs); // Render collapsible dialogs ?>

<div class="card mb-3 p-5">
    <div class="card-body">
        <!-- User Table -->
        <div id="fnehd-user-table-wrapper">
            <?php include_once FNEHD_PLUGIN_PATH . "templates/admin/users/users-table.php"; ?>
        </div>
    </div>
</div>
