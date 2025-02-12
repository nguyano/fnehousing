<?php
/* Quick Shelter Availability update
 * Updates a json file a single shelter
 *
 * @since      1.0.0
 * @package    Fnehousing
 */


$shelter_id = sanitize_text_field($_GET['shelter_id']);
$file_path = FNEHD_SHELTER_JSON_DIR . "$shelter_id.json";

if (!file_exists($file_path)) {
	echo '<p>Shelter data not found.</p>';
}

// Load the JSON data
$data = json_decode(file_get_contents($file_path), true);
if (!$data) {
	echo '<p>Invalid shelter data format.</p>';
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['availability'], $_POST['beds_available'])) {
	$data['availability'] = filter_var($_POST['availability'], FILTER_VALIDATE_BOOLEAN);
	$data['beds_available'] = intval($_POST['beds_available']);

	// Save the updated JSON
	file_put_contents($file_path, json_encode($data, JSON_PRETTY_PRINT));

	echo '<p style="color: green;">Shelter data updated successfully!</p>';
}

// Render the form
ob_start();
?>
<div class="card p-5 shadow-lg">
	<h3 class="pb-4">
		<?php echo sprintf(esc_html__('Edit Shelter Data for %s', 'fnehousing'), esc_html($shelter_id)); ?></h3>
   <?php include_once FNEHD_PLUGIN_PATH . 'templates/forms/shelter-availability-update-form.php'; ?>
</div>

<?php
echo ob_get_clean();
