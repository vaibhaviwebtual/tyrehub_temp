<?php
global $wpdb;
$user_id = get_current_user_id();
	$SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
	$franchise=$wpdb->get_row($SQL);
	if($franchise){
	$installer = "SELECT * 
	            FROM th_franchise_cart_item
	            WHERE cart_item_key = '$cart_item_key' and order_id = ''";
	}
$row = $wpdb->get_results($installer);

if(!empty($row))
{
	$installer_name = '';
    $selected_vehicle_id = '';
    $vehicle_name = '';
    foreach ($row as $key => $installer) 
    {
    	$destination = $installer->destination;
    	
    	$installer_table_id = $installer->cart_item_installer_id;
    	$installer_id = $installer->installer_id;
    	$vehicle_id = $installer->vehicle_id;
    	$installer_name = $wpdb->get_var( $wpdb->prepare( "SELECT business_name FROM th_installer_data WHERE installer_data_id='%s' LIMIT 1" , $installer_id) );

    	$vehicle_name = $wpdb->get_var( $wpdb->prepare( "SELECT vehicle_type FROM th_vehicle_type WHERE vehicle_id='%s' LIMIT 1" , $vehicle_id) );
    	$selected_vehicle_id = $installer->vehicle_id;
    }
    $SQL="SELECT * FROM th_installer_addi_service ids 
		     LEFT JOIN th_service_data as sd ON sd.service_data_id=ids.service_data_id 
		     WHERE sd.status=1 and ids.installer_id='".$installer_id."' AND sd.status=1 AND sd.show_on_cart=1";
    	$addi_service = $wpdb->get_results($SQL);
	  		
?>
	<tr class="service-row product-name">
		<td colspan="6" class="service-inner" style="padding: 0;">
			<table style="margin-bottom: 0;">
				<tbody id="insta-<?=$cart_item_key;?>">
					<?php 
						$_product->get_name();
						$cart_item['variation_id'];
						if($cart_item['variation_id'])
						{
							$product_variation = new WC_Product_Variation( $cart_item['variation_id'] );
							$pa_vehicletype=$product_variation->get_attributes();
						} 
					?>
				
					<?php if($pa_vehicletype['pa_vehicle-type']!='two-wheeler'){?>
					<?php

					if(empty($franchise)){
					$services = "SELECT * 
								FROM th_cart_item_services cis LEFT JOIN th_service_data as sd ON sd.service_data_id=cis.service_data_id
								WHERE cis.cart_item_key = '$cart_item_key' and cis.session_id = '$session_id' and cis.order_id = ''";
					}else{
						if($cart_item['offline-purchase']=='yes'){
									$services = "SELECT * 
									FROM th_franchise_cart_item_services cis LEFT JOIN th_service_data as sd ON sd.service_data_id=cis.service_data_id
									WHERE cis.cart_item_key = '$cart_item_key' and (cis.order_id = '0' OR cis.order_id =null)";
							}
					}

						
						$row = $wpdb->get_results($services);

						$service_name = '';
						$service_list = [];
						$amount = '';
						$total_amout = 0;
						$service_data_id_arr=array();
						foreach ($row as $key => $service) 
						{
							$tyre_count = $service->tyre;
							$service_data_id_arr[] = $service->service_data_id;
							$service_name = $service->service_name;
							if($service_name=='Tyre Fitment'){
								$free=' - Free';
							}else{$free='';}

							$rate = $service->rate;
							$image = $service->image;

							$service_list[$service_name] = $tyre_count;
							$cart_item_services_id= $service->cart_item_services_id;
							$cart_item_key= $service->cart_item_key;
							$session_id= $service->session_id;

							?>
							<tr class="service-list-added">
								<td class="added-service-thumbnail"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/service-icon/<?=$image;?>" alt="" /></td>
								<td class="added-service-name title-sec"><?=$service_name;?> <?=$free;?></td>
								<td class="added-service-qty">&nbsp;</td>
								<td class="added-service-price price-sec">&nbsp;</td>
								<td class="added-service-total"><?=get_woocommerce_currency_symbol().$rate;?></td>
								<td class="added-service-remove">
									<?php if($rate>0){?>
									<a href="JavaScript:void(0);" class="servi-remove" id="<?=$cart_item_services_id;?>" data-cart-ses-id="<?=$session_id;?>" data-offline="yes" data-cart-key="<?=$cart_item_key;?>"><i class="fa fa-trash"></i></a>
									<?php }?>
								</td>
							</tr>
							<?php 
							$total_amout = $total_amout + $service->rate;
						}
						?>	
					<?php }?>
				
				<tr class="additional-ser">
					<td colspan="6">
						<?php 
						if(!empty($row))
						{
						if($pa_vehicletype['pa_vehicle-type']!='two-wheeler'){?>
							<div class="service-list">
								<div class="row offer offer-success offer<?=$installer_table_id;?>">
									<div class="col-md-12 service-list-inner">
										<input type="hidden" name="cart_item_key" id="cart_item_key" value="<?=$cart_item_key?>">
										<input type="hidden" name="session_id" id="session_id" value="<?=$session_id?>">
										<input type="hidden" name="product_id" id="product_id" value="<?=$cart_item['variation_id'];?>">
										
										<div class="title">
											<h4>Additional Services Available : </h4>
										</div>
										<div class="offer-content">
											<div class="funkyradio1">
												<!-- <h3 class="lead">***Special Offer***</h3> -->						
											<?php include('services-popup.php');?>


												</div>

											<div class="th-services">
												
												<?php 
												$vehicle_id=$cart_item['custom_data']['vehicle_type']; 
												//$vehicle_id=$_SESSION['vehicle_type'];
								
								if(empty($franchise)){
								$SQLSUB="SELECT DISTINCT service_data_id FROM th_cart_item_services WHERE cart_item_key = '$cart_item_key' and session_id = '$session_id' and order_id = ''";
								}else{
									if($cart_item['offline-purchase']=='yes'){
												$SQLSUB="SELECT DISTINCT service_data_id FROM th_franchise_cart_item_services  WHERE cart_item_key = '$cart_item_key'  and order_id = ''";
										}
								}

								



								$added_results = $wpdb->get_results($SQLSUB);
								$servi_array=array();
								foreach ($added_results as $key => $value) {
									# code...
									$servi_array[]=$value->service_data_id;

								}
								$services_list_sql = "SELECT sd.*,sdp.rate  FROM th_service_data sd LEFT JOIN th_service_data_price as sdp ON sdp.service_data_id=sd.service_data_id WHERE sdp.vehicle_id = '$vehicle_id' AND sd.show_on_cart!=0 AND sd.status=1";
												$service_results = $wpdb->get_results($services_list_sql);

												foreach ($service_results as $key => $service) {
													$service_id = $service->service_data_id;
													$service_name = $service->service_name;
													$image = $service->image;
													if($service_name == 'Tyre Fitment')
													{
														$fitting_rate=$service->rate;
													}

													if($service_name != 'Tyre Fitment')
													{
														$service_data_id=$service->service_data_id;
														if(in_array($service_id,$servi_array)){
															$class='services-hide';
														}else{
															$class='services-show';
														}	
														?>
														<div class="row <?=$class;?>" id="<?=$cart_item_key?>-<?=$service_data_id;?>">
															<div class="col-xs-2 img-sec">
																<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/service-icon/<?=$image;?>" alt="" />
															</div>
															<div class="col-xs-5 title-sec">
																<?=$service_name?> (per car) 
															</div>
															<div class="col-xs-2 price-sec">
																<?=get_woocommerce_currency_symbol().$service->rate?>
																<input type="hidden" name="service_name[]" value="<?=$service->rate;?>">
															</div>
															<div class="col-xs-3 add-service">
																<a class="btn btn-invert cart-services" href="javascript:void(0);" name="service_name[]" data-cart-id="<?=$installer_table_id;?>" data-id="<?=$cart_item_key?>" id="<?=$cart_item_key?>_checkbox_<?=$service_id?>" data-rate="<?=$service->rate;?>" data-name="<?=$service_name?>" data-vehicle="<?=$vehicle_id;?>"  data-qty="<?=$cart_item_qty;?>" data-session="<?=$session_id;?>" data-product="<?=$cart_item['variation_id'];?>" data-offline="yes" data-value="<?=$service_id;?>" ><span><i class="icon-shopping-cart"></i> Add to Cart</span></a>
															</div>
														</div>


												<?php } ?>                                                                      

													<?php

													}

												?>

												<?php 
												$vehicle_id=$cart_item['custom_data']['vehicle_type'];
												foreach ($addi_service as $key => $service) {
													$SQL="SELECT * FROM th_installer_service_price WHERE (vehicle_id='$vehicle_id' OR vehicle_id=0) and service_data_id='$service->service_data_id' AND city_id=1";
													$service_price = $wpdb->get_row($SQL);
													$service_data_id=$service->service_data_id;

													$service_data_id=$service->service_data_id;
														if(in_array($service_data_id,$servi_array)){
															$class='services-hide';
														}else{
															$class='services-show';
														}
													?>

												<div class="row <?=$class;?>" id="<?=$cart_item_key?>-<?=$service_data_id;?>">
													<div class="col-xs-2 img-sec">
														<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/service-icon/<?=$service->image;?>" alt="" />
													</div>
													<div class="col-xs-5 title-sec">
														<?=$service->service_name?>
													</div>
													<div class="col-xs-2 price-sec">
														<?php if($service->service_name=='Pickup & Drop Off Service'){?>
															<div id="pic_price">
															<a class="collapseExample" data-toggle="collapse" href="#collapseExample<?=$installer_table_id;?>" role="button" aria-expanded="false" aria-controls="collapseExample">Get Price</a>
															</div>
														<?php }else{?>
															<?=get_woocommerce_currency_symbol().$service_price->rate;?>
														<?php }?>
													</div>
													<div class="col-xs-3 add-service">
														<?php if($service->service_name=='Pickup & Drop Off Service'){?>
															<div id="pic_price">
															<a class="collapseExample" data-toggle="collapse" href="#collapseExample<?=$installer_table_id;?>" role="button" aria-expanded="false" aria-controls="collapseExample">Get Price</a>
															</div>

														<a class="btn btn-invert addi-cart-services" style="display: none;" id="pic_address" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample" name="service_name[]" data-cart-id="<?=$installer_table_id;?>" data-id="<?=$cart_item_key?>" id="<?=$cart_item_key?>_checkbox_<?=$service_data_id?>" data-rate="<?=$service_price->rate;?>" data-name="<?=$service->service_name?>" data-vehicle="<?=$vehicle_id;?>"  data-qty="<?=$cart_item_qty;?>" data-session="<?=$session_id;?>" data-product="<?=$cart_item['variation_id'];?>" data-offline="yes" data-value="<?=$service_data_id;?>"><span><i class="icon-shopping-cart"></i> Add to Cart</span></a>

													<?php }else{?>
														<a class="btn btn-invert cart-services" href="javascript:void(0);" name="service_name[]" data-cart-id="<?=$installer_table_id;?>" data-id="<?=$cart_item_key?>" id="<?=$cart_item_key?>_checkbox_<?=$service_data_id?>" data-rate="<?=$service_price->rate;?>" data-name="<?=$service->service_name?>" data-vehicle="<?=$vehicle_id;?>"  data-qty="<?=$cart_item_qty;?>" data-offline="yes" data-session="<?=$session_id;?>" data-product="<?=$cart_item['variation_id'];?>" data-value="<?=$service_data_id;?>" ><span><i class="icon-shopping-cart"></i> Add to Cart</span></a>
													<?php }?>
													</div>
												<?php if($service->service_name=='Pickup & Drop Off Service'){?>	
												<div class="collapse" id="collapseExample<?=$installer_table_id;?>">
													<div class="col-md-12">
														<div class="address-bx-outer">
															<strong>Pickup Location: </strong>
															<span class="address-bx"></span>
														</div>
													</div>
												  <div class="card card-body">
													<div class="col-md-8">
														<input id="myInput" class="search_input" type="text" value="<?=$_SESSION['pic_address']?>" placeholder="Enter Pickup Address" autocomplete="new-password"/>
														<p class="km-error" style="display:none;">Sorry! your provided pickup location is out of coverage area from service center, please select the nearby service center.</p>
													</div>
													<div class="col-md-4">
														<button type="button" class="btn btn-invert get-distance-price" data-installer-id="<?=$installer_id;?>" data-service-id="<?=$service_data_id;?>" data-city-id="1"><span>Confirm Location</span></button>
													</div>										    

												  </div>
												</div>
											<?php }?>
												</div>

												<?php }?>

											</div>
										</div>
									</div>
									
								</div>
							</div>	
						<?php } }?>
					</td>
				</tr>
				</tbody>
			</table>
			
		</td>
	</tr>
<style type="text/css">
	.addi-cart-services, .get-distance-price{
		padding: 5px 10px;
		text-transform: capitalize;
		min-width: auto;
	}
	#pic_price p { margin-bottom: 0px; display: inline-block; }
	#pic_price span { font-size: 14px;}
	.km-error{ color:red; }
	.help-txt{ display: inline-block; margin-left: 20px; }
	a.disabled {
	  pointer-events: none;
	  cursor: default;
	}
</style>
<script type="text/javascript">
	jQuery(document).ready(function(){
    	jQuery("[data-toggle=tooltip]").tooltip();	
    });
	/*Hide Show Additional Service*/
	jQuery(".service-list .title").click(function(){
		jQuery(this).closest('.service-list').toggleClass("ser-hide");
	});
	/*jQuery(".service-list .title").click(function(){
		if(jQuery(this).closest('.service-list').hasClass("ser-hide")) {
			jQuery(this).closest('.service-list').removeClass("ser-hide");
		} else {
			jQuery(this).closest('.service-list').addClass("ser-hide");
		}
	});*/
</script>
<?php
	} 
?>

