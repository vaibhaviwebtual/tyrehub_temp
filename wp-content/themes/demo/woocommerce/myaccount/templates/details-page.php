<?php 
ob_start();
global $woocommerce, $wpdb;
if(isset($_GET['service_id'])){
    $service_id = $_GET['service_id'];
    $destination_data = "SELECT * 
        FROM th_cart_item_installer
        WHERE cart_item_installer_id = '$service_id'";
}
else{
    $voucher_id = $_GET['voucher_id'];
    $destination_data = "SELECT * 
        FROM th_cart_item_service_voucher
        WHERE service_voucher_id = '$voucher_id'";        
}
$args = array(
        'ex_tax_label'       => false,
        'currency'           => '',
        'decimal_separator'  => wc_get_price_decimal_separator(),
        'thousand_separator' => wc_get_price_thousand_separator(),
        'decimals'           => wc_get_price_decimals(),
        'price_format'       => get_woocommerce_price_format(),
      );
$homeul=site_url();
?>
<div class="message" style="color: red; font-size: 18px;font-weight: 700;"></div>
<?php
$row = $wpdb->get_results($destination_data);
if(!empty($row)) {
    foreach ($row as $key => $data) {
        $destination = $data->destination;
        $item_installer = $data->cart_item_installer_id;
        $tyre_status = $data->status;
        $order_id = $data->order_id;
        $order = wc_get_order($order_id);
        $order_data = $order->get_data();
        $order_items = $order->get_items();
        $order_date = $order->order_date;
        $first_name = $order_data['billing']['first_name'];
        $last_name = $order_data['billing']['last_name'];
        $mobile_no = $order_data['billing']['phone'];   
        $email = $order_data['billing']['email']; 
        
        $SQL="SELECT * FROM th_vehicle_details WHERE order_id='".$order_id."'";
        $vehicle_details=$wpdb->get_row($SQL);
        $_SESSION['model_id'] =$vehicle_details->model;  
        $_SESSION['sub_model_id']=$vehicle_details->submodel;

        $SQL="SELECT * FROM th_vehicle_tyre_information WHERE order_id='".$order_id."'";
        $tyre_details=$wpdb->get_results($SQL);




        $order_prd_id = $data->product_id;
        foreach ($order_items as $item_id => $item_data) {   
       // $vehicle_tyep=wc_get_order_item_meta($item_id, 'pa_vehicle-type', true );      
	   $vehicle_tyep=get_post_meta($order_prd_id, 'attribute_pa_vehicle-type', true );  
            if($item_data['variation_id'] != '') {
                $temp_prd_id = $item_data['variation_id'];
            }
            else {
                $temp_prd_id = $item_data['product_id'];
            }   
            if($order_prd_id == $temp_prd_id) {
                $quantity = $item_data['quantity'];
            }
        }
        $product_variation = wc_get_product( $order_prd_id );
        $variation_des = $product_variation->get_description();
        $parent_id = $product_variation->get_parent_id();
        $image = wp_get_attachment_image_src( get_post_thumbnail_id( $parent_id ), 'single-post-thumbnail' );
        if($_GET['voucher_id']) {
            $vehicle_id = $data->vehicle_id;
            
            $vehicle_name = $wpdb->get_var( $wpdb->prepare( "SELECT vehicle_type FROM th_vehicle_type WHERE vehicle_id='%s' LIMIT 1" , $vehicle_id) );
            $voucher_name = $data->voucher_name;
            if($voucher_name == 'promotional' || $voucher_name == 'promotion') {
                $service_type = 'Promotion Voucher';
                $voucher_type = 'promotion';
            } else {
                $service_type = 'Service voucher for vehicle type: '.$vehicle_name;
                $voucher_type = 'service';
            }
        ?>
		<div class="prd-block">
			<div class="single-service detail-page">
				<input type="hidden" name="" class="tyre-installer" value="<?php echo $_GET['voucher_id']; ?>">
				<div class="image-block">
					<img src="<?php  if($image[0] != ''){ echo $image[0]; }else{ echo get_site_url().'/wp-content/themes/demo/images/no_img1.png'; } ?>" data-id="<?php echo $loop->post->ID; ?>">
				</div>
				<div class="data-block">
					<div class="first-row">
						<div class="order-id" data-id="<?php echo $order_id; ?>"><strong>Order #<?php echo $order_id; ?></strong></div>
						<div class="date">
							<i class="fa fa-calendar"></i>
							<?php echo $newDate = date("d-m-Y H:i a", strtotime($order_date)); ?>
						</div>
					</div>
					<?php 
					// ------------ promotion voucher data -------------//
					if($voucher_type == 'promotion'){?>
					<div class="tyre-name">
						<strong>GOODYEAR test tyrename</strong>
					</div>
					<div class="customer-name"><i class="fa fa-user"></i>Ankit Shah
					</div>
					<div class="mobile-no">
						<i class="fa fa-mobile" aria-hidden="true"></i>
						123456789
						<input type="hidden" name="" class="user-mobile-no" value="<?php echo $mobile_no; ?>">
						<i class="fa fa-phone"></i>
					</div>
					<div class="service-details service-status-info">
						<strong class="ser-sec-title">Service Details :</strong>
						<div class="service-line">
							<div class="left">
								<?php                                  
									echo '<img class="service-img" src="'.get_site_url().'/wp-content/themes/demo/images/tyre_fitting.png"></img>';          
								?>
								Tyre Fitment - 4 Tyre                              
							</div>                            
							<div class="right">pending</div>
					   </div>
						<div class="service-line">                                
							<div class="left">
								<?php                                  
									echo '<img class="service-img" src="'.get_site_url().'/wp-content/themes/demo/images/tyre_fitting.png"></img>';          
								?>
								 Wheel Balancing - 1 car                              
							</div>                            
							<div class="right">pending</div>                
					   </div>
				   </div>
					<?php }
					//////------- service voucher -----------//
					elseif ($voucher_type == 'service'){?>
					<div class="tyre-name">
						<strong><?php echo $service_type; ?></strong>
					</div>
					<div class="customer-name">
						<i class="fa fa-user"></i>
						<?php echo $first_name.' '.$last_name; ?>
					</div>
					<div class="mobile-no">
						<i class="fa fa-phone"></i>
						<?php echo $mobile_no; ?>
						<input type="hidden" name="" class="user-mobile-no" value="<?php echo $mobile_no; ?>">
					</div>
					<div class="service-details service-status-info">
						<strong class="ser-sec-title">Service Details :</strong>
						<div class="service-line">
						   <?php 
							//echo $voucher_name;
							if($voucher_name == 'Wheel alignment & balancing') { 
								echo '<div class="left"><img class="service-img" src="'.get_site_url().'/wp-content/themes/demo/images/service-icon/alignment_balance.png"></img> Wheel Alignment & Balancing</div>';
								echo '<div class="right">Pending</div>';
							}
							elseif ($voucher_name == 'Car Washing') {
								echo '<div class="left"><img class="service-img" src="'.get_site_url().'/wp-content/themes/demo/images/service-icon/carwash.png"></img> Car Washing</div>';
								echo '<div class="right">Pending</div>';
							}
							else {
								echo $voucher_name;
							}
							?>
						</div>
					</div>
					<?php
					}
					?>
				</div>
			</div>
			<div class="qrcode-info">
                <?php if ($voucher_name != 'Car Washing') {?>
                    <form id="tab1-form" class="vehicle_details">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="select-wrapper">
                                     <label>Make</label>
                                    <select name="select-car-cmp" class="input-custom select-car-cmp" required>
                                        <option value="" disabled selected="">Make</option>
                                    <?php
                                    global $wpdb , $woocommerce;
                                    $make_data = $wpdb->get_results("SELECT * FROM th_make where vehicle_type = '1' AND status =1 order by make_name asc");
    
                                    foreach ($make_data as $data) {
                                        $make_id = $data->make_id;
                                        $make_name = $data->make_name;
                                    ?>    
                                        <option value="<?php echo $make_id; ?>" <?php if(isset($vehicle_details->make) && $vehicle_details->make == $make_id){ echo 'selected'; }?>><?php echo $make_name; ?></option>
                                    <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="select-wrapper year-wrapper" >
                                    <label>Model</label>
                                    <select disabled="disabled" name="select1" class="input-custom select-model" required>
                                        <option value="" selected="">Model</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="select-wrapper model-wrapper">
                                    <label>Sub Model</label>
                                    <select name="select3" disabled="disabled" class="input-custom select-sub-model" required>
                                        <option value="" disabled selected>Sub Model</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="input-wrapper model-wrapper">
                                    <label>Car Number</label>
                                    <input type="text" class="input-custom" name="car_number" id="car_number" value="<?=$vehicle_details->car_number?>" placeholder="" maxlength="12" size="12">
                                 
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="input-wrapper model-wrapper">
                                    <label>Odo Meter(KM)</label>
                                    <input type="text" class="input-custom" name="odo_meter" id="odo_meter" value="<?=$vehicle_details->odo_meter?>" placeholder="" maxlength="7" size="7" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
                                                                      
                                </div>
                            </div>
                        </div>

                    </div>

               
                   <!--  <div class="modal-footer text-center">
                        <button type="button" class="btn btn-default dcclose-btn" id="car_details_page_save"><span>Save</span></button>
                    </div> -->
              </form>
          <?php  }else{
            $services_id=5;
          }?>
                <input type="hidden" name="user_id" value="<?=$service_row[0]->session_id?>" id="user_id">
                <input type="hidden" name="order_id" value="<?=$order_id?>" id="order_id">
                <input type="hidden" name="tyre_count" value="<?=$serTyreQty?>" id="tyre_count">
                 <input type="hidden" name="product_id" value="<?=$temp_prd_id?>" id="product_id"> 
                 <input type="hidden" name="services_id" value="<?=$services_id?>" id="services_id">
				<div class="scan-btn">
					<button class="scan-barcode custom-btn"><i class="fa fa-qrcode" aria-hidden="true"></i>Scan</button>
				</div>
				<div class="or-text">
					<strong>OR</strong>
				</div>
				<div class="code-text">
					<input type="text" name="barcode" class="barcode-text" placeholder="Enter Code">
					<input type="file" id="imageFile" name="imageFile" class="imageFile" accept="image/*" capture="camera" style="display: none;" />
					<div class="text-center" style="display:none;">
						<img id="qrcode" class="qrcode" style="height:250px;width:250px;" src="http://placehold.it/250x250&amp;text=QR%20Code"/>
						<br/>
						<br/>
						<button id="decodeBtn" class="decodeBtn btn btn-danger btn-lg btn-block" type="button">Decode the QR Code</button>
					</div>
				</div> 
				<div class="btn-part">
					<button id="update-service-status" class="update-service-status custom-btn">Update</button>
				</div>
			</div>
		</div>
        <div class="modal fade" id="installer_modal" role="dialog" >
            <div class="modal-dialog modal-sm">
            
              <!-- Modal content-->
              <div class="modal-content" style="text-align: center;">
                
                <div class="modal-body">
                  <p>Service Status updated</p>
                </div>
                <div class="modal-footer" style="text-align: center;">
                  <a href="<?php echo $homeul; ?>/my-account/service-request/" class="btn btn-invert" style="min-width: 100px; padding: 10px;"><span>ok</span></a>
                </div>
              </div>
              
            </div>
        </div>
        <div class="modal fade" id="after_scan" role="dialog" >
            <div class="modal-dialog modal-sm">
            
              <!-- Modal content-->
              <div class="modal-content" style="text-align: center;">
                
                <div class="modal-body">
                  <p>Sure you want to update service status?</p>
                </div>
                <div class="modal-footer" style="text-align: center;">
                  <button class="confirm-voucher-update btn btn-invert" style="min-width: 100px; padding: 10px;"><span>Update</span></button>
                  <button type="button" class="btn btn-invert" data-dismiss="modal" style="min-width: 100px; padding: 10px;"><span>Cancle</span></button>
                </div>
              </div>
              
            </div>
        </div>        
<?php
        }
        else{
        if($destination == 1) {
            $services = "SELECT * 
                    FROM th_cart_item_services
                    WHERE product_id = '$order_prd_id' and order_id = '$order_id'";
            $service_row = $wpdb->get_results($services);
?>
            <div class="prd-block">
            <input type="hidden" name="" class="tyre-installer" value="<?php echo $item_installer; ?>">
           
            <div class="single-service detail-page">
                <div class="image-block">
                    <img src="<?php  if($image[0] != ''){ echo $image[0]; }else{ echo get_site_url().'/wp-content/themes/demo/images/no_img1.png'; } ?>" data-id="<?php echo $loop->post->ID; ?>">
                </div>
                <div class="data-block">
                    <div class="first-row">
                        <div class="order-id" data-id="<?php echo $order_id; ?>"><strong>Order #<?php echo $order_id; ?></strong></div>
                        <div class="date">
                            <i class="fa fa-calendar"></i>
                            <?php echo $newDate = date("d-m-Y H:i a", strtotime($order_date)); ?>
                        </div>
                    </div>
                    <h3 class="tyre-name"><?php echo $variation_des; ?> (<?php echo $quantity; ?>Tyre)</h3>
                    <div class="customer-name">
                        <i class="fa fa-user"></i>
                        <?php echo $first_name.' '.$last_name; ?>
                    </div>
                    <a class="mobile-no" href="tel:<?php echo $mobile_no; ?>"><i class="fa fa-phone"></i> <?php echo $mobile_no; ?></a>
                    
                    <div class="service-details service-status-info">
                        <strong class="ser-sec-title">Service Details :</strong>
                        <?php
                        global $wpdb;
                        foreach ($service_row as $key => $service) {
                            $service_id = $service->cart_item_services_id;
                            $SQL="SELECT * FROM th_service_data WHERE service_data_id='$service->service_data_id'";
                            $servi=$wpdb->get_row($SQL);
                            $status = $service->status;
                            $qty = $service->tyre;
                            $service_name = $service->service_name;
                            $image = $servi->image;
                            if($service->service_name=='Tyre Fitment'){
                                $serTyreQty=$qty = $service->tyre;
                            }
                        ?>
                            <div class="service-line" id="<?php echo $service_id; ?>">
                            <div class="left">
                            <img class="service-img" src="<?=bloginfo('template_url')?>/images/service-icon/<?=$image;?>"></img>
                                <?php echo $service_name = $service->service_name;?>
                                <?php 
                                    if($service_name == 'Tyre Fitment') {
                                        echo '- '.$qty.' Tyre';
                                    } else {
                                        echo '- '.$qty.' Car';
                                    } 
                                ?>      
                            </div>
                            <div class="right">
                                <?php if($tyre_status == '') { echo 'pending'; } else { echo $tyre_status; } ?>
                            </div>
                       </div>
                        <?php } ?>
                    </div>
                </div>
            </div>   
            <div class="qrcode-info"
                        <?php 
                        if($vehicle_tyep=='two-wheeler'){
                            $title='Please enter bike details';
                            $numbtitle='Bike Number';
                            $vehicletype=2;
                        }else{
                            $title='Please enter car details';
                            $numbtitle='Car Number';
                            $vehicletype=1;
                        }
                    ?>>
                <form id="tab1-form" class="vehicle_details">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="select-wrapper">
                                     <label>Make</label>
                                    <select name="select-car-cmp" class="input-custom select-car-cmp" required>
                                        <option value="" disabled selected="">Make</option>
                                    <?php
                                    global $wpdb , $woocommerce;
                                    $make_data = $wpdb->get_results("SELECT * FROM th_make where vehicle_type = '$vehicletype' AND status =1 order by make_name asc");
    
                                    foreach ($make_data as $data) {
                                        $make_id = $data->make_id;
                                        $make_name = $data->make_name;
                                    ?>    
                                        <option value="<?php echo $make_id; ?>" <?php if(isset($vehicle_details->make) && $vehicle_details->make == $make_id){ echo 'selected'; }?>><?php echo $make_name; ?></option>
                                    <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="select-wrapper year-wrapper" >
                                    <label>Model</label>
                                    <select disabled="disabled" name="select1" class="input-custom select-model" required>
                                        <option value="" selected="">Model</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="select-wrapper model-wrapper">
                                    <label>Sub Model</label>
                                    <select name="select3" disabled="disabled" class="input-custom select-sub-model" required>
                                        <option value="" disabled selected>Sub Model</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="input-wrapper model-wrapper">
                                    <label><?=$numbtitle;?></label>
                                    <input type="text" class="input-custom" name="car_number" id="car_number" value="<?=$vehicle_details->car_number?>" placeholder="" maxlength="12" size="12">
                                 
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="input-wrapper model-wrapper">
                                    <label>Odo Meter(KM)</label>
                                    <input type="text" class="input-custom" name="odo_meter" id="odo_meter" 
									value="<?php if($vehicle_details->odo_meter != '0'){ echo $vehicle_details->odo_meter;}?>" placeholder="" maxlength="7" size="7" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
                                    <input type="hidden" name="user_id" value="<?=$service_row[0]->session_id?>" id="user_id">
                                    <input type="hidden" name="order_id" value="<?=$order_id?>" id="order_id">
                                    <input type="hidden" name="tyre_count" value="<?=$serTyreQty?>" id="tyre_count">
                                     <input type="hidden" name="product_id" value="<?=$temp_prd_id?>" id="product_id">
                                    
                                    
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row serial_number">

                    <?php 
                        if($tyre_details){
                            $j=1;
                            foreach ($tyre_details as $key => $value) {
                                 ?>
                                <div class="col-md-4">
                                    <div class="form-group ">
                                        <label for="" class="col-form-label">Tyre <?=$j;?> Serial Number</label>
                                        <input type="text" class="form-control input-custom serial_number" name="serial_number[]" id="serial_number_<?=$j?>" placeholder="Serial Number" value="<?=$value->serial_number?>" maxlength="4" size="4" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
                                        <input type="hidden" name="tyre_info_id[]" value="<?=$value->id;?>" id="tyre_info_id">
                                    </div>
                                </div>

                                <?php $j++;
                            }
                            $disabled='';
                        }else{
                            $j=1; 

                            for ($i=0; $i < $serTyreQty; $i++)  {?>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="" class="col-form-label">Tyre <?=$j;?> Serial Number</label>
                                    <input type="text" class="form-control input-custom serial_number" name="serial_number[]" id="serial_number_<?=$i?>" placeholder="Serial Number" maxlength="4" size="4" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
                                </div>
                            </div>

                            <?php $j++; }

                            $disabled='disabled';
                        }
                        ?>
                    </div>
               
                   <!--  <div class="modal-footer text-center">
                        <button type="button" class="btn btn-default dcclose-btn" id="car_details_page_save"><span>Save</span></button>
                    </div> -->
              </form>
              
              
                <div class="code-scan-section">
                    <div class="scan-btn">
                        <button class="scan-barcode custom-btn">
                            <i class="fa fa-qrcode" aria-hidden="true"></i>
                            Scan
                        </button>
                    </div>
                    <div class="or-text">
                        <strong>OR</strong>
                    </div>
                    <div class="dcode-input-group code-text">
                        <input type="text" name="barcode" class="barcode-text" placeholder="Enter Code">
                        <input type="file" id="imageFile" name="imageFile" class="imageFile" accept="image/*" capture="camera" style="display: none;" />
                        <button id="car_details_page_save" class="update-service-status dcupdate-service-button custom-btn">Update</button>
                        
                        <div class="text-center" style="display:none;">
                            <img id="qrcode" class="qrcode" style="height:250px;width:250px;" src="http://placehold.it/250x250&amp;text=QR%20Code"/>
                            <br/>
                            <br/>
                            <button id="decodeBtn" class="decodeBtn btn btn-danger btn-lg btn-block" type="button">Decode the QR Code</button>
                        </div>
                    </div> 
                    
                </div>
                    
            </div>
            
         </div>
     <?php } ?>
         <div class="modal fade" id="installer_modal" role="dialog" >
            <div class="modal-dialog modal-sm">
              <!-- Modal content-->
              <div class="modal-content" style="text-align: center;">
                <div class="modal-body">
                  <p>Service Status updated</p>
                </div>
                <div class="modal-footer" style="text-align: center;">
                  <a href="<?php echo $homeul; ?>/my-account/service-request/" class="btn btn-invert" style="min-width: 100px; padding: 10px;"><span>ok</span></a>
                </div>
              </div>
            </div>
        </div>
        <div class="modal fade" id="after_scan" role="dialog" >
            <div class="modal-dialog modal-sm">
              <!-- Modal content-->
              <div class="modal-content" style="text-align: center;">
                <div class="modal-body">
                  <p>Sure you want to update service status?</p>
                </div>
                <div class="modal-footer" style="text-align: center;">
                  <button class="confirm-update btn btn-invert" style="min-width: 100px; padding: 10px;"><span>Update</span></button>
                  <button type="button" class="btn btn-invert" data-dismiss="modal" style="min-width: 100px; padding: 10px;"><span>Cancle</span></button>
                </div>
              </div>
            </div>
        </div>
         <?php
            }
        }
    }