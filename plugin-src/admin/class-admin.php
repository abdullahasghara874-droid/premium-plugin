<?php
/**
 * Admin dashboard for LiveNetTV Pro
 */

defined('ABSPATH') || exit;

class LiveNetTV_Pro_Admin {

    private $db;

    public function __construct() {
        $this->db = livenettv_pro()->get_db();
    }

    public function init() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'settings_init'));
        add_action('admin_action_livenettv_approve_payment', array($this, 'handle_approve_payment'));
        add_action('admin_action_livenettv_reject_payment', array($this, 'handle_reject_payment'));
        add_action('wp_ajax_livenettv_admin_approve', array($this, 'ajax_approve_payment'));
        add_action('wp_ajax_livenettv_admin_reject', array($this, 'ajax_reject_payment'));
        add_action('admin_enqueue_scripts', array($this, 'queue_admin_assets'));
    }

    public function add_admin_menu() {
        add_menu_page(
            __('LiveNetTV Pro', 'livenettv-pro'),
            __('Pro Membership', 'livenettv-pro'),
            'manage_options',
            'livenettv-pro',
            array($this, 'render_dashboard_page'),
            'dashicons-star-filled',
            30
        );

        add_submenu_page(
            'livenettv-pro',
            __('Dashboard', 'livenettv-pro'),
            __('Dashboard', 'livenettv-pro'),
            'manage_options',
            'livenettv-pro',
            array($this, 'render_dashboard_page')
        );

        add_submenu_page(
            'livenettv-pro',
            __('Payments', 'livenettv-pro'),
            __('Payments', 'livenettv-pro'),
            'manage_options',
            'livenettv-pro-payments',
            array($this, 'render_payments_page')
        );

        add_submenu_page(
            'livenettv-pro',
            __('Pro Members', 'livenettv-pro'),
            __('Pro Members', 'livenettv-pro'),
            'manage_options',
            'livenettv-pro-members',
            array($this, 'render_members_page')
        );

        add_submenu_page(
            'livenettv-pro',
            __('Google Settings', 'livenettv-pro'),
            __('Google Settings', 'livenettv-pro'),
            'manage_options',
            'livenettv-pro-google',
            array($this, 'render_google_settings_page')
        );

        add_submenu_page(
            'livenettv-pro',
            __('Payment Wallets', 'livenettv-pro'),
            __('Payment Wallets', 'livenettv-pro'),
            'manage_options',
            'livenettv-pro-wallets',
            array($this, 'render_wallets_page')
        );

        add_submenu_page(
            'livenettv-pro',
            __('Plans & Pricing', 'livenettv-pro'),
            __('Plans & Pricing', 'livenettv-pro'),
            'manage_options',
            'livenettv-pro-plans',
            array($this, 'render_plans_page')
        );

        add_submenu_page(
            'livenettv-pro',
            __('Ad Settings', 'livenettv-pro'),
            __('Ad Settings', 'livenettv-pro'),
            'manage_options',
            'livenettv-pro-ads',
            array($this, 'render_ads_page')
        );

        add_submenu_page(
            'livenettv-pro',
            __('Email Settings', 'livenettv-pro'),
            __('Email Settings', 'livenettv-pro'),
            'manage_options',
            'livenettv-pro-emails',
            array($this, 'render_emails_page')
        );
    }

    public function settings_init() {
        register_setting('livenettv_pro_google', 'livenettv_pro_google_client_id');
        register_setting('livenettv_pro_google', 'livenettv_pro_google_client_secret');
        register_setting('livenettv_pro_google', 'livenettv_pro_redirect_after_login');

        register_setting('livenettv_pro_wallets', 'livenettv_pro_wallet_usdt');
        register_setting('livenettv_pro_wallets', 'livenettv_pro_wallet_btc');
        register_setting('livenettv_pro_wallets', 'livenettv_pro_wallet_eth');
        register_setting('livenettv_pro_wallets', 'livenettv_pro_wallet_bnb');

        register_setting('livenettv_pro_plans', 'livenettv_pro_plans');
        register_setting('livenettv_pro_plans', 'livenettv_pro_pro_page_id');

        register_setting('livenettv_pro_ads', 'livenettv_pro_ad_selectors');
        register_setting('livenettv_pro_ads', 'livenettv_pro_ad_shortcodes');

        register_setting('livenettv_pro_emails', 'livenettv_pro_email_template_mode');
        register_setting('livenettv_pro_emails', 'livenettv_pro_notification_email');
        register_setting('livenettv_pro_emails', 'livenettv_pro_email_from_name');
        register_setting('livenettv_pro_emails', 'livenettv_pro_expiry_warning_days');
        register_setting('livenettv_pro_emails', 'livenettv_pro_support_email');
        register_setting('livenettv_pro_emails', 'livenettv_pro_admin_notification_email');
    }

    public function queue_admin_assets($hook) {
        if (strpos($hook, 'livenettv-pro') === false) {
            return;
        }
    }

    public function render_dashboard_page() {
        $pending_count = $this->db->get_payments_count('pending');
        $approved_count = $this->db->get_payments_count('approved');
        $rejected_count = $this->db->get_payments_count('rejected');

        $total_revenue = $this->get_total_revenue();
        $active_pro_users = $this->get_active_pro_users_count();
        $last_7_days_revenue = $this->get_revenue_last_days(7);
        $last_30_days_revenue = $this->get_revenue_last_days(30);

        include LIVENETTV_PRO_PLUGIN_DIR . 'admin/views/dashboard.php';
    }

    public function render_payments_page() {
        $action = isset($_GET['action']) ? sanitize_key($_GET['action']) : 'list';

        if ($action === 'view' && isset($_GET['id'])) {
            $payment_id = (int) $_GET['id'];
            $payment = $this->db->get_payment($payment_id);

            if (!$payment) {
                wp_die(__('Payment not found.', 'livenettv-pro'));
            }

            $user = get_user_by('ID', $payment->user_id);
            include LIVENETTV_PRO_PLUGIN_DIR . 'admin/views/payment-detail.php';
        } else {
            $status = isset($_GET['status']) ? sanitize_key($_GET['status']) : '';
            $paged = isset($_GET['paged']) ? max(1, (int) $_GET['paged']) : 1;
            $per_page = 20;

            $args = array(
                'status' => $status,
                'limit' => $per_page,
                'offset' => ($paged - 1) * $per_page,
            );

            $payments = $this->db->get_payments($args);
            $total = $this->db->get_payments_count($status);
            $total_pages = ceil($total / $per_page);

            include LIVENETTV_PRO_PLUGIN_DIR . 'admin/views/payments-list.php';
        }
    }

    public function render_members_page() {
        $args = array(
            'meta_key' => 'livenettv_pro_status',
            'meta_value' => 'active',
            'number' => 20,
            'orderby' => 'meta_value',
            'order' => 'ASC',
        );

        $members = get_users($args);
        include LIVENETTV_PRO_PLUGIN_DIR . 'admin/views/members-list.php';
    }

    public function render_google_settings_page() {
        include LIVENETTV_PRO_PLUGIN_DIR . 'admin/views/settings-google.php';
    }

    public function render_wallets_page() {
        include LIVENETTV_PRO_PLUGIN_DIR . 'admin/views/settings-wallets.php';
    }

    public function render_plans_page() {
        include LIVENETTV_PRO_PLUGIN_DIR . 'admin/views/settings-plans.php';
    }

    public function render_ads_page() {
        include LIVENETTV_PRO_PLUGIN_DIR . 'admin/views/settings-ads.php';
    }

    public function render_emails_page() {
        include LIVENETTV_PRO_PLUGIN_DIR . 'admin/views/settings-emails.php';
    }

    public function handle_approve_payment() {
        check_admin_referer('livenettv_approve_payment_' . $_GET['payment_id']);

        if (!current_user_can('manage_options')) {
            wp_die(__('Insufficient permissions.', 'livenettv-pro'));
        }

        $payment_id = (int) $_GET['payment_id'];
        $admin_user_id = get_current_user_id();

        $result = LiveNetTV_Pro_Payment_Processor::process_approval($payment_id, $admin_user_id);

        $redirect_to = admin_url('admin.php?page=livenettv-pro-payments');

        if (is_wp_error($result)) {
            $redirect_to = add_query_arg('error', urlencode($result->get_error_message()), $redirect_to);
        } else {
            $redirect_to = add_query_arg('approved', '1', $redirect_to);
        }

        wp_safe_redirect($redirect_to);
        exit;
    }

    public function handle_reject_payment() {
        check_admin_referer('livenettv_reject_payment_' . $_GET['payment_id']);

        if (!current_user_can('manage_options')) {
            wp_die(__('Insufficient permissions.', 'livenettv-pro'));
        }

        $payment_id = (int) $_GET['payment_id'];
        $admin_user_id = get_current_user_id();
        $reason = isset($_GET['reason']) ? sanitize_textarea_field($_GET['reason']) : '';

        $result = LiveNetTV_Pro_Payment_Processor::process_rejection($payment_id, $admin_user_id, $reason);

        $redirect_to = admin_url('admin.php?page=livenettv-pro-payments');

        if (is_wp_error($result)) {
            $redirect_to = add_query_arg('error', urlencode($result->get_error_message()), $redirect_to);
        } else {
            $redirect_to = add_query_arg('rejected', '1', $redirect_to);
        }

        wp_safe_redirect($redirect_to);
        exit;
    }

    public function ajax_approve_payment() {
        check_ajax_referer('livenettv_pro_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Insufficient permissions.', 'livenettv-pro')));
        }

        $payment_id = (int) $_POST['payment_id'];
        $admin_user_id = get_current_user_id();

        $result = LiveNetTV_Pro_Payment_Processor::process_approval($payment_id, $admin_user_id);

        if (is_wp_error($result)) {
            wp_send_json_error(array('message' => $result->get_error_message()));
        }

        wp_send_json_success($result);
    }

    public function ajax_reject_payment() {
        check_ajax_referer('livenettv_pro_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Insufficient permissions.', 'livenettv-pro')));
        }

        $payment_id = (int) $_POST['payment_id'];
        $admin_user_id = get_current_user_id();
        $reason = sanitize_textarea_field($_POST['reason'] ?? '');

        $result = LiveNetTV_Pro_Payment_Processor::process_rejection($payment_id, $admin_user_id, $reason);

        if (is_wp_error($result)) {
            wp_send_json_error(array('message' => $result->get_error_message()));
        }

        wp_send_json_success($result);
    }

    private function get_total_revenue() {
        global $wpdb;
        $table = $this->db->get_table_payments();

        return (float) $wpdb->get_var(
            "SELECT SUM(plan_price) FROM {$table} WHERE status = 'approved'"
        );
    }

    private function get_revenue_last_days($days) {
        global $wpdb;
        $table = $this->db->get_table_payments();

        $start_date = date('Y-m-d H:i:s', strtotime("-{$days} days"));

        return (float) $wpdb->get_var(
            $wpdb->prepare(
                "SELECT SUM(plan_price) FROM {$table} WHERE status = 'approved' AND processed_at >= %s",
                $start_date
            )
        );
    }

    private function get_active_pro_users_count() {
        $users = get_users(array(
            'meta_key' => 'livenettv_pro_status',
            'meta_value' => 'active',
            'fields' => 'ID',
            'number' => -1,
        ));

        return count($users);
    }
}
