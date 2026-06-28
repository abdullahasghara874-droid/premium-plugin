<?php
defined( 'ABSPATH' ) || exit;

if ( ! $payment ) {
    echo '<div class="wrap"><p>' . esc_html__( 'Payment not found.', 'livenettv-pro' ) . '</p></div>';
    return;
}
?>
<div class="wrap lntv-admin">
    <h1>
        <?php printf( esc_html__( 'Payment #%d', 'livenettv-pro' ), $payment->id ); ?>
        <a href="<?php echo esc_url( admin_url( 'admin.php?page=livenettv-pro-payments' ) ); ?>" class="page-title-action">
            <?php esc_html_e( 'Back to List', 'livenettv-pro' ); ?>
        </a>
    </h1>

    <div class="lntv-payment-detail-grid">
        <div class="lntv-detail-card">
            <h2><?php esc_html_e( 'Payment Information', 'livenettv-pro' ); ?></h2>
            <table class="form-table">
                <tr>
                    <th><?php esc_html_e( 'Status', 'livenettv-pro' ); ?></th>
                    <td>
                        <span class="lntv-status-badge status-<?php echo esc_attr( $payment->status ); ?>">
                            <?php echo esc_html( ucfirst( $payment->status ) ); ?>
                        </span>
                    </td>
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
                    <th><?php esc_html_e( 'Wallet Address', 'livenettv-pro' ); ?></th>
                    <td>
                        <code class="lntv-wallet-address"><?php echo esc_html( $payment->wallet_address ); ?></code>
                    </td>
                </tr>
                <tr>
                    <th><?php esc_html_e( 'Transaction ID', 'livenettv-pro' ); ?></th>
                    <td>
                        <code class="lntv-txid"><?php echo esc_html( $payment->transaction_id ); ?></code>
                    </td>
                </tr>
                <tr>
                    <th><?php esc_html_e( 'Submitted', 'livenettv-pro' ); ?></th>
                    <td><?php echo esc_html( wp_date( 'M j, Y H:i', strtotime( $payment->submitted_at ) ) ); ?></td>
                </tr>
            </table>
        </div>

        <div class="lntv-detail-card">
            <h2><?php esc_html_e( 'User Information', 'livenettv-pro' ); ?></h2>
            <?php if ( $user ) : ?>
                <table class="form-table">
                    <tr>
                        <th><?php esc_html_e( 'Username', 'livenettv-pro' ); ?></th>
                        <td>
                            <a href="<?php echo esc_url( admin_url( 'user-edit.php?user_id=' . $user->ID ) ); ?>">
                                <?php echo esc_html( $user->user_login ); ?>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e( 'Email', 'livenettv-pro' ); ?></th>
                        <td>
                            <a href="mailto:<?php echo esc_attr( $user->user_email ); ?>">
                                <?php echo esc_html( $user->user_email ); ?>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e( 'Display Name', 'livenettv-pro' ); ?></th>
                        <td><?php echo esc_html( $user->display_name ); ?></td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e( 'Pro Status', 'livenettv-pro' ); ?></th>
                        <td>
                            <?php
                            $pro_status = get_user_meta( $user->ID, 'livenettv_pro_status', true );
                            if ( 'active' === $pro_status ) :
                            ?>
                                <span class="lntv-status-badge status-approved">
                                    <?php esc_html_e( 'Active Pro', 'livenettv-pro' ); ?>
                                </span>
                            <?php else : ?>
                                <span class="lntv-status-badge status-pending">
                                    <?php esc_html_e( 'Free User', 'livenettv-pro' ); ?>
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e( 'Registered', 'livenettv-pro' ); ?></th>
                        <td><?php echo esc_html( wp_date( 'M j, Y', strtotime( $user->user_registered ) ) ); ?></td>
                    </tr>
                </table>
            <?php else : ?>
                <p><?php esc_html_e( 'User not found.', 'livenettv-pro' ); ?></p>
            <?php endif; ?>
        </div>

        <div class="lntv-detail-card lntv-full-width">
            <h2><?php esc_html_e( 'Payment Screenshot', 'livenettv-pro' ); ?></h2>
            <?php if ( ! empty( $payment->screenshot_path ) ) : ?>
                <div class="lntv-screenshot-container">
                    <a href="<?php echo esc_url( $payment->screenshot_path ); ?>" target="_blank" class="lntv-screenshot-link">
                        <img src="<?php echo esc_url( $payment->screenshot_path ); ?>" alt="<?php esc_attr_e( 'Payment Screenshot', 'livenettv-pro' ); ?>" class="lntv-screenshot">
                    </a>
                    <p class="description">
                        <a href="<?php echo esc_url( $payment->screenshot_path ); ?>" target="_blank">
                            <?php esc_html_e( 'View full size', 'livenettv-pro' ); ?>
                        </a>
                    </p>
                </div>
            <?php else : ?>
                <p><?php esc_html_e( 'No screenshot uploaded.', 'livenettv-pro' ); ?></p>
            <?php endif; ?>
        </div>
    </div>

    <?php if ( 'pending' === $payment->status ) : ?>
        <div class="lntv-action-buttons">
            <button type="button" id="lntv-approve-btn" class="button button-primary button-large" data-payment-id="<?php echo esc_attr( $payment->id ); ?>">
                <span class="dashicons dashicons-yes-alt"></span>
                <?php esc_html_e( 'Approve Payment', 'livenettv-pro' ); ?>
            </button>
            <button type="button" id="lntv-reject-btn" class="button button-large" data-payment-id="<?php echo esc_attr( $payment->id ); ?>">
                <span class="dashicons dashicons-dismiss"></span>
                <?php esc_html_e( 'Reject Payment', 'livenettv-pro' ); ?>
            </button>
        </div>

        <div id="lntv-reject-form" class="lntv-reject-form" style="display: none;">
            <h3><?php esc_html_e( 'Rejection Reason', 'livenettv-pro' ); ?></h3>
            <textarea id="lntv-reject-reason" rows="3" class="large-text" placeholder="<?php esc_attr_e( 'Enter a reason for rejection (optional, will be sent to user)...', 'livenettv-pro' ); ?>"></textarea>
            <p>
                <button type="button" id="lntv-confirm-reject" class="button"><?php esc_html_e( 'Confirm Rejection', 'livenettv-pro' ); ?></button>
                <button type="button" id="lntv-cancel-reject" class="button"><?php esc_html_e( 'Cancel', 'livenettv-pro' ); ?></button>
            </p>
        </div>
    <?php elseif ( 'approved' === $payment->status ) : ?>
        <div class="notice notice-success inline">
            <p>
                <strong><?php esc_html_e( 'Payment Approved', 'livenettv-pro' ); ?></strong><br>
                <?php
                printf(
                    esc_html__( 'Membership active from %s to %s.', 'livenettv-pro' ),
                    esc_html( $payment->membership_start ?? '' ),
                    esc_html( $payment->membership_end ?? '' )
                );
                ?>
            </p>
        </div>
    <?php elseif ( 'rejected' === $payment->status ) : ?>
        <div class="notice notice-error inline">
            <p>
                <strong><?php esc_html_e( 'Payment Rejected', 'livenettv-pro' ); ?></strong>
                <?php if ( ! empty( $payment->notes ) ) : ?>
                    <br><?php echo esc_html( $payment->notes ); ?>
                <?php endif; ?>
            </p>
        </div>
    <?php endif; ?>
</div>
