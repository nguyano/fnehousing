<?php 
/**
 * Admin Top Menu
 * 
 * @Since   1.0.0
 * @package Fnehousing
 */
 
defined('ABSPATH') || exit;

include FNEHD_PLUGIN_PATH . "templates/admin/template-parts/menu-list.php";

if (!empty($menus)) :
    ob_start(); // Start output buffering
    ?>
    <nav class="navbar fnehd-top-nav navbar-expand-lg navbar-dark bg-dark">
        <div class="pl-4 collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <?php foreach ($menus as $menu) : ?>
                    <li 
                        class="text-light nav-item fnehd-top-nav-item shadow-lg <?= esc_attr($menu['li-classes']); ?> 
                        <?= ($menu['type'] === 'drop-down') ? 'dropdown' : ''; ?>" 
                        id="<?= esc_attr($menu['li-id']); ?>"
                    >
                        <a 
                            class="nav-link fnehd-nav-link <?= ($menu['type'] === 'drop-down') ? 'dropdown-toggle' : ''; ?>"
                            <?php 
                            if ($menu['li-id'] === 'FnehdSettMenuItem' && FNEHD_PLUGIN_INTERACTION_MODE === "modal") {
                                echo 'id="BtnSettings" data-toggle="modal" data-target="#fnehd-sett-modal"';
                            } elseif ($menu['type'] === 'drop-down') {
                                echo 'href="#" id="' . esc_attr($menu['collapse-id']) . '" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"';
                            } else {
                                echo 'href="' . esc_url($menu['href']) . '"';
                            }
                            ?>
                        >
                            <i class="fa fa-<?= esc_attr($menu['icon']); ?>"></i>
                            <span><?= esc_html__($menu['title'], 'fnehousing'); ?></span>
                        </a>

                        <?php if ($menu['type'] === 'drop-down') : ?>
                            <div class="dropdown-menu" aria-labelledby="<?= esc_attr($menu['collapse-id']); ?>">
                                <?php foreach ($menu['submenus'] as $submenu) : ?>
                                    <a 
                                        class="dropdown-item fnehd-rounded"
                                        href="<?= esc_url($submenu['href']); ?>"
                                        <?php 
                                        if ($submenu['li-id'] === 'FnehdQuikResDB') {
                                            if (FNEHD_PLUGIN_INTERACTION_MODE === "modal") {
                                                echo 'data-toggle="modal" data-target="#fnehd-db-restore-modal"';
                                            } else {
                                                echo 'data-toggle="collapse" data-target="#fnehd-db-restore-form-dialog"';
                                            }
                                        }
                                        ?>
                                    >
                                        <i class="fa fa-<?= esc_attr($submenu['icon']); ?>"></i>&nbsp;
                                        <?= esc_html__($submenu['title'], 'fnehousing'); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </nav>
    <?php
    echo ob_get_clean(); // Output the buffered content
endif;
?>
