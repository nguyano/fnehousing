<div class="p-5">
	<form id="<?= $form_id; ?>" enctype="multipart/form-data">
		<div class="card-body">
			<div class="row">
				<div class="col-md-12">
					<label>
						<h3 class="text-dark">
							<?= esc_html__("Shelter Basic info", "fnehousing"); ?>
						</h3>
					</label>
				</div>
				<?php
				include FNEHD_PLUGIN_PATH . "templates/forms/form-fields/shelter-form-fields/shelter-info-form-fields.php";
				?>
				<div class="col-md-12">
					<label>
						<h3 class="text-dark">
							<?php esc_html_e("Shelter Features", "fnehousing"); ?>
						</h3>
					</label>
				</div>
				<?php
				include FNEHD_PLUGIN_PATH . "templates/forms/form-fields/shelter-form-fields/shelter-meta-form-fields.php";
				?>
			</div>
			<br><br>
			<br>
			<button type="submit" class="btn fnehd-btn-primary text-white">
				<?= $submit_text; ?> 
			</button>
			<?php if (FNEHD_PLUGIN_INTERACTION_MODE === "modal") : ?>
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