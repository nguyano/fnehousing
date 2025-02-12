<?php
/**
* Transaction Log Table.
* Shelter Transaction log.
* 
* @since      1.0.0
*/


use Fnehousing\Api\DataTable\DataTableSkeleton;

defined('ABSPATH') || exit;


$table_id = 'fnehd-log-table';

$js_data = [
   'table-id'    => 'fnehd-log-table',
   'ajax-action' => 'fnehd_log_datatable',
   'front-hidden-col' => '[0, 6]',
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
            'icon' => 'fas fa-check',
            'label' => __('Apply', 'fnehousing')
        ],
        'fnehd-option2' => [
            'class' => 'btn btn-danger fnehd-tbl-action-btn fnehd-mult-delete-btn',
            'icon' => 'fas fa-trash',
            'label' => __('Delete', 'fnehousing'),
            'delete_action' => 'fnehd_del_logs',
            'selected_class' => 'fnehd-selected'
        ],
    ],
];

$headers = [
    ['data-orderable' => 'false', 'content' => '<input type="checkbox" id="fnehd-select-all">'],
    ['content' => __('No.', 'fnehousing')],
    ['content' => __('Transaction ID', 'fnehousing')],
	['content' => __('Amount', 'fnehousing')],
    ['content' => __('Details', 'fnehousing')],
    ['content' => __('Date', 'fnehousing')],
    ['content' => __('Action', 'fnehousing')],
];

$data_count = $log_count;

// Render DataTable
$data_table = new DataTableSkeleton($table_id, $headers, $actions, $js_data, $data_count);
echo $data_table->render();
