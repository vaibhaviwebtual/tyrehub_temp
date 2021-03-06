<?php
/*Template Name: Blog*/
get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<?php if ( is_home() && ! is_front_page() ) : ?>
				<header>
					<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
				</header>
			<?php endif; ?>

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'template-parts/content' ); ?>

			<?php endwhile; ?>

			<?php
			/**
			 * Hook - easy_commerce_action_posts_navigation.
			 *
			 * @hooked: easy_commerce_custom_posts_navigation - 10
			 */
			do_action( 'easy_commerce_action_posts_navigation' ); ?>

		<?php else : ?>

			<?php get_template_part( 'template-parts/content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
	/**
	 * Hook - easy_commerce_action_sidebar.
	 *
	 * @hooked: easy_commerce_add_sidebar - 10
	 */
	do_action( 'easy_commerce_action_sidebar' );
?>

<?php get_footer(); ?>
