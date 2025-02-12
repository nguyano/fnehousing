<?php

/**
 * Frontend collapseable dialogs
 * 
 * @Since   1.0.0
 * @package Fnehousing
 */
 
 defined('ABSPATH') || exit;


$dialogs = [
		
     [
		'id' => 'fnehd-add-shelter-form-dialog',
		'data_id' => '',
		'header' => '',
		'title' => __("Add Shelter", "fnehousing"),
		'callback' => 'add-shelter-form.php',
		'type' => 'add-form'
	 ],
	 [
		'id'       => 'nehd-shelter-availability-update-form-dialog',
		'data_id'  => '',
		'header'   => '',
		'title'    => __("Update Shelter Availability", "fnehousing"),
		'callback' => 'quick-shelter-update-form.php',
		'type'     => 'edit-form'
	],
	[
		'id'       => 'fnehd-edit-shelter-form-dialog',
		'data_id'  => '',
		'header'   => '',
		'title'    => __("Update Shelter", "fnehousing"),
		'callback' => 'edit-shelter-form.php',
		'type'     => 'edit-form'
	]
   
];

fnehd_callapsable_dialogs($dialogs);