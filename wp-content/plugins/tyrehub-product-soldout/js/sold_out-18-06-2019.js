jQuery(document).ready(function($) 
{
    $(document).on('click','.save-list',function(e){
        e.preventDefault();
        var admin_url = $('.admin-url').text(); 
        var prd_list = [];
        $(".soldout .selected-products .single-product").each(function()
        {
             product_id = $(this).attr('data-id');           
            prd_list.push(product_id);
        });
       
             $.ajax({    
                        type: "POST", 
                        url: admin_url,
                        data: {
                            action: 'soldout_product_list',
                            prd_list: prd_list,
                        },
                        success: function (data) {
                            $('.message-block').html('<div class="updated notice notice-success is-dismissible"><p>List updated!</p></div>');
                           // $('.product-details').html(data);
                          // location.reload();
                          // window.location.replace('?page=discount_rule');
                        },
                    });
        
    });
    

    $(document).on('click','.soldout .search-btn',function(e){

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
                    action: 'search_product_data',
                    width: width,
                    ratio: ratio,
                    diameter: diameter,
                    cat: cat,
                },
                beforeSend: function() {                        
                        $('.soldout .product-details').append(loader);
                    },
                success: function (data) {
                   
                    $('.soldout .product-details').html(data);
                },
            });
    });

    
    $(document).on('click','.select-all-data',function(e){

        $.confirm({
            title: 'Confirmation',
            content: 'Are you sure search results products sold out?',
            type: 'green',
            buttons: {   
                ok: {
                    text: "OK",
                    btnClass: 'btn-primary',
                    keys: ['enter'],
                    action: function(){
                        
                         $('.product-details .single-product').click();
                    }
                },
                cancel: function(){
                        //return false;
                }
            }
        });
        
    });

    $(document).on('click','.soldout .product-details .single-product',function(e)
    {   
        var admin_url = $('.admin-url').text();
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
        
        
            $.ajax({    
                type: "POST", 
                url: admin_url,
                data: {
                    action: 'soldout_product_list',
                    product_id: id,
                    flag:'add'
                },
                beforeSend: function() {    
                    },
                success: function (data) {
                        
                },
            });    
        
    });

    $(document).on('click','.soldout .selected-products .single-product .remove',function()
    {
            var admin_url = $('.admin-url').text();
            var id = $(this).parent().attr('data-id');
            var name = $(this).parent().find('.name').html();
            var regular_price = $(this).parent().find('.regular-price').html();
            var sale_price = $(this).parent().find('.sale-price').html();
            var amount = $(this).parent().find('.discount_amount').val();
            search_id = '.product-details .single-product#'+id;
            console.log($( ".product-details" ).find('#'+id).html());
            var search_res = $( ".product-details" ).find('#'+id).html();
            if($.type(search_res) === "undefined")
            {
                var temp_html ='<div class="single-product" data-id="'+id+'" id="'+id+'">'; 

                temp_html +='<div class="name">'+name+'</div>';
                temp_html +='<div class="price regular-price">'+regular_price+'</div>';
                temp_html +='<div class="price sale-price">'+sale_price+'</div>';
                temp_html +='<div class="send"><span>>></span></div>';
                $( ".product-details" ).append(temp_html);
            }
            else{
                $(search_id).css('display','block');
            }

            var loader = '<img width="50" src="https://www.tyrehub.com/wp-content/plugins/tyrehub-product-discount/loading.gif">'; 
        
            $.ajax({    
                type: "POST", 
                url: admin_url,
                data: {
                    action: 'soldout_product_list',
                    product_id: id,
                    flag:'remove'
                },
                beforeSend: function() {    
                        //$('.change-price .product-details .body').html('');
                        //$('.soldout .product-details').append(loader);
                    },
                success: function (data) {
                        //$('.soldout .product-details').html('');
                        
                },
            });

            $(this).parent().remove();
             

    });

    $(document).on('click','.change-price .search-btn',function()
    {
        var admin_url = $('.admin-url').text();
        var width = $(this).parent().find('.select-width').val();
        var ratio = $(this).parent().find('.select-ratio').val();
        var diameter = $(this).parent().find('.select-diameter').val();
        var cat = $(this).parent().find('select.select-category').val();
        var loader = '<img width="50" src="https://www.tyrehub.com/wp-content/plugins/tyrehub-product-discount/loading.gif">'; 
        
        $.ajax({    
                type: "POST", 
                url: admin_url,
                data: {
                    action: 'product_data_for_changeprice',
                    width: width,
                    ratio: ratio,
                    diameter: diameter,
                    cat: cat,
                },
                beforeSend: function() {    
                        $('.change-price .product-details .body').html('');
                        $('.change-price .product-details .body').append(loader);
                    },
                success: function (data) {
                        $('.change-price .product-details .body').html(data);
                },
            });
    });

    
    $(document).on('keyup','input.new-price',function()
    { 
        var single_prd = $(this).parents('.single-product');
        var reg_price = $(single_prd).find('.price.regular-price').attr('data-price');
        var new_price = $(this).val();
        var old_dis = $(single_prd).find('.dis-per').attr('data-old-dis');

        if(new_price == ''){
            $(single_prd).find('.dis-per').text(old_dis+'%');
        }else{
            var discount = reg_price - new_price;
            var dis_per = 100 * discount / reg_price;['']
           
            $(single_prd).find('.dis-per').text('('+parseFloat(dis_per.toFixed(2))+'%)off');
        }
        
        
    });

     $(document).on('click','.search-from-log',function()
    {   
        var admin_url = $('.admin-url').text();
        var name = $('.price-change-log .product-name').val();
        console.log(name);
        $.ajax({    
                type: "POST", 
                url: admin_url,
                data: {
                    action: 'search_prd_for_log',
                    name: name,
                },
                success: function (data) {
                   
                    $('.log-table tbody').html(data);
                },
            });
    });

    $(document).on('click','button.change-price',function()
    {
       // alert('hii');
        
        var temp = [];
        var tyre_price_bulk = $('input.bulk-tyre-price').val();
        var tube_price_bulk = $('input.bulk-tube-price').val();
        var update_by = $(".select-update-by option:selected").val();

        $(".change-price .single-product").each(function()
        {
                var tyre_price = 0;
                var tube_price = 0;
                tyre_price = $(this).find('input.tyre-price-real').val();
                tube_price = $(this).find('input.tube-price-real').val();
                var mrp_price = $(this).find('input.mrp-price').val(); 
                var mrp_price_new = $(this).find('input.new-mrp-price').val();
                var sale_price = $(this).find('input.sale-price-real').val();

                var product_id = $(this).attr('data-id');
                
                if(tyre_price == ''){
                    tyre_price = $(this).find('input.tyre-price-real').attr('data-price');
                }
                if(tube_price == ''){
                    tube_price = $(this).find('input.tube-price-real').attr('data-price');
                }

                var prd_list = {};
                var price_list = {};
               
                price_list.mrp_price_new = mrp_price_new;
                price_list.mrp_price = mrp_price;
                price_list.sale_price = sale_price;     
                price_list.tyre_price = tyre_price;
                price_list.tube_price = tube_price;
              //  price_list.update_by = update_by;

                prd_list.product_id = product_id;
                prd_list.price_list = price_list;
               
                temp.push(prd_list);     
        });
        
        console.log(temp);
        if(tyre_price_bulk != '' || tube_price_bulk != '')
        {
                
            var admin_url = $('.admin-url').text();
            $.ajax({    
                    type: "POST", 
                    url: admin_url,
                    data: {
                        action: 'change_product_price',                        
                        tyre_price_bulk: tyre_price_bulk,
                        tube_price_bulk: tube_price_bulk,
                        update_by: 'bulk_update',
                        prd_list: temp,
                    },
                    success: function (data) {
                        
                       // var data = $.parseJSON(data);
                       // alert(data);
                        get_product_data();
                       $('.message-block').html('<div class="updated notice notice-success is-dismissible"><p>Price Changed!</p></div>');
                    },
                });
        }
        else{
            
            var admin_url = $('.admin-url').text();
            $.ajax({    
                    type: "POST", 
                    url: admin_url,
                    data: {
                        action: 'change_product_price',                        
                        prd_list: temp,
                    },
                    success: function (data) {
                        
                       // var data = $.parseJSON(data);
                       // alert(data);
                        get_product_data();
                       $('.message-block').html('<div class="updated notice notice-success is-dismissible"><p>Price Changed!</p></div>');
                    },
                });
        }
       /* $(".change-price .single-product").each(function()
        {
            
           // var amount = $(this).find('.amount input').val();
            
           // console.log(tyre_price,tube_price);
            var tyre_price = $('input.tyre-price-real').val();
            var tube_price = $('input.tube-price-real').val();
            var mrp_price = $(this).find('input.mrp-price').val(); 
            var mrp_price_new = $(this).find('input.new-mrp-price').val();
            var sale_price = $(this).find('input.sale-price-real').val();        
            
           // console.log(tyre_price);
           // console.log(tube_price);


            if(tyre_price_bulk != '' || tube_price_bulk != '')
            {           
                
                var product_id = $(this).attr('data-id');
                
                var prd_list = {};
                var price_list = {};
               // price_list.tyre_price_new = tyre_price_bulk;
               // price_list.tube_price_new = tube_price_bulk;
                price_list.mrp_price_new = mrp_price_new;
                price_list.mrp_price = mrp_price;
                price_list.sale_price = sale_price;     
                price_list.tyre_price = tyre_price;
                price_list.tube_price = tube_price;
                price_list.update_by = update_by;

                prd_list.product_id = product_id;
                prd_list.price_list = price_list;
               
                temp.push(prd_list);
            }
             
        });*/
       // console.log(temp);
        
        /*var admin_url = $('.admin-url').text();
        $.ajax({    
                type: "POST", 
                url: admin_url,
                data: {
                    action: 'change_product_price',
                    prd_list: temp,
                },
                success: function (data) {
                    
                    var data = $.parseJSON(data);
                    alert(data);
                    get_product_data();
                   $('.message-block').html('<div class="updated notice notice-success is-dismissible"><p>Price Changed!</p></div>');
                },
            });*/
    });
    $(document).on('change','.select-update-by',function(e){
        var selectoptionvalue  = jQuery(".select-update-by option:selected").val();
        if(selectoptionvalue == 'productvalue'){
            $('.update-tube-price input').attr("placeholder", "Amount");
            $(".update-tyre-price input").attr("placeholder", "Amount");
            $('.update-tube-price .per-after').remove();
            $('.update-tyre-price .per-after').remove();
        }
        
        if(selectoptionvalue == 'percentage'){
            $('.update-tube-price input').after("<span class='per-after'>%</span>");
            $(".update-tyre-price input").after("<span class='per-after'>%</span>");
            $('.update-tube-price input').attr("placeholder", "");
            $(".update-tyre-price input").attr("placeholder", "");
        }
        $(".update-tube-price").css("display","block");
        $(".update-tyre-price").css("display","block");
    });

    $(document).on('keyup','input.bulk-tube-price',function(){
        var added_tube_price = $(this).val();
        var update_by = $(".select-update-by option:selected").val();

        $(".change-price .single-product").each(function()
        {
            var tube_real_price = $(this).find('.tube-price-real').attr('data-price');
            if(tube_real_price == ''){
                tube_real_price = 0;
            }
            if(update_by == 'productvalue'){
                var new_tube_price = parseInt(added_tube_price) + parseInt(tube_real_price);
            }
            else{
                var per = added_tube_price;
                var per_val = parseInt(per) * parseInt(tube_real_price) / 100;
                var new_tube_price = parseInt(tube_real_price) + parseInt(per_val);
            }            
            
            if($(this).find('.tube-price-real').val() != '-' && $(this).find('.tube-price-real').val() != ''){
                $(this).find('.tube-price-real').val(new_tube_price);
            }
            

            if(added_tube_price == ''){
                if($(this).find('.tube-price-real').val() != '-'){
                    $(this).find('.tube-price-real').val(tube_real_price);
                }                

            }

            // for updating sale price
            var tube_price = $(this).find('.tube-price-real').val();
            var tyre_price = $(this).find('.tyre-price-real').val();
            if(tube_price == ''){
                tube_price = tube_real_price;
            }
            if(tube_price == '-'){
                
                tube_price = 0;
            }
            if(tyre_price == ''){
                tyre_price = $(this).find('.tyre-price-real').attr('data-price');
            }
          //  console.log(tyre_price);
            var new_sale_price = parseInt(tube_price) + parseInt(tyre_price);
            $(this).find('.sale-price-real').val(new_sale_price);
        });
    });

    $(document).on('keyup','input.bulk-tyre-price',function(){
        var added_tyre_price = $(this).val();
        var update_by = $(".select-update-by option:selected").val();
        $(".change-price .single-product").each(function()
        {
           // console.log($(this).attr('data-id'));
            var tyre_real_price = $(this).find('.tyre-price-real').attr('data-price');

            if(update_by == 'productvalue'){
                var new_tyre_price = parseInt(added_tyre_price) + parseInt(tyre_real_price);
            }
            else{
                var per = added_tyre_price;
                var per_val = parseInt(per) * parseInt(tyre_real_price) / 100;
                var new_tyre_price = parseInt(tyre_real_price) + parseInt(per_val);
            }
            $(this).find('.tyre-price-real').val(new_tyre_price);

            if(added_tyre_price == ''){
                $(this).find('.tyre-price-real').val(tyre_real_price);
            }

            // for updating sale price
            var tube_price = $(this).find('.tube-price-real').val();
            var tyre_price = $(this).find('.tyre-price-real').val();
           
            
            if(tube_price == ''){
               // console.log('blank');
                tube_price = $(this).find('.tube-price-real').attr('data-price');
                if(tube_price == ''){
                    tube_price = 0;
                }
            }
            if(tube_price == '-'){
               // console.log('undefined');
                tube_price = 0;
            }
            if(tyre_price == ''){
                tyre_price = tyre_real_price;
            }
           // console.log(tyre_price);
           // console.log(tube_price);
            var new_sale_price = parseInt(tube_price) + parseInt(tyre_price);
            $(this).find('.sale-price-real').val(new_sale_price);
        });
    });

    $(document).on('keyup','input.tube-price-real',function(){
        var single_product = $(this).parents('.single-product');
        var tube_price = $(this).val();
        var tyre_price = $(single_product).find('.tyre-price-real').val();

        if(tyre_price == ''){
            tyre_price = $(single_product).find('.tyre-price-real').attr('data-price');
        }

        var new_sale_price;
        console.log(tube_price);
        if(tube_price == '-' || tube_price == ''){
            console.log('not found');
            new_sale_price = tyre_price;
        }
        else{
            new_sale_price = parseInt(tube_price) + parseInt(tyre_price);
        }
        

        $(single_product).find('.sale-price-real').val(new_sale_price);
    });

    $(document).on('keyup','input.tyre-price-real',function(){
        var single_product = $(this).parents('.single-product');
        var tyre_price = $(this).val();
        
        var tube_price = $(single_product).find('.tube-price-real').val();
        console.log(tube_price);
        if(tube_price == ''){
            tube_price = $(single_product).find('.tube-price-real').attr('data-price');
            if(tube_price == ''){
                tube_price = '-';
            }
        }
        var new_sale_price;
        if(tube_price == '-'){
            new_sale_price = tyre_price;
        }
        else{
            new_sale_price = parseInt(tube_price) + parseInt(tyre_price);
        }
       

        $(single_product).find('.sale-price-real').val(new_sale_price);
    });

});

function get_product_data(){

    var admin_url = $('.admin-url').text();
    var width = $('.select-width').val();
    var ratio = $('.select-ratio').val();
    var diameter = $('.select-diameter').val();
    var cat = $('select.select-category').val();
    var loader = '<img src="https://www.tyrehub.com/wp-content/plugins/tyrehub-product-discount/loading.gif">'; 
        
        

    $.ajax({    
                type: "POST", 
                url: admin_url,
                data: {
                    action: 'product_data_for_changeprice',
                    width: width,
                    ratio: ratio,
                    diameter: diameter,
                    cat: cat,
                },
                beforeSend: function() {   
                    $('.change-price .product-details .body').html('');                     
                        $('.change-price .product-details .body').append(loader);
                    },
                success: function (data) {
                   
                    $('.change-price .product-details .body').html(data);
                },
            });
}