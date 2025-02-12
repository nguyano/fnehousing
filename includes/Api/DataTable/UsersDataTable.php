<?php
/**
 * User DataTable Class
 * Handles the server-side processing and rendering of the DataTable for user data.
 *
 * @version   1.0.0
 * @package   Fnehousing
 */

namespace Fnehousing\Api\DataTable;

use Fnehousing\Api\DataTable\SSP;

defined('ABSPATH') || exit;


class UsersDataTable {
    /**
     * Fetches user data for DataTable.
     *
     * Handles server-side processing of user data and formats it for DataTable.
     * @return void Outputs JSON response for DataTable.
     */
    public static function usersTableData() {
        global $wpdb;

        // Table primary key
        $primaryKey = 'ID';

        // Table name
        $table = $wpdb->prefix . "fnehousing_users";

        // Columns to retrieve and send back to DataTables
        $columns = [
            [
                'db' => 'ID',
                'dt' => 0,
                'formatter' => function ($d, $row) {
                    return [
                        'check_box' => '<input type="checkbox" class="fnehd-checkbox" data-fnehd-row-id="' . esc_attr($d) . '">',
                        'row_id' => esc_attr($d),
                    ];
                },
            ],
            [
                'db' => 'ID',
                'dt' => 1,
                'formatter' => function ($d, $row) {
                    static $rowIndex = 0; // Static variable to maintain row index
                    return ++$rowIndex; // Increment and return index
                },
            ],
            [
                'db' => 'user_image',
                'dt' => 2,
                'formatter' => function ($d, $row) {
                    return [
                        'user_img' => fnehd_image($d, 30, 'rounded'),
                    ];
                },
            ],
            ['db' => 'user_login', 'dt' => 3],
			['db' => 'user_email', 'dt' => 4],
			['db' => 'first_name', 'dt' => 5],
            [
                'db' => 'last_name',
                'dt' => 6,
                'formatter' => function ($d, $row) {
                    return $row['first_name'].' '.$d;
                },
            ],
            [
                'db' => 'user_registered',
                'dt' => 7,
                'formatter' => function ($d, $row) {
                    return date_i18n('jS M Y', strtotime($d));
                },
            ],
            [
                'db' => 'ID',
                'dt' => 8,
                'formatter' => function ($d, $row) {
                    return [
                        'actions' => self::userTableAction($d),
                    ];
                },
            ],
        ];

        // Use the SSP class for server-side processing
        $response = SSP::getFnehdUsers($_POST, $columns);

        // Send JSON response
        wp_send_json($response);
    }

    /**
     * Generates action buttons for a user row.
     *
     * @param int $user_id The user ID.
     * @return string HTML string for the action buttons.
     */
    public static function userTableAction($user_id) {
        ob_start();
        ?>
        <center>
            <a href="admin.php?page=fnehousing-user-profile&user_id=<?= esc_attr($user_id); ?>" id="<?= esc_attr($user_id); ?>" 
               class="btn btn-info btn-icon-text fnehd-btn-sm" 
               title="<?php esc_attr_e('View User', 'fnehousing'); ?>">
                <i class="fas fa-user"></i>
            </a>
            <a href="#" id="fnehdDropdownUser" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <button class="btn btn-flat fnehd-btn-sm fnehd-btn-primary">
                    <i class="fas fa-ellipsis-vertical"></i>
                </button>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="fnehdDropdownUser">
				<a href="#" id="<?= $user_id; ?>" class="dropdown-item fnehd-user-edit-btn fnehd-rounded" 
				data-toggle="<?= FNEHD_PLUGIN_INTERACTION_MODE === 'modal' ? 'modal' : 'collapse'; ?>" 
				data-target="<?=FNEHD_PLUGIN_INTERACTION_MODE  === 'modal' ? '#fnehd-edit-user-modal' : '#fnehd-edit-user-form-dialog'; ?>">
                     <i class="text-info fas fa-user-pen"></i> &nbsp; <?php esc_html_e('Quick Edit', 'fnehousing'); ?>
                </a>
					
                <a href="#" id="<?= esc_attr($user_id); ?>" 
                   class="dropdown-item fnehd-delete-user-btn fnehd-rounded">
                    <i class="text-danger fas fa-trash"></i> &nbsp; <?php esc_html_e('Delete', 'fnehousing'); ?>
                </a>
            </div>
        </center>
        <?php  return ob_get_clean();
    }


}
