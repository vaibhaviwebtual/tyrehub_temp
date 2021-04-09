jQuery(document).ready(function($) 
{
  $(".know-more").on('click', function(event) {

    // Make sure this.hash has a value before overriding default behavior
    if (this.hash !== "") {
      // Prevent default anchor click behavior
      event.preventDefault();

      // Store hash
      var hash = this.hash;
      var dataa = $(hash).offset().top - 500;
      // Using jQuery's animate() method to add smooth page scroll
      // The optional number (800) specifies the number of milliseconds it takes to scroll to the specified area
      $('html, body').animate({
        scrollTop: dataa
      }, 800, function(){
   
        // Add hash (#) to URL when done scrolling (default click behavior)
        window.location.hash = hash;
      });
    } // End if
  });
    
    var ua = window.navigator.userAgent;
    var msie = ua.indexOf("MSIE ");

    if (msie > 0) // If Internet Explorer, return version number
    {
        alert(parseInt(ua.substring(msie + 5, ua.indexOf(".", msie))));
    }
	var site_url = $('.site_url').text();
    $(document).on('click','.vertical-tab .vertical-tab-content.active a',function(){
        var id = '.'+$(this).attr('id');
        var image = '.img_'+$(this).attr('id');
        $('.vertical-tab-content.active').find('a.toggle-btn').removeClass('active');
        $(this).addClass('active');
        $('.vertical-tab-content.active').find('.form-tab').removeClass('active');
        $('.vertical-tab-content').find(id).addClass('active');
        $('.tab_img').removeClass('active');
        $(image).addClass('active');

    });
});

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            jQuery('#blah')
                .attr('src', e.target.result)
                .width(150)
                .height(200);
        };

        reader.readAsDataURL(input.files[0]);
    }
}

// for location search
     var placeSearch, autocomplete;
          var componentForm = {
            street_number: 'short_name',
            route: 'long_name',
            locality: 'long_name',
            administrative_area_level_1: 'short_name',
            country: 'long_name',
            postal_code: 'short_name'
          };


      function initAutocomplete1() {

        // Create the autocomplete object, restricting the search to geographical
        // location types.
        var options = { componentRestrictions: {country: "in"} };
        autocomplete = new google.maps.places.Autocomplete(
            /** @type {!HTMLInputElement} */(document.getElementById('myInput')),options,
            {types: ['geocode']});

        // When the user selects an address from the dropdown, populate the address
        // fields in the form.
        autocomplete.addListener('place_changed', fillInAddress);
      }

      function fillInAddress() {
        // Get the place details from the autocomplete object.
        var place = autocomplete.getPlace();

        for (var component in componentForm) {
          document.getElementById(component).value = '';
          document.getElementById(component).disabled = false;
        }

        // Get each component of the address from the place details
        // and fill the corresponding field on the form.
        for (var i = 0; i < place.address_components.length; i++) {
          var addressType = place.address_components[i].types[0];
          if (componentForm[addressType]) {
            var val = place.address_components[i][componentForm[addressType]];
            document.getElementById(addressType).value = val;
          }
        }
      }

      
 function geolocate() {
   //alert('hii');
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
            var geolocation = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };
            var circle = new google.maps.Circle({
              center: geolocation,
              radius: position.coords.accuracy
            });
            autocomplete.setBounds(circle.getBounds());
          });
        }
      }

