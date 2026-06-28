<?php
defined( 'ABSPATH' ) || exit;

class LiveNetTV_Pro_Payment_Processor {

    public function __construct() {
        add_action( 'init', array( $this, 'init' ) );
    }

    public function init() {
        // admin-post.php handler for authenticated users.
        add_action( 'admin_post_livenettv_submit_payment', array( $this, 'handle_payment_submission' ) );
    }

    public function handle_payment_submission() {
        if ( ! is_user_logged_in() ) {
            wp_safe_redirect( wp_login_url( wp_get_referer() ) );
            exit;
        }

        check_admin_referer( 'livenettv_pro_payment', 'livenettv_pro_nonce' );

        $user_id = get_current_user_id();
        $db      = livenettv_pro()->get_db();

        if ( $db->get_user_pending_payment( $user_id ) ) {
            $this->redirect_with_error( __( 'You already have a pending payment under review.', 'livenettv-pro' ) );
        }

        $plan_slug   = sanitize_key( $_POST['livenettv_plan']   ?? '' );
        $crypto_type = sanitize_key( $_POST['livenettv_crypto'] ?? '' );
        $txid        = sanitize_text_field( $_POST['livenettv_txid'] ?? '' );

        $membership = livenettv_pro()->get_membership();
        $plan       = $membership->get_plan( $plan_slug );

        if ( ! $plan ) {
            $this->redirect_with_error( __( 'Invalid plan selected.', 'livenettv-pro' ) );
        }

        $wallets = $this->get_wallets();

        if ( empty( $wallets[ $crypto_type ] ) ) {
            $this->redirect_with_error( __( 'Invalid payment method selected.', 'livenettv-pro' ) );
        }

        if ( empty( $txid ) ) {
            $this->redirect_with_error( __( 'Transaction ID is required.', 'livenettv-pro' ) );
        }

        $screenshot_url = '';

        if ( ! isset( $_FILES['livenettv_screenshot'] ) || UPLOAD_ERR_OK !== $_FILES['livenettv_screenshot']['error'] ) {
            $this->redirect_with_error( __( 'Please upload a payment screenshot.', 'livenettv-pro' ) );
        }

        $screenshot_url = $this->upload_screenshot( $_FILES['livenettv_screenshot'] );

        if ( is_wp_error( $screenshot_url ) ) {
            $this->redirect_with_error( $screenshot_url->get_error_message() );
        }

        $payment_id = $db->insert_payment( array(
            'user_id'        => $user_id,
            'plan_slug'      => $plan_slug,
            'plan_name'      => $plan['name'],
            'plan_duration'  => $plan['duration'],
            'plan_price'     => $plan['price'],
            'currency'       => $plan['currency'],
            'crypto_type'    => $crypto_type,
            'wallet_address' => $wallets[ $crypto_type ],
            'transaction_id' => $txid,
            'screenshot_path'=> $screenshot_url,
        ) );

        if ( is_wp_error( $payment_id ) ) {
            $this->redirect_with_error( $payment_id->get_error_message() );
        }

        livenettv_pro()->get_emails()->send_new_payment_admin_notification( $payment_id, $user_id );

        $pro_page_id = (int) get_option( 'livenettv_pro_pro_page_id', 0 );
        $redirect    = $pro_page_id ? get_permalink( $pro_page_id ) : home_url( '/' );
        $redirect    = add_query_arg( 'payment_submitted', '1', $redirect );

        wp_safe_redirect( esc_url_raw( $redirect ) );
        exit;
    }

    private function redirect_with_error( $message ) {
        $referer     = wp_get_referer();
        $redirect    = $referer ?: home_url( '/' );
        $redirect    = add_query_arg( 'payment_error', urlencode( $message ), $redirect );
        wp_safe_redirect( esc_url_raw( $redirect ) );
        exit;
    }

    private function upload_screenshot( $file ) {
        $allowed = array( 'image/jpeg', 'image/png', 'image/gif', 'image/webp' );

        if ( ! in_array( $file['type'], $allowed, true ) ) {
            return new WP_Error( 'invalid_type', __( 'Invalid file type. Upload JPG, PNG, GIF or WebP.', 'livenettv-pro' ) );
        }

        if ( $file['size'] > 5 * 1024 * 1024 ) {
            return new WP_Error( 'file_too_large', __( 'File is too large. Maximum size is 5MB.', 'livenettv-pro' ) );
        }

        if ( ! function_exists( 'wp_handle_upload' ) ) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }

