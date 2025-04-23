<?php
/**
 * Admin settings for HideThisContentUntilLogin plugin
 *
 * @package HideThisContentUntilLogin
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Class for handling admin settings
 */
class HTCUL_Admin {

    /**
     * Initialize the admin settings
     */
    public function init() {
        add_action('admin_menu', array($this, 'add_settings_page'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    /**
     * Add settings page to WordPress admin menu
     */
    public function add_settings_page() {
        add_options_page(
            __('HideThisContentUntilLogin Settings', 'hide-this-content-until-login'),
            __('HideThisContentUntilLogin', 'hide-this-content-until-login'),
            'manage_options',
            'htcul-settings',
            array($this, 'render_settings_page')
        );
    }

    /**
     * Register plugin settings
     */
    public function register_settings() {
        register_setting(
            'htcul_options',
            'htcul_options',
            array($this, 'sanitize_options')
        );

        add_settings_section(
            'htcul_section_logged_in',
            __('Logged-in Users Content', 'hide-this-content-until-login'),
            array($this, 'render_section_logged_in'),
            'htcul-settings'
        );

        add_settings_section(
            'htcul_section_anonymous',
            __('Anonymous Visitors Content', 'hide-this-content-until-login'),
            array($this, 'render_section_anonymous'),
            'htcul-settings'
        );

        // Logged-in users fields
        add_settings_field(
            'logged_in_header',
            __('HTML Header for logged-in users', 'hide-this-content-until-login'),
            array($this, 'render_textarea_field'),
            'htcul-settings',
            'htcul_section_logged_in',
            array('field' => 'logged_in_header')
        );

        add_settings_field(
            'logged_in_footer',
            __('HTML Footer for logged-in users', 'hide-this-content-until-login'),
            array($this, 'render_textarea_field'),
            'htcul-settings',
            'htcul_section_logged_in',
            array('field' => 'logged_in_footer')
        );

        // Anonymous visitors fields
        add_settings_field(
            'anonymous_header',
            __('HTML Header for anonymous visitors', 'hide-this-content-until-login'),
            array($this, 'render_textarea_field'),
            'htcul-settings',
            'htcul_section_anonymous',
            array('field' => 'anonymous_header')
        );

        add_settings_field(
            'anonymous_footer',
            __('HTML Footer for anonymous visitors', 'hide-this-content-until-login'),
            array($this, 'render_textarea_field'),
            'htcul-settings',
            'htcul_section_anonymous',
            array('field' => 'anonymous_footer')
        );
    }

    /**
     * Render the settings page
     */
    public function render_settings_page() {
        if (!current_user_can('manage_options')) {
            return;
        }
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields('htcul_options');
                do_settings_sections('htcul-settings');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Render the logged-in section description
     */
    public function render_section_logged_in() {
        echo '<p>' . esc_html__('Configure the HTML content to be displayed before and after the restricted content for logged-in users.', 'hide-this-content-until-login') . '</p>';
    }

    /**
     * Render the anonymous section description
     */
    public function render_section_anonymous() {
        echo '<p>' . esc_html__('Configure the HTML content to be displayed before and after the restricted content for anonymous visitors.', 'hide-this-content-until-login') . '</p>';
    }

    /**
     * Render textarea field
     *
     * @param array $args Field arguments.
     */
    public function render_textarea_field($args) {
        $options = get_option('htcul_options', array(
            'logged_in_header' => '',
            'logged_in_footer' => '',
            'anonymous_header' => '',
            'anonymous_footer' => ''
        ));

        $field = $args['field'];
        $value = isset($options[$field]) ? $options[$field] : '';
        ?>
        <textarea 
            name="htcul_options[<?php echo esc_attr($field); ?>]"
            id="htcul_options_<?php echo esc_attr($field); ?>"
            rows="5"
            cols="50"
            class="large-text code"><?php echo esc_textarea($value); ?></textarea>
        <p class="description"><?php esc_html_e('Enter plain HTML. This content will be displayed before/after the restricted content.', 'hide-this-content-until-login'); ?></p>
        <?php
    }

    /**
     * Sanitize options
     *
     * @param array $input The input options.
     * @return array The sanitized options.
     */
    public function sanitize_options($input) {
        $sanitized_input = array();

        if (isset($input['logged_in_header'])) {
            $sanitized_input['logged_in_header'] = wp_kses_post($input['logged_in_header']);
        }

        if (isset($input['logged_in_footer'])) {
            $sanitized_input['logged_in_footer'] = wp_kses_post($input['logged_in_footer']);
        }

        if (isset($input['anonymous_header'])) {
            $sanitized_input['anonymous_header'] = wp_kses_post($input['anonymous_header']);
        }

        if (isset($input['anonymous_footer'])) {
            $sanitized_input['anonymous_footer'] = wp_kses_post($input['anonymous_footer']);
        }

        return $sanitized_input;
    }
}