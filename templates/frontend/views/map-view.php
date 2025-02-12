<?php
/*This template generates a searchable map
 *
 *@since  1.0.0
 *@packag FneHousing
 *
 */

defined('ABSPATH') || exit;

include_once FNEHD_PLUGIN_PATH . 'templates/frontend/user-routes.php';

$shelter_url = $routes['view_shelter'];
?>
<div id="map" data-shelter-url="<?= $shelter_url; ?>" class="fnehd-shelter-map p-5 mt-5 shadow-lg" style="width: 100%; height: 650px;"></div>