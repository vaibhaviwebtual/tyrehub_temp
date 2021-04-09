  jQuery(document).ready(function($) {
  $("#product_list #doaction").click(function (event) {

    var actionselected = $(this).attr("id").substr(2);
    var action = $('select[name="' + actionselected + '"]').val();
    //alert(action);
    //if ( $.inArray(action, wpo_wcpdf_ajax.bulk_actions) !== -1 ) {
      event.preventDefault();
      var template = action;
      var checked = [];
      $('tbody th.check-column input[type="checkbox"]:checked').each(
        function() {
          checked.push($(this).val());
        }
      );
      if (action==-1) {
        alert('Please select invoice type');
        return;
      }

      if (!checked.length) {
        alert('Please select on or multiple orders to print invoice as single document! You can select and print maximum 30 Invoice at a time.');
        return;
      }
      var adminurl= $( ".admin-url" ).text();
      var nonce =$( ".get-nonce" ).text();
      var order_ids=checked.join('x');

      if (adminurl.indexOf("?") != -1) {
        url = adminurl+'&action=generate_wpo_wcpdf&document_type='+template+'&order_ids='+order_ids+'&_wpnonce='+nonce;
      } else {
        url = adminurl+'?action=generate_wpo_wcpdf&document_type='+template+'&order_ids='+order_ids+'&_wpnonce='+nonce;
      }

      window.open(url,'_blank');
//}

  });

$('#product_list #doaction').attr('value','Print PDF');

$( "#product_list .bulkactions" ).after('<div style="overflow: hidden; padding: 8px 8px 0 0; float:left;">Select Max 30 invoice to print</div>');
});
