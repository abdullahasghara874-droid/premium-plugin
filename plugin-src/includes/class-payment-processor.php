<?php
/**
 * Payment processor for LiveNetTV Pro
 */

defined('ABSPATH') || exit;

class LiveNetTV_Pro_Payment_Processor {

    private $db;

    public function __construct() {
        add_action('init', array($this, 'init'));
    }

    public function init() {
        $this->db = livenettv_pro()->get_db();

        add_action('wp_ajax_livenettv_process_payment', array($this, 'ajax_process_payment'));
        add_action('admin_post_livenettv_submit_payment', array($this, 'handle_payment_submission'));
    }

    public function handle_payment_submission() {
        if (!is_user_logged_in()) {
            wp_die(__('You must be logged in to submit a payment.', 'livenettv-pro'));
        }

        check_admin_referer('livenettv_pro_payment', 'livenettv_pro_nonce');

        $user_id = get_current_user_id();

        $existing_pending = $this->db->get_user_pending_payment($user_id);
        if ($existing_pending) {
            wp_die(__('You already have a pending payment. Please wait for it to be processed.', 'livenettv-pro'));
        }

        $plan_slug = sanitize_text_field($_POST['livenettv_plan'] ?? '');
        $crypto_type = sanitize_text_field($_POST['livenettv_crypto'] ?? '');
        $txid = sanitize_text_field($_POST['livenettv_txid'] ?? '');

        $plans = livenettv_pro()->get_membership()->get_plans();
        if (!isset($plans[$plan_slug])) {
            wp_die(__('Invalid plan selected.', 'livenettv-pro'));
        }

        $plan = $plans[$plan_slug];
        $crypto_wallets = get_option('livenettv_pro_crypto_wallets', array());

        if (!isset($crypto_wallets[$crypto_type]) || empty($crypto_wallets[$crypto_type])) {
            wp_die(__('Invalid payment method selected.', 'livenettv-pro'));
        }

        if (empty($txid)) {
            wp_die(__('Transaction ID is required.', 'livenettv-pro'));
        }

        if (!isset($_FILES['livenettv_screenshot']) || $_FILES['livenettv_screenshot']['error'] !== UPLOAD_ERR_OK) {
            wp_die(__('Please upload a payment screenshot.', 'livenettv-pro'));
        }

        $screenshot_url = $this->handle_screenshot_upload($_FILES['livenettv_screenshot']);

        if (is_wp_error($screenshot_url)) {
            wp_die($screenshot_url->get_error_message());
        }

        $payment_data = array(
            'user_id' => $user_id,
            'plan_slug' => $plan_slug,
            'plan_name' => $plan['name'],
            'plan_duration' => $plan['duration'],
            'plan_price' => $plan['price'],
            'currency' => $plan['currency'],
            'crypto_type' => $crypto_type,
            'wallet_address' => $crypto_wallets[$crypto_type],
            'transaction_id' => $txid,
            'screenshot_path' => $screenshot_url,
        );

        $payment_id = $this->db->insert_payment($payment_data);

        if (is_wp_error($payment_id)) {
            wp_die($payment_id->get_error_message());
        }

        $emails = livenettv_pro()->get_emails();
        $emails->send_new_payment_admin_notification($payment_id, $user_id);

        $redirect_url = get_permalink(get_option('livenettv_pro_pro_page_id', ''));
        $redirect_url = add_query_arg('payment_submitted', '1', $redirect_url);

        wp_safe_redirect($redirect_url);
        exit;
    }

    private function handle_screenshot_upload($file) {
        $allowed_types = array('image/jpeg', 'image/png', 'image/gif', 'image/webp');
        $max_size = 5 * 1024 * 1024; // 5MB

        if (!in_array($file['type'], $allowed_types)) {
            return new WP_Error('invalid_type', __('Invalid file type. Please upload JPG, PNG, or GIF images only.', 'livenettv-pro'));
        }

        if ($file['size'] > $max_size) {
            return new WP_Error('file_too_large', __('File is too large. Maximum size is 5MB.', 'livenettv-pro'));
        }

        if (!function_exists('wp_handle_upload')) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }

        add_filter('upload_dir', array($this, 'get_secure_upload_dir'));

