<?php

class bhistory_List_Table extends WP_List_Table {

    function get_bpartner($history=''){
    	$s=$_POST['s'];
      $user_id=$_GET['user_id'];
      $start_date=$_GET['start_date'];
      $end_date=$_GET['end_date'];
      $is_paid=$_GET['is_paid'];
      if(empty($is_paid)){
      	$is_paid=0;
      }else{
      	$is_paid=$is_paid;
      }
      
    	global $wpdb;
	    $sql = "SELECT * FROM th_business_commission bc LEFT JOIN th_business_partner_data as bpd ON bpd.user_id=bc.user_id";
	   
      if($s){
	     $sqlwhere.=" and bpd.business_name LIKE '%".$s."%'";	
		  }	 

      if($user_id){
       $sqlwhere.=" and bc.user_id =".$user_id;  
      }
      
      if($start_date!='' && $end_date!=''){
       $sqlwhere.=" and bc.update_date BETWEEN '".$start_date."' AND '".$end_date."'"; 
      }

      if(empty($history)){
      	$sqlwhere.=" and bc.is_paid =".$is_paid;  
  	  }else{
  	  	//$sqlwhere.=" and bc.is_paid =0";
  	  }
      
      if(empty($history)){
      	$sql.=" WHERE 1=1 AND bc.order_status='completed' ".$sqlwhere;  
  	  }else{
  	  	$sql.=" WHERE 1=1 AND ((bc.is_paid!=1 AND bc.order_status!='completed') OR (bc.is_paid=1 AND bc.order_status='completed')) ".$sqlwhere;
  	  }
       

	    $sql.=" order by bc.comi_id desc";
	   
	    $bpartner_data = $wpdb->get_results($sql);



   		$bhistory=array();
  		foreach ($bpartner_data as $key => $value)
  		{

        $btob_discount=wc_price( ($value->order_total*$value->percentage)/100);
  			$bhistory[$key]['ID'] = $value->comi_id;
  			$bhistory[$key]['business_name']= $value->business_name;
        $bhistory[$key]['order_id']= $value->order_id;
  			$bhistory[$key]['percentage']= $value->percentage.'|'.$btob_discount;
  			$bhistory[$key]['commission_price']= $value->commission_percentage.'|'.wc_price($value->commission_price);
        $bhistory[$key]['order_total']= wc_price($value->order_total);
        $bhistory[$key]['paid_date']=date('d-m-Y',strtotime($value->paid_date));
        $bhistory[$key]['order_date']=date('d-m-Y',strtotime($value->update_date));

        if($value->is_paid==0){
          $is_paid='No';
        }else{
           $is_paid='Yes';
        }

        $bhistory[$key]['is_paid']= $is_paid;
        if($value->order_status=='on-hold'){
        	$status='Order Received';
        }elseif($value->order_status=='completed'){
        	$status='Order Complete';
        }elseif($value->order_status=='refunded'){
        	$status='Cancel & Refund';
        }elseif($value->order_status=='processing'){
        	$status='Order Dispatched';
        }elseif($value->order_status=='pending'){
        	$status='Pending Payment';
        }elseif($value->order_status=='failed'){
        	$status='Failed';
        }elseif($value->order_status=='customprocess'){
        	$status='Order Processing';
        }elseif($value->order_status=='deltoinstaller'){
        	$status='Order Ready to Install';
        }
        

        $bhistory[$key]['order_status']= $status;

        $btob_discount_total= ($value->order_total*$value->percentage)/100+$btob_discount_total;
        $commission_total= $value->commission_price+$commission_total;
       
  		}
      $bhistory['btob_discount']=wc_price($btob_discount_total);
      $bhistory['commission_total']=wc_price($commission_total);
		return $bhistory;
		die;
    }
    function __construct(){
    global $status, $page;
        parent::__construct( array(
            'singular'  => __( 'bhistory', 'mylisttable' ),     //singular name of the listed records
            'plural'    => __( 'bhistorys', 'mylisttable' ),   //plural name of the listed records
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
    _e( 'No business partner found, dude.' );
  }
  function column_default( $item, $column_name ) {
    switch( $column_name ) { 
        case 'business_name':
        case 'order_id':
        case 'percentage':
        case 'commission_price':
        case 'order_total':
        case 'is_paid':
        case 'order_status':
        case 'paid_date':
        case 'order_date':
            return $item[ $column_name ];
        default:
            return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
    }
  }
function get_sortable_columns() {
  $sortable_columns = array(
    'business_name'  => array('business_name',false),
    'order_id'  => array('order_id',false),
    'percentage' => array('percentage',false),
    'commission_price'   => array('commission_price',false),
    'order_total' => array('order_total',false),
    'is_paid' => array('is_paid',false),    
    'order_status'   => array('order_status',false),
    'paid_date'   => array('paid_date',false),
    'order_date'   => array('order_date',false)
  
  );
  return $sortable_columns;
}
function get_columns(){
        $columns = array(
            'cb'        => '<input type="checkbox" />',
            'business_name' => __( 'Business Name', 'mylisttable' ),
            'order_id'    => __( 'Order No', 'mylisttable' ),
            'percentage'      => __('Percentage%/Amount','mylisttable'),
            'commission_price'      => __('Commission%/Amount', 'mylisttable' ),
            'order_total'      => __('Order Total', 'mylisttable' ),
            'is_paid'      => __( 'Is Paid', 'mylisttable' ),
            'order_status'      => __( 'Order Status', 'mylisttable' ),
            'paid_date'      => __( 'Paid Date', 'mylisttable' ),
            'order_date'      => __( 'Order Date', 'mylisttable' )
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
function column_user_code($item){
  $actions = array(
            'edit'      => sprintf('<a href="?page=%s&action=%s&bhistory_id=%s">Edit</a>','bpartner-add-new','edit',$item['ID']),
            //'delete'    => sprintf('<a href="?page=%s&action=%s&installer_id=%s">Delete</a>',$_REQUEST['page'],'delete',$item['ID']),
        );
  return sprintf('%1$s %2$s', $item['user_code'], $this->row_actions($actions) );
}

function get_bulk_actions() {
  $actions = array(
    'paid'    => 'Paid'
  );
  return $actions;
}

function process_bulk_action() {
    //Detect when a bulk action is being triggered...
    global $wpdb;
    $entry_id = ( is_array( $_REQUEST['bhistory'] ) ) ? $_REQUEST['bhistory'] : array( $_REQUEST['bhistory'] );

    if ( 'paid' === $this->current_action()) {
        global $wpdb;

        foreach ( $entry_id as $id ) {
            $id = absint($id);
            $wpdb->update('th_business_commission', 
                    array( 
                      'is_paid' => 1,  // string
                      'paid_date' => date('Y-m-d H:i:s') // integer (number) 
                    ), 
                    array('comi_id' => $id));

        }
    }

    
  }
function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="bhistory[]" value="%s" />', $item['ID']
        );    
 }
function prepare_items() {
  $columns  = $this->get_columns();
  $hidden   = array();
  $sortable = $this->get_sortable_columns();
  $this->_column_headers = array( $columns, $hidden, $sortable );
  $this->process_bulk_action();
  $data=$this->get_bpartner();
  unset($data['btob_discount']);
  unset($data['commission_total']);
  usort($data, array( &$this, 'usort_reorder' ) );
  
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
	function prepare_all_items() {
	  $columns  = $this->get_columns();
	  $hidden   = array();
	  $sortable = $this->get_sortable_columns();
	  $this->_column_headers = array( $columns, $hidden, $sortable );
	  //$this->process_bulk_action();
	  $data=$this->get_bpartner('allhistory');
	  unset($data['btob_discount']);
	  unset($data['commission_total']);
	  usort($data, array( &$this, 'usort_reorder' ) );
	  
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



function commission_history_fun(){
  $bhistoryListTable = new Bhistory_List_Table();
  ?>
  <div class="wrap"><h2>BtoB Pending Commission <a href="?page=bpartner-add-new" class="page-title-action">Add New Business Partner</a>
  <div class="admin-url" hidden=""><?php echo admin_url('admin-ajax.php'); ?></div></h2> 
  <div style="margin-top: 10px;">
     <form method="get">
      <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>">
     <select name="user_id" id="user_id">
            <option value="">--Choose Business Partner--</option>
            
            <?php
            global $wpdb;
            $sql = "SELECT * FROM th_business_partner_data";
            $bpartner_data = $wpdb->get_results($sql); 
            foreach ($bpartner_data as $partner ) 
                {
                    
                    if($_GET['user_id']==$partner->user_id){
                      $selected='selected="selected"';
                    }else{
                      $selected='';
                    }
                    echo '<option value="'.$partner->user_id.'" '.$selected.'>'.$partner->business_name.'</option>';
                }
            ?>
       </select>
       <?php 
       $is_paid=$_GET['is_paid'];
       if(empty($is_paid)){
		$is_paid=0;
       }else{
		$is_paid=1;
       }
       ?>
       <select name="is_paid" id="is_paid">
            <option value="">--Choose Payment Mode--</option>
            <option value="1" <?php if($is_paid==1){ echo 'selected';}?>>Paid</option>
            <option value="0" <?php if($is_paid==0){ echo 'selected';}?>>UnPaid</option>
            
       </select>

          <input type="date" name="start_date" class="start-date1" autocomplete="off" placeholder="Start Date" value="<?=$_GET['start_date']?>">
          <input type="date" name="end_date" class="end-date1" autocomplete="off" placeholder="End Date" value="<?=$_GET['end_date']?>">

          
      <input type="submit" name="Filter" value="Filter" class="button">
    </form>

</div>
<style type="text/css">
  .total-section{display: inline-block; background-color:#000; padding: 5px 15px; color:#fff; position: absolute; margin-top: 10px; margin-left: 425px;}
  .total-section1{display: inline-block; background-color:#000; padding: 5px 15px; color:#fff; position: absolute; margin-top: 10px; margin-left: 620px;}
</style>
<?php 

$discount=$bhistoryListTable->get_bpartner(); ?>
<div class="total-section"><strong>Total: <?=$discount['btob_discount'];?></strong> </div>
<div class="total-section1"><strong>Total: <?=$discount['commission_total'];?></strong> </div>
 
  <form method="post">
    <input type="hidden" name="page" value="bpartner-manage">
    <?php $bhistoryListTable->prepare_items(); 
?>
    <?php
    $bhistoryListTable->search_box( 'search', 'search_id' );
    $bhistoryListTable->display(); 
    echo '</form></div>'; 
    commission_all_history_fun();
}


function commission_all_history_fun(){
  $bhistoryListTable = new Bhistory_List_Table();
  ?>
  <div style="clear: both;"></div>
  <div class="wrap" style="margin-top: 50px !important;"><h2>BtoB Transection History </h2> 

</div>
<form method="post">
    <input type="hidden" name="page" value="bpartner-manage">
    <?php $bhistoryListTable->prepare_all_items(); 
?>
    <?php
    //$bhistoryListTable->search_box( 'search', 'search_id' );
    $bhistoryListTable->display(); 
    echo '</form></div>'; 
}



