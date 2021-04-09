<?php
if ( !is_user_logged_in() )
{
  wp_redirect(get_permalink( get_option('woocommerce_myaccount_page_id') ).'/installer-login' );    
}
get_header();

global $wpdb;		
		$SQL1="SELECT * FROM th_supplier_data WHERE user_id='".get_current_user_id()."'";
		$supplierData=$wpdb->get_results($SQL1);
		$supplier_id=$supplierData[0]->supplier_data_id;
		$all_product_access=$supplierData[0]->all_product_access;

		$width =$_GET['width'];
		$width=str_replace(".","-",$width);
		$ratio = $_GET['ratio'];
		$diameter =$_GET['diameter'];
		$name = $_GET['category'];
		$status = $_GET['status'];
		$visiblity = 'yes';
		$name = strtolower($name);

	  $SQL="SELECT   sp.*,sd.business_name FROM th_supplier_products_list AS sp ";

	  $SQL.=" LEFT JOIN wp_posts ON ( wp_posts.ID = sp.product_id )";

	  if($width){
			$SQL.=" LEFT JOIN wp_postmeta ON ( wp_posts.ID = wp_postmeta.post_id ) ";
		}
	  if($diameter){
	  $SQL.=" LEFT JOIN wp_postmeta AS mt1 ON ( wp_posts.ID = mt1.post_id )";
	  }
	  
	  if($ratio){  
	  $SQL.=" LEFT JOIN wp_postmeta AS mt2 ON ( wp_posts.ID = mt2.post_id )";
	  } 
	 
	 if($name){
		$SQL.=" LEFT JOIN wp_postmeta AS mt3 ON ( wp_posts.ID = mt3.post_id )";
	 } 

	 $SQL.=" LEFT JOIN wp_postmeta AS mt4 ON ( wp_posts.ID = mt4.post_id )"; 

	 

	 $SQL.=" LEFT JOIN th_supplier_data AS sd ON (sp.supplier_id = sd.supplier_data_id)"; 
	   
	$WHERE="WHERE 1=1 "; 
	
	

	if($width){ 
		$WHERE.=" AND ( wp_postmeta.meta_key = 'attribute_pa_width' AND wp_postmeta.meta_value IN ('".$width."') )";
		}

	if($diameter){
	$WHERE.=" AND ( mt1.meta_key = 'attribute_pa_diameter' AND mt1.meta_value IN ('".$diameter."') )";
	}

	if($ratio){ 
		$WHERE.=" AND ( mt2.meta_key = 'attribute_pa_ratio' AND mt2.meta_value IN ('".$ratio."') ) ";
	}
	
	if($name){
		$WHERE.=" AND ( mt3.meta_key = 'attribute_pa_brand' AND mt3.meta_value IN ('".$name."') )";
	}

	$WHERE.=" AND ( mt4.meta_key = 'tyrehub_visible' AND mt4.meta_value IN ('yes','contact-us')) ";
	 if($supplier_id && $all_product_access==0){
		$WHERE.=" AND (sp.supplier_id =".$supplier_id.")";
		}
	if($status=='pending'){
		$WHERE.=" AND (sp.status =2)";
	}
		
	$WHERE.=" AND wp_posts.post_type = 'product_variation' AND (wp_posts.post_status = 'publish' OR wp_posts.post_status = 'wc-deltoinstaller' OR wp_posts.post_status = 'wc-customprocess' OR wp_posts.post_status = 'future' OR wp_posts.post_status = 'draft' OR wp_posts.post_status = 'pending' OR wp_posts.post_status = 'private') GROUP BY wp_posts.ID ORDER BY sp.updated_date desc";

	$SQL=$SQL.$WHERE;

	$supplierProductData=$wpdb->get_results($SQL);
	
	

?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">

