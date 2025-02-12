<?php
/*This template generates a searchable and 
 *filterable housing listing interface.
 *
 *@since  1.0.0
 *@packag FneHousing
 *
 */

defined('ABSPATH') || exit;

include_once FNEHD_PLUGIN_PATH . 'templates/frontend/user-routes.php';
include_once FNEHD_PLUGIN_PATH . 'templates/forms/listing-search-form.php'; //search bar 

$shelter_url = $routes['view_shelter'];
$data_count = 9;

$user_id = get_current_user_id();

$front_role = get_user_meta($user_id, 'front_role', true);

if($front_role === 'shelter-manager'){ ?>
	
<div class="card shadow-lg p-5">
	<h3 class="display4"> 
		<?= __('No Shelters Found', 'fnehousing'); ?>
	</h3>
</div>	
	
<?php } else {  ?>


<!-- Listings and Pagination -->
<span class="float-left">
    <h2><?php _e('Shelters', 'fnehousing'); ?></h2>
    <span id="fne-shelter-count" class="sm text-dark"><?php printf(__('%d results', 'fnehousing'), fnehd_shelter_count()); ?></span>
</span>

<div class="view-toggle flex justify-end mb-4">
    <span class="pt-3 mr-3">
        <i title="<?php _e('Available shelters', 'fnehousing'); ?>" class="fa-solid text-success fnehd-nav-icon fa-house-circle-check"></i>
        <span class="sm"><?= fnehd_shelter_availability_count('Available'); ?></span>&nbsp;&nbsp;
        <i title="<?php _e('Not Available', 'fnehousing'); ?>" class="fa-solid text-danger fnehd-nav-icon fa-house-circle-xmark"></i>
        <span class="sm"><?= fnehd_shelter_availability_count('Unavailable'); ?></span>
    </span>

	
	<select id="shelter-filter" class="form-select d-none d-md-flex pr-3 pl-4 border rounded">
		<option value="all"><?php _e('All Shelters', 'fnehousing'); ?></option>
		<option value="available"><?php _e('Available', 'fnehousing'); ?></option>
		<option value="unavailable"><?php _e('Unavailable', 'fnehousing'); ?></option>
	</select>

    <button id="fnehd-grid-view" class="view-btn active px-4 py-2 border bg-secondary text-white">
        <i class="fas fa-th"></i>
    </button>
    <button id="fnehd-list-view" class="view-btn px-4 py-2 border text-gray-700">
        <i class="fas fa-bars"></i>
    </button>
	<button id="fnehd-table-view" class="view-btn px-4 py-2 border text-gray-700">
        <i class="fas fa-table"></i>
    </button>
</div>

<div id="fnehd-listing-wrapper" data-shelter-url="<?= $shelter_url; ?>">
    <div id="fnehd-housing-grid" class="pt-5 mb-4 grid-view">
        <div class="listings-container">
            <!-- Listings will be dynamically added here -->
        </div>
    </div>
    <div id="pagination" class="pagination-container mt-5"></div>
</div>
	
<div id="shelter-table-view" class="table-responsive border p-5 mt-5 shadow-lg collapse">
	<?php include FNEHD_PLUGIN_PATH."templates/shelters/shelters-table.php"; ?>
</div> 

<?php } ?>