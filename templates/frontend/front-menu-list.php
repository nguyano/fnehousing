<?php

/**
 * Frontend navbar Menu List
 * Defines frontend navbar menu elements
 * 
 * @Since   1.0.0
 * @package Fnehousing
 */
 
 defined('ABSPATH') || exit;


// Main Menu
$main_menu = [

    [
        "li-classes" => "",
        "li-id" => "FnehdDashFrontNavItem",
        "active_classes" => esc_attr($active_class['dashboard']),
        "type" => "normal",
        "href" => esc_url($routes['dashboard']),
        "icon" => "gauge-high",
        "title" => __("Dashboard", "fnehousing"),
        "collapse-id" => "",
        "submenus" => [],
    ],
    [
        "li-classes" => "",
        "li-id" => "FnehdShelterFrontNavItem",
        "active_classes" => esc_attr($active_class['shelter_list'] . $active_class['earning_list'] . $active_class['view_shelter']),
        "type" => "drop-down",
        "href" => "javascript:;",
        "icon" => "house-chimney-user",
        "title" => __("Manage Shelter", "fnehousing"),
        "collapse-id" => "FnehdManageShelterCollapse",
        "submenus" => [
            [
                "li-classes" => "",
                "li-id" => "FnehdCreateShelterFrontNavItem",
                "type" => "normal",
                "href" => "#",
                "icon" => "add",
                "title" => __("Create Shelter", "fnehousing"),
                "collapse-id" => "",
                "submenus" => [],
            ],
            [
                "li-classes" => "",
                "li-id" => "FnehdShelterListFrontNavItem",
                "type" => "normal",
                "href" => esc_url($routes['shelter_list']),
                "icon" => "user-group",
                "title" => __("Shelter List", "fnehousing"),
                "collapse-id" => "",
                "submenus" => [],
            ],
            [
                "li-classes" => "",
                "li-id" => "FnehdEarnerListFrontNavItem",
                "type" => "normal",
                "href" => esc_url($routes['earning_list']),
                "icon" => "user-group",
                "title" => __("My Earning List", "fnehousing"),
                "collapse-id" => "",
                "submenus" => [],
            ],
        ],
    ],
    [
        "li-classes" => "",
        "li-id" => "FnehdLogFrontNavItem",
        "active_classes" => esc_attr($active_class['transaction_log']),
        "type" => "normal",
        "href" => esc_url($routes['transaction_log']),
        "icon" => "file-invoice-dollar",
        "title" => __("Transaction Log", "fnehousing"),
        "collapse-id" => "",
        "submenus" => [],
    ],
    
	[
        "li-classes" => "",
        "li-id" => "FnehdDepositFrontNavItem",
        "active_classes" => esc_attr($active_class['deposit_history'] . $active_class['deposit_payment_options'] . $active_class['bitcoin_deposit_invoice']),
        "type" => "drop-down",
        "href" => "javascript:;",
        "icon" => "coins",
        "title" => __("Deposit", "fnehousing"),
        "collapse-id" => "FnehdInvoiceCollapse",
        "submenus" => [
            [
                "li-classes" => "",
                "li-id" => "FnehdDepositsFrontNavItem",
                "type" => "normal",
                "href" => esc_url($routes['deposit_payment_options']),
                "icon" => "add",
                "title" => __("Deposit Money", "fnehousing"),
                "collapse-id" => "",
                "submenus" => [],
            ],
            [
                "li-classes" => "",
                "li-id" => "FnehdWithdrawalsFrontNavItem",
                "type" => "normal",
                "href" => esc_url($routes['deposit_history']),
                "icon" => "file-invoice-dollar",
                "title" => __("Deposit History", "fnehousing"),
                "collapse-id" => "",
                "submenus" => [],
            ],
        ],
    ],
    [
        "li-classes" => "",
        "li-id" => "FnehdWithdrawFrontNavItem",
        "active_classes" => esc_attr($active_class['withdraw_history'] . $active_class['withdraw_payment_options'] . $active_class['bitcoin_withdraw_invoice']),
        "type" => "drop-down",
        "href" => "javascript:;",
        "icon" => "coins",
        "title" => __("Withdraw", "fnehousing"),
        "collapse-id" => "FnehdInvoiceCollapse",
        "submenus" => [
            [
                "li-classes" => "",
                "li-id" => "FnehdWithdrawsFrontNavItem",
                "type" => "normal",
                "href" => esc_url($routes['withdraw_payment_options']),
                "icon" => "add",
                "title" => __("Withdraw Money", "fnehousing"),
                "collapse-id" => "",
                "submenus" => [],
            ],
            [
                "li-classes" => "",
                "li-id" => "FnehdWithdrawalsFrontNavItem",
                "type" => "normal",
                "href" => esc_url($routes['withdraw_history']),
                "icon" => "file-invoice-dollar",
                "title" => __("Withdraw History", "fnehousing"),
                "collapse-id" => "",
                "submenus" => [],
            ],
        ],
    ],
    [
        "li-classes" => "",
        "li-id" => "FnehdDisputeFrontNavItem",
        "active_classes" => esc_attr($active_class['my_disputes']. $active_class['disputes_against_me'] . $active_class['view_dispute']),
        "type" => "drop-down",
        "href" => "javascript:;",
        "icon" => "people-arrows",
        "title" => __("Disputes", "fnehousing"),
        "collapse-id" => "FnehdDisputeCollapse",
        "submenus" => [
            [
                "li-classes" => "",
                "li-id" => "FnehdAddDisputesFrontNavItem",
                "type" => "normal",
                "href" => "#",
                "icon" => "add",
                "title" => __("Open New Dispute", "fnehousing"),
                "collapse-id" => "",
                "submenus" => [],
            ],
            [
                "li-classes" => "",
                "li-id" => "FnehdDisputesFrontNavItem",
                "type" => "normal",
                "href" => esc_url($routes['my_disputes']),
                "icon" => "arrow-left",
                "title" => __("My Disputes", "fnehousing"),
                "collapse-id" => "",
                "submenus" => [],
            ],
			[
                "li-classes" => "",
                "li-id" => "FnehdDisputesFrontNavItem",
                "type" => "normal",
                "href" => esc_url($routes['disputes_against_me']),
                "icon" => "arrow-right",
                "title" => __("Disputes Against Me", "fnehousing"),
                "collapse-id" => "",
                "submenus" => [],
            ],
        ],
    ],
	
];

