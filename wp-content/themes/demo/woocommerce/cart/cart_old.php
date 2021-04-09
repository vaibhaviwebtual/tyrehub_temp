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
       			echo '<a class="btn btn-invert" href="'.get_site_url().'/my-account/purchase"><span>Continue Shopping</span></a>';
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
				<th class="product-name"><?php //esc_html_e( 'Product', 'woocommerce' ); ?></th>
				
				<th class="product-quantity"><?php esc_html_e( 'Quantity', 'woocommerce' ); ?></th>
				<th class="product-price"><?php esc_html_e( 'Price', 'woocommerce' ); ?></th>
				<!-- <th class="product-subtotal"><?php esc_html_e( 'Total', 'woocommerce' ); ?></th> -->
				<th class="product-remove">Remove From Cart</th>
			</tr>
		</thead>
		<tbody>
			<?php do_action( 'woocommerce_before_cart_contents' ); ?>

			<?php

			global $woocommerce , $wpdb;
			// get Service Voucher product id 
			$sku = 'service_voucher';
		  	$service_voucher_prd = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );

		    ///echo '<pre>';
		    //print_r(WC()->cart->get_cart());
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) 
			{				
				$cart_item['custom_data']['vehicle_type'];
				if($cart_item['quantity'] > 5)
				{
					$cart_item['quantity'] = 5;
					$woocommerce->cart->set_quantity($cart_item_key, '5');
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

						<?php include('cart-item-name.php'); ?>						

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
										<?php for ($i=1; $i <= 5 ; $i++) { ?>
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
								echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
							?>
						</td>
						<!-- <td class="product-subtotal" data-title="<?php esc_attr_e( 'Total', 'woocommerce' ); ?>">
							<?php
								echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
							?>
						</td> -->
						<td class="custom-remove">
							<span class="link" hidden><?php echo esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
									__( '', 'woocommerce' ) ?></span>
							<?php
								// @codingStandardsIgnoreLine
								/*echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
									'<a onclick=""  class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s" data-cart-item-installer-id ="'.$installer_table_id.'" data-cart_key="'.$cart_item_key.'" data-session_id="'.$session_id.'">Remove</a>',
									esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
									__( 'Remove this item', 'woocommerce' ),
									esc_attr( $product_id ),
									esc_attr( $_product->get_sku() )
								), $cart_item_key );*/

								echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
									'<a href="" class="delete" aria-label="%s" data-product_id="%s" data-product_sku="%s"
									data-cart-item-installer-id ="'.$installer_table_id.'" data-cart_key="'.$cart_item_key.'" data-session_id="'.$session_id.'">Remove</a>',
									__( 'Remove this item', 'woocommerce' ),
									esc_attr( $product_id ),
									esc_attr( $_product->get_sku() )
								), $cart_item_key );
							?>
						</td>
					</tr>
					<?php 
global $wpdb;
   
$installer = "SELECT * 
            FROM th_cart_item_installer
            WHERE cart_item_key = '$cart_item_key' and session_id = '$session_id' and order_id = ''";
$row = $wpdb->get_results($installer);
					include('cart-item-service-row.php'); ?>
					
					<?php
						
						if($current_prd == $service_voucher_prd)
						{
							global $woocommerce;
							$woocommerce->cart->set_quantity($cart_item_key, '1');
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
								$installer_id = $voucher->installer_id;
									    	
								$installer_name = $wpdb->get_var( $wpdb->prepare( "SELECT business_name FROM th_installer_data WHERE installer_data_id='%s' LIMIT 1" , $installer_id) );
							
					?>
								<tr>
									<td class="product-thumbnail">
										
											<img width="300" height="300" src="<?php echo get_site_url(); ?>/wp-content/uploads/2018/08/step2-300x300.jpg" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail wp-post-image" alt="">				</td>
									<td class="product-name">
										<div>
											<b>Service Voucher</b>-<?php echo $vehicle_name; ?>
										</div>
										<div><b><?php echo $installer_name; ?></b></div>
										<div><?php echo $voucher->voucher_name; ?></div>	
									</td>
									
									<td><?php echo $voucher->qty; ?></td>
									<td>
										<?php echo get_woocommerce_currency_symbol().number_format($rate,2,'.',''); ?>
									</td>
									<td class="custom-remove">
										<a href="<?php echo get_site_url().'/cart' ?>" class="delete remove-voucher" aria-label="Remove this item" data-voucher-id="<?php echo $voucher_id; ?>" >Remove</a>
									</td>
								</tr>
					<?php 
							}
						}
					?>
					<?php
				}
			}
			?>
			
			<?php do_action( 'woocommerce_cart_contents' ); ?>

			<!--  <tr> -->
				<!-- <td colspan="6" class="actions"> -->

					<!-- <?php //if ( wc_coupons_enabled() ) { ?>
						<div class="coupon-custom col-md-6">
							<label for="coupon_code"><?php //esc_html_e( 'Coupon:', 'woocommerce' ); ?></label> <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php //esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" /> <button type="submit" class="apply-coupon btn btn-invert" name="apply_coupon" value="<?php //esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><span><?php //esc_attr_e( 'Apply coupon', 'woocommerce' ); ?></span></button>
							<?php //do_action( 'woocommerce_cart_coupon' ); ?>
						</div>
					<?php //} ?> -->

					<div class="up_cart col-md-6">
						<button type="submit" class="btn btn-invert" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><span><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></span></button>
					</div>					

					<?php do_action( 'woocommerce_cart_actions' ); ?>

					<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
				<!-- </td> -->
			<!-- </tr>  -->

			<?php do_action( 'woocommerce_after_cart_contents' ); ?>
		</tbody>
	</table>
	
	<?php 
	if(!empty($row))
	{
	if($pa_vehicletype['pa_vehicle-type']!='two-wheeler'){?>
		<div class="service-list">
		<div class="row offer offer-success offer<?=$installer_table_id;?>">
			<div class="col-md-12 ">
				<input type="hidden" name="cart_item_key" id="cart_item_key" value="<?=$cart_item_key?>">
				<input type="hidden" name="session_id" id="session_id" value="<?=$session_id?>">
				<input type="hidden" name="product_id" id="product_id" value="<?=$cart_item['variation_id'];?>">
				<div class="offer-content">
					<div class="funkyradio1">
						<!-- <h3 class="lead">***Special Offer***</h3> -->						
						<?php
						if(empty($cart_item['custom_data']['vehicle_type'])){ ?>
						<script type="text/javascript">		               	
							setTimeout(function(){
								//jQuery('#vehicle_type').modal('show');
								 jQuery('#vehicle_type').modal({
									backdrop: 'static',
									keyboard: false
								});
							},1000);
						</script>

						<div class="modal fade" id="vehicle_type" role="dialog">
						<input type="hidden" name="admin-url" id="admin-url" value="<?=admin_url('admin-ajax.php' );?>">
						<div class="modal-dialog">  
							<!-- Modal content-->
							<div class="modal-content"  style="pointer-events: auto;">
								 <div class="modal-header">        
								</div>
								<div class="modal-body">
									<div class="vehicle-type-tab">
									<ul class="nav nav-tabs">
										<li class="active"><a data-toggle="tab" href="#home">Four Wheeler</a></li>
										<li><a data-toggle="tab" href="#menu1">Two/Three Wheeler</a></li>
									</ul>	
								  <div class="tab-content">
									<div id="home" class="tab-pane fade in active">
									  <?php
									  global $wpdb; 
									  $row = $wpdb->get_results("SELECT * FROM th_vehicle_type WHERE wheel_type = '2'");
										foreach ($row as $data) {
									?>      
						<div class="inputGroup vehicle_type">
							<input id="<?php echo 'vehicle'.$data->vehicle_type ?>" name="vehicle_type" type="radio" value="<?php echo $data->vehicle_id ?>" />
							<label for="<?php echo 'vehicle'.$data->vehicle_type ?>">

								<?php 
									  if($data->vehicle_type == 'Hatchback'){?>
										 <img class="car-img" src="<?php echo bloginfo('template_directory');?>/images/vehicle_type/hatchback-car-img.png" >
									  <?php }
									  elseif($data->vehicle_type == 'Sedan'){?>
										 <img class="car-img" src="<?php echo bloginfo('template_directory');?>/images/vehicle_type/sedan-car-img.png">
									  <?php } 
									  elseif($data->vehicle_type == 'Suv'){?>
										 <img class="car-img" src="<?php echo bloginfo('template_directory');?>/images/vehicle_type/suv-car-img.png" >
									  <?php }
									  elseif($data->vehicle_type == 'Premium Car'){?>
										 <img class="" style="width: 50px;" src="<?php echo bloginfo('template_directory');?>/images/vehicle_type/premium-car.png" >


									  <?php } ?>
									  <?php echo $data->vehicle_type ?>		
							  </label>
						</div>                          
						<?php } //foreach ?> 
									</div>
									<div id="menu1" class="tab-pane fade">
									  <?php
									  global $wpdb; 
									  $row = $wpdb->get_results("SELECT * FROM th_vehicle_type WHERE wheel_type = '1'");
										foreach ($row as $data) {
						?>      
						<div class="inputGroup vehicle_type">
							<input id="<?php echo 'vehicle'.$data->vehicle_type ?>" name="vehicle_type" type="radio" value="<?php echo $data->vehicle_id ?>" />
							<label for="<?php echo 'vehicle'.$data->vehicle_type ?>">

								<?php 
									  if($data->vehicle_type == 'Bike'){?>
										 <img class="car-img" src="<?php echo bloginfo('template_directory');?>/images/vehicle_type/bike.png" >
									  <?php }
									  elseif($data->vehicle_type == 'Activa/Scooter'){?>
										 <img class="car-img" src="<?php echo bloginfo('template_directory');?>/images/vehicle_type/activa.png" >
									  <?php }
									  elseif($data->vehicle_type == 'Autorickshaw'){?>
										 <img class="car-img" src="<?php echo bloginfo('template_directory');?>/images/vehicle_type/auto-rickshaw.png" >
									  <?php }
									  elseif($data->vehicle_type == 'Premium Bike'){?>
										 <img class="" style="width: 50px;" src="<?php echo bloginfo('template_directory');?>/images/vehicle_type/premium-bike.png" >

									  <?php } 
									  ?>
									  <?php echo $data->vehicle_type ?>		
							  </label>
						</div>                          
						<?php } //foreach ?>
									</div>

								  </div>			    			
								</div>
								</div>
								<div class="modal-footer">
									<button class="btn btn-invert" id="vehicle-type-add" type="button"><span>Select</span></button>
									<!-- <a href="<?php echo get_site_url().'/cart';?> " class="btn btn-invert"><span>Add To Cart</span></a> -->
								</div>
							</div>
						</div>
					</div>

						<?php }?>


						</div>
					
					<div class="th-services">
						<?php 
						$vehicle_id=$cart_item['custom_data']['vehicle_type']; 
						//$vehicle_id=$_SESSION['vehicle_type'];
						$services_list_sql = "SELECT sd.*,sdp.rate  FROM th_service_data sd LEFT JOIN th_service_data_price as sdp ON sdp.service_data_id=sd.service_data_id WHERE sdp.vehicle_id = '$vehicle_id' AND sd.status=1";
						$service_results = $wpdb->get_results($services_list_sql);

						foreach ($service_results as $key => $service) {
							$service_id = $service->service_data_id;
							$service_name = $service->service_name;
							if($service_name == 'Tyre Fitment')
							{
							$fitting_rate=$service->rate;
							}

							if($service_name != 'Tyre Fitment')
							{

								?>
								<div class="row">
									<div class="col-md-4 title-sec">
										<?=$service_name?> (per car) 
									</div>
									<div class="col-md-4 price-sec">
										<?=get_woocommerce_currency_symbol().$service->rate?>
										<input type="hidden" name="service_name[]" value="<?=$service->rate;?>">


									</div>
									<div class="col-md-4 add-service">
										<a class="btn btn-invert cart-services" href="#" name="service_name[]" data-cart-id="<?=$installer_table_id;?>" data-id="<?=$cart_item_key?>" id="<?=$cart_item_key?>_checkbox_<?=$service_id?>" data-rate="<?=$service->rate;?>" data-name="<?=$service_name?>" data-vehicle="<?=$vehicle_id;?>"  data-qty="<?=$cart_item_qty;?>" data-session="<?=$session_id;?>" data-product="<?=$cart_item['variation_id'];?>" value="<?=$service_id;?>" <?php if(in_array($service_id,$service_data_id_arr)){ echo 'checked';}?> ><span>Add to Cart</span></a>
									</div>
								</div>
								<?php /*?> <div class="form-check">
									<label for="<?=$cart_item_key?>_checkbox_<?=$service_id?>">
										<input type="checkbox" class="cart-services" name="service_name[]" data-cart-id="<?=$installer_table_id;?>" data-id="<?=$cart_item_key?>" id="<?=$cart_item_key?>_checkbox_<?=$service_id?>" data-rate="<?=$service->rate;?>" data-name="<?=$service_name?>" data-vehicle="<?=$vehicle_id;?>"  data-qty="<?=$cart_item_qty;?>" data-session="<?=$session_id;?>" data-product="<?=$cart_item['variation_id'];?>" value="<?=$service_id;?>" <?php if(in_array($service_id,$service_data_id_arr)){ echo 'checked';}?>/>

										<span class="label-text"> (<?=get_woocommerce_currency_symbol().$service->rate?> / per car)</span>
									</label>
								</div>  <?php */?>

						<?php } ?>                                                                      

							<?php

							}

						?>

						<?php 
						$vehicle_id=$cart_item['custom_data']['vehicle_type'];
						foreach ($addi_service as $key => $service) {
							$SQL="SELECT * FROM th_installer_service_price WHERE vehicle_id='$vehicle_id' and service_data_id='$service->service_data_id' AND city_id=1";
							$service_price = $wpdb->get_row($SQL);
							?>
						<div class="row">
							<div class="col-md-4 title-sec">
								<?=$service->service_name?>
							</div>
							<div class="col-md-4 price-sec">
								<?=get_woocommerce_currency_symbol().$service_price->rate;?>
							</div>
							<div class="col-md-4 add-service">
								<a class="btn btn-invert cart-services" href="#"><span>Add to Cart</span></a>
							</div>
						</div>
						<?php }?>
					</div>
				</div>
			</div>
		</div>
	</div>	
	<?php } }?>
	
	<?php do_action( 'woocommerce_after_cart_table' ); ?>

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