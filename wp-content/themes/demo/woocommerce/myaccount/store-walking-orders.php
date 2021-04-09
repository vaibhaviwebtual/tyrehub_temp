<?php
if ( !is_user_logged_in() )
 	{
      wp_redirect(get_permalink( get_option('woocommerce_myaccount_page_id') ).'/installer-login' );    
  	}
get_header();

?>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<div id="pageContent">
	<div class="container installer-home wishlist">
		
		<div class="procuct_purchase offline-order-page">			
			<div class="">
				<?php
				
				wc_print_notices();

				$user = wp_get_current_user();
				$role = ( array ) $user->roles;
				$current_user_role = $role[0];
				if($current_user_role != 'Installer') {
					do_action( 'woocommerce_account_navigation' );
				}
				//do_action( 'woocommerce_account_content' ); ?>
				<div class="woocommerce-MyAccount-content">
					
					<div class="product-container">
						        	<div class="search_filter">
        		<div class="deals-top">
                        <div class="col-md-12">
                           <h4 class="text-left">Store Walking Orders</h4>

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
                     <div class="form-group">
                        <input type="text" name="startdate" id="startdate" class="form-control startdate" placeholder="Start Date">
                        <input type="text" name="enddate" id="enddate" class="form-control enddate" placeholder="End Date">
                       
                    </div>
                </div>
                <input type = "submit" value = "Filter" class = "btn btn-success post_search_submit" />
                <input type = "submit" value = "Clear" class = "btn btn-success clear_search_submit" />
                 
            </div>
			</div>
          
            <input type="hidden" name="status_sel_type" id="status_sel_type" value="">
            <input type="hidden" name="status_sel_val" id="status_sel_val" value="">
        </div>
			<br class = "clear" />
			  <script>
              jQuery( function() {
                jQuery("#startdate").datepicker({
                    dateFormat: 'dd-mm-yy'
                });
                 jQuery("#enddate").datepicker({
                    dateFormat: 'dd-mm-yy'
                });
              } );
              </script>
			<script type="text/javascript">
			var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';

			function cvf_load_all_posts(page, th_name, th_sort, search_type){ 
				jQuery('#cover-spin').show(0);
					var post_data = {
						page: page,
						search: jQuery('.post_search_text').val(),
						th_name: th_name,
						startdate: jQuery('.startdate').val(),
                        enddate: jQuery('.enddate').val(),
						th_sort: th_sort,
						search_type: search_type
					};

				   jQuery('form.post-list input').val(JSON.stringify(post_data));
				   var data = {
						action: "store_walking_orders",
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
							var th_sort = jQuery(th_active).hasClass('DESC') ? 'ASC': 'DESC';
							// Search
							jQuery('body').on('click', '.post_search_submit', function(){
								var search_type = "";
								jQuery('#status_sel_type').val('');
								jQuery('#status_sel_val').val('');
								cvf_load_all_posts(1, th_name, th_sort, search_type);
							});
							jQuery('body').on('click', '.clear_search_submit', function(){
                                var search_type = "";
                                jQuery('#startdate').val('');
                                jQuery('#enddate').val('');
                                jQuery('#status').prop('selectedIndex',0);
                                cvf_load_all_posts(1, th_name, th_sort, search_type);
                            });

							// Pagination Clicks                   
							jQuery('.cvf_universal_container .cvf-universal-pagination li.active').live('click',function(){


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
								var current_sort = jQuery(th_active).hasClass('DESC') ? 'DESC': 'ASC';
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

					
			</script>
            <div class = "cvf_pag_loading no-padding">
                <div class = "cvf_universal_container">
                    <div class="cvf-universal-content"></div>
                </div>
            </div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
get_footer();
?>
