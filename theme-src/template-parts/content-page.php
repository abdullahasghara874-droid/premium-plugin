<?php
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header mb-8">
		<?php the_title( '<h1 class="entry-title text-4xl font-bold">', '</h1>' ); ?>
	</header>

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="post-thumbnail mb-8">
			<?php the_post_thumbnail( 'full', array( 'class' => 'rounded-xl shadow-lg w-full h-auto' ) ); ?>
		</div>
	<?php endif; ?>

	<div class="entry-content prose prose-lg max-w-none">
		<?php
		the_content();

		wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'singlo' ),
			'after'  => '</div>',
		) );
		?>
	</div>
</article>
