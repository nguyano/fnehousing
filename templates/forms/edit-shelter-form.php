<?php

/**
 * Add Shelter Form Template
 *
 * @version   1.0.0
 * @package   Fnehousing
 */

$form_type = 'edit';
$form_id = 'EditShelterForm';
$submit_text = __('Update Shelter', 'fnehousing');
$dialog_target = 'fnehd-edit-shelter-form-dialog';

if( FNEHD_SHELTER_FORM_STYLE === 'normal' ) {
	include FNEHD_PLUGIN_PATH . "templates/forms/shelter-form-normal.php";
} else {	 
	include FNEHD_PLUGIN_PATH . "templates/forms/shelter-form-tabed.php";
}
