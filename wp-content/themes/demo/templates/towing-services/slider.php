<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/assest/sweetalert2/src/sweetalert2.scss">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<div class="towing-part">
    <div class="filter-sec">
    <div class="container">
      <div class="row">
        <div class="col-sm-12 col-sm-6 col-md-6 col-lg-6">
          <div class="service_filter">
            <div class="title_part">
              <a href="<?php echo get_home_url(); ?>" class="img-sec">
                <img class="normal" src="<?php echo get_stylesheet_directory_uri(); ?>/images/slide-icon/back_arrow.png" />
              </a>
              <div class="title">
                Towing Services
              </div>
            </div>
            <div class="img_part">
              <img class="normal" src="<?php echo get_stylesheet_directory_uri(); ?>/images/slide-icon/towing_img.png" />
            </div>
            <div class="vehicle_part">
              <!--<h3 class="vehicle_title">Your Information</h3>-->
              <form id="towing-services-form" action="#" method="post">
				<input type="text" id="fullname" name="fullname" placeholder="Name">
				<input type="tel" id="mobile" name="mobile" placeholder="Mobile Number">
				<input type="text" id="vehicle-location" name="vehicle-location" placeholder="Vehicle Location">
				<!-- <input type="text" id="preferred-date" name="preferred-date" placeholder="Preferred Date">
				<select id="preferred-time" name="preferred-time">
					<option value="">Preferred Time</option>
					<option value="8AM-12PM">8AM - 12AM</option>
					<option value="12PM-4PM">12PM - 4PM</option>
					<option value="4PM-8PM">4PM - 8PM</option>
				</select> -->
				<input type="submit" name="" class="btn btn-lg btn-full" value="SUBMIT">
				<p class="or_text">OR</p>
				<p class="call_text">If  You have Any <span>Emergency</span> Please Contact</p>
				<a class="call_icon" href="tel:1-800-233-5551">
					<img class="normal" src="<?php echo get_stylesheet_directory_uri(); ?>/images/slide-icon/call_icon.png" alt="Call Icon">
					<img class="hover" src="<?php echo get_stylesheet_directory_uri(); ?>/images/slide-icon/call_icon_hover.png" alt="Call Icon">
					<span>1-800-233-5551</span></a>
				<p class="call_text">Service is available between 9:30 AM to 6:30 PM Mon to Sat.</p>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    </div>
</div>
<script type="text/javascript">

    /*jQuery('#preferred-date').datetimepicker({
        minView: 2,
        timepicker:false,
      format: 'dd-mm-yyyy',
      autoclose: true,
      minDate : 0,
      maxDate:7
    });*/
$(function () {	
$( "#preferred-date" ).datepicker({
        minView: 2,
        timepicker:false,
      dateFormat: 'dd-mm-yy',
      autoclose: true,
      minDate : 1,
      maxDate:7
    });

});

</script>
