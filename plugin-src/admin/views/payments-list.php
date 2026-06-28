<?php
/**
 * Payments list view
 */

defined('ABSPATH') || exit;

$url_base = admin_url('admin.php?page=livenettv-pro-payments');

?>
<div class="wrap livenettv-pro-admin">
    <h1><span class="dashicons dashicons-money-alt"></span> <?php _e('Payment Requests', 'livenettv-pro'); ?></h1>

    <?php if (isset($_GET['approved'])) : ?>
        <div class="notice notice-success is-dismissible">
            <p><?php _e('Payment approved and membership activated.', 'livenettv-pro'); ?></p>
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

    <div class="livenettv-pro-tabs">
        <ul class="subsubsub">
            <li>
                <a href="<?php echo esc_url($url_base); ?>" <?php echo empty($status) ? 'class="current"' : ''; ?>>
                    <?php _e('All', 'livenettv-pro'); ?>
                    <span class="count">(<?php echo esc_html($this->db->get_payments_count()); ?>)</span>
                </a> |
            </li>
            <li>
                <a href="<?php echo esc_url(add_query_arg('status', 'pending', $url_base)); ?>" <?php echo $status === 'pending' ? 'class="current"' : ''; ?>>
                    <?php _e('Pending', 'livenettv-pro'); ?>
                    <span class="count">(<?php echo esc_html($this->db->get_payments_count('pending')); ?>)</span>
                </a> |
            </li>
            <li>
                <a href="<?php echo esc_url(add_query_arg('status', 'approved', $url_base)); ?>" <?php echo $status === 'approved' ? 'class="current"' : ''; ?>>
                    <?php _e('Approved', 'livenettv-pro'); ?>
                    <span class="count">(<?php echo esc_html($this->db->get_payments_count('approved')); ?>)</span>
                </a> |
            </li>
            <li>
                <a href="<?php echo esc_url(add_query_arg('status', 'rejected', $url_base)); ?>" <?php echo $status === 'rejected' ? 'class="current"' : ''; ?>>
                    <?php _e('Rejected', 'livenettv-pro'); ?>
                    <span class="count">(<?php echo esc_html($this->db->get_payments_count('rejected')); ?>)</span>
                </a>
            </li>
        </ul>
    </div>

    <form method="get">
        <input type="hidden" name="page" value="livenettv-pro-payments">
        <?php if (!empty($status)) : ?>
            <input type="hidden" name="status" value="<?php echo esc_attr($status); ?>">
        <?php endif; ?>
    </form>

    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th scope="col" class="manage-column column-id"><?php _e('ID', 'livenettv-pro'); ?></th>
                <th scope="col" class="manage-column column-user"><?php _e('User', 'livenettv-pro'); ?></th>
                <th scope="col" class="manage-column column-plan"><?php _e('Plan', 'livenettv-pro'); ?></th>
                <th scope="col" class="manage-column column-price"><?php _e('Amount', 'livenettv-pro'); ?></th>
                <th scope="col" class="manage-column column-crypto"><?php _e('Crypto', 'livenettv-pro'); ?></th>
                <th scope="col" class="manage-column column-txid"><?php _e('TXID', 'livenettv-pro'); ?></th>
                <th scope="col" class="manage-column column-status"><?php _e('Status', 'livenettv-pro'); ?></th>
                <th scope="col" class="manage-column column-date"><?php _e('Submitted', 'livenettv-pro'); ?></th>
                <th scope="col" class="manage-column column-actions"><?php _e('Actions', 'livenettv-pro'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($payments)) : ?>
                <?php foreach ($payments as $payment) : ?>
                    <?php $user = get_user_by('ID', $payment->user_id); ?>
                    <tr class="livenettv-pro-payment-row status-<?php echo esc_attr($payment->status); ?>">
                        <td class="column-id">
                            <a href="<?php echo esc_url(add_query_arg(array('action' => 'view', 'id' => $payment->id), $url_base)); ?>">
                                #<?php echo esc_html($payment->id); ?>
                            </a>
                        </td>
                        <td class="column-user">
                            <?php if ($user) : ?>
                                <div class="livenettv-pro-user-info">
                                    <strong><?php echo esc_html($user->display_name); ?></strong><br>
                                    <span class="livenettv-pro-email"><?php echo esc_html($user->user_email); ?></span>
                                </div>
                            <?php else : ?>
                                <span class="livenettv-pro-deleted-user"><?php _e('User deleted', 'livenettv-pro'); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="column-plan">
                            <strong><?php echo esc_html($payment->plan_name); ?></strong><br>
                            <span class="livenettv-pro-duration"><?php echo esc_html($payment->plan_duration); ?> <?php _e('days', 'livenettv-pro'); ?></span>
                        </td>
                        <td class="column-price">
                            $<?php echo esc_html(number_format($payment->plan_price, 2)); ?>
                        </td>
                        <td class="column-crypto">
                            <span class="livenettv-pro-crypto-badge <?php echo esc_attr(strtolower($payment->crypto_type)); ?>">
                                <?php echo esc_html($payment->crypto_type); ?>
                            </span>
                        </td>
                        <td class="column-txid">
                            <code class="livenettv-pro-txid"><?php echo esc_html(substr($payment->transaction_id, 0, 16)); ?>...</code>
                        </td>
                        <td class="column-status">
                            <?php
                            $status_classes = array(
                                'pending' => 'status-pending',
                                'approved' => 'status-approved',
                                'rejected' => 'status-rejected',
                            );
                            $status_labels = array(
                                'pending' => __('Pending', 'livenettv-pro'),
                                'approved' => __('Approved', 'livenettv-pro'),
                                'rejected' => __('Rejected', 'livenettv-pro'),
                            );
                            ?>
                            <span class="livenettv-pro-status-badge <?php echo esc_attr($status_classes[$payment->status]); ?>">
                                <?php echo esc_html($status_labels[$payment->status]); ?>
                            </span>
                        </td>
                        <td class="column-date">
                            <?php echo esc_html(date_i18n(get_option('date_format') . ' H:i', strtotime($payment->submitted_at))); ?>
                        </td>
                        <td class="column-actions">
                            <a href="<?php echo esc_url(add_query_arg(array('action' => 'view', 'id' => $payment->id), $url_base)); ?>" class="button button-small">
                                <?php _e('View', 'livenettv-pro'); ?>
                            </a>
                            <?php if ($payment->status === 'pending') : ?>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?action=livenettv_approve_payment&payment_id=' . $payment->id), 'livenettv_approve_payment_' . $payment->id)); ?>" class="button button-small button-primary livenettv-pro-approve-btn">
                                    <?php _e('Approve', 'livenettv-pro'); ?>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="9">
                        <p class="livenettv-pro-no-results"><?php _e('No payment requests found.', 'livenettv-pro'); ?></p>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php if ($total_pages > 1) : ?>
        <div class="tablenav bottom">
            <div class="tablenav-pages">
                <?php
                echo paginate_links(array(
                    'base' => add_query_arg('paged', '%#%'),
                    'format' => '',
                    'prev_text' => __('&laquo;', 'livenettv-pro'),
                    'next_text' => __('&raquo;', 'livenettv-pro'),
                    'total' => $total_pages,
                    'current' => $paged,
                ));
                ?>
            </div>
        </div>
    <?php endif; ?>
</div>
