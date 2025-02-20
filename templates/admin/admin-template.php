<?php

/**
 * Admin area template for the plugin.
 *
 * @since 1.0.0
 * @package    Fnehousing
 */

defined('ABSPATH') || exit;

// Include required template parts.
include_once FNEHD_PLUGIN_PATH . 'templates/admin/template-parts/header.php'; // Header
include_once FNEHD_PLUGIN_PATH . 'templates/admin/template-parts/sidebar.php'; // Sidebar

?>

<div class="main-panel fnehd-main-panel">

    <?php 
    
    include_once FNEHD_PLUGIN_PATH . 'templates/admin/template-parts/navbar.php'; // Navbar

    if ( FNEHD_ADMIN_NAV_STYLE === 'no-menu' ) {
        echo '<div class="mt-5"></div>';// add top margin if no menu option is on
    }

    if ( FNEHD_ADMIN_NAV_STYLE === 'top-menu' && ! wp_is_mobile() ) {
        include_once FNEHD_PLUGIN_PATH . 'templates/admin/template-parts/top-nav-menu.php';
    }
    ?>

    <div class="content">
        <?= fnehd_ajax_loader(); // AJAX loader ?>

         <div class="container-fluid">
            <?php
            // Plugin-wide dialogs
            $dialogs = [
                [
                    'id' => 'fnehd-db-restore-form-dialog',
                    'data_id' => '',
                    'header' => '',
                    'title' => esc_html__("Restore Database Backup", "fnehousing"),
                    'callback' => 'db-restore-form.php',
                    'type' => 'restore-form'
                ],
                [
                    'id' => 'fnehd-search-results-dialog',
                    'data_id' => 'fnehd-shelter-search-results-dialog-wrap',
                    'header' => '',
                    'title' => esc_html__("Shelter Search Results", "fnehousing"),
                    'callback' => '',
                    'type' => 'data'
                ]
            ];
            fnehd_callapsable_dialogs($dialogs);

            // Main content switch based on current page
            $current_page = sanitize_text_field($_GET['page'] ?? '');
            $content_map = [
                'fnehousing-dashboard' => 'templates/admin/dashboard/dashboard.php',
                'fnehousing-view-shelter' => 'templates/shelters/view-shelter.php',
                'fnehousing-view-dispute' => 'templates/disputes/view-dispute.php',
                'fnehousing-stats' => 'templates/admin/stats/stats.php',
                'fnehousing-settings' => 'templates/admin/template-parts/settings-panel.php',
            ];

            if (isset($content_map[$current_page])) {
                include_once FNEHD_PLUGIN_PATH . $content_map[$current_page];
            } elseif (isset($_GET['user_id'])) {
                echo '<div id="fnehd-profile-container">' . fnehd_loader(esc_html__('Loading..', 'fnehousing')) . '</div>';
            } else {
                echo '<div id="fnehd-admin-container">' . fnehd_loader(esc_html__('Loading..', 'fnehousing')) . '</div>';
            }
            ?>
        </div>
    </div>

    <?php 
    // Include footer and modals if required.
    include_once FNEHD_PLUGIN_PATH . 'templates/admin/template-parts/footer.php';
	
    if ( FNEHD_INTERACTION_MODE === 'modal' ) {
        include_once FNEHD_PLUGIN_PATH . 'templates/admin/template-parts/modals.php';
    }
    if ( isset( $IsStatsPage ) && $IsStatsPage ) {
        include_once FNEHD_PLUGIN_PATH . 'templates/admin/stats/charts.php';
    }
    ?>

</div>

</div><!-- End of bodyWrapper, opening tag in header -->


