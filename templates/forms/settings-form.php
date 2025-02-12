<?php 

/**
 * Admin Settings Form Template
 *
 * @since      1.0.0
 * @package    Fnehousing
 */

defined('ABSPATH') || exit;

$tabs = [
    [
        'id'        => 'GeneralTab',
        'title'     => __("General", "fnehousing"),
        'sections'  => ['general', 'company_logo'],
        'icon'      => 'gear'
    ],
    [
        'id'        => 'SheltersTab',
        'title'     => __("Shelters", "fnehousing"),
        'sections'  => ['backend_shelter', 'ref_id'],
        'icon'      => 'house-chimney-user'
    ],
    [
        'id'        => 'EmailsTab',
        'title'     => __("Email", "fnehousing"),
        'sections'  => ['email', 'smtp', 'user_shelter_email', 'admin_shelter_email'],
        'icon'      => 'envelope'
    ],
    [
        'id'        => 'StylingTab',
        'title'     => __("Styling", "fnehousing"),
        'sections'  => ['admin_appearance', 'public_appearance', 'labels'],
        'icon'      => 'paint-brush'
    ],
    [
        'id'        => 'DBBackupTab',
        'title'     => __("DB Backup", "fnehousing"),
        'sections'  => ['dbbackup'],
        'icon'      => 'database'
    ],
	[
        'id'        => 'GoogleMapTab',
        'title'     => __("Google Map", "fnehousing"),
        'sections'  => ['google_map'],
        'icon'      => 'map-marker-alt'
    ],
    [
        'id'        => 'AdvancedTab',
        'title'     => __("Advanced", "fnehousing"),
        'sections'  => ['advanced'],
        'icon'      => 'shield'
    ]
];

	
$custom_sections = ['company_logo', 'smtp', 'user_shelter_email', 'admin_shelter_email', 'labels'];
	
if (defined('FNEHD_PLUGIN_INTERACTION_MODE') && FNEHD_PLUGIN_INTERACTION_MODE === 'modal') : 

ob_start();

?>
    <div class="text-center">
        <button id="impSett" type="button" class="btn btn-round btn-icon-text fnehd-btn-behance" 
                data-toggle="modal" 
                data-target="#fnehd-options-import-modal">
            <i class="fas fa-upload"></i> <?= esc_html__("Import Settings", "fnehousing"); ?>
        </button>
        <button id="fnehd-export-settings" class="btn btn-round m-1 btn-info">
            <i class="fa fa-download"></i> <?= esc_html__("Export Settings", "fnehousing"); ?>
        </button>
    </div>
    <br>
<?php endif; ?>

