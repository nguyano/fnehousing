<?php

/**
 * Admin Template for Shelters 
 * Displays all shelters n a table
 * 
 * @Since    1.0.0
 * @package  Fnehousing
 */

defined('ABSPATH') || exit;

// Define dialog configurations
$dialogs = [
    [
        'id'       => 'fnehd-add-shelter-form-dialog',
        'data_id'  => '',
        'header'   => '',
        'title'    => __("Add Shelter", "fnehousing"),
        'callback' => 'add-shelter-form.php',
        'type'     => 'add-form'
    ],
	[
        'id'       => 'fnehd-edit-shelter-form-dialog',
        'data_id'  => '',
        'header'   => '',
        'title'    => __("Update Shelter", "fnehousing"),
        'callback' => 'edit-shelter-form.php',
        'type'     => 'edit-form'
    ]
];

?>

<div class="d-sm-flex align-items-center justify-content-between mb-4" id="fnehd-admin-area-title">

    <!-- Add Shelter Button -->
    <button type="button" class="btn btn-icon-text shadow-lg fnehd-btn-white addShelter"
        <?php if (defined('FNEHD_PLUGIN_INTERACTION_MODE') && FNEHD_PLUGIN_INTERACTION_MODE === "modal") { ?>
            data-toggle="modal" data-target="#fnehd-add-shelter-modal"
        <?php } else { ?>
            data-toggle="collapse" data-target="#fnehd-add-shelter-form-dialog"
        <?php } ?>>
        <i class="fas fa-plus"></i> <?= __("Add Shelter", "fnehousing"); ?>
    </button>

    <!-- Desktop Header -->
    <div class="card-header d-none d-md-block text-center">
        <h3>
            <i class="fa-solid fa-house-chimney-user tbl-icon"></i>
            <span class="fnehd-tbl-header"><?= __("Shelters", "fnehousing"); ?></span>
            <button type="button" class="btn btn-round btn-just-icon" style="background-color:transparent; box-shadow: none;" 
                title="<?= esc_attr(__("Reload Table", "fnehousing")); ?>" id="reloadTable">
                <i class="text-dark fas fa-rotate-right"></i>
            </button>
        </h3>
    </div>

    <!-- Export Button -->
    <button id="fnehd-export-to-excel" data-action="fnehd_export_shelters_excel" class="btn m-1 float-right shadow-lg fnehd-btn-white">
        <i class="fa fa-download"></i> <?= __("Export Excel", "fnehousing"); ?>
    </button>

    <!-- Mobile Header -->
    <div class="card-header d-block d-md-none text-center">
        <h3>
            <i class="fas fa-house-chimney-user tbl-icon"></i>
            <span class="fnehd-tbl-header"><?= __("Shelters", "fnehousing"); ?></span>
            <button type="button" class="btn btn-round btn-just-icon" style="background-color:transparent; box-shadow: none;" 
                title="<?= esc_attr(__("Reload Table", "fnehousing")); ?>" id="reloadTable">
                <i class="fas fa-rotate-right"></i>
            </button>
        </h3>
    </div>

</div>

<?php fnehd_callapsable_dialogs($dialogs); // Render collapsible dialogs ?>

<div class="card p-5 mb-3">
    <div class="card-body">
        <div class="table-responsive" id="fnehd-shelter-table-wrapper">
            <?php include FNEHD_PLUGIN_PATH . "templates/shelters/shelters-table.php"; ?>
        </div>
    </div>
</div>
