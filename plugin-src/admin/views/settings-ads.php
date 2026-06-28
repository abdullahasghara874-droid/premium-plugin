<?php
/**
 * Ad removal settings view
 */

defined('ABSPATH') || exit;

$ad_selectors = get_option('livenettv_pro_ad_selectors', array());
$ad_shortcodes = get_option('livenettv_pro_ad_shortcodes', array());

if (empty($ad_selectors)) {
    $default_selectors = array(
        '.popup-ad',
        '.banner-ad',
        '.sticky-ad',
        '.sidebar-ad',
        '.header-ad',
        '.footer-ad',
        '.in-content-ad',
        '.download-page-ad',
        '.ad-container',
        '.advertisement',
        '[id*="google_ads_"]',
        '[id*="ads-"]',
        '[id*="ad-"]',
        'ins.adsbygoogle',
        '.adsbygoogle',
    );
} else {
    $default_selectors = $ad_selectors;
}

?>
<div class="wrap livenettv-pro-admin">
    <h1><span class="dashicons dashicons-hidden"></span> <?php _e('Ad Removal Settings', 'livenettv-pro'); ?></h1>

    <div class="notice notice-info">
        <p><?php _e('Configure CSS selectors and shortcodes used to identify and remove advertisements for Pro users.', 'livenettv-pro'); ?></p>
    </div>

    <form method="post" action="options.php">
        <?php settings_fields('livenettv_pro_ads'); ?>

        <h2><?php _e('Ad CSS Selectors', 'livenettv-pro'); ?></h2>

        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="livenettv_pro_ad_selectors"><?php _e('CSS Selectors', 'livenettv-pro'); ?></label>
                </th>
                <td>
                    <textarea name="livenettv_pro_ad_selectors" id="livenettv_pro_ad_selectors" rows="12" class="large-text" placeholder="<?php esc_attr_e('One selector per line...', 'livenettv-pro'); ?>"><?php echo esc_textarea(is_array($default_selectors) ? implode("\n", $default_selectors) : $default_selectors); ?></textarea>
                    <p class="description">
                        <?php _e('Enter one CSS selector per line. These elements will be hidden for Pro users.', 'livenettv-pro'); ?>
                        <br>
                        <?php _e('Examples: <code>.popup-ad</code>, <code>#header-banner</code>, <code>[class*="adsby"]</code>', 'livenettv-pro'); ?>
                    </p>
                </td>
            </tr>
        </table>

        <h2><?php _e('Ad Shortcodes', 'livenettv-pro'); ?></h2>

        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="livenettv_pro_ad_shortcodes"><?php _e('Shortcodes to Remove', 'livenettv-pro'); ?></label>
                </th>
                <td>
                    <textarea name="livenettv_pro_ad_shortcodes" id="livenettv_pro_ad_shortcodes" rows="4" class="large-text" placeholder="<?php esc_attr_e('ad, adsense, banner...', 'livenettv-pro'); ?>"><?php echo esc_textarea(is_array($ad_shortcodes) ? implode("\n", $ad_shortcodes) : $ad_shortcodes); ?></textarea>
                    <p class="description">
                        <?php _e('Enter shortcode tags (without brackets) that should be removed for Pro users. One per line.', 'livenettv-pro'); ?>
                    </p>
                </td>
            </tr>
        </table>

        <?php submit_button(__('Save Ad Settings', 'livenettv-pro')); ?>
    </form>

    <div class="livenettv-pro-preview-section">
        <h2><?php _e('How Ad Removal Works', 'livenettv-pro'); ?></h2>

        <div class="livenettv-pro-ad-removal-explanation">
            <h3><?php _e('CSS-based Removal', 'livenettv-pro'); ?></h3>
            <ol>
                <li><?php _e('Pro users have special CSS injected in the header', 'livenettv-pro'); ?></li>
                <li><?php _e('All selected CSS elements get display:none by default', 'livenettv-pro'); ?></li>
                <li><?php _e('JavaScript runs on page load to catch dynamic ads', 'livenettv-pro'); ?></li>
                <li><?php _e('Google AdSense (adsbygoogle) is specifically targeted', 'livenettv-pro'); ?></li>
            </ol>

            <h3><?php _e('Content Filter', 'livenettv-pro'); ?></h3>
            <ul>
                <li><?php _e('Post content is filtered before display', 'livenettv-pro'); ?></li>
                <li><?php _e('Specified shortcodes are stripped from content', 'livenettv-pro'); ?></li>
                <li><?php _e('Ad HTML patterns are removed from output', 'livenettv-pro'); ?></li>
            </ul>

            <h3><?php _e('Body Classes', 'livenettv-pro'); ?></h3>
            <ul>
                <li><code>livenettv-pro-user</code> - <?php _e('Added to body for Pro users', 'livenettv-pro'); ?></li>
                <li><code>livenettv-free-user</code> - <?php _e('Added to body for non-Pro users', 'livenettv-pro'); ?></li>
            </ul>
            <p><?php _e('Use these classes to show/hide elements:', 'livenettv-pro'); ?></p>
            <pre>.livenettv-pro-user .show-only-free { display: none !important; }
.livenettv-free-user .show-only-pro { display: none !important; }</pre>
        </div>
    </div>
</div>
