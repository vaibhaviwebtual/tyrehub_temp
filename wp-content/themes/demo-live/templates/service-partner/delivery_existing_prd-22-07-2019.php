
<?php 
	if(isset($_GET['product_id']))
	{
?>
<div class="section deliver-to-home">
	<div class="inner">
<?php
	global $woocommerce;
    $qty = 0;

    if(isset($_GET['total_qty']))
    {
	    if($woocommerce->cart->cart_contents_count > 0)
	    {
	        foreach($woocommerce->cart->get_cart() as $key => $val )
	        {
	            $_product = $val['data'];
	           	$product_variation = new WC_Product_Variation( $_product->get_id() );
	            
				$variation_des = $product_variation->get_description();
				$qty = $cart_item['quantity'];

	            if($product_id == $_product->get_id() )
	            {		            
?>
		        	<script type="text/javascript">		               	
		               	setTimeout(function(){
				        	jQuery('#buy_prd').modal('show');
				       },2000);
		            </script>

	               	<div class="modal fade" id="buy_prd" role="dialog">
					  	<div class="modal-dialog">  
					    	<!-- Modal content-->
					    	<div class="modal-content"  style="pointer-events: auto;">
					    		 <div class="modal-header">        
					      		</div>
					    		<div class="modal-body">
					    			<p>
					    				You have already added <?php echo $variation_des; ?> so you can add or remove quantity from cart page. and you can buy maximum 5 qty of per tyre If you need more than 5 than check out first.
					    			</p>				    			
					    		</div>
					    		<div class="modal-footer">
					    			<a href="<?php echo get_site_url().'/cart';?> " class="btn btn-invert"><span>Cart</span></a>
					    		</div>
					    	</div>
					    </div>
					</div>
		               <?php
		            }
		        }
		    }
		}
			
		?>
		<div class="column">
			<div class="row text" style="margin-top: 0px; background-color:#2F3672; padding-top: 5px; color: #fff; font-weight: bold; ">GET FREE HOME DELIVERY</div>

			<div class="row">

				<div class="col-md-8">
			    <input type="text" placeholder="Enter pincode" name="delivery_pincode" class="delivery_pincode search_input" style="width: 70%;" onfocus="this.placeholder=''" onblur="this.placeholder='Enter pincode'" />
								
				</div>
				<div class="col-md-4">
					<input type="button" name="Confirm" class="btn btn-invert delivery-eligible modal-btn " value="Check" style="min-width: auto;">
                <!-- <div class="btn btn-invert confirm-delivery modal-btn">
        			<span>Confirm</span>
        		</div> -->
								
				</div>
				<?php 
				if(isset($_SESSION['current_pincode1']))
				{		
					//echo'<button class="confirm-deliver-home btn btn-invert"><span>Free Home Delivery</span></button>';		
				}else{
			
					//echo'<button class="btn btn-invert" data-toggle="modal" data-target="#'.$product_id.'_home_delivery_modal"><span>Free Home Delivery</span></button>';
				} 
		
				?>
				<span id="succ-msg"><i class="fa fa-check" aria-hidden="true"></i>
 Congratulations you are eligible for free home delivery</span>
				<button id="add_to_free_delivery" class="confirm-deliver-home btn btn-invert"><span>Free Delivery</span></button>
			</div>
			</div>
			<div class="info-delivery" style="padding: 10px;">	
		</div>

		
			
</div>	
<div class="row descri-delivery" style="font-size:11px; text-align: left; font-weight: bold; ">
	*Free delivery is available to <span style="color:#FEDE00; ">Ahmedabad</span> and <span style="color:#FEDE00; ">Gandhinagar</span> area only.<br>	
	*Home delivery will take within 2-3 working days.</div>
</div>			
<?php 
include('home-delivery-modal.php');
} 
?>
