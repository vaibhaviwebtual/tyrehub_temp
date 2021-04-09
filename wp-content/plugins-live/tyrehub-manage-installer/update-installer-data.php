<?php
function tim_update(){

 	global $wpdb;
    $currency = get_woocommerce_currency_symbol();
    $installer_id = $_GET['installer_id'];
    $sql = "SELECT * FROM th_installer_data where installer_data_id = '$installer_id'";
    $installer_data = $wpdb->get_results($sql);

foreach ($installer_data as $key => $value)
	{
		$id = $value->installer_data_id;
		$user_id = $value->user_id;
		$name = $value->business_name;
		$mobile_no = $value->contact_no;
		$add = $value->address;
		$visibility = $value->visibility;
		$is_franchise = $value->is_franchise;
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
    	$lattitude = $value->location_lattitude;
    	$longitude = $value->location_longitude;
    	
	}

	$user_info = get_userdata($user_id);
	$email = $user_info->user_email;
    
    
?>
	<div class="wrap woocommerce">
 		<h1 class="wp-heading-inline">Update Installer <?php echo $name; ?></h1>
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
		 				<th>Franchise</th>
		 				<td>
		 					 <input type="checkbox" class="" name="franchise_status"  <?php if($is_franchise == 'yes'){ echo 'checked';} ?> />&nbsp;<?php esc_html_e( 'Franchise', 'woocommerce' ); ?>
		 				</td>
		 			</tr>

		 			<tr>
		 				<th>Company / Store name</th>
		 				<td><input type="text" name="business_name" value="<?php echo $name; ?>">
		 					<input type="hidden" class="installer_id" name="installer_id" value="<?php echo $id; ?>">
		 					<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
		 				</td>
		 			</tr>
		 			<tr>
		 				<th>Address</th>
		 				<td><input type="text" name="address" value="<?php echo $add; ?>"></td>
		 			</tr>
		 			<tr>
		                <th>Google Map</th>
		                <td>
		                    <input type="hidden" id="lat" name="lat" value="<?=$lattitude;?>" /> 
		                    <input type="hidden" id="long" name="long" value="<?=$longitude;?>" /> 
		                    <div id="map_canvas" style="width:100%; height: 350px;"></div></td>
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
		                <th>Available time</th>
		                <td><input type="text" name="av_time" value="<?php echo $av_time; ?>"></td>
		            </tr>
		            <tr>
		                <th>Available days</th>
		                <td><input type="text" name="av_day" value="<?php echo $av_day; ?>"></td>
		            </tr>
		 			<tr>
		 				<th>Image</th>
		 				<td>
		 					<input type="text" name="installer_img" readonly="" class="installer_img" value="<?php echo $image; ?>">
		 					<input type="button" id="upload_img" name="" value="Upload Image">
		 					
		 				</td>
		 			</tr>
		 			
		 			<tr>
		                <th>Facilities</th>
		                <td>
		                   <div class="fc-list">
                        
                        <?php
                        global $woocommerce;
                        global $wpdb;
                        $fc_sql = "SELECT * from th_installer_facilities where type = 'f'";
                        $fc_data = $wpdb->get_results($fc_sql);

                        
                        $sfc_sql = $wpdb->get_var("SELECT meta_value from th_installer_meta where installer_id = '$installer_id' and meta_name = 'facilities'");
                        

                        $sfc_sql_arr = unserialize($sfc_sql); 
                        //var_dump($sfc_sql_arr);
                        foreach ($fc_data as $key => $fc_row)
                        {
                            $name = $fc_row->name;
                            $f_id = $fc_row->f_id;
                            
                            ?>
                            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                <input type="checkbox" class="" name=fc-check[]" id="<?php echo $f_id; ?>" value="<?php echo $f_id; ?>" <?php if (is_array($sfc_sql_arr)) { if(in_array($f_id, $sfc_sql_arr)){ echo 'checked';} } ?> />&nbsp;<?php esc_html_e( $name , 'woocommerce' ); ?>      
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
                        $fc_sql = "SELECT * from th_service_data where (as_flag =1  OR service_data_id=4) AND status=1";
                        $services_data = $wpdb->get_results($fc_sql);

                        $SQL="SELECT service_data_id from th_installer_addi_service where installer_id = '$installer_id'";
                        $sas_sql_arr = $wpdb->get_results($SQL);                        
                       
                        foreach ($sas_sql_arr as $key => $seleservice){
                        	$servdata[]=$seleservice->service_data_id;
                        }
                     
                        foreach ($services_data as $key => $service){?>
                              <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                  <input type="checkbox" class="" name="services[]" value="<?php echo $service->service_data_id; ?>" <?php if (is_array($servdata)) { if(in_array($service->service_data_id,$servdata)){ echo 'checked';} }  ?> />&nbsp;<?php esc_html_e($service->service_name,'woocommerce'); ?>      
                              </p>
                              <?php
                          }?>
	                   	 	</div>
		                </td>
		            </tr>
		            <tr>
		                <th>Choose Payment Method</th>
		                <td>
		                 <div class="as-list">                        
	                    <?php
                        global $wpdb;
                        $pay_sql = "SELECT * from wp_franchises_payment_method where  status=1";
                        $paymethod_data = $wpdb->get_results($pay_sql);

                        $SQL="SELECT payment_id from wp_franchises_choose_pmethod where franchise_id = '$installer_id'";
                        $choose_sql_arr = $wpdb->get_results($SQL);                        
                       
                        foreach ($choose_sql_arr as $key => $chmethod){
                        	$choosedata[]=$chmethod->payment_id;
                        }
                     
                        foreach ($paymethod_data as $key => $pay){?>
                              <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                  <input type="checkbox" class="" name="pay_method[]" value="<?php echo $pay->id; ?>" <?php if (is_array($choosedata)) { if(in_array($pay->id,$choosedata)){ echo 'checked';} }  ?> />&nbsp;<?php esc_html_e($pay->payment_method,'woocommerce'); ?>      
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
		                <td><input type="text" name="contact-person" value="<?php echo $contact_person; ?>"></td>
		            </tr>
		            <tr>
		 				<th>Email</th>
		 				<td><input type="text" name="email" value="<?php echo $email; ?>">
		 					<input type="hidden" name="final_email" value="<?php echo $email; ?>" readonly>
		 					
		 				</td>
		 			</tr>
        			<tr>
		 				<th>contact_no</th>
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
		 					<button class="button-primary woocommerce-save-button" type="submit" name="update">
		 					Save Changes</button>
		 					<a href="<?=get_admin_url();?>/admin.php?page=installer-manage" class="button-primary woocommerce-save-button add-installer-btn">Back</a> 
		 				</td>
		            </tr>
        		</table>
        	</div>
 		</form>

 		<?php
 		if(isset($_POST['update']))
 		{
 			

 			global $wpdb, $woocommerce;
 			$installer_id = $_POST['installer_id'];
			$name = $_POST['business_name'];
			$mobile_no = $_POST['contact'];
			$final_contact = $_POST['final_contact'];

			$final_email = $_POST['final_email'];
			$email = $_POST['email'];
			$add = $_POST['address'];
			$installer_img = $_POST['installer_img'];
			$av_time = $_POST['av_time'];
        	$av_day = $_POST['av_day'];

			$url = "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($add)."&sensor=false&key=AIzaSyCmMCGIlJKPcPn_hjlgo6j-RMfN4ZtOH70";
            $result_string = file_get_contents($url);
        
            $result = json_decode($result_string, true);
            $val = $result['results'][0]['geometry']['location'];

            $lat = $val['lat'];
            $lng = $val['lng'];

			$state = $_POST['state'];
			$city = $_POST['city'];
			$SQL="SELECT city_name FROM th_city WHERE city_id ='$city'";
			$city_name = $wpdb->get_var($SQL);

			$pincode = $_POST['pincode'];
			$gst_no = $_POST['gstno'];
			$store_phone = $_POST['store_phone'];
			$cmp_name = $_POST['business_name'];
			$cmp_add = $_POST['address'];
		    $visibility = '0';
		    $is_franchise = "";
		    $pass = $_POST['pass'];
		    $user_id = $_POST['user_id'];
		    $contact_person = $_POST['contact-person'];

		    $latitude = $_POST['lat'];
			$longitude = $_POST['long'];


		    if($_POST['visibility'])
		    {
		         $visibility = '1';
		    }

		    if($_POST['franchise_status'])
		    {
		         $is_franchise = 'yes';
		    }
			
			$facility_arr = [];
			//$as_arr = [];

			if(!empty($_POST['fc-check'])) {
				
                $facility_arr = serialize($_POST['fc-check']);
                var_dump($facility_arr);
            }

            /*if(!empty($_POST['as-check'])) {
                $as_arr = serialize($_POST['as-check']);
            }
*/
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
	            		
	            	 	$ch1 = curl_init();
					    $message = "Dear, ".$contact_person." your username and password changed for your store ".$name.". New username : ".$mobile_no." and paswword: ".$pass." Thank You Tyrehub Team";
					    $message = str_replace(' ', '%20', $message);
					    $url_string="http://nimbusit.co.in/api/swsend.asp?username=t1ankitshah&password=81957032&sender=TYREHB&sendto=91".$mobile_no."&message=".$message;
					    curl_setopt($ch1, CURLOPT_URL, $url_string); 
					    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1); 
					    curl_setopt($ch1, CURLOPT_CUSTOMREQUEST, "GET");			   
					    $result1 = curl_exec($ch1);
					    curl_close ($ch1);
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
	            	update_user_meta( $user_id, 'cmp_name', $cmp_name);
		            update_user_meta( $user_id, 'cmp_add', $cmp_add);
		            update_user_meta( $user_id, 'gst_email',$gst_email);

	            	update_user_meta( $user_id, 'first_name',$contact_person);




	            	$SQL="UPDATE th_installer_data SET address = '$add' , business_name = '$name' , city = '$city_name', city_id = '$city' , state = '$state' , pincode = '$pincode' , gst_no = '$gst_no' , company_name = '$cmp_name' , company_add = '$cmp_add' , wifi_service = '$wifi_service' , is_franchise = '$is_franchise' , water_service = '$water' , tea_service = '$tea' , car_pickup_service = '$pickup', visibility = '$visibility' , location_lattitude = '$lat' , location_longitude = '$lng' , image = '$installer_img' , available_time = '$av_time' , available_days = '$av_day' , store_phone = '$store_phone', location_lattitude = '$latitude', location_longitude = '$longitude' ,contact_no = '$mobile_no',contact_person = '$contact_person' WHERE installer_data_id = '$installer_id'";

	            	
	            	 $update = $wpdb->query($SQL);

	            	 $fccount = $wpdb->get_var("SELECT COUNT(*) from th_installer_meta WHERE installer_id = '$installer_id' and meta_name = 'facilities'");

		 			if($fccount != 0){
		 				 $wpdb->query("UPDATE th_installer_meta SET meta_value = '$facility_arr' WHERE installer_id = '$installer_id' and meta_name = 'facilities'");
		 			}
		 			else{
		 				$insert = $wpdb->insert('th_installer_meta', array( 
	                        'installer_id' => $installer_id,
	                        'meta_name' => 'facilities',
	                        'meta_value' => $facility_arr
	                         ));
		 			}	

		 				global $wpdb;
		                $wpdb->query('DELETE  FROM th_installer_addi_service WHERE installer_id = "'.$installer_id.'"');
		                
		                $services=$_POST['services'];

		                foreach ($services as $key => $value) {
		                    # code...
		                    $insert = $wpdb->insert('th_installer_addi_service', array( 
		                        'installer_id' => $installer_id,
		                        'service_data_id' =>$value
		                    ));
		                }

		                global $wpdb;
		                $wpdb->query('DELETE  FROM wp_franchises_choose_pmethod WHERE franchise_id = "'.$installer_id.'"');
		                
		                $pay_method=$_POST['pay_method'];

		                foreach ($pay_method as $key => $value) {
		                    # code...
		                    $insert = $wpdb->insert('wp_franchises_choose_pmethod', array( 
		                        'franchise_id' => $installer_id,
		                        'payment_id' =>$value,
		                        'status'=>1
		                    ));
		                }
		 			
		 			/*$ascount = $wpdb->get_var("SELECT COUNT(*) from th_installer_meta WHERE installer_id = '$installer_id' and meta_name = 'additional_services'");
		 			
		 			if($fccount != 0){	
		 				$wpdb->query("UPDATE th_installer_meta SET meta_value = '$as_arr' WHERE installer_id = '$installer_id' and meta_name = 'additional_services'");
		 			}else{
		 				 $insert = $wpdb->insert('th_installer_meta', array( 
	                        'installer_id' => $installer_id,
	                        'meta_name' => 'additional_services',
	                        'meta_value' => $as_arr
	                         ));
		 			}*/

		 			echo '<div class="notice notice-success is-dismissible">
                 <p>Installer profile update successfully.</p>
             </div>';
			}


 			wp_redirect( '?page=installer-add-new&action=edit&installer_id='.$installer_id );
 		}
 		?>
 	</div>

 	<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyCmMCGIlJKPcPn_hjlgo6j-RMfN4ZtOH70">
</script>
 <script type="text/javascript"> 
        var map;
        var marker;
        var latitude= jQuery('#lat').val();
        var longitude= jQuery('#long').val();
        var myLatlng = new google.maps.LatLng(latitude,longitude);
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