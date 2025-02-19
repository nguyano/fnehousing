<?php
/**
 * Options Input Callback class for the plugin
 * Render sanitized options fields as callbacks
 * @since      1.0.0
 * @package    Fnehousing
 */

namespace Fnehousing\Api\callbacks;

defined('ABSPATH') || exit;

use Fnehousing\Base\OptionFields;

class OptionsCallbacks extends OptionFields {

    /**
     * Option Field Sanitizer
     * Sanitizes all input fields based on their callback type.
     */
    public function getSanitizedInputs( $input ) {
        $output = array();

        foreach ( $this->options as $option ) {
            // Checkbox sanitization
            if ( $option['callback'] === "checkboxField" ) {
                $output[$option['id']] = isset( $input[$option['id']] ) ? (bool) $input[$option['id']] : false;

            // Multi-select sanitization
            } elseif ( $option['callback'] === "multSelectField" ) {
                $output[$option['id']] = $this->sanitizeArray( $input[$option['id']] ?? [] );

            // General field sanitization
            } else {
                $output[$option['id']] = isset( $input[$option['id']] ) ? sanitize_text_field( $input[$option['id']] ) : '';
            }
        }

        return $output;
    }

    /**
     * Sanitizes array inputs
     */
    private function sanitizeArray( $input ) {
        return is_array( $input ) ? array_map( 'sanitize_text_field', $input ) : [];
    }

    /**
     * Checkbox Field Manager
     */
    public function checkboxField( $args ) {
        $name = esc_attr( $args['label_for'] );
        $icon = esc_attr( $args['icon'] );
        $option_title = esc_html( $args['title'] );
        $option_name = esc_attr( $args['option_name'] );
        $description = esc_html( $args['description'] );
        $divclasses = esc_attr( $args['divclasses'] );
        $checkbox = get_option( $option_name );

        $checked = isset( $checkbox[$name] ) && $checkbox[$name] ? 'checked' : '';
        $toggContent = $checked ? '<b class="toggleOn">ON</b>' : '<b class="toggleOff">OFF</b>';

        echo '<div class="text-light ' . $divclasses . '" id="fnehd_' . $name . '_option">
                <div class="togglebutton">
                    <label>
                        <span class="text-light">
                            <i class="' . ( $icon === "paypal" ? "fa-brands" : "fas" ) . ' fa-' . $icon . ' sett-icon"></i>&nbsp;' . $option_title . '
                        </span>
                        <a class="infopop" tabindex="0" data-toggle="popover" data-trigger="focus" 
                           data-placement="right" data-content="' . $description . '" 
                           title="" data-original-title="Help here">Info</a>
                    </label>
                    <label class="float-right">
                        <input type="checkbox" id="' . $name . '" name="' . $option_name . '[' . $name . ']" value="1" ' . $checked . '>
                        <span id="' . $name . '_on_off" class="toggle">' . $toggContent . '</span>
                    </label>
                </div>
              </div>';
    }

    /**
     * Select Field Manager
     */
    public function selectField( $args ) {
        $name = esc_attr( $args['label_for'] );
        $icon = esc_attr( $args['icon'] );
        $option_title = esc_html( $args['title'] );
        $option_name = esc_attr( $args['option_name'] );
        $description = esc_html( $args['description'] );
        $divclasses = esc_attr( $args['divclasses'] );
        $value = get_option( $option_name );

        echo '<div class="' . $divclasses . '" id="fnehd_' . $name . '_option">	
                <label>
                    <span class="text-light">
                        <i class="fas fa-' . $icon . ' sett-icon"></i>&nbsp;' . $option_title . '
                    </span>
                    <a class="infopop" tabindex="0" data-toggle="popover" data-trigger="focus" 
                        data-placement="right" data-content="' . $description . '" 
                        title="" data-original-title="Help here">Info</a>
                </label>
                <select class="form-control" id="' . $name . '" name="' . $option_name . '[' . $name . ']">';

        $method = $name . '_options'; 
        if ( method_exists( $this, $method ) ) {
            $this->$method( $value[$name] ?? '' );
        }

        echo '  </select>
              </div>';
    }

