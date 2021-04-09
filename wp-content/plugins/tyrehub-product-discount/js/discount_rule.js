jQuery(document).ready(function($) 
{
	
	$(document).on('click','.save-product-list',function(e){
		
		e.preventDefault();
		var admin_url = $('.admin-url').text();	
		var list_type = $('.list-type:checked').val();
        var list_name = $('.list-name').val();
        var product_ids = [];

        $(".product-details .single-product").each(function()
        {
            if($(this).find('.select-option input:checked'))
            {
                var product_id = $(this).find('.select-option input').val();
                product_ids.push(product_id);
            }
        });

		console.log(list_type);
		$.ajax({	
            type: "POST", 
            url: admin_url,
            data: {
                action: 'save_product_list',
                list_type: list_type,
                list_name: list_name,
                product_ids: product_ids,
            },
            success: function (data) {
            	var product_list_id = data;
            	product_list_id = product_list_id.replace(/\n/g, ' ');
                if(list_type == 'bymeta')
                {
                    $(".product-container .row").each(function()
                    {
                        var meta_key = $(this).find('.taxonomy-selector').val();
                        var meta_value = $(this).find('.taxonomy-value').val();

                        $.ajax({    
                                type: "POST", 
                                url: admin_url,
                                data: {
                                    action: 'save_product_meta',
                                    product_list_id: product_list_id,
                                    meta_key: meta_key,
                                    meta_value: meta_value,

                                },
                                success: function (data) {
                                    
                                },
                            });
                    });
                }
                else{   
                    $('form').submit();
                }
            	  
            },
                
        });
	});

    
    
    $(document).on('click','.meta-selection .byid .search-btn',function(e){
        e.preventDefault();
        var width = $('.width').val();
        var ratio = $('.ratio').val();
        var diameter = $('.diameter').val();
        var cat = $('select.category').val();
        var admin_url = $('.admin-url').text();
        var loader = '<img src="https://www.tyrehub.com/dev/wp-content/plugins/tyrehub-product-discount/loading.gif">'; 
        $.ajax({    
                type: "POST", 
                url: admin_url,
                data: {
                    action: 'product_data_byid',
                    width: width,
                    ratio: ratio,
                    diameter: diameter,
                    cat: cat,
                },
                beforeSend: function() {                        
                        $('.product-details').append(loader);
                    },
                success: function (data) {
                   
                    $('.product-details').html(data);
                },
            });
    });

     $(document).on('keyup','.rule-name',function(){
       
        if($(this).val().length >= 2)
        {
            $('input.save_discount').removeAttr('disabled');
        }
        else{
            $('input.save_discount').attr('disabled','disabled');
        }
    });

      $(document).on('click','.save_discount',function(e){
        e.preventDefault();
        var product_list_count = 0;
        var status = 'off';
        if($('input[name="status"]').prop('checked')==true)
        {
            status = 'on';
        }
        var name = $('input[name="post_title"]').val();
        var start_date = $('input[name="start_date"]').val();
        var end_date = $('input[name="end_date"]').val();
        var rule_img = $('#logo_url').val();

      //  console.log(rule_img);
        var admin_url = $('.admin-url').text();

        $(".product-container .selected-products .single-product").each(function()
        {
            product_list_count = product_list_count + 1;
        });
        if(product_list_count >= 1)
        {        
            $.ajax({    
                    type: "POST", 
                    url: admin_url,
                    data: {
                        action: 'save_discount_rule',
                        name: name,
                        start_date: start_date,
                        end_date: end_date,
                        status: status,
                        rule_img: rule_img,
                    },
                    success: function (data)
                    {
                        var rule_id = data;
                        rule_id = rule_id.replace(/\n/g, ' ');

                        $(".product-container .selected-products .single-product").each(function()
                        {
                            var discount_amount = $(this).find('.discount_amount').val();
                            var prd_status = $(this).find('.prd-status').val();
                            var prd_status = 'off';
                            if($(this).find('.prd-status').prop('checked')==true)
                            {
                                prd_status = 'on';
                            }
                            if(discount_amount != '')
                            {
                                product_id = $(this).attr('data-id');

                                $.ajax({    
                                    type: "POST", 
                                    url: admin_url,
                                    data: {
                                        action: 'save_product_list',
                                        discount_amount: discount_amount,
                                        rule_id: rule_id,
                                        product_id: product_id,
                                        prd_status: prd_status,
                                    },
                                    success: function (data) {
                                       // $('.product-details').html(data);
                                      // location.reload();
                                       window.location.replace('?page=discount_rule');
                                    },
                                });
                            }
                        });
                    },
                });
        }
        else{
            $('.error-msg').text('Please select product for rule');
          //      $('.error-msg').focus();
            $('html, body').animate({
                scrollTop: $(".error-msg").offset().top - 50
            }, 1000);
        }
    });

     
    $(document).on('click','.discount-rule .product-details .single-product',function(e)
    {
        var curent_single_prd = $(this);
        var single_product = $(this).html();
        id = $(this).attr('data-id');
        var prd_flg = 'not-in-list';
        if($(".product-container .selected-products").html() == '')
        {
            $('.selected-products').append('<div class="single-product" data-id="'+id+'">'+single_product+'</div>');
            $(curent_single_prd).css('display','none');
        }
        else
        {
            $(".product-container .selected-products .single-product").each(function()
            {
                selected_id = $(this).attr('data-id');
                console.log(selected_id);
                if(id == selected_id)
                {
                    prd_flg = 'in-list';
                }
            });

            if(prd_flg == 'not-in-list'){
                $('.selected-products').append('<div class="single-product" data-id="'+id+'" id="'+id+'"> <div class="remove"><span><<</span></div>'+single_product+'<div class="status"><input type="checkbox" name="status" class="prd-status" checked></div></div>');
                $(curent_single_prd).css('display','none');
            }
        }     
        
    });

    $(document).on('click','.discount-rule .selected-products .single-product .remove',function()
    {
        if(confirm('Are you sure you want to remove discount rule?'))
        {
            var id = $(this).parent().attr('data-id');
            var name = $(this).parent().find('.name').html();
            var price = $(this).parent().find('.price').html();
            var amount = $(this).parent().find('.discount_amount').val();
            search_id = '.product-details .single-product#'+id;
            console.log($( ".product-details" ).find('#'+id).html());
            var search_res = $( ".product-details" ).find('#'+id).html();
            if($.type(search_res) === "undefined")
            {
                var temp_html ='<div class="single-product" data-id="'+id+'" id="'+id+'">'; 

                temp_html +='<div class="name">'+name+'</div>';
                temp_html +='<div class="price">'+price+'</div>';
                temp_html +='<div class="amount">';
                temp_html +='<input type="text" name="discount_amount" class="discount_amount">';
                temp_html +='</div></div>';  

                $( ".product-details" ).append(temp_html);
            }
            else{
                $(search_id).css('display','block');
            }        
            $(this).parent().remove();
        }       

    });

    
     $(document).on('click','.remove-rule',function(e)
    {
       
        if(confirm('Are you sure you want to remove discount rule?'))
        {

        }
        else{
             e.preventDefault();
        }
    });
     $(document).on('click','.update_discount',function(e){
        e.preventDefault();

        var rule_id = $('input[name="rule_id"]').val();
        var status = 'off';
        if($('input[name="status"]').prop('checked')==true)
        {
            status = 'on';
        }
        var name = $('input[name="post_title"]').val();
        var start_date = $('input[name="start_date"]').val();
        var end_date = $('input[name="end_date"]').val();
        var rule_img = $('#logo_url').val();

        var admin_url = $('.admin-url').text();

        $.ajax({    
                type: "POST", 
                url: admin_url,
                data: {
                    action: 'update_discount_rule',
                    name: name,
                    start_date: start_date,
                    end_date: end_date,
                    status: status,
                    rule_id: rule_id,
                    rule_img: rule_img,
                },
                success: function (data)
                {
                    $(".product-container .selected-products .single-product").each(function()
                    {
                        console.log('hii');
                        var discount_amount = $(this).find('.discount_amount').val();
                        product_id = $(this).attr('data-id');
                        var prd_status = 'off';
                        if($(this).find('.prd-status').prop('checked')==true)
                        {
                            prd_status = 'on';
                        }
                        if(discount_amount != '')
                        {                           

                            $.ajax({    
                                type: "POST", 
                                url: admin_url,
                                data: {
                                    action: 'save_product_list',
                                    discount_amount: discount_amount,
                                    rule_id: rule_id,
                                    product_id: product_id,
                                    prd_status: prd_status,
                                },
                                success: function (data) {
                                   // $('.product-details').html(data);
                                   $('.message-block').html('<div class="updated notice notice-success is-dismissible"><p>Discount rule updated!</p></div>');
                                   $('html, body').animate({
                                        scrollTop: $(".message-block").offset().top - 50
                                    }, 1000);
                                },
                            });
                        }
                    });
                }
            });
    });

     $('.end-date').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1
    });

    $('.start-date').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1
    });

    

     $(document).on('click','#after_voucher_order .close',function()
    {
        $('#after_voucher_order').css('display','none');
    });
     
});