<?php

/**
 * Add Shelter Form Template
 *
 * @version   1.0.0
 * @package   Fnehousing
 */

$form_type = 'add';
$form_id = 'AddShelterForm';
$submit_text = __('Add Shelter', 'fnehousing');
$dialog_target = 'fnehd-add-shelter-form-dialog';

if( FNEHD_SHELTER_FORM_STYLE === 'normal' ) {
	include FNEHD_PLUGIN_PATH . "templates/forms/shelter-form-normal.php";
} else {	 
	include FNEHD_PLUGIN_PATH . "templates/forms/shelter-form-tabed.php";
}
