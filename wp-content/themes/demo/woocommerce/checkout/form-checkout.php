<?php
session_start();
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $wpdb , $woocommerce;
foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
	$flag=$cart_item['offline-purchase'];
}
	if(current_user_can('Installer')){
		global $wpdb;
		$user_id = get_current_user_id();
		$SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
		$franchise=$wpdb->get_row($SQL);

		if($franchise){
			if($flag!='yes' && $_SESSION['mobile_no']=='' && $flag!=''){
				wp_redirect(site_url('/cart/'));
			}
			/*echo '<pre>';
			print_r($_SESSION);
			echo '</pre>';*/
			/*if($customer_mobile){

			}else{
				wp_redirect(site_url('/cart/'));
			}*/
		}
	}
?>
<div class="main-breadcrumb">
		<?php main_breadcrumb('checkout');?>
</div>

<?php
wc_print_notices();

do_action('woocommerce_before_checkout_form',$checkout);

// If checkout registration is disabled and not logged in, the user cannot checkout


?>

<?php
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) );
	return;
}

?>
<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

	<?php if ( $checkout->get_checkout_fields() ) : ?>

		<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
		<h1> Payment </h1>
		 <?php if(current_user_can('Installer')){ ?>
		 	<?php
				global $wpdb;
				$current_user_id = get_current_user_id();
				$current_user = get_user_by( 'id', $current_user_id ); // 54 is a user ID
				//	var_dump($current_user);
				$user_id = $current_user->ID;
				$name = $current_user->display_name;
				$number =  $current_user->user_login;
				$email_id =  $current_user->user_email;
				$installer = "SELECT * FROM th_installer_data WHERE user_id = '$user_id'";
		        $row = $wpdb->get_results($installer);
		     //   var_dump($row);
		        foreach ($row as $key => $value)
		        {
		        	$store = $value->business_name;
		        	$add = $value->address;
		        	$gstin = $value->gst_no;
		        	//$email_id = $value->email;
		        }
		        if (\strpos($email_id, 'test') !== false) {
				     $flag =  'true';
				}else{
					 $flag =  'false';
				}
			?>
		 	<div class="my-account installer-info">
		 		<input type="hidden" value="<?php echo $number; ?>" id="hidden-number" />
		 		<input type="hidden" value="<?php echo $name; ?>" id="hidden-name" />
				<div><strong>Contact Person Name: </strong><?php echo $name; ?></div>
				<div><strong>Shop Name: </strong><?php echo $store; ?></div>
				<div><strong>Contact Person No: </strong><?php echo $number; ?></div>
				<div><strong>Shop Address: </strong><?php echo $add; ?></div>
				<div><strong>GSTIN: </strong><?php if($gstin != ""){echo $gstin; }else{ echo "Not Available"; } ?></div>
				<?php if($flag == "false"){ ?>
					<div><strong>Email ID: </strong><?php echo $email_id; ?></div>
				<?php } ?>

			</div>
			
		<?php  } ?>

		
		<?php
			global $wpdb;
			$user_id = get_current_user_id();
			$SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
			$franchise=$wpdb->get_row($SQL);
		?>
		<div class="col2-set" id="customer_details" >
			<div class="col-1"<?php if(current_user_can('Installer') && empty($franchise)){ echo 'style="display:none"'; }?>>
				<?php do_action( 'woocommerce_checkout_billing' ); ?>
			</div>

			<div class="col-2">
				<?php
				global $wpdb , $woocommerce;
			    $current_user = get_current_user_id();
			    $session_id = WC()->session->get_customer_id();
			    $destination = [];
			    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item )
			    {

			        $services = "SELECT *
			                    FROM th_cart_item_installer
			                    WHERE cart_item_key = '$cart_item_key' and session_id = '$session_id'
			                    and order_id = ''";
			                     $row = $wpdb->get_results($services);

			        foreach ($row as $data)
			        {
			            $destination[] = $data->destination;
			        }

			    }
			    if(in_array('0', $destination))
			    {
			        do_action( 'woocommerce_checkout_shipping' );
			    }

				?>
				<!-- <div class="selected_installer_information">
					<h3>Installer Information</h3>
				<?php
						$current_user = get_current_user_id();

						$selected_installer = $current_user.'_selected_installer';
						$installer_data_id = get_option($selected_installer);
                        global $wpdb;
                        if(isset($installer_data_id))
                        {
                        	$sql = "SELECT *
                        			FROM th_installer_data
                        			WHERE installer_data_id = $installer_data_id";
                        	$row = $wpdb->get_results($sql);
                        }

                        foreach ($row as $data)
                        {
                        	?>
                        	<h4><?php echo $data->business_name; ?></h4>
							<div><?php echo $data->address; ?></div>
							<div><?php echo $data->city.'-'.$data->pincode; ?></div>
							<div><?php echo $data->state; ?></div>
							<div><?php echo $data->contact_no; ?></div>
                <?php 	} ?>
				</div> -->
			</div>
		</div>

		<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

	<?php endif; ?>
	
	
	<?php
	// code by bhavesh for OTP form
	foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item ) { 
		$flag=$cart_item['offline-purchase']; 
	}
	if($flag == 'yes') { ?>
	<input type="hidden" name="user_exit" id="user_exit" value="<?php echo $_SESSION['fran_user_id']; ?>">
	<input type="hidden" name="cart_url" id="cart_url" value="<?php echo get_site_url(); ?>/cart">
	<input type="hidden" name="franchise_flag" id="franchise_flag" value="<?php echo $flag; ?>">
		<?php if($_SESSION['fran_user_id']==''){?>
			<div id="message-success" style="color: red;"></div>

			<div class="frachise-user">
				<button type="button" name="create-account-frachise" id="create-account-frachise" class="btn btn-invert"><span>Create Account</span></button>
				<div id="message"></div>
			</div>
			
		<?php }  ?>
			<div id="register-custom-otp" style="display: none;">
				<span class="error-msg" style="color: red;"></span>
				<div class="otp-inner">
					<label for="verify_otp">OTP&nbsp;<span class="required">*</span></label>
					<input type="text" name="verify_otp" id="verify_otp" autocomplete="off" value="">
					<input type="button" name="Resend" id="resendotpbtn" value="Resend" class="btn">
					<input type="button" name="Verify" id="verifyotpbtn" value="Verify" class="btn">

				</div>
			</div>
	<?php } // end of code by bhavesh for OTP form ?>

	<!-- <h3 id="order_review_heading"><?php _e( 'Your order', 'woocommerce' ); ?></h3> -->

	<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

	<div id="order_review" class="woocommerce-checkout-review-order">
		<?php do_action( 'woocommerce_checkout_order_review' ); ?>
	</div>

	<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