<style type="text/css">
    .modal-confirm {        
        color: #636363;
        width: 550px;
    }
    .modal-confirm .modal-content {
        padding: 20px;
        border-radius: 5px;
        border: none;
        text-align: center;
        font-size: 14px;
    }
    .modal-confirm .modal-header {
        border-bottom: none;   
        position: relative;
    }
    .modal-confirm h4 {
        text-align: center;
        font-size: 26px;
        margin: 30px 0 -10px;
    }
    .modal-confirm .close {
        position: absolute;
        top: -5px;
        right: -2px;
    }
    .modal-confirm .modal-body {
        color: #999;
    }
    .modal-confirm .modal-footer {
        border: none;
        text-align: center;     
        border-radius: 5px;
        font-size: 13px;
        /*padding: 10px 15px 25px;*/
    }
    .modal-confirm .modal-footer a {
        color: #999;
    }       
    .modal-confirm .icon-box {
        width: 80px;
        height: 80px;
        margin: 0 auto;
        border-radius: 50%;
        z-index: 9;
        text-align: center;
        border: 3px solid #f15e5e;
    }
    .modal-confirm .icon-box i {
        color: #f15e5e;
        font-size: 46px;
        display: inline-block;
        margin-top: 13px;
    }
    .modal-confirm .btn {
        color: #fff;
        border-radius: 4px;
        background: #60c7c1;
        text-decoration: none;
        transition: all 0.4s;
        line-height: normal;
        min-width: 120px;
        border: none;
        min-height: 40px;
        border-radius: 3px;
        margin: 0 5px;
        outline: none !important;
    }
    .modal-confirm .btn-info {
        background: #c1c1c1;
    }
    .modal-confirm .btn-info:hover, .modal-confirm .btn-info:focus {
        background: #a8a8a8;
    }
    .modal-confirm .btn-danger {
        background: #f15e5e;
    }
    .modal-confirm .btn-danger:hover, .modal-confirm .btn-danger:focus {
        background: #ee3535;
    }
    .trigger-btn {
        display: inline-block;
        margin: 100px auto;
    }
	
	.card {
		position: relative;
		display: inline-block;
		border: 1px solid rgba(55, 62, 132, 1);
		margin-bottom: 10px;
		width: 100%;
		margin-top: 20px;
	}
	.card-body {
		display: inline-block;
		width: 100%;
		vertical-align: top;
		padding: 30px 15px 15px;
		font-weight: 400;
		box-shadow: 0 2px 5px 0 rgba(55, 62, 132, 0.16), 0 2px 10px 0 rgba(55, 62, 132, .12);
	}
	.card-title {
		display: inline-block;
		margin-top: -40px;
		background-color: #fff;
		position: absolute;
		padding: 0px 5px;
		left: 10px;
		text-transform: capitalize;
		color: #373e84;
	}
	.inner-card {
		position: relative;
		display: inline-block;
		width: 100%;
	}
	.inner-card-body {
		display: inline-block;
		width: 100%;
		vertical-align: top;
		padding: 15px 0px 10px;
	}
	.inner-card-title {
		display: inline-block;
		position: absolute;
		padding: 0px 5px;
		left: 10px;
		text-transform: capitalize;
		color: #373e84;
		line-height: 35px;
	}
	.bulk-box-footer {
		margin-top: 20px;
		margin-bottom: 0px;
	}

	.supplier-prd-search input[type="text"]::placeholder,
	table.bulk-box input[type="number"]::placeholder {
	  color: #373e84;
	  opacity: 1;
	}
	.supplier-prd-search input[type="text"]:-ms-input-placeholder,
	table.bulk-box input[type="number"]:-ms-input-placeholder {
	  color: #373e84;
	}
	.supplier-prd-search input[type="text"]::-ms-input-placeholder,
	table.bulk-box input[type="number"]::-ms-input-placeholder {
	  color: #373e84;
	}
	.modal-body{ padding: 0px; }
