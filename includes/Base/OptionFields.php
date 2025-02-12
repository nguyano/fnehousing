<?php

/**
 * Option Fields Class
 * Defines parameters for all option fields
 *
 * Since     1.0.0.
 * @package  Fnehousing
 */
 
 
namespace Fnehousing\Base;

defined('ABSPATH') || exit;

class OptionFields {

    public $options;
	
	public $sections;

    public function __construct() {
		
        $this->options = [
		
            // General Settings
			[
				'id'          =>  'plugin_interaction_mode',
				'title'       =>  __("Plugin Interaction Mode", "fnehousing"),
				'callback'    =>  'selectField',
				'page'        =>  'fnehousing_general',
				'section'     =>  'fnehd_general_settings',
				'placeholder' =>  "",
				'description' =>  __("Choose how users interact with the plugin.", "fnehousing"),
				'divclasses'  =>  'col-md-6 card shadow-lg p-3',
				'icon'        =>  'cog',
				'default'     =>  'page'
			],

			[
				'id'          =>  'access_role',
				'title'       =>  __("Plugin Access Role", "fnehousing"),
				'callback'    =>  'selectField',
				'page'        =>  'fnehousing_general',
				'section'     =>  'fnehd_general_settings',
				'placeholder' =>  "",
				'description' =>  __("Choose which other admin user role other than Administrators, has access to the plugin. NB: Administrators are granted access by default. Also, only administrators can alter plugin settings", "fnehousing"),
				'divclasses'  =>  'col-md-6 p-3 card shadow-lg',
				'icon'        =>  'lock',
				'default'     =>  'editor'
			],

			[
				'id'          =>  'currency',
				'title'       =>  __("Currency", "fnehousing"),
				'callback'    =>  'selectField',
				'page'        =>  'fnehousing_general',
				'section'     =>  'fnehd_general_settings',
				'placeholder' =>  "",
				'description' =>  __("Default currency for transactions.", "fnehousing"),
				'divclasses'  =>  'col-md-6 card shadow-lg p-3',
				'icon'        =>  'money-bill',
				'default'     =>  'USD'
			],

			[
				'id'          =>  'timezone',
				'title'       =>  __("Timezone", "fnehousing"),
				'callback'    =>  'selectField',
				'page'        =>  'fnehousing_general',
				'section'     =>  'fnehd_general_settings',
				'placeholder' =>  "",
				'description' =>  __("Set a default timezone for the plugin (default is: UTC GMT+0:00)", "fnehousing"),
				'divclasses'  =>  'col-md-6 card shadow-lg p-3',
				'icon'        =>  'clock',
				'default'     =>  'UTC'
			],

			[
				'id'          =>  'company_address',
				'title'       =>  __("Company Address", "fnehousing"),
				'callback'    =>  'textField',
				'page'        =>  'fnehousing_general',
				'section'     =>  'fnehd_general_settings',
				'placeholder' =>  __("Enter company address", "fnehousing"),
				'description' =>  __("Enter the formal address of Your Company (used to Populate Email/invoice/waybill headers & footers)", "fnehousing"),
				'divclasses'  =>  'col-md-6 card shadow-lg p-3',
				'icon'        =>  'map-marker',
				'default'     =>  ''
			],

			[
				'id'          =>  'company_phone',
				'title'       =>  __("Company Phone", "fnehousing"),
				'callback'    =>  'textField',
				'page'        =>  'fnehousing_general',
				'section'     =>  'fnehd_general_settings',
				'placeholder' =>  __("Enter company phone", "fnehousing"),
				'description' =>  __("Enter your Company's Office phone (used to Populate Email/invoice/waybill headers & footers)", "fnehousing"),
				'divclasses'  =>  'col-md-6 card shadow-lg p-3',
				'icon'        =>  'phone',
				'default'     =>  ''
			],

			[
				'id'          =>  'company_logo',
				'title'       =>  __("Company Logo", "fnehousing"),
				'callback'    =>  'fileField',
				'page'        =>  'fnehousing_company_logo',
				'section'     =>  'fnehd_company_logo_settings',
				'placeholder' =>  "",
				'description' =>  __("Upload your company logo (will be displayed on invoices). Desired size: 156 x 36px", "fnehousing"),
				'divclasses'  =>  'col-md-6 card shadow-lg p-3',
				'icon'        =>  'image',
				'default'     =>  ''
			],

			[
				'id'          =>  'logo_width',
				'title'       =>  __("Logo Width", "fnehousing"),
				'callback'    =>  'textField',
				'page'        =>  'fnehousing_company_logo',
				'section'     =>  'fnehd_company_logo_settings',
				'placeholder' =>  __("Enter width in px", "fnehousing"),
				'description' =>  __("Define a suitable Logo width (in pixels) for your logo", "fnehousing"),
				'divclasses'  =>  'col-md-6 p-3',
				'icon'        =>  '',
				'default'     =>  '156'
			],

			[
				'id'          =>  'logo_height',
				'title'       =>  __("Logo Height", "fnehousing"),
				'callback'    =>  'textField',
				'page'        =>  'fnehousing_company_logo',
				'section'     =>  'fnehd_company_logo_settings',
				'placeholder' =>  __("Enter height in px", "fnehousing"),
				'description' =>  __("Define a suitable Logo height (in pixels) for your logo", "fnehousing"),
				'divclasses'  =>  'col-md-6 p-3',
				'icon'        =>  '',
				'default'     =>  '36'
			],
			// Shelter Options
            [
				'id'          =>  'shelter_form_style',
				'title'       =>  __("Order Form Style", "fnehousing"),
				'callback'    =>  'selectField',
				'page'        =>  'fnehousing_backend_shelter',
				'section'     =>  'fnehd_backend_shelter_settings',
				'placeholder' =>  "",
				'description' =>  __("Select the shelter form style for adding and editing shelters, options include simple flow and tabs", "fnehousing"),
				'divclasses'  =>  'col-md-12 card shadow-lg p-3',
				'icon'        =>  'house-chimney-user',
				'default'     =>  'normal'
			],
			// Reference ID
            [
				'id'          =>  'refid_length',
				'title'       =>  __("Reference ID Length", "fnehousing"),
				'callback'    =>  'textField',
				'page'        =>  'fnehousing_ref_id',
				'section'     =>  'fnehd_ref_id_settings',
				'placeholder' =>  __("enter a digit..e.g 12.", "fnehousing"),
				'description' =>  __("Enter desired autogenerated reference ID length (number of characters..e.g 14) this will be used to auto generate reference IDs for shelters. The default length is 12 digits. Note: Reference ID is a unique identifier for shelters.", "fnehousing"),
				'divclasses'  =>  'col-md-6 card shadow-lg p-3',
				'icon'        =>  'pen-ruler',
				'default'     =>  '12'
			],

			[
				'id'          =>  'refid_xter_type',
				'title'       =>  __("Reference ID Character Type", "fnehousing"),
				'callback'    =>  'selectField',
				'page'        =>  'fnehousing_ref_id',
				'section'     =>  'fnehd_ref_id_settings',
				'placeholder' =>  __("enter a digit..e.g 12.", "fnehousing"),
				'description' =>  __("Select the desired character type for autogenerated reference id (e.g. numeric, alphanumeric..etc). This will be used to auto generate the reference IDs for shelters. Default is Numeric", "fnehousing"),
				'divclasses'  =>  'col-md-6 card shadow-lg p-3',
				'icon'        =>  'arrow-up-a-z',
				'default'     =>  '0123456789'
			],
			
			
            // Email Settings
			[
				'id'          =>  'company_email',
				'title'       =>  __("Admin/Company Email", "fnehousing"),
				'callback'    =>  'textField',
				'page'        =>  'fnehousing_email',
				'section'     =>  'fnehd_email_settings',
				'placeholder' =>  __("Enter company email, default is WP admin email", "fnehousing"),
				'description' =>  __("Enter Your Company's Email Address (Used as Sender Email Address, Default is WP admin Email)", "fnehousing"),
				'divclasses'  =>  'col-md-12 card shadow-lg p-3',
				'icon'        =>  'envelope',
				'default'     =>  get_option('admin_email')
			],

			[
				'id'          =>  'user_new_shelter_email',
				'title'       =>  __("Notify Users on Shelter Creation", "fnehousing"),
				'callback'    =>  'checkboxField',
				'page'        =>  'fnehousing_email',
				'section'     =>  'fnehd_email_settings',
				'placeholder' =>  "",
				'description' =>  __("Should users receive email notification when their shelters are generated?", "fnehousing"),
				'divclasses'  =>  'col-md-6 card shadow-lg p-3',
				'icon'        =>  'envelope-open-text',
				'default'     =>  false
			],

			[
				'id'          =>  'admin_new_shelter_email',
				'title'       =>  __("Notify Admin on Shelter Creation", "fnehousing"),
				'callback'    =>  'checkboxField',
				'page'        =>  'fnehousing_email',
				'section'     =>  'fnehd_email_settings',
				'placeholder' =>  "",
				'description' =>  __("Should admin receive email notification when an shelter is generated?", "fnehousing"),
				'divclasses'  =>  'col-md-6 card shadow-lg p-3',
				'icon'        =>  'envelope-open-text',
				'default'     =>  true
			],

			[
				'id'          =>  'notify_admin_by_email',
				'title'       =>  __("Send Admin Notifications as Email", "fnehousing"),
				'callback'    =>  'checkboxField',
				'page'        =>  'fnehousing_email',
				'section'     =>  'fnehd_email_settings',
				'placeholder' =>  "",
				'description' =>  __("Receive an email equivalent of admin notifications. These include notifications of tracking attempts, user assignments, user responses, etc.", "fnehousing"),
				'divclasses'  =>  'col-md-6 card shadow-lg p-3',
				'icon'        =>  'envelope-open-text',
				'default'     =>  false
			],

			[
				'id'          =>  'smtp_protocol',
				'title'       =>  __("Send Emails Through Custom SMTP", "fnehousing"),
				'callback'    =>  'checkboxField',
				'page'        =>  'fnehousing_email',
				'section'     =>  'fnehd_email_settings',
				'placeholder' =>  "",
				'description' =>  __("Check to send emails with custom SMTP instead of the default WordPress mail function. If so, please set up the custom SMTP below.", "fnehousing"),
				'divclasses'  =>  'col-md-6 card shadow-lg p-3',
				'icon'        =>  'shield',
				'default'     =>  false
			],

			[
				'id'          =>  'smtp_host',
				'title'       =>  __("SMTP Host", "fnehousing"),
				'callback'    =>  'textField',
				'page'        =>  'fnehousing_smtp',
				'section'     =>  'fnehd_smtp_settings',
				'placeholder' =>  __("Enter SMTP host address", "fnehousing"),
				'description' =>  __("Enter Custom SMTP Host (e.g., smtp.example.com)", "fnehousing"),
				'divclasses'  =>  'col-md-6 p-3',
				'icon'        =>  '',
				'default'     =>  ''
			],

			[
				'id'          =>  'smtp_user',
				'title'       =>  __("SMTP User", "fnehousing"),
				'callback'    =>  'textField',
				'page'        =>  'fnehousing_smtp',
				'section'     =>  'fnehd_smtp_settings',
				'placeholder' =>  __("Enter SMTP username..e.g email@example.com", "fnehousing"),
				'description' =>  __("Enter Username (e.g., email@example.com)", "fnehousing"),
				'divclasses'  =>  'col-md-6 p-3',
				'icon'        =>  '',
				'default'     =>  ''
			],

			[
				'id'          =>  'smtp_pass',
				'title'       =>  __("SMTP Password", "fnehousing"),
				'callback'    =>  'textField',
				'page'        =>  'fnehousing_smtp',
				'section'     =>  'fnehd_smtp_settings',
				'placeholder' =>  __("Enter SMTP password", "fnehousing"),
				'description' =>  __("Enter Email Account Password", "fnehousing"),
				'divclasses'  =>  'col-md-6 p-3',
				'icon'        =>  '',
				'default'     =>  ''
			],

			[
				'id'          =>  'smtp_port',
				'title'       =>  __("SMTP Port", "fnehousing"),
				'callback'    =>  'textField',
				'page'        =>  'fnehousing_smtp',
				'section'     =>  'fnehd_smtp_settings',
				'placeholder' =>  __("Enter SMTP port..e.g 587", "fnehousing"),
				'description' =>  __("Enter SMTP Port (e.g., 587)", "fnehousing"),
				'divclasses'  =>  'col-md-6 p-3',
				'icon'        =>  '',
				'default'     =>  ''
			],

			//User Emails
            [
				'id'          =>  'user_new_shelter_email_subject',
				'title'       =>  __("User New Shelter Email Subject", "fnehousing"),
				'callback'    =>  'textField',
				'page'        =>  'fnehousing_user_shelter_email',
				'section'     =>  'fnehd_user_shelter_email_settings',
				'placeholder' =>  __("Subject for new shelter email", "fnehousing"),
				'description' =>  __("Default subject for user new shelter email.", "fnehousing"),
				'divclasses'  =>  'col-md-12 p-3',
				'icon'        =>  'envelope',
				'default'     =>  self::defaultUserNewShelterEmailSubject()
			],

			[
				'id'          =>  'user_new_shelter_email_body',
				'title'       =>  __("User New Shelter Email Body", "fnehousing"),
				'callback'    =>  'textareaField',
				'page'        =>  'fnehousing_user_shelter_email',
				'section'     =>  'fnehd_user_shelter_email_settings',
				'placeholder' =>  __("Body for new shelter email", "fnehousing"),
				'description' =>  __("Default body for user new shelter email.", "fnehousing"),
				'divclasses'  =>  'col-md-12 p-3',
				'icon'        =>  'file-alt',
				'default'     =>  self::defaultUserNewShelterEmailBody()
			],


			[
				'id'          =>  'user_new_shelter_email_footer',
				'title'       =>  __("User New Shelter Email Footer", "fnehousing"),
				'callback'    =>  'textareaField',
				'page'        =>  'fnehousing_user_shelter_email',
				'section'     =>  'fnehd_user_shelter_email_settings',
				'placeholder' =>  __("Footer for new shelter email", "fnehousing"),
				'description' =>  __("Default footer for user new shelter email.", "fnehousing"),
				'divclasses'  =>  'col-md-12 p-3',
				'icon'        =>  'file-alt',
				'default'     =>  self::defaultUserNewShelterEmailFooter()
			],

			//Admin Emails
            [
				'id'          =>  'admin_new_shelter_email_subject',
				'title'       =>  __("Admin New Shelter Email Subject", "fnehousing"),
				'callback'    =>  'textField',
				'page'        =>  'fnehousing_admin_shelter_email',
				'section'     =>  'fnehd_admin_shelter_email_settings',
				'placeholder' =>  __("Subject for admin new shelter email", "fnehousing"),
				'description' =>  __("Default subject for admin new shelter email.", "fnehousing"),
				'divclasses'  =>  'col-md-12 p-3',
				'icon'        =>  'envelope',
				'default'     =>  self::defaultAdminNewShelterEmailSubject()
			],

			[
				'id'          =>  'admin_new_shelter_email_body',
				'title'       =>  __("Admin New Shelter Email Body", "fnehousing"),
				'callback'    =>  'textareaField',
				'page'        =>  'fnehousing_admin_shelter_email',
				'section'     =>  'fnehd_admin_shelter_email_settings',
				'placeholder' =>  __("Body for admin new shelter email", "fnehousing"),
				'description' =>  __("Default body for admin new shelter email.", "fnehousing"),
				'divclasses'  =>  'col-md-12 p-3',
				'icon'        =>  'file-alt',
				'default'     =>  self::defaultAdminNewShelterEmailBody()
			],

			[
				'id'          =>  'admin_new_shelter_email_footer',
				'title'       =>  __("Admin New Shelter Email Footer", "fnehousing"),
				'callback'    =>  'textareaField',
				'page'        =>  'fnehousing_admin_shelter_email',
				'section'     =>  'fnehd_admin_shelter_email_settings',
				'placeholder' =>  __("Footer for admin new shelter email", "fnehousing"),
				'description' =>  __("Default footer for admin new shelter email.", "fnehousing"),
				'divclasses'  =>  'col-md-12 p-3',
				'icon'        =>  'file-alt',
				'default'     =>  self::defaultAdminNewShelterEmailFooter()
			],

		    //Admin Styling
			[
				'id'          =>  'theme_class',
				'title'       =>  __("Admin Theme Colour Scheme", "fnehousing"),
				'callback'    =>  'selectField',
				'page'        =>  'fnehousing_admin_appearance',
				'section'     =>  'fnehd_admin_appearance_settings',
				'placeholder' =>  "",
				'description' =>  __("Choose between light and dark theme colour schemes", "fnehousing"),
				'divclasses'  =>  'col-md-6 card shadow-lg p-3',
				'icon'        =>  'palette',
				'default'     =>  'light-edition'
			],

			[
				'id'          =>  'admin_nav_style',
				'title'       =>  __("Admin Navigation Menu Style", "fnehousing"),
				'callback'    =>  'selectField',
				'page'        =>  'fnehousing_admin_appearance',
				'section'     =>  'fnehd_admin_appearance_settings',
				'placeholder' =>  "",
				'description' =>  __("Choose desired admin navigation menu style", "fnehousing"),
				'divclasses'  =>  'col-md-6 card shadow-lg p-3',
				'icon'        =>  'palette',
				'default'     =>  'top-menu'
			],

			[
				'id'          =>  'fold_wp_menu',
				'title'       =>  __("Fold WP Menu while Using Plugin", "fnehousing"),
				'callback'    =>  'checkboxField',
				'page'        =>  'fnehousing_admin_appearance',
				'section'     =>  'fnehd_admin_appearance_settings',
				'placeholder' =>  "",
				'description' =>  __("Keep WordPress menu folded while using this plugin? You will still be able to expand & fold it manually.", "fnehousing"),
				'divclasses'  =>  'col-md-6 card shadow-lg p-3',
				'icon'        =>  'minimize',
				'default'     =>  false
			],

			[
				'id'          =>  'fold_fnehd_menu',
				'title'       =>  __("Fold Fnehousing Menu (Sidebar Menu)", "fnehousing"),
				'callback'    =>  'checkboxField',
				'page'        =>  'fnehousing_admin_appearance',
				'section'     =>  'fnehd_admin_appearance_settings',
				'placeholder' =>  "",
				'description' =>  __("Keep Fnehousing menu folded (show only icon menu bar with details on hover)? You will still be able to expand & fold it manually. Works only with vertical navigation (sidebar menu).", "fnehousing"),
				'divclasses'  =>  'col-md-6 card shadow-lg p-3',
				'icon'        =>  'minimize',
				'default'     =>  false
			],

			[
				'id'          =>  'primary_color',
				'title'       =>  __("Primary Frontend Colour", "fnehousing"),
				'callback'    =>  'colourField',
				'page'        =>  'fnehousing_public_appearance',
				'section'     =>  'fnehd_public_appearance_settings',
				'placeholder' =>  __("Select colour", "fnehousing"),
				'description' =>  __("Choose a primary colour to match your brand or website", "fnehousing"),
				'divclasses'  =>  'col-md-6 card shadow-lg p-3',
				'icon'        =>  'code',
				'default'     =>  '#ff5604'
			],

			[
				'id'          =>  'secondary_color',
				'title'       =>  __("Secondary Frontend Colour", "fnehousing"),
				'callback'    =>  'colourField',
				'page'        =>  'fnehousing_public_appearance',
				'section'     =>  'fnehd_public_appearance_settings',
				'placeholder' =>  __("Select colour", "fnehousing"),
				'description' =>  __("Choose a secondary colour to match your brand or website", "fnehousing"),
				'divclasses'  =>  'col-md-6 card shadow-lg p-3',
				'icon'        =>  'code',
				'default'     =>  '#8080ff'
			],

			[
				'id'          =>  'custom_css',
				'title'       =>  __("Custom Css (frontend)", "fnehousing"),
				'callback'    =>  'textareaField',
				'page'        =>  'fnehousing_public_appearance',
				'section'     =>  'fnehd_public_appearance_settings',
				'placeholder' =>  __("Enter all your frontend custom css here", "fnehousing"),
				'description' =>  __("Add all custom CSS for frontend tracking results page", "fnehousing"),
				'divclasses'  =>  'col-md-12 card shadow-lg p-3',
				'icon'        =>  'code',
				'default'     =>  ''
			],

            // Labels
			[
				'id'          =>  'shelter_label',
				'title'       =>  __("Shelter", "fnehousing"),
				'callback'    =>  'textField',
				'page'        =>  'fnehousing_labels',
				'section'     =>  'fnehd_labels_settings',
				'placeholder' =>  __("Default label is Shelter", "fnehousing"),
				'description' =>  __("Enter text to replace the word 'Shelter' at frontend. Default is 'Shelter'", "fnehousing"),
				'divclasses'  =>  'col-md-6 p-3',
				'icon'        =>  '',
				'default'     =>  'Shelter'
			],

			[
				'id'          =>  'shelter_list_label',
				'title'       =>  __("Shelter List", "fnehousing"),
				'callback'    =>  'textField',
				'page'        =>  'fnehousing_labels',
				'section'     =>  'fnehd_labels_settings',
				'placeholder' =>  __("Default label is RECENT SHELTER COMMENTS", "fnehousing"),
				'description' =>  __("Enter text to replace shelter list metric Label at frontend. Default label is Shelter List", "fnehousing"),
				'divclasses'  =>  'col-md-6 p-3',
				'icon'        =>  '',
				'default'     =>  'Shelter List'
			],

			[
				'id'          =>  'login_form_label',
				'title'       =>  __("User Login", "fnehousing"),
				'callback'    =>  'textField',
				'page'        =>  'fnehousing_labels',
				'section'     =>  'fnehd_labels_settings',
				'placeholder' =>  __("Default label is User Login", "fnehousing"),
				'description' =>  __("Enter text to replace user login form Label at frontend. Default label is User Login", "fnehousing"),
				'divclasses'  =>  'col-md-6 p-3',
				'icon'        =>  '',
				'default'     =>  'User Login'
			],

			[
				'id'          =>  'signup_form_label',
				'title'       =>  __("User Signup", "fnehousing"),
				'callback'    =>  'textField',
				'page'        =>  'fnehousing_labels',
				'section'     =>  'fnehd_labels_settings',
				'placeholder' =>  __("Default label is User Signup", "fnehousing"),
				'description' =>  __("Enter text to replace user signup form Label at frontend. Default label is User Signup", "fnehousing"),
				'divclasses'  =>  'col-md-6 p-3',
				'icon'        =>  '',
				'default'     =>  'User Signup'
			],
			
			// DB Backup Options
            [
				'id'          =>  'dbackup_log',
				'title'       =>  __("Enable Database Backup Log", "fnehousing"),
				'callback'    =>  'checkboxField',
				'page'        =>  'fnehousing_dbbackup',
				'section'     =>  'fnehd_dbbackup_settings',
				'placeholder' =>  "",
				'description' =>  __("Should a backup log file be viewable on the backup table?", "fnehousing"),
				'divclasses'  =>  'col-md-6 card shadow-lg p-3',
				'icon'        =>  'list',
				'default'     =>  true
			],

			[
				'id'          =>  'auto_dbackup',
				'title'       =>  __("Enable Auto Database Backup", "fnehousing"),
				'callback'    =>  'checkboxField',
				'page'        =>  'fnehousing_dbbackup',
				'section'     =>  'fnehd_dbbackup_settings',
				'placeholder' =>  "",
				'description' =>  __("Should a backup be created automatically according to a defined schedule? If yes, please choose schedule below.", "fnehousing"),
				'divclasses'  =>  'col-md-6 card shadow-lg p-3',
				'icon'        =>  'sync',
				'default'     =>  false
			],

			[
				'id'          =>  'auto_dbackup_freq',
				'title'       =>  __("Auto DB Backup Frequency", "fnehousing"),
				'callback'    =>  'selectField',
				'page'        =>  'fnehousing_dbbackup',
				'section'     =>  'fnehd_dbbackup_settings',
				'placeholder' =>  "",
				'description' =>  __("Choose the frequency of backups, how often do you want a backup to be created automatically?", "fnehousing"),
				'divclasses'  =>  'col-md-12 card shadow-lg p-3',
				'icon'        =>  'clock',
				'default'     =>  'weekly'
			],
			
			//Google map
			[
				'id'          =>  'google_map_api_key',
				'title'       =>  __("Google Map API key", "fnehousing"),
				'callback'    =>  'textField',
				'page'        =>  'fnehousing_google_map',
				'section'     =>  'fnehd_google_map_settings',
				'placeholder' =>  "",
				'description' =>  __("Google Map API key for maps, places and geocoding", "fnehousing"),
				'divclasses'  =>  'col-md-12 card shadow-lg p-3',
				'icon'        =>  'key',
				'default'     =>  ''
			],
			
            
            // Advanced Options
            [
				'id'          =>  'enable_rest_api',
				'title'       =>  __("Share Shelter Data via REST API?", "fnehousing"),
				'callback'    =>  'checkboxField',
				'page'        =>  'fnehousing_advanced',
				'section'     =>  'fnehd_advanced_settings',
				'placeholder' =>  "",
				'description' =>  __("Want to send plugin REST API Endpoint data through HTTPS Request?", "fnehousing"),
				'divclasses'  =>  'col-md-6 card shadow-lg p-3',
				'icon'        =>  'share-nodes',
				'default'     =>  false
			],

			[
				'id'          =>  'enable_rest_api_key',
				'title'       =>  __("Require API Key for REST API Access?", "fnehousing"),
				'callback'    =>  'checkboxField',
				'page'        =>  'fnehousing_advanced',
				'section'     =>  'fnehd_advanced_settings',
				'placeholder' =>  "",
				'description' =>  __("Want to authenticate REST API Endpoint data request with a key?", "fnehousing"),
				'divclasses'  =>  'col-md-6 card shadow-lg p-3',
				'icon'        =>  'key',
				'default'     =>  false
			],

			[
				'id'          =>  'rest_api_key',
				'title'       =>  __("REST API key for Fnehousing", "fnehousing"),
				'callback'    =>  'textField',
				'page'        =>  'fnehousing_advanced',
				'section'     =>  'fnehd_advanced_settings',
				'placeholder' =>  "",
				'description' =>  __("Create API key for REST API endpoint authentication. Block unauthorized access to plugin REST API data.", "fnehousing"),
				'divclasses'  =>  'col-md-12 card shadow-lg p-3',
				'icon'        =>  'key',
				'default'     =>  ''
			],

			[
				'id'          =>  'rest_api_enpoint_url',
				'title'       =>  __("REST API Endpoint URL", "fnehousing"),
				'callback'    =>  'textField',
				'page'        =>  'fnehousing_advanced',
				'section'     =>  'fnehd_advanced_settings',
				'placeholder' =>  "",
				'description' =>  __("REST API custom Endpoint URL. Do not share with unauthorized users!", "fnehousing"),
				'divclasses'  =>  'col-md-12 card shadow-lg p-3',
				'icon'        =>  'link',
				'default'     =>  ''
			],

			[
				'id'          =>  'rest_api_data',
				'title'       =>  __("Select Shelter Data to Share Via REST API", "fnehousing"),
				'callback'    =>  'multSelectField',
				'page'        =>  'fnehousing_advanced',
				'section'     =>  'fnehd_advanced_settings',
				'placeholder' =>  "",
				'description' =>  __("Choose specific shelter data to share via your REST API Endpoint.", "fnehousing"),
				'divclasses'  =>  'col-md-12 card shadow-lg p-3',
				'icon'        =>  'list-check',
				'default'     =>  []
			],
			
        ];
		
		
		//Setting Sections
		$this->sections = [
			[
				'id'    => 'fnehd_general_settings',
				'title' => __('General Settings', 'fnehousing'),
				'page'  => 'fnehousing_general'
			],
			[
				'id'    => 'fnehd_admin_appearance_settings',
				'title' => __('Admin Appearance Settings', 'fnehousing'),
				'page'  => 'fnehousing_admin_appearance'
			],
			[
				'id'    => 'fnehd_public_appearance_settings',
				'title' => __('Frontend Appearance Settings', 'fnehousing'),
				'page'  => 'fnehousing_public_appearance'
			],
			[
				'id'    => 'fnehd_backend_shelter_settings',
				'title' => __('Shelter Settings', 'fnehousing'),
				'page'  => 'fnehousing_backend_shelter'
			],
			[
				'id'    => 'fnehd_email_settings',
				'title' => __('Email Notification Settings', 'fnehousing'),
				'page'  => 'fnehousing_email'
			],
			[
				'id'    => 'fnehd_dbbackup_settings',
				'title' => __('Database Backup', 'fnehousing'),
				'page'  => 'fnehousing_dbbackup'
			],
			[
				'id'    => 'fnehd_google_map_settings',
				'title' => __('Google Map Settings', 'fnehousing'),
				'page'  => 'fnehousing_google_map'
			],
			[
				'id'    => 'fnehd_advanced_settings',
				'title' => __('Advanced', 'fnehousing'),
				'page'  => 'fnehousing_advanced'
			],
			// Section Groups
			[
				'id'    => 'fnehd_smtp_settings',
				'title' => __('Custom SMTP Setup', 'fnehousing'),
				'page'  => 'fnehousing_smtp'
			],
			[
				'id'    => 'fnehd_company_logo_settings',
				'title' => __('Company Logo', 'fnehousing'),
				'page'  => 'fnehousing_company_logo'
			],
			[
				'id'    => 'fnehd_user_shelter_email_settings',
				'title' => __('Front User Email Settings', 'fnehousing'),
				'page'  => 'fnehousing_user_shelter_email'
			],
			[
				'id'    => 'fnehd_admin_shelter_email_settings',
				'title' => __('Admin Email Settings', 'fnehousing'),
				'page'  => 'fnehousing_admin_shelter_email'
			],
			[
				'id'    => 'fnehd_labels_settings',
				'title' => __('Custom Labels (Change all default labels at the frontend)', 'fnehousing'),
				'page'  => 'fnehousing_labels'
			],
			[
				'id'    => 'fnehd_ref_id_settings',
				'title' => __('Reference ID/Number Options', 'fnehousing'),
				'page'  => 'fnehousing_ref_id'
			]
		];

    }

