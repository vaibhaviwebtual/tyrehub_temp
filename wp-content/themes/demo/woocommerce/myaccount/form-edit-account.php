<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
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

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_edit_account_form' ); ?>

<form class="woocommerce-EditAccountForm edit-account" action="" method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?>>

	
	<?php 
		if(current_user_can('Installer'))
		{	global $wpdb;
			$user_id = get_current_user_id();
		    $SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
		    $franchise=$wpdb->get_row($SQL);
		    if($franchise){
		    	if(!isset($_SESSION['admin_access'])) {
					wp_redirect(site_url('/my-account/franchise-home/'));
				}
		    }
			
				$current_user_id = get_current_user_id();
				$current_user = get_user_by( 'id', $current_user_id ); // 54 is a user ID
				
				$user_id = $current_user->ID;
				$name = $current_user->display_name;
				$number =  $current_user->user_login;
				$email_id =  $current_user->user_email;

				$installer = "SELECT * FROM th_installer_data WHERE user_id = '$user_id'";
		        $row = $wpdb->get_results($installer);

		        foreach ($row as $key => $value)
		        {
		        	$installer_id = $value->installer_data_id;
		        	$store = $value->business_name;
		        	$add = $value->address;
		        	$gstno = $value->gst_no;
		        	$state = $value->state;
		        	$pincode = $value->pincode;
		        	$city = $value->city;
		        	$water = $value->water_service;
		        	$wifi = $value->wifi_service;
		        	$tea = $value->tea_service;
		        	$pickup = $value->car_pickup_service;
		        	$cmp_name = $value->company_name;
		        	$cmp_add = $value->company_add;
		        	$store_phone = $value->store_phone;
		        	$contact_person = $value->contact_person;
		        }
		
	?>
	<?php do_action( 'woocommerce_edit_account_form_start' ); ?>
	<legend><?php esc_html_e( 'Service Center Details', 'woocommerce' ); ?></legend>

		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-first">
			<label for="store_name"><?php esc_html_e( 'Service center name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
			<input type="text" class="woocommerce-Input input-text" name="store_name" id="store_name"  value="<?php echo $store; ?>" /><span><em><?php esc_html_e( 'This will be how your name will be displayed in the service partner page', 'woocommerce' ); ?></em></span>
		</p>

		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-last">
			<label for="store_name"><?php esc_html_e( 'Store Phone', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
			<input type="text" class="woocommerce-Input input-text" name="store_phone" id="store_phone"  value="<?php echo $store_phone; ?>" /><span></span>
		</p>

		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="store_add"><?php esc_html_e( 'Address', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
			<input type="text" class="woocommerce-Input input-text" name="store_add" id="store_add"  value="<?php echo $add; ?>" />
		</p>
		<br>

	<legend><?php esc_html_e( 'Primary Contact Person', 'woocommerce' ); ?></legend>

		<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
			<label for="account_display_name"><?php esc_html_e( 'Contact Person Name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
			<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_display_name" id="account_display_name" value="<?php echo esc_attr( $contact_person ); ?>" /> <span><em><?php esc_html_e( 'This will be how your name will be displayed in the account section and in reviews', 'woocommerce' ); ?></em></span>
		</p>

		<p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
			<label for="mobile_no"><?php esc_html_e( 'Mobile No.', 'woocommerce' ); ?>&nbsp;</label>
			<input type="text" readonly="" class="woocommerce-Input input-text" name="mobile_no" id="mobile_no"  value="<?php echo $user->user_login; ?>" />
		</p>

		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="account_email"><?php esc_html_e( 'Email address', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
			<input type="text" class="woocommerce-Input woocommerce-Input--email input-text" name="account_email" id="account_email" autocomplete="email" value="<?php echo esc_attr( $user->user_email ); ?>" />
		</p>

		<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first" hidden="">
			<label for="account_first_name"><?php esc_html_e( 'First name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
			<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_first_name" id="account_first_name" autocomplete="given-name" value="<?php echo esc_attr( $user->first_name ); ?>" />
		</p>

		<p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last" hidden="">
			<label for="account_last_name"><?php esc_html_e( 'Last name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
			<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_last_name" id="account_last_name" autocomplete="family-name" value=" " />
		</p>

	
		
	
	<br>
	<legend><?php esc_html_e( 'Additional Information', 'woocommerce' ); ?></legend>

		<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
			<label for="gst_no"><?php esc_html_e( 'GST No.', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
			<input type="text" class="woocommerce-Input input-text" name="gst_no" id="gst_no" maxlength="15"  value="<?php echo $gstno; ?>" />
		</p>

		<p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
			<label for="cmp_name"><?php esc_html_e( 'Company Name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
			<input type="text" class="woocommerce-Input input-text" name="cmp_name" id="cmp_name"  value="<?php echo $cmp_name; ?>" />
		</p>

		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="cmp_add"><?php esc_html_e( 'Company Address', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
			<input type="text" class="woocommerce-Input input-text" name="cmp_add" id="cmp_add"  value="<?php echo $cmp_add; ?>" />
		</p>
	<!-- <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
		<label for="store_add"><?php esc_html_e( 'State', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
		<input type="text" class="woocommerce-Input input-text" name="state" id="state"  value="<?php echo $state; ?>" />
	</p>

	<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
		<label for="store_add"><?php esc_html_e( 'City', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
		<input type="text" class="woocommerce-Input input-text" name="city" id="city"  value="<?php echo $city; ?>" />
	</p>

	<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
		<label for="store_add"><?php esc_html_e( 'Pincode', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
		<input type="text" class="woocommerce-Input input-text" name="pincode" id="pincode"  value="<?php echo $pincode; ?>" />
	</p>

 -->

 	<br>
	<legend>Facilities </legend>
	<?php
        global $wpdb;
        $fc_sql = "SELECT * from th_installer_facilities where type = 'f'";
        $fc_data = $wpdb->get_results($fc_sql);

        
        $sfc_sql = $wpdb->get_var("SELECT meta_value from th_installer_meta where installer_id = '$installer_id' and meta_name = 'facilities'");
        

        $sfc_sql_arr = unserialize($sfc_sql); 
        //var_dump($sfc_sql_arr);
        foreach ($fc_data as $key => $fc_row)
        {
            $name = $fc_row->name;
            $f_id = $fc_row->f_id;
            if (is_array($sfc_sql_arr)) {
			  $selected = in_array(strtolower($f_id), array_map('strtolower', $sfc_sql_arr))?' selected="selected"':'';
			} else {
			  $selected = '';
			}


            ?>
            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <input type="checkbox" class="" disabled name="fc-check[]" id="<?php echo $f_id; ?>" value="<?php echo $f_id; ?>" <?php echo $selected; ?>/>&nbsp;<?php esc_html_e( $name , 'woocommerce' ); ?>      
            </p>
            <?php
        }
        ?>
	
	<input type="text" name="installer_id" value="<?php echo $installer_id; ?>" hidden>

	<br>
	<legend>Additional Services</legend>
	<?php
        global $wpdb;
        $fc_sql = "SELECT * from th_service_data where (as_flag =1  OR service_data_id=4) AND status=1";
        $services_data = $wpdb->get_results($fc_sql);

        $SQL="SELECT service_data_id from th_installer_addi_service where installer_id = '$installer_id'";
        $sas_sql_arr = $wpdb->get_results($SQL);                        
       
        foreach ($sas_sql_arr as $key => $seleservice){
        	$servdata[]=$seleservice->service_data_id;
        }


	     foreach ($services_data as $key => $service){?>
                              <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                  <input type="checkbox" class="" disabled name="services[]" value="<?php echo $service->service_data_id; ?>" <?php if (is_array($servdata)) { if(in_array($service->service_data_id,$servdata)){ echo 'checked';} }  ?> />&nbsp;<?php esc_html_e($service->service_name,'woocommerce'); ?>      
                              </p>
                              <?php
                          }?>
     <?php 
		global $wpdb;
		$SQL="SELECT * FROM th_installer_data WHERE is_franchise='yes' AND user_id='".get_current_user_id()."'";
	    $franchise=$wpdb->get_row($SQL);
     if( $franchise){
     ?>

	<br>
	<legend>Accept Payment Method</legend>
	<?php
        global $wpdb;
        $paym_sql = "SELECT * from wp_franchises_payment_method WHERE status=1";
        $pmethod_data = $wpdb->get_results($paym_sql);

        $SQL="SELECT payment_id from wp_franchises_choose_pmethod  where franchise_id = '$installer_id'";
        $choosepay = $wpdb->get_results($SQL);                        
       
        foreach ($choosepay as $key => $choose){
        	$choosepayment[]=$choose->payment_id;
        }


	     foreach ($pmethod_data as $key => $payment){?>
              <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                  <input type="checkbox" class=""  name="pay_method[]" value="<?php echo $payment->id; ?>" <?php if (is_array($choosepayment)) { if(in_array($payment->id,$choosepayment)){ echo 'checked';} }  ?> />&nbsp;<?php esc_html_e($payment->payment_method,'woocommerce'); ?>      
              </p>
              <?php
          }?>
<?php }?>
	<br>
	<fieldset>
		
		<legend><?php esc_html_e( 'Change Password', 'woocommerce' ); ?></legend>

		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="password_current"><?php esc_html_e( 'Current password (leave blank to leave unchanged)', 'woocommerce' ); ?></label>
			<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_current" id="password_current" autocomplete="off" />
		</p>
		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="password_1"><?php esc_html_e( 'New password (leave blank to leave unchanged)', 'woocommerce' ); ?></label>
			<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_1" id="password_1" autocomplete="off" />
		</p>
		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="password_2"><?php esc_html_e( 'Confirm new password', 'woocommerce' ); ?></label>
			<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_2" id="password_2" autocomplete="off" />
		</p>
	</fieldset>
	<div class="clear"></div>

	<?php do_action( 'woocommerce_edit_account_form' ); ?>

	<p>
		<?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
		<button type="submit" class="woocommerce-Button button btn-invert" name="save_account_details" value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>"><?php esc_html_e( 'Save changes', 'woocommerce' ); ?></button>
		<input type="hidden" name="action" value="save_account_details" />
	</p>

	<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
<?php } 
elseif(current_user_can('customer') || current_user_can('btobpartner')){
	$gst_no = get_user_meta( $user->ID, 'gst_no', true );
    $cmp_name = get_user_meta( $user->ID, 'company_name', true );
    $cmp_add = get_user_meta( $user->ID, 'company_add', true );
	?>
		<?php do_action( 'woocommerce_edit_account_form_start' ); ?>

		<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
			<label for="account_first_name"><?php esc_html_e( 'First name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
			<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_first_name" id="account_first_name" autocomplete="given-name" value="<?php echo esc_attr( $user->first_name ); ?>" />
		</p>
		<input type="hidden" name="user_id" value="<?php echo esc_attr( $user->ID ); ?>">
		<p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
			<label for="account_last_name"><?php esc_html_e( 'Last name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
			<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_last_name" id="account_last_name" autocomplete="family-name" value="<?php echo esc_attr( $user->last_name ); ?>" />
		</p>
		<div class="clear"></div>

		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="account_display_name"><?php esc_html_e( 'Display name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
			<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_display_name" id="account_display_name" value="<?php echo esc_attr( $user->display_name ); ?>" /> <span><em><?php esc_html_e( 'This will be how your name will be displayed in the account section and in reviews', 'woocommerce' ); ?></em></span>
		</p>
		<div class="clear"></div>

		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="account_email"><?php esc_html_e( 'Email address', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
			<input type="email" class="woocommerce-Input woocommerce-Input--email input-text" name="account_email" id="account_email" autocomplete="email" value="<?php echo esc_attr( $user->user_email ); ?>" />
		</p>

		<legend><?php esc_html_e( 'Additional Information', 'woocommerce' ); ?></legend>

		<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
			<label for="gst_no"><?php esc_html_e( 'GST No.', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
			<input type="text" class="woocommerce-Input input-text" name="gst_no" id="gst_no"  value="<?php echo $gst_no; ?>" />
		</p>

		<p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
			<label for="cmp_name"><?php esc_html_e( 'Company Name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
			<input type="text" class="woocommerce-Input input-text" name="cmp_name" id="cmp_name"  value="<?php echo $cmp_name; ?>" />
		</p>

		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
			<label for="cmp_add"><?php esc_html_e( 'Company Address', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
			<input type="text" class="woocommerce-Input input-text" name="cmp_add" id="cmp_add"  value="<?php echo $cmp_add; ?>" />
		</p>


		<fieldset>
			<legend><?php esc_html_e( 'Password change', 'woocommerce' ); ?></legend>

			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="password_current"><?php esc_html_e( 'Current password (leave blank to leave unchanged)', 'woocommerce' ); ?></label>
				<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_current" id="password_current" autocomplete="off" />
			</p>
			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="password_1"><?php esc_html_e( 'New password (leave blank to leave unchanged)', 'woocommerce' ); ?></label>
				<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_1" id="password_1" autocomplete="off" />
			</p>
			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="password_2"><?php esc_html_e( 'Confirm new password', 'woocommerce' ); ?></label>
				<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_2" id="password_2" autocomplete="off" />
			</p>
		</fieldset>
		<div class="clear"></div>

		<?php do_action( 'woocommerce_edit_account_form' ); ?>

		<p>
			<?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
			<button type="submit" class="woocommerce-Button button" name="save_account_details" value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>"><?php esc_html_e( 'Save changes', 'woocommerce' ); ?></button>
			<input type="hidden" name="action" value="save_account_details" />
		</p>

		<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
	<?php
}elseif(current_user_can('supplier')){
				global $wpdb;
				$current_user_id = get_current_user_id();
				$current_user = get_user_by( 'id', $current_user_id ); // 54 is a user ID
				
				$user_id = $current_user->ID;
				$name = $current_user->display_name;
				$number =  $current_user->user_login;
				$email_id =  $current_user->user_email;

				$installer = "SELECT * FROM th_supplier_data WHERE user_id = '$user_id'";
		        $row = $wpdb->get_results($installer);

		        foreach ($row as $key => $value)
		        {
		        	$supplier_id = $value->supplier_data_id;
		        	$store = $value->business_name;
		        	$add = $value->address;
		        	$gstno = $value->gst_no;
		        	$state = $value->state;
		        	$pincode = $value->pincode;
		        	$city = $value->city;
		        	$city_id = $value->city_id;
		        	$cmp_name = $value->company_name;
		        	$cmp_add = $value->company_add;
		        	$store_phone = $value->store_phone;
		        	$contact_person = $value->contact_person;
		        }
		
	?>
	<?php do_action( 'woocommerce_edit_account_form_start' ); ?>
		<div class="suppliers-details">
			<legend><?php esc_html_e( 'Suppliers Details', 'woocommerce' ); ?></legend>
			<input type="hidden" name="user_id" value="<?php echo esc_attr( $user->ID ); ?>">
			<div class="col-md-6 col-sm-6 box">
				<label for="store_name"><?php esc_html_e( 'Supplier Store name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
				<input type="text" class="woocommerce-Input input-text" name="store_name" id="store_name"  value="<?php echo $store; ?>" /><span><em><?php esc_html_e( 'This will be how your name will be displayed in the service partner page', 'woocommerce' ); ?></em></span>
			</div>
			<div class="col-md-6 col-sm-6 box">
				<label for="store_name"><?php esc_html_e( 'Store Phone', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
				<input type="text" class="woocommerce-Input input-text" name="store_phone" id="store_phone"  value="<?php echo $store_phone; ?>" /><span></span>
			</div>
			<div class="col-md-12 col-sm-12 box">
				<label for="store_add"><?php esc_html_e( 'Address', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
				<input type="text" class="woocommerce-Input input-text" name="store_add" id="store_add"  value="<?php echo $add; ?>" />
			</div>
		</div>
	
		<div class="suppliers-details">
			<legend><?php esc_html_e( 'Primary Contact Person', 'woocommerce' ); ?></legend>
			<div class="col-md-4 col-sm-4 box">
				<label for="account_display_name"><?php esc_html_e( 'Contact Person Name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
				<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_display_name" id="account_display_name" value="<?php echo esc_attr( $contact_person ); ?>" /> <span><em><?php esc_html_e( 'This will be how your name will be displayed in the account section and in reviews', 'woocommerce' ); ?></em></span>
			</div>
			<div class="col-md-4 col-sm-4 box">
				<label for="mobile_no"><?php esc_html_e( 'Mobile No.', 'woocommerce' ); ?>&nbsp;</label>
				<input type="text" readonly="" class="woocommerce-Input input-text" name="mobile_no" id="mobile_no"  value="<?php echo $user->user_login; ?>" />
			</div>
			<div class="col-md-4 col-sm-4 box">
				<label for="account_email"><?php esc_html_e( 'Email address', 'woocommerce' ); ?></label>
				<input type="text" class="woocommerce-Input woocommerce-Input--email input-text" name="account_email" id="account_email" autocomplete="email" value="<?php echo esc_attr( $user->user_email ); ?>" />
			</div>
		</div>
		<div class="suppliers-details">
			<legend><?php esc_html_e( 'Additional Information', 'woocommerce' ); ?></legend>
			<div class="col-sm-6 col-md-6 box">
				<label for="gst_no"><?php esc_html_e( 'GST No.', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
				<input type="text" class="woocommerce-Input input-text" name="gst_no" id="gst_no"  value="<?php echo $gstno; ?>" />
			</div>
			<div class="col-sm-6 col-md-6 box">
				<label for="cmp_name"><?php esc_html_e( 'Company Name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
				<input type="text" class="woocommerce-Input input-text" name="cmp_name" id="cmp_name"  value="<?php echo $cmp_name; ?>" />
			</div>
			<div class="col-sm-12 col-md-12 box">
				<label for="cmp_add"><?php esc_html_e( 'Company Address', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
				<input type="text" class="woocommerce-Input input-text" name="cmp_add" id="cmp_add"  value="<?php echo $cmp_add; ?>" />
			</div>
			<div class="col-sm-4 col-md-4 box">
				<label for="store_add"><?php esc_html_e( 'City', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
				<?php /*?> <input type="text" class="woocommerce-Input input-text" name="city" id="city"  value="<?php echo $city; ?>" /> <?php */?>
				<?php
                	global $wpdb, $woocommerce;
                    $city_sql = "SELECT * FROM th_city";
                    $city_data = $wpdb->get_results($city_sql);
				?>
				<select name="city" id="city" class="woocommerce-Input input-text">
					<option value="0">Select City</option>
					<?php 
					foreach ($city_data as $data) {?>
					   <option value="<?=$data->city_id;?>" <?php if($city_id==$data->city_id){ echo 'selected';}?>><?=$data->city_name;?></option>
				   <?php }?>
				</select>
			</div>
			<div class="col-sm-4 col-md-4 box">
				<label for="store_add"><?php esc_html_e( 'State', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
				<input type="text" class="woocommerce-Input input-text" name="state" id="state"  value="<?php echo $state; ?>" />
			</div>
			<div class="col-sm-4 col-md-4 box">
				<label for="store_add"><?php esc_html_e( 'Pincode', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
				<input type="text" class="woocommerce-Input input-text" name="pincode" id="pincode"  value="<?php echo $pincode; ?>" />
			</div>
		</div>
	<div class="suppliers-details change-pass">
		<input type="text" name="supplier_id" value="<?php echo $supplier_id; ?>" hidden>
		<legend><?php esc_html_e( 'Change Password', 'woocommerce' ); ?></legend>
		
		<div class="col-sm-4 col-md-4 box">
			<label for="password_current"><?php esc_html_e( 'Current password (leave blank to leave unchanged)', 'woocommerce' ); ?></label>
			<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_current" id="password_current" autocomplete="off" />
		</div>
		<div class="col-sm-4 col-md-4 box">
			<label for="password_1"><?php esc_html_e( 'New password (leave blank to leave unchanged)', 'woocommerce' ); ?></label>
			<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_1" id="password_1" autocomplete="off" />
		</div>
		<div class="col-sm-4 col-md-4 box">
			<label for="password_2"><?php esc_html_e( 'Confirm new password', 'woocommerce' ); ?></label>
			<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_2" id="password_2" autocomplete="off" />
		</div>
	</div>
	<div class="suppliers-button-part">
		<?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
		<button type="submit" class="woocommerce-Button button" name="save_account_details" value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>"><?php esc_html_e( 'Save changes', 'woocommerce' ); ?></button>
		<input type="hidden" name="action" value="save_account_details" />
		<a class="back-btn" href="<?=esc_url( wc_get_page_permalink( 'myaccount' ));?>">Back</a>
	</div>
<?php }?>
</form>

<?php do_action( 'woocommerce_after_edit_account_form' ); ?>
