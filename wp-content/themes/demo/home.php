<?php
get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php if ( have_posts() ) : ?>

			<?php //if ( is_home() && ! is_front_page() ) : ?>
				
			<?php //endif; ?>
			<div id="pageContent">
				<div class="container">
				 <div class="col-md-9">
				<?php while ( have_posts() ) : the_post(); ?>				
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>		


					<header class="entry-header">
						<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
					</header> <!-- .entry-header -->
						<?php the_content(); ?>

					</article><!-- #post-## -->


				<?php endwhile; ?>

			<?php else : ?>



			<?php endif; ?>
				</div>
				<div class="col-md-3">
					<?php get_sidebar(); ?>
				</div>
				</div>
			</div>
		</main><!-- #main -->
		
	</div><!-- #primary -->

<?php get_footer(); ?>
