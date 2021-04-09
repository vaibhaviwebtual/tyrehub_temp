<?php
/**
 * Cart totals
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-totals.php.
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
 * @version     2.3.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="cart_totals <?php echo ( WC()->customer->has_calculated_shipping() ) ? 'calculated_shipping' : ''; ?>">

	<?php do_action( 'woocommerce_before_cart_totals' ); ?>

	<!-- <h2><?php _e( 'Cart totals', 'woocommerce' ); ?></h2> -->

	
					
					<div class="up_cart col-md-12">
						<button type="submit" class="btn btn-invert" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><span><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></span></button>
					</div>					

					<?php do_action( 'woocommerce_cart_actions' ); ?>

					<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' );  ?>
					<div class="custom-subtotal col-md-12">
						<table cellspacing="0" class="shop_table shop_table_responsive">

							<tr class="cart-subtotal">
								<th><?php _e( 'Subtotal', 'woocommerce' ); ?>
								<div style="font-size: 10px;">	
								<?php 
								global $wpdb , $woocommerce;
								$session_id = WC()->session->get_customer_id();
			               
			                  	$sku = 'service_voucher';
							  	$service_voucher_prd = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );

							  	$cart_item_total = WC()->cart->get_cart_contents_count(); 
							  	$voucher_in_cart = 0;

							  	
								foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) 
								{				

									$product_id = $cart_item['product_id'];
									$services = "SELECT * 
												FROM th_cart_item_installer
												WHERE cart_item_key = '$cart_item_key' and session_id = '$session_id'
												and order_id = ''";
												 $row = $wpdb->get_results($services);
									foreach ($row as $data)
									{

										if($data->destination == 1)
										{       
											$destination[] = $data->destination;
										}
									}

									$product_id = $cart_item['product_id'];
									$services1 = "SELECT * 
												FROM th_cart_item_services
												WHERE cart_item_key = '$cart_item_key' and session_id = '$session_id'
												and order_id = ''";
												 $row1 = $wpdb->get_results($services1);
									foreach ($row1 as $data1)
									{

										$destination[] = $data1->destination;
									}


									if($product_id == $service_voucher_prd)
									{
										$voucher_in_cart = 1;
										$voucher_info = "SELECT * 
									                    FROM th_cart_item_service_voucher
									                    WHERE product_id = '$service_voucher_prd' AND  service_data_id=".$cart_item['services_name']." and session_id = '$session_id' and order_id = ''";
									    $row = $wpdb->get_results($voucher_info);
									   // var_dump(count($row));
									    $service_voucher_count = count($row);
									}
								}
								//$service_total = count($destination);
								//$total_services = $service_total + $service_voucher_count;
								//$cart_item_total = $cart_item_total - $voucher_in_cart;		
								?>
								<?php //echo '('.$cart_item_total.' Tyres , '.$total_services.' Services)'; ?>
							</div>
								</th>
								<td data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>">
									<?php wc_cart_totals_subtotal_html(); ?>
									<input type="hidden" name="subtotal" id="subtotal" value="<?=WC()->cart->subtotal;?>">
									<input type="hidden" name="maintotal" id="maintotal" value="<?=WC()->cart->total;?>">
									</td>
							</tr>
						</table>
					</div>

					<?php if ( wc_coupons_enabled() ) { ?>
						<?php 
							global $wpdb , $woocommerce;
							
							foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item )
							{

								$flag=$cart_item['offline-purchase'];

							}

							if($flag=='yes'){
							?>
							<!-- <div class="coupon-custom col-md-12">
							<input type="text" name="discount_price" class="input-text" id="discount_price" value="" placeholder="<?php esc_attr_e( 'Discount Amount', 'woocommerce' ); ?>" /> <button type="button" class="btn btn-invert" name="discount_price_btn" id="discount_price_btn" value="<?php esc_attr_e( 'Apply', 'woocommerce' ); ?>"><span><?php esc_attr_e( 'Apply', 'woocommerce' ); ?></span></button>
								<?php do_action( 'woocommerce_cart_coupon' ); ?>
							</div> -->
							<div class="coupon-custom col-md-12">
							<!-- <label for="coupon_code"><?php esc_html_e( 'Coupon:', 'woocommerce' ); ?></label>  --><input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" /> <button type="submit" class="apply-coupon btn btn-invert" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><span><?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?></span></button>
							<?php do_action( 'woocommerce_cart_coupon' ); ?>
						</div>
							<?php }else{?>

						<div class="coupon-custom col-md-12">
							<!-- <label for="coupon_code"><?php esc_html_e( 'Coupon:', 'woocommerce' ); ?></label>  --><input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" /> <button type="submit" class="apply-coupon btn btn-invert" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><span><?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?></span></button>
							<?php do_action( 'woocommerce_cart_coupon' ); ?>
						</div>
					<?php }?>
					<?php } ?>


					<div class="col-md-12">
							<table cellspacing="0" class="shop_table shop_table_responsive">							
							<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>

								<tr class="fee <?php if($fee->name == 'Service Charges'){ echo 'service-fee'; } ?>">
									<th><?php echo esc_html( $fee->name ); ?></th>
									<td data-title="<?php echo esc_attr( $fee->name ); ?>"><?php wc_cart_totals_fee_html( $fee ); ?></td>
								</tr>
							<?php endforeach; ?>

							<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
								<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
									<th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
									<td data-title="<?php echo esc_attr( wc_cart_totals_coupon_label( $coupon, false ) ); ?>"><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
								</tr>
							<?php endforeach; ?>
							
							<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) :
								$taxable_address = WC()->customer->get_taxable_address();
								$estimated_text  = WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping()
										? sprintf( ' <small>' . __( '(estimated for %s)', 'woocommerce' ) . '</small>', WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] )
										: '';

								if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
									<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
										<tr class="tax-rate tax-rate-<?php echo sanitize_title( $code ); ?>">
											<th><?php echo esc_html( $tax->label ) . $estimated_text; ?></th>
											<td data-title="<?php echo esc_attr( $tax->label ); ?>"><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
										</tr>
									<?php endforeach; ?>
								<?php else : ?>
									<tr class="tax-total">
										<th><?php echo esc_html( WC()->countries->tax_or_vat() ) . $estimated_text; ?></th>
										<td data-title="<?php echo esc_attr( WC()->countries->tax_or_vat() ); ?>"><?php wc_cart_totals_taxes_total_html(); ?></td>
									</tr>
								<?php endif; ?>
							<?php endif; ?>

							<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

							<tr class="order-total">
								<th class="final-total"><?php _e( 'Total', 'woocommerce' ); ?></th>
								<td data-title="<?php esc_attr_e( 'Total', 'woocommerce' ); ?>"><?php wc_cart_totals_order_total_html(); ?></td>
							</tr>
							
							<?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>

						</table>				
							<?php 
							global $wpdb , $woocommerce;
							
							foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item )
							{

								$flag=$cart_item['offline-purchase'];

							}

							if($flag=='yes'){
							?>
							
									<input type="number" name="customer_mobile" class="input-text" id="customer_mobile" value="<?=$_SESSION['mobile_no'];?>" placeholder="Customer Mobile"  onkeyup="check(); return false;" maxlength="10">
									<span id="message"></span>
									<input type="hidden" name="customer_type" class="input-text" id="customer_type" value="offline">
								
						<?php } ?>
							<div class="wc-proceed-to-checkout">
							
								<?php 
								if($flag!='yes'){
								do_action( 'woocommerce_proceed_to_checkout' );
								} ?>
							</div>
							<?php if($flag=='yes'){?>
<button type="button" id="proceed_to_checkout" class="checkout-button btn btn-invert alt wc-forward"><span>Proceed to checkout</span></button><?php }?>
							</div>
						</div>

	<?php do_action( 'woocommerce_after_cart_totals' ); ?>