/* ---------- home page select functionality ---------- */
jQuery(document).ready(function($) 
{
	var admin_url = $('.admin_url').text();
	 var car_cmp = $('.select-car-cmp').val();
     
    $.ajax({
            type: "POST", 
            url: admin_url,
            data: {
                action: 'select_model',
                car_cmp : car_cmp,    
            },
            success: function (data) {
                $('.select-model').html(data);
                $('.select-model').removeAttr('disabled');
                $(".year-wrapper").find(".new-loader").remove();

                 var model = $('.select-model').val();
                 $.ajax({
		            type: "POST", 
		            url: admin_url,
		            data: {
		                action: 'select_sub_modal',  
		                model: model,      
		            },
		            success: function (data) {
		              $('.select-sub-model').html(data);
		              $('.select-sub-model').removeAttr('disabled');
		               $(".model-wrapper").find(".new-loader").remove();
		            },
		            error: function (errorThrown) {
		            }
		        });

            },
            error: function (errorThrown) {
            }
        });

    
    // When select car compny dropdown
    $(document).on('change','.select-car-cmp',function()
    {
        $('.select-model').html('<option value="" disabled selected>Model</option>');
        $('.select-year').html('<option value="" disabled selected>Year</option>');
         $('.select-error').css('display','none');
        var car_cmp = $(this).val();
        var admin_url = $('.admin_url').text();
        var loader = "<span class='new-loader'><img src='https://tyrehub.com/loading.gif' width='20' height='20' />";
        $(".year-wrapper").append(loader);
        $.ajax({
                    type: "POST", 
                    url: admin_url,
                    data: {
                        action: 'select_model',
                        car_cmp : car_cmp,         
                    },
                    success: function (data) {

                      $('.select-model').html(data);
                      $('.select-model').removeAttr('disabled');
                      $(".year-wrapper").find(".new-loader").remove();
                    },
                    error: function (errorThrown) {
                    }
                });
    });

    // When select car name dropdown
    $(document).on('change','.select-model',function(){
        
        var year = $(this).val();
       var model = $('.select-model').val();
        $('.select-error').css('display','none');
        var admin_url = $('.admin_url').text();
        var loader = "<span class='new-loader'><img src='https://tyrehub.com/loading.gif' width='20' height='20' />";
        $(".model-wrapper").append(loader);
        $.ajax({
                    type: "POST", 
                    url: admin_url,
                    data: {
                        action: 'select_sub_modal',
                        model : model,       
                    },
                    success: function (data) {
                      $('.select-sub-model').html(data);
                      $('.select-sub-model').removeAttr('disabled');
                       $(".model-wrapper").find(".new-loader").remove();
                    },
                    error: function (errorThrown) {
                    }
                });
    });

    // select width dropdown
    
    $(document).on('change','.select-width',function()
    {
        $('.select-error').css('display','none');   
        $('.select-ratio').html('<option value="" disabled selected>Ratio/Profile</option>');
        $('.select-diameter').html('<option value="" disabled selected>Rim Diameter</option>');
        var width = $(this).val();
        var admin_url = $('.admin_url').text();
        var loader = "<span class='new-loader'><img src='https://tyrehub.com/loading.gif' width='20' height='20' />";
        $(".ratio-wrapper").append(loader);
        $.ajax({
                    type: "POST", 
                    url: admin_url,
                    data: {
                        action: 'select_ratio',
                        width : width,         
                    },
                    success: function (data) 
                    {
                      $('.select-ratio').html(data);
                      $('.select-ratio').removeAttr('disabled');
                      $(".ratio-wrapper").find(".new-loader").remove();
                      var ratio_val = $(document).find('.select-ratio').val();
                      console.log(ratio_val);
                      $('.select-ratio').trigger('change');
                    },
                    error: function (errorThrown) {
                    }
                });
    });

     // select width dropdown
    
    $(document).on('change','.select-ratio',function()
    {
        var ratio = $(this).val();
       
        var width = $('.select-width').val();
        var admin_url = $('.admin_url').text();
        var loader = "<span class='new-loader'><img src='https://tyrehub.com/loading.gif' width='20' height='20' />";
        $(".diameter-wrapper").append(loader);
        $.ajax({
                    type: "POST", 
                    url: admin_url,
                    data: {
                        action: 'select_diameter',
                        ratio : ratio,
                        width : width,         
                    },
                    success: function (data) {
                      $('.select-diameter').html(data);
                      $('.select-diameter').removeAttr('disabled');
                      $(".diameter-wrapper").find(".new-loader").remove();
                    },
                    error: function (errorThrown) {
                    }
                });
    });


    
    $(document).on('click','.get-tyre-bywidth',function()
    {
       
        var width1;
          var width = $('.select-width :selected').text();
          //width1=width.replace('.', '-');
         
        //var ratio = $('.select-ratio :selected').text();
          var ratio =$(".select-ratio option:selected").text();
        var ratio_val = $('.select-ratio').val();
       // alert(ratio_val+'----'+ratio);
        var vehicle_type = $('#vehicle_type_size').val();  
        
        //var diameter = $('.select-diameter :selected').text();
        var diameter =$(".select-diameter option:selected").text();
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
          $("#vehicle_type_size").addClass('errormsg');         
               errors= true;
        }else{    
          $("#vehicle_type_size").removeClass('errormsg');         
             errors= false;
         }

if((width == "" || width ==null || width=='Width') || (ratio == "" || ratio ==null || ratio=='Ratio/Profile')|| (diameter_value == "" || diameter_value ==null) || (vehicle_type == "" || vehicle_type ==null)) {
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

            var url = shop_page+'?searchby=size&filter_vehicle-type=car-tyre&qt_vehicle-type=or&filter_width='+width+'&filter_ratio='+ratio+'&filter_diameter='+diameter+'&vehicle_type='+vehicle_type;
            
            window.location.href = url;
        }
        
        

    });



$(document).on('click','.get-tyre-bymodel',function()
{
    var sub_modal = $('.select-sub-model').val(); 
    var modal = $('.select-model').val();
    var make = $('.select-car-cmp').val();
    
     var vehicle_type = $('#vehicle_type_model').val();  
     
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
                        vehicle_type : vehicle_type, 
                    },
                    success: function (data) 
                    {
                     // data = data.replace(/(\r\n|\n|\r)/gm, "");

                      /*var url = shop_page+'car-tyre/'+data.submodel_name+'/';
                      window.location.href = url;*/ 
                      
                        if(data.diameter != null)
                        {
                           var diameter = Math.floor(data.diameter);
                        }
                        else{
                            var diameter = data.diameter;
                        }
                      
                        var url = shop_page+'?searchby=model&filter_vehicle-type=car-tyre&qt_vehicle-type=or';

                        if(data.width != 0)
                        {
                          if(data.width != ""  && data.width != null){
                           url += '&filter_width='+data.width+'&qt_width=or';
                          }else{
                            url += '&filter_width=test&qt_width=or';
                          }
                        }

                        if(data.ratio != 0)
                        {
                          if(data.ratio != ""  && data.ratio != null){
                           url += '&filter_ratio='+data.ratio+'&qt_ratio=or';
                          }else{
                            url += '&filter_ratio=test&qt_ratio=or';
                          }
                           
                        }else{
                          url += '&filter_ratio=r&qt_ratio=or';
                        }

                        if(diameter != 0 )
                        {
                          if(diameter != "" && diameter != null){
                           url += '&filter_diameter='+diameter+'&qt_diameter=or';
                          }else{
                            url += '&filter_diameter=test&qt_diameter=or';
                          }
                          
                        }else{
                          url += '&filter_diameter=r&qt_diameter=or';
                        } 

                        if(vehicle_type != 0 )
                        {
                          if(vehicle_type != "" && vehicle_type != null){
                           url += '&vehicle_type='+vehicle_type+'&';
                          }else{
                            url += '&vehicle_type=test';
                          }
                          
                        }else{
                          url += '&vehicle_type=0';
                        }  
                        //alert(url);
                        //$('#cover-spin').hide(0);
                        window.location.href = url;                      
                    },
                    error: function (errorThrown) {
                    }
                });
}
//validate form
  
});

    

    $(document).on('click','.get-tyre-bymodel-twowheel',function()
    {
          var sub_modal = $('.select-sub-model').val(); 
          var modal = $('.select-model').val();
          var make = $('.select-car-cmp').val();
          var vehicle_type = $('#vehicle_type_two_model').val();

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
 $("#vehicle_type_two_model").addClass('errormsg');        
     errors= true;                            
}else{
  $("#vehicle_type_two_model").removeClass('errormsg');         
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
                          vehicle_type : vehicle_type, 

                      },
                      success: function (data) 
                      {
                          
                          


                          if(data.diameter != null){
                             var diameter = Math.floor(data.diameter);
                            }else{
                              var diameter = data.diameter;
                            }                      
                        
                      var url = shop_page+'?searchby=model&filter_vehicle-type=two-wheeler&qt_vehicle-type=or&vehicle=twowheel';
                        if(data.width != 0 )
                        {
                          if(data.width != "" && data.width != null){
                           url += '&filter_width='+data.width+'&qt_width=or';
                          }else{
                            url += '&filter_width=test&qt_width=or';
                          }
                        }
                        if(data.ratio != 0 )
                        {
                          if(data.ratio != "" && data.ratio != null){
                           url += '&filter_ratio='+data.ratio+'&qt_ratio=or';
                          }else{
                            url += '&filter_ratio=test&qt_ratio=or';
                          }
                           
                        }else{
                          url += '&filter_ratio=0&qt_ratio=or';
                        }
                        if(diameter != 0 )
                        {
                          if(diameter != "" && diameter != null){
                           url += '&filter_diameter='+diameter+'&qt_diameter=or';
                          }else{
                            url += '&filter_diameter=test&qt_diameter=or';
                          }
                          
                        }else{
                          url += '&filter_diameter=0&qt_diameter=or';
                        }

                        if(vehicle_type != 0 )
                        {
                          if(vehicle_type != "" && vehicle_type != null){
                           url += '&vehicle_type='+vehicle_type+'&';
                          }else{
                            url += '&vehicle_type=test';
                          }
                          
                        }else{
                          url += '&vehicle_type=0';
                        }

                       window.location.href = url;
                       //$('#cover-spin').hide(0);
                      },
                      error: function (errorThrown) {
                      }
                  });
}

       
      });

    $(document).on('click','.get-tyre-bywidth-twowheel',function()
      {
         $('.select-error-size').css('display','none');
          var width1;
          var width = $('.select-width :selected').text();
          width1=width.replace('.', '-');
          var ratio = $('.select-ratio :selected').text();
          var diameter = $('.select-diameter :selected').text();
          var vehicle_type = $('#vehicle_type_two_size').val();

          var diameter = Math.floor(diameter);
          var diameter_value = $('.select-diameter').val();
          var shop_page = $('.shop_page_url').text();
          
          var errors = false;

         if (width == "" || width ==null || width == "Width"){
          $(".select-width").addClass('errormsg');         
               errors= true;
        }else{    
          $(".select-width").removeClass('errormsg');         
             errors= false;
         }

         if (ratio == "" || ratio ==null || ratio == "Ratio/Profile"){
          $(".select-ratio").addClass('errormsg');         
               errors= true;
        }else{    
          $(".select-ratio").removeClass('errormsg');         
             errors= false;
         }

         if (diameter == "" || diameter ==null || diameter =='Rim Diameter'){
          $(".select-diameter").addClass('errormsg');         
               errors= true;
        }else{    
          $(".select-diameter").removeClass('errormsg');         
             errors= false;
         }
         if (vehicle_type == "" || vehicle_type ==null){
          $("#vehicle_type_two_size").addClass('errormsg');         
               errors= true;
        }else{    
          $("#vehicle_type_two_size").removeClass('errormsg');         
             errors= false;
         } 
if((width == "" || width ==null || width=='Width') || (ratio == "" || ratio ==null || ratio=='Ratio/Profile')|| (diameter_value == "" || diameter_value ==null) || (vehicle_type == "" || vehicle_type ==null)) {
  errors=true;
}else{

   errors=false;
}
if(errors == true){
    return false;
}else{
    var url = shop_page+'?searchby=size&filter_vehicle-type=two-wheeler&qt_vehicle-type=or&vehicle=twowheel&filter_width='+width+'&filter_ratio='+ratio+'&filter_diameter='+diameter+'&vehicle_type='+vehicle_type;
          
    window.location.href = url;
}



         
      });

    $(document).on('click','.get-installer',function()
    {
        
        var vehicle_type = $("input[name='vehicle_type']:checked").val();

    });

    $(document).on('click','.shop-page .show-filter',function()
    {
      $('.column-left.column-filters').animate({"right":"0"},"slow");
      //$('.column-left.column-filters').toggle();
    });

    $(document).on('click','.shop-page .close-filter',function()
    {
      $('.column-left.column-filters').animate({"right":"110%"},"slow");
    });


   
    //getLocation();
    function getLocation()
    {
        if (navigator.geolocation) {              
            navigator.geolocation.getCurrentPosition(showPosition);
        } else { 
           // x.innerHTML = "Geolocation is not supported by this browser.";
        }
    }

    function showPosition(position)
    {        
        //var R = 6371; // km (change this constant to get miles)
       
        var current_lat = position.coords.latitude;
        
        var current_lon = position.coords.longitude;

         $('.current-lat').text(current_lat);
        $('.current-lon').text(current_lon);
 
    }

    
      

   /* myarray.sort(function(a,b){ 
        return a - b
    });
    */
    $(document).on('click','#service_modal .service_name',function() 
    {
      
      var service = $(this).parent().find('.sname').text();
      if(service == "Wheel Balancing" || service == "Wheel alignment")
      {
        var tyre = $(this).parent().find('select.cart_tyre').val();
       
        if(tyre == 0){
            $(this).removeAttr('checked','checked');
        }
      }
        
        service_price_calculation();
    });
    
    $('.use_services').click(function() {
        service_price_calculation();
    });
   

    
    $(document).on('change','#service_modal .cart_tyre',function() 
    {      
        var value = $(this).val(); 
        if(value != 0){
           $(this).parents('.service_type').find('input.service_name').attr("checked", "checked");
         
        } 
        else{
            $(this).parents('.service_type').find('input.service_name').removeAttr("checked", "checked");
        }
        service_price_calculation();
    });

    $('.use_this_installer').click(function() {
        var installer_id = $(this).parents('.single-list').attr('data-id');
        var admin_url = $('.admin_url').text();
       	
       	$.ajax({
                    type: "POST", 
                    url: admin_url,
                    data: {
                        action: 'update_selected_installer',
                        installer_id : installer_id,      
                    },
                    success: function (data)
                    {                       
                        $('#service_modal .selected_installer_information .content').html(data);                                  
                    },
                });
       
        $('#service_modal .screen').css('display','none');
        $('#service_modal .select-car-type').css('display','block');
        $('#service_modal .vehicle_type').trigger('click');       

    });

     var admin_url = $('.admin_url').text();
     var cart_item_id = $('.cart_item_id').text();
     var product_id = $('.product_id').text();
     var session_id = $('.session-key').text();
    $(document).on('click','#service_modal .vehicle_type',function()
    {
        var vehicle_type = $("input[name='vehicle_type']:checked").val();
        var site_url = $('.site_url').text();
        if(vehicle_type)
        {
            $('.next-to-service-voucher').removeAttr('disabled');
        }
      
        
    });
     $('input[name="vehicle_type"]').change(function() 
    {
        var abc = $("input[name='vehicle_type']:checked").val();
        
        $('#vehicle-type').val(abc);
        $('.vehicle-id').text(abc);
        $('#service_rate').val($('#sprice'+abc).val());
        
        var loader = "<div class='modal-loader'><img src='https://tyrehub.com/loading.gif' width='20' height='20' /></div>";
        $.ajax({
                    type: "POST", 
                    url: admin_url,
                    data: {
                        action: 'demo',
                        abc : abc,
                        cart_item_id : cart_item_id,
                        product_id : product_id      
                    },
          beforeSend:function() {                    
            $('#service_modal .data-body').html('');
            $('#service_modal .data-body').html(loader);
           },
                    success: function (data)
                    {               
                        $('#service_modal .data-body').html(data);
                        service_price_calculation();
                    },
                }); 
    });

    $('.service-in-ahm input[name="vehicle_type"]').change(function() 
    {
        var vehicle_id= $('.vehicle-type').val();
        var  radioValue = $(this).val();
        $('.vehicle-type').val(radioValue);
       
       if(vehicle_id==''){
        $('.searchbtn').trigger('click');
       }
        
    });

    


    $('#service_modal .next-to-service-voucher').click(function() 
    {       
        $('#service_modal .screen').css('display','none'); 
        $('#service_modal .select-service-voucher').css('display','block');
    });
    $(document).on('click','.next-to-review',function() 
    {
        $('#service_modal .screen').css('display','none');
        $('#service_modal .review-installer').css('display','block');         

    });

    
    $(document).on('click','.prev-to-car-type',function() 
    {
        var modal_body = $(this).parents('.modal-content');
        $(modal_body).find('.screen').css('display','none');
        $(modal_body).find('.select-car-type').css('display','block');  
    });
    
    $(document).on('click','.prev-to-service-voucher',function() 
    {
        
        var modal_body = $(this).parents('.modal-content');
        $(modal_body).find('.screen').css('display','none');
        $(modal_body).find('.select-service-voucher').css('display','block');
    });

    // cart page click deliver to installer
    $(document).on('click','button.intsaller-delivery',function(){
        $('#service_modal .screen').css('display','none');
        $('#service_modal .review-installer').css('display','block');
    });


    $(document).on('click','.use_this_installer',function()
    {
        $('.single-list').removeClass('selected');
        $(this).parents('.single-list').addClass('selected');
    });

    $(document).on('click','#tab-1 .with-product-voucher1',function()
    {
        
        //$('#cover-spin').show(0);
        var product_id = $('.product_id').text();
        //var vehicle_id = $("input[name='vehicle_type']:checked").val();
        var vehicle_id=$('#vehicle-type').val();
        
        var tyre = $('.cart_tyre').val();
        
        // save installer info
        var installer_id;
        
        if(vehicle_id==''){
         BootstrapDialog.show({
           type: BootstrapDialog.TYPE_WARNING,
            title: 'Notice!',
            message: 'Please select vehicle type!',
            buttons: [{
                label: 'Ok',
                action: function(dialogItself){
                    dialogItself.close();
                }
            }]
        });
          $('html, body').animate({scrollTop : 0},700);
          return false;
        }
        
        installer_id = $(this).attr('data-id');
           //alert(installer_id);
                $.ajax({
                    type: "POST", 
                    url: admin_url,
                    data: {
                        action: 'save_installer_info',
                        installer_id : installer_id, 
                        cart_item_id : cart_item_id,
                        product_id : product_id,
                        vehicle_id : vehicle_id,
                        tyre : tyre,
                        session_id : session_id,
                    },
                    success: function (redata)
                    {
                      redata = redata.replace(/(\r\n|\n|\r)/gm, "");
                      redata = $.parseJSON(redata);
                      var barcode_text = redata[1];

                      $(".barcode-image").qrcode({
                              width: 150,
                              height: 150,
                              text: barcode_text,
                              colorDark : "#000000",
                              colorLight : "#ffffff",
                          });

                      var canvas = $('.barcode-image canvas');                      
                      var img = canvas.get(0).toDataURL("image/png");
                      var barcode_img = img;
                     
                      $.ajax({
                          type: "POST", 
                          url: admin_url,
                          data: {
                               action: 'save_barcode_img',
                                installer_id : redata[0],
                                barcode_img : barcode_img,
                             },
                          success: function (data)
                          {
                              // save service info
             var service_list = [];
             var tyre_list = [];
             var service_rate_list = [];
             var service_id_arr = [];
            
                    service_name ='Tyre Fitment';
                    tyre_count = 1;
                    service_rate=0;
                    service_id=1;
                    service_list.push(service_name);
                    //tyre_list.push(tyre_count);
                    service_rate_list.push(service_rate);
                    service_id_arr.push(service_id);


        var site_url = $('.site-url').text();
        $.ajax({
                    type: "POST", 
                    url: admin_url,
                    data: {
                        action: 'save_service_info',
                        service_list : service_list, 
                        cart_item_id : cart_item_id,
                        product_id : product_id,
                        tyre_list : tyre_count,
                        vehicle_id : vehicle_id,
                        service_rate_list : service_rate_list, 
                        session_id : session_id,
                        service_id : service_id_arr,

                    },
                    success: function (data)
                    {   //$('#cover-spin').hide(0);
                        var url = site_url+'/cart';
                        window.location.replace(url);
                    },
                });
                          },
                        });                        
                    },
                });
    });
    

    $(document).on('click','.confirm_installer',function()
    {   
       //$('#cover-spin').show(0);
        var product_id = $('.product_id').text();
        //var vehicle_id = $("input[name='vehicle_type']:checked").val();
        var vehicle_id=$('#vehicle-type').val();
        
        var tyre = $('.cart_tyre').val();
        if(tyre<=0){
          tyre=1;
        }
        // save installer info
        var installer_id;
        
         

        installer_id = $(this).attr('data-id');
           //alert(installer_id);
                $.ajax({
                    type: "POST", 
                    url: admin_url,
                    data: {
                        action: 'save_installer_info',
                        installer_id : installer_id, 
                        cart_item_id : cart_item_id,
                        product_id : product_id,
                        vehicle_id : vehicle_id,
                        tyre : tyre,
                        session_id : session_id,
                    },
                    success: function (redata)
                    {
                      redata = redata.replace(/(\r\n|\n|\r)/gm, "");
                      redata = $.parseJSON(redata);
                      var barcode_text = redata[1];
                      
                      $(".barcode-image").qrcode({
                              width: 150,
                              height: 150,
                              text: barcode_text,
                              colorDark : "#000000",
                              colorLight : "#ffffff",
                          });

                      var canvas = $('.barcode-image canvas');                      
                      var img = canvas.get(0).toDataURL("image/png");
                      var barcode_img = img;
                     
                      $.ajax({
                          type: "POST", 
                          url: admin_url,
                          data: {
                               action: 'save_barcode_img',
                                installer_id : redata[0],
                                barcode_img : barcode_img,
                             },
                          success: function (data)
                          {
                              // save service info
             var service_list = [];
             var tyre_list = [];
             var service_rate_list = [];
             var service_id_arr = [];
            
                    service_name ='Tyre Fitment';
                    tyre_count = tyre;
                    service_rate=0;
                    service_id=1;
                    service_list.push(service_name);
                    //tyre_list.push(tyre_count);
                    service_rate_list.push(service_rate);
                    service_id_arr.push(service_id);


        var site_url = $('.site-url').text();
        $.ajax({
                    type: "POST", 
                    url: admin_url,
                    data: {
                        action: 'save_service_info',
                        service_list : service_list, 
                        cart_item_id : cart_item_id,
                        product_id : product_id,
                        tyre_list : tyre_count,
                        vehicle_id : vehicle_id,
                        service_rate_list : service_rate_list, 
                        session_id : session_id,
                        service_id : service_id_arr,

                    },
                    success: function (data)
                    {   //$('#cover-spin').hide(0);
                        var url = site_url+'/cart';
                        window.location.replace(url);
                    },
                });
                          },
                        });                        
                    },
                });

        

    });

    $(document).on('click','.service-voucher-prd',function(e)
    { 
        
       
        e.preventDefault();
        var rate = '';
        var service_name = '';
        var qty = '';
        var vehicle_id = $('.vehicle-id').text();
        var site_url = $('.site-url').text();
        
        if(vehicle_id==''){
         BootstrapDialog.show({
           type: BootstrapDialog.TYPE_WARNING,
            title: 'Notice!',
            message: 'Please select vehicle type!',
            buttons: [{
                label: 'Ok',
                action: function(dialogItself){
                    dialogItself.close();
                }
            }]
        });
          $('html, body').animate({scrollTop : 0},700);
          return false;
        }
        console.log(vehicle_id);

        var installer_id;
        $('.single-list').each(function(){
            if ($(this).hasClass('selected')) 
            {
              
            }
        });

        installer_id = $(this).parents('.single-list').attr('data-id');        
        rate = $('input.service_rate').val();
        service_name = 'Wheel alignment & balancing';

        var service_id = $('#service_id').val();
        qty = 1;

       
        if(service_name == ''){
            e.preventDefault();
           
        }
        else{
            //$('#cover-spin').show(0);

            $.ajax({
                    type: "POST", 
                    url: admin_url,
                    data: {
                        action: site_url+'/add-to-cart=3550', //https://www.tyrehub.com/shop?add-to-cart=3550                                            
                    },
                    success: function (data)
                    {

                    }
            });
                $.ajax({
                    type: "POST", 
                    url: admin_url,
                    data: {
                        action: 'service_product_add_to_cart',                                             
                    },
                    success: function (data)
                    {
                        data = data.replace(/(\r\n|\n|\r)/gm, "");
                        $.ajax({
                            type: "POST", 
                            url: admin_url,
                            data: {
                                action: 'service_voucher_product_price',
                                rate : rate,
                                service_id : service_id,
                                vehicle_id  : vehicle_id,
                                qty : qty,
                                session_id : data,
                                installer_id : installer_id,
                                
                            },
                            success: function (data1){

                              data1 = data1.replace(/(\r\n|\n|\r)/gm, "");
                              data1 = $.parseJSON(data1);
                              var barcode_text = data1[1];
                              console.log(barcode_text);
                              $(".barcode-image").qrcode({
                                      width: 150,
                                      height: 150,
                                      text: barcode_text,
                                       colorDark : "#000000",
                                      colorLight : "#ffffff",
                                  });

                              var canvas = $('.barcode-image canvas');                      
                              var img = canvas.get(0).toDataURL("image/png");
                              var barcode_img = img;
                              console.log(barcode_img);
                              console.log(data1[0]);

                              $.ajax({
                                  type: "POST", 
                                  url: admin_url,
                                  data: {
                                       action: 'save_voucher_barcode_img',
                                        installer_id : data1[0],
                                        barcode_img : barcode_img,
                                     },
                                  success: function (data)
                                  {
                                    //$('#cover-spin').hide(0);
                                    window.location.href = site_url+'/cart';
                                  },
                                });

                               
                            }                           
                        });
                    }                  

                });
            }
             
    });

