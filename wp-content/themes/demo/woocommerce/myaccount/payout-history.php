<?php
cart_clear_franchise();
if ( !is_user_logged_in() || !isset($_SESSION['admin_access'])) {
  wp_redirect(get_permalink( get_option('woocommerce_myaccount_page_id') ).'/installer-login' );    
}
get_header();
?>
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css">
<div id="pageContent">
    <div class="container installer service-request-page">
        <div class="woocommerce">
            <?php
            if ( ! defined( 'ABSPATH' ) ) {
                exit;
            }
            wc_print_notices();
            ?>
            <?php
              $user = wp_get_current_user();
            $role = ( array ) $user->roles;
            $current_user_role = $role[0];
           
            $SQL="SELECT * FROM th_installer_data WHERE user_id='".$user->ID."'";
            $franchise=$wpdb->get_row($SQL);
          ?>
            <style>
                .deals-top {
                    background-color: #f2f2f2;
                    padding: 15px;
                }
                .deals-top h3 {
                    font-size: 26px;
                    margin-bottom: 0px;
                }
                .add-discount {
                    background-color: #2f3672;
                    color: #fff;
                    border: 0px;
                    height: 35px;
                    padding: 0px 20px;
                    cursor: pointer;
                    display: inline-block;
                    vertical-align: top;
                    line-height: 35px;
                    
                    display: inline-block;
                }
                .add-discount:hover, .add-discount:focus {
                    background-color: #ffd642;
                    color: #fff;
                }
                .active {
                    background-color: #ffd642;
                }
                .active:hover, .active:focus {
                    background-color: #2f3672;
                    color: #fff;
                }
                #example_wrapper {
                    display: inline-block;
                    width: 100%;
                    background-color: #f2f2f2;
                    padding: 15px;
                }
                #example_wrapper input[type="search"] {
                    padding: 0px;
                }
                #example_wrapper a {
                    color: #000;
                    text-transform: capitalize;
                }
                #example_wrapper .pagination>.active>a,
                #example_wrapper .pagination>.active>a:focus,
                #example_wrapper .pagination>.active>a:hover,
                #example_wrapper .pagination>.active>span,
                #example_wrapper .pagination>.active>span:focus,
                #example_wrapper .pagination>.active>span:hover {
                    color: #fff;
                    background-color: #2f3672;
                    border-color: #2f3672;
                }
            </style>
                <div class="woocommerce-MyAccount-content" style="width: 100%;">
                    <div class="deals-top row">
                        
                        <div class="col-sm-6 col-md-6">
                            <a href="<?=site_url('/my-account/payout-process/');?>" class="add-discount ">Completed</a>
                            <a href="<?=site_url('/my-account/franchise-payout/');?>" class="add-discount">Payout</a>
                            <a href="<?=site_url('/my-account/payout-history/');?>" class="add-discount active">History</a>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <h3>Payout History</h3>
                        </div>
                    </div>
                    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">
                    <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
                    <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>
                    <div class="row">
                    <table id="example" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <!-- <th>Order Number</th> -->
                                <th>Pay Order No</th>                                
                                <th width="25%">Tyres</th>
                                <th>Services</th>
                                <th>Payout Date</th>
                                <th>Your Profit</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            global $wpdb;
        $SQL="SELECT * FROM `th_franchise_profit` WHERE franchise_id='".$franchise->installer_data_id."' AND status = 'completed' and payout_status='paid' ORDER BY profit_id DESC";
                            $results=$wpdb->get_results($SQL);
                            
                            foreach ($results as $key => $res) {
                                $SQL="SELECT pp.* FROM th_payout_history ph LEFT JOIN th_profit_payout as pp ON pp.payout_id=ph.payout_id WHERE ph.profit_id='".$res->profit_id."'";
                                $invoice=$wpdb->get_row($SQL);

                                $variation_des='';
                                 $product_variation = wc_get_product($res->product_id);
                                    $variation_des = $product_variation->get_description();
                                     if(empty($variation_des)){
                                       $variation_des ='*No Tyre Purchase*';
                                     }
                                   
                                    $serviArra=array();
                                    $service='';
                                    if($res->balancing_price>0){
                                        $serviArra[0]['name']='Balancing';
                                        $serviArra[0]['image']=get_template_directory_uri().'/images/service-icon/balancing.png';
                                    }
                                    if($res->alignment_price>0){
                                        $serviArra[1]['name']='Alignment';
                                        $serviArra[1]['image']=get_template_directory_uri().'/images/service-icon/alignment.png';
                                    }
                                    if($res->carwash_price>0){
                                        $serviArra[2]['name']='Carwash';
                                        $serviArra[2]['image']=get_template_directory_uri().'/images/service-icon/carwash.png';
                                    }
                                    if($res->balancing_alignment>0){
                                        $serviArra[3]['name']='Balancing & Alignment';
                                        $serviArra[3]['image']=get_template_directory_uri().'/images/service-icon/alignment_balance.png';
                                    }
                                    if($res->single_carwash>0){
                                        $serviArra[4]['name']='Carwash';
                                        $serviArra[4]['image']=get_template_directory_uri().'/images/service-icon/carwash.png';
                                    }

                                    if(!empty($serviArra)){
                                       
                                        //$service=implode(',',$serviArra);
                                    }

                                ?>
                            <tr>
                               <!--  <td> #<?=$res->order_id;?></td> -->
                                <td><?=$invoice->invoice_no;?></td>
                                <td><?=$variation_des;?></td>
                                <td>
                                    <?php 
                                    foreach ($serviArra as $value) {
                                        //echo $value['name'];
                                        echo '<span style="margin-right:12px;"><img class="service-img"  title="'.$value['name'].'" "'.$value['name'].'" src="'.$value['image'].'"></span>';
                                    }
                                   ?>
                                </td>
                                <td><?=date('d-m-Y',strtotime($res->payout_date));?></td>
                                <td><i class="fa fa-inr"></i><?=(int)$res->profit_with_gst;?></td>
                                <td style="text-align: center;"><a href="javascript:void(0);" id="<?=$res->profit_id;?>" class="single_view"><i class="fa fa-eye" style="font-size: 25px;"></i></a> </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                        
                    </table>
                </div>
                </div>
            </div>
        </div>
</div>

<?php
get_footer();
?>
<!-- Modal -->
<div id="profit_view" class="profit_view modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" style="text-align: center;">Profit Share View</h4>
       
      </div>
      <div class="modal-body">
        <table class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr style="background-color: #e3e3e3;">
                                <th>Tyre And Service List</th>
                                <th>*All amount are before GST</th>
                                
                            </tr>
                            <tr id="tyre_sale_tr">
                                <td>Tyrehub Tyre Selling Price ( Tyres QTY <span id="qty"></span>)</td>
                                <td id="tyre_sale" style="text-align: right;"></td>
                                
                            </tr>
                            <tr id="tyre_buy_tr">
                                <td>Tyrehub Buying Price ( Tyres QTY <span id="qty1"></span>)</td>
                                <td id="tyre_buy" style="text-align: right;"></td>
                                
                            </tr>
                            <tr id="tyre_profit_tr" style="background-color: #e3e3e3;">
                                <th>Your Frabchise Benefits </th>
                                <th id="tyre_profit" style="text-align: right"></th>
                                
                            </tr>
                            <tr id="balancing_with_tyre_tr">
                                <td>Balancing</td>
                                <td id="balancing_with_tyre" style="text-align: right;"></td>
                                
                            </tr>
                             <tr id="alignment_with_tyre_tr">
                                <td>Alignment</td>
                                <td id="alignment_with_tyre" style="text-align: right;"></td>
                                
                            </tr>
                             <tr id="car_wash_with_tyre_tr">
                                <td>Car Wash</td>
                                <td id="car_wash_with_tyre" style="text-align: right;"></td>
                                
                            </tr>
                             <tr id="balancing_alignment_tr">
                                <td>Balancing & Alignment</td>
                                <td id="balancing_alignment" style="text-align: right;"></td>
                                
                            </tr>
                             <tr id="separate_car_wash_tr">
                                <td>Car Wash</td>
                                <td  id="separate_car_wash" style="text-align: right;"></td>
                                
                            </tr>
                             <tr id="service_payment_tr" style="background-color: #e3e3e3;">
                                <th>Your Services Benefits</th>
                                <th id="service_payment" style="text-align: right;"></th>
                                
                            </tr>
                            <tr id="gross_total_tr" style="background-color: #e3e3e3;">
                                <th>Sub Total</th>
                                <th style="text-align: right;" id="gross_total"></th>
                                
                            </tr>
                            <tr>
                                <td>Online Payment Handling Charges</td>
                                <td id="handling_charge" style="text-align: right;"></td>
                                
                            </tr>
                            <tr style="background-color: #e3e3e3;">
                                <th>Your Profit</th>
                                <th id="your_profit" style="text-align: right;"></th>
                                
                            </tr>
                            <tr>
                                <td>GST</td>
                                <td id="profit_gst" style="text-align: right;"></td>
                                
                            </tr>
                            <tr style="background-color: #e3e3e3;">
                                <th>Total Amount</th>
                                <th id="total_profit" style="text-align: right;"></th>
                                
                            </tr>
                        </thead>
                        
                    </table>
      </div>
     
    </div>

  </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function() {
    jQuery('#example').DataTable({
    "ordering": false
});
} );

    jQuery(document).on('click','.single_view',function(e){
        e.preventDefault();

        
        var profit_id = jQuery(this).attr('id');
        var admin_url = jQuery('.admin_url').text();
        jQuery('#cover-spin').show(0);
        jQuery.ajax({    
                type: "POST", 
                url: admin_url,
                data: {
                    action: 'profit_view_by_row',
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

                    var obj = jQuery.parseJSON(data);
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

                    jQuery('#profit_view').modal('show');
                    jQuery('#cover-spin').hide(0);
                },
            });
    });

    
</script>