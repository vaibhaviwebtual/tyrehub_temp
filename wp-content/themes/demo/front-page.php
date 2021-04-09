<?php
/**
 * Template for front page.
 *
 * @package Easy_Commerce
 */
get_header(); ?>
<?php //get_template_part( 'template-parts/content', 'none' ); ?>

<!-- Slider -->
	<?php include('templates/homepage/slider.php'); ?>
<!-- Slider -->

<!-- Service Section  -->
	<?php include('templates/homepage/service_section.php'); ?>
<!-- // Service Section  -->

<!-- How it Work  -->
	<?php include('templates/homepage/how_it_work.php'); ?>
<!-- How it Work  -->

<!-- How it Work  -->
	<?php include('templates/homepage/our_advantage.php'); ?>
<!-- How it Work  -->

<!-- How it Work  -->
	<?php include('templates/homepage/testimonial.php'); ?>
<!-- How it Work  -->

<!-- Brand Section --> 				
	<?php include('templates/homepage/brand_section.php'); ?>
<!-- Brand Section End -->

<?php get_footer(); ?>