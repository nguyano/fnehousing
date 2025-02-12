<form id="fnehd-options-imp-form" enctype="multipart/form-data">
	<div class="card-body p-5">
	    <div style="color: red;" id="fnehd-option-import-error"></div>
		<div class="row">
			<input type="hidden" name="action" value="fnehd_imp_options">
				
			<div class="col-md-12" style="padding-top: 15px !important;">
				<div class="fileinput fileinput-new" data-provides="fileinput">
				    <div>
						<span class="btn btn-round fnehd-btn-primary btn-file fnehd-btn-sm">
							<span class="fileinput-new"><?= __("Choose OPtions File", "fnehousing"); ?></span>
							<span class="fileinput-exists"><?= __("Change", "fnehousing"); ?> 
							<?= __("File", "fnehousing"); ?></span>
							<input type="file" name="option_file" accept="application/json" required> 
						</span>  
						<span class="text-light fileinput-preview">
							<?= __("File must be in Json (.json) format", "fnehousing"); ?>
						</span>
						<br/>
					    <a href="javascript:;" class="btn btn-danger btn-round fileinput-exists fnehd-btn-sm" data-dismiss="fileinput"><i class="fa fa-times"></i> <?= __("Remove", "fnehousing"); ?></a>
				    </div>
				</div>
			</div>
	    </div><br><br>
	    <button type="submit" class="btn fnehd-btn-primary" id="imp_options_btn">
	    <?= __("Import Options", "fnehousing"); ?></button>
	    <?php if(FNEHD_PLUGIN_INTERACTION_MODE == "modal"){ ?>
		   <button type="button" class="btn btn-default" data-dismiss="modal">
		   <?= __("Cancel", "fnehousing"); ?></button>
		<?php } else { ?>
			<button type="button" class="btn btn-default" data-toggle="collapse" data-target="#DisplayOptionsImportForm">
			   <?= __("Close Form", "fnehousing"); ?>
			</button> 
		<?php } ?>	
	  </div>
</form>