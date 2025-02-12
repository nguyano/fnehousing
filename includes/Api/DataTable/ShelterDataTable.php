<?php
/**
 * Shelter DataTable Class
 * Handles server-side processing and rendering of  
 * the DataTable for shelter data.
 *
 * @version   1.0.0
 * @package   Fnehousing
 */

namespace Fnehousing\Api\DataTable;

use Fnehousing\Api\DataTable\SSP;


defined('ABSPATH') || exit;


class ShelterDataTable {
	
    /**
     * Fetches shelter data for DataTable.
     *
     * Handles server-side processing of shelter data and formats it for DataTable.
     * @return void Outputs JSON response for DataTable.
     */
    public static function sheltersTableData() {
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
                        'actions' => self::shelterTableAction($d),
                    ];
                },
            ],
        ];


         // Default WHERE condition
        $where = "1=1";
		
		if(isset($_POST['extraData'][0]['availability'])) {
			$availabiliity =  sanitize_text_field($_POST['extraData'][0]['availability']);
			$where = $wpdb->prepare("availability = %s", $availabiliity);
		}

        // Use the SSP class with the WHERE clause
        $response = SSP::complex($_POST, $table, $primaryKey, $columns, $where);

        // Send JSON response
        wp_send_json($response);
    }
	

    /**
     * Generates action buttons for an shelter row.
     *
     * @param int    $shelter_id The shelter ID.
     * @param string $ref_id    The reference ID for the shelter.
     * @return string HTML string for the action buttons.
     */
    public static function shelterTableAction($shelter_id) {
        ob_start();

        ?>
        <center>
            <?php if (fnehd_is_front_user()) : ?>
                <?php
                $shelter_url = isset($_POST['extraData'][0]['shelter_url']) ? esc_url($_POST['extraData'][0]['shelter_url']) : '';
                $shelter_url = add_query_arg(['shelter_id' => $shelter_id], $shelter_url);
                ?>
                <a href="<?= $shelter_url; ?>" class="btn fnehd-btn-secondary btn-icon-text fnehd-btn-sm">
                    <i class="fas fa-eye"></i> &nbsp;<?php esc_html_e('View', 'fnehousing'); ?>
                </a>
				<a href="#" id="<?= esc_attr($shelter_id); ?>" class="btn btn-info btn-icon-text fnehd-btn-sm"
				   <?= (FNEHD_PLUGIN_INTERACTION_MODE === "modal") ? 'data-toggle="modal" data-target="#fnehd-shelter-availability-update-modal"' : 'data-toggle="collapse" data-target="#fnehd-shelter-availability-update-form-dialog"'; ?>>
					<i class="fas fa-pencil"></i> &nbsp;<?php esc_html_e('Quick updtae', 'fnehousing'); ?>
				</a>
				
            <?php else : ?>
                <!--<a href="admin.php?page=fnehousing-view-shelter&shelter_id=<?= esc_attr($shelter_id); ?>" class="btn fnehd-btn-behance btn-icon-text fnehd-btn-sm">
                    <i class="fas fa-eye"></i> &nbsp;<?php esc_html_e('View', 'fnehousing'); ?>
                </a>-->
				
				<a href="#" id="<?= esc_attr($shelter_id); ?>" class="fnehd-edit-shelter-btn btn btn-info btn-icon-text fnehd-btn-sm"
				   <?= (FNEHD_PLUGIN_INTERACTION_MODE === "modal") ? 'data-toggle="modal" data-target="#fnehd-edit-shelter-modal"' : 'data-toggle="collapse" data-target="#fnehd-edit-shelter-form-dialog"'; ?>>
					<i class="fas fa-pencil"></i> &nbsp;<?php esc_html_e('Edit', 'fnehousing'); ?>
				</a>
                <a href="#" id="<?= esc_attr($shelter_id); ?>" class="btn btn-danger btn-icon-text fnehd-btn-sm fnehd-delete-btn" data-action="fnehd_del_shelter">
                    <i class="fas fa-trash"></i> &nbsp;<?php esc_html_e('Delete', 'fnehousing'); ?>
                </a>
            <?php endif; ?>
        </center>
        <?php

        return ob_get_clean();
    }
	
	
}
