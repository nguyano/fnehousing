<?php

/**
 * Template for Shelter Milestones
 * Displays shelter milestones for a particular shelter.
 * 
 * @Since    1.0.0
 * @package  Fnehousing
 */

use Fnehousing\Database\ShelterDBManager;

defined('ABSPATH') || exit;

if (!isset($_GET['shelter_id']) || empty($_GET['shelter_id'])) {
    exit();
}

$shelter_id = sanitize_text_field($_GET['shelter_id']);

$shelter = new ShelterDBManager();

// Fetch required data
$data = $shelter->getShelterById($shelter_id);

$meta_data = $shelter->fetchShelterMetaById($shelter_id);
$shelter_meta_count = $shelter->getShelterMetaCount($shelter_id);
$shelter_count = $shelter->shelterExists($shelter_id);

// Define dialogs
$dialogs = [
    [
        'id'       => 'fnehd-milestone-form-dialog',
        'data_id'  => '',
        'header'   => '',
        'title'    => __("Add Milestone", "fnehousing"),
        'callback' => 'add-milestone-form.php',
        'type'     => 'add-form'
    ],
    [
        'id'       => 'fnehd-view-milestone-dialog',
        'data_id'  => 'fnehd-milestone-details',
        'header'   => '',
        'title'    => __("Shelter Milestone Details", "fnehousing"),
        'callback' => '',
        'type'     => 'data'
    ],
    [
        'id'       => 'fnehd-add-dispute-form-dialog',
        'data_id'  => '',
        'header'   => '',
        'title'    => __("Add Dispute", "fnehousing"),
        'callback' => 'add-dispute-form.php',
        'type'     => 'add-form'
    ]
];

?>

<div class="d-sm-flex align-items-center justify-content-between mb-4" data-shelter-id="<?= esc_attr($shelter_id); ?>" id="fnehd-admin-area-title">

    <!-- Create Milestone Button -->
    <button type="button" class="btn btn-icon-text shadow-lg fnehd-btn-white addShelter"
        <?php if (FNEHD_INTERACTION_MODE === "modal") { ?>
            data-toggle="modal" data-target="#fnehd-milestone-form-modal"
        <?php } else { ?>
            data-toggle="collapse" data-target="#fnehd-milestone-form-dialog"
        <?php } ?>>
        <i class="fas fa-plus"></i> <?= __("Create Milestone", "fnehousing"); ?>
    </button>

    <!-- Header for Desktop -->
    <div class="card-header d-none d-md-block text-center">
        <h4>
            <i class="fas fa-house-chimney-user tbl-icon"></i>
            <span class="fnehd-tbl-header">
                <?php 
                echo sprintf(
                    __("Shelter History For %s & %s (Ref#: %s)", "fnehousing"), 
                    esc_html($data["payer"]), 
                    esc_html($data["earner"]), 
                    esc_html($data['ref_id'])
                ); 
                ?>
            </span>
        </h4>
    </div>

    <!-- Export Button -->
    <button id="fnehd-export-to-excel" data-action="fnehd_export_shelter_meta_excel" class="btn m-1 float-right shadow-lg fnehd-btn-white">
        <i class="fa fa-download"></i> <?= __("Export Excel", "fnehousing"); ?>
    </button>

    <!-- Header for Mobile -->
    <div class="card-header d-block d-md-none text-center">
        <h3>
            <i class="fas fa-house-chimney-user tbl-icon"></i>
            <span class="fnehd-tbl-header"><?= __("Shelters", "fnehousing"); ?></span>
            &nbsp;&nbsp;
            <button type="button" class="btn btn-round btn-just-icon" style="background-color:transparent; box-shadow: none;" title="<?= __("Reload Table", "fnehousing"); ?>" id="reloadTable">
                <i class="fas fa-sync-alt"></i>
            </button>
        </h3>
    </div>

</div>

<?php fnehd_callapsable_dialogs($dialogs); // Render collapsible dialogs ?>

<div class="card mb-3 p-5">
    <div class="card-body">
        <div class="table-responsive" id="fnehd-view-shelter-table-wrapper">
            <?php include FNEHD_PLUGIN_PATH . "templates/shelters/view-shelter-table.php"; ?>
        </div>
    </div>
</div>
