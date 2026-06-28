<?php
/**
 * Homepage Builder settings.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function singlo_homepage_default_settings() {
	return array(
		'hero_eyebrow'          => __( 'Apps, updates and guides', 'singlo' ),
		'hero_title'            => '',
		'hero_description'      => '',
		'hero_background_color' => '#d8e8eb',
		'hero_media_type'       => 'image',
		'hero_image_id'         => 0,
		'hero_video_id'         => 0,
		'hero_primary_label'    => __( 'View latest app', 'singlo' ),
		'hero_primary_url'      => '',
		'hero_secondary_label'  => __( 'Browse all apps', 'singlo' ),
		'hero_secondary_url'    => '',
		'show_quick_links'      => 1,
		'quick_apps_title'      => __( 'Latest apps', 'singlo' ),
		'quick_apps_text'       => __( 'New and recently updated', 'singlo' ),
		'quick_guides_title'    => __( 'Recent guides', 'singlo' ),
		'quick_guides_text'     => __( 'Setup and troubleshooting', 'singlo' ),
		'quick_trending_title'  => __( 'Trending now', 'singlo' ),
		'quick_trending_text'   => __( 'Ranked by real views', 'singlo' ),
		'app_layout_style'      => 'list',
		'app_columns_desktop'   => 2,
		'app_columns_tablet'    => 2,
		'app_columns_mobile'    => 2,
		'show_recent_apps'      => 1,
		'apps_eyebrow'          => __( 'Recently updated', 'singlo' ),
		'apps_title'            => __( 'Apps', 'singlo' ),
		'apps_button_label'     => __( 'View all updates', 'singlo' ),
		'apps_button_url'       => '',
		'apps_count'            => 8,
		'show_trending_carousel' => 1,
		'trending_eyebrow'       => __( 'Popular now', 'singlo' ),
		'trending_title'         => __( 'Top 20 Trending Apps', 'singlo' ),
		'trending_button_label'  => __( 'Browse all apps', 'singlo' ),
		'trending_button_url'    => '',
		'trending_count'         => 20,
		'show_category_apps'    => 0,
		'category_apps_id'      => 0,
		'category_apps_eyebrow' => __( 'Featured collection', 'singlo' ),
		'category_apps_title'   => __( 'Category Apps', 'singlo' ),
		'category_button_label' => __( 'View all', 'singlo' ),
		'category_apps_count'   => 6,
		'show_recent_guides'    => 1,
		'guides_eyebrow'        => __( 'Help and articles', 'singlo' ),
		'guides_title'          => __( 'Recent guides', 'singlo' ),
		'guides_button_label'   => __( 'Browse guides', 'singlo' ),
		'guides_button_url'     => '',
		'guides_count'          => 4,
		'show_sidebar'          => 1,
		'show_trending_apps'    => 1,
		'trending_apps_count'   => 5,
		'show_trending_posts'   => 1,
		'trending_posts_count'  => 5,
		'show_sidebar_updates'  => 1,
		'sidebar_updates_count' => 5,
		'show_sidebar_guides'   => 1,
		'sidebar_guides_count'  => 5,
	);
}

function singlo_get_homepage_settings() {
	$saved = get_theme_mod( 'singlo_homepage_settings', array() );

	return wp_parse_args( is_array( $saved ) ? $saved : array(), singlo_homepage_default_settings() );
}

function singlo_sanitize_homepage_settings( $source ) {
	$defaults = singlo_homepage_default_settings();
	$settings = array();
	$text_fields = array(
		'hero_eyebrow',
		'hero_title',
		'hero_primary_label',
		'hero_secondary_label',
		'quick_apps_title',
		'quick_apps_text',
		'quick_guides_title',
		'quick_guides_text',
		'quick_trending_title',
		'quick_trending_text',
		'apps_eyebrow',
		'apps_title',
		'apps_button_label',
		'trending_eyebrow',
		'trending_title',
		'trending_button_label',
		'category_apps_eyebrow',
		'category_apps_title',
		'category_button_label',
		'guides_eyebrow',
		'guides_title',
		'guides_button_label',
	);
	$url_fields = array(
		'hero_primary_url',
		'hero_secondary_url',
		'apps_button_url',
		'trending_button_url',
		'guides_button_url',
	);
	$checkbox_fields = array(
		'show_quick_links',
		'show_recent_apps',
		'show_trending_carousel',
		'show_category_apps',
		'show_recent_guides',
		'show_sidebar',
		'show_trending_apps',
		'show_trending_posts',
		'show_sidebar_updates',
		'show_sidebar_guides',
	);
	$count_fields = array(
		'app_columns_desktop'   => 4,
		'app_columns_tablet'    => 4,
		'app_columns_mobile'    => 4,
		'apps_count'            => 12,
		'trending_count'        => 20,
		'category_apps_count'   => 12,
		'guides_count'          => 12,
		'trending_apps_count'   => 10,
		'trending_posts_count'  => 10,
		'sidebar_updates_count' => 10,
		'sidebar_guides_count'  => 10,
	);

	foreach ( $text_fields as $key ) {
		$settings[ $key ] = isset( $source[ $key ] ) ? sanitize_text_field( wp_unslash( $source[ $key ] ) ) : $defaults[ $key ];
	}

	foreach ( $url_fields as $key ) {
		$settings[ $key ] = isset( $source[ $key ] ) ? esc_url_raw( wp_unslash( $source[ $key ] ) ) : '';
	}

	foreach ( $checkbox_fields as $key ) {
		$settings[ $key ] = isset( $source[ $key ] ) ? 1 : 0;
	}

	foreach ( $count_fields as $key => $maximum ) {
		$value = isset( $source[ $key ] ) ? absint( $source[ $key ] ) : $defaults[ $key ];
		$settings[ $key ] = min( $maximum, max( 1, $value ) );
	}

	$settings['hero_description'] = isset( $source['hero_description'] )
		? sanitize_textarea_field( wp_unslash( $source['hero_description'] ) )
		: '';
	$hero_background_color = isset( $source['hero_background_color'] )
		? sanitize_hex_color( wp_unslash( $source['hero_background_color'] ) )
		: '';
	$settings['hero_background_color'] = $hero_background_color ? $hero_background_color : $defaults['hero_background_color'];
	$settings['hero_media_type'] = (
		isset( $source['hero_media_type'] )
		&& 'video' === sanitize_key( $source['hero_media_type'] )
	) ? 'video' : 'image';
	$settings['app_layout_style'] = (
		isset( $source['app_layout_style'] )
		&& 'grid' === sanitize_key( $source['app_layout_style'] )
	) ? 'grid' : 'list';
	$settings['hero_image_id'] = isset( $source['hero_image_id'] ) ? absint( $source['hero_image_id'] ) : 0;
	$settings['hero_video_id'] = isset( $source['hero_video_id'] ) ? absint( $source['hero_video_id'] ) : 0;
	$settings['category_apps_id'] = isset( $source['category_apps_id'] ) ? absint( $source['category_apps_id'] ) : 0;

	return $settings;
}

function singlo_homepage_default_section_order() {
	return array(
		'recent_apps',
		'category_apps',
		'recent_guides',
		'custom_sections',
	);
}

function singlo_homepage_section_labels() {
	return array(
		'recent_apps'    => __( 'Recent Apps', 'singlo' ),
		'category_apps'  => __( 'Category Apps', 'singlo' ),
		'recent_guides'  => __( 'Guide Carousel', 'singlo' ),
		'custom_sections' => __( 'Custom Sections', 'singlo' ),
	);
}

function singlo_sanitize_homepage_section_order( $source ) {
	$defaults = singlo_homepage_default_section_order();
	$order    = array();

	if ( is_array( $source ) ) {
		foreach ( $source as $section_key ) {
			$section_key = sanitize_key( wp_unslash( $section_key ) );

			if ( in_array( $section_key, $defaults, true ) && ! in_array( $section_key, $order, true ) ) {
				$order[] = $section_key;
			}
		}
	}

	foreach ( $defaults as $section_key ) {
		if ( ! in_array( $section_key, $order, true ) ) {
			$order[] = $section_key;
		}
	}

	return $order;
}

function singlo_get_homepage_section_order() {
	$saved = get_theme_mod( 'singlo_homepage_section_order', singlo_homepage_default_section_order() );

	return singlo_sanitize_homepage_section_order( $saved );
}

function singlo_sanitize_homepage_sections( $source ) {
	$sections = array();

	if ( ! is_array( $source ) ) {
		return $sections;
	}

	$allowed_positions = array( 'before_apps', 'between_sections', 'after_guides' );

	foreach ( $source as $section ) {
		if ( ! is_array( $section ) ) {
			continue;
		}

		$title   = isset( $section['title'] ) ? sanitize_text_field( wp_unslash( $section['title'] ) ) : '';
		$content = isset( $section['content'] ) ? wp_kses_post( wp_unslash( $section['content'] ) ) : '';

		if ( '' === $title && '' === trim( $content ) ) {
			continue;
		}

		$position = isset( $section['position'] ) ? sanitize_key( $section['position'] ) : 'after_guides';
		$position = in_array( $position, $allowed_positions, true ) ? $position : 'after_guides';

		$sections[] = array(
			'enabled'  => isset( $section['enabled'] ) ? 1 : 0,
			'title'    => $title,
			'position' => $position,
			'content'  => $content,
		);
	}

	return array_slice( $sections, 0, 20 );
}

function singlo_add_homepage_builder_menu() {
	add_submenu_page(
		'singlo-settings',
		__( 'Homepage Builder', 'singlo' ),
		__( 'Homepage Builder', 'singlo' ),
		'manage_options',
		'singlo-homepage-builder',
		'singlo_homepage_builder_page'
	);
}
add_action( 'admin_menu', 'singlo_add_homepage_builder_menu' );

function singlo_homepage_builder_admin_assets( $hook ) {
	if ( 'singlo-settings_page_singlo-homepage-builder' !== $hook ) {
		return;
	}

	wp_enqueue_media();
	wp_enqueue_script( 'jquery-ui-sortable' );
}
add_action( 'admin_enqueue_scripts', 'singlo_homepage_builder_admin_assets' );

function singlo_render_homepage_custom_sections( $position, $display_order = null ) {
	$sections = get_theme_mod( 'singlo_homepage_sections', array() );

	if ( ! is_array( $sections ) ) {
		return;
	}

	foreach ( $sections as $section ) {
		$section_position = isset( $section['position'] ) ? $section['position'] : 'after_guides';

		if (
			empty( $section['enabled'] )
			|| empty( $section['content'] )
			|| ( 'all' !== $position && $position !== $section_position )
		) {
			continue;
		}
		?>
		<section
			class="lnt-home-section lnt-home-custom-section"
			<?php if ( null !== $display_order ) : ?>style="order: <?php echo esc_attr( (int) $display_order ); ?>;"<?php endif; ?>
		>
			<?php if ( ! empty( $section['title'] ) ) : ?>
				<header class="lnt-home-section__header">
					<div><h2><?php echo esc_html( $section['title'] ); ?></h2></div>
				</header>
			<?php endif; ?>
			<div class="lnt-home-custom-section__content">
				<?php echo do_shortcode( wpautop( wp_kses_post( $section['content'] ) ) ); ?>
			</div>
		</section>
		<?php
	}
}

function singlo_homepage_builder_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	if (
		isset( $_POST['singlo_save_homepage'] )
		&& check_admin_referer( 'singlo_homepage_builder_action', 'singlo_homepage_builder_nonce' )
	) {
		$settings = isset( $_POST['singlo_homepage'] )
			? singlo_sanitize_homepage_settings( $_POST['singlo_homepage'] )
			: singlo_homepage_default_settings();
		$sections = isset( $_POST['singlo_homepage_sections'] )
			? singlo_sanitize_homepage_sections( $_POST['singlo_homepage_sections'] )
			: array();
		$section_order = isset( $_POST['singlo_homepage_section_order'] )
			? singlo_sanitize_homepage_section_order( $_POST['singlo_homepage_section_order'] )
			: singlo_homepage_default_section_order();

		set_theme_mod( 'singlo_homepage_settings', $settings );
		set_theme_mod( 'singlo_homepage_sections', $sections );
		set_theme_mod( 'singlo_homepage_section_order', $section_order );

		echo '<div class="notice notice-success is-dismissible"><p><strong>' . esc_html__( 'Homepage settings saved.', 'singlo' ) . '</strong></p></div>';
	}

	$settings = singlo_get_homepage_settings();
	$sections = get_theme_mod( 'singlo_homepage_sections', array() );
	$sections = is_array( $sections ) ? $sections : array();
	$section_order = singlo_get_homepage_section_order();
	$section_labels = singlo_homepage_section_labels();
	$image_url = $settings['hero_image_id'] ? wp_get_attachment_image_url( $settings['hero_image_id'], 'medium' ) : '';
	$video_url = $settings['hero_video_id'] ? wp_get_attachment_url( $settings['hero_video_id'] ) : '';
	?>
	<div class="wrap singlo-home-builder">
		<h1><?php esc_html_e( 'Homepage Builder', 'singlo' ); ?></h1>
		<p><?php esc_html_e( 'Control homepage text, images, content sections, sidebar widgets, and custom HTML or shortcode sections.', 'singlo' ); ?></p>

		<form method="post">
			<?php wp_nonce_field( 'singlo_homepage_builder_action', 'singlo_homepage_builder_nonce' ); ?>

			<div class="singlo-home-builder__card">
				<h2><?php esc_html_e( 'Hero Section', 'singlo' ); ?></h2>
				<div class="singlo-home-builder__grid">
					<?php singlo_homepage_text_field( 'hero_eyebrow', __( 'Small heading', 'singlo' ), $settings ); ?>
					<?php singlo_homepage_text_field( 'hero_title', __( 'Main heading', 'singlo' ), $settings, __( 'Leave empty to use the site name.', 'singlo' ) ); ?>
					<div class="singlo-home-builder__field">
						<label for="singlo-hero-background-color"><?php esc_html_e( 'Hero background color', 'singlo' ); ?></label>
						<input type="color" id="singlo-hero-background-color" name="singlo_homepage[hero_background_color]" value="<?php echo esc_attr( $settings['hero_background_color'] ); ?>">
					</div>
				</div>

				<label for="singlo-hero-description"><?php esc_html_e( 'Description', 'singlo' ); ?></label>
				<textarea id="singlo-hero-description" name="singlo_homepage[hero_description]" rows="4"><?php echo esc_textarea( $settings['hero_description'] ); ?></textarea>

				<div class="singlo-home-builder__media">
					<label for="singlo-hero-media-type"><?php esc_html_e( 'Hero media type', 'singlo' ); ?></label>
					<select id="singlo-hero-media-type" name="singlo_homepage[hero_media_type]">
						<option value="image" <?php selected( $settings['hero_media_type'], 'image' ); ?>><?php esc_html_e( 'Image', 'singlo' ); ?></option>
						<option value="video" <?php selected( $settings['hero_media_type'], 'video' ); ?>><?php esc_html_e( 'Video clip', 'singlo' ); ?></option>
					</select>

					<div class="singlo-home-builder__media-panel singlo-home-builder__media-panel--image">
						<label><?php esc_html_e( 'Hero image', 'singlo' ); ?></label>
						<div class="singlo-home-builder__image-preview">
						<?php if ( $image_url ) : ?>
							<img src="<?php echo esc_url( $image_url ); ?>" alt="">
						<?php endif; ?>
						</div>
						<input type="hidden" id="singlo-hero-image-id" name="singlo_homepage[hero_image_id]" value="<?php echo esc_attr( $settings['hero_image_id'] ); ?>">
						<button type="button" class="button button-secondary singlo-select-hero-image"><?php esc_html_e( 'Choose image', 'singlo' ); ?></button>
						<button type="button" class="button singlo-remove-hero-image"><?php esc_html_e( 'Remove image', 'singlo' ); ?></button>
						<p class="description"><?php esc_html_e( 'When empty, the newest app image is used.', 'singlo' ); ?></p>
					</div>

					<div class="singlo-home-builder__media-panel singlo-home-builder__media-panel--video">
						<label><?php esc_html_e( 'Hero video clip', 'singlo' ); ?></label>
						<div class="singlo-home-builder__video-preview">
							<?php if ( $video_url ) : ?>
								<video src="<?php echo esc_url( $video_url ); ?>" muted controls playsinline></video>
							<?php endif; ?>
						</div>
						<input type="hidden" id="singlo-hero-video-id" name="singlo_homepage[hero_video_id]" value="<?php echo esc_attr( $settings['hero_video_id'] ); ?>">
						<button type="button" class="button button-secondary singlo-select-hero-video"><?php esc_html_e( 'Choose video', 'singlo' ); ?></button>
						<button type="button" class="button singlo-remove-hero-video"><?php esc_html_e( 'Remove video', 'singlo' ); ?></button>
						<p class="description"><?php esc_html_e( 'Use a short optimized MP4 or WebM clip. It plays muted, loops, and stays inline on mobile.', 'singlo' ); ?></p>
					</div>
				</div>

				<div class="singlo-home-builder__grid">
					<?php singlo_homepage_text_field( 'hero_primary_label', __( 'Primary button label', 'singlo' ), $settings ); ?>
					<?php singlo_homepage_url_field( 'hero_primary_url', __( 'Primary button URL', 'singlo' ), $settings ); ?>
					<?php singlo_homepage_text_field( 'hero_secondary_label', __( 'Secondary button label', 'singlo' ), $settings ); ?>
					<?php singlo_homepage_url_field( 'hero_secondary_url', __( 'Secondary button URL', 'singlo' ), $settings ); ?>
				</div>
			</div>

			<div class="singlo-home-builder__card">
				<h2><?php esc_html_e( 'Homepage Content Order', 'singlo' ); ?></h2>
				<p><?php esc_html_e( 'Drag these sections into the order you want. The saved order is used immediately on the homepage.', 'singlo' ); ?></p>
				<ul id="singlo-homepage-section-order" class="singlo-homepage-section-order">
					<?php foreach ( $section_order as $section_key ) : ?>
						<li class="singlo-homepage-section-order__item">
							<span class="dashicons dashicons-move singlo-homepage-section-order__handle" aria-hidden="true"></span>
							<strong><?php echo esc_html( isset( $section_labels[ $section_key ] ) ? $section_labels[ $section_key ] : $section_key ); ?></strong>
							<input type="hidden" name="singlo_homepage_section_order[]" value="<?php echo esc_attr( $section_key ); ?>">
						</li>
					<?php endforeach; ?>
				</ul>
			</div>

			<div class="singlo-home-builder__card">
				<h2><?php esc_html_e( 'Main Homepage Sections', 'singlo' ); ?></h2>
				<?php singlo_homepage_checkbox( 'show_quick_links', __( 'Show quick navigation links', 'singlo' ), $settings ); ?>
				<div class="singlo-home-builder__grid">
					<?php singlo_homepage_text_field( 'quick_apps_title', __( 'Apps quick-link title', 'singlo' ), $settings ); ?>
					<?php singlo_homepage_text_field( 'quick_apps_text', __( 'Apps quick-link text', 'singlo' ), $settings ); ?>
					<?php singlo_homepage_text_field( 'quick_guides_title', __( 'Guides quick-link title', 'singlo' ), $settings ); ?>
					<?php singlo_homepage_text_field( 'quick_guides_text', __( 'Guides quick-link text', 'singlo' ), $settings ); ?>
					<?php singlo_homepage_text_field( 'quick_trending_title', __( 'Trending quick-link title', 'singlo' ), $settings ); ?>
					<?php singlo_homepage_text_field( 'quick_trending_text', __( 'Trending quick-link text', 'singlo' ), $settings ); ?>
				</div>

				<h3><?php esc_html_e( 'Homepage App Layout', 'singlo' ); ?></h3>
				<p class="description"><?php esc_html_e( 'These settings apply to Recent Apps, Category Apps, and Trending Apps on the homepage only.', 'singlo' ); ?></p>
				<div class="singlo-home-builder__grid">
					<div class="singlo-home-builder__field">
						<label for="singlo-app-layout-style"><?php esc_html_e( 'App card style', 'singlo' ); ?></label>
						<select id="singlo-app-layout-style" name="singlo_homepage[app_layout_style]">
							<option value="list" <?php selected( $settings['app_layout_style'], 'list' ); ?>><?php esc_html_e( 'List - image beside details', 'singlo' ); ?></option>
							<option value="grid" <?php selected( $settings['app_layout_style'], 'grid' ); ?>><?php esc_html_e( 'Grid - image above details', 'singlo' ); ?></option>
						</select>
					</div>
					<?php singlo_homepage_columns_field( 'app_columns_desktop', __( 'Desktop columns', 'singlo' ), $settings ); ?>
					<?php singlo_homepage_columns_field( 'app_columns_tablet', __( 'Tablet columns', 'singlo' ), $settings ); ?>
					<?php singlo_homepage_columns_field( 'app_columns_mobile', __( 'Mobile columns', 'singlo' ), $settings ); ?>
				</div>

				<h3><?php esc_html_e( 'Recent Apps', 'singlo' ); ?></h3>
				<?php singlo_homepage_checkbox( 'show_recent_apps', __( 'Show recent apps section', 'singlo' ), $settings ); ?>
				<div class="singlo-home-builder__grid">
					<?php singlo_homepage_text_field( 'apps_eyebrow', __( 'Small heading', 'singlo' ), $settings ); ?>
					<?php singlo_homepage_text_field( 'apps_title', __( 'Section heading', 'singlo' ), $settings ); ?>
					<?php singlo_homepage_text_field( 'apps_button_label', __( 'Button label', 'singlo' ), $settings ); ?>
					<?php singlo_homepage_url_field( 'apps_button_url', __( 'Button URL', 'singlo' ), $settings ); ?>
					<?php singlo_homepage_number_field( 'apps_count', __( 'Number of apps', 'singlo' ), $settings, 12 ); ?>
				</div>

				<h3><?php esc_html_e( 'Category Apps', 'singlo' ); ?></h3>
				<p class="description"><?php esc_html_e( 'Create another app section and choose which WordPress category supplies its apps.', 'singlo' ); ?></p>
				<?php singlo_homepage_checkbox( 'show_category_apps', __( 'Show category apps section', 'singlo' ), $settings ); ?>
				<div class="singlo-home-builder__grid">
					<?php singlo_homepage_category_field( 'category_apps_id', __( 'Apps category', 'singlo' ), $settings ); ?>
					<?php singlo_homepage_text_field( 'category_apps_eyebrow', __( 'Small heading', 'singlo' ), $settings ); ?>
					<?php singlo_homepage_text_field( 'category_apps_title', __( 'Section heading', 'singlo' ), $settings, __( 'Leave empty to use the selected category name.', 'singlo' ) ); ?>
					<?php singlo_homepage_text_field( 'category_button_label', __( 'Button label', 'singlo' ), $settings ); ?>
					<?php singlo_homepage_number_field( 'category_apps_count', __( 'Number of apps', 'singlo' ), $settings, 12 ); ?>
				</div>

				<h3><?php esc_html_e( 'Recent Guides', 'singlo' ); ?></h3>
				<?php singlo_homepage_checkbox( 'show_recent_guides', __( 'Show recent guides section', 'singlo' ), $settings ); ?>
				<div class="singlo-home-builder__grid">
					<?php singlo_homepage_text_field( 'guides_eyebrow', __( 'Small heading', 'singlo' ), $settings ); ?>
					<?php singlo_homepage_text_field( 'guides_title', __( 'Section heading', 'singlo' ), $settings ); ?>
					<?php singlo_homepage_text_field( 'guides_button_label', __( 'Button label', 'singlo' ), $settings ); ?>
					<?php singlo_homepage_url_field( 'guides_button_url', __( 'Button URL', 'singlo' ), $settings ); ?>
					<?php singlo_homepage_number_field( 'guides_count', __( 'Number of guides', 'singlo' ), $settings, 12 ); ?>
				</div>
			</div>

			<div class="singlo-home-builder__card">
				<h2><?php esc_html_e( 'Top Trending Apps Carousel', 'singlo' ); ?></h2>
				<p class="description"><?php esc_html_e( 'This full-width carousel appears directly below the homepage quick links and is ranked automatically by real recorded views.', 'singlo' ); ?></p>
				<?php singlo_homepage_checkbox( 'show_trending_carousel', __( 'Show trending apps carousel', 'singlo' ), $settings ); ?>
				<div class="singlo-home-builder__grid">
					<?php singlo_homepage_text_field( 'trending_title', __( 'Section heading', 'singlo' ), $settings ); ?>
					<?php singlo_homepage_text_field( 'trending_button_label', __( 'Button label', 'singlo' ), $settings ); ?>
					<?php singlo_homepage_url_field( 'trending_button_url', __( 'Button URL', 'singlo' ), $settings ); ?>
					<?php singlo_homepage_number_field( 'trending_count', __( 'Number of apps', 'singlo' ), $settings, 20 ); ?>
				</div>
			</div>

			<div class="singlo-home-builder__card">
				<h2><?php esc_html_e( 'Sidebar', 'singlo' ); ?></h2>
				<?php singlo_homepage_checkbox( 'show_sidebar', __( 'Show homepage sidebar', 'singlo' ), $settings ); ?>
				<div class="singlo-home-builder__grid">
					<?php singlo_homepage_toggle_count( 'show_trending_apps', 'trending_apps_count', __( 'Trending Apps', 'singlo' ), $settings ); ?>
					<?php singlo_homepage_toggle_count( 'show_trending_posts', 'trending_posts_count', __( 'Trending Posts', 'singlo' ), $settings ); ?>
					<?php singlo_homepage_toggle_count( 'show_sidebar_updates', 'sidebar_updates_count', __( 'Recent Updates', 'singlo' ), $settings ); ?>
					<?php singlo_homepage_toggle_count( 'show_sidebar_guides', 'sidebar_guides_count', __( 'Recent Guides', 'singlo' ), $settings ); ?>
				</div>
			</div>

			<div class="singlo-home-builder__card">
				<h2><?php esc_html_e( 'Custom Sections', 'singlo' ); ?></h2>
				<p><?php esc_html_e( 'Add HTML and WordPress shortcodes. PHP and JavaScript are intentionally not executed.', 'singlo' ); ?></p>
				<div id="singlo-home-sections">
					<?php foreach ( $sections as $index => $section ) : ?>
						<?php singlo_homepage_section_editor( $index, $section ); ?>
					<?php endforeach; ?>
				</div>
				<button type="button" class="button button-secondary" id="singlo-add-home-section"><?php esc_html_e( 'Add New Section', 'singlo' ); ?></button>
			</div>

			<?php submit_button( __( 'Save Homepage', 'singlo' ), 'primary', 'singlo_save_homepage' ); ?>
		</form>
	</div>

	<script type="text/template" id="tmpl-singlo-home-section">
		<?php singlo_homepage_section_editor( '__INDEX__', array( 'enabled' => 1, 'title' => '', 'position' => 'after_guides', 'content' => '' ) ); ?>
	</script>

	<style>
		.singlo-home-builder{max-width:1120px}.singlo-home-builder__card{margin:22px 0;padding:24px;background:#fff;border:1px solid #dcdcde;border-radius:6px}.singlo-home-builder__card h2{margin-top:0}.singlo-home-builder__card h3{margin-top:28px;padding-top:20px;border-top:1px solid #eee}.singlo-home-builder__grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:18px}.singlo-home-builder label{display:block;margin:0 0 6px;font-weight:600}.singlo-home-builder input[type=text],.singlo-home-builder input[type=url],.singlo-home-builder input[type=number],.singlo-home-builder textarea,.singlo-home-builder select{width:100%}.singlo-home-builder input[type=color]{width:72px;height:40px;padding:2px}.singlo-home-builder__field{margin-bottom:16px}.singlo-home-builder__check{display:flex!important;align-items:center;gap:8px;margin:12px 0!important}.singlo-home-builder__check input{margin:0}.singlo-home-builder__media{margin:20px 0}.singlo-home-builder__media>select{max-width:280px;margin-bottom:15px}.singlo-home-builder__media-panel{margin-top:14px;padding:18px;background:#f6f7f7;border:1px solid #dcdcde;border-radius:5px}.singlo-home-builder__image-preview img,.singlo-home-builder__video-preview video{display:block;max-width:320px;max-height:220px;margin:10px 0;border:1px solid #ddd;border-radius:4px}.singlo-homepage-section-order{display:grid;gap:10px;max-width:680px;margin:18px 0 0}.singlo-homepage-section-order__item{display:flex;align-items:center;gap:12px;margin:0;padding:14px 16px;background:#f6f7f7;border:1px solid #dcdcde;border-radius:5px}.singlo-homepage-section-order__handle{cursor:move;color:#646970}.singlo-homepage-section-order__item.ui-sortable-helper{background:#fff;box-shadow:0 8px 24px rgba(0,0,0,.12)}.singlo-homepage-section-order__item.ui-sortable-placeholder{min-height:50px;visibility:visible!important;background:#f0f6fc;border:1px dashed #2271b1}.singlo-home-section-editor{margin:16px 0;padding:18px;background:#f6f7f7;border:1px solid #dcdcde;border-radius:5px}.singlo-home-section-editor__head{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:14px}.singlo-home-section-editor__handle{cursor:move}.singlo-home-section-preview{display:none;width:100%;min-height:180px;margin-top:14px;background:#fff;border:1px solid #dcdcde}.singlo-home-section-preview.is-visible{display:block}@media(max-width:782px){.singlo-home-builder__grid{grid-template-columns:1fr}}
	</style>

	<script>
	jQuery(function($){
		let sectionIndex = <?php echo absint( count( $sections ) ); ?>;
		let imageFrame;
		let videoFrame;

		function updateHeroMediaPanels() {
			const mediaType = $('#singlo-hero-media-type').val();
			$('.singlo-home-builder__media-panel--image').toggle(mediaType === 'image');
			$('.singlo-home-builder__media-panel--video').toggle(mediaType === 'video');
		}

		$('#singlo-hero-media-type').on('change', updateHeroMediaPanels);
		updateHeroMediaPanels();

		$('.singlo-select-hero-image').on('click', function(){
			if (imageFrame) {
				imageFrame.open();
				return;
			}
			imageFrame = wp.media({
				title: '<?php echo esc_js( __( 'Choose hero image', 'singlo' ) ); ?>',
				button: {text: '<?php echo esc_js( __( 'Use this image', 'singlo' ) ); ?>'},
				library: {type: 'image'},
				multiple: false
			});
			imageFrame.on('select', function(){
				const image = imageFrame.state().get('selection').first().toJSON();
				$('#singlo-hero-image-id').val(image.id);
				$('.singlo-home-builder__image-preview').html('<img src="' + image.url + '" alt="">');
			});
			imageFrame.open();
		});

		$('.singlo-remove-hero-image').on('click', function(){
			$('#singlo-hero-image-id').val('');
			$('.singlo-home-builder__image-preview').empty();
		});

		$('.singlo-select-hero-video').on('click', function(){
			if (videoFrame) {
				videoFrame.open();
				return;
			}
			videoFrame = wp.media({
				title: '<?php echo esc_js( __( 'Choose hero video', 'singlo' ) ); ?>',
				button: {text: '<?php echo esc_js( __( 'Use this video', 'singlo' ) ); ?>'},
				library: {type: 'video'},
				multiple: false
			});
			videoFrame.on('select', function(){
				const video = videoFrame.state().get('selection').first().toJSON();
				$('#singlo-hero-video-id').val(video.id);
				$('.singlo-home-builder__video-preview').html('<video src="' + video.url + '" muted controls playsinline></video>');
			});
			videoFrame.open();
		});

		$('.singlo-remove-hero-video').on('click', function(){
			$('#singlo-hero-video-id').val('');
			$('.singlo-home-builder__video-preview').empty();
		});

		$('#singlo-add-home-section').on('click', function(){
			const template = $('#tmpl-singlo-home-section').html().replaceAll('__INDEX__', sectionIndex++);
			$('#singlo-home-sections').append(template);
		});

		$(document).on('click', '.singlo-remove-home-section', function(){
			$(this).closest('.singlo-home-section-editor').remove();
		});

		$(document).on('click', '.singlo-preview-home-section', function(){
			const editor = $(this).closest('.singlo-home-section-editor');
			const content = editor.find('textarea').val();
			const preview = editor.find('.singlo-home-section-preview');
			preview.prop('srcdoc', '<!doctype html><html><head><meta charset="utf-8"><style>body{font-family:Arial,sans-serif;padding:16px;line-height:1.6;color:#1d2327}img{max-width:100%;height:auto}</style></head><body>' + content + '</body></html>').addClass('is-visible');
		});

		$('#singlo-home-sections').sortable({handle: '.singlo-home-section-editor__handle'});
		$('#singlo-homepage-section-order').sortable({
			handle: '.singlo-homepage-section-order__handle',
			placeholder: 'singlo-homepage-section-order__item ui-sortable-placeholder'
		});
	});
	</script>
	<?php
}

function singlo_homepage_text_field( $key, $label, $settings, $description = '' ) {
	?>
	<div class="singlo-home-builder__field">
		<label for="singlo-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></label>
		<input type="text" id="singlo-<?php echo esc_attr( $key ); ?>" name="singlo_homepage[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $settings[ $key ] ); ?>">
		<?php if ( $description ) : ?><p class="description"><?php echo esc_html( $description ); ?></p><?php endif; ?>
	</div>
	<?php
}

function singlo_homepage_url_field( $key, $label, $settings ) {
	?>
	<div class="singlo-home-builder__field">
		<label for="singlo-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></label>
		<input type="url" id="singlo-<?php echo esc_attr( $key ); ?>" name="singlo_homepage[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_url( $settings[ $key ] ); ?>">
	</div>
	<?php
}

function singlo_homepage_number_field( $key, $label, $settings, $maximum = 10 ) {
	?>
	<div class="singlo-home-builder__field">
		<label for="singlo-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></label>
		<input type="number" min="1" max="<?php echo esc_attr( $maximum ); ?>" id="singlo-<?php echo esc_attr( $key ); ?>" name="singlo_homepage[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $settings[ $key ] ); ?>">
	</div>
	<?php
}

function singlo_homepage_columns_field( $key, $label, $settings ) {
	?>
	<div class="singlo-home-builder__field">
		<label for="singlo-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></label>
		<select id="singlo-<?php echo esc_attr( $key ); ?>" name="singlo_homepage[<?php echo esc_attr( $key ); ?>]">
			<?php for ( $columns = 1; $columns <= 4; $columns++ ) : ?>
				<option value="<?php echo esc_attr( $columns ); ?>" <?php selected( (int) $settings[ $key ], $columns ); ?>>
					<?php
					echo esc_html(
						sprintf(
							_n( '%d column', '%d columns', $columns, 'singlo' ),
							$columns
						)
					);
					?>
				</option>
			<?php endfor; ?>
		</select>
	</div>
	<?php
}

function singlo_homepage_category_field( $key, $label, $settings ) {
	$categories = get_categories(
		array(
			'hide_empty' => false,
			'orderby'    => 'name',
			'order'      => 'ASC',
		)
	);
	?>
	<div class="singlo-home-builder__field">
		<label for="singlo-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></label>
		<select id="singlo-<?php echo esc_attr( $key ); ?>" name="singlo_homepage[<?php echo esc_attr( $key ); ?>]">
			<option value="0"><?php esc_html_e( 'Select a category', 'singlo' ); ?></option>
			<?php foreach ( $categories as $category ) : ?>
				<option value="<?php echo esc_attr( $category->term_id ); ?>" <?php selected( (int) $settings[ $key ], (int) $category->term_id ); ?>>
					<?php echo esc_html( $category->name ); ?>
				</option>
			<?php endforeach; ?>
		</select>
	</div>
	<?php
}

function singlo_homepage_checkbox( $key, $label, $settings ) {
	?>
	<label class="singlo-home-builder__check">
		<input type="checkbox" name="singlo_homepage[<?php echo esc_attr( $key ); ?>]" value="1" <?php checked( ! empty( $settings[ $key ] ) ); ?>>
		<span><?php echo esc_html( $label ); ?></span>
	</label>
	<?php
}

function singlo_homepage_toggle_count( $toggle_key, $count_key, $label, $settings ) {
	?>
	<div class="singlo-home-builder__field">
		<?php singlo_homepage_checkbox( $toggle_key, $label, $settings ); ?>
		<label for="singlo-<?php echo esc_attr( $count_key ); ?>"><?php esc_html_e( 'Items to show', 'singlo' ); ?></label>
		<input type="number" min="1" max="10" id="singlo-<?php echo esc_attr( $count_key ); ?>" name="singlo_homepage[<?php echo esc_attr( $count_key ); ?>]" value="<?php echo esc_attr( $settings[ $count_key ] ); ?>">
	</div>
	<?php
}

function singlo_homepage_section_editor( $index, $section ) {
	$section = wp_parse_args(
		$section,
		array(
			'enabled'  => 1,
			'title'    => '',
			'position' => 'after_guides',
			'content'  => '',
		)
	);
	$name = 'singlo_homepage_sections[' . $index . ']';
	?>
	<div class="singlo-home-section-editor">
		<div class="singlo-home-section-editor__head">
			<strong class="singlo-home-section-editor__handle"><span class="dashicons dashicons-move"></span> <?php esc_html_e( 'Custom Section', 'singlo' ); ?></strong>
			<button type="button" class="button-link-delete singlo-remove-home-section"><?php esc_html_e( 'Remove', 'singlo' ); ?></button>
		</div>
		<div class="singlo-home-builder__grid">
			<div class="singlo-home-builder__field">
				<label><?php esc_html_e( 'Section title', 'singlo' ); ?></label>
				<input type="text" name="<?php echo esc_attr( $name ); ?>[title]" value="<?php echo esc_attr( $section['title'] ); ?>">
			</div>
			<div class="singlo-home-builder__field">
				<label><?php esc_html_e( 'Position', 'singlo' ); ?></label>
				<select name="<?php echo esc_attr( $name ); ?>[position]">
					<option value="before_apps" <?php selected( $section['position'], 'before_apps' ); ?>><?php esc_html_e( 'Before Recent Apps', 'singlo' ); ?></option>
					<option value="between_sections" <?php selected( $section['position'], 'between_sections' ); ?>><?php esc_html_e( 'Between Apps and Guides', 'singlo' ); ?></option>
					<option value="after_guides" <?php selected( $section['position'], 'after_guides' ); ?>><?php esc_html_e( 'After Recent Guides', 'singlo' ); ?></option>
				</select>
			</div>
		</div>
		<label class="singlo-home-builder__check">
			<input type="checkbox" name="<?php echo esc_attr( $name ); ?>[enabled]" value="1" <?php checked( ! empty( $section['enabled'] ) ); ?>>
			<span><?php esc_html_e( 'Show this section', 'singlo' ); ?></span>
		</label>
		<label><?php esc_html_e( 'HTML or shortcode code', 'singlo' ); ?></label>
		<textarea rows="8" name="<?php echo esc_attr( $name ); ?>[content]"><?php echo esc_textarea( $section['content'] ); ?></textarea>
		<p>
			<button type="button" class="button singlo-preview-home-section"><?php esc_html_e( 'Preview HTML', 'singlo' ); ?></button>
		</p>
		<iframe class="singlo-home-section-preview" sandbox="" title="<?php esc_attr_e( 'Custom section preview', 'singlo' ); ?>"></iframe>
	</div>
	<?php
}