</style>
<div id="pageContent">
	<div class="container installer service-request-page">
		<div class="woocommerce">
			<?php
			if ( ! defined( 'ABSPATH' ) ) {
				exit;
			}
			wc_print_notices();
			?>
			<?php
			$user = wp_get_current_user();
		   	$role = ( array ) $user->roles;
		   	$current_user_role = $role[0];
		   	if($current_user_role != 'Supplier')
		   	{
				//do_action( 'woocommerce_account_navigation' ); 
			}
		    // do_action( 'woocommerce_account_content' ); ?>
			
			<div class="woocommerce-MyAccount-content <?php if($current_user_role == 'Supplier'){echo 'supplier-account';} ?>" style="width: 100%;">
				<?php
				if(isset($_GET['service_id']) || isset($_GET['voucher_id']))
				{
					//include_once('templates/details-page.php');
				}
				else
				{
				?>
				
				<div class="card">
					<div class="card-body tyre-discount">
						<h5 class="card-title">Tube &amp; Tyre Price Change</h5>
						<form action="/my-account/tyre-products/" method="get">
						<div class="inner-card supplier-prd-search">
							<div class="inner-card-body">
								<div class="col-xs-4 col-sm-2 col-md-2">
									<h5 class="inner-card-title">Search Products : </h5>
								</div>
								<div class="col-xs-4 col-sm-2 col-md-2">
									<input type="text" name="width" class="select-width" value="<?=$_GET['width'];?>" placeholder="Width">
								</div>
								<div class="col-xs-4 col-sm-2 col-md-2">
									<input type="text" name="ratio" class="select-ratio" value="<?=$_GET['ratio'];?>" placeholder="Ration">
								</div>
								<div class="col-xs-4 col-sm-2 col-md-2">
									<input type="text" name="diameter" class="select-diameter" value="<?=$_GET['diameter'];?>" placeholder="Diameter">
								</div>
								<div class="col-xs-8 col-sm-4 col-md-2">
									<select class="select-category" name="category">
										<option value="">Select Tyre Brand</option>
										<?php 
										$taxonomy = 'product_cat';
										$terms=  get_terms($taxonomy);

										$terms_select="";
										foreach ($terms as $term)
										{?>
											<option value="<?=$term->name;?>" <?php if($term->name==$_GET['category']){ echo 'selected';}?>><?=$term->name;?></option>

										<?php 
										}
										?>
									</select>
								</div>
								<div class="col-xs-4 col-sm-2 col-md-2">
									<button class="search-btn" id="supplier_prodct_search">Search</button>
								</div>
							</div>
						</div>
						</form>
						<div class="message-block" style="width: 100%; float: left;"></div>
						
						<div class="supplier-tbl-outer">
							<div class="bulk-price-update">
								<legend><label><input class="" type="checkbox" name="colorRadio" value="bulk-box"> Bulk Price Update</legend></label>
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
											<label>Update Price by</label>
											<select class="custom-select custom-select-sm select-update-by" name="select-update-by" id="select-update-by">
												<option value="productvalue">By Value</option>			                        
												<option value="percentage">By Percentage</option>
											</select>
										</td>
										<td>
											<div class="update-tube-price" style="display: block;">
												<!--<label>Tube Price</label>-->
												<input type="number" name="new_tub_price" class="bulk-tube-price" placeholder="Amount">
											</div>
										</td>
										<td>
											<div class="update-tyre-price" style="display: block;">
												<!--<label>Tyre Price</label>-->
												<input type="number" name="new_tyre_price" class="bulk-tyre-price" placeholder="Amount">      
											</div>
										</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td><!-- <button class="search-btn" id="supplier_price_change" data-toggle="modal" >Update Price</button> -->
											<a href="#myModal" class="search-btn" data-toggle="modal" id="price_change_confirm">Submit New Price</a></td>
									</tr>
								</table>
							</div>
						</div>
						<table id="supplier-tbl" class="table table-striped table-bordered" cellspacing="0" width="100%">
							<thead>
								<tr>
								  <th class="pro-title">Product</th>
								  <th>Tube Price</th>
								  <th>Tyre Price</th>
								  <th>M.R.P</th>
								  <th>Total</th>
								  <th>Status</th>
								</tr>
							</thead>
							<tbody id="supplier-product" class="supplier-product">
								<?php 
									if($supplierProductData){
		foreach ($supplierProductData as $key => $value)
				{
					//$product_variation = new WC_Product_Variation($value->product_id);
					$tyre_type=get_post_meta($value->product_id,'attribute_pa_tyre-type',true);             
					$user_info = get_userdata($value->user_id);
					
					$product= get_post_meta($value->product_id,'_variation_description',true);

					if($value->new_mrp && $value->status!=4){
						$regular_price=$value->new_mrp;
					}else{
						$regular_price=$value->old_mrp;
					}

					$new_tube_price =$value->new_tube_price;
					$old_tube_price =$value->old_tube_price;
					

					if($new_tube_price && $value->status!=4){
						$tube_price=$new_tube_price;
					}else{
						$tube_price=$old_tube_price;
					}
					$new_tyre_price =$value->new_tyre_price;
					$old_tyre_price =$value->old_tyre_price;

					if($new_tyre_price && $value->status!=4){
						$tyre_price=$new_tyre_price;
					}else{
						$tyre_price=$old_tyre_price;
					}
					$sale_price=($tube_price+$tyre_price);

					echo '<tr class="product-row" data-id="'.$value->id.'">';
					echo '<td>'.$product.'</td>';
					echo '<td>';
						if($tyre_type == 'tubetyre')
						{ 
							echo wc_price( $tube_price, $args ); ?>
							<input type="number" value="" class="tube-price-real" name="tube_price_real" data-price="<?php  echo $tube_price; ?>">
							
						<?php  }
						else{
							echo '-';
							?>
							 <input type="hidden" value="0" class="tube-price-real" name="tube_price_real">
							<?php
								}
							echo '</td>';
							echo '<td>';
								echo wc_price( $tyre_price); 
								echo '<input type="number" value="" class="tyre-price-real" name="tyre_price_real" data-price="'.$tyre_price.'">';
							echo '</td>';
							echo '<td>';
								echo wc_price( $regular_price); 
								echo '<div class="price regular-price" data-price="'.$regular_price.'">';
								echo '<input type="hidden" value="'.$regular_price.'" name="mrp_price" class="mrp-price">';
								echo '<input type="number" name="new_mrp_price" class="new-mrp-price">';
							echo '</div>';
							echo '</td>';
							echo '<td>';
								echo wc_price( $sale_price); 
								echo '<input type="number"  value="" class="sale-price-real" name="sale_price_real" data-price="" readonly>';
							echo '</td>';
							echo '<td>';
								if($value->status==1){
									$status='A';
									$class='status status-a';
									$titletool='Accept';
								}elseif($value->status==2){
									$status='P';
									$class='status status-p';
									$titletool='Pending';
								}elseif($value->status==3){
									$status='AA';
									$class='status status-aa';
									$titletool='Auto Approve';
								}elseif($value->status==4){
								   $status='C';
									$class='status status-c'; 
									$titletool='Cancel';
								}elseif($value->status==7){
								   	$status='N/A';
									$class='status status-a'; 
									$titletool='None';
								}else{
									/*$status='Cancel';
									$class='btn btn-warning';*/
								}
								echo '<a class="'.$class.'" data-toggle="tooltip" title="'.$titletool.'">'.$status.'</a>';
							echo '</td>';
						   
							echo '</tr>';
					   
						} 
					}else{
						echo '<tr><td colspan="5">Product not found!</td></tr>';
					}
								?>
								
							</tbody>
						</table>
						
						<table class="bulk-box-footer">
							<tr>
								<td class="price-sel">&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td colspan="1">
									<!-- <button class="search-btn" id="supplier_price_change">Update Price</button> -->
									<a href="#myModal" class="search-btn" data-toggle="modal" id="price_change_confirm">Submit New Price</a>
								</td>
							</tr>
						</table>

					</div>
				</div>
			</div>
		<?php } ?>
		</div>
	</div>
