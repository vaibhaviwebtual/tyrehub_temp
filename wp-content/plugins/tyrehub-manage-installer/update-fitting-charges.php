<h2>Update Installer Services Charges <a href="?page=installer-fitment-charges" class="page-title-action">Back</a></h2>

<?php
global $wpdb, $woocommerce; 
$service_price_id = $_GET['service_price_id'];

$charges_sql = "SELECT * FROM th_installer_service_price where service_price_id = '$service_price_id'";
$charges_data = $wpdb->get_results($charges_sql);
foreach ($charges_data as $key => $value) {
	$this_service_id = $value->service_data_id;
	$this_vehicle_id = $value->vehicle_id; 
	$this_city_id = $value->city_id;
	$rate = $value->rate;
	$upto_km = $value->upto_km;
	$from_km = $value->from_km;
	$to_km = $value->to_km;
	$per_km_price = $value->per_km_price;

}

if($from_km>0 && $per_km_price>0 && $to_km>0){
	$class='';
}else{$class='hideclass';}
?>
<style type="text/css">
	.hideclass{ display: none; }
</style>
<form method="post" action="">
<table class="form-table">
	<tr>
		<td style="width: 20%;">City</td>
		<td>
			<?php
				
				$city_sql = "SELECT * FROM th_city";
				$city_data = $wpdb->get_results($city_sql);

			?>
			<select name="select_city" disabled="">
				<option value="0">Select City</option>
				<?php 
				foreach ($city_data as $data) {
					if($this_city_id == $data->city_id)
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
		<td>Serivce Type</td>
		<td>
			<?php
				
				$service_sql = "SELECT * FROM th_service_data";
				$service_data = $wpdb->get_results($service_sql);

			?>
			<select name="select_service" disabled="" id="select_service">
				<option value="0">Select Service</option>
				<?php 
				foreach ($service_data as $data) {
					if($this_service_id == $data->service_data_id)
					{
						echo '<option value="'.$data->service_data_id.'" selected>'.$data->service_name.'</option>';
					}
					else{
						echo '<option value="'.$data->service_data_id.'">'.$data->service_name.'</option>';
					}
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
			<select name="select_vehicle" disabled="">
				<option value="0">Select Vehicle</option>
				<?php 
				foreach ($vehicle_data as $data) {
					if($this_vehicle_id == $data->vehicle_id)
					{
						echo '<option value="'.$data->vehicle_id.'" selected>'.$data->vehicle_type.'</option>';
					}
					else{
						echo '<option value="'.$data->vehicle_id.'">'.$data->vehicle_type.'</option>';
					}
				}
				?>
			</select>
		</td>
	</tr>
	
	<tr>
		<td>Rate</td>
		<td><input type="number" name="rate" value="<?php echo $rate; ?>"> &nbsp;&nbsp;Upto KM <input type="number" id="upto_km" name="upto_km"  placeholder="Upto KM" class="<?=$display;?>" value="<?=$upto_km;?>"></td>
	</tr>

	<tr id="km_section" class="<?=$display;?>">
		<td>KM</td>
		<td>From Km <input type="number" name="from_km" value="<?=$from_km;?>"> to km <input type="number" name="to_km" value="<?=$to_km;?>"> Per KM Price <input type="number" name="per_km_price" value="<?=$per_km_price;?>"></td>
	</tr>
	<tr>
		<td><input type="submit" name="update_charges" class="button-primary" value="Save"></td>
	</tr>
</table>
</form>

<?php 

if(isset($_POST['update_charges'])){

	$service_price_id = $_GET['service_price_id'];
	$city = $_POST['select_city'];
	$vehicle = $_POST['select_vehicle'];
	$service = $_POST['select_service'];
	$rate = $_POST['rate'];
	$upto_km = $_POST['upto_km'];
	$from_km = $_POST['from_km'];
	$to_km = $_POST['to_km'];
	$per_km_price = $_POST['per_km_price'];
	

	$update_service = $wpdb->get_results("UPDATE th_installer_service_price set rate = '$rate',upto_km = '$upto_km',from_km='$from_km',to_km='$to_km',per_km_price='$per_km_price' WHERE service_price_id = '$service_price_id' ");

	wp_redirect('?page=installer-fitment-charges');
	/*$select_record = "SELECT COUNT(*) FROM th_installer_service_price where service_data_id = '$service' and vehicle_id = '$vehicle' and city_id = '$city'";
	$res_count = $wpdb->get_var($select_record);

	if($res_count == 0){
		if($city == 0 || $vehicle == 0 || $service == 0 || $rate == '')
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
                        ));
		}
		
	}
	else{
		 echo '<div class="notice notice-warning is-dismissible">
             <p>You can not update have already add charges for selected value.</p>
         </div>';
	}*/
	
}