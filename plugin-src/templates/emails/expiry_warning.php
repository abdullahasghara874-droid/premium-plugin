<?php
/**
 * Email Template: Expiry Warning
 */

defined('ABSPATH') || exit;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; background-color: #f4f4f4;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td style="padding: 40px 20px;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden;">
                    <tr>
                        <td style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 24px;">
                                Pro Expiring Soon
                            </h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="margin: 0 0 20px; color: #555; line-height: 1.6;">
                                Hi <?php echo esc_html($user_name); ?>,
                            </p>

                            <p style="margin: 0 0 20px; color: #555; line-height: 1.6;">
                                Your Pro membership will expire in <strong><?php echo esc_html($days_remaining); ?> <?php echo _n('day', 'days', $days_remaining, 'livenettv-pro'); ?></strong>!
                            </p>

                            <table border="0" cellspacing="0" cellpadding="0" style="width: 100%; margin: 20px 0;">
                                <tr>
                                    <td style="padding: 25px; background-color: #fff0f5; border-radius: 6px; text-align: center;">
                                        <p style="margin: 0 0 10px; color: #666; font-size: 14px;">
                                            Expiry Date
                                        </p>
                                        <p style="margin: 0 0 20px; color: #f5576c; font-size: 24px; font-weight: 600;">
                                            <?php echo esc_html($expiry_date); ?>
                                        </p>
                                        <p style="margin: 0;">
                                            <a href="<?php echo esc_url($renewal_url); ?>" style="display: inline-block; padding: 15px 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: 600;">
                                                Renew Now
                                            </a>
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 20px 0; color: #555; line-height: 1.6;">
                                Don't lose your ad-free experience! Renew your membership today to continue enjoying:
                            </p>

                            <ul style="margin: 0 0 20px; padding: 0 0 0 20px; color: #555; line-height: 1.8;">
                                <li>No popup ads</li>
                                <li>No banner ads</li>
                                <li>No sticky ads</li>
                                <li>Faster browsing</li>
                            </ul>

                            <p style="margin: 30px 0 0;">
                                <a href="<?php echo esc_url($site_url); ?>" style="display: inline-block; padding: 12px 25px; background: #f0f0f0; color: #333; text-decoration: none; border-radius: 5px; font-weight: 500;">
                                    Visit <?php echo esc_html($site_name); ?>
                                </a>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 20px 30px; background-color: #f8f9fa; text-align: center; border-top: 1px solid #eee;">
                            <p style="margin: 0; color: #999; font-size: 12px;">
                                <?php echo esc_html($site_name); ?> - <?php echo esc_url($site_url); ?>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
