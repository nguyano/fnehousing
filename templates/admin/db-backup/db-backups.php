
<div class="d-sm-flex align-items-center justify-content-between mb-4">

	<button id="BackupDB" type="button" class="btn btn-icon-text shadow-lg fnehd-btn-white">
		<i class="fas fa-plus"></i> <?= __("Create", "fnehousing"); ?> 
		<span class="d-none d-md-inline"><?= __("New DB", "fnehousing"); ?></span> 
		<?= __("Backup", "fnehousing"); ?>
	</button>
	  
	<!--- Desktop Only ----->
	<div class="card-header d-none d-md-block">
	  <h3>  <i class="fa-solid fa-database tbl-icon"></i><span class="fnehd-tbl-header"> 
	  <?= __("Fnehousing Database Backups", "fnehousing"); ?> </span></h3>
	</div>
	  
	<button id="GLotDBRestore" class="btn m-1 float-right shadow-lg fnehd-btn-white" 
		<?php if(FNEHD_INTERACTION_MODE == "modal") { ?> data-toggle="modal" data-target="#fnehd-db-restore-modal" 
		<?php } else { ?> data-toggle="collapse" data-target="#fnehd-db-restore-form-dialog" <?php } ?> >
		<i class="fa fa-sync"></i> <?= __("Restore", "fnehousing"); ?> 
		<span class="d-none d-md-inline"><?= __("Old Backup", "fnehousing"); ?></span> 
	</button>

	
	<!--- Mobile Only ----->
	<div class="card-header d-block d-md-none" style="text-align:center;">
	   <h3>
			<i class="fa-solid fa-paste tbl-icon"></i>
			<span class="fnehd-tbl-header"><?= __("Database Backups", "fnehousing"); ?></span>
	   </h3>
	</div>
	
</div>

<div class="card mb-3 p-5">
  <div class="card-body">
	 <div class="table-responsive" id="tableDataDBBackup"> 
	
	 <?php include_once FNEHD_PLUGIN_PATH."templates/admin/db-backup/dbbackups-table.php"; ?>
	
	 </div>
   </div>
   
</div>