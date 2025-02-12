<?php
/**
* The shelter Table.
* List all available shelters.
*
* @version   1.0.0
* @package   Fnehousing
*/

use Fnehousing\Api\DataTable\DataTableSkeleton;

defined('ABSPATH') || exit;


$table_id = 'fnehd-shelter-table';

$js_data = [
   'table-id' => 'fnehd-shelter-table',
   'ajax-action' => 'fnehd_shelters_datatable',
   'front-hidden-col' => 0,
   'shelter-url' => $shelter_url?? '',
   'order-col'   => 6
];

$actions = [
    'select_id' => 'fnehd-list-actions',
    'options' => [
        'fnehd-option1' => 'Select action',
        'fnehd-option2' => 'Delete',
    ],
    'buttons' => [
        'fnehd-option1' => [
            'class' => 'btn fnehd-tbl-action-btn',
            'icon' => 'check',
            'label' => __('Apply', 'fnehousing')
        ],
        'fnehd-option2' => [
            'class' => 'btn btn-danger fnehd-tbl-action-btn fnehd-mult-delete-btn',
            'icon' => 'trash',
            'label' => __('Delete', 'fnehousing'),
            'delete_action' => 'fnehd_del_shelters',
            'selected_class' => 'fnehd-selected'
        ],
    ],
];

$headers = [
    ['data-orderable' => 'false', 'content' => '<input type="checkbox" id="fnehd-select-all">'],
    ['content' => __('No.', 'fnehousing')],
    ['content' => __('Reference ID', 'fnehousing')],
    ['content' => __('Shelter Name', 'fnehousing')],
    ['content' => __('Email', 'fnehousing')],
	['content' => __('phone', 'fnehousing')],
	['content' => __('Availability', 'fnehousing')],
    ['content' => __('Created Date', 'fnehousing')],
    ['content' => __('Action', 'fnehousing')],
];


// Render DataTable
$data_table = new DataTableSkeleton($table_id, $headers, $actions, $js_data, $data_count);
echo $data_table->render();
 

