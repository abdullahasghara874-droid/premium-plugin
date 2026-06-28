<?php
defined( 'ABSPATH' ) || exit;

class LiveNetTV_Pro_Frontend {

    public function __construct() {
        add_action( 'init', array( $this, 'init' ) );
        add_filter( 'theme_page_templates', array( $this, 'register_page_template' ) );
        add_filter( 'template_include',     array( $this, 'load_page_template' ) );
    }

    public function init() {
        add_shortcode( 'livenettv_premium_plans', array( $this, 'shortcode_premium_plans' ) );
        add_shortcode( 'livenettv_payment_form',  array( $this, 'shortcode_payment_form' ) );
        add_shortcode( 'livenettv_pro_status',    array( $this, 'shortcode_pro_status' ) );
        add_shortcode( 'livenettv_pro_cta',       array( $this, 'shortcode_pro_cta' ) );

        add_action( 'wp_ajax_livenettv_wp_login',              array( $this, 'ajax_wp_login' ) );
        add_action( 'wp_ajax_nopriv_livenettv_wp_login',       array( $this, 'ajax_wp_login' ) );
        add_action( 'wp_ajax_livenettv_wp_register',           array( $this, 'ajax_wp_register' ) );
        add_action( 'wp_ajax_nopriv_livenettv_wp_register',    array( $this, 'ajax_wp_register' ) );
        add_action( 'wp_ajax_livenettv_get_google_url',        array( $this, 'ajax_get_google_url' ) );
        add_action( 'wp_ajax_nopriv_livenettv_get_google_url', array( $this, 'ajax_get_google_url' ) );

        add_action( 'wp_footer',  array( $this, 'render_auth_modal' ) );
        add_action( 'body_class', array( $this, 'add_body_classes' ) );

        // Inject account icon into nav.
        add_filter( 'wp_nav_menu_items', array( $this, 'inject_nav_account_icon' ), 10, 2 );
    }

