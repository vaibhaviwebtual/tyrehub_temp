<?php
function tim_add_new(){
?>
<div class="wrap">
 	<h1 class="wp-heading-inline">Add New Installer</h1>
    <span hidden="" class="admin-url"><?php echo admin_url('admin-ajax.php'); ?></span>
	<form action="" method="post">
 	<table class="form-table" >
 		<tbody>
            <tr>
                <th>Enable</th>
                <td> <input type="checkbox" class="" name="visibility" /></td>
            </tr>
 			<tr>
 				<th>Company / Store name</th>
 				<td><input type="text" name="business_name"></td>
 			</tr>
            <tr>
                <th>Address</th>
                <td><input type="text" name="address"></td>
            </tr>
            <tr>
                <th>State</th>
                <td><input type="text" name="state"></td>
            </tr>
            <tr>
                <th>City</th>
                <td>
                    <?php
                        global $wpdb, $woocommerce;
                        $city_sql = "SELECT * FROM th_city";
                        $city_data = $wpdb->get_results($city_sql);

                    ?>
                    <select name="city">
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
                <th>Pincode</th>
                <td><input type="text" name="pincode"></td>
            </tr>
            <tr>
                <th>GST No.</th>
                <td><input type="text" name="gstno"></td>
            </tr>
            <tr>
                <th>Store Phone</th>
                <td><input type="text" name="store_phone"></td>
            </tr>
            <tr>
                <th>Available time</th>
                <td><input type="text" name="av_time">
                    <p class="description">Ex: 10 am to 9 pm</p></td>

            </tr>
            <tr>
                <th>Available days</th>
                <td><input type="text" name="av_day"><p class="description">Ex: Closed on sunday</p></td>
            </tr>
            <tr>
                <th>Image</th>
                <td>
                    <input type="text" name="installer_img" readonly="" class="installer_img">
                    <input type="button" id="upload_img" name="" value="Upload Image">
                            
                </td>
            </tr>
 			
 			<!-- <tr>
                <th>Company Name</th>
                <td><input type="text" name="cmp_name"></td>
            </tr>
            <tr>
                <th>Company Address</th>
                <td><input type="text" name="cmp_add"></td>
            </tr> -->
 			
 			 
            <tr>
                <th>Facilities</th>
                <td>
                    <div class="fc-list">
                        
                        <?php
                        global $wpdb;
                        $fc_sql = "SELECT * from th_installer_facilities where type = 'f'";
                        $fc_data = $wpdb->get_results($fc_sql);
                        foreach ($fc_data as $key => $fc_row)
                        {
                            $name = $fc_row->name;
                            $f_id = $fc_row->f_id;
                            ?>
                            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                <input type="checkbox" class="" name=fc-check[]" id="<?php echo $f_id; ?>" value="<?php echo $f_id; ?>" />&nbsp;<?php esc_html_e( $name , 'woocommerce' ); ?>      
                            </p>
                            <?php
                        }
                        ?>
                    </div>
                    
                </td>

            </tr>
            <tr>
                <td></td>
                <td><button class="button open-fc-modal">Add New</button>
                    <div class="custom-modal admin" id="add_new_facility" style="display: none;">
                        <div class="inner">
                            <div class="body">
                                <h3>Add New Facility</h3>
                                <input type="text" name="fc_name" class="fc-name">
                                <button class="fc-save">Save</button>
                            </div>
                            <div class="footer">
                            	<button class="close">Cancle</button>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <th>Additional Services</th>
                <td>
                    <div class="as-list">
                        
                        <?php
                        global $wpdb;
                        $fc_sql = "SELECT * from th_installer_facilities where type = 'as'";
                        $fc_data = $wpdb->get_results($fc_sql);
                        foreach ($fc_data as $key => $fc_row)
                        {
                            $name = $fc_row->name;
                            $f_id = $fc_row->f_id;
                            ?>
                            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                <input type="checkbox" class="" name=as-check[]" id="<?php echo $f_id; ?>" value="<?php echo $f_id; ?>" />&nbsp;<?php esc_html_e( $name , 'woocommerce' ); ?>      
                            </p>
                            <?php
                        }
                        ?>
                    </div>
                </td>
            </tr>
           
            <tr>
                <td></td>
                <td><button class="button open-as-modal">Add New</button>
                    <div class="custom-modal admin" id="add_new_as" style="display: none;">
                        <div class="inner">
                            <div class="body">
                                <h3>Add new additional services</h3>
                                <input type="text" name="as_name" class="as-name">
                                <button class="as-save">Save</button>
                                
                            </div>
                            <div class="footer">
                            	<button class="close">Cancle</button>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
 		</tbody>
 		
 	</table>
    <div class="user-details">
        <table class="form-table">
            <tr>
                <th>Contact person name</th>
                <td><input type="text" name="contact-person"></td>
            </tr>
            <tr>
                <th>contact_no</th>
                <td><input type="text" name="contact"></td>
            </tr>
            <tr>
                <th>Password</th>
                <td><input type="text" name="pass"></td>
            </tr>
            <tr>
                <td>
                     
                </td>
            </tr>
        </table>
    </div>
    <button class="button-primary woocommerce-save-button add-installer-btn" type="submit" name="submit">
                    Save Changes</button> 
    <a href="<?=get_admin_url();?>/admin.php?page=installer-manage" class="button-primary woocommerce-save-button add-installer-btn">Back</a> 
 	</form>
 	<?php

 	if(isset($_POST['submit']))
 	{
 		global $woocommerce, $wpdb;
 		
 		$contact = $_POST['contact'];
 		$pass = $_POST['pass'];
 		$name = $_POST['business_name'];
        $add = $_POST['address'];
        $image = $_POST['installer_img'];
        $av_time = $_POST['av_time'];
        $av_day = $_POST['av_day'];
        $contact_person = $_POST['contact-person'];
        $store_phone = $_POST['store_phone'];

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
            $cmp_name = $_POST['cmp_name'];
            $cmp_add = $_POST['cmp_add'];
            
            $visibility = '0';
            

            if($_POST['visibility'])
            {
                 $visibility = '1';
            }
           

            
        if ( !username_exists( $contact ))
        {
            $userdata = array (
                    'user_login' => $contact,
                    'user_pass' => $pass,
                    'role' => 'Installer',
                    'user_nicename' => $name,
                   // 'user_email' => $email,
                    'display_name' => $name,
                    'nickname' => $name,
                   // 'otp' => $otp,
                    'first_name' => $contact_person,
                    'first_name' => ' ',
                );

            $new_user_id = wp_insert_user( $userdata );  

            update_user_meta( $new_user_id, '_active', 1 );

            /*Send Message code*/
            $ch1 = curl_init();
            
            $message = "Welcome to the Tyrehub family! Your username:".$contact." and password:".$pass." Thank You Tyrehub Team";
            $message = str_replace(' ', '%20', $message);

            $url_string="http://nimbusit.co.in/api/swsend.asp?username=t1ankitshah&password=81957032&sender=TYREHB&sendto=91".$contact."&message=".$message;
            curl_setopt($ch1, CURLOPT_URL, $url_string); 
            curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1); 
            curl_setopt($ch1, CURLOPT_CUSTOMREQUEST, "GET");                   
            $result1 = curl_exec($ch1);
            curl_close ($ch1);
             /*Send Message code*/
        
            $insert = $wpdb->insert('th_installer_data', array(
                                    'business_name' => $name,
                                    'user_id' => $new_user_id,
                                    'address' => $add,
                                    'contact_no' => $contact,
                                    'city' => $city_name,
                                    'city_id' => $city,
                                    'state' => $state,
                                    'pincode' => $pincode,
                                    'gst_no' => $gst_no,
                                    'visibility' => $visibility,
                                    'location_lattitude' => $lat,
                                    'location_longitude' => $lng,
                                    'available_time' => $av_time,
                                    'available_days' => $av_day,
                                    'image' => $image,
                                    'contact_person' => $contact_person,
                                    'store_phone' => $store_phone,
                                ));

           echo $last_id = $wpdb->insert_id;

            if(!empty($_POST['fc-check'])) {
                $facility_arr = serialize($_POST['fc-check']);
            }

            if(!empty($_POST['as-check'])) {
                $as_arr = serialize($_POST['as-check']);
            }

            $insert = $wpdb->insert('th_installer_meta', array( 
                        'installer_id' => $last_id,
                        'meta_name' => 'facilities',
                        'meta_value' => $facility_arr
            ));

            $insert = $wpdb->insert('th_installer_meta', array( 
                        'installer_id' => $last_id,
                        'meta_name' => 'additional_services',
                        'meta_value' => $as_arr
            ));

            echo '<div class="notice notice-success is-dismissible">
                 <p>Installer register successfully.</p>
             </div>';
        }
        else{
             echo '<div class="notice notice-warning is-dismissible">
                 <p>Mobile Number already used.</p>
             </div>';
        }
 		           
    	
 	}
 	?>
 </div>
 	<?php
}