</div>
<!-- Modal HTML -->
<div id="myModal" class="modal fade">
    <div class="modal-dialog modal-confirm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Are you sure?</h4>  
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <div id="alrt-msg"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="search-btn" data-dismiss="modal">Cancel</button>
                <button type="button" class="search-btn" id="supplier_price_change">Yes</button>
            </div>
        </div>
    </div>
</div> 

<!-- Modal HTML -->
<div id="myModalBulk" class="modal fade">
    <div class="modal-dialog modal-confirm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Alert!</h4>  
                <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
            </div>
            <div class="modal-body">
                You have more then 100 plus products in your list, Bulk update not allowed for more then 100 products,Please use filter to reduce product list
            </div>
            <div class="modal-footer">
                <button type="button" class="search-btn" data-dismiss="modal" id="mdclose">OK</button>
                <!-- <button type="button" class="search-btn" id="bulkupdate100">Yes</button> -->
            </div>
        </div>
    </div>
</div>

<?php
get_footer();
?>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>

    

<style type="text/css">
	#myModal .modal-confirm h4 {
		text-transform: capitalize;
	   	font-size: 20px;
	   	margin: 0px;
		color: #000;
	}
	#myModal .modal-confirm .close {
	    top: 5px;
	    right: -10px;
	}
	#myModal #alrt-msg {
		font-size: 16px;
		color: #000;
	}
