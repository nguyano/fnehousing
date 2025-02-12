<?php 

/**
 * Admin Template for frontend User's Profile
 * Display admin side frontend user's profile.
 * 
 * @package Fnehousing
 */
 
 
if(isset($_POST["user_id"]))  {
	   
	$user_id = sanitize_text_field($_POST["user_id"]);
	$user = get_user_by( 'ID', $user_id ); 
	
	if (!$user || !username_exists($user->user_login)) {
        echo "<h3>" . esc_html__('User no Longer Exist', 'fnehousing') . "</h3>";
        exit();
    }
	
	$username = $user->user_login;
	
	$dialogs = [
		   
		   ['id' => 'fnehd-add-user-form-dialog',
			'data_id' => '',
			'header' => '',
			'title' => __("Add User", "fnehousing"),
			'callback' => 'add-user-form.php',
			'type' => 'add-form'
		   ],
		   ['id' => 'fnehd-edit-user-form-dialog',
			'data_id' => '',
			'header' => '',
			'title' => __("Edit User Account ", "fnehousing").' [<span class="small" id="CrtEditUserID"></span>]',
			'callback' => 'edit-user-form.php',
			'type' => 'edit-form'
		   ]
	];
	
	fnehd_callapsable_dialogs($dialogs);
	
	$output = '';
  
	$user_data = $this->getUserById($user_id);
	
	
?>	
<!-- Navigation Buttons -->
<div class="pr-5 pl-5 row"> 
	<div class="col-md-12 col-sm-12">  
		<div class="nav nav-pills d-sm-flex align-items-center justify-content-between mb-4" data-users-url="admin.php?page=fnehousing-users" id="fnehd-user-account">
			<a class="fnehd-user-profile-btn float-right btn shadow-lg fnehd-btn-white" data-toggle="tab" href="#fnehd-user-profile-tab" role="tablist">
			 <i class="fas fa-user"></i> <?= __("User Profile", "fnehousing"); ?>
			</a>
			<a id="fnehd-user-shelters-btn" class="float-right btn shadow-lg fnehd-btn-white" data-toggle="tab" href="#fnehd-user-shelter-tab" role="tablist">
			 <i class="fas fa-house-chimney-user"></i> <?= __("User Shelters", "fnehousing"); ?>
			</a>
			<h3 class="d-none d-md-inline"> 
				<span class="fnehd-user-profile-title"><?= __("User Profile", "fnehousing"); ?></span>
				<span class="fnehd-user-shelter-tbl-title collapse"><?= __('User Shelters', 'fnehousing'); ?></span>
			</h3>		
			<a href="#" title="Delete User" id="<?= $user_data["ID"]; ?>" class="btn btn-danger m-1 shadow-lg fnehd-delete-user-btn"> <i class="fas fa-trash"></i> 
				<span class="d-none d-md-inline"> <?= __("Delete User", "fnehousing"); ?></span>
			</a>
		</div>
		<!--Mobile Title-->
		<h3 class="d-block d-md-none text-center"> 
		    <span class="fnehd-user-profile-title"><?= __("User Profile", "fnehousing"); ?></span>
			<span class="fnehd-user-shelter-tbl-title collapse"><?= __('User Shelters', 'fnehousing'); ?></span>
		</h3> 
	</div>
</div>

 <!-- Tab Content -->
<div class="tab-content tab-space">
	<div class="tab-pane active" id="fnehd-user-profile-tab">
		<?php include FNEHD_PLUGIN_PATH."templates/admin/users/user-dashboard.php"; ?>	
	</div>	
	<div class="p-5 tab-pane" id="fnehd-user-shelter-tab">
		<div class='card fnehd-admin-forms'>
			<div class='card-body'>
				<div class="table-responsive fnehd-user-shelter-tbl">
				</div>
			</div>
		</div>
	</div>
</div>	

<?php  } ?>
   
	   

