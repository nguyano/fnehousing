<?php

/**
* Core Plugin wide functions
* Provide helper functionalities
* @since      1.0.0
* @package    Fnehousing
*/

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

defined('ABSPATH') || exit;

/**
 * Verify user permissions.
 *
 * @param string $capability The capability to check.
 */
function fnehd_verify_permissions($capability) {
	$capability = fnehd_is_front_user()? 'fnehousing_user' : $capability;
	if (!current_user_can($capability)) {
		wp_send_json_error(__('You do not have the required permissions.', 'fnehousing'), 403);
	}
}

 /**
 * Validates the nonce for AJAX requests.
 *
 * @param string $nonce_action Action for nonce validation.
 * @param string $nonce_field  Nonce field to validate.
 */
function fnehd_validate_ajax_nonce($nonce_action, $nonce_field) {
	if (!check_ajax_referer($nonce_action, $nonce_field, false)) {
		wp_send_json_error(['message' => __('Invalid nonce. Try reloading the page.', 'fnehousing')]);
	}
}

/**
 * Generate a QR Code.
 *
 * @param string $data Data to encode in the QR code.
 * @param int    $width Width of the QR code.
 * @param int    $height Height of the QR code.
 * @return string HTML string for the QR code.
 */
function fnehd_generate_qrcode($data, $width = 300, $height = 300) {
    $options = new QROptions([
        'eccLevel' => QRCode::ECC_L,
        'outputType' => QRCode::OUTPUT_MARKUP_SVG,
        'version' => 5,
    ]);

    $qrcode = (new QRCode($options))->render($data);
    return sprintf('<img src="%s" alt="QR Code" width="%d" height="%d" />', $qrcode, intval($width), intval($height));
}


/**
 * Deregister unwanted scripts.
 *
 * @param array $handles List of script handles to remove.
 */
function fnehd_deregister_scripts(array $handles) {
    foreach ($handles as $handle) {
        wp_dequeue_script($handle);
        wp_deregister_script($handle);
    }
}

/**
 * Deregister unwanted styles.
 *
 * @param array $handles List of style handles to remove.
 */
function fnehd_deregister_styles(array $handles) {
    foreach ($handles as $handle) {
        wp_dequeue_style($handle);
        wp_deregister_style($handle);
    }
}

/**
 * Retrieve the title of a specific section in WordPress settings.
 *
 * This function extracts the section title for a given settings page and section ID
 * from the global `$wp_settings_sections` array. It parses the title to retrieve the 
 * text content inside `<h4>` tags.
 *
 * @param string $page  The slug of the settings page.
 * @param string $section_id The ID of the section.
 * 
 * @return string|null  The extracted section title, or null if not found.
 */
function get_section_title($page, $section_id) {
    global $wp_settings_sections;

    // Check if the page and section exist
    if (isset($wp_settings_sections[$page][$section_id])) {
        $title = $wp_settings_sections[$page][$section_id]['title'];
		preg_match('/<h4>(.*?)<\/h4>/', $title, $matches);
		
		return $matches[1];
    }
    return null;
}


/**
 * Generate a random unique ID.
 *
 * @param int $length Length of the generated ID.
 * @return string Random unique ID.
 */
function fnehd_unique_id($length = 12) {
    $characters = defined('FNEHD_SHELTER_REF_CHARACTERS') ? FNEHD_SHELTER_REF_CHARACTERS : '0123456789';
    $ref_id = '';
    for ($i = 0; $i < intval($length); $i++) {
        $ref_id .= $characters[random_int(0, strlen($characters) - 1)];
    }
    return $ref_id;
}



/**
 * Check if string is variable.
 *
 * @param string $url URL or string to be checked.
 * @return bool.
 */
function fnehd_is_valid_url($url) {
    return filter_var($url, FILTER_VALIDATE_URL) !== false;
}


/**
 * Handle file upload securely.
 *
 * @param string $file File input name.
 * @return string|WP_Error Uploaded file URL or error message.
 */
