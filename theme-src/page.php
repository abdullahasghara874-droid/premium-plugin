<?php
add_filter('wpseo_json_ld_output', '__return_false');
add_filter('rank_math/json_ld', function ($data, $jsonld) {
    unset($data);
    return $data;
}, 99, 2);

get_header();
?>

<?php
$request_update_url = get_theme_mod('singlo_request_update_url', '#');
$telegram_url       = get_theme_mod('singlo_telegram_url', '#');

$post_id = get_the_ID();
$post_layout = get_post_meta($post_id, '_singlo_post_layout', true);

// Check if this is a regular page without a custom layout
$is_standard_page = is_page() && empty($post_layout);

if ($is_standard_page) {
    // Standard WordPress page layout
    ?>
    <main id="main" class="site-main" role="main">
        <div class="ct-container">
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                </header>
                <div class="entry-content">
                    <?php
                    while (have_posts()) :
                        the_post();
                        the_content();

                        wp_link_pages(array(
                            'before' => '<div class="page-links">' . esc_html__('Pages:', 'singlo'),
                            'after'  => '</div>',
                        ));
                    endwhile;
                    ?>
                </div>
            </article>
            <?php
            if (comments_open() || get_comments_number()) :
                comments_template();
            endif;
            ?>
        </div>
    </main>
    <?php
    get_footer();
    return;
}

// If we have a custom post layout, use the app/guide templates below
if (empty($post_layout)) {
    $post_layout = 'app';
}
$rating_value = get_post_meta($post_id, '_singlo_app_rating_value', true);
$rating_count = get_post_meta($post_id, '_singlo_app_rating_count', true);
$version      = get_post_meta($post_id, '_singlo_app_version', true);
$size         = get_post_meta($post_id, '_singlo_app_size', true);
$downloads    = get_post_meta($post_id, '_singlo_app_downloads', true);
$developer    = get_post_meta($post_id, '_singlo_app_developer', true);
$package_name = get_post_meta($post_id, '_singlo_app_package_name', true);
$app_category = get_post_meta($post_id, '_singlo_app_category', true);
$min_os       = get_post_meta($post_id, '_singlo_app_min_os', true);
$supported_devices = get_post_meta($post_id, '_singlo_app_supported_devices', true);
$real_downloads = get_post_meta($post_id, '_singlo_download_counts', true);
$categories   = get_the_category();
$category     = !empty($categories) ? $categories[0]->name : 'Uncategorized';
$category_link = !empty($categories) ? get_category_link($categories[0]->term_id) : '#';

