jQuery(document).ready(function($) 
{
	var admin_url = $('.admin_url').text();
    var cart_item_id = $('.cart_item_id').text();
    var session_id = $('.session-key').text();
    var site_url = $('.site-url').text();
    // when click on select installer
    $(document).on('click','.select-installer',function()
    {
        url = $('.site-url').text();
        var product_id = $(this).attr('data-id');
        var vehicle_type = $(this).attr('data-vehicle');
        var cart_item_id = $(this).attr('data-cart-item-id');
        var total_qty = $(this).attr('data-total-qty');
    window.location.href = url+'/online-tyre-services-partner?product_id='+product_id+'&cart_item_id='+cart_item_id+'&total_qty='+total_qty;    
    });

    // when click on change service button
    $('#133_service_modal').on('hidden.bs.modal', function () { 
        alert();
        $('.modal-backdrop').css('display','none');
    });

	$(document).on('click','.change-services',function()
	{ 

        var single_prd_class = $(this).parents('.service-row');
        $(single_prd_class).find('.modal-backdrop').css('display','block');
        var modal = $(this).attr('data-target');
        console.log(modal);
        var vehicle_type = $(modal).find(".vehicle_type input:checked").val();
        console.log(vehicle_type);
        var tyre = $(modal).find('.modal-body .cart_tyre').val();
        if(vehicle_type)
        {
            $(modal).find('.next-to-service-voucher').removeAttr('disabled');
   	    }
	   	var rate = 0;

	   	$(modal).find(".modal-body .service_list").each(function()
        {
            	
            var self = $(this);
            if($(this).find('.service_name').is(':checked'))
            {
                current_rate = $(this).find('.service_rate').val();                
                service_name = $(this).find('.sname').text();
                if (typeof tyre === "undefined") {
                    rate_per_tyre = current_rate;
                }
                else{
                    if(service_name == 'Wheel alignment'){
                        rate_per_tyre = current_rate;
                    }
                    else{
                        rate_per_tyre = current_rate * tyre;
                      
                         $(self).find('.select_tyre').html(' x '+tyre);
                    }
                }
               
                if(rate == 0){
                    rate = rate_per_tyre;
                }
                else{
                    rate = parseInt(rate) + parseInt(rate_per_tyre);
                }
            }
        });
	    $('.service_voucher_total .amount').text(rate);
        $('.review-installer .total-pay .amount').text(rate);

        $(modal).find('.modal-body .screen').css('display','none');
        $(modal).find('.modal-body .select-car-type').css('display','block');
	});

	
    $(document).on('change','.vehicle_type input',function(){
		
    	var modal_body = $(this).parents('.modal-body');
        var abc = $(modal_body).find(".vehicle_type input:checked").val();
		var selected_vehicle = $(modal_body).find('.selected-vehicle').text();
        var product_id = $(modal_body).find(".product-id").text();
		var loader = "<div class='modal-loader'><img src='https://www.tyrehub.com/loading.gif' width='20' height='20' /></div>";
         var cart_item_id = $(modal_body).find('.cart_item_id').text();
		
        $.ajax({
                type: "POST", 
                url: admin_url,
                data: {
                    action: 'demo',
                    abc : abc,
                    product_id : product_id,
					selected_vehicle : selected_vehicle, 
                    cart_item_id : cart_item_id, 
                },
				beforeSend:function() {                    
                    $(modal_body).find('.data-body').html('');
                    $(modal_body).find('.data-body').html(loader);
					$(modal_body).find('.service_voucher_total').css('display','none');
                 },
                success: function (data)
                {               
                    $(modal_body).find('.data-body').html(data);
					price_calculation(modal_body);
                    },
        }); 
    });
    
    $(document).on('click','.custom-remove .delete',function(e)
    {
       
        e.preventDefault();
        var installer_table_id = $(this).attr('data-cart-item-installer-id');
        var cart_key = $(this).attr('data-cart_key');
        var session_id = $(this).attr('data-session_id');
        var link = $(this).parent().find('.link').text();
        var self = $(this);
       
        if(confirm('Are you sure you want to remove/change the selected Tyre or Installer?'))
        {
            $(self).attr('href',link);
            $.ajax({
                type: "POST", 
                url: admin_url,
                data: {
                    action: 'remove_service',
                    installer_table_id : installer_table_id,
                    cart_key : cart_key,
                    session_id : session_id,
                },
                success: function (data)
                {    
                    window.location.href = link;
                    $(this).parents('.service-row').css('display','none'); 
                },
            });
        }
        else
        {        
        }        
    });

	
	$(document).on('click','.cart-item-service-model .service_name',function()
    {
        var rate = 0;
        var service = $(this).parent().find('.sname').text();
          if(service == "Wheel Balancing")
          {
            var tyre = $('select.cart_tyre').val();
           
            if(tyre == 0){
                $(this).removeAttr('checked','checked');
            }
          }
      
        var modal_body_class = $(this).parents('.modal-body');
        $(modal_body_class).find('.review-installer .service-name-list').html('');
                
           price_calculation(modal_body_class);
	       
    });

    
    $(document).on('click','.cart_tyre',function() 
    {    	
        
        $(this).parents('.service_type').find('input.service_name').attr("checked", "checked");
        var modal_body_class = $(this).parents('.modal-body');
        $(modal_body_class).find('.review-installer .service-name-list').html('');           
            
        var value = $(this).val();
        console.log(value); 
        if(value != 0){
           $(this).parents('.service_type').find('input.service_name').attr("checked", "checked");
         
        } 
        else{
            $(this).parents('.service_type').find('input.service_name').removeAttr("checked", "checked");
        }
        price_calculation(modal_body_class);

    });


    function price_calculation(modal_body_class)
    {	
    	var rate = 0;
		var service_name = '';
		$(modal_body_class).find('.review-installer .service-name-list').html('<table><tr><th>Service Name</th><th>Charge per tyre</th><th>Amount</th></tr></table>');
    	$(modal_body_class).find(".service_list").each(function()
        {
            	
            	var service_name_arr = [];
            	
                var self = $(this);
                if($(this).find('.service_name').is(':checked'))
                {
                    current_rate = $(this).find('.service_rate').val();                
                    service_name = $(this).find('.sname').text();
                    
                    var cart_tyre = $(this).find('.cart_tyre').val();
                    rate_per_tyre = current_rate * cart_tyre;
                            
                    
                    $(this).find('.service-amount').attr('data-amount' , rate_per_tyre);
                   
              		if(service_name == 'Tyre Fitment')
                    {
                        var service_name_list = '<tr><td>'+service_name+'</td><td> ₹'+current_rate+' x '+cart_tyre+' Tyre </td> <td>₹'+rate_per_tyre+'</td></tr>';
                    }
                    else
                    {
                        var service_name_list = '<tr><td>'+service_name+'</td><td> ₹'+current_rate+'</td> <td>₹'+rate_per_tyre+'</td></tr>';
                 
                    }

                    $(modal_body_class).find('.review-installer .service-name-list table').append(service_name_list);
                    service_name_arr.push(service_name);
                    if(rate == 0){
                        rate = rate_per_tyre;
                    }
                    else{
                        rate = parseInt(rate) + parseInt(rate_per_tyre);
                    }
                }
            });

			if(service_name != '')
            {
               
                $('.service_voucher_total').css('display','block');
                $('.next-to-review').removeAttr('disabled');
            }
            else{
                $('.next-to-review').attr('disabled','disabled');
                $('.service_voucher_total').css('display','none');
            }
		
    		$('.service_voucher_total .amount').text(rate);
	        $('.review-installer .total-pay .amount').text(rate);
    }
	// when confirm services 

	$(document).on('click','.confirm-services',function()
    { 
        var service_list = [];
        var tyre_list = [];
        var service_rate_list = [];
        var service_id_arr = [];

        var main = $(this);
        var modal_body = $(this).parents('.modal-body');
        var cart_item_id = $(modal_body).find('.cart_item_id').text();
        var product_id = $(modal_body).find('.product-id').text();
        var vehicle_id = $(modal_body).find(".vehicle_type input:checked").val();
        
        $(modal_body).find(".service_list").each(function()
        {
            var self = $(this);
            
            var tyre_count = '';
            if($(this).find('.service_name').is(':checked'))
            {
                service_name = $(self).find('.sname').text();
                tyre_count = $(self).find('.cart_tyre').val();
                service_rate = $(self).find('input.service_rate').val();
                service_id = $(self).find('input.service_name').val();

                service_list.push(service_name);
                tyre_list.push(tyre_count);
                service_rate_list.push(service_rate);
                service_id_arr.push(service_id);
            }
        });
        
        $.ajax({
                    type: "POST", 
                    url: admin_url,
                    data: {
                        action: 'save_service_info',
                        service_list : service_list, 
                        cart_item_id : cart_item_id,
                        tyre_list : tyre_list,
                        vehicle_id : vehicle_id,
                        service_rate_list : service_rate_list,
                        session_id : session_id,
                        product_id : product_id,
                        service_id : service_id_arr, 
                    },
                    success: function (data)
                    {
                        $(main).parents('.product-name').find('.product-service-list').html(data);
                        url = site_url+'/cart';
                        window.location.replace(url);
                    },
                });
    });

     $(document).on("keypress", ".delivery_pincode" ,function(e) {
     
	        if (e.keyCode == 13) {
	        
	            $(this).parent().parent().parent().parent().find('.confirm-delivery').trigger('click');
	            e.preventDefault();
	        }
	});
    $(document).on('click','.confirm-delivery',function() 
    {       
        // service_price_calculation();
        //  alert();
        var thisnew = $(this);
        var cart_key = $(this).parents('.modal-body').find('.cart_item_id').text();
        var pincode = $(this).parents('.modal-body').find('.delivery_pincode').val();
        var session_id = $(this).parents('.modal-body').find('.session-id').text();
        var product_id =  $(this).parents('.modal-body').find('.product-id').text();
         $(thisnew).parents('.modal-body').find('.modal-footer .info-delivery').html('').html("<img src='https://www.tyrehub.com/loading.gif' width='10' height='10' />");

        $.ajax({
                    type: "POST", 
                    url: admin_url,
                    data: {
                        action: 'save_home_or_installer',
                        session_id : session_id, 
                        cart_key : cart_key,
                        product_id : product_id,
                        pincode :    pincode,
                },
                    success: function (data)
                {
                    console.log(data);
                    data = data.replace(/(\r\n|\n|\r)/gm, "");
                    if(data == "0"){
                        $(thisnew).parents('.modal-body').find('.modal-footer .info-delivery').html('').html("<span style='color:red'>Sorry we don't deliver tyres to your location!</span></br><span style='font-size: 15px;'> <strong>We will come to your city very soon, if you still like to buy tyres from us today with an addition delivery cost, please call us on 1-800-233-5551 </strong></span><button class='close btn btn-invert' data-dismiss='modal'><span>OK</span></button>");
                    }else{
                         url = site_url+'/cart';
                         window.location.replace(url);

                    }
                    // $("[name='update_cart']").trigger("click"); 
                    // $(main).parents('.product-name').find('.product-service-list').html(data);
                    //url = site_url+'/cart';
                   // window.location.replace(url);

                },
                });

    });

    $(document).on('click','.session-deliver-to-home',function() 
    {
        //$('#cover-spin').show(0);
        var thisnew = $(this);
        var cart_key = $(this).attr('data-cart_key');
        var pincode = $(this).attr('data-pincode');
        var session_id = $(this).attr('data-session_id');
        var product_id =  $(this).attr('data-id');
         
        $.ajax({
                    type: "POST", 
                    url: admin_url,
                    data: {
                        action: 'save_home_or_installer',
                        session_id : session_id, 
                        cart_key : cart_key,
                        product_id : product_id,
                        pincode :    pincode,
                    },
                    success: function (data)
                    {
                        console.log(data);
                        data = data.replace(/(\r\n|\n|\r)/gm, "");
                        if(data == "0"){
                           // $(thisnew).parents('.modal-body').find('.modal-footer .info-delivery').html('').html("<span style='color:red'>Sorry we don't deliver tyres to your location!</span></br><span style='font-size: 15px;'> <strong>We will come to your city very soon, if you still like to buy tyres from us today with an addition delivery cost, please call us on 1-800-233-5551 </strong></span><button class='close btn btn-invert' data-dismiss='modal'><span>OK</span></button>");
                        }else{
                             url = site_url+'/cart';
                             window.location.replace(url);
                             //$('#cover-spin').hide(0);

                        }
                    },
                });
    });

    $(document).on('click','.cart-item-delivery-model button.close',function(e) 
    {
         $(this).parents('.modal-body').find('.modal-footer .info-delivery').html('');
    });
     
    $(document).on('click','.checkout-button',function(e) 
    {
       // alert();
       var count = 0;
       $('table.cart tbody tr.cart_item').each(function()
        {
            
            var destination = $(this).find('.product-name span.destination').text();
             if($(this).find('.product-name span.destination').length !== 0)
            {
                if(destination == ''){
                    count = count + 1;
                    $(this).find('.product-name .error-msg').text('*Please Select one of the following option');
                }
            }
        });
      console.log(count); 
     //  e.preventDefault();
       if(count > 0){
            $('.service-validation-msg').text('*Please check cart some information missing');
            e.preventDefault();
       }
    });

    $(document).on('click','.remove-voucher',function(e) 
    {
        var voucher_id = $(this).attr('data-voucher-id');
        var cart_key = $(this).attr('data-cart-key');
        if(confirm('Are you sure you want to remove the selected  Services?'))
        {
             $('#cover-spin').show(0);
            $.ajax({
                    type: "POST", 
                    url: admin_url,
                    data: {
                        action: 'remove_service_voucher',
                        voucher_id : voucher_id,
                        cart_key : cart_key,     
                    },
                    success: function (data)
                    {               
                      location.reload();
                       $('#cover-spin').hide(0);
                    },
                  });
        }
    });

   
	// next - previous button action start
	$(document).on('click','.cart-item-service-model .next-to-service-voucher',function() 
    {      
		
       // service_price_calculation();
       var modal_body = $(this).parents('.modal-body');
        var abc = $(modal_body).find(".vehicle_type input:checked").val();
        var selected_vehicle = $(modal_body).find('.selected-vehicle').text();
        var product_id = $(modal_body).find(".product-id").text();
        var loader = "<div class='modal-loader'><img src='https://www.tyrehub.com/loading.gif' width='20' height='20' /></div>";
        var cart_item_id = $(modal_body).find('.cart_item_id').text();
       
        $.ajax({
                type: "POST", 
                url: admin_url,
                data: {
                    action: 'demo',
                    abc : abc,
                    product_id : product_id,
                    selected_vehicle : selected_vehicle, 
                    cart_item_id : cart_item_id,   
                },
                beforeSend:function() {                    
                    $(modal_body).find('.data-body').html('');
                    $(modal_body).find('.data-body').html(loader);
					$(modal_body).find('.service_voucher_total').css('display','none');
                 },
                success: function (data)
                {               
                    data = data.replace(/(\r\n|\n|\r)/gm, "");
                    $(modal_body).find('.data-body').html(data);
					price_calculation(modal_body);
                },
        }); 
		
		
        $('.cart-item-service-model .screen').css('display','none'); 
        $('.cart-item-service-model .select-service-voucher').css('display','block');
    });

    $(document).on('click','.cart-item-service-model .next-to-review',function(e) 
    {
		e.preventDefault();
        $('.cart-item-service-model .screen').css('display','none');
        $('.cart-item-service-model .review-installer').css('display','block');         

    });

    $(document).on('click','.prev-to-car-type',function(e) 
    {
		e.preventDefault();
        var modal_body = $(this).parents('.modal-content');
        $(modal_body).find('.screen').css('display','none');
        $(modal_body).find('.select-car-type').css('display','block');  
    });
    
    $(document).on('click','.prev-to-service-voucher',function(e) 
    {
        e.preventDefault();
        var modal_body = $(this).parents('.modal-content');
        $(modal_body).find('.screen').css('display','none');
        $(modal_body).find('.select-service-voucher').css('display','block');
    });
   	// ----------- next - previous button action end ------- //     
    
    $(document).on('click','.apply-coupon',function() 
    {
    	$('form .apply-coupon').trigger('click');
    });

    $(".login-container .login").mouseenter(function() {
        $('.login-dropdown').css('display','block');
    }).mouseleave(function() {
        $('.login-dropdown').css('display','none');
    });

    function remove_item_alert()
    {
        alert('');
    }
    
    $(document).on('click','.cart-services',function() 
    {

        //$('#overlay').show();                     
        $('#cover-spin').show(0);
        cart_item_id=$(this).attr('data-id');
        session_id=$(this).attr('data-session');
        product_id=$(this).attr('data-product');
        service_ids=$(this).attr('data-value');
        service_name=$(this).attr('data-name');
        vehicle_id=$(this).attr('data-vehicle');
        service_rate=$(this).attr('data-rate');
        offline=$(this).attr('data-offline');

        subtotal = $('#subtotal').val();
        cart_total = $('#maintotal').val();                        
        tyre_count=1;
            
        services_add_ajax();

                //var site_url = $('.site-url').text();
 
    });




$(document).on('click','.get-distance-price',function(){
        
        pic_address=$('.search_input').val();
        installer_id=$(this).attr('data-installer-id');
        service_id=$(this).attr('data-service-id');
        city_id=$(this).attr('data-city-id');
        if(pic_address){
            $('#cover-spin').show(0);
                $.ajax({
            type: "POST", 
            url: admin_url,
            data: {
                action: 'get_pickup_price',
                pic_address: pic_address, 
                installer_id : installer_id,
                service_id : service_id,
                city_id : city_id,

            },
            success: function (data)
            {
                
                var json = JSON.parse(data);
                if(json.km>10){
                    $('#pic_address').addClass('disabled');
                    $('.km-error').show();
                }else{
                    $('.km-error').hide();
                    $('#pic_address').removeClass('disabled');
                    $('.collapseExample').hide(0);
    $('#pic_price').html('<p>'+json.currency_symbol+json.price+' ('+json.km+' km) </p><span class="help-txt"><i class="fa fa-question-circle"></i></span>');
                    $('.address-bx').html('<span>'+json.pic_address+'</span>');
                    $("#pic_address").attr("href", "");
                    $("#pic_address").attr("data-rate", json.price);
                    $('.fa-question-circle').attr('data-toggle','tooltip');
                    $('.fa-question-circle').attr('data-placement','right');
                    $('.fa-question-circle').attr('data-trigger','hover');
                    $('.fa-question-circle').attr('data-html','true');
                    $('.fa-question-circle').attr('title','For first 5km ₹'+json.base_price+' after 5km each ₹'+json.per_km_price+'/km');
                    $('.collapseExample').hide();
                    $('.addi-cart-services').show();

                }
               $('#cover-spin').hide(0); 
            },
        });
        }else{
         alert('Please enter your pickup address!.'); 
         $( "#myInput" ).focus();  
        }
    

});



$(document).on('click','.addi-cart-services',function() 
    {
        //$('#overlay').show();                     
        $('#cover-spin').show(0);
        cart_item_id=$(this).attr('data-id');
        session_id=$(this).attr('data-session');
        product_id=$(this).attr('data-product');
        service_ids=$(this).attr('data-value');
        service_name=$(this).attr('data-name');
        vehicle_id=$(this).attr('data-vehicle');
        service_rate=$(this).attr('data-rate');
        cart_total = $('#maintotal').val(); 
        subtotal = $('#subtotal').val(); 
        offline=$(this).attr('data-offline');
        tyre_count=1;
          
        services_add_ajax();

                //var site_url = $('.site-url').text();
 
    });

function services_add_ajax(){
    var pic_address=$('#collapseExample .search_input').val(); 
    var src=$('#'+cart_item_id+'-'+service_ids+' img').attr('src');
    $.ajax({
        type: "POST", 
        url: admin_url,
        data: {
            action: 'save_service_info_from_cart',
            service_name : service_name, 
            cart_item_id : cart_item_id,
            product_id : product_id,
            tyre_count : tyre_count,
            vehicle_id : vehicle_id,
            service_rate : service_rate, 
            session_id : session_id,
            service_id : service_ids,
            pic_address: pic_address,
            subtotal: subtotal,
            cart_total: cart_total,
            offline: offline

        },
        success: function (data)
        {
            var json = JSON.parse(data);
           
            //var url = site_url+'/cart';
            $("#"+cart_item_id+'-'+service_ids).hide(100);
                var markup; 
                markup += '<tr class="service-list-added">';
                markup += '<td class="added-service-thumbnail"><img src="'+src+'"></td>';
                markup += '<td class="added-service-name title-sec">'+service_name+'</td>';
                markup += '<td class="added-service-qty">&nbsp;</td>';
                markup += '<td class="added-service-price price-sec">₹'+service_rate+'</td>';
                markup += '<td class="added-service-total price-sec">&nbsp;</td>';
                markup += '<td class="added-service-remove">';
                markup += '<a href="JavaScript:void(0);" class="servi-remove" id="'+json.cart_item_services_id+'" data-cart-ses-id="'+session_id+'" data-offline="'+offline+'" data-cart-key="'+cart_item_id+'"><i class="fa fa-trash"></i></a>';
                markup += '</td>';
                markup += '</tr>';


        $("#insta-"+cart_item_id+" tr:last").before(markup);

            var cart_subtotal=json.cart_subtotal;
            //cart_subtotal= parseInt(cart_subtotal);
            var cart_total=json.cart_total;
             $('#maintotal').val(cart_total);
            //cart_total+= parseFloat(service_rate);                            
            $('#subtotal').val(json.cart_subtotal);
            var tohtml='<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">'+json.currency_symbol+'</span>'+cart_total+'</span>';
            $('.cart_totals table .order-total .woocommerce-Price-amount').html(tohtml);

            var subtohtml='<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">'+json.currency_symbol+'</span>'+cart_subtotal+'</span>';
            $('.custom-subtotal table .cart-subtotal .woocommerce-Price-amount').html(subtohtml);
            
            $( document.body ).trigger( 'wc_fragment_refresh' );
            $('#cover-spin').hide(0);
        },
    });
}


    $(document).on('click','.servi-remove',function() 
    {
                $('#cover-spin').show();

                cart_item_id=$(this).attr('id');
                session_id=$(this).attr('data-cart-ses-id');                        
                cart_item_key=$(this).attr('data-cart-key');
                offline=$(this).attr('data-offline');
                cart_total=$('#maintotal').val();
                subtotal=$('#subtotal').val();
                var admin_url = $('.admin_url').text();
                $.ajax({
                        type: "POST", 
                        url: admin_url,
                        data: {
                            action: 'remove_service_info_from_cart',
                            cart_item_id : cart_item_id,
                            session_id : session_id,
                            cart_item_key : cart_item_key,
                            cart_total : cart_total,
                            offline : offline,
                            subtotal : subtotal
                            
                        },
                        success: function (data)
                        {
                            var json = JSON.parse(data);
                           
                            var cart_subtotal=json.cart_subtotal;
                            //cart_subtotal= parseInt(cart_subtotal);
                            var cart_total=json.cart_total;
                            //cart_total= parseInt(cart_total);

                            $('#subtotal').val(json.cart_subtotal);
                            $('#maintotal').val(json.cart_total);
                            var tohtml='<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">'+json.currency_symbol+'</span>'+cart_total+'</span>';
                            $('.cart_totals table .order-total .woocommerce-Price-amount').html(tohtml);

                            var subtohtml='<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">'+json.currency_symbol+'</span>'+cart_subtotal+'</span>';
                            $('.cart_totals table .cart-subtotal .woocommerce-Price-amount').html(subtohtml);

                            $('#'+cart_item_key+'-'+json.service_data_id).show(100);                            
                            $( document.body ).trigger( 'wc_fragment_refresh' );
                            
                            $('#cover-spin').hide();
                        },
                });
        
        $(this).parent().parent("tr:first").fadeOut(500, function(){ jQuery(this).parent().parent("tr:first").remove();});
    });

    $('#noticeModal').modal('show');

});





