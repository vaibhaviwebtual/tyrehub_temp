jQuery(document).ready(function() {
    
    var user_flag_val = jQuery("#user_exit").val();
    var franchise_flag = jQuery("#franchise_flag").val();
    if(user_flag_val != '' && franchise_flag == 'yes')
    {
          //jQuery(".woocommerce-form__label-for-checkbox").hide();
          jQuery('span[id^="user_flag_span"]').remove();
          var carturl = jQuery("#cart_url").val();
          jQuery('#billing_phone').attr('readonly', true);
          jQuery("#billing_phone_field").after().append("<span id='user_flag_span'><a style='color:blue;' href=" + carturl + ">Change Phone Number</a></span>");
        
          jQuery(document).on('click','.check-gst-no',function(){ 
          });
  }else{
     
      if(franchise_flag == 'yes'){
        jQuery(document).on('click','.check-gst-no',function(){ 
           
        });
       //jQuery(".woocommerce-form__label-for-checkbox").hide();
      var carturl = jQuery("#cart_url").val();
      jQuery('#billing_phone').attr('readonly', true);
      jQuery("#billing_phone_field").after().append("<span id='user_flag_span'><a style='color:blue;' href=" + carturl + ">Change Phone Number</a></span>");
      }
    }
});
jQuery(document).on('click','.placeorder',function(){ 
         jQuery('#message-success').text('');
        //var user_login = jQuery('#created_user_id').val();
        if(jQuery('#created_user_id').val().length === 0 ) {
                jQuery('#message-success').text("Please First register!");
                return false;
        }
        var billing_first_name =  jQuery('#billing_first_name').val();
            var billing_last_name =  jQuery('#billing_last_name').val();
            var billing_phone =  jQuery('#billing_phone').val();
            var billing_email =  jQuery('#billing_email').val();
            var gst =  jQuery('#gst_no').val();
            
            var errors = false;
         if (billing_first_name == "" || billing_first_name ==null){
          jQuery("#billing_first_name").addClass('errormsg');
               errors= true;
        }else{
          jQuery("#billing_first_name").removeClass('errormsg');
             errors= false;
         }
         if (billing_last_name == "" || billing_last_name ==null){
          jQuery("#billing_last_name").addClass('errormsg');
               errors= true;
        }else{
          jQuery("#billing_last_name").removeClass('errormsg');
             errors= false;
         }
         var mobile = document.getElementById('billing_phone');
         var mobile_count = mobile.value.length;
         
         if (billing_phone == "" || billing_phone ==null || mobile_count!=10){
            //alert("Enter Valid Mobile Number");
              jQuery('span[id^="mobileval"]').remove();
              jQuery("#billing_phone_field").after().append("<span style='color:red;' id='mobileval'>Enter Valid Mobile Number</span>");
              
           errors= true;
        }else{
          jQuery('span[id^="mobileval"]').remove();
          jQuery("#billing_phone").removeClass('errormsg');
             errors= false;
         }
         
         var pEmails_tmp = jQuery('#billing_email').val();
         
        if (pEmails_tmp.length >= 50) {
            jQuery("#billing_email_field").after().append("<span style='color:red;' id='emailval'>Please Email should be minimum 50 length</span>");
            errors= true;
        }
        else
        {
          jQuery('span[id^="emailval"]').remove(); 
                jQuery("#billing_email").removeClass('errormsg');
             errors= false;
        }
         if(IsEmail(billing_email)==false)
         {
            //jQuery("#billing_email_field").after().append("<span style='color:red;' id='emailval'>Invalid Email</span>");
            jQuery('span[id^="emailval"]').remove(); 
                jQuery("#billing_email").removeClass('errormsg');
             errors= false;
            //errors= true;
         } else {
                jQuery('span[id^="emailval"]').remove(); 
                jQuery("#billing_email").removeClass('errormsg');
             errors= false;
         }
   if (jQuery('.gst-field-container').css('display') == 'block'){      
         var gstno = document.getElementById('gst_no');
         var compnay_name = document.getElementById('cmp_name');
         var cmp_address = document.getElementById('cmp_add');
         
         var gst_count = gstno.value.length;
         var cmp_name = compnay_name.value.length;
         var cmp_add = cmp_address.value.length;
         if (jQuery('.gst-field-container').css('display') == 'block' && gst_count!=15 || gst == "" || gst ==null){
            //alert("Enter Valid Mobile Number");
              jQuery('span[id^="gstval"]').remove();
              jQuery("#gst_no_field").after().append("<span style='color:red;' id='gstval'>Enter Valid GST No</span>");
              
           errors= true;
        }else{
          jQuery('span[id^="gstval"]').remove();
          jQuery("#gst_no").removeClass('errormsg');
             errors= false;
         }
         if (jQuery('.gst-field-container').css('display') == 'block' && cmp_name == "" || cmp_name ==null){
            //alert("Enter Valid Mobile Number");
              jQuery('span[id^="campval"]').remove();
              jQuery("#cmp_name_field").after().append("<span style='color:red;' id='campval'>Enter Comapny Name</span>");
              
           errors= true;
          }else{
            jQuery('span[id^="campval"]').remove();
            jQuery("#cmp_name").removeClass('errormsg');
               errors= false;
           }
           if (jQuery('.gst-field-container').css('display') == 'block' && cmp_add == "" || cmp_add ==null){
            //alert("Enter Valid Mobile Number");
              jQuery('span[id^="addresspval"]').remove();
              jQuery("#cmp_add_field").after().append("<span style='color:red;' id='addresspval'>Enter Comapny Address</span>");
              
           errors= true;
          }else{
            jQuery('span[id^="addresspval"]').remove();
            jQuery("#cmp_add").removeClass('errormsg');
               errors= false;
           }
           if (jQuery('.gst-field-container').css('display') == 'block' && cmp_add == "" || cmp_add ==null){
            //alert("Enter Valid Mobile Number");
              jQuery('span[id^="addresspval"]').remove();
              jQuery("#cmp_add_field").after().append("<span style='color:red;' id='addresspval'>Enter Comapny Address</span>");
              
           errors= true;
          }else{
            jQuery('span[id^="addresspval"]').remove();
            jQuery("#cmp_add").removeClass('errormsg');
               errors= false;
           }
           
  }
        if((billing_first_name == "" || billing_first_name ==null)  || (billing_last_name == "" || billing_last_name ==null)|| (billing_phone=='' || billing_phone==null)) {
          errors=true;
        }else{
           errors=false;
        }
        // if(errors == true){
        //     return false;
        // }else{
        //     return true;
        // }
        if (jQuery('.gst-field-container').css('display') == 'block'){
            
            if((cmp_name == "" || cmp_name ==null) || (gst_count == "" || gst_count ==null) || (cmp_add == "" || cmp_add ==null)){
                
                //console.log(gst_count);
                errors=true;
                
            }else{
                
                errors=false;
            }
        }
        
        var admin_url = jQuery('.admin_url').text();
        var created_user_id = jQuery('#created_user_id').val();
        var franchise_id = jQuery('#franchise_id').val();
        var billing_first_name =  jQuery('#billing_first_name').val();
        var billing_last_name =  jQuery('#billing_last_name').val();
        var billing_phone =  jQuery('#billing_phone').val();
        var billing_email =  jQuery('#billing_email').val();
         var cmp_name =  jQuery('#cmp_name').val();
         var cmp_add =  jQuery('#cmp_add').val();
         var gst_no =  jQuery('#gst_no').val();
         var gst_email =  jQuery('#gst_email').val();
          var payment_type = jQuery("input[name='payment_type']:checked").val();
     if(jQuery("input:radio[name='payment_type']").is(":checked") && errors == false) { 
            jQuery('#cover-spin').show(0);
            //jQuery('#placeorder').removeAttr("disabled");
            jQuery('#placeorder').hide();
            
            jQuery.ajax({
                type: "POST",
                url: admin_url,
                data: {
                    action: 'save_franchies_info_from_cart',
                    created_user_id : created_user_id,
                    billing_first_name:billing_first_name,
                    billing_last_name:billing_last_name,
                    billing_phone : billing_phone,
                    billing_email : billing_email,
                    cmp_name : cmp_name,
                    cmp_add : cmp_add,
                    gst_no : gst_no,
                    gst_email : gst_email,
                    payment_type : payment_type,
                },
                success: function (data)
                {    
                    //var json = JSON.parse(data);
                   window.location=data;
                   jQuery('#cover-spin').hide(0);
                    
                },
            });
    }else{
      
        jQuery('#pay_radio_error').text("Please select at least one payment method.").css('color','red');
        jQuery('#cover-spin').hide(0);
    }
});
function IsEmail(email) {
  var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  if(!regex.test(email)) {
    return false;
  }else{
    return true;
  }
}
function extractEmails(text) {
    return text.match(/([a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-zA-Z0-9._-]+)\s/gi);
}
/// create custome user on franchies checkout page
jQuery(document).on('click','#create-account-frachise',function(){
        jQuery('#message-success').text('');
        var admin_url = jQuery('.admin_url').text();
            var billing_first_name =  jQuery('#billing_first_name').val();
            var billing_last_name =  jQuery('#billing_last_name').val();
            var billing_phone =  jQuery('#billing_phone').val();
            var billing_email =  jQuery('#billing_email').val();
            var errors = false;
         if (billing_first_name == "" || billing_first_name ==null){
          jQuery("#billing_first_name").addClass('errormsg');
               errors= true;
        }else{
          jQuery("#billing_first_name").removeClass('errormsg');
             errors= false;
         }
         if (billing_last_name == "" || billing_last_name ==null){
          jQuery("#billing_last_name").addClass('errormsg');
               errors= true;
        }else{
          jQuery("#billing_last_name").removeClass('errormsg');
             errors= false;
         }
         if (billing_phone == "" || billing_phone ==null){
          jQuery("#billing_phone").addClass('errormsg');
               errors= true;
        }else{
          jQuery("#billing_phone").removeClass('errormsg');
             errors= false;
         }
        if((billing_first_name == "" || billing_first_name ==null) || (billing_last_name == "" || billing_last_name ==null)|| (billing_phone=='' || billing_phone==null)) {
          errors=true;
        }else{
           errors=false;
        }
        if(errors == true){
            return false;
        }else{
            //jQuery('#cover-spin').show(0);
            jQuery.ajax({
                type: "POST",
                url: admin_url,
                data: {
                    action: 'save_create_account_frachise',
                    billing_first_name : billing_first_name,
                    billing_last_name : billing_last_name,
                    billing_phone : billing_phone,
                    billing_email : billing_email,
                },
                success: function (data)
                {
                    var data1 = jQuery.parseJSON(data);
                    if(data1.result == 'success')
                    {
                        jQuery('.frachise-user').hide();
                        //jQuery('#register-custom-otp').show();
                        jQuery('#created_user_id').val(data1.user_id);
                    	  /*jQuery('#created_user_id').val(data1.userdata.data.ID);*/
                        //jQuery('#billing_first_name').val(data1.first_name);
                        //jQuery('#billing_last_name').val(data1.last_name);
                        /*jQuery('#billing_phone').val(user_login);*/
                        //jQuery('#billing_email').val(billing_email);
                        jQuery('#cover-spin').hide(0);
                         jQuery('#message-success').text(data1.message).css('color','green');
                        jQuery('#placeorder').removeAttr("disabled");
                            jQuery('#placeorder').show();
                    }
                    if(data1.result == 'error')
                    {
                        jQuery('#message').text(data1.message).css('color','red');
                        jQuery('#cover-spin').hide(0);
                    }
                },
            });
        }
});
jQuery(document).on('click','#resendotpbtn',function(){
        jQuery('#message-success').text('');
        var admin_url = jQuery('.admin_url').text();
            var billing_first_name =  jQuery('#billing_first_name').val();
            var billing_last_name =  jQuery('#billing_last_name').val();
            var billing_phone =  jQuery('#billing_phone').val();
            var billing_email =  jQuery('#billing_email').val();
            var errors = false;
         if (billing_first_name == "" || billing_first_name ==null){
          jQuery("#billing_first_name").addClass('errormsg');
               errors= true;
        }else{
          jQuery("#billing_first_name").removeClass('errormsg');
             errors= false;
         }
         if (billing_last_name == "" || billing_last_name ==null){
          jQuery("#billing_last_name").addClass('errormsg');
               errors= true;
        }else{
          jQuery("#billing_last_name").removeClass('errormsg');
             errors= false;
         }
         if (billing_phone == "" || billing_phone ==null){
          jQuery("#billing_phone").addClass('errormsg');
               errors= true;
        }else{
          jQuery("#billing_phone").removeClass('errormsg');
             errors= false;
         }
        if((billing_first_name == "" || billing_first_name ==null) || (billing_last_name == "" || billing_last_name ==null)|| (billing_phone=='' || billing_phone==null)) {
          errors=true;
        }else{
           errors=false;
        }
        if(errors == true){
            return false;
        }else{
            //jQuery('#cover-spin').show(0);
            jQuery.ajax({
                type: "POST",
                url: admin_url,
                data: {
                    action: 'save_create_account_frachise',
                    billing_first_name : billing_first_name,
                    billing_last_name : billing_last_name,
                    billing_phone : billing_phone,
                    billing_email : billing_email,
                },
                success: function (data)
                {
                    var data1 = jQuery.parseJSON(data);
                    if(data1.result == 'success')
                    {
                        jQuery('.frachise-user').hide();
                        //jQuery('#register-custom-otp').show();
                        jQuery('#created_user_id').val(data1.user_id);
                        /*jQuery('#created_user_id').val(data1.userdata.data.ID);*/
                        //jQuery('#billing_first_name').val(data1.first_name);
                        //jQuery('#billing_last_name').val(data1.last_name);
                        /*jQuery('#billing_phone').val(user_login);*/
                        //jQuery('#billing_email').val(billing_email);
                        jQuery('#cover-spin').hide(0);
                    }
                    if(data1.result == 'error')
                    {
                        jQuery('#message').text(data1.message).css('color','red');
                        jQuery('#cover-spin').hide(0);
                    }
                },
            });
        }
});
jQuery(document).on('click','#verifyotpbtn', function()
    {
        var admin_url = jQuery('.admin_url').text();
        var otp = jQuery('#verify_otp').val();
        var user_id = jQuery('#created_user_id').val();
            var billing_first_name =  jQuery('#billing_first_name').val();
            var billing_last_name =  jQuery('#billing_last_name').val();
            var billing_phone =  jQuery('#billing_phone').val();
            var billing_email =  jQuery('#billing_email').val();
        if (otp == "" || otp ==null){
          jQuery("#verify_otp").addClass('errormsg');
               errors= true;
        }else{
          jQuery("#verify_otp").removeClass('errormsg');
             errors= false;
         }
      if((otp == "" || otp ==null)) {
            errors=true;
        }else{
            errors=false;
        }
        if(errors == true){
            return false;
        }else{
        jQuery('#cover-spin').show(0);
        jQuery.ajax({
                    type: "POST",
                    url: admin_url,
                    data: {
                        action: 'verify_otp_chk',
                        otp : otp,
                        billing_first_name : billing_first_name,
                        billing_last_name : billing_last_name,
                        billing_phone : billing_phone,
                        billing_email : billing_email,
                    },
                    success: function (data)
                    {
                        jQuery('#register-custom-otp').hide();
                        data = data.replace(/(\r\n|\n|\r)/gm, "");
                        jQuery('.error-msg').text('');
                        if(data>0)
                        {
                            jQuery('.frachise-user').hide();
                            jQuery('#created_user_id').val(data);
                            jQuery('#message-success').text('Yor are registered successfully!. Thank You').css('color','green');
                            jQuery('#placeorder').hide();
                            jQuery('#placeorder').removeAttr("disabled");
                            jQuery('#placeorder').show();
                            //jQuery('.custom-registartion-form').html('Yor are registered successfully Please Login!. Thank You </br> <br> <a href="https://www.tyrehub.com/my-account" class="btn btn-invert"><span>Login</span></a>');
                            jQuery('.error-msg').text('');
                        }
                        else{
                            jQuery('.frachise-user').hide();
                            jQuery('#register-custom-otp').show();
                            jQuery('.error-msg').text('Invalid OTP!');
                        }
                      //  $('.custom-registartion-form').html(html);
                      jQuery('#cover-spin').hide(0);
                    },
                });
            }
    });
