<?php
/**
 * Email Order Items
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-order-items.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates/Emails
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

$text_align = is_rtl() ? 'right' : 'left';
$i=0;
$j=1;
foreach ( $items as $item_id => $item ) :
	$product       = $item->get_product();
	$sku           = '';
	$purchase_note = '';
	$image         = '';

	if ( ! apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
		continue;
	}

	if ( is_object( $product ) ) {
		$sku           = $product->get_sku();
		$purchase_note = $product->get_purchase_note();
		$image         = $product->get_image( $image_size );
	}

	?>
	<div style="width: 100%; float: left; border: none; margin-bottom: 10px;" class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'order_item', $item, $order ) ); ?>">
		<?php
				global $woocommerce , $wpdb;
	
				$sku = 'service_voucher';
			  	$service_voucher_prd = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );

			  	global $wpdb , $woocommerce;
				$order_data = $order->get_data(); // The Order data
				$order_id = $order_data['id'];
				$item_data = $item->get_data();
				$item_qty = $item_data['quantity'];
				
				if($item_data['variation_id'])
				{
					$product_id = $item_data['variation_id'];
				}
				else
				{
					$product_id = $item_data['product_id'];       
				}  
				if($product_id != $service_voucher_prd){  
		?>
		<div class="item-thumb" style="width: 39%; float: left; border:none; height: 150px; overflow: hidden;">
			<?php
				// Show title/image etc.
				if ( $show_image ) {
				//echo wp_kses_post( apply_filters( 'woocommerce_order_item_thumbnail', $image, $item ) );
				}
				//echo get_the_post_thumbnail_url($item['product_id']); 
			?>
			<img width="150px" src="<?php echo get_the_post_thumbnail_url($item['product_id']); ?>" >
		</div>

		<div style="width: 60%; border: none; float: left;" class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>; vertical-align: middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; word-wrap:break-word;">

			<div class="product-name" style="width: 70%; border: none; margin-bottom:10px; float: left;">
    		<?php
    			if($product_id != $service_voucher_prd)
				{
					echo wp_kses_post( apply_filters( 'woocommerce_order_item_name', $item->get_name(), $item, false ) );

					if($item['variation_id'])
					{
						$variation_ID = $item['variation_id'];
						$product_variation = new WC_Product_Variation( $variation_ID );
					    $variation_des = $product_variation->get_description();
					    	    
					    echo ' - '.$variation_des;
					}

					// SKU.
					if ( $show_sku && $sku ) {
						//echo wp_kses_post( ' (#' . $sku . ')' );
					}

					// allow other plugins to add additional product information here.
					do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order, $plain_text );
				//	wc_display_item_meta( $item );

					// allow other plugins to add additional product information here.
					do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order, $plain_text );
					?>
					<div style="border:none;" class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>; vertical-align:middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;"><strong> Qty: <?php echo wp_kses_post( apply_filters( 'woocommerce_email_order_item_quantity', $item->get_quantity(), $item ) ); ?></strong>
					
					</div>
					<?php
				}
			?>				
			</div>	

			<div class="td" style="width: 30%; float: left; border:none; text-align:<?php echo esc_attr( $text_align ); ?>; vertical-align:middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;">
				<?php
					if($product_id != $service_voucher_prd)
					{
						echo wp_kses_post( $order->get_formatted_line_subtotal( $item ) );
					} 
				?>
			</div>

			<?php 
		
			   	$installer = "SELECT * 
			                FROM th_cart_item_installer
			                WHERE order_id = '$order_id' and product_id = '$product_id'";
			    $row = $wpdb->get_results($installer); 
			   	
			    if(!empty($row))
			    {
				    foreach ($row as $key => $installer) 
				    {
				    	$destination = $installer->destination;	    	
				    	$installer_table_id = $installer->cart_item_installer_id;
				    	$installer_id = $installer->installer_id;
				    	$vehicle_id = $installer->vehicle_id;
				    	$installer_name = $wpdb->get_var( $wpdb->prepare( "SELECT business_name FROM th_installer_data WHERE installer_data_id='%s' LIMIT 1" , $installer_id) );

				    	$vehicle_name = $wpdb->get_var( $wpdb->prepare( "SELECT vehicle_type FROM th_vehicle_type WHERE vehicle_id='%s' LIMIT 1" , $vehicle_id) );
				    	$selected_vehicle_id = $installer->vehicle_id;
				    	//$selected_tyre = $installer->no_of_tyre;
				    }

				?>
	<div style="width: 70%; float: left; margin-bottom: 10px;">	
			
		<?php										
			if($destination == '0')
		    {
		    	echo 'Deliver To Home';
		    }
		    else
		    {
?>
				<div class="installer-name"><?php echo '<b>'.$installer_name.'</b>'; ?></div>
<?php 
				   	if($vehicle_name !='')
				   	{
?>
		    			<div class="vehicle-typre"><b>Vehicle Type : </b><?php echo $vehicle_name; ?></div>
<?php 				}
?>
						<div class="product-service-list">
<?php
						    $services = "SELECT * 
				                	FROM th_cart_item_services
				                	WHERE order_id = '$order_id' and product_id = '$product_id'";
				            $row = $wpdb->get_results($services);

				            $service_name = '';
				            $service_list = [];
				            $amount = '';
				            $total_amout = 0;
				            foreach ($row as $key => $service) 
						    {
						    	$tyre_count = $service->tyre;
						    	$service_name = $service->service_name;
						    	$rate = $service->rate;
						    	
						    	$service_list[$service_name] = $tyre_count;

						    	if($service_name == 'Wheel alignment'){
						    		$amount = $rate;
						    		echo '<div>'.$service_name.' '.get_woocommerce_currency_symbol().$rate.' - '.get_woocommerce_currency_symbol().$amount.'</div>';
						    	}
						    	elseif($service_name == 'Tyre Fitment'){
						    		$amount = $rate;
						    		if($rate == 0){
						    			echo '<div>'.$service_name.' - free</div>';
						    		}
						    		else{
						    			echo '<div>'.$service_name.' '.get_woocommerce_currency_symbol().$rate.' - '.get_woocommerce_currency_symbol().$amount.'</div>';
						    		}
						    		
						    	}
						    	else{
						    		$amount = $tyre_count * $rate;
						    		echo '<div>'.$service_name.' '.get_woocommerce_currency_symbol().$rate.' x '.$tyre_count.' - '.get_woocommerce_currency_symbol().$amount.'</div>';
						    	}
						    	$total_amout = $total_amout + $amount;
						    }
						    ?>
						</div>		
					<?php 
			}
					?>
	</div>
	<div class="service-charge" style="width: 30%; border: none; float: left;">
		<?php 
			if($destination != '0')
		    {
		    	echo get_woocommerce_currency_symbol().number_format($total_amout,2,'.','');
		    }
		    else
		    {
		    	$home_delivery_charge = 100 * $item_qty;
		    	echo get_woocommerce_currency_symbol().number_format($home_delivery_charge,2,'.','');
		    }
		?>
	</div>
		
<?php } ?>

</div>
<?php } ?>
	</div>
<?php 

if($product_id == $service_voucher_prd)
{
	$voucher_info = "SELECT * 
                	FROM th_cart_item_service_voucher
                	WHERE order_id = '$order_id' and product_id = '$product_id' LIMIT $i,$j";
                
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


		<div style="width: 100%; float: left; border: none; margin-bottom: 10px;" class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'order_item', $item, $order ) ); ?>">

			<div class="item-thumb" style="width: 39%; float: left; border:none; height: 150px; overflow: hidden;">
				<?php
					// Show title/image etc.
					if ( $show_image ) {
					//echo wp_kses_post( apply_filters( 'woocommerce_order_item_thumbnail', $image, $item ) );
					}
					echo get_the_post_thumbnail_url($item['variation_id']); 
				?>
				<img width="150px" src="<?php echo get_the_post_thumbnail_url($item['product_id']); ?>" >
			</div>

			<div style="width: 60%; border: none; float: left;" class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>; vertical-align: middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; word-wrap:break-word;">

				<div class="product-name" style="width: 70%; border: none; margin-bottom:10px; float: left;">
					<b>Service Voucher</b>
					<?php echo $vehicle_name; ?>
					<div><b><?php echo $installer_name; ?></b></div>
					<div><?php echo $voucher->voucher_name; ?></div>
					<div style="border:none;" class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>; vertical-align:middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;"><strong> Qty:<?php echo $voucher->qty; ?></strong>
					</div>
				</div>

				<div class="td" style="width: 30%; float: left; border:none; text-align:<?php echo esc_attr( $text_align ); ?>; vertical-align:middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;">
		
					<?php 	$rate = $voucher->rate;
							echo get_woocommerce_currency_symbol().number_format((float)$rate, 2, '.', '');  ?>					
				</div>

			</div>
		</div>
		<?php

	}	
	$i++;
	$j++;					
}
?>
<!-- custom code -->
<!-- custom code ends -->
	<?php

	if ( $show_purchase_note && $purchase_note ) {
		?>
		<tr>
			<td colspan="3" style="text-align:<?php echo esc_attr( $text_align ); ?>; vertical-align:middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;">
				<?php
				echo wp_kses_post( wpautop( do_shortcode( $purchase_note ) ) );
				?>
			</td>
		</tr>
		<?php
	}
	?>

<?php endforeach; ?>
