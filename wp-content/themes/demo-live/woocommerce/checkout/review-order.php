<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
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
$session_id = WC()->session->get_customer_id();

?>
<table class="shop_table woocommerce-checkout-review-order-table" hidden="">
	<thead>
		<tr>
			<th class="product-name"><?php _e( 'Product', 'woocommerce' ); ?></th>
			<th class="product-total"><?php _e( 'Total', 'woocommerce' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php
			do_action( 'woocommerce_review_order_before_cart_contents' );

			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) 
			{
				$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id = $cart_item['product_id'];
				global $woocommerce , $wpdb;
				// get Service Voucher product id 
				$sku = 'service_voucher';
			  	$service_voucher_prd = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );
				//echo $cart_item_key;
				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					?>
					<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?> <?php if($product_id == $service_voucher_prd){ echo 'service-voucher-item';}?> ">
						<td class="product-name">
							<?php 
								echo apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;'; ?>
							<?php 
								//if($service_voucher_prd != $current_prd)
								//{

								echo apply_filters( 'woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">' . sprintf( '&times; %s', $cart_item['quantity'] ) . '</strong>', $cart_item, $cart_item_key ); 
							//}
								?>							   				
							<?php echo wc_get_formatted_cart_item_data( $cart_item ); ?>
							
						</td>
						<td class="product-total">
							<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
						</td>
					</tr>
					<?php 
						global $woocommerce , $wpdb;
						    	
	                	$installer = "SELECT * 
	                        	FROM th_cart_item_installer
	                        	WHERE cart_item_key = '$cart_item_key' and session_id = '$session_id' and order_id = ''";
	                    $row = $wpdb->get_results($installer); 
	                  //  var_dump($row);
	                    if(empty($row))
	                    {
	                    	if(!current_user_can('Installer') && !current_user_can('shop_manager') && !current_user_can('administrator'))
	                    	{
	                    		if($product_id != $service_voucher_prd)
								{
			                    	wp_redirect( get_site_url().'/cart' );
									exit;
								}
	                    	}
	                    	
	                    }			
	        			else
			           	{
			           		$installer_name = '';
		                    $selected_vehicle_id = '';
		                    $vehicle_name = '';
		                    foreach ($row as $key => $installer) 
								    {
								    	$destination = $installer->destination;
								    	$installer_id = $installer->installer_id;
								    	$vehicle_id = $installer->vehicle_id;
								    	$installer_name = $wpdb->get_var( $wpdb->prepare( "SELECT business_name FROM th_installer_data WHERE installer_data_id='%s' LIMIT 1" , $installer_id) );

								    	$vehicle_name = $wpdb->get_var( $wpdb->prepare( "SELECT vehicle_type FROM th_vehicle_type WHERE vehicle_id='%s' LIMIT 1" , $vehicle_id) );
								    	$selected_vehicle_id = $installer->vehicle_id;
								    	//$selected_tyre = $installer->no_of_tyre;
							    }
			         ?>
			         		<tr>
								<td>
					<?php
									if($destination == 0){
										echo "Deliver To Home";
									}
		                    
					                if($installer_name != '' && $destination == 1)
					                {

					?>						
							    <div class="installer-name"><?php echo '<b>'.$installer_name.'</b>'; ?></div>
					<?php 
							    	if($vehicle_name !='')
							    	{							    	
					?>
							    		<div class="vehicle-typre"><b>Vehicle Type : </b><?php echo $vehicle_name; ?></div>
					<?php 			} 
					?>

							    <div class="product-service-list">
							    <?php

							    $services = "SELECT * 
			                        	FROM th_cart_item_services
			                        	WHERE cart_item_key = '$cart_item_key' and session_id = '$session_id' and order_id = ''";
			                    $row = $wpdb->get_results($services);

			                    $service_name = '';
			                    $service_list = [];
			                    $amount = '';
			                    $total_amount = '';
			                    foreach ($row as $key => $service) 
							    {
							    	$tyre_count = $service->tyre;
							    	$service_name = $service->service_name;
							    	$rate = $service->rate;
							    	
							    	$service_list[$service_name] = $tyre_count;

							    	if($service_name == 'Wheel alignment'){
							    		$amount = $rate;
							    		echo '<div>'.$service_name.' - '.get_woocommerce_currency_symbol().$amount.'</div>';
							    	}
							    	else{
							    		$amount = $tyre_count * $rate;
							    		echo '<div>'.$service_name.' x '.$tyre_count.' - '.get_woocommerce_currency_symbol().$amount.'</div>';
							    	}
							    	 $total_amount = $total_amount + $amount;
							    }
							    ?>
							</div>
						<?php } ?>
						</td>
						<td>
							 
							<?php 
								if($installer_name != '' && $destination == 1)
					            {
									echo get_woocommerce_currency_symbol().number_format( $total_amount, 2, '.', ',' ); 
								}
								?>
						</td>
					</tr>
					<?php
				
			}
				?>
				<?php
					if($product_id == $service_voucher_prd)
					{
						$voucher_info = "SELECT * 
			                        	FROM th_cart_item_service_voucher
			                        	WHERE product_id = '$service_voucher_prd' and session_id = '$session_id' and order_id = ''";
			                $voucher_row = $wpdb->get_results($voucher_info);
			                foreach ($voucher_row as $key => $voucher) 
							{
								$voucher_id = $voucher->service_voucher_id;
								$voucher->voucher_name;
								$vehicle_id = $voucher->vehicle_id;
								$rate = $voucher->rate;
								$vehicle_name = $wpdb->get_var( $wpdb->prepare( "SELECT vehicle_type FROM th_vehicle_type WHERE vehicle_id='%s' LIMIT 1" , $vehicle_id) );
							
					?>
								<tr>
									<td>
										<div>
											<b>Service Voucher</b>
											<?php echo $vehicle_name; ?>
										</div>
										<div><?php echo $voucher->voucher_name; ?><strong class="product-quantity"> × <?php echo $voucher->qty; ?></strong></div>	
									</td>
									<td>
										<?php echo get_woocommerce_currency_symbol().number_format($rate,2,'.',''); ?>
									</td>
									
								</tr>
					<?php 
							}
						
					}
				}
			}

			do_action( 'woocommerce_review_order_after_cart_contents' );
		?>
	</tbody>
	<tfoot>

		<tr class="cart-subtotal">
			<th><?php _e( 'Subtotal', 'woocommerce' ); ?></th>
			<td><?php wc_cart_totals_order_total_html(); ?></td>
		</tr>

		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
				<th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
				<td><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

			<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>

			<?php wc_cart_totals_shipping_html(); ?>

			<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>

		<?php endif; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<tr class="fee">
				<th><?php echo esc_html( $fee->name ); ?></th>
				<td><?php wc_cart_totals_fee_html( $fee ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
			<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
				<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
					<tr class="tax-rate tax-rate-<?php echo sanitize_title( $code ); ?>">
						<th><?php echo esc_html( $tax->label ); ?></th>
						<td><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
					</tr>
				<?php endforeach; ?>
			<?php else : ?>
				<tr class="tax-total">
					<th><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></th>
					<td><?php wc_cart_totals_taxes_total_html(); ?></td>
				</tr>
			<?php endif; ?>
		<?php endif; ?>

		<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

		<tr class="order-total">
			<th><?php _e( 'Total', 'woocommerce' ); ?></th>
			<td><?php wc_cart_totals_order_total_html(); ?></td>
		</tr>

		<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>

	</tfoot>
</table>