jQuery(document).on('click','#vehicle-type-add', function(e)
  {
      e.preventDefault();
        var admin_url = jQuery('#admin-url').val();
         
          var vehicle_type = jQuery("input[name='vehicle_type']:checked").val();



          jQuery.ajax({
                    type: "POST", 
                    url: admin_url,
                    data: {
                        action: 'direct_vehicle_type_add',
                        vehicle_type : vehicle_type, 
                    },
                    success: function (data)
                    {
                         location.reload();               
                    },
                });

});

jQuery(document).on('click','#proceed_to_checkout', function(e)
    { 
          
        e.preventDefault();
          var admin_url = jQuery('.admin_url').text();
          var customer_mobile = jQuery('#customer_mobile').val();
          var cust_type = jQuery('#customer_type').val(); 

    if (customer_mobile == "" || customer_mobile ==null){
     jQuery("#customer_mobile").addClass('errormsg');        
         errors= true;                            
    }else{
      jQuery("#customer_mobile").removeClass('errormsg');         
           errors= false;
    }
    check();

    if(errors == true){
        return false;
    }else{
        jQuery('#cover-spin').show(0);
          jQuery.ajax({
                    type: "POST", 
                    url: admin_url,
                    data: {
                        action: 'proceed_to_checkout',
                        mobile_no : customer_mobile,
                        cust_type : cust_type,
                    },
                    success: function (data)
                    {
                        var jdata = JSON.parse(data);
                            
                            if(jdata.user_id!=''){
                                window.location=jdata.checkout;
                            }
                      jQuery('#cover-spin').hide(0);               
                    },
                });
      }
    });


