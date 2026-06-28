<?php
?>

<article id="post-<?php the_ID(); ?>" <?php body_class( 'card mb-6 overflow-hidden' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="post-thumbnail">
			<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail( 'large', array( 'class' => 'w-full h-auto object-cover transition-transform duration-300 hover:scale-105' ) ); ?>
			</a>
		</div>
	<?php endif; ?>

	<div class="p-6">
		<header class="entry-header mb-4">
			<?php the_title( '<h2 class="entry-title text-2xl font-bold mb-2"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark" class="hover:text-primary-600">', '</a></h2>' ); ?>

			<?php if ( 'post' === get_post_type() ) : ?>
				<div class="entry-meta text-sm text-gray-500 flex items-center gap-4">
					<span class="posted-on">
						<i class="far fa-calendar-alt mr-1"></i> <?php echo get_the_date(); ?>
					</span>
					<span class="byline">
						<i class="far fa-user mr-1"></i> <?php the_author(); ?>
					</span>
				</div>
			<?php endif; ?>
		</header>

		<div class="entry-content text-gray-600 mb-6">
			<?php
			the_excerpt();
			?>
		</div>

		<footer class="entry-footer">
			<a href="<?php the_permalink(); ?>" class="btn btn-primary">
				<?php esc_html_e( 'Read More', 'singlo' ); ?>
			</a>
		</footer>
	</div>
</article>
