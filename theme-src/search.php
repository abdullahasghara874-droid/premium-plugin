<?php
get_header();
?>

<style>
.ct-media-container {
    position: relative;
    display: block;
}
</style>

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
    <div class="hero-section" data-type="type-2">
        <header class="entry-header ct-container-narrow">
            <h1 class="page-title" itemprop="headline">
                <?php printf(esc_html__('Search Results for: %s', 'singlo'), get_search_query()); ?>
            </h1>
        </header>
    </div>
    <div class="ct-container" data-vertical-spacing="top:bottom">
        <section>
            <?php if (have_posts()) : ?>
                <div class="entries" data-archive="default" data-layout="grid" data-cards="boxed">
                    <?php while (have_posts()) : the_post(); ?>
                        <article class="entry-card card-content <?php echo join(' ', get_post_class()); ?>">
                            <a class="ct-media-container" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('medium_large', array('class' => 'attachment-medium_large size-medium_large wp-post-image', 'itemprop' => 'image')); ?>

                                    <!-- Add Supported Devices Badge -->
                                    <?php
                                    $supported_devices = get_post_meta(get_the_ID(), '_singlo_app_supported_devices', true);
                                    if ($supported_devices) :
                                        // Determine badge color based on device type
                                        $badge_color = '#2ECC71'; // Default for MOBILE & MOB & TV
                                        $device_upper = strtoupper($supported_devices);

                                        if (strpos($device_upper, 'TV') !== false && strpos($device_upper, 'MOB') === false) {
                                            $badge_color = '#E74C3C'; // For TV only
                                        } elseif (strpos($device_upper, 'MOB') !== false && strpos($device_upper, 'TV') === false) {
                                            $badge_color = '#2872FA'; // For MOBILE only
                                        }
                                        // For MOB & TV or MOBILE & TV, it keeps the default #2872FA
                                    ?>
                                    <div class="devices-badge" style="background-color: <?php echo $badge_color; ?>; position: absolute; top: 15px; right: 15px; color: white; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 500; z-index: 2; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);">
                                        <span class="badge-text">
                                            <?php
                                            // Format the text: replace underscores with spaces and fix capitalization
                                            $formatted_devices = str_replace('_', ' ', $supported_devices);
                                            $formatted_devices = str_replace('AND', '&', $formatted_devices);
                                            echo esc_html($formatted_devices);
                                            ?>
                                        </span>
                                    </div>
                                    <?php endif; ?>
                                    <!-- End Supported Devices Badge -->

                                <?php endif; ?>
                            </a>
                            <ul class="entry-meta" data-type="simple:none" data-id="meta_1">
                                <li class="meta-categories" data-type="simple">
                                    <?php
                                    $categories = get_the_category();
                                    if (!empty($categories)) {
                                        echo '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '" rel="tag" class="ct-term-' . $categories[0]->term_id . '">' . esc_html($categories[0]->name) . '</a>';
                                    }
                                    ?>
                                </li>
                            </ul>
                            <h2 class="entry-title">
                                <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
                            </h2>
                            <ul class="entry-meta" data-type="simple:none" data-id="meta_2">
                                <li class="meta-updated-date" itemprop="dateModified">
                                    <time class="ct-meta-element-date" datetime="<?php echo get_the_modified_date('c'); ?>">
                                        <?php echo get_the_modified_date(); ?>
                                    </time>
                                </li>
                                <li class="meta-views">
                                    <?php echo esc_html(singlo_get_post_views_text(get_the_ID())); ?>
                                </li>
                            </ul>
                        </article>
                    <?php endwhile; ?>
                </div>
                <nav class="ct-pagination" data-pagination="simple">
                    <div class="">
                        <?php
                        echo paginate_links(array(
                            'mid_size'  => 2,
                            'prev_next' => false,
                            'type'      => 'plain',
                        ));
                        ?>
                    </div>
                    <?php if (get_next_posts_link()) : ?>
                        <a class="next page-numbers" rel="next" href="<?php echo esc_url(get_next_posts_page_link()); ?>">Next <svg width="9px" height="9px" viewBox="0 0 15 15" fill="currentColor">
                                <path d="M4.1,15c0.2,0,0.4-0.1,0.6-0.2L11.4,8c0.3-0.3,0.3-0.8,0-1.1L4.8,0.2C4.5-0.1,4-0.1,3.7,0.2C3.4,0.5,3.4,1,3.7,1.3l6.1,6.1l-6.2,6.2c-0.3,0.3-0.3,0.8,0,1.1C3.7,14.9,3.9,15,4.1,15z"></path>
                            </svg></a>
                    <?php endif; ?>
                </nav>
            <?php else : ?>
                <div class="no-results-not-found">
                    <p><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'singlo'); ?></p>
                </div>
            <?php endif; ?>
        </section>
    </div>
</main>
<?php
get_footer();