</style>
<script type="text/javascript">
	/*jQuery(document).ready(function() {
    jQuery('#supplier-tbl').DataTable( {
        "ajax": "data/arrays.txt"
    } );
} );*/
jQuery(document).ready(function() {
    jQuery('#supplier-tbl').DataTable();
    jQuery('#example').DataTable();
} );
jQuery(document).ready(function() {

		var width = jQuery('.select-width').val();
        var ratio = jQuery('.select-ratio').val();
        var diameter = jQuery('.select-diameter').val();
        var cat = jQuery('.select-category').val();
        var status = jQuery(this).attr('data-status');
        var admin_url = jQuery('.admin_url').text();

    jQuery('#supplier-tbl1').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": admin_url,
            "type": "POST",
            "data": {
                    action:"supplier_search_product_data",
                    width: width,
                    ratio: ratio,
                    diameter: diameter,
                    category: cat,
                    status:status,
                },
        },
        "columns": [
            { "data": "product" },
            { "data": "tube_price" },
            { "data": "tyre_price" },
            { "data": "mrp_price" },
            { "data": "total_price" },
            { "data": "status" }
        ]
    } );
} );


	jQuery(document).on('click','#supplier_prodct_search1',function(e){
        e.preventDefault();

        var width = jQuery('.select-width').val();
        var ratio = jQuery('.select-ratio').val();
        var diameter = jQuery('.select-diameter').val();
        var cat = jQuery('.select-category').val();
        var status = jQuery(this).attr('data-status');
        var admin_url = jQuery('.admin_url').text();
        var loader = '<tr style="text-align: center;"><td colspan="5"><img src="https://www.tyrehub.com/wp-content/plugins/tyrehub-product-discount/loading.gif"></td></tr>'; 
        jQuery.ajax({    
                type: "POST", 
                url: admin_url,
                data: {
                    action: 'supplier_search_product_data1',
                    width: width,
                    ratio: ratio,
                    diameter: diameter,
                    category: cat,
                    status:status,
                },
                beforeSend: function() {   
                		jQuery("#supplier-tbl > tbody").empty();                 
                        jQuery('#supplier-product').append(loader);
                    },
                success: function (data) {
                   jQuery('#supplier-product').html(data);
                    
                },
            });
    });

    /*setTimeout(function() {
    jQuery('#supplier_prodct_search').trigger('click');
}, 1000);*/



    jQuery(document).on('keyup','#supplier-tbl input.tube-price-real',function(){
        
        var supplier_product = jQuery(this).parents('.product-row');
        var tube_price = jQuery(this).val();

        var tyre_price = jQuery(supplier_product).find('.tyre-price-real').val();

        if(tyre_price == ''){
            tyre_price = jQuery(supplier_product).find('.tyre-price-real').attr('data-price');
        }
	
	    
        var new_sale_price;

        if(tube_price == '-' || tube_price == ''){
            //console.log('not found');
            new_sale_price = parseFloat(tyre_price);
        }
        else{
            new_sale_price = parseInt(tube_price) + parseInt(tyre_price);
        }
       
        console.log(new_sale_price);
        jQuery(supplier_product).find('.sale-price-real').val(new_sale_price);
         var mrp_price = jQuery(supplier_product).find('.new-mrp-price').val();
        if(mrp_price == '' || mrp_price ==0){
            mrp_price = jQuery(supplier_product).find('.mrp-price').val();
        }
           
        if(mrp_price<new_sale_price){
            	
            	jQuery(supplier_product).find('.new-mrp-price').css("border-color", "red");
            	//jQuery('#price_change_confirm').attr("disable", "disable");
            	jQuery('#price_change_confirm').prop('disabled', true);
            	return false;	
        }else{
        	jQuery(supplier_product).find('.new-mrp-price').css("border-color", "green");
        	jQuery('#price_change_confirm').prop('disabled', false);
        	//jQuery('#price_change_confirm').removeAttr('disable');
        }
    });

    jQuery(document).on('keyup','#supplier-tbl input.tyre-price-real',function(){
        
        var supplier_product = jQuery(this).parents('.product-row');
        var tyre_price = jQuery(this).val();

        var tube_price = jQuery(supplier_product).find('.tube-price-real').val();

        if(tube_price == ''){
            tube_price = jQuery(supplier_product).find('.tube-price-real').attr('data-price');
        }
	
	    var new_sale_price;

        if(tube_price == '-' || tube_price == ''){
            //console.log('not found');
            new_sale_price = parseFloat(tyre_price);
        }else{
            new_sale_price = parseInt(tube_price) + parseInt(tyre_price);
        }
        
        console.log(new_sale_price);
        jQuery(supplier_product).find('.sale-price-real').val(new_sale_price);

        var mrp_price = jQuery(supplier_product).find('.mrp-price').val();
        if(mrp_price == ''){
            mrp_price = jQuery(supplier_product).find('.new-mrp-price').attr('data-mrp-price');
        }
        
        if(mrp_price<new_sale_price){
            	
            	jQuery(supplier_product).find('.new-mrp-price').css("border-color", "red");
            	jQuery('#price_change_confirm').prop('disabled', true);
            	return false;	
        }else{
        	jQuery(supplier_product).find('.new-mrp-price').css("border-color", "green");
        	jQuery('#price_change_confirm').prop('disabled', false);
        }
    });

