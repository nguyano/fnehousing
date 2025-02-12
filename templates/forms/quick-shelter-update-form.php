<form id="fnehd-quick-shelter-update-form">
	<div class="form-group">
		<label for="availability"><?php esc_html_e('Available?', 'fnehousing'); ?></label>
		<select name="availability" id="availability" required>
			<option value="yes"><?php esc_html_e('Yes', 'fnehousing'); ?></option>
			<option value="no"><?php esc_html_e('No', 'fnehousing'); ?></option>
		</select>
	</div>
	<div class="form-group">
		<label for="beds"><?php esc_html_e('Number of Beds', 'fnehousing'); ?></label>
		<input type="number" name="beds" id="beds" min="0" required>
	</div>
	<div class="form-group">
		<button type="submit"><?php esc_html_e('Update', 'fnehousing'); ?></button>
	</div>
</form>