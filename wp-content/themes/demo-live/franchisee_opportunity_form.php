<?php
function franchisee_opportunity() {
	ob_start();
	//echo admin_url('admin-ajax.php');
	?>
<script type="text/javascript">
jQuery(document).ready(function($) {
	 jQuery('#send').click(function(){
		jQuery('#frm-franchisee').validate({
		  rules: {
			youname: {  required: true },
			email: {
			  required: true,
			  email: true,
			},
			mobile: {
			   required: true,
			   number:true,
			   minlength:10,
			   maxlength:10

			 }

		  },
		  messages: {
			youname: { required: "Please enter the name" },
			mobile: { required: "Please enter valid contact number",
			 maxlength: jQuery.validator.format("Please enter max 10 number"),
			 minlength: jQuery.validator.format("Please enter min 10 number"),
			 number: jQuery.validator.format("Please enter valid  number"),

			  },
			email: { required: "Please enter Email Address" }
		   },
		  submitHandler: function(form) {
				$('#cover-spin').show(0);
				  var admin_url=jQuery('.admin_url').text();
				   var fullname=jQuery('#youname').val();
				  var email=jQuery('#email').val();
				  var mobile=jQuery('#mobile').val();
				  var calltime=jQuery('#calltime').val();
				  var yourprofession=jQuery('#yourprofession').val();
				  var message=jQuery('#message').val();

						jQuery.ajax({
						type: "POST",
						url:'<?php echo admin_url( 'admin-ajax.php' );?> ',
						data: {
							'action':'custom_action',
							'fullname' : fullname,
							'email' : email,
							'mobile' : mobile,
							'calltime' : calltime,
							'yourprofession' : yourprofession,
							'message' : message,
						},
						success: function (data)
						{

							jQuery('#frm-franchisee').hide();
							jQuery('#franchisee-otp').show();


							$('#cover-spin').hide(0);
						},
					});

				  }
			});

	});


	jQuery('#verify').click(function(){
		jQuery('#otp-franchisee').validate({
		  rules: {
			otp: {  required: true },


		  },
		  messages: {
			youname: { required: "Please enter the OTP" },
		   },
		  submitHandler: function(form) {
				$('#cover-spin').show(0);
				  var admin_url=jQuery('.admin_url').text();
				   var otp=jQuery('#otp').val();


						jQuery.ajax({
						type: "POST",
						url:'<?php echo admin_url( 'admin-ajax.php' );?> ',
						data: {
							'action':'verify_otp_action',
							'otp' : otp,
						},
						success: function (data)
						{
							if(data==1){
								$('#otp').removeClass('error');
								jQuery('#franchisee-otp').hide();
								jQuery('#franchisee-frm').show();
								jQuery('#frm-franchisee').show();

								$("<p style='color:green;'>Thank you for getting in touch! We appreciate your interest for franchise with Tyre Hub. Our team will contact you soon. Have a great day!</p>").insertBefore("#franchisee-frm");

								 $("#frm-franchisee")[0].reset();
								 $("#otp-franchisee")[0].reset();
							}else{
								$('#otp').addClass('error');
								$("<p>Your OTP is not valid!</p>").insertAfter("#otp");

							}

							$('#cover-spin').hide(0);
						},
					});

				  }
			});

	});
});
</script>

<div id="franchisee-frm">
<form class="frm-franchisee" id="frm-franchisee" action="" method="post">
<div class="row">
	<div class="col-sm-6">
		<input type="text" name="youname" id="youname" placeholder="Name*" value="">
	</div>
	<div class="col-sm-6">
		<input type="text" name="email" id="email" value="" placeholder="Email*">
	</div>
</div>
<div class="row">
	<div class="col-sm-6">
		<input type="text" name="mobile" id="mobile" value="" placeholder="Contact Number*">
	</div>
	<div class="col-sm-6">
		<input type="text" name="calltime" id="calltime" value="" placeholder="call Time">
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<input type="text" name="yourprofession" id="yourprofession" value="" placeholder="Tell us your current Profession">
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<textarea cols="3" placeholder="Your Message" name="message" id="message"></textarea>
	</div>
</div>
<input type="hidden" name="action" value="custom_action">

<?php wp_nonce_field( 'custom_action_nonce', 'name_of_nonce_field' ); ?>
<div class="row">
	<div class="col-sm-12">
		<input type="submit" name="send" class="wpcf7-form-control wpcf7-submit btn" id="send" value="Send">
	</div>
</div>
</form>
</div>
<div id="franchisee-otp" style="display: none;">
	<form class="otp-franchisee" id="otp-franchisee" action="" method="post">
	<div class="row">
		<div class="col-sm-12">
			<input type="text" name="otp" id="otp" placeholder="OTP*" class="otp" value="">
		</div>

	</div>


	<?php wp_nonce_field( 'custom_action_nonce', 'name_of_nonce_field' ); ?>
	<div class="row">
		<div class="col-sm-12">
			<input type="submit" name="verify" class="wpcf7-form-control wpcf7-submit btn" id="verify" value="Verify">
		</div>
	</div>
	</form>
</div>
<?php return ob_get_clean(); }
add_shortcode('franchisee_form', 'franchisee_opportunity');

