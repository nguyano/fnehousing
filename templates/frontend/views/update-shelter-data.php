<?php
/* Quick Shelter Availability update
 * Single shelter update utility for shelter organization
 *
 * @since      1.0.0
 * @package    Fnehousing
 */


// Render the form
ob_start();
?>
<div class="card p-5 shadow-lg">
    <?php 
		if(!isset($_GET['shelter_id'])) { 
			echo '<label>
					<i class="fa-solid fa-circle-exclamation fa-xl text-danger"></i> '.__('Shelter ID not found, check the link', 'fnehousing').'
			</label>';
			return;
		}
		
		$shelter_id = sanitize_text_field($_GET['shelter_id']);
		
		if(!fnehd_shelter_exist('shelter_id', $shelter_id)) { 
			echo '<label>
					<i class="fa-solid fa-circle-exclamation fa-xl text-danger"></i> '.__('Shelter with associted ID not found, please request a new link', 'fnehousing').'
				</label>'; 
		} else { 
	?>
	<h3 class="pb-4">
		<?php 
			$shelter_name = fnehd_get_shelter_data($shelter_id)['shelter_name'];
			echo sprintf( __('Edit Shelter Data for <b>%s</b>', 'fnehousing'), ucwords($shelter_name) ); 
		?>
	</h3>
		<?php include_once FNEHD_PLUGIN_PATH . 'templates/forms/shelter-availability-update-form.php'; } ?>
</div>

<?php
echo ob_get_clean();
