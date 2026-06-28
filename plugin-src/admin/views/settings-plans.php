<?php
/**
 * Plans & Pricing settings view
 */

defined('ABSPATH') || exit;

$membership = livenettv_pro()->get_membership();
$plans = $membership->get_plans();
$pro_page_id = get_option('livenettv_pro_pro_page_id', '');

?>
<div class="wrap livenettv-pro-admin">
    <h1><span class="dashicons dashicons-tag"></span> <?php _e('Plans & Pricing', 'livenettv-pro'); ?></h1>

    <div class="notice notice-info">
        <p><?php _e('Configure your Pro membership plans. Each plan defines the price duration available to users.', 'livenettv-pro'); ?></p>
    </div>

    <form method="post" action="options.php">
        <?php settings_fields('livenettv_pro_plans'); ?>

        <h2><?php _e('Membership Plans', 'livenettv-pro'); ?></h2>

        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th scope="col"><?php _e('Plan', 'livenettv-pro'); ?></th>
                    <th scope="col"><?php _e('Duration', 'livenettv-pro'); ?></th>
                    <th scope="col"><?php _e('Price (USD)', 'livenettv-pro'); ?></th>
                    <th scope="col"><?php _e('Slug', 'livenettv-pro'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($plans as $slug => $plan) : ?>
                    <tr>
                        <td><strong><?php echo esc_html($plan['name']); ?></strong></td>
                        <td><?php echo esc_html($plan['duration_text']); ?> (<?php echo esc_html($plan['duration']); ?> <?php _e('days', 'livenettv-pro'); ?>)</td>
                        <td>$<?php echo esc_html(number_format($plan['price'], 2)); ?></td>
                        <td><code><?php echo esc_html($slug); ?></code></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p class="description">
            <?php _e('Currently, custom plan prices are fixed in code. To modify pricing, contact your developer or add custom plan options via the "livenettv_pro_plans" filter.', 'livenettv-pro'); ?>
        </p>

        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="livenettv_pro_pro_page_id"><?php _e('Pro Page', 'livenettv-pro'); ?></label>
                </th>
                <td>
                    <?php
                    wp_dropdown_pages(array(
                        'name' => 'livenettv_pro_pro_page_id',
                        'selected' => $pro_page_id,
                        'show_option_none' => __('Select a page', 'livenettv-pro'),
                    ));
                    ?>
                    <p class="description"><?php _e('Select the page where users can purchase Pro membership. This page should contain the [livenettv_pro_form] shortcode.', 'livenettv-pro'); ?></p>
                </td>
            </tr>
        </table>

        <?php submit_button(__('Save Plans Settings', 'livenettv-pro')); ?>
    </form>

    <div class="livenettv-pro-shortcode-reference">
        <h2><?php _e('Available Shortcodes', 'livenettv-pro'); ?></h2>
        <table class="widefat">
            <tr>
                <td><code>[livenettv_pro_form]</code></td>
                <td><?php _e('Displays the complete Pro membership purchase form with plan selection, crypto payment, and upload.', 'livenettv-pro'); ?></td>
            </tr>
            <tr>
                <td><code>[livenettv_pro_status]</code></td>
                <td><?php _e('Displays current user membership status (Pro badge with days remaining).', 'livenettv-pro'); ?></td>
            </tr>
            <tr>
                <td><code>[livenettv_pro_cta text="Go Pro"]</code></td>
                <td><?php _e('Displays "Go Pro" call-to-action button for free users.', 'livenettv-pro'); ?></td>
            </tr>
            <tr>
                <td><code>[livenettv_login_button text="Sign in with Google"]</code></td>
                <td><?php _e('Displays Google Sign-In button.', 'livenettv-pro'); ?></td>
            </tr>
        </table>
    </div>
</div>
