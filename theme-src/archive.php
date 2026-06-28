<?php
/**
 * Dynamic Archive Template
 */

get_header();

$current_cat = get_queried_object();
if ( is_category() ) {
    $cat_name = single_cat_title('', false);
} elseif ( is_tag() ) {
    $cat_name = single_tag_title('', false);
} else {
    $cat_name = get_the_archive_title();
}

?>
<div data-block="hook:1674">
    <article id="post-1674" class="post-1674">
        <div class="entry-content is-layout-constrained">
            <div class="aap_metatv-search-wrapper">
                <div class="aap_metatv-search-container">
                    <form id="aap_searchForm" action="https://www.google.com/search" method="GET" target="_blank">
                        <div class="aap_search-input-group">
                            <span class="aap_search-icon">
                                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="11" cy="11" r="7"></circle>
                                    <path d="M20 20L16 16"></path>
                                </svg>
                            </span>
                            <input type="text" id="aap_searchQuery" name="q" placeholder="Search for apps..." autocomplete="off">
                            <button type="button" id="aap_clearInput" class="aap_clear-input" title="Clear input">
                                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M18 6L6 18"></path>
                                    <path d="M6 6L18 18"></path>
                                </svg>
                            </button>
                            <button type="submit" id="aap_searchBtn">Search</button>
                        </div>
                    </form>
                    <div class="aap_search-history">
                        <div id="aap_metatv-google-search-history">
                            <p>No recent searches</p>
                        </div>
                        <button id="aap_clearBtn" title="Clear history">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3 6H21"></path>
                                <path d="M19 6V20C19 21 18 22 17 22H7C6 22 5 21 5 20V6"></path>
                                <path d="M8 6V4C8 3 9 2 10 2H14C15 2 16 3 16 4V6"></path>
                            </svg>
                            <span>Clear History</span>
                        </button>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                document.addEventListener('DOMContentLoaded', function() {
                    if (window.aapSearchInitialized) return;
                    window.aapSearchInitialized = true;

                    var currentSiteUrl = window.location.protocol + '//' + window.location.hostname;
                    var searchHistory = JSON.parse(localStorage.getItem("aap_searchHistory_" + currentSiteUrl.replace(/[^a-zA-Z0-9]/g, '_'))) || [];
                    var searchInput = document.getElementById("aap_searchQuery");
                    var searchForm = document.getElementById("aap_searchForm");
                    var clearInputBtn = document.getElementById("aap_clearInput");
                    var clearHistoryBtn = document.getElementById("aap_clearBtn");
                    var searchContainer = document.querySelector(".aap_search-input-group");

                    if (!searchInput || !searchForm) return;

                    function aap_appendExtraQuery(e) {
                        e.preventDefault();
                        var query = searchInput.value.trim();
                        if (query !== "") {
                            searchInput.value = query + " free download site:" + currentSiteUrl.replace(/^https?:\/\//, '');
                            searchHistory.unshift(query);
                            if (searchHistory.length > 5) {
                                searchHistory.pop();
                            }
                            aap_updateSearchHistory();
                            aap_updateLocalStorage();
                            setTimeout(function() {
                                searchForm.submit();
                                searchInput.value = "";
                                aap_updateInputState();
                            }, 100);
                        }
                    }

                    function aap_updateSearchHistory() {
                        var historyElement = document.getElementById("aap_metatv-google-search-history");
                        if (historyElement) {
                            var historyHtml = searchHistory.length ?
                                "<p><strong>Recent:</strong> " + searchHistory.join(", ") + "</p>" :
                                "<p>No recent searches</p>";
                            historyElement.innerHTML = historyHtml;
                        }
                    }

                    function aap_updateLocalStorage() {
                        localStorage.setItem("aap_searchHistory_" + currentSiteUrl.replace(/[^a-zA-Z0-9]/g, '_'), JSON.stringify(searchHistory));
                    }

                    function aap_clearSearchHistory() {
                        searchHistory = [];
                        aap_updateSearchHistory();
                        aap_updateLocalStorage();
                    }

                    function aap_clearInput() {
                        searchInput.value = "";
                        searchInput.focus();
                        aap_updateInputState();
                    }

                    function aap_updateInputState() {
                        if (searchInput && searchContainer) {
                            if (searchInput.value.trim()) {
                                searchContainer.classList.add("aap_has-text");
                            } else {
                                searchContainer.classList.remove("aap_has-text");
                            }
                        }
                    }

                    if (searchForm) searchForm.addEventListener("submit", aap_appendExtraQuery);
                    if (clearHistoryBtn) clearHistoryBtn.addEventListener("click", aap_clearSearchHistory);
                    if (clearInputBtn) clearInputBtn.addEventListener("click", aap_clearInput);
                    if (searchInput) searchInput.addEventListener("input", aap_updateInputState);

                    aap_updateSearchHistory();
                    aap_updateInputState();
                });
            </script>

        </div>
    </article>
</div>
<main id="main" class="site-main hfeed" itemscope="itemscope" itemtype="https://schema.org/CreativeWork">
    <div data-block="archive:dynamic" data-block-structure="custom" data-vertical-spacing="top:bottom">
        <div class="ct-container-full" data-content="normal">
            <div class="entry-content is-layout-constrained">
                <h1 class="category-title"><?php echo esc_html($cat_name); ?></h1>
                
                <?php if ( have_posts() ) : ?>
                    <div class="app-list-grid ftgkle">
                        <div class="fUEl2e">
                            <?php 
                            while ( have_posts() ) : the_post(); 
                                $post_id = get_the_ID();
                                $version = get_post_meta($post_id, '_singlo_app_version', true);
                                $size    = get_post_meta($post_id, '_singlo_app_size', true) ?: 'N/A';
                                $rating  = singlo_get_rating_value($post_id);
                                $devices = get_post_meta($post_id, '_singlo_app_supported_devices', true);
                                
                                $label_class = '';
                                $label_text  = '';
                                $label_title = '';
                                
                                if ($devices === 'MOBILE') {
                                    $label_class = 'gam-label-mobile';
                                    $label_text  = 'Mobile';
                                    $label_title = 'Works on Mobile Only';
                                } elseif ($devices === 'TV') {
                                    $label_class = 'gam-label-tv';
                                    $label_text  = 'TV';
                                    $label_title = 'Works on TV Only';
                                } elseif ($devices === 'MOBILE_AND_TV') {
                                    $label_class = 'gam-label-both';
                                    $label_text  = 'TV & Mob';
                                    $label_title = 'Works on TV & Mobile';
                                }
                            ?>
                                <div class="VfPpkd-WsjYwc KC1dQ Usd1Ac Y8RQXd">
                                    <div class="app-list-item TAQqTe">
                                        <a href="<?php the_permalink(); ?>" class="Si6A0c Gy4nib">
                                            <div class="Shbxxd">
                                                <?php if ($label_text) : ?>
                                                    <div class="gam-thumbnail-label <?php echo esc_attr($label_class); ?>" title="<?php echo esc_attr($label_title); ?>" style="position: absolute">
                                                        <?php echo esc_html($label_text); ?>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <?php if (has_post_thumbnail()) : ?>
                                                    <?php 
                                                    the_post_thumbnail(array(72, 72), array(
                                                        'class' => 'app-banner T75of',
                                                        'alt'   => get_the_title() . ($version ? ' v' . $version : '') . ' (' . $size . ')',
                                                        'title' => get_the_title() . ($version ? ' v' . $version : '') . ' (' . $size . ')'
                                                    )); 
                                                    ?>
                                                <?php endif; ?>
                                            </div>
                                            <div class="app-list-content">
                                                <?php if (has_post_thumbnail()) : ?>
                                                    <?php the_post_thumbnail(array(72, 72), array('class' => 'app-icon', 'alt' => get_the_title())); ?>
                                                <?php endif; ?>
                                                <div class="app-info">
                                                    <div class="app-name"><?php the_title(); ?></div>
                                                    <div class="app-size"><?php echo esc_html($size); ?></div>
                                                    <div class="app-rating"><?php echo esc_html($rating); ?> &#9733;</div>
                                                    <div class="app-views"><?php echo esc_html(singlo_get_post_views_text($post_id)); ?></div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>

                    <nav class="pagination-nav">
                        <?php
                        echo paginate_links(array(
                            'mid_size'  => 2,
                            'prev_next' => true,
                            'prev_text' => '<svg width="9px" height="9px" viewBox="0 0 15 15" fill="currentColor" style="transform: rotate(180deg);"><path d="M4.1,15c0.2,0,0.4-0.1,0.6-0.2L11.4,8c0.3-0.3,0.3-0.8,0-1.1L4.8,0.2C4.5-0.1,4-0.1,3.7,0.2C3.4,0.5,3.4,1,3.7,1.3l6.1,6.1l-6.2,6.2c-0.3,0.3-0.3,0.8,0,1.1C3.7,14.9,3.9,15,4.1,15z"></path></svg>',
                            'next_text' => '<svg width="9px" height="9px" viewBox="0 0 15 15" fill="currentColor"><path d="M4.1,15c0.2,0,0.4-0.1,0.6-0.2L11.4,8c0.3-0.3,0.3-0.8,0-1.1L4.8,0.2C4.5-0.1,4-0.1,3.7,0.2C3.4,0.5,3.4,1,3.7,1.3l6.1,6.1l-6.2,6.2c-0.3,0.3-0.3,0.8,0,1.1C3.7,14.9,3.9,15,4.1,15z"></path></svg>',
                        ));
                        ?>
                    </nav>
                <?php else : ?>
                    <p><?php _e('No apps found in this category.', 'singlo'); ?></p>
                <?php endif; ?>

            </div>
        </div>
    </div>
</main>

<?php
get_footer();
