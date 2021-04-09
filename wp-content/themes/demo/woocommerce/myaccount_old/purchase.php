<?php

if ( !is_user_logged_in() )
 	{
      wp_redirect(get_permalink( get_option('woocommerce_myaccount_page_id') ).'/installer-login' );    
  	}
get_header();
?>
<div id="pageContent">
	<div class="container installer-home">
		
		<div class="woocommerce">
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
			<div style="text-align: left;" class="search-bar">
				<div class="left">
				</div>
				<div class="right">
					<div class="column">
						<span style="text-align: left;">Width</span>
						<div><input type="text" name="" class="width"></div>
					</div>
					<div class="column">
						<span style="text-align: left;">Ratio</span>
						<div><input type="text" name="" class="ratio"></div>
					</div>
					<div class="column">
						<span style="text-align: left;">Diameter</span>
						<div><input type="text" name="" class="diameter"></div>
					</div>
					<div class="column" style="width: 20%;">
						<span style="text-align: left;">Search</span>
						<div><select class="searchbyname">
								<option selected disabled="" >Select Category</option>
								<option>Apollo</option>
								<option>Bridgestone</option>
								<option>Cavendish</option>
								<option>Ceat</option>
								<option>Falken</option>
								<option>GoodYear</option>
								<option>MRF</option>
								<option>JK</option>
							</select></div>
					</div>
					<div class="column" style="width: 20%;">
						<button class="searchbywidth btn btn-invert"><span>Search</span></button>
					</div>
				</div>
			</div>
			
			<?php
			
			if ( ! defined( 'ABSPATH' ) ) {
				exit;
			}

			wc_print_notices();
		
			$user = wp_get_current_user();
		   	$role = ( array ) $user->roles;
		   	$current_user_role = $role[0];
		   	if($current_user_role != 'Installer')
		   	{
				do_action( 'woocommerce_account_navigation' ); 
			} 
		//	do_action( 'woocommerce_account_content' ); ?>
			<div class="woocommerce-MyAccount-content <?php if($current_user_role == 'Installer'){echo 'installer-account';} ?>">
				 <div class="product-container"> 
				<!-- <div class="product-container"> -->
					Please search for purchase
				</div>
					
			
			</div>
		</div>
	</div>
</div>

<?php
get_footer();
?>