jQuery(document).on('click','#offline_order_complated,#offline_order_pending', function(){
        var order_id = jQuery('#th_orderid').val();
        var order_status = jQuery(this).attr("order-status");
        var admin_url = jQuery('.admin_url').text();
        jQuery('#cover-spin').show(0);
        jQuery.ajax({
                    type: "POST",
                    url: admin_url,
                    datatype: "html",
                    data: {
                        action: 'change_the_offline_status',
                        order_id : order_id,
                        order_status : order_status,
                    },
                    success: function (res,status)
                    {
                        //jQuery('#thankyou_part1').show();
                        location.reload();
                        jQuery('#cover-spin').show(0);
                    },
                });
});
jQuery(document).on('change', '.status_ch', function(){
    var order_id = jQuery(this).attr("data-sel");
    var order_status = jQuery(this).val();
    var admin_url = jQuery('.admin_url').text();
        jQuery('#cover-spin').show(0);
        jQuery.ajax({
                    type: "POST",
                    url: admin_url,
                    datatype: "html",
                    data: {
                        action: 'change_the_offline_status',
                        order_id : order_id,
                        order_status : order_status,
                    },
                    success: function (res,status)
                    {
                        //jQuery('#thankyou_part1').show();
                        jQuery('#cover-spin').hide(0);
                    },
                });
});
jQuery(document).on('change', '.order_status_ch', function(){
    var order_id = jQuery(this).attr("data-sel");
    var order_status = jQuery(this).val();
    var redirect_url = jQuery(this).attr("data-url");
    var admin_url = jQuery('.admin_url').text();
        jQuery('#cover-spin').show(0);
        jQuery.ajax({
                    type: "POST",
                    url: admin_url,
                    datatype: "html",
                    data: {
                        action: 'change_order_pending_status',
                        order_id : order_id,
                        order_status : order_status,
                    },
                    success: function (res,status)
                    {
                        jQuery('#cover-spin').hide(0);
                        window.location.href = redirect_url; 
                    },
                });
});
