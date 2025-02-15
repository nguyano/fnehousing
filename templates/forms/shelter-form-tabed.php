<?php
$sections = [
    'Shelter Basics' => ['id' => 'ShelterInfoTab', 'file' => 'shelter-info-form-fields.php'],
    'Shelter Working Hours' => ['id' => 'ShelterWorkingHoursTab', 'file' => 'shelter-working-hours-form-fields.php'],
    'Shelter Features' => ['id' => 'DetailsTab', 'file' => 'shelter-meta-form-fields.php']
];
?>

<div class="wizard-container">
    <div class="card card-wizard bg-transparent p-0" data-color="purple" id="wizardProfile" style="border:none; box-shadow: none;">
        <form method="post" id="<?= $form_id ?>">
            <div class="wizard-navigation">
                <ul class="nav nav-pills">
                    <?php foreach ($sections as $title => $data): ?>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#<?= $form_type . $data['id']; ?>" role="tab">
                                <?php esc_html_e($title, "fnehousing"); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="mt-5 card-footer">
                <div class="mr-auto">
                    <input type="button" class="btn btn-outline-secondary btn-round btn-previous btn-fill btn-wd disabled" name="previous" value="<?php esc_html_e("Previous", "fnehousing"); ?>">
                </div>
                <div class="ml-auto">
                    <input type="button" class="btn btn-outline-info btn-next btn-round btn-fill btn-wd fnehd-form-next-btn" name="next" value="<?php esc_html_e("Next", "fnehousing"); ?>">
                    <button type="submit" class="btn btn-outline-success btn-finish btn-round btn-fill btn-wd" name="finish" style="display: none;">
                        <i class="fas fa-user-plus"></i> <?= $submit_text ?> 
                    </button>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <?php foreach ($sections as $title => $data): ?>
                        <div class="tab-pane" id="<?= $form_type . $data['id']; ?>">
                            <h3 class="text-light">
                                <?php esc_html_e($title, "fnehousing"); ?>
                            </h3>
                            <br><br>
                            <div class="row justify-content-center">
                                <?php include FNEHD_PLUGIN_PATH . "templates/forms/form-fields/shelter-form-fields/" . $data['file']; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="mt-5 card-footer">
                <div class="mr-auto">
                    <input type="button" class="btn btn-outline-secondary btn-round btn-previous btn-fill btn-wd disabled" name="previous" value="<?php esc_html_e("Previous", "fnehousing"); ?>">
                </div>
                <div class="ml-auto">
                    <input type="button" class="btn btn-outline-info btn-next btn-round btn-fill btn-wd fnehd-form-next-btn" name="next" value="<?php esc_html_e("Next", "fnehousing"); ?>">
                    <button type="submit" class="btn btn-outline-success btn-finish btn-round btn-fill btn-wd" name="finish" style="display: none;">
                        <i class="fas fa-user-plus"></i> <?= $submit_text ?> 
                    </button>
                </div>
                <div class="clearfix"></div>
            </div>
        </form>
    </div>
</div>
