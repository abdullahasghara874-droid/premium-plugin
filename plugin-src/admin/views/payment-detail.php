<?php
/**
 * Payment detail view
 */

defined('ABSPATH') || exit;

$back_url = admin_url('admin.php?page=livenettv-pro-payments');

?>
<div class="wrap livenettv-pro-admin">
    <h1>
        <a href="<?php echo esc_url($back_url); ?>" class="page-title-action"><?php _e('Back to Payments', 'livenettv-pro'); ?></a>
        <?php _e('Payment Details', 'livenettv-pro'); ?> #<?php echo esc_html($payment->id); ?>
    </h1>

    <div class="livenettv-pro-payment-detail-container">
        <div class="livenettv-pro-payment-main">
            <div class="livenettv-pro-card livenettv-pro-payment-info-card">
                <h2><?php _e('Payment Information', 'livenettv-pro'); ?></h2>

                <table class="form-table">
                    <tr>
                        <th scope="row"><?php _e('Status', 'livenettv-pro'); ?></th>
                        <td>
                            <?php
                            $status_classes = array(
                                'pending' => 'status-pending',
                                'approved' => 'status-approved',
                                'rejected' => 'status-rejected',
                            );
                            $status_labels = array(
                                'pending' => __('Pending Review', 'livenettv-pro'),
                                'approved' => __('Approved', 'livenettv-pro'),
                                'rejected' => __('Rejected', 'livenettv-pro'),
                            );
                            ?>
                            <span class="livenettv-pro-status-badge large <?php echo esc_attr($status_classes[$payment->status]); ?>">
                                <?php echo esc_html($status_labels[$payment->status]); ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Plan', 'livenettv-pro'); ?></th>
                        <td>
                            <strong><?php echo esc_html($payment->plan_name); ?></strong><br>
                            <span class="description"><?php echo esc_html($payment->plan_duration); ?> <?php _e('days', 'livenettv-pro'); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Amount', 'livenettv-pro'); ?></th>
                        <td>
                            <strong>$<?php echo esc_html(number_format($payment->plan_price, 2)); ?> <?php echo esc_html($payment->currency); ?></strong>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Payment Method', 'livenettv-pro'); ?></th>
                        <td>
                            <span class="livenettv-pro-crypto-badge <?php echo esc_attr(strtolower($payment->crypto_type)); ?>">
                                <?php echo esc_html($payment->crypto_type); ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Wallet Address', 'livenettv-pro'); ?></th>
                        <td>
                            <code class="livenettv-pro-wallet-address"><?php echo esc_html($payment->wallet_address); ?></code>
                            <button type="button" class="button button-small livenettv-pro-copy-btn" data-copy="<?php echo esc_attr($payment->wallet_address); ?>">
                                <?php _e('Copy', 'livenettv-pro'); ?>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Transaction ID', 'livenettv-pro'); ?></th>
                        <td>
                            <code class="livenettv-pro-txid-full"><?php echo esc_html($payment->transaction_id); ?></code>
                            <button type="button" class="button button-small livenettv-pro-copy-btn" data-copy="<?php echo esc_attr($payment->transaction_id); ?>">
                                <?php _e('Copy', 'livenettv-pro'); ?>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Submitted', 'livenettv-pro'); ?></th>
                        <td>
                            <?php echo esc_html(date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($payment->submitted_at))); ?>
                        </td>
                    </tr>
                    <?php if ($payment->processed_at) : ?>
                        <tr>
                            <th scope="row"><?php _e('Processed', 'livenettv-pro'); ?></th>
                            <td>
                                <?php echo esc_html(date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($payment->processed_at))); ?>
                                <?php
                                $processor = get_user_by('ID', $payment->processed_by);
                                if ($processor) :
                                ?>
                                    <span class="description">(<?php _e('by', 'livenettv-pro'); ?> <?php echo esc_html($processor->display_name); ?>)</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <?php if ($payment->membership_start && $payment->membership_end) : ?>
                        <tr>
                            <th scope="row"><?php _e('Membership Period', 'livenettv-pro'); ?></th>
                            <td>
                                <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($payment->membership_start))); ?>
                                <?php _e('to', 'livenettv-pro'); ?>
                                <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($payment->membership_end))); ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <?php if ($payment->notes) : ?>
                        <tr>
                            <th scope="row"><?php _e('Notes', 'livenettv-pro'); ?></th>
                            <td>
                                <?php echo esc_html($payment->notes); ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </table>
            </div>

            <div class="livenettv-pro-card livenettv-pro-screenshot-card">
                <h2><?php _e('Payment Screenshot', 'livenettv-pro'); ?></h2>
                <div class="livenettv-pro-screenshot-container">
                    <?php if (!empty($payment->screenshot_path)) : ?>
                        <a href="<?php echo esc_url($payment->screenshot_path); ?>" target="_blank">
                            <img src="<?php echo esc_url($payment->screenshot_path); ?>" alt="<?php esc_attr_e('Payment Screenshot', 'livenettv-pro'); ?>" class="livenettv-pro-screenshot">
                        </a>
                        <p class="description">
                            <a href="<?php echo esc_url($payment->screenshot_path); ?>" target="_blank"><?php _e('View full size', 'livenettv-pro'); ?></a>
                        </p>
                    <?php else : ?>
                        <p class="description"><?php _e('No screenshot uploaded.', 'livenettv-pro'); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="livenettv-pro-payment-sidebar">
            <div class="livenettv-pro-card livenettv-pro-user-card">
                <h2><?php _e('User Information', 'livenettv-pro'); ?></h2>
                <?php if ($user) : ?>
                    <div class="livenettv-pro-user-avatar">
                        <?php echo get_avatar($user->ID, 96); ?>
                    </div>
                    <table class="form-table livenettv-pro-user-table">
                        <tr>
                            <th><?php _e('Name', 'livenettv-pro'); ?></th>
                            <td><?php echo esc_html($user->display_name); ?></td>
                        </tr>
                        <tr>
                            <th><?php _e('Email', 'livenettv-pro'); ?></th>
                            <td>
                                <a href="mailto:<?php echo esc_attr($user->user_email); ?>">
                                    <?php echo esc_html($user->user_email); ?>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e('Registered', 'livenettv-pro'); ?></th>
                            <td><?php echo esc_html(date_i18n(get_option('date_format'), strtotime($user->user_registered))); ?></td>
                        </tr>
                        <?php
                        $membership_status = get_user_meta($user->ID, 'livenettv_pro_status', true);
                        $expiry_date = get_user_meta($user->ID, 'livenettv_pro_expiry_date', true);
                        ?>
                        <tr>
                            <th><?php _e('Pro Status', 'livenettv-pro'); ?></th>
                            <td>
                                <?php if ($membership_status === 'active') : ?>
                                    <span class="livenettv-pro-status-badge status-approved"><?php _e('Active', 'livenettv-pro'); ?></span>
                                <?php elseif ($membership_status === 'expired') : ?>
                                    <span class="livenettv-pro-status-badge status-rejected"><?php _e('Expired', 'livenettv-pro'); ?></span>
                                <?php else : ?>
                                    <span class="livenettv-pro-status-badge status-pending"><?php _e('Free', 'livenettv-pro'); ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php if ($expiry_date) : ?>
                            <tr>
                                <th><?php _e('Expiry Date', 'livenettv-pro'); ?></th>
                                <td><?php echo esc_html(date_i18n(get_option('date_format'), strtotime($expiry_date))); ?></td>
                            </tr>
                        <?php endif; ?>
                    </table>
                    <p>
                        <a href="<?php echo esc_url(admin_url('user-edit.php?user_id=' . $user->ID)); ?>" class="button">
                            <?php _e('Edit User', 'livenettv-pro'); ?>
                        </a>
                    </p>
                <?php else : ?>
                    <p class="description"><?php _e('User no longer exists.', 'livenettv-pro'); ?></p>
                <?php endif; ?>
            </div>

            <?php if ($payment->status === 'pending') : ?>
                <div class="livenettv-pro-card livenettv-pro-actions-card">
                    <h2><?php _e('Actions', 'livenettv-pro'); ?></h2>
                    <p class="livenettv-pro-action-buttons">
                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?action=livenettv_approve_payment&payment_id=' . $payment->id), 'livenettv_approve_payment_' . $payment->id)); ?>" class="button button-primary button-large livenettv-pro-approve-btn">
                            <?php _e('Approve Payment', 'livenettv-pro'); ?>
                        </a>
                    </p>
                    <div class="livenettv-pro-reject-form">
                        <h3><?php _e('Reject Payment', 'livenettv-pro'); ?></h3>
                        <form method="get" id="livenettv-reject-form">
                            <input type="hidden" name="action" value="livenettv_reject_payment">
                            <input type="hidden" name="payment_id" value="<?php echo esc_attr($payment->id); ?>">
                            <?php wp_nonce_field('livenettv_reject_payment_' . $payment->id, 'reject_nonce'); ?>
                            <p>
                                <label for="rejection_reason"><?php _e('Reason (optional):', 'livenettv-pro'); ?></label><br>
                                <textarea name="reason" id="rejection_reason" rows="3" class="large-text" placeholder="<?php esc_attr_e('Explain why the payment is being rejected...', 'livenettv-pro'); ?>"></textarea>
                            </p>
                            <p>
                                <button type="submit" class="button livenettv-pro-reject-btn">
                                    <?php _e('Reject Payment', 'livenettv-pro'); ?>
                                </button>
                            </p>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
