<?php
/**
 * Frontend functionality for LiveNetTV Pro
 */

defined('ABSPATH') || exit;

class LiveNetTV_Pro_Frontend {

    private $db;

    public function __construct() {
        add_action('init', array($this, 'init'));
    }

    public function init() {
        $this->db = livenettv_pro()->get_db();

        add_shortcode('livenettv_pro_form', array($this, 'render_pro_form'));
        add_shortcode('livenettv_pro_status', array($this, 'render_pro_status'));
        add_shortcode('livenettv_pro_cta', array($this, 'render_pro_cta'));
        add_shortcode('livenettv_pro_button', array($this, 'render_pro_button'));

        add_action('wp_ajax_livenettv_upload_payment', array($this, 'ajax_upload_payment'));
        add_action('wp_ajax_livenettv_get_crypto_data', array($this, 'ajax_get_crypto_data'));
        add_action('wp_ajax_livenettv_check_payment_status', array($this, 'ajax_check_payment_status'));
    }

    public function render_pro_form($atts = array()) {
        if (!is_user_logged_in()) {
            return $this->render_login_prompt();
        }

        $user_id = get_current_user_id();
        $membership = livenettv_pro()->get_membership();

        if ($membership->is_pro_user($user_id)) {
            return $this->render_active_status($user_id);
        }

        $pending_payment = $this->db->get_user_pending_payment($user_id);
        if ($pending_payment) {
            return $this->render_pending_payment($pending_payment);
        }

        return $this->render_payment_form();
    }

    private function render_login_prompt() {
        ob_start();
        ?>
        <div class="livenettv-pro-login-prompt">
            <h2><?php _e('Sign in to get Pro', 'livenettv-pro'); ?></h2>
            <p><?php _e('You need to sign in with Google to purchase a Pro membership.', 'livenettv-pro'); ?></p>
            <?php echo do_shortcode('[livenettv_login_button]'); ?>
        </div>
        <?php
        return ob_get_clean();
    }

