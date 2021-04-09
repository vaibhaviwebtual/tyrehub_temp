<?php
/**
 * Order Item Details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details-item.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.7.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
	return;
}
?>
<?php 
	global $wpdb , $woocommerce;
	$order_data = $order->get_data(); // The Order data
	$order_id = $order_data['id'];

	$item_data = $item->get_data();
	//var_dump($item_data);

	if($item_data['variation_id'] != ''){
		$item_id = $item_data['variation_id'];
	}
	else{
		$item_id = $item_data['product_id'];
	}
	$item_qty = $item_data['quantity'];

	$sku = 'service_voucher';
	$service_voucher_prd = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );
if($item_id != $service_voucher_prd){

?>
<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'woocommerce-table__line-item order_item', $item, $order ) ); ?>">
	<?php 
		//var_dump($order);
		
	?>
	<td class="woocommerce-table__product-name product-name">
		<?php
			$is_visible        = $product && $product->is_visible();
			$product_permalink = apply_filters( 'woocommerce_order_item_permalink', $is_visible ? $product->get_permalink( $item ) : '', $item, $order );

			$variation_ID = $variation->ID;

			$product_variation = wc_get_product( $item_id );

		
						
			//echo apply_filters( 'woocommerce_order_item_name', $product_permalink ? sprintf( '<a href="%s">%s</a>', $product_permalink, $item->get_name() ) : $item->get_name(), $item, $is_visible );
			echo $variation_des = $product_variation->get_description();
			echo apply_filters( 'woocommerce_order_item_quantity_html', ' <strong class="product-quantity">' . sprintf( '&times; %s', $item->get_quantity() ) . '</strong>', $item );

			//do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order, false );

			//wc_display_item_meta( $item );

			//do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order, false );
		?>
	</td>

	<td class="woocommerce-table__product-total product-total">
		<?php echo $order->get_formatted_line_subtotal( $item ); ?>
	</td>

</tr>
<?php } ?>
<?php 
	global $wpdb;
	$session_id = WC()->session->get_customer_id();	               
   	$installer = "SELECT * 
                FROM th_cart_item_installer
                WHERE order_id = '$order_id' and session_id = '$session_id' and product_id = '$item_id'";
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
<tr>	
	<td>
<?php										
	if($destination == '0')
    {
    		echo 'Free Home Delivery';
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
<?php 		}
?>
			<div class="product-service-list">
<?php
		    $services = "SELECT * 
                	FROM th_cart_item_services
                	WHERE order_id = '$order_id' and session_id = '$session_id' and product_id = '$item_id'";
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
		    		echo '<div>'.$service_name.' '.get_woocommerce_currency_symbol().$rate.'</div>';
		    	}
		    	elseif($service_name == 'Tyre Fitment'){
		    		$amount = $rate;
		    		if($rate == 0){
		    			echo '<div>'.$service_name.'- free</div>';
		    		}else{
		    			echo '<div>'.$service_name.' '.get_woocommerce_currency_symbol().$rate.'</div>';
		    		}
		    		
		    	}
		    	else{
		    		$amount = $tyre_count * $rate;
		    		echo '<div>'.$service_name.' '.get_woocommerce_currency_symbol().$rate.'</div>';
		    	}
		    	$total_amout = $total_amout + $amount;
		    }
		    ?>
		</div>		
<?php 
	}
?>
	<div style="width: 100%; float: left;">
		<strong>For: </strong><?php echo $item->get_name(); ?>
	</div>
	</td>
	<td style="text-align: right;">
		<?php 
			if($destination != '0')
		    {
		    	echo get_woocommerce_currency_symbol().number_format($total_amout,2,'.','');
		    }
		    if($destination == '0')
		    {
		    	$home_delivery_charge = 100 * $item_qty;
		    	$home_delivery_charge=0;
		    	echo get_woocommerce_currency_symbol().number_format($home_delivery_charge,2,'.','');
		    }
		?>
	</td>


<?php } ?>
</tr>
<?php

	global $woocommerce , $wpdb;
			// get Service Voucher product id 
			
					
	if($item_id == $service_voucher_prd)
	{

		 $voucher_info = "SELECT * 
                	FROM th_cart_item_service_voucher
                	WHERE product_id = '$service_voucher_prd' and session_id = '$session_id' and order_id = '$order_id' LIMIT $i,$j";

        $voucher = $wpdb->get_row($voucher_info);
       /* echo "<pre>";
        print_r($voucher_row);
        echo "</pre>";*/
        //foreach ($voucher_row as $key => $voucher) 
		//{
			$voucher_id = $voucher
			->service_voucher_id;
			$vehicle_id = $voucher->vehicle_id;
			$rate = $voucher->rate;
			$voucher_qty = $voucher->qty;
			$amount = $rate * $voucher_qty;
			$vehicle_name = $wpdb->get_var( $wpdb->prepare( "SELECT vehicle_type FROM th_vehicle_type WHERE vehicle_id='%s' LIMIT 1" , $vehicle_id) );
		
?>
			<tr>
				<td>
					<div>
						<b>Service Voucher</b>
						<?php echo $vehicle_name; ?>
					</div>
					<div>
						<?php echo $voucher->voucher_name; ?>
						<!-- <strong> x <?php echo $voucher->qty; ?></strong> -->
					</div>	
				</td>
				<td>
					<?php echo get_woocommerce_currency_symbol().number_format($amount,2,'.',''); ?>
				</td>
				
			</tr>
<?php 
			
		//}
	}
?>
<?php if ( $show_purchase_note && $purchase_note ) : ?>

<tr class="woocommerce-table__product-purchase-note product-purchase-note">

	<td colspan="2"><?php echo wpautop( do_shortcode( wp_kses_post( $purchase_note ) ) ); ?></td>

</tr>

<?php endif; ?>

