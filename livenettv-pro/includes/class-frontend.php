<?php
defined( 'ABSPATH' ) || exit;

class LiveNetTV_Pro_Frontend {

    public function __construct() {
        add_action( 'init', array( $this, 'init' ) );

        // Register the page template.
        add_filter( 'theme_page_templates', array( $this, 'register_page_template' ) );
        add_filter( 'template_include',     array( $this, 'load_page_template' ) );
    }

    public function init() {
        add_shortcode( 'livenettv_premium_plans',   array( $this, 'shortcode_premium_plans' ) );
        add_shortcode( 'livenettv_payment_form',    array( $this, 'shortcode_payment_form' ) );
        add_shortcode( 'livenettv_pro_status',      array( $this, 'shortcode_pro_status' ) );
        add_shortcode( 'livenettv_pro_cta',         array( $this, 'shortcode_pro_cta' ) );
        add_shortcode( 'livenettv_pro_button',      array( $this, 'shortcode_pro_cta' ) );

        // Keep backward-compat shortcode.
        add_shortcode( 'livenettv_pro_form', array( $this, 'shortcode_payment_form' ) );

        add_action( 'wp_ajax_livenettv_get_crypto_data',        array( $this, 'ajax_get_crypto_data' ) );
        add_action( 'wp_ajax_livenettv_check_payment_status',   array( $this, 'ajax_check_payment_status' ) );
        add_action( 'wp_ajax_livenettv_wp_login',               array( $this, 'ajax_wp_login' ) );
        add_action( 'wp_ajax_nopriv_livenettv_wp_login',        array( $this, 'ajax_wp_login' ) );
        add_action( 'wp_ajax_livenettv_wp_register',            array( $this, 'ajax_wp_register' ) );
        add_action( 'wp_ajax_nopriv_livenettv_wp_register',     array( $this, 'ajax_wp_register' ) );
        add_action( 'wp_ajax_livenettv_get_google_url',         array( $this, 'ajax_get_google_url' ) );
        add_action( 'wp_ajax_nopriv_livenettv_get_google_url',  array( $this, 'ajax_get_google_url' ) );

        // Add the auth modal + pro badge to every page.
        add_action( 'wp_footer', array( $this, 'render_auth_modal' ) );
        add_action( 'body_class', array( $this, 'add_body_classes' ) );
    }

    // -------------------------------------------------------------------------
    // Page template registration
    // -------------------------------------------------------------------------

    public function register_page_template( $templates ) {
        $templates['templates/page-premium-plans.php'] = __( 'LiveNetTV Pro: Premium Plans', 'livenettv-pro' );
        return $templates;
    }

    public function load_page_template( $template ) {
        if ( ! is_page() ) {
            return $template;
        }

        $page_template = get_post_meta( get_the_ID(), '_wp_page_template', true );

        if ( 'templates/page-premium-plans.php' === $page_template ) {
            $plugin_template = LIVENETTV_PRO_PLUGIN_DIR . 'templates/page-premium-plans.php';
            if ( file_exists( $plugin_template ) ) {
                return $plugin_template;
            }
        }

        return $template;
    }

    // -------------------------------------------------------------------------
    // Body classes
    // -------------------------------------------------------------------------

    public function add_body_classes( $classes ) {
        $user_id = get_current_user_id();
        if ( $user_id && livenettv_pro()->get_membership()->is_pro_user( $user_id ) ) {
            $classes[] = 'lntv-pro-user';
        } else {
            $classes[] = 'lntv-free-user';
        }
        return $classes;
    }

    // -------------------------------------------------------------------------
    // Shortcodes
    // -------------------------------------------------------------------------

    public function shortcode_premium_plans( $atts = array() ) {
        ob_start();
        include LIVENETTV_PRO_PLUGIN_DIR . 'templates/page-premium-plans.php';
        return ob_get_clean();
    }

    public function shortcode_payment_form( $atts = array() ) {
        $user_id    = get_current_user_id();
        $membership = livenettv_pro()->get_membership();
        $db         = livenettv_pro()->get_db();

        if ( ! is_user_logged_in() ) {
            return $this->render_login_prompt();
        }

        if ( $membership->is_pro_user( $user_id ) ) {
            return $this->render_active_status( $user_id );
        }

        if ( $db->get_user_pending_payment( $user_id ) ) {
            return $this->render_pending_status( $db->get_user_pending_payment( $user_id ) );
        }

        return $this->render_payment_form();
    }

