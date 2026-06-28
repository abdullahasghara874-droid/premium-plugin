<?php
/**
 * Admin dashboard view
 */

defined('ABSPATH') || exit;

?>
<div class="wrap livenettv-pro-admin">
    <h1><span class="dashicons dashicons-star-filled"></span> <?php _e('LiveNetTV Pro Dashboard', 'livenettv-pro'); ?></h1>

    <?php if (isset($_GET['approved'])) : ?>
        <div class="notice notice-success is-dismissible">
            <p><?php _e('Payment approved and membership activated successfully.', 'livenettv-pro'); ?></p>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['rejected'])) : ?>
        <div class="notice notice-success is-dismissible">
            <p><?php _e('Payment rejected and user notified.', 'livenettv-pro'); ?></p>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])) : ?>
        <div class="notice notice-error is-dismissible">
            <p><?php echo esc_html(urldecode($_GET['error'])); ?></p>
        </div>
    <?php endif; ?>

    <div class="livenettv-pro-dashboard-cards">
        <div class="livenettv-pro-card">
            <div class="livenettv-pro-card-header">
                <span class="dashicons dashicons-users"></span>
                <h2><?php _e('Pro Members', 'livenettv-pro'); ?></h2>
            </div>
            <div class="livenettv-pro-card-body">
                <div class="livenettv-pro-stat-value"><?php echo esc_html($active_pro_users); ?></div>
                <p><?php _e('Active premium members', 'livenettv-pro'); ?></p>
            </div>
        </div>

        <div class="livenettv-pro-card">
            <div class="livenettv-pro-card-header">
                <span class="dashicons dashicons-warning"></span>
                <h2><?php _e('Pending Payments', 'livenettv-pro'); ?></h2>
            </div>
            <div class="livenettv-pro-card-body">
                <div class="livenettv-pro-stat-value <?php echo $pending_count > 0 ? 'has-pending' : ''; ?>">
                    <?php echo esc_html($pending_count); ?>
                </div>
                <?php if ($pending_count > 0) : ?>
                    <p class="livenettv-pro-action-note">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=livenettv-pro-payments&status=pending')); ?>">
                            <?php _e('Review now', 'livenettv-pro'); ?>
                        </a>
                    </p>
                <?php else : ?>
                    <p><?php _e('No pending payments', 'livenettv-pro'); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <div class="livenettv-pro-card">
            <div class="livenettv-pro-card-header">
                <span class="dashicons dashicons-chart-bar"></span>
                <h2><?php _e('Total Revenue', 'livenettv-pro'); ?></h2>
            </div>
            <div class="livenettv-pro-card-body">
                <div class="livenettv-pro-stat-value">$<?php echo esc_html(number_format($total_revenue, 2)); ?></div>
                <p><?php _e('From approved payments', 'livenettv-pro'); ?></p>
            </div>
        </div>

        <div class="livenettv-pro-card">
            <div class="livenettv-pro-card-header">
                <span class="dashicons dashicons-clock"></span>
                <h2><?php _e('Last 7 Days', 'livenettv-pro'); ?></h2>
            </div>
            <div class="livenettv-pro-card-body">
                <div class="livenettv-pro-stat-value">$<?php echo esc_html(number_format($last_7_days_revenue, 2)); ?></div>
                <p><?php _e('Revenue in last 7 days', 'livenettv-pro'); ?></p>
            </div>
        </div>

        <div class="livenettv-pro-card">
            <div class="livenettv-pro-card-header">
                <span class="dashicons dashicons-calendar-alt"></span>
                <h2><?php _e('Last 30 Days', 'livenettv-pro'); ?></h2>
            </div>
            <div class="livenettv-pro-card-body">
                <div class="livenettv-pro-stat-value">$<?php echo esc_html(number_format($last_30_days_revenue, 2)); ?></div>
                <p><?php _e('Revenue in last 30 days', 'livenettv-pro'); ?></p>
            </div>
        </div>

        <div class="livenettv-pro-card">
            <div class="livenettv-pro-card-header">
                <span class="dashicons dashicons-yes-alt"></span>
                <h2><?php _e('Approved', 'livenettv-pro'); ?></h2>
            </div>
            <div class="livenettv-pro-card-body">
                <div class="livenettv-pro-stat-value"><?php echo esc_html($approved_count); ?></div>
                <p><?php _e('Total approved payments', 'livenettv-pro'); ?></p>
            </div>
        </div>

        <div class="livenettv-pro-card">
            <div class="livenettv-pro-card-header">
                <span class="dashicons dashicons-dismiss"></span>
                <h2><?php _e('Rejected', 'livenettv-pro'); ?></h2>
            </div>
            <div class="livenettv-pro-card-body">
                <div class="livenettv-pro-stat-value"><?php echo esc_html($rejected_count); ?></div>
                <p><?php _e('Total rejected payments', 'livenettv-pro'); ?></p>
            </div>
        </div>
    </div>

    <div class="livenettv-pro-quick-links">
        <h2><?php _e('Quick Actions', 'livenettv-pro'); ?></h2>
        <div class="livenettv-pro-quick-actions">
            <a href="<?php echo esc_url(admin_url('admin.php?page=livenettv-pro-payments&status=pending')); ?>" class="button button-primary">
                <?php printf(_n('Review %d Payment', 'Review %d Payments', $pending_count, 'livenettv-pro'), $pending_count); ?>
            </a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=livenettv-pro-members')); ?>" class="button">
                <?php _e('View Pro Members', 'livenettv-pro'); ?>
            </a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=livenettv-pro-wallets')); ?>" class="button">
                <?php _e('Configure Wallets', 'livenettv-pro'); ?>
            </a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=livenettv-pro-google')); ?>" class="button">
                <?php _e('Google Settings', 'livenettv-pro'); ?>
            </a>
        </div>
    </div>
</div>
