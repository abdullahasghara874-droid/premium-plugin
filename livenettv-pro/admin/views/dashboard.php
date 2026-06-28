<?php
defined( 'ABSPATH' ) || exit;
?>
<div class="wrap lntv-admin">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

    <div class="lntv-dashboard-grid">
        <div class="lntv-stat-card">
            <div class="lntv-stat-icon pending">
                <span class="dashicons dashicons-clock"></span>
            </div>
            <div class="lntv-stat-content">
                <h3><?php echo esc_html( $stats['pending_payments'] ); ?></h3>
                <p><?php esc_html_e( 'Pending Payments', 'livenettv-pro' ); ?></p>
            </div>
            <?php if ( $stats['pending_payments'] > 0 ) : ?>
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=livenettv-pro-payments&status=pending' ) ); ?>" class="lntv-stat-link">
                    <?php esc_html_e( 'Review', 'livenettv-pro' ); ?>
                </a>
            <?php endif; ?>
        </div>

        <div class="lntv-stat-card">
            <div class="lntv-stat-icon approved">
                <span class="dashicons dashicons-yes-alt"></span>
            </div>
            <div class="lntv-stat-content">
                <h3><?php echo esc_html( $stats['approved_payments'] ); ?></h3>
                <p><?php esc_html_e( 'Approved Payments', 'livenettv-pro' ); ?></p>
            </div>
        </div>

        <div class="lntv-stat-card">
            <div class="lntv-stat-icon members">
                <span class="dashicons dashicons-groups"></span>
            </div>
            <div class="lntv-stat-content">
                <h3><?php echo esc_html( $active_members ); ?></h3>
                <p><?php esc_html_e( 'Active Pro Members', 'livenettv-pro' ); ?></p>
            </div>
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=livenettv-pro-members&mstatus=active' ) ); ?>" class="lntv-stat-link">
                <?php esc_html_e( 'View All', 'livenettv-pro' ); ?>
            </a>
        </div>

        <div class="lntv-stat-card">
            <div class="lntv-stat-icon total">
                <span class="dashicons dashicons-chart-bar"></span>
            </div>
            <div class="lntv-stat-content">
                <h3><?php echo esc_html( $stats['total_payments'] ); ?></h3>
                <p><?php esc_html_e( 'Total Payments', 'livenettv-pro' ); ?></p>
            </div>
        </div>
    </div>

    <div class="lntv-dashboard-section">
        <div class="lntv-section-header">
            <h2><?php esc_html_e( 'Recent Payments', 'livenettv-pro' ); ?></h2>
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=livenettv-pro-payments' ) ); ?>" class="button">
                <?php esc_html_e( 'View All Payments', 'livenettv-pro' ); ?>
            </a>
        </div>

        <?php if ( empty( $recent_payments ) ) : ?>
            <p><?php esc_html_e( 'No payments yet.', 'livenettv-pro' ); ?></p>
        <?php else : ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'ID', 'livenettv-pro' ); ?></th>
                        <th><?php esc_html_e( 'User', 'livenettv-pro' ); ?></th>
                        <th><?php esc_html_e( 'Plan', 'livenettv-pro' ); ?></th>
                        <th><?php esc_html_e( 'Amount', 'livenettv-pro' ); ?></th>
                        <th><?php esc_html_e( 'Crypto', 'livenettv-pro' ); ?></th>
                        <th><?php esc_html_e( 'Status', 'livenettv-pro' ); ?></th>
                        <th><?php esc_html_e( 'Submitted', 'livenettv-pro' ); ?></th>
                        <th><?php esc_html_e( 'Actions', 'livenettv-pro' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( $recent_payments as $payment ) :
                        $user = get_user_by( 'id', $payment->user_id );
                    ?>
                        <tr>
                            <td>#<?php echo esc_html( $payment->id ); ?></td>
                            <td>
                                <?php if ( $user ) : ?>
                                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=livenettv-pro-payments&view=' . $payment->id ) ); ?>">
                                        <?php echo esc_html( $user->user_login ); ?>
                                    </a>
                                <?php else : ?>
                                    <?php echo esc_html( $payment->user_id ); ?>
                                <?php endif; ?>
                            </td>
                            <td><?php echo esc_html( $payment->plan_name ); ?></td>
                            <td><?php echo esc_html( $payment->plan_price . ' ' . $payment->currency ); ?></td>
                            <td><?php echo esc_html( strtoupper( $payment->crypto_type ) ); ?></td>
                            <td>
                                <span class="lntv-status-badge status-<?php echo esc_attr( $payment->status ); ?>">
                                    <?php echo esc_html( ucfirst( $payment->status ) ); ?>
                                </span>
                            </td>
                            <td><?php echo esc_html( wp_date( 'M j, Y H:i', strtotime( $payment->submitted_at ) ) ); ?></td>
                            <td>
                                <a href="<?php echo esc_url( admin_url( 'admin.php?page=livenettv-pro-payments&view=' . $payment->id ) ); ?>" class="button button-small">
                                    <?php esc_html_e( 'View', 'livenettv-pro' ); ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <div class="lntv-dashboard-section">
        <div class="lntv-section-header">
            <h2><?php esc_html_e( 'Quick Actions', 'livenettv-pro' ); ?></h2>
        </div>
        <div class="lntv-quick-actions">
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=livenettv-pro-payments&status=pending' ) ); ?>" class="button button-primary">
                <span class="dashicons dashicons-clock"></span>
                <?php esc_html_e( 'Review Pending Payments', 'livenettv-pro' ); ?>
            </a>
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=livenettv-pro-settings&tab=wallets' ) ); ?>" class="button">
                <span class="dashicons dashicons-admin-settings"></span>
                <?php esc_html_e( 'Configure Wallets', 'livenettv-pro' ); ?>
            </a>
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=livenettv-pro-settings&tab=google' ) ); ?>" class="button">
                <span class="dashicons dashicons-google"></span>
                <?php esc_html_e( 'Setup Google OAuth', 'livenettv-pro' ); ?>
            </a>
        </div>
    </div>
</div>
