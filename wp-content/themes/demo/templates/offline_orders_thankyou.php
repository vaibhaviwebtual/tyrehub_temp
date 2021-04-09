<?php
 /* Template Name: offline thank you page */
$user = wp_get_current_user();
$role = $user->roles[0];
if ( !is_user_logged_in() && $role != 'Installer'){
     wp_redirect(get_permalink( get_option('woocommerce_myaccount_page_id')));
     die();
}
get_header(); 	
?>
<style>
     .inline{
         display: inline-block;
         float: right;
         margin: 20px 0px;
     }
     input, button{
         height: 34px;
     }
    </style>
<div id="pageContent" class="">
	<div class="container">
		<div class="woocommerce">
			<div class="woocommerce-order">
				<?php
					global $woocommerce , $wpdb;
					$order_number=base64_decode($_GET['order_id']);
					$limit =1;
					global $wpdb;
					$user_id = get_current_user_id();
					$SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
					$franchise=$wpdb->get_row($SQL);
					$row = $wpdb->get_row("SELECT *, foi.order_item_id as itemid FROM wp_franchises_order as fo,wp_franchise_order_items as foi  where fo.order_id = foi.order_id AND fo.order_number='$order_number' AND franchise_id = '$franchise->installer_data_id' ORDER BY fo.order_id DESC LIMIT 0,$limit");
					$od_meta_id = $row->itemid;
					$od_order_id = $row->order_id;
					$total = $row->total;
						$SQL="SELECT * FROM wp_franchises_payment_method WHERE id='$row->payment_method'";
						$payment=$wpdb->get_row($SQL);
			$payment_title=$payment->payment_method;
					$order_meta_product = $wpdb->get_results("SELECT * FROM wp_franchise_order_items as oi, wp_francise_order_itemmeta as om where oi.order_id = '$od_order_id' and om.order_item_id = oi.order_item_id");
					$p_count = count($order_meta_product);
					$product_array = array();
					$qty_array = array();
					foreach ($order_meta_product as $key => $value) {
						if($value->meta_key == '_product_id')
						{
							$product_array[$value->order_item_id]['product_id'] = $value->meta_value;
						}
						if($value->meta_key == '_qty')
						{
							$product_array[$value->order_item_id]['qty'] = $value->meta_value;
						}
						if($value->meta_key == '_line_subtotal')
						{
							$product_array[$value->order_item_id]['_line_subtotal'] = $value->meta_value;
						}
						if($value->meta_key == '_line_total')
						{
							$product_array[$value->order_item_id]['_line_total'] = $value->meta_value;
						}
						if($value->meta_key == '_sgst')
						{
							$product_array[$value->order_item_id]['_sgst'] = $value->meta_value;
						}
						if($value->meta_key == '_cgst')
						{
							$product_array[$value->order_item_id]['_cgst'] = $value->meta_value;
						}
					}
					$product_array = array_values($product_array);
				$sku = 'service_voucher';
        $service_voucher_prd = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );
				?>
				<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received">Thank you. Your order has been received.</p>
	            <ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">
	                <li class="woocommerce-order-overview__order order">
	                    <?php _e( 'Order number:', 'woocommerce' ); ?>
	                    <strong><?php echo $row->order_number; ?></strong>
	                </li>
	                <li class="woocommerce-order-overview__date date">
	                    <?php _e( 'Date:', 'woocommerce' ); ?>
	                    <strong><?php echo date('d-m-Y g:i a',strtotime($row->date_completed)); ?></strong>
	                </li>
					<li class="woocommerce-order-overview__total total">
	                    <?php _e( 'Total:', 'woocommerce' ); ?>
	                    <strong><?php echo $row->total; ?></strong>
	                </li>
					<li class="woocommerce-order-overview__payment-method method">
	                        <?php _e( 'Payment method:', 'woocommerce' ); ?>
							<strong><?php echo $payment_title; ?></strong>
	                </li>
				</ul>
			</div>
			<section class="woocommerce-order-details">
				<h2 class="woocommerce-order-details__title"><?php _e( 'Order details', 'woocommerce' ); ?></h2>
				<table class="woocommerce-table woocommerce-table--order-details shop_table order_details">
					<thead>
						<tr>
							<th class="woocommerce-table__product-name product-name"><?php _e( 'Product', 'woocommerce' ); ?></th>
							<th class="woocommerce-table__product-table product-total"><?php _e( 'Total', 'woocommerce' ); ?></th>
						</tr>
						<tbody>
						<?php	
						/*echo '<pre>';
						print_r($product_array);
						echo '</pre>';*/
						foreach ($product_array as $key => $value) {
							$product_id = $value['product_id'];
							$vehicle_tyep=get_post_meta($product_id,'attribute_pa_vehicle-type',true);
							$product_array= array(get_option("car_wash"),get_option("balancing_alignment"));
							if(in_array($product_id,$product_array)){
								//$product = wc_get_product($product_id);
								if($product_id==get_option("balancing_alignment")){
									 $variation_des = 'Balancing & Alignment';
									 $services_id=$product_id;
								}else{
									 $variation_des = 'Car Wash';

								}
							}else{
								$product_variation = wc_get_product($product_id);
								$variation_des = $product_variation->get_description();
								 $serTyreQty = $serTyreQty + $value['qty'];
								 $services_id=0;
							}
				            //$product_variation = wc_get_product( $product_id );
				           
							?>
							<tr class="woocommerce-table__line-item order_item">
								<td class="woocommerce-table__product-name product-name">
			<a href=""><?php echo $variation_des; ?><strong class="product-quantity">× <?php echo $value['qty'];  ?></strong>
								</td>
								<td class="woocommerce-table__product-total product-total">
									<?php echo wc_price($value['_line_total']); ?>
								</td>
							</tr>
						 <?php $subtotal= $subtotal + $value['_line_subtotal'];
						 	 $SQL1="SELECT * FROM th_franchise_cart_item_services WHERE product_id='$product_id' AND order_id='".$order_number."'";
							 $services=$wpdb->get_results($SQL1);
							 foreach ($services as $key => $service) {
							 	if((count($services)-1)==$key){
								$border='border-bottom: 3px solid #3E4796';
								}else{
									$border='';
								}
								?>
								<tr class="woocommerce-table__line-item order_item">
									<td class="woocommerce-table__product-name product-name" style="padding-left: 30px; <?=$border?>">
									<a href=""><?php echo $service->service_name; ?></strong>
									</td>
									<td class="woocommerce-table__product-total product-total" style="<?=$border?>">
										<?php echo wc_price($service->rate); ?>
									</td>
								</tr>
							 <?php $asubtotal= $asubtotal + $service->rate;
							 $services_id=0;

							} 

							if($service_voucher_prd==$product_id){
                        		$servicesPopup='notopen';
		                    }else{
		                         $withTyreServicesPopup='open';
		                    }

						} ?>
					
						</tbody>
					</thead>
					<tfoot>
							<tr>
								<th scope="row">Subtotal:</th>
								<td><?php echo wc_price($row->sub_total); ?> </td>
							</tr>
							<tr hidden="">
								<th scope="row">Service Charges:</th>
								<td>
									<?php echo wc_price($asubtotal); ?>
								</td>
							</tr>
							<tr>
								<th scope="row">Payment method:</th>
								<td><?php echo $payment_title; ?></td>
							</tr>
							<tr>
								<th scope="row">Total:</th>
								<td>
									<?php echo wc_price($row->total); //echo wc_price($subtotal+$asubtotal); ?>
									</td>
							</tr>
					</tfoot>
				</table>
				<div class="go-to-home">
					<?php if($row->status == 1 || $row->status == 2) { ?>
					<span id="thankyou_part1">
					<a class="btn btn-invert" href="<?php echo get_site_url(); ?>"><span>Continue Shopping</span></a>
					<a href="<?=site_url();?>/pdf-view/?document_type=offline-invoice&&order_ids=<?php echo $od_order_id; ?>&service_id=<?php echo $od_order_id; ?>" class="btn btn-invert"   style="margin: 10px;"><span><i class="fa fa-file-pdf-o" aria-hidden="true"></i>&nbsp;Download invoice (PDF)</span></a>
					<!-- <a href="<?php echo admin_url(); ?>/admin-ajax.php?action=offline_order_pdf&document_type=offline-invoice&order_ids=<?php echo $od_order_id; ?>&service_id=<?php echo $od_order_id; ?>&_wpnonce=04e74a5779" class="btn btn-invert" style="margin: 10px;" target="_blank" ><span><i class="fa fa-file-pdf-o" aria-hidden="true"></i>&nbsp;Download invoice (PDF)</span></a> -->
					

					</span>
				<?php } ?>
					<?php if($row->status == 0) { ?>
						<a class="btn btn-invert" style="margin: 10px;" order-status="1" id="offline_order_pending"><span><i class="fa fa-clock-o" aria-hidden="true"></i> Pending</span></a>

						<?php  if($variation_des=='Car Wash'){?>
							<a class="btn btn-invert" style="margin: 10px;" order-status="2" id="carwash_offline_order_complated_without_popup"><span><i class="fa fa-clock-o" aria-hidden="true"></i> Completed</span></a>
						<?php }else{?>
						<a class="btn btn-invert" style="margin: 10px;" order-status="2" id="offline_order_complated"><span><i class="fa fa-check" aria-hidden="true"></i>
    Completed</span></a>
    					<?php }?>
						<input type="hidden" name="th_orderid" id="th_orderid" value="<?php echo $od_order_id; ?>">
				<?php } ?>
				</div>
			</section>
		</div>
	</div>
