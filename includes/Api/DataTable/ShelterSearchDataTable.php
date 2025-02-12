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
     * Fetches shelter search data for DataTable.
     *
     * Handles search term processing, filtering, and data formatting.
     * @return void Outputs JSON response for DataTable.
     */
    public static function sheltersSearchTableData() {
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
                        'check_box' => '<input type="checkbox" class="fnehd-checkbox2" data-fnehd-row-id="'. esc_attr($d) .'">',
                        'row_id' => esc_attr($d),
                    ];
                },
            ],
            [
                'db' => 'shelter_id',
                'dt' => 1,
                'formatter' => function ($d, $row) {
                    static $rowIndex = 0;
                    return ++$rowIndex;
                },
            ],
            ['db' => 'ref_id', 'dt' => 2],
            ['db' => 'payer',  'dt' => 3],
            ['db' => 'earner', 'dt' => 4],
            [
                'db' => 'creation_date',
                'dt' => 5,
                'formatter' => function ($d, $row) {
                    return date_i18n('jS M Y', strtotime($d));
                },
            ],
            [
                'db' => 'shelter_id',
                'dt' => 6,
                'formatter' => function ($d, $row) {
                    return [
                        'actions' => self::shelterSearchTableAction($d),
                    ];
                },
            ],
        ];

        // Retrieve and sanitize the search term
        $search_text = isset($_POST['extraData'][0]['search']) ? sanitize_text_field($_POST['extraData'][0]['search']) : '';
        $search_like = '%' . $wpdb->esc_like($search_text) . '%';

        // Build the WHERE clause for filtering
        $where = $wpdb->prepare("(earner LIKE %s OR payer LIKE %s)", $search_like, $search_like);

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
     * @param int $shelter_id The shelter ID.
     * @return string HTML string for the action buttons.
     */
    public static function shelterSearchTableAction($shelter_id) {
        ob_start();
        ?>
        <center>
            <a href="admin.php?page=fnehousing-view-shelter&shelter_id=<?= esc_attr($shelter_id); ?>" 
               class="btn fnehd-btn-behance btn-icon-text fnehd-btn-sm">
                <i class="fas fa-eye"></i> &nbsp;<?php esc_html_e('View', 'fnehousing'); ?>
            </a>
            <a href="#" 
               id="<?= esc_attr($shelter_id); ?>" 
               class="btn btn-danger btn-icon-text fnehd-btn-sm fnehd-delete-btn" 
               data-action="fnehd_del_shelter">
                <i class="fas fa-trash"></i> &nbsp;<?php esc_html_e('Delete', 'fnehousing'); ?>
            </a>
        </center>
        <?php
        return ob_get_clean();
    }
	
	
}
