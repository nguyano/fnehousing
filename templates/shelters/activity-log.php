
<?php

/**
 * Admin Template for Transaction Log
 * Display all shelter transaction logs
 * 
 * @Since   1.0.0
 * @package Fnehousing
 */

defined('ABSPATH') || exit;

?>

<div class="d-sm-flex align-items-center justify-content-between mb-4" id="fnehd-admin-area-title">

    <!-- Back Home Button -->
    <a type="button" href="admin.php?page=fnehousing-dashboard" class="btn btn-icon-text shadow-lg fnehd-btn-white">
        <i class="fas fa-arrow-left"></i> <?= __("Back Home", "fnehousing"); ?>
    </a>

    <!-- Desktop Header -->
    <div class="card-header d-none d-md-block text-center">
        <h3>
            <i class="fas fa-house-chimney-user tbl-icon"></i>
            <span class="fnehd-tbl-header"><?= __("Transaction Log", "fnehousing"); ?></span>
        </h3>
    </div>

    <!-- Export Button -->
    <button id="fnehd-export-to-excel" data-action="fnehd_export_log_excel" class="btn m-1 float-right shadow-lg fnehd-btn-white">
        <i class="fa fa-download"></i> <?= __("Export Excel", "fnehousing"); ?>
    </button>

    <!-- Mobile Header -->
    <div class="card-header d-block d-md-none text-center">
        <h3>
            <i class="fas fa-house-chimney-user tbl-icon"></i>
            <span class="fnehd-tbl-header"><?= __("Transaction Log", "fnehousing"); ?></span>
            &nbsp;
            <button type="button" class="btn btn-round btn-just-icon" style="background-color:transparent; box-shadow: none;"
                title="<?= esc_attr(__("Reload Table", "fnehousing")); ?>" id="reloadTable">
                <i class="fas fa-sync-alt"></i>
            </button>
        </h3>
    </div>

</div>

<div class="card mb-3 p-5">
    <div class="card-body">
        <div class="table-responsive" id="fnehd-shelter-log-table-wrapper">
            <?php
            // Include the transaction log table template
            include FNEHD_PLUGIN_PATH . "templates/shelters/transaction-log-table.php";
            ?>
        </div>
    </div>
</div>