function fnehd_uploader($file, $file_size_limit = 5, array $allowed_types = ['image/jpeg','image/png','application/pdf']) {
    // Check if the file input is set and has a valid name
    if (!empty($_FILES[$file]['name'])) {
        // Include WordPress file handling functions
        require_once ABSPATH . 'wp-admin/includes/file.php';
        
        // Sanitize file input to prevent potential security risks
        $uploadedfile = $_FILES[$file];
        $upload_overrides = [
            'test_form' => false, // Prevent form checking to avoid issues with AJAX submissions
        ];

        // Check file size
        if ($uploadedfile['size'] > $file_size_limit*1024*1024) {
			$error = sprintf(__('The uploaded file exceeds the size limit of %s megabytes.', 'fnehousing'), $file_size_limit);
			return new \WP_Error('file_size_error', $error);
        }

        // Check file type
        $file_type = wp_check_filetype($uploadedfile['name']);
        if (!in_array($file_type['type'], $allowed_types)) {
			$allowed = [];
			foreach($allowed_types as $type){
				switch($type){
					case 'image/jpeg':
					$allowed[] = 'Jpeg';
					break;
					case 'image/png':
					$allowed[] = 'Png';
					break;
					case 'application/pdf':
					$allowed[] = 'Pdf';
					break;
					case 'application/msword':
					$allowed[] = 'Word';
					break;
					case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
					$allowed[] = 'Excel(xls)';
					break;
					case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
					$allowed[] = 'Excel(xlsx)';
					break;
				}
			}
			if (count($allowed) > 1) {
				$last_item = array_pop($allowed); // Remove the last element
				$allowed_types = implode(', ', $allowed) . ' and ' . $last_item; // Concatenate the rest with 'and'
			} else {
				$allowed_types = $allowed[0]; // If there's only one element
			}
			$error = sprintf(__('Invalid file type. Only %s files are allowed.', 'fnehousing'), $allowed_types);
			return new \WP_Error('file_type_error', $error);
        }

        // Handle the file upload
        $movefile = wp_handle_upload($uploadedfile, $upload_overrides);

        // Check if the file upload was successful
        if ($movefile && !isset($movefile['error'])) {
            // Return the file URL if upload is successful
            return esc_url($movefile['url']);
        } else {
            // Return the error message if upload failed
            return new \WP_Error('upload_error', esc_html($movefile['error']));
        }
    }
}



/**
 * Check if a user has the "fnehousing_user" role.
 *
 * @param int $user_id User ID.
 * @return bool True if the user has the role, false otherwise.
 */
function fnehd_is_front_user($user_id = null) {
    $user_id = $user_id ?: get_current_user_id();
    $user = get_userdata($user_id);
    return $user && in_array('fnehousing_user', (array) $user->roles, true);
}
	

/**
 * Display an image with fallback.
 *
 * @param string $src Image URL.
 * @param int    $size Image width.
 * @param string $class Optional CSS classes.
 * @return string HTML string for the image.
 */
function fnehd_image($src, $size, $class = '') {
    $default = FNEHD_PLUGIN_URL . 'assets/img/placeholder.jpg';
    $img_src = !empty($src) ? esc_url($src) : esc_url($default);
    return sprintf('<img src="%s" class="%s" width="%d" alt="" />', $img_src, esc_attr($class), intval($size));
}
	


/**
 * Sanitize and prepare form data for processing.
 *
 * @param array $fields List of form fields to process.
 * @return array Sanitized form data.
 */
function fnehd_sanitize_form_data(array $fields) {
    $data = [];
    foreach ($fields as $key) {
        if (isset($_POST[$key])) {
            $value = wp_unslash($_POST[$key]);
            switch ($key) {
                case 'email':
                    $data[$key] = sanitize_email($value);
                    break;
                case 'username':
                    $data[$key] = sanitize_user($value);
                    break;
                case 'redirect':
                    $data[$key] = esc_url_raw($value);
                    break;
                default:
                    $data[$key] = sanitize_text_field($value);
            }
        } else {
            $data[$key] = '';
        }
    }
    return $data;
}


