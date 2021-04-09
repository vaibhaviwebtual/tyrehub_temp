<?php
/**
 * My Account page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
wc_print_notices();
/**
 * My Account navigation.
 * @since 2.6.0
 */
?>
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

	if(current_user_can('Installer')){
		$installer = "SELECT * FROM th_installer_data WHERE user_id = '$user_id'";
		$user_id = get_current_user_id();
	    $SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
	    $franchise=$wpdb->get_row($SQL);
	    if($franchise){
	    	if(!isset($_SESSION['admin_access'])) {
				wp_redirect(site_url('/my-account/franchise-home/'));
			}
	    }
	}

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
	<div><strong>Contact Person Name: </strong><?php echo $name; ?></div>		
	<div><strong>Shop Name: </strong><?php echo $store; ?></div>
	<div><strong>Contact Person No: </strong><?php echo $number; ?></div>
	<div><strong>Shop Address: </strong><?php echo $add; ?></div>
	<div><strong>GSTIN: </strong><?php if($gstin != "") { echo $gstin; } else { echo "Not Available"; } ?></div>
	<?php if($flag == "false"){ ?>
		<div><strong>Email ID: </strong><?php echo $email_id; ?></div>
	<?php } ?>
</div>
<?php } ?>

<div class="my-account-sec">
	<?php
		$user = wp_get_current_user();
		$role = ( array ) $user->roles;
		$current_user_role = $role[0];
		if($current_user_role != 'Installer' && $current_user_role!= 'supplier' && $current_user_role!= 'btobpartner') {
			do_action( 'woocommerce_account_navigation' ); 
		}
	?>	
	<div class="woocommerce-MyAccount-content <?php if($current_user_role == 'Installer' || $current_user_role == 'supplier' || $current_user_role == 'btobpartner') { echo 'installer-account'; } ?>">
		<?php 
		do_action( 'woocommerce_account_content');
		?>
		<?php
			/**
			 * My Account content.
			 * @since 2.6.0
			 */
			do_action( 'woocommerce_account_dashboard' );
		?>
	</div>
</div>