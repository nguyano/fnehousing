<?php

/**
 * Admin Template for Settings Panel
 * Display admin settings page.
 * 
 * @Since   1.0.0 
 * @package Fnehousing
 */

defined('ABSPATH') || exit;

// Define dialogs
$dialogs = [
    [
        'id' => 'DisplayOptionsImportForm',
        'data_id' => '',
        'header' => '',
        'title' => __('Import Options', 'fnehousing'),
        'callback' => 'options-import-form.php',
        'type' => 'restore-form',
    ],
    [
        'id' => 'fnehd-db-restore-form-dialog',
        'data_id' => '',
        'header' => '',
        'title' => __('Restore Database Backup', 'fnehousing'),
        'callback' => 'db-restore-form.php',
        'type' => 'restore-form',
    ],
    [
        'id' => 'fnehd-search-results-dialog',
        'data_id' => 'fnehd-shelter-search-results-dialog-wrap',
        'header' => '',
        'title' => __('Shelter Search Results', 'fnehousing'),
        'callback' => '',
        'type' => 'data',
    ],
];

?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <!-- Import Settings Button -->
    <button id="impSett" type="button" class="fnehd-btn-sm pr-4 pl-4 pt-3 pb-3 btn btn-icon-text shadow-lg fnehd-btn-white"
        <?php if (FNEHD_PLUGIN_INTERACTION_MODE === "modal") { ?>
            data-toggle="modal" data-target="#doptions-import"
        <?php } else { ?>
            data-toggle="collapse" data-target="#DisplayOptionsImportForm"
        <?php } ?>>
        <i class="fas fa-upload"></i> <?= esc_html__("Import Settings", "fnehousing"); ?>
    </button>

    <!-- Desktop Header -->
    <div class="card-header d-none d-md-block" style="text-align:center;">
        <h3>
            <i class="fa fa-screwdriver-wrench tbl-icon"></i>
            <span class="fnehd-tbl-header"><?= esc_html__("Fnehousing Settings", "fnehousing"); ?></span>
        </h3>
    </div>

    <!-- Export Settings Button -->
    <button id="fnehd-export-settings" class="fnehd-btn-sm pr-4 pl-4 pt-3 pb-3 btn m-1 float-right shadow-lg fnehd-btn-white">
        <i class="fa fa-download"></i> <?= esc_html__("Export Settings", "fnehousing"); ?>
    </button>

    <!-- Mobile Header -->
    <div class="card-header d-block d-md-none" style="text-align:center;">
        <h3>
            <i class="fas fa-house-chimney-user tbl-icon"></i>
            <span class="fnehd-tbl-header"><?= esc_html__("Settings", "fnehousing"); ?></span>
        </h3>
    </div>
</div>

<?php fnehd_callapsable_dialogs($dialogs); // Render collapsible dialogs ?>

<!-- Settings Form -->
<div class="card mb-3 p-5 fnehd-admin-forms">
    <div class="card-body" style="padding:30px 7%;">
        <?php include_once FNEHD_PLUGIN_PATH . "templates/forms/settings-form.php"; ?>
    </div>
</div>
