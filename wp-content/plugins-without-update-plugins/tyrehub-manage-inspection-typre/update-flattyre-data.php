<?php
function flattyre_update(){

 	global $wpdb;
    $currency = get_woocommerce_currency_symbol();
    $flat_id = $_GET['flat_id'];
    $sql = "SELECT * FROM th_flat_tyre_inquiry where id = '$flat_id'";
    $flatyre_data = $wpdb->get_row($sql);
    
		$id = $flatyre_data->bpartner_data_id;
		$user_id = $flatyre_data->user_id;
		$name = $flatyre_data->business_name;
		$mobile_no = $flatyre_data->contact_no;
		$add = $flatyre_data->address;
		$visibility = $flatyre_data->visibility;
		$image = $flatyre_data->image;
		$state = $flatyre_data->state;
		$city_name = $flatyre_data->city;
		$city_id = $flatyre_data->city_id;
		$pincode = $flatyre_data->pincode;
		$gst_no = $flatyre_data->gst_no;
		$company_name = $flatyre_data->company_name;
		$company_add = $flatyre_data->company_name;
		$contact_person = $flatyre_data->contact_person;
		$av_time = $flatyre_data->available_time;
		$av_day = $flatyre_data->available_days;
		$store_phone = $flatyre_data->store_phone;
		$percentage = $flatyre_data->percentage;
		$commission = $flatyre_data->commission_percentage;


	$user_info = get_userdata($user_id);
	$email = $user_info->user_email;
    
?>
	<div class="wrap woocommerce">
 		<h1 class="wp-heading-inline">Update Flat Tyre / Jump Start <?php echo $name; ?></h1>
 		<span hidden="" class="admin-url"><?php echo admin_url('admin-ajax.php'); ?></span>
 		<form action="" method="post">
		 	<table class="form-table" >
		 		<tbody>
		 			
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
		 			
		 			
		 		</tbody>
		 		
		 	</table>
		 
 		</form>

 		<?php
 		if(isset($_POST['update']))
 		{
 			
		}


 		?>
 	</div>
 	<?php
}