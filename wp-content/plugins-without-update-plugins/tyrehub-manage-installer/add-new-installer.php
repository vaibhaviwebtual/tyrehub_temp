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
                <td> <input type="checkbox" class="" name="visibility" <?php if($_POST['visibility']){ echo 'checked';} ?>/></td>
            </tr>

             <tr>
                <th>Franchise</th>
                <td> <input type="checkbox" class="" name="franchise_status" <?php if($_POST['franchise_status']){ echo 'checked';} ?>/></td>
            </tr>
 			<tr>
 				<th>Company / Store name</th>
 				<td><input type="text" name="business_name" value="<?=$_POST['business_name'];?>"></td>
 			</tr>
            <tr>
                <th>Address</th>
                <td><input type="text" name="address" value="<?=$_POST['address'];?>"></td>
            </tr>
            <tr>
                <th>Google Map</th>
                <td>
                    <input type="hidden" id="lat" name="lat" value="<?=$_POST['lat'];?>" /> 
                    <input type="hidden" id="long" name="long" value="<?=$_POST['long'];?>" /> 
                    <div id="map_canvas" style="width:100%; height: 350px;"></div></td>
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
                            <option value="<?=$data->city_id;?>" <?php if($_POST['long']==$data->city_id){ echo 'selected';} ?>><?=$data->city_name;?></option>
                        <?php }
                        ?>
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
                <th>Available time</th>
                <td><input type="text" name="av_time">
                    <p class="description">Ex: 10 am to 9 pm</p></td>

            </tr>
            <tr>
                <th>Available days</th>
                <td><input type="text" name="av_day" value="<?=$_POST['av_day'];?>"><p class="description">Ex: Closed on sunday</p></td>
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

                            if(!empty($_POST['fc-check'])){
                                $_POST['fc-check']=$_POST['fc-check'];
                            }else{
                                $_POST['fc-check']=array();
                            }


                            ?>
                            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                <input type="checkbox" class="" name="fc-check[]" id="<?php echo $f_id; ?>" value="<?php echo $f_id; ?>" <?php if(in_array($f_id,$_POST['fc-check'])){ echo 'checked';}?> />&nbsp;<?php esc_html_e( $name , 'woocommerce' ); ?>      
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
                        $fc_sql = "SELECT * from th_service_data where as_flag =1 AND status=1";
                        $services_data = $wpdb->get_results($fc_sql);
                        if(!empty($_POST['services'])){
                                $_POST['services']=$_POST['services'];
                            }else{
                                $_POST['services']=array();
                            }
                        foreach ($services_data as $key => $service){?>
                              <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                  <input type="checkbox" class="" name="services[]" value="<?php $service->service_data_id; ?>" <?php if(in_array($service->service_data_id, $_POST['services'])){ echo 'checked';}?>/>&nbsp;<?php esc_html_e($service->service_name,'woocommerce'); ?>      
                              </p>
                              <?php
                          }?>
                    </div>
                </td>
            </tr>
            <tr>
                <th>Choose Payment Option</th>
                <td>
                    <div class="as-list">
                        
                        <?php
                        global $wpdb;
                        $pay_sql = "SELECT * from wp_franchises_payment_method where  status=1";
                        $payment_data = $wpdb->get_results($pay_sql);
                        if(!empty($_POST['pay_method'])){
                                $_POST['pay_method']=$_POST['pay_method'];
                            }else{
                                $_POST['pay_method']=array();
                            }
                        foreach ($payment_data as $key => $pay){?>
                              <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                  <input type="checkbox" class="" name="pay_method[]" value="<?php $pay->id; ?>" <?php if(in_array($pay->id, $_POST['pay_method'])){ echo 'checked';}?>/>&nbsp;<?php esc_html_e($pay->payment_method,'woocommerce'); ?>      
                              </p>
                              <?php
                          }?>
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
                <td><input type="text" name="contact-person" value="<?=$_POST['contact-person']?>"></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><input type="text" name="email" value="<?=$_POST['email'];?>"></td>
            </tr>
            <tr>
                <th>Mobile (Username)</th>
                <td><input type="text" name="contact" ></td>
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
        $email = $_POST['email'];
 		$name = $_POST['business_name'];
        $add = $_POST['address'];
        $image = $_POST['installer_img'];
        $av_time = $_POST['av_time'];
        $av_day = $_POST['av_day'];
        $contact_person = $_POST['contact-person'];
        $store_phone = $_POST['store_phone'];
        $latitude=$_POST['lat'];
        $longitude=$_POST['long'];

            if($latitude!='' && $longitude!=''){
              $lat = $latitude;
              $lng = $longitude;  
            }else{

                $url = "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($add)."&sensor=false&key=AIzaSyCmMCGIlJKPcPn_hjlgo6j-RMfN4ZtOH70";
                $result_string = file_get_contents($url);
                $result = json_decode($result_string, true);
                $val = $result['results'][0]['geometry']['location'];

                $lat = $val['lat'];
                $lng = $val['lng'];  
            }
           

            $state = $_POST['state'];
            $city = $_POST['city'];

            $city_name = $wpdb->get_var( $wpdb->prepare( "SELECT city_name FROM th_city WHERE city_id ='%s' LIMIT 1", $city ) );
            $std_code = $wpdb->get_var( $wpdb->prepare( "SELECT std_code FROM th_city WHERE city_id ='%s' LIMIT 1", $city ) );


            $pincode = $_POST['pincode'];
            $gst_no = $_POST['gstno'];
            $cmp_name = $_POST['cmp_name'];
            $cmp_add = $_POST['cmp_add'];
            
            $visibility = '0';
            $franchise_status = "";
            

            if($_POST['visibility'])
            {
                 $visibility = '1';
            }
            if($_POST['franchise_status'])
            {
                 $franchise_status = 'yes';
            }
           

            
        if ( !username_exists( $contact ) && !email_exists($email ))
        {
            $userdata = array (
                    'user_login' => $contact,
                    'user_pass' => $pass,
                    'role' => 'Installer',
                    'user_nicename' => $name,
                    'user_email' => $email,
                    'display_name' => $name,
                    'nickname' => $name,
                   // 'otp' => $otp,
                    'first_name' => $contact_person,
                );

            $new_user_id = wp_insert_user( $userdata );  

            add_user_meta( $new_user_id, 'cmp_name', $_POST['cmp_name'] );
            add_user_meta( $new_user_id, 'cmp_add', $_POST['cmp_add'] );
            add_user_meta( $new_user_id, 'gst_email', $_POST['gst_email'] );
            update_user_meta( $new_user_id, '_active', 1 );
            /*cmp_name  
cmp_add 
gst_no
gst_email*/ 

            /*Send Message code*/
            
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
           
            $insert = $wpdb->insert('th_installer_data', array(
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
                                    'is_franchise' => $franchise_status,
                                    'location_lattitude' => $lat,
                                    'location_longitude' => $lng,
                                    'available_time' => $av_time,
                                    'available_days' => $av_day,
                                    'image' => $image,
                                    'contact_person' => $contact_person,
                                    'store_phone' => $store_phone,
                                ));

            $last_id = $wpdb->insert_id;
            if(!empty($_POST['fc-check'])) {
                $facility_arr = serialize($_POST['fc-check']);
            }

            if(!empty($_POST['services'])) {
                global $wpdb;
                $wpdb->query('DELETE  FROM th_installer_addi_service WHERE installer_id = "'.$last_id.'"');
                
                $services=$_POST['services'];
                foreach ($services as $key => $value) {
                    # code...
                    $insert = $wpdb->insert('th_installer_addi_service', array( 
                        'installer_id' => $last_id,
                        'service_data_id' =>$value
                    ));
                }
                 
            }

           if(!empty($_POST['pay_method'])) {
                global $wpdb;
                $wpdb->query('DELETE  FROM wp_franchises_choose_pmethod WHERE franchise_id = "'.$last_id.'"');
                
                $pay_method=$_POST['pay_method'];
                foreach ($pay_method as $key => $value) {
                    # code...
                    $insert = $wpdb->insert('wp_franchises_choose_pmethod', array( 
                        'installer_id' => $last_id,
                        'payment_id' =>$value,
                        'status' =>1
                    ));
                }
                 
            }

            $insert = $wpdb->insert('th_installer_meta', array( 
                        'installer_id' => $last_id,
                        'meta_name' => 'facilities',
                        'meta_value' => $facility_arr
            ));

           

            echo '<div class="notice notice-success is-dismissible">
                 <p>Installer register successfully.</p>
             </div>';
        }
        else{
             echo '<div class="notice notice-warning is-dismissible">
                 <p>Mobile Number/Email Address already used.</p>
             </div>';
        }
 		           
    	
 	}
 	?>
 </div>

