<?php
/**
 * Single variation cart button
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

global $product;
?>
<div class="woocommerce-variation-add-to-cart variations_button">
	<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

	<?php
	do_action( 'woocommerce_before_add_to_cart_quantity' );

	/*woocommerce_quantity_input( array(
		'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
		'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
		'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
	) );*/

		$min = 1;
	  $max = 5;
	  $step = 1;
	  $options = '';
	   
	  for ( $count = $min; $count <= $max; $count = $count+$step ) {
	    $options .= '<option value="' . $count . '">' . $count . '</option>';
	  }
	?>
		<div class="quantity"><strong>Qty</strong><select name="quantity" class="product-qty"><?php echo $options; ?></select></div>
	<?php
	do_action( 'woocommerce_after_add_to_cart_quantity' );
	?>


<?php
	$product_variations = $product->get_available_variations();
	
	$arr_variations_id = array();
	foreach ($product_variations as $variation) {
	    $product_variation_id = $variation['variation_id'];
	    $prd_width = $variation['attributes']['attribute_pa_width'];
	   	$prd_ratio = $variation['attributes']['attribute_pa_ratio'];
	    $prd_diameter = $variation['attributes']['attribute_pa_diameter'];
	    $prd_tyre = $variation['attributes']['attribute_pa_tyre-type'];
	    $prd_brand = $variation['attributes']['attribute_pa_brand'];
	    $prd_vehicle_type = $variation['attributes']['attribute_pa_vehicle-type'];	    	    	
		$visiblity = get_post_meta($product_variation_id, 'tyrehub_visible', true  );
		if($visiblity == 'contact-us') {
			$prd_visible_arr[] = $product_variation_id;
		}   
	}
	?>
	<span class="contact-us-prd-list" hidden="">
	<?php 
		foreach ($prd_visible_arr as $key => $prd_id) {
			echo $prd_id.',';
		}
	?>			
	</span>
	<?php 
		global $wpdb , $woocommerce;
		$list_sql = "SELECT * FROM th_soldout_product_list";
		$list_data = $wpdb->get_results($list_sql);

		$soldout_prd_arr = [];         
		foreach ($list_data as $key => $list_row) {
			$soldout_prd_arr[] = $list_row->product_id;
		}
	?>
	<span class="soldout-prd-list" hidden="">
	<?php 
	foreach ($soldout_prd_arr as $key => $soldout_prd_id) {
		echo $soldout_prd_id.',';
	}
	?>			
	</span>
	<button type="submit" class="single_add_to_cart_button button alt"><span><?php echo esc_html( $product->single_add_to_cart_text() ); ?></span></button>
	
	<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

	<input type="hidden" name="add-to-cart" value="<?php echo absint( $product->get_id() ); ?>" />
	<input type="hidden" name="vehicle_type" value="<?php echo $_SESSION['vehicle_type']; ?>" />
	<input type="hidden" name="product_id" value="<?php echo absint( $product->get_id() ); ?>" />
	<input type="hidden" name="variation_id" class="variation_id" value="0" />
	<input type="hidden" name="two_wheel" value="<?php echo $_GET['attribute_pa_vehicle-type']; ?>" />
</div>
</div>
<div class="modal fade" id="duplicate_product" role="dialog">
	<div class="modal-dialog">  
	<!-- Modal content-->
	<div class="modal-content"  style="pointer-events: auto;">
		 <div class="modal-header">        
  		</div>
		<div class="modal-body">
			<p id="pro_msg"></p>				    			
		</div>
		<div class="modal-footer">
			<a href="<?php echo get_site_url().'/cart';?>" id="cartlink" style="display: none" class="btn btn-invert"><span>Cart</span></a>
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		</div>
	</div>
</div>
</div>