$(document).on('click','#tab-1 .only-voucher1',function(e)
    { 
        
        console.log('hii');
         e.preventDefault();
        var rate = '';
        var service_name = '';
        var qty = '';
        var vehicle_id = $('.vehicle-id').text();
        var site_url = $('.site-url').text();
        
        if(vehicle_id==''){
         BootstrapDialog.show({
           type: BootstrapDialog.TYPE_WARNING,
            title: 'Notice!',
            message: 'Please select vehicle type!',
            buttons: [{
                label: 'Ok',
                action: function(dialogItself){
                    dialogItself.close();
                }
            }]
        });
          $('html, body').animate({scrollTop : 0},700);
          return false;
        }
        console.log(vehicle_id);

        var installer_id;
        $('.single-list').each(function(){
            if ($(this).hasClass('selected')) 
            {
              
            }
        });

        installer_id = $(this).parents('.single-list').attr('data-id');        
        rate = $('input.service_rate').val();
        service_name = 'Wheel alignment & balancing';
        qty = 1;

       
        if(service_name == ''){
            e.preventDefault();
           
        }
        else{
            //$('#cover-spin').show(0);

            $.ajax({
                    type: "POST", 
                    url: admin_url,
                    data: {
                        action: site_url+'/shop?add-to-cart=3550', //https://www.tyrehub.com/shop?add-to-cart=3550                                            
                    },
                    success: function (data)
                    {

                    }
                  });
                $.ajax({
                    type: "POST", 
                    url: admin_url,
                    data: {
                        action: 'service_product_add_to_cart',                                             
                    },
                    success: function (data)
                    {
                        data = data.replace(/(\r\n|\n|\r)/gm, "");
                        $.ajax({
                            type: "POST", 
                            url: admin_url,
                            data: {
                                action: 'service_voucher_product_price',
                                rate : rate,
                                service_name : service_name,
                                vehicle_id  : vehicle_id,
                                qty : qty,
                                session_id : data,
                                installer_id : installer_id,
                                
                            },
                            success: function (data1){

                              data1 = data1.replace(/(\r\n|\n|\r)/gm, "");
                              data1 = $.parseJSON(data1);
                              var barcode_text = data1[1];
                              console.log(barcode_text);
                              $(".barcode-image").qrcode({
                                      width: 150,
                                      height: 150,
                                      text: barcode_text,
                                       colorDark : "#000000",
                                      colorLight : "#ffffff",
                                  });

                              var canvas = $('.barcode-image canvas');                      
                              var img = canvas.get(0).toDataURL("image/png");
                              var barcode_img = img;
                              console.log(barcode_img);
                              console.log(data1[0]);

                              $.ajax({
                                  type: "POST", 
                                  url: admin_url,
                                  data: {
                                       action: 'save_voucher_barcode_img',
                                        installer_id : data1[0],
                                        barcode_img : barcode_img,
                                     },
                                  success: function (data)
                                  {
                                    //$('#cover-spin').hide(0);
                                    window.location.href = site_url+'/cart';
                                  },
                                });

                               
                            }                           
                        });
                    }                  

                });
            }
             
    });

    // shop page quantity 
    $(document).on('click', '.ui-spinner-up', function()
        {            
            var value = $(this).parents('.quantity').find('.qty').val();             
            var new_value = parseInt(value) + parseInt(1);              
            $(this).parents('.quantity').find('.qty').val(new_value);
        });

    $(document).on('click', '.ui-spinner-down', function()
    {            
        var value = $(this).parents('.quantity').find('.qty').val();
        if(value <= 1)
        {

        }
        else{
            var new_value = parseInt(value) - parseInt(1);              
            $(this).parents('.quantity').find('.qty').val(new_value);
        }             
        
    });

    // service partner page search functionality

    function installer_disatance()
    {
         var loader = "<div class='' style='text-align:center;'><img src='https://tyrehub.com/loading.gif' width='60' height='60' /></div>";
      
        myarray = [];
        var current_lat = $('.current-lat').text();
        var current_lon = $('.current-lon').text();
        var product_id = $('.product_id').text();
        var vehicle_type = $('.vehicle-type').val();

        $.ajax({
                    type: "POST", 
                    url: admin_url,
                    data: {
                        action: 'search_by_pincode',
                        current_lon : current_lon,
                        current_lat : current_lat, 
                        product_id : product_id,
                        vehicle_type : vehicle_type,
                    },

                    beforeSend: function() {                        
                        $('.installer-list').html(loader);
                    },
                    success: function (data)
                    {
                        $('.installer-list').html(data);
                    },
                });

        /*$(".installer-list .single-list").each(function()
        {            
            var lat2 = $(this).find('.lattitude').text();
            var lon2 = $(this).find('.longitude').text();
            getLocation();
            var self = $(this);
            var id = $(self).attr('data-id');

           

            function showPosition(position)
            {        
                var R = 6371; // km (change this constant to get miles)
                lat1 = position.coords.latitude;
                lon1 = position.coords.longitude;

                var dLat = (lat2-lat1) * Math.PI / 180;
                var dLon = (lon2-lon1) * Math.PI / 180;
                var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                    Math.cos(lat1 * Math.PI / 180 ) * Math.cos(lat2 * Math.PI / 180 ) *
                    Math.sin(dLon/2) * Math.sin(dLon/2);
                var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
                var d = R * c;
                myarray.push({
                            id: id, 
                            km: d
                        });
                    var result = d.toFixed(2)+' km';
                    $(self).find('.km').html(result);
                   // 
                
                    return d;
            }            
        });*/
    }

    var search_add = $('#myInput').val();

    if(search_add != '')
    {
        setTimeout(function(){
          $('.searchbtn').trigger('click');
         },1000);
         //$('.searchbtn').trigger('click');
        
    }
    else{
       /* setTimeout(function(){
          installer_disatance(); 
         },2000);*/
            
    }
    $('.services-list input[type="checkbox"]').click(function(){
        var cart_item_id= $('.cart_item_id').text();
        if(cart_item_id){
          var vehicle = $('.vehicle-type').val();
        }else{
          var vehicle = $('.vehicle-id').text();
        }
        
        
        if(vehicle==''){
         BootstrapDialog.show({
           type: BootstrapDialog.TYPE_WARNING,
            title: 'Notice!',
            message: 'Please select vehicle type!',
            buttons: [{
                label: 'Ok',
                action: function(dialogItself){
                    dialogItself.close();
                }
            }]
        });
          $('html, body').animate({scrollTop : 0},700);
          return false;
        }

    $('.searchbtn').trigger('click');
        //console.log(val);

    });
    $(document).on('click', '.searchbtn', function()
    {
      var val = [];
      /*$('.services-list :checkbox:checked').each(function(i){
        val[i] = $(this).val();
      });*/

      val.push($('#services_id').val());
        console.log(val);
        $('button.show-list').addClass('show-map');
        $('button.show-list').text('Show Map');
        $('button.show-list').removeClass('show-list');      
        //var postal_code = $('#postal_code').val();
        var postal_code = $('#myInput').val();
        var vehicle_type = $('.vehicle-type').val();

		    var product_id = $('.product_id').text();
        var prd_attr_vehicle = $('.prd_attr_vehicle').text();
         var location = $('#myInput').val();
        $('.pincode-error').text('');

        if(postal_code==''){
          $('.search_input').addClass('error');
          var erorrflag = 1; 
          $( "#errorlb" ).remove();
        $( ".search_input" ).after('<span style="color:red;" id="errorlb">Please enter pincode!</span>'); 
        }
        if(erorrflag==1){
            return false;
        }else{
        $('.search_input').removeClass('error'); 
         
        $('#errorlb').fadeOut();
        }

        var loader = "<div class='loding' style='text-align:center;'><img src='https://tyrehub.com/loading.gif' width='60' height='60' /></div>";
        if(postal_code != ''){
          var services=val;
            $.ajax({
                    type: "POST", 
                    url: admin_url,
                    dataType: "json",
                    data:{
                        action: 'search_by_pincode',
                        postal_code : postal_code,
                        vehicle_type : vehicle_type, 
						            product_id : product_id,
                        prd_attr_vehicle : prd_attr_vehicle,
                        services : services,
                    },

                    beforeSend: function() {                        
                        $('.installer-list').html(loader);
                    },
                    success: function (data)
                    {
                      var html='';
                      $.each(data, function(index, element) {
              if(element.product_id!=''){
                  $clsss1='with-product-voucher';
                }else{
                  $clsss1='only-voucher';
                }
                           
            html+='<div class="single-list '+$clsss1+'" data-id="'+element.id+'">';
            html+='<div class="left">';
            html+='<div class="col-md-2 col-sm-2 img-part">';
            html+='<img class="aligncenter size-full wp-image-74" src="https://www.tyrehub.com/wp-content/uploads/2018/09/icons8-car-service-filled-100.png" alt="" width="100" height="100">';
            html+='</div>';
                html+='<div class="col-md-4 col-sm-4 text-part">';
                    html+='<h4>'+element.business_name+'</h4>';
                    html+='<div>'+element.address+'</div>';
                     html+='<div>'+element.city+'-'+element.pincode+'</div>';
                    html+='<div></div>';
                html+='</div>';
            html+='</div>';

            html+='<div class="right">';
                html+='<div class="col-md-2 col-sm-2">';
                var url='https://www.google.com/maps/dir/?api=1&origin='+element.select_lat+','+element.select_lng+'&destination='+element.lattitude+','+element.longitude+'&travelmode=DRIVING';
                html+='<a href="'+url+'" target="_blank">';
                html+='<img class="aligncenter size-full wp-image-76" src="https://www.tyrehub.com/wp-content/uploads/2018/09/icons8-map-marker-100.png" alt="" width="100" height="100">';
                html+='</a>';
                html+='</div>';
                html+='<div class="col-md-3 col-sm-3 text-part">';
                html+='<span class="lattitude" hidden="">'+element.lattitude+'</span>';
                html+='<span class="longitude" hidden="">'+element.longitude+'</span>';
                html+='<div class="km"><i class="fa fa-map-marker"></i>&nbsp;'+element.km+' km</div>';
                   
                html+='<div><i class="fa fa-calendar"></i>&nbsp;'+element.available_time+'&nbsp;('+element.available_days+')</div>';
                            
                html+='</div>';
                if(element.product_id!=''){
                  $clsss='confirm_installer';
                }else{
                  $clsss='service-voucher-prd';
                }
                html+='<div class="col-md-2 col-sm-2 installer-btn">';
                html+='<div class="btn btn-invert button installer_btn '+$clsss+'" data-id="'+element.id+'"><span>Choose Installer</span></div>';
                html+='</div></div>';

                html+='<ul class="installer-type">'; 
                $.each(element.facilities, function(index, faci) {       
                  html+='<li><i class="fas fa '+faci.icon+'"></i>'+faci.name+'</li>';
                  
                  });    
                html+='</ul>';
                  if (element.additional_services && element.additional_services.length > 0) {
                html+='<ul class="installer-type additional_services">';
                      $.each(element.additional_services, function(index, servi) {       
                      html+='<li><i class="fas fa '+servi.icon+'"></i>'+servi.name+'</li>';
                      
                      });

                html+='</ul>';
                }        
              html+='</div>';


              
                      });
                      $('.installer-list').html('');
                      $('.installer-list').append(html);
                      //$('.installer-list').html(data);
                    },
                }); 
        }
        else{
           
            $.ajax({
                    type: "POST", 
                    url: admin_url,
                    data: {
                        action: 'search_by_location',
                        location : location,
                        vehicle_type : vehicle_type, 
                        product_id : product_id,
                        prd_attr_vehicle : prd_attr_vehicle,
                    },

                    beforeSend: function() {                        
                        $('.installer-list').html(loader);
                    },
                    success: function (data)
                    {
                        $('.installer-list').html(data);
                    },
                });
        }
              
    });    

  	$(document).on('click', '.installer_btn', function()
    {
      var vehicle_id = $('.vehicle-id').text();
      var vehicle_type = $('#vehicle-type').val();
      
      if(vehicle_id!='' || vehicle_type!=''){
        $(this).html('<span><i class="fa fa-spinner fa-spin"></i>Loading</span>');
      }
      
    });
	
	/*$(document).on('click', '.single-list', function() {
	  	$(this).find( ".installer_btn" ).trigger( "click" );
	});*/
		

    $(document).on('click', '.show-map', function()
    { 
       // alert('hii');
	    var map;
	    initMap();
       $(this).removeClass('show-map');
       $(this).addClass('show-list');
       $(this).text('Show List');
       $('.installer-list').css('display','none');
        $('.installer-map').css('display','block');
	});

    $(document).on('click', '.show-list', function()
    { 
        //alert('hii');
        var map;
        $(this).removeClass('show-list');
        $(this).addClass('show-map');
        $(this).text('Show Map');
        $('.installer-list').css('display','block');
        $('.installer-map').css('display','none');
    });
	
	
	$(document).on('click', '.check-gst-no', function()
    {		
		var admin_url = $('.admin_url').text();
		if($(this).prop("checked") == true)
		{
			
			 $.ajax({
                    type: "POST", 
                    url: admin_url,
                    data: {
                        action: 'gst_fields',
                    },
                    success: function (data)
                    {
                        $('.gst-field-container').html(data);
						            $('.gst-field-container').css('display','block');
                    },
                }); 
		}
		else{
			$('.gst-field-container').css('display','none');
			$('.gst-field-container').html('');
		}
	});
	
	
	$(".login-container .login").mouseenter(function() {
		$('.login-dropdown').css('display','block');
	}).mouseleave(function() {
		$('.login-dropdown').css('display','none');
	});

    $(document).on('keyup', 'input#reg_mobile_no', function(){
        //alert();
        var mobile_no = $(this).val();
           $("input#reg_username").val(mobile_no);
    });

    $(document).on('click','.custom_register', function(){
       // alert();
        var admin_url = $('.admin-url').text();
        var mobile_no = $('#custom_mobile').val();
        var first_name = $('#first_name').val();
        var last_name = $('#last_name').val();
        //var email = $('#custom_email').val();
        //var pass = $('#custom_pass').val();
        var mobile_whatsapp = $('.mobile_whatsapp:checked').val(); 
        console.log(mobile_whatsapp);   
        var validate = 1;

        if(mobile_no !='' && first_name !='' &&  last_name !='')
        {
            if(!$.isNumeric(mobile_no))
            {
                validate = 0;
                $('.error-msg').text('Mobile No not valid!');
            }
            //var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            
            /*if(email != '' && !regex.test(email) )
            {
                
                    validate = 0;
                $('.error-msg').text('Email Not Valid!');
            }*/
            if(validate == 1)
            {
               
                $.ajax({
                    type: "POST", 
                    url: admin_url,
                    data: {
                        action: 'custom_registartion',
                        mobile_no : mobile_no,    
                        first_name : first_name,
                        last_name : last_name,  
                        mobile_whatsapp : mobile_whatsapp,              
                    },
                    success: function (data)
                    {
                        $('.error-msg').text('');
                        var html = '';

                        html += '<p>We have sent OTP to your mobile no '+mobile_no+' Please Check!</p>';
                        html += '<p>If you not get then click on Resend OTP.</p>';
                        html += '<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">';

                        html +='<label for="custom_otp">Verify OTP&nbsp;<span class="required">*</span></label>';
                        html +='<input name="custom_otp" id="custom_otp" autocomplete="custom_mobile" value="" type="text">';
                        html +='</p>';
                        html +='<span class="user-id" hidden>'+data+'</span>';
                        html +='<button type="button" class="woocommerce-Button button verify-otp" name="verify-otp" value="Log in">Verify OTP</button>';
                        html +='<button type="button" class="woocommerce-Button button resend-otp" name="verify-otp" value="Log in">Resend OTP</button>';
                         $('.custom-registartion-form').html(html);
                    },
                });
            }           
        }
        else{
            $('.error-msg').text('Please fill required filled!');
        }

        
    });

    $(document).on('click','.campaign_register', function(){
       // alert();
        var admin_url = $('.admin-url').text();
        var mobile_no = $('#custom_mobile').val();
        var first_name = $('#first_name').val();
        var last_name = $('#last_name').val();
        var email = $('#custom_email').val();

        var mobile_whatsapp = $('.mobile_whatsapp:checked').val(); 
        console.log(mobile_whatsapp);   
        var validate = 1;

        if(mobile_no !='' && first_name !='' &&  last_name !='')
        {
            if(!$.isNumeric(mobile_no))
            {
                validate = 0;
                $('.error-msg').text('Mobile No not valid!');
            }
            //var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            
            /*if(email != '' && !regex.test(email) )
            {
                
                    validate = 0;
                $('.error-msg').text('Email Not Valid!');
            }*/
            if(validate == 1)
            {
               
                $.ajax({
                    type: "POST", 
                    url: admin_url,
                    data: {
                        action: 'custom_registartion',
                        mobile_no : mobile_no,    
                        first_name : first_name,
                        last_name : last_name,  
                        pass : pass, 
                        mobile_whatsapp : mobile_whatsapp,              
                    },
                    success: function (data)
                    {
                        $('.error-msg').text('');
                        var html = '';

                        html += '<p>We have sent OTP to your mobile no '+mobile_no+' Please Check!</p>';
                        html += '<p>If you not get then click on Resend OTP.</p>';
                        html += '<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">';

                        html +='<label for="custom_otp">Verify OTP&nbsp;<span class="required">*</span></label>';
                        html +='<input name="custom_otp" id="custom_otp" autocomplete="custom_mobile" value="" type="text">';
                        html +='</p>';
                        html +='<span class="user-id" hidden>'+data+'</span>';
                        html +='<button type="button" class="woocommerce-Button button verify-otp" name="verify-otp" value="Log in">Verify OTP</button>';
                        html +='<button type="button" class="woocommerce-Button button resend-otp" name="verify-otp" value="Log in">Resend OTP</button>';
                         $('.custom-registartion-form').html(html);
                    },
                });
            }           
        }
        else{
            $('.error-msg').text('Please fill required filled!');
        }

        
    });