    /**
     * Multi-Select Field Manager
     */
    public function multSelectField( $args ) {
        $name = esc_attr( $args['label_for'] );
        $icon = esc_attr( $args['icon'] );
        $option_title = esc_html( $args['title'] );
        $option_name = esc_attr( $args['option_name'] );
        $description = esc_html( $args['description'] );
        $divclasses = esc_attr( $args['divclasses'] );
        $value = get_option( $option_name );

        echo '<div class="' . $divclasses . '" id="fnehd_' . $name . '_option">	
                <label>
                    <span class="text-light">
                        <i class="fas fa-' . $icon . ' sett-icon"></i>&nbsp;' . $option_title . '
                    </span>
                    <a class="infopop" tabindex="0" data-toggle="popover" data-trigger="focus" 
                        data-placement="right" data-content="' . $description . '" 
                        title="" data-original-title="Help here">Info</a>
                </label>
                <select class="form-control" id="' . $name . '" name="' . $option_name . '[' . $name . '][]" multiple>';

        $method = $name . '_options'; 
        if ( method_exists( $this, $method ) ) {
            $this->$method( $value[$name] ?? [] ); 
        }

        echo '  </select>
              </div>';
    }
	
	
	/**
     * Text Field Manager
     */
	public function textField($args) {
		// Extract and sanitize the arguments
		$name = esc_attr($args['label_for']);
		$icon = esc_attr($args['icon']);
		$option_title = esc_html($args['title']);
		$inpclasses = esc_attr($args['inpclasses']);
		$option_name = esc_attr($args['option_name']);
		$description = esc_html($args['description']);
		$divclasses = esc_attr($args['divclasses']);
		$placeholder = esc_attr($args['placeholder']);
		$value = get_option($option_name);

		// Start output
		echo '<div class="' . $divclasses . '" id="fnehd_' . $name . '_option">
				<label>
					<span class="text-light">
						<i class="fas fa-' . $icon . ' sett-icon"></i>&nbsp;' . $option_title . '
					</span>
					<a class="infopop" tabindex="0" data-toggle="popover" data-trigger="focus" data-placement="right" 
					   data-content="' . $description . '" title="" data-original-title="Help here">Info</a>';

		// Render special call to action buttons
		$btns = ['rest_api_key', 'rest_api_endpoint_url', 'google_api_key'];
		foreach ($btns as $btn) {
			$href = $btn ==='google_api_key'? 'https://cloud.google.com/maps-platform' : '#';
			$prefix = $btn ==='google_api_key'? 'Get a ' : 'Generate new ';
			$end = explode('_', $btn);
			$tle = $prefix.end($end);
			if ($name === $btn) {
				echo '<a type="button" id="fnehd_' . $btn . '_generator" href="'.$href.'"
						 class="btn btn-round btn-outline-info fnehd-btn-sm w-auto ml-3 upper-case">
						 ' . esc_html($tle) . '
					  </a>';
			}
		}

		// Close the label and add the input field
		echo '</label>
				<input type="text" id="' . $name . '" 
					   class="form-control ' . $inpclasses . '" 
					   name="' . $option_name . '[' . $name . ']" 
					   placeholder="' . $placeholder . '" 
					   value="' . esc_attr($value[$name] ?? '') . '">
				<div class="well text-danger" id="' . $name . '_notice"></div>';

		echo '</div>';
	}

	
	
