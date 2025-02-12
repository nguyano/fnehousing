<?php 
 /**
 * The Users Table.
 * List all available Shelter Users.
 *
 * @since      1.0.0
 * @package    Fnehousing
 */ 


use Fnehousing\Api\DataTable\DataTableSkeleton;

defined('ABSPATH') || exit;


$data_count = $this->getTotalUserCount();

$table_id = 'fnehd-user-table';

$js_data = [
   'table-id' => 'fnehd-user-table',
   'ajax-action' => 'fnehd_users_datatable',
   'front-hidden-col' => 0,
   'order-col'   => 5
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
            'class' => 'btn btn-danger fnehd-tbl-action-btn fnehd-mult-delete-user-btn',
            'icon' => 'trash',
            'label' => __('Delete', 'fnehousing'),
            'delete_action' => 'fnehd_del_users',
            'selected_class' => 'fnehd-selected'
        ],
    ],
];

$headers = [
    ['data-orderable' => 'false', 'content' => '<input type="checkbox" id="fnehd-select-all">'],
    ['content' => __('No.', 'fnehousing')],
    ['content' => __('Image', 'fnehousing')],
    ['content' => __('Username', 'fnehousing')],
	['content' => __('Email', 'fnehousing')],
	['content' => __('First Name', 'fnehousing')],
    ['content' => __('Name', 'fnehousing')],
    ['content' => __('Created', 'fnehousing')],
    ['content' => __('Action', 'fnehousing')],
];


// Render DataTable
$data_table = new DataTableSkeleton($table_id, $headers, $actions, $js_data, $data_count);
echo $data_table->render();
 

