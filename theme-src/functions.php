<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/* error_reporting(E_ALL);
ini_set('display_errors', 'on'); */
function singlo_setup() {
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
	) );
	add_theme_support( 'customize-selective-refresh-widgets' );

	register_nav_menus( array(
		'header' => esc_html__( 'Header Menu', 'singlo' ),
		'sub_header' => esc_html__( 'Sub Header Menu', 'singlo' ),
		'mobile_header' => esc_html__( 'Mobile Header Menu', 'singlo' ),
		'footer'  => esc_html__( 'Footer Menu', 'singlo' ),
	) );
}
add_action( 'after_setup_theme', 'singlo_setup' );

function singlo_scripts() {
	wp_enqueue_style( 'singlo-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap', array(), null );
	wp_enqueue_style( 'singlo-extras-min', get_template_directory_uri() . '/assets/css/extras.min.css', array(), '1.0.0' );
	wp_enqueue_style( 'singlo-main-min', get_template_directory_uri() . '/assets/css/main.min.css', array(), '1.0.0' );
	wp_enqueue_style( 'singlo-kedance', get_template_directory_uri() . '/assets/css/kedance.css', array(), '1.0.0' );
	wp_enqueue_style( 'singlo-style-block', get_template_directory_uri() . '/assets/css/style-block.css', array(), '1.0.0' );
	
	wp_enqueue_style( 'singlo-page-title', get_template_directory_uri() . '/assets/css/page-title.min.css', array(), '1.0.0' );
	wp_enqueue_style( 'singlo-sidebar', get_template_directory_uri() . '/assets/css/sidebar.min.css', array(), '1.0.0' );
	wp_enqueue_style( 'singlo-share-box', get_template_directory_uri() . '/assets/css/share-box.min.css', array(), '1.0.0' );
	wp_enqueue_style( 'singlo-comments', get_template_directory_uri() . '/assets/css/comments.min.css', array(), '1.0.0' );
	wp_enqueue_style( 'singlo-archive', get_template_directory_uri() . '/assets/css/archive.css', array(), '1.0.0' );
	wp_enqueue_style( 'singlo-search', get_template_directory_uri() . '/assets/css/search.css', array(), '1.0.0' );
	wp_enqueue_style( 'singlo-captcha', get_template_directory_uri() . '/assets/css/captcha.css', array(), '1.0.0' );
	wp_enqueue_style( 'singlo-search-input', get_template_directory_uri() . '/assets/css/search-input.min.css', array(), '1.0.0' );
	wp_enqueue_style( 'singlo-live-search', get_template_directory_uri() . '/assets/css/live-search.css', array(), '1.0.0' );
	wp_enqueue_style( 'singlo-divider', get_template_directory_uri() . '/assets/css/divider.min.css', array(), '1.0.0' );
	wp_enqueue_style( 'singlo-similar-apps', get_template_directory_uri() . '/assets/css/aap-similar-apps.css', array(), '1.0.0' );
	wp_enqueue_style( 'singlo-weekly-views', get_template_directory_uri() . '/assets/css/weekly-views.css', array(), '1.0.0' );
	wp_enqueue_style( 'singlo-recent-content', get_template_directory_uri() . '/assets/css/aap-recent-content.css', array(), '1.0.0' );
	wp_enqueue_style( 'singlo-rating', get_template_directory_uri() . '/assets/css/rating.css', array(), '1.0.0' );
	wp_enqueue_style( 'singlo-download', get_template_directory_uri() . '/assets/css/download.css', array(), '1.0.0' );
	wp_enqueue_style( 'singlo-read-more', get_template_directory_uri() . '/assets/css/read-more.css', array(), '1.0.0' );
	wp_enqueue_style( 'singlo-carousel', get_template_directory_uri() . '/assets/css/carousel.css', array(), '1.0.0' );
	wp_enqueue_style( 'singlo-accordion', get_template_directory_uri() . '/assets/css/accordion.css', array(), '1.0.0' );

	if ( is_front_page() ) {
		$homepage_css_path = get_template_directory() . '/assets/css/homepage.css';
		$homepage_version  = file_exists( $homepage_css_path ) ? filemtime( $homepage_css_path ) : '1.0.0';

		wp_enqueue_style(
			'singlo-homepage',
			get_template_directory_uri() . '/assets/css/homepage.css',
			array( 'singlo-main-min', 'singlo-sidebar', 'singlo-recent-content' ),
			$homepage_version
		);

		$homepage_script_path = get_template_directory() . '/assets/js/homepage-carousels.js';
		$homepage_script_version = file_exists( $homepage_script_path ) ? filemtime( $homepage_script_path ) : '1.0.0';

		wp_enqueue_script(
			'singlo-homepage-carousels',
			get_template_directory_uri() . '/assets/js/homepage-carousels.js',
			array(),
			$homepage_script_version,
			true
		);
	}
	

	$main_script_dependencies = array( 'jquery' );
	$load_singular_assets     = is_singular() && ! is_front_page();

	if ( $load_singular_assets ) {
		wp_enqueue_style( 'fancybox-5', 'https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css', array(), '5.0.0' );
		wp_enqueue_script( 'fancybox-5', 'https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js', array(), '5.0.0', true );
		$main_script_dependencies[] = 'fancybox-5';
	}

	wp_enqueue_script( 'singlo-main', get_template_directory_uri() . '/assets/js/main.js', $main_script_dependencies, '1.0.0', true );

	if ( $load_singular_assets ) {
		wp_enqueue_script( 'singlo-rating-hover', get_template_directory_uri() . '/assets/js/rating-hover.js', array( 'jquery' ), '1.0.2', true );
	}

	wp_enqueue_script( 'singlo-content-protection', get_template_directory_uri() . '/assets/js/content-protection.js', array(), '1.0.0', true );

	if ( $load_singular_assets ) {
		wp_add_inline_script( 'singlo-main', 'Fancybox.bind("[data-fancybox]", { Thumbs: false });' );
	}

    wp_localize_script( 'singlo-main', 'singlo_ajax_obj', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' )
    ) );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'singlo_scripts', 99 );