$rating_value = singlo_normalize_rating($rating_value);
$rating_count = singlo_normalize_rating_count($rating_count);
if (empty($package_name)) $package_name = 'N/A';
if (empty($app_category)) $app_category = $category;
if (empty($min_os)) $min_os = 'Android 5.0+';
if (empty($real_downloads)) $real_downloads = $downloads ?: '0';
$views_text = singlo_get_post_views_text($post_id);
?>
<main id="main" class="site-main hfeed" itemscope="itemscope" itemtype="https://schema.org/CreativeWork">
    <div data-block="single:4637" data-block-structure="custom" data-vertical-spacing="top:bottom">
        <div class="ct-container" data-sidebar="right">
            <article id="post-4637" class="post-2228 post type-post status-publish format-standard has-post-thumbnail hentry category-top-apps">
                <div class="entry-content is-layout-constrained">
                    <?php if ($post_layout === 'app') : ?>
                        <div data-block="hook:3469" class="alignfull">
                            <article id="post-3469" class="post-3469">
                                <div class="entry-content is-layout-constrained">
                                    <?php
                                    $header_telegram = get_theme_mod('singlo_telegram_url', '');
                                    $header_youtube  = get_theme_mod('singlo_youtube_url', '');

                                    if (! empty($header_telegram) || ! empty($header_youtube)) :
                                    ?>
                                        <div class="wp-block-kadence-advancedbtn kb-buttons-wrap kb-btns3469_161c0d-e6">
                                            <?php if (! empty($header_telegram)) : ?>
                                                <a class="kb-button kt-button button kb-btn3469_fff293-a1 kt-btn-size-large kt-btn-width-type-auto kb-btn-global-inherit  kt-btn-has-text-true kt-btn-has-svg-true  wp-block-button__link wp-block-kadence-singlebtn" href="<?php echo esc_url($telegram_url); ?>" target="_blank" rel="noreferrer noopener nofollow">
                                                    <span class="kb-svg-icon-wrap kb-svg-icon-fa_telegram-plane kt-btn-icon-side-left">
                                                        <svg viewBox="0 0 448 512" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                            <path d="M446.7 98.6l-67.6 318.8c-5.1 22.5-18.4 28.1-37.3 17.5l-103-75.9-49.7 47.8c-5.5 5.5-10.1 10.1-20.7 10.1l7.4-104.9 190.9-172.5c8.3-7.4-1.8-11.5-12.9-4.1L117.8 284 16.2 252.2c-22.1-6.9-22.5-22.1 4.6-32.7L418.2 66.4c18.4-6.9 34.5 4.1 28.5 32.2z"></path>
                                                        </svg>
                                                    </span>
                                                    <span class="kt-btn-inner-text">Join Us On Telegram</span>
                                                </a>
                                            <?php endif; ?>

                                            <?php if (! empty($header_youtube)) : ?>
                                                <a class="kb-button kt-button button kb-btn3469_4310a4-3d kt-btn-size-large kt-btn-width-type-auto kb-btn-global-inherit  kt-btn-has-text-true kt-btn-has-svg-true  wp-block-button__link wp-block-kadence-singlebtn" href="<?php echo esc_url($header_youtube); ?>" target="_blank" rel="noreferrer noopener nofollow">
                                                    <span class="kb-svg-icon-wrap kb-svg-icon-fe_youtube kt-btn-icon-side-left">
                                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                            <path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"></path>
                                                            <polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon>
                                                        </svg>
                                                    </span>
                                                    <span class="kt-btn-inner-text">Join Us On Youtube</span>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </article>
                        </div>
                        <div class="dzkqwc">
                            <div class="wkMJlb YWi3ub">
                                <div class="Mqg6jb Mhrnjf">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <?php the_post_thumbnail(array(160, 160), array('class' => 'T75of nm4vBd arM4bb', 'itemprop' => 'image', 'fetchpriority' => 'high')); ?>
                                    <?php endif; ?>
                                </div>
                                <div class="hnnXjf">
                                    <div class="Il7kR">
                                        <div class="RhBWnf">
                                            <?php if (has_post_thumbnail()) : ?>
                                                <?php the_post_thumbnail(array(160, 160), array('class' => 'T75of cN0oRe fFmL2e', 'itemprop' => 'image')); ?>
                                            <?php endif; ?>
                                            <div>
                                                <div class="Fd93Bb F5UCq xwcR9d">
                                                    <h1 style="margin-block-end: auto;"><span class="AfwdI" itemprop="name"><?php the_title(); ?></span></h1>
                                                </div>
                                                <div class="tv4jIf">
                                                    <div class="Vbfug auoIOc">
                                                        <a href="<?php echo esc_url($category_link); ?>">
                                                            <span><?php echo esc_html($category); ?></span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="JU1wdd">
                                        <div class="l8YSdd">
                                            <div class="w7Iutd">
                                                <?php singlo_display_star_rating($post_id); ?>
                                                <div class="wVqUob">
                                                    <div class="ClM7O">
                                                        <?php echo esc_html($downloads); ?>
                                                    </div>
                                                    <div class="g1rdde">
                                                        <svg width="16" height="16" style="margin-right:6px; vertical-align:middle;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" role="img" aria-label="Downloads icon">
                                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                            <polyline points="7 10 12 15 17 10"></polyline>
                                                            <line x1="12" y1="15" x2="12" y2="3"></line>
                                                        </svg> Downloads
                                                    </div>
                                                </div>
                                                <div class="wVqUob">
                                                    <div class="ClM7O">
                                                         v<?php echo esc_html($version); ?>
                                                    </div>
                                                    <?php if ($supported_devices === 'MOBILE') : ?>
                                                        <div class="gam-thumbnail-label gam-label-mobile" title="Works on Mobile Only">Mobile</div>
                                                    <?php elseif ($supported_devices === 'TV') : ?>
                                                        <div class="gam-thumbnail-label gam-label-tv" title="Works on TV Only">TV</div>
                                                    <?php elseif ($supported_devices === 'MOBILE_AND_TV') : ?>
                                                        <div class="gam-thumbnail-label gam-label-both" title="Works on TV &amp; Mobile">TV &amp; Mob</div>
                                                    <?php endif; ?>
                                                    <div class="g1rdde">
                                                        <svg width="16" height="16" style="margin-right:6px; vertical-align:middle;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" role="img" aria-label="Version icon">
                                                            <path d="M12 20v-6m0-4V4m0 0L8 8m4-4 4 4"></path>
                                                        </svg> Version
                                                    </div>
                                                </div>
                                                <div class="wVqUob">
                                                    <div class="ClM7O"><?php echo esc_html($size); ?></div>
                                                    <div class="g1rdde">
                                                        <svg width="16" height="16" style="margin-right:6px; vertical-align:middle;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" role="img" aria-label="File size icon">
                                                            <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                                                            <path d="M8 3v18"></path>
                                                        </svg> Size
                                                    </div>
                                                </div>
                                                <div class="wVqUob">
                                                    <div class="ClM7O"><?php echo esc_html(singlo_format_count(singlo_get_post_views($post_id))); ?></div>
                                                    <div class="g1rdde">
                                                        <svg width="16" height="16" style="margin-right:6px; vertical-align:middle;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" role="img" aria-label="Views icon">
                                                            <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"></path>
                                                            <circle cx="12" cy="12" r="3"></circle>
                                                        </svg> Views
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                $download_url = get_permalink() . 'download/';
                                ?>
                                <div class="kk2r5b">
                                    <div class="edaMIf">
                                        <div class="bGJWSe">
                                            <div class="VAgTTd LMcLV">
                                                <div>
                                                    <div class="u4ICaf">
                                                        <div class="VfPpkd-dgl2Hf-ppHlrf-sM5MNb" data-is-touch-wrapper="true">
                                                            <div id="download-container" style="min-width: 200px;" oncontextmenu="return false;" data-download-url="<?php echo esc_url($download_url); ?>">
                                                                <a id="initialBtn" class="ga-button ga-btn_2db7d5-c7 ga-btn-size-standard ga-btn-width-type-full ga-btn-global-fill ga-btn-has-text-true ga-btn-has-svg-true" style="cursor: pointer;">
                                                                    <span class="ga-svg-icon-wrap ga-svg-icon-fe_downloadCloud ga-btn-icon-side-left">
                                                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                                            <polyline points="8 17 12 21 16 17"></polyline>
                                                                            <line x1="12" y1="12" x2="12" y2="21"></line>
                                                                            <path d="M20.88 18.09A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.29"></path>
                                                                        </svg>
                                                                    </span>
                                                                    <span class="ga-btn-inner-text">DOWNLOAD (<?php echo esc_html($size); ?>)</span>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if ($request_update_url && $request_update_url !== '#') : ?>
                                        <div class="OBVQ7">
                                            <div oncontextmenu="return false;" class="wp-block-kadence-advancedbtn kb-buttons-wrap kb-btns1970_1b6dd2-fe">
                                                <a class="kb-button kt-button button kb-btn1970_0480ae-c7 kt-btn-size-standard kt-btn-width-type-auto kb-btn-global-inherit kt-btn-has-text-true kt-btn-has-svg-true wp-block-button__link wp-block-kadence-singlebtn" href="<?php echo esc_url($request_update_url); ?>" target="_blank" rel="noreferrer noopener nofollow">
                                                    <span class="kb-svg-icon-wrap kb-svg-icon-fe_refreshCw kt-btn-icon-side-left">
                                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                            <polyline points="23 4 23 10 17 10"></polyline>
                                                            <polyline points="1 20 1 14 7 14"></polyline>
                                                            <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                                                        </svg>
                                                    </span>
                                                    <span class="kt-btn-inner-text">Request Update</span>
                                                </a>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="wkMJlb YWi3ub">
                            <div class="nRgZne">
                                <div ssk="8:ZnF4ie-0">
                                    <?php
                                    $screenshots = get_post_meta(get_the_ID(), '_singlo_app_screenshots', true);
                                    if (!empty($screenshots) && is_array($screenshots)) :
                                    ?>
                                        <div class="aap-screenshots-container">
                                            <div class="aap-screenshots-wrapper">
                                                <button class="aap-scroll-btn aap-prev" aria-label="Previous">
                                                    <svg viewBox="0 0 24 24">
                                                        <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z" />
                                                    </svg>
                                                </button>
                                                <div class="aap-screenshots-scroll" id="aap-ss-scroll">
                                                    <?php foreach ($screenshots as $index => $url) : ?>
                                                        <div class="aap-screenshot-item">
                                                            <img <?php echo $index === 0 ? 'fetchpriority="high"' : ''; ?> width="236" height="512" decoding="async" src="<?php echo esc_url($url); ?>" class="T75of B5GQxf" alt="<?php the_title_attribute(); ?> Screenshot" itemprop="image" role="button" tabindex="0" loading="lazy" data-fancybox="gallery">
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                                <button class="aap-scroll-btn aap-next" aria-label="Next">
                                                    <svg viewBox="0 0 24 24">
                                                        <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        <script>
                                            document.addEventListener('DOMContentLoaded', function() {
                                                const scrollContainer = document.getElementById('aap-ss-scroll');
                                                const nextBtn = document.querySelector('.aap-next');
                                                const prevBtn = document.querySelector('.aap-prev');

                                                if (scrollContainer && nextBtn && prevBtn) {
                                                    nextBtn.addEventListener('click', () => {
                                                        scrollContainer.scrollBy({
                                                            left: 300,
                                                            behavior: 'smooth'
                                                        });
                                                    });
                                                    prevBtn.addEventListener('click', () => {
                                                        scrollContainer.scrollBy({
                                                            left: -300,
                                                            behavior: 'smooth'
                                                        });
                                                    });
                                                }
                                            });
                                        </script>
                                    <?php endif; ?>
                                    <section class="HcyOxe">
                                        <header class=" cswwxf">
                                            <div class="VMq4uf">
                                                <div class="EaMWib">
                                                    <h2 class="XfZNbf">About this app</h2>
                                                </div>
                                            </div>
                                        </header>
                                        <div class="SfzRHd">
                                            <div class="bARER post-description" data-g-id="description">
                                                <div class="description-content">
                                                    <?php the_content(); ?>
                                                </div>
                                                <div class="read-more-wrapper">
                                                    <button class="read-more-btn">
                                                        <span class="text">Read More ↓</span>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="TKjAsc">
                                                <div>
                                                    <div class="lXlx5">Updated on</div>
                                                    <div class="xg1aie"><?php echo get_the_modified_date(); ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                    <section class="HcyOxe">
                                        <header class=" cswwxf">
                                            <div class="VMq4uf">
                                                <div class="EaMWib">
                                                    <h2 class="XfZNbf">App Info</h2>
                                                </div>
                                            </div>
                                        </header>
                                        <figure class="wp-block-table is-style-stripes">
                                            <table itemscope="" itemtype="https://schema.org/SoftwareApplication">
                                                <tbody>
                                                    <tr itemprop="name" content="<?php the_title_attribute(); ?>">
                                                        <td>
                                                            <strong>
                                                                <svg width="16" height="16" style="margin-right:6px; vertical-align:middle;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" role="img">
                                                                    <rect x="3" y="3" width="7" height="7"></rect>
                                                                    <rect x="14" y="3" width="7" height="7"></rect>
                                                                    <rect x="14" y="14" width="7" height="7"></rect>
                                                                    <rect x="3" y="14" width="7" height="7"></rect>
                                                                </svg>
                                                                App Name
                                                            </strong>
                                                        </td>
                                                        <td><?php the_title(); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <strong>
                                                                <svg width="16" height="16" style="margin-right:6px; vertical-align:middle;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                    <path d="M16.5 9.4l-9-5.19M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                                                </svg>
                                                                Package Name
                                                            </strong>
                                                        </td>
                                                        <td itemprop="identifier"><?php echo esc_html($package_name); ?></td>
                                                    </tr>
                                                    <tr itemprop="applicationCategory" content="<?php echo esc_attr($app_category); ?>">
                                                        <td>
                                                            <strong>
                                                                <svg width="16" height="16" style="margin-right:6px; vertical-align:middle;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" role="img">
                                                                    <path d="M4 6h16M4 12h8M4 18h16"></path>
                                                                </svg>
                                                                Category
                                                            </strong>
                                                        </td>
                                                        <td><?php echo esc_html($app_category); ?></td>
                                                    </tr>
                                                    <tr itemprop="softwareVersion" content="<?php echo esc_attr($version); ?>">
                                                        <td>
                                                            <strong>
                                                                <svg width="16" height="16" style="margin-right:6px; vertical-align:middle;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" role="img">
                                                                    <path d="M12 20v-6m0-4V4m0 0L8 8m4-4 4 4"></path>
                                                                </svg>
                                                                Version
                                                            </strong>
                                                        </td>
                                                        <td><?php echo esc_html($version); ?></td>
                                                    </tr>
                                                    <tr itemprop="operatingSystem" content="<?php echo esc_attr($min_os); ?>">
                                                        <td>
                                                            <strong>
                                                                <svg width="16" height="16" style="margin-right:6px; vertical-align:middle;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" role="img">
                                                                    <path d="M16 18a2 2 0 0 0 2-2V7H6v9a2 2 0 0 0 2 2h8zM12 2v2"></path>
                                                                </svg>
                                                                Minimum OS
                                                            </strong>
                                                        </td>
                                                        <td><?php echo esc_html($min_os); ?></td>
                                                    </tr>
                                                    <tr itemprop="fileSize" content="<?php echo esc_attr($size); ?>">
                                                        <td>
                                                            <strong>
                                                                <svg width="16" height="16" style="margin-right:6px; vertical-align:middle;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" role="img">
                                                                    <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                                                                    <path d="M8 3v18"></path>
                                                                </svg>
                                                                Size
                                                            </strong>
                                                        </td>
                                                        <td><?php echo esc_html($size); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <strong>
                                                                <svg width="16" height="16" style="margin-right:6px; vertical-align:middle;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" role="img">
                                                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                                    <polyline points="7 10 12 15 17 10"></polyline>
                                                                    <line x1="12" y1="15" x2="12" y2="3"></line>
                                                                </svg>
                                                                Downloads
                                                            </strong>
                                                        </td>
                                                        <td itemprop="interactionStatistic" itemscope="" itemtype="https://schema.org/InteractionCounter">
                                                            <meta itemprop="interactionType" content="https://schema.org/DownloadAction">
                                                            <meta itemprop="userInteractionCount" content="<?php echo esc_attr(str_replace('+', '', $downloads)); ?>">
                                                            <?php echo esc_html($downloads); ?>
                                                        </td>
                                                    </tr>
                                                    <tr itemprop="dateModified" content="<?php echo get_the_modified_date('Y-m-d'); ?>">
                                                        <td>
                                                            <strong>
                                                                <svg width="16" height="16" style="margin-right:6px; vertical-align:middle;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" role="img">
                                                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                                                </svg>
                                                                Updated on
                                                            </strong>
                                                        </td>
                                                        <td><?php echo get_the_modified_date('F j, Y'); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <strong>
                                                                <svg width="16" height="16" style="margin-right:6px; vertical-align:middle;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" role="img">
                                                                    <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"></path>
                                                                    <circle cx="12" cy="12" r="3"></circle>
                                                                </svg>
                                                                Views
                                                            </strong>
                                                        </td>
                                                        <td><?php echo esc_html($views_text); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <strong>
                                                                <svg width="16" height="16" style="margin-right:6px; vertical-align:middle;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" role="img">
                                                                    <polygon points="12 2 15 9 22 9 17 14 19 21 12 17 5 21 7 14 2 9 9 9"></polygon>
                                                                </svg>
                                                                Ratings
                                                            </strong>
                                                        </td>
                                                        <td>
                                                            <div class="starblocks" itemprop="aggregateRating" itemscope="" itemtype="https://schema.org/AggregateRating">
                                                                <meta itemprop="ratingValue" content="<?php echo esc_attr($rating_value); ?>">
                                                                <meta itemprop="ratingCount" content="<?php echo esc_attr($rating_count); ?>">
                                                                <?php echo esc_html($rating_value); ?> ★ / 5
                                                                (<span itemprop="reviewCount"><?php echo esc_html($rating_count); ?></span> reviews)
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </figure>
                                    </section>
                                    <?php
                                    $changelogs = get_post_meta(get_the_ID(), '_singlo_app_changelogs', true);
                                    if (! empty($changelogs) && is_array($changelogs)) :
                                    ?>
                                        <section class="HcyOxe">
                                            <header class=" cswwxf">
                                                <div class="VMq4uf">
                                                    <div class="EaMWib">
                                                        <h2 class="XfZNbf"><?php printf(__('Detailed Changelog for %s', 'singlo'), get_the_title()); ?></h2>
                                                    </div>
                                                </div>
                                            </header>
                                            <div class="SfzRHd">
                                                <div class="singlo-changelog-entries">
                                                    <?php foreach ($changelogs as $log) :
                                                        $log_version = isset($log['version']) ? $log['version'] : '';
                                                        $log_date    = isset($log['date']) ? $log['date'] : '';
                                                        $log_note    = isset($log['note']) ? $log['note'] : '';
                                                    ?>
                                                        <div class="changelog-entry" style="margin-bottom: 25px; padding-bottom: 20px; border-bottom: 1px dashed #e2e8f0;">
                                                            <div class="changelog-entry-header" style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                                                                <span style="background: #4f46e5; color: white; padding: 4px 12px; border-radius: 20px; font-size: 14px; font-weight: 700;">v<?php echo esc_html($log_version); ?></span>
                                                                <?php if ($log_date) : ?>
                                                                    <span style="color: #64748b; font-size: 13px; font-weight: 500;">(Released: <?php echo date_i18n(get_option('date_format'), strtotime($log_date)); ?>)</span>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="changelog-entry-content" itemprop="description">
                                                                <?php echo wpautop($log_note); ?>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </section>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php elseif ($post_layout === 'guide') : ?>
                        <div class="hero-section is-width-constrained" data-type="type-1">
                            <header class="entry-header">
                                <div data-block="hook:3469">
                                    <article id="post-3469" class="post-3469">
                                        <div class="entry-content is-layout-constrained">
                                            <?php
                                            $header_telegram = get_theme_mod('singlo_telegram_url', '');
                                            $header_youtube  = get_theme_mod('singlo_youtube_url', '');

                                            if (! empty($header_telegram) || ! empty($header_youtube)) :
                                            ?>
                                                <div class="wp-block-kadence-advancedbtn kb-buttons-wrap kb-btns3469_161c0d-e6">
                                                    <?php if (! empty($header_telegram)) : ?>
                                                        <a class="kb-button kt-button button kb-btn3469_fff293-a1 kt-btn-size-large kt-btn-width-type-auto kb-btn-global-inherit  kt-btn-has-text-true kt-btn-has-svg-true  wp-block-button__link wp-block-kadence-singlebtn" href="<?php echo esc_url($telegram_url); ?>" target="_blank" rel="noreferrer noopener nofollow">
                                                            <span class="kb-svg-icon-wrap kb-svg-icon-fa_telegram-plane kt-btn-icon-side-left">
                                                                <svg viewBox="0 0 448 512" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                                    <path d="M446.7 98.6l-67.6 318.8c-5.1 22.5-18.4 28.1-37.3 17.5l-103-75.9-49.7 47.8c-5.5 5.5-10.1 10.1-20.7 10.1l7.4-104.9 190.9-172.5c8.3-7.4-1.8-11.5-12.9-4.1L117.8 284 16.2 252.2c-22.1-6.9-22.5-22.1 4.6-32.7L418.2 66.4c18.4-6.9 34.5 4.1 28.5 32.2z"></path>
                                                                </svg>
                                                            </span>
                                                            <span class="kt-btn-inner-text">Join Us On Telegram</span>
                                                        </a>
                                                    <?php endif; ?>

                                                    <?php if (! empty($header_youtube)) : ?>
                                                        <a class="kb-button kt-button button kb-btn3469_4310a4-3d kt-btn-size-large kt-btn-width-type-auto kb-btn-global-inherit  kt-btn-has-text-true kt-btn-has-svg-true  wp-block-button__link wp-block-kadence-singlebtn" href="<?php echo esc_url($header_youtube); ?>" target="_blank" rel="noreferrer noopener nofollow">
                                                            <span class="kb-svg-icon-wrap kb-svg-icon-fe_youtube kt-btn-icon-side-left">
                                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                                    <path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"></path>
                                                                    <polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon>
                                                                </svg>
                                                            </span>
                                                            <span class="kt-btn-inner-text">Join Us On Youtube</span>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </article>
                                </div>
                                <h1 class="page-title" itemprop="headline"><?php the_title(); ?></h1>
                                <ul class="entry-meta" data-type="icons:slash">
                                    <li class="meta-categories" data-type="simple">
                                        <svg width="20" height="20" viewBox="0,0,384,512">
                                            <path d="M128.3 160v32h32v-32zm64-96h-32v32h32zm-64 32v32h32V96zm64 32h-32v32h32zm177.6-30.1L286 14C277 5 264.8-.1 252.1-.1H48C21.5 0 0 21.5 0 48v416c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48V131.9c0-12.7-5.1-25-14.1-34zM256 51.9l76.1 76.1H256zM336 464H48V48h79.7v16h32V48H208v104c0 13.3 10.7 24 24 24h104zM194.2 265.7c-1.1-5.6-6-9.7-11.8-9.7h-22.1v-32h-32v32l-19.7 97.1C102 385.6 126.8 416 160 416c33.1 0 57.9-30.2 51.5-62.6zm-33.9 124.4c-17.9 0-32.4-12.1-32.4-27s14.5-27 32.4-27 32.4 12.1 32.4 27-14.5 27-32.4 27zm32-198.1h-32v32h32z"></path>
                                        </svg>
                                        <a href="<?php echo esc_url($category_link); ?>" rel="tag"><?php echo esc_html($category); ?></a>
                                    </li>
                                    <li class="meta-updated-date" itemprop="dateModified">
                                        <svg width="20" height="20" viewBox="0,0,512,512">
                                            <path d="M296 384h-80c-13.3 0-24-10.7-24-24V192h-87.7c-17.8 0-26.7-21.5-14.1-34.1L242.3 5.7c7.5-7.5 19.8-7.5 27.3 0l152.2 152.2c12.6 12.6 3.7 34.1-14.1 34.1H320v168c0 13.3-10.7 24-24 24zm216-8v112c0 13.3-10.7 24-24 24H24c-13.3 0-24-10.7-24-24V376c0-13.3 10.7-24 24-24h136v8c0 30.9 25.1 56 56 56h80c30.9 0 56-25.1 56-56v-8h136c13.3 0 24 10.7 24 24zm-124 88c0-11-9-20-20-20s-20 9-20 20 9 20 20 20 20-9 20-20zm64 0c0-11-9-20-20-20s-20 9-20 20 9 20 20 20 20-9 20-20z"></path>
                                        </svg>
                                        <time class="ct-meta-element-date" datetime="<?php echo get_the_modified_date('c'); ?>"><?php echo get_the_modified_date(); ?></time>
                                    </li>
                                    <li class="meta-views singlo-meta-views">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                            <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                        <span><?php echo esc_html($views_text); ?></span>
                                    </li>
                                </ul>
                            </header>
                        </div>
                        <div class="entry-content is-layout-flow">
                            <?php if (has_post_thumbnail()) : ?>
                                <figure class="wp-block-image aligncenter size-full is-resized">
                                    <?php the_post_thumbnail('medium', array('style' => 'width:164px;height:auto', 'class' => 'wp-image-thumbnail')); ?>
                                </figure>
                            <?php endif; ?>
                            <?php the_content(); ?>
                        </div>
                    <?php endif; ?>
                    <div style="margin-top: var(--margin, 25px);"></div>
                    <div data-block="hook:1970" class="alignfull">
                        <article id="post-1970" class="post-1970">
                            <div class="entry-content is-layout-constrained">
                                <p style="font-size:10px"><em><strong>Legal Copyright Disclaimer</strong>: As has been noted, streaming copyrighted content is unlawful and could get you into legal trouble. On account of this, we do not condone the streaming of copyrighted content. Therefore, check your local laws for streaming content online before doing so. Consequently, the information on this website is for general information and educational purposes only.&nbsp;</em></p>
                                <div class="wp-block-kadence-advancedbtn kb-buttons-wrap kb-btns1970_1b6dd2-fe">
                                    <?php if ($post_layout === 'app' && $request_update_url && $request_update_url !== '#') : ?>
                                        <a class="kb-button kt-button button kb-btn1970_0480ae-c7 kt-btn-size-standard kt-btn-width-type-auto kb-btn-global-inherit  kt-btn-has-text-true kt-btn-has-svg-true  wp-block-button__link wp-block-kadence-singlebtn" href="<?php echo esc_url($request_update_url); ?>" target="_blank" rel="noreferrer noopener nofollow">
                                            <span class="kb-svg-icon-wrap kb-svg-icon-fe_refreshCw kt-btn-icon-side-left">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                    <polyline points="23 4 23 10 17 10"></polyline>
                                                    <polyline points="1 20 1 14 7 14"></polyline>
                                                    <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                                                </svg>
                                            </span>
                                            <span class="kt-btn-inner-text">Request Update</span>
                                        </a>
                                    <?php endif; ?>
                                    <a class="kb-button kt-button button kb-btn1970_e688c2-8b kt-btn-size-standard kt-btn-width-type-auto kb-btn-global-inherit  kt-btn-has-text-true kt-btn-has-svg-true  wp-block-button__link wp-block-kadence-singlebtn" href="<?php echo esc_url($telegram_url); ?>" target="_blank" rel="noreferrer noopener nofollow">
                                        <span class="kb-svg-icon-wrap kb-svg-icon-fa_telegram-plane kt-btn-icon-side-left">
                                            <svg viewBox="0 0 448 512" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                <path d="M446.7 98.6l-67.6 318.8c-5.1 22.5-18.4 28.1-37.3 17.5l-103-75.9-49.7 47.8c-5.5 5.5-10.1 10.1-20.7 10.1l7.4-104.9 190.9-172.5c8.3-7.4-1.8-11.5-12.9-4.1L117.8 284 16.2 252.2c-22.1-6.9-22.5-22.1 4.6-32.7L418.2 66.4c18.4-6.9 34.5 4.1 28.5 32.2z"></path>
                                            </svg>
                                        </span>
                                        <span class="kt-btn-inner-text">Join Us On Telegram</span>
                                    </a>
                                </div>
                            </div>
                        </article>
                    </div>
                    <div style="margin-top: var(--margin, 50px);"></div>
                    <?php
                    $share_url   = urlencode(get_permalink());
                    $share_title = urlencode(get_the_title());
                    $share_text  = ($post_layout === 'guide') ? $share_title : urlencode(get_the_title() . ' Free Download For Android');
                    ?>
                    <div class="ct-share-box" data-type="type-1">
                        <span class="ct-module-title">Share This Article!</span>
                        <div data-icons-type="simple">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url; ?>" data-network="facebook" aria-label="Facebook" rel="noopener noreferrer nofollow">
                                <span class="ct-tooltip">Share on Facebook</span><span class="ct-icon-container">
                                    <svg width="20px" height="20px" viewBox="0 0 20 20" aria-hidden="true">
                                        <path d="M20,10.1c0-5.5-4.5-10-10-10S0,4.5,0,10.1c0,5,3.7,9.1,8.4,9.9v-7H5.9v-2.9h2.5V7.9C8.4,5.4,9.9,4,12.2,4c1.1,0,2.2,0.2,2.2,0.2v2.5h-1.3c-1.2,0-1.6,0.8-1.6,1.6v1.9h2.8L13.9,13h-2.3v7C16.3,19.2,20,15.1,20,10.1z"></path>
                                    </svg>
                                </span>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url=<?php echo $share_url; ?>&text=<?php echo $share_text; ?>" data-network="twitter" aria-label="X (Twitter)" rel="noopener noreferrer nofollow">
                                <span class="ct-tooltip">Share on X (Twitter)</span><span class="ct-icon-container">
                                    <svg width="20px" height="20px" viewBox="0 0 20 20" aria-hidden="true">
                                        <path d="M2.9 0C1.3 0 0 1.3 0 2.9v14.3C0 18.7 1.3 20 2.9 20h14.3c1.6 0 2.9-1.3 2.9-2.9V2.9C20 1.3 18.7 0 17.1 0H2.9zm13.2 3.8L11.5 9l5.5 7.2h-4.3l-3.3-4.4-3.8 4.4H3.4l5-5.7-5.3-6.7h4.4l3 4 3.5-4h2.1zM14.4 15 6.8 5H5.6l7.7 10h1.1z"></path>
                                    </svg>
                                </span>
                            </a>
                            <a href="https://pinterest.com/pin/create/button/?url=<?php echo $share_url; ?>&description=<?php echo $share_text; ?>" data-network="pinterest" aria-label="Pinterest" rel="noopener noreferrer nofollow">
                                <span class="ct-tooltip">Share on Pinterest</span><span class="ct-icon-container">
                                    <svg width="20px" height="20px" viewBox="0 0 20 20" aria-hidden="true">
                                        <path d="M10,0C4.5,0,0,4.5,0,10c0,4.1,2.5,7.6,6,9.2c0-0.7,0-1.5,0.2-2.3c0.2-0.8,1.3-5.4,1.3-5.4s-0.3-0.6-0.3-1.6c0-1.5,0.9-2.6,1.9-2.6c0.9,0,1.3,0.7,1.3,1.5c0,0.9-0.6,2.3-0.9,3.5c-0.3,1.1,0.5,1.9,1.6,1.9c1.9,0,3.2-2.4,3.2-5.3c0-2.2-1.5-3.8-4.2-3.8c-3,0-4.9,2.3-4.9,4.8c0,0.9,0.3,1.5,0.7,2C6,12,6.1,12.1,6,12.4c0,0.2-0.2,0.6-0.2,0.8c-0.1,0.3-0.3,0.3-0.5,0.3c-1.4-0.6-2-2.1-2-3.8c0-2.8,2.4-6.2,7.1-6.2c3.8,0,6.3,2.8,6.3,5.7c0,3.9-2.2,6.9-5.4,6.9c-1.1,0-2.1-0.6-2.4-1.2c0,0-0.6,2.3-0.7,2.7c-0.2,0.8-0.6,1.5-1,2.1C8.1,19.9,9,20,10,20c5.5,0,10-4.5,10-10C20,4.5,15.5,0,10,0z"></path>
                                    </svg>
                                </span>
                            </a>
                            <a href="https://t.me/share/url?url=<?php echo $share_url; ?>&text=<?php echo $share_text; ?>" data-network="telegram" aria-label="Telegram" rel="noopener noreferrer nofollow">
                                <span class="ct-tooltip">Share on Telegram</span><span class="ct-icon-container">
                                    <svg width="20px" height="20px" viewBox="0 0 20 20" aria-hidden="true">
                                        <path d="M19.9,3.1l-3,14.2c-0.2,1-0.8,1.3-1.7,0.8l-4.6-3.4l-2.2,2.1c-0.2,0.2-0.5,0.5-0.9,0.5l0.3-4.7L16.4,5c0.4-0.3-0.1-0.5-0.6-0.2L5.3,11.4L0.7,10c-1-0.3-1-1,0.2-1.5l17.7-6.8C19.5,1.4,20.2,1.9,19.9,3.1z"></path>
                                    </svg>
                                </span>
                            </a>
                            <a href="whatsapp://send?text=<?php echo $share_text; ?>%20<?php echo $share_url; ?>" data-network="whatsapp" aria-label="WhatsApp" rel="noopener noreferrer nofollow">
                                <span class="ct-tooltip">Share on WhatsApp</span><span class="ct-icon-container">
                                    <svg width="20px" height="20px" viewBox="0 0 20 20" aria-hidden="true">
                                        <path d="M10,0C4.5,0,0,4.5,0,10c0,1.9,0.5,3.6,1.4,5.1L0.1,20l5-1.3C6.5,19.5,8.2,20,10,20c5.5,0,10-4.5,10-10S15.5,0,10,0zM6.6,5.3c0.2,0,0.3,0,0.5,0c0.2,0,0.4,0,0.6,0.4c0.2,0.5,0.7,1.7,0.8,1.8c0.1,0.1,0.1,0.3,0,0.4C8.3,8.2,8.3,8.3,8.1,8.5C8,8.6,7.9,8.8,7.8,8.9C7.7,9,7.5,9.1,7.7,9.4c0.1,0.2,0.6,1.1,1.4,1.7c0.9,0.8,1.7,1.1,2,1.2c0.2,0.1,0.4,0.1,0.5-0.1c0.1-0.2,0.6-0.7,0.8-1c0.2-0.2,0.3-0.2,0.6-0.1c0.2,0.1,1.4,0.7,1.7,0.8s0.4,0.2,0.5,0.3c0.1,0.1,0.1,0.6-0.1,1.2c-0.2,0.6-1.2,1.1-1.7,1.2c-0.5,0-0.9,0.2-3-0.6c-2.5-1-4.1-3.6-4.2-3.7c-0.1-0.2-1-1.3-1-2.6c0-1.2,0.6-1.8,0.9-2.1C6.1,5.4,6.4,5.3,6.6,5.3z"></path>
                                    </svg>
                                </span>
                            </a>
                        </div>
                    </div>
                    <div style="margin-top: 60px;
  padding-top: 50px;
  border-top: 1px solid var(--theme-border-color);"></div>
                    <?php comments_template(); ?>

                    <div data-block="hook:4626">
                        <article id="post-4626" class="post-4626">
                            <div class="entry-content is-layout-constrained">
                                <?php
                                $categories = get_the_category();
                                if ($categories) {
                                    $target_category = null;

                                    foreach ($categories as $cat) {
                                        if ($cat->parent != 0) {
                                            $target_category = $cat->term_id;
                                            break;
                                        }
                                    }

                                    if (!$target_category) {
                                        $target_category = $categories[0]->term_id;
                                    }

                                    $args = array(
                                        'category__in' => array($target_category),
                                        'post__not_in' => array(get_the_ID()),
                                        'posts_per_page' => 6,
                                        'orderby' => 'rand'
                                    );

                                    $similar_query = new WP_Query($args);

                                    if ($similar_query->have_posts()) :
                                ?>
                                        <div class="aap_similar-apps-container" itemscope="" itemtype="https://schema.org/ItemList">
                                            <meta itemprop="name" content="Similar apps">

                                            <div class="aap_similar-apps-header">
                                                <span>Similar</span>
                                                <a href="<?php echo esc_url(get_category_link($target_category)); ?>" class="aap_arrow-link">→</a>
                                            </div>

                                            <div class="aap_similar-apps-grid">
                                                <?php
                                                $pos = 1;
                                                while ($similar_query->have_posts()) : $similar_query->the_post();
                                                    $s_rating = get_post_meta(get_the_ID(), '_singlo_app_rating_value', true);
                                                    $s_size   = get_post_meta(get_the_ID(), '_singlo_app_size', true);
                                                    if (empty($s_rating)) $s_rating = '4.5';
                                                    if (empty($s_size)) $s_size = 'N/A';
                                                ?>
                                                    <a href="<?php the_permalink(); ?>" class="aap_similar-app-item" itemscope="" itemtype="https://schema.org/ListItem" itemprop="itemListElement">
                                                        <meta itemprop="position" content="<?php echo $pos++; ?>">

                                                        <?php if (has_post_thumbnail()) : ?>
                                                            <?php the_post_thumbnail('thumbnail', array('alt' => get_the_title(), 'title' => get_the_title())); ?>
                                                        <?php endif; ?>

                                                        <div class="aap_app-details">
                                                            <span class="aap_app-name" itemprop="name"><?php the_title(); ?></span>
                                                            <div class="aap_app-size"><?php echo esc_html($s_size); ?></div>
                                                            <div class="aap_app-rating"><?php echo esc_html($s_rating); ?> ★</div>
                                                        </div>
                                                    </a>
                                                <?php endwhile;
                                                wp_reset_postdata(); ?>
                                            </div>
                                        </div>
                                <?php
                                    endif;
                                }
                                ?>

                            </div>
                        </article>
                    </div>
                </div>
            </article>
            <aside class="ct-hidden-sm ct-hidden-md" data-type="type-2" id="sidebar" itemtype="https://schema.org/WPSideBar" itemscope="itemscope">
                <div class="ct-sidebar" data-sticky="widgets" data-widgets="separated">
                    <?php
                    singlo_render_sidebar_trending_apps_widget(3);
                    singlo_render_sidebar_trending_posts_widget(5);
                    singlo_render_sidebar_recent_updates_widget(6);
                    ?>
                    <div class="ct-sticky-widgets">
                        <?php singlo_render_sidebar_recent_guides_widget(6); ?>
                    </div>
                    <?php if (false) : ?>
                    <div class="ct-widget is-layout-flow widget_block" id="block-27">
                        <div class="wp-block-group is-layout-constrained wp-block-group-is-layout-constrained">
                            <div class="aap-widget__titleTR">
                                <p class="aap-widget__title-text"><svg xmlns="http://www.w3.org/2000/svg" fill="currentcolor" width="17" height="15" viewBox="0 0 24 24">
                                        <path d="M7,2V13H10V22L17,10H13L17,2H7Z"></path>
                                    </svg><span><span class="aap-first-word">Trending</span> Now</span></p>
                            </div>
                            <p></p>
                            <div class="aap-trending-apps-container" itemscope="" itemtype="https://schema.org/ItemList">
                                <meta itemprop="name" content="Trending Apps">
                                <div class="aap-trending-apps-ngrid">
                                    <?php
                                    $trending_args = array(
                                        'post_type' => 'post',
                                        'meta_key' => 'wpb_post_views_count',
                                        'orderby' => 'meta_value_num',
                                        'order' => 'DESC',
                                        'posts_per_page' => 3
                                    );
                                    $trending_query = new WP_Query($trending_args);

                                    if (!$trending_query->have_posts()) {
                                        $trending_args = array(
                                            'post_type' => 'post',
                                            'posts_per_page' => 3,
                                            'orderby' => 'date',
                                            'order' => 'DESC'
                                        );
                                        $trending_query = new WP_Query($trending_args);
                                    }

                                    $t_pos = 1;
                                    while ($trending_query->have_posts()) : $trending_query->the_post();
                                        $t_rating = get_post_meta(get_the_ID(), '_singlo_app_rating_value', true);
                                        $t_size   = get_post_meta(get_the_ID(), '_singlo_app_size', true);
                                        $t_version = get_post_meta(get_the_ID(), '_singlo_app_version', true);
                                        if (empty($t_rating)) $t_rating = '4.5';
                                        if (empty($t_size)) $t_size = 'N/A';
                                    ?>
                                        <a href="<?php the_permalink(); ?>" class="aap-similar-app-item" itemscope="" itemtype="https://schema.org/ListItem" itemprop="itemListElement">
                                            <meta itemprop="position" content="<?php echo $t_pos++; ?>">
                                            <?php if (has_post_thumbnail()) : ?>
                                                <?php the_post_thumbnail('thumbnail', array('alt' => get_the_title(), 'title' => get_the_title() . ' v' . $t_version . ' (' . $t_size . ')')); ?>
                                            <?php endif; ?>
                                            <div class="aap-app-details">
                                                <span class="aap-app-name" itemprop="name"><?php the_title(); ?></span>
                                                <div class="aap-app-size"><?php echo esc_html($t_size); ?></div>
                                                <div class="aap-app-rating"><?php echo esc_html($t_rating); ?> ★</div>
                                            </div>
                                        </a>
                                    <?php endwhile;
                                    wp_reset_postdata(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ct-widget is-layout-flow widget_block" id="block-18">
                        <div class="wp-block-group is-layout-flow wp-block-group-is-layout-flow">
                            <div class="aap-widget__title">
                                <p class="aap-widget__title-text">
                                    <span class="aap-first-word">Recent</span> Updates
                                </p>
                                <div class="aap-widget__title-seperator"></div>
                            </div>
                            <ul class="aap-recent-list">
                                <?php
                                $recent_updates_args = array(
                                    'post_type' => 'post',
                                    'posts_per_page' => 6,
                                    'orderby' => 'modified',
                                    'order' => 'DESC'
                                );
                                $recent_updates_query = new WP_Query($recent_updates_args);
                                while ($recent_updates_query->have_posts()) : $recent_updates_query->the_post();
                                    $r_version = get_post_meta(get_the_ID(), '_singlo_app_version', true);
                                    $r_size    = get_post_meta(get_the_ID(), '_singlo_app_size', true);
                                ?>
                                    <li class="aap-hwguides">
                                        <strong>
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_title(); ?> <?php if ($r_version) echo 'v' . esc_html($r_version); ?> <?php if ($r_size) echo '(' . esc_html($r_size) . ')'; ?>
                                                <br><small>(Updated On: <?php echo get_the_modified_date('j M Y'); ?>)</small>
                                            </a>
                                        </strong>
                                    </li>
                                <?php endwhile;
                                wp_reset_postdata(); ?>
                            </ul>
                        </div>
                    </div>
                    <div class="ct-sticky-widgets">
                        <div class="ct-widget is-layout-flow widget_block" id="block-20">
                            <div class="wp-block-group is-layout-flow wp-block-group-is-layout-flow">
                                <div class="aap-widget__title">
                                    <p class="aap-widget__title-text">
                                        <span class="aap-first-word">Recent</span> Guides
                                    </p>
                                    <div class="aap-widget__title-seperator"></div>
                                </div>
                                <ul class="aap-recent-list">
                                    <?php
                                    $guide_cat = get_category_by_slug('guide');
                                    if ($guide_cat) {
                                        $guide_args = array(
                                            'post_type'      => 'post',
                                            'posts_per_page' => 6,
                                            'category__in'   => array($guide_cat->term_id),
                                            'post__not_in'   => array(get_the_ID()),
                                            'orderby'        => 'date',
                                            'order'          => 'DESC'
                                        );
                                        $guide_query = new WP_Query($guide_args);
                                        if ($guide_query->have_posts()) {
                                            while ($guide_query->have_posts()) {
                                                $guide_query->the_post();
                                    ?>
                                                <li class="aap-hwguides">
                                                    <strong><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></strong>
                                                </li>
                                    <?php
                                            }
                                            wp_reset_postdata();
                                        } else {
                                            echo '<li>No guides found.</li>';
                                        }
                                    } else {
                                        echo '<li>Guide category not found.</li>';
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </aside>
        </div>
    </div>
</main>

<?php
get_footer();
