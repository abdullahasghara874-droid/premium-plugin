<?php
defined( 'ABSPATH' ) || exit;

class LiveNetTV_Pro_Admin {

    private $plugin_name = 'livenettv-pro';
    private $version;

    public function __construct() {
        $this->version = LIVENETTV_PRO_VERSION;
        add_action( 'admin_menu', array( $this, 'add_menu' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
        add_action( 'wp_ajax_livenettv_pro_approve_payment', array( $this, 'ajax_approve_payment' ) );
        add_action( 'wp_ajax_livenettv_pro_reject_payment', array( $this, 'ajax_reject_payment' ) );
    }

    public function add_menu() {
        add_menu_page(
            __( 'LiveNetTV Pro', 'livenettv-pro' ),
            __( 'LiveNetTV Pro', 'livenettv-pro' ),
            'manage_options',
            'livenettv-pro',
            array( $this, 'render_dashboard' ),
            'dashicons-star-filled',
            30
        );

        add_submenu_page(
            'livenettv-pro',
            __( 'Dashboard', 'livenettv-pro' ),
            __( 'Dashboard', 'livenettv-pro' ),
            'manage_options',
            'livenettv-pro',
            array( $this, 'render_dashboard' )
        );

        add_submenu_page(
            'livenettv-pro',
            __( 'Payments', 'livenettv-pro' ),
            __( 'Payments', 'livenettv-pro' ),
            'manage_options',
            'livenettv-pro-payments',
            array( $this, 'render_payments_list' )
        );

        add_submenu_page(
            'livenettv-pro',
            __( 'Members', 'livenettv-pro' ),
            __( 'Members', 'livenettv-pro' ),
            'manage_options',
            'livenettv-pro-members',
            array( $this, 'render_members_list' )
        );

        add_submenu_page(
            'livenettv-pro',
            __( 'Settings', 'livenettv-pro' ),
            __( 'Settings', 'livenettv-pro' ),
            'manage_options',
            'livenettv-pro-settings',
            array( $this, 'render_settings' )
        );
    }

    public function register_settings() {
        // Google OAuth Settings.
        register_setting( 'livenettv_pro_google', 'livenettv_pro_google_client_id' );
        register_setting( 'livenettv_pro_google', 'livenettv_pro_google_client_secret' );

        // Wallet Settings.
        register_setting( 'livenettv_pro_wallets', 'livenettv_pro_wallet_usdt' );
        register_setting( 'livenettv_pro_wallets', 'livenettv_pro_wallet_btc' );
        register_setting( 'livenettv_pro_wallets', 'livenettv_pro_wallet_eth' );
        register_setting( 'livenettv_pro_wallets', 'livenettv_pro_wallet_bnb' );

        // Plan Settings.
        register_setting( 'livenettv_pro_plans', 'livenettv_pro_custom_plans' );

        // Ad Removal Settings.
        register_setting( 'livenettv_pro_ads', 'livenettv_pro_remove_ads' );
        register_setting( 'livenettv_pro_ads', 'livenettv_pro_ad_selectors' );

        // Email Settings.
        register_setting( 'livenettv_pro_emails', 'livenettv_pro_email_from_name' );
        register_setting( 'livenettv_pro_emails', 'livenettv_pro_email_from_address' );
        register_setting( 'livenettv_pro_emails', 'livenettv_pro_admin_notification_email' );

        // General Settings.
        register_setting( 'livenettv_pro_general', 'livenettv_pro_pro_page_id' );
        register_setting( 'livenettv_pro_general', 'livenettv_pro_redirect_after_login' );
    }

    public function enqueue_assets( $hook ) {
        if ( strpos( $hook, 'livenettv-pro' ) === false ) {
            return;
        }

        wp_enqueue_style(
            'livenettv-pro-admin-css',
            LIVENETTV_PRO_URL . 'assets/css/admin.css',
            array(),
            $this->version
        );

        wp_enqueue_script(
            'livenettv-pro-admin-js',
            LIVENETTV_PRO_URL . 'assets/js/admin.js',
            array( 'jquery' ),
            $this->version,
            true
        );

        wp_localize_script( 'livenettv-pro-admin-js', 'livenettvProAdmin', array(
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'livenettv_pro_admin_nonce' ),
            'strings' => array(
                'confirmApprove' => __( 'Are you sure you want to approve this payment?', 'livenettv-pro' ),
                'confirmReject'  => __( 'Are you sure you want to reject this payment?', 'livenettv-pro' ),
                'processing'      => __( 'Processing...', 'livenettv-pro' ),
            ),
        ) );
    }

    public function render_dashboard() {
        $db = livenettv_pro()->get_db();

        $stats = array(
            'pending_payments'  => (int) $db->get_payments_count( 'pending' ),
            'approved_payments' => (int) $db->get_payments_count( 'approved' ),
            'rejected_payments' => (int) $db->get_payments_count( 'rejected' ),
            'total_payments'    => (int) $db->get_payments_count(),
        );

        $active_members = count( get_users( array(
            'meta_key'   => 'livenettv_pro_status',
            'meta_value' => 'active',
            'number'     => 0,
            'fields'     => 'ID',
        ) ) );

        $recent_payments = $db->get_payments( array( 'limit' => 10 ) );

        include LIVENETTV_PRO_PATH . 'admin/views/dashboard.php';
    }

    public function render_payments_list() {
        $db = livenettv_pro()->get_db();

        $status    = isset( $_GET['status'] ) ? sanitize_key( $_GET['status'] ) : '';
        $page      = isset( $_GET['paged'] ) ? absint( $_GET['paged'] ) : 1;
        $per_page  = 20;
        $offset    = ( $page - 1 ) * $per_page;

        $args = array(
            'limit'  => $per_page,
            'offset' => $offset,
        );

        if ( ! empty( $status ) ) {
            $args['status'] = $status;
        }

        $payments = $db->get_payments( $args );
        $total    = $db->get_payments_count( $status );
        $pages    = ceil( $total / $per_page );

        // Handle single payment view.
        if ( isset( $_GET['view'] ) ) {
            $payment_id = absint( $_GET['view'] );
            $payment    = $db->get_payment( $payment_id );
            $user       = get_user_by( 'id', $payment->user_id );
            include LIVENETTV_PRO_PATH . 'admin/views/payment-detail.php';
            return;
        }

        include LIVENETTV_PRO_PATH . 'admin/views/payments-list.php';
    }

    public function render_members_list() {
        $page     = isset( $_GET['paged'] ) ? absint( $_GET['paged'] ) : 1;
        $per_page = 20;
        $offset   = ( $page - 1 ) * $per_page;
        $status   = isset( $_GET['mstatus'] ) ? sanitize_key( $_GET['mstatus'] ) : '';

        $args = array(
            'number' => $per_page,
            'offset' => $offset,
            'fields' => array( 'ID', 'user_login', 'user_email', 'display_name' ),
        );

        if ( ! empty( $status ) ) {
            $args['meta_query'] = array(
                array(
                    'key'   => 'livenettv_pro_status',
                    'value' => $status,
                ),
            );
        } else {
            $args['meta_query'] = array(
                array(
                    'key'     => 'livenettv_pro_status',
                    'compare' => 'EXISTS',
                ),
            );
        }

        $user_query = new WP_User_Query( $args );
        $users      = $user_query->get_results();
        $total      = $user_query->get_total();
        $pages      = ceil( $total / $per_page );

        $membership  = livenettv_pro()->get_membership();
        $db          = livenettv_pro()->get_db();

        include LIVENETTV_PRO_PATH . 'admin/views/members-list.php';
    }

    public function render_settings() {
        // Determine active tab.
        $tabs = array(
            'general'  => __( 'General', 'livenettv-pro' ),
            'google'  => __( 'Google OAuth', 'livenettv-pro' ),
            'wallets' => __( 'Crypto Wallets', 'livenettv-pro' ),
            'plans'   => __( 'Plans', 'livenettv-pro' ),
            'ads'     => __( 'Ad Removal', 'livenettv-pro' ),
            'emails'  => __( 'Emails', 'livenettv-pro' ),
        );

        $active_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'general';

        // Handle plan form submission.
        if ( 'plans' === $active_tab && isset( $_POST['save_plans'] ) ) {
            check_admin_referer( 'livenettv_pro_save_plans' );
            $this->save_plans();
        }

        // Show settings messages.
        if ( isset( $_GET['settings-updated'] ) ) {
            add_settings_error( $this->plugin_name, 'settings_updated', __( 'Settings saved.', 'livenettv-pro' ), 'updated' );
        }

        include LIVENETTV_PRO_PATH . 'admin/views/settings.php';
    }

    private function save_plans() {
        $plans = array();
        $plan_data = $_POST['plans'] ?? array();

        foreach ( $plan_data as $slug => $data ) {
            $slug = sanitize_key( $slug );
            $plans[ $slug ] = array(
                'name'          => sanitize_text_field( $data['name'] ?? '' ),
                'duration'      => absint( $data['duration'] ?? 0 ),
                'duration_text' => sanitize_text_field( $data['duration_text'] ?? '' ),
                'price'         => floatval( $data['price'] ?? 0 ),
                'currency'      => sanitize_text_field( $data['currency'] ?? 'USD' ),
                'recommended'   => ! empty( $data['recommended'] ),
                'badge'         => sanitize_text_field( $data['badge'] ?? '' ),
                'save_percent'  => absint( $data['save_percent'] ?? 0 ),
            );
        }

        update_option( 'livenettv_pro_custom_plans', $plans );
    }

    public function ajax_approve_payment() {
        check_ajax_referer( 'livenettv_pro_admin_nonce', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => __( 'Permission denied.', 'livenettv-pro' ) ) );
        }

        $payment_id = absint( $_POST['payment_id'] ?? 0 );
        $admin_id   = get_current_user_id();

        $result = LiveNetTV_Pro_Payment_Processor::process_approval( $payment_id, $admin_id );

        if ( is_wp_error( $result ) ) {
            wp_send_json_error( array( 'message' => $result->get_error_message() ) );
        }

        wp_send_json_success( $result );
    }

    public function ajax_reject_payment() {
        check_ajax_referer( 'livenettv_pro_admin_nonce', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => __( 'Permission denied.', 'livenettv-pro' ) ) );
        }

        $payment_id = absint( $_POST['payment_id'] ?? 0 );
        $reason     = sanitize_textarea_field( $_POST['reason'] ?? '' );
        $admin_id   = get_current_user_id();

        $result = LiveNetTV_Pro_Payment_Processor::process_rejection( $payment_id, $admin_id, $reason );

        if ( is_wp_error( $result ) ) {
            wp_send_json_error( array( 'message' => $result->get_error_message() ) );
        }

        wp_send_json_success( $result );
    }
}
