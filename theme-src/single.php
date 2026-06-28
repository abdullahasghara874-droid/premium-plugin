<?php
add_filter('wpseo_json_ld_output', '__return_false');
add_filter('rank_math/json_ld', function ($data, $jsonld) {
    unset($data);
    return $data;
}, 99, 2);

get_header();
?>
<div class="post-back-wrapper">
    <button type="button" class="post-back-pill" onclick="history.back();">
        <svg class="post-back-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <span>Go Back</span>
    </button>
</div>

<?php
$request_update_url = get_theme_mod('singlo_request_update_url', '#');
$telegram_url       = get_theme_mod('singlo_telegram_url', '#');

$post_id = get_the_ID();
$post_layout = get_post_meta($post_id, '_singlo_post_layout', true) ?: 'app';
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
                                    <?php if ($telegram_url && $telegram_url !== '#') : ?>
                                        <div class="OBVQ7">
                                            <div oncontextmenu="return false;" class="wp-block-kadence-advancedbtn kb-buttons-wrap kb-btns1970_1b6dd2-fe">
                                                <a class="kb-button kt-button button kb-btn1970_0480ae-c7 kt-btn-size-standard kt-btn-width-type-auto kb-btn-global-inherit kt-btn-has-text-true kt-btn-has-svg-true wp-block-button__link wp-block-kadence-singlebtn" href="<?php echo esc_url($telegram_url); ?>" target="_blank" rel="noreferrer noopener nofollow">
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
                                                            <img <?php echo $index === 0 ? 'fetchpriority="high"' : ''; ?> decoding="async" src="<?php echo esc_url($url); ?>" class="T75of B5GQxf" alt="<?php the_title_attribute(); ?> Screenshot" itemprop="image" role="button" tabindex="0" loading="lazy" data-fancybox="gallery">
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

                                        <div class="aap-taxonomy-section" style="margin-top: 16px; padding: 16px 0; border-top: 1px solid #e5e7eb;">
                                            <?php
                                            $post_tags = get_the_tags();
                                            $categories = get_the_category();
                                            ?>

                                            <?php if (!empty($categories)) : ?>
                                                <div class="aap-category-item" style="margin-bottom: 12px;">
                                                    <div style="display: flex; align-items: center; gap: 8px;">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: #6b7280;">
                                                            <path d="M4 6h16M4 12h8M4 18h16"></path>
                                                        </svg>
                                                        <span style="font-weight: 600; color: #374151; font-size: 14px;">Category:</span>
                                                        <?php foreach ($categories as $cat) : ?>
                                                            <a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>"
                                                               style="display: inline-flex; align-items: center; padding: 4px 12px; background: #f3f4f6; color: #1f2937; border-radius: 16px; font-size: 13px; text-decoration: none; transition: background 0.2s;">
                                                                <?php echo esc_html($cat->name); ?>
                                                            </a>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                            <?php if (!empty($post_tags)) : ?>
                                                <div class="aap-tags-item">
                                                    <div style="display: flex; align-items: flex-start; gap: 8px;">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: #6b7280; margin-top: 2px;">
                                                            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                                            <line x1="7" y1="7" x2="7.01" y2="7"></line>
                                                        </svg>
                                                        <span style="font-weight: 600; color: #374151; font-size: 14px;">Tags:</span>
                                                        <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                                                            <?php foreach ($post_tags as $tag) : ?>
                                                                <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>"
                                                                   style="display: inline-flex; align-items: center; padding: 4px 10px; background: #eff6ff; color: #1e40af; border-radius: 12px; font-size: 12px; text-decoration: none; transition: background 0.2s;">
                                                                    <?php echo esc_html($tag->name); ?>
                                                                </a>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
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

                    <style>
                        .ai-summary-box { background: #ffffff; border: 1px solid #f0f2f5; border-radius: 12px; padding: 25px; text-align: center; box-shadow: 0 4px 20px rgba(0,0,0,.05); margin: 30px 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; clear: both; }
                        .ai-summary-header { display: flex; align-items: center; justify-content: center; gap: 8px; margin-bottom: 20px; }
                        .ai-sparkle { color: #5f9ea0; font-size: 20px; }
                        .ai-summary-header p { margin: 0; color: #4a5568; font-size: 16px; font-weight: 500; }
                        .ai-tools-list { display: flex; justify-content: center; align-items: center; gap: 20px; flex-wrap: wrap; }
                        .ai-tool-icon { display: flex; align-items: center; justify-content: center; width: 50px; height: 50px; background: #f8fafc; border-radius: 12px; transition: all .3s ease; padding: 10px; text-decoration: none; cursor: pointer; color: #333333; }
                        .ai-tool-icon svg { width: 100%; height: auto; max-height: 28px; }
                        .ai-tool-icon:hover { background: #ffffff; transform: translateY(-4px); box-shadow: 0 10px 15px -3px rgba(0,0,0,.1); }
                        @media (max-width: 600px) { .ai-summary-box { padding: 15px; } .ai-tools-list { gap: 12px; } .ai-tool-icon { width: 45px; height: 45px; } .ai-summary-header p { font-size: 14px; } }
                    </style>

                    <div class="ai-summary-box">
                        <div class="ai-summary-header">
                            <span class="ai-sparkle">✦</span>
                            <p>Get a smart summary of this page with the AI tool you trust.</p>
                        </div>
                        <div class="ai-tools-list">
                            <a onclick="askAI('claude')" class="ai-tool-icon" title="Claude">
                                <svg height="1em" style="flex:none;line-height:1" viewBox="0 0 24 24" width="1em" xmlns="http://www.w3.org/2000/svg"><title>Claude</title><path d="M4.709 15.955l4.72-2.647.08-.23-.08-.128H9.2l-.79-.048-2.698-.073-2.339-.097-2.266-.122-.571-.121L0 11.784l.055-.352.48-.321.686.06 1.52.103 2.278.158 1.652.097 2.449.255h.389l.055-.157-.134-.098-.103-.097-2.358-1.596-2.552-1.688-1.336-.972-.724-.491-.364-.462-.158-1.008.656-.722.881.06.225.061.893.686 1.908 1.476 2.491 1.833.365.304.145-.103.019-.073-.164-.274-1.355-2.446-1.446-2.49-.644-1.032-.17-.619a2.97 2.97 0 01-.104-.729L6.283.134 6.696 0l.996.134.42.364.62 1.414 1.002 2.229 1.555 3.03.456.898.243.832.091.255h.158V9.01l.128-1.706.237-2.095.23-2.695.08-.76.376-.91.747-.492.584.28.48.685-.067.444-.286 1.851-.559 2.903-.364 1.942h.212l.243-.242.985-1.306 1.652-2.064.73-.82.85-.904.547-.431h1.033l.76 1.129-.34 1.166-1.064 1.347-.881 1.142-1.264 1.7-.79 1.36.073.11.188-.02 2.856-.606 1.543-.28 1.841-.315.833.388.091.395-.328.807-1.969.486-2.309.462-3.439.813-.042.03.049.061 1.549.146.662.036h1.622l3.02.225.79.522.474.638-.079.485-1.215.62-1.64-.389-3.829-.91-1.312-.329h-.182v.11l1.093 1.068 2.006 1.81 2.509 2.33.127.578-.322.455-.34-.049-2.205-1.657-.851-.747-1.926-1.62h-.128v.17l.444.649 2.345 3.521.122 1.08-.17.353-.608.213-.668-.122-1.374-1.925-1.415-2.167-1.143-1.943-.14.08-.674 7.254-.316.37-.729.28-.607-.461-.322-.747.322-1.476.389-1.924.315-1.53.286-1.9.17-.632-.012-.042-.14.018-1.434 1.967-2.18 2.945-1.726 1.845-.414.164-.717-.37.067-.662.401-.589 2.388-3.036 1.44-1.882.93-1.086-.006-.158h-.055L4.132 18.56l-1.13.146-.487-.456.061-.746.231-.243 1.908-1.312-.006.006z" fill="#D97757" fill-rule="nonzero"></path></svg>
                            </a>
                            
                            <a onclick="askAI('gemini')" class="ai-tool-icon" title="Gemini">
                                <svg height="1em" style="flex:none;line-height:1" viewBox="0 0 24 24" width="1em" xmlns="http://www.w3.org/2000/svg"><title>Gemini</title><path d="M20.616 10.835a14.147 14.147 0 01-4.45-3.001 14.111 14.111 0 01-3.678-6.452.503.503 0 00-.975 0 14.134 14.134 0 01-3.679 6.452 14.155 14.155 0 01-4.45 3.001c-.65.28-1.318.505-2.002.678a.502.502 0 000 .975c.684.172 1.35.397 2.002.677a14.147 14.147 0 014.45 3.001 14.112 14.112 0 013.679 6.453.502.502 0 00.975 0c.172-.685.397-1.351.677-2.003a14.145 14.145 0 013.001-4.45 14.113 14.113 0 016.453-3.678.503.503 0 000-.975 13.245 13.245 0 01-2.003-.678z" fill="#3186FF"></path><path d="M20.616 10.835a14.147 14.147 0 01-4.45-3.001 14.111 14.111 0 01-3.678-6.452.503.503 0 00-.975 0 14.134 14.134 0 01-3.679 6.452 14.155 14.155 0 01-4.45 3.001c-.65.28-1.318.505-2.002.678a.502.502 0 000 .975c.684.172 1.35.397 2.002.677a14.147 14.147 0 014.45 3.001 14.112 14.112 0 013.679 6.453.502.502 0 00.975 0c.172-.685.397-1.351.677-2.003a14.145 14.145 0 013.001-4.45 14.113 14.113 0 016.453-3.678.503.503 0 000-.975 13.245 13.245 0 01-2.003-.678z" fill="url(#lobe-icons-gemini-fill-0)"></path><path d="M20.616 10.835a14.147 14.147 0 01-4.45-3.001 14.111 14.111 0 01-3.678-6.452.503.503 0 00-.975 0 14.134 14.134 0 01-3.679 6.452 14.155 14.155 0 01-4.45 3.001c-.65.28-1.318.505-2.002.678a.502.502 0 000 .975c.684.172 1.35.397 2.002.677a14.147 14.147 0 014.45 3.001 14.112 14.112 0 013.679 6.453.502.502 0 00.975 0c.172-.685.397-1.351.677-2.003a14.145 14.145 0 013.001-4.45 14.113 14.113 0 016.453-3.678.503.503 0 000-.975 13.245 13.245 0 01-2.003-.678z" fill="url(#lobe-icons-gemini-fill-1)"></path><path d="M20.616 10.835a14.147 14.147 0 01-4.45-3.001 14.111 14.111 0 01-3.678-6.452.503.503 0 00-.975 0 14.134 14.134 0 01-3.679 6.452 14.155 14.155 0 01-4.45 3.001c-.65.28-1.318.505-2.002.678a.502.502 0 000 .975c.684.172 1.35.397 2.002.677a14.147 14.147 0 014.45 3.001 14.112 14.112 0 013.679 6.453.502.502 0 00.975 0c.172-.685.397-1.351.677-2.003a14.145 14.145 0 013.001-4.45 14.113 14.113 0 016.453-3.678.503.503 0 000-.975 13.245 13.245 0 01-2.003-.678z" fill="url(#lobe-icons-gemini-fill-2)"></path><defs><linearGradient gradientUnits="userSpaceOnUse" id="lobe-icons-gemini-fill-0" x1="7" x2="11" y1="15.5" y2="12"><stop stop-color="#08B962"></stop><stop offset="1" stop-color="#08B962" stop-opacity="0"></stop></linearGradient><linearGradient gradientUnits="userSpaceOnUse" id="lobe-icons-gemini-fill-1" x1="8" x2="11.5" y1="5.5" y2="11"><stop stop-color="#F94543"></stop><stop offset="1" stop-color="#F94543" stop-opacity="0"></stop></linearGradient><linearGradient gradientUnits="userSpaceOnUse" id="lobe-icons-gemini-fill-2" x1="3.5" x2="17.5" y1="13.5" y2="12"><stop stop-color="#FABC12"></stop><stop offset=".46" stop-color="#FABC12" stop-opacity="0"></stop></linearGradient></defs></svg>
                            </a>
                            
                            <a onclick="askAI('chatgpt')" class="ai-tool-icon" title="ChatGPT">
                                <svg fill="currentColor" fill-rule="evenodd" height="1em" style="flex:none;line-height:1" viewBox="0 0 24 24" width="1em" xmlns="http://www.w3.org/2000/svg"><title>OpenAI</title><path d="M21.55 10.004a5.416 5.416 0 00-.478-4.501c-1.217-2.09-3.662-3.166-6.05-2.66A5.59 5.59 0 0010.831 1C8.39.995 6.224 2.546 5.473 4.838A5.553 5.553 0 001.76 7.496a5.487 5.487 0 00.691 6.5 5.416 5.416 0 00.477 4.502c1.217 2.09 3.662 3.165 6.05 2.66A5.586 5.586 0 0013.168 23c2.443.006 4.61-1.546 5.361-3.84a5.553 5.553 0 003.715-2.66 5.488 5.488 0 00-.693-6.497v.001zm-8.381 11.558a4.199 4.199 0 01-2.675-.954c.034-.018.093-.05.132-.074l4.44-2.53a.71.71 0 00.364-.623v-6.176l1.877 1.069c.02.01.033.029.036.05v5.115c-.003 2.274-1.87 4.118-4.174 4.123zM4.192 17.78a4.059 4.059 0 01-.498-2.763c.032.02.09.055.131.078l4.44 2.53c.225.13.504.13.73 0l5.42-3.088v2.138a.068.068 0 01-.027.057L9.9 19.288c-1.999 1.136-4.552.46-5.707-1.51h-.001zM3.023 8.216A4.15 4.15 0 015.198 6.41l-.002.151v5.06a.711.711 0 00.364.624l5.42 3.087-1.876 1.07a.067.067 0 01-.063.005l-4.489-2.559c-1.995-1.14-2.679-3.658-1.53-5.63h.001zm15.417 3.54l-5.42-3.088L14.896 7.6a.067.067 0 01.063-.006l4.489 2.557c1.998 1.14 2.683 3.662 1.529 5.633a4.163 4.163 0 01-2.174 1.807V12.38a.71.71 0 00-.363-.623zm1.867-2.773a6.04 6.04 0 00-.132-.078l-4.44-2.53a.731.731 0 00-.729 0l-5.42 3.088V7.325a.068.068 0 01.027-.057L14.1 4.713c2-1.137 4.555-.46 5.707 1.513.487.833.664 1.809.499 2.757h.001zm-11.741 3.81l-1.877-1.068a.065.065 0 01-.036-.051V6.559c.001-2.277 1.873-4.122 4.181-4.12.976 0 1.92.338 2.671.954-.034.018-.092.05-.131.073l-4.44 2.53a.71.71 0 00-.365.623l-.003 6.173v.002zm1.02-2.168L12 9.25l2.414 1.375v2.75L12 14.75l-2.415-1.375v-2.75z"></path></svg>
                            </a>
                            
                            <a onclick="askAI('perplexity')" class="ai-tool-icon" title="Perplexity">
                                <svg height="1em" style="flex:none;line-height:1" viewBox="0 0 24 24" width="1em" xmlns="http://www.w3.org/2000/svg"><title>Perplexity</title><path d="M19.785 0v7.272H22.5V17.62h-2.935V24l-7.037-6.194v6.145h-1.091v-6.152L4.392 24v-6.465H1.5V7.188h2.884V0l7.053 6.494V.19h1.09v6.49L19.786 0zm-7.257 9.044v7.319l5.946 5.234V14.44l-5.946-5.397zm-1.099-.08l-5.946 5.398v7.235l5.946-5.234V8.965zm8.136 7.58h1.844V8.349H13.46l6.105 5.54v2.655zm-8.982-8.28H2.59v8.195h1.8v-2.576l6.192-5.62zM5.475 2.476v4.71h5.115l-5.115-4.71zm13.219 0l-5.115 4.71h5.115v-4.71z" fill="#22B8CD" fill-rule="nonzero"></path></svg>
                            </a>
                            
                            <a onclick="askAI('grok')" class="ai-tool-icon" title="Grok">
                                <svg fill="currentColor" fill-rule="evenodd" height="1em" style="flex:none;line-height:1" viewBox="0 0 24 24" width="1em" xmlns="http://www.w3.org/2000/svg"><title>Grok</title><path d="M9.27 15.29l7.978-5.897c.391-.29.95-.177 1.137.272.98 2.369.542 5.215-1.41 7.169-1.951 1.954-4.667 2.382-7.149 1.406l-2.711 1.257c3.889 2.661 8.611 2.003 11.562-.953 2.341-2.344 3.066-5.539 2.388-8.42l.006.007c-.983-4.232.242-5.924 2.75-9.383.06-.082.12-.164.179-.248l-3.301 3.305v-.01L9.267 15.292M7.623 16.723c-2.792-2.67-2.31-6.801.071-9.184 1.761-1.763 4.647-2.483 7.166-1.425l2.705-1.25a7.808 7.808 0 00-1.829-1A8.975 8.975 0 005.984 5.83c-2.533 2.536-3.33 6.436-1.962 9.764 1.022 2.487-.653 4.246-2.34 6.022-.599.63-1.199 1.259-1.682 1.925l7.62-6.815"></path></svg>
                            </a>
                        </div>
                    </div>

                    <script>
                        function askAI(t){let e=window.location.href,a=encodeURIComponent("tell me about "+e),n="";"claude"===t?n="https://claude.ai/new?q="+a:"gemini"===t?n="https://gemini.google.com/app?q="+a:"perplexity"===t?n="https://www.perplexity.ai/?q="+a:"chatgpt"===t?n="https://chatgpt.com/?q="+a:"grok"===t&&(n="https://x.com/i/grok?text="+a),n&&window.open(n,"_blank")}
                    </script>
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

                // ============================================
                // ADD THIS: Define which categories should show similar apps
                // ============================================
                // Replace these IDs with your actual category IDs
                $allowed_category_ids = array(66, 51, 71, 95, 88, 89, 90, 91, 92, 70, 93, 94, 96,56); // EXAMPLE: Change these!
                
                // Check if current post is in allowed categories
                $show_similar_apps = false;
                foreach ($categories as $cat) {
                    if (in_array($cat->term_id, $allowed_category_ids)) {
                        $show_similar_apps = true;
                        break;
                    }
                }
                
                // Only proceed if post is in allowed categories
                if ($show_similar_apps) :
                // ============================================
                
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
                endif; // End if have_posts
                endif; // End if $show_similar_apps
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
