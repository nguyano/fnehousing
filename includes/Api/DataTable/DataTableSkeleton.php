<?php

/**
 * Fnehousing DataTable Skeleton Class 
 * Defines the DataTable structure and handles its dynamic data and actions.
 *
 * @since      1.0.0
 * @package    Fnehousing
 */
 
namespace Fnehousing\Api\DataTable;

// Exit if accessed directly
defined('ABSPATH') || exit;

class DataTableSkeleton {
    private $table_id;
    private $headers;
    private $actions;
    private $js_data;
    private $data_count;
    private $is_front_user;

    public function __construct($table_id, $headers, $actions = null, $js_data = null, $data_count = 0) {
        $this->table_id = $table_id;
        $this->headers = $headers;
        $this->actions = $actions;
        $this->js_data = $js_data;
        $this->data_count = $data_count;
        $this->is_front_user = fnehd_is_front_user();
    }

    /**
     * Render the Swal.fire alert script.
     */
    private function renderSwalFire() {
        $alert_script = sprintf(
            'Swal.fire("%s", "%s", "warning");',
            esc_html__("No Action Selected!", "fnehousing"),
            esc_html__("Please select an action to continue!", "fnehousing")
        );
        return esc_js($alert_script);
    }

    /**
     * Render the actions dropdown and buttons.
     */
    private function renderActions() {
        if (!isset($this->actions) || $this->data_count < 1) {
            return;
        }

        $output = '<select id="' . esc_attr($this->actions['select_id']) . '">';
        foreach ($this->actions['options'] as $option_id => $label) {
            $output .= '<option value="' . esc_attr($option_id) . '">' . esc_html__($label, 'fnehousing') . '</option>';
        }
        $output .= '</select>';

        foreach ($this->actions['buttons'] as $id => $button) {
			$onclick = $id === 'fnehd-option1'? 'onclick="' . $this->renderSwalFire() . '"' : '';
            $output .= '<div id="' . esc_attr($id) . '" class="collapse fnehd-apply-btn">';
            $output .= '<a type="button" class="' . esc_attr($button['class']) . '" ' . $onclick;
            if (isset($button['delete_action'])) {
                $output .= ' data-action="' . esc_attr($button['delete_action']) . '"';
            }
            $output .= '>';
            $output .= '<i class="fas fa-' . esc_attr($button['icon']) . '"></i> ' . esc_html($button['label']);
            $output .= '</a>';

            if (!empty($button['selected_class'])) {
                $output .= '<span class="' . esc_attr($button['selected_class']) . '" id="fnehd-select-count">';
                $output .= '<b>0</b> ' . __('Selected', 'fnehousing');
                $output .= '</span>';
            }

            $output .= '</div>';
        }

        return $output;
    }

    /**
     * Render the table header.
     */
    private function renderTableHeader() {
        $output = '<thead class="fnehd-data-tbl-header"><tr>';
        foreach ($this->headers as $header) {
            $orderable = isset($header['data-orderable']) ? 'data-orderable="' . esc_attr($header['data-orderable']) . '"' : '';
            $output .= '<th ' . $orderable . ' class="fnehd-th">' . $header['content'] . '</th>';
        }
        $output .= '</tr></thead>';
        return $output;
    }

    /**
     * Render the table.
     */
    private function renderTable() {
        if ($this->data_count <= 0) {
            return '<h3 class="text-dark text-center mt-5">' . __('No records found', 'fnehousing') . '</h3>';
        }

        $table_attributes = '';
        if (isset($this->js_data)) {
            foreach ($this->js_data as $id => $data) {
                $table_attributes .= ' data-' . $id . '="' . esc_attr($data) . '"';
            }
        }

        $output = '<table class="table border fnehd-data-table stripe hover order-column display" id="' . esc_attr($this->table_id) . '" ' . $table_attributes . '>';
        $output .= $this->renderTableHeader();
        $output .= '</table>';

        return $output;
    }

    /**
     * Render the complete DataTable.
     */
    public function render() {
        if (!$this->is_front_user) {
            $actions_html = $this->renderActions();
        } else {
            $actions_html = '';
        }

        $table_html = $this->renderTable();

        return $actions_html . $table_html;
    }
}

