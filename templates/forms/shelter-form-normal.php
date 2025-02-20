<?php
$sections = [
    'Shelter Basic Info' => 'shelter-info-form-fields.php',
    'Shelter Working Hours' => 'shelter-working-hours-form-fields.php',
    'Shelter Features' => 'shelter-meta-form-fields.php'
];
?>

<div class="p-5">
    <form id="<?= $form_id; ?>" enctype="multipart/form-data">
        <div class="card-body">
            <div class="row">
                <?php foreach ($sections as $title => $file) : ?>
                    <div class="col-md-12">
                        <label>
                            <h3 class="text-dark">
                                <?= esc_html__($title, "fnehousing"); ?>
                            </h3>
                        </label>
                    </div>
                    <?php include FNEHD_PLUGIN_PATH . "templates/forms/form-fields/shelter-form-fields/" . $file; ?>
                <?php endforeach; ?>
            </div>
            <br><br>
            <br>
            <button type="submit" class="btn fnehd-btn-primary text-white">
                <?= $submit_text; ?> 
            </button>
            <?php if (FNEHD_INTERACTION_MODE === "modal") : ?>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php esc_html_e("Cancel", "fnehousing"); ?>
                </button>
            <?php else : ?>
                <button type="button" class="btn btn-default" data-toggle="collapse" data-target="#<?= $dialog_target; ?>">
                    <?php esc_html_e("Close Form", "fnehousing"); ?>
                </button>
            <?php endif; ?>
        </div>
    </form>
</div>
