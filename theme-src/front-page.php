<?php
/**
 * Front page template.
 *
 * displaying current WordPress content, media, ratings, and view counts.
 */

get_header();

$hero_query = singlo_get_content_query( 'app', 'recent', 1, false );
$hero_post  = ! empty( $hero_query->posts ) ? $hero_query->posts[0] : null;
$hero_id    = $hero_post ? (int) $hero_post->ID : 0;
$home_settings = singlo_get_homepage_settings();

$site_name        = get_bloginfo( 'name' );
$site_description = get_bloginfo( 'description' );
$site_description = $site_description ? $site_description : __( 'Discover recently updated apps and practical installation guides.', 'singlo' );
$hero_title       = $home_settings['hero_title'] ? $home_settings['hero_title'] : $site_name;
$hero_description = $home_settings['hero_description'] ? $home_settings['hero_description'] : $site_description;
$hero_image_id    = absint( $home_settings['hero_image_id'] );
$hero_video_id    = absint( $home_settings['hero_video_id'] );
$hero_video_url   = $hero_video_id ? wp_get_attachment_url( $hero_video_id ) : '';
$hero_media_type  = ( 'video' === $home_settings['hero_media_type'] && $hero_video_url ) ? 'video' : 'image';
$hero_background  = sanitize_hex_color( $home_settings['hero_background_color'] );
$hero_background  = $hero_background ? $hero_background : '#d8e8eb';

$apps_category = get_category_by_slug( 'apps' );
$apps_url      = $apps_category ? get_category_link( $apps_category->term_id ) : home_url( '/apps/' );
$apps_url      = is_wp_error( $apps_url ) ? home_url( '/apps/' ) : $apps_url;

$guide_category = get_category_by_slug( 'guide' );
if ( ! $guide_category ) {
	$guide_category = get_category_by_slug( 'guides' );
}
$guides_url = $guide_category ? get_category_link( $guide_category->term_id ) : home_url( '/guide/' );
$guides_url = is_wp_error( $guides_url ) ? home_url( '/guide/' ) : $guides_url;

$updates_url = home_url( '/latest-updates/' );
$updates_url = $home_settings['apps_button_url'] ? $home_settings['apps_button_url'] : $updates_url;
$guides_url  = $home_settings['guides_button_url'] ? $home_settings['guides_button_url'] : $guides_url;
$apps_url    = $home_settings['hero_secondary_url'] ? $home_settings['hero_secondary_url'] : $apps_url;
$hero_primary_url = $home_settings['hero_primary_url']
	? $home_settings['hero_primary_url']
	: ( $hero_id ? get_permalink( $hero_id ) : '' );
$app_layout_style    = ( 'grid' === $home_settings['app_layout_style'] ) ? 'grid' : 'list';
$app_columns_desktop = min( 4, max( 1, absint( $home_settings['app_columns_desktop'] ) ) );
$app_columns_tablet  = min( 4, max( 1, absint( $home_settings['app_columns_tablet'] ) ) );
$app_columns_mobile  = min( 4, max( 1, absint( $home_settings['app_columns_mobile'] ) ) );
$home_section_keys      = singlo_get_homepage_section_order();
$home_section_positions = array_flip( $home_section_keys );
$home_section_order = static function ( $section_key ) use ( $home_section_positions ) {
	return isset( $home_section_positions[ $section_key ] )
		? (int) $home_section_positions[ $section_key ] + 1
		: 99;
};
$trending_url = ! empty( $home_settings['trending_button_url'] )
	? $home_settings['trending_button_url']
	: $apps_url;
$home_quick_links = array(
	'recent_apps' => array(
		'enabled' => ! empty( $home_settings['show_recent_apps'] ),
		'href'    => '#recent-apps',
		'title'   => $home_settings['quick_apps_title'],
		'text'    => $home_settings['quick_apps_text'],
	),
	'recent_guides' => array(
		'enabled' => ! empty( $home_settings['show_recent_guides'] ),
		'href'    => '#recent-guides',
		'title'   => $home_settings['quick_guides_title'],
		'text'    => $home_settings['quick_guides_text'],
	),
);
?>

