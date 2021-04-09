(function( $ ) {
    'use strict';

    $(function() {
        
        $('#upload_image').click(open_custom_media_window);
        function open_custom_media_window() {
            if (this.window === undefined) {
                this.window = wp.media({
                    title: 'Insert Image',
                    library: {type: 'image'},
                    multiple: false,
                    button: {text: 'Insert Image'}
                });

                var self = this;
                this.window.on('select', function() {
                    var response = self.window.state().get('selection').first().toJSON();
                    console.log(response);
                    $('.wp_attachment_id').val(response.id);
                    $('.image').attr('src', response.sizes.full.url);
                    $('.image-shiv').hide();
                    $('.image').show();
                    
                });
            }

            this.window.open();
            return false;
        }

    $( ".upload-button1" ).click(function() {
        open_custom_media_window_shiv(this,1);
     });
     $( ".upload-button2" ).click(function() {
        open_custom_media_window_shiv(this,2);
     });
     $( ".upload-button3" ).click(function() {
        open_custom_media_window_shiv(this,3);
     });
     $( ".upload-button4" ).click(function() {
        open_custom_media_window_shiv(this,4);
     });


        function open_custom_media_window_shiv(thisvalue,pera) {
           
            if (thisvalue.window === undefined) {
                thisvalue.window = wp.media({
                    title: 'Insert Image',
                    library: {type: 'image'},
                    multiple: false,
                    button: {text: 'Insert Image'}
                });

                var self = thisvalue;
                thisvalue.window.on('select', function() {
                    var response = self.window.state().get('selection').first().toJSON();
                    console.log(response);
                    $('.wp_attachment_id'+pera).val(response.id);
                    $('.image'+pera).attr('src', response.sizes.full.url);
                    $('.image'+pera).show();
                });
            }

            thisvalue.window.open();
            return false;
        }



        $( ".variant-name-binding-area select" ).change(function() {
          var product = $('#product option:selected').text();
                var width = $('#width option:selected').text();
                var ratio = $('#ratio option:selected').text();
                var diameter = $('#diameter option:selected').text();
                var tyre_type = $('#tyre_type option:selected').text();
                var vehicle_type = $('#vehicle_type option:selected').text();
                var brand = $('#brand option:selected').text();

                if(width!='Any width…'){
                    width = width;
                }else{
                    width ='';   
                }
                if(ratio!='Any ratio…'){
                    ratio=ratio;
                }else{
                    ratio='';
                }
                if(diameter!='Any diameter…'){
                    diameter=diameter;
                }else{
                    diameter='';
                }   
                if(tyre_type!='Any Tyre Type…'){
                    tyre_type=tyre_type
                }else{
                    tyre_type='';
                }
                if(vehicle_type!='Any vehicle type…'){
                    vehicle_type=vehicle_type;
                }else{
                    vehicle_type='';
                }
                if(brand!='Any brand…'){
                    brand=brand;
                }else{
                   brand=''; 
                }   

                //MRF 145 / 80 R12 74S ZVTS Tubeless Car Tyre
                  var variant_name = brand+' '+width+' / '+ratio+' R'+diameter+' '+product+' '+tyre_type+' '+vehicle_type;
                $('#variable_description').text(variant_name);
                console.log(variant_name);
        
        });

        function variant_name_binding(){
       
                var product = $('#product option:selected').text();
                var width = $('#width option:selected').text();
                var ratio = $('#ratio option:selected').text();
                var diameter = $('#diameter option:selected').text();
                var tyre_type = $('#tyre_type option:selected').text();
                var vehicle_type = $('#vehicle_type option:selected').text();
                var brand = $('#brand option:selected').text();

                //MRF 145 / 80 R12 74S ZVTS Tubeless Car Tyre
                  var variant_name = brand+' '+width+' / '+ratio+' R'+diameter+' '+product+' '+tyre_type+' '+vehicle_type;
                $('#variable_description').text(variant_name);
                console.log(variant_name);

        }


        $(".sale_pricing input").keyup(function(){ 

                var tyre_price = $('#tyre_price').val();
                var tube_price = $('#tube_price').val();
                var percentage = $('#percentage').val();
                var margin_price = $('#margin_price').val();
                if(tyre_price!=''){
                    tyre_price = tyre_price;
                }else{
                    tyre_price ='';   
                }
                if(tube_price!=''){
                    tube_price=tube_price;
                }else{
                    tube_price='';
                }
                if(percentage!=''){
                    percentage=percentage;
                }else{
                    percentage='';
                }   
                if(margin_price!=''){
                    margin_price=margin_price
                }else{
                    margin_price='';
                }
               if(tube_price){
                var parcent = ((parseFloat(tube_price) + parseFloat(tyre_price)) * parseFloat(percentage) ) / 100;
                }else{
                  var parcent = ((parseFloat(tyre_price)) * parseFloat(percentage) ) / 100;  
                }
                
                var sale_price1 = (parseFloat(parcent) + parseFloat(margin_price));
                if(tube_price){
                    var sale_price =  (parseFloat(tube_price) + parseFloat(tyre_price)) + sale_price1;
                }else{
                        var sale_price =  (parseFloat(tyre_price)) + sale_price1;
                }
                $('#sale_price').val(sale_price);
        
        });

   /* function sale_price_binding(){
       
                var product = $('#product option:selected').text();
                var width = $('#width option:selected').text();
                var ratio = $('#ratio option:selected').text();
                var diameter = $('#diameter option:selected').text();
                var tyre_type = $('#tyre_type option:selected').text();
                var vehicle_type = $('#vehicle_type option:selected').text();
                var brand = $('#brand option:selected').text();

                //MRF 145 / 80 R12 74S ZVTS Tubeless Car Tyre
                  var variant_name = brand+' '+width+' / '+ratio+' R'+diameter+' '+product+' '+tyre_type+' '+vehicle_type;
                $('#variable_description').text(variant_name);
                console.log(variant_name);

        }

*/
 });
})( jQuery ); 