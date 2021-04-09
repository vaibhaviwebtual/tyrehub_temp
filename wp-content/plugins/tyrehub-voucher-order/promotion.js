jQuery(document).ready(function($) 
{
  $(document).on('click','.create-bulk-promotional-voucher',function()
    {
        var price = $('.voucher-price').val(); 
        var count = 0;  
        var admin_url = $('.admin-url').text();
        var order_ids = [];
        if(price != '')
        {
            $(".promotion-voucher .installer-data .single-installer").each(function()
            {
                var e = $(this);
                var id = $(this).attr('data-id');
                
                if($(this).find('.installer-selection').prop('checked')==true)
                {                   
                    

                    $.ajax({    
                            type: "POST", 
                            url: admin_url,
                            data: {
                                action: 'create_bulk_prmotion_voucher_order',
                                installer: id,
                                price: price,
                            },
                            success: function (data)
                            {

                                 data = data.replace(/(\r\n|\n|\r)/gm, "");
                                  data = $.parseJSON(data);
                                  var barcode_text = data[1];
                                  var order_id = data[2];
                                  order_ids.push(order_id);

                                  $(e).find(".barcode-image").qrcode({
                                          width: 150,
                                          height: 150,
                                          text: barcode_text,
                                           colorDark : "#000000",
                                          colorLight : "#ffffff",
                                      });

                                  var canvas = $(e).find('.barcode-image canvas');                      
                                  var img = canvas.get(0).toDataURL("image/png");
                                  var barcode_img = img;

                                  $.ajax({
                                      type: "POST", 
                                      url: admin_url,
                                      data: {
                                           action: 'save_voucher_barcode_img',
                                            installer_id : data[0],
                                            barcode_img : barcode_img,
                                         },
                                      success: function (data)
                                      {
                                        count = count + parseInt(1);
                                        console.log(count);
                                        if(count == 1){
                                            save_bulk_voucher();
                                        }
                                        
                                      },
                                    });
                            }
                        });
                }
               
            });
        }
        else{
            alert('Please add price');
        }
        
         function save_bulk_voucher(){
            var size = order_ids.length;
            console.log(size);
            $.ajax({
              type: "POST", 
              url: admin_url,
              data: {
                   action: 'save_bulk_voucher_info',
                    count : size,
                    price : price,
                    order_ids: order_ids,
                 },
              success: function (data)
              {
                var text = order_ids.join('x');
                var old_href = $('#after_voucher_order a.download-pdf').attr('href');
                var new_href = old_href+'&order_ids='+text;
                $('#after_voucher_order a.download-pdf').attr('href',new_href);
                $('#after_voucher_order').css('display','block');
              },
            });
         }
        
        
        //$('.message-block').html('<div class="updated notice notice-success is-dismissible"><p>Order Created!</p></div>');
                                   
    });

    $(document).on('keyup','.serch-text',function()
    {
      
      var text = $(this).val();
      
        $(".promotion-voucher .installer-data .single-installer").each(function()
        {
            var installer_name = $(this).find('.second-column .first-row strong').text();
            installer_name = installer_name.toLowerCase();
            var mobile_no = $(this).find('.second-column .mobile-no').text();
              if(installer_name.indexOf(text) != -1 || mobile_no.indexOf(text) != -1)
              {
                  $(this).css('display','block');
              }
              else{
                  $(this).css('display','none');
              }
        });
    });

    
    $(document).on('click','.installer-selection',function()
    {
      var count = 0;
      $(".promotion-voucher .installer-data .single-installer").each(function()
      {
        if($(this).find('.installer-selection').prop('checked')==true)
        {
          count = parseInt(count) + parseInt(1);
        }
      });

      if(count != 0){
        $('.selected-installer').text('[ '+count+' Installer Selected ]');
        $('.selected-installer').css('display','inline-block');
      }
      else{
        $('.selected-installer').text('[ No Installer Selected ]');
      }
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