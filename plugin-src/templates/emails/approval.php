<?php
/**
 * Email Template: Payment Approval
 *
 * Variables available:
 * $user_name     - User's display name
 * $user_email    - User's email
 * $plan_name     - Name of the plan purchased
 * $expiry_date   - Membership expiry date
 * $site_name     - Site name
 * $site_url      - Site URL
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
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 24px;">
                                <?php echo esc_html($site_name); ?> Pro
                            </h1>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <h2 style="margin: 0 0 20px; color: #333; font-size: 22px;">
                                Pro Membership Activated!
                            </h2>

                            <p style="margin: 0 0 20px; color: #555; line-height: 1.6;">
                                Hi <?php echo esc_html($user_name); ?>,
                            </p>

                            <p style="margin: 0 0 20px; color: #555; line-height: 1.6;">
                                Congratulations! Your <strong><?php echo esc_html($plan_name); ?></strong> membership is now active.
                            </p>

                            <table border="0" cellspacing="0" cellpadding="0" style="width: 100%; margin: 20px 0;">
                                <tr>
                                    <td style="padding: 20px; background-color: #f8f9fa; border-radius: 6px; text-align: center;">
                                        <p style="margin: 0 0 10px; color: #666; font-size: 14px;">Your membership will expire on</p>
                                        <p style="margin: 0; color: #667eea; font-size: 20px; font-weight: 600;">
                                            <?php echo esc_html($expiry_date); ?>
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <h3 style="margin: 30px 0 15px; color: #333; font-size: 18px;">
                                Your Pro Benefits:
                            </h3>

                            <ul style="margin: 0 0 20px; padding: 0 0 0 20px; color: #555; line-height: 1.8;">
                                <li>No popup ads</li>
                                <li>No banner ads</li>
                                <li>No sticky ads</li>
                                <li>No download page ads</li>
                                <li>Faster, cleaner browsing experience</li>
                            </ul>

                            <p style="margin: 30px 0 0;">
                                <a href="<?php echo esc_url($site_url); ?>" style="display: inline-block; padding: 15px 30px; background: #667eea; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: 600;">
                                    Start Browsing Ad-Free
                                </a>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
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