	// Text Area Field Manager
	public function textareaField($args) {
		$name = esc_attr($args['label_for']);
		$icon = esc_attr($args['icon']);
		$option_title = esc_html($args['title']);
		$inpclasses = esc_attr($args['inpclasses']);
		$option_name = esc_attr($args['option_name']);
		$description = esc_html($args['description']);
		$divclasses = esc_attr($args['divclasses']);
		$placeholder = esc_attr($args['placeholder']);
		$value = get_option($option_name);

		$output = '<div class="' . $divclasses . '" id="fnehd_' . $name . '_option">
					   <label>
						   <span class="text-light">
							   <i class="fas fa-' . $icon . ' sett-icon"></i>&nbsp;' . $option_title . '
						   </span>
						   <a class="infopop" tabindex="0" data-toggle="popover" data-trigger="focus" data-placement="right" 
							  data-content="' . $description . '" title="" data-original-title="Help here">Info</a>';

							// Render special call to action buttons
							$btns = ['rest_api_endpoint_urls'];
							foreach ($btns as $btn) {
								$prefix = 'Generate ';
								$end = explode('_', $btn);
								$tle = $prefix.end($end);
								if ($name === $btn) {
									$output .= '<a type="button" id="fnehd_' . $btn . '_generator" href="#"
											 class="btn btn-round btn-outline-info fnehd-btn-sm w-auto ml-3 upper-case">
											 ' . esc_html($tle) . '
										  </a>';
								}
							}

					// Close the label and add the input field
					$output .= '</label>
					   <textarea class="form-control pl-4 ' . $inpclasses . '" id="' . $name . '" name="' . $option_name . '[' . $name . ']" 
								 placeholder="' . $placeholder . '" rows="10">' . esc_textarea($value[$name] ?? '') . '</textarea>
				   </div>';

		echo $output;
	}
	
	
	// File Field Manager
	public function fileField($args) {
		$name = esc_attr($args['label_for']);
		$icon = esc_attr($args['icon']);
		$option_title = esc_html($args['title']);
		$inpclasses = esc_attr($args['inpclasses']);
		$option_name = esc_attr($args['option_name']);
		$description = esc_html($args['description']);
		$divclasses = esc_attr($args['divclasses']);
		$value = get_option($option_name);

		echo '<div class="' . $divclasses . '" id="fnehd_' . $name . '_option">
				 <label for="' . $name . '">
					 <span class="text-light">
						 <i class="fas fa-' . $icon . ' sett-icon"></i>&nbsp;' . $option_title . '
					 </span>
					 <a class="infopop" tabindex="0" data-toggle="popover" data-trigger="focus" data-placement="right" 
						data-content="' . $description . '" title="" data-original-title="Help here">Info</a>
				 </label>
				 <div style="text-align: center;" class="col-md-4" style="padding-top: 15px !important;">
					 <div class="fileinput fileinput-new text-center">
						 <div class="fileinput-new thumbnail img-circle ' . $name . '-FilePrv">
							 <img class="' . $name . '-PrevUpload" src="' . esc_url($value[$name] ?? '') . '" alt="...">
						 </div>
						 <div class="fileinput-preview fileinput-exists thumbnail"></div>
						 <div>
							 <span class="btn btn-round btn-primary btn-file btn-sm">
								 <span class="fileinput-new ' . $name . ' ' . $name . '-AddFile">Add Logo</span>
								 <span class="fileinput-exists ' . $name . ' ' . $name . '-ChangeFile">Change Image</span>
							 </span>
							 <br>
							 <a href="javascript:;" class="btn btn-danger btn-round fileinput-exists btn-sm ' . $name . '-dismissPic" 
								data-dismiss="fileinput">
								 <i class="fa fa-times"></i> Remove Image
							 </a>
							 <input class="widefat ' . $name . '-FileInput" id="' . $name . '" name="' . $option_name . '[' . $name . ']" 
									type="hidden" value="' . esc_attr($value[$name] ?? '') . '">
						 </div>
					 </div>
				 </div>
			  </div>';
	}
	
	
	// Color Field Manager
	public function colourField($args) {
		$name = esc_attr($args['label_for']);
		$icon = esc_attr($args['icon']);
		$option_title = esc_html($args['title']);
		$inpclasses = esc_attr($args['inpclasses']);
		$option_name = esc_attr($args['option_name']);
		$description = esc_html($args['description']);
		$divclasses = esc_attr($args['divclasses']);
		$placeholder = esc_attr($args['placeholder']);
		$value = get_option($option_name);

		// Default color if no value is saved
		$color_value = isset($value[$name]) ? esc_attr($value[$name]) : '#ffffff';

		echo '<div class="' . $divclasses . '" id="fnehd_' . $name . '_option">
				<label>
					<span class="text-light">
						<i class="fas fa-' . $icon . ' sett-icon"></i>&nbsp;' . $option_title . '
					</span>
					<a class="infopop" tabindex="0" data-toggle="popover" data-trigger="focus" data-placement="right" 
					   data-content="' . $description . '" title="" data-original-title="Help here">Info</a>
				</label>
				<div class="d-inline-flex">
					<input type="text" id="' . $name . '_val" class="mt-0 border w-25 well" 
						   placeholder="' . $placeholder . '" value="' . $color_value . '">
					<input type="color" id="' . $name . '" class="ml-2 border" 
						   name="' . $option_name . '[' . $name . ']" value="' . $color_value . '">
				</div>
				<div class="well text-danger" id="' . $name . '_notice"></div>
			  </div>';
	}
	

