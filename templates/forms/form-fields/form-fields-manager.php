<?php
/**
 * Generates form fields dynamically based on the provided configuration.
 * This function iterates through the `$fields` array to generate a variety of form fields.
 *
 * @since      1.0.0
 * @package    Fnehousing
 *
 * @param array  $fields    Array of field definitions.
 * @param string $form_type The form type (e.g., 'edit' or 'create'), used to adjust field IDs.
 *
 * @return void Outputs the generated HTML for the form fields.
 */

defined('ABSPATH') || exit;

$output = '';

foreach ($fields as $field) {
    // Ensure required keys exist in the $field array
    if (!isset($field['id'], $field['name'], $field['type'], $field['title'])) {
        continue; // Skip invalid fields
    }

    $id = ($form_type === 'edit') ? 'Edit' . $field['id'] : $field['id'];
    $div_id = $id . 'DivID';
    $div_class = isset($field['div-class']) ? esc_attr($field['div-class']) : '';
    $div_style = isset($field['display']) && !$field['display'] ? 'style="display: none;"' : '';
    $field_name = esc_attr($field['name']);
    $field_placeholder = isset($field['placeholder']) ? esc_attr($field['placeholder']) : '';
    $is_required = !empty($field['required']) ? 'required' : '';

    // Start outer div
    $output .= "<div class='form-group {$div_class}' id='{$div_id}' {$div_style}>";

    // Add label or heading
    if (!in_array($field['type'], ['checkbox', 'subheading', 'hidden'], true)) {
        $output .= "<label for='{$field_name}'>" . esc_html($field['title']);
        if (!empty($field['help-info'])) {
            $help_info = esc_attr($field['help-info']);
            $output .= "<a class='infopop' tabindex='0' data-toggle='popover' data-placement='right' data-content='{$help_info}' title='Help here'>Info</a>";
        }
        $output .= "</label>";
    } elseif ($field['type'] === 'subheading') {
        $output .= "<h4>" . esc_html($field['title']) . "</h4>";
    }

    // Add input fields
    switch ($field['type']) {
        case 'textarea':
            $output .= "<textarea name='{$field_name}' id='{$id}' class='form-control' rows='4' placeholder='{$field_placeholder}' {$is_required}></textarea>";
            break;

        case 'select':
        case 'multi_select':
            $output .= "<select name='{$field_name}' id='{$id}' class='form-control' " . ($field['type'] === 'multi_select' ? 'multiple' : '') . ">";
            if (isset($field['callback']) && is_array($field['callback'])) {
                foreach ($field['callback'] as $option => $option_title) {
                    if (is_array($field['callback']) && array_keys($field['callback']) === range(0, count($field['callback']) - 1)) {
                        // Handle non-associative array
                        $option_value = esc_attr($option_title);
                        $option_label = ucwords(esc_html($option_title));
                    } else {
                        // Handle associative array
                        $option_value = esc_attr($option);
                        $option_label = esc_html($option_title);
                    }
                    $output .= "<option value='{$option_value}'>{$option_label}</option>";
                }
            }
            $output .= "</select>";
            break;

        case 'radio':
            if (isset($field['callback']) && is_array($field['callback'])) {
                foreach ($field['callback'] as $option) {
                    $option = trim($option);
                    $output .= "<label><input type='radio' name='{$field_name}' value='" . esc_attr($option) . "' class='form-control'> <span class='text-light radio'>" . esc_html($option) . "</span></label>";
                }
            }
            break;

        case 'checkbox':
            $output .= "
            <div class='togglebutton'>
                <label><span style='color: #06accd;' class='title'>" . esc_html($field['title']) . "</span></label>
                <label>
                    <input type='checkbox' id='{$id}' name='{$field_name}'>
                    <span class='toggle'></span>
                </label>
                <span id='{$id}_setting_on_off'></span>
            </div>";
            break;

        case 'file':
            if (function_exists('fnehd_is_front_user') && fnehd_is_front_user()) {
                $output .= "
                <div class='fileinput fileinput-new text-center' data-provides='fileinput'>
                    <div class='fileinput-preview fileinput-exists thumbnail'></div>
                    <div>
                        <span class='btn btn-round fnehd-btn-primary btn-file btn-sm'>
                            <span class='fileinput-new'>Add File</span>
                            <span class='fileinput-exists'>Change File</span>
                            <input name='{$field_name}' class='form-control' type='file'>
                        </span>
                        <a href='javascript:;' class='btn btn-danger btn-round fileinput-exists fnehd-btn-sm' data-dismiss='fileinput'><i class='fa fa-times'></i> Remove</a>
                    </div>
                </div>";
            } else {
                $output .= "
                <i class='fnehd-chat-attachment fa fa-paperclip attachment text-dark lead p-1 border' aria-hidden='true'></i>
                <input name='{$field_name}' class='" . esc_attr($field['callback']) . "' type='hidden'>
                <div class='w-25 {$field_placeholder}'></div>";
            }
            break;

        case 'image':
            $output .= "
            <div class='p-0'>																							
                <div class='fileinput fileinput-new' data-provides='fileinput'>
                    <div class='fileinput-new {$id}-FilePrv'>
                    </div>
                    <div class='fileinput-preview fileinput-exists thumbnail'></div>
                    <div>
                        <span class='btn btn-outline-primary btn-file btn-sm'>
                            <span class='fileinput-new {$id} {$id}-AddFile'>Add " . esc_html($field['title']) . "</span>
                            <span class='fileinput-exists {$id} {$id}-ChangeFile'>Change Image</span>
                        </span>
                        <a href='javascript:;' class='btn btn-outline-danger fileinput-exists fnehd-btn-sm {$id}-dismissPic'
                            data-dismiss='fileinput'><i class='fa fa-times'></i> Remove Image</a>
                        <input class='widefat {$id}-FileInput' id='{$id}' name='{$field_name}' type='hidden' value=''>
                    </div>
                </div>
            </div>";
            break;
			
		case 'gallery':
			 $output .= "
				<div class='{$id}-container'>
					<button type='button' class='{$id}-upload-button btn btn-sm btn-outline-primary'>".__('Add gallery Images', 'fnehousing')."</button>
					<button type='button' class='{$id}-add-more btn btn-sm btn-outline-primary' style='display:none;'>".__('Add Images', 'fnehousing')."</button>
					<input type='hidden' class='{$id}-input-field' id='{$id}' name='{$field_name}'>
					<div class='w-100 {$id}-preview-container'></div>
					<button type='button' class='{$id}-remove-all btn btn-sm btn-outline-danger' style='display:none;'>".__('Remove All', 'fnehousing')."</button>
				</div>";
			break;
		
        default:
            $output .= "<input name='{$field_name}' id='{$id}' class='form-control' type='" . esc_attr($field['type']) . "'";
            $output .= $field_placeholder ? " placeholder='{$field_placeholder}'" : '';
            $output .= isset($field['callback']) ? " value='" . esc_attr($field['callback']) . "'" : '';
            $output .= ($field['type'] === 'number') ? " step='any'" : '';
            $output .= " {$is_required}><div class='well text-danger' id='{$id}_notice'></div>";
            break;
    }

    // End outer div
    $output .= "</div><br>";
}

echo $output;