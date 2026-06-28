<?php
/**
 * Google OAuth settings view
 */

defined('ABSPATH') || exit;

?>
<div class="wrap livenettv-pro-admin">
    <h1><span class="dashicons dashicons-google"></span> <?php _e('Google Sign-In Settings', 'livenettv-pro'); ?></h1>

    <div class="notice notice-info">
        <p>
            <strong><?php _e('Redirect URI:', 'livenettv-pro'); ?></strong>
            <code><?php echo esc_html(site_url('/?livenettv_auth=google')); ?></code>
            <button type="button" class="button button-small livenettv-pro-copy-btn" data-copy="<?php echo esc_attr(site_url('/?livenettv_auth=google')); ?>">
                <?php _e('Copy', 'livenettv-pro'); ?>
            </button>
        </p>
        <p><?php _e('Use this URI in your Google Cloud Console when configuring OAuth 2.0 credentials.', 'livenettv-pro'); ?></p>
    </div>

    <div class="livenettv-pro-setting-card">
        <h2><?php _e('Setup Instructions', 'livenettv-pro'); ?></h2>
        <ol>
            <li><?php _e('Go to <a href="https://console.cloud.google.com/" target="_blank">Google Cloud Console</a>', 'livenettv-pro'); ?></li>
            <li><?php _e('Create a new project or select existing one', 'livenettv-pro'); ?></li>
            <li><?php _e('Enable Google+ API', 'livenettv-pro'); ?></li>
            <li><?php _e('Go to "Credentials" and create OAuth 2.0 Client ID', 'livenettv-pro'); ?></li>
            <li><?php _e('Add the Redirect URI shown above to "Authorized redirect URIs"', 'livenettv-pro'); ?></li>
            <li><?php _e('Copy the Client ID and Client Secret below', 'livenettv-pro'); ?></li>
        </ol>
    </div>

    <form method="post" action="options.php">
        <?php settings_fields('livenettv_pro_google'); ?>

        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="livenettv_pro_google_client_id"><?php _e('Google Client ID', 'livenettv-pro'); ?></label>
                </th>
                <td>
                    <input type="text" name="livenettv_pro_google_client_id" id="livenettv_pro_google_client_id" value="<?php echo esc_attr(get_option('livenettv_pro_google_client_id', '')); ?>" class="regular-text">
                    <p class="description"><?php _e('Your Google OAuth 2.0 Client ID from Google Cloud Console.', 'livenettv-pro'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="livenettv_pro_google_client_secret"><?php _e('Google Client Secret', 'livenettv-pro'); ?></label>
                </th>
                <td>
                    <input type="password" name="livenettv_pro_google_client_secret" id="livenettv_pro_google_client_secret" value="<?php echo esc_attr(get_option('livenettv_pro_google_client_secret', '')); ?>" class="regular-text">
                    <p class="description"><?php _e('Your Google OAuth 2.0 Client Secret from Google Cloud Console.', 'livenettv-pro'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="livenettv_pro_redirect_after_login"><?php _e('Redirect After Login', 'livenettv-pro'); ?></label>
                </th>
                <td>
                    <input type="url" name="livenettv_pro_redirect_after_login" id="livenettv_pro_redirect_after_login" value="<?php echo esc_attr(get_option('livenettv_pro_redirect_after_login', '')); ?>" class="regular-text" placeholder="<?php echo esc_attr(home_url('/pro/')); ?>">
                    <p class="description"><?php _e('URL where users will be redirected after successful Google login. Leave empty for default.', 'livenettv-pro'); ?></p>
                </td>
            </tr>
        </table>

        <?php submit_button(__('Save Google Settings', 'livenettv-pro')); ?>
    </form>

    <div class="livenettv-pro-test-section">
        <h2><?php _e('Test Configuration', 'livenettv-pro'); ?></h2>
        <?php
        $client_id = get_option('livenettv_pro_google_client_id');
        $client_secret = get_option('livenettv_pro_google_client_secret');
        ?>
        <?php if (empty($client_id) || empty($client_secret)) : ?>
            <div class="notice notice-warning inline">
                <p><?php _e('Please enter your Google Client ID and Client Secret to enable Google Sign-In.', 'livenettv-pro'); ?></p>
            </div>
        <?php else : ?>
            <div class="notice notice-success inline">
                <p><?php _e('Google Sign-In is configured. Use the shortcode [livenettv_login_button] to display the login button.', 'livenettv-pro'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>
