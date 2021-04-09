<div class="modal fade cart-item-delivery-model" id="<?php echo $current_prd; ?>_delivery_modal" role="dialog" >
  	<div class="modal-dialog">		    
    	<!-- Modal content-->
    	<div class="modal-content"  style="pointer-events: auto;">
    		 <div class="modal-header">
        	 <button type="button" class="close" data-dismiss="modal">&times;</button>        
      		</div>
    		<div class="modal-body">
      		<span class="cart_item_id" hidden=""><?php echo $cart_item_key; ?></span>
      		<span class="product-id" hidden=""><?php echo $current_prd; ?></span>
      		<span class="session-id" hidden=""><?php echo $session_id; ?></span>

<!-------------- select vehicle type Screen ---------------------->
				<div id="tab4-form" method="post" class="select-car-type screen">
					<div class="product-info"><span class="id"></span></div>     
					<div>Enter Valid Delivery Pincode</div> 
		                <div class="modal-footer">
							<div class="right">
								<input type="text" placeholder="Enter pincode" name="delivery_pincode" class="delivery_pincode" />
								
							</div>
							<div class="left">	                    		
				                <div class="btn btn-invert confirm-delivery modal-btn">
				        			<span>Confirm Pincode</span>
				        		</div>
				        	</div>
						</div>

				</div>

 <!----------------- vehicle Screen ------------------------>
		<div class="modal-footer">
			
        	<span class="info-delivery"></span>	                    	
		</div>
    	
    </div>
             
<!---------------- Review Screen --------------------------------->
		        </div>
		        
		      </div>
		      
		    </div>
		  </div>