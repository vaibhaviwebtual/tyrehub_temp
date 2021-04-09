<?php
function store_created_user_function() {
	ob_start();
?>
<script type="text/javascript">
jQuery(document).ready(function($) {
  var admin_url=jQuery('.admin_url').text();
     jQuery('#send').click(function(){
  
         // validate signup form on keyup and submit
    var validator = jQuery("#frm-storeuser").validate({
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
      // specifying a submitHandler prevents the default submit, good for the demo
      submitHandler: function(e) {
                  jQuery('#cover-spin').show(0);
                  var first_name=jQuery('#first_name').val();
                  var last_name=jQuery('#last_name').val();
                  var mobile_no=jQuery('#custom_mobile').val();
                  var email=jQuery('#custom_email').val();
                  var user_id=jQuery('#user_id').val();
                  var installer_id=jQuery('#installer_id').val();
                  //var vehicle_type=$('#vehicle_type').val();
                  var vehicle_type = [];
                  jQuery(':checkbox:checked').each(function(i){
                    vehicle_type[i] = jQuery(this).val();
                  });

                 jQuery.ajax({
                    type: "POST", 
                    url: admin_url,
                    data: {
                        'action':'store_users',
                        'first_name' : first_name,
                        'last_name' : last_name,
                        'user_id' : user_id,
                        'installer_id' : installer_id,
                        'mobile_no' : mobile_no,    
                        'email' : email, 
                        'vehicle_type' : vehicle_type,              
                    },
                    success: function (data)
                    {
                     
                        jQuery('.error-msg').text('');
                        jQuery('#register-custom').hide();
                        jQuery('#register-custom-otp').show();
                      
                      jQuery('#cover-spin').hide(0); 
                    },
                });

      }
    });

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
                jQuery('<p style="color:green; text-align: center;">Thank you for Registering with tyrehub.com</p>').insertAfter(".reg-title" );
                        jQuery('#register-custom-otp').hide();
                        jQuery('#frm-storeuser').show();
                        jQuery('#frm-storeuser')[0].reset();
                      }
                     
                      
                      jQuery('#cover-spin').hide(0); 

                    },
                });

      }
    });


});
</script>
<style type="text/css">
.frm-storeuser label.error { border:none!important; }

.otp-franchisee label.error { border:none!important;}

#register-otp label.error { border:none!important; }
</style>

