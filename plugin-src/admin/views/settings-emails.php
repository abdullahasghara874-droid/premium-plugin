<?php
/**
 * Email settings view
 */

defined('ABSPATH') || exit;

$email_mode = get_option('livenettv_pro_email_template_mode', 'html');
$notification_email = get_option('livenettv_pro_notification_email', get_option('admin_email'));
$from_name = get_option('livenettv_pro_email_from_name', get_bloginfo('name'));
$warning_days = get_option('livenettv_pro_expiry_warning_days', array(7, 3, 1));
$support_email = get_option('livenettv_pro_support_email', get_option('admin_email'));
$admin_notification_email = get_option('livenettv_pro_admin_notification_email', get_option('admin_email'));

?>
<div class="wrap livenettv-pro-admin">
    <h1><span class="dashicons dashicons-email-alt"></span> <?php _e('Email Settings', 'livenettv-pro'); ?></h1>

    <div class="notice notice-info">
        <p><?php _e('Configure email notifications for payment approvals, rejections, and membership expiry warnings.', 'livenettv-pro'); ?></p>
    </div>

    <form method="post" action="options.php">
        <?php settings_fields('livenettv_pro_emails'); ?>

        <h2><?php _e('Email Format', 'livenettv-pro'); ?></h2>

        <table class="form-table">
            <tr>
                <th scope="row">
                    <label><?php _e('Email Format', 'livenettv-pro'); ?></label>
                </th>
                <td>
                    <fieldset>
                        <label>
                            <input type="radio" name="livenettv_pro_email_template_mode" value="html" <?php checked($email_mode, 'html'); ?>>
                            <?php _e('HTML (Recommended)', 'livenettv-pro'); ?>
                        </label>
                        <br>
                        <label>
                            <input type="radio" name="livenettv_pro_email_template_mode" value="text" <?php checked($email_mode, 'text'); ?>>
                            <?php _e('Plain Text', 'livenettv-pro'); ?>
                        </label>
                    </fieldset>
                    <p class="description"><?php _e('HTML emails look better but require an email client that supports HTML.', 'livenettv-pro'); ?></p>
                </td>
            </tr>
        </table>

        <h2><?php _e('Sender Information', 'livenettv-pro'); ?></h2>

        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="livenettv_pro_email_from_name"><?php _e('From Name', 'livenettv-pro'); ?></label>
                </th>
                <td>
                    <input type="text" name="livenettv_pro_email_from_name" id="livenettv_pro_email_from_name" value="<?php echo esc_attr($from_name); ?>" class="regular-text">
                    <p class="description"><?php _e('Name shown as email sender.', 'livenettv-pro'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="livenettv_pro_notification_email"><?php _e('From Email', 'livenettv-pro'); ?></label>
                </th>
                <td>
                    <input type="email" name="livenettv_pro_notification_email" id="livenettv_pro_notification_email" value="<?php echo esc_attr($notification_email); ?>" class="regular-text">
                    <p class="description"><?php _e('Email address shown as sender.', 'livenettv-pro'); ?></p>
                </td>
            </tr>
        </table>

        <h2><?php _e('Notification Recipients', 'livenettv-pro'); ?></h2>

        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="livenettv_pro_admin_notification_email"><?php _e('Admin Notification Email', 'livenettv-pro'); ?></label>
                </th>
                <td>
                    <input type="email" name="livenettv_pro_admin_notification_email" id="livenettv_pro_admin_notification_email" value="<?php echo esc_attr($admin_notification_email); ?>" class="regular-text">
                    <p class="description"><?php _e('Where to send new payment request notifications.', 'livenettv-pro'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="livenettv_pro_support_email"><?php _e('Support Email', 'livenettv-pro'); ?></label>
                </th>
                <td>
                    <input type="email" name="livenettv_pro_support_email" id="livenettv_pro_support_email" value="<?php echo esc_attr($support_email); ?>" class="regular-text">
                    <p class="description"><?php _e('Shown in rejection emails for user support.', 'livenettv-pro'); ?></p>
                </td>
            </tr>
        </table>

        <h2><?php _e('Expiry Warnings', 'livenettv-pro'); ?></h2>

        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="livenettv_pro_expiry_warning_days"><?php _e('Warning Days', 'livenettv-pro'); ?></label>
                </th>
                <td>
                    <input type="text" name="livenettv_pro_expiry_warning_days" id="livenettv_pro_expiry_warning_days" value="<?php echo esc_attr(is_array($warning_days) ? implode(', ', $warning_days) : $warning_days); ?>" class="regular-text">
                    <p class="description"><?php _e('Comma-separated days before expiry to send warning emails. Example: 7, 3, 1', 'livenettv-pro'); ?></p>
                </td>
            </tr>
        </table>

        <?php submit_button(__('Save Email Settings', 'livenettv-pro')); ?>
    </form>

    <div class="livenettv-pro-email-types">
        <h2><?php _e('Email Types', 'livenettv-pro'); ?></h2>

        <table class="widefat">
            <thead>
                <tr>
                    <th><?php _e('Email Type', 'livenettv-pro'); ?></th>
                    <th><?php _e('Trigger', 'livenettv-pro'); ?></th>
                    <th><?php _e('Recipients', 'livenettv-pro'); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong><?php _e('Payment Approval', 'livenettv-pro'); ?></strong></td>
                    <td><?php _e('Admin approves payment', 'livenettv-pro'); ?></td>
                    <td><?php _e('User', 'livenettv-pro'); ?></td>
                </tr>
                <tr>
                    <td><strong><?php _e('Payment Rejection', 'livenettv-pro'); ?></strong></td>
                    <td><?php _e('Admin rejects payment', 'livenettv-pro'); ?></td>
                    <td><?php _e('User', 'livenettv-pro'); ?></td>
                </tr>
                <tr>
                    <td><strong><?php _e('Membership Expiry', 'livenettv-pro'); ?></strong></td>
                    <td><?php _e('Membership expires (daily cron)', 'livenettv-pro'); ?></td>
                    <td><?php _e('User', 'livenettv-pro'); ?></td>
                </tr>
                <tr>
                    <td><strong><?php _e('Expiry Warning', 'livenettv-pro'); ?></strong></td>
                    <td><?php _e('X days before expiry (daily cron)', 'livenettv-pro'); ?></td>
                    <td><?php _e('User', 'livenettv-pro'); ?></td>
                </tr>
                <tr>
                    <td><strong><?php _e('New Payment Request', 'livenettv-pro'); ?></strong></td>
                    <td><?php _e('User submits payment', 'livenettv-pro'); ?></td>
                    <td><?php _e('Admin', 'livenettv-pro'); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