add_action( 'wp_ajax_custom_action', 'custom_action' );
add_action( 'wp_ajax_nopriv_custom_action', 'custom_action' );
function custom_action() {
	global $woocommerce , $wpdb;
	extract($_POST);
    $otp = rand(100000,999999);
    $data = array('fullname' => $fullname,
    	'calltime' =>$calltime,
    	'mobile' =>$mobile,
    	'email' =>$email,
    	'yourprofession' =>$yourprofession,
    	'message' =>$message,
    	'otp' =>$otp,
    	'is_verify' =>0,
    );
	$wpdb->insert('th_franchise_data',$data);
	$my_id = $wpdb->insert_id;

    $ch1 = curl_init();
    $message = "You have receive your request for franchise inquiry your otp is ".$otp." Thank You Tyrehub Team";
    $message = str_replace(' ', '%20', $message);
    $url_string="http://nimbusit.co.in/api/swsend.asp?username=t1ankitshah&password=81957032&sender=TYREHB&sendto=91".$mobile."&message=".$message;
    curl_setopt($ch1, CURLOPT_URL, $url_string);
    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch1, CURLOPT_CUSTOMREQUEST, "GET");
    $result1 = curl_exec($ch1);
    curl_close ($ch1);
	die;
    //$update = $wpdb->get_results("UPDATE `th_franchise_data` SET otp = '$otp' WHERE ID = '$user_id'");

}

add_action( 'wp_ajax_verify_otp_action', 'verify_otp_action' );
add_action( 'wp_ajax_nopriv_verify_otp_action', 'verify_otp_action' );
function verify_otp_action() {

	global $woocommerce , $wpdb;
	extract($_POST);
	$SQL="SELECT * FROM th_franchise_data WHERE otp='".$otp."'";
	$chk=$wpdb->get_row($SQL);
	$customer_email = $chk->email;
	if($chk->id>0){

		$update = $wpdb->query("UPDATE `th_franchise_data` SET is_verify =1  WHERE otp = '$otp'");

	    $ch1 = curl_init();
	    $message = "Thank you for getting in touch! We appreciate your interest for franchise with Tyre Hub. Our team will contact you soon. Have a great day!";
	    $message = str_replace(' ', '%20', $message);
	    $url_string="http://nimbusit.co.in/api/swsend.asp?username=t1ankitshah&password=81957032&sender=TYREHB&sendto=91".$chk->mobile."&message=".$message;
	    curl_setopt($ch1, CURLOPT_URL, $url_string);
	    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch1, CURLOPT_CUSTOMREQUEST, "GET");
	    $result1 = curl_exec($ch1);
	    //var_dump($result1);
	    curl_close ($ch1);


		$to = "hitesh@tyrehub.com,pdixit@webtual.com,pdixit@tyrehub.com,ashah@webtual.com,srathod@webtual.com,savaji.webtual@gmail.com";
		$from = 'sales@tyrehub.com';
		$subject = 'Tyrehub Franchisee Opportunity';
	    $headers[] = 'From: Tyrehub Franchise Inquiry <'.$from.'>';
	    $headers[] = 'Content-Type: text/html; charset=UTF-8';
	    // Compose a simple HTML email message
		$message = '';
		$message .= '<table>';
		$message .= '<tr>
							<td>Name :</td>
							<td>'.$chk->fullname.'</td>
						</tr>';
		$message .= '<tr>
							<td>Email :</td>
							<td>'.$chk->email.'</td>
						</tr>';
		$message .= '<tr>
							<td>Contact Number :</td>
							<td>'.$chk->mobile.'</td>
						</tr>';
		$message .= '<tr>
							<td>Call Time :</td>
							<td>'.$chk->calltime.'</td>
						</tr>';
			$message .= '<tr>
							<td>Current Profession :</td>
							<td>'.$chk->yourprofession.'</td>
						</tr>';
			$message .= '<tr>
							<td>Message :</td>
							<td>'.$chk->message.'</td>
						</tr>';
		$message .= '</table>';

		wp_mail( $to, $subject, $message, $headers);



		$to1 = $customer_email;
		$from1 = 'sales@tyrehub.com';
		$subject1 = 'Tyrehub Franchisee Opportunity';
	    $headers1[] = 'From: Tyrehub Franchise Inquiry <'.$from.'>';
	    $headers1[] = 'Content-Type: text/html; charset=UTF-8';
	    // Compose a simple HTML email message
		$message1 = 'Thank you for getting in touch! We appreciate your interest for franchise with Tyre Hub. Our team will contact you soon. Have a great day!';

		wp_mail( $to1, $subject1, $message1, $headers1);

		

	    $flag=1;
	}else{
		$flag=0;
	}
	echo $flag;
    die;
}