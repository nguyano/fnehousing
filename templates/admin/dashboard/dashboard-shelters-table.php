<?php 
 /**
 * The Dashboard shelters Table.
 * List 5 recent shelters on the dashboard .
 * 
 * @since      1.0.0
 */ 

$output = "";

$shelters = $this->fetchRecentShelters();
$rowCnt = $this->getTotalShelterCount();


if ($rowCnt > 0) { 
  
	$output .='	 
	<div class="table-responsive">

		<table class="fnehd-table-numbering table" id="dashTable">
			<thead class="tbl-head">
				<tr>
					<th class="fnehd-th">'.__("No.", "fnehousing").'</th>
					<th class="fnehd-th">'.__("ID.", "fnehousing").'</th>
					<th class="fnehd-th">'.__("Shelter", "fnehousing").'</th>
					<th class="fnehd-th">'.__("Email", "fnehousing").'</th>
					<th class="fnehd-th">'.__("Created", "fnehousing").'</th>
					<th class="fnehd-th text-right"><center>'.__("Action", "fnehousing").'</center></th>
				</tr>
			</thead>
				  
			<tbody>';
					
			foreach ($shelters as $shelter) {

			   $output .="
		 
				<tr>
					<td style='font-weight: bold;'></td>
					<td>#".$shelter['ref_id']."</td>
					<td>".$shelter['shelter_name']."</td>
					<td>".$shelter['email']."</td>
					<td> <i class='fa fa-calendar-days'></i> ".date('Y-m-d', strtotime($shelter['creation_date']))."</td>
					   
				<td>
				<center>
				
					<a href='#' id='fnehdDropdownShelters' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'> <button class='btn btn-flat fnehd-btn-sm fnehd-btn-primary'><i class='fas fa-ellipsis-vertical'></i></button></a>
		  
					<div class='dropdown-menu dropdown-menu-right' aria-labelledby='fnehdDropdownShelters'>";
						 $output .='
						<a href="#" id="'.esc_attr($shelter['shelter_id']).'" class="fnehd-edit-shelter-btn dropdown-item"
						   '.(FNEHD_PLUGIN_INTERACTION_MODE === "modal" ? 'data-toggle="modal" data-target="#fnehd-edit-shelter-modal"' : 'data-toggle="collapse" data-target="#fnehd-edit-shelter-form-dialog"').'>
							<i class="text-success fas fa-pencil"></i> &nbsp; '.__('Edit', 'fnehousing').'
						</a>';
						 $output .="
						<a href='#' id='".$shelter['shelter_id']."' class='dropdown-item fnehd-delete-btn' data-action='fnehd_del_shelter'>
							<i class='text-danger fas fa-trash'></i> &nbsp; ".__('Delete', 'fnehousing')."
						</a>
					
					</div>
					
				</center>
				</td> ";     
		   
				}							 
		   
				$output .='	 
				</tr>
		  </tbody>
		</table>
	</div>';
	
echo $output;  
	
} else{
	   echo '<h4 class="text-light text-center mt-5">'.__("No records found", "fnehousing").'</h4>';
}