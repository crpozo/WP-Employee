<?php

/**
 * Plugin Name:       WP Employees
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Allows an overview of your employees.
 * Version:           1.0.0
 * Author:            Carlos Pozo
 * Author URI:        https://author.example.com/
 * License:           GPL v3 or later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * */

declare(strict_types=1);

// Prevents triggering code from visiting url 
if( ! defined( 'ABSPATH' ) ) exit;

class FeaturedEmployee{
  function __construct() {
    add_action('init', [$this, 'OnInit']);
  }

  function OnInit() {
    wp_register_script('featuredEmployeeScript', plugin_dir_url(__FILE__) . 'build/index.js', array('wp-blocks', 'wp-i18n', 'wp-editor', 'jquery'));
    wp_register_script( 'bootstrapScript', plugin_dir_url(__FILE__) . 'node_modules/bootstrap/dist/js/bootstrap.bundle.min.js', true);
    wp_register_style('featuredEmployeeStyle', plugin_dir_url(__FILE__) . 'build/index.css');
    wp_register_style( 'bootstrapStyle', plugin_dir_url(__FILE__) . 'node_modules/bootstrap/dist/css/bootstrap.min.css' );
    wp_enqueue_script('bootstrapScript');
    wp_enqueue_style('bootstrapStyle');

    // Query employees external fake API
    $url = 'https://my-json-server.typicode.com/crpozo/staff/employees';
    $arguments = array(
      'method' => 'GET'
    );

    $response = wp_remote_get ($url,$arguments);    

    if( wp_remote_retrieve_response_code($response) == 200 ){
      $bodyResponse = wp_remote_retrieve_body($response);
      $employees = json_decode($bodyResponse,true);    

      wp_localize_script('featuredEmployeeScript','queryEmployees',
        array(
          'response' => $employees
          )
      );
      
      register_block_type('plugin/featured-employee', array(
        'editor_script' => 'featuredEmployeeScript',
        'editor_style' => 'featuredEmployeeStyle'
      ));

    }

    if(is_wp_error($response)){
      $error_message = $response->get_error_message();
      echo "Something went wrong: $error_message";
    }    
  }

}

$featuredEmployee = new FeaturedEmployee();