<main
	id="primary"
	class="lnt-home lnt-home--apps-<?php echo esc_attr( $app_layout_style ); ?> lnt-home--desktop-cols-<?php echo esc_attr( $app_columns_desktop ); ?> lnt-home--tablet-cols-<?php echo esc_attr( $app_columns_tablet ); ?> lnt-home--mobile-cols-<?php echo esc_attr( $app_columns_mobile ); ?>"
	style="--lnt-app-cols-desktop: <?php echo esc_attr( $app_columns_desktop ); ?>; --lnt-app-cols-tablet: <?php echo esc_attr( $app_columns_tablet ); ?>; --lnt-app-cols-mobile: <?php echo esc_attr( $app_columns_mobile ); ?>;"
>
	<section class="lnt-home-hero" aria-labelledby="lnt-home-title" style="--lnt-hero: <?php echo esc_attr( $hero_background ); ?>;">
		<div class="lnt-home-shell lnt-home-hero__inner">
			<div class="lnt-home-hero__media">
				<?php if ( 'video' === $hero_media_type ) : ?>
					<video
						class="lnt-home-hero__video"
						src="<?php echo esc_url( $hero_video_url ); ?>"
						<?php if ( $hero_image_id ) : ?>poster="<?php echo esc_url( wp_get_attachment_image_url( $hero_image_id, 'large' ) ); ?>"<?php endif; ?>
						autoplay
						muted
						loop
						playsinline
						preload="metadata"
					></video>
				<?php elseif ( $hero_image_id ) : ?>
					<?php
					echo wp_get_attachment_image(
						$hero_image_id,
						'large',
						false,
						array(
							'class'         => 'lnt-home-hero__image',
							'loading'       => 'eager',
							'fetchpriority' => 'high',
							'decoding'      => 'async',
						)
					);
					?>
				<?php elseif ( $hero_id && has_post_thumbnail( $hero_id ) ) : ?>
					<a href="<?php echo esc_url( get_permalink( $hero_id ) ); ?>" aria-label="<?php echo esc_attr( get_the_title( $hero_id ) ); ?>">
						<?php
						echo get_the_post_thumbnail(
							$hero_id,
							'large',
							array(
								'class'         => 'lnt-home-hero__image',
								'loading'       => 'eager',
								'fetchpriority' => 'high',
								'decoding'      => 'async',
								'alt'           => get_the_title( $hero_id ),
							)
						);
						?>
					</a>
				<?php else : ?>
					<div class="lnt-home-hero__placeholder" aria-hidden="true">
						<span><?php echo esc_html( $site_name ); ?></span>
					</div>
				<?php endif; ?>
			</div>

			<div class="lnt-home-hero__content">
				<p class="lnt-home-eyebrow"><?php echo esc_html( $home_settings['hero_eyebrow'] ); ?></p>
				<h1 id="lnt-home-title"><?php echo esc_html( $hero_title ); ?></h1>
				<p class="lnt-home-hero__description"><?php echo esc_html( $hero_description ); ?></p>

				<div class="lnt-home-hero__actions">
					<?php if ( $hero_primary_url ) : ?>
						<a class="lnt-home-button lnt-home-button--primary" href="<?php echo esc_url( $hero_primary_url ); ?>">
							<?php echo esc_html( $home_settings['hero_primary_label'] ); ?>
						</a>
					<?php endif; ?>
					<a class="lnt-home-button lnt-home-button--secondary" href="<?php echo esc_url( $apps_url ); ?>">
						<?php echo esc_html( $home_settings['hero_secondary_label'] ); ?>
					</a>
				</div>
			</div>
		</div>
		<!-- Search Section -->
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

                <input type="text" id="aap_searchQuery" name="q"
                    placeholder="Search for apps..." autocomplete="off">

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

