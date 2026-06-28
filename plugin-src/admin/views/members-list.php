<?php
/**
 * Pro Members list view
 */

defined('ABSPATH') || exit;

?>
<div class="wrap livenettv-pro-admin">
    <h1><span class="dashicons dashicons-groups"></span> <?php _e('Pro Members', 'livenettv-pro'); ?></h1>

    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th scope="col" class="manage-column column-user"><?php _e('User', 'livenettv-pro'); ?></th>
                <th scope="col" class="manage-column column-plan"><?php _e('Plan', 'livenettv-pro'); ?></th>
                <th scope="col" class="manage-column column-started"><?php _e('Started', 'livenettv-pro'); ?></th>
                <th scope="col" class="manage-column column-expires"><?php _e('Expires', 'livenettv-pro'); ?></th>
                <th scope="col" class="manage-column column-days"><?php _e('Days Left', 'livenettv-pro'); ?></th>
                <th scope="col" class="manage-column column-actions"><?php _e('Actions', 'livenettv-pro'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($members)) : ?>
                <?php foreach ($members as $member) : ?>
                    <?php
                    $membership = livenettv_pro()->get_membership();
                    $plan_slug = get_user_meta($member->ID, 'livenettv_pro_plan', true);
                    $plan = $membership->get_plan($plan_slug);
                    $start_date = get_user_meta($member->ID, 'livenettv_pro_start_date', true);
                    $expiry_date = get_user_meta($member->ID, 'livenettv_pro_expiry_date', true);
                    $days_remaining = $membership->get_days_remaining($member->ID);
                    $is_lifetime = $plan_slug === 'lifetime';
                    ?>
                    <tr>
                        <td class="column-user">
                            <div class="livenettv-pro-user-info">
                                <?php echo get_avatar($member->ID, 40); ?>
                                <div>
                                    <strong><?php echo esc_html($member->display_name); ?></strong><br>
                                    <span class="description"><?php echo esc_html($member->user_email); ?></span>
                                </div>
                            </div>
                        </td>
                        <td class="column-plan">
                            <?php if ($plan) : ?>
                                <?php echo esc_html($plan['name']); ?>
                            <?php else : ?>
                                <?php _e('Unknown', 'livenettv-pro'); ?>
                            <?php endif; ?>
                        </td>
                        <td class="column-started">
                            <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($start_date))); ?>
                        </td>
                        <td class="column-expires">
                            <?php if ($is_lifetime) : ?>
                                <span class="livenettv-pro-lifetime"><?php _e('Never (Lifetime)', 'livenettv-pro'); ?></span>
                            <?php else : ?>
                                <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($expiry_date))); ?>
                            <?php endif; ?>
                        </td>
                        <td class="column-days">
                            <?php if ($is_lifetime) : ?>
                                <span class="livenettv-pro-lifetime">-</span>
                            <?php else : ?>
                                <span class="<?php echo $days_remaining <= 7 ? 'livenettv-pro-expiring-soon' : ''; ?>">
                                    <?php echo esc_html($days_remaining); ?>
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="column-actions">
                            <a href="<?php echo esc_url(admin_url('user-edit.php?user_id=' . $member->ID)); ?>" class="button button-small">
                                <?php _e('Edit', 'livenettv-pro'); ?>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="6">
                        <p class="livenettv-pro-no-results"><?php _e('No active Pro members found.', 'livenettv-pro'); ?></p>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
