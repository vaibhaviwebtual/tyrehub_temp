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
	<div style="width: 100%; float: left; border-top:1px solid #e5e5e5;" class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'order_item', $item, $order ) ); ?>">
		<div class="item-thumb" style="width: 39%; float: left; border:none; text-align: center;">
		<?php
			// Show title/image etc.
			if ( $show_image ) {
			echo wp_kses_post( apply_filters( 'woocommerce_order_item_thumbnail', $image, $item ) );
			}

		?>
			<img width="100px" src="<?php echo get_the_post_thumbnail($item['product_id']); ?>
		</div>

		<div style="width: 60%; border: none; float: left;" class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>; vertical-align: middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; word-wrap:break-word;">
			<div style="border:none;">
    		<?php
				echo wp_kses_post( apply_filters( 'woocommerce_order_item_name', $item->get_name(), $item, false ) );

				if($item['variation_id'])
				{
					$variation_ID = $item['variation_id'];
					$product_variation = wc_get_product( $variation_ID );
				    $variation_des = $product_variation->get_description();
				    	    
				    echo ' - '.$variation_des;
				}

				// SKU.
				if ( $show_sku && $sku ) {
					echo wp_kses_post( ' (#' . $sku . ')' );
				}

				// allow other plugins to add additional product information here.
				do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order, $plain_text );

				//wc_display_item_meta( $item );

				// allow other plugins to add additional product information here.
				do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order, $plain_text );

			?>
			</div>
		

		<div style="border:none;" class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>; vertical-align:middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;">
			<?php echo wp_kses_post( apply_filters( 'woocommerce_email_order_item_quantity', $item->get_quantity(), $item ) ); ?>
		</div>
		<div class="td" style=" border:none; text-align:<?php echo esc_attr( $text_align ); ?>; vertical-align:middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;">
			<?php echo wp_kses_post( $order->get_formatted_line_subtotal( $item ) ); ?>
		</div>
		</div>
	</div>
	<!-- custom code -->
	<?php 
	global $wpdb , $woocommerce;
	$order_data = $order->get_data(); // The Order data
	$order_id = $order_data['id'];
	$item_data = $item->get_data();
	$item_qty = $item_data['quantity'];
	//var_dump($item_data);
	if($item_data['variation_id'])
	{
		$item_id = $item_data['variation_id'];
	}
	else
	{
		$item_id = $item_data['product_id'];       
	}    

   	$installer = "SELECT * 
                FROM th_cart_item_installer
                WHERE order_id = '$order_id' and product_id = '$item_id'";
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
	<div style="width: 100%; float: left; border-bottom: 1px solid #e5e5e5; margin-bottom: 20px;">	
	<div style="width: 39%; float: left;">
		<strong>Services: </strong><?php echo $item->get_name(); ?>
	</div>
	
	<div style="width: 60%; float: left;">
		<?php										
	if($destination == '0')
    {
    		echo '<div>Deliver To Home</div>';
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
                	WHERE order_id = '$order_id' and product_id = '$item_id'";
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
	<div style="width: 100%; float: left;">
		
	</div>

		<?php 
			if($destination != '0')
		    {
		    	echo get_woocommerce_currency_symbol().number_format($total_amout,2,'.','');
		    }
		    if($destination == '0')
		    {
		    	$home_delivery_charge = 100 * $item_qty;
		    	echo get_woocommerce_currency_symbol().number_format($home_delivery_charge,2,'.','');
		    }
		?>
	</div>


<?php } ?>
</div>
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
