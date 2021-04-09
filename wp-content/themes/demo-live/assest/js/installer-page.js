jQuery(document).ready(function($)
{


	$(document).on("keyup","input.installer-prd",function(){var n=$(this).val().toLowerCase();if(n.length>2){$(".product-container .single-product").each(function(){-1!=$(this).find(".name").text().toLowerCase().indexOf(n)?(console.log("match"),1,$(this).css("display","block")):$(this).css("display","none")})}});


    /*$(document).on('change','.installer-home .qty select',function()
    {

        var qty = $(this).val();
        console.log(qty);
        var id = $(this).parents('.single-product').attr('data-id');
        var href = $(this).parents('.single-product').find('.add_to_cart_button').attr('href');
        href = '/?add-to-cart='+id+'&quantity='+qty;
        $(this).parents('.single-product').find('.add_to_cart_button').attr('href',href);
    });*/

     $(document).on('change','.installer-home .qty select',function()
        {

            var qty = $(this).val();
            console.log(qty);
            var proid = $(this).attr('data-proid');
            if(qty>0){
               $("#addcart"+proid).prop("checked", true);

               var price =$('#price'+proid).attr('data-price');
                $('.price-total'+proid).text( parseFloat(price) * parseFloat(qty));
               }else{
                  $("#addcart"+proid).prop("checked", false);
                  $('.price-total'+proid).text('00.00');

               }
            var total1=0;
            var total=0;
             $.each($("input[name='franaddtocart[]']:checked"), function(){
               var id=$(this).val();
               var qty=$('#fran_quantity'+id).val();

               total = $('.price-total'+id).text();
               total1 = (parseInt(total1) + parseInt(total));
            });

           $('#all-totalprice').text(parseInt(total1));

        });

     $(document).on('change','.wishlist .qty select',function()
        {
            $('#cover-spin').show();
            var qty = $(this).val();
             var t = $(".admin_url").text();
               var proid = $(this).attr('data-proid');
             jQuery.ajax({
                 type: "POST",
                 url: t,
                 data: {
                     action: "update_wishlist_qty",
                     qty:qty,
                     proid:proid
                 },
                 beforeSend: function() {
                     //jQuery(".product-container").html("<div class='modal-loader'><img src='https://www.tyrehub.com/wp-content/themes/demo/images/loading.gif' width='150px;' /></div>")
                 },
                 success: function(t) {
                     //jQuery(".product-container ").html(t);
                    $('#cover-spin').hide();
                 },
                 error: function(t) {}
             });
           

        });


     $(document).on('click','.wishlist .wish-delete ',function()
        {
            $('#cover-spin').show();
           //$(this).closest('.single-product').remove();
             var t = $(".admin_url").text();
               var wish_id = $(this).attr('data-wishid');
               var idn= $(this).attr('id');
             jQuery.ajax({
                 type: "POST",
                 url: t,
                 data: {
                     action: "delete_wishlist_qty",
                     wish_id:wish_id
                 },
                 beforeSend: function() {
                     //jQuery(".product-container").html("<div class='modal-loader'><img src='https://www.tyrehub.com/wp-content/themes/demo/images/loading.gif' width='150px;' /></div>")
                 },
                 success: function(t) {
                     //jQuery(".product-container ").html(t);
                    //$("div").parent().parent().remove();
                     $('#'+idn).closest('.single-product').remove();
                    $('#cover-spin').hide();
                 },
                 error: function(t) {}
             });
           

        });
     $(document).on('click','.franchise-po-generate .wish-delete ',function()
        {
            $('#cover-spin').show();
           //$(this).closest('.single-product').remove();
             var t = $(".admin_url").text();
               var wish_id = $(this).attr('data-wishid');
               var idn= $(this).attr('id');
             jQuery.ajax({
                 type: "POST",
                 url: t,
                 data: {
                     action: "delete_wishlist_qty",
                     wish_id:wish_id
                 },
                 beforeSend: function() {
                     //jQuery(".product-container").html("<div class='modal-loader'><img src='https://www.tyrehub.com/wp-content/themes/demo/images/loading.gif' width='150px;' /></div>")
                 },
                 success: function(t) {
                     //jQuery(".product-container ").html(t);
                    //$("div").parent().parent().remove();
                     $('#'+idn).closest('.single-product').remove();
                    $('#cover-spin').hide();
                 },
                 error: function(t) {}
             });
           

        });

   $(document).on('change','.frabaddtocart',function()
        {
                var id = $(this).val();
             if($(this).is(":checked")) {
                $("#fran_quantity"+id).val(1);
            var price =$('#price'+id).attr('data-price');
            $('.price-total'+id).text( parseFloat(price) * 1);

             }else{

                $("#fran_quantity"+id).val(0);
                $('.price-total'+id).html('00.00');
            }


            var total1=0;
            $.each($("input[name='franaddtocart[]']:checked"), function(){
               var id=$(this).val();
               var qty=$('#fran_quantity'+id).val();

               var total = $('.price-total'+id).text();
                 total1 = parseFloat(total1) + parseFloat(total);
            });

          $('#all-totalprice').text(parseFloat(total1));

    });

    $(document).on("click", ".searchbywidth", function() {
        var t = $(".admin_url").text();
        var width = $('.width').val();
        var ratio = $('.ratio').val();
        var diameter = $('.diameter').val();
        var brand = $('.searchbyname').val();

        var shop_page = $('.shop_page_url').text();

        var errors = false;

         if (width == "" || width ==null || width=='Width'){
          $(".width").addClass('errormsg');
               errors= true;
        }else{
          $(".width").removeClass('errormsg');
             errors= false;
         }



        if((width == "" || width ==null || width=='Width') ) {
          errors=true;
        }else{

           errors=false;
        }

        if(errors == true){

            return false;
        }else{
            if(ratio == 0)
            {
              ratio = 'R';
            }

            //var url = shop_page+'?searchby=size&filter_vehicle-type=car-tyre&qt_vehicle-type=or&filter_width='+width+'&filter_ratio='+ratio+'&filter_diameter='+diameter+'&vehicle_type='+vehicle_type;
            $('#all-totalprice').text('00.00');
                 $.ajax({
                     type: "POST",
                     url: t,
                     data: {
                         action: "installer_product_by_attribute",
                         width: width,
                         ratio:ratio,
                         diameter:diameter,
                         name: brand
                     },
                     beforeSend: function() {
                         $(".product-container ").html("<div class='modal-loader'><img src='https://www.tyrehub.com/wp-content/themes/demo/images/loading.gif' width='150px;' /></div>")
                     },
                     success: function(t) {
                         $(".product-container ").html(t)
                     },
                     error: function(t) {}
                 })
        }

     /*var t = $(".admin_url").text(),
         a = $(".width").val(),
         e = $(".ratio").val(),
         i = $(".diameter").val(),
         n = $(".searchbyname").val();*/

 });

// $( ".qty #fran_quantity" ).change(function() {

//   alert( "Handler for .change() called." );

// });

$(document).on('click','.byvehicle .store-search',function()
{
    var sub_modal = $('.select-sub-model').val();
    var modal = $('.select-model').val();
    var make = $('.select-car-cmp').val();

     var brand =  $(".searchbyname").val();

    var shop_page = $('.shop_page_url').text();
    var admin_url = $('.admin_url').text();



    var errors = false;
    //$(".errors").remove();

  //refresh error messages on submit

if (make == "" || make ==null){
  $(".select-car-cmp").addClass('errormsg');
       errors= true;

  }else{
    $(".select-car-cmp").removeClass('errormsg');
       errors= false;
  }

//validate name field has entry

 if (modal == "" || modal ==null){

 $(".select-model").addClass('errormsg');
     errors= true;
}else{
  $(".select-model").removeClass('errormsg');
       errors= false;
}

if (sub_modal == "" || sub_modal ==null){
 $(".select-sub-model").addClass('errormsg');
     errors= true;
}else{
  $(".select-sub-model").removeClass('errormsg');
       errors= false;
}

if (brand == "" || brand ==null){
 $("#vehicle_type_model").addClass('errormsg');
     errors= true;
}else{
  $("#vehicle_type_model").removeClass('errormsg');
       errors= false;
}

if((sub_modal == "" || sub_modal ==null) || (brand == "" || brand ==null)|| (make == "" || make ==null) || (modal == "" || modal ==null)) {
  errors=true;
}else{

   errors=false;
}


if(errors == true){
    return false;
}else{
    //$('#cover-spin').show(0);
        $.ajax({
                    type: "POST",
                    url: admin_url,
                    dataType: "json",
                    data: {
                        action: 'get_data_by_submodel',
                        sub_modal : sub_modal,
                        modal : modal,
                        make : make,
                    },
                    success: function (data) {

                        if(data.diameter != null)
                        {
                           var diameter = Math.floor(data.diameter);
                        }
                        else{
                            var diameter = data.diameter;
                        }


                         $.ajax({
                             type: "POST",
                             url: admin_url,
                             data: {
                                 action: "installer_product_by_attribute",
                                 width: data.width,
                                 ratio:data.ratio,
                                 diameter:diameter,
                                 name: brand
                             },
                             beforeSend: function() {
                                 $(".product-container ").html("<div class='modal-loader'><img src='https://www.tyrehub.com/wp-content/themes/demo/images/loading.gif' width='150px;' /></div>")
                             },
                             success: function(t) {
                                 $(".product-container ").html(t)
                             },
                             error: function(t) {}
                         })

                        //window.location.href = url;
                    },
                    error: function (errorThrown) {
                    }
                });
}
//validate form

});


     $(document).on("click", ".offline-customer-width", function() {
        var t = $(".admin_url").text();
        var width1;
        var width = $('.select-width :selected').text();
          //width1=width.replace('.', '-');

        //var ratio = $('.select-ratio :selected').text();
        var ratio =$(".select-ratio :selected").text();
        var ratio_val = $('.select-ratio').val();
       // alert(ratio_val+'----'+ratio);
        //var brand =  $(".bysize .searchbyname").val();
        var vehicle_type =  $(".bysize #vehicle_type_width").val();

        //var diameter = $('.select-diameter :selected').text();
        var diameter =$(".select-diameter :selected").text();
        var diameter = Math.floor(diameter);
        var diameter_value = $('.select-diameter').val();
        var shop_page = $('.shop_page_url').text();

        var errors = false;

         if (width == "" || width ==null || width=='Width'){
          $(".select-width").addClass('errormsg');
               errors= true;
        }else{
          $(".select-width").removeClass('errormsg');
             errors= false;
         }

         if (ratio == "" || ratio ==null || ratio=='Ratio/Profile'){
          $(".select-ratio").addClass('errormsg');
               errors= true;
        }else{
          $(".select-ratio").removeClass('errormsg');
             errors= false;
         }

         if (diameter_value == "" || diameter_value ==null ){
          $(".select-diameter").addClass('errormsg');
               errors= true;
        }else{
          $(".select-diameter").removeClass('errormsg');
             errors= false;
         }
         if (vehicle_type == "" || vehicle_type ==null){
          $("#vehicle_type_width").addClass('errormsg');
               errors= true;
        }else{
          $("#vehicle_type_width").removeClass('errormsg');
             errors= false;
         }

        if((width == "" || width ==null || width=='Width') || (ratio == "" || ratio ==null || ratio=='Ratio/Profile')|| (diameter == "" || diameter ==null) || (vehicle_type == "" || vehicle_type ==null)) {
          errors=true;
        }else{

           errors=false;
        }

        if(errors == true){
            return false;
        }else{
            /*if(ratio == 0)
            {
              ratio = 'R';
            }*/

            //var url = shop_page+'?searchby=size&filter_vehicle-type=car-tyre&qt_vehicle-type=or&filter_width='+width+'&filter_ratio='+ratio+'&filter_diameter='+diameter+'&vehicle_type='+vehicle_type;

                 $.ajax({
                     type: "POST",
                     url: t,
                     data: {
                         action: "franchise_product_by_customer",
                         width: width,
                         ratio:ratio,
                         diameter:diameter,
                         vehicle_type: vehicle_type
                     },
                     beforeSend: function() {
                        $(".installer-home").removeClass("franchise_banner");
                         $(".product-container ").html("<div class='modal-loader'><img src='https://www.tyrehub.com/wp-content/themes/demo/images/loading.gif' width='150px;' /></div>")
                     },
                     success: function(t) {
                        
                         $(".product-container ").html(t)
                     },
                     error: function(t) {}
                 })
        }
 });

$(document).on('click','.offline-customer-bymodel',function()
{
    var sub_modal = $('.select-sub-model').val();
    var modal = $('.select-model').val();
    var make = $('.select-car-cmp').val();

     var vehicle_type =  $("#vehicle_type_model").val();

    var shop_page = $('.shop_page_url').text();
    var admin_url = $('.admin_url').text();



    var errors = false;
    //$(".errors").remove();

  //refresh error messages on submit

if (make == "" || make ==null){
  $(".select-car-cmp").addClass('errormsg');
       errors= true;

  }else{
    $(".select-car-cmp").removeClass('errormsg');
       errors= false;
  }

//validate name field has entry

 if (modal == "" || modal ==null){

 $(".select-model").addClass('errormsg');
     errors= true;
}else{
  $(".select-model").removeClass('errormsg');
       errors= false;
}

if (sub_modal == "" || sub_modal ==null){
 $(".select-sub-model").addClass('errormsg');
     errors= true;
}else{
  $(".select-sub-model").removeClass('errormsg');
       errors= false;
}

if (vehicle_type == "" || vehicle_type ==null){
 $("#vehicle_type_model").addClass('errormsg');
     errors= true;
}else{
  $("#vehicle_type_model").removeClass('errormsg');
       errors= false;
}

if((sub_modal == "" || sub_modal ==null) || (vehicle_type == "" || vehicle_type ==null)|| (make == "" || make ==null) || (modal == "" || modal ==null)) {
  errors=true;
}else{

   errors=false;
}


if(errors == true){
    return false;
}else{
    //$('#cover-spin').show(0);
$.ajax({
        type: "POST",
        url: admin_url,
        dataType: "json",
        data: {
            action: 'get_data_by_submodel',
            sub_modal : sub_modal,
            modal : modal,
            make : make,
        },
        success: function (data) {

            if(data.diameter != null)
            {
               var diameter = Math.floor(data.diameter);
            }
            else{
                var diameter = data.diameter;
            }


             $.ajax({
                 type: "POST",
                 url: admin_url,
                 data: {
                     action: "franchise_product_by_customer",
                     width: data.width,
                     ratio:data.ratio,
                     diameter:diameter,
                     vehicle_type: vehicle_type
                 },
                 beforeSend: function() {
                    $(".installer-home").removeClass("franchise_banner");
                     $(".product-container ").html("<div class='modal-loader'><img src='https://www.tyrehub.com/wp-content/themes/demo/images/loading.gif' width='150px;' /></div>")
                 },
                 success: function(t) {
                    
                     $(".product-container ").html(t)
                 },
                 error: function(t) {}
             })

            //window.location.href = url;
        },
        error: function (errorThrown) {
        }
    });
}
//validate form

});


$(document).ready(function(){

    $(document).on('change','.product-qty',function()
    {
            var add_to_cart_button = jQuery( this ).parents( ".product" ).find( ".add_to_cart_button" );

            // For AJAX add-to-cart actions
            add_to_cart_button.attr( "data-quantity", jQuery( this ).val() );
            //$("#offline-product").attr("data-quantity",jQuery( this ).val());

    });

});



/*jQuery( "#offline-product .quantity .product-qty" ).on("change",function() {
    alert('asdsadsads');
            var add_to_cart_button = jQuery( this ).parents( ".product" ).find( ".add_to_cart_button" );
            // For AJAX add-to-cart actions
            add_to_cart_button.data( "quantity", jQuery( this ).val() );
            // For non-AJAX add-to-cart actions
            add_to_cart_button.attr( "href", "?add-to-cart=" + add_to_cart_button.attr( "data-product_id" ) + "&variation_id="+add_to_cart_button.attr( "data-product_id" )+"&quantity=" + jQuery( this ).val() );
        });*/

$(document).on('click','.offline-wishlist',function()
{

    var product_id=$(this).attr('data-product_id');
    var variation_id=$(this).attr('data-variation_id');
    var quantity=$(this).attr('data-quantity');
    

    var admin_url = $('.admin_url').text();
        $('#cover-spin').show(0);
         $.ajax({
                 type: "POST",
                 url: admin_url,
                 data: {
                     action: "offline_product_add_to_wishlist",
                     product_id: product_id,
                     variation_id:variation_id,
                     quantity:quantity
                 },
                 beforeSend: function() {
                 },
                 success: function(t) {
                     //$(".product-container ").html(t)
                     
                     //$(this).attr('data-wish')=t;
                     //$('#wishcount').text(t);
                      $('#wish'+variation_id+' #wishcount').text(t);
                      $('#cover-spin').hide(0);
                     //var beforeqty=0;
                    // var newqty=0;
                    // beforeqty=parseInt($('.head-cart .cart-contents-count').text());
                     //newqty = (beforeqty + parseInt(quantity));
                     //$('.head-cart .cart-contents-count').text('');
                     //$('.head-cart .cart-contents-count').text(newqty);
                     
                 },
                 error: function(t) {}
             })


    });
$(document).on('click','.offline-product',function()
{

    var product_id=$(this).attr('data-product_id');
    var variation_id=$(this).attr('data-variation_id');
    var quantity=$(this).attr('data-quantity');
    var vehicle_type=$('#vehicle_type_width').val();
    if(vehicle_type==''){
      var vehicle_type=$('#vehicle_type_model').val();
    }

    var admin_url = $('.admin_url').text();
        $('#cover-spin').show(0);
         $.ajax({
                 type: "POST",
                 url: admin_url,
                 data: {
                     action: "offline_product_add_to_cart",
                     product_id: product_id,
                     variation_id:variation_id,
                     quantity:quantity,
                     vehicle_type:vehicle_type
                 },
                 beforeSend: function() {
                     //$(".product-container ").html("<div class='modal-loader'><img src='https://www.tyrehub.com/wp-content/themes/demo/images/loading.gif' width='150px;' /></div>")
                 },
                 success: function(t) {
                     //$(".product-container ").html(t)
                     window.location=t;
                     var beforeqty=0;
                     var newqty=0;
                     beforeqty=parseInt($('.head-cart .cart-contents-count').text());
                     newqty = (beforeqty + parseInt(quantity));
                     $('.head-cart .cart-contents-count').text('');
                     $('.head-cart .cart-contents-count').text(newqty);
                     $('#cover-spin').hide(0);
                 },
                 error: function(t) {}
             })


    });

    $(document).on('click','.franshise-purchase .add_to_cart_button',function()
    {


            var products =[];
            var quantity=0;
            $.each($("input[name='franaddtocart[]']:checked"), function(){
               var id=$(this).val();
               var qty=$('#fran_quantity'+id).val();
                products.push({id : id,qty :qty});
                quantity = parseInt(quantity) + parseInt(qty);
            });

        var admin_url = $('.admin_url').text();


            $('#cover-spin').show(0);
             $.ajax({
                     type: "POST",
                     url: admin_url,
                     data: {
                         action: "franchise_product_add_to_cart",
                         products: products
                     },
                     beforeSend: function() {
                         //$(".product-container ").html("<div class='modal-loader'><img src='https://www.tyrehub.com/wp-content/themes/demo/images/loading.gif' width='150px;' /></div>")
                     },
                     success: function(t) {
                        var obj = JSON.parse(t); 
                         //$(".product-container ").html(t)
                         //window.location=t;
                     if(obj.QTY!='' && obj.QTY!=null){
                        var text="You have already added "+obj.QTY+" Tyre of '"+obj.product+"' in your cart. You can order max 10 qty per product in a single order.";
                        $('#dupplicate_pro_msg').text(text);

                       $('#dupplicate_prd_cart').modal('show'); 
                     }
                     /*var beforeqty=0;
                     var newqty=0;
                     beforeqty=parseInt($('.head-cart .cart-contents-count').text());
                     newqty = (beforeqty + parseInt(quantity));
                     $('.head-cart .cart-contents-count').text('');*/
                     $('.head-cart .cart-contents-count').text(obj.cartQTY);

                         $('#cover-spin').hide(0);
                     },
                     error: function(t) {}
                 })


        });


$(document).on("click", "#admin_access,#admin_redend_otp", function() {

        var t = $(".admin_url").text();
        var shop_page = $('.shop_page_url').text();
        $('#cover-spin').show(0);
     $.ajax({
         type: "POST",
         url: t,
         data: {
             action: "installer_admin_access_menu"
         },
         beforeSend: function() {
             //$(".product-container ").html("<div class='modal-loader'><img src='https://www.tyrehub.com/wp-content/themes/demo/images/loading.gif' width='150px;' /></div>")
         },
         success: function(t) {
            $('#adminAccess').modal('show');
            $('#cover-spin').hide(0);
         },
         error: function(t) {}
     })

 });

$(document).on("click", "#adminAccess .admin-access-verify", function() {
        var t = $(".admin_url").text();
        var shop_page = $('.shop_page_url').text();
        var admin_verify_otp= $('#admin_verify_otp').val();


        if (admin_verify_otp == "" || admin_verify_otp ==null){
          jQuery("#admin_verify_otp").addClass('errormsg');
               errors= true;
        }else{
          jQuery("#admin_verify_otp").removeClass('errormsg');
             errors= false;
         }




        if((admin_verify_otp == "" || admin_verify_otp ==null)) {
           errors=true;
        }else{
           errors=false;
        }

        if(errors == true){
            return false;
        }else{
            $('#cover-spin').show(0);
               $.ajax({
                 type: "POST",
                 url: t,
                 data: {
                     action: "admin_verify_otp",
                     verify_otp: admin_verify_otp
                 },
                 beforeSend: function() {
                     //$(".product-container ").html("<div class='modal-loader'><img src='https://www.tyrehub.com/wp-content/themes/demo/images/loading.gif' width='150px;' /></div>")
                 },
                 success: function(t) {
                    //$('#adminAccess').modal('show');
                    if(t==0){
                      $("#admin_verify_otp").after( "<p style='color:red;'>The OTP entered is incorrect.</p>" );
                    }else{
                         location.reload(true);
                    }

                    $('#cover-spin').hide(0);
                 },
                 error: function(t) {}
             });
        }


 });
$(document).on("click", "#admin_access_logout", function() {

        var t = $(".admin_url").text();
        var shop_page = $('.shop_page_url').text();
        $('#cover-spin').show(0);
     $.ajax({
         type: "POST",
         url: t,
         data: {
             action: "admin_access_logout"
         },
         beforeSend: function() {
             //$(".product-container ").html("<div class='modal-loader'><img src='https://www.tyrehub.com/wp-content/themes/demo/images/loading.gif' width='150px;' /></div>")
         },
         success: function(t) {
           location.reload(true);
         },
         error: function(t) {}
     })

 });

});

