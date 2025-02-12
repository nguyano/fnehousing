<?php
/**
 * DataTable Actions Class
 *
 * Registers AJAX actions for handling various DataTables in the Fnehousing plugin.
 * Dynamically resolves and invokes the appropriate class methods for each action.
 *
 * @since      1.0.0
 * @package    Fnehousing
 */

namespace Fnehousing\Api\DataTable;

defined('ABSPATH') || exit;


class DataTableActions {

    /**
     * List of actions with their corresponding handlers.
     * Key: Action name, Value: ['class' => ClassName, 'method' => MethodName]
     */
    private $actions = [
        'fnehd_shelters_datatable'      => ['class' => 'ShelterDataTable', 'method' => 'sheltersTableData'],
        'fnehd_log_datatable'           => ['class' => 'LogDataTable', 'method' => 'logTableData'],
		'fnehd_shelter_search_datatable'=> ['class' => 'ShelterSearchDataTable', 'method' => 'sheltersSearchTableData'],
		'fnehd_users_datatable'         => ['class' => 'UsersDataTable', 'method' => 'usersTableData'],
		'fnehd_dbbackup_datatable'      => ['class' => 'DBBackupDataTable', 'method' => 'dbBackupTableData'],
    ];

    /**
     * Register hooks for all defined actions.
     */
    public function register() {
        foreach ($this->actions as $action => $handler) {
            add_action("wp_ajax_$action", [$this, 'handleAction']);
        }
    }

    /**
     * Handles AJAX actions dynamically by resolving the appropriate class and method.
     */
    public function handleAction() {
        $current_action = current_action();
        $action_key = str_replace('wp_ajax_', '', $current_action);

        if (isset($this->actions[$action_key])) {
            $handler = $this->actions[$action_key];
            $class = "Fnehousing\\Api\\DataTable\\{$handler['class']}";

            if (class_exists($class) && method_exists($class, $handler['method'])) {
                call_user_func([$class, $handler['method']]);
            } else {
                wp_send_json_error(['message' => __('Invalid Data Table configuration', 'fnehousing')], 400);
            }
        } else {
            wp_send_json_error(['message' => __('Invalid Data Table action', 'fnehousing')], 400);
        }
    }
}
