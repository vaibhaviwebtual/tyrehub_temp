<?php
/**
 * Lost password confirmation text.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/lost-password-confirmation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wc_print_notices();
//wc_print_notice( __( 'Password reset email has been sent.', 'woocommerce' ) );
?>

<!-- <p><?php // echo apply_filters( 'woocommerce_lost_password_message', __( 'A password reset email has been sent to the email address on file for your account, but may take several minutes to show up in your inbox. Please wait at least 10 minutes before attempting another reset.', 'woocommerce' ) ); ?></p> -->
<div class="pass-verified-section">
	<p><?php  echo apply_filters( 'woocommerce_lost_password_message', __( 'Your mobile number is verified successfully. Please Click on below link to reset password.', 'woocommerce' ) ); ?></p>

	<p>
	<?php 
		global $woocommerce , $wpdb;
		$final_key = $_SESSION['pass_reset_key'];
		$user_id = $_SESSION['reset_password_user'];

		$user = get_user_by('id', $user_id);
		
		$user_login = $user->user_login;
	  

		$rp_link = '<a class="dcreset-button" style="" href="' .get_permalink( get_option('woocommerce_myaccount_page_id'))."/lost-password/?action=rp&key=$final_key&login=" . rawurlencode($user_login) . '">Reset Password</a>';

		echo $rp_link;
	?>
	</p>
</div>
