<?php
function tim_add_new_bpartner(){
?>
<div class="wrap">
 	<h1 class="wp-heading-inline">Add New Business Partner</h1>
    <span hidden="" class="admin-url"><?php echo admin_url('admin-ajax.php'); ?></span>
	<form action="" method="post">
 	<table class="form-table" >
 		<tbody>
            <tr>
                <th>Enable</th>
                <td> <input type="checkbox" class="" name="visibility" <?php if($_POST['visibility']) { echo 'checked';}?> /></td>
            </tr>
 			<tr>
 				<th>Company / Store name</th>
 				<td><input type="text" name="business_name" value="<?=$_POST['business_name'];?>"></td>
 			</tr>
            <tr>
                <th>Percentage</th>
                <td><input type="text" name="percentage" value="<?=$_POST['percentage'];?>"></td>
            </tr>
             <tr>
                <th>Commission Percentage</th>
                <td><input type="text" name="commission" value="<?=$_POST['commission'];?>"></td>
            </tr>
            <tr>
                <th>Address</th>
                <td><input type="text" name="address" value="<?=$_POST['address'];?>"></td>
            </tr>
            <tr>
                <th>State</th>
                <td><input type="text" name="state" value="<?=$_POST['state'];?>"></td>
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
                        foreach ($city_data as $data) {?>

                            <option value="<?=$data->city_id;?>" <?php if($_POST['city']){ echo 'selected';}?>><?=$data->city_name;?></option>
                        <?php }?>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Pincode</th>
                <td><input type="text" name="pincode" value="<?=$_POST['pincode'];?>"></td>
            </tr>
            <tr>
                <th>GST No.</th>
                <td><input type="text" name="gstno" value="<?=$_POST['gstno'];?>" maxlength="15"></td>
            </tr>
            <tr>
                <th>Store Phone</th>
                <td><input type="text" name="store_phone" value="<?=$_POST['store_phone'];?>" maxlength="10"></td>
            </tr>
            
            <tr>
                <th>Image</th>
                <td>
                    <input type="text" name="bpartner_img" readonly="" class="bpartner_img">
                    <input type="button" id="upload_img" name="" value="Upload Image">
                            
                </td>
            </tr>
 			
 		</tbody>
 		
 	</table>
    <div class="user-details">
        <table class="form-table">
            <tr>
                <th>Contact Person Name</th>
                <td><input type="text" name="contact-person" value="<?=$_POST['contact-person'];?>"></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><input type="text" name="email" value="<?=$_POST['email'];?>"></td>
            </tr>
            <tr>
                <th>Username(Mobile)</th>
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
    <a href="<?=get_admin_url();?>/admin.php?page=bpartner-manage" class="button-primary woocommerce-save-button add-installer-btn">Back</a> 
 	</form>
 	<?php

 	if(isset($_POST['submit']))
 	{
 		global $woocommerce, $wpdb;
 		
 		$contact = $_POST['contact'];
        $email = $_POST['email'];
 		$pass = $_POST['pass'];
 		$name = $_POST['business_name'];
        $add = $_POST['address'];
        $image = $_POST['bpartner_img'];
      
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
            $std_code = $wpdb->get_var( $wpdb->prepare( "SELECT std_code FROM th_city WHERE city_id ='%s' LIMIT 1", $city ) );


            $pincode = $_POST['pincode'];
            $gst_no = $_POST['gstno'];
            $cmp_name = $_POST['cmp_name'];
            $cmp_add = $_POST['cmp_add'];
            $commission = $_POST['commission'];
            $visibility = '0';
            

            if($_POST['visibility'])
            {
                 $visibility = '1';
            }
           
        $percentage = $_POST['percentage'];
            
        if ( !username_exists( $contact ) && !email_exists($email ))
        {
            $userdata = array (
                    'user_login' => $contact,
                    'user_pass' => $pass,
                    'role' => 'btobpartner',
                    'user_nicename' => $name,
                     'user_email' => $email,
                    'display_name' => $name,
                    'nickname' => $name,
                   // 'otp' => $otp,
                    'first_name' => $contact_person,
                );

            $new_user_id = wp_insert_user( $userdata );  

            update_user_meta( $new_user_id, '_active', 1 );

            /*Send Message code*/
            $ch1 = curl_init();
            
            $message = "Welcome to the Tyrehub family! Your username:".$contact." and password:".$pass." Thank You Tyrehub Team";
            $message = str_replace(' ', '%20', $message);
            sms_send_to_customer($message,$contact,$templateID=1);

            
             /*Send Message code*/
                if(strlen($std_code)<4 && strlen($new_user_id)<4){

                if(strlen($std_code)==3 && strlen($new_user_id)==3){
                  $zero=0;
                }
                if(strlen($std_code)==2 && strlen($new_user_id)==2){
                  $zero=00;
                }
                if(strlen($std_code)==1 && strlen($new_user_id)==1){
                  $zero=000;                    
                }
                $user_code=$zero.$std_code.$zero.$new_user_id;
               
            }else{
              $user_code=$std_code.$new_user_id;   
            }
           
            $insert = $wpdb->insert('th_business_partner_data', array(
                                    'user_code' => $user_code,
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
                                    'image' => $image,
                                    'contact_person' => $contact_person,
                                    'store_phone' => $store_phone,
                                    'percentage' => $percentage,
                                    'commission_percentage' => $commission,
                                    
                                ));

            $last_id = $wpdb->insert_id;

            //echo $wpdb->last_query;

            echo '<div class="notice notice-success is-dismissible">
                  <p>Business Partner register successfully.</p>
                  </div>';
        }
        else{
             echo '<div class="notice notice-warning is-dismissible">
                 <p>Mobile Number and Email Address already used.</p>
             </div>';
        }
 		           
    	
 	}
 	?>
 </div>
 	<?php
}
