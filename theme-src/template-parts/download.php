<?php
/**
 * Template for dynamic download page
 */

$post_id = isset($GLOBALS['custom_download_id']) ? $GLOBALS['custom_download_id'] : get_the_ID();

if (!$post_id) {
    global $post;
    $post_id = $post->ID;
}

// Fetch App Meta
$version      = get_post_meta($post_id, '_singlo_app_version', true);
$size         = get_post_meta($post_id, '_singlo_app_size', true);
$downloads    = get_post_meta($post_id, '_singlo_app_downloads', true);
$download_url = get_post_meta($post_id, '_singlo_app_download_url', true);
$min_os       = get_post_meta($post_id, '_singlo_app_min_os', true);
$changelogs   = get_post_meta($post_id, '_singlo_app_changelogs', true);
$real_downloads = get_post_meta($post_id, '_singlo_download_counts', true);
$faqs         = get_theme_mod( 'singlo_faqs', [] );

if (empty($real_downloads)) $real_downloads = '0';

// Defaults
if (empty($min_os)) $min_os = 'Android 5.0+';

get_header();
?>
<div class="post-back-wrapper">
    <button type="button" class="post-back-pill" onclick="goBackSmart();">
        <svg class="post-back-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M15 18l-6-6 6-6"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"/>
        </svg>
        <span>Go Back</span>
    </button>
</div>

<script>
function goBackSmart() {
    if (window.history.length > 1) {
        window.history.back();
    } else {
        window.location.href = "<?php echo esc_url( get_permalink($post_id) ); ?>";
    }
}
</script>

<main id="main" class="site-main hfeed">

<style>
.adxfire-ad {
    justify-content: center;
}

.adxfire-ad {
    width: 100%;
    text-align: center;
    position: relative;
    margin: 0;
    padding: 0;
    font-family: sans-serif;
}

.button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 12px 24px;
    font-size: 16px;
    font-weight: 600;
    color: white;
    background-color: #2872fa;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    gap: 8px;
    text-decoration: none;
    width: 100%;
    max-width: 300px;
    margin: 10px auto;
}

