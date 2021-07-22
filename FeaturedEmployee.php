<?php

/**
 * Plugin Name:       WP Employees
 * Plugin URI:        https://github.com/crpozo/WP-Staff
 * Description:       Allows an overview of your employees.
 * Version:           1.0.0
 * Author:            Carlos Pozo
 * Author URI:        https://github.com/crpozo/
 * License:           GPL v3 or later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * */

declare(strict_types=1);

namespace Plugin\Employee;

if (!defined('ABSPATH')) {
    exit;
}

class FeaturedEmployee
{

    private const URL = 'https://my-json-server.typicode.com/crpozo/staff/employees';

    public function __construct()
    {

        add_action('init', [$this, 'onInit']);
    }

    public function onInit()
    {
        define('BOOTSTRAP_VERSION', '5.0.2');

        wp_register_script('featuredEmployeeScript', plugin_dir_url(__FILE__) . 'build/index.js', ['wp-blocks', 'wp-editor', 'jquery']);
        wp_register_script('bootstrapScript', plugin_dir_url(__FILE__) . 'node_modules/bootstrap/dist/js/bootstrap.bundle.min.js', 'BOOTSTRAP_VERSION', true);
        wp_register_style('featuredEmployeeStyle', plugin_dir_url(__FILE__) . 'build/index.css');
        wp_register_style('bootstrapStyle', plugin_dir_url(__FILE__) . 'node_modules/bootstrap/dist/css/bootstrap.min.css', false, 'BOOTSTRAP_VERSION');
        wp_enqueue_script('bootstrapScript');
        wp_enqueue_style('bootstrapStyle');

        $this->queryEmployeeAPI();
    }

    public function queryEmployeeAPI(): bool
    {

        $arguments = [
        'method' => 'GET',
        ];

        $response = wp_remote_get(self::URL, $arguments);

        if (wp_remote_retrieve_response_code($response) === 200) {
            $bodyResponse = wp_remote_retrieve_body($response);
            $employees = json_decode($bodyResponse, true);

            wp_localize_script(
                'featuredEmployeeScript',
                'queryEmployees',
                [
                'response' => $employees,
                ]
            );

            register_block_type('plugin/featured-employee', [
            'editor_script' => 'featuredEmployeeScript',
            'editor_style' => 'featuredEmployeeStyle',
            ]);

            return true;
        }

        if (is_wp_error($this->$response)) {
            $errorMessage = $response->get_error_message();
        }

        return false;
    }
}

$featuredEmployee = new FeaturedEmployee();