$().ready(function() {
    
    // validate signup form on keyup and submit
    var validator = $("#register").validate({
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
                        return $('#custom_mobile').val();
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
                  $('#cover-spin').show(0);
                  var first_name=$('#first_name').val();
                  var last_name=$('#last_name').val();
                  var mobile_no=$('#custom_mobile').val();
                  var email=$('#custom_email').val();
                  //var vehicle_type=$('#vehicle_type').val();
                  var vehicle_type = [];
                  $(':checkbox:checked').each(function(i){
                    vehicle_type[i] = $(this).val();
                  });

                 $.ajax({
                    type: "POST", 
                    url: admin_url,
                    data: {
                        'action':'campaign_register',
                        'first_name' : first_name,
                        'last_name' : last_name,
                        'mobile_no' : mobile_no,    
                        'email' : email, 
                        'vehicle_type' : vehicle_type,              
                    },
                    success: function (data)
                    {
                      $('.error-msg').text('');
                        var html = '';
                        html += '<div class="msg-success"><div class="img-sec"><img src="http://3.6.39.134/tyrehub/wp-content/themes/demo/images/message-true.gif" />';
                        html += '</div>';
                        html += '<div class="alert alert-success">';
                        html += 'Thank you for the registration, You will be receiving a Gift Voucher of Rs.100 on your registered phone number.';
                        html += '</div></div>';

                        html += '<div><a href="https://www.tyrehub.com/my-account/"><button type="button" class="woocommerce-Button btn btn-invert btn-full" name="login" value="Log in"><span>Continue Shopping</span></button></a></div>';

                      $('.custom-registartion-form').html(html);
                      
                    $('#cover-spin').hide(0); 
                    },
                });

      }
    });
   
  });


