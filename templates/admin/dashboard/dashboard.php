<?php    

/**
 * Admin Dashboard.
 *
 * @package Fnehousing
 * @since   1.0.0
 */         

defined('ABSPATH') || exit;

$IsStatsPage = True;
  
require_once(FNEHD_PLUGIN_PATH."includes/Database/StatsDBManager.php"); 

require_once (FNEHD_PLUGIN_PATH."templates/admin/stats/shelter-charts-points.php"); 
  
$dialogs = [

	[
        'id'       => 'fnehd-add-shelter-form-dialog',
        'data_id'  => '',
        'header'   => '',
        'title'    => __("Add Shelter", "fnehousing"),
        'callback' => 'add-shelter-form.php',
        'type'     => 'add-form'
    ],
	[
        'id'       => 'fnehd-edit-shelter-form-dialog',
        'data_id'  => '',
        'header'   => '',
        'title'    => __("Update Shelter", "fnehousing"),
        'callback' => 'edit-shelter-form.php',
        'type'     => 'edit-form'
    ]
];
fnehd_callapsable_dialogs($dialogs);
	
?>

	
<div class="row">
	<?php 
	   
		$col_class= "col-md-6 d-flex align-items-stretch";
		$color    = "info"; 
		$icon     = "house-medical-circle-check"; 
		$title    = __("Recent Shelters", "fnehousing");
		if(FNEHD_PLUGIN_INTERACTION_MODE == "modal"){ 
			$subtitle = '<a href="admin.php?page=fnehousing-shelters" class="fnehd-text-info" title="'.__("See All Shelters", "fnehousing").'"> 
							<i class="fas fnehd-text-info fa-house-chimney-user"></i><span class="d-none d-md-inline">'.__("All Shelters", "fnehousing").'&nbsp;&nbsp;</span> 
						</a>
						<a href="#" class="fnehd-text-info" data-toggle="modal" data-target="#fnehd-add-shelter-modal" title="'.__("Add Shelter", "fnehousing").'"> 
							<i class="fas fnehd-text-info fa-plus"></i> <span class="d-none d-md-inline">'.__("Add Shelter", "fnehousing").'</span> 
						</a>';	
		} else {
			 $subtitle = '<a href="admin.php?page=fnehousing-shelters" class="fnehd-text-info" title="'.__("See All Shelters", "fnehousing").'"> 
							<i class="fas fnehd-text-info fa-house-chimney-user"></i><span class="d-none d-md-inline">
							'.__(" All Shelters", "fnehousing").'</span> 
						</a>&nbsp;
						<a href="#" id="addShelterDash" class="addShelter fnehd-text-info" data-toggle="collapse" data-target="#fnehd-add-shelter-form-dialog" title="'.__("Add Shelter", "fnehousing").'"> 
							<i class="fas fnehd-text-info fa-plus"></i> <span class="d-none d-md-inline">'.__("Add Shelter", "fnehousing").'</span> 
						</a>';
		}								 
		$type     = "table"; 
		$count    = 0;
		$id       = "tableDataDB";
		$box_ht   = "";
		echo fnehd_stat_chart_tbl($col_class, $color, $icon, $title, $subtitle, $type, $count, $id, $box_ht); 
		
		$top_users_data = [
			"id"       => "fnehd-stat-top-users-chart",
			"col_class"=> "col-md-6 d-flex align-items-stretch",
			"color"    => "info", 
			"icon"     => "fa-solid fa-house-fire", 
			"title"    => __("Top Shelter stats", "fnehousing"),
			"subtitle" => "", 
			"box_ht"   => "",
			"tabs" => [
				"tab1" =>[
					"id"       => "fnehd-stat-top-payers-chart",
					"title"	   => __("Top Shelter  (Bed Capacity)", "fnehousing"),
					"type"     => "chart", 
					"count"    => $totalShelterCount,
					"active"   => true,
					"selected" => "true"
				],
				"tab2" =>[
					"id"       => "fnehd-stat-top-earners-chart",
					"title"	   => __("Top Shelters (Referrals)", "fnehousing"),
					"type"     => "chart", 
					"count"    => $totalShelterCount,
					"active"   => false,
					"selected" => "false"
				]
				
			]
		];
		echo fnehd_tab_stat_chart_tbl($top_users_data);	
		
	?>
</div>
	
	
<div class="row">
	<?php 
		$col_class= "col-md-6 d-flex align-items-stretch";
		$color    = "info"; 
		$icon     = "fa-solid fa-chart-bar fa-lg"; 
		$title    = __("Monthly Shelter referrals", "fnehousing"); 
		$subtitle = ''; 
		$type     = "chart"; 
		$count    = $totalShelterCount;
		$id       = "fnehd-stat-monthly-shelter-chart"; 
		$box_ht   = "230px";
		echo fnehd_stat_chart_tbl($col_class, $color, $icon, $title, $subtitle, $type, $count, $id, $box_ht);
	?>
	<div class='col-md-6'>
		<div class="row">
			<?php 
				
				$id       = 'fnehd-stat-total-shelter-count';
				$count    = $stats->getTotalShelterCount();
				$sub      = __("Shelter", "fnehousing");
				$sub_s    = __("Shelters", "fnehousing");
				$col_class= "col-lg-6 col-md-6 col-sm-6 d-flex align-items-stretch";
				$color    = "info"; 
				$icon     = "fa-solid fa-house-user"; 
				$title    = "Total Shelters";
				echo fnehd_stat_box($id, $count, $sub, $sub_s, $col_class, $color, $icon, $title); 
					
				
				$id       = 'fnehd-stat-recent-shelter-count';
				$count    = $stats->recentShelterCount();
				$sub      = __("Shelter in last 24 hours", "fnehousing");
				$sub_s    = __("Shelters in last 24 hours", "fnehousing");
				$col_class= "col-lg-6 col-md-6 col-sm-6 d-flex align-items-stretch";
				$color    = "info"; 
				$icon     = "house-medical-circle-check"; 
				$title    = __("Recent Shelters", "fnehousing"); 
				echo fnehd_stat_box($id, $count, $sub, $sub_s, $col_class, $color, $icon, $title);
			?>
		</div>
		<div class="row">
			<?php 
				$id       = 'fnehd-stat-total-balance';
				$count    = $stats->totalAvailableShelters();
				$sub      = __("Currently Available shelter", "fnehousing");
				$sub_s    = __("Currently Available shelters", "fnehousing");
				$col_class= "col-lg-6 col-md-6 col-sm-6 d-flex align-items-stretch";
				$color    = "info"; 
				$icon     = "fa-solid fa-house-circle-check"; 
				$title    = __("Available Shelters", "fnehousing");
				echo fnehd_stat_box($id, $count, $sub, $sub_s, $col_class, $color, $icon, $title);
				
				$id       = 'fnehd-stat-total-user-count';
				$count    = $stats->getTotalUserCount();
				$sub      = "User";
				$sub_s    = "Users";
				$col_class= "col-lg-6 col-md-6 col-sm-6 d-flex align-items-stretch";
				$color    = "info"; 
				$icon     = "user-group"; 
				$title    = __("Total Users", "fnehousing");
				echo fnehd_stat_box($id, $count, $sub, $sub_s, $col_class, $color, $icon, $title);	
			?>
		</div>
	</div>		
</div>
