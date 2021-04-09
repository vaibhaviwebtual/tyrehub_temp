<?php
/**
 * Checkout terms and conditions area.
 *
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

if ( apply_filters( 'woocommerce_checkout_show_terms', true ) && function_exists( 'wc_terms_and_conditions_checkbox_enabled' ) ) {
	do_action( 'woocommerce_checkout_before_terms_and_conditions' );

	?>
	<div class="woocommerce-terms-and-conditions-wrapper">
		<?php
		/**
		 * Terms and conditions hook used to inject content.
		 *
		 * @since 3.4.0.
		 * @hooked wc_privacy_policy_text() Shows custom privacy policy text. Priority 20.
		 * @hooked wc_terms_and_conditions_page_content() Shows t&c page content. Priority 30.
		 */
		do_action( 'woocommerce_checkout_terms_and_conditions' );
		?>
		<?php
	// code by bhavesh for OTP form
	global $wpdb;
					$user_id = get_current_user_id();
					$SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
					$franchise=$wpdb->get_row($SQL);	
	foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item ) { 
		$flag=$cart_item['offline-purchase']; 
	}
	 ?>


		<?php if ( wc_terms_and_conditions_checkbox_enabled() ) : ?>
			<p class="form-row validate-required">
				<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
				<?php 
					if(current_user_can('shop_manager')){
				?>
				<input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" checked="" name="terms" <?php checked( apply_filters( 'woocommerce_terms_is_checked_default', isset( $_POST['terms'] ) ), true ); // WPCS: input var ok, csrf ok. ?> id="terms" />
				<?php
				}
				else{
				?>
				<?php if($flag == 'yes' && isset($franchise)) {  } else { ?>
				<input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" name="terms" <?php checked( apply_filters( 'woocommerce_terms_is_checked_default', isset( $_POST['terms'] ) ), true ); // WPCS: input var ok, csrf ok. ?> id="terms" />
				<?php }
				}
				?>
				<?php if($flag == 'yes' && isset($franchise)) {  } else { ?>
					<span class="woocommerce-terms-and-conditions-checkbox-text"><?php wc_terms_and_conditions_checkbox_text(); ?></span>&nbsp;<span class="required">*</span>
				<?php } ?>	
				</label>
				<input type="hidden" name="terms-field" value="1" />
			</p>
		<?php endif; ?>
	</div>
	<?php

	do_action( 'woocommerce_checkout_after_terms_and_conditions' );
}