/**
 * Sanitize and prepare multiple select form data for processing.
 *
 * @param array $fields List of multiple selection form fields to process.
 * @return array Sanitized form data.
 */
function fnehd_sanitize_mult_select_form_data(array $multiple_select_fields) {
	$data = [];
	foreach ($multiple_select_fields as $field) {
		// Ensure the field exists in $_POST and is an array
		if (isset($_POST[$field]) && is_array($_POST[$field])) {
			// Sanitize each value before imploding
			$sanitized_values = array_map('sanitize_text_field', $_POST[$field]);
			$data[$field] = implode(",", $sanitized_values);
		} else {
			$data[$field] = ""; // Default to an empty string if no selection
		}
	}
	return $data;
}



/**
 * Get sanitized request data ($_GET).
 *
 * @param array $fields List of fields to process.
 * @return array Sanitized request data.
 */
function fnehd_get_request_data(array $fields) {
    $data = [];
    foreach ($fields as $key) {
        $data[$key] = isset($_GET[$key]) ? sanitize_text_field(wp_unslash($_GET[$key])) : '';
    }
    return $data;
}

	
/**
 * Validate user login/signup form data.
 *
 * @param array $data Form data to validate.
 * @return WP_Error Validation errors, if any.
 */
function fnehd_validate_form($data) {
    $error = new \WP_Error();

    if (empty($data['user_login'])) {
        $error->add('empty_username', __('Username is required.', 'fnehousing'));
    }
    if (empty($data['user_pass'])) {
        $error->add('empty_password', __('Password is required.', 'fnehousing'));
    }
    if (!empty($_POST['form_type']) && $_POST['form_type'] === 'signup form') {
        if (empty($data['user_email']) || !is_email($data['user_email'])) {
            $error->add('invalid_email', __('Valid email is required.', 'fnehousing'));
        }
        if (empty($data['confirm_pass']) || $data['user_pass'] !== $data['confirm_pass']) {
            $error->add('passwords_do_not_match', __('Passwords do not match.', 'fnehousing'));
        }
    }

    return apply_filters('fnehd_validate_form_data', $error, $data);
}


//Return redirection url. Filters redirection URL to redirect to after user is logged in.
function fnehd_redirect_url( $user, $redirect ) {
	$redirect_url = apply_filters( 'fnehd_after_login_redirect_url', $redirect, $user );
	$redirect_url = wp_validate_redirect( $redirect_url, $redirect );
	return $redirect_url;
}


//Minify Count
function fnehd_minify_count($count) {
	if(is_numeric($count)) {
		if ( $count > 999 && $count <= 999999 ){ 
		   $output = round($count/1000, 1).'K'; 
		} elseif ( $count > 999999 ) { 
			$output = round($count/1000000, 1).'M'; 
		}  elseif ( $count > 999999999 ) { 
			$output = round($count/1000000000, 1).'B';
		}  else { 
			$output = $count; 
		} 
	}else {
		$output = 0;
	}
	return $output;
}

