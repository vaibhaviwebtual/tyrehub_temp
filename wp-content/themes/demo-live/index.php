<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Easy_Commerce
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php if ( have_posts() ) : ?>

			<?php if ( is_home() && ! is_front_page() ) : ?>
				<header>
					<h1 class="page-title screen-reader-text"><?php the_title(); ?></h1>
				</header>
			<?php endif; ?>

			<?php while ( have_posts() ) : the_post(); ?>

				
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			
						<!-- <header class="entry-header">
							<?php //the_title( '<h1 class="entry-title">', '</h1>' ); ?>
						</header> --><!-- .entry-header -->
						<div id="pageContent">
							<div class="container">
									<?php the_content(); ?>
									<?php
										wp_link_pages( array(
											'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'easy-commerce' ),
											'after'  => '</div>',
										) );
									?>
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