    // -------------------------------------------------------------------------
    // Page template
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
            $plugin_tpl = LIVENETTV_PRO_PATH . 'templates/page-premium-plans.php';
            if ( file_exists( $plugin_tpl ) ) {
                return $plugin_tpl;
            }
        }
        return $template;
    }

    // -------------------------------------------------------------------------
    // Body classes
    // -------------------------------------------------------------------------

    public function add_body_classes( $classes ) {
        $uid = get_current_user_id();
        if ( $uid && livenettv_pro()->get_membership()->is_pro_user( $uid ) ) {
            $classes[] = 'lntv-pro-user';
        } else {
            $classes[] = 'lntv-free-user';
        }
        return $classes;
    }

    // -------------------------------------------------------------------------
    // Nav account icon
    // -------------------------------------------------------------------------

    public function inject_nav_account_icon( $items, $args ) {
        // Only inject into the primary menu.
        if ( 'primary' !== ( $args->theme_location ?? '' ) ) {
            return $items;
        }

        $uid        = get_current_user_id();
        $membership = livenettv_pro()->get_membership();
        $is_pro     = $uid && $membership->is_pro_user( $uid );
        $pro_url    = $this->get_pro_page_url();

        ob_start();
        if ( $is_pro ) {
            $user = get_userdata( $uid );
            $avatar = get_avatar_url( $uid, array( 'size' => 32 ) );
            ?>
            <li class="lntv-nav-account lntv-nav-account--pro">
                <a href="<?php echo esc_url( $pro_url ); ?>" title="<?php esc_attr_e( 'Pro Member', 'livenettv-pro' ); ?>">
                    <span class="lntv-nav-avatar">
                        <img src="<?php echo esc_url( $avatar ); ?>" alt="" width="32" height="32">
                        <span class="lntv-nav-pro-dot"></span>
                    </span>
                    <span class="lntv-nav-pro-label">PRO</span>
                </a>
            </li>
            <?php
        } elseif ( $uid ) {
            ?>
            <li class="lntv-nav-account">
                <a href="<?php echo esc_url( $pro_url ); ?>" class="lntv-nav-upgrade-btn">
                    <?php esc_html_e( 'Go Pro', 'livenettv-pro' ); ?>
                </a>
            </li>
            <?php
        } else {
            ?>
            <li class="lntv-nav-account">
                <button type="button" class="lntv-nav-login-btn lntv-open-auth-modal" data-redirect="<?php echo esc_attr( $pro_url ); ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                    </svg>
                    <?php esc_html_e( 'Sign In', 'livenettv-pro' ); ?>
                </button>
            </li>
            <?php
        }
        $icon = ob_get_clean();

        return $items . $icon;
    }

    // -------------------------------------------------------------------------
    // Shortcodes
    // -------------------------------------------------------------------------

    /**
     * [livenettv_premium_plans]
     * Always shows pricing plans. Subscribe Now triggers auth modal for guests,
     * or reveals the payment form for logged-in users.
     */
    public function shortcode_premium_plans( $atts = array() ) {
        $membership = livenettv_pro()->get_membership();
        $plans      = $membership->get_plans();
        $uid        = get_current_user_id();
        $is_pro     = $uid && $membership->is_pro_user( $uid );
        $db         = livenettv_pro()->get_db();
        $pending    = $uid ? $db->get_user_pending_payment( $uid ) : null;
        $pre_plan   = isset( $_GET['plan'] ) ? sanitize_key( $_GET['plan'] ) : '';
        $pro_url    = $this->get_pro_page_url();

        ob_start();
        ?>
        <div class="lntv-pro-page">

            <?php if ( isset( $_GET['payment_submitted'] ) ) : ?>
                <div class="lntv-alert lntv-alert--success">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                    <?php esc_html_e( 'Payment submitted! Our team will verify it within 24–48 hours. Check your email for confirmation.', 'livenettv-pro' ); ?>
                </div>
            <?php endif; ?>

            <?php if ( isset( $_GET['payment_error'] ) ) : ?>
                <div class="lntv-alert lntv-alert--error">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <?php echo esc_html( urldecode( sanitize_text_field( $_GET['payment_error'] ) ) ); ?>
                </div>
            <?php endif; ?>

            <!-- ========== HERO ========== -->
            <div class="lntv-hero">
                <h1 class="lntv-hero__title"><?php esc_html_e( 'Browse Without Ads', 'livenettv-pro' ); ?></h1>
                <p class="lntv-hero__subtitle"><?php esc_html_e( 'Choose a plan and enjoy an uninterrupted, ad-free experience.', 'livenettv-pro' ); ?></p>
            </div>

            <?php if ( $is_pro ) : ?>
                <!-- ========== PRO STATUS ========== -->
                <?php echo $this->render_active_status( $uid ); ?>

            <?php elseif ( $pending ) : ?>
                <!-- ========== PENDING STATUS ========== -->
                <?php echo $this->render_pending_status( $pending ); ?>

            <?php else : ?>

                <!-- ========== PLANS GRID ========== -->
                <div class="lntv-plans-grid" id="lntv-plans-grid">
                    <?php foreach ( $plans as $slug => $plan ) : ?>
                        <div class="lntv-plan-card <?php echo ! empty( $plan['recommended'] ) ? 'lntv-plan-card--recommended' : ''; ?>" data-plan="<?php echo esc_attr( $slug ); ?>">
                            <?php if ( ! empty( $plan['badge'] ) ) : ?>
                                <span class="lntv-plan-card__badge"><?php echo esc_html( $plan['badge'] ); ?></span>
                            <?php endif; ?>
                            <div class="lntv-plan-card__name"><?php echo esc_html( $plan['name'] ); ?></div>
                            <div class="lntv-plan-card__price">
                                <span class="lntv-plan-card__currency">$</span><?php echo esc_html( number_format( $plan['price'], 2 ) ); ?>
                            </div>
                            <div class="lntv-plan-card__duration"><?php echo esc_html( $plan['duration_text'] ); ?></div>
                            <?php if ( ! empty( $plan['save_percent'] ) ) : ?>
                                <div class="lntv-plan-card__save"><?php printf( esc_html__( 'Save %d%%', 'livenettv-pro' ), $plan['save_percent'] ); ?></div>
                            <?php endif; ?>
                            <button
                                type="button"
                                class="lntv-subscribe-btn <?php echo $uid ? 'lntv-show-payment' : 'lntv-open-auth-modal'; ?>"
                                data-plan="<?php echo esc_attr( $slug ); ?>"
                                data-redirect="<?php echo esc_attr( add_query_arg( 'plan', $slug, $pro_url ) ); ?>"
                            >
                                <?php esc_html_e( 'Subscribe Now', 'livenettv-pro' ); ?>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- ========== FEATURES ========== -->
                <div class="lntv-features">
                    <div class="lntv-feature">
                        <div class="lntv-feature__icon lntv-feature__icon--red">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                        </div>
                        <h3><?php esc_html_e( 'Ad-Free', 'livenettv-pro' ); ?></h3>
                        <p><?php esc_html_e( 'Enjoy uninterrupted streaming with no popup or banner ads.', 'livenettv-pro' ); ?></p>
                    </div>
                    <div class="lntv-feature">
                        <div class="lntv-feature__icon lntv-feature__icon--blue">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                        </div>
                        <h3><?php esc_html_e( 'Faster Browsing', 'livenettv-pro' ); ?></h3>
                        <p><?php esc_html_e( 'Pages load faster without heavy ad scripts slowing you down.', 'livenettv-pro' ); ?></p>
                    </div>
                    <div class="lntv-feature">
                        <div class="lntv-feature__icon lntv-feature__icon--gold">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/></svg>
                        </div>
                        <h3><?php esc_html_e( 'Premium Badge', 'livenettv-pro' ); ?></h3>
                        <p><?php esc_html_e( 'Your profile displays an exclusive PRO badge on all pages.', 'livenettv-pro' ); ?></p>
                    </div>
                    <div class="lntv-feature">
                        <div class="lntv-feature__icon lntv-feature__icon--green">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        </div>
                        <h3><?php esc_html_e( 'Safe Payment', 'livenettv-pro' ); ?></h3>
                        <p><?php esc_html_e( 'Pay securely with crypto — verified manually by our team.', 'livenettv-pro' ); ?></p>
                    </div>
                </div>

                <!-- ========== PAYMENT FORM (logged-in only) ========== -->
                <?php if ( $uid ) : ?>
                    <div class="lntv-billing-section" id="lntv-billing-section" style="display:none;">
                        <h2 class="lntv-billing-section__title"><?php esc_html_e( 'Complete Your Order', 'livenettv-pro' ); ?></h2>
                        <?php echo $this->render_payment_form( $pre_plan ); ?>
                    </div>
                <?php endif; ?>

                <!-- ========== FAQ ========== -->
                <div class="lntv-faq">
                    <h2><?php esc_html_e( 'FAQ', 'livenettv-pro' ); ?></h2>
                    <?php
                    $faqs = array(
                        array(
                            'q' => __( 'How does crypto payment work?', 'livenettv-pro' ),
                            'a' => __( 'Select your plan, copy the wallet address, send the exact amount, then upload a screenshot and your transaction ID. Our team verifies it within 24–48 hours.', 'livenettv-pro' ),
                        ),
                        array(
                            'q' => __( 'How long does approval take?', 'livenettv-pro' ),
                            'a' => __( 'Usually within 24 hours. You will receive an email when your membership is activated.', 'livenettv-pro' ),
                        ),
                        array(
                            'q' => __( 'What payment methods are accepted?', 'livenettv-pro' ),
                            'a' => __( 'We accept USDT (TRC20/ERC20), Bitcoin (BTC), Ethereum (ETH), and BNB (BEP20).', 'livenettv-pro' ),
                        ),
                        array(
                            'q' => __( 'Can I get a refund?', 'livenettv-pro' ),
                            'a' => __( 'Due to the nature of cryptocurrency payments, refunds are not available once a payment is confirmed.', 'livenettv-pro' ),
                        ),
                    );
                    foreach ( $faqs as $i => $faq ) :
                    ?>
                        <div class="lntv-faq__item">
                            <button class="lntv-faq__q" data-index="<?php echo esc_attr( $i ); ?>">
                                <?php echo esc_html( $faq['q'] ); ?>
                                <svg class="lntv-faq__chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                            </button>
                            <div class="lntv-faq__a">
                                <p><?php echo esc_html( $faq['a'] ); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * [livenettv_payment_form] — standalone billing shortcode.
     */
    public function shortcode_payment_form( $atts = array() ) {
        $uid        = get_current_user_id();
        $membership = livenettv_pro()->get_membership();
        $db         = livenettv_pro()->get_db();

        if ( ! $uid ) {
            return '';
        }

        if ( $membership->is_pro_user( $uid ) ) {
            return $this->render_active_status( $uid );
        }

        $pending = $db->get_user_pending_payment( $uid );
        if ( $pending ) {
            return $this->render_pending_status( $pending );
        }

        return $this->render_payment_form( sanitize_key( $_GET['plan'] ?? '' ) );
    }

    /**
     * [livenettv_pro_status] — small badge/status indicator.
     */
    public function shortcode_pro_status( $atts = array() ) {
        $uid = get_current_user_id();
        if ( ! $uid ) {
            return '';
        }
        $membership = livenettv_pro()->get_membership();
        $is_pro     = $membership->is_pro_user( $uid );
        if ( ! $is_pro ) {
            return '';
        }
        $days = $membership->get_days_remaining( $uid );
        ob_start();
        ?>
        <span class="lntv-pro-badge">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            PRO
            <?php if ( ! $membership->is_lifetime( $uid ) && $days > 0 ) : ?>
                <span class="lntv-pro-badge__days"><?php echo esc_html( $days ); ?>d</span>
            <?php endif; ?>
        </span>
        <?php
        return ob_get_clean();
    }

    /**
     * [livenettv_pro_cta] — "Go Pro" link shown to non-pro users.
     */
    public function shortcode_pro_cta( $atts = array() ) {
        $uid = get_current_user_id();
        if ( $uid && livenettv_pro()->get_membership()->is_pro_user( $uid ) ) {
            return '';
        }
        $atts = shortcode_atts( array( 'text' => __( 'Go Pro — Remove Ads', 'livenettv-pro' ) ), $atts );
        ob_start();
        ?>
        <a href="<?php echo esc_url( $this->get_pro_page_url() ); ?>" class="lntv-cta-link">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            <?php echo wp_kses_post( $atts['text'] ); ?>
        </a>
        <?php
        return ob_get_clean();
    }

    // -------------------------------------------------------------------------
    // Render helpers
    // -------------------------------------------------------------------------

    private function render_active_status( $uid ) {
        $membership  = livenettv_pro()->get_membership();
        $data        = $membership->get_user_membership_data( $uid );
        $days        = $membership->get_days_remaining( $uid );
        $plan        = $membership->get_plan( $data['plan'] );
        $is_lifetime = $membership->is_lifetime( $uid );
        $fmt         = get_option( 'date_format' );

        ob_start();
        ?>
        <div class="lntv-status lntv-status--active">
            <div class="lntv-status__icon">
                <svg width="36" height="36" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            </div>
            <h2><?php esc_html_e( 'Pro Membership Active', 'livenettv-pro' ); ?></h2>
            <p>
                <?php if ( $is_lifetime ) : ?>
                    <?php esc_html_e( 'You are a Lifetime Pro member.', 'livenettv-pro' ); ?>
                <?php else : ?>
                    <?php printf( esc_html( _n( '%d day remaining', '%d days remaining', $days, 'livenettv-pro' ) ), $days ); ?>
                <?php endif; ?>
            </p>
            <div class="lntv-status__meta">
                <?php if ( $plan ) : ?>
                    <div class="lntv-status__row"><span><?php esc_html_e( 'Plan', 'livenettv-pro' ); ?></span><strong><?php echo esc_html( $plan['name'] ); ?></strong></div>
                <?php endif; ?>
                <?php if ( $data['start_date'] ) : ?>
                    <div class="lntv-status__row"><span><?php esc_html_e( 'Started', 'livenettv-pro' ); ?></span><strong><?php echo esc_html( date_i18n( $fmt, strtotime( $data['start_date'] ) ) ); ?></strong></div>
                <?php endif; ?>
                <?php if ( ! $is_lifetime && $data['expiry_date'] ) : ?>
                    <div class="lntv-status__row"><span><?php esc_html_e( 'Expires', 'livenettv-pro' ); ?></span><strong><?php echo esc_html( date_i18n( $fmt, strtotime( $data['expiry_date'] ) ) ); ?></strong></div>
                <?php endif; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    private function render_pending_status( $payment ) {
        $fmt = get_option( 'date_format' ) . ' ' . get_option( 'time_format' );
        ob_start();
        ?>
        <div class="lntv-status lntv-status--pending">
            <div class="lntv-status__icon lntv-status__icon--clock">
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <h2><?php esc_html_e( 'Payment Under Review', 'livenettv-pro' ); ?></h2>
            <p><?php esc_html_e( 'Your payment is being verified. This usually takes 24–48 hours.', 'livenettv-pro' ); ?></p>
            <div class="lntv-status__meta">
                <div class="lntv-status__row"><span><?php esc_html_e( 'Plan', 'livenettv-pro' ); ?></span><strong><?php echo esc_html( $payment->plan_name ); ?></strong></div>
                <div class="lntv-status__row"><span><?php esc_html_e( 'Submitted', 'livenettv-pro' ); ?></span><strong><?php echo esc_html( date_i18n( $fmt, strtotime( $payment->submitted_at ) ) ); ?></strong></div>
                <?php if ( $payment->transaction_id ) : ?>
                    <div class="lntv-status__row"><span><?php esc_html_e( 'TXID', 'livenettv-pro' ); ?></span><code><?php echo esc_html( $payment->transaction_id ); ?></code></div>
                <?php endif; ?>
            </div>
            <p class="lntv-status__note"><?php esc_html_e( 'You will receive an email once your payment is processed.', 'livenettv-pro' ); ?></p>
        </div>
        <?php
        return ob_get_clean();
    }

    private function render_payment_form( $pre_plan = '' ) {
        $plans   = livenettv_pro()->get_membership()->get_plans();
        $wallets = $this->get_configured_wallets();

        ob_start();
        ?>
        <form id="lntv-payment-form"
              method="post"
              action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
              enctype="multipart/form-data"
              class="lntv-payment-form">

            <input type="hidden" name="action" value="livenettv_submit_payment">
            <?php wp_nonce_field( 'livenettv_pro_payment', 'livenettv_pro_nonce' ); ?>

            <!-- Step 1: Plan -->
            <div class="lntv-form-step">
                <div class="lntv-form-step__label">
                    <span class="lntv-step-num">1</span>
                    <?php esc_html_e( 'Select Plan', 'livenettv-pro' ); ?>
                </div>
                <div class="lntv-plan-radios">
                    <?php foreach ( $plans as $slug => $plan ) : ?>
                        <label class="lntv-plan-radio <?php echo ! empty( $plan['recommended'] ) ? 'lntv-plan-radio--recommended' : ''; ?> <?php echo $slug === $pre_plan ? 'lntv-plan-radio--selected' : ''; ?>">
                            <input type="radio" name="livenettv_plan" value="<?php echo esc_attr( $slug ); ?>" <?php checked( $slug, $pre_plan ); ?> required>
                            <span class="lntv-plan-radio__name"><?php echo esc_html( $plan['name'] ); ?></span>
                            <span class="lntv-plan-radio__price">$<?php echo esc_html( number_format( $plan['price'], 2 ) ); ?></span>
                            <span class="lntv-plan-radio__dur"><?php echo esc_html( $plan['duration_text'] ); ?></span>
                            <?php if ( ! empty( $plan['recommended'] ) ) : ?>
                                <span class="lntv-plan-radio__badge"><?php echo esc_html( $plan['badge'] ?? __( 'Recommended', 'livenettv-pro' ) ); ?></span>
                            <?php endif; ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Step 2: Crypto -->
            <div class="lntv-form-step">
                <div class="lntv-form-step__label">
                    <span class="lntv-step-num">2</span>
                    <?php esc_html_e( 'Choose Payment Method', 'livenettv-pro' ); ?>
                </div>
                <?php if ( empty( $wallets ) ) : ?>
                    <p class="lntv-alert lntv-alert--warning"><?php esc_html_e( 'No payment methods configured. Please contact support.', 'livenettv-pro' ); ?></p>
                <?php else : ?>
                    <div class="lntv-crypto-radios" data-wallets="<?php echo esc_attr( wp_json_encode( $wallets ) ); ?>">
                        <?php
                        $crypto_labels = array(
                            'usdt' => 'USDT',
                            'btc'  => 'Bitcoin (BTC)',
                            'eth'  => 'Ethereum (ETH)',
                            'bnb'  => 'BNB',
                        );
                        foreach ( $wallets as $key => $addr ) :
                        ?>
                            <label class="lntv-crypto-radio">
                                <input type="radio" name="livenettv_crypto" value="<?php echo esc_attr( $key ); ?>" required>
                                <span class="lntv-crypto-radio__dot lntv-crypto-dot--<?php echo esc_attr( $key ); ?>"></span>
                                <span class="lntv-crypto-radio__name"><?php echo esc_html( $crypto_labels[ $key ] ?? strtoupper( $key ) ); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <!-- Wallet address display -->
                    <div class="lntv-wallet-display" id="lntv-wallet-display" hidden>
                        <div class="lntv-wallet-display__label"><?php esc_html_e( 'Send payment to:', 'livenettv-pro' ); ?></div>
                        <div class="lntv-wallet-display__row">
                            <code class="lntv-wallet-address" id="lntv-wallet-address"></code>
                            <button type="button" class="lntv-copy-btn" id="lntv-copy-wallet">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                                <?php esc_html_e( 'Copy', 'livenettv-pro' ); ?>
                            </button>
                        </div>
                        <p class="lntv-wallet-display__note">
                            <?php esc_html_e( 'Send the exact amount for your selected plan. Crypto transactions cannot be reversed.', 'livenettv-pro' ); ?>
                        </p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Step 3: Upload -->
            <div class="lntv-form-step">
                <div class="lntv-form-step__label">
                    <span class="lntv-step-num">3</span>
                    <?php esc_html_e( 'Upload Payment Proof', 'livenettv-pro' ); ?>
                </div>
                <div class="lntv-upload-area" id="lntv-upload-area">
                    <input type="file" name="livenettv_screenshot" id="lntv-screenshot" accept="image/*" class="lntv-upload-input" required>
                    <label for="lntv-screenshot" class="lntv-upload-label">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/>
                            <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/>
                        </svg>
                        <span><?php esc_html_e( 'Click or drag screenshot here', 'livenettv-pro' ); ?></span>
                        <span class="lntv-upload-hint"><?php esc_html_e( 'JPG, PNG, WebP — max 5 MB', 'livenettv-pro' ); ?></span>
                    </label>
                    <div class="lntv-upload-preview" id="lntv-upload-preview"></div>
                </div>
                <div class="lntv-field">
                    <label for="lntv-txid"><?php esc_html_e( 'Transaction ID (TXID)', 'livenettv-pro' ); ?></label>
                    <input type="text" name="livenettv_txid" id="lntv-txid"
                           placeholder="<?php esc_attr_e( 'Paste your transaction hash', 'livenettv-pro' ); ?>"
                           required>
                </div>
            </div>

            <!-- Submit -->
            <button type="submit" class="lntv-btn lntv-btn--primary lntv-btn--full">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                <?php esc_html_e( 'Submit Payment', 'livenettv-pro' ); ?>
            </button>
            <p class="lntv-form-note"><?php esc_html_e( 'Membership activates after manual verification (24–48 hrs).', 'livenettv-pro' ); ?></p>
        </form>
        <?php
        return ob_get_clean();
    }

    // -------------------------------------------------------------------------
    // Auth Modal (wp_footer)
    // -------------------------------------------------------------------------

    public function render_auth_modal() {
        if ( is_user_logged_in() ) {
            return;
        }

        $google_auth       = new LiveNetTV_Pro_Google_Auth();
        $google_configured = $google_auth->is_configured();
        $pro_url           = $this->get_pro_page_url();

        $cap_a = wp_rand( 1, 12 );
        $cap_b = wp_rand( 1, 12 );

        $pp       = get_option( 'wp_page_for_privacy_policy' );
        $tos_id   = get_option( 'livenettv_pro_tos_page_id', 0 );
        $pp_link  = $pp     ? '<a href="' . esc_url( get_permalink( $pp ) ) . '" target="_blank">' . esc_html__( 'Privacy Policy', 'livenettv-pro' ) . '</a>' : esc_html__( 'Privacy Policy', 'livenettv-pro' );
        $tos_link = $tos_id ? '<a href="' . esc_url( get_permalink( $tos_id ) ) . '" target="_blank">' . esc_html__( 'Terms of Service', 'livenettv-pro' ) . '</a>' : esc_html__( 'Terms of Service', 'livenettv-pro' );
        ?>
        <div id="lntv-auth-modal" class="lntv-modal" role="dialog" aria-modal="true" hidden>
            <div class="lntv-modal__overlay" id="lntv-modal-overlay"></div>
            <div class="lntv-modal__box">
                <button class="lntv-modal__close" id="lntv-modal-close" aria-label="<?php esc_attr_e( 'Close', 'livenettv-pro' ); ?>">&#x2715;</button>

                <!-- ===== LOGIN PANEL ===== -->
                <div class="lntv-modal__panel" id="lntv-panel-login">
                    <h2 class="lntv-modal__title"><?php printf( esc_html__( 'Welcome to %s', 'livenettv-pro' ), esc_html( get_bloginfo( 'name' ) ) ); ?></h2>

                    <div id="lntv-login-error" class="lntv-form-error" hidden></div>

                    <?php if ( $google_configured ) : ?>
                        <button type="button" class="lntv-social-btn lntv-google-btn" id="lntv-google-login-btn">
                            <svg viewBox="0 0 24 24" width="20" height="20"><g transform="matrix(1,0,0,1,27.009,-39.238)"><path fill="#4285F4" d="M-3.264 51.509c0-.79-.07-1.54-.19-2.27H-14.754v4.51h6.47c-.23 1.24-.94 2.3-1.98 3.01v3.02h3.62c2.12-1.96 3.38-4.83 3.38-8.27z"/><path fill="#34A853" d="M-14.754 62.769c3.24 0 5.96-1.08 7.94-2.99l-3.62-2.92c-1.15.79-2.65 1.26-4.32 1.26-3.14 0-5.76-2.12-6.7-4.8h-3.72v3.11c1.97 4.19 6.4 6.34 10.44 6.34z"/><path fill="#FBBC05" d="M-21.454 53.379c-.24-.73-.37-1.51-.37-2.31s.13-1.58.37-2.31v-3.11h-3.72c-.85 1.78-1.33 3.78-1.33 5.86s.48 4.08 1.33 5.86l3.72-3.11z"/><path fill="#EA4335" d="M-14.754 44.099c1.91 0 3.64.66 4.99 1.9l3.21-3.21c-2.25-2.1-4.97-3.45-8.2-3.45-4.05 0-7.48 2.09-9.41 5.3l3.72 3.11c.94-2.68 3.56-5.65 5.69-3.65z"/></g></svg>
                            <?php esc_html_e( 'Sign in with Google', 'livenettv-pro' ); ?>
                        </button>
                        <div class="lntv-modal__or"><span><?php esc_html_e( 'OR', 'livenettv-pro' ); ?></span></div>
                    <?php endif; ?>

                    <form id="lntv-login-form" novalidate>
                        <div class="lntv-field">
                            <input type="text" name="log" placeholder="<?php esc_attr_e( 'Username or Email', 'livenettv-pro' ); ?>" autocomplete="username" required>
                        </div>
                        <div class="lntv-field">
                            <input type="password" name="pwd" placeholder="<?php esc_attr_e( 'Password', 'livenettv-pro' ); ?>" autocomplete="current-password" required>
                        </div>
                        <div class="lntv-terms-check">
                            <label>
                                <input type="checkbox" required>
                                <?php printf( wp_kses_post( __( 'I agree to the %1$s | %2$s', 'livenettv-pro' ) ), $tos_link, $pp_link ); ?>
                            </label>
                        </div>
                        <button type="submit" class="lntv-btn lntv-btn--primary lntv-btn--full lntv-btn--email">
                            <?php esc_html_e( 'Login with Username or Email', 'livenettv-pro' ); ?>
                        </button>
                    </form>

                    <p class="lntv-modal__switch">
                        <?php esc_html_e( "Don't have an account?", 'livenettv-pro' ); ?>
                        <button type="button" class="lntv-modal__link" id="lntv-goto-register"><?php esc_html_e( 'Sign Up', 'livenettv-pro' ); ?></button>
                    </p>
                </div>

                <!-- ===== REGISTER PANEL ===== -->
                <div class="lntv-modal__panel" id="lntv-panel-register" hidden>
                    <h2 class="lntv-modal__title"><?php esc_html_e( 'Sign Up', 'livenettv-pro' ); ?></h2>

                    <div id="lntv-register-error" class="lntv-form-error" hidden></div>

                    <form id="lntv-register-form" novalidate>
                        <div class="lntv-field">
                            <input type="text" name="user_login" placeholder="<?php esc_attr_e( 'Username', 'livenettv-pro' ); ?>" autocomplete="username" required>
                        </div>
                        <div class="lntv-field">
                            <input type="email" name="user_email" placeholder="<?php esc_attr_e( 'Email', 'livenettv-pro' ); ?>" autocomplete="email" required>
                        </div>
                        <div class="lntv-field">
                            <input type="password" name="user_pass" placeholder="<?php esc_attr_e( 'Password', 'livenettv-pro' ); ?>" autocomplete="new-password" required>
                        </div>
                        <div class="lntv-field">
                            <input type="password" name="user_pass2" placeholder="<?php esc_attr_e( 'Confirm Password', 'livenettv-pro' ); ?>" autocomplete="new-password" required>
                        </div>
                        <div class="lntv-field lntv-captcha-field">
                            <input type="number" name="captcha_answer" placeholder="<?php echo esc_attr( "$cap_a + $cap_b = ?" ); ?>" required>
                            <input type="hidden" name="captcha_correct" value="<?php echo esc_attr( $cap_a + $cap_b ); ?>">
                        </div>
                        <div class="lntv-terms-check">
                            <label>
                                <input type="checkbox" required>
                                <?php printf( wp_kses_post( __( 'I agree to the %1$s | %2$s', 'livenettv-pro' ) ), $tos_link, $pp_link ); ?>
                            </label>
                        </div>
                        <button type="submit" class="lntv-btn lntv-btn--primary lntv-btn--full">
                            <?php esc_html_e( 'Sign Up', 'livenettv-pro' ); ?>
                        </button>
                    </form>

                    <p class="lntv-modal__switch">
                        <?php esc_html_e( 'Already have an account?', 'livenettv-pro' ); ?>
                        <button type="button" class="lntv-modal__link" id="lntv-goto-login"><?php esc_html_e( 'Log In', 'livenettv-pro' ); ?></button>
                    </p>
                </div>

            </div>
        </div>
        <?php
    }

    // -------------------------------------------------------------------------
    // AJAX handlers
    // -------------------------------------------------------------------------

    public function ajax_wp_login() {
        check_ajax_referer( 'livenettv_pro_nonce', 'nonce' );

        $username = sanitize_text_field( $_POST['log'] ?? '' );
        $password = $_POST['pwd'] ?? '';
        $redirect = esc_url_raw( $_POST['redirect'] ?? '' );

        if ( empty( $username ) || empty( $password ) ) {
            wp_send_json_error( array( 'message' => __( 'Enter your username/email and password.', 'livenettv-pro' ) ) );
        }

        $user = wp_authenticate( $username, $password );
        if ( is_wp_error( $user ) ) {
            wp_send_json_error( array( 'message' => __( 'Invalid username or password.', 'livenettv-pro' ) ) );
        }

        wp_set_current_user( $user->ID );
        wp_set_auth_cookie( $user->ID, false );

        wp_send_json_success( array( 'redirect' => $redirect ?: $this->get_pro_page_url() ) );
    }

    public function ajax_wp_register() {
        check_ajax_referer( 'livenettv_pro_nonce', 'nonce' );

        if ( ! get_option( 'users_can_register' ) ) {
            wp_send_json_error( array( 'message' => __( 'User registration is disabled.', 'livenettv-pro' ) ) );
        }

        $username  = sanitize_user( $_POST['user_login']  ?? '' );
        $email     = sanitize_email( $_POST['user_email'] ?? '' );
        $password  = $_POST['user_pass']  ?? '';
        $password2 = $_POST['user_pass2'] ?? '';
        $cap_a     = absint( $_POST['captcha_answer']  ?? -1 );
        $cap_c     = absint( $_POST['captcha_correct'] ?? 0 );
        $redirect  = esc_url_raw( $_POST['redirect']   ?? '' );

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
        if ( $cap_a !== $cap_c ) {
            wp_send_json_error( array( 'message' => __( 'Incorrect verification code.', 'livenettv-pro' ) ) );
        }
        if ( username_exists( $username ) ) {
            wp_send_json_error( array( 'message' => __( 'Username already taken.', 'livenettv-pro' ) ) );
        }
        if ( email_exists( $email ) ) {
            wp_send_json_error( array( 'message' => __( 'Email already registered.', 'livenettv-pro' ) ) );
        }

        $uid = wp_create_user( $username, $password, $email );
        if ( is_wp_error( $uid ) ) {
            wp_send_json_error( array( 'message' => $uid->get_error_message() ) );
        }

        wp_set_current_user( $uid );
        wp_set_auth_cookie( $uid, false );

        wp_send_json_success( array( 'redirect' => $redirect ?: $this->get_pro_page_url() ) );
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
        return array_filter( array(
            'usdt' => get_option( 'livenettv_pro_wallet_usdt', '' ),
            'btc'  => get_option( 'livenettv_pro_wallet_btc',  '' ),
            'eth'  => get_option( 'livenettv_pro_wallet_eth',  '' ),
            'bnb'  => get_option( 'livenettv_pro_wallet_bnb',  '' ),
        ) );
    }

    private function get_pro_page_url() {
        $id = (int) get_option( 'livenettv_pro_pro_page_id', 0 );
        return $id ? get_permalink( $id ) : home_url( '/' );
    }
}