</div>
		<?php if($withTyreServicesPopup=='open'){?>
			<?php 
                if($vehicle_tyep=='two-wheeler'){
                    $title='Please enter bike details';
                    $numbtitle='Bike Number';
                    $vehicletype=2;
					$two_title= 'two/three wheeler';
                }else{
                    $title='Please enter car details';
                    $numbtitle='Car Number';
                    $vehicletype=1;
					$two_title= 'car';
                }
            ?>
            <div id="carDetails" class="modal fade" role="dialog">
              <div class="modal-dialog">

                <!-- Modal content-->
            <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?=$title?></h4>
            </div>
            <div class="modal-body">
                <form id="tab1-form" class="vehicle_details offline-car-details">
                    <div class="row">
                    	<div class="col-md-12">Please fill out the <?=$two_title?> detail for which you are installing this Tyre,  the provided car detail will be registered for Tyre Guarantee and warranty purpose.</div>
                    	<div class="col-md-12"></div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="select-wrapper">
                                	 <label><strong>Make</strong></label>
                                    <select name="select-car-cmp" class="input-custom select-car-cmp" required>
                                        <option value="" disabled selected="">Make</option>
                                    <?php
                                    if(!isset($_GET['modifysearch'])) {
                                        unset($_SESSION['make_id']);
                                        unset($_SESSION['model_id']);
                                        unset($_SESSION['sub_model_id']);
                                    }
                                    global $wpdb , $woocommerce;
                                    $make_data = $wpdb->get_results("SELECT * FROM th_make where vehicle_type = '$vehicletype' AND status =1 order by make_name asc");
    
                                    foreach ($make_data as $data) {
                                        $make_id = $data->make_id;
                                        $make_name = $data->make_name;
                                    ?>    
                                        <option value="<?php echo $make_id; ?>" <?php if(isset($_SESSION['make_id']) && $_SESSION['make_id'] == $make_id){ echo 'selected'; }?>><?php echo $make_name; ?></option>
                                    <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="select-wrapper year-wrapper" >
                                	 <label><strong>Model</strong></label>
                                    <select disabled="disabled" name="select1" class="input-custom select-model" required>
                                        <option value="" selected="">Model</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="select-wrapper model-wrapper">
                                	 <label><strong>Sub Model</strong></label>
                                    <select name="select3" disabled="disabled" class="input-custom select-sub-model" required>
                                        <option value="" disabled selected>Sub Model</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="select-wrapper input-wrapper model-wrapper">
                                    <label><strong><?=$numbtitle;?></strong></label>
                                    <input type="text" class="input-custom" name="car_number" id="car_number" value="" placeholder="" maxlength="12" size="12">
                                    <input type="hidden" name="user_id" value="<?=$row->customer_id;?>" id="user_id">
                                    <input type="hidden" name="order_id" value="<?php echo $order_number; ?>" id="order_id">
                                    <input type="hidden" name="franchise_id" value="<?php echo $franchise->installer_data_id; ?>" id="franchise_id">
                                    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" id="product_id">
                                    <input type="hidden" name="services_id" value="<?php echo $services_id; ?>" id="services_id">
                                    
                                </div>
                            </div>
                        </div>
                         <div class="col-md-6">
                            <div class="form-group">
                                <div class="input-wrapper model-wrapper">
                                    <label><strong>Odo Meter(KM)</strong></label>
                                    <input type="text" class="input-custom" name="odo_meter" id="odo_meter" value="<?=$vehicle_details->odo_meter?>" placeholder="" maxlength="7" size="7" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
                                   
                                    
                                </div>
                            </div>
                        </div>
                       

                    </div>
                   <div class="row">
                   	 <?php 
                       		if($serTyreQty){
                       		$j=1;
                            for ($i=0; $i < $serTyreQty; $i++)  {?>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="" class="col-form-label"><strong>Tyre <?=$j;?> Serial Number</strong></label>
                                    <input type="text" class="form-control input-custom serial_number" name="serial_number[]" id="serial_number_<?=$i?>" placeholder="Serial Number" maxlength="4" size="4" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
                                </div>
                            </div>

                            <?php $j++; } 
                        } ?>
                   </div>

                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default dcclose-btn" id="offline_car_details_save"><span>Save</span></button>
                    <button type="button" class="btn btn-default dcclose-btn" data-dismiss="modal"><span>Skip</span></button>
                  </div>
              </form>
                </div>

              </div>
            </div>
           <?php }?>
<?php
get_footer();
?>