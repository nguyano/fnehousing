<?php
/**
 * Admin Top Address bar
 * 
 * @Since   1.0.0
 * @package Fnehousing
 */
 
 defined('ABSPATH') || exit;
?>
<nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top">
    <div class="container-fluid">
        <div class="navbar-wrapper">
            <?php if (FNEHD_ADMIN_NAV_STYLE === 'sidebar') : ?>
                <div class="navbar-minimize">
                    <button id="minimizeSidebar" class="btn btn-just-icon bg-transparent btn-fab btn-round" title="<?php esc_attr_e('Toggle Menu', 'fnehd'); ?>">
                        <i class="text-light fa fa-maximize fnehd-nav-icon text_align-center visible-on-sidebar-regular"></i>
                        <i class="text-light fa fa-minimize fnehd-nav-icon design_bullet-list-67 visible-on-sidebar-mini"></i>
                    </button>
                </div>
            <?php else : ?>
                <div class="p-2 pl-3 pr-3 mr-5 border rounded-pill border-primary d-none d-md-block">
                    <?php include FNEHD_PLUGIN_PATH."templates/admin/template-parts/fnehd-admin-logo.php"; ?>
                </div>
            <?php endif; ?>

            <?php  include FNEHD_PLUGIN_PATH."templates/admin/template-parts/navbar-breadcrumps.php"; ?>

            <!-- Mobile Top Header -->
            <div class="justify-content-end mobile-top d-block d-lg-none">
                <a href="#" class="toggle-theme" title="<?php esc_attr_e('Toggle Light/Dark Mode', 'fnehd'); ?>" id="<?= FNEHD_THEME_CLASS === 'dark-edition' ? 'ToggleLightModeMbl' : 'ToggleDarkModeMbl'; ?>">
                    <?= FNEHD_THEME_CLASS === 'dark-edition' ? fnehd_light_svg() : '<i class="fa fa-moon fnehd-nav-icon text-light fixed-plugin-button-nav"></i>'; ?>
                </a>
                <div class="fnehd-view-notif">
                    <a class="notify-mobile" href="#" id="ShowMnoty" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <i class="text-light fa fa-bell fnehd-nav-icon"></i>
                        <span class="notification" style="background: transparent; border: none !important;"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-left notif-content" aria-labelledby="ShowMnoty"></div>
                </div>
            </div>
        </div>

        <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="<?php esc_attr_e('Toggle navigation', 'fnehd'); ?>">
            <span class="navbar-toggler-icon icon-bar text-light"></span>
            <span class="navbar-toggler-icon icon-bar text-light"></span>
            <span class="navbar-toggler-icon icon-bar text-light"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end">
            <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                <?php include FNEHD_PLUGIN_PATH . 'templates/forms/shelter-search-form.php'; ?>
            </div>

            <ul class="navbar-nav">
                <!-- Toggle Light/Dark Mode -->
                <li class="nav-item ps-3 d-flex align-items-center">
                    <a href="#" title="<?php esc_attr_e('Toggle Light/Dark Mode', 'fnehd'); ?>" class="nav-link text-body p-0" id="<?= FNEHD_THEME_CLASS === 'dark-edition' ? 'ToggleLightMode' : 'ToggleDarkMode'; ?>">
                        <?= FNEHD_THEME_CLASS === 'dark-edition' ? fnehd_light_svg() : '<i class="fa fa-moon fnehd-nav-icon text-light fixed-plugin-button-nav"></i>'; ?>
                    </a>
                </li>

                <!-- Settings -->
                <li class="nav-item px-3">
                    <a href="<?= FNEHD_PLUGIN_INTERACTION_MODE === 'modal' ? 'javascript:;' : esc_url(admin_url('admin.php?page=fnehousing-settings')); ?>" class="nav-link text-body p-0" data-toggle="<?= FNEHD_PLUGIN_INTERACTION_MODE === 'modal' ? 'modal' : ''; ?>" data-target="<?= FNEHD_PLUGIN_INTERACTION_MODE === 'modal' ? '#fnehd-sett-modal' : ''; ?>">
                        <i class="text-light fa fa-screwdriver-wrench fnehd-nav-icon"></i>
                    </a>
                </li>

                <!-- Notifications -->
                <li class="nav-item px-3 dropdown fnehd-view-notif">
                    <a class="nav-link" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <i class="text-light fa fa-bell fnehd-nav-icon"></i>
                        <span class="fnehd-notification notification" style="background: transparent; border: none !important;"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right notif-content" aria-labelledby="navbarDropdownMenuLink"></div>
                </li>
            </ul>
        </div>
    </div>
</nav>
