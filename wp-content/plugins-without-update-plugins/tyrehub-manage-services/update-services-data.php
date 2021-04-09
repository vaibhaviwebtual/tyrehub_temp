<?php
function services_update(){

 	global $wpdb;
    $services_id = $_GET['services_id'];
    $sql = "SELECT * FROM th_service_data where service_data_id= '$services_id'";
    $services_data = $wpdb->get_row($sql);
    
		$id = $services_data->bpartner_data_id;
		$services_name = $services_data->service_name;
		$icon = $services_data->icon;
		$as_flag = $services_data->as_flag;
		$onoff = $services_data->service_onoff_on_listing;
		$status = $services_data->status;
		
	

?>
	<div class="wrap woocommerce">
 		<h1 class="wp-heading-inline">Update Services <?php echo $name; ?></h1>
 		<span hidden="" class="admin-url"><?php echo admin_url('admin-ajax.php'); ?></span>
 		<form action="" method="post">
 			<input type="hidden" name="services_id" value="<?=$services_id;?>">
 			<table class="form-table" >
		 		<tbody>
		           
		 			<tr>
		 				<th>Services Name</th>
		 				<td><input type="text" name="services_name" value="<?=$services_name;?>"></td>
		 			</tr>
		            <tr>
		                <th>Icon</th>
		                <td><input type="text" name="icon" value="<?=$icon;?>"></td>
		            </tr>
		             <tr>
		                <th>AS Flag</th>
		                <td><input type="checkbox" class="" name="as_flag" value="1" <?php if($as_flag==1) { echo 'checked';}?> /></td>
		            </tr>
		            <tr>
		                <th>Installer Listing Display</th>
		                <td><input type="checkbox" class="" name="service_onoff_on_listing" value="1" <?php if($onoff==1) { echo 'checked';}?> /></td>
		            </tr>
		            <tr>
		                <th>Status</th>
		                <td><input type="checkbox" class="" name="status" value="1" <?php if($status==1) { echo 'checked';}?> /></td>
		            </tr>
		            
		 			
		 		</tbody>
		 		
		 	</table>
		 	<button class="button-primary woocommerce-save-button add-installer-btn" type="submit" name="update">
                    Save Changes</button> 
    <a href="<?=get_admin_url();?>/admin.php?page=services-manage" class="button-primary woocommerce-save-button add-installer-btn">Back</a>
 		</form>

 		<?php
 		if(isset($_POST['update']))
 		{
 			global $wpdb, $woocommerce;
 			$services_id = $_POST['services_id'];
			$service_name = $_POST['services_name'];
			$as_flag = $_POST['as_flag'];
			$icon = $_POST['icon'];
			$status = $_POST['status'];
			
			$onoff = $_POST['service_onoff_on_listing'];
			
		
			
			$SQL="UPDATE th_service_data SET service_name = '$services_name' , as_flag = '$as_flag' , icon = '$icon', service_onoff_on_listing = '$onoff' , status = '$status' WHERE service_data_id = '$services_id'";

		 			$update = $wpdb->query($SQL);
		    
 			wp_redirect(site_url('/wp-admin/admin.php?page=services-manage'));
 		}
 		?>
 	</div>
 	<?php
}