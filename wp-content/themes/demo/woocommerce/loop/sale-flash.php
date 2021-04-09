<?php
/**
 * Product loop sale flash
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/sale-flash.php.
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

global $post, $product;

?>

<?php if ( $product->is_on_sale() ) : ?>

	<?php echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . esc_html__( 'Sale!', 'woocommerce' ) . '</span>', $post, $product ); ?>

<?php endif;

	    global $post;
				
		$parent_id = $product->get_parent_id();
		if($parent_id != ''){
			$terms = get_the_terms( $parent_id, 'product_cat' );
		}
		else{
			$terms = get_the_terms( $post->ID, 'product_cat' );
		}
		if($terms){
			foreach ($terms as $term) 
			{
			    $product_cat_id = $term->term_id;

			    $thumbnail_id = get_woocommerce_term_meta( $product_cat_id, 'thumbnail_id', true );
			    $image = wp_get_attachment_url( $thumbnail_id );
			    if ( $image ) {
				    echo '<img class="prd_logo" src="' . $image . '" alt="' . $cat->name . '" />';
				}		   
			   	break;
			}	
		}
		
		
/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
