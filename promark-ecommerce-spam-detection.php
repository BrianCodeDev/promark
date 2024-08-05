<?php
/**
 * Plugin Name: Promark - eCommerce Product Spam Detection Tool
 * Plugin URI: http://yourwebsite.com/promark
 * Description: A tool for detecting and managing spammy product listings in WooCommerce.
 * Version: 1.0
 * Author: Your Name
 * Author URI: http://yourwebsite.com
 * License: GPL2
 */

// Security measure to prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Include necessary files
include_once(plugin_dir_path(__FILE__) . 'includes/promark-functions.php');

// Activation and Deactivation hooks
register_activation_hook(__FILE__, 'promark_activate');
register_deactivation_hook(__FILE__, 'promark_deactivate');

// Activation function
function promark_activate() {
    // Code to run on plugin activation, e.g., create default options
    add_option('promark_spam_keywords', 'buy now, free, guarantee, click here');
}

// Deactivation function
function promark_deactivate() {
    // Code to run on plugin deactivation, e.g., clean up options
    delete_option('promark_spam_keywords');
}
function promark_load_textdomain() {
    load_plugin_textdomain('promark', false, basename(dirname(__FILE__)) . '/languages');
}

add_action('plugins_loaded', 'promark_load_textdomain');