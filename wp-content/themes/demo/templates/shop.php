<?php
 /* Template Name: Shop Page */
get_header();
?>
<div id="pageContent">
		<div class="container">
		    <div class="row">
			    <div class="col-md-4 col-lg-3 column-left column-filters">

			    </div>
			    <div class="col-md-8 col-lg-9 column-center">
			    	<div class="content">
						<?php if ( have_posts() ) : ?>
							<?php while ( have_posts() ) : the_post(); ?>
								<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
									<div class="post-header">
										<h2><a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
									</div><!--.post-header-->
									<div class="entry clear">
										<?php if ( function_exists( 'add_theme_support' ) ) the_post_thumbnail(); ?>
										<?php the_content(); ?>
										<?php //edit_post_link(); ?>
										<?php wp_link_pages(); ?>
									</div><!--. entry-->
									<!-- <footer class="post-footer">
										<div class="comments"><?php //comments_popup_link( 'Leave a Comment', '1 Comment', '% Comments' ); ?></div>
									</footer> --><!--.post-footer-->
								</div><!-- .post-->
							<?php endwhile; /* rewind or continue if all posts have been fetched */ ?>
								<nav class="navigation index">
									<div class="alignleft"><?php next_posts_link( 'Older Entries' ); ?></div>
									<div class="alignright"><?php previous_posts_link( 'Newer Entries' ); ?></div>
								</nav><!--.navigation-->
							<?php else : ?>
						<?php endif; ?>
					</div><!--.content-->
			    </div>
			</div>
		</div>
	</div>

		
	
<?php
get_footer();
?>