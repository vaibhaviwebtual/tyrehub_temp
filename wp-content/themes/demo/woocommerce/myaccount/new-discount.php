<?php

if ( !is_user_logged_in() )
 	{
      wp_redirect(get_permalink( get_option('woocommerce_myaccount_page_id') ).'/installer-login' );    
  	}
get_header();

?>
<div id="pageContent">
	<div class="container installer service-request-page">
		
		<div class="woocommerce">
			<?php
			if ( ! defined( 'ABSPATH' ) ) {
				exit;
			}

			wc_print_notices();

			?>
			<!-- <div class="toggle-menu">
				<i class="fa fa-bars" aria-hidden="true"></i>
			</div> -->
			<?php
			$user = wp_get_current_user();
		   	$role = ( array ) $user->roles;
		   	$current_user_role = $role[0];
		   	if($current_user_role != 'Supplier')
		   	{
				//do_action( 'woocommerce_account_navigation' ); 
			}
			//	do_action( 'woocommerce_account_content' ); ?>
			
			<div class="woocommerce-MyAccount-content <?php if($current_user_role == 'Supplier'){echo 'supplier-account';} ?>" style="width: 100%;">
				<!--<h2>Add New Discount</h2>-->
			    
    		<span class="error-msg" style="color: red;"></span>
     		<div class="admin-url" hidden=""><?php echo admin_url('admin-ajax.php'); ?></div>
     		<?php $omnizzOption = get_option("omnizz_logo"); ?>
    			<form name="post" method="post" id="post" enctype="multipart/form-data">
        			<div id="poststuff" class="discount-rule">
            			<div id="post-body" class="metabox-holder">
                			<div id="">
								<div id="titlediv">
									<div class="inside">
									</div>
									<input type="hidden" id="samplepermalinknonce" name="samplepermalinknonce" value="58eea30609">
								</div>
					
						   
								<div class="postbox-container">
									<div class="card">
									  <div class="card-body">
										<h5 class="card-title">New Discount</h5>
										<div class="col-md-4">
											<input type="text" name="post_title" class="rule-name" size="30" value="" id="title" spellcheck="true" autocomplete="off" placeholder="Discount Title">
										</div>
										<div class="col-md-4">
											<input type="text" name="start_date" class="start-date" autocomplete="off" placeholder="Start Date">
										</div>
										<div class="col-md-4">
										   <input type="text" name="end_date" class="end-date" autocomplete="off" placeholder="End Date">
										</div>
									  </div>
									</div>
						
									<div class="card">
						  				<div class="card-body tyre-discount">
											<h5 class="card-title">Add Tyres Discount</h5>
											<div class="inner-card search-product">
											  <div class="inner-card-body">
													<div class="col-xs-4 col-sm-2 col-md-2">
														<h5 class="inner-card-title">Search Products : </h5>
													</div>
												  	<div class="col-xs-4 col-sm-2 col-md-2">
														<input type="text" name="width" class="width" placeholder="Width">
													</div>
													<div class="col-xs-4 col-sm-2 col-md-2">
														<input type="text" name="ratio" class="ratio" placeholder="Ration">
													</div>
													<div class="col-xs-4 col-sm-2 col-md-2">
														<input type="text" name="diameter" class="diameter" placeholder="Diameter">
													</div>
													<div class="col-xs-8 col-sm-2 col-md-2">
														<select class="select-category" name="category">
															<option value="">Select Tyre Brand</option>
															<?php 
															$taxonomy = 'product_cat';
															$terms=  get_terms($taxonomy);

															$terms_select="";
															foreach ($terms as $term)
															{?>
																<option value="<?=$term->name;?>" <?php if($term->name==$_POST['category']){ echo 'selected';}?>><?=$term->name;?></option>
															<?php 
															}
															?>
														</select>
													</div>
													<div class="col-xs-4 col-sm-2 col-md-2">
														<button class="search-btn" id="supplier_prodct_search" type="button">Search</button>
													</div>  
											  </div>
											</div>

											<div class="inner-card">
												<div class="inner-card-body">
													<div class="product-container">
														<div class="product-details">
															<h2>Search Product List</h2>
															<div class="header">
												                <div class="name"><strong>Name</strong></div>
												                 <div class="price"><strong>Tube</strong></div>
												                 <div class="price"><strong>Tyre</strong></div>
												                <div class="price"><strong>Total</strong></div>
												                <div class="add"><strong>Add</strong></div>
												                
												            </div>
												            <div class="product-details-list"></div>
														</div>
														<div class="selected-products">
															<h2>Selected Product List</h2>
															<div class="supplier-tbl-outer">
																<div class="bulk-price-update">
																	<legend><label><input class="" type="checkbox" name="colorRadio" value="bulk-box"> Bulk Discount</legend></label>
																	<script type="text/javascript">
																	jQuery(document).ready(function(){
																		jQuery('input[type="checkbox"]').click(function(){
																			var inputValue = jQuery(this).attr("value");
																			var targetBox = jQuery("." + inputValue);
																			jQuery(targetBox).toggle();
																		});
																	});
																	</script>
																	<table class="bulk-box">
																		<tr>
																			<td class="price-sel">
																				<label>Tyre Discount by</label>
																				<select class="custom-select custom-select-sm select-update-by" name="select-update-by" id="select-update-by">
																					<option value="productvalue">By Value</option>
																					<option value="percentage">By Percentage</option>
																				</select>
																			</td>
																			<td class="discount-amount">
																				<input type="number" name="new_tub_price" class="bulk-tube-price" placeholder="Amount">
																			</td>
																		</tr>
																	</table>
																</div>
															</div>
															<div class="header">
																<div class="remove"><strong>Remove</strong></div>
																<div class="name"><strong>Name</strong></div>
																<div class="price"><strong>Tube</strong></div>
																<div class="price"><strong>Tyre</strong></div>
																<div class="price"><strong>Total</strong></div>
																<div class="amount"><strong>Discount</strong></div>
																<div class="status"><strong>On/Off</strong></div>
															</div>
														</div>
													</div>
													<div class="discount-container-bottom">
														<input type="submit" name="publish" id="publish" class="save_discount" value="Publish" disabled="">
														<a href="<?=site_url('/my-account/deals-discount/');?>" class="back-btn">Back</a>
													</div>
												</div>
											</div>
									  </div>
									</div>
                   				</div>
                			</div>
            			</div>
        			</div>
    			</form>

			</div>
				
		
			</div>
		</div>
	</div>
</div>

<?php
get_footer();
?>
