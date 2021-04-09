<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<?php wc_print_notices(); ?>

<?php do_action( 'woocommerce_before_customer_login_form' ); ?>

<?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>
	<div class="admin-url" hidden=""><?php echo admin_url('admin-ajax.php'); ?></div>
	<div class="myaccount-page" hidden=""><?php echo get_site_url().'/my-account'; ?></div>

	<div class="u-columns col2-set" id="customer_login">
	<?php endif; ?>
	<?php 
		global $wp_query;
		if(!isset( $wp_query->query_vars['registration']) && !isset($wp_query->query_vars['register'])){	
	?>
		<div class="col-md-4 col-md-offset-4">
			<div class="u-column1 col-1">
				<div class="row animated fadeInRight" id="first-verify">
					<form class="woocommerce-form woocommerce-form-login login" method="post" id="loginfrm">
						<input type="hidden" name="mobile" value="" id="mobile">
						<div class="alert alert-danger" style="display: none;"></div>
						<h2><?php esc_html_e( 'Login', 'woocommerce' ); ?></h2>
						<div class="row">
							<?php do_action( 'woocommerce_login_form_start' ); ?>
							<div class="col-md-12">

								<div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
							<label class="mobile" for="username"><?php esc_html_e( 'Mobile Number', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
									<div class="mobile_otp">
										<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" value="" autocomplete="off" />

									</div>

								</div>
								<button type="submit" class="woocommerce-Button btn btn-invert" name="login" value="Log in"><span>Log in</span></button>
						   </div>

						</div>
					</form>
				</div>
			<div class="row animated fadeInRight" id="pass-verify" style="display: none;">
				<form class="woocommerce-form woocommerce-form-login login" method="post" id="passfrm">
					<input type="hidden" name="mobile" value="" id="mobile">
					<div class="alert alert-danger" style="display: none;"></div>
					<h2><?php esc_html_e( 'Login', 'woocommerce' ); ?></h2>
					<div class="row">
						<div class="col-md-12" id="pass">
							<div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
								<label class="mobile" for="username"><?php esc_html_e( 'Password', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
								<div class="mobile_otp">
									<input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="off" />
								</div>
							</div>
							<?php
								if(!isset( $wp_query->query_vars['installer-login'] )){
							?>
							<p class="woocommerce-LostPassword lost_password">
								<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'woocommerce' ); ?></a>
							</p>
							<?php
								}
							?>
							<button type="submit" class="woocommerce-Button btn btn-invert" id="passlogin" name="passlogin" value="Log in"><span>Log in</span></button>
						</div>
					</div>
			 		
				</form>
			</div>
			<div class="row animated fadeInRight" id="otp-verify" style="display: none;">
				<form class="woocommerce-form woocommerce-form-login login" method="post" id="otpfrm">
					<input type="hidden" name="mobile" value="" id="mobile">
					<div class="alert alert-danger" style="display: none;"></div>
					<h2><?php esc_html_e( 'Login', 'woocommerce' ); ?></h2>
					<div class="col-md-12" id="otp-sec">
						<div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
							<label class="mobile" for="username"><?php esc_html_e( 'OTP', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
							<div class="mobile_otp">
								<input class="woocommerce-Input woocommerce-Input--text input-text" type="text" name="otp" id="otp" autocomplete="off" />
							</div>
						</div>
						<button type="submit" class="woocommerce-Button btn btn-invert" name="verify" id="verify" value="Verify"><span>Verify</span></button>
				   	</div>
				</form>
			</div>
			<?php do_action( 'woocommerce_login_form_end' ); ?>
		</div>
		</div>
	</div>
	<?php } ?>
	<?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>
	<?php 
		if(isset( $wp_query->query_vars['registration'] ) ){
	?>
	<div class="u-column2 col-2">
		<h2 class="reg-title"><?php esc_html_e( 'Register', 'woocommerce' ); ?></h2>
		<div class="col-md-6 col-md-offset-3">
			<form method="post" action="<?php echo get_site_url(); ?>/my-account/" class="woocommerce-form woocommerce-form-register register custom-registartion-form">
				<span class="error-msg" style="color: red;"></span>
				<div class="row">
					<div class="col-md-6">
						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
							<label for="first_name"><?php esc_html_e( 'First Name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
							<input type="text"  name="first_name" id="first_name" autocomplete="first_name" value="<?php echo ( ! empty( $_POST['first_name'] ) ) ? esc_attr( wp_unslash( $_POST['first_name'] ) ) : ''; ?>" />
						</p>
					</div>
					<div class="col-md-6">
						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
							<label for="last_name"><?php esc_html_e( 'Last Name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
							<input type="text"  name="last_name" id="last_name" autocomplete="last_name" value="<?php echo ( ! empty( $_POST['last_name'] ) ) ? esc_attr( wp_unslash( $_POST['last_name'] ) ) : ''; ?>" />
						</p>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
							<label for="custom_mobile"><?php esc_html_e( 'Mobile No', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
							<input type="text"  name="custom_mobile" id="custom_mobile" autocomplete="custom_mobile" value="<?php echo ( ! empty( $_POST['custom_mobile'] ) ) ? esc_attr( wp_unslash( $_POST['custom_mobile'] ) ) : ''; ?>" minlength="10" maxlength="10" />
						</p>
					</div>
				<div class="col-md-6">			
					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
						<label for="custom_mobile"><?php esc_html_e( ' Do you have whats app on this number?', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>

						<input type="radio" name="mobile_whatsapp" autocomplete="mobile_whatsapp" class="mobile_whatsapp" value="yes" /> Yes <input type="radio" name="mobile_whatsapp" class="mobile_whatsapp" autocomplete="mobile_whatsapp" value="no" /> No 
					</p>
				</div>

				<?php /*?><p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="custom_email"><?php esc_html_e( 'Email Address', 'woocommerce' ); ?>&nbsp;</label>
					<input type="text"  name="custom_email" id="custom_email" autocomplete="custom_email" value="<?php echo ( ! empty( $_POST['custom_email'] ) ) ? esc_attr( wp_unslash( $_POST['custom_email'] ) ) : ''; ?>" />
				</p> 
				<div class="col-md-6">
					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
						<label for="custom_pass"><?php esc_html_e( 'Password', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
						<input type="password"  name="custom_pass" id="custom_pass" autocomplete="custom_pass" value="<?php echo ( ! empty( $_POST['custom_pass'] ) ) ? esc_attr( wp_unslash( $_POST['custom_pass'] ) ) : ''; ?>" />
					</p>
			 	</div> <?php */?>
			 </div>
			 <input type="button" name="Register" value="Register" class="custom_register btn">
		</form>
	   	</div>
	</div>
	<?php } ?>
	<?php 
		if(isset( $wp_query->query_vars['register'] ) ){
	?>
	<script src="<?=bloginfo('template_url');?>/assest/js/jquery.validate.min.js"></script>
	<div class="u-column2 col-2">
		<h2 class="reg-title"><?php esc_html_e( 'Registration', 'woocommerce' ); ?></h2>
		<div class="col-md-6 col-md-offset-3">
			<form method="post" action="<?php echo get_site_url(); ?>/my-account/" class="woocommerce-form woocommerce-form-register register custom-registartion-form" id="register">
				<span class="error-msg" style="color: red;"></span>
				<div class="row">
					<div class="col-md-6">
						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
								<label for="first_name"><?php esc_html_e( 'First Name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
								<input type="text"  name="first_name" id="first_name" autocomplete="first_name" value="<?php echo ( ! empty( $_POST['first_name'] ) ) ? esc_attr( wp_unslash( $_POST['first_name'] ) ) : ''; ?>" />
						</p>
					</div>
					<div class="col-md-6">
						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
								<label for="last_name"><?php esc_html_e( 'Last Name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
								<input type="text"  name="last_name" id="last_name" autocomplete="last_name" value="<?php echo ( ! empty( $_POST['last_name'] ) ) ? esc_attr( wp_unslash( $_POST['last_name'] ) ) : ''; ?>" />
						</p>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
							<label for="custom_mobile"><?php esc_html_e( 'Mobile No', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
							<input type="text"  name="custom_mobile" id="custom_mobile" autocomplete="custom_mobile" value="<?php echo ( ! empty( $_POST['custom_mobile'] ) ) ? esc_attr( wp_unslash( $_POST['custom_mobile'] ) ) : ''; ?>" minlength="10" maxlength="10" />
						</p>
					</div>
					<div class="col-md-6">
						<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
							<label for="custom_email"><?php esc_html_e( 'Email Address', 'woocommerce' ); ?></label>
							<input type="text"  name="custom_email" id="custom_email" autocomplete="custom_email" value="<?php echo ( ! empty( $_POST['custom_email'] ) ) ? esc_attr( wp_unslash( $_POST['custom_email'] ) ) : ''; ?>" />
						</p>
			 		</div>
			 	</div>
				<div class="row">
					<div class="col-md-12">
						<label for="custom_mobile"><?php esc_html_e( ' Which type of vehicle do you have?', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>

						<p class="checkbox-part">
						<label for="vehicle_type">Four Wheeler</label>
						<input type="checkbox" name="vehicle_type[]" id="vehicle_type" value="four" /> 
						</p>
						<p class="checkbox-part">
						<label for="vehicle_type1">Two Wheeler</label>
						<input type="checkbox" name="vehicle_type[]" id="vehicle_type1" value="two" /> 
						</p>
					</div>
				</div>
				<input type="submit" name="Register" value="Register" class="btn">			
			</form>
	   </div>
	</div>
<?php } ?>
<?php endif; ?>
<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
</div>