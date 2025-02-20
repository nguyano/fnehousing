<form id="UserAddForm" enctype="multipart/form-data">
	<div class="card-body">
		<div style="color: red;" id="fnehd-add-user-error"></div>
		<div class="row">
			<?php 
				$form_type = "add"; 
				include FNEHD_PLUGIN_PATH."templates/forms/form-fields/user-form-fields.php"; 
			?>
		</div>
			<br><br>
			<button type="submit" class="btn fnehd-btn-primary" name="add_user" id="adduser">
			<?= __("Submit", "fnehousing"); ?></button>
			<?php if(FNEHD_INTERACTION_MODE == "modal"){ ?>
			<button type="button" class="btn btn-default" data-dismiss="modal"><?= __("Cancel", "fnehousing"); ?></button> 
			<?php } else { ?>
				<button type="button" class="btn btn-default" data-toggle="collapse" data-target="#fnehd-add-user-form-dialog">
				   <?= __("Close Form", "fnehousing"); ?>
				</button> 
			<?php } ?>
	</div>
</form>