/**
First Step of login
*******************/

$('button[name="login"]').click(function(){ 

  var login = $("#loginfrm").validate({
      rules: {
        username: {
          required: true,
          minlength: 10,
          maxlength: 10,
          number: true,
          
        },
      messages: {
        username: {
          required: "Enter a mobile number",
          minlength: "Enter at least 10 digit",
          maxlength: "Enter at least 10 digit"
        },
       
      }},
      // specifying a submitHandler prevents the default submit, good for the demo
      submitHandler: function(e) {
                  $('#cover-spin').show(0);
                  var username=$('#username').val();
                
                 $.ajax({
                    type: "POST", 
                    url: admin_url,
                    data: {
                        'action':'login_check',
                        'username' : username
                    },
                    success: function (data)
                    {
                       var data = $.parseJSON(data);
                      $('.error-msg').text('');
                      if(data.result=='error'){
                         $('.woocommerce-form-login .alert-danger').text(data.message);
                         $('.woocommerce-form-login .alert-danger').show();
                      }

                      if(data.result!='error'){
                        $('#mobile').val(username);
                         $('.woocommerce-form-login .alert-danger').hide();
                         $('#first-verify').hide();
                         $('#last-verify').show();

                          if(data.login_with=='otp'){
                            $('#first-verify').hide();
                            $('#pass-verify').hide();
                            $('#otp-verify').show();
                           //$(this).unbind('submit');
                            //$('.woocommerce-form').removeClass('woocommerce-form-login');
                            //$('.woocommerce-form').removeClass('woocommerce-form-pass');
                            //$('.woocommerce-form').addClass('woocommerce-form-otp');
                          }
                          if(data.login_with=='pass'){
                            $('#first-verify').hide();
                            $('#pass-verify').show();
                            $('#otp-verify').hide();
                            //$(this).unbind('submit'); 
                            //$('.woocommerce-form').removeClass('woocommerce-form-login');
                            //$('.woocommerce-form').removeClass('woocommerce-form-otp');
                            //$('.woocommerce-form').addClass('woocommerce-form-pass');
                          }
                        //$('.woocommerce-form-login').data('validator', null);
                        //$(".woocommerce-form-login").unbind('validate');
                      }
                     
                      
                      
                    $('#cover-spin').hide(0); 
                    },
                });

      }

    });

});