        $upload = wp_handle_upload($file, array(
            'test_form' => false,
            'mime_types' => $allowed_types,
        ));

        remove_filter('upload_dir', array($this, 'get_secure_upload_dir'));

        if (isset($upload['error'])) {
            return new WP_Error('upload_error', $upload['error']);
        }

        return $upload['url'];
    }

    public function get_secure_upload_dir($dirs) {
        $dirs['subdir'] = '/livenettv-pro-payments/' . gmdate('Y/m');
        $dirs['path'] = $dirs['basedir'] . $dirs['subdir'];
        $dirs['url'] = $dirs['baseurl'] . $dirs['subdir'];

        if (!file_exists($dirs['path'])) {
            wp_mkdir_p($dirs['path']);
            // Add index file to prevent directory listing
            file_put_contents($dirs['path'] . '/index.html', '');
        }

        return $dirs;
    }

    public function validate_payment_data($data) {
        $errors = array();

        if (empty($data['plan_slug'])) {
            $errors[] = __('Please select a plan.', 'livenettv-pro');
        }

        if (empty($data['crypto_type'])) {
            $errors[] = __('Please select a payment method.', 'livenettv-pro');
        }

        if (empty($data['transaction_id'])) {
            $errors[] = __('Transaction ID is required.', 'livenettv-pro');
        }

        if (empty($data['screenshot_path'])) {
            $errors[] = __('Payment screenshot is required.', 'livenettv-pro');
        }

        return empty($errors) ? true : new WP_Error('validation_failed', implode(' ', $errors));
    }

    public static function process_approval($payment_id, $admin_user_id) {
        $db = livenettv_pro()->get_db();
        $emails = livenettv_pro()->get_emails();
        $membership = livenettv_pro()->get_membership();

        $payment = $db->get_payment($payment_id);

        if (!$payment) {
            return new WP_Error('invalid_payment', __('Payment not found.', 'livenettv-pro'));
        }

        if ($payment->status !== 'pending') {
            return new WP_Error('payment_processed', __('Payment has already been processed.', 'livenettv-pro'));
        }

        $start_date = current_time('Y-m-d');
        $expiry_date = date('Y-m-d', strtotime("+{$payment->plan_duration} days", strtotime($start_date)));
        if ($payment->plan_slug === 'lifetime') {
            $expiry_date = date('Y-m-d', strtotime('+100 years'));
        }

        $update_data = array(
            'status' => 'approved',
            'processed_at' => current_time('mysql'),
            'processed_by' => $admin_user_id,
            'membership_start' => $start_date,
            'membership_end' => $expiry_date,
        );

        $db->update_payment($payment_id, $update_data);

        $plan = $membership->get_plan($payment->plan_slug);
        $result = $membership->activate_membership($payment->user_id, $payment->plan_slug, $payment_id);

        $updated_payment = $db->get_payment($payment_id);
        $emails->send_approval_notification($payment->user_id, $updated_payment);

        return array(
            'success' => true,
            'message' => __('Payment approved and membership activated.', 'livenettv-pro'),
            'user_id' => $payment->user_id,
            'expiry_date' => $expiry_date,
        );
    }

    public static function process_rejection($payment_id, $admin_user_id, $reason = '') {
        $db = livenettv_pro()->get_db();
        $emails = livenettv_pro()->get_emails();

        $payment = $db->get_payment($payment_id);

        if (!$payment) {
            return new WP_Error('invalid_payment', __('Payment not found.', 'livenettv-pro'));
        }

        if ($payment->status !== 'pending') {
            return new WP_Error('payment_processed', __('Payment has already been processed.', 'livenettv-pro'));
        }

        $update_data = array(
            'status' => 'rejected',
            'processed_at' => current_time('mysql'),
            'processed_by' => $admin_user_id,
            'notes' => $reason,
        );

        $db->update_payment($payment_id, $update_data);

        $updated_payment = $db->get_payment($payment_id);
        $emails->send_rejection_notification($payment->user_id, $updated_payment);

        return array(
            'success' => true,
            'message' => __('Payment rejected and user notified.', 'livenettv-pro'),
        );
    }
}
