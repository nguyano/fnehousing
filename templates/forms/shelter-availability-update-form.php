<form method="post" action="">
	<label for="availability"><?php esc_html_e('Availability:', 'fnehousing'); ?></label>
	<select id="availability" name="availability">
		<option value="1" <?php selected($data['availability'], true); ?>>
			<?php esc_html_e('Available', 'fnehousing'); ?>
		</option>
		<option value="0" <?php selected($data['availability'], false); ?>>
			<?php esc_html_e('Unavailable', 'fnehousing'); ?>
		</option>
	</select>
	<br><br>
	
	<label for="beds_available"><?php esc_html_e('Beds Available:', 'fnehousing'); ?></label>
	<input type="number" id="beds_available" name="beds_available" value="<?php echo esc_attr($data['beds_available']); ?>" min="0">
	<br><br>
	
	<button type="submit"><?php esc_html_e('Save Changes', 'fnehousing'); ?></button>
</form>