// User Profile Menu
$user_name = strlen($user_data["user_login"]) > 10 ? substr(esc_html($user_data["user_login"]), 0, 10) . '..' : esc_html($user_data["user_login"]);
$user_img = fnehd_image($user_data["user_image"], 35, "rounded-circle");

$profile_menu = [
    [
        "li-classes" => "",
        "li-id" => "FnehdUserProfileFrontNavItem",
        "active_classes" => esc_attr($active_class['user_profile']),
        "type" => "drop-down",
        "href" => "javascript:;",
        "icon" => "",
        "title" => $user_img . '<span class="text-capitalize"> ' . $user_name . '</span>',
        "collapse-id" => "FnehdManageShelterCollapse",
        "submenus" => [
            [
                "li-classes" => "",
                "li-id" => "FnehdMyProfileFrontNavItem",
                "type" => "normal",
                "href" => esc_url($routes['user_profile']),
                "icon" => "user",
                "title" => __("My Profile", "fnehousing"),
                "collapse-id" => "",
                "submenus" => [],
            ],
            [
                "li-classes" => "",
                "li-id" => "FnehdLogOutFrontNavItem",
                "type" => "normal",
                "href" => "#",
                "icon" => "sign-out",
                "title" => __("Log out", "fnehousing"),
                "collapse-id" => "",
                "submenus" => [],
            ],
            [
                "li-classes" => "",
                "li-id" => "FnehdBalanceListFrontNavItem",
                "type" => "normal",
                "href" => "#",
                "icon" => "user_group",
                "title" => __("Balance: ", "fnehousing") . esc_html($user_data["balance"]) . ' ' . FNEHD_CURRENCY,
                "collapse-id" => "",
                "submenus" => [],
            ],
        ],
    ],
	
];
