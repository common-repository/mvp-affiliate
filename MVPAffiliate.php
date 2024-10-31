<?php
/*
Plugin Name: MVP Affiliate
Description: Modifies microsoft affiliate links in the content by adding your MVP content creator parameter to the query string
Version: 1.1
Author: Peter Smulovics
Author URI: https://dotneteers.net
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Stable Tag: 1.1
Requires at least: 6.5
Tested up to: 6.6.1
*/

// Hook into the_content filter to modify links
add_filter('the_content', 'microsoft_mvp_modify_links');

function microsoft_mvp_modify_links($content) {
    // Get the configured string parameter from the plugin settings
    $parameter = get_option('microsoft_mvp_parameter');

    $pattern = '/<a(.*?)href=["\'](https?:\/\/.*(?:microsoft\.com|visualstudio\.com)[^\?\s"\']*\?[^\?\s"\']*)["\'](.*?)>/i';
    $replacement = '<a$1href="$2&wt.md_id=' . $parameter . '"$3>';
    $content = preg_replace($pattern, $replacement, $content);

    $pattern = '/<a(.*?)href=["\'](https?:\/\/.*(?:microsoft\.com|visualstudio\.com)[^\?\s"\']*)["\'](.*?)>/i';
    $replacement = '<a$1href="$2?wt.md_id=' . $parameter . '"$3>';
    $content = preg_replace($pattern, $replacement, $content);



    return $content;
}

// Register the plugin settings
add_action('admin_init', 'microsoft_mvp_register_settings');

function microsoft_mvp_register_settings() {
    // Register a new setting for the plugin
    register_setting('microsoft_mvp_settings', 'microsoft_mvp_parameter');
}

// Add a settings page for the plugin
add_action('admin_menu', 'microsoft_mvp_add_settings_page');

function microsoft_mvp_add_settings_page() {
    // Add a new submenu page under the Settings menu
    add_options_page('MVP Affiliate Settings', 'MVP Affiliate', 'manage_options', 'mvp-plugin-settings', 'microsoft_mvp_render_settings_page');
}

function microsoft_mvp_render_settings_page() {
    ?>
    <div class="wrap">
        <h1>MVP Plugin Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('microsoft_mvp_settings'); ?>
            <?php do_settings_sections('microsoft_mvp_settings'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Your MVP ID (like 'mvp_123456')</th>
                    <td><input type="text" name="microsoft_mvp_parameter" value="<?php echo esc_attr(get_option('microsoft_mvp_parameter')); ?>" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}