    /**
     * Render options dynamically for select fields.
     */
	private function renderOptions($options, $selected_value = '') {
		foreach ($options as $value => $label) {
			$selected = ($value == $selected_value) ? 'selected' : '';
			echo '<option value="' . esc_attr($value) . '" ' . $selected . '>' . esc_html($label) . '</option>';
		}
	} 
	
	private function renderMultOptions($options, array $selected_value = []) {
		foreach ($options as $value => $label) {
			$selected = '';
			foreach ($selected_value as $value_key) {
				// Check if the option is in the stored selected values
				$selected = (is_array($value_key) && in_array($value, $value_key)) ? 'selected' : '';
			}
			echo '<option value="' . esc_attr($value) . '" ' . $selected . '>' . esc_html($label) . '</option>';
		}
	} 
	
	public function theme_class_options($selected_value = '') {
		$options = [
			'dark-edition'  => 'Dark Theme (Default)',
			'light-edition' => 'Light Theme'
		];
		$this->renderOptions($options, $selected_value);
	}
	
	public function auto_dbackup_freq_options($selected_value = '') {
		$options = [
			'monthly'     => 'Monthly',
			'weekly'      => 'Weekly',
			'daily'       => 'Daily',
			'twicedaily'  => 'Twice Daily',
			'hourly'      => 'Hourly'
		];
		$this->renderOptions($options, $selected_value);
	}
	
	public function plugin_interaction_mode_options($selected_value = '') {
		$options = [
			'page'  => 'Pages & Collapsable Dialogs',
			'modal' => 'Sleek Modal Popups'
		];
		$this->renderOptions($options, $selected_value);
	}
	
	
	public function access_role_options($selected_value = '') {
		$options = [
			'administrator' => 'Administrator',
			'editor'        => 'Editor',
			'author'        => 'Author',
			'contributor'   => 'Contributor'
		];
		$this->renderOptions($options, $selected_value);
	}
	
	
	public function refid_xter_type_options($selected_value = '') {
		$options = [
			'0123456789'                                => 'Numeric (0123456789)',
			'abcdefghijklmnopqrstuvwxyz'                => 'Alpha Lowercase (abcdefghijklmnopqrstuvwxyz)',
			'ABCDEFGHIJKLMNOPQRSTUVWXYZ'                => 'Alpha Uppercase (ABCDEFGHIJKLMNOPQRSTUVWXYZ)',
			'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' => 'Alpha (abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ)',
			'0123456789abcdefghijklmnopqrstuvwxyz'      => 'Alphanumeric Lowercase (0123456789abcdefghijklmnopqrstuvwxyz)',
			'0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'      => 'Alphanumeric Uppercase (0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ)',
			'0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' => 'Alphanumeric (0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ)'
		];
		$this->renderOptions($options, $selected_value);
	}

	
	public function admin_nav_style_options($selected_value = '') {
		$options = [
			'sidebar'   => 'Vertical Navigation (Left Sidebar)',
			'top-menu'  => 'Horizontal Navigation (Top Menu)',
			'no-menu'   => 'No Navigation (Use WP Menu)'
		];
		$this->renderOptions($options, $selected_value);
	}

	
	public function shelter_form_style_options($selected_value = '') {
		$options = [
			'normal' => 'Single Thread',
			'tab'    => 'Modern Tabs'
		];
		$this->renderOptions($options, $selected_value);
	}

	public function rest_api_data_options($selected_value = '') {
		$options = [
			'plugin_basics'     => 'Plugin Basics (Version, Name, Developer)',
			'shelters'          => 'Shelters Data',
			'users'             => 'Users Data'
		];
		$this->renderMultOptions($options, $selected_value);
	}


    public function timezone_options( $selected_value = '' ) {
        $zones = fnehd_timezones();
		$this->renderOptions($zones, $selected_value);
    }

    public function currency_options( $selected_value = '' ) {
        $currencies = fnehd_currencies(); 
		$this->renderOptions($currencies, $selected_value);
    }
	
	

}
