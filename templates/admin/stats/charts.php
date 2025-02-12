<script>

window.onload = function () {

<?php 
	
	if ($IsStatsPage && $totalShelterCount > 0) { 
	
		$pie_charts =[
		   "Top Payers" => [
		        "title" =>  __("Top Shelters (Bed Capacity)", "fnehousing"),
				"id" => "fnehd-stat-top-payers-chart",
				"data_points" => $top_beds_data_points
		   
		   ],
		   "Top Earners" => [
		        "title" =>  __("Top Shelters (Referrals)", "fnehousing"),
				"id" => "fnehd-stat-top-earners-chart",
				"data_points" => $top_referrals_data_points
		   
		   ]
		];
		
		
		foreach ($pie_charts as $chart){
?>
 
			var chart = new CanvasJS.Chart("<?= $chart['id']; ?>", {
				animationEnabled: true,
				exportEnabled: false,
				backgroundColor: "transparent",
				
				axisX:{
			   labelFontColor: "#029b61"
				  },
				 
				title:{
				text: "<?= $chart['title']; ?>",
					fontColor: "#2271b1",
					fontFamily: "jost",
					fontSize: "18",
				},
				axisY:{
					includeZero: true,
					labelFontColor: "red"
				},
				data: [{
					type: "doughnut", //change type to bar, line, area, pie, etc
					innerRadius: 45,
					radius: "75%",
					indexLabel: "{label} ({y})",
					indexLabelFontColor: "#2271b1",
					indexLabelFontSize: "12.2",
					indexLabelPlacement: "outside",   
					dataPoints: <?= json_encode($chart['data_points'], JSON_NUMERIC_CHECK); ?>
				}]
			});
			chart.render();

        <?php } ?>
	

		    //Shelter Stats spline graph 
		    var chart = new CanvasJS.Chart("fnehd-stat-monthly-shelter-chart", {
				animationEnabled: true,
				exportEnabled: false,
				backgroundColor: "transparent",
				
				axisX:{
			   labelFontColor: "#029b61"
				  },
				 
				title:{
				text: "Shelters: <?= date('Y'); ?> vs <?= date('Y')-1; ?>",
					fontColor: "#2271b1",
					fontFamily: "jost",
				},
				axisY:{
					includeZero: true,
					labelFontColor: "#029b61"
				},
				legend:{
					fontColor: "#2271b1",
				},
				data: [{
					type: "spline", //change type to bar, line, area, pie, etc
					 name: "<?= date('Y')-1; ?>",        
					showInLegend: true,  
					dataPoints: <?= json_encode($monthly_shelter_data_points_last_yr, JSON_NUMERIC_CHECK); ?>
				},
				
				{
					type: "spline", //change type to bar, line, area, pie, etc
					 name: "<?= date('Y'); ?>",        
					showInLegend: true,  
					dataPoints: <?= json_encode($monthly_shelter_data_points_this_yr, JSON_NUMERIC_CHECK); ?>
				},
				]
			});
		    chart.render();

   <?php } ?>


	function toggleDataSeries(e) {
		if(typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
			e.dataSeries.visible = false;
		}
		else {
			e.dataSeries.visible = true;            
		}
		chart.render();
	}

}

</script> 