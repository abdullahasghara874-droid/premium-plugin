<?php
/**
 * Email Template: Admin Notification (New Payment Request)
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
                        <td style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); padding: 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 24px;">
                                New Payment Request
                            </h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="margin: 0 0 20px; color: #555; line-height: 1.6;">
                                A new payment request has been submitted and requires your review.
                            </p>

                            <table border="0" cellspacing="0" cellpadding="0" style="width: 100%; margin: 20px 0; background-color: #f8f9fa; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0 0 10px; color: #666; font-size: 14px;">
                                            <strong>User:</strong> <?php echo esc_html($user_name); ?>
                                        </p>
                                        <p style="margin: 0 0 10px; color: #666; font-size: 14px;">
                                            <strong>Email:</strong> <?php echo esc_html($user_email); ?>
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 30px 0 0;">
                                <a href="<?php echo esc_url($admin_url); ?>" style="display: inline-block; padding: 15px 30px; background: #11998e; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: 600;">
                                    Review Payment
                                </a>
                            </p>

                            <p style="margin: 20px 0 0; color: #888; font-size: 13px;">
                                or copy this URL:<br>
                                <code style="background: #f0f0f0; padding: 8px 12px; border-radius: 4px; display: inline-block; margin-top: 5px; word-break: break-all;">
                                    <?php echo esc_html($admin_url); ?>
                                </code>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 20px 30px; background-color: #f8f9fa; text-align: center; border-top: 1px solid #eee;">
                            <p style="margin: 0; color: #999; font-size: 12px;">
                                <?php echo esc_html($site_name); ?> Pro Membership System
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
