<?php
defined( 'ABSPATH' ) || exit;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #f9f9f9; padding: 30px; border-radius: 8px; }
        h2 { color: #6c757d; margin-top: 0; }
        .btn { display: inline-block; padding: 12px 24px; background: #0073aa; color: #fff; text-decoration: none; border-radius: 4px; }
        .footer { margin-top: 30px; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <h2><?php esc_html_e( 'Your Pro Membership Has Expired', 'livenettv-pro' ); ?></h2>

        <p><?php esc_html_e( 'Your Pro membership has expired. You no longer have access to Pro features.', 'livenettv-pro' ); ?></p>

        <p><?php esc_html_e( 'Renew your membership today to get back to ad-free viewing.', 'livenettv-pro' ); ?></p>

        <p>
            <a href="<?php echo esc_url( $pro_page ); ?>" class="btn">
                <?php esc_html_e( 'Renew Now', 'livenettv-pro' ); ?>
            </a>
        </p>

        <p>
            <?php esc_html_e( 'We hope to see you back soon!', 'livenettv-pro' ); ?><br>
            <?php echo esc_html( get_bloginfo( 'name' ) ); ?>
        </p>

        <div class="footer">
            <p><?php echo esc_html( home_url() ); ?></p>
        </div>
    </div>
</body>
</html>
