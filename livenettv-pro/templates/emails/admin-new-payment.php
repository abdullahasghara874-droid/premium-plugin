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
        h2 { color: #e50914; margin-top: 0; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #eee; }
        .btn { display: inline-block; padding: 12px 24px; background: #0073aa; color: #fff; text-decoration: none; border-radius: 4px; }
        .footer { margin-top: 30px; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <h2><?php esc_html_e( 'New Payment Submission', 'livenettv-pro' ); ?></h2>

        <p><?php esc_html_e( 'A new payment has been submitted and requires your review.', 'livenettv-pro' ); ?></p>

        <table>
            <tr>
                <th><?php esc_html_e( 'User', 'livenettv-pro' ); ?></th>
                <td><?php echo esc_html( $user->user_login ); ?> (<?php echo esc_html( $user->user_email ); ?>)</td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Plan', 'livenettv-pro' ); ?></th>
                <td><?php echo esc_html( $payment->plan_name ); ?></td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Amount', 'livenettv-pro' ); ?></th>
                <td><?php echo esc_html( number_format( $payment->plan_price, 2 ) . ' ' . $payment->currency ); ?></td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Cryptocurrency', 'livenettv-pro' ); ?></th>
                <td><?php echo esc_html( strtoupper( $payment->crypto_type ) ); ?></td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Transaction ID', 'livenettv-pro' ); ?></th>
                <td><code><?php echo esc_html( $payment->transaction_id ); ?></code></td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Submitted', 'livenettv-pro' ); ?></th>
                <td><?php echo esc_html( wp_date( 'M j, Y H:i', strtotime( $payment->submitted_at ) ) ); ?></td>
            </tr>
        </table>

        <p>
            <a href="<?php echo esc_url( $admin_url ); ?>" class="btn">
                <?php esc_html_e( 'Review Payment', 'livenettv-pro' ); ?>
            </a>
        </p>

        <div class="footer">
            <p><?php echo esc_html( get_bloginfo( 'name' ) ); ?></p>
        </div>
    </div>
</body>
</html>
