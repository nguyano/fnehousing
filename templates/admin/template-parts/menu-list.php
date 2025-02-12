 <?php
  
/**
 * Admin Menu List for Fnehousing
 * Defines fnehousing menu elements
 * 
 * @Since   1.0.0
 * @package Fnehousing
 */
 
 defined('ABSPATH') || exit;
 
$menus = [
		[
		 "li-classes" => "",  "li-id" => "FnehdDashMenuItem",
		 "type" => "normal",
		 "href" => "admin.php?page=fnehousing-dashboard",
		 "icon" => "grip-horizontal",
		 "title" => __("Dashboard", "fnehousing"),
		 "collapse-id" => "",
		 "submenus" => []
		],
		
		[
		 "li-classes" => "",  "li-id" => "FnehdShelterMenuItem",
		 "type" => "normal",
		 "href" => "admin.php?page=fnehousing-shelters",
		 "icon" => "house-chimney-user",
		 "title" => __("Shelters", "fnehousing"),
		 "collapse-id" => "FnehdShelterSubMenu",
		 "submenus" => []
		],
		
		/* [
		 "li-classes" => "",  "li-id" => "FnehdLogMenuItem",
		 "type" => "normal",
		 "href" => "admin.php?page=fnehousing-transaction-log",
		 "icon" => "file-invoice-dollar",
		 "title" => __("Transaction Log", "fnehousing"),
		 "collapse-id" => "FnehdShelterSubMenu",
		 "submenus" => []
		], */
		
		/* [
		 "li-classes" => "",  "li-id" => "FnehdInvoiceMenuItem",
		 "type" => "drop-down",
		 "href" => "#FnehdInvoiceSubMenu",
		 "icon" => "user-group",
		 "title" => __("Shelter Invoices", "fnehousing"),
		 "collapse-id" => "FnehdInvoiceSubMenu",
		 "submenus" => [
						[
						  "li-classes" => "",  "li-id" => "FnehdDeposits",
						  "type" => "normal",
						  "href" => "admin.php?page=fnehousing-deposits",
						  "icon" => "coins",
						  "title" => __("Deposits", "fnehousing"),
						  "collapse-id" => "",
						  "submenus" => []
						],
						[
						  "li-classes" => "",  "li-id" => "FnehdWithdrawals",
						  "type" => "normal",
						  "href" => "admin.php?page=fnehousing-withdrawals",
						  "icon" => "coins",
						  "title" => __("Withdrawals", "fnehousing"),
						  "collapse-id" => "",
						  "submenus" => []
						]
					]	
		], */
		
		[
		 "li-classes" => "",  "li-id" => "FnehdUserMenuItem",
		 "type" => "normal",
		 "href" => "admin.php?page=fnehousing-users",
		 "icon" => "user-group",
		 "title" => __("FNE Users", "fnehousing"),
		 "collapse-id" => "FnehdUserSubMenu",
		 "submenus" => []	
		],
		
		[
		 "li-classes" => "",  "li-id" => "FnehdStatsMenuItem",
		 "type" => "normal",
		 "href" => "admin.php?page=fnehousing-stats",
		 "icon" => "chart-pie",
		 "title" => __("Metrics", "fnehousing"),
		 "collapse-id" => "",
		 "submenus" => []	
		],
		
		[
		 "li-classes" => "",  "li-id" => "FnehdDBMenuItem",
		 "type" => "drop-down",
		 "href" => "#FnehdDBSubMenu",
		 "icon" => "database",
		 "title" => __("DB Backup", "fnehousing"),
		 "collapse-id" => "FnehdDBSubMenu",
		 "submenus" => [
						[
						  "li-classes" => "",  "li-id" => "FnehdMgeDBs",
						  "type" => "normal",
						  "href" => "admin.php?page=fnehousing-db-backups",
						  "icon" => "download",
						  "title" => __("Manage Backups", "fnehousing"),
						  "collapse-id" => "",
						  "submenus" => []
						],
						
						[
						  "li-classes" => "",  "li-id" => "FnehdQuikResDB",
						  "type" => "normal",
						  "href" => "#",
						  "icon" => "upload",
						  "title" => __("Quick Restore", "fnehousing"),
						  "collapse-id" => "",
						  "submenus" => []
						]
					]	
		],
		
		/* [
		 "li-classes" => "",  "li-id" => "FnehdSupportMenuItem",
		 "type" => "normal",
		 "href" => "admin.php?page=fnehousing-disputes",
		 "icon" => "people-arrows",
		 "title" => __("Disputes", "fnehousing"),
		 "collapse-id" => "FnehdSupportSubMenu",
		 "submenus" => []
		], */
		 
		[
		 "li-classes" => "d-none d-md-block",  "li-id" => "FnehdSettMenuItem",
		 "type" => "normal",
		 "href" => "admin.php?page=fnehousing-settings",
		 "icon" => "screwdriver-wrench",
		 "title" => __("Settings", "fnehousing"),
		 "collapse-id" => "",
		 "submenus" => []
		],
		
		[
		 "li-classes" => "d-block d-md-none",  "li-id" => "FnehdMblSettMenuItem",
		 "type" => "normal",
		 "href" => "admin.php?page=fnehousing-settings",
		 "icon" => "screwdriver-wrench",
		 "title" => __("Settings", "fnehousing"),
		 "collapse-id" => "",
		 "submenus" => []
		],
		
		[
		 "li-classes" => "",  "li-id" => "FnehdHelpMenuItem",
		 "type" => "drop-down",
		 "href" => "#FnehdHelpSubMenu",
		 "icon" => "question-circle",
		 "title" => __("Help", "fnehousing"),
		 "collapse-id" => "FnehdHelpSubMenu",
		 "submenus" => [
						[
						  "li-classes" => "",  "li-id" => "FnehdAboutUs",
						  "type" => "normal",
						  "href" => "#",
						  "icon" => "circle-info",
						  "title" => __("About Fnehousing", "fnehousing"),
						  "collapse-id" => "",
						  "submenus" => []
						],
						[
						  "li-classes" => "",  "li-id" => "FnehdShortCode",
						  "type" => "normal",
						  "href" => "#",
						  "icon" => "expand",
						  "title" => __("Shortcodes", "fnehousing"),
						  "collapse-id" => "",
						  "submenus" => []
						],
						
						[
						  "li-classes" => "",  "li-id" => "FnehdDocumentation",
						  "type" => "normal",
						  "href" => "#",
						  "icon" => "list",
						  "title" => __("Documentation", "fnehousing"),
						  "collapse-id" => "",
						  "submenus" => []
						]
					]
		]
		
		
];	