<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyCmMCGIlJKPcPn_hjlgo6j-RMfN4ZtOH70">
</script>
 <script type="text/javascript"> 
        lat= jQuery('#lat').val();
        long= jQuery('#long').val();

        if(lat=='' && long==''){
            
            lat=23.0952585;
            long=72.5962474;
        }

        var map;
        var marker;
        var myLatlng = new google.maps.LatLng(lat,long);
        var geocoder = new google.maps.Geocoder();
        var infowindow = new google.maps.InfoWindow();
        function initialize(){
        var mapOptions = {
        zoom: 14,
        center: myLatlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);

        marker = new google.maps.Marker({
        map: map,
        position: myLatlng,
        draggable: true 
        }); 

        geocoder.geocode({'latLng': myLatlng }, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
        if (results[0]) {
        //$('#latitude,#longitude').show();
        //$('#address').val(results[0].formatted_address);
        jQuery('#lat').val(marker.getPosition().lat());
        jQuery('#long').val(marker.getPosition().lng());
        infowindow.setContent(results[0].formatted_address);
        infowindow.open(map, marker);
        }
        }
        });

        google.maps.event.addListener(marker, 'dragend', function() {

        geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
        if (results[0]) {
        //$('#address').val(results[0].formatted_address);
        jQuery('#lat').val(marker.getPosition().lat());
        jQuery('#long').val(marker.getPosition().lng());
        infowindow.setContent(results[0].formatted_address);
        infowindow.open(map, marker);
        }
        }
        });
        });

        }
        google.maps.event.addDomListener(window, 'load', initialize);
</script>
 	<?php
}
