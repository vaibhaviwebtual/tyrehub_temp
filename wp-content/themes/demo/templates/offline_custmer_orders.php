<?php
/* Template Name: offline-franchise-orders */ 
$user = wp_get_current_user();
$role = $user->roles[0];
if ( !is_user_logged_in() && $role != 'Installer'){
     wp_redirect(get_permalink( get_option('woocommerce_myaccount_page_id')));
     die();
}
get_header(); ?>
<div id="pageContent" class="offline-order-page">
    <div class="container tracking-process">
		<div class = "row content">
        	<div class="search_filter">
        		<div class="deals-top">
                        <div class="col-md-12">
                           <h4 class="text-left">Offline Customer Orders</h4>
                         </div>
                        <div class="col-md-6">
                         
                        </div>
                    </div>
            <form class = "post-list">
                <input type = "hidden" value = "" />
            </form>
            <div class="col-md-12">
            <div class="search-box">
            	<div class="form-group">
            		 <select name="status" id="status" class="status_change_click">
                    <option value="">Select</option>
                    <option value="1">Pending</option>
                    <option value="2">Completed</option>
            </select>
            	</div>
                <div class="form-group">
                    <input type="text" class="form-control post_search_text" placeholder="Enter a keyword">
                </div>
                <input type = "submit" value = "Search" class = "btn btn-success post_search_submit" />
                 
            </div>
			</div>
          
            <input type="hidden" name="status_sel_type" id="status_sel_type" value="">
            <input type="hidden" name="status_sel_val" id="status_sel_val" value="">
        </div>
			<br class = "clear" />
			<script type="text/javascript">
			var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
			function cvf_load_all_posts(page, th_name, th_sort, search_type){ 
				jQuery('#cover-spin').show(0);
					var post_data = {
						page: page,
						search: jQuery('.post_search_text').val(),
						th_name: th_name,
						th_sort: th_sort,
						search_type: search_type
					};
				   jQuery('form.post-list input').val(JSON.stringify(post_data));
				   var data = {
						action: "demo_load_my_posts",
						data: JSON.parse(jQuery('form.post-list input').val())
					};
					jQuery.post(ajaxurl, data, function(response) {
						if(jQuery(".cvf_universal_container").html(response)){
							jQuery('.table-post-list th').each(function() {
								jQuery(this).find('span.glyphicon').remove();   
									if(jQuery(this).hasClass('active')){
										if(JSON.parse(jQuery('form.post-list input').val()).th_sort == 'DESC'){
											jQuery(this).append(' <span class="glyphicon glyphicon-chevron-down"></span>');
										} else {
											jQuery(this).append(' <span class="glyphicon glyphicon-chevron-up"></span>');
										}
									}
								});
								jQuery('#cover-spin').hide(); 
							}
						});
					}
					jQuery(document).ready(function(jQuery) { 
					var search_type = "";                                                              
							if(jQuery('form.post-list input').val()){
							// Submit hidden form input value to load previous page number
								data = JSON.parse(jQuery('form.post-list input').val());
								cvf_load_all_posts(data.page, data.th_name, data.th_sort, search_type);
							} else {
							// Load first page
								cvf_load_all_posts(1, 'billing_first_name', 'DESC', search_type);
							}
							var th_active = jQuery('.table-post-list th.active');
							var th_name = jQuery(th_active).attr('id');
							//var th_sort = jQuery(th_active).hasClass('DESC') ? 'ASC': 'DESC';
							var th_sort = 'DESC';
							// Search
							jQuery('body').on('click', '.post_search_submit', function(){
								var search_type = "";
								jQuery('#status_sel_type').val('');
								jQuery('#status_sel_val').val('');
								cvf_load_all_posts(1, th_name, th_sort, search_type);
							});
							// Pagination Clicks                   
						//	jQuery('.cvf_universal_container .cvf-universal-pagination li.active').on('click',function(){
							jQuery('body').on('click', '.cvf-universal-pagination.offvb li.active', function(){
									var status1 = jQuery('#status_sel_type').val();
									var status2 = jQuery('#status_sel_val').val();
									var search_type = "";
									if(status1 == "status" && status2 !== "")
									{
									   search_type = status1;
									   th_name = status2;
									}
									else{
									   search_type = '';
									   th_name = jQuery(th_active).attr('id');
									}
								var page = jQuery(this).attr('p');
								//var current_sort = jQuery(th_active).hasClass('DESC') ? 'DESC': 'ASC';
								var current_sort = 'DESC';
									cvf_load_all_posts(page, th_name, current_sort,search_type);
							});
							// Sorting Clicks
							jQuery('body').on('click', '.table-post-list th', function(e) {
							e.preventDefault();                            
								var th_name = jQuery(this).attr('id');
								if(th_name){
									var search_type = "";
									// Remove all TH tags with an "active" class
									if(jQuery('.table-post-list th').removeClass('active')) {
									// Set "active" class to the clicked TH tag
										jQuery(this).addClass('active');
									 }
									if(!jQuery(this).hasClass('DESC')){
										cvf_load_all_posts(1, th_name, 'DESC', search_type);
										jQuery(this).addClass('DESC');
									} else {
										cvf_load_all_posts(1, th_name, 'ASC', search_type);
										jQuery(this).removeClass('DESC');
									}
								}
							})
					});
					jQuery(document).ready(function(){
							jQuery(".status_change_click").change(function(){
								var sel = jQuery(this).val();
								jQuery('form.post-list input').val('');
								jQuery('#status_sel_type').val('status');
								jQuery('#status_sel_val').val(sel);
								var th_active = jQuery('.table-post-list th.active');
								var th_name = sel;
								var search_type = "status";
								var th_sort = jQuery(th_active).hasClass('DESC') ? 'ASC': 'DESC';
								var th_sort ='DESC';
								cvf_load_all_posts(1, th_name, th_sort, search_type);
							});
					});
		jQuery(document).ready(function(){
			jQuery('body').on('click', '.ord-delete', function(e) {
				e.preventDefault();
				jQuery('#DeleteConfirm').modal('show');				 
					rowdelete=jQuery(this);                           
					var order_id = jQuery(this).attr('data-order');
					jQuery('#delete_id').val(order_id);
					jQuery('#rowdelete').val(rowdelete);
					
				/*jQuery('#cover-spin').show(); 
					e.preventDefault(); 
					rowdelete=jQuery(this);                           
					var order_id = jQuery(this).attr('data-order');
					var post_data = {
						order_id: order_id
					};
				   var data = {
						action: "offline_order_delete",
						data: JSON.parse(JSON.stringify(post_data))
					};
					jQuery.post(ajaxurl, data, function(response) {
						jQuery(rowdelete).closest('tr').remove();
						jQuery('#cover-spin').hide(); 
					});*/
				
				});
			jQuery('body').on('click', '.yes-deleted', function(e) {
				jQuery('#cover-spin').show(); 
					e.preventDefault(); 
					//rowdelete=jQuery(this);                           
					var rowdelete = jQuery('#rowdelete').val();
					var order_id = jQuery('#delete_id').val();
					var deleted_reason = jQuery('#deleted_reason').val();
					var post_data = {
						order_id: order_id,
						deleted_reason: deleted_reason
					};
				   var data = {
						action: "offline_order_delete",
						data: JSON.parse(JSON.stringify(post_data))
					};
					jQuery.post(ajaxurl, data, function(response) {
						jQuery('#odr'+order_id).remove();
						jQuery('#DeleteConfirm').modal('hide');
						jQuery('#cover-spin').hide(); 
					});
				
				});
		});
			</script>
            <div class = "cvf_pag_loading no-padding">
                <div class = "cvf_universal_container">
                    <div class="cvf-universal-content"></div>
                </div>
            </div>
        </div>
   </div>
