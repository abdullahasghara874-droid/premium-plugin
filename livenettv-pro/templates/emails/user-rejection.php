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
        h2 { color: #dc3545; margin-top: 0; }
        .btn { display: inline-block; padding: 12px 24px; background: #0073aa; color: #fff; text-decoration: none; border-radius: 4px; }
        .reason { background: #fff; padding: 15px; border-left: 4px solid #dc3545; margin: 20px 0; }
        .footer { margin-top: 30px; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <h2><?php esc_html_e( 'Payment Submission Update', 'livenettv-pro' ); ?></h2>

        <p>
            <?php
            printf(
                esc_html__( 'Unfortunately, your payment for %s could not be verified.', 'livenettv-pro' ),
                '<strong>' . esc_html( $payment->plan_name ) . '</strong>'
            );
            ?>
        </p>

        <?php if ( ! empty( $reason ) ) : ?>
            <div class="reason">
                <strong><?php esc_html_e( 'Reason:', 'livenettv-pro' ); ?></strong><br>
                <?php echo esc_html( $reason ); ?>
            </div>
        <?php endif; ?>

        <p><?php esc_html_e( 'You can submit a new payment at any time. Please ensure your transaction ID is correct and the screenshot clearly shows the payment.', 'livenettv-pro' ); ?></p>

        <p>
            <a href="<?php echo esc_url( $pro_page ); ?>" class="btn">
                <?php esc_html_e( 'Submit New Payment', 'livenettv-pro' ); ?>
            </a>
        </p>

        <p>
            <?php esc_html_e( 'If you believe this is an error, please contact our support team.', 'livenettv-pro' ); ?><br>
            <?php echo esc_html( get_bloginfo( 'name' ) ); ?>
        </p>

        <div class="footer">
            <p><?php echo esc_html( home_url() ); ?></p>
        </div>
    </div>
</body>
</html>
