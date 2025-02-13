<?php
/*Single shelter template. 
 *Shelter details
 *
 *@since  1.0.0
 *@packag FneHousing
 *
 */
 
use Fnehousing\Database\ShelterDBManager; 

defined('ABSPATH') || exit;


if( !isset($_GET['shelter_id']) ) {
	wp_redirect( home_url('/404') ); // Redirect to the 404 page URL if shelter id not found
	exit;
};

$obj = new ShelterDBManager();

$shelter_id = $_GET['shelter_id'];

$shelter = $obj->getShelterById($shelter_id);

$default_img_url = FNEHD_PLUGIN_URL."assets/img/fne-default-home.webp";


$features = [];
$contact_url = '#';
$update_url = '#';
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-light rounded-lg px-3 py-2">
        <?php 
        $breadcrumbs = [
            ['label' => 'Shelter', 'url' => home_url()],
            ['label' => $shelter['shelter_name'], 'url' => '', 'active' => true]
        ];
        foreach ($breadcrumbs as $breadcrumb): ?>
            <li class="breadcrumb-item<?= isset($breadcrumb['active']) ? ' active" aria-current="page"' : '' ?>">
                <?php if (!isset($breadcrumb['active'])): ?>
                    <a href="<?= esc_url($breadcrumb['url']); ?>" class="text-primary"><?= esc_html($breadcrumb['label']); ?></a>
                <?php else: ?>
                    <?= esc_html($breadcrumb['label']); ?>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ol>
</nav>

