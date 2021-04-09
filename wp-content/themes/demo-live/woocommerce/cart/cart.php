<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;
global $wpdb;
$user_id= get_current_user_id();
	$SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
	$franchise=$wpdb->get_row($SQL);
	
/*echo '<pre>';
print_r(WC()->cart->get_cart());
echo '</pre>';*/

do_action( 'woocommerce_before_cart' ); ?>

<div class="site-url" hidden=""><?php echo get_site_url(); ?></div>
<span class="session-key" hidden=""><?php echo $session_id = WC()->session->get_customer_id(); ?></span>
<span class="update-item" hidden=""></span>
<div class="main-breadcrumb">
		<?php main_breadcrumb('review-order');?>
    </div>
    <div class="clearfix"></div>
    <?php wc_print_notices();?>
<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
	<div class="cart_left">
		<div class="cart-shopping-btn">
		<?php
			$user = wp_get_current_user();
       		$role = $user->roles[0];
       		if($role == 'administrator' || $role == 'shop_manager')
       		{	
       			echo '<a class="btn btn-invert" href="?empty-cart=true"><span>Clear Cart</span></a>';
       		}
       		
       		if($role == 'Installer')
       		{	
       				if($franchise){
					echo '<a class="btn btn-invert" href="'.get_site_url().'/my-account/franchise-home/"><span>Continue Shopping</span></a>';
       				}else{
       				echo '<a class="btn btn-invert" href="'.get_site_url().'/my-account/purchase"><span>Continue Shopping</span></a>';	
       				}
       			
       		}
       		else{
       			if(isset($_SESSION['shopURL'])){
       				echo '<a class="btn btn-invert" href="'.$_SESSION['shopURL'].'"><span>Continue Shopping</span></a>';
       			}
       			else{
       				echo '<a class="btn btn-invert" href="'.get_site_url().'/shop"><span>Continue Shopping</span></a>';
       			}
       			
       			echo '<a class="btn btn-invert" href="'.get_site_url().'"><span>Start from begining</span></a>';
       		}
		?>
		
	</div>
		<?php do_action( 'woocommerce_before_cart_table' ); ?>

		<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
			<thead>
				<tr>				
					<th class="product-thumbnail">&nbsp;</th>
					<th class="product-name"><?php esc_html_e( 'Product / Service', 'woocommerce' ); ?></th>
					<th class="product-quantity"><?php esc_html_e( 'Quantity', 'woocommerce' ); ?></th>
					<th class="product-price"><?php esc_html_e( 'Price', 'woocommerce' ); ?></th>
					<th class="product-subtotal"><?php esc_html_e( 'Total', 'woocommerce' ); ?></th> 
					<th class="product-remove">Remove</th>
				</tr>
			</thead>
			<tbody>
				<?php do_action( 'woocommerce_before_cart_contents' ); ?>

				<?php

				global $woocommerce , $wpdb;
				// get Service Voucher product id 
				$sku = 'service_voucher';
				$service_voucher_prd = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );

				/*echo '<pre>';
				print_r(WC()->cart->get_cart());
				echo '</pre>';*/
				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) 
				{				
					$cart_item['custom_data']['vehicle_type'];
					if($franchise && $cart_item['offline-purchase']!='yes'){
						$qty=10;
					}else{
						$qty=5;
					}
					if($cart_item['quantity'] > $qty)
					{
						$cart_item['quantity'] = $qty;
						$woocommerce->cart->set_quantity($cart_item_key, $qty);
					}


					$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

					$current_prd =  $_product->get_id();

					$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );


					if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
						$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );

						//$vehicle_type=$cart_item['custom_data']['vehicle_type'];
						//$product_permalink.='&vehicle_type='.$vehicle_type;
						?>

						<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?> <?php if($product_id == $service_voucher_prd){ echo 'service-voucher-item';}?>">						

							<td class="product-thumbnail">
							<?php
							 $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

							if ( ! $product_permalink ) {
								echo wp_kses_post( $thumbnail );
							} else {
								printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), wp_kses_post( $thumbnail ) );
							}

							?>
							</td>

							<?php 
							
							include('cart-item-name.php'); ?>						

							<td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">

							<?php
								if($current_prd != $service_voucher_prd){
									if ( $_product->is_sold_individually() ) {
										$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );

									} else {
										$product_quantity = woocommerce_quantity_input( array(
											'input_name'   => "cart[{$cart_item_key}][qty]",
											'input_value'  => $cart_item['quantity'],
											'max_value'    => $_product->get_max_purchase_quantity(),
											'min_value'    => '0',
											'product_name' => $_product->get_name(),
										), $_product, false );

										$cart_item_qty = $cart_item['quantity'];
											# code...

										
										?>
										<select class="qty" data-product-id="<?php echo $current_prd; ?>">
											<?php for ($i=1; $i <= $qty ; $i++) { ?>
											<option <?php if($cart_item_qty == $i){echo 'selected';}?>><?php echo $i; ?></option>
										<?php } ?>
										</select><i class="fa fa-angle-down"></i>
										<?php

									}
									echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
								}


								// PHPCS: XSS ok.

							?>
							</td>

							<td class="product-price" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
								<?php
						/*	global $wpdb;
							$user_id = get_current_user_id(); 
							$SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
							$franchise=$wpdb->get_row($SQL);

							if(!empty($franchise)){
								if($cart_item['offline-purchase']!='yes'){
									$SQLSHIV="SELECT * FROM `th_supplier_products_final` where product_id='".$cart_item['variation_id']."' AND updated_date>DATE_SUB(NOW(),INTERVAL 1 YEAR)  GROUP by id HAVING min(tyre_price+tube_price) ORDER BY (tyre_price+tube_price) ASC LIMIT 0,1";
								    $productsshiv=$wpdb->get_row($SQLSHIV);

								    $tube_price = $productsshiv->tube_price;
								    $tyre_price = $productsshiv->tyre_price;

								    $product_price=($tube_price +$tyre_price) + (($tube_price +$tyre_price)*0)/100;
								}else{
									$product_price=WC()->cart->get_product_price( $_product );
								}
					  
							}else{
								$product_price=WC()->cart->get_product_price( $_product );
							}*/

							$product_price=WC()->cart->get_product_price( $_product );
							$product_price_sub= WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'], $cart_item, $cart_item_key );

									echo apply_filters( 'woocommerce_cart_item_price',$product_price, $cart_item, $cart_item_key ); // PHPCS: XSS ok.
								?>
							</td>
							 <td class="product-subtotal" data-title="<?php esc_attr_e( 'Total', 'woocommerce' ); ?>">
								<?php
									echo apply_filters('woocommerce_cart_item_subtotal',$product_price_sub, $cart_item, $cart_item_key ); // PHPCS: XSS ok.
								?>
							</td> 
							<td class="custom-remove">
								<span class="link" hidden><?php echo esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
										__( '', 'woocommerce' ) ?></span>
								<?php
									

									echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
										'<a href="" class="delete" aria-label="%s" data-product_id="%s" data-product_sku="%s"
										data-cart-item-installer-id ="'.$installer_table_id.'" data-cart_key="'.$cart_item_key.'" data-session_id="'.$session_id.'"><i class="fa fa-trash"></i></a>',
										__( 'Remove this item', 'woocommerce' ),
										esc_attr( $product_id ),
										esc_attr( $_product->get_sku() )
									), $cart_item_key );
								?>
							</td>
						</tr>
						<?php include('cart-item-service-row.php'); ?>

						<?php 
						
						global $wpdb;
						$user_id = get_current_user_id();
						$SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
						$franchise=$wpdb->get_row($SQL);
						if($franchise){
							include('cart-item-franchise-service-row.php');	
						}
						 ?>

						<?php
							//$current_prd.'='.$service_voucher_prd;

							if($current_prd == $service_voucher_prd)
							{

								
								if(empty($franchise)){

									global $woocommerce;
									$woocommerce->cart->set_quantity($cart_item_key, '1');
									$voucher_info = "SELECT * 
												FROM th_cart_item_service_voucher
												WHERE product_id = '$service_voucher_prd' AND  service_data_id=".$cart_item['services_name']." and session_id = '$session_id' and order_id = ''";

									$voucher_row = $wpdb->get_results($voucher_info);
								}
								if($voucher_row){
								foreach ($voucher_row as $key => $voucher) 
								{ 
									$voucher_id = $voucher->service_voucher_id;
									$voucher->voucher_name;
									$vehicle_id = $voucher->vehicle_id;
									$rate = $voucher->rate;
									$vehicle_name = $wpdb->get_var( $wpdb->prepare( "SELECT vehicle_type FROM th_vehicle_type WHERE vehicle_id='%s' LIMIT 1" , $vehicle_id) );
									$installer_id = $voucher->installer_id;

									$installer_name = $wpdb->get_var( $wpdb->prepare( "SELECT business_name FROM th_installer_data WHERE installer_data_id='%s' LIMIT 1" , $installer_id) );

						?>
									<tr>
										<td class="product-thumbnail">
											<?php if($voucher->service_data_id!=5){?>
												<img width="300" height="300" src="<?php echo get_stylesheet_directory_uri()?>/images/service-icon/store.png" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail wp-post-image" alt="">
											<?php }?>

											<?php if($voucher->service_data_id==5){?>
												<img width="300" height="300" src="<?php echo get_stylesheet_directory_uri()?>/images/service-icon/store.png" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail wp-post-image" alt="">
											<?php }?>
											</td>
										<td class="product-name">
											<div>
												<b>Service Voucher</b>-<?php echo $vehicle_name; ?>
											</div>
											<div><b><?php echo $installer_name; ?></b></div>
											<div><?php echo $voucher->voucher_name; ?></div>	
										</td>

										<td class="service-qty"><?php echo $voucher->qty; ?></td>

										<td class="service-price">
											<?php echo get_woocommerce_currency_symbol().number_format($rate,2,'.',''); ?>
										</td>
										<td class="service-total">&nbsp;</td>
										<td class="custom-remove">
											<a href="<?php echo get_site_url().'/cart' ?>" class="delete remove-voucher" aria-label="Remove this item" data-voucher-id="<?php echo $voucher_id; ?>" data-cart-key="<?php echo $cart_item_key; ?>" ><i class="fa fa-trash"></i></a>
										</td>
									</tr>
						<?php 
								}
							 }
							}
						?>
						<?php
					}
				}
				?>

				<?php do_action( 'woocommerce_cart_contents' ); ?>
						<div class="up_cart col-md-6">
							<button type="submit" class="btn btn-invert" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><span><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></span></button>
						</div>					
						<?php do_action( 'woocommerce_cart_actions' ); ?>
						<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
					<!-- </td> -->
				<!-- </tr>  -->
				<?php do_action( 'woocommerce_after_cart_contents' ); ?>
				<?php
				global $woocommerce , $wpdb;
					$customer_orders = get_posts( array(
					    'numberposts' => 1,
					    'meta_key'    => '_customer_user',
					    'meta_value'  => get_current_user_id(),
					    'post_type'   => wc_get_order_types(),
					    'post_status' => array_keys( wc_get_order_statuses() ),
					) );
				$count_failed_order = $customer_orders;
				$order_id = $customer_orders[0]->ID;
				$order_status = $customer_orders[0]->post_status;	
				// $order = new WC_Order( $order_id );
				// $items = $order->get_items();
				// foreach ( $items as $item ) {
				// 	$product_name = $item['name'];
				//     $product_id = $item['product_id'];
				//     $qty = $item['quantity'];
				//     $product_variation_id = $item['variation_id'];
				// }
				
				// $variation = new WC_Product_Variation($product_variation_id);
				// $variationName = $variation->get_description();
				$current_user = get_current_user_id();
				$user_meta=get_userdata($current_user);
				$user_roles=$user_meta->roles;
				//wc-failed
				//wc-pending
			   if(!empty($count_failed_order) && $order_status == "wc-failed" && $user_roles[0] == "customer") 
			   {
				?>
				<tr>
					<td colspan="6"><span style="font-size: 12px;color: red;">
					 Dear Customer, your previous item Payment failed. please order place again <a href="<?php echo get_site_url();?>/my-account/orders" style="color: blue;">click Here to Pay</a>
					</span></td>
				</tr>
			   <?php } ?>	
			</tbody>
		</table>
		<?php do_action( 'woocommerce_after_cart_table' ); ?>
	</div>
	<div class="cart-collaterals">
	<?php
		/**
		 * Cart collaterals hook.
		 *
		 * @hooked woocommerce_cross_sell_display
		 * @hooked woocommerce_cart_totals - 10
		 */
		do_action( 'woocommerce_cart_collaterals' );
	?>
	</div>
</form>

<?php do_action( 'woocommerce_after_cart' ); ?>
