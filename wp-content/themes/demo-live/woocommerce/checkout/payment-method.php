<?php
/**
 * Output a single payment method
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment-method.php.
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
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<li class="wc_payment_method payment_method_<?php echo $gateway->id; ?>">
	<input id="payment_method_<?php echo $gateway->id; ?>" type="radio" class="input-radio" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( $gateway->chosen, true ); ?> data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>" />

	<label for="payment_method_<?php echo $gateway->id; ?>">
		<?php echo $gateway->get_title(); ?> <?php echo $gateway->get_icon(); ?>
	</label>
	<?php if ( $gateway->has_fields() || $gateway->get_description() ) : ?>
		<div class="payment_box payment_method_<?php echo $gateway->id; ?>" <?php if ( ! $gateway->chosen ) : ?>style="display:none;"<?php endif; ?>>
			
			<?php if($gateway->id=='wallet'){
			global $woocommerce , $wpdb;
		    $user_id = get_current_user_id();
		    $SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
		    $franchise=$wpdb->get_row($SQL);
		    $franchise_id=$franchise->installer_data_id;
		    if($franchise){
		    	$SQL="SELECT * FROM th_franchise_payment WHERE 1=1 AND franchise_id = '$franchise_id'  ORDER BY id DESC LIMIT 0,1";
		    	$balance = $wpdb->get_row($SQL);
		    	$cartTotal=number_format(WC()->cart->total,2,'.', '');
		    	/*if($cartTotal>$balance->close_balance){
		    		wc_add_notice( __( 'You have not enough  wallet balance'), 'error' );	
		    	}*/
		    	$available1 = number_format(($balance->close_balance - $cartTotal),2,'.', '');
		    	if($available1<0){
		    		$available ='- <i class="fa fa-inr"></i>'. number_format(abs($available1),2,'.', '');
		    	}else{
		    		$available ='<i class="fa fa-inr"></i>'.$available1;
		    	}
		    	

		    	if($cartTotal>$balance->close_balance){
		    		$string='You do not have enough balance in your wallet. Please <a href="javascript:void();" id="bank-details">add more credit</a> to place the order.';
		    		$color='red';
		    		$disabled='disabled';
		    	}elseif($available<1000){
					$string='You are running low credit balance in your wallet. Please <a href="javascript:void();" id="bank-details">add more credit.</a>';
					$color='#FBBC05';
					$disabled='';
		    	}else{
					$string='You will have '.$available.' credit remaining in your wallet. To <a href="javascript:void();" id="bank-details">add more credit.</a>';
					$color='#34A853';
					$disabled='';
		    	}
		    }


				?>
			<div class="amount-section">
					<div class="panel panel-primary">
					<div class="panel-heading" style="color: #fff; background-color: #2F3672; border-color: #2F3672;">
						<h3 class="panel-title">Wallet Balance</h3>
						<span class="pull-right clickable"></span>
					</div>
					<div class="panel-body">
					<table style="background-color: #fff;">
					<thead>
						<tr><td>Current Balance:</td><td class="tdright"><i class="fa fa-inr"></i> <?=number_format(($balance->close_balance),2,'.', '')?></td></tr>
						<tr><td>Order Amount:</td><td class="tdright"><i class="fa fa-inr"></i> <?=$cartTotal;?></td></tr>
						<tr style="border-top: double 1px;  "><td>Available Balance:</td><td class="tdright" style="color:<?=$color;?>"> <?=$available;?> *</td></tr>
						<tr><td colspan="2" style="color:<?=$color;?>;">* <?=$string;?></td></tr>

					</thead>

				</table>
					</div>
				</div>
				
				<div class="panel panel-primary bank-details-sec" style="display: none;">
					<div class="panel-heading" style="color: #fff; background-color: #2F3672; border-color: #2F3672;">
						<h3 class="panel-title">Bank Detail for RTGS/NEFT/IMPS</h3>
						<span class="pull-right clickable"></span>
					</div>
					<div class="panel-body">
						<table style="background-color: #fff;">
					<thead>
						<tr><td>Beneficiary Name: <strong>ATOZ Tyre Hub Pvt. Ltd</strong></td><td>Beneficiary Account number: <strong>739600301000098</strong></td></tr>
						<tr><td>Beneficiary IFSC Code: <strong>VIJB0007396</strong></td><td>Beneficiary Bank name: <strong>VIJAYA BANK</strong></td></tr>
						<tr><td>Branch name: <strong>MOTERA</strong></td><td></td></tr>
						
					</thead>

				</table>
					</div>
				</div>
			</div>
			<?php }else{?>
			<?php $gateway->payment_fields(); ?>

			<?php }?>
		</div>
	<?php endif; ?>
</li>
<style type="text/css">

.clickable{
    cursor: pointer;   
}

.panel-heading span {
	margin-top: -20px;
	font-size: 15px;
}
.amount-section a {color:#337ab7;  text-decoration: underline!important;}
.panel-primary { width: 65%; }
.tdright { text-align: right; width: 20%; }
</style>
<script type="text/javascript">
jQuery("#bank-details").click(function(){
    jQuery(".bank-details-sec").toggle('slow');
  });
</script>
