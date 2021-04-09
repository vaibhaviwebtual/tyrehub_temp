jQuery(document).ready(function($) 
{
	$(document).on('click','.search-history',function(e){
		var installer_id = $('.installer-list').val();
        var month = $('#month').val();
        var year = $('#year').val();
		//console.log(installer_id);

		var au = $('.admin-url').text();

		$.ajax({    
                type: "POST", 
                url: au,
                data: {
                    action: 'tir_installer_info',
                    installer_id: installer_id,
                    month: month,
                    year: year,
                },
                beforeSend: function() { 
                    $('.installer-data table tbody').html('<tr><td colspan="6"><div class="spinner"></div></td></tr>');
                    $(".installer-data .spinner").show();
                    $(".installer-data .spinner").css('visibility','visible');
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

	});

	$(document).on('click','.select-all-service',function(e){
         //$(":checkbox").attr("checked", true);
        if($(this).prop('checked') == true){
            console.log('hello');
            $('table.installer-report tbody').find('.service-select').prop('checked',true);
            
        }
        else{
            $('table.installer-report tbody').find('.service-select').prop('checked',false);
            
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
            $('.create-invoice').css('display','inline-block');
        }
        else{
            $('.total-charge').css('display','none');
            $('.create-invoice').css('display','none');
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
            $('.create-invoice').css('display','inline-block');
        }
        else{
        	$('.total-charge').css('display','none');
            $('.create-invoice').css('display','none');
        }

	});

	$(document).on('click','.create-invoice',function(e){
		var total = 0;
		var au = $('.admin-url').text();
		var service_arr = [],voucher_arr = [];
        var installer_id = $('.installer-list').val();
		$(".one-service").each(function()
        {
        	var t = $(this);
        	id = $(this).attr('data-id');
            if($(t).find('.service-select').prop('checked')==true)
            {
            	var cp = $(t).find('.final-price').text();
            	total = parseInt(total) + parseInt(cp);
            	if((t).hasClass('service')){
            		type = 's';
            		service_arr.push(id);
            	}
            	else{
            		type = 'v';
            		voucher_arr.push(id);
            	}
            	//console.log(type);
            	$.ajax({    
	                type: "POST", 
	                url: au,
	                data: {
	                    action: 'tir_update_status_paid',
	                    id: id,
	                    type: type,
	                },
	                success: function (r) {
	                  //  $('.installer-data').html(r);
	                },
	            });
            }
        });

        $.ajax({    
                type: "POST", 
                url: au,
                data: {
                    action: 'tir_save_paid_details',
                    service_arr: service_arr,
                    voucher_arr: voucher_arr,
                    total: total,
                    installer_id: installer_id,
                },
                success: function (r) {
                  //  $('.installer-data').html(r);
                  $('#after_create_invoice').css('display','block');
                  var href = $('#after_create_invoice a.download-pdf').attr('href');
                  href += '&service_id='+r;
                  $('#after_create_invoice a.download-pdf').attr('href',href);

                },
            });
      //  console.log(service_arr,voucher_arr);

	});

    $(document).on('click','#after_create_invoice .close',function(e){
        
        var installer_id = $('.installer-list').val();
        
        var au = $('.admin-url').text();

        $.ajax({    
                type: "POST", 
                url: au,
                data: {
                    action: 'tir_installer_info',
                    installer_id: installer_id,
                },
                beforeSend: function() { 
                    $('.installer-data table tbody').html('<tr><td colspan="6"><div class="spinner"></div></td></tr>');
                    $(".installer-data .spinner").show();
                    $(".installer-data .spinner").css('visibility','visible');
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
        $('#after_create_invoice').css('display','none');
            
    });

    
    $(document).on('click','select.all-installer',function(e){
        var installer_id = $(this).val();

        var au = $('.admin-url').text();

        $.ajax({    
                type: "POST", 
                url: au,
                data: {
                    action: 'tir_invoice_report_data',
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

