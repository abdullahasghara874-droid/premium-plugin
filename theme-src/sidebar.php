<?php
/**
 * Sidebar Template
 */
?>
<aside id="secondary" class="widget-area w-full lg:w-1/3">
	<?php
	singlo_render_sidebar_trending_apps_widget(5);
	singlo_render_sidebar_trending_posts_widget(5);
	singlo_render_sidebar_recent_updates_widget(5);
	singlo_render_sidebar_recent_guides_widget(5);
	?>

	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside>
