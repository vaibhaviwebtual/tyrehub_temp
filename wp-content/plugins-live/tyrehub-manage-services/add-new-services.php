<?php
function tim_add_new_services(){
?>
<div class="wrap">
 	<h1 class="wp-heading-inline">Add New Services</h1>
    <span hidden="" class="admin-url"><?php echo admin_url('admin-ajax.php'); ?></span>
	<form action="" method="post">
 	<table class="form-table" >
 		<tbody>
           
 			<tr>
 				<th>Services Name</th>
 				<td><input type="text" name="services_name" value="<?=$_POST['services_name'];?>"></td>
 			</tr>
            <tr>
                <th>Icon</th>
                <td><input type="text" name="icon" value="<?=$_POST['icon'];?>"></td>
            </tr>
             <tr>
                <th>AS Flag</th>
                <td><input type="checkbox" class="" name="as_flag" <?php if($_POST['as_flag']) { echo 'checked';}?> /></td>
            </tr>
            <tr>
                <th>Installer Listing Display</th>
                <td><input type="checkbox" class="" name="service_onoff_on_listing" <?php if($_POST['service_onoff_on_listing']) { echo 'checked';}?> /></td>
            </tr>
            <tr>
                <th>Status</th>
                <td><input type="checkbox" class="" name="status" <?php if($_POST['status']) { echo 'checked';}?> /></td>
            </tr>
            
 			
 		</tbody>
 		
 	</table>
   
    <button class="button-primary woocommerce-save-button add-installer-btn" type="submit" name="submit">
                    Save Changes</button> 
    <a href="<?=get_admin_url();?>/admin.php?page=services-manage" class="button-primary woocommerce-save-button add-installer-btn">Back</a> 
 	</form>
 	<?php

 	if(isset($_POST['submit']))
 	{
 		global $woocommerce, $wpdb;
 		
 		$service_name = $_POST['services_name'];
 		$icon = $_POST['icon'];
              
        $as_flag =0;
        if($_POST['as_flag'])
        {
         $as_flag =1;
        }

        $status = 0;
        if($_POST['status'])
        {
         $status =1;
        }

        $onoff = 0;
        if($_POST['service_onoff_on_listing'])
        {
         $onoff = 1;
        }
           
        
            
        $insert = $wpdb->insert('th_service_data', array(
                            'service_name' => $service_name,
                            'as_flag' => $as_flag,
                            'icon' => $icon,
                            'status' => $status,
                            'service_onoff_on_listing' => $onoff
                            
                        ));

            $last_id = $wpdb->insert_id;
            //echo $wpdb->last_query;
            //echo $wpdb->last_query;

            echo '<div class="notice notice-success is-dismissible">
                  <p>Services add successfully.</p>
                  </div>';
 		           
    	wp_redirect(site_url('/wp-admin/admin.php?page=services-manage'));
 	}
 	?>
 </div>
 	<?php
}
