<?php
function supplier_per_change(){

 	global $wpdb;
    $currency = get_woocommerce_currency_symbol();
    $supp_data_id = $_GET['supp_data_id'];
    $sql = "SELECT * FROM th_supplier_products_list where id = '$supp_data_id'";
    $supplier_data = $wpdb->get_results($sql);
    foreach ($supplier_data as $key => $value)
	{
		
		$sql = "SELECT * FROM th_supplier_data where supplier_data_id = '$value->supplier_id'";
    	$supplier = $wpdb->get_results($sql);

		$id = $value->id;
		//$supp_data_id=$value->supp_data_id;
		$user_id = $value->user_id;
		$supplier = $supplier[0]->business_name;
		$productname = get_post_meta($value->product_id,'_variation_description',true);
		$new_tube_price = $value->new_tube_price;
		$old_tube_price = $value->old_tube_price;
		$new_tyre_price = $value->new_tyre_price;
		$old_tyre_price = $value->old_tyre_price;
		$flat_percentage = $value->flat_percentage;
		$margin_price = $value->margin_price;
		$new_mrp = $value->new_mrp;
		$old_mrp = $value->old_mrp;
		$new_total_price = $value->new_total_price;
		$old_total_price = $value->old_total_price;
		$status = $value->status;
		
	}
    
?>
	<div class="wrap woocommerce">
 		<h1 class="wp-heading-inline">Supplier Porduct Price <?php echo $productname; ?></h1>
 		<span hidden="" class="admin-url"><?php echo admin_url('admin-ajax.php'); ?></span>
 		<form action="" name="price-change" id="pricechange" method="post">
 			<input type="hidden" name="act" id="act" value="">
		 	<table class="form-table" >
		 		<tbody>
		 			
		 			<tr>
		 				<th>Supplier</th>
		 				<td><strong><?=$supplier;?></strong>
		 					<input type="hidden" class="id" name="id" value="<?php echo $id; ?>">

		 					
		 				</td>
		 			</tr>
		 			<tr>
		 				<th>Old Tube Price</th>
		 				<td><strong><?php echo wc_price($old_tube_price); ?></strong>
		 					<input type="hidden" class="old_tube_price" name="old_tube_price" value="<?php echo $old_tube_price; ?>">
		 				</td>
		 			</tr>
		 			<tr>
		 				<th>New Tube Price</th>
		 				<td><strong><?php echo wc_price($new_tube_price); ?></strong>
		 					<input type="hidden" class="new_tube_price" name="new_tube_price" value="<?php echo $new_tube_price; ?>">
		 				</td>
		 			</tr>
		 			
		 			<tr>
		 				<th>Old Tyre Price</th>
		 				<td><strong><?php echo wc_price($old_tyre_price); ?></strong>
		 					<input type="hidden" class="old_tyre_price" name="old_tyre_price" value="<?php echo $old_tyre_price; ?>">
		 				</td>
		 			</tr>
		 			<tr>
		 				<th> New Tyre Price</th>
		 				<td><strong><?php echo wc_price($new_tyre_price); ?></strong>
		 					<input type="hidden" class="new_tyre_price" name="new_tyre_price" value="<?php echo $new_tyre_price; ?>">
		 				</td>
		 			</tr>
		 			<tr>
		                <th>Percentage % </th>
		                <td><strong><input type="text" name="flat_percentage" id="flat_percentage" value="<?php echo $flat_percentage; ?>"></strong></td>
		            </tr>
		 			<tr>
		                <th>Margin Price</th>
		                <td><strong><?=get_woocommerce_currency_symbol();?><input type="text" name="margin_price" id="margin_price" value="<?php echo $margin_price; ?>"></strong></td>
		            </tr>
		            <tr>
		                <th>Old MRP</th>
		                <td><strong><?php echo wc_price($old_mrp); ?></strong></td>
		                <input type="hidden" name="old_mrp" id="old_mrp" value="<?php echo $old_mrp; ?>">
		                <?php if(empty($new_mrp)){
		                	$new_mrp=$old_mrp;
		                }?>
		            </tr>
		 			<tr>
		 				<th>New MRP</th>
		 				<td>
		 					<strong><?=get_woocommerce_currency_symbol();?><input type="text" name="new_mrp" id="new_mrp" value="<?php echo $new_mrp; ?>"></strong>
		 				</td>
		 			</tr>
		 			<tr>
		 				<th>Old Total</th>
		 				<td><strong><?php echo wc_price($old_total_price); ?></strong></td>
		 			</tr>
		 			<tr>
		 				<th>New Total</th>
		 				<td><strong><?=get_woocommerce_currency_symbol();?><span id="new_total"><?php echo number_format($new_total_price,2); ?></span></strong></td>
		 			</tr>
		 			<tr>
		 				<td colspan="3"><input type="submit" name="submit" value="Save & Accept" class="button-primary woocommerce-save-button add-installer-btn">
		 				<input type="submit" id="sava-close" name="submit" value="Save/Close" class="button-primary woocommerce-save-button add-installer-btn">
		 				</td>
		 				
		 			</tr>
		 		</tbody>
		 		
		 	</table>
		 	
 		</form>

 		<?php
 		if(isset($_POST['submit']) && $_POST['id']!='')
 		{
	 			global $wpdb, $woocommerce;

	 			
	 			$spid=$_POST['id'];
	 			$percentage=$_POST['flat_percentage'];
	 			$margin_price=$_POST['margin_price'];
	 			$old_mrp=$_POST['old_mrp'];
	 			$new_mrp=$_POST['new_mrp'];

	$supp_pro_id=$spid;
	$flat_percentage=$percentage;
	$margin_price=$margin_price;
	
	$SQL = "SELECT * FROM th_supplier_products_list where id = '$supp_pro_id'";
	$supplier_pro = $wpdb->get_row($SQL);

	$product_id=$supplier_pro->product_id;
	$supplier_id=$supplier_pro->supplier_id;

	$percentage=$flat_percentage;	

				if($supplier_pro->new_mrp!=$new_mrp){
					$update_data['new_mrp'] = $new_mrp;
				}

				
				$new_tube_price =$supplier_pro->new_tube_price;
				$old_tube_price =$supplier_pro->old_tube_price;        

				if($new_tube_price){
					$tube_price=$new_tube_price;
				}else{
					$tube_price=$old_tube_price;
				}

				$new_tyre_price =$supplier_pro->new_tyre_price;
				$old_tyre_price =$supplier_pro->old_tyre_price;

				if($new_tyre_price){
					$tyre_price=$new_tyre_price;
				}else{
					$tyre_price=$old_tyre_price;
				} 
				//echo $tube_price;
				//echo '<br>';
				//echo $percentage;                
				//echo '<br>';
				$perce_price=(($tyre_price+$tube_price)*$percentage)/100;
				$margin=$margin_price;
				//echo '<br>';
				$tyre_total=round(($tyre_price+$tube_price+$perce_price+$margin));

				if($supplier_pro->flat_percentage!=$flat_percentage){
					$update_data['flat_percentage'] = $flat_percentage;
				}

				if($supplier_pro->margin_price!=$margin_price){
					$update_data['margin_price'] = $margin_price;
				}

				$update_data['new_total_price'] =$tyre_total;
				$update_data['updated_date'] =date('Y-m-d H:i:s');
				$update_data['status'] =2;
			   
				 $wpdb->update('th_supplier_products_list',$update_data, 
						array('id' =>$supp_pro_id)
					);

				$SQL2 = "SELECT * FROM th_supplier_products_log where supp_pro_id = '$supp_pro_id'";
				$pro_log = $wpdb->get_row($SQL2);

				if($pro_log->flat_percentage!=$flat_percentage){
					$log_data['flat_percentage'] = $flat_percentage;
				}

				if($pro_log->margin_price!=$margin_price){
					$log_data['margin_price'] = $margin_price;
				}

				if($supplier_pro->new_mrp!=$mrp){
					$log_data['new_mrp'] = $mrp;
				}

				$log_data['new_total_price'] = $tyre_total;

				$log_data['user_id'] =get_current_user_id();
				$log_data['status'] =2;
				$log_data['updated_date'] = date('Y-m-d H:i:s');
			
			   
		$wpdb->update('th_supplier_products_log',$log_data,array('supp_pro_id'=>$supp_pro_id,'product_id'=>$product_id,'status'=>2));		

	 	$SQL="SELECT * FROM  th_supplier_products_list  WHERE  id = '$supp_pro_id' AND product_id='$product_id' AND common_status=2 AND status=2";
		$products=$wpdb->get_results($SQL);	

		if($_POST['act']=='save-close'){

			wp_redirect( '?page=product-price-list&action=edit&product_id='.$product_id);
			//wp-admin//admin.php?page=supplier-per-change&action=edit&supp_data_id=1411
		}else{
			supplier_price_sync_to_product($products,0,0);
			//wp_redirect( '?page=supplier-product-price-change-list');
			wp_redirect( '?page=product-price-list&action=edit&product_id='.$product_id);
		}
		




//product_price_change_by_admin_and_supplier($spid,'','',$percentage,$margin_price,$new_mrp,'','aproveprice');
		
 			
 		}
 		?>
 	</div>
 	<?php
}?>
<script type="text/javascript">
	// A $( document ).ready() block.
