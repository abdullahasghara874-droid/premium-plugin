<?php
/**
 * Template Name: Updates List
 * Description: Displays recently updated apps in a grid format
 */
get_header();

$current_cat = get_queried_object();
?>

<style>
/* Color Variables */
:root {
    --primary-color: #2563eb;
    --button-hover: #2F6BB2;
    --text-primary: #1f2937;
    --text-secondary: #6b7280;
    --text-muted: #9ca3af;
    --border-color: #e5e7eb;
    --bg-light: #f9fafb;
    --bg-card: #ffffff;
    --radius: 8px;
    --shadow: 0 2px 4px rgba(0,0,0,0.05);
    --shadow-hover: 0 6px 12px rgba(0,0,0,0.1);
}

/* Page Header - Remove blue background */
.updates-page-header {
    text-align: center;
    margin-bottom: 40px;
    padding: 30px 20px;
    background: transparent !important;
}

.updates-title {
    font-size: 36px;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
    position: relative;
    display: inline-block;
}

.updates-title:after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 3px;
    background: var(--primary-color);
    border-radius: 2px;
}

.updates-description {
    max-width: 800px;
    margin: 30px auto 0;
    color: var(--text-secondary);
    font-size: 16px;
    line-height: 1.6;
}

.updates-description p {
    margin: 0;
}

/* Grid Container */
.updates-grid-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 20px 40px;
}

/* 4x4 Grid Layout */
.updates-grid-4x4 {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 25px;
    margin-bottom: 40px;
}

/* Grid Item */
.update-grid-item {
    background: var(--bg-card);
    border-radius: var(--radius);
    border: 1px solid var(--border-color);
    overflow: hidden;
    transition: border-color 0.3s ease;
    height: 100%;
    position: relative;
	 padding-top: 20px;
}

.update-grid-item:hover {
    border-color: var(--primary-color);
    transform: none;
    box-shadow: none;
}

.update-item-inner {
    display: flex;
    flex-direction: column;
    height: 100%;
}

/* Thumbnail - Updated to 150x150 with 20px border radius */
.update-thumbnail {
    width: 100%;
    height: 150px;
    overflow: hidden;
    background: transparent !important;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    position: relative;
}

.update-image {
    width: 150px;
    height: 150px;
    object-fit: cover;
    transition: transform 0.5s ease;
    background: transparent !important;
    border-radius: 10px;
}

.update-thumbnail:hover .update-image {
    transform: scale(1.05);
}

/* Supported Devices Badge - Top Right */
.devices-badge {
    position: absolute;
    top: 10px;
    right: 15px;
    background: #2872FA;
    color: white;
    padding: 4px 10px;
    border-radius: 2px;
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
    font-weight: 500;
  
}

.update-grid-item:hover .devices-badge {
    background: #2872FA;
}

.badge-text {
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.3px;
}

/* Content */
.update-content {
    padding: 20px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.update-title {
    margin: 0 0 15px 0;
    font-size: 16px;
    font-weight: 600;
    line-height: 1.4;
    height: 44px;
    overflow: hidden;
}

.update-link {
    color: var(--text-primary);
    text-decoration: none;
    transition: color 0.2s;
}

.update-link:hover {
    color: var(--primary-color);
}

/* Metadata Grid */
.update-metadata-grid {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid var(--border-color);
}

.metadata-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    color: var(--text-secondary);
    line-height: 1.3;
}

.metadata-item.metadata-full-width {
    grid-column: 1 / -1;
}

.metadata-icon {
    flex-shrink: 0;
    color: var(--primary-color);
}

.metadata-label {
    font-weight: 500;
    color: var(--text-primary);
    min-width: 60px;
}

