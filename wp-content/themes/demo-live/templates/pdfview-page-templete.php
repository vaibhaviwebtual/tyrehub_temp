<?php
/* Template Name: PDF View */
get_header();
error_reporting(0);
?>
<div class="container">
<div style="margin-top: 10px;"><a href="<?=site_url();?>" class="btn btn-invert">Back to Home</a>&nbsp;&nbsp;<a href="<?=site_url('/offline-order-history/');?>" class="btn btn-invert">Back to Orders</a></div>

<?php 

	$document_type = sanitize_text_field( $_GET['document_type'] );
    $download = sanitize_text_field( $_GET['download'] );
    $local_order=$_GET['order_ids'];
 	$order_ids = (array) array_map( 'absint', explode( 'x', $local_order ) );
    $order_ids = array_reverse( $order_ids );

   	$upload_dir = wp_upload_dir();                        
    $upload_base = trailingslashit($upload_dir['basedir'] );
    $tmp_base = $upload_base.'wpo_wcpdf/attachments/';

if($document_type=='offline-invoice'){
	$filename=$document_type.'-'.$local_order.'.pdf';
	$document = wcpdf_get_document( $document_type, $order_ids, true );
	$output_mode ='inline';
    

    $tmp_path = $tmp_base;
    // get pdf data & store
    $pdf_data = $document->get_pdf();
    $filename = $filename;
    $pdf_path =  $tmp_path.$filename;
    file_put_contents($pdf_path,$pdf_data);
    
    //$document->output_offline_pdf($output_mode,$filename);
	
}elseif($document_type=='invoice'){
	$document = wcpdf_get_document( $document_type, $order_ids, true );
	//$output_mode ='inline';
	//$document->output_pdf( $output_mode, $document_type);
	//$pdf_data = $document->get_pdf();
	$filename = $document->get_filename();

	
}

$pdf_url=$upload_dir['baseurl'].'/wpo_wcpdf/attachments/'.$filename;
 echo do_shortcode('[pdfviewer]'.$pdf_url.'[/pdfviewer]');
?>
</div>
<?php
get_footer();
?>