<script>
document.addEventListener('DOMContentLoaded', function () {
    if (window.aapSearchInitialized) return;
    window.aapSearchInitialized = true;

    var currentSiteUrl = window.location.protocol + '//' + window.location.hostname;

    var searchHistory = JSON.parse(
        localStorage.getItem(
            "aap_searchHistory_" +
            currentSiteUrl.replace(/[^a-zA-Z0-9]/g, '_')
        )
    ) || [];

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
            searchInput.value =
                query +
                " free download site:" +
                currentSiteUrl.replace(/^https?:\/\//, '');

            searchHistory.unshift(query);

            if (searchHistory.length > 5) {
                searchHistory.pop();
            }

            aap_updateSearchHistory();
            aap_updateLocalStorage();

            setTimeout(function () {
                searchForm.submit();
                searchInput.value = "";
                aap_updateInputState();
            }, 100);
        }
    }

    function aap_updateSearchHistory() {
        var historyElement = document.getElementById(
            "aap_metatv-google-search-history"
        );

        if (historyElement) {
            historyElement.innerHTML = searchHistory.length
                ? "<p><strong>Recent:</strong> " +
                  searchHistory.join(", ") +
                  "</p>"
                : "<p>No recent searches</p>";
        }
    }

    function aap_updateLocalStorage() {
        localStorage.setItem(
            "aap_searchHistory_" +
            currentSiteUrl.replace(/[^a-zA-Z0-9]/g, '_'),
            JSON.stringify(searchHistory)
        );
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

    searchForm.addEventListener("submit", aap_appendExtraQuery);
    clearHistoryBtn.addEventListener("click", aap_clearSearchHistory);
    clearInputBtn.addEventListener("click", aap_clearInput);
    searchInput.addEventListener("input", aap_updateInputState);

    aap_updateSearchHistory();
    aap_updateInputState();
});
</script>
	</section>
     
	<?php if ( ! empty( $home_settings['show_quick_links'] ) ) : ?>
	<nav class="lnt-home-quick" aria-label="<?php esc_attr_e( 'Homepage sections', 'singlo' ); ?>">
		<div class="lnt-home-shell lnt-home-quick__grid">
			<?php
			$quick_link_number = 0;
			foreach ( $home_section_keys as $section_key ) :
				if ( empty( $home_quick_links[ $section_key ]['enabled'] ) ) {
					continue;
				}

				$quick_link_number++;
				$quick_link = $home_quick_links[ $section_key ];
				?>
				<a href="<?php echo esc_attr( $quick_link['href'] ); ?>">
					<span class="lnt-home-quick__number"><?php echo esc_html( sprintf( '%02d', $quick_link_number ) ); ?></span>
					<span><strong><?php echo esc_html( $quick_link['title'] ); ?></strong><small><?php echo esc_html( $quick_link['text'] ); ?></small></span>
				</a>
			<?php endforeach; ?>
			<?php if ( ! empty( $home_settings['show_trending_carousel'] ) ) : ?>
				<?php $quick_link_number++; ?>
				<a href="#trending-apps-carousel">
					<span class="lnt-home-quick__number"><?php echo esc_html( sprintf( '%02d', $quick_link_number ) ); ?></span>
					<span><strong><?php echo esc_html( $home_settings['quick_trending_title'] ); ?></strong><small><?php echo esc_html( $home_settings['quick_trending_text'] ); ?></small></span>
				</a>
			<?php endif; ?>
		</div>
	</nav>
	<?php endif; ?>

	<?php if ( ! empty( $home_settings['show_trending_carousel'] ) ) : ?>
	<div class="lnt-home-trending-area">
		<div class="lnt-home-trending-wrap">
			<section id="trending-apps-carousel" class="lnt-home-carousel-section lnt-home-trending">
				<header class="lnt-home-trending__header">
					<h2><?php echo esc_html( $home_settings['trending_title'] ); ?></h2>
					<?php if ( ! empty( $home_settings['trending_button_label'] ) ) : ?>
						<a href="<?php echo esc_url( $trending_url ); ?>">
							<span class="screen-reader-text"><?php echo esc_html( $home_settings['trending_button_label'] ); ?></span>
							<span aria-hidden="true">&#8250;</span>
						</a>
					<?php endif; ?>
				</header>

				<div class="lnt-home-carousel lnt-home-trending__carousel" data-home-carousel>
					<div class="lnt-home-carousel__viewport" data-carousel-viewport tabindex="0">
						<div class="lnt-home-carousel__track">
							<?php
							$trending_count = min( 20, max( 1, absint( $home_settings['trending_count'] ) ) );
							$trending_query = singlo_get_trending_apps_query( $trending_count );
							$trending_rank  = 0;

							if ( $trending_query->have_posts() ) :
								while ( $trending_query->have_posts() ) :
									$trending_query->the_post();
									$trending_rank++;
									$trending_rank_class = $trending_rank >= 10
										? ' lnt-home-trending-card--double-rank'
										: '';
									?>
									<article id="trending-post-<?php the_ID(); ?>" <?php post_class( 'lnt-home-carousel__slide lnt-home-trending-card' . $trending_rank_class ); ?>>
										<a class="lnt-home-trending-card__link" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
											<span class="lnt-home-trending-card__rank" aria-label="<?php echo esc_attr( sprintf( __( 'Rank %d', 'singlo' ), $trending_rank ) ); ?>">
												<?php echo esc_html( $trending_rank ); ?>
											</span>
											<span class="lnt-home-trending-card__thumb">
												<?php if ( has_post_thumbnail() ) : ?>
													<?php the_post_thumbnail( 'thumbnail', array( 'loading' => 'lazy', 'decoding' => 'async' ) ); ?>
												<?php else : ?>
													<span class="lnt-home-app-card__fallback" aria-hidden="true"><?php echo esc_html( substr( wp_strip_all_tags( get_the_title() ), 0, 1 ) ); ?></span>
												<?php endif; ?>
											</span>
											<span class="lnt-home-trending-card__body">
												<h3><?php the_title(); ?></h3>
												<span class="lnt-home-trending-card__description"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 13 ) ); ?></span>
											</span>
										</a>
									</article>
									<?php
								endwhile;
								wp_reset_postdata();
							else :
								?>
								<p class="lnt-home-empty"><?php esc_html_e( 'No trending apps are available yet.', 'singlo' ); ?></p>
							<?php endif; ?>
						</div>
					</div>

					<div class="lnt-home-carousel__controls" aria-label="<?php esc_attr_e( 'Trending apps carousel controls', 'singlo' ); ?>">
						<button type="button" class="lnt-home-carousel__button" data-carousel-prev aria-label="<?php esc_attr_e( 'Previous apps', 'singlo' ); ?>">
							<span aria-hidden="true">&#8249;</span>
						</button>
						<button type="button" class="lnt-home-carousel__button" data-carousel-next aria-label="<?php esc_attr_e( 'Next apps', 'singlo' ); ?>">
							<span aria-hidden="true">&#8250;</span>
						</button>
					</div>
				</div>
			</section>
		</div>
	</div>
	<?php endif; ?>

	<div class="lnt-home-content">
		<div class="lnt-home-shell lnt-home-layout<?php echo empty( $home_settings['show_sidebar'] ) ? ' lnt-home-layout--no-sidebar' : ''; ?>">
			<div class="lnt-home-main">
				<?php if ( ! empty( $home_settings['show_recent_apps'] ) ) : ?>
				<section id="recent-apps" class="lnt-home-section" style="order: <?php echo esc_attr( $home_section_order( 'recent_apps' ) ); ?>;">
					<header class="lnt-home-section__header">
						<div>
							<p class="lnt-home-eyebrow"><?php echo esc_html( $home_settings['apps_eyebrow'] ); ?></p>
							<h2><?php echo esc_html( $home_settings['apps_title'] ); ?></h2>
						</div>
						<a href="<?php echo esc_url( $updates_url ); ?>"><?php echo esc_html( $home_settings['apps_button_label'] ); ?></a>
					</header>

					<div class="lnt-home-app-grid">
						<?php
						$apps_query = singlo_get_content_query( 'app', 'recent', $home_settings['apps_count'], false );

						if ( $apps_query->have_posts() ) :
							while ( $apps_query->have_posts() ) :
								$apps_query->the_post();
								$app_id      = get_the_ID();
								$app_version = get_post_meta( $app_id, '_singlo_app_version', true );
								$app_size    = get_post_meta( $app_id, '_singlo_app_size', true );
								$app_rating  = singlo_get_rating_value( $app_id );
								?>
								<article id="post-<?php the_ID(); ?>" <?php post_class( 'lnt-home-app-card' ); ?>>
									<a class="lnt-home-app-card__thumb" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
										<?php if ( has_post_thumbnail() ) : ?>
											<?php the_post_thumbnail( 'thumbnail', array( 'loading' => 'lazy', 'decoding' => 'async' ) ); ?>
										<?php else : ?>
											<span class="lnt-home-app-card__fallback" aria-hidden="true"><?php echo esc_html( substr( wp_strip_all_tags( get_the_title() ), 0, 1 ) ); ?></span>
										<?php endif; ?>
									</a>

									<div class="lnt-home-app-card__body">
										<p class="lnt-home-app-card__updated"><?php echo esc_html( get_the_modified_date( 'j M Y' ) ); ?></p>
										<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
										<div class="lnt-home-app-card__details">
											<?php if ( $app_version ) : ?>
												<span>v<?php echo esc_html( $app_version ); ?></span>
											<?php endif; ?>
											<?php if ( $app_size ) : ?>
												<span><?php echo esc_html( $app_size ); ?></span>
											<?php endif; ?>
										</div>
										<div class="lnt-home-app-card__stats">
											<span class="lnt-home-rating"><?php echo esc_html( $app_rating ); ?> &#9733;</span>
											<span><?php echo esc_html( singlo_get_post_views_text( $app_id ) ); ?></span>
										</div>
									</div>
								</article>
								<?php
							endwhile;
							wp_reset_postdata();
						else :
							?>
							<p class="lnt-home-empty"><?php esc_html_e( 'No app updates are available yet.', 'singlo' ); ?></p>
						<?php endif; ?>
					</div>
				</section>
				<?php endif; ?>

				<?php
				/*
				 * Category Apps section.
				 *
				 * Controlled from:
				 * Theme Settings > Homepage Builder > Category Apps
				 */
				$show_category_apps = ! empty( $home_settings['show_category_apps'] );
				$category_apps_id   = isset( $home_settings['category_apps_id'] )
					? absint( $home_settings['category_apps_id'] )
					: 0;
				$category_apps_term = $category_apps_id ? get_category( $category_apps_id ) : null;
				$category_apps_count = isset( $home_settings['category_apps_count'] )
					? min( 12, max( 1, absint( $home_settings['category_apps_count'] ) ) )
					: 6;
				$category_apps_eyebrow = isset( $home_settings['category_apps_eyebrow'] )
					? $home_settings['category_apps_eyebrow']
					: __( 'Featured collection', 'singlo' );
				$category_apps_title = isset( $home_settings['category_apps_title'] )
					? $home_settings['category_apps_title']
					: '';
				$category_button_label = isset( $home_settings['category_button_label'] )
					? $home_settings['category_button_label']
					: __( 'View all', 'singlo' );
				?>

				<?php if ( $show_category_apps && $category_apps_term && ! is_wp_error( $category_apps_term ) ) : ?>
				<section id="category-apps" class="lnt-home-section" style="order: <?php echo esc_attr( $home_section_order( 'category_apps' ) ); ?>;">
					<header class="lnt-home-section__header">
						<div>
							<?php if ( $category_apps_eyebrow ) : ?>
								<p class="lnt-home-eyebrow"><?php echo esc_html( $category_apps_eyebrow ); ?></p>
							<?php endif; ?>
							<h2>
								<?php echo esc_html( $category_apps_title ? $category_apps_title : $category_apps_term->name ); ?>
							</h2>
						</div>

						<?php if ( $category_button_label ) : ?>
							<a href="<?php echo esc_url( get_category_link( $category_apps_term->term_id ) ); ?>">
								<?php echo esc_html( $category_button_label ); ?>
							</a>
						<?php endif; ?>
					</header>

					<div class="lnt-home-app-grid">
						<?php
						$category_apps_query = new WP_Query(
							array(
								'post_type'           => array( 'post', 'page' ),
								'post_status'         => 'publish',
								'posts_per_page'      => $category_apps_count,
								'cat'                 => absint( $category_apps_term->term_id ),
								'orderby'             => 'modified',
								'order'               => 'DESC',
								'ignore_sticky_posts' => true,
								'no_found_rows'       => true,
							)
						);

						if ( $category_apps_query->have_posts() ) :
							while ( $category_apps_query->have_posts() ) :
								$category_apps_query->the_post();
								$category_app_id      = get_the_ID();
								$category_app_version = get_post_meta( $category_app_id, '_singlo_app_version', true );
								$category_app_size    = get_post_meta( $category_app_id, '_singlo_app_size', true );
								$category_app_rating  = singlo_get_rating_value( $category_app_id );
								?>
								<article id="category-post-<?php the_ID(); ?>" <?php post_class( 'lnt-home-app-card' ); ?>>
									<a class="lnt-home-app-card__thumb" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
										<?php if ( has_post_thumbnail() ) : ?>
											<?php the_post_thumbnail( 'thumbnail', array( 'loading' => 'lazy', 'decoding' => 'async' ) ); ?>
										<?php else : ?>
											<span class="lnt-home-app-card__fallback" aria-hidden="true">
												<?php echo esc_html( substr( wp_strip_all_tags( get_the_title() ), 0, 1 ) ); ?>
											</span>
										<?php endif; ?>
									</a>

									<div class="lnt-home-app-card__body">
										<p class="lnt-home-app-card__updated"><?php echo esc_html( get_the_modified_date( 'j M Y' ) ); ?></p>
										<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

										<div class="lnt-home-app-card__details">
											<?php if ( $category_app_version ) : ?>
												<span>v<?php echo esc_html( $category_app_version ); ?></span>
											<?php endif; ?>
											<?php if ( $category_app_size ) : ?>
												<span><?php echo esc_html( $category_app_size ); ?></span>
											<?php endif; ?>
										</div>

										<div class="lnt-home-app-card__stats">
											<span class="lnt-home-rating"><?php echo esc_html( $category_app_rating ); ?> &#9733;</span>
											<span><?php echo esc_html( singlo_get_post_views_text( $category_app_id ) ); ?></span>
										</div>
									</div>
								</article>
								<?php
							endwhile;
							wp_reset_postdata();
						else :
							?>
							<p class="lnt-home-empty"><?php esc_html_e( 'No apps are available in this category yet.', 'singlo' ); ?></p>
						<?php endif; ?>
					</div>
				</section>
				<?php endif; ?>

				<?php if ( ! empty( $home_settings['show_recent_guides'] ) ) : ?>
				<section
					id="recent-guides"
					class="lnt-home-section lnt-home-guides lnt-home-carousel-section"
					style="order: <?php echo esc_attr( $home_section_order( 'recent_guides' ) ); ?>;"
				>
					<header class="lnt-home-section__header">
						<div>
							<p class="lnt-home-eyebrow"><?php echo esc_html( $home_settings['guides_eyebrow'] ); ?></p>
							<h2><?php echo esc_html( $home_settings['guides_title'] ); ?></h2>
						</div>
						<div class="lnt-home-section__actions">
							<a class="lnt-home-section__view-all" href="<?php echo esc_url( $guides_url ); ?>"><?php echo esc_html( $home_settings['guides_button_label'] ); ?></a>
							<div class="lnt-home-carousel__controls" aria-label="<?php esc_attr_e( 'Guide carousel controls', 'singlo' ); ?>">
								<button type="button" class="lnt-home-carousel__button" data-carousel-prev aria-label="<?php esc_attr_e( 'Previous guides', 'singlo' ); ?>">
									<span aria-hidden="true">&#8592;</span>
								</button>
								<button type="button" class="lnt-home-carousel__button" data-carousel-next aria-label="<?php esc_attr_e( 'Next guides', 'singlo' ); ?>">
									<span aria-hidden="true">&#8594;</span>
								</button>
							</div>
						</div>
					</header>

					<div class="lnt-home-carousel" data-home-carousel>
						<div class="lnt-home-carousel__viewport" data-carousel-viewport tabindex="0">
							<div class="lnt-home-carousel__track">
								<?php
								$guides_query = singlo_get_content_query( 'guide', 'recent', $home_settings['guides_count'], false );

								if ( $guides_query->have_posts() ) :
									while ( $guides_query->have_posts() ) :
										$guides_query->the_post();
										?>
										<article id="guide-post-<?php the_ID(); ?>" <?php post_class( 'lnt-home-carousel__slide lnt-home-guide-card' ); ?>>
											<?php if ( has_post_thumbnail() ) : ?>
												<a class="lnt-home-guide-card__thumb" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
													<?php the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy', 'decoding' => 'async' ) ); ?>
												</a>
											<?php endif; ?>

											<div class="lnt-home-guide-card__body">
												<div class="lnt-home-guide-card__meta">
													<span><?php echo esc_html( get_the_modified_date( 'j M Y' ) ); ?></span>
													<span><?php echo esc_html( singlo_get_post_views_text( get_the_ID() ) ); ?></span>
												</div>
												<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
												<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 18 ) ); ?></p>
												<a class="lnt-home-text-link" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read guide', 'singlo' ); ?></a>
											</div>
										</article>
										<?php
									endwhile;
									wp_reset_postdata();
								else :
									?>
									<p class="lnt-home-empty"><?php esc_html_e( 'No guides are available yet.', 'singlo' ); ?></p>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</section>
				<?php endif; ?>
				<?php singlo_render_homepage_custom_sections( 'all', $home_section_order( 'custom_sections' ) ); ?>
			</div>

			<?php if ( ! empty( $home_settings['show_sidebar'] ) ) : ?>
			<aside class="lnt-home-sidebar" aria-label="<?php esc_attr_e( 'Popular and recent content', 'singlo' ); ?>">
				<div class="ct-sidebar" data-widgets="separated">
					<?php if ( ! empty( $home_settings['show_trending_apps'] ) ) : ?>
					<div id="trending-apps">
						<?php singlo_render_sidebar_trending_apps_widget( $home_settings['trending_apps_count'] ); ?>
					</div>
					<?php endif; ?>
					<?php if ( ! empty( $home_settings['show_trending_posts'] ) ) : ?>
						<?php singlo_render_sidebar_trending_posts_widget( $home_settings['trending_posts_count'] ); ?>
					<?php endif; ?>
					<?php if ( ! empty( $home_settings['show_sidebar_updates'] ) ) : ?>
						<?php singlo_render_sidebar_recent_updates_widget( $home_settings['sidebar_updates_count'] ); ?>
					<?php endif; ?>
					<?php if ( ! empty( $home_settings['show_sidebar_guides'] ) ) : ?>
						<?php singlo_render_sidebar_recent_guides_widget( $home_settings['sidebar_guides_count'] ); ?>
					<?php endif; ?>
				</div>
			</aside>
			<?php endif; ?>
		</div>

	</div>
</main>

<?php
get_footer();