.button:hover {
    background-color: #2c5fd7;
    transform: translateY(-3px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
}

.button:active {
    transform: translateY(1px);
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
}

.button svg {
    margin-right: 8px;
    vertical-align: middle;
    width: 20px;
    height: 20px;
}

.my-centered-container {
    margin: 0 auto;
    width: 90%;
    padding: 0 15px;
}

.download-message {
    background: #f0f4ff;
    border: 1px solid #d0deff;
    border-radius: 8px;
    padding: 12px 16px;
    margin: 16px auto;
    max-width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
    font-size: 14px;
    line-height: 1.4;
    color: #333;
}

.download-message-text {
    flex: 1;
    word-break: break-word;
}

.download-message-close {
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
    color: #999;
    padding: 0;
    min-width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: color 0.2s ease;
    flex-shrink: 0;
}

.download-message-close:hover {
    color: #333;
}

@media only screen and (max-width: 600px) {
    .button {
        padding: 10px 14px;
        font-size: 13px;
        max-width: 100%;
    }

    .button svg {
        width: 18px;
        height: 18px;
    }

    .my-centered-container {
        width: 95%;
        padding: 0 10px;
    }

    .download-message {
        padding: 10px 12px;
        font-size: 12px;
        gap: 8px;
    }

    h1 {
        font-size: 24px,
        margin: 12px 0 !important;
    }

    figure.wp-block-table {
        font-size: 13px;
    }

    figure.wp-block-table table td {
        padding: 8px 6px !important;
    }
}

@media screen and (min-width: 1024px) {
    .my-centered-container {
        width: 65%;
        max-width: 1200px;
    }
}
</style>

<div class="my-centered-container">
        
    <!-- APK DOWNLOAD PAGE -->
    <h1 style="text-align:center;margin-top:5px;">
        <?php echo get_the_title($post_id); ?>
        <?php if ($version) : ?>
            <span style="font-size: 0.5em; vertical-align: middle; margin-left: 8px; opacity: 0.8;">v<?php echo esc_html($version); ?></span>
        <?php endif; ?>
    </h1>
    	<figure class="is-style-stripes wp-block-table">
        <table>
            <tbody>
                <tr>
                    <td>
                        <strong>
                            <!-- App Grid Icon -->
                            <svg width="16" height="16" style="margin-right:6px; vertical-align:middle;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" role="img" aria-label="App Name icon">
                                <rect x="3" y="3" width="7" height="7"></rect>
                                <rect x="14" y="3" width="7" height="7"></rect>
                                <rect x="14" y="14" width="7" height="7"></rect>
                                <rect x="3" y="14" width="7" height="7"></rect>
                            </svg>
                            App Name
                        </strong>
                    </td>
                    <td><?php echo get_the_title($post_id); ?></td>
                </tr>
    
                <tr>
                    <td>
                        <strong>
                            <svg width="16" height="16" style="margin-right:6px; vertical-align:middle;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" role="img" aria-label="Version icon">
                                <path d="M20.59 13.41L11 3.83a2 2 0 0 0-2.83 0l-5.59 5.59a2 2 0 0 0 0 2.83l9.59 9.59a2 2 0 0 0 2.83 0l5.59-5.59a2 2 0 0 0 0-2.83z"></path>
                                <circle cx="7.5" cy="7.5" r="1.5"></circle>
                            </svg>
                            Minimum OS
                        </strong>
                    </td>
                    <td><?php echo esc_html($min_os); ?></td>
                </tr>
    
                <tr>
                    <td>
                        <strong>
                            <!-- File Icon -->
                            <svg width="16" height="16" style="margin-right:6px; vertical-align:middle;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" role="img" aria-label="File Size icon">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                            </svg>
                            Size
                        </strong>
                    </td>
                    <td><?php echo esc_html($size); ?></td>
                </tr>
    
                <tr>
                    <td>
                        <strong>
                            <!-- Clock Icon -->
                            <svg width="16" height="16" style="margin-right:6px; vertical-align:middle;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" role="img" aria-label="Last Updated icon">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                            Updated
                        </strong>
                    </td>
                    <td>
                        <?php echo human_time_diff( get_the_modified_time('U', $post_id), current_time('timestamp') ) . ' ' . __('ago', 'singlo'); ?>
                    </td>
                </tr>
                
                <tr>
                    <td>
                        <strong>
                            <!-- Folder Icon -->
                            <svg width="16" height="16" style="margin-right:6px; vertical-align:middle;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" role="img" aria-label="App Category icon">
                                <path d="M3 7v13h18V7H3zm0-2h7l2 2h9v2H3V5z"></path>
                            </svg>
                            Downloads count
                        </strong>
                    </td>
                    <td><?php echo esc_html($real_downloads); ?></td>
                </tr>
                
            </tbody>
        </table>
    </figure>
    
    <div class="dl-btn" id="dl-btn-container">
        <div id="download-message" class="download-message" style="display: none;">
            <span class="download-message-text" id="message-text"></span>
            <button class="download-message-close" onclick="document.getElementById('download-message').style.display='none';" aria-label="Close message">&times;</button>
        </div>

        <div class="wp-block-buttons" style="text-align:center">
            <div class="has-custom-font-size has-medium-font-size wp-block-button" style="display: block;">
                <button oncontextmenu="return false;" onclick="redirectViaCustomDL('<?php echo esc_url($download_url); ?>', <?php echo (int) $post_id; ?>)" id="download-button" class="button" role="button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" role="img">
                        <path d="M12 5v14m7-7l-7 7-7-7"></path>
                    </svg>
                    Download APK
                </button>
            </div>
        </div>
    
    <script>
    function redirectViaCustomDL(redirectUrl, postId) {
        var messageEl = document.getElementById('download-message');
        var messageText = document.getElementById('message-text');

        if (typeof jQuery !== 'undefined' && typeof singlo_ajax_obj !== 'undefined') {
            jQuery.post(singlo_ajax_obj.ajaxurl, {
                action: 'singlo_increment_download_count',
                post_id: postId
            });
        }

        messageText.textContent = 'Downloading...';
        messageEl.style.display = 'flex';

        var iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        iframe.src = redirectUrl;
        document.body.appendChild(iframe);

        setTimeout(function() {
            messageText.textContent = 'Download started successfully!';
            document.body.removeChild(iframe);
        }, 2000);

        setTimeout(function() {
            messageEl.style.display = 'none';
        }, 5000);
    }
    </script>
    
    <br>

    <?php if (!empty($changelogs) && is_array($changelogs)) : 
        $latest_log = end($changelogs); 
    ?>
      <div class="wp-block-kadence-accordion alignnone">
        <div class="kt-accordion-wrap kt-accordion-id_f9a645-c2 kt-accordion-has-3-panes kt-active-pane-0 kt-accordion-block kt-pane-header-alignment-left kt-accodion-icon-style-basic kt-accodion-icon-side-right" style="max-width:none">
            <div class="kt-accordion-inner-wrap kt-accordion-initialized" data-allow-multiple-open="true" data-start-open="none">
                <div class="wp-block-kadence-pane kt-accordion-pane kt-accordion-pane-1 kt-pane_9fb0d7-20">
                    <div class="kt-accordion-header-wrap">
                        <button class="kt-blocks-accordion-header kt-acccordion-button-label-show" id="kt-accordion-header-990284" aria-controls="kt-accordion-panel-990284" data-kt-accordion-header-id="0" aria-expanded="false" onclick="this.classList.toggle('kt-accordion-panel-active'); var panel = this.parentElement.nextElementSibling; panel.style.display = (panel.style.display === 'block' ? 'none' : 'block');">
                            <span class="kt-blocks-accordion-title-wrap">
                                <span class="kt-blocks-accordion-title"><strong>Changelog in v<?php echo esc_html($latest_log['version']); ?>:</strong></span>
                            </span>
                            <span class="kt-blocks-accordion-icon-trigger"></span>
                        </button>
                    </div>
                    <div class="kt-accordion-panel" id="kt-accordion-panel-990284" style="display:none;">
                        <div class="kt-accordion-panel-inner">
                            <blockquote class="wp-block-quote">
                                <p>
                                    <b>v<?php echo esc_html($latest_log['version']); ?> - <?php echo date_i18n(get_option('date_format'), strtotime($latest_log['date'])); ?></b><br>
                                    <?php echo wpautop($latest_log['note']); ?>
                                </p>
                            </blockquote>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
    <?php endif; ?>
    
    <!---the cmtent--->
    <div class="aap_similar-apps-container" itemscope="" itemtype="https://schema.org/ItemList">
        <meta itemprop="name" content="Similar apps">
        
        <div class="aap_similar-apps-header">
            <span>Similar apps</span>
            <?php
            $categories = get_the_category($post_id);
            if ($categories) :
                $target_category = null;
                foreach ($categories as $cat) {
                    if ($cat->parent != 0) {
                        $target_category = $cat;
                        break;
                    }
                }
                if (!$target_category) {
                    $target_category = $categories[0];
                }
            ?>
                <a href="<?php echo get_category_link($target_category->term_id); ?>" class="aap_arrow-link">→</a>
            <?php endif; ?>
        </div>
        
        <div class="aap_similar-apps-grid">
            <?php
            $categories = get_the_category($post_id);
            if ($categories) :
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
                    'post_type' => 'post',
                    'category__in' => array($target_category),
                    'post__not_in' => array($post_id),
                    'posts_per_page' => 6,
                    'orderby' => 'rand'
                );

                $similar_apps = new WP_Query($args);

                if ($similar_apps->have_posts()) :
                    $pos = 1;
                    while ($similar_apps->have_posts()) : $similar_apps->the_post();
                        $s_size = get_post_meta(get_the_ID(), '_singlo_app_size', true);
                        $s_rating = get_post_meta(get_the_ID(), '_singlo_app_rating_value', true);
                        if (empty($s_rating)) $s_rating = '4.5';
                        $s_download_url = trailingslashit(get_permalink());
            ?>
                <a href="<?php echo esc_url($s_download_url); ?>" class="aap_similar-app-item" itemscope="" itemtype="https://schema.org/ListItem" itemprop="itemListElement">
                    <meta itemprop="position" content="<?php echo $pos++; ?>">
                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('thumbnail', array('alt' => get_the_title(), 'title' => get_the_title() . ' v' . get_post_meta(get_the_ID(), '_singlo_app_version', true) . ' (' . $s_size . ')', 'loading' => 'lazy')); ?>
                    <?php endif; ?>
                    <div class="aap_app-details">
                        <span class="aap_app-name" itemprop="name"><?php the_title(); ?></span>
                        <div class="aap_app-size"><?php echo esc_html($s_size); ?></div>
                        <div class="aap_app-rating"><?php echo esc_html($s_rating); ?> ★</div>
                    </div>
                </a>
            <?php endwhile; wp_reset_postdata(); endif; endif; ?>
        </div>
    </div>
    