</div>
<!-- Modal -->
<div class="modal fade" id="DeleteConfirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="DeleteConfirm">Order Delete Confirm</h5>
        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button> -->
      </div>
      <div class="modal-body">
      	<textarea id="deleted_reason" name="deleted_reason" placeholder="Delete Reason"></textarea>
      	<input type="hidden" name="delete_id" id="delete_id" value="">
      	<input type="hidden" name="rowdelete" id="rowdelete" value="">
      	
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary yes-deleted">Yes Delete</button>
      </div>
    </div>
  </div>
</div>

<div id="carDetails" class="modal fade" role="dialog">
              <div class="modal-dialog">

                <!-- Modal content-->
            <div class="modal-content">
                  <div class="modal-header">
                    <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                <h4 class="modal-title">Please enter car/bike details</h4>
            </div>
            <div class="modal-body">
                <form id="tab1-form" class="vehicle_details offline-car-details">
                    <div class="row">
                    	<div class="col-md-12">Please fill out the car detail for which you are installing this Tyre,  the provided car detail will be registered for Tyre Guarantee and warranty purpose.</div>
                    	<div class="col-md-12"></div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="select-wrapper">
                                	 <label><strong>Make</strong></label>
                                    <select name="select-car-cmp" class="input-custom select-car-cmp" required>
                                        <option value="" disabled selected="">Make</option>
                                    
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
                                    <label><strong>Car/Bike Number</strong></label>
                                    <input type="text" class="input-custom" name="car_number" id="car_number" value="" placeholder="" maxlength="12" size="12">
                                    
                                    
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
                   	<div id="tyre_serial_number">
                   		
                   	</div>
                   	 <?php 
                       		$j=1;
                            for ($i=0; $i < $serTyreQty; $i++)  {?>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="" class="col-form-label"><strong>Tyre <?=$j;?> Serial Number</strong></label>
                                    <input type="text" class="form-control input-custom serial_number" name="serial_number[]" id="serial_number_<?=$i?>" placeholder="Serial Number" maxlength="4" size="4" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
                                </div>
                            </div>

                            <?php $j++; } ?>
                   </div>

                  </div>
                  <div class="modal-footer">
                  	<input type="hidden" name="user_id" value="<?=$row->customer_id;?>" id="user_id">
                    <input type="hidden" name="order_id" value="<?php echo $order_number; ?>" id="order_id">
                    <input type="hidden" name="franchise_id" value="<?php echo $franchise->installer_data_id; ?>" id="franchise_id">
                     <input type="hidden" name="order_status" value="" id="order_status">
                      <input type="hidden" name="product_id" value="" id="product_id" value="">
                      <input type="hidden" name="service_type_id" value="" id="service_type_id" value="">

                    <button type="button" class="btn btn-default dcclose-btn" id="offline_car_details_save_status_change"><span>Save</span></button>
                    <button type="button" id="car_details_skip" class="btn btn-default dcclose-btn" data-dismiss="modal"><span>Skip</span></button>
                  </div>
              </form>
                </div>

              </div>
            </div>
<?php get_footer(); ?>