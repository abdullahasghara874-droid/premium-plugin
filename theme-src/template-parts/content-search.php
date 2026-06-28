<?php
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'card p-6' ); ?>>
	<header class="entry-header">
		<?php the_title( sprintf( '<h2 class="entry-title text-xl font-bold mb-2"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

		<?php if ( 'post' === get_post_type() ) : ?>
			<div class="entry-meta text-sm text-gray-500 mb-4">
				<?php echo get_the_date(); ?>
			</div>
		<?php endif; ?>
	</header>

	<div class="entry-summary text-gray-600">
		<?php the_excerpt(); ?>
	</div>

	<footer class="entry-footer mt-4">
		<a href="<?php the_permalink(); ?>" class="text-primary-600 font-medium hover:underline"><?php esc_html_e( 'Read More', 'singlo' ); ?> &rarr;</a>
	</footer>
</article>
