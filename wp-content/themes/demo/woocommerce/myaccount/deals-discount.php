<?php
if ( !is_user_logged_in() ) {
  wp_redirect(get_permalink( get_option('woocommerce_myaccount_page_id') ).'/installer-login' );    
}
get_header();
?>
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css">
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
		//	do_action( 'woocommerce_account_content' ); ?>
			<style>
				.deals-top {
					background-color: #f2f2f2;
					padding: 15px;
				}
				.deals-top h3 {
					font-size: 26px;
					margin-bottom: 0px;
				}
				.add-discount {
					background-color: #2f3672;
					color: #fff;
					border: 0px;
					height: 35px;
					padding: 0px 20px;
					cursor: pointer;
					display: inline-block;
					vertical-align: top;
					line-height: 35px;
				    float: right;
				    display: inline-block;
				}
				.add-discount:hover {
					background-color: #ffd642;
				}
				#example_wrapper {
				    display: inline-block;
    				width: 100%;
					background-color: #f2f2f2;
    				padding: 15px;
				}
				#example_wrapper input[type="search"] {
					padding: 0px;
				}
				#example_wrapper a {
					color: #000;
					text-transform: capitalize;
				}
				#example_wrapper .pagination>.active>a,
				#example_wrapper .pagination>.active>a:focus,
				#example_wrapper .pagination>.active>a:hover,
				#example_wrapper .pagination>.active>span,
				#example_wrapper .pagination>.active>span:focus,
				#example_wrapper .pagination>.active>span:hover {
					color: #fff;
					background-color: #2f3672;
    				border-color: #2f3672;
				}
			</style>
				<div class="woocommerce-MyAccount-content <?php if($current_user_role == 'Supplier'){echo 'supplier-account';} ?>" style="width: 100%;">
					<div class="deals-top row">
						<div class="col-md-6">
							<h3>Deals and Discount</h3>
						</div>
						<div class="col-md-6">
							<a href="<?=site_url('/my-account/new-discount/');?>" class="add-discount">Add Discount</a>
						</div>
					</div>
					<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">
					<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
					<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>

					<table id="example" class="table table-striped table-bordered" style="width:100%">
						<thead>
							<tr>
								<th>Title</th>
								<th>Start Date</th>
								<th>End Date</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							global $wpdb;
							$SQL="SELECT * FROM `th_discount_rule` WHERE supplier_id='".get_current_user_id()."'";
							$results=$wpdb->get_results($SQL);
							foreach ($results as $key => $res) {?>
							<tr>
								<td> <a href="<?=site_url('/my-account/update-discount/?id='.$res->rule_id);?>"><?=$res->name;?></a></td>
								<td><?=$res->start_date;?></td>
								<td><?=$res->end_date;?></td>
								<td><?=$res->status;?></td>
								<td><a href="<?=site_url('/my-account/update-discount/?id='.$res->rule_id);?>"><i class="fa fa-pencil-square-o" aria-hidden="true" style="font-size: 20px;"></i></a> <a href="<?=site_url('/my-account/update-discount/?id='.$res->rule_id);?>"><i class="fa fa-trash" aria-hidden="true" style="font-size: 20px;"></i></a></td>
							</tr>
							<?php } ?>
						</tbody>
						<tfoot>
							<tr>
								<th>Title</th>
								<th>Start Date</th>
								<th>End Date</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
</div>

<?php
get_footer();
?>
<script type="text/javascript">
	jQuery(document).ready(function() {
    jQuery('#example').DataTable();
} );

	jQuery(document).on('click','#supplier_prodct_search',function(e){
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
                    action: 'supplier_search_product_data',
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

    setTimeout(function() {
    jQuery('#supplier_prodct_search').trigger('click');
}, 1000);



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
    });

 jQuery(document).on('click','#supplier_price_change',function()
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
                       jQuery('#supplier_prodct_search').trigger('click');
                       jQuery('#cover-spin').hide(0);
                    },
                });
    });



jQuery(document).on('change','.select-update-by',function(e){
	
        var selectoptionvalue  = jQuery(".select-update-by option:selected").val();
        if(selectoptionvalue == 'productvalue'){
            jQuery('.update-tube-price input').attr("placeholder", "Amount");
            jQuery(".update-tyre-price input").attr("placeholder", "Amount");
            jQuery('.update-tube-price .per-after').remove();
            jQuery('.update-tyre-price .per-after').remove();
        }
        
        if(selectoptionvalue == 'percentage'){
            jQuery('.update-tube-price input').after("<span class='per-after'>%</span>");
            jQuery(".update-tyre-price input").after("<span class='per-after'>%</span>");
            jQuery('.update-tube-price input').attr("placeholder", "");
            jQuery(".update-tyre-price input").attr("placeholder", "");
        }
        jQuery(".update-tube-price").css("display","block");
        jQuery(".update-tyre-price").css("display","block");
    });


jQuery(document).on('keyup','input.bulk-tube-price',function(){
	
        var added_tube_price = jQuery(this).val();
        var update_by = jQuery(".select-update-by option:selected").val();
        
        jQuery("#supplier-tbl .supplier-product").each(function()
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

           

       

            if(jQuery(this).find('.tube-price-real').val() != '-'){
                jQuery(this).find('.sale-price-real').val(new_sale_price.toFixed(0));
            }
        });
    });

    jQuery(document).on('keyup','input.bulk-tyre-price',function(){
        var added_tyre_price = jQuery(this).val();
        var update_by = jQuery(".select-update-by option:selected").val();
        jQuery("#supplier-tbl .supplier-product").each(function()
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

            
            jQuery(this).find('.sale-price-real').val(new_sale_price.toFixed(0));
        });
    });
</script>