//dsiplay chart and table stats
function fnehd_stat_chart_tbl($col_class, $color, $icon, $title, $subtitle, $type, $count, $id, $box_ht) {
	ob_start();
	?>
	<div class="<?= esc_attr($col_class); ?>">
		<div class="card card-chart">
			<div class="card-header card-header-icon card-header-<?= esc_attr($color); ?>">
				<div class="card-icon">
					<?php if (strpos($icon, "fa-") !== false) { ?>
						<i class="<?= esc_attr($icon); ?>"></i>
					<?php } else { ?>
						<i class="fas fa-<?= esc_attr($icon); ?>"></i>
					<?php } ?>
				</div>
				<h4 class="card-title mt-3 mb-2 ms-3">
					<?= $title; ?>
					<?php if (!empty($subtitle)) : ?>
						<small>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $subtitle; ?></small>
					<?php endif; ?>
				</h4>
			</div>
			<div class="card-body p-3">
				<?php if ($type === 'table') : ?>
					<div id="<?= esc_attr($id); ?>">
						<?= fnehd_loader(__('Loading...', 'fnehousing')); ?>
					</div>
				<?php elseif ($type === 'chart') : ?>
					<?php if ($count > 0) : ?>
						<div style="height: <?= intval($box_ht); ?>px;" id="<?= esc_attr($id); ?>" class="ct-chart">
							<?= fnehd_loader(__('Loading...', 'fnehousing')); ?>
						</div>
					<?php else : ?>
						<h4 style="height: <?= intval($box_ht); ?>px;" class="text-light text-center pt-5">
							<?= esc_html__('Not enough data to draw chart', 'fnehousing'); ?>
						</h4>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<?php
	return ob_get_clean();
}




/**
 * Display chart and table stats with tabs.
 *
 * @param array $data Data array containing configuration for charts, tables, and tabs.
 * @return string Sanitized HTML output for the tabs, charts, and tables.
 */
function fnehd_tab_stat_chart_tbl($data) {
    // Validate required keys in $data
    $required_keys = ['col_class', 'id', 'color', 'icon', 'title', 'tabs', 'box_ht'];
    foreach ($required_keys as $key) {
        if (!isset($data[$key])) {
            return ''; // Exit early if required data is missing.
        }
    }

    ob_start(); // Start output buffering
    ?>
    <div class="<?= esc_attr($data['col_class']); ?>" id="<?= esc_attr($data['id']); ?>">
        <div class="card card-chart">
            <div class="card-header card-header-icon card-header-<?= esc_attr($data['color']); ?>">
                <div class="card-icon">
                    <?php if (strpos($data['icon'], "fa-") !== false) { ?>
						<i class="<?= esc_attr($data['icon']); ?>"></i>
					<?php } else { ?>
						<i class="fas fa-<?= esc_attr($data['icon']); ?>"></i>
					<?php } ?>
                </div>
                <h4 class="card-title mt-3 mb-2 ms-3">
                    <?= esc_html($data['title']); ?>
                    <?php if (!empty($data['subtitle'])) : ?>
                        <small>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= esc_html($data['subtitle']); ?></small>
                    <?php endif; ?>
                </h4>
            </div>
            <div class="card-body p-3">
                <div class="col-xs-12" id="fnehd-horizontal-tabs">
                    <nav>
                        <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                            <?php foreach ($data['tabs'] as $tab) : ?>
                                <?php
                                if (!isset($tab['id'], $tab['title'], $tab['active'], $tab['selected'])) {
                                    continue;
                                }
                                $active_class = !empty($tab['active']) ? ' show active' : '';
                                ?>
                                <a class="text-light nav-item nav-link<?= esc_attr($active_class); ?>"
                                   data-toggle="tab"
                                   href="#<?= esc_attr($tab['id']); ?>-tab"
                                   role="tab"
                                   aria-selected="<?= esc_attr($tab['selected']); ?>">
                                    <?= esc_html($tab['title']); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </nav>
                    <div class="tab-content py-3 px-3 px-sm-0">
                        <?php foreach ($data['tabs'] as $tab) : ?>
                            <?php
                            if (!isset($tab['id'], $tab['type'], $tab['active'], $tab['count'])) {
                                continue;
                            }
                            $active_class = !empty($tab['active']) ? ' show active' : '';
                            ?>
                            <div class="tab-pane fade<?= esc_attr($active_class); ?>"
                                 id="<?= esc_attr($tab['id']); ?>-tab"
                                 role="tabpanel">
                                <?php if ($tab['type'] === 'table') : ?>
                                    <div id="<?= esc_attr($tab['id']); ?>">
                                        <?= fnehd_loader(__('Loading...', 'fnehousing')); ?>
                                    </div>
                                <?php elseif ($tab['type'] === 'chart') : ?>
                                    <?php if ((int)$tab['count'] > 0) : ?>
                                        <div style="height: <?= esc_attr($data['box_ht']); ?>;"
                                             id="<?= esc_attr($tab['id']); ?>"
                                             class="ct-chart">
                                            <?= fnehd_loader(__('Loading...', 'fnehousing')); ?>
                                        </div>
                                    <?php else : ?>
                                        <h4 style="height: <?= esc_attr($data['box_ht']); ?>;"
                                            class="text-light text-center pt-5">
                                            <?php esc_html_e('Not enough data to draw chart', 'fnehousing'); ?>
                                        </h4>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    return trim(ob_get_clean()); // Return the buffered content.
}



