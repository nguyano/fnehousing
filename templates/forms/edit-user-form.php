<form id="fnehd-edit-user-form" enctype="multipart/form-data">
	<div class="card-body">
		<div style="color: red;" id="fnehd-edit-user-error"></div>
		<div class="row">
			<?php 
				$form_type = "edit"; 
				include FNEHD_PLUGIN_PATH."templates/forms/form-fields/user-form-fields.php"; 
			?>
		</div>
		<button type="submit" class="btn fnehd-btn-primary" name="edit_user" id="fnehd-edit-user-btn">
		<?= __("Submit", "fnehousing"); ?></button>
		<?php if(FNEHD_PLUGIN_INTERACTION_MODE == "modal"){ ?>
			<button type="button" class="btn btn-default" data-dismiss="modal"><?= __("Cancel", "fnehousing"); ?></button> 
		<?php } else { ?>
			<button type="button" class="btn btn-default" data-toggle="collapse" data-target="#fnehd-edit-user-form-dialog">
				<?= __("Close Form", "fnehousing"); ?>
			</button> 
		<?php } ?> 
	</div>
</form>