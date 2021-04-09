jQuery(document).ready(function($) 
{
	$(document).on('click','.franhise-search-history',function(e){
		var installer_id = $('.franchise-list').val();
        var month = $('#month').val();
        var year = $('#year').val();
		//console.log(installer_id);

		var au = $('.admin-url').text();

		$.ajax({    
                type: "POST", 
                url: au,
                data: {
                    action: 'tir_franchise_info',
                    installer_id: installer_id,
                    month: month,
                    year: year,
                },
                beforeSend: function() { 
                    $('.franchise-data table tbody').html('<tr><td colspan="6"><div class="spinner"></div></td></tr>');
                    $(".franchise-data .spinner").show();
                    $(".franchise-data .spinner").css('visibility','visible');
                    $('.total-charge').css('display','none');
                },
                success: function (r) {
                    $('.franchise-data table tbody').html(r);

                    var a = 0;
                    
                    $(".one-service").each(function()
                    {
                        var t = $(this);
                        var cp = $(t).find('.final-price').text();
                        a = parseInt(a) + parseInt(cp);
                    });
                    $('.grand-total .amount').text(a);
                },
            });	

	});

        
        setTimeout(function() {
           $ (".franhise-history").trigger( "click" );
        }, 1000);
        $(document).on('click','.franhise-history',function(e){
        var installer_id = $('.all-franchise').val();
        var month = $('#month').val();
        var year = $('#year').val();
        //console.log(installer_id);

        var au = $('.admin-url').text();

        $.ajax({    
                type: "POST", 
                url: au,
                data: {
                    action: 'tir_franchise_payout_history',
                    installer_id: installer_id,
                    month: month,
                    year: year,
                },
                beforeSend: function() { 
                    $('.franchise-data table tbody').html('<tr><td colspan="6"><div class="spinner"></div></td></tr>');
                    $(".franchise-data .spinner").show();
                    $(".franchise-data .spinner").css('visibility','visible');
                    $('.total-charge').css('display','none');
                },
                success: function (r) {
                    $('.franchise-data table tbody').html(r);

                    var a = 0;
                    
                    $(".one-service").each(function()
                    {
                        var t = $(this);
                        var cp = $(t).find('.final-price').text();
                        a = parseInt(a) + parseInt(cp);
                    });
                    $('.grand-total .amount').text(a);
                },
            }); 

    });




	$(document).on('click','.select-all-service',function(e){
         //$(":checkbox").attr("checked", true);
        if($(this).prop('checked') == true){
            console.log('hello');
            $('table.franchise-report tbody').find('.service-select').prop('checked',true);
            
        }
        else{
            $('table.franchise-report tbody').find('.service-select').prop('checked',false);
            
        }
        
        var total = 0;
        $(".one-service").each(function()
        {
            var t = $(this);
            id = $(this).attr('data-id');
            var cp = $(t).find('.final-price').text();
            if($(t).find('.service-select').prop('checked')==true)
            {
                
                total = parseInt(total) + parseInt(cp);
            }
        });

        if(total != 0){
            $('.total-charge .amount').text(total);
            $('.total-charge').css('display','inline-block');
            $('.create-franchise-invoice').css('display','inline-block');
        }
        else{
            $('.total-charge').css('display','none');
            $('.create-franchise-invoice').css('display','none');
        }

  });
    
	$(document).on('click','.service-select',function(e){
		var total = 0;
		var au = $('.admin-url').text();
		$(".one-service").each(function()
        {
        	var t = $(this);
        	id = $(this).attr('data-id');
            var cp = $(t).find('.final-price').text();
            if($(t).find('.service-select').prop('checked')==true)
            {
            	
            	total = parseInt(total) + parseInt(cp);
            }
        });

        if(total != 0){
        	$('.total-charge .amount').text(total);
        	$('.total-charge').css('display','inline-block');
            $('.create-franchise-invoice').css('display','inline-block');
        }
        else{
        	$('.total-charge').css('display','none');
            $('.create-franchise-invoice').css('display','none');
        }

	});

	$(document).on('click','.create-franchise-invoice',function(e){
		var total = 0;
		var au = $('.admin-url').text();
		var profit = [];
        var franchise_id = $('.franchise-list').val();
        var month = $('#month').val();
        var year = $('#year').val();
		$(".one-service").each(function()
        {
        	var t = $(this);
        	id = $(this).attr('data-id');
            if($(t).find('.service-select').prop('checked')==true)
            {
            	var cp = $(t).find('.final-price').text();
            	total = parseInt(total) + parseInt(cp);
            	profit.push(id);
            	//console.log(type);
            	
            }
        });

        $.ajax({    
                type: "POST", 
                url: au,
                data: {
                    action: 'tir_update_franchise_status_paid',
                    profit: profit,
                    total:total,
                    month:month,
                    year:year,
                    franchise_id:franchise_id
                },
                success: function (r) {
                  $('#after_create_franchise_invoice').css('display','block');
                  var href = $('#after_create_franchise_invoice a.download-pdf').attr('href');
                  href += '&payout_id='+r;
                  $('#after_create_franchise_invoice a.download-pdf').attr('href',href);
                },
            });

       /* $.ajax({    
                type: "POST", 
                url: au,
                data: {
                    action: 'tir_franchise_save_paid_details',
                    service_arr: service_arr,
                    voucher_arr: voucher_arr,
                    total: total,
                    installer_id: installer_id,
                },
                success: function (r) {
                  //  $('.installer-data').html(r);
                  $('#after_create_franchise_invoice').css('display','block');
                  var href = $('#after_create_franchise_invoice a.download-pdf').attr('href');
                  href += '&payout_id='+r;
                  $('#after_create_franchise_invoice a.download-pdf').attr('href',href);

                },
            });*/
      //  console.log(service_arr,voucher_arr);

	});

    $(document).on('click','#after_create_franchise_invoice #payoutclose',function(e){
        var installer_id = $('.franchise-list').val();
        
        var au = $('.admin-url').text();

        $.ajax({    
                type: "POST", 
                url: au,
                data: {
                    action: 'tir_franchise_info',
                    installer_id: installer_id,
                },
                beforeSend: function() { 
                    $('.franchise-data table tbody').html('<tr><td colspan="6"><div class="spinner"></div></td></tr>');
                    $(".franchise-data .spinner").show();
                    $(".franchise-data .spinner").css('visibility','visible');
                    $('.total-charge').css('display','none');
                },
                success: function (r) {
                    $('.installer-data table tbody').html(r);

                    var a = 0;
                    
                    $(".one-service").each(function()
                    {
                        var t = $(this);
                        var cp = $(t).find('.final-price').text();
                        a = parseInt(a) + parseInt(cp);
                    });
                    $('.grand-total .amount').text(a);
                },
            });
        $('#after_create_franchise_invoice').css('display','none');
            
    });

    
    $(document).on('change','select.all-franchise',function(e){
        var installer_id = $(this).val();

        var au = $('.admin-url').text();

        $.ajax({    
                type: "POST", 
                url: au,
                data: {
                    action: 'tir_franchise_invoice_report_data',
                    installer_id: installer_id,
                },
                beforeSend: function() { 
                    $('.invoice-report-data tbody').html('<tr><td colspan="6"><div class="spinner"></div></td></tr>');
                    $(".invoice-report-data .spinner").show();
                    $(".invoice-report-data .spinner").css('visibility','visible');
                },
                success: function (r) {
                    $('.invoice-report-data tbody').html(r);                   
                },
            });
    });

    
    // pagination 

    var show_per_page = 10;
    var number_of_items = $('.pagnation').children('.newsbox').size();
    var number_of_pages = Math.ceil(number_of_items / show_per_page);

    $('.page-info').append('<input id=current_page type=hidden><input id=show_per_page type=hidden>');
    $('#current_page').val(0);
    $('#show_per_page').val(show_per_page);

    var navigation_html = '<a class="prev" onclick="previous()">Prev</a>';
    var current_link = 0;
    while (number_of_pages > current_link) {
        navigation_html += '<a class="page" onclick="go_to_page(' + current_link + ')" longdesc="' + current_link + '">' + (current_link + 1) + '</a>';
        current_link++;
    }
    navigation_html += '<a class="next" onclick="next()">Next</a>';

    $('.controls').html(navigation_html);
    $('.controls .page:first').addClass('active');

    $('.pagnation').children().css('display', 'none');
    $('.pagnation').children().slice(0, show_per_page).css('display', 'table-row');


});

    
function go_to_page(page_num) {
    var show_per_page = parseInt($('#show_per_page').val(), 0);

    start_from = page_num * show_per_page;

    end_on = start_from + show_per_page;

    $('.pagnation').children().css('display', 'none').slice(start_from, end_on).css('display', 'table-row');

    $('.page[longdesc=' + page_num + ']').addClass('active').siblings('.active').removeClass('active');

    $('#current_page').val(page_num);
}



