<?Php
    		
	$bio_data1 = [
		__('First Name', 'fnehousing') => $user_data["first_name"],
		__('Last Name', 'fnehousing')  => $user_data["last_name"],
		__('Main Role', 'fnehousing') => ($shelter_count > $earning_count? 'Payer' : 'Earner')
	];
	
		
	$bio_data2 = [
	    __('Phone', 'fnehousing')   => $user_data["phone"],
		__('Country', 'fnehousing') => $user_data["country"],
		__('Email', 'fnehousing')   => $user_data["user_email"]
	];
	
	$approved_swal = "Swal.fire('".__('Complete Profile', 'fnehousing')."', '".__('User Profile 100% Complete', 'fnehousing')."', 'success');";
	$pending_swal = "Swal.fire('".__('Incomplete Profile', 'fnehousing')."', '".__('Please Complete Your Profile!', 'fnehousing')."', 'warning');";
	
?>
<div class="section fnehd-user-dashboard gray-bg text-light">
	<div class="row p-5">
		<div class="col-md-12">
			<div class="card shadow-lg row <?= wp_is_mobile()? 'p-3' : 'p-5';  ?> align-items-center flex-row-reverse">
				<div class="col-lg-7">
					<div class="about-text go-to">
						<h3 class="fnehd-label"><?= $user_data["first_name"].' '.$user_data["last_name"]; ?> 
							<span class="h3">
								<button type="button"  id="<?= $user_data["ID"]; ?>"
									<?php if(FNEHD_PLUGIN_INTERACTION_MODE == "modal"){ ?>
										data-toggle="modal" data-target="#fnehd-edit-user-modal"
									<?php } else { ?>
										data-toggle="collapse" data-target="#fnehd-edit-user-form-dialog"
									<?php } ?>
									class="btn btn-sm btn-round btn-success fnehd-user-edit-btn mt-0"> 
									<i class="fas fa-user-pen"></i> <?= __("edit", "fnehousing"); ?>
								</button>
							</span>
						</h3>
						<p class="lead text-secondary">
							<span class="badge badge-secondary">
								<?= $shelter_count > $earning_count? 'Shelter Payer' : 
								           ($shelter_count == 0 && $earning_count == 0 ? 'New User' : 'Shelter Earner') ?>
							</span> <b>::</b> 
							<a href="<?= (empty($user_data["user_url"])? '#' : $user_data["user_url"]);  ?>">
								<?php  echo $user_data["affiliation"]; ?>
							</a> <b>::</b> 
							<small><i class="fa fa-clock"></i> <?= $user_data["user_registered"]; ?> </small>
						</p>

						<p><?= $user_data["bio"]; ?></p><hr/>
						<div class="row about-list">
							<div class="col-md-6">
								<?php foreach($bio_data1 as $tle => $val){  ?>
									<div class="media">
										<label><?= $tle; ?></label>
										<p><?= $val; ?></p>
									</div>
							  <?php } ?>	 
							</div>
							<div class="col-md-6">
								<?php foreach($bio_data2 as $tle => $val) { ?>
									<div class="media">
										<label><?= $tle; ?></label>
										<p><?= $val; ?></p>
									</div>
							   <?php } ?>	 
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-5">
					<div class="about-avatar">
						<?= fnehd_image($user_data["user_image"], '350', 'fnehd-rounded'); ?>
					</div>
				</div>
			</div>
		</div>	
	</div>
	
</div>