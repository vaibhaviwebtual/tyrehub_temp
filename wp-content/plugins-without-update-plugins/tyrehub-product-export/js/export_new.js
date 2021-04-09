jQuery(document).ready(function($) 
{
    

    $(document).on('click','#prodct_csv_export',function(e){
        $('#action').val('export');

        $( "#product_list" ).submit();
            
        
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

  
    $(document).on('click','.product-csv-export #csv_prodct_search',function()
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
                    action: 'product_csv_data_search',
                    width: width,
                    ratio: ratio,
                    diameter: diameter,
                    cat: cat,
                },
                beforeSend: function() {    
                        $('.product-csv-export .product-details .body').html('');
                        $('.product-csv-export .product-details .body').append(loader);
                    },
                success: function (data) {
                        $('.product-csv-export .product-details .body').html(data);
                },
            });
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
                    action: 'product_csv_data_search',
                    width: width,
                    ratio: ratio,
                    diameter: diameter,
                    cat: cat,
                },
                beforeSend: function() {   
                    $('.change-price .product-details .body').html('');                     
                        $('.product-csv-export .product-details .body').append(loader);
                    },
                success: function (data) {
                   
                    $('.product-csv-export .product-details .body').html(data);
                },
            });
}