<div class="u-column2 col-2">
	<h2 class="reg-title text-center"><?php esc_html_e( 'Registration', 'woocommerce' ); ?></h2>
    	<div class="col-md-6 col-md-offset-3">
        <div id="register-custom">
        <form method="post" action="#" class="woocommerce-form woocommerce-form-register frm-storeuser custom-registartion-form" id="frm-storeuser">

      <span class="error-msg" style="color: red;"></span>
      <div class="row">
      <div class="col-md-6">
        <p>
            <label for="first_name"><?php esc_html_e( 'First Name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
            <input type="text"  name="first_name" id="first_name" autocomplete="first_name" value="" />
        </p>
      </div>
      <div class="col-md-6">
        <p>
            <label for="last_name"><?php esc_html_e( 'Last Name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
            <input type="text"  name="last_name" id="last_name" autocomplete="last_name" value="" />
        </p>
        </div>
        </div>
        <div class="row">
      <div class="col-md-6">
      <p>
          <label for="custom_mobile"><?php esc_html_e( 'Mobile No', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
          <input type="text"  name="custom_mobile" id="custom_mobile" autocomplete="custom_mobile" value="" minlength="10" maxlength="10" />
      </p>

      
      </div>

      <div class="col-md-6">
      <p>
          <label for="custom_email"><?php esc_html_e( 'Email Address', 'woocommerce' ); ?></label>
          <input type="text"  name="custom_email" id="custom_email" autocomplete="custom_email" value="" />
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
     <input type="hidden" name="action" value="store_users">
        <input type="hidden" name="user_id" id="user_id" value="<?=$_GET['uid']?>">
        <input type="hidden" name="installer_id" id="installer_id" value="<?=$_GET['instaid']?>">
    <input type="submit" name="Register" id="send" value="Register" class="btn">
    </form>

      
    </div>
  <div id="register-custom-otp" style="display: none;">
    <form method="post" action="<?php echo get_site_url(); ?>/my-account/" class="woocommerce-form woocommerce-form-register register-otp custom-registartion-form" id="register-otp">
      <span class="error-msg" style="color: red;"></span>
      <div class="row">
      <div class="col-md-12">
        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="verify_otp"><?php esc_html_e('OTP','woocommerce'); ?>&nbsp;<span class="required">*</span></label>
            <input type="text"  name="verify_otp" id="verify_otp" autocomplete="off" value="" />
        </p>
      </div>
      </div>
        <input type="submit" name="Verify" value="Verify" class="btn">
    </form>
     </div>
 </div>
</div>
<?php
return ob_get_clean(); }
add_shortcode('store_created_user', 'store_created_user_function');


add_action( 'wp_ajax_store_users', 'store_users' );
add_action( 'wp_ajax_nopriv_store_users', 'store_users');
function store_users() {

 global $woocommerce , $wpdb;
  extract($_POST);
  $otp = rand(100000,999999);
  if ( !username_exists( $mobile_no ))
  {
    
    if(!email_exists($email)){
      $userdata = array (
      'user_login' =>$mobile_no,
      'user_pass' =>$mobile_no,
      'user_email' =>$email,
      'role' => 'customer',
      'user_nicename' =>$first_name.' '.$last_name,
      'first_name' =>$first_name,
      'last_name'=>$last_name,
      'display_name' =>$first_name.' '.$last_name,
      'nickname' =>$first_name.' '.$last_name,
    );

    $new_user_id = wp_insert_user( $userdata );
    update_user_meta( $new_user_id, '_active', 0 );
    update_user_meta( $new_user_id, 'vehicle_type',implode(',',$vehicle_type));
    update_user_meta( $new_user_id, 'custom_mobile', sanitize_text_field( $mobile_no ) );
    update_user_meta( $new_user_id, 'franchise_id',$user_id);
    update_user_meta( $new_user_id, 'referral_type','franchise');

    $ch1 = curl_init();
    $message = "We have receive your request for registration your otp is ".$otp." Thank You Tyrehub Team";
    $message = str_replace(' ', '%20', $message);
    $url_string="http://nimbusit.co.in/api/swsend.asp?username=t1ankitshah&password=81957032&sender=TYREHB&sendto=91".$mobile_no."&message=".$message;
    curl_setopt($ch1, CURLOPT_URL, $url_string);
    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch1, CURLOPT_CUSTOMREQUEST, "GET");

    $result1 = curl_exec($ch1);
    curl_close ($ch1);

    $update = $wpdb->get_results("UPDATE `wp_users` SET otp = '$otp' WHERE ID = '$new_user_id'");
    $SQL="SELECT * FROM th_installer_data WHERE user_id='".get_current_user_id()."'";
    $insta=$wpdb->get_row($SQL);
    $wpdb->insert('th_customer_register',array (
        'user_id' => $new_user_id,
        'parent_id' => $user_id,
        'installer_id' =>$installer_id,
        'first_name' =>$first_name,
        'last_name'=>$last_name,
        'mobile' =>$mobile_no,
        'email' =>$email,
        'campaing_name' =>'installer',
        'vehicle_type'=>implode(',',$vehicle_type)
        ));

      $result = array('result' => 'ok', 'user_id' => $new_user_id );
      echo json_encode($result);
    }else{
      $result = array('result' => 'error', 'message' => 'Email already exist!');
      echo json_encode($result);
    }
    
  }else{
    $result = array('result' => 'error', 'message' => 'Mobile number is already registered.');

      echo json_encode($result);
  }

  die;
}