    private function render_active_status($user_id) {
        $membership = livenettv_pro()->get_membership();
        $user_membership = $membership->get_user_membership_data($user_id);
        $days_remaining = $membership->get_days_remaining($user_id);

        ob_start();
        ?>
        <div class="livenettv-pro-status active">
            <div class="livenettv-pro-status-header">
                <span class="dashicons dashicons-yes-alt"></span>
                <h2><?php _e('Pro Membership Active', 'livenettv-pro'); ?></h2>
            </div>
            <div class="livenettv-pro-status-content">
                <?php if ($user_membership['plan'] === 'lifetime') : ?>
                    <p class="livenettv-pro-lifetime-badge">
                        <?php _e('Lifetime Member', 'livenettv-pro'); ?>
                        <span class="dashicons dashicons-sticky"></span>
                    </p>
                <?php else : ?>
                    <p>
                        <strong><?php _e('Plan:', 'livenettv-pro'); ?></strong>
                        <?php echo esc_html($membership->get_plan($user_membership['plan'])['name'] ?? __('Pro', 'livenettv-pro')); ?>
                    </p>
                    <p>
                        <strong><?php _e('Started:', 'livenettv-pro'); ?></strong>
                        <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($user_membership['start_date']))); ?>
                    </p>
                    <p>
                        <strong><?php _e('Expires:', 'livenettv-pro'); ?></strong>
                        <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($user_membership['expiry_date']))); ?>
                    </p>
                    <p class="livenettv-pro-days-remaining">
                        <?php printf(_n('%d day remaining', '%d days remaining', $days_remaining, 'livenettv-pro'), $days_remaining); ?>
                    </p>
                <?php endif; ?>
                <div class="livenettv-pro-benefits">
                    <h3><?php _e('Your Benefits', 'livenettv-pro'); ?></h3>
                    <ul>
                        <li><?php _e('No popup ads', 'livenettv-pro'); ?></li>
                        <li><?php _e('No banner ads', 'livenettv-pro'); ?></li>
                        <li><?php _e('No sticky ads', 'livenettv-pro'); ?></li>
                        <li><?php _e('No download page ads', 'livenettv-pro'); ?></li>
                        <li><?php _e('Faster, cleaner browsing', 'livenettv-pro'); ?></li>
                    </ul>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    private function render_pending_payment($payment) {
        ob_start();
        ?>
        <div class="livenettv-pro-status pending">
            <div class="livenettv-pro-status-header">
                <span class="dashicons dashicons-clock"></span>
                <h2><?php _e('Payment Under Review', 'livenettv-pro'); ?></h2>
            </div>
            <div class="livenettv-pro-status-content">
                <p><?php _e('Your payment is being reviewed by our team. This usually takes 24-48 hours.', 'livenettv-pro'); ?></p>
                <div class="livenettv-pro-payment-details">
                    <p><strong><?php _e('Plan:', 'livenettv-pro'); ?></strong> <?php echo esc_html($payment->plan_name); ?></p>
                    <p><strong><?php _e('Submitted:', 'livenettv-pro'); ?></strong> <?php echo esc_html(date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($payment->submitted_at))); ?></p>
                    <p><strong><?php _e('Transaction ID:', 'livenettv-pro'); ?></strong> <?php echo esc_html($payment->transaction_id); ?></p>
                </div>
                <p class="livenettv-pro-notification">
                    <?php _e('You will receive an email notification once your payment is processed.', 'livenettv-pro'); ?>
                </p>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    private function render_payment_form() {
        $plans = livenettv_pro()->get_membership()->get_plans();
        $crypto_wallets = get_option('livenettv_pro_crypto_wallets', $this->get_default_crypto_wallets());

        wp_enqueue_media();

        ob_start();
        ?>
        <div class="livenettv-pro-form-container">
            <div class="livenettv-pro-header">
                <h2><?php _e('Get Pro Membership', 'livenettv-pro'); ?></h2>
                <p><?php _e('Remove all ads and enjoy a faster, cleaner browsing experience.', 'livenettv-pro'); ?></p>
            </div>

            <form id="livenettv-pro-payment-form" method="post" enctype="multipart/form-data">
                <?php wp_nonce_field('livenettv_pro_payment', 'livenettv_pro_nonce'); ?>

                <div class="livenettv-pro-section">
                    <h3><?php _e('1. Select Your Plan', 'livenettv-pro'); ?></h3>
                    <div class="livenettv-pro-plans-grid">
                        <?php foreach ($plans as $slug => $plan) : ?>
                            <label class="livenettv-pro-plan-option">
                                <input type="radio" name="livenettv_plan" value="<?php echo esc_attr($slug); ?>" required>
                                <div class="livenettv-pro-plan-card">
                                    <div class="livenettv-pro-plan-name"><?php echo esc_html($plan['name']); ?></div>
                                    <div class="livenettv-pro-plan-duration"><?php echo esc_html($plan['duration_text']); ?></div>
                                    <div class="livenettv-pro-plan-price">$<?php echo esc_html(number_format($plan['price'], 2)); ?></div>
                                </div>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="livenettv-pro-section">
                    <h3><?php _e('2. Choose Payment Method', 'livenettv-pro'); ?></h3>
                    <div class="livenettv-pro-crypto-options">
                        <?php foreach ($crypto_wallets as $crypto => $address) : ?>
                            <label class="livenettv-pro-crypto-option">
                                <input type="radio" name="livenettv_crypto" value="<?php echo esc_attr($crypto); ?>" required>
                                <div class="livenettv-pro-crypto-card">
                                    <span class="livenettv-pro-crypto-icon <?php echo esc_attr(strtolower($crypto)); ?>"></span>
                                    <span class="livenettv-pro-crypto-name"><?php echo esc_html($crypto); ?></span>
                                </div>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="livenettv-pro-section">
                    <h3><?php _e('3. Send Payment', 'livenettv-pro'); ?></h3>
                    <div class="livenettv-pro-payment-info" style="display:none;">
                        <div class="livenettv-pro-qrcode-container">
                            <div class="livenettv-pro-qrcode"></div>
                        </div>
                        <div class="livenettv-pro-wallet-info">
                            <div class="livenettv-pro-wallet-label">
                                <?php _e('Wallet Address:', 'livenettv-pro'); ?>
                            </div>
                            <div class="livenettv-pro-wallet-address-group">
                                <code class="livenettv-pro-wallet-address"></code>
                                <button type="button" class="livenettv-pro-copy-btn" data-copy="">
                                    <span class="dashicons dashicons-admin-page"></span>
                                    <?php _e('Copy', 'livenettv-pro'); ?>
                                </button>
                            </div>
                        </div>
                        <div class="livenettv-pro-amount-to-send">
                            <strong><?php _e('Amount to send:', 'livenettv-pro'); ?></strong>
                            <span class="livenettv-pro-amount-value"></span>
                        </div>
                    </div>
                </div>

                <div class="livenettv-pro-section">
                    <h3><?php _e('4. Upload Payment Proof', 'livenettv-pro'); ?></h3>
                    <div class="livenettv-pro-upload-container">
                        <div class="livenettv-pro-upload-area">
                            <input type="file" name="livenettv_screenshot" id="livenettv_screenshot" accept="image/*" class="livenettv-pro-upload-input" required>
                            <label for="livenettv_screenshot" class="livenettv-pro-upload-label">
                                <span class="dashicons dashicons-upload"></span>
                                <span class="livenettv-pro-upload-text"><?php _e('Click to upload screenshot', 'livenettv-pro'); ?></span>
                            </label>
                            <div class="livenettv-pro-upload-preview"></div>
                        </div>
                    </div>
                    <div class="livenettv-pro-txid-field">
                        <label for="livenettv_txid"><?php _e('Transaction ID (TXID):', 'livenettv-pro'); ?></label>
                        <input type="text" name="livenettv_txid" id="livenettv_txid" placeholder="<?php esc_attr_e('Enter your transaction ID', 'livenettv-pro'); ?>" required>
                    </div>
                </div>

                <div class="livenettv-pro-section livenettv-pro-submit-section">
                    <button type="submit" class="livenettv-pro-submit-btn">
                        <span class="dashicons dashicons-yes"></span>
                        <?php _e('Submit Payment', 'livenettv-pro'); ?>
                    </button>
                </div>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }

    private function get_default_crypto_wallets() {
        return array(
            'USDT' => get_option('livenettv_pro_wallet_usdt', ''),
            'BTC' => get_option('livenettv_pro_wallet_btc', ''),
            'ETH' => get_option('livenettv_pro_wallet_eth', ''),
            'BNB' => get_option('livenettv_pro_wallet_bnb', ''),
        );
    }

    public function ajax_get_crypto_data() {
        check_ajax_referer('livenettv_pro_nonce', 'nonce');

        $crypto = sanitize_text_field($_POST['crypto'] ?? '');
        $plan_slug = sanitize_text_field($_POST['plan'] ?? '');

        $plans = livenettv_pro()->get_membership()->get_plans();
        if (!isset($plans[$plan_slug])) {
            wp_send_json_error(array('message' => __('Invalid plan selected.', 'livenettv-pro')));
        }

        $crypto_wallets = get_option('livenettv_pro_crypto_wallets', array());
        if (!isset($crypto_wallets[$crypto]) || empty($crypto_wallets[$crypto])) {
            wp_send_json_error(array('message' => __('Wallet address not configured.', 'livenettv-pro')));
        }

        $wallet_address = $crypto_wallets[$crypto];
        $qr_url = $this->get_qr_url($crypto, $wallet_address);

        wp_send_json_success(array(
            'wallet_address' => $wallet_address,
            'qr_url' => $qr_url,
            'crypto' => $crypto,
        ));
    }

    private function get_qr_url($crypto, $address) {
        $uri = '';

        switch (strtoupper($crypto)) {
            case 'BTC':
                $uri = "bitcoin:{$address}";
                break;
            case 'ETH':
                $uri = "ethereum:{$address}";
                break;
            case 'USDT':
                $uri = "ethereum:{$address}?token=USDT";
                break;
            case 'BNB':
                $uri = "binance:{$address}";
                break;
            default:
                $uri = $address;
        }

        $qr_api = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($uri);
        return $qr_api;
    }

    public function ajax_check_payment_status() {
        check_ajax_referer('livenettv_pro_nonce', 'nonce');

        $user_id = get_current_user_id();
        $pending_payment = $this->db->get_user_pending_payment($user_id);

        if ($pending_payment) {
            wp_send_json_success(array(
                'status' => 'pending',
                'submitted_at' => $pending_payment->submitted_at,
            ));
        } else {
            $is_pro = livenettv_pro()->get_membership()->is_pro_user($user_id);
            wp_send_json_success(array(
                'status' => $is_pro ? 'approved' : 'none',
            ));
        }
    }

    public function render_pro_status($atts = array()) {
        if (!is_user_logged_in()) {
            return '';
        }

        $user_id = get_current_user_id();
        $membership = livenettv_pro()->get_membership();
        $status = $membership->get_user_membership_data($user_id);

        ob_start();
        ?>
        <div class="livenettv-pro-user-status">
            <?php if ($membership->is_pro_user($user_id)) : ?>
                <span class="livenettv-pro-badge active">
                    <span class="dashicons dashicons-yes-alt"></span>
                    <?php _e('Pro', 'livenettv-pro'); ?>
                    <?php if (!$membership->is_lifetime($user_id)) : ?>
                        <span class="livenettv-pro-days">(<?php echo $membership->get_days_remaining($user_id); ?>d)</span>
                    <?php endif; ?>
                </span>
            <?php else : ?>
                <span class="livenettv-pro-badge upgrade">
                    <span class="dashicons dashicons-arrow-up-alt"></span>
                    <?php _e('Free', 'livenettv-pro'); ?>
                </span>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    public function render_pro_cta($atts = array()) {
        $defaults = array(
            'text' => __('Go Pro - Remove Ads', 'livenettv-pro'),
        );

        $atts = shortcode_atts($defaults, $atts);

        $user_id = get_current_user_id();
        $membership = livenettv_pro()->get_membership();

        if ($membership->is_pro_user($user_id)) {
            return '';
        }

        $pro_page_id = get_option('livenettv_pro_pro_page_id');
        $pro_url = $pro_page_id ? get_permalink($pro_page_id) : home_url('/');

        ob_start();
        ?>
        <div class="livenettv-pro-cta">
            <a href="<?php echo esc_url($pro_url); ?>" class="livenettv-pro-cta-link">
                <span class="dashicons dashicons-star-filled"></span>
                <?php echo esc_html($atts['text']); ?>
            </a>
        </div>
        <?php
        return ob_get_clean();
    }

    public function render_pro_button($atts = array()) {
        return $this->render_pro_cta($atts);
    }
}
