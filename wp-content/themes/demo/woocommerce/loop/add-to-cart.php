<?php
/**
 * Loop Add to Cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/add-to-cart.php.
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
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;
$product_data = $product->get_data();
$id = $product_data['id'];
$visiblity = get_post_meta($id, 'tyrehub_visible', true  );

global $woocommerce ,$wpdb;
	$user_id = get_current_user_id(); 
	$SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
	$franchise=$wpdb->get_row($SQL);
	
	$minifra="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_minifranchise='yes'";
	$minifranchise=$wpdb->get_row($minifra);
	
	
    $list_sql = "SELECT * FROM th_soldout_product_list";
    $list_data = $wpdb->get_results($list_sql);
    $soldout_product_list = [];
    foreach ($list_data as $key => $value)
    {
        $soldout_product_list[] = $value->product_id;
    }
    
    
if($visiblity == 'contact-us')
{
	if(current_user_can('shop_manager') || current_user_can('administrator')){
	?>
		<a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="btn btn-invert add_to_cart_button"><span>Please call us</span></a>
	<?php
	}elseif(!empty($franchise)){
		echo '<a href="javascript:void(0);" data-product_id="'.$product->parent_id.'" data-variation_id="'.$product->get_id().'" data-quantity="1" data-type="offline" class="offline-product btn btn-invert add_to_cart_button" rel="nofollow"><span><i class="fa fa-shopping-cart"></i>Add to cart</span></a>';

	}elseif(!empty($minifranchise)){
		echo '<a href="javascript:void(0);" data-product_id="'.$product->parent_id.'" data-variation_id="'.$product->get_id().'" data-quantity="1" data-type="offline" class="offline-product btn btn-invert add_to_cart_button" rel="nofollow"><span><i class="fa fa-shopping-cart"></i>Add to cart</span></a>';

	}
	else{
	?>
		<a href="<?php echo get_site_url().'/contact-us'; ?>" class="btn btn-invert add_to_cart_button"><span>Please call us</span></a>
	<?php
	}
}
else{

	if(in_array($id, $soldout_product_list))
	{
		?>
		<a class="btn btn-invert add_to_cart_button sold-out-btn"><span>Sold Out!</span></a>
		<?php
	}
	else{
		if($franchise){

			/*echo '<a href="javascript:void(0);" id="wish'.$product->get_id().'" data-product_id="'.$product->parent_id.'" data-variation_id="'.$product->get_id().'" data-quantity="1" data-wish="1" data-type="offline" class="offline-wishlist btn btn-invert add_to_cart_button" rel="nofollow"><span>(<span id="wishcount">'.$wishqty.'</span>) Wishlist</span></a>';*/

			echo '<a href="javascript:void(0);" data-product_id="'.$product->parent_id.'" data-variation_id="'.$product->get_id().'" data-quantity="1" data-type="offline" class="offline-product btn btn-invert add_to_cart_button" rel="nofollow"><span><i class="fa fa-shopping-cart"></i>Add to cart</span></a>';

			/*echo apply_filters( 'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
		sprintf( '<a href="#" data-quantity="%s" class="btn btn-invert %s" %s><span><i class="fa fa-shopping-cart"></i>%s</span></a>',
			esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
			esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
			isset( $args['attributes'] ) ? wc_implode_html_attributes($args['attributes'] ) : '',
			esc_html( $product->add_to_cart_text())),$product, $args );
*/
			
		}elseif($minifranchise){

			/*echo '<a href="javascript:void(0);" id="wish'.$product->get_id().'" data-product_id="'.$product->parent_id.'" data-variation_id="'.$product->get_id().'" data-quantity="1" data-wish="1" data-type="offline" class="offline-wishlist btn btn-invert add_to_cart_button" rel="nofollow"><span>(<span id="wishcount">'.$wishqty.'</span>) Wishlist</span></a>';*/

			echo '<a href="javascript:void(0);" data-product_id="'.$product->parent_id.'" data-variation_id="'.$product->get_id().'" data-quantity="1" data-type="offline" class="offline-product btn btn-invert add_to_cart_button" rel="nofollow"><span><i class="fa fa-shopping-cart"></i>Add to cart</span></a>';

			/*echo apply_filters( 'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
		sprintf( '<a href="#" data-quantity="%s" class="btn btn-invert %s" %s><span><i class="fa fa-shopping-cart"></i>%s</span></a>',
			esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
			esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
			isset( $args['attributes'] ) ? wc_implode_html_attributes($args['attributes'] ) : '',
			esc_html( $product->add_to_cart_text())),$product, $args );
*/
			
		}else{
			/*echo apply_filters( 'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
		sprintf( '<a href="%s" data-quantity="%s" class="btn btn-invert %s" %s><span><i class="fa fa-shopping-cart"></i>%s</span></a>',
			esc_url( $product->add_to_cart_url()),
			esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
			esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
			isset( $args['attributes'] ) ? wc_implode_html_attributes($args['attributes'] ) : '',
			esc_html( $product->add_to_cart_text() )
		),
		$product, $args );*/

		echo '<a href="javascript:void(0);" data-vehicle_type="'.$_GET['filter_vehicle-type'].'" data-product_id="'.$product->parent_id.'" data-variation_id="'.$product->get_id().'" data-quantity="1" data-type="organic" class="btn btn-invert button product_type_variation add_to_cart_button organic_order_add_to_cart" rel="nofollow"><span><i class="fa fa-shopping-cart"></i>Add to cart</span></a>';
		}
		
		
	}
}

?>
