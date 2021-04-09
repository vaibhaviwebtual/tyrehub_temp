<?php
function flattyre_update(){

 	global $wpdb;
    $currency = get_woocommerce_currency_symbol();
    $flat_id = $_GET['flat_id'];
    $sql = "SELECT * FROM th_flat_tyre_inquiry where id = '$flat_id'";
    $flatyre_data = $wpdb->get_row($sql);

		$id = $flatyre_data->id;
		$name =  $flatyre_data->name;
		$mobile_number =  $flatyre_data->mobile_number;
		$vehicle_location =  $flatyre_data->vehicle_location;
		$type =  $flatyre_data->type;
		$status =  $flatyre_data->status;





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
		 				<th>Name</th>
		 				<td><input type="text" name="name" value="<?php echo $name; ?>">

		 				</td>
		 			</tr>
		 			<tr>
		 				<th>Mobile Number</th>
		 				<td><input type="text" name="mobile_number" value="<?php echo $mobile_number; ?>">

		 				</td>
		 			</tr>
		 			<tr>
		 				<th>location</th>
		 				<td>
		 					<input type="text" name="vehicle_location" value="<?php echo $vehicle_location; ?>">
		 				</td>
		 			</tr>
		 			<tr>
		 				<th>Type</th>
		 				<td><input type="text" name="type" value="<?php echo $type; ?>"></td>
		 			</tr>
		 			<!-- <tr>
		 				<th>Status</th>
		 				<td><input type="text" name="status" value="<?php echo $status; ?>"></td>
		 			</tr> -->

		 			<tr>
						<td><input type="submit" name="update" id="update" value="Update"></td>
					</tr>

		 		</tbody>

		 	</table>

 		</form>

 		<?php
 		if(isset($_POST['update']))
 		{

 				if(isset($_POST['save_city'])){
					$city_name = $_POST['city_name'];
					$std_code = $_POST['std_code'];
					if($_GET['action'] == 'add'){

						$insert = $wpdb->insert('th_city', array(
		                                        'city_name' => $city_name,
		                                        'std_code' => $std_code,
		                                        ));
					}
					elseif ($_GET['action'] == 'edit') {
						$city_id = $_GET['city_id'];


						$update_service = $wpdb->get_results("UPDATE th_city set city_name = '$city_name', std_code = '$std_code' WHERE city_id = '$city_id' ");
					}

					wp_redirect('?page=flattyre-add-new');
				}

		}



 		?>
 	</div>
 	<?php
}