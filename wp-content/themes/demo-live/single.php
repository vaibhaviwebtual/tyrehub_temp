<?php
get_header(); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : the_post(); ?>			
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>	
						<div id="pageContent">
							<div class="container">
								<div class="row">
									<div class="col-md-8 blog-leftside">
										<div class="blog-leftside-inner">
											<header class="entry-header">
												<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
											</header>
												<?php if ( '' != get_the_post_thumbnail() && is_single() ) : ?>
											<div class="post-thumbnail">
												<a href="<?php the_permalink(); ?>">
													<?php the_post_thumbnail(); ?>
												</a>
											</div>
											<?php endif; ?>
											<?php the_content(); ?>
											<?php
												wp_link_pages( array(
													'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'easy-commerce' ),
													'after'  => '</div>',
												) );

											?>
										</div>
									</div>
									<div class="col-md-4 blog-sidebar">
										<div class="blog-sidebar-inner">
											<?php get_sidebar(); ?>
										</div>
									</div>
								</div>
						</div>

						<footer class="entry-footer">
							<?php edit_post_link( esc_html__( 'Edit', 'easy-commerce' ), '<span class="edit-link">', '</span>' ); ?>
						</footer><!-- .entry-footer -->
				</article><!-- #post-## -->
			<?php endwhile; ?>
		<?php else : ?>
		<?php endif; ?>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>

