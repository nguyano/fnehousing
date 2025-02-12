<?php
/**
 * Main public template
 *
 *@since    1.0.0
 * @package FneHousing
 */
 
 defined('ABSPATH') || exit;
 
 
// Define page-wide translations
$trans = array(
    'Shelter' => FNEHD_SHELTER_LABEL,
);

// Start output buffering to capture all echoed content
ob_start();
?>

<div class="fnehd-main-panel" id="fnehd-front-wrapper">
    <?php
        echo fnehd_ajax_loader('working..');

        if (is_user_logged_in()) {
            if (fnehd_is_front_user()) { // Allow only Fnehousing users
                include_once FNEHD_PLUGIN_PATH . "templates/frontend/user-profile.php";

                if (FNEHD_PLUGIN_INTERACTION_MODE === "modal") {
                    include_once FNEHD_PLUGIN_PATH . "templates/frontend/front-modals.php";
                }
            } else {
                include_once FNEHD_PLUGIN_PATH . "templates/frontend/user-login.php";
            }
        } else {
            $endpoint = isset($_GET['endpoint']) ? sanitize_text_field($_GET['endpoint']) : '';

            switch ($endpoint) {
                case 'update_shelter_data':
                    include_once FNEHD_PLUGIN_PATH . "templates/frontend/views/update-shelter-data.php";
                    break;
                case 'send_password_reset_link':
                    include_once FNEHD_PLUGIN_PATH . "templates/frontend/views/send-password-reset-link.php";
                    break;
                case 'reset_password':
                    include_once FNEHD_PLUGIN_PATH . "templates/frontend/views/reset-password.php";
                    break;
                default:
                    include_once FNEHD_PLUGIN_PATH . "templates/frontend/user-login.php";
                    break;
            }
        }
    ?>
</div>

<?php
// Get the buffered content
$content = ob_get_clean();

// Transform the content using strtr
echo strtr($content, $trans);