$("#customer_login" ).on( "click", "#passlogin", function() {
     $("#passfrm").validate({
      rules: {
        password: {
          required: true
        },
      messages: {
        password: {
          required: "Enter a password",
        },
       
      }},
      submitHandler: function(e) {

                  $('#cover-spin').show(0);
                  var password=$('#password').val();
                  var mobile=$('#mobile').val();
                
                 $.ajax({
                    type: "POST", 
                    url: admin_url,
                    data: {
                        'action':'login_with_pass',
                        'mobile' : mobile,
                        'pass' : password
                    },
                    success: function (data)
                    {
                       var data = $.parseJSON(data);
                      $('.error-msg').text('');
                      if(data.result=='error'){
                         $('#passfrm .alert-danger').text(data.message);
                         $('#passfrm .alert-danger').show();
                      }
                       $('#cover-spin').hide(0);
                      if(data.result!='error'){
                         window.location.reload(true);
                      }
                    
                    
                    },
                });

      }
    });

   
  });

$("#customer_login" ).on( "click", "#verify", function() {
     $("#otpfrm").validate({
      rules: {
        otp: {
          required: true,
          minlength: 6,
          maxlength: 6,
          number: true,
          
        },
      messages: {
        otp: {
          required: "Enter a OTP number",
        },
       
      }},
      submitHandler: function(e) {

                  $('#cover-spin').show(0);
                  var otp=$('#otp').val();
                  var mobile=$('#mobile').val();
                
                 $.ajax({
                    type: "POST", 
                    url: admin_url,
                    data: {
                        'action':'login_with_otp',
                        'mobile' : mobile,
                        'otp' : otp
                    },
                    success: function (data)
                    {
                       var data = $.parseJSON(data);
                      if(data.result=='error'){
                         $('#otpfrm .alert-danger').text(data.message);
                         $('#otpfrm .alert-danger').show();
                      }
                       $('#cover-spin').hide(0);
                      if(data.result!='error'){
                         window.location.reload(true);
                      }
                     
                       
                      
                    
                    },
                });

      }
    });

   
  });
    $(document).on('click','.custom-registartion-form .verify-otp', function()
    {
        var admin_url = $('.admin-url').text();
        var myaccount = $('.myaccount-page').text();
        var otp = $('#custom_otp').val();
        var user_id =$('.user-id').text();
        var obj = jQuery.parseJSON($('.user-id').text());

        $.ajax({
                    type: "POST", 
                    url: admin_url,
                    data: {
                        action: 'verify_otp',
                        otp : otp, 
                        user_id : obj.user_id,                  
                    },
                    success: function (data)
                    {
                        data = data.replace(/(\r\n|\n|\r)/gm, "");
                        $('.error-msg').text('');
                        if(data == 1)
                        {
                            $('.custom-registartion-form').html('Yor are registered successfully Please Login!. Thank You </br> <br> <a href="https://www.tyrehub.com/my-account" class="btn btn-invert"><span>Login</span></a>');
                             $('.error-msg').text('');
                        }
                        else{
                            $('.error-msg').text('Invalid OTP!');
                        }
                      //  $('.custom-registartion-form').html(html);
                    },
                });
    });

    $(document).on('click','.resend-otp', function()
    {
        var admin_url = $('.admin-url').text();
        var myaccount = $('.myaccount-page').text();
        var otp = $('#custom_otp').val();
        var user_id =$('.user-id').text();
        $.ajax({
                    type: "POST", 
                    url: admin_url,
                    data: {
                        action: 'resend_otp',
                        otp : otp, 
                        user_id : user_id,                  
                    },
                    success: function (data)
                    {
                        $('.error-msg').text('');
                    },
                });
    });
   // resend-otp

  
    
    $(document).on('click','.pincode-load-installer', function()
    {
        var postal_code = $('#postal_code').val();
        var vehicle_type = $('.vehicle-type').val();
        var product_id = $('.product_id').text();
        $.ajax({
                    type: "POST", 
                    url: admin_url,
                    data: {
                        action: 'search_by_pincode',
                        postal_code : postal_code,
                        vehicle_type : vehicle_type, 
                        product_id : product_id,
                         load : 'insatller',  

                    },
                    success: function (data)
                    {
                        $('.installer-list').html(data);
                       
                    },
                });
    });


    $(document).on('click','.lost-pass .send-otp', function(e)
    {
        e.preventDefault();
        var admin_url = $('.admin-url').text();
        var mobile_no = $('#mobile_no').val();
        console.log(mobile_no);
        $.ajax({
                    type: "POST", 
                    url: admin_url,
                    data: {
                        action: 'lost_pass_send_otp',
                        mobile_no : mobile_no,
                    },
                    success: function (data)
                    {
                      data = data.replace(/(\r\n|\n|\r)/gm, "");
                      if(data == 'Done')
                      { 
                        $('.lost-pass').html('<input type="hidden" class="mobile-no" value="'+mobile_no+'"> <p><label for="lost_otp">Enter OTP</label><input class="woocommerce-Input woocommerce-Input--text input-text" type="text" name="lostpass_otp" id="lostpass_otp"></p><button class="btn btn-invert verify-otp"><span>Verify OTP</span></button><div class="error-msg"></div>');
                      
                      }
                      else{
                        $('.lost-pass .error-msg').html(data);
                      }                
                    },
                });        
    });

    $(document).on('click','.lost-pass .verify-otp', function(e)
    {
      var otp = $('#lostpass_otp').val();
      var admin_url = $('.admin-url').text();
       var mobile_no = $('.mobile-no').val();

       $.ajax({
                    type: "POST", 
                    url: admin_url,
                    data: {
                        action: 'lost_pass_verify_otp',
                        mobile_no : mobile_no,
                        otp : otp,
                    },
                    success: function (data)
                    {
                      data = data.replace(/(\r\n|\n|\r)/gm, "");
                      if(data == 1)
                      { 
                        $('#user_login').val(mobile_no);
                        $('.lost_reset_password').submit();
                      //  $('.lost-pass').html('<p><label for="lost_otp">Enter OTP</label><input class="woocommerce-Input woocommerce-Input--text input-text" type="text" name="lostpass_otp" id="lostpass_otp"></p><button class="btn btn-invert verify-otp"><span>Verify OTP</span></button>');
                       // $('.lost_reset_password').css('display',);
                      }
                      else{
                        $('.lost-pass .error-msg').html('Wrong OTP');
                      }                
                    },
                });
    });

  $(document).on('click','.confirm-delivery',function() 
  {       
        var thisnew = $(this);
        var cart_key = $('.cart_item_id').text();
        var pincode = $(this).parents('.modal-body').find('.delivery_pincode').val();
        var session_id = $(this).parents('.modal-body').find('.session-id').text();
        var product_id =  $(this).parents('.modal-body').find('.product-id').text();
         $(thisnew).parents('.modal-body').find('.modal-footer .info-delivery').html('').html("<img src='https://tyrehub.com/loading.gif' width='10' height='10' />");
         var site_url = $('.site-url').text();
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
                    data = data.replace(/(\r\n|\n|\r)/gm, "");
                    if(data == "0"){
                        $(thisnew).parents('.modal-body').find('.modal-footer .info-delivery').html('').html("<span style='color:red'>Sorry we don't deliver tyres to your location!</span></br><span style='font-size: 15px;'> <strong>We will come to your city very soon, if you still like to buy tyres from us today with an addition delivery cost, please call us on 1-800-233-5551 </strong></span><button class='close btn btn-invert' data-dismiss='modal'><span>OK</span></button>");
                    }else{
                         url = site_url+'/cart';
                         window.location.replace(url);

                    }
                },
                });
    });

  $(document).on('click','.delivery-eligible',function() 
  {       
        var thisnew = $(this);
        //var cart_key = $('.cart_item_id').text();
        //var pincode = $(this).parents('.modal-body').find('.delivery_pincode').val();
       // var session_id = $(this).parents('.modal-body').find('.session-id').text();
       // var product_id =  $(this).parents('.modal-body').find('.product-id').text();

       var pincode = $('.delivery_pincode').val();
       
       if(pincode==''){
         $(".delivery_pincode").css("border-color", "red"); 
         return false;
       }
       $('.descri-delivery').hide();
       

         $(thisnew).parents('.deliver-to-home').find('.info-delivery').html('').html("<img src='https://tyrehub.com/loading.gif' width='40' height='40' />");
         var site_url = $('.site-url').text();
        $.ajax({
                    type: "POST", 
                    url: admin_url,
                    data: {
                        action: 'delivery_eligible_check',
                        pincode : pincode,
                },
                    success: function (data)
                {
                    data = data.replace(/(\r\n|\n|\r)/gm, "");
                    if(data == "0"){
                        $('#add_to_free_delivery').hide();
                        $('#succ-msg').hide();
                        $(thisnew).parents('.deliver-to-home').find('.info-delivery').html('').html("<div class='info-delivery-inner'><p class='sorry-msg'>Sorry we don't deliver tyres to your location!</p><p>We will come to your city very soon, if you still like to buy tyres from us today with an addition delivery cost, please call us on</p><a href='tel:1-800-233-5551'><i class='fa fa-phone' aria-hidden='true'></i> 1-800-233-5551</a></div>");
                    }else{
                      $(thisnew).parents('.deliver-to-home').find('.info-delivery').html('');  
                      $('#succ-msg').show();
                      $('#add_to_free_delivery').show();
                      $('.descri-delivery').show();
                    }
                },
                });
    });

    $(document).on('click','.location_section1 .confirm-deliver-home',function() 
    { 
        //$('#cover-spin').show(0);
        var cart_key = $('.cart_item_id').text();
        var pincode = $('.current-pincode-text').text();
        var product_id =  $('.product-id').text();
        var site_url = $('.site-url').text();
        $.ajax({
                    type: "POST", 
                    url: admin_url,
                    data: {
                        action: 'save_home_or_installer', 
                        cart_key : cart_key,
                        product_id : product_id,
                        pincode :    pincode,
                },
                    success: function (data)
                {
                    console.log(data);
                    data = data.replace(/(\r\n|\n|\r)/gm, "");
                    if(data == "0"){
                    }else{
                         url = site_url+'/cart';
                         window.location.replace(url);
                          //$('#cover-spin').hide(0);

                    }
                },
                });
    });