add_action('wp_ajax_save_facilities', 'save_facilities');
add_action('wp_ajax_nopriv_save_facilities', 'save_facilities');
function save_facilities(){
    $name = $_POST['name'];
    $installer_id = $_POST['installer_id'];
    global $wpdb;

     $insert = $wpdb->insert('th_installer_facilities', array(
        'name' => $name,
        'type' => 'f'
        ));

    $fc_sql = "SELECT * from th_installer_facilities where type = 'f'";
    $fc_data = $wpdb->get_results($fc_sql);

    $sfc_sql = $wpdb->get_var( $wpdb->prepare("SELECT meta_value from th_installer_meta where installer_id = '$installer_id' and meta_name = 'facilities'"));
                        

    $sfc_sql_arr = unserialize($sfc_sql); 

    if($installer_id != '')
    {
    	foreach ($fc_data as $key => $fc_row)
        {
            $name = $fc_row->name;
            $f_id = $fc_row->f_id;
            ?>
            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <input type="checkbox" class="" name=fc-check[]" id="<?php echo $f_id; ?>" value="<?php echo $f_id; ?>" <?php if(in_array($f_id, $sfc_sql_arr)){ echo 'checked';}  ?>/>&nbsp;<?php esc_html_e( $name , 'woocommerce' ); ?>      
            </p>
            <?php
        }
    }
    else{
    	foreach ($fc_data as $key => $fc_row)
	    {
	        $name = $fc_row->name;
	        $f_id = $fc_row->f_id;
	        ?>
	        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
	            <input type="checkbox" class="" name="fc-check[]" value="<?php $f_id; ?>" />&nbsp;<?php esc_html_e( $name , 'woocommerce' ); ?>      
	        </p>
	        <?php
	    }
    }
    die();
}


