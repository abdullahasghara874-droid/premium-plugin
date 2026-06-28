<?php
/**
 * Scheduled tasks (WP-Cron) for LiveNetTV Pro
 */

defined('ABSPATH') || exit;

class LiveNetTV_Pro_Cron {

    public function __construct() {
        add_action('init', array($this, 'init'));
    }

    public function init() {
        add_action('livenettv_pro_check_expired_memberships', array($this, 'check_expired_memberships'));
        add_action('livenettv_pro_send_expiry_warnings', array($this, 'send_expiry_warnings'));
    }

    public function schedule_events() {
        if (!wp_next_scheduled('livenettv_pro_check_expired_memberships')) {
            wp_schedule_event(time(), 'hourly', 'livenettv_pro_check_expired_memberships');
        }

        if (!wp_next_scheduled('livenettv_pro_send_expiry_warnings')) {
            wp_schedule_event(time(), 'daily', 'livenettv_pro_send_expiry_warnings');
        }
    }

    public function clear_scheduled_events() {
        wp_clear_scheduled_hook('livenettv_pro_check_expired_memberships');
        wp_clear_scheduled_hook('livenettv_pro_send_expiry_warnings');
    }

    public function check_expired_memberships() {
        global $wpdb;

        $today = current_time('Y-m-d');

        $users = get_users(array(
            'meta_key' => 'livenettv_pro_status',
            'meta_value' => 'active',
            'number' => -1,
            'fields' => array('ID'),
        ));

        foreach ($users as $user) {
            $expiry_date = get_user_meta($user->ID, 'livenettv_pro_expiry_date', true);
            $plan_slug = get_user_meta($user->ID, 'livenettv_pro_plan', '');

            if ($plan_slug === 'lifetime') {
                continue;
            }

            if ($expiry_date && $expiry_date < $today) {
                $this->expire_membership($user->ID);
            }
        }
    }

    private function expire_membership($user_id) {
        update_user_meta($user_id, 'livenettv_pro_status', 'expired');

        $user = get_user_by('ID', $user_id);
        if ($user) {
            $emails = livenettv_pro()->get_emails();
            $emails->send_expiry_notification($user_id);
        }

        do_action('livenettv_pro_membership_expired', $user_id);
    }

    public function send_expiry_warnings() {
        $warning_days = get_option('livenettv_pro_expiry_warning_days', array(7, 3, 1));

        if (!is_array($warning_days)) {
            $warning_days = array(7, 3, 1);
        }

        foreach ($warning_days as $days) {
            $this->send_warnings_for_days((int) $days);
        }
    }

    private function send_warnings_for_days($days_before_expiry) {
        $target_date = date('Y-m-d', strtotime("+{$days_before_expiry} days"));

        $users = get_users(array(
            'meta_key' => 'livenettv_pro_expiry_date',
            'meta_value' => $target_date,
            'meta_compare' => '=',
            'number' => -1,
            'fields' => array('ID'),
            'meta_query' => array(
                array(
                    'key' => 'livenettv_pro_status',
                    'value' => 'active',
                    'compare' => '=',
                ),
            ),
        ));

        $emails = livenettv_pro()->get_emails();

        foreach ($users as $user) {
            $plan_slug = get_user_meta($user->ID, 'livenettv_pro_plan', '');

            if ($plan_slug === 'lifetime') {
                continue;
            }

            $warning_sent_key = "livenettv_pro_expiry_warning_sent_{$days_before_expiry}";
            $warning_sent = get_user_meta($user->ID, $warning_sent_key, true);

            if ($warning_sent) {
                continue;
            }

            $emails->send_expiry_warning($user->ID, $days_before_expiry);

            update_user_meta($user->ID, $warning_sent_key, true);
        }
    }

    public function get_next_scheduled($event) {
        return wp_next_scheduled("livenettv_pro_{$event}");
    }

    public static function manual_expired_check() {
        $instance = new self();
        $instance->check_expired_memberships();

        return array(
            'success' => true,
            'message' => __('Expired memberships processed successfully.', 'livenettv-pro'),
        );
    }
}
