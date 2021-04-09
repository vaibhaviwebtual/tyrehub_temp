<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' ); ?>
<div id="pageContent" class="single_product">
	<div class="container">
	<?php
		/**
		 * woocommerce_before_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		do_action( 'woocommerce_before_main_content' );
	?>

		<?php while ( have_posts() ) : the_post(); ?>

			<?php wc_get_template_part( 'content', 'single-product' ); ?>

		<?php endwhile; // end of the loop. ?>

	<?php
		/**
		 * woocommerce_after_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'woocommerce_after_main_content' );
	?>

	<?php
		/**
		 * woocommerce_sidebar hook.
		 *
		 * @hooked woocommerce_get_sidebar - 10
		 */
		do_action( 'woocommerce_sidebar' );

		global $product;

		$name = $product->get_name();
		$des = $product->get_short_description();
		$cat_id = $product->get_category_ids();
		
		$image_url = get_the_post_thumbnail_url( $product->get_id(), 'full' );
		$average_count = $product->get_average_rating();
		$review_count = $product->get_review_count();
		$price = $product->get_price();
		$url = get_permalink( $product->get_id() );

		$term = get_term_by('id', $cat_id[0], 'product_cat', 'ARRAY_A');
    	$category_name = $term['slug'];    

    	if($des == ''){
    		$des = $name;
    	}   

    	$comments = get_comments(array(
		  'post_id' => $product->get_id(),
		));


	?>
	
	<div typeof="schema:Product">

	<?php foreach($comments as $comment) {

		?>
    <div rel="schema:review">
      <div typeof="schema:Review">
        <div rel="schema:reviewRating">
          <div typeof="schema:Rating">
            <div property="schema:ratingValue" content="<?php echo get_comment_meta( $comment->comment_ID, 'rating', true); ?>"></div>
            <div property="schema:bestRating" content="<?php echo $average_count; ?>"></div>
          </div>
        </div>
        <div rel="schema:author">
          <div typeof="schema:Person">
            <div property="schema:name" content="<?php comment_author(); ?>"></div>
          </div>
        </div>
      </div>
    </div>

<?php } ?>
    <div rel="schema:image" resource="<?=bloginfo('template_url');?>/images/no_img1.png"></div>
   <div property="schema:mpn" content="<?php echo $product->get_id(); ?>"></div>
    <div property="schema:name" content="<?php echo $name; ?>"></div>
    <div property="schema:description" content="<?php echo $des; ?>"></div>
    <div rel="schema:image" resource="<?php echo $image_url; ?>"></div>
    <div rel="schema:brand">
      <div typeof="schema:Thing">
        <div property="schema:name" content="<?php echo $category_name; ?>"></div>
      </div>
    </div>
    <div rel="schema:aggregateRating">
      <div typeof="schema:AggregateRating">
        <div property="schema:reviewCount" content="<?php echo $review_count ?>"></div>
        <div property="schema:ratingValue" content="<?php echo $average_count; ?>"></div>
      </div>
    </div>
    <div rel="schema:offers">
      	<div typeof="schema:Offer">
	        <div property="schema:price" content="<?php echo $price; ?>"></div>
	        <div property="schema:availability" content="https://schema.org/InStock"></div>
	        
	        <div property="schema:priceCurrency" content="IN"></div>
	        
	        <div rel="schema:url" resource="<?php echo $url; ?>"></div>
        </div>
    </div>
    </div>
   
  </div>


</div>
</div>
<?php get_footer( 'shop' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