jQuery(document).on('click','#discount_price_btn', function(e)
    { 
        jQuery('#cover-spin').show(0); 
        e.preventDefault();
          var admin_url = jQuery('.admin_url').text();
          var discount_price = jQuery('#discount_price').val();
          jQuery.ajax({
                    type: "POST", 
                    url: admin_url,
                    data: {
                        action: 'discount_price_apply',
                        discount_price : discount_price,
                    },
                    success: function (data)
                    {
                      location.reload();
                      jQuery('#cover-spin').hide(0);               
                    },
                    complete: function() {
                    }
                });
    });

function check()
{

    var mobile = document.getElementById('customer_mobile');
    var message = document.getElementById('message');

    var goodColor = "red";
    var badColor = "red";
    if(mobile.value.length!=10){
        //mobile.style.backgroundColor = badColor;
        mobile.style.borderColor = badColor;
        message.style.color = badColor;
        message.innerHTML = "Enter Valid Mobile Number"
        jQuery('#proceed_to_checkout').attr('disabled','disabled');
    }else{
     message.innerHTML = "";
     mobile.style.borderColor ='green'; 
     jQuery('#proceed_to_checkout').removeAttr('disabled');
    }
}
/*jQuery( document ).ready(function() {
    var autocomplete;
    autocomplete = new google.maps.places.Autocomplete((document.getElementById('myInput')), {
        types: ['geocode'],
    });
    
    google.maps.event.addListener(autocomplete, 'place_changed', function () {
        var near_place = autocomplete.getPlace();
        document.getElementById('loc_lat').value = near_place.geometry.location.lat();
        document.getElementById('loc_long').value = near_place.geometry.location.lng();
        
    });

    setTimeout(function(){
      var pic_address=jQuery('#collapseExample .search_input').val();

        if(pic_address){
            
            $('.collapseExample').trigger('click');
            $('.get-distance-price').trigger('click'); 
        }  
    }, 1000);
});*/




