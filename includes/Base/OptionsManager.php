<?php
/**
 * The options manager class of the plugin.
 * Dynamically adds all setting options, sections, and fields,
 * and creates usable option constants.
 * 
 * @since      1.0.0
 * @package    Fnehousing
 */

namespace Fnehousing\Base;

defined('ABSPATH') || exit;

use Fnehousing\Api\SettingsApi;
use Fnehousing\Api\Callbacks\OptionsCallbacks;

class OptionsManager extends OptionFields {

    private $settings;
    private $callbacks;

    /**
     * Initialize hooks, settings, sections, and fields.
     */
    public function register() {
        $this->settings = new SettingsApi();
        $this->callbacks = new OptionsCallbacks();

        $this->initializeSettings();
        $this->settings->register();
        $this->defineOptionConstants();

        $this->registerAjaxActions();
    }
	
	
    /**
     * Register AJAX actions.
     */
    private function registerAjaxActions() {
        add_action('wp_ajax_fnehd_exp_options', [$this, 'exportOptions']);
        add_action('wp_ajax_fnehd_imp_options', [$this, 'importOptions']);
        add_action('wp_ajax_fnehd_reset_options', [$this, 'resetOptions']);
        add_action('wp_ajax_fnehd_options', [$this, 'sendOptionsToAjax']);
        add_action('fnehd_default_options', [$this, 'setDefaultOptions']);
    }

    /**
     * Initialize settings, sections, and fields.
     */
    private function initializeSettings() {
        $this->defineSettings();
        $this->defineSections();
        $this->defineFields();
    }

    /**
     * Define plugin settings.
     */
    private function defineSettings() {
        $args = [
            [
                'option_group' => 'fnehousing_plugin_settings',
                'option_name'  => 'fnehousing_options',
                'callback'     => [$this->callbacks, 'getSanitizedInputs'],
            ],
        ];
        $this->settings->setSettings($args);
    }

    /**
     * Define settings sections.
     */
    private function defineSections() {
        $sections = array_map(function ($section) {
            return [
                'id'    => esc_attr($section['id']),
                'title' => sprintf(
                    '<div id="%s" class="form-group col-md-12"><br><label><h4>%s</h4></label></div>',
                    esc_attr($section['id']),
                    esc_html($section['title'])
                ),
                'page'  => esc_attr($section['page']),
            ];
        }, $this->sections);

        $this->settings->setSections($sections);
    }

    /**
     * Define settings fields.
     */
    private function defineFields() {
        $args = array_map(function ($option) {
            return [
                'id'       => esc_attr($option['id']),
                'title'    => '',
                'callback' => [$this->callbacks, $option['callback']],
                'page'     => esc_attr($option['page']),
                'section'  => esc_attr($option['section']),
                'args'     => [
                    'option_name' => 'fnehousing_options',
                    'label_for'   => esc_attr($option['id']),
                    'placeholder' => esc_attr($option['placeholder']),
                    'inpclasses'  => 'ui-toggle',
                    'description' => esc_html($option['description']),
                    'divclasses'  => esc_attr($option['divclasses']),
                    'title'       => esc_html($option['title']),
                    'icon'        => esc_attr($option['icon']),
                ],
            ];
        }, $this->options);

        $this->settings->setFields($args);
    }

    /**
     * Define option constants based on saved options.
     */
    private function defineOptionConstants() {
        $saved_options = $this->getSavedOptions();

        foreach ($this->options as $option) {
            $constant = strtoupper('fnehd_' . $option['id']);
            $value = $saved_options[$option['id']];
			$value = isset($value) ? $value : $option['default'];
            defined($constant) || define($constant, $value);
        }
    }

    /**
     * Retrieve saved options.
     *
     * @return array Associative array of saved options.
     */
    private function getSavedOptions() {
        return get_option('fnehousing_options', []);
    }

    /**
     * Set default options.
     */
    public function setDefaultOptions() {
        $default_options = array_column($this->options, 'default', 'id');
        update_option('fnehousing_options', $default_options);
    }

    /**
     * Export plugin options as JSON.
     */
    public function exportOptions() {
        fnehd_verify_permissions('manage_options');

        $options = $this->getSavedOptions();

        if (empty($options)) {
            wp_send_json_error(__('No options found to export.', 'fnehousing'), 404);
        }

        $sanitized_options = array_map('sanitize_text_field', $options);
        wp_send_json_success(['options' => wp_json_encode($sanitized_options), 'message' => __('Options exported successfully.', 'fnehousing')]);
    }

    /**
     * Import plugin options from a JSON file.
     */
    public function importOptions() {
        fnehd_verify_permissions('manage_options');

        if (empty($_FILES['option_file']['tmp_name'])) {
            wp_send_json_error(__('No file uploaded.', 'fnehousing'), 400);
        }

        $file_contents = file_get_contents($_FILES['option_file']['tmp_name']);
        $options = json_decode($file_contents, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($options)) {
            update_option('fnehousing_options', $options);
            wp_send_json_success(__('Options imported successfully.', 'fnehousing'));
        } else {
            wp_send_json_error(__('Invalid JSON file.', 'fnehousing'), 400);
        }
    }

    /**
     * Reset plugin options to default.
     */
    public function resetOptions() {
        fnehd_verify_permissions('manage_options');
        $this->setDefaultOptions();
        wp_send_json_success(__('Options restored to defaults successfully.', 'fnehousing'));
    }

    /**
     * Retrieve options and send them via AJAX.
     */
    public function sendOptionsToAjax() {
        fnehd_verify_permissions('manage_options');
        wp_send_json($this->getSavedOptions());
    }

	
}