    /**
     * Default subject for user new shelter email
     * @return string
     */
    private static function defaultUserNewShelterEmailSubject() {
        return __('Your shelter has been successfully created.', 'fnehousing');
    }

    /**
     * Default body for user new shelter email
     * @return string
     */
    private static function defaultUserNewShelterEmailBody() {
        return __('<p>Hello,</p><p>Thank you for adding your shelter. Your shelter has been successfully created and is now active.</p>', 'fnehousing');
    }

    /**
     * Default footer for user new shelter email
     * @return string
     */
    private static function defaultUserNewShelterEmailFooter() {
        return __('<p>Best regards,<br>The Fnehousing Team</p>', 'fnehousing');
    }

    /**
     * Default subject for admin new shelter email
     * @return string
     */
    private static function defaultAdminNewShelterEmailSubject() {
        return __('New Shelter Created', 'fnehousing');
    }

    /**
     * Default body for admin new shelter email
     * @return string
     */
    private static function defaultAdminNewShelterEmailBody() {
        return __('<p>Hello Admin,</p><p>A new shelter has been created on the platform. Please review the shelter details in your admin panel.</p>', 'fnehousing');
    }

    /**
     * Default footer for admin new shelter email
     * @return string
     */
    private static function defaultAdminNewShelterEmailFooter() {
        return __('<p>Best regards,<br>The Fnehousing System</p>', 'fnehousing');
    }
	
	
}
