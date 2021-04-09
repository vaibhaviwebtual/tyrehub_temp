<?php

if ( !is_user_logged_in() )
 	{
      wp_redirect(get_permalink( get_option('woocommerce_myaccount_page_id') ).'/my-account/' );    
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
			
			<?php
			do_action( 'woocommerce_account_navigation' ); 
			//do_action( 'woocommerce_account_content' ); ?>
			
			<div class="woocommerce-MyAccount-content installer-account">			    
    		<span class="error-msg" style="color: red;"></span>
     		<div class="admin-url" hidden=""><?php echo admin_url('admin-ajax.php'); ?></div>
	<?php 
		if(isset( $wp_query->query_vars['customer-register'] ) ){
	?>
	<script src="<?=bloginfo('template_url');?>/assest/js/jquery.validate.min.js"></script>
	<style type="text/css">
		#register label.error {
		    border:none!important;
		}
	</style>
	<div class="u-column2 col-2">
		<h3 class="reg-title text-center"><?php esc_html_e( 'Customer Register', 'woocommerce' ); ?></h3>
		<div class="col-md-8 col-md-offset-2">
		<div id="register-custom">
			<form method="post" action="<?php echo get_site_url(); ?>/my-account/" class="woocommerce-form woocommerce-form-register customer-register custom-registartion-form" id="customer-register">

			<span class="error-msg" style="color: red;"></span>
			<div class="row">
			<div class="col-md-6">
				<p>
						<label for="first_name"><?php esc_html_e( 'First Name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
						<input type="text"  name="first_name" id="first_name" autocomplete="first_name" value="<?php echo ( ! empty( $_POST['first_name'] ) ) ? esc_attr( wp_unslash( $_POST['first_name'] ) ) : ''; ?>" />
				</p>
			</div>
			<div class="col-md-6">
				<p>
						<label for="last_name"><?php esc_html_e( 'Last Name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
						<input type="text"  name="last_name" id="last_name" autocomplete="last_name" value="<?php echo ( ! empty( $_POST['last_name'] ) ) ? esc_attr( wp_unslash( $_POST['last_name'] ) ) : ''; ?>" />
				</p>
		    </div>
		    </div>
		    <div class="row">
			<div class="col-md-6">
			<p>
					<label for="custom_mobile"><?php esc_html_e( 'Mobile No', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
					<input type="text"  name="custom_mobile" id="custom_mobile" autocomplete="custom_mobile" value="<?php echo ( ! empty( $_POST['custom_mobile'] ) ) ? esc_attr( wp_unslash( $_POST['custom_mobile'] ) ) : ''; ?>" minlength="10" maxlength="10" />
			</p>

			
			</div>

			<div class="col-md-6">
			<p>
					<label for="custom_email"><?php esc_html_e( 'Email Address', 'woocommerce' ); ?></label>
					<input type="text"  name="custom_email" id="custom_email" autocomplete="custom_email" value="<?php echo ( ! empty( $_POST['custom_email'] ) ) ? esc_attr( wp_unslash( $_POST['custom_email'] ) ) : ''; ?>" />
			</p>
		 </div>

		 </div>
		    <div class="row">
			<div class="col-md-12">
				<label for="custom_mobile"><?php esc_html_e( ' Which type of vehicle do you have?', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
				<p class="checkbox-part">
		        <label for="vehicle_type">Four Wheeler</label>
		        <input type="checkbox" name="vehicle_type[]" id="vehicle_type" value="four" />
		        </p>
		        <p class="checkbox-part">
		        <label for="vehicle_type1">Two Wheeler</label>
		        <input type="checkbox" name="vehicle_type[]" id="vehicle_type1" value="two" /> 
		        </p>
			</div>
		 </div>
		<input type="submit" name="Register" value="Register" class="btn">
		<div class="share-reg-link" style="padding: 0px 10px;text-align: right;">
			<a href="#" data-toggle="modal" data-target="#shareModal" ><i class="fa fa-share-alt" aria-hidden="true"></i> Share Register Link To Customer</a>
		</div>
		</form>

		</div>
		
		<div id="register-custom-otp" style="display: none;">
		<form method="post" action="<?php echo get_site_url(); ?>/my-account/" class="woocommerce-form woocommerce-form-register register-otp custom-registartion-form" id="register-otp">
			<span class="error-msg" style="color: red;"></span>
			<div class="row">
			<div class="col-md-12">
				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
						<label for="verify_otp"><?php esc_html_e( 'OTP', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
						<input type="text"  name="verify_otp" id="verify_otp" autocomplete="off" value="" />
				</p>
			</div>
			</div>
				<input type="submit" name="Verify" value="Verify" class="btn">
		</form>
	   </div>
	
	</div>
<?php } ?>
			</div>
		</div>
	</div>
</div>
<!-- Modal -->
<div id="shareModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Share Registration Link</h4>
      </div>
      	<form action="" method="post" id="share-frm" name="share-frm">
	      <div class="modal-body">
	      	<div class="row">
	  			<div class="col-md-12">
					<label for="share_mobile">Mobile&nbsp;<span class="required">*</span></label>
					<input type="text" name="share_mobile" id="share_mobile" autocomplete="share_mobile" value="">
				</div>
	      	</div>
	      </div>
	      <div class="modal-footer">
	        <button type="submit" name="share" value="share" class="btn btn-invert"><span>Share</span></button>
	      </div>
	    </div>
    </form>

  </div>
</div>
<?php
get_footer();
?>
<script type="text/javascript">
	jQuery().ready(function() {
    var admin_url=jQuery('.admin_url').text();
    // validate signup form on keyup and submit
    var validator = jQuery("#customer-register").validate({
      rules: {
        first_name: "required",
        last_name: "required",
        custom_mobile: {
          required: true,
          minlength: 10,
          number: true,
          remote:{
                url:admin_url,
                type: "post",
                data:
                {
                  action:'check_mobile',
                  custom_mobile: function()
                    {
                        return jQuery('#custom_mobile').val();
                    }
                }
          }
        },
        custom_email: {
          email: true,
          remote:{
                url:admin_url,
                type: "post",
                data:
                {
                  action:'check_emailid',
                  custom_email: function()
                    {
                        return jQuery('#custom_email').val();
                    }
                }
          }
        },
        'vehicle_type[]': {
            required: true,
            minlength: 1
           
          }
      },
      messages: {
        first_name: "Enter your firstname",
        last_name: "Enter your lastname",
        custom_mobile: {
          required: "Enter a mobile number",
          minlength: "Enter at least 10 digit",
          remote: jQuery.validator.format("{0} is already in use")
        },
        custom_email: {
          email: "Enter a valid email id",
          remote: jQuery.validator.format("{0} is already in use")
        },
        'vehicle_type[]': "You must check at least 1 box",
      },
      submitHandler: function(e) {
                  jQuery('#cover-spin').show(0);
                  var first_name=jQuery('#first_name').val();
                  var last_name=jQuery('#last_name').val();
                  var mobile_no=jQuery('#custom_mobile').val();
                  var email=jQuery('#custom_email').val();
                  //var vehicle_type=$('#vehicle_type').val();
                  var vehicle_type = [];
                  jQuery(':checkbox:checked').each(function(i){
                    vehicle_type[i] = jQuery(this).val();
                  });

                 jQuery.ajax({
                    type: "POST", 
                    url: admin_url,
                    data: {
                        'action':'customer_register',
                        'first_name' : first_name,
                        'last_name' : last_name,
                        'mobile_no' : mobile_no,    
                        'email' : email, 
                        'vehicle_type' : vehicle_type,              
                    },
                    success: function (data)
                    {
                      var objData = JSON.parse(data);
                      if(objData.error=='error'){
                      	jQuery('#custom_email').addClass('error');
                      	$('<label for="custom_mobile" class="error" style="display: block;">Enter a email</label>').insertAfter( "#custom_email" );
                      	//return false;
                      }else{
                        jQuery('.error-msg').text('');
                        jQuery('#register-custom').hide();
                        jQuery('#register-custom-otp').show();
                      }
                     
                      
                      jQuery('#cover-spin').hide(0); 

                    },
                });

      }
    });


    // OTP Verify

       var validator = jQuery("#register-otp").validate({
      rules: {
        verify_otp: "required",
      },
      messages: {
        verify_otp: "Enter your OTP",
      },
      submitHandler: function(e) {
                  jQuery('#cover-spin').show(0);
                  var verify_otp=jQuery('#verify_otp').val();
                 jQuery.ajax({
                    type: "POST", 
                    url: admin_url,
                    data: {
                        'action':'customer_register_otp_verify',
                        'verify_otp' : verify_otp              
                    },
                    success: function (data)
                    {
                      //var objData = JSON.parse(data);
                      if(data==0){
                        jQuery('#custom_email').addClass('error');
                      	jQuery('<label for="custom_mobile" class="error" style="display: block;">Enter valid OTP</label>').insertAfter("#verify_otp");
                      	//return false;
                      }else{
                        jQuery('.error-msg').text('');
                jQuery('<p style="color:green;">Thank you for Registering with tyrehub.com</p>').insertAfter(".reg-title" );
                        jQuery('#register-custom-otp').hide();
                        jQuery('#register-custom').show();
                        
                      }
                     
                      
                      jQuery('#cover-spin').hide(0); 

                    },
                });

      }
    });
   

    var sharefrm = jQuery("#share-frm").validate({
      rules: {
        share_mobile: "required",
      },
      messages: {
        share_mobile: "Enter customer mobile number",
      },
      submitHandler: function(e) {
                  jQuery('#cover-spin').show(0);
                  var share_mobile=jQuery('#share_mobile').val();
                 jQuery.ajax({
                    type: "POST", 
                    url: admin_url,
                    data: {
                        'action':'customer_share_link',
                        'share_mobile' : share_mobile              
                    },
                    success: function (data)
                    {
                    	jQuery('#shareModal').modal('hide'); 
                    	jQuery('#cover-spin').hide(0); 

                    },
                });

      }
    });

  });
</script>