    public function shortcode_pro_status( $atts = array() ) {
        if ( ! is_user_logged_in() ) {
            return '';
        }

        $user_id    = get_current_user_id();
        $membership = livenettv_pro()->get_membership();
        $is_pro     = $membership->is_pro_user( $user_id );

        ob_start();
        ?>
        <span class="lntv-pro-badge <?php echo $is_pro ? 'lntv-pro-badge--active' : 'lntv-pro-badge--free'; ?>">
            <?php if ( $is_pro ) : ?>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                PRO
                <?php if ( ! $membership->is_lifetime( $user_id ) ) : ?>
                    <span class="lntv-pro-badge__days"><?php echo esc_html( $membership->get_days_remaining( $user_id ) ); ?>d</span>
                <?php endif; ?>
            <?php else : ?>
                FREE
            <?php endif; ?>
        </span>
        <?php
        return ob_get_clean();
    }

    public function shortcode_pro_cta( $atts = array() ) {
        $atts = shortcode_atts( array(
            'text' => __( 'Go Pro &mdash; Remove Ads', 'livenettv-pro' ),
        ), $atts );

        $user_id = get_current_user_id();

        if ( $user_id && livenettv_pro()->get_membership()->is_pro_user( $user_id ) ) {
            return '';
        }

        $pro_page_id = (int) get_option( 'livenettv_pro_pro_page_id', 0 );
        $url         = $pro_page_id ? get_permalink( $pro_page_id ) : '#';

        ob_start();
        ?>
        <a href="<?php echo esc_url( $url ); ?>" class="lntv-cta-btn">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            <?php echo wp_kses_post( $atts['text'] ); ?>
        </a>
        <?php
        return ob_get_clean();
    }

    // -------------------------------------------------------------------------
    // Partial renders
    // -------------------------------------------------------------------------

    private function render_login_prompt() {
        $pro_page_id = (int) get_option( 'livenettv_pro_pro_page_id', 0 );
        $pro_url     = $pro_page_id ? get_permalink( $pro_page_id ) : home_url( '/' );

        ob_start();
        ?>
        <div class="lntv-login-prompt">
            <div class="lntv-login-prompt__icon">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
                </svg>
            </div>
            <h2><?php esc_html_e( 'Sign in to get Pro', 'livenettv-pro' ); ?></h2>
            <p><?php esc_html_e( 'Create a free account or sign in to purchase a Pro membership and enjoy an ad-free experience.', 'livenettv-pro' ); ?></p>
            <button class="lntv-btn lntv-btn--primary lntv-open-auth-modal" data-redirect="<?php echo esc_attr( $pro_url ); ?>">
                <?php esc_html_e( 'Sign In / Sign Up', 'livenettv-pro' ); ?>
            </button>
        </div>
        <?php
        return ob_get_clean();
    }