add_action('wp_ajax_save_additional_service', 'save_additional_service');
add_action('wp_ajax_nopriv_save_additional_service', 'save_additional_service');
function save_additional_service(){
    $name = $_POST['name'];
    echo $installer_id = $_POST['installer_id'];

    global $wpdb;

    $insert = $wpdb->insert('th_installer_facilities', array(
        'name' => $name,
        'type' => 'as'
        ));

    $fc_sql = "SELECT * from th_installer_facilities where type = 'as'";
    $fc_data = $wpdb->get_results($fc_sql);

    if($installer_id != '')
    {
    	$sas_sql = $wpdb->get_var( $wpdb->prepare("SELECT meta_value from th_installer_meta where installer_id = '$installer_id' and meta_name = 'additional_services'"));
                        

        $sas_sql_arr = unserialize($sas_sql);

        foreach ($fc_data as $key => $fc_row)
	    {
	        $name = $fc_row->name;
	        $f_id = $fc_row->f_id;
	        ?>
	        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
	            <input type="checkbox" class="" name="as-check[]" value="<?php $f_id; ?>" <?php if(in_array($f_id, $sas_sql_arr)){ echo 'checked';}  ?> />&nbsp;<?php esc_html_e( $name , 'woocommerce' ); ?>      
	        </p>
	        <?php
	    }
    }
   	else{
   		foreach ($fc_data as $key => $fc_row)
	    {
	        $name = $fc_row->name;
	        $f_id = $fc_row->f_id;
	        ?>
	        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
	            <input type="checkbox" class="" name="as-check[]" value="<?php $f_id; ?>" />&nbsp;<?php esc_html_e( $name , 'woocommerce' ); ?>      
	        </p>
	        <?php
	    }
   	}
    
    die();
}