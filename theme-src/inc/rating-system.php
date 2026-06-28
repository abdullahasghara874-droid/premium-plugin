<?php
/**
 * Singlo Rating System
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Enqueue scripts and localize AJAX URL
 */
function singlo_enqueue_rating_scripts() {
    wp_localize_script( 'jquery', 'singlo_ajax_obj', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' )
    ) );
}
add_action( 'wp_enqueue_scripts', 'singlo_enqueue_rating_scripts' );

/**
 * Handle AJAX rating request and auto-clear cache
 */
function singlo_handle_rating() {
    header( 'Cache-Control: no-cache, no-store, must-revalidate' );
    header( 'Pragma: no-cache' );
    header( 'Expires: 0' );

    check_ajax_referer( 'singlo-rating-nonce', 'nonce' );

    $post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
    $rating  = isset( $_POST['rating'] ) ? intval( $_POST['rating'] ) : 0;

    if ( $post_id <= 0 || get_post_status( $post_id ) !== 'publish' || $rating < 1 || $rating > 5 ) {
        wp_send_json_error( 'Invalid data' );
    }

    $current_avg = singlo_normalize_rating( get_post_meta( $post_id, '_singlo_app_rating_value', true ), 0 );
    $current_count = singlo_normalize_rating_count( get_post_meta( $post_id, '_singlo_app_rating_count', true ), 0 );

    $new_count = $current_count + 1;
    $new_avg = ( ( $current_avg * $current_count ) + $rating ) / $new_count;
    $new_avg = singlo_normalize_rating( $new_avg, 0 );

    // Update the database
    update_post_meta( $post_id, '_singlo_app_rating_value', $new_avg );
    update_post_meta( $post_id, '_singlo_app_rating_count', $new_count );

    // ==========================================
    // CACHE CLEARING LOGIC START
    // Automatically purge the cache for this specific post
    // ==========================================

    // 1. Core WordPress Cache
    clean_post_cache( $post_id );

    // 2. LiteSpeed Cache
    if ( has_action( 'litespeed_purge_post' ) ) {
        do_action( 'litespeed_purge_post', $post_id );
    }

    // 3. WP Rocket
    if ( function_exists( 'rocket_clean_post' ) ) {
        rocket_clean_post( $post_id );
    }

    // 4. W3 Total Cache
    if ( function_exists( 'w3tc_flush_post' ) ) {
        w3tc_flush_post( $post_id );
    }

    // 5. WP Super Cache
    if ( function_exists( 'wp_cache_post_change' ) ) {
        wp_cache_post_change( $post_id );
    }
    
    // 6. WP Fastest Cache
    if ( function_exists( 'wpfc_clear_post_cache_by_id' ) ) {
        wpfc_clear_post_cache_by_id( $post_id );
    }

    // ==========================================
    // CACHE CLEARING LOGIC END
    // ==========================================

    wp_send_json_success( [
        'new_avg' => $new_avg,
        'new_count' => $new_count,
        'message' => 'Thank you for your rating!'
    ] );
}
add_action( 'wp_ajax_singlo_rate_post', 'singlo_handle_rating' );
add_action( 'wp_ajax_nopriv_singlo_rate_post', 'singlo_handle_rating' );

/**
 * Output Star Rating HTML
 */
function singlo_display_star_rating( $post_id ) {
    $post_id = absint( $post_id );
    $rating_value = singlo_get_rating_value( $post_id );
    $rating_count = singlo_get_rating_count( $post_id );
    $app_name      = get_the_title( $post_id );
    $app_version  = get_post_meta( $post_id, '_singlo_app_version', true );
    $app_category = get_post_meta( $post_id, '_singlo_app_category', true );
    $app_icon      = get_the_post_thumbnail_url( $post_id, 'thumbnail' );

    if ( empty( $app_category ) ) $app_category = 'Application';
    $rating_width = min( 100, max( 0, ( (float) $rating_value / 5 ) * 100 ) );

    ?>
    <div class="wVqUob singlo-star-rating" data-post-id="<?php echo esc_attr( $post_id ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce('singlo-rating-nonce') ); ?>" itemscope itemtype="https://schema.org/SoftwareApplication">
        <meta itemprop="name" content="<?php echo esc_attr( $app_name ); ?>">
        <meta itemprop="applicationCategory" content="<?php echo esc_attr( $app_category ); ?>">
        <meta itemprop="operatingSystem" content="Android">
        <?php if ( $app_version ) : ?>
        <meta itemprop="softwareVersion" content="<?php echo esc_attr( $app_version ); ?>">
        <?php endif; ?>
        <?php if ( $app_icon ) : ?>
        <meta itemprop="image" content="<?php echo esc_url( $app_icon ); ?>">
        <?php endif; ?>

        <div class="ClM7O">
            <div itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating">
                <meta itemprop="ratingValue" content="<?php echo esc_attr( $rating_value ); ?>">
                <meta itemprop="bestRating" content="5">
                <meta itemprop="ratingCount" content="<?php echo esc_attr( $rating_count ); ?>">
                <div class="TT9eCd" aria-label="<?php echo esc_attr( sprintf( __( 'Rated %s stars out of five stars', 'singlo' ), $rating_value ) ); ?>">
                    <span class="singlo-rating-number"><?php echo esc_html( $rating_value ); ?></span> <i class="google-material-icons notranslate ERwvGb" aria-hidden="true" style="font-style: normal; margin-left: 2px;">★</i>
                </div>
            </div>
        </div>
        <div class="g1rdde">
            <span class="singlo-rating-count"><?php echo esc_html( $rating_count ); ?></span> <?php esc_html_e('reviews', 'singlo'); ?>
        </div>
        <div class="starblocks" aria-label="User ratings">
            <div class="stars-outer" style="font-size: 20px" data-rating="<?php echo esc_attr( $rating_value ); ?>">
                <div class="stars-inner" style="width: <?php echo esc_attr( $rating_width ); ?>%;"></div>
                <div class="stars-hover"></div>
                <div class="stars-interact">
                    <span data-val="1"></span>
                    <span data-val="2"></span>
                    <span data-val="3"></span>
                    <span data-val="4"></span>
                    <span data-val="5"></span>
                </div>
            </div>
        </div>
    </div>
    <?php
}
