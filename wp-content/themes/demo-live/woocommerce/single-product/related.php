<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
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
 * @version     3.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $post;
// Define Query Arguments
$product_id = $post->ID;
//$product = wc_get_product($product_id);
//$related_products=$product->get_children();
$tickets = new WC_Product_Variable($product_id);
$related_products = $tickets->get_available_variations();
   
global $post, $product;    
$terms = get_the_terms( $product->get_id(), 'product_cat' );

if(!isset($_GET['attribute_pa_width'])) {
	$margin='margin-top:0;';
}

if ( $related_products ) : ?>
	<section class="related products" style="<?=$margin;?>">
		<h2><?php esc_html_e(the_title(' '.$terms[0]->name.' ').' products', 'woocommerce' ); ?></h2>
		<?php woocommerce_product_loop_start(); ?>
			<?php foreach ( $related_products as $related_product ) : ?>
				<?php
				 	$post_object = get_post( $related_product['variation_id'] );
					setup_postdata( $GLOBALS['post'] =& $post_object );
					wc_get_template_part( 'content', 'product' ); 
				?>
			<?php endforeach; ?>
		<?php woocommerce_product_loop_end(); ?>
	</section>
<?php endif;
wp_reset_postdata();