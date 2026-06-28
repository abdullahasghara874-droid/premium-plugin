<?php
get_header();

$current_cat = get_queried_object();
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
<main id="main" class="site-main hfeed">
    <div class="ct-container-full" data-content="normal" data-vertical-spacing="top:bottom">
        <article id="post-4612" class="post-4612 page type-page status-publish hentry">
            <div class="entry-content is-layout-constrained">
                <div class="aap-top-apps">
                    <h3>Top 5 Apps of the Week!</h3>
                    <div class="aap-top-apps-list">
                        <?php
                        // Use the same category IDs from section 2
                        $parent_cats_to_show = array(70, 90, 89, 91, 88, 95, 66, 92, 93, 94, 56, 96);
                        
                        $top_apps_args = array(
                            'post_type'      => 'post',
                            'posts_per_page' => 5,
                            'meta_key'       => 'wpb_post_views_count',
                            'orderby'        => 'meta_value_num',
                            'order'          => 'DESC',
                            'tax_query'      => array(
                                array(
                                    'taxonomy' => 'category',
                                    'field'    => 'term_id',
                                    'terms'    => $parent_cats_to_show,
                                )
                            )
                        );
                        $top_apps_query = new WP_Query($top_apps_args);
                        if ($top_apps_query->have_posts()) :
                            $rank = 1;
                            while ($top_apps_query->have_posts()) : $top_apps_query->the_post();
                                $app_version = get_post_meta(get_the_ID(), '_singlo_app_version', true);
                                $app_size    = get_post_meta(get_the_ID(), '_singlo_app_size', true);
                                $title_attr  = get_the_title();
                                if ($app_version) $title_attr .= ' v' . $app_version;
                                if ($app_size) $title_attr .= ' (' . $app_size . ')';
                        ?>
                                <div class="aap-app-card">
                                    <div class="aap-badge"><?php echo $rank++; ?></div>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <?php the_post_thumbnail(array(512, 512), array(
                                                'class' => 'aap-logo entered lazyloaded',
                                                'alt'   => get_the_title(),
                                                'title' => $title_attr
                                            )); ?>
                                        <?php else : ?>
                                            <img width="512" height="512" src="<?php echo get_template_directory_uri(); ?>/assets/images/default-icon.png" class="aap-logo" alt="<?php the_title(); ?>">
                                        <?php endif; ?>
                                    </a>
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    <div class="aap-app-views"><?php echo esc_html(singlo_get_post_views_text(get_the_ID())); ?></div>
                                </div>
                        <?php
                            endwhile;
                            wp_reset_postdata();
                        else:
                            echo '<p>No apps found in the specified categories.</p>';
                        endif;
                        ?>
                    </div>
                </div>
                <div class="emoji-category-heading">📱 App Categories</div>
                <div class="emoji-category-grid">
                    <?php
                    // List of parent category IDs you want to show
                    $parent_cats_to_show = array(70, 90, 89, 91, 88, 95, 66, 92, 94, 93, 56, 96); // Replace with actual category IDs
                    
                    $categories = get_categories(array(
                        'include'    => $parent_cats_to_show,
                        'orderby'    => 'name',
                        'order'      => 'ASC',
                        'hide_empty' => false
                    ));

                    if (!empty($categories)) {
                        foreach ($categories as $category) {
                            // Skip the excluded categories
                            if (in_array($category->slug, array('firestick-guide', 'android-tv-google-tv-guide'))) {
                                continue;
                            }
                    ?>
                            <div class="emoji-category-item">
                                <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>"><?php echo esc_html($category->name); ?></a>
                            </div>
                    <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </article>
    </div>
</main>
<?php
get_footer();
