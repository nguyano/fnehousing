<?php             

	defined('ABSPATH') || exit;
	
	$IsStatsPage = True;
		  
	require_once(FNEHD_PLUGIN_PATH."includes/Database/StatsDBManager.php"); 
 
	require_once FNEHD_PLUGIN_PATH."templates/admin/stats/shelter-charts-points.php";
	
?>			
			
<div class="row">
  
	<!--  Order Stats    -->
	<div class="col-md-12">
	 <h3 class="card-title fnehd-th"><center>
	 <?= __("Shelters Stats", "fnehousing"); ?> </center></h3><br>
	</div>
  
	<?php
		$col_class= "col-md-6 d-flex align-items-stretch";
		$color    = "info"; 
		$icon     = "fa-solid fa-chart-bar fa-lg"; 
		$title    = __("Monthly Shelter Transactions", "fnehousing"); 
		$subtitle = ''; 
		$type     = "chart"; 
		$count    = $totalShelterCount;
		$id       = "fnehd-stat-monthly-shelter-chart"; 
		$box_ht   = "250px";
		echo fnehd_stat_chart_tbl($col_class, $color, $icon, $title, $subtitle, $type, $count, $id, $box_ht);
		
		$top_users_data = [
			"id"       => "fnehd-stat-top-users-chart",
			"col_class"=> "col-md-6 d-flex align-items-stretch",
			"color"    => "info", 
			"icon"     => "fa-solid fa-house-fire", 
			"title"    => __("Top Shelter Useers", "fnehousing"),
			"subtitle" => "", 
			"box_ht"   => "",
			"tabs" => [
				"tab1" =>[
					"id"       => "fnehd-stat-top-payers-chart",
					"title"	   => __("Top Shelter Payers", "fnehousing"),
					"type"     => "chart", 
					"count"    => $totalShelterCount,
					"active"   => true,
					"selected" => "true"
				],
				"tab2" =>[
					"id"       => "fnehd-stat-top-earners-chart",
					"title"	   => __("Top Shelter Earners", "fnehousing"),
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
		$id       = 'fnehd-stat-total-shelter-count';
		$count    = $stats->getTotalShelterCount();
		$sub      = __("Shelter", "fnehousing");
		$sub_s    = __("Shelters", "fnehousing");
		$col_class= "col-lg-3 col-md-6 col-sm-6 d-flex align-items-stretch";
		$color    = "info"; 
		$icon     = "fa-solid fa-house-user"; 
		$title    = "Total Shelters";
		echo fnehd_stat_box($id, $count, $sub, $sub_s, $col_class, $color, $icon, $title); 
			
		
		$id       = 'fnehd-stat-recent-shelter-count';
		$count    = $stats->recentShelterCount();
		$sub      = __("Shelter in last 24 hours", "fnehousing");
		$sub_s    = __("Shelters in last 24 hours", "fnehousing");
		$col_class= "col-lg-3 col-md-6 col-sm-6 d-flex align-items-stretch";
		$color    = "info"; 
		$icon     = "house-medical-circle-check"; 
		$title    = __("Recent Shelters", "fnehousing"); 
		echo fnehd_stat_box($id, $count, $sub, $sub_s, $col_class, $color, $icon, $title);
		
		$id       = 'fnehd-stat-total-balance';
		$count    = $stats->totalAvailableShelters();
		$sub      = __("Currently Available shelter", "fnehousing");
		$sub_s    = __("Currently Available shelters", "fnehousing");
		$col_class= "col-lg-3 col-md-6 col-sm-6 d-flex align-items-stretch";
		$color    = "info"; 
		$icon     = "a-solid fa-house-circle-check"; 
		$title    = __("Available Shelters", "fnehousing");
		echo fnehd_stat_box($id, $count, $sub, $sub_s, $col_class, $color, $icon, $title);	
		
		$id       = 'fnehd-stat-total-user-count';
		$count    = $stats->getTotalUserCount();
		$sub      = "User";
		$sub_s    = "Users";
		$col_class= "col-lg-3 col-md-6 col-sm-6 d-flex align-items-stretch";
		$color    = "info"; 
		$icon     = "user-group"; 
		$title    = __("Total Users", "fnehousing");
		echo fnehd_stat_box($id, $count, $sub, $sub_s, $col_class, $color, $icon, $title);		
		
						
			
	?>
  
</div>

<div class="row">

	<!--  Other Stats    -->
	<br><div class="col-md-12  pt-3">
	 <h3 class="card-title fnehd-th"><center>
	 <?= __("Other General Stats", "fnehousing"); ?> </center></h3><br>
	</div>

		<?php
			$id       = 'fnehd-stat-total-backup-count';
			$count    = $stats->dbBackupsCount();
			$sub      = "Database backup";
			$sub_s    = "Database backups";
			$col_class= "col-lg-3 col-md-6 col-sm-6 d-flex align-items-stretch";
			$color    = "info"; 
			$icon     = "database"; 
			$title    = "Available Backups"; 
			echo fnehd_stat_box($id, $count, $sub, $sub_s, $col_class, $color, $icon, $title); 
			
		?>
  
</div>	



			  
			  
			  

			
			
			
      

