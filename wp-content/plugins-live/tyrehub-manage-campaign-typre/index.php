<?php
/*
Plugin Name: Tyrehub Campaign Users Management
Plugin URI: https://webtual.com/
Description: Campaign Users.
Version: 1.1.1
Author: Webtual
Author URI: https://webtual.com/
*/
add_action('admin_menu', 'tim_campaign_menu');

function tim_campaign_menu()
{
  $hook = add_menu_page('Campaign Users', 'Campaign Users', 'manage_options', 'campaign-users', 'campaign_users_page', 'dashicons-admin-home',62 );

  add_submenu_page('','Installer Cities', 'Add New', 'manage_options', 'campaign-users-add', 'campaign_users', '',62 );

  add_action( "load-$hook", 'add_options_campaign' );
}

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class campaign_user_Table extends WP_List_Table {
  function get_installers(){
      $s=$_POST['s'];
      global $wpdb;
      $sql = "SELECT * FROM th_campaing_users";
      if($s){
               $sql.=" where first_name LIKE '%".$s."%' OR campaing_name LIKE '%".$s."%' OR vehicle_type LIKE '%".$s."%' OR last_name LIKE '%".$s."%' OR mobile LIKE '%".$s."%' OR email LIKE '%".$s."%'";
            }
        $sql.=" order by id desc";
      $campaign_data = $wpdb->get_results($sql);
            $campaign=array();
            foreach ($campaign_data as $key => $value)
            {
                $campaign[$key]['id'] = $value->id;
                $campaign[$key]['first_name']= $value->first_name;
                $campaign[$key]['last_name']= $value->last_name;
                $campaign[$key]['mobile']= $value->mobile;
                $campaign[$key]['email']= $value->email;
                $campaign[$key]['vehicle_type']= $value->vehicle_type;
                $campaign[$key]['campaing_name']= $value->campaing_name;
                $campaign[$key]['created_at']= $value->created_at;

            }
          return $campaign;
          die;
    }
    function __construct(){
        global $status, $page;
            parent::__construct( array(
                'singular'  => __( 'installer', 'mylisttable' ),     //singular name of the listed records
                'plural'    => __( 'installers', 'mylisttable' ),   //plural name of the listed records
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
        _e( 'No campaign Users found, dude.' );
    }

    function column_default( $item, $column_name ) {
        switch( $column_name ) {
            case 'first_name':
            case 'last_name':
            case 'mobile':
            case 'email':
            case 'vehicle_type':
            case 'campaing_name':
             case 'created_at':
                return $item[ $column_name ];
            default:
                return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
        }
      }

    function get_sortable_columns() {
      $sortable_columns = array(
        'first_name'  => array('first_name',false),
            'last_name'  => array('last_name',false),
            'mobile' => array('mobile',false),
            'email'   => array('email',false),
            'vehicle_type' => array('vehicle_type',false),
            'campaing_name' => array('campaing_name',false),
             'created_at' => array('created_at',false)

      );
      return $sortable_columns;


    }

    function get_columns(){
          $columns = array(
              'cb'        => '<input type="checkbox" />',
              'first_name' => __( 'First name', 'mylisttable' ),
            'last_name' => __( 'Last name', 'mylisttable' ),
            'mobile'    => __('Mobile No', 'mylisttable' ),
            'email'      => __('Email', 'mylisttable' ),
            'vehicle_type'      => __('Vehicle Type', 'mylisttable' ),
            'campaing_name'      => __( 'Campaing Name', 'mylisttable' ),
            'created_at'      => __( 'Created at', 'mylisttable' )

          );
           return $columns;
      }

      function usort_reorder( $a, $b ) {
      // If no sort, default to title
      $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'id';
      // If no order, default to asc
      $order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
      // Determine sort order
      $result = strcmp( $a[$orderby], $b[$orderby] );
      // Send final sort direction to usort
      return ( $order === 'asc' ) ? $result : -$result;
    }

    function column_first_name($item){
      $actions = array(
                //'edit'      => sprintf('<a href="?page=%s&action=%s&city_id=%s">Edit</a>','campaign-users-add','edit',$item['id']),
                'delete'    => sprintf('<a href="?page=%s&action=%s&campaign_id=%s">Delete</a>','campaign-users-add','delete',$item['id']),

        );
      return sprintf('%1$s %2$s', $item['first_name'], $this->row_actions($actions) );
    }

    function get_bulk_actions() {
      $actions = array(
        'delete'    => 'Delete'
      );
      return $actions;
    }

    function column_cb($item) {
          return sprintf(
              '<input type="checkbox" name="installers_city[]" value="%s" />', $item['id']
          );
      }

      function prepare_items() {
        $columns  = $this->get_columns();
        $hidden   = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array( $columns, $hidden, $sortable );
        $data=$this->get_installers();

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


}

function add_options_campaign() {
  global $campaignUsersTable;
  $option = 'per_page';
  $args = array(
         'label' => 'campaign',
         'default' => 10,
         'option' => 'campaign_per_page'
         );
  add_screen_option( $option, $args );
  $campaignUsersTable = new campaign_user_Table();
}

function campaign_users_page(){
  global $campaignUsersTable;

  ?>
   <div class="wrap"><h2>Campaign Users Management <!-- <a href="?page=campaign-users-add&action=add" class="page-title-action">Add New City</a> -->
  <div class="admin-url" hidden=""><?php echo admin_url('admin-ajax.php'); ?></div></h2></div>

   <?php $campaignUsersTable->prepare_items(); ?>

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

      <input type="hidden" name="page" value="installer-manage">
    <?php
      $campaignUsersTable->search_box( 'search', 'search_id' );
      $campaignUsersTable->display();
  ?>
  </form></div>

  <?php

}

function campaign_users (){
  ?>
  <div class="wrap">
    <h2>Edit Campaign User</h2>
    <?php
    global $wpdb, $woocommerce;
    $campaign_id = $_GET['campaign_id'];

    if($_GET['action'] == 'delete'){
      $wpdb->get_results("DELETE from th_campaing_users WHERE id = '$campaign_id'");
      wp_redirect('?page=campaign-users');
    }

    $sql = "SELECT * FROM th_city where id = '$campaign_id'";
      $city_data = $wpdb->get_results($sql);
      foreach ($city_data as $data) {
        $city_name = $data->city_name;
        $std_code = $data->std_code;
      }
    ?>
    <form method="post" action="">
      <table class="form-table">
        <tr>
          <td>City Name</td>
          <td><input type="text" name="city_name" value="<?php echo $city_name; ?>"></td>
        </tr>
        <tr>
          <td>STD Code</td>
          <td><input type="text" name="std_code" value="<?php echo $std_code; ?>"></td>
        </tr>
        <tr>
          <td><input type="submit" name="save_city" value="Save City"></td>
        </tr>
      </table>
    </form>

    <?php
    if(isset($_POST['save_city'])){
      $city_name = $_POST['city_name'];
      $std_code = $_POST['std_code'];
      if($_GET['action'] == 'add'){

        $insert = $wpdb->insert('th_city', array(
                                        'city_name' => $city_name,
                                        'std_code' => $std_code,
                                        ));
      }
      elseif ($_GET['action'] == 'edit') {
        $city_id = $_GET['city_id'];


        $update_service = $wpdb->get_results("UPDATE th_city set city_name = '$city_name', std_code = '$std_code' WHERE city_id = '$city_id' ");
      }

      wp_redirect('?page=campaign-users');
    }
    ?>
  </div>
  <?php

}
?>