/**
 * Generate a statistics box.
 *
 * @param string $id        The ID of the stat box.
 * @param int    $count     The numerical value to display.
 * @param string $sub       Singular label for the stat (e.g., "Transaction").
 * @param string $sub_s     Plural label for the stat (e.g., "Transactions").
 * @param string $col_class CSS class for the column container.
 * @param string $color     Color theme for the stat box.
 * @param string $icon      Font Awesome icon class for the box.
 * @param string $title     Title of the stat box.
 *
 * @return string Sanitized HTML output for the stat box.
 */
function fnehd_stat_box($id, $count, $sub, $sub_s, $col_class, $color, $icon, $title) {
    $label = ($count == 1) ? $sub : $sub_s;

    ob_start(); // Start output buffering
    ?>
    <div class="<?= esc_attr($col_class); ?>">
        <div class="card card-chart mb-2">
            <div class="card-header card-header-<?= esc_attr($color); ?> card-header-icon">
                <div class="card-icon fnehd-rounded">
                    <i class="fas fa-<?= esc_attr($icon); ?>"></i>
                </div>
                <div class="text-end pt-1">
                    <p class="card-category text-right text-sm mb-0 text-capitalize">
                        <?= esc_html($title); ?>
                    </p>
                    <h4 class="text-right fnehd-stats-text mb-4">
                        <?= esc_html(fnehd_minify_count($count)); ?>
                    </h4>
                </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-3">
                <p class="mb-0">
                    <span class="text-success text-sm font-weight-bolder">
                        <?= esc_html(fnehd_minify_count($count)); ?>
                    </span>
                    <span class="text-light text-dark"><?= esc_html($label); ?></span>
                </p>
            </div>
        </div>
    </div>
    <?php
    return trim(ob_get_clean()); 
}


function fnehd_merge_tags_table() {
    $columns = [
        ["Shelter Ref ID", "{ref-id}"],
        ["Escow Status", "{status}"],
        ["Shelter Earner", "{earner}"],
        ["Shelter Amount", "{amount}"],
        ["Shelter Payer", "{payer}"],
        ["Shelter Title", "{title}"],
        ["Shelter Details", "{details}"],
        ["Current Year", "{current-year}"],
        ["Website Title", "{site-title}"],
        ["Company Address", "{company-address}"],
    ];

    ob_start(); ?>

    <div id="FnehdMergeTags" class="text-gray">
        <span class="small">
            <?= __("Use the following merge tags in the form fields if necessary", "fnehousing"); ?>
        </span>
        <table class="table-responsive fnehd-tbl">
            <tr class="fnehd-tr">
                <?php foreach ($columns as $column): ?>
                    <th class="fnehd-th fnehd-th-sm">
                        <?= __( $column[0], "fnehousing" ); ?>
                    </th>
                <?php endforeach; ?>
            </tr>
            <tr class="fnehd-tr">
                <?php foreach ($columns as $column): ?>
                    <td class="fnehd-td">
                        <?= $column[1]; ?>
                    </td>
                <?php endforeach; ?>
            </tr>
        </table>
    </div>

    <?php
    return ob_get_clean();
}




