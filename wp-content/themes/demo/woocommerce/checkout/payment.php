<?php
/**
 * Checkout Payment Section
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.3
 */

defined( 'ABSPATH' ) || exit;
global $wpdb;
error_reporting(0);
/*$SQL="SELECT * FROM th_installer_data WHERE is_franchise='yes'";
$installer=$wpdb->get_results($SQL);

foreach ($installer as $key => $inst) {
	$SQL="SELECT * FROM wp_franchises_payment_method";
	$gateway=$wpdb->get_results($SQL);
	foreach ($gateway as $key => $value) {
		$data=array('payment_id'=>$value->id,'franchise_id'=>$inst->installer_data_id,'status'=>1);
		$wpdb->insert('wp_franchises_choose_pmethod',$data);
	}




}
die;*/
$SQL="SELECT * FROM th_installer_data WHERE is_franchise='yes' AND user_id='".get_current_user_id()."'";
$franchise=$wpdb->get_row($SQL);
	if($franchise){
		foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item ) {

			$flag=$cart_item['offline-purchase'];
		}
	}


if ( ! is_ajax() ) {
	do_action( 'woocommerce_review_order_before_payment' );
}
?>
<div id="payment" class="woocommerce-checkout-payment">
	<?php if ( WC()->cart->needs_payment() ) : ?>
		<?php


	    if(empty($franchise) || $flag!='yes'){

		?>
		<ul class="wc_payment_methods payment_methods methods">
			<?php
			if ( ! empty( $available_gateways ) ) {
				foreach ( $available_gateways as $gateway ) {
					wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
				}
			} else {
				echo '<li class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . apply_filters( 'woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ) : esc_html__( 'Please fill in your details above to see available payment methods.', 'woocommerce' ) ) . '</li>'; // @codingStandardsIgnoreLine
			}
			?>
		</ul>
	<?php }?>
	<?php endif; ?>
	<div class="form-row place-order">
		<noscript>
			<?php esc_html_e( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the <em>Update Totals</em> button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce' ); ?>
			<br/><button type="submit" class="button alt" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e( 'Update totals', 'woocommerce' ); ?>"><?php esc_html_e( 'Update totals', 'woocommerce' ); ?></button>
		</noscript>

		<?php

		 if($flag=='yes'){?>
		 	<span id="pay_radio_error"></span>
			<ul class="payment_list">
				<?php
					global $wpdb;
					$SQL="SELECT DISTINCT(fpm.id), fpm.* FROM wp_franchises_choose_pmethod fcp  LEFT JOIN  wp_franchises_payment_method as fpm ON fpm.id=fcp.payment_id WHERE fcp.status=1 AND fcp.franchise_id=".$franchise->installer_data_id." ORDER BY fpm.sorting ASC";
					$choosemethod=$wpdb->get_results($SQL);

					//$SQL="SELECT * FROM wp_franchises_payment_method WHERE status=1";
					//$pmethod=$wpdb->get_results($SQL);

					foreach ($choosemethod as $key => $method) {
						if(in_array(@$method->payment_id,$franchise))
						?>
				<li>
				  <input type="radio" id="pmethod_<?=$method->id;?>" name="payment_type" value="<?=$method->id;?>">
				  <label for="pmethod_<?=$method->id;?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icon/payment/<?=$method->payment_logo;?>"><span><?=$method->payment_method;?></span></label>
				</li>
			<?php }?>
			</ul>
		<?php } ?>
		<?php wc_get_template( 'checkout/terms.php' ); ?>

		<?php do_action( 'woocommerce_review_order_before_submit' ); ?>

		<?php
		//******************Code for Franchise Customer user created or register and cart******************************//

			if($franchise){
				$SQL="SELECT * FROM th_franchise_payment WHERE 1=1 AND franchise_id = '$franchise->installer_data_id'  ORDER BY id DESC LIMIT 0,1";
		    	$balance = $wpdb->get_row($SQL);
		    	$cartTotal=number_format(WC()->cart->total,2,'.', '');
		    	

		    	if($cartTotal>$balance->close_balance){
		    		
		    		$disabled='disabled';
		    	}elseif($available<1000){
					
					$disabled='';
		    	}else{
					
					$disabled='';
		    	}
					if($flag == 'yes'){ ?>
					<?php if($_SESSION['fran_user_id']==''){
						$disabled='disabled';
					}else{
						$disabled='';
					}?>
						<form id="frmcustom_order" name="frmcustom_order" action="#">
					<input type="hidden" name="created_user_id"  id="created_user_id" value="<?php echo $_SESSION['fran_user_id']; ?>">
						</form>
						&nbsp;&nbsp;&nbsp;<input type="button" class="placeorder button alt" style="color: #000;background: #E4C24D;border-radius: 0;" name="placeorder" id="placeorder" <?=$disabled;?> value="Place Order">
					<?php } else {

							echo apply_filters( 'woocommerce_order_button_html', '<button type="submit" '.$disabled.' class="button alt" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">' . esc_html( $order_button_text ) . '</button>' );
						}	?>
			<?php } else { ?>

					<?php echo apply_filters( 'woocommerce_order_button_html', '<button type="submit"  class="button alt" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">' . esc_html( $order_button_text ) . '</button>' ); // @codingStandardsIgnoreLine ?>

			<?php } ?>

		<?php echo apply_filters( 'woocommerce_backtocart_button_html', '<a href="'.esc_attr(site_url('/cart/')).'" id="place_order" class="button alt" style=" margin-left:10px;">Back</a>' ); // @codingStandardsIgnoreLine ?>

		<?php do_action( 'woocommerce_review_order_after_submit' ); ?>

		<?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>
	</div>
</div>
<?php
if ( ! is_ajax() ) {
	do_action( 'woocommerce_review_order_after_payment' );
}
