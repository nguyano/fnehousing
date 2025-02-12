<?php
/*Listing search bar template 
 *
 *@since  1.0.0
 *@packag FneHousing
 *
 */

defined('ABSPATH') || exit;

// Dropdown options
$dropdown_options = [
    'gender' => [
        'label' => __('Select Gender', 'fnehousing'),
        'items' => [
            'females' => __('Females', 'fnehousing'),
            'males' => __('Males', 'fnehousing'),
            'transgender' => __('Transgender', 'fnehousing'),
            'non-conforming' => __('Non-Conforming', 'fnehousing'),
            'questioning' => __('Questioning', 'fnehousing'),
        ],
    ],
    'age' => [
        'label' => __('Select Age Group', 'fnehousing'),
        'items' => [
            'youth' => __('Youth (<18)', 'fnehousing'),
            'adults' => __('Adults (18-69)', 'fnehousing'),
            'older-adults' => __('Older Adults (70+)', 'fnehousing'),
            'families' => __('Families', 'fnehousing'),
        ],
    ],
    'pets' => [
        'label' => __('Select Pet Policy', 'fnehousing'),
        'items' => [
            'allowed' => __('Pets Allowed', 'fnehousing'),
            'not-allowed' => __('Pets Not Allowed', 'fnehousing'),
        ],
    ],
    'assistance' => [
        'label' => __('Select Assistance Needs', 'fnehousing'),
        'items' => [
            'oxygen' => __('Require Oxygen', 'fnehousing'),
            'adls' => __('Unable to Perform Some/All ADLs', 'fnehousing'),
            'dressing' => __('Need Dressing Assistance', 'fnehousing'),
            'toileting' => __('Need Toileting Assistance', 'fnehousing'),
            'transferring' => __('Need Transferring Assistance', 'fnehousing'),
        ],
    ],
];


?>

<div class="p-3 card shadow-lg mt-5">
    <div class="card-body">

        <!-- Search Field -->
        <div class="fnehd-listing-search">
            <div class="fnehd-listing-search-field">
                <input id="search" type="text" placeholder="Type Keywords">
                <div class="icon-wrap">
                    <svg class="svg-inline--fa fa-search fa-w-16" fill="#ccc" aria-hidden="true" data-prefix="fas" data-icon="search" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Dropdown Filters -->
        <form id="fnehd-listing-search-form">
            <div class="d-flex flex-wrap">

                <?php foreach ($dropdown_options as $key => $dropdown): ?>
                    <div class="form-group col-md-4">
                        <div class="dropdown">
                            <button class="btn fnehd-btn-secondary shadow-lg dropdown-toggle w-100" type="button" id="<?= $key; ?>Dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?= $dropdown['label']; ?>
                            </button>
                            <div class="dropdown-menu w-100" aria-labelledby="<?= $key; ?>Dropdown">
                                <div class="px-3">
                                    <?php foreach ($dropdown['items'] as $value => $label): ?>
                                        <div class="form-check">
                                            <input type="checkbox" id="<?= $value; ?>" value="<?= $value; ?>">
                                            <label class="form-check-label" for="<?= $value; ?>"><?= $label; ?></label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <!-- Additional Filters -->
                <div class="form-group col-md-4">
                    <div class="dropdown">
                        <button class="btn fnehd-btn-secondary shadow-lg dropdown-toggle w-100" type="button" id="bedsDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Number of Beds
                        </button>
                        <div class="dropdown-menu w-100" aria-labelledby="bedsDropdown">
                            <div class="px-3">
								 <div id="fnehd-beds-slider" class="fnehd-slider-container">
									<input type="range" name="beds" class="fnehd-custom-slider" 
									min="0" max="200" value="0" />
									<div id="fnehd-beds-slider-val" class="fnehd-slider-value">50</div>
								</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group col-md-4">
                    <div class="dropdown">
                        <button class="btn fnehd-btn-secondary dropdown-toggle w-100" type="button" id="radiusDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Search Radius
                        </button>
                        <div class="dropdown-menu w-100" aria-labelledby="radiusDropdown">
                            <div class="px-3">
                                <div id="fnehd-radius-slider" class="fnehd-slider-container">
									<input type="range" name="radius" class="fnehd-custom-slider" 
									min="0" max="200" value="0" />
									<div id="fnehd-radius-slider-val" class="fnehd-slider-value">50</div>
								</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


