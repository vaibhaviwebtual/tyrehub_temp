<?php
function supplier_update(){

 	global $wpdb;
    $currency = get_woocommerce_currency_symbol();
    $supplier_id = $_GET['supplier_id'];
   $sql = "SELECT * FROM th_supplier_data where supplier_data_id = '$supplier_id'";
    $supplier_data = $wpdb->get_results($sql);
    foreach ($supplier_data as $key => $value)
	{
		$id = $value->supplier_data_id;
		$user_id = $value->user_id;
		$name = $value->business_name;
		$mobile_no = $value->contact_no;
		$add = $value->address;
		$visibility = $value->visibility;
		$auto_approve = $value->auto_approve;
		$all_product_access = $value->all_product_access;		
		$image = $value->image;
		$state = $value->state;
		$city_name = $value->city;
		$city_id = $value->city_id;
		$pincode = $value->pincode;
		$gst_no = $value->gst_no;
		$company_name = $value->company_name;
		$company_add = $value->company_name;
		$contact_person = $value->contact_person;
    	
    	$store_phone = $value->store_phone;
	}
    
?>
	<div class="wrap woocommerce">
 		<h1 class="wp-heading-inline">Update Supplier <?php echo $name; ?></h1>
 		<span hidden="" class="admin-url"><?php echo admin_url('admin-ajax.php'); ?></span>
 		<form action="" method="post">
		 	<table class="form-table" >
		 		<tbody>
		 			<tr>
		 				<th>Enable</th>
		 				<td>
		 					 <input type="checkbox" class="" name="visibility"  <?php if($visibility == '1'){ echo 'checked';} ?> />&nbsp;<?php esc_html_e( 'Visibility', 'woocommerce' ); ?>
		 				</td>
		 			</tr>
		 			<tr>
		 				<th>Price Auto Approve</th>
		 				<td>
		 					 <input type="checkbox" class="" name="auto_approve"  <?php if($auto_approve == '1'){ echo 'checked';} ?> />&nbsp;<?php esc_html_e( 'Auto Approve', 'woocommerce' ); ?>
		 				</td>
		 			</tr>
		 			<tr>
		 				<th>All Products Access</th>
		 				<td>
		 					 <input type="checkbox" class="" name="all_product_access"  <?php if($all_product_access == '1'){ echo 'checked';} ?> />&nbsp;<?php esc_html_e( 'All Products Access', 'woocommerce' ); ?>
		 				</td>
		 			</tr>
		 			
		 			<tr>
		 				<th>Company / Store Name (Supplier Name)</th>
		 				<td><input type="text" name="business_name" value="<?php echo $name; ?>">
		 					<input type="hidden" class="supplier_id" name="supplier_id" value="<?php echo $id; ?>">
		 					<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
		 				</td>
		 			</tr>
		 			<tr>
		 				<th>Address</th>
		 				<td><input type="text" name="address" value="<?php echo $add; ?>"></td>
		 			</tr>
		 			<tr>
		 				<th>State</th>
		 				<td><input type="text" name="state" value="<?php echo $state; ?>"></td>
		 			</tr>
		 			<tr>
		 				<th>City</th>
		 				<td>
		 					<?php				
								$city_sql = "SELECT * FROM th_city";
								$city_data = $wpdb->get_results($city_sql);
							?>
		 					<select name="city">
								<option value="0">Select City</option>
								<?php 
								foreach ($city_data as $data) {
									if($city_id == $data->city_id)
									{ 
										echo '<option value="'.$data->city_id.'" selected>'.$data->city_name.'</option>';
									}
									else{
										echo '<option value="'.$data->city_id.'">'.$data->city_name.'</option>';
									}
									
								}
								?>
							</select>
		 				</td>
		 			</tr>
		 			<tr>
		 				<th>Pincode</th>
		 				<td><input type="text" name="pincode" value="<?php echo $pincode; ?>"></td>
		 			</tr>
		 			<tr>
		 				<th>GST No.</th>
		 				<td><input type="text" name="gstno" value="<?php echo $gst_no; ?>" maxlength="15"> </td>
		 			</tr>
		 			<tr>
		                <th>Store Phone</th>
		                <td><input type="text" name="store_phone" value="<?php echo $store_phone; ?>" maxlength="10"></td>
		            </tr>
		 			
		 			<tr>
		 				<th>Image</th>
		 				<td>
		 					<input type="text" name="installer_img" readonly="" class="installer_img" value="<?php echo $image; ?>">
		 					<input type="button" id="upload_img" name="" value="Upload Image">
		 					
		 				</td>
		 			</tr>
		 			
		 		</tbody>
		 		
		 	</table>
		 	<div class="user-details">
        		<table class="form-table">
        			<tr>
		                <th>Contact Person Name</th>
		                <td><input type="text" name="contact-person" value="<?php echo $contact_person; ?>"></td>
		            </tr>
        			<tr>
		 				<th>Mobile(Username)</th>
		 				<td><input type="text" name="contact" autocomplete="false" value="<?php echo $mobile_no; ?>">
		 					<input type="hidden" name="final_contact" value="<?php echo $mobile_no; ?>" readonly>
		 				</td>
		 			</tr>
		 			<tr>
		 				<th>Password</th>
		                <td><input type="password" name="pass" id="password" style="width: 400px;"><p class="description">Leave blank if you dont change</p><input type="checkbox" onclick="myFunction()">Show Password</td>
		 				
		 			</tr>
		 			<tr>
		               	<td>
		 					<button class="button-primary woocommerce-save-button" type="submit" name="update">
		 					Save Changes</button>
		 					<a href="<?=get_admin_url();?>/admin.php?page=supplier-manage" class="button-primary woocommerce-save-button add-installer-btn">Back</a> 
		 				</td>
		            </tr>
        		</table>
        	</div>
 		</form>
<script type="text/javascript">
	function myFunction() {
  var x = document.getElementById("password");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
} 
</script>
 		<?php
 		if(isset($_POST['update']))
 		{
 			global $wpdb, $woocommerce;
 			$supplier_id = $_POST['supplier_id'];
			$name = $_POST['business_name'];
			$mobile_no = $_POST['contact'];
			$final_contact = $_POST['final_contact'];
			$add = $_POST['address'];
			$installer_img = $_POST['supplier_img'];
			
			/*$url = "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($add)."&sensor=false&key=AIzaSyCmMCGIlJKPcPn_hjlgo6j-RMfN4ZtOH70";
            $result_string = file_get_contents($url);
        
            $result = json_decode($result_string, true);
            $val = $result['results'][0]['geometry']['location'];

            $lat = $val['lat'];
            $lng = $val['lng'];*/

			$state = $_POST['state'];
			$city = $_POST['city'];

			$city_name = $wpdb->get_var( $wpdb->prepare( "SELECT city_name FROM th_city WHERE city_id ='%s' LIMIT 1", $city ) );

			$pincode = $_POST['pincode'];
			$gst_no = $_POST['gstno'];
			$store_phone = $_POST['store_phone'];
			$cmp_name = $_POST['cmp_name'];
			$cmp_add = $_POST['cmp_add'];
		    $visibility = '0';
		    $pass = $_POST['pass'];
		    $user_id = $_POST['user_id'];
		    $contact_person = $_POST['contact-person'];

		    if($_POST['visibility'])
		    {
		         $visibility = '1';
		    }
		    if($_POST['auto_approve'])
		    {
		         $auto_approve = 1;
		    }else{
		    	$auto_approve = 0;
		    }

		    if($_POST['all_product_access'])
		    {
		         $all_product_access = 1;
		    }else{
		    	$all_product_access = 0;
		    }
			
			

            if($final_contact != $mobile_no){
            	

            	if ( !username_exists( $mobile_no ))
        		{
        			if($pass == ''){
        				echo '<div class="notice notice-warning is-dismissible">
			                 <p>If you change username you need to enter password.</p>
			             </div>';
        			}
        			else
        			{
	            		wp_delete_user($user_id);

	            		$userdata = array (
	                    'user_login' => $mobile_no,
	                    'user_pass' => $pass,
	                    'role' => 'Installer',
	                    'user_nicename' => $name,
	                    'display_name' => $name,
	                    'nickname' => $name,
	                	);

			           	$new_user_id = wp_insert_user( $userdata );  

			            update_user_meta( $new_user_id, '_active', 1 );

					    $message = "Dear, ".$contact_person." your username and password changed for your store ".$name.". New username : ".$mobile_no." and paswword: ".$pass." Thank You Tyrehub Team";
					    $message = str_replace(' ', '%20', $message);
					    sms_send_to_customer($message,$mobile_no,$templateID=1);

					    
			            $update = $wpdb->get_results("UPDATE th_supplier_data SET address = '$add' , business_name = '$name' , city = '$city_name', city_id = '$city' , state = '$state' , pincode = '$pincode' , gst_no = '$gst_no' , company_name = '$cmp_name' , company_add = '$cmp_add' , wifi_service = '$wifi_service' , water_service = '$water' , tea_service = '$tea' , car_pickup_service = '$pickup', visibility = '$visibility', auto_approve = '$auto_approve', all_product_access = '$all_product_access' , car_wash = '$carwash' , puncture = '$puncture' , image = '$supplier_img', store_phone = '$store_phone' , user_id = '$new_user_id',contact_no = '$mobile_no' WHERE supplier_data_id = '$supplier_id'");	           
	 				
	 				}

            	}
            }
            else{

            	if($pass != ''){
            		
            	 	wp_set_password($pass, $user_id);
            	}
            	
            	update_user_meta( $user_id, 'first_name', $contact_person );
            	
            	

	 			$update = $wpdb->get_results("UPDATE th_supplier_data SET address = '$add' , business_name = '$name' , city = '$city_name', city_id = '$city' , state = '$state' , pincode = '$pincode' , gst_no = '$gst_no' , company_name = '$cmp_name' , company_add = '$cmp_add' , visibility = '$visibility', auto_approve = '$auto_approve', all_product_access = '$all_product_access', image = '$supplier_img' , store_phone = '$store_phone', contact_person = '$contact_person' WHERE supplier_data_id = '$supplier_id'");
	 			
 			}
 			wp_redirect( '?page=supplier-add-new&action=edit&supplier_id='.$supplier_id );
 		}
 		?>
 	</div>
 	<?php
}