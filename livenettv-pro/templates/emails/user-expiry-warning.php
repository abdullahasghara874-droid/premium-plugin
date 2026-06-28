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
        h2 { color: #ffc107; margin-top: 0; }
        .warning-box { background: #fff3cd; padding: 20px; border-radius: 4px; margin: 20px 0; text-align: center; }
        .warning-box .days { font-size: 2rem; font-weight: bold; color: #856404; }
        .btn { display: inline-block; padding: 12px 24px; background: #ffc107; color: #000; text-decoration: none; border-radius: 4px; }
        .footer { margin-top: 30px; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <h2><?php esc_html_e( 'Your Pro Membership Expires Soon', 'livenettv-pro' ); ?></h2>

        <div class="warning-box">
            <span class="days"><?php echo esc_html( $days_remaining ); ?></span><br>
            <?php esc_html_e( 'days remaining', 'livenettv-pro' ); ?>
        </div>

        <p><?php esc_html_e( 'Renew now to continue enjoying ad-free viewing and all Pro features.', 'livenettv-pro' ); ?></p>

        <p>
            <a href="<?php echo esc_url( $pro_page ); ?>" class="btn">
                <?php esc_html_e( 'Renew Now', 'livenettv-pro' ); ?>
            </a>
        </p>

        <p>
            <?php esc_html_e( 'Thank you for being a Pro member!', 'livenettv-pro' ); ?><br>
            <?php echo esc_html( get_bloginfo( 'name' ) ); ?>
        </p>

        <div class="footer">
            <p><?php echo esc_html( home_url() ); ?></p>
        </div>
    </div>
</body>
</html>
