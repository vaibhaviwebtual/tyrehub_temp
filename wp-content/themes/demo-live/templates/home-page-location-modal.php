<div class="modal fade" id="current_pincode" role="dialog" >
        <div class="modal-dialog">
        
          <!-- Modal content-->
          	<div class="modal-content" style="text-align: center;">
            
                <div class="modal-body">

                    <div class="city-section">
                        <p>We are providing service in this city</p>
                          <div class="col-md-4 cityname <?php if($_SESSION['current_city']=='Ahmedabad'){ echo 'active';}?>" id="ahmedabad"  data-pincode="380001">
                             <img src="<?php echo bloginfo('template_directory')?>/images/Ahmedabad-512_120.png" >
                            <p>Ahmedabad</p>
                          </div>
                         
                          <div class="col-md-4 cityname <?php if($_SESSION['current_city']=='Gandhinagar'){ echo 'active';}?>" id="gandhinagar" data-pincode="382007" >
                            <img src="<?php echo bloginfo('template_directory')?>/images/Mumbai-512_120.png">
                            <p>Gandhinagar</p>
                          </div>
                          <div class="col-md-4 cityname" id="other-city" data-pincode="">
                            <img src="<?php echo bloginfo('template_directory')?>/images/Mumbai-512_120.png" >
                            <p>Other City</p>
                          </div>
                       
                      </div>
                      <?php if($_SESSION['current_pincode']!=''){
                        $display='block;';
                       }else{
                        $display='none;';
                       }?>
                    <div class="row" id="detailsFrm" style="display:<?=$display;?>;">
                      <div class="col-md-12">                      
                        <input type="text" name="pincode" class="current-pincode" placeholder="Enter Pincode" value="<?=$_SESSION['current_pincode'];?>"> 
                    </div>
  
                    
                    <!-- <div class="col-md-6">                      
                        <input type="text" name="" class="current-fullname" placeholder="Enter Name"> 
                    </div>
 -->                    <div class="col-md-12">                      
                        <input type="text" name="mobile" class="current-mobile" placeholder="Enter Mobile" size="10" maxlength="10"> 
                    </div>
                      
                     </div>
                     <div class="other-city-msg" style="display: none; margin-top: 7px;">We will come to your city very soon, if you still like to buy tyres from us today with an addition delivery cost, please call us on 1-800-233-5551</div>
                    <div class="modal-footer" style="text-align: right;">
                    <button class="confirm-location btn btn-invert" id="confirmBtn" style="min-width: 100px; padding: 10px;float: right; display: <?=$display;?>"><span>Confirm</span></button>
                    
                    <?php if($_SESSION['current_pincode']!=''){?>  
                    <button type="button" class="btn btn-invert" data-dismiss="modal" style="min-width: 100px; padding: 10px; margin-right:5px;"><span>Cancel</span></button>
                  <?php }?>
                  <img id="loding" style="display: none; " src='<?=site_url();?>/loading.gif' width='30' height='30' />
                       <div class="message"  style="text-align: left;"></div>
                    </div>
                </div>

               

                

          	</div>
          
        </div>
    </div>