<div style="height:39px" aria-hidden="true" class="wp-block-spacer"></div>

<style>
.kt-accordion-id_faqs .kt-accordion-panel-inner{column-gap:var(--global-kb-gap-md, 2rem);row-gap:10px; border-top:0px solid rgba(0,0,0,0);border-right:1px solid rgba(0,0,0,0);border-bottom:1px solid rgba(0,0,0,0);border-left:1px solid rgba(0,0,0,0);background:var(--theme-palette-color-8, #ffffff);padding:1.5rem;}
.kt-accordion-id_faqs .kt-blocks-accordion-header{border-top:1px solid #eeeeee;border-right:1px solid #eeeeee;border-bottom:1px solid #eeeeee;border-left:2px solid #eeeeee;background:#f7fafc;color:#4a5568;padding:14px 16px; width: 100%; text-align: left; cursor: pointer; display: flex; justify-content: space-between; align-items: center;}
.kt-accordion-id_faqs .kt-blocks-accordion-header:hover{color:#444444;background:var(--theme-palette-color-8, #ffffff);border-color:#d4d4d4;}
.kt-accordion-id_faqs .kt-blocks-accordion-header.kt-accordion-panel-active{color:var(--theme-palette-color-8, #ffffff);background:var(--theme-palette-color-1, #2872fa);border-color:#eeeeee;border-left-color:#0e9cd1;}
</style>

<?php if (!empty($faqs)) : ?>
<div class="wp-block-kadence-accordion alignnone kt-accordion-id_faqs">
    <div class="kt-accordion-wrap kt-accordion-has-12-panes kt-active-pane-0 kt-accordion-block kt-pane-header-alignment-left kt-accodion-icon-style-basic kt-accodion-icon-side-right" style="max-width:none">
        <div class="kt-accordion-inner-wrap kt-accordion-initialized" data-allow-multiple-open="false" data-start-open="none">
            <?php foreach ($faqs as $index => $faq) : ?>
                <div class="wp-block-kadence-pane kt-accordion-pane">
                    <div class="kt-accordion-header-wrap">
                        <button class="kt-blocks-accordion-header kt-acccordion-button-label-show" type="button" aria-expanded="false" onclick="this.classList.toggle('kt-accordion-panel-active'); var panel = this.parentElement.nextElementSibling; panel.style.display = (panel.style.display === 'block' ? 'none' : 'block');">
                            <span class="kt-blocks-accordion-title-wrap">
                                <span class="kt-blocks-accordion-title"><strong><?php echo esc_html($faq['question']); ?></strong></span>
                            </span>
                            <span class="kt-blocks-accordion-icon-trigger"></span>
                        </button>
                    </div>
                    <div class="kt-accordion-panel" style="display:none;">
                        <div class="kt-accordion-panel-inner">
                            <?php echo wpautop($faq['answer']); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<br>
</div>
</main>
<?php get_footer(); ?>