require get_template_directory() . '/inc/template-functions.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/meta-boxes.php';
require get_template_directory() . '/inc/rating-system.php';
require get_template_directory() . '/inc/theme-settings.php';
require get_template_directory() . '/inc/homepage-settings.php';

class Singlo_Desktop_Walker_Nav_Menu extends Walker_Nav_Menu {
    function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        
        // Remove 'menu-item-has-children' to prevent any submenu hover/display logic
        $classes = array_diff($classes, array('menu-item-has-children'));

        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

        $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
        $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

        $output .= '<li' . $id . $class_names . '>';

        $atts = array();
        $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
        $atts['target'] = ! empty( $item->target )     ? $item->target     : '';
        $atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
        $atts['href']   = ! empty( $item->url )        ? $item->url        : '';
        $atts['class']  = 'ct-menu-link';

        $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

        $attributes = '';
        foreach ( $atts as $attr => $value ) {
            if ( ! empty( $value ) ) {
                $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $title = apply_filters( 'the_title', $item->title, $item->ID );

        $item_output = $args->before;
        $item_output .= '<a'. $attributes .'>';
        $item_output .= $args->link_before . $title . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}

class Singlo_Mobile_Walker_Nav_Menu extends Walker_Nav_Menu {
    function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        $has_children = in_array( 'menu-item-has-children', $classes );

        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

        $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
        $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

        $output .= '<li' . $id . $class_names . '>';

        $atts = array();
        $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
        $atts['target'] = ! empty( $item->target )     ? $item->target     : '';
        $atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
        $atts['href']   = ! empty( $item->url )        ? $item->url        : '';
        $atts['class']  = 'ct-menu-link';

        $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

        $attributes = '';
        foreach ( $atts as $attr => $value ) {
            if ( ! empty( $value ) ) {
                $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $title = apply_filters( 'the_title', $item->title, $item->ID );

        $item_output = $args->before;

        if ( $has_children ) {
            $item_output .= '<span class="ct-sub-menu-parent">';
        }

        $item_output .= '<a'. $attributes .'>';
        $item_output .= $args->link_before . $title . $args->link_after;
        $item_output .= '</a>';

        if ( $has_children ) {
            $item_output .= '<button class="ct-toggle-dropdown-mobile" aria-label="Expand dropdown menu" aria-haspopup="true" aria-expanded="false">';
            $item_output .= '<svg class="ct-icon toggle-icon-2" width="15" height="15" viewBox="0 0 15 15" aria-hidden="true"><path d="M14.1,6.6H8.4V0.9C8.4,0.4,8,0,7.5,0S6.6,0.4,6.6,0.9v5.7H0.9C0.4,6.6,0,7,0,7.5s0.4,0.9,0.9,0.9h5.7v5.7C6.6,14.6,7,15,7.5,15s0.9-0.4,0.9-0.9V8.4h5.7C14.6,8.4,15,8,15,7.5S14.6,6.6,14.1,6.6z"></path></svg>';
            $item_output .= '</button>';
            $item_output .= '</span>';
        }

        $item_output .= $args->after;

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}

/**
 * Comment Callback for Singlo
 */
function singlo_comment_callback( $comment, $args, $depth ) {
    $tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
    ?>
    <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( 'ct-has-avatar' ); ?>>
        <article class="ct-comment-inner" id="ct-comment-inner-<?php comment_ID(); ?>" itemprop="comment" itemscope="" itemtype="https://schema.org/Comment">
            <footer class="ct-comment-meta">
                <figure class="ct-media-container">
                    <?php if ( $args['avatar_size'] != 0 ) echo get_avatar( $comment, $args['avatar_size'], '', '', array( 'class' => 'entered lazyloaded' ) ); ?>
                </figure>
                <h4 class="ct-comment-author" itemprop="author" itemscope="" itemtype="https://schema.org/Person">
                    <cite itemprop="name"><?php echo get_comment_author_link(); ?></cite>
                </h4>

                <div class="ct-comment-meta-data">
                    <a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>">
                        <time datetime="<?php comment_time( 'c' ); ?>" itemprop="datePublished">
                            <?php printf( _x( '%1$s / %2$s', '1: date, 2: time', 'singlo' ), get_comment_date(), get_comment_time() ); ?>
                        </time>
                    </a>

                    <?php
                    comment_reply_link( array_merge( $args, array(
                        'add_below' => 'ct-comment-inner',
                        'depth'     => $depth,
                        'max_depth' => $args['max_depth'],
                        'reply_text' => __( 'Reply', 'singlo' )
                    ) ) );
                    ?>
                </div>
            </footer>

            <div class="ct-comment-content entry-content is-layout-flow" itemprop="text">
                <?php if ( $comment->comment_approved == '0' ) : ?>
                    <em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'singlo' ); ?></em><br/>
                <?php endif; ?>
                <?php comment_text(); ?>
            </div>
        </article>
    <?php
}

/**
 * Math Captcha for Comments
 */
function singlo_get_math_captcha() {
    $num1 = rand(1, 10);
    $num2 = rand(1, 10);
    return array(
        'question' => "$num1 + $num2 = ?",
        'answer'   => $num1 + $num2
    );
}

function singlo_add_math_captcha_field() {
    $captcha = singlo_get_math_captcha();
    ?>
    <div id="page0x-captcha-container">
        <div class="comment-form-captcha">
            <label for="ga_math_captcha_answer">
                <strong>Security Check:</strong> Please solve this simple math problem:
            </label>
            <div class="captcha-question-wrapper">
                <span class="captcha-question"><?php echo $captcha['question']; ?></span>
                <input type="number" name="ga_math_captcha_answer" id="ga_math_captcha_answer" class="captcha-input" required="" autocomplete="off" placeholder="Your answer">
                <input type="hidden" name="ga_math_captcha_correct_answer" value="<?php echo $captcha['answer']; ?>">
            </div>
        </div>
    </div>
    <div class="captcha-error" style="display:none;color:#d63638;background:#fcf0f1;border:1px solid #f0b7b8;padding:8px;border-radius:4px;margin-top:5px;">
        ❌ Incorrect answer. Please try again.
    </div>
    <?php
}
add_action( 'comment_form_after_fields', 'singlo_add_math_captcha_field' );
add_action( 'comment_form_logged_in_after', 'singlo_add_math_captcha_field' );

function singlo_verify_math_captcha( $commentdata ) {
    if ( ! is_user_logged_in() || current_user_can( 'moderate_comments' ) === false ) {
        if ( isset( $_POST['ga_math_captcha_answer'] ) && isset( $_POST['ga_math_captcha_correct_answer'] ) ) {
            if ( intval( $_POST['ga_math_captcha_answer'] ) !== intval( $_POST['ga_math_captcha_correct_answer'] ) ) {
                wp_die( __( 'Error: Please solve the math problem correctly to post a comment.', 'singlo' ) );
            }
        } else {
            wp_die( __( 'Error: Please solve the math problem to post a comment.', 'singlo' ) );
        }
    }
    return $commentdata;
}
add_filter( 'preprocess_comment', 'singlo_verify_math_captcha' );

/**
 * Post views and lightweight content queries.
 */
function singlo_get_category_term_ids($slugs) {
    $ids = array();

    foreach ((array) $slugs as $slug) {
        $term = get_category_by_slug($slug);

        if (! $term) {
            continue;
        }

        $ids[] = (int) $term->term_id;
        $children = get_term_children($term->term_id, 'category');

        if (! is_wp_error($children)) {
            $ids = array_merge($ids, array_map('intval', $children));
        }
    }

    return array_values(array_unique(array_filter($ids)));
}

function singlo_get_app_category_ids() {
    $ids = singlo_get_category_term_ids(array('apps', 'top-apps'));

    if (empty($ids)) {
        $legacy_ids = array(70, 90, 89, 91, 88, 95, 66, 92, 93, 94, 56, 96);

        foreach ($legacy_ids as $term_id) {
            if (get_term($term_id, 'category') && ! is_wp_error(get_term($term_id, 'category'))) {
                $ids[] = (int) $term_id;
            }
        }
    }

    $guide_ids = singlo_get_guide_category_ids();

    if (! empty($guide_ids)) {
        $ids = array_diff($ids, $guide_ids);
    }

    return array_values(array_unique(array_filter($ids)));
}

function singlo_get_guide_category_ids() {
    return singlo_get_category_term_ids(array(
        'guide',
        'guides',
        'tutorial',
        'tutorials',
        'firestick-guide',
        'android-tv-google-tv-guide',
    ));
}

function singlo_add_meta_query_clause(&$args, $clause) {
    if (empty($clause)) {
        return;
    }

    if (empty($args['meta_query'])) {
        $args['meta_query'] = array($clause);
        return;
    }

    if (empty($args['meta_query']['relation'])) {
        $args['meta_query'] = array_merge(array('relation' => 'AND'), $args['meta_query']);
    }

    $args['meta_query'][] = $clause;
}

function singlo_apply_content_type_filter($args, $type) {
    $type = ('guide' === $type) ? 'guide' : 'app';
    $app_ids = singlo_get_app_category_ids();
    $guide_ids = singlo_get_guide_category_ids();

    if ('guide' === $type) {
        if (! empty($guide_ids)) {
            $args['category__in'] = $guide_ids;

            if (! empty($app_ids)) {
                $args['category__not_in'] = $app_ids;
            }

            singlo_add_meta_query_clause($args, array(
                'relation' => 'OR',
                array(
                    'key'     => '_singlo_post_layout',
                    'value'   => 'guide',
                    'compare' => '=',
                ),
                array(
                    'key'     => '_singlo_post_layout',
                    'compare' => 'NOT EXISTS',
                ),
                array(
                    'key'     => '_singlo_post_layout',
                    'value'   => '',
                    'compare' => '=',
                ),
            ));
        } else {
            singlo_add_meta_query_clause($args, array(
                'key'     => '_singlo_post_layout',
                'value'   => 'guide',
                'compare' => '=',
            ));
        }

        return $args;
    }

    if (! empty($app_ids)) {
        $args['category__in'] = $app_ids;

        if (! empty($guide_ids)) {
            $args['category__not_in'] = $guide_ids;
        }

        singlo_add_meta_query_clause($args, array(
            'relation' => 'OR',
            array(
                'key'     => '_singlo_post_layout',
                'value'   => 'app',
                'compare' => '=',
            ),
            array(
                'key'     => '_singlo_post_layout',
                'compare' => 'NOT EXISTS',
            ),
            array(
                'key'     => '_singlo_post_layout',
                'value'   => '',
                'compare' => '=',
            ),
        ));
    } else {
        singlo_add_meta_query_clause($args, array(
            'key'     => '_singlo_post_layout',
            'value'   => 'app',
            'compare' => '=',
        ));
    }

    return $args;
}

function singlo_get_content_query($type, $mode = 'recent', $limit = 6, $exclude_current = true) {
    $args = array(
        'post_type'           => 'post',
        'post_status'         => 'publish',
        'posts_per_page'      => absint($limit),
        'ignore_sticky_posts' => true,
        'no_found_rows'       => true,
    );

    if ($exclude_current && is_singular()) {
        $args['post__not_in'] = array(get_queried_object_id());
    }

    if ('trending' === $mode) {
        $args['meta_key'] = 'wpb_post_views_count';
        $args['orderby']  = 'meta_value_num';
        $args['order']    = 'DESC';
    } else {
        $args['orderby'] = 'modified';
        $args['order']   = 'DESC';
    }

    $args = singlo_apply_content_type_filter($args, $type);
    $query = new WP_Query($args);

    if (! $query->have_posts()) {
        $fallback_args = array(
            'post_type'           => array('post', 'page'),
            'post_status'         => 'publish',
            'posts_per_page'      => absint($limit),
            'ignore_sticky_posts' => true,
            'no_found_rows'       => true,
            'meta_query'          => array(
                array(
                    'key'     => '_singlo_post_layout',
                    'value'   => ('guide' === $type) ? 'guide' : 'app',
                    'compare' => '=',
                ),
            ),
        );

        if ($exclude_current && is_singular()) {
            $fallback_args['post__not_in'] = array(get_queried_object_id());
        }

        if ('trending' === $mode) {
            $fallback_args['meta_key'] = 'wpb_post_views_count';
            $fallback_args['orderby']  = 'meta_value_num';
            $fallback_args['order']    = 'DESC';
        } else {
            $fallback_args['orderby'] = 'modified';
            $fallback_args['order']   = 'DESC';
        }

        $query = new WP_Query($fallback_args);
    }

    if (! $query->have_posts() && 'app' === $type) {
        $app_meta_args = array(
            'post_type'           => array('post', 'page'),
            'post_status'         => 'publish',
            'posts_per_page'      => absint($limit),
            'ignore_sticky_posts' => true,
            'no_found_rows'       => true,
            'meta_query'          => array(
                'relation' => 'AND',
                array(
                    'key'     => '_singlo_app_version',
                    'compare' => 'EXISTS',
                ),
                array(
                    'relation' => 'OR',
                    array(
                        'key'     => '_singlo_post_layout',
                        'value'   => 'app',
                        'compare' => '=',
                    ),
                    array(
                        'key'     => '_singlo_post_layout',
                        'compare' => 'NOT EXISTS',
                    ),
                    array(
                        'key'     => '_singlo_post_layout',
                        'value'   => '',
                        'compare' => '=',
                    ),
                ),
            ),
        );

        if ($exclude_current && is_singular()) {
            $app_meta_args['post__not_in'] = array(get_queried_object_id());
        }

        if ('trending' === $mode) {
            $app_meta_args['meta_key'] = 'wpb_post_views_count';
            $app_meta_args['orderby']  = 'meta_value_num';
            $app_meta_args['order']    = 'DESC';
        } else {
            $app_meta_args['orderby'] = 'modified';
            $app_meta_args['order']   = 'DESC';
        }

        $query = new WP_Query($app_meta_args);
    }

    if ('trending' === $mode && ! $query->have_posts()) {
        $recent_fallback = singlo_get_content_query($type, 'recent', $limit, $exclude_current);

        if ($recent_fallback->have_posts()) {
            $query = $recent_fallback;
        }
    }

    return $query;
}

function singlo_get_trending_apps_query($limit = 20) {
    $limit = min(20, max(1, absint($limit)));

    return singlo_get_content_query('app', 'trending', $limit, false);
}

function singlo_normalize_rating($rating, $default = 4.5) {
    $rating = is_numeric($rating) ? (float) $rating : (float) $default;
    $rating = min(5, max(0, $rating));

    return rtrim(rtrim(number_format($rating, 2, '.', ''), '0'), '.');
}

function singlo_normalize_rating_count($count, $default = 100) {
    $count = is_numeric($count) ? (int) $count : (int) $default;

    return max(0, $count);
}

function singlo_get_rating_value($post_id) {
    return singlo_normalize_rating(get_post_meta($post_id, '_singlo_app_rating_value', true));
}

function singlo_get_rating_count($post_id) {
    return singlo_normalize_rating_count(get_post_meta($post_id, '_singlo_app_rating_count', true));
}

function singlo_format_count($count) {
    $count = max(0, (int) $count);

    if ($count >= 1000000) {
        return rtrim(rtrim(number_format($count / 1000000, 1), '0'), '.') . 'M';
    }

    if ($count >= 1000) {
        return rtrim(rtrim(number_format($count / 1000, 1), '0'), '.') . 'K';
    }

    return number_format_i18n($count);
}

function singlo_get_post_views($post_id = 0) {
    $post_id = $post_id ? absint($post_id) : get_the_ID();

    return max(0, (int) get_post_meta($post_id, 'wpb_post_views_count', true));
}

function singlo_get_post_views_text($post_id = 0) {
    $views = singlo_get_post_views($post_id);

    return sprintf(
        _n('%s view', '%s views', $views, 'singlo'),
        singlo_format_count($views)
    );
}

function apkt_set_post_views($post_id) {
    $post_id = absint($post_id);

    if (! $post_id) {
        return;
    }

    $meta_key = 'wpb_post_views_count';
    $count    = (int) get_post_meta($post_id, $meta_key, true);

    update_post_meta($post_id, $meta_key, $count + 1);
}

function singlo_is_probable_bot() {
    $agent = isset($_SERVER['HTTP_USER_AGENT']) ? strtolower(wp_unslash($_SERVER['HTTP_USER_AGENT'])) : '';

    if ('' === $agent) {
        return false;
    }

    return (bool) preg_match('/bot|crawl|spider|slurp|ahrefs|semrush|mj12|bingpreview|facebookexternalhit|whatsapp|telegram/i', $agent);
}

function apkt_track_post_views() {
    if (is_admin() || is_preview() || ! is_singular(array('post', 'page'))) {
        return;
    }

    $post_id = get_queried_object_id();

    if (! $post_id || 'publish' !== get_post_status($post_id)) {
        return;
    }

    if (is_front_page() || (is_user_logged_in() && current_user_can('edit_post', $post_id)) || singlo_is_probable_bot()) {
        return;
    }

    apkt_set_post_views($post_id);
}
add_action('template_redirect', 'apkt_track_post_views', 20);

function singlo_render_widget_title($first, $second, $is_trending = false) {
    $title_class = $is_trending ? 'aap-widget__titleTR' : 'aap-widget__title';
    ?>
    <div class="<?php echo esc_attr($title_class); ?>">
        <p class="aap-widget__title-text">
            <?php if ($is_trending) : ?>
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentcolor" width="17" height="15" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M7,2V13H10V22L17,10H13L17,2H7Z"></path>
                </svg>
            <?php endif; ?>
            <span><span class="aap-first-word"><?php echo esc_html($first); ?></span> <?php echo esc_html($second); ?></span>
        </p>
        <?php if (! $is_trending) : ?>
            <div class="aap-widget__title-seperator"></div>
        <?php endif; ?>
    </div>
    <?php
}

function singlo_render_trending_app_items($limit = 3) {
    $query = singlo_get_content_query('app', 'trending', $limit);

    if (! $query->have_posts()) {
        echo '<p class="aap-empty-widget">' . esc_html__('No trending apps found.', 'singlo') . '</p>';
        return;
    }

    $position = 1;

    while ($query->have_posts()) {
        $query->the_post();
        $post_id = get_the_ID();
        $rating  = singlo_get_rating_value($post_id);
        $size    = get_post_meta($post_id, '_singlo_app_size', true);
        $version = get_post_meta($post_id, '_singlo_app_version', true);
        ?>
        <a href="<?php the_permalink(); ?>" class="aap-similar-app-item" itemscope itemtype="https://schema.org/ListItem" itemprop="itemListElement">
            <meta itemprop="position" content="<?php echo esc_attr($position++); ?>">
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('thumbnail', array('alt' => get_the_title(), 'title' => get_the_title() . ($version ? ' v' . $version : '') . ($size ? ' (' . $size . ')' : ''))); ?>
            <?php endif; ?>
            <div class="aap-app-details">
                <span class="aap-app-name" itemprop="name"><?php the_title(); ?></span>
                <?php if ($size) : ?>
                    <div class="aap-app-size"><?php echo esc_html($size); ?></div>
                <?php endif; ?>
                <div class="aap-app-rating"><?php echo esc_html($rating); ?> &#9733;</div>
                <div class="aap-app-views"><?php echo esc_html(singlo_get_post_views_text($post_id)); ?></div>
            </div>
        </a>
        <?php
    }

    wp_reset_postdata();
}

function singlo_render_sidebar_recent_items($type = 'app', $limit = 6) {
    $type = ('guide' === $type) ? 'guide' : 'app';
    $query = singlo_get_content_query($type, 'recent', $limit);

    if (! $query->have_posts()) {
        echo '<li class="aap-empty-widget">' . esc_html__('No items found.', 'singlo') . '</li>';
        return;
    }

    while ($query->have_posts()) {
        $query->the_post();
        $post_id = get_the_ID();
        $version = get_post_meta($post_id, '_singlo_app_version', true);
        $size    = get_post_meta($post_id, '_singlo_app_size', true);
        ?>
        <li class="aap-hwguides">
            <strong>
                <a href="<?php the_permalink(); ?>">
                    <?php the_title(); ?>
                    <?php if ('app' === $type && $version) : ?> v<?php echo esc_html($version); ?><?php endif; ?>
                    <?php if ('app' === $type && $size) : ?> (<?php echo esc_html($size); ?>)<?php endif; ?>
                    <br><small><?php echo esc_html__('Updated On:', 'singlo'); ?> <?php echo esc_html(get_the_modified_date('j M Y')); ?> - <?php echo esc_html(singlo_get_post_views_text($post_id)); ?></small>
                </a>
            </strong>
        </li>
        <?php
    }

    wp_reset_postdata();
}

function singlo_render_sidebar_trending_posts_widget($limit = 5) {
    $query = singlo_get_content_query('guide', 'trending', $limit);
    ?>
    <div class="ct-widget is-layout-flow widget_block singlo-sidebar-widget singlo-trending-posts">
        <?php singlo_render_widget_title(__('Trending', 'singlo'), __('Posts', 'singlo'), false); ?>
        <ul class="aap-recent-list">
            <?php
            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    ?>
                    <li class="aap-hwguides">
                        <strong>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                                <br><small><?php echo esc_html(singlo_get_post_views_text(get_the_ID())); ?></small>
                            </a>
                        </strong>
                    </li>
                    <?php
                }
                wp_reset_postdata();
            } else {
                echo '<li class="aap-empty-widget">' . esc_html__('No trending posts found.', 'singlo') . '</li>';
            }
            ?>
        </ul>
    </div>
    <?php
}

function singlo_render_sidebar_trending_apps_widget($limit = 3) {
    ?>
    <div class="ct-widget is-layout-flow widget_block singlo-sidebar-widget singlo-trending-apps">
        <?php singlo_render_widget_title(__('Trending', 'singlo'), __('Apps', 'singlo'), true); ?>
        <div class="aap-trending-apps-container" itemscope itemtype="https://schema.org/ItemList">
            <meta itemprop="name" content="<?php echo esc_attr__('Trending Apps', 'singlo'); ?>">
            <div class="aap-trending-apps-ngrid">
                <?php singlo_render_trending_app_items($limit); ?>
            </div>
        </div>
    </div>
    <?php
}

function singlo_render_sidebar_recent_updates_widget($limit = 6) {
    ?>
    <div class="ct-widget is-layout-flow widget_block singlo-sidebar-widget singlo-recent-updates">
        <?php singlo_render_widget_title(__('Recent', 'singlo'), __('Updates', 'singlo'), false); ?>
        <ul class="aap-recent-list">
            <?php singlo_render_sidebar_recent_items('app', $limit); ?>
        </ul>
    </div>
    <?php
}

function singlo_render_sidebar_recent_guides_widget($limit = 6) {
    ?>
    <div class="ct-widget is-layout-flow widget_block singlo-sidebar-widget singlo-recent-guides">
        <?php singlo_render_widget_title(__('Recent', 'singlo'), __('Guides', 'singlo'), false); ?>
        <ul class="aap-recent-list">
            <?php singlo_render_sidebar_recent_items('guide', $limit); ?>
        </ul>
    </div>
    <?php
}

add_action('init', function () {
    add_rewrite_endpoint('download', EP_ALL);
});

add_action('template_redirect', function () {
    global $wp_query;

    if (!isset($wp_query->query_vars['download'])) {
        return;
    }

    $download_var = $wp_query->query_vars['download'];
    $template     = '';

    // Check if it's /download/{id}
    if (is_numeric($download_var)) {
        $GLOBALS['custom_download_id'] = (int) $download_var;
        $template = locate_template('template-parts/download.php');
    } else {
        $template = locate_template('template-parts/download.php');
    }

    if ($template) {
        include $template;
        exit;
    }
});

/**
 * AJAX Live Search with Thumbnails
 */
add_action( 'wp_ajax_singlo_live_search', 'singlo_live_search_handler' );
add_action( 'wp_ajax_nopriv_singlo_live_search', 'singlo_live_search_handler' );
function singlo_live_search_handler() {
    header( 'Cache-Control: no-cache, no-store, must-revalidate' );
    header( 'Pragma: no-cache' );
    header( 'Expires: 0' );

    if ( ! isset( $_GET['nonce'] ) || ! wp_verify_nonce( $_GET['nonce'], 'ct-live-results' ) ) {
        wp_send_json_error( 'Invalid nonce' );
    }

    $query = isset( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';

    if ( empty( $query ) || strlen( $query ) < 2 ) {
        wp_send_json_success( array( 'results' => array() ) );
        return;
    }

    $posts = get_posts( array(
        's'              => $query,
        'posts_per_page' => 8,
        'post_type'      => 'post',
        'post_status'    => 'publish',
    ) );

    $results = array();
    foreach ( $posts as $post ) {
        $thumbnail_url = get_the_post_thumbnail_url( $post->ID, array( 50, 50 ) );
        $version = get_post_meta( $post->ID, '_singlo_app_version', true );
        $size = get_post_meta( $post->ID, '_singlo_app_size', true );

        $results[] = array(
            'id'        => $post->ID,
            'title'     => get_the_title( $post->ID ),
            'url'       => get_permalink( $post->ID ),
            'thumbnail' => $thumbnail_url ? $thumbnail_url : '',
            'version'   => $version,
            'size'      => $size,
        );
    }

    wp_send_json_success( array( 'results' => $results ) );
}

/**
 * Increment Download Count via AJAX
 */
add_action( 'wp_ajax_singlo_increment_download_count', 'singlo_increment_download_count' );
add_action( 'wp_ajax_nopriv_singlo_increment_download_count', 'singlo_increment_download_count' );
function singlo_increment_download_count() {
    header( 'Cache-Control: no-cache, no-store, must-revalidate' );
    header( 'Pragma: no-cache' );
    header( 'Expires: 0' );

    $post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
    if ( $post_id ) {
        $count = (int) get_post_meta( $post_id, '_singlo_download_counts', true );
        update_post_meta( $post_id, '_singlo_download_counts', $count + 1 );
        wp_send_json_success( array( 'new_count' => $count + 1 ) );
    }
    wp_send_json_error();
}

/**
 * Handle Homepage Redirect
 */
add_action( 'template_redirect', function() {
    if ( is_front_page() || is_home() ) {
        $redirect_id = get_theme_mod( 'singlo_home_redirect_id', 0 );
        if ( $redirect_id && get_post_status( $redirect_id ) === 'publish' ) {
            wp_redirect( get_permalink( $redirect_id ), 301 );
            exit;
        }
    }
}, 1 );

/**
 * Use custom template for 'apps' category and its subcategories
 */
function singlo_subcategory_template( $template ) {
    if ( is_category() ) {
        $cat = get_queried_object();
        $apps_cat = get_category_by_slug('apps');
        if ( $apps_cat ) {
            if ( $cat->term_id == $apps_cat->term_id || cat_is_ancestor_of( $apps_cat->term_id, $cat->term_id ) ) {
                $custom_template = locate_template( 'category-apps.php' );
                if ( $custom_template ) {
                    return $custom_template;
                }
            }
        }
    }
    return $template;
}
add_filter( 'template_include', 'singlo_subcategory_template' );
/* Remove category base from URLs */
add_filter('category_link', function ($link) {
    return str_replace('/category/', '/', $link);
});

/* Add rewrite rules so WP understands clean category URLs */
add_action('init', function () {
    $categories = get_categories(['hide_empty' => false]);

    foreach ($categories as $cat) {
        // Rule for paginated category pages
        add_rewrite_rule(
            '^' . $cat->slug . '/page/?([0-9]{1,})/?$',
            'index.php?category_name=' . $cat->slug . '&paged=$matches[1]',
            'top'
        );

        // Rule for main category page
        add_rewrite_rule(
            '^' . $cat->slug . '/?$',
            'index.php?category_name=' . $cat->slug,
            'top'
        );
    }
});

/* Redirect old category URLs to new clean URLs (301) */
add_action('template_redirect', function () {
    if (is_category() && strpos($_SERVER['REQUEST_URI'], '/category/') !== false) {
        wp_redirect(
            home_url(str_replace('/category/', '/', $_SERVER['REQUEST_URI'])),
            301
        );
        exit;
    }
});

/**
 * Contact Form Handler Functions
 */

// Keep private service credentials in wp-config.php, never in a distributable theme.
if ( ! defined( 'SINGLO_TURNSTILE_SECRET_KEY' ) ) {
    define( 'SINGLO_TURNSTILE_SECRET_KEY', '' );
}

/**
 * SMTP Configuration for Email Delivery
 */
add_action('phpmailer_init', function($phpmailer) {
    if (
        ! defined( 'SINGLO_SMTP_USERNAME' )
        || ! defined( 'SINGLO_SMTP_PASSWORD' )
        || ! SINGLO_SMTP_USERNAME
        || ! SINGLO_SMTP_PASSWORD
    ) {
        return;
    }

    $phpmailer->isSMTP();
    $phpmailer->Host = defined( 'SINGLO_SMTP_HOST' ) ? SINGLO_SMTP_HOST : 'smtp.hostinger.com';
    $phpmailer->SMTPAuth = true;
    $phpmailer->Port = defined( 'SINGLO_SMTP_PORT' ) ? (int) SINGLO_SMTP_PORT : 587;
    $phpmailer->Username = SINGLO_SMTP_USERNAME;
    $phpmailer->Password = SINGLO_SMTP_PASSWORD;
    $phpmailer->SMTPSecure = defined( 'SINGLO_SMTP_SECURE' ) ? SINGLO_SMTP_SECURE : 'tls';
    $phpmailer->From = defined( 'SINGLO_SMTP_FROM_EMAIL' ) ? SINGLO_SMTP_FROM_EMAIL : SINGLO_SMTP_USERNAME;
    $phpmailer->FromName = get_bloginfo('name');
    $phpmailer->SMTPDebug = 0;
});

/**
 * Verify Cloudflare Turnstile Token
 */
function singlo_verify_turnstile($token) {
    if ( ! SINGLO_TURNSTILE_SECRET_KEY ) {
        return false;
    }

    $url = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
    
    $response = wp_remote_post($url, array(
        'body' => array(
            'secret' => SINGLO_TURNSTILE_SECRET_KEY,
            'response' => $token,
            'remoteip' => $_SERVER['REMOTE_ADDR']
        ),
        'timeout' => 10
    ));

    if (is_wp_error($response)) {
        return false;
    }

    $body = wp_remote_retrieve_body($response);
    $result = json_decode($body, true);

    return isset($result['success']) && $result['success'] === true;
}

/**
 * Handle Contact Form Submission via AJAX
 */
function singlo_contact_form_handler() {
    // Validate required fields
    if (empty($_POST['name'])) {
        wp_send_json_error(array('message' => 'Name is required'));
    }

    if (empty($_POST['email'])) {
        wp_send_json_error(array('message' => 'Email is required'));
    }

    if (!is_email($_POST['email'])) {
        wp_send_json_error(array('message' => 'Invalid email format'));
    }

    if (empty($_POST['message'])) {
        wp_send_json_error(array('message' => 'Message is required'));
    }

    if (empty($_POST['turnstile_token'])) {
        wp_send_json_error(array('message' => 'Security verification is required'));
    }

    // Verify Turnstile
    if (!singlo_verify_turnstile($_POST['turnstile_token'])) {
        wp_send_json_error(array('message' => 'Security verification failed. Please try again.'));
    }

    // Sanitize inputs
    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_email($_POST['email']);
    $subject = !empty($_POST['subject']) ? sanitize_text_field($_POST['subject']) : 'New Contact Form Submission';
    $message = sanitize_textarea_field($_POST['message']);

    // Additional validation
    if (strlen($name) < 2 || strlen($name) > 100) {
        wp_send_json_error(array('message' => 'Name must be between 2 and 100 characters'));
    }

    if (strlen($message) < 10 || strlen($message) > 5000) {
        wp_send_json_error(array('message' => 'Message must be between 10 and 5000 characters'));
    }

    // Prepare email using this site's own configured addresses.
    $to = defined( 'SINGLO_CONTACT_TO_EMAIL' ) && is_email( SINGLO_CONTACT_TO_EMAIL )
        ? SINGLO_CONTACT_TO_EMAIL
        : get_option( 'admin_email' );
    $from_email = defined( 'SINGLO_SMTP_FROM_EMAIL' ) && is_email( SINGLO_SMTP_FROM_EMAIL )
        ? SINGLO_SMTP_FROM_EMAIL
        : get_option( 'admin_email' );
    $email_subject = 'Contact Form: ' . $subject;
    
    $email_message = "You have received a new message from your website contact form.\n\n";
    $email_message .= "Name: " . $name . "\n";
    $email_message .= "Email: " . $email . "\n";
    $email_message .= "Subject: " . $subject . "\n\n";
    $email_message .= "Message:\n" . $message . "\n\n";
    $email_message .= "---\n";
    $email_message .= "IP Address: " . ( isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'Unknown' ) . "\n";
    $email_message .= "User Agent: " . ( isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : 'Unknown' ) . "\n";
    $email_message .= "Time: " . current_time('mysql') . "\n";

    $headers = array(
        'Content-Type: text/plain; charset=UTF-8',
        'From: ' . $from_email,
        'Reply-To: ' . $name . ' <' . $email . '>'
    );

    // Send email
    $sent = wp_mail($to, $email_subject, $email_message, $headers);

    if ($sent) {
        wp_send_json_success(array('message' => 'Thank you! Your message has been sent successfully.'));
    } else {
        global $phpmailer;
        if (isset($phpmailer->ErrorInfo) && !empty($phpmailer->ErrorInfo)) {
            error_log('Contact form email failed. SMTP Error: ' . $phpmailer->ErrorInfo . ' | Email: ' . $email);
        } else {
            error_log('Contact form email failed for: ' . $email);
        }
        wp_send_json_error(array('message' => 'Failed to send your message. Please try again later.'));
    }
}

// Register AJAX handlers
add_action('wp_ajax_singlo_contact_form', 'singlo_contact_form_handler');
add_action('wp_ajax_nopriv_singlo_contact_form', 'singlo_contact_form_handler');



/**
 * Register the category taxonomy for pages
 */
function add_categories_to_pages() {
    register_taxonomy_for_object_type('category', 'page');
}
add_action('init', 'add_categories_to_pages');

/**
 * Ensure the category metabox appears in the page editor
 */
function add_category_metabox_to_pages() {
    register_taxonomy_for_object_type('category', 'page');
}
add_action('admin_init', 'add_category_metabox_to_pages');

/**
 * Include pages in category archives
 * This allows pages to show up when viewing category archive pages
 */
function include_pages_in_category_query($query) {
    if (!is_admin() && $query->is_category() && $query->is_main_query()) {
        $query->set('post_type', array('post', 'page'));
    }
}
add_action('pre_get_posts', 'include_pages_in_category_query');

/**
 * Add category column to pages list in admin
 */
function add_category_column_to_pages($columns) {
    $columns['categories'] = 'Categories';
    return $columns;
}
add_filter('manage_pages_columns', 'add_category_column_to_pages');

/**
 * Display categories in the pages list column
 */
function display_page_categories_column($column_name, $post_id) {
    if ($column_name === 'categories') {
        $categories = get_the_category($post_id);
        if (!empty($categories)) {
            $category_names = array();
            foreach ($categories as $category) {
                $category_names[] = '<a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a>';
            }
            echo implode(', ', $category_names);
        } else {
            echo '—';
        }
    }
}
add_action('manage_pages_custom_column', 'display_page_categories_column', 10, 2);

/**
 * Make the categories column sortable
 */
function make_page_categories_column_sortable($columns) {
    $columns['categories'] = 'categories';
    return $columns;
}
add_filter('manage_edit-page_sortable_columns', 'make_page_categories_column_sortable');

