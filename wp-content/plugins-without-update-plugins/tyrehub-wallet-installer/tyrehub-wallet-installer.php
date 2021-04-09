<?php
/*
Plugin Name: Tyrehub Wallet Installer
Plugin URI: https://webtual.com/
Description: Here we can add wallet Balance
Version: 1.1.1
Author: Webtual
Author URI: https://webtual.com/
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}
// Update CSS within in Admin
function admin_style() {
  wp_enqueue_style('admin-styles', get_template_directory_uri().'/assest/css/plugins/bootstrap.min.css',array(), rand(111,999), true);
 wp_enqueue_style('admin-wallet', plugin_dir_url( __FILE__ ).'wallet-installer.css',array(), rand(111,999), true);
  

}
add_action('admin_enqueue_scripts', 'admin_style');
function dolly_css() {?>
  <style type="text/css">
    #cover-spin::after {
    content: '';
    display: block;
    position: absolute;
    left: 48%;
    top: 40%;
    width: 40px;
    height: 40px;
    border-style: solid;
    border-color: black;
        border-top-color: black;
    border-top-color: transparent;
    border-width: 4px;
    border-radius: 50%;
    -webkit-animation: spin .8s linear infinite;
    animation: spin .8s linear infinite;
}

#cover-spin {
    position: fixed;
    width: 100%;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    background-color: rgba(200,205,220,0.7);
    z-index: 9999;
    display: none;
}
  </style>
<?php 
  echo '<div id="cover-spin"></div>';
}
add_action( 'admin_head', 'dolly_css' );

class Wallet_History_List_Table extends WP_List_Table {

    function get_wallet_history(){
        global $wpdb;
        $franchise=$_GET['franchise']; 
        $status=$_GET['status'];        
        $startdate=date('Y-m-d',strtotime($_GET['startdate'])); 
        $enddate=date('Y-m-d',strtotime($_GET['enddate']));        
        $sql = "SELECT * FROM th_franchise_payment";
        $where="";

        if($franchise){
            $where.=" AND  franchise_id= ".$franchise;    
        } 
        if($status){
            $where.=" AND  tran_type= '".$status."'";    
        } 

        if($_GET['startdate']){
            $where.=" AND   DATE_FORMAT(created_at, '%Y-%m-%d') BETWEEN '".$startdate."' AND  '".$enddate."'";    
        }

        $sql.=" WHERE 1=1 ".$where." order by id desc";
        //echo $sql;
        $whistory = $wpdb->get_results($sql);


        $history=array();
        foreach ($whistory as $key => $value)
        {
            
            $SQL="SELECT * FROM th_installer_data WHERE installer_data_id='".$value->franchise_id."' AND is_franchise='yes'";
            $franchise=$wpdb->get_row($SQL);
            $franchise_id=$franchise->installer_data_id;


            if($value->tran_type=='cr'){
               $credit= '<i class="fa fa-inr"></i> '.$value->amount;
               //$trans_details='Payment received';
            }else{
               $credit='-'; 
            }
            if($value->tran_type=='dr'){
               $debit='<i class="fa fa-inr"></i> '.$value->amount;
               //$trans_details='Payment debited for order - ' .$value->order_id;
            }else{
               $debit='-'; 
            }

            $history[$key]['ID'] = $value->id;
            $history[$key]['id'] = $value->id;
            $history[$key]['business_name']= $franchise->business_name;
            $history[$key]['transaction_details']= $value->transaction_details;
            $history[$key]['date']= date('d-m-Y',strtotime($value->created_at));
            $history[$key]['tran_type']= $value->tran_type;
            
            $history[$key]['credit']= $credit;
            $history[$key]['debit']= $debit;
            $history[$key]['close_balance']='<i class="fa fa-inr"></i> '.$value->close_balance;
            
            
            // if($value->status==0){
            // $history[$key]['status']= '<a href="'.get_admin_url().'/admin.php?page=installer-add-new&action=edit&installer_id='.$value->id.'" class="button-secondary woocommerce-save-button add-installer-btn">No</a>'; 
            // }else{
            // $history[$key]['status']= '<a href="'.get_admin_url().'/admin.php?page=installer-add-new&action=edit&installer_id='.$value->id.'" class="button-primary woocommerce-save-button add-installer-btn">Yes</a> '; 
            // }
            
        }

        return $history;
        die;
    }
    function __construct(){
    global $status, $page;
        parent::__construct( array(
            'singular'  => __( 'wallet-history', 'mylisttable' ),     //singular name of the listed records
            'plural'    => __( 'wallet-history', 'mylisttable' ),   //plural name of the listed records
            'ajax'      => true        //does this table support ajax?
    ) );
    add_action( 'admin_head', array( &$this, 'admin_header' ) );            
    }
  function admin_header() {
    $page = ( isset($_GET['page'] ) ) ? esc_attr( $_GET['page'] ) : false;
    if( 'my_list_test' != $page )
    return;
    echo '<style type="text/css">';
    echo '.wp-list-table .column-id { width: 5%; }';
    echo '.wp-list-table .column-booktitle { width: 40%; }';
    echo '.wp-list-table .column-author { width: 35%; }';
    echo '.wp-list-table .column-isbn { width: 20%;}';
    echo '</style>';
  }
  function no_items() {
    _e( 'No wallet-history found, dude.' );
  }
  function column_default( $item, $column_name ) {
    switch( $column_name ) { 
        case 'id':
        case 'business_name':
        case 'date':
        case 'credit':
        case 'debit':
        case 'close_balance':
        case 'tran_type':
        case 'transaction_details':        
            return $item[ $column_name ];
        default:
            return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
    }
  }
function get_sortable_columns() {
  $sortable_columns = array(
    'id'  => array('ID',false),
    'business_name'  => array('business_name',false),
    'date' => array('date',false),
    'credit'   => array('credit',false),
    'debit'   => array('debit',false),
    'close_balance'   => array('close_balance',false),
    'tran_type'   => array('tran_type',false),
    'transaction_details'   => array('transaction_details',false)
    
  );
  return $sortable_columns;
}
function get_columns(){
        $columns = array(
            'cb'        => '<input type="checkbox" />',
            'id' => __( 'ID', 'mylisttable' ),
            'transaction_details' => __( 'Transaction Details', 'mylisttable' ),
            'business_name' => __( 'Franchise', 'mylisttable' ),
            'date'    => __( 'Date', 'mylisttable' ),
            'tran_type'      => __( 'Type', 'mylisttable' ),
             'debit'      => __( 'Debit', 'mylisttable' ),
            'credit'      => __( 'Credit', 'mylisttable' ),
            'close_balance'      => __( 'Balance', 'mylisttable' )
        );
         return $columns;
    }
function usort_reorder( $a, $b ) {
  // If no sort, default to title
  $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'business_name';
  // If no order, default to asc
  $order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
  // Determine sort order
  $result = strcmp( $a[$orderby], $b[$orderby] );
  // Send final sort direction to usort
  return ( $order === 'asc' ) ? $result : -$result;
}
function column_id($item){
  $actions = array(
            'edit'      => sprintf('<a href="?page=%s&action=%s&id=%s">Edit</a>','wallet-balance-add','edit',$item['ID']),
            //'delete'    => sprintf('<a href="?page=%s&action=%s&installer_id=%s">Delete</a>',$_REQUEST['page'],'delete',$item['ID']),
        );
  return sprintf('%1$s %2$s', $item['id'], $this->row_actions($actions) );
}
function get_bulk_actions() {
  $actions = array(
    'delete'    => 'Delete'
  );
  return $actions;
}
function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="history[]" value="%s" />', $item['ID']
        );    
    }
function prepare_items() {
  $columns  = $this->get_columns();
  $hidden   = array();
  $sortable = $this->get_sortable_columns();
  $this->_column_headers = array( $columns, $hidden, $sortable );
  $data=$this->get_wallet_history();
 if($_GET['order']!=''){
    usort($data, array( &$this, 'usort_reorder' ) );
 }
  
  
  $per_page = 10;
  $current_page = $this->get_pagenum();
  $total_items = count($data);
  // only ncessary because we have sample data
  $found_data = array_slice($data,( ( $current_page-1 )* $per_page ), $per_page );
  $this->set_pagination_args( array(
    'total_items' => $total_items,                  //WE have to calculate the total number of items
    'per_page'    => $per_page                     //WE have to determine how many items to show on a page
  ) );
  $this->items = $found_data;
}
} //class

function wallet_tyrehub_admin_menu()
{
    $hook =  add_menu_page(__('Wallet Balance', 'wallet_history'), __('Credit System', 'wallet_history'), 'manage_options', 'wallet-balance-history', 'wallet_balance_history');

       
    add_submenu_page('wallet-balance-history','Balance Add', 'Balance Add', 'manage_options', 'wallet-balance-add', 'wallet_balance_add', 'dashicons-media-text',62 );
    //add_submenu_page('wallet-balance-history','Franchise Payout History', 'Franchise Payout History', 'manage_options', 'franchise-payout-history', 'franchise_payout_history', 'dashicons-media-text',62 );
    add_action( "load-$hook", 'add_wallet_options' );
}

add_action('admin_menu', 'wallet_tyrehub_admin_menu');
function add_wallet_options() {
  global $walletHistoryListTable;
  $option = 'per_page';
  $args = array(
         'label' => 'Wallet Transation',
         'default' => 10,
         'option' => 'wallet_history_per_page'
         );
  add_screen_option( $option, $args );
  $walletHistoryListTable = new Wallet_History_List_Table();
}
//add_action( 'admin_menu', 'my_add_menu_items' );
function wallet_balance_history(){
    global $wpdb;
    $SQL="SELECT * FROM th_installer_data WHERE is_franchise='yes'";
    $franchise=$wpdb->get_results($SQL);
  global $walletHistoryListTable;
  ?>
  <form method="get" action="<?=site_url()?>/wp-admin/admin.php">
      <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <div class="wrap"><h2>Wallet <a href="?page=wallet-balance-add" class="page-title-action">Add Amount</a>
    <div class="admin-url" hidden=""><?php echo admin_url('admin-ajax.php'); ?></div></h2>
       <div class="search-box">
        <div class="form-group">
             <select name="franchise" id="franchise" class="status_change_click">
                    <option value="">Choose Franchise</option>
                    <?php 
                    foreach ($franchise as $key => $value) {?>
                   
                    <option value="<?=$value->installer_data_id?>" <?php if($_GET['franchise']==$value->installer_data_id) { echo 'selected'; } ?>><?=$value->business_name;?></option>
                    <?php } ?>
            </select>

            <input type="text" name="startdate" id="startdate" value="<?=$_GET['startdate']?>" class="form-control startdate" placeholder="Start Date" style="height: 30px;">
            <input type="text" name="enddate" id="enddate" value="<?=$_GET['enddate']?>" class="form-control enddate" placeholder="End Date" style="height: 30px;">

            <select name="status" id="status" class="status_change_click">
                    <option value="">Type</option>
                    <option value="cr" <?php ($_GET['status']=='cr')? 'selected' : '' ?>>Cr</option>
                    <option value="dr" <?php ($_GET['status']=='dr')? 'selected' : '' ?>>Dr</option>
            </select>

          <input type = "submit" value = "Search" class = "button  post_search_submit" /> 
        </div>
        
    </div> 
                  <script>
              jQuery( function() {
                jQuery("#startdate").datepicker({
                    dateFormat: 'dd-mm-yy'
                });
                 jQuery("#enddate").datepicker({
                    dateFormat: 'dd-mm-yy'
                });
              } );
              </script>
 <?php $walletHistoryListTable->prepare_items(); 
?>
  
    <input type="hidden" name="page" value="wallet-balance-history">
    <?php
    //$walletHistoryListTable->search_box( 'search', 'search_id' );
    $walletHistoryListTable->display(); 
    echo '</form></div>'; 
}


function wallet_balance_add()
{
      global $woocommerce , $wpdb;
   
?>
  <div class="wrap"><div id="icon-options-general" class="icon32"><br></div>
        <h2>Add  Amount</h2></div>
        </br>
        <div class="row">
        <div class="col-md-6" style="width: 50%; float: left;">
        <form method="post" action="" id="amount_add">
          <div id="msg" style="color: green;"></div>
        <table class="form-table" role="presentation">
            <tbody>
           <tr class="user-display-name-wrap">
                    <th><label for="display_name">Franchise</label></th>
                    <td>
                            <select name="franchise" id="franchise" required="" class="get_franchise_amount">
                                 <option value="">Select Franchise</option>
                                <?php 
                                    $SQL="SELECT * FROM th_installer_data WHERE is_franchise='yes'";
                                    $franchiseData=$wpdb->get_results($SQL);

                                    foreach ($franchiseData as $key => $value) {
                                    ?>
                                    <option value="<?=$value->installer_data_id;?>"><?=$value->business_name;?></option>
                                    <?php }?>
                            </select>
                    </td>
            </tr>
           
            <tr class="user-last-name-wrap">
                    <th><label for="last_name">Description</label></th>
                    <td>
                    <textarea name="description" id="description" rows="4" cols="30"></textarea>
                    </td>
            </tr>

             <tr class="user-last-name-wrap">
                    <th><label for="last_name">Type</label></th>
                    <td>
                         <select name="tran_type" id="tran_type" class="status_change_click" required="">
                                <option value="">Choose Type</option>
                                <option value="dr">Debit</option>
                                <option value="cr">Credit</option>
                                
                        </select>
                    </td>
            </tr>
             <tr class="user-last-name-wrap">
                    <th><label for="last_name">Amount</label></th>
                    <td><input type="number" name="amount" id="amountamount" value="" class="regular-text" required=""></td>
            </tr>
            
            
            
            <tr class="user-last-name-wrap">
            <td>
                <p class="submit">
                    <input type="button" name="subwallet" id="add-wallet-amount" class="button button-primary" value="Submit">
                </p>
            </td>
            </tr>
            
            </tbody>
            </table>
          </form>
        </div>
        <div class="col-md-6" style="width: 50%; float: left;">
          <table class="form-table" role="presentation">
            <tbody>
           <tr class="user-display-name-wrap">
                    <th><label for="display_name">Franchise</label></th>
                    <th style="width: 130px">:</th>
                    <td  style="width:60%;">
                       <div id="franchise_name"></div>     
                    </td>
            </tr>
           
            <tr class="user-last-name-wrap">
                    <th><label for="last_name">Address</label></th>
                    <th style="width: 130px">:</th>
                    <td  style="width:60%;">
                      <div id="franchise_address"></div> 
                    </td>
            </tr>

             <tr class="user-last-name-wrap">
                    <th><label for="last_name">Contact Person </label></th>
                    <th style="width: 130px">:</th>
                    <td  style="width:60%;">
                        <div id="contact_person"></div> 
                    </td>
            </tr>
            <tr class="user-last-name-wrap">
                    <th><label for="last_name">Contact Number</label></th>
                     <th style="width: 130px">:</th>
                    <td  style="width:60%;">
                      <div id="contact_number"></div>
                    </td>
            </tr>
             <tr class="user-last-name-wrap">
                    <th><label for="last_name">Balance</label></th>
                     <th style="width: 130px">:</th>
                    <td  style="width:60%;">
                     <div id="balance" class="fa fa-inr"></div>
                    </td>
            </tr>
            
            
           
            </tbody>
            </table>
        </div>
      </div>
      <style type="text/css">
        .error {border: 1px solid red!important;}
        .amount_add textarea, input[type="text"],input[type="number"], select { width: 100%!important; }
        .amount_add input, select, textarea {width: 100%!important;}
      </style>
          <script type="text/javascript">
               jQuery(document).ready(function(){
                            jQuery(".get_franchise_amount").change(function(){
                                jQuery('#cover-spin').show();
                                var franchise_id = jQuery(this).val();
                               
                                  jQuery.ajax({
                                     type: "POST",
                                     url: ajaxurl,
                                     data: {
                                         action: "get_franchise_amount",
                                         franchise_id:franchise_id
                                     },
                                     beforeSend: function() {
                                        
                                     },
                                     success: function(t) {
                                      var objData = JSON.parse(t); 
                                     jQuery('#franchise_name').text('');
                                     jQuery('#franchise_name').text(objData.franchise_name);

                                     jQuery('#franchise_address').text('');
                                     jQuery('#franchise_address').text(objData.franchise_address);

                                     jQuery('#contact_person').text('');
                                     jQuery('#contact_person').text(objData.contact_person);

                                     jQuery('#contact_number').text('');
                                     jQuery('#contact_number').text(objData.contact_number);

                                     jQuery('#balance').text('');
                                     jQuery('#balance').text(objData.balance);


                                     jQuery('#cover-spin').hide();   
                                     },
                                     error: function(t) {}
                                 })

                            });

                            jQuery("#add-wallet-amount").click(function(){
                                jQuery('#cover-spin').show();
                                var franchise_id = jQuery('#franchise').val();
                                var description = jQuery('#description').val();
                                var tran_type = jQuery('#tran_type').val();
                                var amountamount = jQuery('#amountamount').val();
                                  if(franchise_id==''){
                                      jQuery("#franchise").addClass("error");
                                  }else{
                                     jQuery("#franchise").removeClass("error");
                                  }

                                  if(franchise_id==''){
                                      jQuery("#franchise").addClass("error");
                                  }else{
                                      jQuery("#franchise").removeClass("error");
                                  }

                                  if(description==''){
                                      jQuery("#description").addClass("error");
                                  }else{
                                    jQuery("#description").removeClass("error");
                                  }

                                  if(tran_type==''){
                                      jQuery("#tran_type").addClass("error");
                                  }else{
                                    jQuery("#tran_type").removeClass("error");
                                  }

                                  if(amountamount==''){
                                      jQuery("#amountamount").addClass("error");
                                  }else{
                                     jQuery("#amountamount").removeClass("error");
                                  }

                                  if(franchise_id!='' && description!='' && tran_type!='' && amountamount!=''){
                                      jQuery.ajax({
                                       type: "POST",
                                       url: ajaxurl,
                                       data: {
                                           action: "save_franchise_wallet_amount",
                                           franchise_id:franchise_id,
                                           description:description,
                                           tran_type:tran_type,
                                           amountamount:amountamount
                                       },
                                       beforeSend: function() {
                                          
                                       },
                                       success: function(t) {
                                        jQuery('.get_franchise_amount').trigger('change');
                                       jQuery('#msg').text(t);
                                       
                                       jQuery("#amount_add")[0].reset();
                                       jQuery('#cover-spin').hide();   
                                       },
                                       error: function(t) {}
                                   });
                                  }
                                  jQuery('#cover-spin').hide();
                            });


      });
          </script>
<?php
}