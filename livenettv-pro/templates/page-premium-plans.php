<?php
/**
 * Template Name: Premium Plans Page
 *
 * @package LiveNetTV_Pro
 */

defined( 'ABSPATH' ) || exit;

get_header();

$membership = livenettv_pro()->get_membership();
$plans      = $membership->get_plans();
?>

<main id="primary" class="site-main lntv-premium-page">
    <div class="lntv-container">
        <?php if ( is_user_logged_in() && $membership->is_pro_user( get_current_user_id() ) ) : ?>
            <?php echo do_shortcode( '[livenettv_pro_status]' ); ?>
        <?php else : ?>
            <section class="lntv-hero-section">
                <div class="lntv-hero-content">
                    <h1 class="lntv-hero-title"><?php esc_html_e( 'Unlock Premium Features', 'livenettv-pro' ); ?></h1>
                    <p class="lntv-hero-subtitle"><?php esc_html_e( 'Get access to ad-free streaming and exclusive content', 'livenettv-pro' ); ?></p>
                </div>
            </section>

            <section class="lntv-plans-section">
                <h2 class="lntv-section-title"><?php esc_html_e( 'Choose Your Plan', 'livenettv-pro' ); ?></h2>
                <div class="lntv-plans-grid">
                    <?php foreach ( $plans as $slug => $plan ) : ?>
                        <div class="lntv-plan-card <?php echo ! empty( $plan['recommended'] ) ? 'lntv-plan-recommended' : ''; ?>">
                            <?php if ( ! empty( $plan['badge'] ) ) : ?>
                                <span class="lntv-plan-badge"><?php echo esc_html( $plan['badge'] ); ?></span>
                            <?php endif; ?>

                            <h3 class="lntv-plan-name"><?php echo esc_html( $plan['name'] ); ?></h3>

                            <div class="lntv-plan-pricing">
                                <span class="lntv-plan-price">
                                    <?php echo esc_html( number_format( $plan['price'], 2 ) ); ?>
                                </span>
                                <span class="lntv-plan-currency"><?php echo esc_html( $plan['currency'] ); ?></span>
                                <?php if ( $slug !== 'lifetime' ) : ?>
                                    <span class="lntv-plan-period">/<?php echo esc_html( strtolower( $plan['duration_text'] ) ); ?></span>
                                <?php else : ?>
                                    <span class="lntv-plan-period"> <?php esc_html_e( 'one-time', 'livenettv-pro' ); ?></span>
                                <?php endif; ?>
                            </div>

                            <?php if ( ! empty( $plan['save_percent'] ) ) : ?>
                                <p class="lntv-plan-save"><?php printf( esc_html__( 'Save %d%%', 'livenettv-pro' ), $plan['save_percent'] ); ?></p>
                            <?php endif; ?>

                            <p class="lntv-plan-duration"><?php echo esc_html( $plan['duration_text'] ); ?></p>

                            <ul class="lntv-plan-features">
                                <li><i class="fas fa-check-circle"></i> <?php esc_html_e( 'Ad-free streaming', 'livenettv-pro' ); ?></li>
                                <li><i class="fas fa-check-circle"></i> <?php esc_html_e( 'HD quality streams', 'livenettv-pro' ); ?></li>
                                <li><i class="fas fa-check-circle"></i> <?php esc_html_e( 'Priority support', 'livenettv-pro' ); ?></li>
                                <li><i class="fas fa-check-circle"></i> <?php esc_html_e( 'All channels unlocked', 'livenettv-pro' ); ?></li>
                            </ul>

                            <button class="lntv-subscribe-btn" data-plan="<?php echo esc_attr( $slug ); ?>">
                                <?php esc_html_e( 'Subscribe Now', 'livenettv-pro' ); ?>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <section class="lntv-features-section">
                <h2 class="lntv-section-title"><?php esc_html_e( 'Premium Benefits', 'livenettv-pro' ); ?></h2>
                <div class="lntv-features-grid">
                    <div class="lntv-feature-item">
                        <i class="fas fa-shield-alt lntv-feature-icon"></i>
                        <h3><?php esc_html_e( 'Ad-Free Experience', 'livenettv-pro' ); ?></h3>
                        <p><?php esc_html_e( 'Enjoy uninterrupted streaming with no ads blocking your content.', 'livenettv-pro' ); ?></p>
                    </div>
                    <div class="lntv-feature-item">
                        <i class="fas fa-hd lntv-feature-icon"></i>
                        <h3><?php esc_html_e( 'HD Quality', 'livenettv-pro' ); ?></h3>
                        <p><?php esc_html_e( 'Watch all channels in high definition quality for the best experience.', 'livenettv-pro' ); ?></p>
                    </div>
                    <div class="lntv-feature-item">
                        <i class="fas fa-headset lntv-feature-icon"></i>
                        <h3><?php esc_html_e( 'Priority Support', 'livenettv-pro' ); ?></h3>
                        <p><?php esc_html_e( 'Get faster response times and dedicated support team access.', 'livenettv-pro' ); ?></p>
                    </div>
                    <div class="lntv-feature-item">
                        <i class="fas fa-unlock lntv-feature-icon"></i>
                        <h3><?php esc_html_e( 'All Channels', 'livenettv-pro' ); ?></h3>
                        <p><?php esc_html_e( 'Access to all premium channels without any restrictions.', 'livenettv-pro' ); ?></p>
                    </div>
                </div>
            </section>

            <section class="lntv-faq-section">
                <h2 class="lntv-section-title"><?php esc_html_e( 'Frequently Asked Questions', 'livenettv-pro' ); ?></h2>
                <div class="lntv-faq-container">
                    <div class="lntv-faq-item">
                        <button class="lntv-faq-question">
                            <?php esc_html_e( 'How do I pay with cryptocurrency?', 'livenettv-pro' ); ?>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="lntv-faq-answer">
                            <p><?php esc_html_e( 'Select your preferred cryptocurrency (USDT, BTC, ETH, or BNB) and send the equivalent amount to the provided wallet address. Upload a screenshot of your transaction and enter the transaction ID.', 'livenettv-pro' ); ?></p>
                        </div>
                    </div>
                    <div class="lntv-faq-item">
                        <button class="lntv-faq-question">
                            <?php esc_html_e( 'How long does approval take?', 'livenettv-pro' ); ?>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="lntv-faq-answer">
                            <p><?php esc_html_e( 'Payment approvals are typically processed within 24 hours. You will receive an email notification once your payment is approved.', 'livenettv-pro' ); ?></p>
                        </div>
                    </div>
                    <div class="lntv-faq-item">
                        <button class="lntv-faq-question">
                            <?php esc_html_e( 'Can I get a refund?', 'livenettv-pro' ); ?>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="lntv-faq-answer">
                            <p><?php esc_html_e( 'Due to the nature of cryptocurrency payments, refunds are not available. Please make sure you want to subscribe before making a payment.', 'livenettv-pro' ); ?></p>
                        </div>
                    </div>
                    <div class="lntv-faq-item">
                        <button class="lntv-faq-question">
                            <?php esc_html_e( 'What happens when my subscription expires?', 'livenettv-pro' ); ?>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="lntv-faq-answer">
                            <p><?php esc_html_e( 'You will receive warning emails before your subscription expires. After expiration, you will lose access to premium features. You can renew anytime to restore access.', 'livenettv-pro' ); ?></p>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif; ?>
    </div>
</main>

<?php
get_footer();
