<td class="product-name" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>">

<?php
	if ( ! $product_permalink ) {
		echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
	} else {
		echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink), $_product->get_name() ), $cart_item, $cart_item_key ) );
	}

	// display selected service list						

	$product_variation_new = wc_get_product( $current_prd );

	$parent_id = wp_get_post_parent_id($current_prd);
	$guarantee_text = get_post_meta($parent_id, '_guarantee_cart', true );
	if($guarantee_text != ''){
		echo '<div class="guarantee-info">*'.$guarantee_text.'</div>';
	}
						

	$prd_attr_vehicle = '';
	$variation_data = $product_variation_new->get_data(); 

	if($variation_data['attributes']['pa_vehicle-type'] != 'car-tyre'){
		$prd_attr_vehicle = $variation_data['attributes']['pa_vehicle-type'];
	}
						
	global $wpdb;
			               
	$installer = "SELECT * 
        	FROM th_cart_item_installer
        	WHERE cart_item_key = '$cart_item_key' and session_id = '$session_id' and order_id = ''";
    $row = $wpdb->get_results($installer);                  			

    $installer_name = '';
    $selected_vehicle_id = '';
    $vehicle_name = '';
    $destination = '';
    $installer_table_id = '';
    foreach ($row as $key => $installer) 
    {
    	$destination = $installer->destination;
    	$installer_table_id = $installer->cart_item_installer_id;
    	$installer_id = $installer->installer_id;
    	$vehicle_id = $installer->vehicle_id;
    	$installer_name = $wpdb->get_var( $wpdb->prepare( "SELECT business_name FROM th_installer_data WHERE installer_data_id='%s' LIMIT 1" , $installer_id) );

    	$selected_vehicle_id = $installer->vehicle_id;
    	//$selected_tyre = $installer->no_of_tyre;
    }

	do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

	// Meta data.
	echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

	// Backorder notification.
	if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
		echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>' ) );
	}
	?>
	<?php
		$user_role = '';
		if( is_user_logged_in() ) 
		{
		    $user = wp_get_current_user();
		    $role = ( array ) $user->roles;
		    $user_role = $role[0];
		}
		else{
			 $user_role = 'guest';
		}
		
		$role_arr = array('Installer');
		if($user_role != '' && !in_array($user_role, $role_arr))
		{
			if($current_prd == $service_voucher_prd)
			{
				?>
				<span class="destination" hidden="">-</span>
				<?php
			}
			else{
				?>
				<span class="destination" hidden=""><?php 
						if(($user_role == 'shop_manager' || $user_role == 'administrator') && $destination == '')
						{echo '--';
						}else{echo $destination;
						}?></span>
				<div class="error-msg"></div>
				<?php
			}
		?>
						
		<div class="service-section">
			<span class="item_data" 
				data-cart-item-id="<?php echo $cart_item_key; ?>" 
				data-product-id="<?php echo $current_prd; ?>"
				data-image='<?php echo $thumbnail; ?>'>	
			</span>							
							
			<!-- Select Deliver to home -->
			<?php //echo $cart_item['custom_data']['vehicle_type']; ?>
			<?php 

			if($current_prd != $service_voucher_prd)
			{
				if($destination == '')
				{
			?>		<div class="row">
						<div class="col-md-12">
							<?php 
							if(isset($_SESSION['current_pincode'])){
								?>
								<span class="selection-btn session-deliver-to-home"  data-id="<?php echo $current_prd; ?>"  data-cart_key="<?php echo $cart_item_key;?>" 
								data-pincode="<?php echo $_SESSION['current_pincode']; ?>" data-session_id="<?php echo $session_id; ?>">Deliver To Home</span>
								<?php
							}
							else{
								?>
								<span class="selection-btn deliver-to-home"  data-id="<?php echo $current_prd; ?>"  data-cart_key="<?php echo $cart_item_key;?>" data-session_id="<?php echo $session_id; ?>"  data-toggle="modal" data-target="#<?php echo $current_prd; ?>_delivery_modal">Deliver To Home</span>
							<?php
							}
							?>
							
							<p class="help-text">Get the tyres delivered to your home address.</p>
							
						</div>
						<div class="col-md-12">
							<span class="selection-btn select-installer"  data-id="<?php echo $current_prd; ?>" data-vehicle="<?php echo $cart_item['custom_data']['vehicle_type']; ?>" data-cart-item-id="<?php echo $cart_item_key; ?>">Select Service Partner</span>
							<p class="help-text">Get your tyres fitting, Alignment and Balancing from our certified Service Partners.</p>
						</div>
					</div>
							
							
							
					<?php include('cart-item-delivery-modal.php');   ?>
		<?php 	} ?>
					
		<?php 	if($installer_name && $destination == '1')
				{
				}
			}
		}?>
	</div>
	
	</td>