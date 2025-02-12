<div class="wizard-container">
	<div class="card card-wizard bg-transparent p-0" data-color="purple" id="wizardProfile" style="border:none; box-shadow: none;"  style="box-shadow: none !important;" >
		<form method="post" id="<?= $form_id ?>">
			<div class="wizard-navigation">
				<ul class="nav nav-pills">
					<li class="nav-item">
						<a class="nav-link active" data-toggle="tab" href="#<?= $form_type; ?>ShelterInfoTab" role="tab">
							<?php esc_html_e("Shelter Basics", "fnehousing"); ?>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link fnehd-form-next-btn" data-toggle="tab" href="#<?= $form_type; ?>DetailsTab" role="tab">
							<?php esc_html_e("Shelter Features", "fnehousing"); ?>
						</a>
					</li>
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
					<div class="tab-pane active" id="<?= $form_type; ?>ShelterInfoTab">
						<h3 class="text-light">
							<?php esc_html_e("Shelter Basic Info", "fnehousing"); ?>
						</h3>
						<br><br>
						<div class="row justify-content-center">
							<?php include FNEHD_PLUGIN_PATH . "templates/forms/form-fields/shelter-form-fields/shelter-info-form-fields.php"; ?>
						</div>
					</div>
					<div class="tab-pane" id="<?= $form_type; ?>DetailsTab">
						<h3 class="text-light">
							<?php esc_html_e("Shelter Features", "fnehousing"); ?>
						</h3>
						<br><br>
						<div class="row justify-content-center">
							<?php include FNEHD_PLUGIN_PATH . "templates/forms/form-fields/shelter-form-fields/shelter-meta-form-fields.php"; ?>
						</div>
					</div>
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
