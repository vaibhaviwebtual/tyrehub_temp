	<?php
											if(empty($cart_item['custom_data']['vehicle_type'])){ ?>
											<script type="text/javascript">		               	
												setTimeout(function(){
													//jQuery('#vehicle_type').modal('show');
													 jQuery('#vehicle_type').modal({
														backdrop: 'static',
														keyboard: false
													});
												},1000);
											</script>

											<div class="modal fade" id="vehicle_type" role="dialog">
											<input type="hidden" name="admin-url" id="admin-url" value="<?=admin_url('admin-ajax.php' );?>">
											<div class="modal-dialog">  
												<!-- Modal content-->
												<div class="modal-content"  style="pointer-events: auto;">
													 <div class="modal-header">        
													</div>
													<div class="modal-body">
														<div class="vehicle-type-tab">
														<ul class="nav nav-tabs">
															<li class="active"><a data-toggle="tab" href="#home">Four Wheeler</a></li>
															<li><a data-toggle="tab" href="#menu1">Two/Three Wheeler</a></li>
														</ul>	
													  <div class="tab-content">
														<div id="home" class="tab-pane fade in active">
														  <?php
														  global $wpdb; 
														  $row = $wpdb->get_results("SELECT * FROM th_vehicle_type WHERE wheel_type = '2'");
															foreach ($row as $data) {
														?>      
											<div class="inputGroup vehicle_type">
												<input id="<?php echo 'vehicle'.$data->vehicle_type ?>" name="vehicle_type" type="radio" value="<?php echo $data->vehicle_id ?>" />
												<label for="<?php echo 'vehicle'.$data->vehicle_type ?>">

													<?php 
														  if($data->vehicle_type == 'Hatchback'){?>
															 <img class="car-img" src="<?php echo bloginfo('template_directory');?>/images/vehicle_type/hatchback-car-img.png" >
														  <?php }
														  elseif($data->vehicle_type == 'Sedan'){?>
															 <img class="car-img" src="<?php echo bloginfo('template_directory');?>/images/vehicle_type/sedan-car-img.png">
														  <?php } 
														  elseif($data->vehicle_type == 'Suv'){?>
															 <img class="car-img" src="<?php echo bloginfo('template_directory');?>/images/vehicle_type/suv-car-img.png" >
														  <?php }
														  elseif($data->vehicle_type == 'Premium Car'){?>
															 <img class="" style="width: 50px;" src="<?php echo bloginfo('template_directory');?>/images/vehicle_type/premium-car.png" >


														  <?php } ?>
														  <?php echo $data->vehicle_type ?>		
												  </label>
											</div>                          
											<?php } //foreach ?> 
														</div>
														<div id="menu1" class="tab-pane fade">
														  <?php
														  global $wpdb; 
														  $row = $wpdb->get_results("SELECT * FROM th_vehicle_type WHERE wheel_type = '1'");
															foreach ($row as $data) {
											?>      
											<div class="inputGroup vehicle_type">
												<input id="<?php echo 'vehicle'.$data->vehicle_type ?>" name="vehicle_type" type="radio" value="<?php echo $data->vehicle_id ?>" />
												<label for="<?php echo 'vehicle'.$data->vehicle_type ?>">

													<?php 
														  if($data->vehicle_type == 'Bike'){?>
															 <img class="car-img" src="<?php echo bloginfo('template_directory');?>/images/vehicle_type/bike.png" >
														  <?php }
														  elseif($data->vehicle_type == 'Activa/Scooter'){?>
															 <img class="car-img" src="<?php echo bloginfo('template_directory');?>/images/vehicle_type/activa.png" >
														  <?php }
														  elseif($data->vehicle_type == 'Autorickshaw'){?>
															 <img class="car-img" src="<?php echo bloginfo('template_directory');?>/images/vehicle_type/auto-rickshaw.png" >
														  <?php }
														  elseif($data->vehicle_type == 'Premium Bike'){?>
															 <img class="" style="width: 50px;" src="<?php echo bloginfo('template_directory');?>/images/vehicle_type/premium-bike.png" >

														  <?php } 
														  ?>
														  <?php echo $data->vehicle_type ?>		
												  </label>
											</div>                          
											<?php } //foreach ?>
														</div>

													  </div>			    			
													</div>
													</div>
													<div class="modal-footer">
														<button class="btn btn-invert" id="vehicle-type-add" type="button"><span>Select</span></button>
														
													</div>
												</div>
											</div>
										</div>

											<?php }?>