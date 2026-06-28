<?php
defined( 'ABSPATH' ) || exit;

$remove_ads = (bool) get_option( 'livenettv_pro_remove_ads', true );

// Get selectors as array or textarea value.
$selectors_raw = get_option( 'livenettv_pro_ad_selectors', array() );
if ( is_array( $selectors_raw ) ) {
    $selectors_text = implode( "\n", $selectors_raw );
} else {
    $selectors_text = $selectors_raw;
}
?>
<div class="lntv-settings-section">
    <h2><?php esc_html_e( 'Ad Removal Settings', 'livenettv-pro' ); ?></h2>
    <p class="description">
        <?php esc_html_e( 'Ads are removed automatically for Pro members. Configure the CSS selectors to hide.', 'livenettv-pro' ); ?>
    </p>
</div>

<form method="post" action="options.php">
    <?php
    settings_fields( 'livenettv_pro_ads' );
    ?>
    <table class="form-table">
        <tr>
            <th scope="row">
                <?php esc_html_e( 'Enable Ad Removal', 'livenettv-pro' ); ?>
            </th>
            <td>
                <label>
                    <input type="checkbox" name="livenettv_pro_remove_ads" value="1" <?php checked( $remove_ads, true ); ?>>
                    <?php esc_html_e( 'Hide ads for Pro members', 'livenettv-pro' ); ?>
                </label>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="livenettv_pro_ad_selectors"><?php esc_html_e( 'Ad Selectors', 'livenettv-pro' ); ?></label>
            </th>
            <td>
                <textarea
                    name="livenettv_pro_ad_selectors"
                    id="livenettv_pro_ad_selectors"
                    rows="10"
                    class="large-text code"
                    placeholder=".adsbygoogle&#10;.ad-banner&#10;.advertisement"
                ><?php echo esc_textarea( $selectors_text ); ?></textarea>
                <p class="description">
                    <?php esc_html_e( 'One CSS selector per line. These elements will be hidden for Pro members.', 'livenettv-pro' ); ?>
                </p>
            </td>
        </tr>
    </table>

    <?php submit_button( __( 'Save Ad Settings', 'livenettv-pro' ) ); ?>
</form>
