<?php
/**
 * Plugin Name: HideThisContentUntilLogin
 * Description: Provides a custom Gutenberg block to restrict content visibility based on user login status.
 * Version: 0.1.0
 * Author: Waylayer
 * Text Domain: hide-this-content-until-login
 * Domain Path: /languages
 * License: GPL-2.0+
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Define plugin constants
 */
define('HTCUL_VERSION', '0.1.0');
define('HTCUL_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('HTCUL_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Register the block and its assets
 */
function htcul_register_block() {
    // Register block script
    wp_register_script(
        'htcul-restricted-content-block',
        HTCUL_PLUGIN_URL . 'build/index.js',
        array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n'),
        HTCUL_VERSION,
        true
    );

    // Register block editor styles
    wp_register_style(
        'htcul-restricted-content-block-editor',
        HTCUL_PLUGIN_URL . 'build/index.css',
        array('wp-edit-blocks'),
        HTCUL_VERSION
    );

    // Register block
    register_block_type('htcul/restricted-content', array(
        'editor_script' => 'htcul-restricted-content-block',
        'editor_style' => 'htcul-restricted-content-block-editor',
        'render_callback' => 'htcul_render_restricted_content_block'
    ));
}
add_action('init', 'htcul_register_block');

/**
 * Server-side rendering for the restricted content block
 *
 * @param array    $attributes Block attributes.
 * @param string   $content    Block content.
 * @return string  Modified block content.
 */
function htcul_render_restricted_content_block($attributes, $content) {
    // Get visibility setting
    $show_to = isset($attributes['showTo']) ? $attributes['showTo'] : 'logged-in';
    
    // Get settings
    $options = get_option('htcul_options', array(
        'logged_in_header' => '',
        'logged_in_footer' => '',
        'anonymous_header' => '',
        'anonymous_footer' => ''
    ));
    
    // Check user status
    $is_logged_in = is_user_logged_in();
    
    // Determine if content should be shown
    $show_content = false;
    $header = '';
    $footer = '';
    
    if ($is_logged_in && $show_to === 'logged-in') {
        $show_content = true;
        $header = wp_kses_post($options['logged_in_header']);
        $footer = wp_kses_post($options['logged_in_footer']);
    } elseif (!$is_logged_in && $show_to === 'anonymous') {
        $show_content = true;
        $header = wp_kses_post($options['anonymous_header']);
        $footer = wp_kses_post($options['anonymous_footer']);
    }
    
    // Return content with headers and footers if it should be shown
    if ($show_content) {
        return $header . $content . $footer;
    }
    
    // Otherwise return empty string
    return '';
}

/**
 * Include admin settings page
 */
require_once HTCUL_PLUGIN_DIR . 'admin/class-htcul-admin.php';

/**
 * Initialize admin settings
 */
function htcul_init_admin() {
    $admin = new HTCUL_Admin();
    $admin->init();
}
add_action('plugins_loaded', 'htcul_init_admin');