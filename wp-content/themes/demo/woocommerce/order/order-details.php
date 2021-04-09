<?php
/**
 * Order details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.6.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! $order = wc_get_order( $order_id ) ) {
	return;
}
$order_items           = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
$show_purchase_note    = $order->has_status( apply_filters( 'woocommerce_purchase_note_order_statuses', array( 'completed', 'processing' ) ) );
$show_customer_details = is_user_logged_in() && $order->get_user_id() === get_current_user_id();
$downloads             = $order->get_downloadable_items();
$show_downloads        = $order->has_downloadable_item() && $order->is_download_permitted();
if ( $show_downloads ) {
	wc_get_template( 'order/order-downloads.php', array( 'downloads' => $downloads, 'show_title' => true ) );
}
?>
<?php
	$order->get_status();
	if($order->has_status('on-hold')){
		 if($order->get_payment_method() == 'bacs'){
			 //echo "<div class='order-hold-msg'><p class='note'>[ Note : This information is important to identify your payment against your order in our system. ]</p><p>Once we receive money we update status to confirm ideal time to change is 1 hour</p></div>";
		 	$remark='"Remarks"';
		 	echo "<div class='order-hold-msg'><p class='note'><small>[#Note : $remark is important to identify your payment against your order in our system. ]</small</p></div>";
		 }
	}
?>
<section class="woocommerce-order-details">
	<?php do_action( 'woocommerce_order_details_before_order_table', $order ); ?>
	<h2 class="woocommerce-order-details__title"><?php _e( 'Order details', 'woocommerce' ); ?></h2>
	<table class="woocommerce-table woocommerce-table--order-details shop_table order_details">
		<thead>
			<tr>
				<th class="woocommerce-table__product-name product-name"><?php _e( 'Product', 'woocommerce' ); ?></th>
				<th class="woocommerce-table__product-table product-total"><?php _e( 'Total', 'woocommerce' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			do_action( 'woocommerce_order_details_before_order_table_items', $order );
			ob_flush();
			$i=0;
			$j=1;
			foreach ( $order_items as $item_id => $item ) {
					$item_data = $item->get_data();
	//var_dump($item_data);
	if($item_data['variation_id'] != ''){
		$item_id = $item_data['variation_id'];
	}
	else{
		$item_id = $item_data['product_id'];
	}
	
				wc_get_template( 'order/order-details-item.php', array(
					'order'			     => $order,
					'item_id'		     => $item_id,
					'item'			     => $item,
					'show_purchase_note' => $show_purchase_note,
					'purchase_note'	     => $product ? $product->get_purchase_note() : '',
					'product'	         => $product,
					'i'	         => $i,
					'j'	         => $j,
				) );
				if($item_id == 3550){
							$i++;
							$j++;
				}
			}
			do_action( 'woocommerce_order_details_after_order_table_items', $order );
			?>
		</tbody>
		<tfoot>
			<?php
				global $wpdb , $woocommerce;
				$order_data = $order->get_data(); // The Order data
				$order_id = $order_data['id'];
				$session_id = WC()->session->get_customer_id();
				$service_charge_total = 0;
				foreach ($order_items as $item)
				{
					$item_data = $item->get_data();
					$item_id = $item_data['product_id'];
					if($item_data['variation_id']){
						$item_id = $item_data['variation_id'];
					}
					$item_qty = $item_data['quantity'];
					$destination_data = "SELECT *
                    FROM th_cart_item_installer
                    WHERE product_id = '$item_id' and order_id = '$order_id'";
			        $row = $wpdb->get_results($destination_data);
			        if(!empty($row))
			        {
			            foreach ($row as $key => $data)
			            {
			                $destination = $data->destination;
			            }
			            if($destination == 1)
			            {
			                $services = "SELECT *
			                        FROM th_cart_item_services
			                        WHERE product_id = '$item_id' and order_id = '$order_id'";
			                 $row = $wpdb->get_results($services);
			                 foreach ($row as $key => $service)
			                {
			                  	$service_name = $service->service_name;
			                    $tyre_count = $service->tyre;
			                    $rate = $service->rate;
			                    if($service_name == 'Wheel alignment'){
			                        $amount =  $rate;
			                    }
			                    else{
			                        $amount = $tyre_count * $rate;
			                    }
			                $service_charge_total = $service_charge_total + $amount;
			                }
			            }
			            if($destination == 0){
			               $service_charge_total = $service_charge_total + (100 * $item_qty);
			               $service_charge_total = 0;
			            }
			        }else{
			        	$destination = 1;
			        }
				}
				foreach ( $order->get_order_item_totals() as $key => $total ) {
					$order_items = $order->get_items();
					$fee = strpos($key, 'fee');
					/*if( strpos( $key, 'fee' ) !== false)
					{
						 $fee_amount = $total['value'];
					}
					else{*/
						if($total['label'] != 'Shipping:'){
		?>
						<tr <?php if($total['label'] == 'Service Charges:'){echo 'hidden';} ?>>
							<th scope="row"><?php echo $total['label']; ?></th>
							<td>
								<?php
									if($key == 'cart_subtotal'){
										$order_subtotal = $order->get_subtotal();
										$final_subtotal = $order_subtotal + $service_charge_total;
										echo get_woocommerce_currency_symbol().number_format($final_subtotal,2,'.','');
									}
									else{
										echo $total['value'];
									}
								?>
							</td>
						</tr>
		<?php
				}		//}
			}
			?>
			<?php if ( $order->get_customer_note() ) : ?>
				<tr>
					<th><?php _e( 'Note:', 'woocommerce' ); ?></th>
					<td><?php echo wptexturize( $order->get_customer_note() ); ?></td>
				</tr>
			<?php endif; ?>
		</tfoot>
	</table>
	<p>
		<div style="font-size: 16px; padding-top: 5px;">
			*It is customer's responsibility to call tyre company and register guarantee and warranty, for more details visit: <a href="<?php echo get_site_url().'/guarantee-warranty/'; ?>" style="color: #474494;"> <?php echo get_site_url().'/guarantee-warranty/'; ?></a>
		</div>
	</p>
	<?php do_action( 'woocommerce_order_details_after_order_table', $order ); ?>
