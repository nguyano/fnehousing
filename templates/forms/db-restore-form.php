<form id="RestoreDBForm" enctype="multipart/form-data">
	<div class="card-body p-5">
		<div style="color: red;" id="error_msgdbres"></div>
	    <div class="row">
		<input type="hidden" name="action" value="fnehd_extfile_dbrestore"><!-- Ajax action callback-->
		<div class="col-md-12" style="padding-top: 15px !important;">
			<div class="fileinput fileinput-new" data-provides="fileinput">
			    <div>
				    <span class="btn btn-round fnehd-btn-primary btn-file btn-sm">
					    <span class="fileinput-new"><?= __("Choose Backup File", "fnehousing"); ?></span>
					    <span class="fileinput-exists"><?= __("Change File", "fnehousing"); ?></span>
					    <input type="file" name="backup_file" accept=".sql" required> 
				    </span>  
				    <span class="text-light fileinput-preview "><?= __("File must be in sql format", "fnehousing"); ?></span>
				    <br/>
				    <a href="javascript:;" class="btn btn-danger btn-round fileinput-exists fnehd-btn-sm" data-dismiss="fileinput"><i class="fa fa-times"></i> <?= __("Remove", "fnehousing"); ?></a>
				</div>
			</div>
		</div>
	   </div><br><br>
		<button type="submit" class="btn fnehd-btn-primary" id="restoredb">
		<?= __("Restore DB", "fnehousing"); ?></button>
		<?php if(FNEHD_PLUGIN_INTERACTION_MODE == "modal"){ ?>
		   <button type="button" class="btn btn-default" data-dismiss="modal">
		   <?= __("Cancel", "fnehousing"); ?></button>
		<?php } else { ?>
			<button type="button" class="btn btn-default" data-toggle="collapse" data-target="#fnehd-db-restore-form-dialog">
			   <?= __("Close Form", "fnehousing"); ?>
			</button> 
		<?php } ?>	
	</div>
</form>