<?php
/**
 * Admin side bar menu
 * 
 * @Since   1.0.0
 * @package Fnehousing
 */
 
 defined('ABSPATH') || exit;
?>

<div 
    class="sidebar<?= (FNEHD_ADMIN_NAV_STYLE === 'sidebar') ? '' : ' d-block d-md-none'; ?>" 
    data-color="azure" 
    data-background-color="default"
>
    <?php
    // Include admin logo and menu list templates
    include FNEHD_PLUGIN_PATH . "templates/admin/template-parts/fnehd-admin-logo.php";
    include FNEHD_PLUGIN_PATH . "templates/admin/template-parts/menu-list.php";
    ?>

    <div class="sidebar-wrapper">
        <ul class="nav">
            <?php if (!empty($menus)) : ?>
                <?php foreach ($menus as $menu) : ?>
                    <li class="nav-item <?= esc_attr($menu['li-classes']); ?>" id="<?= esc_attr($menu['li-id']); ?>">
                        <?php if ($menu['li-id'] === 'FnehdSettMenuItem' && FNEHD_INTERACTION_MODE === 'modal') : ?>
                            <a class="nav-link fnehd-nav-link" id="BtnSettings" data-toggle="modal" data-target="#fnehd-sett-modal">
                        <?php else : ?>
                            <a class="nav-link fnehd-nav-link" 
                                <?= ($menu['type'] === 'drop-down') ? 'data-toggle="collapse"' : ''; ?> 
                                href="<?= esc_url($menu['href']); ?>">
                        <?php endif; ?>
                                <i class="fa-solid fa-<?= esc_attr($menu['icon']); ?>"></i>
                                <p>
                                    <?= esc_html__($menu['title'], 'fnehousing'); ?>
                                    <?php if ($menu['type'] === 'drop-down') : ?>
                                        <b class="caret"></b>
                                    <?php endif; ?>
                                </p>
                            </a>

                        <?php if ($menu['type'] === 'drop-down') : ?>
                            <div class="collapse" id="<?= esc_attr($menu['collapse-id']); ?>">
                                <ul class="nav">
                                    <?php foreach ($menu['submenus'] as $submenu) : ?>
                                        <li class="nav-item" id="<?= esc_attr($submenu['li-id']); ?>">
                                            <a 
                                                class="nav-link fnehd-nav-link"
                                                <?php 
                                                if ($submenu['li-id'] === 'FnehdQuikResDB') {
                                                    echo (FNEHD_INTERACTION_MODE === 'modal') 
                                                        ? 'data-toggle="modal" data-target="#fnehd-db-restore-modal"' 
                                                        : 'data-toggle="collapse" data-target="#fnehd-db-restore-form-dialog"';
                                                } 
                                                ?>
                                                href="<?= esc_url($submenu['href']); ?>"
                                            >
                                                <i class="fas fa-<?= esc_attr($submenu['icon']); ?>"></i>
                                                <span class="sidebar-normal"><?= esc_html__($submenu['title'], 'fnehousing'); ?></span>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
</div>
