<form id="EditShelterAvailabilityForm">
	<div class="form-group">
	    <input type="hidden" id="fnehd-quick-update-shelter-id-input" name="shelter_id" value="<?= $shelter_id?? ''; ?>">
		<input type="hidden" name="action" value="fnehd_update_shelter_availability">
		<?php wp_nonce_field('fnehd_shelter_availability_update_nonce', 'nonce'); ?>
		<label for="availability"><?php esc_html_e('Available?', 'fnehousing'); ?></label>
		<select class="form-control" name="availability" id="availability" required>
			<option value=""><?php esc_html_e('- Select Availability -', 'fnehousing'); ?></option>
			<option value="Available"><?php esc_html_e('Available', 'fnehousing'); ?></option>
			<option value="Unavailable"><?php esc_html_e('Unavailable', 'fnehousing'); ?></option>
		</select>
	</div>
	<div class="form-group">
		<label for="available_beds"><?php esc_html_e('Beds Available', 'fnehousing'); ?></label>
		<input type="number" class="form-control" name="available_beds" id="fnehd-quick-update-available-beds" min="0" required>
	</div>
	<div class="form-group">
		<button type="submit"><?php esc_html_e('Update', 'fnehousing'); ?></button>
	</div>
</form>