        add_filter( 'upload_dir', array( $this, 'get_upload_dir' ) );
        $upload = wp_handle_upload( $file, array( 'test_form' => false ) );
        remove_filter( 'upload_dir', array( $this, 'get_upload_dir' ) );

        if ( isset( $upload['error'] ) ) {
            return new WP_Error( 'upload_error', $upload['error'] );
        }

        return $upload['url'];
    }

    public function get_upload_dir( $dirs ) {
        $dirs['subdir'] = '/livenettv-pro-payments/' . gmdate( 'Y/m' );
        $dirs['path']   = $dirs['basedir'] . $dirs['subdir'];
        $dirs['url']    = $dirs['baseurl'] . $dirs['subdir'];

        if ( ! file_exists( $dirs['path'] ) ) {
            wp_mkdir_p( $dirs['path'] );
            // Prevent direct directory browsing.
            file_put_contents( $dirs['path'] . '/index.html', '' );
        }

        return $dirs;
    }

    private function get_wallets() {
        return array(
            'usdt' => get_option( 'livenettv_pro_wallet_usdt', '' ),
            'btc'  => get_option( 'livenettv_pro_wallet_btc',  '' ),
            'eth'  => get_option( 'livenettv_pro_wallet_eth',  '' ),
            'bnb'  => get_option( 'livenettv_pro_wallet_bnb',  '' ),
        );
    }

    public static function process_approval( $payment_id, $admin_user_id ) {
        $db         = livenettv_pro()->get_db();
        $emails     = livenettv_pro()->get_emails();
        $membership = livenettv_pro()->get_membership();

        $payment = $db->get_payment( $payment_id );

        if ( ! $payment ) {
            return new WP_Error( 'not_found', __( 'Payment not found.', 'livenettv-pro' ) );
        }

        if ( 'pending' !== $payment->status ) {
            return new WP_Error( 'already_processed', __( 'Payment has already been processed.', 'livenettv-pro' ) );
        }

        $start  = current_time( 'Y-m-d' );
        $expiry = ( 'lifetime' === $payment->plan_slug )
            ? date( 'Y-m-d', strtotime( '+100 years' ) )
            : date( 'Y-m-d', strtotime( "+{$payment->plan_duration} days", strtotime( $start ) ) );

        $db->update_payment( $payment_id, array(
            'status'           => 'approved',
            'processed_at'     => current_time( 'mysql' ),
            'processed_by'     => absint( $admin_user_id ),
            'membership_start' => $start,
            'membership_end'   => $expiry,
        ) );

        $membership->activate_membership( $payment->user_id, $payment->plan_slug, $payment_id );

        $emails->send_approval_notification( $payment->user_id, $db->get_payment( $payment_id ) );

        return array(
            'success'     => true,
            'message'     => __( 'Payment approved and membership activated.', 'livenettv-pro' ),
            'user_id'     => $payment->user_id,
            'expiry_date' => $expiry,
        );
    }

    public static function process_rejection( $payment_id, $admin_user_id, $reason = '' ) {
        $db     = livenettv_pro()->get_db();
        $emails = livenettv_pro()->get_emails();

        $payment = $db->get_payment( $payment_id );

        if ( ! $payment ) {
            return new WP_Error( 'not_found', __( 'Payment not found.', 'livenettv-pro' ) );
        }

        if ( 'pending' !== $payment->status ) {
            return new WP_Error( 'already_processed', __( 'Payment has already been processed.', 'livenettv-pro' ) );
        }

        $db->update_payment( $payment_id, array(
            'status'       => 'rejected',
            'processed_at' => current_time( 'mysql' ),
            'processed_by' => absint( $admin_user_id ),
            'notes'        => sanitize_textarea_field( $reason ),
        ) );

        $emails->send_rejection_notification( $payment->user_id, $db->get_payment( $payment_id ) );

        return array(
            'success' => true,
            'message' => __( 'Payment rejected and user notified.', 'livenettv-pro' ),
        );
    }
}
