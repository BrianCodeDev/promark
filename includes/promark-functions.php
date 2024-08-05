<?php

// Security measure to prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Function to check for spammy content
function promark_check_product_for_spam($post_id) {
    // Verify if the post is a product and if it's not a revision
    if (get_post_type($post_id) !== 'product' || wp_is_post_revision($post_id)) {
        return;
    }

    // Get the product description
    $product_description = get_post_field('post_content', $post_id);
    $spam_keywords = get_option('promark_spam_keywords', '');

    // Convert keywords to an array
    $spam_keywords_array = array_map('trim', explode(',', $spam_keywords));

    foreach ($spam_keywords_array as $keyword) {
        if (stripos($product_description, $keyword) !== false) {
            // Mark as spam
            update_post_meta($post_id, '_is_spam', '1');
            return;
        }
    }

    // If no spam found, mark as not spam
    update_post_meta($post_id, '_is_spam', '0');
}

// Hook into WooCommerce product save
add_action('save_post_product', 'promark_check_product_for_spam');

// Function to add admin menu
function promark_add_admin_menu() {
    add_options_page(
        'Promark Settings',
        'Promark',
        'manage_options',
        'promark-settings',
        'promark_settings_page'
    );
}

add_action('admin_menu', 'promark_add_admin_menu');

// Function to display the settings page
function promark_settings_page() {
    ?>
    <div class="wrap">
        <h1>Promark - eCommerce Product Spam Detection Tool</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('promark_settings_group');
            do_settings_sections('promark-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Function to register settings
function promark_register_settings() {
    register_setting('promark_settings_group', 'promark_spam_keywords');
    add_settings_section('promark_main_section', 'Main Settings', null, 'promark-settings');
    add_settings_field('promark_spam_keywords', 'Spam Keywords', 'promark_spam_keywords_callback', 'promark-settings', 'promark_main_section');
}

add_action('admin_init', 'promark_register_settings');

// Callback function for spam keywords
function promark_spam_keywords_callback() {
    $keywords = get_option('promark_spam_keywords', '');
    echo '<input type="text" name="promark_spam_keywords" value="' . esc_attr($keywords) . '" class="regular-text" />';
}
