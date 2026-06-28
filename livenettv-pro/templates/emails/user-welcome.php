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
        h2 { color: #28a745; margin-top: 0; }
        .btn { display: inline-block; padding: 12px 24px; background: #28a745; color: #fff; text-decoration: none; border-radius: 4px; }
        .footer { margin-top: 30px; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <h2><?php esc_html_e( 'Payment Approved - Welcome to Pro!', 'livenettv-pro' ); ?></h2>

        <p>
            <?php
            printf(
                esc_html__( 'Great news! Your payment for %s has been approved.', 'livenettv-pro' ),
                '<strong>' . esc_html( $payment->plan_name ) . '</strong>'
            );
            ?>
        </p>

        <?php if ( ! empty( $payment->membership_end ) ) : ?>
            <p>
                <?php
                printf(
                    esc_html__( 'Your Pro membership is now active until %s.', 'livenettv-pro' ),
                    '<strong>' . esc_html( $payment->membership_end ) . '</strong>'
                );
                ?>
            </p>
        <?php endif; ?>

        <p><?php esc_html_e( 'You now have access to all Pro features including ad-free viewing.', 'livenettv-pro' ); ?></p>

        <p>
            <a href="<?php echo esc_url( $pro_page ); ?>" class="btn">
                <?php esc_html_e( 'Go to Pro Page', 'livenettv-pro' ); ?>
            </a>
        </p>

        <p>
            <?php esc_html_e( 'Thank you for your support!', 'livenettv-pro' ); ?><br>
            <?php echo esc_html( get_bloginfo( 'name' ) ); ?>
        </p>

        <div class="footer">
            <p><?php echo esc_html( home_url() ); ?></p>
        </div>
    </div>
</body>
</html>
