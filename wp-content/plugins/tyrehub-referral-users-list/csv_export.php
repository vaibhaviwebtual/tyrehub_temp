<?php
require_once("../../../wp-load.php");

if($_GET['action']=='export'){
			
			global $wpdb, $woocommerce;
$SQL="SELECT wp_users.*
FROM wp_users INNER JOIN wp_usermeta 
ON wp_users.ID = wp_usermeta.user_id";
$where=" WHERE 1=1";
if($_GET['start_date']!='' && $_GET['end_date']!=''){

  $where .=" AND DATE(wp_users.user_registered) BETWEEN '".$_GET['start_date']."' AND '".$_GET['end_date']."'";
}
$where .=" AND wp_usermeta.meta_key = 'wp_capabilities' 
AND wp_usermeta.meta_value LIKE '%customer%' 
ORDER BY wp_users.ID DESC";
$SQL .= $where;

$results=$wpdb->get_results($SQL);

    if(count($results) > 0){
       $delimiter = ",";
        $filename = "customers_" . date('Y-m-d') . ".csv";               
        //create a file pointer
       $fh = @fopen( 'php://output', 'w' );
        
        //set column headers
        $fields = array('ID', 'Username/Mobile','Customer', 'Email', 'Referral Name','Referral Type', 'Register Date');
        fputcsv($fh, $fields, $delimiter);
        
        //output each row of the data, format line as csv and write to file pointer
        //$filename = $sitename . '_product.' . date( 'Y-m-d-H-i-s' ) . '.csv';
        header("Content-type: application/force-download");
        header( 'Content-Description: File Transfer' );
        header( 'Content-Disposition: attachment; filename=' . $filename );
        header( 'Content-Type: text/csv; charset=' . get_option( 'blog_charset' ), true );

        foreach ($results as $key => $customer) {
            /*$result[$key]['ID']=$customer->ID;
            $result[$key]['username']=$customer->user_login;
            $result[$key]['name']=$customer->display_name;
            $result[$key]['email']=$customer->user_email;*/
            $user_id=get_user_meta($customer->ID,'franchise_id',true);
            if($user_id!=''){
            $SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."'";
            $franchiseData=$wpdb->get_row($SQL);
            
            if($referral_type=='organic' || ($referral_type=='' && $user_id==0)){
              $referral_type='Tyrehub';
            }else{
              if($franchiseData->is_franchise=='yes'){
                $referral_type='Franchise';
              }else{
                $referral_type='Installer';
              }
            } 
          }else{
            $referral_type='Tyrehub';
          }

            $user_meta = get_userdata($user_id);
            /*$result[$key]['referral_name']=$user_meta->display_name;
            $result[$key]['referral_type']=$referral_type;      
            $result[$key]['register_date']=$customer->user_registered;*/

            $lineData = array(
                        $customer->ID,
                        $customer->user_login,
                        $customer->display_name,
                        $customer->user_email,
                        $user_meta->display_name,
                        $referral_type,
                        date('d-m-Y',strtotime($customer->user_registered))                        
                    );
           fputcsv($fh, $lineData, $delimiter);

         }
         fclose( $fh );    
         ob_end_flush();
           
    }
    exit;

	}
				
			    
?>