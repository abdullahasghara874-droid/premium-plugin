<?php
?>

<section class="no-results not-found text-center py-20">
	<header class="page-header mb-8">
		<h1 class="page-title text-4xl font-bold"><?php esc_html_e( 'Nothing Found', 'singlo' ); ?></h1>
	</header>

	<div class="page-content max-w-lg mx-auto">
		<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
			<p>
				<?php
				printf(
					wp_kses(
						__( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'singlo' ),
						array(
							'a' => array(
								'href' => array(),
							),
						)
					),
					esc_url( admin_url( 'post-new.php' ) )
				);
				?>
			</p>
		<?php elseif ( is_search() ) : ?>
			<p class="mb-6"><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'singlo' ); ?></p>
			<?php get_search_form(); ?>
		<?php else : ?>
			<p class="mb-6"><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'singlo' ); ?></p>
			<?php get_search_form(); ?>
		<?php endif; ?>
	</div>
</section>