jQuery(document).on('click','#price_change_confirm',function()
    {
        var temp = [];


        jQuery("#supplier-tbl .product-row").each(function()
        {
                var tyre_price = 0;
                var tube_price = 0;
                var percentage=0;
                var margin_price=0;
                tyre_price = jQuery(this).find('input.tyre-price-real').val();
                tube_price = jQuery(this).find('input.tube-price-real').val();
                var mrp_price = jQuery(this).find('input.mrp-price').val(); 
                var mrp_price_new = jQuery(this).find('input.new-mrp-price').val();
                if(mrp_price_new){
                	mrp= mrp_price_new;
                }else{
                	mrp=mrp_price;
                }
                if(mrp<=0){

                }
                var sale_price = jQuery(this).find('input.sale-price-real').val();

                var supp_data_id = jQuery(this).attr('data-id');
                
               
                var prd_list = {};
                var price_list = {};
               
                price_list.mrp_price_new = mrp_price_new;
                price_list.mrp_price = mrp_price;
                price_list.sale_price = sale_price;     
                price_list.tyre_price = tyre_price;
                price_list.tube_price = tube_price;
                
                //price_list.update_by = update_by;

                //prd_list.product_id = product_id;
                prd_list.spid = supp_data_id;
                prd_list.price_list = price_list;
                if(sale_price!='' || mrp_price_new!=''){
                	temp.push(prd_list);
                }           
                     
        	});

        	jQuery('#alrt-msg').html('');

        	jQuery('#alrt-msg').html('This price change will affect <strong>'+temp.length+'</strong> product from your search product list,<br/>Are you sure you want to change product Prices?');
        	
        	
        	
    });

 jQuery(document).on('click','#supplier_price_change',function()
    {
        jQuery('#myModal').modal('hide');
        var temp = [];
        

        jQuery("#supplier-tbl .product-row").each(function()
        {
                var tyre_price = 0;
                var tube_price = 0;
                var percentage=0;
                var margin_price=0;
                tyre_price = jQuery(this).find('input.tyre-price-real').val();
                tube_price = jQuery(this).find('input.tube-price-real').val();
                var mrp_price = jQuery(this).find('input.mrp-price').val(); 
                var mrp_price_new = jQuery(this).find('input.new-mrp-price').val();
                var sale_price = jQuery(this).find('input.sale-price-real').val();

                var supp_data_id = jQuery(this).attr('data-id');
                
               
                var prd_list = {};
                var price_list = {};
               
                price_list.mrp_price_new = mrp_price_new;
                price_list.mrp_price = mrp_price;
                price_list.sale_price = sale_price;     
                price_list.tyre_price = tyre_price;
                price_list.tube_price = tube_price;
                
                //price_list.update_by = update_by;

                //prd_list.product_id = product_id;
                prd_list.spid = supp_data_id;
                prd_list.price_list = price_list;               
                temp.push(prd_list);     
        });
        	console.log(temp);
        	/*if(temp.length>1){
        		jQuery('.message-block').html('<div class="alert alert-danger"><strong>Error!</strong> Please any product price change then update.</div>');
        		return false;
        	}*/
        	jQuery('#cover-spin').show(0);
        	var admin_url = jQuery('.admin_url').text();
            jQuery.ajax({    
                    type: "POST", 
                    url: admin_url,
                    data: {
                        action: 'supplier_change_product_price',                        
                        prd_list: temp,
                    },
                    success: function (data) {
                        
                       jQuery('.message-block').html('<div class="alert alert-success"><strong>Success!</strong> Product price submited successfully.</div>');
                       //jQuery('#supplier_prodct_search').trigger('click');
                       jQuery('#cover-spin').hide(0);
                       location.reload();
                    },
                });
    });



