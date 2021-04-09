<?php
/*
Plugin Name: Tyrehub inspection Tyre Management
Plugin URI: https://webtual.com/
Description: Flat Tyre.
Version: 1.1.1
Author: Webtual
Author URI: https://webtual.com/
*/
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
//include_once('manage-business.js');

class Flat_inspection_List_Table extends WP_List_Table {

function get_inspection_tyre()
    {
           global $wpdb;
           $s=$_POST['s'];

              $inspection_id = $_GET['inspection_id'];

              if($_GET['action'] == 'delete'){
                $wpdb->get_results("DELETE from th_free_tyre_inspection WHERE id = '$inspection_id'");
                wp_redirect('?page=inspection-manage');
              }

          $sql = "SELECT * FROM  th_free_tyre_inspection";
           if($s){
               $sql.=" where name LIKE '%".$s."%' OR vehicle_location LIKE '%".$s."%' OR mobile_number LIKE '%".$s."%' OR email LIKE '%".$s."%'";
            }
            $sql.=" order by id desc";
            $inspection_data = $wpdb->get_results($sql);
            $inspection=array();
            foreach ($inspection_data as $key => $value)
            {
                $inspection[$key]['id'] = $value->id;
                $inspection[$key]['name']= $value->name;
                $inspection[$key]['mobile_number']= $value->mobile_number;
                $inspection[$key]['vehicle_location']= $value->vehicle_location;
                $inspection[$key]['preferred_date']= $value->preferred_date;
                $inspection[$key]['preferred_timing']= $value->preferred_timing;
                $inspection[$key]['status']= $value->status;
                $inspection[$key]['created_at']= $value->created_at;



                if($value->status==0){
                $inspection[$key]['status']= '<a href="#" class="button-secondary woocommerce-save-button add-installer-btn">No</a>';
                }else{
                $inspection[$key]['status']= '<a href="#" class="button-primary woocommerce-save-button add-installer-btn">Yes</a> ';
                }
            }
          return $inspection;
          die;
    }


  function __construct(){
        global $status, $page;
        parent::__construct( array(
            'singular'  => __( 'flattyre', 'mylisttable' ),     //singular name of the listed records
            'plural'    => __( 'flattyres', 'mylisttable' ),   //plural name of the listed records
            'ajax'      => true        //does this table support ajax?
        ));
        add_action( 'admin_head', array( &$this, 'admin_header' ) );
  }


  function admin_header()
  {
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
    _e( 'No Flat Tyre found, dude.' );
  }

  function column_default( $item, $column_name ) {
    switch( $column_name ) {
        case 'name':
        case 'mobile_number':
        case 'vehicle_location':
        case 'preferred_date':
        case 'preferred_timing':
        case 'status':
        case 'created_at':
            return $item[ $column_name ];
        default:
            return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
    }
  }

  function get_sortable_columns() {
      $sortable_columns = array(
            'name'  => array('Name',false),
            'mobile_number'  => array('Mobile Number',false),
            'vehicle_location' => array('Vehicle location',false),
            'preferred_date'   => array('Preferred date',false),
            'preferred_timing' => array('Preferred timing',false),
            'status' => array('Status',false),
            'created_at' => array('Created at',false)
        );

        return $sortable_columns;
  }

  function get_columns(){
        $columns = array(
            'cb'        => '<input type="checkbox" />',
            'name' => __( 'Name', 'mylisttable' ),
            'mobile_number' => __( 'Mobile Number', 'mylisttable' ),
            'vehicle_location'    => __('Vehicle location', 'mylisttable' ),
            'preferred_date'      => __('Preferred date', 'mylisttable' ),
            'preferred_timing'      => __('Preferred timing', 'mylisttable' ),
            'status'      => __( 'Status', 'mylisttable' ),
            );
                return $columns;
  }

  function usort_reorder( $a, $b ) {
      // If no sort, default to title
      $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'name';
      // If no order, default to asc
      $order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
      // Determine sort order
      $result = strcmp( $a[$orderby], $b[$orderby] );
      // Send final sort direction to usort
      return ( $order === 'asc' ) ? $result : -$result;
  }

  function column_name($item){
      $actions = array(
                //'edit' => sprintf('<a href="?page=%s&action=%s&flat_id=%s">Edit</a>','inspection-add-new','edit',$item['ID']),
              'delete'    => sprintf('<a href="?page=%s&action=%s&inspection_id=%s">Delete</a>',$_REQUEST['page'],'delete',$item['id']),
            );
      return sprintf('%1$s %2$s', $item['name'], $this->row_actions($actions) );
  }

  function get_bulk_actions() {
        $actions = array(
          'delete'    => 'Delete',
        );
        return $actions;
  }

  function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="flat[]" value="%s" />', $item['ID']
        );
  }

  function prepare_items() {
      $columns  = $this->get_columns();
      $hidden   = array();
      $sortable = $this->get_sortable_columns();
      $this->_column_headers = array( $columns, $hidden, $sortable );
      $data=$this->get_inspection_tyre();
      if(isset($_GET['orderby'])){
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

add_action('admin_enqueue_scripts', 'inspection_style');
    function inspection_style()
          {

             wp_enqueue_script('inspection_style_scripta', plugins_url('/manage-business.js', __FILE__), array('jquery'));
          }


add_action('admin_menu', 'inspection_menu');

    function inspection_menu()
      {
         $hook = add_menu_page('inspection', 'Free Tyre inspection', 'manage_options', 'inspection-manage', 'inspection_list_page', 'dashicons-admin-tools',63 );

          add_submenu_page('inspection-manage','inspection Tyre Edit', 'Edit New', 'inspection-manage', 'inspection-add-new', 'inspection_from', 'dashicons-media-text',64 );
      }



      /*add_action('admin_enqueue_scripts', 'bpartner_admin_style');

      function bpartner_admin_style()
      {

           wp_enqueue_style('bpartner_admin_stylea', plugins_url('/style.css', __FILE__));

           wp_enqueue_script('bpartner_admin_scripta', plugins_url('/manage-bpartner.js', __FILE__), array('jquery'));
      }
      */
      //add_action( 'admin_menu', 'my_add_menu_items' );


    function inspection_list_page(){
        $FlatTypreListTable = new Flat_inspection_List_Table(); ?>
            <div class="wrap">
                <h2>Free Tyre inspection Management
                  <div class="admin-url" hidden=""><?php echo admin_url('admin-ajax.php'); ?></div>
                </h2>
            <?php $FlatTypreListTable->prepare_items(); ?>
            <form method="post">
                <div>
                  <?php
                  if($_POST['flat']){
                  $flat=implode(',',$_POST['flat']);
                  }else{
                    $flat='';
                  }
                  ?>
                <div><a style="border: 1px solid;padding: 7px;" href="<?=plugin_dir_url( __FILE__ );?>csv-export.php?flat=<?=$flat;?>">Export Data</a></div>
                </div>
                  <input type="hidden" name="page" value="inspection-manage">
                      <?php
                          $FlatTypreListTable->search_box( 'search', 'search_id' );
                          $FlatTypreListTable->display();
        echo '</form></div>';
    }

    function inspection_from(){
       $action=$_GET['action'];
         if($action=='edit'){
             include('update-flattyre-data.php');
             flattyre_update();
           }
    }

