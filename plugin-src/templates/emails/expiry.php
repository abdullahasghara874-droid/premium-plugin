<?php
/**
 * Email Template: Membership Expiry
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
                        <td style="background: linear-gradient(135deg, #ff9a56 0%, #ff6b6b 100%); padding: 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 24px;">
                                Membership Expired
                            </h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="margin: 0 0 20px; color: #555; line-height: 1.6;">
                                Hi <?php echo esc_html($user_name); ?>,
                            </p>

                            <p style="margin: 0 0 20px; color: #555; line-height: 1.6;">
                                Your Pro membership has expired. Advertisements will now be visible on the website.
                            </p>

                            <table border="0" cellspacing="0" cellpadding="0" style="width: 100%; margin: 20px 0;">
                                <tr>
                                    <td style="padding: 25px; background-color: #fff8f0; border-radius: 6px; text-align: center;">
                                        <p style="margin: 0 0 15px; color: #e67e22; font-size: 16px;">
                                            Renew now to keep enjoying an ad-free experience!
                                        </p>
                                        <p style="margin: 0;">
                                            <a href="<?php echo esc_url($renewal_url); ?>" style="display: inline-block; padding: 15px 30px; background: #ff6b6b; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: 600;">
                                                Renew Pro Membership
                                            </a>
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 20px 0 0; color: #555; line-height: 1.6;">
                                Thank you for being a valued member of <?php echo esc_html($site_name); ?>!
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
