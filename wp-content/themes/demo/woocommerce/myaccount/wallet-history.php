<?php
cart_clear_franchise();
if ( !is_user_logged_in() || !isset($_SESSION['admin_access'])) {
  wp_redirect(get_permalink( get_option('woocommerce_myaccount_page_id') ).'/installer-login' );    
}

get_header();
    global $woocommerce , $wpdb;
    $user_id = get_current_user_id();
    $SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
    $franchise=$wpdb->get_row($SQL);
    $franchise_id=$franchise->installer_data_id;
    $SQL="SELECT * FROM th_franchise_payment WHERE 1=1 AND franchise_id = '$franchise_id'  ORDER BY id DESC LIMIT 0,1";
    $balance = $wpdb->get_row($SQL);
    $paramList = array();
    $paramList = $_POST;
    $postData = $_POST;
    if($paramList){
      $balance->close_balance = franchise_wallet_recharge($paramList,$franchise_id,$user_id,$balance->close_balance);  
    }
    


?>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/assest/sweetalert2/src/sweetalert2.scss">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  
<style>
   
    .deals-top h3 {
        font-size: 26px;
        margin-bottom: 0px;
    }
   
    .active {
        background-color: #ffd642;
    }
    .active:hover {
        background-color: #2f3672;
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
    table {
    	table-layout: auto;
    }
    #add-money-frm  label.error {
        font-size: 12px !important;
        border: 0px !important;
        text-transform: capitalize;
        margin-top: -5px;
        border: none!important;
    }
    #rechargeModal .modal-header{ background-color: #2F3672; color: #fff; }

.clickable{
    cursor: pointer;   
}

