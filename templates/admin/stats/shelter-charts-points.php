<?php
/**
 * Defines all Shelter stats chart points.
 *
 * @since      1.0.0
 * @package    Fnehousing
 */

defined('ABSPATH') || exit;

$stats = new StatsDBManager();
$totalShelterCount = $stats->getTotalShelterCount(); 

if ($IsStatsPage) {//Make Sure its a Stats Page.
	
	$top_beds_data_points = [];
	$top_referrals_data_points = [];
	
	$top_beds  = $stats->topShelterTopBeds(); //Top 10 Shelter in terms of Bed Capacity
	$top_referrals = $stats->topShelterReferrals(); //Top 10 Shelter in terms of referrals
	 
	foreach ($top_beds  as $data) {
		if($data['total_beds'] == 0) continue;
		$top_beds_data_points[] = ["y"=> $data['total_beds'], "label"=> ucfirst($data['shelter_name'])];//Add points to pie chart
	}
	
	foreach ($top_referrals  as $data) {
		if($data['referrals'] == 0) continue;
		$top_referrals_data_points[] = ["y"=> $data['referrals'], "label"=> ucfirst($data['shelter_name'])];
	}
	
	

	//Create Monthly Shelters Chart points
	$yr = date('Y');
	$last_yr = date('Y')-1;
	
	$months = [ "Jan"=>1, "Feb"=>2, "Mch"=>3, "Apr"=>4, "May"=>5, "Jun"=>6,"Jul"=>7, "Aug"=>8, "Sep"=>9, "Oct"=>10, "Nov"=>11, "Dec"=>12 ];
	
	$monthly_shelter_data_points_this_yr = [];
	$monthly_shelter_data_points_last_yr = [];
	
	foreach($months as $month => $month_num){
		$monthly_shelter_data_points_this_yr[] = ["y"=> $stats->monthlySheltersPerYr($month_num, $yr), "label"=> $month];
		$monthly_shelter_data_points_last_yr[] = ["y"=> $stats->monthlySheltersPerYr($month_num, $last_yr), "label"=> $month];
	}
		
}
   


	
	
	
    