<?php
defined( 'ABSPATH' ) || exit;
?>
<div class="wrap lntv-admin">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

    <div class="lntv-filters">
        <form method="get">
            <input type="hidden" name="page" value="livenettv-pro-payments">
            <select name="status" id="status-filter">
                <option value=""><?php esc_html_e( 'All Statuses', 'livenettv-pro' ); ?></option>
                <option value="pending" <?php selected( $status, 'pending' ); ?>><?php esc_html_e( 'Pending', 'livenettv-pro' ); ?></option>
                <option value="approved" <?php selected( $status, 'approved' ); ?>><?php esc_html_e( 'Approved', 'livenettv-pro' ); ?></option>
                <option value="rejected" <?php selected( $status, 'rejected' ); ?>><?php esc_html_e( 'Rejected', 'livenettv-pro' ); ?></option>
            </select>
            <button type="submit" class="button"><?php esc_html_e( 'Filter', 'livenettv-pro' ); ?></button>
        </form>
    </div>

    <?php if ( $pages > 1 ) : ?>
        <div class="tablenav top">
            <div class="tablenav-pages">
                <?php
                echo paginate_links( array(
                    'base'      => add_query_arg( 'paged', '%#%', admin_url( 'admin.php?page=livenettv-pro-payments' ) ),
                    'format'    => '',
                    'prev_text' => '&laquo;',
                    'next_text' => '&raquo;',
                    'total'     => $pages,
                    'current'   => $page,
                ) );
                ?>
            </div>
        </div>
    <?php endif; ?>

    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th class="column-id"><?php esc_html_e( 'ID', 'livenettv-pro' ); ?></th>
                <th><?php esc_html_e( 'User', 'livenettv-pro' ); ?></th>
                <th><?php esc_html_e( 'Plan', 'livenettv-pro' ); ?></th>
                <th><?php esc_html_e( 'Amount', 'livenettv-pro' ); ?></th>
                <th><?php esc_html_e( 'Crypto', 'livenettv-pro' ); ?></th>
                <th><?php esc_html_e( 'Transaction ID', 'livenettv-pro' ); ?></th>
                <th><?php esc_html_e( 'Status', 'livenettv-pro' ); ?></th>
                <th><?php esc_html_e( 'Submitted', 'livenettv-pro' ); ?></th>
                <th><?php esc_html_e( 'Actions', 'livenettv-pro' ); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if ( empty( $payments ) ) : ?>
                <tr>
                    <td colspan="9"><?php esc_html_e( 'No payments found.', 'livenettv-pro' ); ?></td>
                </tr>
            <?php else : ?>
                <?php foreach ( $payments as $payment ) :
                    $user = get_user_by( 'id', $payment->user_id );
                ?>
                    <tr>
                        <td>#<?php echo esc_html( $payment->id ); ?></td>
                        <td>
                            <?php if ( $user ) : ?>
                                <a href="<?php echo esc_url( admin_url( 'user-edit.php?user_id=' . $user->ID ) ); ?>">
                                    <?php echo esc_html( $user->user_login ); ?>
                                </a>
                                <br><small><?php echo esc_html( $user->user_email ); ?></small>
                            <?php else : ?>
                                <?php esc_html_e( 'Unknown User', 'livenettv-pro' ); ?>
                            <?php endif; ?>
                        </td>
                        <td><?php echo esc_html( $payment->plan_name ); ?></td>
                        <td><?php echo esc_html( number_format( $payment->plan_price, 2 ) . ' ' . $payment->currency ); ?></td>
                        <td><?php echo esc_html( strtoupper( $payment->crypto_type ) ); ?></td>
                        <td>
                            <code class="lntv-txid"><?php echo esc_html( $payment->transaction_id ); ?></code>
                        </td>
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
            <?php endif; ?>
        </tbody>
    </table>

    <?php if ( $pages > 1 ) : ?>
        <div class="tablenav bottom">
            <div class="tablenav-pages">
                <?php
                echo paginate_links( array(
                    'base'      => add_query_arg( 'paged', '%#%', admin_url( 'admin.php?page=livenettv-pro-payments' ) ),
                    'format'    => '',
                    'prev_text' => '&laquo;',
                    'next_text' => '&raquo;',
                    'total'     => $pages,
                    'current'   => $page,
                ) );
                ?>
            </div>
        </div>
    <?php endif; ?>
</div>
