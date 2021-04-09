<?php
function bpartner_update(){

 	global $wpdb;
    $currency = get_woocommerce_currency_symbol();
    $bpartner_id = $_GET['bpartner_id'];
    $sql = "SELECT * FROM th_business_partner_data where bpartner_data_id = '$bpartner_id'";
    $partner_data = $wpdb->get_results($sql);
    foreach ($partner_data as $key => $value)
	{
		$id = $value->bpartner_data_id;
		$user_id = $value->user_id;
		$name = $value->business_name;
		$mobile_no = $value->contact_no;
		$add = $value->address;
		$visibility = $value->visibility;
		$image = $value->image;
		$state = $value->state;
		$city_name = $value->city;
		$city_id = $value->city_id;
		$pincode = $value->pincode;
		$gst_no = $value->gst_no;
		$company_name = $value->company_name;
		$company_add = $value->company_name;
		$contact_person = $value->contact_person;
    	$av_time = $value->available_time;
    	$av_day = $value->available_days;
    	$store_phone = $value->store_phone;
    	$percentage = $value->percentage;
    	$commission = $value->commission_percentage;
	}

	$user_info = get_userdata($user_id);
	$email = $user_info->user_email;
    
?>
	<div class="wrap woocommerce">
 		<h1 class="wp-heading-inline">Update Business Partner <?php echo $name; ?></h1>
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
		 				<th>Percentage</th>
		 				<td><input type="text" name="percentage" value="<?php echo $percentage; ?>">
		 					
		 				</td>
		 			</tr>
		 			<tr>
		 				<th>Commission Percentage</th>
		 				<td><input type="text" name="commission" value="<?php echo $commission; ?>">
		 					
		 				</td>
		 			</tr>
		 			<tr>
		 				<th>Company / Store name</th>
		 				<td><input type="text" name="business_name" value="<?php echo $name; ?>">
		 					<input type="hidden" class="bpartner_id" name="bpartner_id" value="<?php echo $id; ?>">
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
		 				<td><input type="text" name="gstno" value="<?php echo $gst_no; ?>" maxlength="15"></td>
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
		 				<th>Email</th>
		 				<td><input type="text" name="email" value="<?php echo $email; ?>">
		 					<input type="hidden" name="final_email" value="<?php echo $email; ?>" readonly>
		 					
		 				</td>
		 			</tr>
        			<tr>
		 				<th>Username (Mobile)</th>
		 				<td><input type="text" name="contact" value="<?php echo $mobile_no; ?>">
		 					<input type="hidden" name="final_contact" value="<?php echo $mobile_no; ?>" readonly>
		 				</td>
		 			</tr>
		 			<tr>
		 				<th>Password</th>
		                <td><input type="text" name="pass"><p class="description">Leave blank if you dont change</p></td>
		 				
		 			</tr>
		 			<tr>
		               	<td>
		 					<button class="button-primary woocommerce-save-button" type="submit" name="update" value="update">
		 					Save Changes</button>
		 					<a href="<?=get_admin_url();?>/admin.php?page=bpartner-manage" class="button-primary woocommerce-save-button add-installer-btn">Back</a> 
		 				</td>
		            </tr>
        		</table>
        	</div>
 		</form>

 		<?php
 		if(isset($_POST['update']))
 		{
 			global $wpdb, $woocommerce;
 			$bpartner_id = $_POST['bpartner_id'];
			$name = $_POST['business_name'];
			$mobile_no = $_POST['contact'];
			$email = $_POST['email'];
			$final_email = $_POST['final_email'];
			
			$final_contact = $_POST['final_contact'];
			$add = $_POST['address'];
			$bpartner_img = $_POST['bpartner_img'];
		

			$url = "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($add)."&sensor=false&key=AIzaSyCmMCGIlJKPcPn_hjlgo6j-RMfN4ZtOH70";
            $result_string = file_get_contents($url);
        
            $result = json_decode($result_string, true);
            $val = $result['results'][0]['geometry']['location'];

            $lat = $val['lat'];
            $lng = $val['lng'];

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
			
			$percentage=$_POST['percentage'];
			$commission=$_POST['commission'];
			
			$msg='';
			if($final_contact != $mobile_no){
				if (!username_exists($mobile_no))
        		{
        			$error='';

        		}else{
        			$error=1;
        			$msg.='Mobile Number already used. <br>';
        		}
			}else{
				if($final_email!=$email){
					if (!email_exists($email )) {
						$error='';
					}else{
						$error=1;
						$msg.='Email address already used.';
					}
				}
			
			}

		if($error){
			echo '<div class="notice notice-warning is-dismissible">
		                 <p>'.$msg.'</p>
		             </div>';
		}else{
			
					

	            	if($pass != '' && $email!=''){
	            		
					    $message = "Dear, ".$contact_person." your username and password changed for your store ".$name.". New username : ".$mobile_no." and paswword: ".$pass." Thank You Tyrehub Team";
					    $message = str_replace(' ', '%20', $message);

					    sms_send_to_customer($message,$mobile_no,$templateID=1);

	            	}
	            	
	            	if($pass){
	            	 	wp_set_password($pass, $user_id);
	            	}
	            	if($email){
	            		$user_data = wp_update_user(array('ID'=>$user_id,'user_email' =>$email));
	            	}

	            	if($mobile_no){
	            		$wpdb->update($wpdb->users, array('user_login' => $mobile_no), array('ID' => $user_id));
	            	}

	            	
	            	update_user_meta( $user_id, 'first_name',$contact_person);
	            	
	            	$SQL="UPDATE th_business_partner_data SET address = '$add' , business_name = '$name' , city = '$city_name', city_id = '$city' , state = '$state' , pincode = '$pincode' , gst_no = '$gst_no' , company_name = '$cmp_name' , company_add = '$cmp_add' , visibility = '$visibility' , location_lattitude = '$lat' , location_longitude = '$lng' , image = '$bpartner_img' , percentage = '$percentage',commission_percentage = '$commission', store_phone = '$store_phone', contact_person = '$contact_person',contact_no = '$mobile_no' WHERE bpartner_data_id = '$bpartner_id'";

		 			$update = $wpdb->query($SQL);

		 			echo '<div class="notice notice-success is-dismissible">
                  <p>Business Partner profile update successfully.</p>
                  </div>';
		}




		    
 			//wp_redirect( '?page=bpartner-add-new&action=edit&bpartner_id='.$bpartner_id );
 		}
 		?>
 	</div>
 	<?php
}