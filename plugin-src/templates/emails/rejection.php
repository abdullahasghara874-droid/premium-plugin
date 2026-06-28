<?php
/**
 * Email Template: Payment Rejection
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
                        <td style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); padding: 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 24px;">
                                Payment Update
                            </h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="margin: 0 0 20px; color: #555; line-height: 1.6;">
                                Hi <?php echo esc_html($user_name); ?>,
                            </p>

                            <p style="margin: 0 0 20px; color: #555; line-height: 1.6;">
                                Unfortunately, your payment request for <strong><?php echo esc_html($plan_name); ?></strong> could not be approved.
                            </p>

                            <?php if (!empty($rejection_notes)) : ?>
                            <table border="0" cellspacing="0" cellpadding="0" style="width: 100%; margin: 20px 0;">
                                <tr>
                                    <td style="padding: 20px; background-color: #fff3f3; border-left: 4px solid #e74c3c; border-radius: 4px;">
                                        <p style="margin: 0 0 10px; color: #e74c3c; font-weight: 600; font-size: 14px;">
                                            Reason:
                                        </p>
                                        <p style="margin: 0; color: #555; line-height: 1.6;">
                                            <?php echo esc_html($rejection_notes); ?>
                                        </p>
                                    </td>
                                </tr>
                            </table>
                            <?php endif; ?>

                            <p style="margin: 20px 0; color: #555; line-height: 1.6;">
                                If you believe this is an error or have any questions, please contact our support team:
                            </p>

                            <p style="margin: 20px 0;">
                                <a href="mailto:<?php echo esc_attr($support_email); ?>" style="color: #667eea; text-decoration: none;">
                                    <?php echo esc_html($support_email); ?>
                                </a>
                            </p>

                            <p style="margin: 30px 0 0;">
                                <a href="<?php echo esc_url($site_url); ?>" style="display: inline-block; padding: 15px 30px; background: #667eea; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: 600;">
                                    Try Again
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