/* --------- For Tracking Order Otp ---------------*/
    $(document).on('click','.track-line .sendotp', function(e)
    { 
      e.preventDefault();
        var admin_url = $('.admin-url').text();
          var track_form = $(this).parents('.track-form');
          var mobile_no = $(track_form).find('.mobileno').val();
          $.ajax({
                    type: "POST", 
                    url: admin_url,
                    data: {
                        action: 'tracking_order_send_otp',
                        mobile_no : mobile_no,  
                    },
                    success: function (data)
                    {
                      $('.order-details').html('');

                      var html = '<input type="text" value="1" class="otp-count" hidden><div class="msg">We have sent OTP to your mobile no!</div><input type="text" name="otp" class="otp" placeholder="Enter OTP"><input type="text" value="'+mobile_no+'" class="mobileno" name="mobileno" hidden><input type="submit" class="verifyotp  btn-invert btn" value="Verify OTP"><input type="button" class="resendotp  btn-invert btn" value="Resend OTP">';
                        $(track_form).html(html); 
                        $('.track-line .msg').css('color','green');                     
                    },
                });
    });

     
    $(document).on('click','.track-line .resendotp', function(e)
    { 
        e.preventDefault();
        var admin_url = $('.admin-url').text();
          var track_form = $(this).parents('.track-form');
          var mobile_no = $(track_form).find('.mobileno').val();
          var otp_count = $(document).find('.otp-count').val();
          if(otp_count < 3)
          {
            $.ajax({
                    type: "POST", 
                    url: admin_url,
                    data: {
                        action: 'tracking_order_send_otp',
                        mobile_no : mobile_no,  
                    },
                    success: function (data)
                    {
                      var new_count = parseInt(otp_count) + parseInt(1);
                      $(document).find('.otp-count').val(new_count);
                      $('.order-details').html('');
                      $('.track-line .msg').css('color','green');  
                      $('.track-line .msg').text('Resend OTP to your mobile no Please Wait!');                  
                    },
                });
          }
          else
          {
            $(this).remove();
          }
          
    });

    $(document).on('click','.track-line .verifyotp', function(e)
    { 
        e.preventDefault();
          var admin_url = $('.admin-url').text();
          var track_form = $(this).parent();

          var mobile_no = $(track_form).find('.mobileno').val();
      
          var otp = $(track_form).find('.otp').val();

          $.ajax({
                    type: "POST", 
                    url: admin_url,
                    data: {
                        action: 'tracking_order_verify_otp',
                        mobile_no : mobile_no, 
                        otp : otp, 
                    },
                    success: function (data)
                    {
                      data = data.replace(/(\r\n|\n|\r)/gm, "");
                      if(data == 'true')
                      { 
                        $(track_form).submit();
                      }
                      else{
                        $('.track-line .msg').html('Your OTP is wrong.');
                        $('.track-line .msg').css('color','red');
                      }                
                    },
                });
    });
/* --------- For Tracking Order Otp ---------------*/

