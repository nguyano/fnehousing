<section class="fnehd-content-section">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-12 p-5 mb-5">
			<style>
			  body{ background-image: url('<?= home_url(); ?>/wp-content/uploads/2025/01/anschutz.jpg'); background-size: cover;}
			</style>
			<h2 class="text-center display-4 text-light fnehd-hero-lg">
				<?= __('UCHealth FNE Housing Dashboard', 'fnehousing'); ?>
			 </h2><br>
			 <h4 class="text-center text-light fnehd-hero-sm"> 
				<?= __('A Product of UCHealth Forensic Nurse Examiner & VESPA (Elder Abuse) Programs', 'fnehousing'); ?>
			</h4>
			 <div class="pb-5"></div>
				<?php 
					if(isset($_GET['endpoint']) && $_GET['endpoint'] == 'user_signup'){ 
						include FNEHD_PLUGIN_PATH."templates/forms/user-signup-form.php";
					} else {
						include FNEHD_PLUGIN_PATH."templates/forms/login-form.php"; 
					}
				?>
			</div>
		</div>
	</div>
</section>
