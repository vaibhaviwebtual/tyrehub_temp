<h2>Add Installer Services Charges</h2>
<form method="post" action="">
<table class="form-table">
	<tr>
		<td style="width: 20%;">City</td>
		<td>
			<?php
				global $wpdb, $woocommerce;
				$city_sql = "SELECT * FROM th_city";
				$city_data = $wpdb->get_results($city_sql);

			?>
			<select name="select_city">
				<option value="0">Select City</option>
				<?php 
				foreach ($city_data as $data) {
					echo '<option value="'.$data->city_id.'">'.$data->city_name.'</option>';
				}
				?>
			</select>
		</td>
	</tr>
	<tr>
		<td>Serivce Type</td>
		<td>
			<?php
				
				$service_sql = "SELECT * FROM th_service_data";
				$service_data = $wpdb->get_results($service_sql);

			?>
			<select name="select_service" id="select_service">
				<option value="0">Select Service</option>
				<?php 
				foreach ($service_data as $data) {
					echo '<option value="'.$data->service_data_id.'">'.$data->service_name.'</option>';
				}
				?>
			</select>
		</td>
	</tr>
	<tr id="vehicle_type">
		<td>Vehicle</td>
		<td>
			<?php
				
				$vehicle_sql = "SELECT * FROM th_vehicle_type";
				$vehicle_data = $wpdb->get_results($vehicle_sql);

			?>
			<select name="select_vehicle">
				<option value="0">Select Vehicle</option>
				<?php 
				foreach ($vehicle_data as $data) {
					echo '<option value="'.$data->vehicle_id.'">'.$data->vehicle_type.'</option>';
				}
				?>
			</select>
		</td>
	</tr>
	
	<tr>
		<td>Rate</td>
		<td><input type="number" name="rate">&nbsp;&nbsp;Upto KM <input type="number" id="upto_km" name="upto_km"  placeholder="Upto KM" style="display: none;"></td>
	</tr>
	<tr id="km_section" style="display: none;">
		<td>KM</td>
		<td>From Km <input type="number" name="from_km"> to km <input type="number" name="to_km"> Per KM Price <input type="number" name="per_km_price"></td>
	</tr>
	
	<tr>
		<td><input type="submit" class="button-primary" name="save_charges" value="Save"></td>
	</tr>
</table>
</form>

<?php 

if(isset($_POST['save_charges'])){

	$city = $_POST['select_city'];
	$vehicle = $_POST['select_vehicle'];
	$service = $_POST['select_service'];
	$rate = $_POST['rate'];
	$upto_km = $_POST['upto_km'];
	$from_km = $_POST['from_km'];
	$to_km = $_POST['to_km'];
	$per_km_price = $_POST['per_km_price'];

	$select_record = "SELECT COUNT(*) FROM th_installer_service_price where service_data_id = '$service' and vehicle_id = '$vehicle' and city_id = '$city'";
	$res_count = $wpdb->get_var($select_record);

	if($res_count == 0){
		if($city == 0 || $service == 0 || $rate == '')
		{
			echo '<div class="notice notice-warning is-dismissible">
		             <p>Please select all options.</p>
		         </div>';
		}else
		{	
			$insert = $wpdb->insert('th_installer_service_price', array(
                            'service_data_id' => $service,
                            'vehicle_id' => $vehicle,
                            'city_id' => $city,
                            'rate' => $rate,
                             'upto_km' => $upto_km,
                            'from_km' => $from_km,
                            'to_km' => $to_km,
                            'per_km_price' => $per_km_price,
                        ));

			wp_redirect('?page=installer-fitment-charges');
		}
		
	}
	else{
		 echo '<div class="notice notice-warning is-dismissible">
             <p>You have already add charges for selected value.</p>
         </div>';
	}
	
}