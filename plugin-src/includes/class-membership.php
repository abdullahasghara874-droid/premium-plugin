<?php
/**
 * Membership management for LiveNetTV Pro
 */

defined('ABSPATH') || exit;

class LiveNetTV_Pro_Membership {

    private static $plans = array();

    public function __construct() {
        self::$plans = $this->get_default_plans();
    }

    private function get_default_plans() {
        return array(
            '1_month' => array(
                'name' => __('1 Month Pro', 'livenettv-pro'),
                'duration' => 30,
                'duration_text' => __('30 days', 'livenettv-pro'),
                'price' => 9.99,
                'currency' => 'USD',
            ),
            '3_months' => array(
                'name' => __('3 Months Pro', 'livenettv-pro'),
                'duration' => 90,
                'duration_text' => __('90 days', 'livenettv-pro'),
                'price' => 24.99,
                'currency' => 'USD',
            ),
            '6_months' => array(
                'name' => __('6 Months Pro', 'livenettv-pro'),
                'duration' => 180,
                'duration_text' => __('6 months', 'livenettv-pro'),
                'price' => 44.99,
                'currency' => 'USD',
            ),
            '1_year' => array(
                'name' => __('1 Year Pro', 'livenettv-pro'),
                'duration' => 365,
                'duration_text' => __('1 year', 'livenettv-pro'),
                'price' => 74.99,
                'currency' => 'USD',
            ),
            'lifetime' => array(
                'name' => __('Lifetime Pro', 'livenettv-pro'),
                'duration' => 36500,
                'duration_text' => __('Lifetime', 'livenettv-pro'),
                'price' => 149.99,
                'currency' => 'USD',
            ),
        );
    }

    public function get_plans() {
        return apply_filters('livenettv_pro_plans', self::$plans);
    }

    public function get_plan($slug) {
        $plans = $this->get_plans();
        return isset($plans[$slug]) ? $plans[$slug] : null;
    }

    public function is_pro_user($user_id) {
        if (!$user_id) {
            return false;
        }

        $membership_status = get_user_meta($user_id, 'livenettv_pro_status', true);

        if ($membership_status !== 'active') {
            return false;
        }

        $expiry_date = get_user_meta($user_id, 'livenettv_pro_expiry_date', true);

        if (empty($expiry_date)) {
            return false;
        }

        $today = current_time('Y-m-d');
        if ($expiry_date >= $today) {
            return true;
        }

        $this->expire_membership($user_id);
        return false;
    }

    public function activate_membership($user_id, $plan_slug, $payment_id = 0) {
        $plan = $this->get_plan($plan_slug);
        if (!$plan) {
            return new WP_Error('invalid_plan', __('Invalid plan selected.', 'livenettv-pro'));
        }

        $start_date = current_time('Y-m-d');
        $expiry_date = date('Y-m-d', strtotime("+{$plan['duration']} days", strtotime($start_date)));

        update_user_meta($user_id, 'livenettv_pro_status', 'active');
        update_user_meta($user_id, 'livenettv_pro_plan', $plan_slug);
        update_user_meta($user_id, 'livenettv_pro_start_date', $start_date);
        update_user_meta($user_id, 'livenettv_pro_expiry_date', $expiry_date);
        update_user_meta($user_id, 'livenettv_pro_activated_at', current_time('mysql'));
        update_user_meta($user_id, 'livenettv_pro_payment_id', $payment_id);

        do_action('livenettv_pro_membership_activated', $user_id, $plan_slug, $payment_id);

        return true;
    }

    public function expire_membership($user_id) {
        update_user_meta($user_id, 'livenettv_pro_status', 'expired');

        do_action('livenettv_pro_membership_expired', $user_id);

        // Send expiry notification email
        $emails = livenettv_pro()->get_emails();
        $emails->send_expiry_notification($user_id);

        return true;
    }

    public function renew_membership($user_id, $plan_slug, $payment_id = 0) {
        $current_expiry = get_user_meta($user_id, 'livenettv_pro_expiry_date', true);
        $plan = $this->get_plan($plan_slug);

        if (!$plan) {
            return new WP_Error('invalid_plan', __('Invalid plan selected.', 'livenettv-pro'));
        }

        $today = current_time('Y-m-d');
        $start_date = ($current_expiry && $current_expiry > $today) ? $current_expiry : $today;
        $expiry_date = date('Y-m-d', strtotime("+{$plan['duration']} days", strtotime($start_date)));

        update_user_meta($user_id, 'livenettv_pro_status', 'active');
        update_user_meta($user_id, 'livenettv_pro_plan', $plan_slug);
        update_user_meta($user_id, 'livenettv_pro_expiry_date', $expiry_date);
        update_user_meta($user_id, 'livenettv_pro_renewed_at', current_time('mysql'));
        update_user_meta($user_id, 'livenettv_pro_renewal_payment_id', $payment_id);

        do_action('livenettv_pro_membership_renewed', $user_id, $plan_slug, $payment_id);

        return true;
    }

    public function get_user_membership_data($user_id) {
        return array(
            'status' => get_user_meta($user_id, 'livenettv_pro_status', ''),
            'plan' => get_user_meta($user_id, 'livenettv_pro_plan', ''),
            'start_date' => get_user_meta($user_id, 'livenettv_pro_start_date', ''),
            'expiry_date' => get_user_meta($user_id, 'livenettv_pro_expiry_date', ''),
            'activated_at' => get_user_meta($user_id, 'livenettv_pro_activated_at', ''),
            'payment_id' => get_user_meta($user_id, 'livenettv_pro_payment_id', ''),
        );
    }

    public function get_days_remaining($user_id) {
        $expiry_date = get_user_meta($user_id, 'livenettv_pro_expiry_date', true);

        if (empty($expiry_date)) {
            return 0;
        }

        $today = current_time('Y-m-d');

        if ($expiry_date < $today) {
            return 0;
        }

        $remaining = strtotime($expiry_date) - strtotime($today);
        return floor($remaining / DAY_IN_SECONDS);
    }

    public function is_lifetime($user_id) {
        $plan_slug = get_user_meta($user_id, 'livenettv_pro_plan', true);
        return $plan_slug === 'lifetime';
    }

    public function get_status_label($user_id) {
        if (!is_user_logged_in()) {
            return __('Free User', 'livenettv-pro');
        }

        if ($this->is_pro_user($user_id)) {
            $days = $this->get_days_remaining($user_id);
            if ($this->is_lifetime($user_id)) {
                return __('Lifetime Pro Member', 'livenettv-pro');
            }
            return sprintf(_n('%d day remaining', '%d days remaining', $days, 'livenettv-pro'), $days);
        }

        return __('Free User', 'livenettv-pro');
    }
}