function previous() {

    new_page = parseInt($('#current_page').val(), 0) - 1;
    //if there is an item before the current active link run the function
    if ($('.active').prev('.page').length == true) {
        
        go_to_page(new_page);
    }

}

function next() {
    new_page = parseInt($('#current_page').val(), 0) + 1;
    console.log(parseInt($('#current_page').val(), 0) + 1)
    //if there is an item after the current active link run the function
    if ($('.active').next('.page').length == true) {
        go_to_page(new_page);
    }

}
jQuery(document).ready(function($) {

jQuery(document).on('click','.single_view',function(e){
        e.preventDefault();

        
        var profit_id = jQuery(this).attr('id');
        var admin_url =jQuery('.admin_url').text();
        jQuery('#cover-spin').show(0);
        jQuery.ajax({    
                type: "POST", 
                url: ajaxurl,
                data: {
                    action: 'profit_view_by_row_admin',
                    profit_id: profit_id
                },
                beforeSend: function() {   
                                         
                    },
                success: function (data) {
                    jQuery( "#profit_view table tr" ).each(function( index ) {
                        if (jQuery(this).css('display') == 'none'){
                           jQuery(this).show();
                       }
                    });

                    var obj = JSON.parse(data);
                    jQuery('#qty').html(obj.qty);
                    jQuery('#qty1').html(obj.qty);

                    if(obj.tyre_base_sale_price<=0){
                        jQuery('#tyre_sale_tr').hide();

                    }
                    if(obj.base_purchase_price<=0){
                     jQuery('#tyre_buy_tr').hide();   
                    }

                    if(obj.balancing_without_gst<=0){
                       jQuery('#balancing_with_tyre_tr').hide(); 
                    }
                    if(obj.alignment_without_gst<=0){
                       jQuery('#alignment_with_tyre_tr').hide(); 
                    }
                    if(obj.carwash_without_gst<=0){
                        jQuery('#car_wash_with_tyre_tr').hide();
                    }
                    
                    if(obj.balancing_alignment_without_gst<=0){
                       jQuery('#balancing_alignment_tr').hide();
                    }
                    if(obj.single_carwash_without_gst<=0){
                       jQuery('#separate_car_wash_tr').hide();
                    }

                    if(obj.single_carwash_without_gst<=0 && obj.carwash_without_gst<=0 && obj.alignment_without_gst<=0 && obj.balancing_without_gst<=0 && obj.tyre_base_sale_price>=0){
                    jQuery('#gross_total_tr').hide();
                    }    
                    
                    if(obj.balancing_alignment_without_gst>=0){
                        jQuery('#gross_total_tr').hide();
                    }
                    
                    jQuery('#tyre_sale').html('<i class="fa fa-inr"></i>'+parseInt(obj.tyre_base_sale_price));
                    jQuery('#tyre_buy').html('- <i class="fa fa-inr"></i>'+ parseInt(obj.base_purchase_price));

                    jQuery('#balancing_with_tyre').html('<i class="fa fa-inr"></i>'+parseInt(obj.balancing_without_gst));
                    jQuery('#alignment_with_tyre').html('<i class="fa fa-inr"></i>'+parseInt(obj.alignment_without_gst));
                    jQuery('#car_wash_with_tyre').html('<i class="fa fa-inr"></i>'+parseInt(obj.carwash_without_gst));
                    jQuery('#balancing_alignment').html('<i class="fa fa-inr"></i>'+parseInt(obj.balancing_alignment_without_gst));
                    jQuery('#separate_car_wash').html('<i class="fa fa-inr"></i>'+parseInt(obj.single_carwash_without_gst));



                    var tyre_profit=parseInt(obj.tyre_base_sale_price) - parseInt(obj.base_purchase_price);
                    jQuery('#tyre_profit').html('(A)&nbsp;&nbsp;&nbsp;<i class="fa fa-inr"></i>'+tyre_profit);
                    jQuery('#service_payment').html('(B)&nbsp;&nbsp;&nbsp;<i class="fa fa-inr"></i>'+parseInt(obj.service_base_charge));
                    var gross_total= tyre_profit + parseInt(obj.service_base_charge);
                    jQuery('#gross_total').html('(A+B)&nbsp;&nbsp;&nbsp;<i class="fa fa-inr"></i>'+gross_total);
                    jQuery('#handling_charge').html('- <i class="fa fa-inr"></i>'+parseInt(obj.payment_gateway_base_cost));
                    jQuery('#your_profit').html('<i class="fa fa-inr"></i>'+parseInt(obj.base_profit));
                    var profit_gst= parseInt(obj.profit_with_gst) - parseInt(obj.base_profit);
                    jQuery('#profit_gst').html('<i class="fa fa-inr"></i>'+profit_gst);
                    jQuery('#total_profit').html('<i class="fa fa-inr"></i>'+parseInt(obj.profit_with_gst));


                    if(tyre_profit<=0){
                       jQuery('#tyre_profit_tr').hide();
                    }
                    if(obj.service_base_charge<=0){
                       jQuery('#service_payment_tr').hide();
                    }
                    
                    //$('#profit_view').modal('show');
                     jQuery('#profit_view').show();
                    jQuery('#cover-spin').hide(0);
                },
            });
    });

jQuery(document).on('click','#profit_view .close',function(){
    jQuery('#profit_view').hide();
});


       

} );