    private function render_active_status( $user_id ) {
        $membership = livenettv_pro()->get_membership();
        $data       = $membership->get_user_membership_data( $user_id );
        $days       = $membership->get_days_remaining( $user_id );
        $plan       = $membership->get_plan( $data['plan'] );
        $is_lifetime= $membership->is_lifetime( $user_id );

        ob_start();
        ?>
        <div class="lntv-status lntv-status--active">
            <div class="lntv-status__header">
                <div class="lntv-status__icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                </div>
                <div>
                    <h2><?php esc_html_e( 'Pro Membership Active', 'livenettv-pro' ); ?></h2>
                    <?php if ( $is_lifetime ) : ?>
                        <p class="lntv-status__lifetime"><?php esc_html_e( 'Lifetime member', 'livenettv-pro' ); ?></p>
                    <?php else : ?>
                        <p class="lntv-status__days"><?php echo esc_html( sprintf( _n( '%d day remaining', '%d days remaining', $days, 'livenettv-pro' ), $days ) ); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="lntv-status__meta">
                <div class="lntv-status__row">
                    <span><?php esc_html_e( 'Plan', 'livenettv-pro' ); ?></span>
                    <strong><?php echo esc_html( $plan ? $plan['name'] : $data['plan'] ); ?></strong>
                </div>
                <?php if ( $data['start_date'] ) : ?>
                <div class="lntv-status__row">
                    <span><?php esc_html_e( 'Started', 'livenettv-pro' ); ?></span>
                    <strong><?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $data['start_date'] ) ) ); ?></strong>
                </div>
                <?php endif; ?>
                <?php if ( ! $is_lifetime && $data['expiry_date'] ) : ?>
                <div class="lntv-status__row">
                    <span><?php esc_html_e( 'Expires', 'livenettv-pro' ); ?></span>
                    <strong><?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $data['expiry_date'] ) ) ); ?></strong>
                </div>
                <?php endif; ?>
            </div>
            <div class="lntv-status__perks">
                <h3><?php esc_html_e( 'Your Benefits', 'livenettv-pro' ); ?></h3>
                <ul>
                    <li><?php esc_html_e( 'No popup ads', 'livenettv-pro' ); ?></li>
                    <li><?php esc_html_e( 'No banner or sticky ads', 'livenettv-pro' ); ?></li>
                    <li><?php esc_html_e( 'No download page ads', 'livenettv-pro' ); ?></li>
                    <li><?php esc_html_e( 'Faster, cleaner browsing', 'livenettv-pro' ); ?></li>
                    <li><?php esc_html_e( 'Exclusive Pro badge', 'livenettv-pro' ); ?></li>
                </ul>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    private function render_pending_status( $payment ) {
        ob_start();
        ?>
        <div class="lntv-status lntv-status--pending">
            <div class="lntv-status__header">
                <div class="lntv-status__icon lntv-status__icon--clock">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                    </svg>
                </div>
                <div>
                    <h2><?php esc_html_e( 'Payment Under Review', 'livenettv-pro' ); ?></h2>
                    <p><?php esc_html_e( 'Your payment is being verified. This usually takes 24&#8211;48 hours.', 'livenettv-pro' ); ?></p>
                </div>
            </div>
            <div class="lntv-status__meta">
                <div class="lntv-status__row">
                    <span><?php esc_html_e( 'Plan', 'livenettv-pro' ); ?></span>
                    <strong><?php echo esc_html( $payment->plan_name ); ?></strong>
                </div>
                <div class="lntv-status__row">
                    <span><?php esc_html_e( 'Submitted', 'livenettv-pro' ); ?></span>
                    <strong><?php echo esc_html( date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $payment->submitted_at ) ) ); ?></strong>
                </div>
                <?php if ( $payment->transaction_id ) : ?>
                <div class="lntv-status__row">
                    <span><?php esc_html_e( 'Transaction ID', 'livenettv-pro' ); ?></span>
                    <code><?php echo esc_html( $payment->transaction_id ); ?></code>
                </div>
                <?php endif; ?>
            </div>
            <p class="lntv-status__notice">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <?php esc_html_e( 'You will receive an email notification once your payment is processed.', 'livenettv-pro' ); ?>
            </p>
        </div>
        <?php
        return ob_get_clean();
    }

    private function render_payment_form() {
        $plans   = livenettv_pro()->get_membership()->get_plans();
        $wallets = $this->get_configured_wallets();

        // Pre-selected plan from URL.
        $pre_plan = isset( $_GET['plan'] ) ? sanitize_key( $_GET['plan'] ) : '';

        ob_start();
        ?>
        <div class="lntv-payment-form-wrap">
            <?php if ( isset( $_GET['payment_error'] ) ) : ?>
                <div class="lntv-alert lntv-alert--error">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <?php echo esc_html( urldecode( sanitize_text_field( $_GET['payment_error'] ) ) ); ?>
                </div>
            <?php endif; ?>

            <?php if ( isset( $_GET['payment_submitted'] ) ) : ?>
                <div class="lntv-alert lntv-alert--success">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                    <?php esc_html_e( 'Payment submitted! Our team will verify it within 24&#8211;48 hours. You\'ll receive an email when done.', 'livenettv-pro' ); ?>
                </div>
            <?php endif; ?>

            <form id="lntv-payment-form"
                  method="post"
                  action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
                  enctype="multipart/form-data">

                <input type="hidden" name="action" value="livenettv_submit_payment">
                <?php wp_nonce_field( 'livenettv_pro_payment', 'livenettv_pro_nonce' ); ?>

                <div class="lntv-form-section">
                    <h3 class="lntv-form-section__title">
                        <span class="lntv-step-num">1</span>
                        <?php esc_html_e( 'Select Your Plan', 'livenettv-pro' ); ?>
                    </h3>
                    <div class="lntv-plans-grid">
                        <?php foreach ( $plans as $slug => $plan ) : ?>
                            <label class="lntv-plan-card <?php echo $slug === $pre_plan ? 'lntv-plan-card--selected' : ''; ?> <?php echo ! empty( $plan['recommended'] ) ? 'lntv-plan-card--recommended' : ''; ?>">
                                <input type="radio"
                                       name="livenettv_plan"
                                       value="<?php echo esc_attr( $slug ); ?>"
                                       <?php checked( $slug, $pre_plan ); ?>
                                       required>
                                <?php if ( ! empty( $plan['recommended'] ) ) : ?>
                                    <span class="lntv-plan-badge lntv-plan-badge--recommended"><?php echo esc_html( $plan['badge'] ?? __( 'Best Value', 'livenettv-pro' ) ); ?></span>
                                <?php endif; ?>
                                <div class="lntv-plan-card__name"><?php echo esc_html( $plan['name'] ); ?></div>
                                <div class="lntv-plan-card__duration"><?php echo esc_html( $plan['duration_text'] ); ?></div>
                                <div class="lntv-plan-card__price">
                                    <span class="lntv-plan-card__currency">$</span><?php echo esc_html( number_format( $plan['price'], 2 ) ); ?>
                                    <?php if ( ! empty( $plan['save_percent'] ) ) : ?>
                                        <span class="lntv-plan-card__save">-<?php echo esc_html( $plan['save_percent'] ); ?>%</span>
                                    <?php endif; ?>
                                </div>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="lntv-form-section">
                    <h3 class="lntv-form-section__title">
                        <span class="lntv-step-num">2</span>
                        <?php esc_html_e( 'Choose Payment Method', 'livenettv-pro' ); ?>
                    </h3>
                    <?php if ( empty( $wallets ) ) : ?>
                        <p class="lntv-alert lntv-alert--warning"><?php esc_html_e( 'No payment methods configured yet. Please contact support.', 'livenettv-pro' ); ?></p>
                    <?php else : ?>
                        <div class="lntv-crypto-options">
                            <?php foreach ( $wallets as $key => $address ) : ?>
                                <label class="lntv-crypto-card">
                                    <input type="radio" name="livenettv_crypto" value="<?php echo esc_attr( $key ); ?>" required>
                                    <span class="lntv-crypto-card__dot lntv-crypto-card__dot--<?php echo esc_attr( $key ); ?>"></span>
                                    <span class="lntv-crypto-card__name"><?php echo esc_html( strtoupper( $key ) ); ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="lntv-form-section lntv-payment-info-section" style="display:none;">
                    <h3 class="lntv-form-section__title">
                        <span class="lntv-step-num">3</span>
                        <?php esc_html_e( 'Send Payment', 'livenettv-pro' ); ?>
                    </h3>
                    <div class="lntv-payment-info">
                        <div class="lntv-payment-qr">
                            <div class="lntv-payment-qr__img"></div>
                        </div>
                        <div class="lntv-payment-details">
                            <div class="lntv-payment-details__label"><?php esc_html_e( 'Send to wallet address:', 'livenettv-pro' ); ?></div>
                            <div class="lntv-wallet-address-row">
                                <code class="lntv-wallet-address"></code>
                                <button type="button" class="lntv-copy-btn" data-copy="">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                                    <?php esc_html_e( 'Copy', 'livenettv-pro' ); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                    <p class="lntv-payment-note">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <?php esc_html_e( 'Send the exact amount for your selected plan. Copy the address carefully — crypto transactions cannot be reversed.', 'livenettv-pro' ); ?>
                    </p>
                </div>

                <div class="lntv-form-section">
                    <h3 class="lntv-form-section__title">
                        <span class="lntv-step-num">4</span>
                        <?php esc_html_e( 'Upload Payment Proof', 'livenettv-pro' ); ?>
                    </h3>
                    <div class="lntv-upload-area" id="lntv-upload-area">
                        <input type="file"
                               name="livenettv_screenshot"
                               id="lntv-screenshot"
                               accept="image/*"
                               class="lntv-upload-input"
                               required>
                        <label for="lntv-screenshot" class="lntv-upload-label">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                <polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/>
                                <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/>
                            </svg>
                            <span class="lntv-upload-label__text"><?php esc_html_e( 'Click or drag your screenshot here', 'livenettv-pro' ); ?></span>
                            <span class="lntv-upload-label__hint"><?php esc_html_e( 'JPG, PNG, WebP — max 5MB', 'livenettv-pro' ); ?></span>
                        </label>
                        <div class="lntv-upload-preview"></div>
                    </div>
                    <div class="lntv-field" style="margin-top:16px;">
                        <label for="lntv-txid"><?php esc_html_e( 'Transaction ID (TXID)', 'livenettv-pro' ); ?></label>
                        <input type="text"
                               name="livenettv_txid"
                               id="lntv-txid"
                               placeholder="<?php esc_attr_e( 'Paste your transaction hash here', 'livenettv-pro' ); ?>"
                               required>
                    </div>
                </div>

                <div class="lntv-form-submit">
                    <button type="submit" class="lntv-btn lntv-btn--primary lntv-submit-btn">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                        <?php esc_html_e( 'Submit Payment', 'livenettv-pro' ); ?>
                    </button>
                    <p class="lntv-form-submit__note">
                        <?php esc_html_e( 'Your subscription will be activated after our team verifies the payment.', 'livenettv-pro' ); ?>
                    </p>
                </div>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }

    // -------------------------------------------------------------------------
    // Auth modal
    // -------------------------------------------------------------------------

    public function render_auth_modal() {
        if ( is_user_logged_in() ) {
            return;
        }

        $google_auth = new LiveNetTV_Pro_Google_Auth();
        $google_configured = $google_auth->is_configured();

        ?>
        <div id="lntv-auth-modal" class="lntv-modal" role="dialog" aria-modal="true" aria-labelledby="lntv-modal-title" hidden>
            <div class="lntv-modal__overlay" id="lntv-modal-overlay"></div>
            <div class="lntv-modal__box">
                <button class="lntv-modal__close" id="lntv-modal-close" aria-label="<?php esc_attr_e( 'Close', 'livenettv-pro' ); ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>

                <!-- LOGIN PANEL -->
                <div class="lntv-modal__panel" id="lntv-panel-login">
                    <h2 class="lntv-modal__title" id="lntv-modal-title">
                        <?php echo esc_html( get_bloginfo( 'name' ) ); ?>
                    </h2>
                    <p class="lntv-modal__subtitle"><?php esc_html_e( 'Sign in to your account to continue', 'livenettv-pro' ); ?></p>

                    <?php if ( $google_configured ) : ?>
                        <button type="button" class="lntv-google-btn" id="lntv-google-login-btn">
                            <svg viewBox="0 0 24 24" width="20" height="20"><g transform="matrix(1,0,0,1,27.009155,-39.238177)"><path fill="#4285F4" d="M -3.264 51.509 C -3.264 50.719 -3.334 49.969 -3.454 49.239 L -14.754 49.239 L -14.754 53.749 L -8.284 53.749 C -8.514 54.989 -9.224 56.049 -10.264 56.759 L -10.264 59.779 L -6.644 59.779 C -4.524 57.819 -3.264 54.949 -3.264 51.509 Z"/><path fill="#34A853" d="M -14.754 62.769 C -11.514 62.769 -8.794 61.789 -6.644 59.779 L -10.264 56.759 C -11.414 57.549 -12.914 58.019 -14.754 58.019 C -17.894 58.019 -20.514 56.059 -21.454 53.379 L -25.174 53.379 L -25.174 56.489 C -23.024 60.679 -18.594 62.769 -14.754 62.769 Z"/><path fill="#FBBC05" d="M -21.454 53.379 C -21.694 52.649 -21.824 51.869 -21.824 51.069 C -21.824 50.269 -21.694 49.489 -21.454 48.759 L -21.454 45.649 L -25.174 45.649 C -26.024 47.429 -26.504 49.429 -26.504 51.509 C -26.504 53.589 -26.024 55.589 -25.174 57.369 L -21.454 54.259 Z"/><path fill="#EA4335" d="M -14.754 44.099 C -12.844 44.099 -11.114 44.759 -9.754 45.999 L -6.544 42.789 C -8.794 40.689 -11.514 39.349 -14.754 39.349 C -18.594 39.349 -22.024 41.439 -23.954 44.649 L -20.334 47.759 C -19.404 45.079 -16.784 43.099 -14.754 44.099 Z"/></g></svg>
                            <?php esc_html_e( 'Sign in with Google', 'livenettv-pro' ); ?>
                        </button>

                        <div class="lntv-modal__divider"><span><?php esc_html_e( 'OR', 'livenettv-pro' ); ?></span></div>
                    <?php endif; ?>

                    <form id="lntv-login-form" novalidate>
                        <div id="lntv-login-error" class="lntv-form-error" hidden></div>

                        <div class="lntv-field">
                            <input type="text"
                                   name="log"
                                   id="lntv-login-username"
                                   placeholder="<?php esc_attr_e( 'Username or Email', 'livenettv-pro' ); ?>"
                                   autocomplete="username"
                                   required>
                        </div>
                        <div class="lntv-field">
                            <input type="password"
                                   name="pwd"
                                   id="lntv-login-password"
                                   placeholder="<?php esc_attr_e( 'Password', 'livenettv-pro' ); ?>"
                                   autocomplete="current-password"
                                   required>
                        </div>

                        <div class="lntv-terms">
                            <label>
                                <input type="checkbox" name="terms" required>
                                <?php
                                $pp  = get_option( 'wp_page_for_privacy_policy' );
                                $tos = get_option( 'livenettv_pro_tos_page_id', 0 );
                                $pp_link  = $pp  ? '<a href="' . esc_url( get_permalink( $pp ) ) . '" target="_blank">' . esc_html__( 'Privacy Policy', 'livenettv-pro' ) . '</a>' : esc_html__( 'Privacy Policy', 'livenettv-pro' );
                                $tos_link = $tos ? '<a href="' . esc_url( get_permalink( $tos ) ) . '" target="_blank">' . esc_html__( 'Terms of Service', 'livenettv-pro' ) . '</a>' : esc_html__( 'Terms of Service', 'livenettv-pro' );
                                printf( wp_kses_post( __( 'I agree to the %1$s and %2$s', 'livenettv-pro' ) ), $tos_link, $pp_link );
                                ?>
                            </label>
                        </div>

                        <button type="submit" class="lntv-btn lntv-btn--primary lntv-btn--full">
                            <?php esc_html_e( 'Login with Username or Email', 'livenettv-pro' ); ?>
                        </button>
                    </form>

                    <p class="lntv-modal__switch">
                        <?php esc_html_e( "Don't have an account?", 'livenettv-pro' ); ?>
                        <button type="button" class="lntv-modal__switch-link" id="lntv-goto-register">
                            <?php esc_html_e( 'Sign Up', 'livenettv-pro' ); ?>
                        </button>
                    </p>
                </div>

                <!-- REGISTER PANEL -->
                <div class="lntv-modal__panel" id="lntv-panel-register" hidden>
                    <h2 class="lntv-modal__title"><?php esc_html_e( 'Create Account', 'livenettv-pro' ); ?></h2>
                    <p class="lntv-modal__subtitle"><?php esc_html_e( 'Sign up for a free account to get started', 'livenettv-pro' ); ?></p>

                    <form id="lntv-register-form" novalidate>
                        <div id="lntv-register-error" class="lntv-form-error" hidden></div>

                        <div class="lntv-field">
                            <input type="text"
                                   name="user_login"
                                   placeholder="<?php esc_attr_e( 'Username', 'livenettv-pro' ); ?>"
                                   autocomplete="username"
                                   required>
                        </div>
                        <div class="lntv-field">
                            <input type="email"
                                   name="user_email"
                                   placeholder="<?php esc_attr_e( 'Email', 'livenettv-pro' ); ?>"
                                   autocomplete="email"
                                   required>
                        </div>
                        <div class="lntv-field">
                            <input type="password"
                                   name="user_pass"
                                   placeholder="<?php esc_attr_e( 'Password', 'livenettv-pro' ); ?>"
                                   autocomplete="new-password"
                                   required>
                        </div>
                        <div class="lntv-field">
                            <input type="password"
                                   name="user_pass2"
                                   placeholder="<?php esc_attr_e( 'Confirm Password', 'livenettv-pro' ); ?>"
                                   autocomplete="new-password"
                                   required>
                        </div>

                        <?php
                        $cap_num1 = wp_rand( 1, 10 );
                        $cap_num2 = wp_rand( 1, 10 );
                        ?>
                        <div class="lntv-field lntv-captcha-field">
                            <input type="number"
                                   name="captcha_answer"
                                   placeholder="<?php echo esc_attr( "$cap_num1 + $cap_num2 = ?" ); ?>"
                                   required>
                            <input type="hidden" name="captcha_correct" value="<?php echo esc_attr( $cap_num1 + $cap_num2 ); ?>">
                        </div>

                        <div class="lntv-terms">
                            <label>
                                <input type="checkbox" name="terms" required>
                                <?php printf( wp_kses_post( __( 'I agree to the %1$s and %2$s', 'livenettv-pro' ) ), $tos_link, $pp_link ); ?>
                            </label>
                        </div>

                        <button type="submit" class="lntv-btn lntv-btn--primary lntv-btn--full">
                            <?php esc_html_e( 'Sign Up', 'livenettv-pro' ); ?>
                        </button>
                    </form>

                    <p class="lntv-modal__switch">
                        <?php esc_html_e( 'Already have an account?', 'livenettv-pro' ); ?>
                        <button type="button" class="lntv-modal__switch-link" id="lntv-goto-login">
                            <?php esc_html_e( 'Log In', 'livenettv-pro' ); ?>
                        </button>
                    </p>
                </div>
            </div>
        </div>
        <?php
    }

    // -------------------------------------------------------------------------
    // AJAX handlers
    // -------------------------------------------------------------------------

    public function ajax_get_crypto_data() {
        check_ajax_referer( 'livenettv_pro_nonce', 'nonce' );

        $crypto    = sanitize_key( $_POST['crypto'] ?? '' );
        $plan_slug = sanitize_key( $_POST['plan']   ?? '' );

        $plan = livenettv_pro()->get_membership()->get_plan( $plan_slug );
        if ( ! $plan ) {
            wp_send_json_error( array( 'message' => __( 'Invalid plan.', 'livenettv-pro' ) ) );
        }

        $wallets = $this->get_configured_wallets();
        if ( empty( $wallets[ $crypto ] ) ) {
            wp_send_json_error( array( 'message' => __( 'Wallet not configured.', 'livenettv-pro' ) ) );
        }

        $address = $wallets[ $crypto ];

        wp_send_json_success( array(
            'wallet_address' => $address,
            'qr_url'         => $this->qr_url( $crypto, $address ),
            'crypto'         => strtoupper( $crypto ),
        ) );
    }

    public function ajax_check_payment_status() {
        check_ajax_referer( 'livenettv_pro_nonce', 'nonce' );

        $user_id = get_current_user_id();
        if ( ! $user_id ) {
            wp_send_json_success( array( 'status' => 'not_logged_in' ) );
        }

        $db      = livenettv_pro()->get_db();
        $pending = $db->get_user_pending_payment( $user_id );

        if ( $pending ) {
            wp_send_json_success( array( 'status' => 'pending', 'submitted_at' => $pending->submitted_at ) );
        }

        $is_pro = livenettv_pro()->get_membership()->is_pro_user( $user_id );
        wp_send_json_success( array( 'status' => $is_pro ? 'approved' : 'none' ) );
    }

    public function ajax_wp_login() {
        check_ajax_referer( 'livenettv_pro_nonce', 'nonce' );

        $username = sanitize_text_field( $_POST['log'] ?? '' );
        $password = $_POST['pwd'] ?? '';
        $redirect = esc_url_raw( $_POST['redirect'] ?? '' );

        if ( empty( $username ) || empty( $password ) ) {
            wp_send_json_error( array( 'message' => __( 'Please enter your username/email and password.', 'livenettv-pro' ) ) );
        }

        $user = wp_authenticate( $username, $password );

        if ( is_wp_error( $user ) ) {
            wp_send_json_error( array( 'message' => __( 'Invalid username or password.', 'livenettv-pro' ) ) );
        }

        wp_set_current_user( $user->ID );
        wp_set_auth_cookie( $user->ID, false );

        $destination = $redirect ?: get_option( 'livenettv_pro_redirect_after_login', home_url( '/' ) );

        wp_send_json_success( array(
            'redirect' => $destination ?: ( function_exists( 'get_permalink' ) && get_option( 'livenettv_pro_pro_page_id' ) ? get_permalink( get_option( 'livenettv_pro_pro_page_id' ) ) : home_url( '/' ) ),
        ) );
    }

    public function ajax_wp_register() {
        check_ajax_referer( 'livenettv_pro_nonce', 'nonce' );

        if ( ! get_option( 'users_can_register' ) ) {
            wp_send_json_error( array( 'message' => __( 'User registration is currently disabled.', 'livenettv-pro' ) ) );
        }

        $username   = sanitize_user( $_POST['user_login']  ?? '' );
        $email      = sanitize_email( $_POST['user_email'] ?? '' );
        $password   = $_POST['user_pass']  ?? '';
        $password2  = $_POST['user_pass2'] ?? '';
        $captcha_a  = absint( $_POST['captcha_answer']  ?? -999 );
        $captcha_c  = absint( $_POST['captcha_correct'] ?? 0 );
        $redirect   = esc_url_raw( $_POST['redirect']   ?? '' );

        if ( empty( $username ) || empty( $email ) || empty( $password ) ) {
            wp_send_json_error( array( 'message' => __( 'All fields are required.', 'livenettv-pro' ) ) );
        }

        if ( ! is_email( $email ) ) {
            wp_send_json_error( array( 'message' => __( 'Invalid email address.', 'livenettv-pro' ) ) );
        }

        if ( $password !== $password2 ) {
            wp_send_json_error( array( 'message' => __( 'Passwords do not match.', 'livenettv-pro' ) ) );
        }

        if ( strlen( $password ) < 8 ) {
            wp_send_json_error( array( 'message' => __( 'Password must be at least 8 characters.', 'livenettv-pro' ) ) );
        }

        if ( $captcha_a !== $captcha_c ) {
            wp_send_json_error( array( 'message' => __( 'Incorrect security answer. Please try again.', 'livenettv-pro' ) ) );
        }

        if ( username_exists( $username ) ) {
            wp_send_json_error( array( 'message' => __( 'Username already exists. Please choose another.', 'livenettv-pro' ) ) );
        }

        if ( email_exists( $email ) ) {
            wp_send_json_error( array( 'message' => __( 'An account with this email already exists.', 'livenettv-pro' ) ) );
        }

        $user_id = wp_create_user( $username, $password, $email );

        if ( is_wp_error( $user_id ) ) {
            wp_send_json_error( array( 'message' => $user_id->get_error_message() ) );
        }

        wp_set_current_user( $user_id );
        wp_set_auth_cookie( $user_id, false );

        $pro_page_id = (int) get_option( 'livenettv_pro_pro_page_id', 0 );
        $destination = $redirect ?: ( $pro_page_id ? get_permalink( $pro_page_id ) : home_url( '/' ) );

        wp_send_json_success( array( 'redirect' => $destination ) );
    }

    public function ajax_get_google_url() {
        check_ajax_referer( 'livenettv_pro_nonce', 'nonce' );

        $google_auth = new LiveNetTV_Pro_Google_Auth();

        if ( ! $google_auth->is_configured() ) {
            wp_send_json_error( array( 'message' => __( 'Google Sign-In is not configured.', 'livenettv-pro' ) ) );
        }

        $redirect = esc_url_raw( $_POST['redirect'] ?? '' );

        wp_send_json_success( array( 'auth_url' => $google_auth->get_auth_url( $redirect ) ) );
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function get_configured_wallets() {
        $raw = array(
            'usdt' => get_option( 'livenettv_pro_wallet_usdt', '' ),
            'btc'  => get_option( 'livenettv_pro_wallet_btc',  '' ),
            'eth'  => get_option( 'livenettv_pro_wallet_eth',  '' ),
            'bnb'  => get_option( 'livenettv_pro_wallet_bnb',  '' ),
        );

        return array_filter( $raw );
    }

    private function qr_url( $crypto, $address ) {
        $prefixes = array(
            'btc'  => 'bitcoin',
            'eth'  => 'ethereum',
            'usdt' => 'ethereum',
            'bnb'  => 'binance',
        );

        $prefix = $prefixes[ strtolower( $crypto ) ] ?? strtolower( $crypto );
        $uri    = $prefix . ':' . $address;

        return 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . rawurlencode( $uri );
    }
}
