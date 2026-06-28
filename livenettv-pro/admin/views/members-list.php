<?php
defined( 'ABSPATH' ) || exit;
?>
<div class="wrap lntv-admin">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

    <div class="lntv-filters">
        <form method="get">
            <input type="hidden" name="page" value="livenettv-pro-members">
            <select name="mstatus" id="mstatus-filter">
                <option value=""><?php esc_html_e( 'All Members', 'livenettv-pro' ); ?></option>
                <option value="active" <?php selected( $status, 'active' ); ?>><?php esc_html_e( 'Active', 'livenettv-pro' ); ?></option>
                <option value="expired" <?php selected( $status, 'expired' ); ?>><?php esc_html_e( 'Expired', 'livenettv-pro' ); ?></option>
            </select>
            <button type="submit" class="button"><?php esc_html_e( 'Filter', 'livenettv-pro' ); ?></button>
        </form>
    </div>

    <?php if ( $pages > 1 ) : ?>
        <div class="tablenav top">
            <div class="tablenav-pages">
                <?php
                echo paginate_links( array(
                    'base'      => add_query_arg( 'paged', '%#%', admin_url( 'admin.php?page=livenettv-pro-members' ) ),
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
                <th><?php esc_html_e( 'User', 'livenettv-pro' ); ?></th>
                <th><?php esc_html_e( 'Email', 'livenettv-pro' ); ?></th>
                <th><?php esc_html_e( 'Plan', 'livenettv-pro' ); ?></th>
                <th><?php esc_html_e( 'Status', 'livenettv-pro' ); ?></th>
                <th><?php esc_html_e( 'Started', 'livenettv-pro' ); ?></th>
                <th><?php esc_html_e( 'Expires', 'livenettv-pro' ); ?></th>
                <th><?php esc_html_e( 'Days Left', 'livenettv-pro' ); ?></th>
                <th><?php esc_html_e( 'Actions', 'livenettv-pro' ); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if ( empty( $users ) ) : ?>
                <tr>
                    <td colspan="8"><?php esc_html_e( 'No members found.', 'livenettv-pro' ); ?></td>
                </tr>
            <?php else : ?>
                <?php foreach ( $users as $user ) :
                    $plan       = get_user_meta( $user->ID, 'livenettv_pro_plan', true );
                    $mstatus    = get_user_meta( $user->ID, 'livenettv_pro_status', true );
                    $start_date = get_user_meta( $user->ID, 'livenettv_pro_start_date', true );
                    $expiry     = get_user_meta( $user->ID, 'livenettv_pro_expiry_date', true );
                    $days_left  = $membership->get_days_remaining( $user->ID );
                    $plan_data  = $membership->get_plan( $plan );
                    $is_lifetime = 'lifetime' === $plan;
                ?>
                    <tr>
                        <td>
                            <a href="<?php echo esc_url( admin_url( 'user-edit.php?user_id=' . $user->ID ) ); ?>">
                                <?php echo esc_html( $user->display_name ); ?>
                            </a>
                        </td>
                        <td><?php echo esc_html( $user->user_email ); ?></td>
                        <td><?php echo esc_html( $plan_data['name'] ?? $plan ); ?></td>
                        <td>
                            <span class="lntv-status-badge status-<?php echo esc_attr( $mstatus ); ?>">
                                <?php echo esc_html( ucfirst( $mstatus ) ); ?>
                            </span>
                        </td>
                        <td><?php echo esc_html( $start_date ? wp_date( 'M j, Y', strtotime( $start_date ) ) : '-' ); ?></td>
                        <td>
                            <?php if ( $is_lifetime ) : ?>
                                <span class="lntv-lifetime"><?php esc_html_e( 'Lifetime', 'livenettv-pro' ); ?></span>
                            <?php else : ?>
                                <?php echo esc_html( $expiry ? wp_date( 'M j, Y', strtotime( $expiry ) ) : '-' ); ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ( $is_lifetime ) : ?>
                                <span class="dashicons dashicons-infinity"></span>
                            <?php elseif ( 'active' === $mstatus ) : ?>
                                <span class="<?php echo $days_left <= 7 ? 'lntv-warning' : ''; ?>">
                                    <?php echo esc_html( $days_left ); ?>
                                </span>
                            <?php else : ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo esc_url( admin_url( 'user-edit.php?user_id=' . $user->ID ) ); ?>" class="button button-small">
                                <?php esc_html_e( 'Edit', 'livenettv-pro' ); ?>
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
                    'base'      => add_query_arg( 'paged', '%#%', admin_url( 'admin.php?page=livenettv-pro-members' ) ),
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