//Ajax loader
function fnehd_ajax_loader($text = ""){
	return '<div id="fnehd-loader" style="display:none;">
				<span class="spinner-border text-success" id="progress"></span>
				<span id="SpinLoaderText" class="text-success">'.$text.'</span>
			</div>';
}
		
//General loader		
function fnehd_loader($text = ""){
	return '<div class="text-center text-success">
				<span class="spinner-border"></span>
				<span>'.$text.'</span>
		   </div>';
}


//Get current url.
function fnehd_current_url() {
	if ( isset( $_SERVER['HTTP_HOST'], $_SERVER['REQUEST_URI'] ) ) {
		return set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . untrailingslashit( $_SERVER['REQUEST_URI'] ) );
	}
	return ''; 
}


/**
 * Get plugin countries list.
 *
 * @return array List of countries.
 */
function fnehd_countries() {
    include_once FNEHD_PLUGIN_PATH . 'lib/countries.php';
    return $countries?? [];
}


//Get currency list
function fnehd_currencies(){
   include FNEHD_PLUGIN_PATH."lib/currencies.php";
   return $currencies?? [];
}


/**
 * Get plugin timezones list.
 *
 * @return array List of timezones.
 */
function fnehd_timezones() {
    include_once FNEHD_PLUGIN_PATH . 'lib/timezones.php';
    return $timezones?? [];
}

function fnehd_light_svg(){
	return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-sun"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>';
}

//Display Collapsable contents
/**
 * Display collapsable dialog content cards.
 *
 * @param array $dialogs Array of dialog configurations.
 * @return string Sanitized HTML output for collapsable dialogs.
 */
function fnehd_callapsable_dialogs(array $dialogs) {
    if (FNEHD_PLUGIN_INTERACTION_MODE === 'modal') {
        return '';
    }
	
    foreach ($dialogs as $dialog) {
        $dialog_id = esc_attr($dialog['id']);
        $title = $dialog['title'];
        $data_id = !empty($dialog['data_id']) ? esc_attr($dialog['data_id']) : '';
        $type_class = isset($dialog['type']) && $dialog['type'] === 'data' ? 'p-5' : '';
        $header_path = !empty($dialog['header']) ? FNEHD_PLUGIN_PATH . "templates/admin/template-parts/" . sanitize_file_name($dialog['header']) : '';
        $callback_path = !empty($dialog['callback']) ? FNEHD_PLUGIN_PATH . "templates/forms/" . sanitize_file_name($dialog['callback']) : '';

        ?>
        <div class="card shadow-lg mb-3 fnehd-admin-forms collapse" id="<?= $dialog_id; ?>">
            <div class="card-header">
                <h3 class="text-dark card-title text-center">
                    <?= $title; ?>
                    <?php if (!empty($header_path) && file_exists($header_path)) : ?>
                        <?php include $header_path; ?>
                    <?php endif; ?>
                </h3>
                <span class="float-right">
                    <button type="button" class="close fnehd-close-btn text-light" data-toggle="collapse"
                            data-target="#<?= $dialog_id; ?>">
                        <span aria-hidden="true"><b>&times;</b></span>
                    </button>
                </span>
            </div>
            <div class="card-body <?= esc_attr($type_class); ?>">
                <div <?= !empty($data_id) ? 'id="' . $data_id . '"' : ''; ?>>
                    <?php if (!empty($callback_path) && file_exists($callback_path)) : ?>
                        <?php include $callback_path; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }
	
}

	
/**
 * Generates an HTML table for export.
 *
 * @param array  $data       Data to populate the table rows.
 * @param array  $columns    Column headers and corresponding keys in the data array.
 * @param string $label      Label for the exported data.
 */
function fnehd_excel_table($data, $columns, $label) {
	$output = '<table border="1"><tr style="font-weight:bold">';
	
	// Generate table headers
	foreach ($columns as $header => $key) {
		$output .= "<th>{$header}</th>";
	}
	$output .= '</tr>';
	
	// Generate table rows
	foreach ($data as $row) {
		$output .= '<tr>';
		foreach ($columns as $key) {
			$output .= '<td>' . esc_html($row[$key]) . '</td>';
		}
		$output .= '</tr>';
	}
	$output .= '</table>';
	
	if ( wp_doing_ajax() ) {
		wp_send_json(['data' => $output, 'label' => $label]);
	} else {
		return $output;
	}
}