jQuery(document).on('change','.select-update-by',function(e){
	
        var selectoptionvalue  = jQuery(".select-update-by option:selected").val();
        if(selectoptionvalue == 'productvalue'){
            jQuery('.update-tube-price input').attr("placeholder", "Tube");
            jQuery(".update-tyre-price input").attr("placeholder", "Tyre");
            jQuery('.update-tube-price .per-after').remove();
            jQuery('.update-tyre-price .per-after').remove();
        }
        
        if(selectoptionvalue == 'percentage'){
            jQuery('.update-tube-price input').after("<span class='per-after'>%</span>");
            jQuery(".update-tyre-price input").after("<span class='per-after'>%</span>");
            jQuery('.update-tube-price input').attr("placeholder", "");
            jQuery(".update-tyre-price input").attr("placeholder", "");
        }
        jQuery(".bulk-tube-price").val('');
         jQuery(".bulk-tyre-price").val('');
        
        jQuery(".update-tube-price").css("display","block");
        jQuery(".update-tyre-price").css("display","block");
    });


jQuery(document).on('keyup','input.bulk-tube-price',function(){
	
        var added_tube_price = jQuery(this).val();
        var update_by = jQuery(".select-update-by option:selected").val();
        
        jQuery("#supplier-tbl .product-row").each(function()
        {
            var tube_real_price = jQuery(this).find('.tube-price-real').attr('data-price');
            if(tube_real_price == ''){
                tube_real_price = 0;
            }

            if(update_by == 'productvalue'){
                var new_tube_price = parseInt(added_tube_price) + parseInt(tube_real_price);
            }else{
                var per = added_tube_price;
                var per_val = parseInt(per) * parseInt(tube_real_price) / 100;
                var new_tube_price = parseInt(tube_real_price) + parseInt(per_val);
            }   

            

            if(jQuery(this).find('.tube-price-real').val() != '-'){

            jQuery(this).find('.tube-price-real').val(new_tube_price);
            }
            /*console.log(new_tube_price);
            console.log(added_tube_price);*/
            if(added_tube_price == ''){
                if(jQuery(this).find('.tube-price-real').val() != '-'){
                    jQuery(this).find('.tube-price-real').val(tube_real_price);
                }                

            }

            // for updating sale price
            var tube_price = jQuery(this).find('.tube-price-real').val();
            var tyre_price = jQuery(this).find('.tyre-price-real').val();
            if(tube_price == ''){
                tube_price = tube_real_price;
            }
            if(tube_price == '-'){
                
                tube_price = 0;
            }
            if(tyre_price == ''){
                tyre_price = jQuery(this).find('.tyre-price-real').attr('data-price');
            }
            

            var new_sale_price = Math.round(parseInt(tube_price) + parseInt(tyre_price));

            var mrp_price = jQuery(this).find('.new-mrp-price').val();
            if(mrp_price == ''){
                mrp_price = jQuery(this).find('.new-mrp-price').attr('data-mrp-price');
            }

            if(mrp_price<new_sale_price){
            	
            	$(this).find('.new-mrp-price').css("border-color", "red");
            	return false;	
            }
           

       

            if(jQuery(this).find('.tube-price-real').val() != '-'){
                jQuery(this).find('.sale-price-real').val(new_sale_price.toFixed(0));
            }
        });
    });

    jQuery(document).on('keyup','input.bulk-tyre-price',function(){
        var added_tyre_price = jQuery(this).val();
        var update_by = jQuery(".select-update-by option:selected").val();
        jQuery("#supplier-tbl .product-row").each(function()
        {
           // console.log($(this).attr('data-id'));
            var tyre_real_price = jQuery(this).find('.tyre-price-real').attr('data-price');

            if(update_by == 'productvalue'){

                var new_tyre_price = parseInt(added_tyre_price) + parseInt(tyre_real_price);
            }
            else{
                var per = added_tyre_price;
                var per_val = parseInt(per) * parseInt(tyre_real_price) / 100;
                var new_tyre_price = parseInt(tyre_real_price) + parseInt(per_val);
            }

           

            jQuery(this).find('.tyre-price-real').val(new_tyre_price);

            if(added_tyre_price == ''){
                jQuery(this).find('.tyre-price-real').val(tyre_real_price);
            }

            // for updating sale price
            var tube_price = jQuery(this).find('.tube-price-real').val();
            var tyre_price = jQuery(this).find('.tyre-price-real').val();
           
            
            if(tube_price == ''){
               // console.log('blank');
                tube_price = jQuery(this).find('.tube-price-real').attr('data-price');
                if(tube_price == ''){
                    tube_price = 0;
                }
            }
            if(tube_price == '-'){
               // console.log('undefined');
                tube_price = 0;
            }
            if(tyre_price == ''){
                tyre_price = tyre_real_price;
            }
           // console.log(tyre_price);
           // console.log(tube_price);
            var new_sale_price = Math.round(parseInt(tube_price) + parseInt(tyre_price));

            var mrp_price = jQuery(this).find('.new-mrp-price').val();
            if(mrp_price == ''){
                mrp_price = jQuery(this).find('.new-mrp-price').attr('data-mrp-price');
            }

            if(mrp_price<new_sale_price){
            	
            	$(this).find('.new-mrp-price').css("border-color", "red");
            	return false;	
            }

            
            jQuery(this).find('.sale-price-real').val(new_sale_price.toFixed(0));
        });
        jQuery('[data-toggle="tooltip"]').tooltip(); 
    });


        jQuery('input[name="colorRadio"]').click(function(){

            if(jQuery(this).prop("checked") == true){

                <?php if(count($supplierProductData)>100){?>
                	jQuery('#myModalBulk').modal('show');
                	return false;
                <?php }?>
				//jQuery("#supplier-tbl_length select").val(100);
				jQuery('#supplier-tbl_length select').val(100).trigger('change');
			//supplier-tbl_length

            }else if(jQuery(this).prop("checked") == false){

                jQuery('#supplier-tbl_length select').val(25).trigger('change');

            }

        });

    

</script>