jQuery( document ).ready(function() {
   
   jQuery('#sava-close').click(function(){

   		
   		jQuery('#act').val('save-close');
   		jQuery('#pricechange').submit();
   });

   jQuery("#flat_percentage,#margin_price,#new_mrp" ).keyup(function() {

   		var percentage=parseFloat(jQuery('#flat_percentage').val());
   		var new_tube_price	=parseFloat(jQuery('.new_tube_price').val());
   		var old_tube_price	=parseFloat(jQuery('.old_tube_price').val());
   		if(new_tube_price){
   			tube_price=new_tube_price;
   		}else{
   			tube_price=old_tube_price;
   		}
   		var new_tyre_price	=parseFloat(jQuery('.new_tyre_price').val());
   		var old_tyre_price	=parseFloat(jQuery('.old_tyre_price').val());

   		if(new_tyre_price){
   			tyre_price=new_tyre_price;
   		}else{
   			tyre_price=old_tyre_price;
   		}

   		perce_price=((tyre_price+tube_price)*percentage)/100;
   		var margin=parseFloat(jQuery('#margin_price').val());
   		
	  	tyre_total=(tyre_price+tube_price+perce_price+margin);

	  	mrp = jQuery('#new_mrp').val();

	  	if(mrp<tyre_total){
	  		jQuery(".button-primary").attr("disabled", true);
	  		jQuery('#mrpmsg').remove();
	  		jQuery('#new_mrp').after('<p style="color:red;" id="mrpmsg">Please enter MRP greater than total price.</p>');
	  	}else{
	  		jQuery(".button-primary").attr("disabled", false);
	  		jQuery('#mrpmsg').remove();
	  	}

	  	jQuery('#new_total').html(tyre_total);
	});

  

});
</script>