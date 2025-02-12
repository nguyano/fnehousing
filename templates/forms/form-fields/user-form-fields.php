<?php  
        
		
$fields = [
    [ 
        'title' => 'Ajax Action',
        'id' => 'FnehdUserAjaxAction', 
        'name' => 'action', 
        'type' => 'hidden',  
        'placeholder' => '',
        'div-class' => 'col-md-12', 
        'callback' => 'fnehd_insert_user',
        'display' => false,
        'help-info' => '', 
        'required' => false
    ],
    [ 
        'title' => 'Ajax Nonce',
        'id' => 'FnehdUserAjaxNonce', 
        'name' => 'nonce', 
        'type' => 'hidden',  
        'placeholder' => '',                    
        'div-class' => 'col-md-12', 
        'callback' => wp_create_nonce( 'fnehd_user_nonce' ),
        'display' => false,
        'help-info' => '', 
        'required' => false
    ],
    [ 
        'title' => 'User ID',
        'id' => 'FnehdUserID', 
        'name' => 'ID', 
        'type' => 'hidden',  
        'placeholder' => '',    
        'div-class' => 'col-md-12', 
        'callback' => '',
        'display' => false,
        'help-info' => '',
        'required' => false
    ],
    [ 
        'title' => __('Username', 'fnehousing'),
        'id' => 'FnehdUserUsername', 
        'name' => 'user_login', 
        'type' => 'text',  
        'placeholder' => __('Enter username', 'fnehousing'),    
        'div-class' => 'col-md-4', 
        'callback' => '',
        'display' => true,
        'help-info' => '', 
        'required' => true
    ],
    [ 
        'title' => __('Email', 'fnehousing'),
        'id' => 'FnehdUserEmail', 
        'name' => 'user_email', 
        'type' => 'text',  
        'placeholder' => __('Enter Email', 'fnehousing'),    
        'div-class' => 'col-md-4', 
        'callback' => '',
        'display' => true,
        'help-info' => '', 
        'required' => true
    ],
    [ 
        'title' => __('Password', 'fnehousing'),
        'id' => 'FnehdUserPass', 
        'name' => 'user_pass', 
        'type' => 'text',  
        'placeholder' => '',    
        'div-class' => 'col-md-4', 
        'callback' => '',
        'display' => $form_type == "add" ? true : false,
        'help-info' => '', 
        'required' => $form_type == "add" ? true : false,
    ],
    [
        'title' => __('First Name', 'fnehousing'),
        'id' => 'FnehdUserFirstName',
        'name' => 'first_name',
        'type' => 'text',
        'placeholder' => __('Enter First Name', 'fnehousing'),
        'div-class' => 'col-md-4',
        'callback' => '',
        'display' => true,
        'help-info' => '',
        'required' => true
    ],
    [
        'title' => __('Last Name', 'fnehousing'),
        'id' => 'FnehdUserLastName',
        'name' => 'last_name',
        'type' => 'text',
        'placeholder' => __('Enter Last Name', 'fnehousing'),
        'div-class' => 'col-md-4',
        'callback' => '',
        'display' => true,
        'help-info' => '',
        'required' => true
    ],
    [
        'title' => __('Phone', 'fnehousing'),
        'id' => 'FnehdUserPhone',
        'name' => 'phone',
        'type' => 'text',
        'placeholder' => __('Enter Phone Number', 'fnehousing'),
        'div-class' => 'col-md-4',
        'callback' => '',
        'display' => true,
        'help-info' => '',
        'required' => false
    ],
	[
        'title' => __('Affiliation', 'fnehousing'),
        'id' => 'FnehdUserAffiliation',
        'name' => 'affiliation',
        'type' => 'select',
        'placeholder' => "",
        'div-class' => 'col-md-4',
        'callback' => ['UCHealth - UC Hospital', 'UCHealth - Highlands Ranch'],
        'display' => true,
        'help-info' => '',
        'required' => false
    ],
    [
        'title' => __('Website', 'fnehousing'),
        'id' => 'FnehdUserUrl',
        'name' => 'user_url',
        'type' => 'text',
        'placeholder' => __('Enter Website URL', 'fnehousing'),
        'div-class' => $form_type == "add" ? 'col-md-4' : 'col-md-8',
        'callback' => '',
        'display' => true,
        'help-info' => '',
        'required' => false
    ],
	[ 
	    'title' => __('Country', 'fnehousing'),
        'id' => 'FnehdUserCountry', 
        'name' => 'country', 
        'type' => 'select',  
        'placeholder' => '',
        'div-class' => 'col-md-4', 
        'callback' => fnehd_countries(),
        'display' => true,
        'help-info' => '', 
        'required' => false
    ],	
    [ 
	    'title' => __('Address', 'fnehousing'),
        'id' => 'FnehdUserAddress', 
        'name' => 'address', 
        'type' => 'text',  
        'placeholder' => __('Enter Address', 'fnehousing'),
        'div-class' => 'col-md-8', 
        'callback' => '',
        'display' => true,
        'help-info' => '', 
        'required' => false
    ],
    [ 
	    'title' => __('User Role', 'fnehousing'),
        'id' => 'FnehdUserRole', 
        'name' => 'front_role', 
        'type' => 'select',  
        'placeholder' => '',
        'div-class' => 'col-md-2', 
        'callback' => [ 'fne-nurse' => __('FNE Nurse/Worker', 'fnehousing'), 'shelter-manager' => __('Shelter Manager', 'fnehousing') ],
        'display' => true,
        'help-info' => '', 
        'required' => false
    ],	
    [ 
	    'title' => __('Status', 'fnehousing'),
        'id' => 'FnehdUserStatus', 
        'name' => 'status', 
        'type' => 'select',  
        'placeholder' => '',
        'div-class' => 'col-md-2', 
        'callback' => [ 1 => __('Active User', 'fnehousing'), 0 => __('Inactive User', 'fnehousing') ],
        'display' => true,
        'help-info' => '', 
        'required' => false
    ],
    [
        'title' => __('User Bio', 'fnehousing'),
        'id' => 'FnehdUserBio',
        'name' => 'bio',
        'type' => 'textarea',
        'placeholder' => __('Enter Short Bio', 'fnehousing'),
        'div-class' => 'col-md-10',
        'callback' => '',
        'display' => true,
        'help-info' => '',
        'required' => false
    ],
    [
        'title' => __('User Image', 'fnehousing'),
        'id' => 'FnehdUserImg',
        'name' => 'user_image',
        'type' => 'image',
        'placeholder' => '',
        'div-class' => 'col-md-2',
        'callback' => '',
        'display' => true,
        'help-info' => '',
        'required' => false
    ]
];


if($form_type == "edit"){ //update ajax action field
	$fields[0]['callback'] = 'fnehd_update_user'; 
}
include FNEHD_PLUGIN_PATH."templates/forms/form-fields/form-fields-manager.php"; 