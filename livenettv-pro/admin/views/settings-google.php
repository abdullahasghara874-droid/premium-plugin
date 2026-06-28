<?php
defined( 'ABSPATH' ) || exit;

$client_id     = get_option( 'livenettv_pro_google_client_id', '' );
$client_secret = get_option( 'livenettv_pro_google_client_secret', '' );
$redirect_uri  = site_url( '/?livenettv_auth=google' );
?>
<div class="lntv-settings-section">
    <h2><?php esc_html_e( 'Google OAuth Settings', 'livenettv-pro' ); ?></h2>
    <p class="description">
        <?php esc_html_e( 'To enable Google Sign-In, you need to create OAuth credentials in the Google Cloud Console.', 'livenettv-pro' ); ?>
        <a href="https://console.cloud.google.com/apis/credentials" target="_blank"><?php esc_html_e( 'Go to Google Cloud Console', 'livenettv-pro' ); ?></a>
    </p>
</div>

<form method="post" action="options.php">
    <?php
    settings_fields( 'livenettv_pro_google' );
    ?>
    <table class="form-table">
        <tr>
            <th scope="row">
                <label for="livenettv_pro_google_client_id"><?php esc_html_e( 'Client ID', 'livenettv-pro' ); ?></label>
            </th>
            <td>
                <input type="text" name="livenettv_pro_google_client_id" id="livenettv_pro_google_client_id" value="<?php echo esc_attr( $client_id ); ?>" class="regular-text">
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="livenettv_pro_google_client_secret"><?php esc_html_e( 'Client Secret', 'livenettv-pro' ); ?></label>
            </th>
            <td>
                <input type="password" name="livenettv_pro_google_client_secret" id="livenettv_pro_google_client_secret" value="<?php echo esc_attr( $client_secret ); ?>" class="regular-text">
            </td>
        </tr>
        <tr>
            <th scope="row">
                <?php esc_html_e( 'Redirect URI', 'livenettv-pro' ); ?>
            </th>
            <td>
                <code class="lntv-code-block"><?php echo esc_url( $redirect_uri ); ?></code>
                <p class="description">
                    <?php esc_html_e( 'Add this URL to your Google OAuth application as an authorized redirect URI.', 'livenettv-pro' ); ?>
                </p>
            </td>
        </tr>
    </table>

    <?php submit_button( __( 'Save Google Settings', 'livenettv-pro' ) ); ?>
</form>