</section>
<?php
if ( $show_customer_details ) {
	wc_get_template( 'order/order-details-customer.php', array( 'order' => $order ) );
}
?>
<p>
</p>
<div class="go-to-home">
	<a class="btn btn-invert" href="<?php echo get_site_url(); ?>"><span>Continue Shopping</span></a>
	<?php
		$user = $order->get_user();
		 $role = $user->roles[0];
		if($role == 'administrator' || $role == 'shop_manager'){
			global $woocommerce;
	 	$pdf_url = wp_nonce_url( admin_url( 'admin-ajax.php?action=generate_wpo_wcpdf&document_type=invoice&order_ids='.$order_id. '&my-account'), 'generate_wpo_wcpdf' );
	 	
	?>
		<a class="btn btn-invert" href="<?php echo $pdf_url; ?>" target="_blank"><span><i class="fa fa-file-pdf-o" aria-hidden="true"></i>&nbsp;Download invoice (PDF)</span></a>
	<?php }
	$order_status = $order->get_status();
	global $wpdb;
 	$SQL="SELECT * FROM th_installer_data WHERE is_franchise='yes' AND user_id='".get_current_user_id()."'";
    $franchise=$wpdb->get_row($SQL);
	//echo $destination;
	if($order_status != 'failed' && $order_status != 'pending' && $order_status != 'cancelled' && $destination == 1 && empty($franchise)) {
		$pdf_url1 = wp_nonce_url( admin_url( 'admin-ajax.php?action=generate_wpo_wcpdf1&document_type=invoice&order_ids='.$order_id. '&prd_id='.$voucher_id.'&my-account'), 'generate_wpo_wcpdf1' );
	?>
		<a class="btn btn-invert" href="<?php echo $pdf_url1; ?>" target="_blank" ><span><i class="fa fa-file-pdf-o" aria-hidden="true"></i>&nbsp;Download Voucher</span></a>
	<?php } ?>

</div>