<form method="post" action="options.php" id="fnehd-options-form">
    <?php 
    // Output security fields for the registered setting
    settings_fields('fnehousing_plugin_settings'); 
    // Display errors or notifications
    settings_errors(); 
    ?>

    <div id="fnehd-settings-panel">
        <div class="card-body">
		<div class="pb-5 float-right card-header justify-content-between">
			<button title="<?= esc_html__("Save Settings", "fnehousing"); ?>" type="submit" name="submit" class="fnehd-submit-settings shadow-lg btn btn-sm btn-outline-success">
				<i class="fa fa-save"></i> <span class="d-none d-md-inline"><?= esc_html__("Save Settings", "fnehousing"); ?></span>
			</button>
			 <button title="<?= esc_html__("Reset Settings", "fnehousing"); ?>" type="button" class="fnehd-reset-settings shadow-lg btn btn-sm btn-outline-danger ml-3">
				<i class="fa fa-shuffle"></i> <span class="d-none d-md-inline"><?= esc_html__("Reset Settings", "fnehousing"); ?></span>
			</button>
		</div>
		
            <ul class="nav nav-pills w-100" role="tablist"> 
                <?php  foreach ($tabs as $tab) : ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $tab['id'] === 'GeneralTab' ? 'active' : ''; ?>" 
                           data-toggle="tab" 
                           href="#<?= esc_attr($tab['id']); ?>" 
                           role="tablist">
                            <i class="fa fa-<?= esc_attr($tab['icon']); ?>"></i> 
                            <?= esc_html($tab['title']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>

            <div class="tab-content tab-space">
                <?php foreach ($tabs as $tab) : ?>
                    <div class="tab-pane <?= $tab['id'] === 'GeneralTab' ? 'active' : ''; ?>" id="<?= esc_attr($tab['id']); ?>">
					<section id="fnehd-horizontal-tabs">
							<div class="pt-5 container">
								<div class="row">
									<div class="col-md-12 ">
										<nav>
											<div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
												<?php $counter = 0; ?>
												<?php  foreach ($tab['sections'] as $section) : ?>
													<?php
														$page = 'fnehousing_' . $section;
														$section_id = 'fnehd_' . $section .'_settings';
													?>
													<a class="nav-item nav-link <?=  $counter === 0 ? 'active' : ''; ?>" id="<?= $section_id ?>-tab" data-toggle="tab" href="#<?= $section_id ?>_id" role="tab" aria-controls="<?= $section_id ?>_id" aria-selected="<?=  $counter === 0 ? 'true' : 'false'; ?>">
														<?= esc_html(get_section_title($page, $section_id)); ?>
													</a>
												<?php $counter++; endforeach; ?>	
											</div>
										</nav>
										<div class="tab-content py-3 px-3 px-sm-0 w-100" id="nav-tabContent">
											<?php $counter = 0; ?>
										    <?php  foreach ($tab['sections'] as $section) : ?>
											
											    <?php
												$page = 'fnehousing_' . $section;
												$section_id = 'fnehd_' . $section .'_settings';
												?>
												<div class="tab-pane fade <?=  $counter === 0 ? 'active show' : ''; ?>" id="<?= $section_id ?>_id" role="tabpanel" aria-labelledby="<?= $section_id ?>-tab"> 
													<div class="row">
													
														<?php if( in_array($section, $custom_sections) ) { 
															$width = $section==='company_logo'? 6 : 12; 
															$id = 'fnehd-'.$section.'-options'; 
														?>
															<div id="<?= $id; ?>" class="col-md-<?= $width; ?> flex card shadow-lg pr-5 pl-5">
																<div class="row">
																	<?php 
																		if($section ==='user_shelter_email' || $section ==='admin_shelter_email'){ 
																			echo fnehd_merge_tags_table(); 
																		} 
																		do_settings_sections( 'fnehousing_' . $section ); 
																	?>
																</div>
															</div>
													
													   <?php } else { do_settings_sections('fnehousing_' . $section); } ?>
													</div>	
												</div>
												
											<?php   $counter++; endforeach;  ?>
											 
										</div>
									
									</div>
								</div>
							</div>
						</section>
						
						
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <br><br>

        <button title="<?= esc_html__("Save Settings", "fnehousing"); ?>" type="submit" name="submit" class="fnehd-submit-settings float-right shadow-lg btn btn-sm btn-outline-success">
				<i class="fa fa-save"></i> <span class="d-none d-md-inline"><?= esc_html__("Save Settings", "fnehousing"); ?></span>
		</button>
		 <button title="<?= esc_html__("Reset Settings", "fnehousing"); ?>" type="button" class="mr-3 fnehd-reset-settings float-right shadow-lg btn btn-sm btn-outline-danger">
			<i class="fa fa-shuffle"></i> <span class="d-none d-md-inline"><?= esc_html__("Reset Settings", "fnehousing"); ?></span>
		</button>
        <?php if (FNEHD_PLUGIN_INTERACTION_MODE === "modal" && !wp_is_mobile()) : ?>
            <button type="button" class="btn shadow-lg btn btn-sm btn-outline-default float-right" data-dismiss="modal">
                <?= esc_html__("Close Panel", "fnehousing"); ?>
            </button>
        <?php endif; ?>
    </div>
</form>

<?php 

$content = ob_get_clean(); 
$translations = [
	'{fnehd_currency}' => FNEHD_CURRENCY,
];

echo strtr($content, $translations);