function fnehd_user_profile_menu() {	
	$user_id = get_current_user_id();
	$username = get_user_by('ID', $user_id)->user_login;
	$user_img = get_user_meta($user_id, 'user_image', true);
	$trans = array(
		'Shelter'  => FNEHD_SHELTER_LABEL,
	);
	
	ob_start();
	?>
	<div class="slide-menu slide-section">
		<ul class="fnehd-listing-menu nav-menu nav-tabs">
			<li class="nav-item pt-1 menu-item" id="FnehdDasboardNavItem">
				<a class="nav-link fnehd-menu-item" href="<?= add_query_arg(['endpoint' => 'dashboard'], esc_url(home_url())); ?>">
					</i><i class="fa-solid fa-house-chimney-user fa-xl"></i>
					<span class="text-capitalize"> <?= __('Shelters', 'fnehousing'); ?></span>          
				</a>
			</li>
			 <li class="nav-item pt-1 menu-item" id="FnehdMapNavItem">
				<a class="nav-link fnehd-menu-item" href="<?= add_query_arg(['endpoint' => 'map_view'], esc_url(home_url())); ?>">
					<i class="fa-solid fa-map-location-dot fa-xl"></i>
					<span class="text-capitalize"> <?= __('Map View', 'fnehousing'); ?></span>          
				</a>
			</li>
			<li class="nav-item pt-1 menu-item" id="FnehdMapNavItem">
				<a type="button" class="text-light btn fnehd-calltoaction shadow-lg btn-sm btn-text btn-round btn-outline-info"  <?= (FNEHD_PLUGIN_INTERACTION_MODE === "modal") ? 'data-toggle="modal" data-target="#fnehd-add-shelter-modal"' : 'data-toggle="collapse" data-target="#fnehd-add-shelter-form-dialog"'; ?> href="#">
					<i class="fa-solid fa-house-medical-circle-check fa-xl"></i>
					<span class="text-capitalize"> <?= __('Add Shelter', 'fnehousing'); ?></span>          
				</a>
			</li>	
			<li class="dark-mode-menu-item">
				<button aria-label="Click to toggle dark mode" class="dark-mode-widget">
			   <?= fnehd_light_svg(); ?>
			   <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-moon"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>	
			   </button>
			</li>	
			<li class="nav-item dropdown" id="FnehdUserProfileFrontNavItem">
				<a class="nav-link dropdown-toggle wp-block-button__link wp-element-button" href="#" id="FnehdManageShelterCollapse" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<?= fnehd_image($user_img, 35, "border rounded-circle"); ?> 
					<span class="text-capitalize text-dark"> <?= $username; ?></span>          
				</a>
				<div class="dropdown-menu dropdown-menu-right rounded p-2" aria-labelledby="FnehdManageShelterCollapse" x-placement="bottom-end" style="position: absolute; will-change: top, left; top: 55px; left: 125px;">
					<a id="FnehdMyProfileFrontNavItem" class="dropdown-item fnehd-rounded" href="<?= home_url(); ?>/?endpoint=user_profile">
						<i class="fnehd-nav-icon fas fa-user"></i>&nbsp; <?= __('My Profile', 'fnehousing'); ?>
					</a>
				   <div class="dropdown-divider"></div>
				   <a id="FnehdLogOutFrontNavItem" class="dropdown-item  fnehd-fnehd-rounded" href="#">
						<i class="fnehd-nav-icon fas fa-sign-out"></i>&nbsp;  <?= __('Log out', 'fnehousing'); ?>       
					</a>							
				</div>
			</li>
		</ul>
	</div>
	<?php return strtr(ob_get_clean(), $trans);
}