.panel-heading span {
    margin-top: -20px;
    font-size: 15px;
}
.amount-section a {color:#337ab7;  text-decoration: underline!important;}
.panel-primary { width: 65%; }
.tdright { text-align: right; width: 20%; }
#add-money-frm{max-width: 600px;}
</style>
<div id="pageContent" class="offline-order-page">
    <div class="container installer service-request-page">
         <div class="deals-top row">
                        
                        <div class="col-md-12 text-left">
                            <div class="row">
                                <div class="col-md-5">
                                    <span style="font-size: 25px;">Wallet : </span><span style="font-size: 20px; margin-bottom:10px;" id="available-balance">(<i class="fa fa-inr"></i><?=$balance->close_balance;?>) </span>
                                    <input type = "button" value = "Add Money" class = "btn btn-success" data-toggle="modal" id="rechargeMoney"  data-target="#rechargeModal1" />
                                </div>
                                <div class="col-md-7">
                                    <div class="rechage-details-sec" style="display: none;">                                        
                                            <form name="add-money-frm" id="add-money-frm" method="post" action="" style="max-width: 600px;">
                                                <div class="row text-center">
                                                   
                                                    <div class="col-md-6">
                                                        <input title="TXN_AMOUNT" tabindex="10" type="text" name="TXN_AMOUNT" id="TXN_AMOUNT" required value="" style="margin-bottom: 0;" placeholder="Enter Amount">
                                                        <input type="hidden" id="ORDER_ID" tabindex="1" maxlength="20" size="20" name="ORDER_ID" autocomplete="off" value="<?php echo  "WLT" . rand(10000,99999999)?>">
                                                        <input type="hidden" id="CUST_ID" tabindex="2" maxlength="12" size="12" name="CUST_ID" autocomplete="off" value="<?=$franchise_id;?>">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <button type="submit" class="btn btn-primary" id="pay_now" style="min-width: unset;"><span>Pay Now</span></button>
                                                    </div>
                                                </div>
                                            </form>
                                            <div id="paytm-from-generate"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                
                            </div>
                           
                        </div>
                    </div>
        <div class ="row content">
            <div class="search_filter">
            <form class = "post-list">
                <input type = "hidden" value = "" />
            </form>
           
            <div class="col-md-12">
                <select name="status" id="status" style="float: left!important;" class="status_change_click">
                    <option value="">Type</option>
                    <option value="cr">Cr</option>
                    <option value="dr">Dr</option>
            </select>
                <div class="search-box">
                    <div class="form-group">
                        <input type="text" name="startdate" id="startdate" class="form-control startdate" placeholder="Start Date">
                        <input type="text" name="enddate" id="enddate" class="form-control enddate" placeholder="End Date">
                       
                    </div>
                    <input type = "submit" value = "Filter" class = "btn btn-success post_search_submit" />
                    <input type = "submit" value = "Clear" class = "btn btn-success clear_search_submit" />
                </div>
                 <input type="hidden" name="status_sel_type" id="status_sel_type" value="">
            <input type="hidden" name="status_sel_val" id="status_sel_val" value="">
            </div>
             
           
        </div>
            <br class = "clear" />
              <script>
              $.noConflict();
              jQuery( function($) {
                $("#startdate").datepicker({
                    dateFormat: 'dd-mm-yy'
                });
                 $("#enddate").datepicker({
                    dateFormat: 'dd-mm-yy'
                });
              } );
              </script>
            <script type="text/javascript">
             var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";

            function get_all_wallet_history(page, th_name, th_sort, search_type){ 
                jQuery('#cover-spin').show(0);
                    var post_data = {
                        page: page,
                        startdate: jQuery('.startdate').val(),
                        enddate: jQuery('.enddate').val(),
                        th_name: th_name,
                        th_sort: th_sort,
                        search_type: search_type
                    };

                   jQuery('form.post-list input').val(JSON.stringify(post_data));
                   var data = {
                        action: "get_wallete_history",
                        data: JSON.parse(jQuery('form.post-list input').val())
                    };

                    jQuery.post(ajaxurl, data, function(response) {
                        if(jQuery(".cvf_universal_container").html(response)){
                            jQuery('.table-post-list th').each(function() {
                                jQuery(this).find('span.glyphicon').remove();   
                                    if(jQuery(this).hasClass('active')){
                                        if(JSON.parse(jQuery('form.post-list input').val()).th_sort == 'DESC'){
                                            jQuery(this).append(' <span class="glyphicon glyphicon-chevron-down"></span>');
                                        } else {
                                            jQuery(this).append(' <span class="glyphicon glyphicon-chevron-up"></span>');
                                        }
                                    }
                                });
                                jQuery('#cover-spin').hide(); 
                            }
                        });
                    }

                    jQuery(document).ready(function(jQuery) { 
                    var search_type = "";                                                              
                            if(jQuery('form.post-list input').val()){
                            // Submit hidden form input value to load previous page number
                                data = JSON.parse(jQuery('form.post-list input').val());
                                get_all_wallet_history(data.page, data.th_name, data.th_sort, search_type);
                            } else {
                            // Load first page
                                get_all_wallet_history(1, 'billing_first_name', 'DESC', search_type);
                            }


                            var th_active = jQuery('.table-post-list th.active');
                            var th_name = jQuery(th_active).attr('id');
                            var th_sort = jQuery(th_active).hasClass('DESC') ? 'ASC': 'DESC';
                            // Search
                            jQuery('body').on('click', '.post_search_submit', function(){
                                jQuery('#status_sel_type').val('');
                                jQuery('#status_sel_val').val('');
                               var th_name= jQuery('#status').val();
                               var search_type= 'status';
                                get_all_wallet_history(1, th_name, th_sort, search_type);
                            });

                            jQuery('body').on('click', '.clear_search_submit', function(){
                                var search_type = "";
                                jQuery('#startdate').val('');
                                jQuery('#enddate').val('');
                                jQuery('#status').prop('selectedIndex',0);
                                get_all_wallet_history(1, th_name, th_sort, search_type);
                            });


                            // Pagination Clicks                   
                         //   jQuery('.cvf_universal_container .cvf-universal-pagination li.active').live('click',function(){
						jQuery('body').on('click', '.cvf_universal_container .cvf-universal-pagination li.active', function(){

                                    var status1 = jQuery('#status_sel_type').val();
                                    var status2 = jQuery('#status_sel_val').val();
                                    var search_type = "";
                                    if(status1 == "status" && status2 !== "")
                                    {
                                       search_type = status1;
                                       th_name = status2;
                                    }
                                    else{
                                       search_type = '';
                                       th_name = jQuery(th_active).attr('id');
                                    }
                                var page = jQuery(this).attr('p');
                                var current_sort = jQuery(th_active).hasClass('DESC') ? 'DESC': 'ASC';
                                    get_all_wallet_history(page, th_name, current_sort,search_type);
                            });
                            // Sorting Clicks
                            jQuery('body').on('click', '.table-post-list th', function(e) {
                            e.preventDefault();                            
                                var th_name = jQuery(this).attr('id');
                                if(th_name){
                                    var search_type = "";
                                    // Remove all TH tags with an "active" class
                                    if(jQuery('.table-post-list th').removeClass('active')) {
                                    // Set "active" class to the clicked TH tag
                                        jQuery(this).addClass('active');
                                     }
                                    if(!jQuery(this).hasClass('DESC')){
                                        get_all_wallet_history(1, th_name, 'DESC', search_type);
                                        jQuery(this).addClass('DESC');
                                    } else {
                                        get_all_wallet_history(1, th_name, 'ASC', search_type);
                                        jQuery(this).removeClass('DESC');
                                    }
                                }
                            })
                    });

                    jQuery(document).ready(function(){
                            jQuery(".status_change_click").change(function(){
                                var sel = jQuery(this).val();
                                jQuery('form.post-list input').val('');
                                jQuery('#status_sel_type').val('status');
                                jQuery('#status_sel_val').val(sel);
                                var th_active = jQuery('.table-post-list th.active');
                                var th_name = sel;
                                var search_type = "status";
                                var th_sort = jQuery(th_active).hasClass('DESC') ? 'ASC': 'DESC';
                                get_all_wallet_history(1, th_name, th_sort, search_type);
                            });
                    });
            </script>
            <div class = "cvf_pag_loading no-padding">
                <div class = "cvf_universal_container">
                    <div class="cvf-universal-content"></div>
                </div>
            </div>
        </div>
   </div>
</div>

<div class="clearfix"></div>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        jQuery("#rechargeMoney").click(function(){
        jQuery(".rechage-details-sec").toggle('slow');
      });

        <?php if($postData){
                    if($postData['STATUS']=='TXN_FAILURE'){
                        $title='Trancastion Failed';
                        $msg='There is some issue in your transaction please try again leter.';
                        $icon='error';
                    }else{
                        $title='Thank You!';
                        $msg='₹ '.$paramList['TXNAMOUNT'].' Money has been added in your wallet.';
                         $icon='success';
                    }
                    ?>
                    swal({
                        title: '<?=$title;?>',
                        text: "<?=$msg;?>",
                        icon: "<?=$icon;?>",
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33'
                    }).then(function(){
                                        //location.reload();
                                        window.location.replace('<?=site_url('/my-account/wallet-history/')?>');
                                    }
                     );

        <?php }?>

        jQuery('#add-money-frm').validate({ // initialize the plugin
                rules: {               
                    TXN_AMOUNT: {
                        required: true,
                        min:1,
                        max:1000000,
                        number: true,
                    }
                },
                messages :{
                   TXN_AMOUNT: {
                          required: "Enter a amount",
                          min: "You can not enter minus amount",
                          max: "Enter maximum  ₹10,00,000 amount",
                          number: "Enter at numeric value",
                        }
                },
                submitHandler: function(form) {
                        var TXN_AMOUNT=$('#TXN_AMOUNT').val();
                        var ORDER_ID=$('#ORDER_ID').val();
                        var CUST_ID=$('#CUST_ID').val();
                        var admin_url=$('.admin_url').text();
                   
                        jQuery('#cover-spin').show(0);
                         jQuery.ajax({
                            type: "POST",
                            url: admin_url,
                            data: {
                                'action':'add_money_in_wallet',
                                'TXN_AMOUNT' : TXN_AMOUNT,
                                'ORDER_ID' : ORDER_ID,
                                'CUST_ID' : CUST_ID
                            },
                            success: function (data)
                            {
                               
                                jQuery('#paytm-from-generate').html(data);
                               document.f1.submit();
                               jQuery('#cover-spin').hide(0);

                            },
                        });
                }
            });

        
    });

</script>
<?php
get_footer();
?>