.metadata-value {
    color: var(--text-secondary);
    flex: 1;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.metadata-link {
    color: var(--primary-color);
    text-decoration: none;
    transition: color 0.2s;
}

.metadata-link:hover {
    color: var(--button-hover);
    text-decoration: underline;
}

/* Download Button */
.update-button-container {
    margin-top: auto;
}

.download-btn-grid {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 10px 15px;
    background: var(--primary-color);
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-weight: 500;
    font-size: 14px;
    transition: all 0.2s ease;
    border: none;
    cursor: pointer;
}

.download-btn-grid:hover {
    background: #2F6BB2;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(47, 107, 178, 0.3);
}

.download-btn-grid:hover,
.download-btn-grid:hover .btn-icon-grid {
    color: white !important;
    stroke: white !important;
}

.btn-icon-grid {
    flex-shrink: 0;
}

/* Pagination - Remove blue background */
.updates-pagination-grid {
    margin-top: 40px;
    text-align: center;
}

.updates-pagination-grid ul {
    list-style: none;
    margin: 0;
    padding: 0;
    display: inline-flex;
    gap: 5px;
    background: transparent !important;
}

.updates-pagination-grid .page-numbers {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 36px;
    height: 36px;
    padding: 0 12px;
    border: 1px solid var(--border-color);
    border-radius: 6px;
    text-decoration: none;
    color: var(--text-secondary);
    font-weight: 500;
    font-size: 14px;
    transition: all 0.2s;
    position: relative;
    overflow: hidden;
    background: white;
}

.updates-pagination-grid .page-numbers.prev,
.updates-pagination-grid .page-numbers.next {
    min-width: 90px;
    background: white;
}

.updates-pagination-grid .page-numbers:not(.current):hover {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
    z-index: 1;
}

.updates-pagination-grid .page-numbers.current {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

.updates-pagination-grid .pagination-arrow {
    font-size: 16px;
    line-height: 1;
}

/* Ensure prev/next buttons don't have blue background */
.updates-pagination-grid .page-numbers.prev:hover,
.updates-pagination-grid .page-numbers.next:hover {
    background: var(--primary-color) !important;
}

/* No Updates State */
.no-updates-grid {
    text-align: center;
    padding: 60px 20px;
    background: var(--bg-light);
    border-radius: var(--radius);
}

.no-updates-icon-grid {
    margin-bottom: 20px;
    color: var(--text-muted);
}

.no-updates-grid h3 {
    margin: 0 0 10px 0;
    color: var(--text-primary);
    font-size: 20px;
}

.no-updates-grid p {
    color: var(--text-secondary);
    margin: 0;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .updates-grid-4x4 {
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }
    
    .update-thumbnail {
        height: 140px;
    }
    
    .update-image {
        width: 140px;
        height: 140px;
    }
    
    .metadata-label {
        min-width: 55px;
    }
    
    .devices-badge {
        top: 10px;
        right: 10px;
        padding: 3px 8px;
        font-size: 11px;
    }
    
    .badge-text {
        font-size: 10px;
    }
}

@media (max-width: 992px) {
    .updates-grid-4x4 {
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
    
    .updates-title {
        font-size: 32px;
    }
    
    .update-thumbnail {
        height: 130px;
    }
    
    .update-image {
        width: 130px;
        height: 130px;
    }
    
    .metadata-item {
        font-size: 11px;
    }
    
    .metadata-label {
        min-width: 50px;
    }
    
    .devices-badge {
        top: 8px;
        right: 8px;
        padding: 3px 7px;
        font-size: 10px;
    }
    
    .badge-text {
        font-size: 9px;
    }
}

@media (max-width: 768px) {
    .updates-grid-4x4 {
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
    
    .update-thumbnail {
        height: 120px;
        padding: 15px;
    }
    
    .update-image {
        width: 120px;
        height: 120px;
    }
    
    .update-content {
        padding: 15px;
    }
    
    .updates-title {
        font-size: 28px;
    }
    
    .updates-description {
        font-size: 14px;
    }
    
    .metadata-item {
        font-size: 10px;
        gap: 6px;
    }
    
    .metadata-label {
        min-width: 45px;
    }
    
    .download-btn-grid {
        font-size: 13px;
        padding: 8px 12px;
    }
    
    .devices-badge {
        top: 7px;
        right: 7px;
        padding: 2px 6px;
        font-size: 9px;
    }
    
    .badge-text {
        font-size: 8px;
    }
}

@media (max-width: 576px) {
    .updates-grid-4x4 {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    
    .update-thumbnail {
        height: 110px;
        padding: 12px;
    }
    
    .update-image {
        width: 110px;
        height: 110px;
        border-radius: 15px;
    }
    
    .update-content {
        padding: 12px;
    }
    
    .update-title {
        font-size: 14px;
        height: 38px;
        margin-bottom: 10px;
    }
    
    .updates-title {
        font-size: 24px;
    }
    
    .updates-description {
        font-size: 13px;
        margin-top: 20px;
    }
    
    .metadata-item {
        font-size: 9px;
        gap: 4px;
    }
    
    .metadata-icon {
        width: 12px;
        height: 12px;
    }
    
    .metadata-label {
        min-width: 40px;
    }
    
    .updates-pagination-grid .page-numbers {
        min-width: 30px;
        height: 30px;
        padding: 0 8px;
        font-size: 12px;
    }
    
    .updates-pagination-grid .page-numbers.prev,
    .updates-pagination-grid .page-numbers.next {
        min-width: 70px;
    }
    
    .devices-badge {
        top: 5px;
        right: 5px;
        padding: 2px 5px;
        font-size: 8px;
    }
    
    .badge-text {
        font-size: 7px;
    }
}

@media (max-width: 400px) {
    .updates-grid-4x4 {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .update-thumbnail {
        height: 150px;
        padding: 20px;
    }
    
    .update-image {
        width: 150px;
        height: 150px;
        border-radius: 20px;
    }
    
    .updates-grid-container {
        padding: 0 15px 30px;
    }
    
    .metadata-item {
        font-size: 11px;
    }
    
    .devices-badge {
        top: 15px;
        right: 15px;
        padding: 4px 10px;
        font-size: 11px;
    }
    
    .badge-text {
        font-size: 10px;
    }
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
        // Properly encode the query for Google search
        var encodedQuery = encodeURIComponent(query);
        var siteDomain = currentSiteUrl.replace(/^https?:\/\//, '');
        
        // Create the complete Google search URL
        var googleSearchUrl = "https://www.google.com/search?q=" + 
                              encodedQuery + 
                              "+free+download+site%3A" + 
                              siteDomain;
        
        // Store the original query for history
        searchHistory.unshift(query);
        if (searchHistory.length > 5) {
            searchHistory.pop();
        }
        aap_updateSearchHistory();
        aap_updateLocalStorage();
        
        // Open Google search in new tab
        window.open(googleSearchUrl, '_blank');
        
        // Clear the input
        searchInput.value = "";
        aap_updateInputState();
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

<main id="main" class="site-main hfeed">
    <div class="ct-container-full" data-content="normal" data-vertical-spacing="top:bottom">
        <article class="page type-page status-publish hentry">
            <div class="entry-content is-layout-constrained">

                <!-- Page Header -->
                <div class="updates-page-header">
                    <h1 class="updates-title"><?php the_title(); ?></h1>
                    
                    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                        <?php if (get_the_content()) : ?>
                            <div class="updates-description">
                                <?php the_content(); ?>
                            </div>
                        <?php endif; ?>
                    <?php endwhile; endif; ?>
                </div>

                <!-- Updates Grid Container -->
                <div class="updates-grid-container">
                    <?php
                    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

                    // Define the category slugs you want to include
                    $allowed_categories = array(
                        'sports',        // ⚽ Sports
                        'iptv',          // 📡 IPTV
                        'uk-tv',         // 🇬🇧 UK TV 
                        'anime',         // 🎌 Anime 
                        'iptv-codes',    // 🎟️ IPTV Codes 
                        'movies-tv',     // 🎬 Movies & TV 
                        'music-radio-podcasts',         // 🎵 Music, Radio & Podcasts 
                        'iptv-players',  // 📡 IPTV Players
                        'live-tv',       // 📺 Live TV 
                        'video-players', // 📺 Video Players 
                        'vpn',           // 🔒 VPN 
                        'adult',         // 🔞 XXX Adult
                        'tools'          // 🛠️ Tools
                    );

                    // Get category IDs from slugs
                    $category_ids = array();
                    foreach ($allowed_categories as $category_slug) {
                        $category = get_category_by_slug($category_slug);
                        if ($category) {
                            $category_ids[] = $category->term_id;
                        }
                    }

                    $updates_args = array(
                        'post_type'      => 'post',
                        'posts_per_page' => 16,
                        'paged'          => $paged,
                        'orderby'        => 'modified',
                        'order'          => 'DESC',
                        'post_status'    => 'publish',
                        'category__in'   => $category_ids // Filter by category IDs
                    );

                    $updates_query = new WP_Query($updates_args);

                    if ($updates_query->have_posts()) :
                    ?>
                        <!-- 4x4 Grid -->
                        <div class="updates-grid-4x4">
                            <?php
                            while ($updates_query->have_posts()) : $updates_query->the_post();
                                $app_version = get_post_meta(get_the_ID(), '_singlo_app_version', true);
                                $app_size    = get_post_meta(get_the_ID(), '_singlo_app_size', true);
                                $download_count = get_post_meta(get_the_ID(), '_singlo_download_counts', true);
                                $supported_devices = get_post_meta(get_the_ID(), '_singlo_app_supported_devices', true);
                                
                                $modified_date = get_the_modified_date('d M Y');
                                $time_ago = human_time_diff(get_the_modified_time('U'), current_time('timestamp')) . ' ago';
                                
                                // Get categories
                                $categories = get_the_category();
                                $category_name = '';
                                $category_link = '';
                                if (!empty($categories)) {
                                    $category = $categories[0];
                                    $category_name = $category->name;
                                    $category_link = get_category_link($category->term_id);
                                }
                                
                                // Get post thumbnail
                                $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                                if (!$thumbnail_url) {
                                    $thumbnail_url = 'https://via.placeholder.com/150x150/f0f0f0/666?text=' . urlencode(get_the_title());
                                }
                            ?>
                                <article class="update-grid-item">
                                    <div class="update-item-inner">
                                        <!-- Thumbnail -->
                                        <div class="update-thumbnail">
                                            <a href="<?php the_permalink(); ?>" class="update-image-link">
                                                <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php the_title_attribute(); ?>" class="update-image" width="150" height="150" loading="lazy">
                                                
                                                <!-- Supported Devices Badge - Top Right -->
<?php if ($supported_devices) : ?>
    <?php 
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
    
    <div class="devices-badge" style="background-color: <?php echo $badge_color; ?>;">
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
                                            </a>
                                        </div>
                                        
                                        <!-- Content -->
                                        <div class="update-content">
                                            <h3 class="update-title">
                                                <a href="<?php the_permalink(); ?>" class="update-link">
                                                    <?php the_title(); ?>
                                                </a>
                                            </h3>
                                            
                                            <!-- All Metadata in Grid -->
                                            <div class="update-metadata-grid">
                                                <!-- Version -->
                                                <?php if ($app_version) : ?>
                                                    <div class="metadata-item">
                                                        <svg class="metadata-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <path d="M9.5 11.5l2.5 2.5 5-5"/>
                                                            <path d="M20 12a8 8 0 11-8-8 8 8 0 018 8z"/>
                                                        </svg>
                                                        <span class="metadata-label">Version:</span>
                                                        <span class="metadata-value">v<?php echo esc_html($app_version); ?></span>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <!-- Size -->
                                                <?php if ($app_size) : ?>
                                                    <div class="metadata-item">
                                                        <svg class="metadata-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                                                        </svg>
                                                        <span class="metadata-label">Size:</span>
                                                        <span class="metadata-value"><?php echo esc_html($app_size); ?></span>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <!-- Last Update -->
                                                <div class="metadata-item">
                                                    <svg class="metadata-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M12 8v4l3 3"/>
                                                        <circle cx="12" cy="12" r="10"/>
                                                    </svg>
                                                    <span class="metadata-label">Updated:</span>
                                                    <span class="metadata-value" title="<?php echo esc_attr($time_ago); ?>">
                                                        <?php echo esc_html($modified_date); ?>
                                                    </span>
                                                </div>
                                                
                                                <!-- Download Count -->
                                                <?php if ($download_count) : ?>
                                                    <div class="metadata-item">
                                                        <svg class="metadata-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/>
                                                        </svg>
                                                        <span class="metadata-label">Downloads:</span>
                                                        <span class="metadata-value">
                                                            <?php 
                                                            // Format download count
                                                            $download_count_num = intval($download_count);
                                                            if ($download_count_num >= 1000000) {
                                                                echo number_format($download_count_num / 1000000, 1) . 'M';
                                                            } elseif ($download_count_num >= 1000) {
                                                                echo number_format($download_count_num / 1000, 1) . 'K';
                                                            } else {
                                                                echo $download_count_num;
                                                            }
                                                            ?>
                                                        </span>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <!-- Category -->
                                                <?php if ($category_name) : ?>
                                                    <div class="metadata-item">
                                                        <svg class="metadata-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/>
                                                        </svg>
                                                        <span class="metadata-label">Category:</span>
                                                        <?php if ($category_link) : ?>
                                                            <a href="<?php echo esc_url($category_link); ?>" class="metadata-value metadata-link">
                                                                <?php echo esc_html($category_name); ?>
                                                            </a>
                                                        <?php else : ?>
                                                            <span class="metadata-value"><?php echo esc_html($category_name); ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <!-- Download Button -->
                                            <div class="update-button-container">
                                                <a href="<?php the_permalink(); ?>" class="download-btn-grid">
                                                    Download Now
                                                    <svg class="btn-icon-grid" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            <?php
                            endwhile;
                            ?>
                        </div>

                        <!-- Pagination -->
                        <?php
                        $total_pages = $updates_query->max_num_pages;
                        if ($total_pages > 1) :
                        ?>
                            <div class="updates-pagination-grid">
                                <?php
                                echo paginate_links(array(
                                    'total'      => $total_pages,
                                    'current'    => $paged,
                                    'prev_text'  => '<span class="pagination-arrow">&laquo; Prev</span>',
                                    'next_text'  => '<span class="pagination-arrow">Next &raquo;</span>',
                                    'mid_size'   => 1,
                                    'type'       => 'list'
                                ));
                                ?>
                            </div>
                        <?php endif; ?>

                    <?php
                        wp_reset_postdata();
                    else :
                    ?>
                        <div class="no-updates-grid">
                            <svg class="no-updates-icon-grid" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M12 8v4M12 16h.01"/>
                            </svg>
                            <h3>No Updates Found</h3>
                            <p>Check back later for new app updates.</p>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </article>
    </div>
</main>


<?php
get_footer();
?>