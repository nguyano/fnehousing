<?php
/**
 * Shelter Search DataTable Class
 * Handles server-side processing for shelter search results in DataTable.
 *
 * @since      1.0.0
 * @package    Fnehousing
 */

namespace Fnehousing\Api\DataTable;

use Fnehousing\Api\DataTable\SSP;


defined('ABSPATH') || exit;


class ShelterSearchDataTable {
	
    /**
     * Fetches shelter data for DataTable.
     *
     * Handles server-side processing of shelter data and formats it for DataTable.
     * @return void Outputs JSON response for DataTable.
     */
    public static function sheltersSearchsTableData() {
        global $wpdb;

        // Table primary key
        $primaryKey = 'shelter_id';

        // Table name
        $table = $wpdb->prefix . "fnehousing_shelters";

        // Columns to retrieve and send back to DataTables
        $columns = [
            [
                'db' => 'shelter_id',
                'dt' => 0,
                'formatter' => function ($d, $row) {
                    return [
                        'check_box' => '<input type="checkbox" class="fnehd-checkbox" data-fnehd-row-id="' . esc_attr($d) . '">',
                        'row_id' => esc_attr($d),
                    ];
                },
            ],
            [
                'db' => 'shelter_id',
                'dt' => 1,
                'formatter' => function ($d, $row) {
                    static $rowIndex = 0; // Static variable to maintain row index
                    return ++$rowIndex; // Increment and return index
                },
            ],
            ['db' => 'ref_id', 'dt' => 2],
            ['db' => 'shelter_name', 'dt' => 3],
            ['db' => 'email', 'dt' => 4],
			['db' => 'phone', 'dt' => 5],
			[
                'db' => 'availability',
                'dt' => 6,
                'formatter' => function ($d, $row) {
					$class = $d === 'Available'? 'text-success' : 'text-danger';
                    return '<span class="'.$class.'">'.$d.'</span>';
                },
            ],
            [
                'db' => 'creation_date',
                'dt' => 7,
                'formatter' => function ($d, $row) {
                    return date_i18n('jS M Y', strtotime($d));
                },
            ],
            [
                'db' => 'shelter_id',
                'dt' => 8,
                'formatter' => function ($d, $row) {
                    return [
                        'actions' => self::sheltersSearchTableAction($d),
                    ];
                },
            ],
        ];


        // Retrieve and sanitize the search term
        $search_text = isset($_POST['extraData'][0]['search']) ? sanitize_text_field($_POST['extraData'][0]['search']) : '';
        $search_like = '%' . $wpdb->esc_like($search_text) . '%';

        // Build the WHERE clause for filtering
        $where = $wpdb->prepare("(shelter_name LIKE %s OR shelter_organization LIKE %s)", $search_like, $search_like);

        // Fetch data using the SSP class with the WHERE clause
        $response = SSP::complex($_POST, $table, $primaryKey, $columns, $where);

        // Highlight search terms in relevant columns
        if (!empty($response['data']) && is_array($response['data'])) {
            foreach ($response['data'] as &$row) {
                foreach ([2, 3, 4] as $columnIndex) { // Columns to highlight
                    if (!empty($row[$columnIndex])) {
                        $row[$columnIndex] = str_ireplace(
                            $search_text,
                            '<b>' . esc_html($search_text) . '</b>',
                            esc_html($row[$columnIndex])
                        );
                    }
                }
            }
        }

        // Add a custom message for when no records are found
        $response['no_records_message'] = sprintf(
            __('No records found for search phrase <b>%s</b>', 'fnehousing'),
            esc_html($search_text)
        );

        // Send JSON response
        wp_send_json($response);
    }
	

    /**
     * Generates action buttons for an shelter row.
     *
     * @param int    $shelter_id The shelter ID.
     * @return string HTML string for the action buttons.
     */
    public static function sheltersSearchTableAction($shelter_id) {
        ob_start();

        ?>
        <center>
			<a href="#" id="<?= esc_attr($shelter_id); ?>" class="fnehd-edit-shelter-btn btn btn-info btn-icon-text fnehd-btn-sm"
			   <?= (FNEHD_INTERACTION_MODE === "modal") ? 'data-toggle="modal" data-target="#fnehd-edit-shelter-modal"' : 'data-toggle="collapse" data-target="#fnehd-edit-shelter-form-dialog"'; ?>>
				<i class="fas fa-pencil"></i> &nbsp;<?php esc_html_e('Edit', 'fnehousing'); ?>
			</a>
			<a href="#" id="<?= esc_attr($shelter_id); ?>" class="btn btn-danger btn-icon-text fnehd-btn-sm fnehd-delete-btn" data-action="fnehd_del_shelter">
				<i class="fas fa-trash"></i> &nbsp;<?php esc_html_e('Delete', 'fnehousing'); ?>
			</a>
        </center>
        <?php

        return ob_get_clean();
    }
	
	
}