<div class="row shadow-lg <?= wp_is_mobile()? ' p-3' : ' p-5'?>">
    <!-- Main Content -->
    <div class="fnehd-shelter-main-content border col-md-8<?= wp_is_mobile()? ' p-3' : ' p-5'?>">
        <!-- Title Bar -->
        <div class="mb-4 fnehd-shelter-title-bar">
            <?= $shelter['shelter_name']; ?> <i class="text-success fa fa-check-circle"></i>
        </div>
        <!-- Gallery -->
		<div class="fnehd-gallery-container">
		    <div class="main-image">
				<button class="prev-btn">&lt;</button>
				<img id="mainImage" src="<?= $shelter['main_image']? $shelter['main_image'] : $default_img_url; ?>">
				<button class="next-btn">&gt;</button>
			</div>
			<div class="pt-3 thumbnail-slider">
				<div class="thumbnails">
					<img src="<?= $shelter['main_image']? $shelter['main_image'] : $default_img_url; ?>" class="thumbnail active" data-full="<?= $shelter['main_image']; ?>">
					<?php 
					if(!empty($shelter['gallery'])) {
						$gallery = explode(',', $shelter['gallery']);
						foreach ($gallery as $image_url){ ?>
						<img class="thumbnail" data-full="<?= $image_url; ?>" src="<?= $image_url; ?>">
					<?php } }?>
					
					
				</div>
			</div>
		</div>


        <!-- Description -->
        <div class="pt-4 fnehd-shelter-description">
            <h2><?php esc_html_e('Description', 'fnehousing'); ?></h2>
            <p><?= $shelter['description']; ?></p>
        </div>

        <!-- Features -->
        <?php 
        $features = [
            ['title' => 'Availability', 'value' => $shelter['availability'], 'icon' => 'house-circle-check'],
			['title' => 'Total Beds', 'value' => $shelter['bed_capacity'], 'icon' => 'bed'],
            ['title' => 'Available Beds', 'value' => $shelter['available_beds'], 'icon' => 'bed'],
            ['title' => 'Bathrooms', 'value' => '7', 'icon' => 'bath'],
            ['title' => 'Wi-Fi', 'value' => 'Available', 'icon' => 'wifi'],
            ['title' => 'Parking', 'value' => 'Available', 'icon' => 'square-parking'],
        ];
        ?>
        <div class="fnehd-shelter-features">
            <h2><?php esc_html_e('Shelter Highlights', 'fnehousing'); ?></h2>
            <div class="pt-4 pb-4 fnehd-shelter-features-grid">
                <?php foreach ($features as $feature): ?>
                    <div class="fnehd-shelter-feature-item">
						<i class="fa-solid text-success fnehd-nav-icon fa-<?= $feature['icon']; ?>"></i>
                        <div class="fnehd-shelter-feature-title"><?= esc_html($feature['title']); ?></div>
                        <div class="fnehd-shelter-feature-value"><?= esc_html($feature['value']); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Location Map -->
        <div class="map">
            <h2><?php esc_html_e('Location', 'fnehousing'); ?></h2>
            <div style="overflow:hidden;max-width:100%;width:100%;height:280px; border: 4px solid silver;">
               <iframe 
					style="height:100%;width:100%;border:0;" 
					frameborder="0" 
					src="https://www.google.com/maps/embed/v1/place?q=<?php echo urlencode($shelter['address']); ?>&key=AIzaSyAUYJEfeT6Wpv6LvpTIwKGReJClMprgDuU">
			  </iframe>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="fnehd-shelter-sidebar border col-md-4 pt-5 p-4">
        <h3><?php esc_html_e('Update Shelter Availability', 'fnehousing'); ?></h3>
        <?php include FNEHD_PLUGIN_PATH."templates/forms/quick-shelter-update-form.php"; ?>

        <!-- Contact Details -->
        <div class="border p-4 fnehd-contact-details mt-5">
            <h3><?php esc_html_e('Contact Details', 'fnehousing'); ?></h3><br>
            <ul>
                <?php 
                $contacts = [
                    ['icon' => 'user-check', 'label' => $shelter['manager'].' (Manager)'],
					['icon' => 'phone', 'label' => $shelter['phone']],
                    ['icon' => 'envelope', 'label' => $shelter['email'], 'type' => 'email'],
                    ['icon' => 'fax', 'label' => '222 567 7885 877'],
                    ['icon' => 'globe', 'label' => 'exshelter.com', 'url' => 'exshelter.com'],
					['icon' => 'map-marker-alt', 'label' => $shelter['address']]
                ];
                foreach ($contacts as $contact): ?>
                    <li>
                        <i class="fas fa-<?= $contact['icon']; ?>"></i>&nbsp;
                        <?php if (isset($contact['url'])): ?>
                            <a href="<?= esc_url($contact['url']); ?>" target="_blank"><?= esc_html($contact['label']); ?></a>
                        <?php else: ?>
                            <?= esc_html($contact['label']); ?>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
	    <button class="w-100 contact-button" onclick="window.location.href='<?php echo esc_url($contact_url); ?>'">
			<?php esc_html_e('Contact Shelter', 'fnehousing'); ?>
		</button>
		<button class="w-100 mt-3 contact-button bg-info">
			<?php esc_html_e('Mark as Referred', 'fnehousing'); ?>
		</button>
		
		<!-- Features -->
		<div class="mt-5 fnehd-shelter-features">
			<h2><?php esc_html_e('All Features', 'fnehousing'); ?></h2>
			<br>
			<ul>
				<?php
				// Define features data
				$features = [
					'Gender identity' => ['Females', 'Males', 'Transgender', 'Non-conforming/questioning'],
					'Accepted Age' => explode(',', $shelter['accepted_ages']),
					'Pets policy' => [ $shelter['pet_policy'] ],
					'Accepts clients that…' => explode(',', $shelter['specific_services']),
				];
				
				foreach ($features as $category => $items) {
					if (!empty($items)) { // Only display if $items is not empty
						echo '<li><strong>' . esc_html__($category, 'fnehousing') . '</strong></li>';
						foreach ($items as $item) {
							echo '<li><i class="pl-1 text-success fa fa-check-circle"></i>&nbsp; ' . esc_html__($item, 'fnehousing') . '</li>';
						}
						echo '<br>';
					}
				}
				?>
			</ul>
		</div>

    </div>
</div>