/* --------- Code From demo.js file ---------------*/
  $('.carousel-showmanymoveone .item').each(function(){
      var itemToClone = $(this);

      for (var i=1;i<6;i++) {
        itemToClone = itemToClone.next();

        // wrap around if at end of item collection
        if (!itemToClone.length) {
          itemToClone = $(this).siblings(':first');
        }

        // grab item, clone, add marker class, add to collection
        itemToClone.children(':first-child').clone()
          .addClass("cloneditem-"+(i))
          .appendTo($(this));
      }
    });

  $('.select-lang').on('change',function(){
        var lang = $(this).val();
        var select_lang = '.select-lang-content .'+lang;
        $('.select-lang-content .language-content').css('display','none');
        $(select_lang).css('display','block');

      });


      $('.installer-tab').click(function(){
          var tab = $(this).attr('data-tab');
          var selected_class = '.'+tab;
          $('.installer-tab').removeClass('active');
          $(this).addClass('active');
          $('.installer-tab-content').removeClass('active');
          $(selected_class).addClass('active');

      });
    $('ul.tabs li').click(function(){
      var tab_id = $(this).attr('data-tab');

      $('ul.tabs li').removeClass('current');
      $('.tab-content').removeClass('current');

      $(this).addClass('current');
      $("#"+tab_id).addClass('current');
      });
  /* --------- Code From demo.js file ---------------*/
  	
});

    

function initMap() {
	
	var locations = [];
	    $('.single-list-for-map').each(function()
	    {
            
	    	var single_location = [];
	    	var add = $(this).find('.address').html();    	
	    	var lat= $(this).find('.lattitude').text();
	    	var lon = $(this).find('.longitude').text();
	    	single_location.push(add);
	    	single_location.push(lat);
	    	single_location.push(lon);
	    	locations.push(single_location);
	    });
	    
	var map = new google.maps.Map(document.getElementById('map'), {
		zoom: 10,
		center: new google.maps.LatLng(23.1789054, 72.6347909),
		mapTypeId: google.maps.MapTypeId.ROADMAP
	});

	var infowindow = new google.maps.InfoWindow({});

	var marker, i;

	for (i = 0; i < locations.length; i++) {
		marker = new google.maps.Marker({
			position: new google.maps.LatLng(locations[i][1], locations[i][2]),
			map: map
		});

		google.maps.event.addListener(marker, 'click', (function (marker, i) {
			return function () {
				infowindow.setContent(locations[i][0]);
				infowindow.open(map, marker);
			}
		})(marker, i));
	}

   // initAutocomplete();
}
	
 function service_price_calculation()
 {
    var rate = 0;
    jQuery('.review-installer .service-name-list').html('<table><tr><th>Service Name</th><th>Charge per tyre</th><th>Amount</th></tr></table>');
        var admin_url = jQuery('.admin_url').text();
        var service_name_arr = [];
        
       service_name = '';
    jQuery(".modal-body .service_list").each(function()
    {
        var self = jQuery(this);
        if(jQuery(this).find('.service_name').is(':checked'))
        {
            current_rate = jQuery(this).find('.service_rate').val();                
            service_name = jQuery(this).find('.sname').text();
            var tyre = jQuery(this).find('.cart_tyre').val();

            rate_per_tyre = current_rate * tyre;
              
          
            if(service_name == 'Tyre Fitment')
            {
                var service_name_list = '<tr><td>'+service_name+'</td><td> ₹'+current_rate+' x '+tyre+' Tyre </td> <td>₹'+rate_per_tyre+'</td></tr>';
            }
            else
            {
              var service_name_list = '<tr><td>'+service_name+'</td><td> ₹'+current_rate+'</td> <td>₹'+rate_per_tyre+'</td></tr>';
         
            }
           
            jQuery('.review-installer .service-name-list table').append(service_name_list);
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

            jQuery('.service_voucher_total').css('display','block');
            jQuery('.next-to-review').removeAttr('disabled');
        }
        else{
        	jQuery('.next-to-review').attr('disabled','disabled');
            jQuery('.service_voucher_total').css('display','none');
        }
    jQuery('.service_voucher_total .amount').text(rate);
    jQuery('.review-installer .total-pay .amount').text(rate);
 }

// Tooltips




jQuery(document).on('click','#fortooltip', function(e)
  {
 e.preventDefault();
 jQuery('#car_type_info').modal('show');
});

jQuery(document).on('click','#fortooltip', function(e)
  {
      e.preventDefault();
 jQuery('#two_type_info').modal('show');
});

jQuery().ready(function() {
    
    // validate signup form on keyup and submit
    var validator = jQuery("#free-tyre-form").validate({
      rules: {
        fullname: "required",
        mobile: {
          required: true,
          minlength: 10,
          number: true,
         
        },
        'vehicle-location': {
            required: true           
          }
      },
      messages: {
        fullname: "Enter your name",
        mobile: {
          required: "Enter a mobile number",
          minlength: "Enter at least 10 digit",
        },
        'vehicle-location': "Enter your vehicle location",
      },
      // specifying a submitHandler prevents the default submit, good for the demo
      submitHandler: function(e) {
        
            jQuery('#cover-spin').show(0);
                  var fullname=jQuery('#fullname').val();
                  var mobile=jQuery('#mobile').val();
                  var vehiclelocation=jQuery('#vehicle-location').val();
                  var preferred_date=jQuery('#preferred-date').val();
                  var preferred_time=jQuery('#preferred-time').val();
                  //var vehicle_type=$('#vehicle_type').val();
                  var admin_url=jQuery('.admin_url').text();

                 jQuery.ajax({
                    type: "POST", 
                    url: admin_url,
                    data: {
                        'action':'free_tyre_inspection',
                        'fullname' : fullname,
                        'mobile' : mobile,
                        'vehiclelocation' : vehiclelocation,    
                        'preferred_date' : preferred_date, 
                        'preferred_time' : preferred_time,              
                    },
                    success: function (data)
                    {
                      jQuery('.error-msg').text('');
                        swal({
                        title: "Thank You!",
                        text: "We have received your request for Free Tyre Inspection, Our team will contact you soon.",
                        icon: "success",
                        buttons: true,
                        dangerMode: false,
                      });

                      
                    jQuery('#cover-spin').hide(0); 
                    },
                });


      }
    });

    // validate signup form on keyup and submit
    var validator = jQuery("#towing-services-form").validate({
      rules: {
        fullname: "required",
        mobile: {
          required: true,
          minlength: 10,
          number: true,
         
        },
        'vehicle-location': {
            required: true           
          }
      },
      messages: {
        fullname: "Enter your name",
        mobile: {
          required: "Enter a mobile number",
          minlength: "Enter at least 10 digit",
        },
        'vehicle-location': "Enter your vehicle location",
      },
      // specifying a submitHandler prevents the default submit, good for the demo
      submitHandler: function(e) {
        
            jQuery('#cover-spin').show(0);
                  var fullname=jQuery('#fullname').val();
                  var mobile=jQuery('#mobile').val();
                  var vehiclelocation=jQuery('#vehicle-location').val();
                  var preferred_date=jQuery('#preferred-date').val();
                  var preferred_time=jQuery('#preferred-time').val();
                  //var vehicle_type=$('#vehicle_type').val();
                  var admin_url=jQuery('.admin_url').text();

                 jQuery.ajax({
                    type: "POST", 
                    url: admin_url,
                    data: {
                        'action':'towing_services',
                        'fullname' : fullname,
                        'mobile' : mobile,
                        'vehiclelocation' : vehiclelocation,    
                        'preferred_date' : preferred_date, 
                        'preferred_time' : preferred_time,              
                    },
                    success: function (data)
                    {
                      jQuery('.error-msg').text('');
                        swal({
                        title: "Thank You!",
                        text: "We have received your request for Towing Service, Our team will contact you soon.",
                        icon: "success",
                        buttons: true,
                        dangerMode: false,
                      });

                      
                    jQuery('#cover-spin').hide(0); 
                    },
                });


      }
    });

// validate signup form on keyup and submit
 var validator = jQuery("#flat-typre").validate({
      rules: {
        service_type: "required",
        fullname: "required",
        mobile: {
          required: true,
          minlength: 10,
          maxlength: 10,
          number: true,
         
        },
        'vehicle-location': {
            required: true           
          }
      },
      messages: {
        service_type: "Please select a service type",
        fullname: "Enter your name",
        mobile: {
          required: "Enter a mobile number",
          minlength: "Enter at least 10 digit",
          maxlength: "Enter at least 10 digit",
        },
        'vehicle-location': "Enter your vehicle location",
      },
      // specifying a submitHandler prevents the default submit, good for the demo
      submitHandler: function(e) {
        
            jQuery('#cover-spin').show(0);
              var type = jQuery("input[name='service_type']:checked").val();
              var fullname=jQuery('#fullname').val();
              var mobile=jQuery('#mobile').val();
              var vehiclelocation=jQuery('#vehicle-location').val();
              var preferred_date=jQuery('#preferred-date').val();
              var preferred_time=jQuery('#preferred-time').val();
              //var vehicle_type=$('#vehicle_type').val();
              var admin_url=jQuery('.admin_url').text();

                 jQuery.ajax({
                    type: "POST", 
                    url: admin_url,
                    data: {
                        'action':'flat_tyre',
                        'type' : type,
                        'fullname' : fullname,
                        'mobile' : mobile,
                        'vehiclelocation' : vehiclelocation,    
                        'preferred_date' : preferred_date, 
                        'preferred_time' : preferred_time,              
                    },
                    success: function (data)
                    {
                      jQuery('.error-msg').text('');
                        swal({
                        title: "Thank You!",
                        text: "We have received your request for "+type+", Our team will contact you soon.",
                        icon: "success",
                        buttons: true,
                        dangerMode: false,
                      });
                      
                    jQuery('#cover-spin').hide(0); 
                    },
                });


      }
    });
   
  });

/*jQuery('#preferred-date').datepicker({
    format: 'mm/dd/yyyy',
    startDate: '-3d'
});*/


   

   