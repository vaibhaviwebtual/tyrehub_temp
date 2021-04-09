<?php
/**
 * Template Name: Download PDF
 *
 * @package Easy_Commerce
 */

/*$order_id=$_GET['order_id'];
$voucher_pdfname='invoice-voucher-'.$order_id.'.pdf';

$big_voucher_url='https://dev2.tyrehub.com/download/?filename='.$voucher_pdfname;
//test it out!
$vouc_short_url = get_short_url($big_voucher_url);

$invoice=get_post_meta($order_id,'_wcpdf_invoice_number',true);
$invo_pdfname='invoice-'.$invoice.'.pdf';
$big_invo_url='https://dev2.tyrehub.com/download/?filename='.$invo_pdfname;
//test it out!
$invo_short_url = get_short_url($big_invo_url);


$customer_pdf="Service Voucher for your  order number ".$order_id." has been generated, your voucher download click here ".$vouc_short_url." .";

$customer_pdf = trim(preg_replace('/\s+/', ' ', $customer_pdf));

$ch123 = curl_init();
$customer_pdf = str_replace(' ', '%20', $customer_pdf);
$url_string="http://nimbusit.co.in/api/swsend.asp?username=t1ankitshah&password=81957032&sender=TYREHB&sendto=919909225311&message=".$customer_pdf;
curl_setopt($ch123, CURLOPT_URL, $url_string); 
curl_setopt($ch123, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch123, CURLOPT_CUSTOMREQUEST, "GET");
$result1 = curl_exec($ch123);
// var_dump($result1);
curl_close ($ch123);
die;*/

$uploads = wp_upload_dir();


$pdfname=$_GET['filename'];
$file=$uploads['basedir'].'/wpo_wcpdf/attachments/'.$pdfname;

if (file_exists($file))
{

    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header("Content-Type: application/force-download");
    header('Content-Disposition: attachment; filename=' . urlencode(basename($file)));
    // header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    ob_clean();
    flush();
    readfile($file);
    exit;
}


 ?>