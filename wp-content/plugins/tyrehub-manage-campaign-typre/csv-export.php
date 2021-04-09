<?php
require_once("../../../wp-load.php");
   global $wpdb;
   $chk_array1 = $_GET['flat'];
   if($chk_array1){
   	$chk_array2 =$chk_array1;
	}
         	$Filename = "Example_MySQL_Export_";
            $sql = "SELECT * FROM th_campaing_users";
           if(isset($_GET['flat']) && !empty($_GET['flat']))
	         {
	         	 $sql.=" where id IN (".$chk_array2.")";

	         }
            $sql.=" order by id desc";

            $res_data = $wpdb->get_results($sql);

            if(!empty($res_data)){

			   $delimiter = ",";
			    $filename = "campaing_users_" . date('Y-m-d') . ".csv";
			    //create a file pointer
			   $fh = @fopen( 'php://output', 'w' );

			    //set column headers
			    $fields = array('ID', 'Firstname', 'Lastname', 'Mobile', 'Email', 'Vehicle Type','Campaing Name','Created Date');
			    fputcsv($fh, $fields, $delimiter);

			    //output each row of the data, format line as csv and write to file pointer
			    $filename = $sitename . '_product.' . date( 'Y-m-d-H-i-s' ) . '.csv';
				header("Content-type: application/force-download");
	           	header( 'Content-Description: File Transfer' );
				header( 'Content-Disposition: attachment; filename=' . $filename );
				header( 'Content-Type: text/csv; charset=' . get_option( 'blog_charset' ), true );

			    foreach ($res_data as $key => $value) {
			    	$type=str_replace(",","-",$value->vehicle_type);
			        $lineData = array(
					        	$value->id,
					        	$value->first_name,
					        	$value->last_name,
					        	$value->mobile,
					        	$value->email,
					        	$type,
					        	$value->campaing_name,
					        	$value->created_at
					        );
			       fputcsv($fh, $lineData, $delimiter);

			   	 }
			   	 fclose( $fh );
   				 ob_end_flush();



			exit;


			}



?>