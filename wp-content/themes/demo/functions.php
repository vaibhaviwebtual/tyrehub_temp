<?php
function sess_start() {
	if (!session_id())
	session_start();
}
add_action('init','sess_start');
require_once("paytm/config_paytm.php");
require_once("paytm/encdec_paytm.php");
include_once('main-breadcrumb.php');
include_once('franchise-function.php');
/**
 * Theme functions and definitions.
 *
 * @link https://codex.wordpress.org/Functions_File_Explained
 *
 * @package Easy_Commerce
 */
if ( ! function_exists( 'easy_commerce_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function easy_commerce_setup() {
		// Make theme available for translation.
		load_theme_textdomain( 'easy-commerce' );
		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );
		// Let WordPress manage the document title.
		add_theme_support( 'title-tag' );
		// Enable support for Post Thumbnails on posts and pages.
		add_theme_support( 'post-thumbnails' );
		add_image_size( 'easy-commerce-slider', 900, 500, true );
		// This theme uses wp_nav_menu() in four location.
		register_nav_menus( array(
			'primary'  => esc_html__( 'Primary Menu', 'easy-commerce' ),
			'footer'   => esc_html__( 'Footer Menu', 'easy-commerce' ),
			'social'   => esc_html__( 'Social Menu', 'easy-commerce' ),
			'notfound' => esc_html__( '404 Menu', 'easy-commerce' ),
		) );
		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		/*add_theme_support( 'html5', array(
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );*/
		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'easy_commerce_custom_background_args', array(
			'default-color' => 'FFFFFF',
			'default-image' => '',
		) ) );
		// Enable support for selective refresh of widgets in Customizer.
	//	add_theme_support( 'customize-selective-refresh-widgets' );
		// Enable support for custom logo.
	//	add_theme_support( 'custom-logo' );
		// Enable support for footer widgets.
	//	add_theme_support( 'footer-widgets', 4 );
		// Add WooCommerce Support.
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
		// Load Supports.
	//	require_once trailingslashit( get_template_directory() ) . 'inc/support.php';
	}
endif;
add_action( 'after_setup_theme', 'easy_commerce_setup' );
function easy_commerce_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'easy_commerce_content_width',640);
}
add_action( 'after_setup_theme', 'easy_commerce_content_width', 0 );
/**
 * Load init.
 */
/////////////////////////////////////////////////////////////////////
$capabilities =  array(
	   // 'read'              => true, // Allows a user to read
	   // 'create_posts'      => true, // Allows user to create new posts
	   // 'edit_posts'        => true, // Allows user to edit their own posts
	   // 'edit_others_posts' => true, // Allows user to edit others posts too
	   // 'publish_posts'     => true, // Allows the user to publish posts
	   // 'manage_categories' => true, // Allows user to manage post categories
		);
add_role( 'Installer', 'Installer', $capabilities );
add_action( 'init', 'blockusers_init' );
function blockusers_init() {
	if ( is_admin() && current_user_can( 'Installer' ) && current_user_can( 'supplier' ) && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX )  ) {
	wp_redirect( get_permalink( get_option('woocommerce_myaccount_page_id')).'/service-request/' );
	exit;
	}
}
add_action('init', 'start_session', 1);
function start_session() {
	if(!session_id()) {
		session_start();
	}
	add_action('wp_logout', 'end_session');
	add_action('wp_login', 'end_session');
	add_action('end_session_action', 'end_session');
	function end_session() {
		session_destroy();
		//if( function_exists('WC') ){ WC()->cart->empty_cart(); }
	}
}
add_action('admin_menu', 'tyrehub_theme_menu');
function tyrehub_theme_menu()
{
	add_menu_page('Theme Settings', 'Theme Settings', 'manage_options', 'theme_settings', 'tyrehub_theme_settings' , 'dashicons-art',  61);
}
function tyrehub_theme_settings()
{
	if (isset($_POST["head_email"]))
	{
		if (!empty($_FILES['head_logo']['name']))
		{
			$uploads = wp_upload_dir();
			$upload_path = $uploads['path'];
			$save_path = $uploads['url'].'/'.basename($_FILES["head_logo"]["name"]);
			$target_dir = "uploads/";
			$target_file = $upload_path .'/'. basename($_FILES["head_logo"]["name"]);
			$uploadOk = 1;
			$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			// Check if image file is a actual image or fake image
				$check = getimagesize($_FILES["head_logo"]["tmp_name"]);
				if($check !== false)
				{
				} else {
					echo "Selected Header logo is not an image.";
				}
				if (move_uploaded_file($_FILES["head_logo"]["tmp_name"], $target_file))
				{
				}
				$head_logo = $save_path;
				update_option("head_logo", $head_logo);
		}
		$head_email = esc_attr($_POST["head_email"]);
		$head_add = esc_attr($_POST["head_add"]);
		$phone = esc_attr($_POST["phone"]);
		$facebook = esc_attr($_POST["facebook"]);
		$instagram = esc_attr($_POST["instagram"]);
		$linkin = esc_attr($_POST["linkin"]);
		$twitter = esc_attr($_POST["twitter"]);
		$carwash = esc_attr($_POST["carwash"]);
		$pickup = esc_attr($_POST["pickup"]);
		$towing = esc_attr($_POST["towing"]);
		$car_wash = esc_attr($_POST["car_wash"]);
		$balancing_alignment = esc_attr($_POST["balancing_alignment"]);
			update_option("head_email", $head_email);
			update_option("head_add", $head_add);
			update_option("phone", $phone);
			update_option("facebook", $facebook);
			update_option("instagram", $instagram);
			update_option("linkin", $linkin);
			update_option("twitter", $twitter);
			update_option("carwash", $carwash);
			update_option("pickup", $pickup);
			update_option("towing", $towing);
			update_option("car_wash", $car_wash);
			update_option("balancing_alignment", $balancing_alignment);
			
	}
?>
	<form method="POST" action="" enctype="multipart/form-data">
		<h3>Header Options</h3>
	<table class="form-table">
		<tr>
			<th>Car Wash (Product ID)</th>
			<td>
				<input type="Text" name="car_wash" value="<?php echo get_option("car_wash"); ?>">
			</td>
		</tr>
		<tr>
			<th>Balancing Alignment (Product ID)</th>
			<td>
				<input type="Text" name="balancing_alignment" value="<?php echo get_option("balancing_alignment"); ?>">
			</td>
		</tr>
		<tr>
			<th>Header Logo</th>
			<td>
				<img id="blah" src="<?php echo get_option("head_logo"); ?>" alt="your image" width="180" style="background: #000;" /><br/>
				<input type="file" name="head_logo" onchange="readURL(this);">
			</td>
		</tr>
		<tr>
			<th>Header Email</th>
			<td><input type="Text" name="head_email" value="<?php echo get_option("head_email"); ?>"></td>
		</tr>
		<tr>
			<th>Header Address</th>
			<td>
				<textarea name="head_add"><?php echo get_option("head_add"); ?></textarea>
			</td>
		</tr>
		<tr>
			<th>Header Phone</th>
			<td>
				<textarea name="phone" rows="8"><?php echo get_option("phone"); ?></textarea>
			</td>
		</tr>
	</table>
	<h3>Social Media</h3>
	<table class="form-table">
		<tr>
			<th>Facebook</th>
			<td><input type="Text" placeholder="Add Facebook Url" name="facebook" value="<?php echo get_option("facebook"); ?>"></td>
		</tr>
		<tr>
			<th>Instagram</th>
			<td><input type="Text" placeholder="Add Instagram Url" name="instagram" value="<?php echo get_option("instagram"); ?>"></td>
		</tr>
		<tr>
			<th>Twitter</th>
			<td><input type="Text" placeholder="Add Twitter Url" name="twitter" value="<?php echo get_option("twitter"); ?>"></td>
		</tr>
		<tr>
			<th>Google Plus</th>
			<td><input type="Text" placeholder="Add Google Url" name="linkin" value="<?php echo get_option("linkin"); ?>"></td>
		</tr>
		<tr>
			<td><?php submit_button(); ?></td>
		</tr>
	</table>
	<!-- 	<h3>Serivices Enable/Disable</h3>
		<table class="form-table">
		<tr>
			<th>Car Wash Disable</th>
			<td><input type="checkbox" name="carwash" value="1" <?php if(get_option("carwash")==1){ echo 'checked';} ?>></td>
		</tr>
		<tr>
			<th> Pickup & Drop Off Service Disable</th>
			<td><input type="checkbox" name="pickup" value="1" <?php if(get_option("pickup")==1){ echo 'checked';} ?>></td>
		</tr>
		<tr>
			<th>Towing Disable</th>
			<td><input type="checkbox" name="towing" value="1" <?php if(get_option("towing")==1){ echo 'checked';} ?>></td>
		</tr>
		<tr>
			<td><?php submit_button(); ?></td>
		</tr>
	</table> -->
	</table>
</form>
<?php
}
function wptutsplus_register_theme_menu() {
	register_nav_menu( 'primary', 'Main Navigation Menu' );
}
add_action( 'init', 'wptutsplus_register_theme_menu' );
function tutsplus_widgets_init() {
	// First footer widget area, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'First Footer Widget Area', 'tutsplus' ),
		'id' => 'first-footer-widget-area',
		'description' => __( 'The first footer widget area', 'tutsplus' ),
		'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	// Second Footer Widget Area, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Second Footer Widget Area', 'tutsplus' ),
		'id' => 'second-footer-widget-area',
		'description' => __( 'The second footer widget area', 'tutsplus' ),
		'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	// Third Footer Widget Area, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Third Footer Widget Area', 'tutsplus' ),
		'id' => 'third-footer-widget-area',
		'description' => __( 'The third footer widget area', 'tutsplus' ),
		'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	// Fourth Footer Widget Area, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Fourth Footer Widget Area', 'tutsplus' ),
		'id' => 'fourth-footer-widget-area',
		'description' => __( 'The fourth footer widget area', 'tutsplus' ),
		'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	register_sidebar( array(
		'name' => __( 'Shop Page', 'tutsplus' ),
		'id' => 'shop-page-widget-area',
		'description' => __( 'The shop page widget area', 'tutsplus' ),
		'before_widget' => '<div id="%1$s" class="%2$s side-block widget">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="title-aside">',
		'after_title' => '</h4>',
	) );
	register_sidebar( array(
		'name'          => __( 'Blog Sidebar', 'twentyseventeen' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Add widgets here to appear in your sidebar on blog posts and archive pages.', 'twentyseventeen' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
// Register sidebars by running tutsplus_widgets_init() on the widgets_init hook.
add_action( 'widgets_init', 'tutsplus_widgets_init' );
add_action( 'widgets_init', 'override_woocommerce_widgets', 15 );
function override_woocommerce_widgets() {
  // Ensure our parent class exists to avoid fatal error
	// price filter override
		if ( class_exists( 'WC_Widget_Price_Filter' ) ) {
			unregister_widget( 'WC_Widget_Price_Filter' );
			include_once( trailingslashit( get_template_directory() ).'/custom-widgets/price-filter.php' );
			register_widget( 'custom_Price_Filter' );
		}
	// attribute filter override
		if ( class_exists( 'WC_Widget_Layered_Nav' ) ) {
			unregister_widget( 'WC_Widget_Layered_Nav' );
			include_once( trailingslashit( get_template_directory() ).'/custom-widgets/attribute-filter.php' );
			register_widget( 'custom_Layered_Nav' );
		}
	// categories filter override
		if ( class_exists( 'WC_Widget_Product_Categories' ) ) {
			unregister_widget( 'WC_Widget_Product_Categories' );
			include_once( trailingslashit( get_template_directory() ).'/custom-widgets/categories.php' );
			register_widget( 'custom_Product_Categories' );
		}
}
add_action('template_redirect', 'remove_shop_breadcrumbs' );
function remove_shop_breadcrumbs(){
	  //  remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);
}
add_action('wp_ajax_select_model', 'select_model');
add_action('wp_ajax_nopriv_select_model', 'select_model');
function select_model()
{
	/*if(isset($_SESSION['make_id']))
	{
	   echo $car_company_name = $_SESSION['make_id'];
	}*/
	if(isset($_POST['car_cmp'])){
		$car_company_name = $_POST['car_cmp'];
	}
	global $wpdb;
	if(isset($_POST['model_id'])){
		$mod_id = $_POST['model_id'];
	}else{
		$mod_id = $_SESSION['model_id'];
	}
	$sql = "SELECT * FROM th_model where make_rid = '$car_company_name' AND status=1 order by model_name asc";
	$model_data = $wpdb->get_results($sql);
	?>
	<option value="0" disabled selected>Model</option>
	<?php
	foreach ($model_data as $data) {
		$model_id = $data->model_id;
		$model_name = $data->model_name;
	?>
		<option value="<?php echo $model_id; ?>" <?php if($mod_id == $model_id ){ echo 'selected'; }?>><?php echo $model_name; ?></option>
<?php }
	die;
}
add_action('wp_ajax_select_sub_modal', 'select_sub_modal');
add_action('wp_ajax_nopriv_select_sub_modal', 'select_sub_modal');
function select_sub_modal()
{
	$model = $_POST['model'];
	/* if(isset($_SESSION['model_id']))
	{
		$model = $_SESSION['model_id'];
	}*/
	if(isset($_POST['model'])){
		$model = $_POST['model'];
	}
	if(isset($_POST['sub_model_id'])){
		$smod_id = $_POST['sub_model_id'];
	}else{
		$smod_id = $_SESSION['sub_model_id'];
	}
	global $wpdb;
	$sql = "SELECT * FROM th_submodel where model_rid = '$model' AND status=1 GROUP BY(submodel_name)";
	$submodel_data = $wpdb->get_results($sql);
?>
	<option value="0" disabled selected>Sub Model</option>
	<?php
	foreach ($submodel_data as $data) {
		$submodel_name = $data->submodel_name;
		$submodel_id = $data->submodel_id;
	?>
		<option value="<?php echo $submodel_id; ?>" <?php if($smod_id == $submodel_id ){ echo 'selected'; }?> ><?php echo $submodel_name; ?></option>
<?php }
	die;
}
add_action('wp_ajax_get_data_by_submodel', 'get_data_by_submodel');
add_action('wp_ajax_nopriv_get_data_by_submodel', 'get_data_by_submodel');
function get_data_by_submodel()
{
	session_start();
	$sub_modal = $_POST['sub_modal'];
	$modal = $_POST['modal'];
	$make = $_POST['make'];
	$vehicle_type = $_POST['vehicle_type'];
	$_SESSION['model_id'] = $modal;
	$_SESSION['make_id'] = $make;
	$_SESSION['sub_model_id'] = $sub_modal;
	$_SESSION['vehicle_id'] = $vehicle_type;
	$_SESSION['vehicle_type']=$vehicle_type;

	
	global $wpdb;
	$response = [];
	$SQL = "SELECT * FROM th_submodel where submodel_id = '$sub_modal' AND status=1";
	/*$SQL="SELECT w.width_value,r.ratio_value,d.diameter_value,sm.submodel_name FROM th_submodel as sm LEFT JOIN th_width as w ON w.width_id=sm.width_rid LEFT JOIN th_ratio as r ON r.ratio_id=sm.ratio_rid LEFT JOIN th_diameter as d ON d.diameter_id=sm.diameter_rid WHERE sm.submodel_id = '".$sub_modal."' AND sm.status=1";*/
		//$submodel_data = $wpdb->get_results($SQL);
		$submodel_data = $wpdb->get_row($SQL);
		$tyre_size=explode('-',$submodel_data->tyre_size);
		$width_value = $tyre_size[0];
		$ratio_value = $tyre_size[1];
		$diameter_value = $tyre_size[2];
		$submodel_name = generateSeoURL($submodel_data->submodel_name);
		$response['width'] = str_replace('.','-',$width_value);
		$response['ratio'] = $ratio_value;
		$response['diameter'] = $diameter_value;
		$response['vehicle_type'] = $vehicle_type;
		$response['submodel_name'] = $submodel_name;
	$response = json_encode($response);
	echo $response;
	die();
}
add_action('wp_ajax_select_ratio', 'select_ratio');
add_action('wp_ajax_nopriv_select_ratio', 'select_ratio');
function select_ratio()
{
	$width = $_POST['width'];
	global $wpdb;
	$sql = "SELECT * FROM th_ratio where width_rid = '$width'";
	$ratio_data = $wpdb->get_results($sql);
	$ratio_arr = [];
	?>
		<option value="0" disabled selected>Ratio/Profile</option>
	<?php
	foreach ($ratio_data as $data)
	{
	   // $ratio_arr[] = $data->aspect_ratio;
		$ratio_id = $data->ratio_id;
		$ratio_value = $data->ratio_value;
	?>
	<option value="<?php echo $ratio_id; ?>" <?php if($ratio_value == 0){ echo 'selected'; }?> ><?php if($ratio_value == 0){ echo 'R'; }else{ echo $ratio_value; }?></option>
	<?php
	}
	die;
}
add_action('wp_ajax_select_diameter', 'select_diameter');
add_action('wp_ajax_nopriv_select_diameter', 'select_diameter');
function select_diameter()
{
	$ratio_id = $_POST['ratio'];
	$width = $_POST['width'];
	global $wpdb;
	$sql = "SELECT * FROM th_diameter where ratio_rid = '$ratio_id'";
	$diameter_data = $wpdb->get_results($sql);
	$diameter_arr = [];
?>
		<option value="0" disabled selected>Rim Diameter
</option>
	<?php
	foreach ($diameter_data as $data)
	{
		$diameter_id = $data->diameter_id;
		$diameter_value = $data->diameter_value;
?>
		<option value="<?php echo $diameter_id; ?>"><?php echo $diameter_value; ?></option>
<?php
	}
	die;
}
add_action('admin_enqueue_scripts','theme_settings_script');
add_action('wp_enqueue_scripts', 'theme_settings_script');
function theme_settings_script($hook_suffix){
	if($hook_suffix == 'woocommerce_page_promotions_voucher' || $_GET['page']=='promotions_voucher')
	{
		wp_enqueue_script('tyrehub_custom_barcode', get_template_directory_uri().'/assest/js/plugins/jquery.qrcode.min.js', array('jquery'));
		wp_enqueue_script('tyrehub_custom_barcode1', get_template_directory_uri().'/assest/js/plugins/fileinput.min.js', array('jquery'));
		wp_enqueue_script('tyrehub_custom_barcode128', get_template_directory_uri().'/assest/js/barcode/grid.js', array('jquery'));
		wp_enqueue_script('tyrehub_custom_barcode1788', get_template_directory_uri().'/assest/js/barcode/version.js', array('jquery'));
		wp_enqueue_script('tyrehub_custom_barcode152', get_template_directory_uri().'/assest/js/barcode/detector.js', array('jquery'));
		wp_enqueue_script('tyrehub_custom_barcode125', get_template_directory_uri().'/assest/js/barcode/formatinf.js', array('jquery'));
		wp_enqueue_script('tyrehub_custom_barcode163', get_template_directory_uri().'/assest/js/barcode/errorlevel.js', array('jquery'));
		wp_enqueue_script('tyrehub_custom_barcode3', get_template_directory_uri().'/assest/js/barcode/bitmat.js', array('jquery'));
		wp_enqueue_script('tyrehub_custom_barcode144', get_template_directory_uri().'/assest/js/barcode/datablock.js', array('jquery'));
		wp_enqueue_script('tyrehub_custom_barcode345', get_template_directory_uri().'/assest/js/barcode/bmparser.js', array('jquery'));
		wp_enqueue_script('tyrehub_custom_barcode166', get_template_directory_uri().'/assest/js/barcode/datamask.js', array('jquery'));
		wp_enqueue_script('tyrehub_custom_barcode16999', get_template_directory_uri().'/assest/js/barcode/rsdecoder.js', array('jquery'));
		wp_enqueue_script('tyrehub_custom_barcode127', get_template_directory_uri().'/assest/js/barcode/gf256poly.js', array('jquery'));
		wp_enqueue_script('tyrehub_custom_barcode126', get_template_directory_uri().'/assest/js/barcode/gf256.js', array('jquery'));
		wp_enqueue_script('tyrehub_custom_barcode174', get_template_directory_uri().'/assest/js/barcode/decoder.js', array('jquery'));
		wp_enqueue_script('tyrehub_custom_barcode12799', get_template_directory_uri().'/assest/js/barcode/qrcode.js', array('jquery'));
		wp_enqueue_script('tyrehub_custom_barcode1555', get_template_directory_uri().'/assest/js/barcode/findpat.js', array('jquery'));
		wp_enqueue_script('tyrehub_custom_barcode2', get_template_directory_uri().'/assest/js/barcode/alignpat.js', array('jquery'));
		wp_enqueue_script('tyrehub_custom_barcode155', get_template_directory_uri().'/assest/js/barcode/databr.js', array('jquery'));
		wp_enqueue_script('tyrehub_custom_barcode14555', get_template_directory_uri().'/assest/js/barcode/rotate.js', array('jquery'));
	   // wp_enqueue_script('promotion_voucher_js', get_template_directory_uri().'/assest/js/plugins/bootstrap.min.js', array('jquery'));
	   // wp_enqueue_style('promotion_voucher_css',  get_template_directory_uri().'/assest/css/plugins/bootstrap.min.css', __FILE__);
	}
}
function my_assets() {
	$ver = rand(111,999);
	if(is_cart()){
		 wp_enqueue_script('tyrehub_cartjs', get_template_directory_uri().'/assest/js/cart.js', array('jquery') , $ver);
	}else{
		wp_enqueue_script('tyrehub_custom_barcode', get_template_directory_uri().'/assest/js/plugins/jquery.qrcode.min.js', array('jquery'));
		wp_enqueue_script('tyrehub_custom_barcode1', get_template_directory_uri().'/assest/js/plugins/fileinput.min.js', array('jquery'));
		wp_enqueue_script('tyrehub_custom_barcode128', get_template_directory_uri().'/assest/js/barcode/grid.js', array('jquery'));
		wp_enqueue_script('tyrehub_custom_barcode1788', get_template_directory_uri().'/assest/js/barcode/version.js', array('jquery'));
		wp_enqueue_script('tyrehub_custom_barcode152', get_template_directory_uri().'/assest/js/barcode/detector.js', array('jquery'));
		wp_enqueue_script('tyrehub_custom_barcode125', get_template_directory_uri().'/assest/js/barcode/formatinf.js', array('jquery'));
		wp_enqueue_script('tyrehub_custom_barcode163', get_template_directory_uri().'/assest/js/barcode/errorlevel.js', array('jquery'));
		wp_enqueue_script('tyrehub_custom_barcode3', get_template_directory_uri().'/assest/js/barcode/bitmat.js', array('jquery'));
		wp_enqueue_script('tyrehub_custom_barcode144', get_template_directory_uri().'/assest/js/barcode/datablock.js', array('jquery'));
		wp_enqueue_script('tyrehub_custom_barcode345', get_template_directory_uri().'/assest/js/barcode/bmparser.js', array('jquery'));
		wp_enqueue_script('tyrehub_custom_barcode166', get_template_directory_uri().'/assest/js/barcode/datamask.js', array('jquery'));
		wp_enqueue_script('tyrehub_custom_barcode16999', get_template_directory_uri().'/assest/js/barcode/rsdecoder.js', array('jquery'));
		wp_enqueue_script('tyrehub_custom_barcode127', get_template_directory_uri().'/assest/js/barcode/gf256poly.js', array('jquery'));
		wp_enqueue_script('tyrehub_custom_barcode126', get_template_directory_uri().'/assest/js/barcode/gf256.js', array('jquery'));
		wp_enqueue_script('tyrehub_custom_barcode174', get_template_directory_uri().'/assest/js/barcode/decoder.js', array('jquery'));
		wp_enqueue_script('tyrehub_custom_barcode12799', get_template_directory_uri().'/assest/js/barcode/qrcode.js', array('jquery'));
		wp_enqueue_script('tyrehub_custom_barcode1555', get_template_directory_uri().'/assest/js/barcode/findpat.js', array('jquery'));
		wp_enqueue_script('tyrehub_custom_barcode2', get_template_directory_uri().'/assest/js/barcode/alignpat.js', array('jquery'));
		wp_enqueue_script('tyrehub_custom_barcode155', get_template_directory_uri().'/assest/js/barcode/databr.js', array('jquery'));
		wp_enqueue_script('tyrehub_custom_barcode14555', get_template_directory_uri().'/assest/js/barcode/rotate.js', array('jquery'));
		wp_enqueue_script('tyrehub_custom', get_template_directory_uri().'/assest/js/custom_tyrehub.js', array('jquery') , $ver);	
		wp_enqueue_script('easy_paginate', get_template_directory_uri().'/assest/js/jquery.easyPaginate.js', array('jquery'));
		wp_enqueue_script('tyrehub_franchies_cartjs', get_template_directory_uri().'/assest/js/franchies_cart.js', array('jquery'),rand(), true );
	}
	wp_enqueue_script('installer_page', get_template_directory_uri().'/assest/js/installer-page.js', array('jquery'),rand(),true);
	wp_enqueue_script('custom js', get_template_directory_uri().'/assest/js/custom.js', array('jquery'),rand(),true);
}
add_action( 'wp_enqueue_scripts', 'my_assets' );
add_action('wp_ajax_select_tyre_by_modal', 'select_tyre_by_modal');
add_action('wp_ajax_nopriv_select_tyre_by_modal', 'select_tyre_by_modal');
function select_tyre_by_modal()
{
	$make = $_POST['make'];
	$model = $_POST['model'];
	$year = $_POST['year'];
	$ch1 = curl_init();
	$url_string="https://api.wheel-size.com/v1/search/by_model/?user_key=ea0799b9071ad3d27518d4880d3db80b&make=".$make."&model=".$model."&year=".$year;
	curl_setopt($ch1, CURLOPT_URL, $url_string);
	curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch1, CURLOPT_CUSTOMREQUEST, "GET");
	$headers = array();
	$headers[] = "Accept: application/json";
	curl_setopt($ch1, CURLOPT_HTTPHEADER, $headers);
	$result1 = curl_exec($ch1);
	$data1 = json_decode($result1);
	if (curl_errno($ch1))
	{
		echo 'Error:' . curl_error($ch1);
	}
	curl_close ($ch1);
	foreach ($data1 as $data) {
		$res = $data->wheels;
		foreach ($res as $datanew)
		{
			$width_arr[] = $datanew->front->tire_width;
			$ratio_arr[] = $datanew->front->tire_aspect_ratio;
			$rim_diameter[] = $datanew->front->rim_diameter;
		}
	}
	$width_arr = array_unique($width_arr);
	$ratio_arr = array_unique($ratio_arr);
	$rim_diameter = array_unique($rim_diameter);
	foreach ($width_arr as $key => $width)
	{
		$final_width_arr[] = $width;
	}
	foreach ($ratio_arr as $key => $ratio)
	{
		$final_ratio_arr[] = $ratio;
	}
	foreach ($rim_diameter as $key => $rim_diameter)
	{
		$final_diameter_arr[] = $rim_diameter;
	}
	$result['width'] = $final_width_arr;
	$result['ratio'] = $final_ratio_arr;
	$result['diameter'] = $final_diameter_arr;
	echo json_encode($result , true);
	die();
}
// update_selected_installer
add_action('wp_ajax_update_selected_installer', 'update_selected_installer');
add_action('wp_ajax_nopriv_update_selected_installer', 'update_selected_installer');
function update_selected_installer()
{
	$installer_id = $_POST['installer_id'];
	$rate = 0;
	global $wpdb;
	$sql = "SELECT * FROM th_installer_data WHERE installer_data_id = $installer_id";
			$row = $wpdb->get_results($sql);
		 foreach ($row as $data)
									{
										?>
										<h4>
												<?php echo $data->business_name; ?>
											</h4>
											<div>
												<?php echo $data->address; ?>
											</div>
											<div>
												<?php echo $data->city.'-'.$data->pincode; ?>
											</div>
											<div>
												<?php echo $data->state; ?>
											</div>
											<div>
												<?php echo $data->contact_no; ?>
											</div>
										<?php
									}
	die();
}
// update_selected_installer
// save_installer_info
add_action('wp_ajax_save_installer_info', 'save_installer_info');
add_action('wp_ajax_nopriv_save_installer_info', 'save_installer_info');
function save_installer_info()
{
	global $woocommerce , $wpdb;
	$installer_id = $_POST['installer_id'];
	$vehicle_id = $_POST['vehicle_id'];
	$cart_item_id = $_POST['cart_item_id'];
	$tyre = $_POST['tyre'];
	$product_id = $_POST['product_id'];
	$session_id = $_POST['session_id'];
	$th_cart_item_installer = 'th_cart_item_installer';
	// delete already save installer
	$delete_service = $wpdb->get_results("DELETE from $th_cart_item_installer WHERE product_id='$product_id' AND cart_item_key = '$cart_item_id' and session_id = '$session_id' and order_id = ''");
	// insert new record
	$SQL="SELECT COUNT(*) FROM $th_cart_item_installer WHERE product_id='$product_id' AND cart_item_key = '$cart_item_id' and session_id = '$session_id' and order_id = ''"; //sales@tyrehub.com
	$rowcount = $wpdb->get_var($SQL);
	if($rowcount<=0){
		$insert = $wpdb->insert($th_cart_item_installer, array(
											'cart_item_key' => $cart_item_id,
											'session_id' => $session_id,
											'product_id' => $product_id,
											'destination' => 1,
											'installer_id' => $installer_id,
											'vehicle_id' => $vehicle_id,
											));
		$last_id = $wpdb->insert_id;
		date_default_timezone_set('Asia/Kolkata');
		$date = date('ymdhis', time());
		//$barcode_string = $date.$product_id.$installer_id.$vehicle_id;
		$barcode_string=barcode_generate();
		$result_arr = array($last_id, $barcode_string);
		echo json_encode($result_arr);
		 $update_service = $wpdb->get_results("UPDATE $th_cart_item_installer set barcode = '$barcode_string' WHERE cart_item_installer_id = '$last_id' ");
	}
	die();
}
// save barcode image
add_action('wp_ajax_save_barcode_img', 'save_barcode_img');
add_action('wp_ajax_nopriv_save_barcode_img', 'save_barcode_img');
function save_barcode_img()
{
	global $wpdb;
	$barcode_img = $_POST['barcode_img'];
	$installer_id = $_POST['installer_id'];
	$update_service = $wpdb->get_results("UPDATE th_cart_item_installer set barcode_img = '$barcode_img' WHERE cart_item_installer_id =".$installer_id);
	die();
}
add_action('wp_ajax_save_voucher_barcode_img', 'save_voucher_barcode_img');
add_action('wp_ajax_nopriv_save_voucher_barcode_img', 'save_voucher_barcode_img');
function save_voucher_barcode_img()
{
	global $wpdb;
	echo $barcode_img = $_POST['barcode_img'];
	echo $installer_id = $_POST['installer_id'];
	$update_service = $wpdb->get_results("UPDATE th_cart_item_service_voucher set barcode_img = '$barcode_img' WHERE service_voucher_id = '$installer_id' ");
	die();
}
// save_service_info
add_action('wp_ajax_save_service_info', 'save_service_info');
add_action('wp_ajax_nopriv_save_service_info', 'save_service_info');
function save_service_info()
{
	global $woocommerce , $wpdb;
	$service_list = $_POST['service_list'];
	$cart_item_id = $_POST['cart_item_id'];
	$tyre_list = $_POST['tyre_list'];
	$service_rate_list = $_POST['service_rate_list'];
	$vehicle_id = $_POST['vehicle_id'];
	$session_id = $_POST['session_id'];
	$product_id = $_POST['product_id'];
	$service_id_list = $_POST['service_id'];
	$th_cart_item_services = 'th_cart_item_services';
	$delete_service = $wpdb->get_results("DELETE from $th_cart_item_services WHERE product_id='$product_id' AND cart_item_key = '$cart_item_id' and session_id = '$session_id' and order_id = ''");
	$SQL="SELECT COUNT(*) FROM $th_cart_item_services WHERE product_id='$product_id' AND cart_item_key = '$cart_item_id' and session_id = '$session_id' and order_id = ''"; //sales@tyrehub.com
	$rowcount = $wpdb->get_var($SQL);
	if($rowcount<=0){

		foreach($service_list as $key => $service)
		{
			$service_arr [] = $service;
		}
		$insert = $wpdb->insert($th_cart_item_services, array(
										   'cart_item_key' => $cart_item_id,
										   'session_id' => $session_id,
										   'product_id' => $product_id,
										   'vehicle_id' => $vehicle_id,
										   'service_data_id' => $service_id_list[0],
											'service_name' => $service_arr[0],
											'tyre' => $tyre_list,
											'rate' =>$service_rate_list[0],
										));
		
	}else{
		foreach($service_list as $key => $service)
		{
			$service_arr [] = $service;
		}
		$data=array(
		   'cart_item_key' => $cart_item_id,
		   'session_id' => $session_id,
		   'product_id' => $product_id,
		   'vehicle_id' => $vehicle_id,
		   'service_data_id' => $service_id_list[0],
			'service_name' => $service_arr[0],
			'tyre' => $tyre_list,
			'rate' =>$service_rate_list[0],
		);
		$where=array('product_id'=>$product_id,'cart_item_key' =>$cart_item_id,'session_id'=>$session_id,'order_id'=>'');
		$wpdb->update('th_cart_item_services',$data,$where);
	}
	$update = $wpdb->get_results("UPDATE th_cart_item_installer SET vehicle_id = '$vehicle_id' WHERE cart_item_key = '$cart_item_id' and session_id = '$session_id' and order_id = ''");

	$services = "SELECT *
				FROM th_cart_item_services
				WHERE cart_item_key = '$cart_item_id' and session_id = '$session_id' and order_id = ''";
	$row = $wpdb->get_results($services);
	$service_list_arr = [];
	foreach ($row as $key => $service)
	{
		$service_name = $service->service_name;
		$service_list_arr[] = $service_name;
		echo '<div>'.$service_name.'</div>';
	}
	die();
}
// save_service_info
add_action('wp_ajax_save_service_info_from_cart', 'save_service_info_from_cart');
add_action('wp_ajax_nopriv_save_service_info_from_cart', 'save_service_info_from_cart');
function save_service_info_from_cart()
{
	
	global $woocommerce , $wpdb;
	$service_name = $_POST['service_name'];
	$cart_item_id = $_POST['cart_item_id'];
	$tyre_count = $_POST['tyre_count'];
	$service_rate = $_POST['service_rate'];
	$vehicle_id = $_POST['vehicle_id'];
	$session_id = $_POST['session_id'];
	$product_id = $_POST['product_id'];
	$service_id = $_POST['service_id'];
	$pic_address = $_POST['pic_address'];
	$cart_total = $_POST['cart_total'];
	$subtotal = $_POST['subtotal'];
	$offline = $_POST['offline'];
	if($offline=='yes'){
	
		$dataArray=array();
		$dataArray=array(
				   'cart_item_key' => $cart_item_id,
				   'customer_id' => $customer_id,
				   'product_id' => $product_id,
				   'vehicle_id' => $vehicle_id,
				   'service_data_id' => $service_id,
				   'service_name' =>$service_name,
				   'customer_id' =>0,
				   'order_id' =>0,
				   'tyre' =>$tyre_count,
				   'rate' =>$service_rate,
				);
		$insert = $wpdb->insert('th_franchise_cart_item_services', $dataArray);
		//echo $wpdb->last_query;
		$lastid = $wpdb->insert_id;
		$update = $wpdb->query("UPDATE th_franchise_cart_item SET vehicle_id = '$vehicle_id' WHERE cart_item_key = '$cart_item_id'");
		$services = "SELECT *
					FROM th_franchise_cart_item_services
					WHERE cart_item_key = '$cart_item_id' and order_id = ''";
		$row = $wpdb->get_results($services);
		$service_list_arr = [];
		foreach ($row as $key => $service)
		{
			$service_name = $service->service_name;
			$service_list_arr[$key]['service_name'] = $service_name;
			$service_list_arr[$key]['service_price'] = $service->rate;
			//echo '<div>'.$service_name.'</div>';
			$service_price1=$service_price1+$service->rate;
		}
	}else{
		
		
		$th_cart_item_services = 'th_cart_item_services';
		$dataArray=array();
		$dataArray=array(
				   'cart_item_key' => $cart_item_id,
				   'session_id' => $session_id,
				   'product_id' => $product_id,
				   'vehicle_id' => $vehicle_id,
				   'service_data_id' => $service_id,
				   'service_name' =>$service_name,
				   'pikcup_address' =>$pic_address,
				   'tyre' =>$tyre_count,
				   'rate' =>$service_rate,
				);
		$insert = $wpdb->insert($th_cart_item_services, $dataArray);
		//echo $wpdb->last_query;
		$lastid = $wpdb->insert_id;
		$update = $wpdb->get_results("UPDATE th_cart_item_installer SET vehicle_id = '$vehicle_id' WHERE cart_item_key = '$cart_item_id' and session_id = '$session_id' and order_id = ''");
		$services = "SELECT *
					FROM th_cart_item_services
					WHERE cart_item_key = '$cart_item_id' and session_id = '$session_id' and order_id = ''";
		$row = $wpdb->get_results($services);
		$service_list_arr = [];
		foreach ($row as $key => $service)
		{
			$service_name = $service->service_name;
			$service_list_arr[$key]['service_name'] = $service_name;
			$service_list_arr[$key]['service_price'] = $service->rate;
			//echo '<div>'.$service_name.'</div>';
			$service_price1=$service_price1+$service->rate;
		}
	}
	$service_price=number_format($service_price1,2,'.','');
    $returnData['cart_item_services_id']= $lastid;
    $returnData['currency_symbol']= get_woocommerce_currency_symbol();
    $returnData['cart_subtotal']= number_format($subtotal+$service_rate,2,'.','');
    $returnData['cart_total']= number_format($cart_total+$service_rate,2,'.','');
	$returnData['redata']=$service_list_arr;

	echo json_encode($returnData);
	die();
}
// Store the custom data to cart object
function save_custom_product_data1( $cart_item_data, $product_id ) {
	$bool = true;
	$data = array();
	$cart_item_data['custom_data']['shivkumar'] = 'developer';
	if( $bool ) {
		// below statement make sure every add to cart action as unique line item
		WC()->session->set( 'custom_variations', $data );
	}
	return $cart_item_data;
}
// remove_service_info
add_action('wp_ajax_remove_service_info_from_cart', 'remove_service_info_from_cart');
add_action('wp_ajax_nopriv_remove_service_info_from_cart', 'remove_service_info_from_cart');
function remove_service_info_from_cart()
{
	global $woocommerce , $wpdb;
	$cart_item_id = $_POST['cart_item_id'];
	$session_id = $_POST['session_id'];
	$cart_item_key = $_POST['cart_item_key'];
	$cart_total = $_POST['cart_total'];
	$offline = $_POST['offline'];
	$subtotal = $_POST['subtotal'];
	if($offline=='yes'){
		$SQL="SELECT * FROM th_franchise_cart_item_services WHERE cart_item_services_id = '$cart_item_id'  and order_id = ''";
		$delete_service = $wpdb->get_row($SQL);
		$delete_service1 = $wpdb->get_results("DELETE from th_franchise_cart_item_services WHERE cart_item_services_id = '$cart_item_id' and order_id = ''");
		$services = "SELECT *
					FROM th_franchise_cart_item_services
					WHERE cart_item_key = '$cart_item_key' and order_id = ''";
	}else{
		$th_cart_item_services = 'th_cart_item_services';
	 $SQL="SELECT * FROM th_cart_item_services WHERE cart_item_services_id = '$cart_item_id' and session_id = '$session_id' and order_id = ''";
		$delete_service = $wpdb->get_row($SQL);
		$delete_service1 = $wpdb->get_results("DELETE from $th_cart_item_services WHERE cart_item_services_id = '$cart_item_id' and session_id = '$session_id' and order_id = ''");
		$services = "SELECT *
					FROM th_cart_item_services
					WHERE cart_item_key = '$cart_item_key' and session_id = '$session_id' and order_id = ''";
	}
	$row = $wpdb->get_results($services);
	$service_list_arr = [];
	foreach ($row as $key => $service)
	{
		$service_name = $service->service_name;
		$service_list_arr[$key]['service_name'] = $service_name;
		$service_list_arr[$key]['service_price'] = $service->rate;
		//echo '<div>'.$service_name.'</div>';
		$service_price1=$service_price1+$service->rate;
	}
	$returnData['cart_item_services_id']= $delete_service->cart_item_services_id;
	$returnData['service_data_id']= $delete_service->service_data_id;
    $returnData['currency_symbol']= get_woocommerce_currency_symbol();
    $returnData['cart_subtotal']= number_format($subtotal-$delete_service->rate,2,'.','');
    $returnData['cart_total']= number_format($cart_total-$delete_service->rate,2,'.','');
	$returnData['redata']=$service_list_arr;
	echo json_encode($returnData);
	die();
}
add_action('wp_ajax_demo', 'demo');
add_action('wp_ajax_nopriv_demo', 'demo');
function demo()
{
	global $wpdb , $woocommerce;
	$current_user = get_current_user_id();
	$vehicle_id = $_POST['abc'];
	$product_id = $_POST['product_id'];
	$selected_vehicle = $_POST['selected_vehicle'];
	$cart_item_id = $_POST['cart_item_id'];
	$session_id = WC()->session->get_customer_id();
	//-----
	$services = "SELECT *
				FROM th_cart_item_services
				WHERE cart_item_key = '$cart_item_id' and session_id = '$session_id' and order_id = ''";
		$row = $wpdb->get_results($services);
		$service_name = '';
		$service_list = [];
		foreach ($row as $key => $service)
		{
			$service_name = $service->service_name;
			 $tyre_count = $service->tyre;
			$service_list[$service_name] = $tyre_count;
		}
	$items = $woocommerce->cart->get_cart();
		foreach($items as $item => $values) {
		   if($values['variation_id'] != ''){
				$cart_prd_id =  $values['variation_id'];
		   }
		   else{
				$cart_prd_id =  $values['product_id'];
		   }
			if($cart_prd_id == $product_id){
			  $cart_item_total =$values['quantity'];
			}
		}
   // $row = $wpdb->get_results("SELECT * FROM th_service_data where service_data_id != 4");
	 $row = $wpdb->get_results('SELECT * from th_service_data_price as a join th_service_data as b on a.service_data_id = b.service_data_id where a.vehicle_id ='.$vehicle_id.' && b.service_data_id != 4');
	foreach ($row as $data)
	{
			 $service_name = $data->service_name;
			if(array_key_exists($service_name, $service_list))
			{
				$selected_tyre = $service_list[$service_name];
					  //  }
			}
?>
	<div class="service_list">
		<div class="inputGroup service_type">
<?php
			$selected_vehicle = $_POST['selected_vehicle'];
			if($service_name == 'Tyre Fitment')
			{
?>
				<input id="service_<?php echo $data->service_data_id ?>" name="servie_type" type="checkbox" value="<?php echo $data->service_data_id ?>" class="service_name" checked disabled>
<?php
			}
			else
			{
				if($selected_vehicle == $vehicle_id)
				{
			?>
					<input id="service_<?php echo $data->service_data_id ?>" name="servie_type" type="checkbox" value="<?php echo $data->service_data_id ?>" class="service_name" <?php if(array_key_exists($data->service_name, $service_list)){echo 'checked';}?>>
			<?php
				}
				else{
			?>
					<input id="service_<?php echo $data->service_data_id ?>" name="servie_type" type="checkbox" value="<?php echo $data->service_data_id ?>" class="service_name">
			<?php
				} }
			?>
			<label for="service_<?php echo $data->service_data_id ?>"  <?php if($service_name == 'Tyre Fitment')
					{
						echo "class='tyre-fitment'";
					} ?> >
				<?php
				if($service_name == 'Tyre Fitment')
				{
					echo '<img class="service-img" src="'.get_site_url().'/wp-content/themes/demo/images/tyre_fitting.png" style="width:45px;"></img>';
				}
				if($service_name == 'Wheel Balancing')
				{
					echo '<img class="service-img" src="'.get_site_url().'/wp-content/themes/demo/images/tyre_balancing.png" style="width:45px;"></img>';
				}
				if($service_name == 'Wheel alignment')
				{
					echo '<img class="service-img" src="'.get_site_url().'/wp-content/themes/demo/images/wheel-alignment.png" style="width:45px;"></img>';
				}
				?>
				<?php echo '<span class="sname">'.$data->service_name.'</span>' ?>
				<?php
					$row = $wpdb->get_results("SELECT * FROM th_service_data_price where vehicle_id = $vehicle_id and service_data_id = $data->service_data_id");
					foreach ($row as $data)
					{
						echo '<input class="service_rate" value="'.$data->rate.'" hidden>';
						if($service_name == 'Tyre Fitment')
						{
							echo '<span class="rate"> Free</span>';
						}else{
							echo '<span class="rate">'.get_woocommerce_currency_symbol().$data->rate.'</span>';
						}
						echo '<span class="select_tyre"></span>';
						echo '<span class="service-amount" data-amount=""></span>';
				?>
				 <?php
						if($service_name == 'Tyre Fitment')
						{
				?>
							<input type="hidden" name="" class="cart_tyre" value="<?php echo $cart_item_total; ?>">
				<?php
						}
						else{
				?>
							<input type="hidden" name="" class="cart_tyre" value="1">
				<?php
						}
					}
				?>
			</label>
		</div>
	</div>
		<?php } ?>
	<?php
	die();
}
// change add to cart link to checkout and set quantity to 1
//add_filter ('woocommerce_add_to_cart_redirect', 'redirect_to_checkout');
function redirect_to_checkout() {
   // die();
	global $woocommerce , $wpdb;
	// get Service Voucher product id
	$sku = 'service_voucher';
	$product_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );
	$service_voucher_prd = $product_id;
	$qty = 1;   // Example qty
	$cart = WC()->instance()->cart; // Instantiate cart object
	/*foreach( WC()->cart->cart_contents as $prod_in_cart )
	{
			// Get the Variation or Product ID
		$prod_id = ( isset( $prod_in_cart['variation_id'] ) && $prod_in_cart['variation_id'] != 0 ) ? $prod_in_cart['variation_id'] : $prod_in_cart['product_id'];
			// Check to see if IDs match
		if( $service_voucher_prd == $prod_id )
		{
			$cart_id        = $cart->generate_cart_id($service_voucher_prd);
			$cart_item_id   = $cart->find_product_in_cart($cart_id);
			$cart->set_quantity($cart_item_id, $qty);
		}
		else{
			return $checkout_url;
		}
	}*/
	/*if(isset($_GET['page']) && $_GET['page'] == 'service-page'){
		global $woocommerce;
		$checkout_url = $woocommerce->cart->get_checkout_url();
	}	 */
	//$checkout_url = get_site_url().'/service-partner';
	return $checkout_url;
}
// add custom price to service_voucher product
add_action( 'woocommerce_before_calculate_totals', 'add_custom_price' );
function add_custom_price( $cart_object )
{
	global $wpdb;
	$sku = 'service_voucher';
	$service_voucher_prd = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );
	$session_id = WC()->session->get_customer_id();
	foreach ( $cart_object->cart_contents as $key => $value )
	{
		if($value['product_id'] == $service_voucher_prd)
		{
			$voucher_info = "SELECT *
					FROM th_cart_item_service_voucher
					WHERE product_id = '$service_voucher_prd'   AND cart_item_key='$key' AND  service_data_id=".$value['services_name']." and session_id = '$session_id' and order_id = ''";
			$voucher_row = $wpdb->get_row($voucher_info);
			$total = 0;
				$voucher_id = $voucher_row->service_voucher_id;
				$voucher_row->voucher_name;
				$vehicle_id = $voucher_row->vehicle_id;
				$rate = $voucher_row->rate;
				$qty = $voucher_row->qty;
				$amount = $rate * $qty;
				$total = $total + $amount;
			$value['data']->set_price($total);
			if($total == 0)
			{
			   // WC()->cart->remove_cart_item( $key );
			}
		}
				global $wpdb;
			$user_id = get_current_user_id();
			$SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
			$franchise=$wpdb->get_row($SQL);
		  if($franchise){
		  	/*$SQLSHIV="SELECT * FROM `th_supplier_products_final` where product_id='".$value['variation_id']."' AND updated_date>DATE_SUB(NOW(),INTERVAL 1 YEAR)  GROUP by id HAVING min(tyre_price+tube_price) ORDER BY (tyre_price+tube_price) ASC LIMIT 0,1";*/
		  	$SQLSHIV="SELECT * FROM `th_supplier_products_final` where product_id='".$value['variation_id']."'  GROUP by id HAVING min(tyre_price+tube_price) ORDER BY (tyre_price+tube_price) ASC LIMIT 0,1";
		    $productsshiv=$wpdb->get_row($SQLSHIV);
		    $tube_price = $productsshiv->tube_price;
		    $tyre_price = $productsshiv->tyre_price;
		    if($value['offline-purchase']!='yes'){
		    	$product_price=($tube_price +$tyre_price) + (($tube_price +$tyre_price)*0)/100;
		    	$value['data']->set_price($product_price);
			}
		  }
	}
}
// add save price total and percentage for particular product
//add_filter( 'woocommerce_get_price_html', 'custom_price_html', 100, 2 );
function custom_price_html( $price, $product )
{
	$regular_price = $product->get_regular_price();
	$sale_price = $product->get_sale_price();
	if($sale_price == ''){
		return $price;
	}
	else{
		$save = $regular_price - $sale_price;
		$save_per = ($save * 100 ) / $regular_price;
		$save_per = number_format($save_per,2,'.','');
		$save_html = '<div class="save">You save: '.get_woocommerce_currency_symbol(). $save .' ('. floor(floatval($save_per)).'%) </div>';
		return $price . $save_html;
	}
}
// update header cart count
add_action('wp_ajax_update_header_cart_begde', 'update_header_cart_begde');
add_action('wp_ajax_nopriv_update_header_cart_begde', 'update_header_cart_begde');
function update_header_cart_begde()
{
	global $woocommerce;
	echo  WC()->cart->get_cart_contents_count();
	die();
}
add_action('wp_ajax_update_tyrefitment_qty', 'update_tyrefitment_qty');
add_action('wp_ajax_nopriv_update_tyrefitment_qty', 'update_tyrefitment_qty');
function update_tyrefitment_qty()
{
	global $woocommerce , $wpdb;
	 $product_id = $_POST['product_id'];
	$session_id = WC()->session->get_customer_id();
	$items = $woocommerce->cart->get_cart();
	//echo "<pre>";
	//print_r($items);
	
	foreach($items as $item => $values)
	{
		$cart_prd_id =  $values['product_id'];
		if($values['variation_id'] != '')
		{
			$cart_prd_id =  $values['variation_id'];
		}
		if($cart_prd_id == $product_id){
			//$cart_item_total =$values['quantity'];
			$cart_item_total =$_POST['qty'];
		}
	}
	/*echo $SQL="UPDATE th_cart_item_services SET tyre = '$cart_item_total' WHERE session_id = '$session_id' and product_id = '$product_id' and order_id = '' and service_name = 'Tyre Fitment'";*/
	if($values['offline-purchase'] == "yes"){
		$update = $wpdb->update('th_franchise_cart_item_services',array('tyre' => $cart_item_total),
		array( 'cart_item_key' => $values['key'],'product_id' => $product_id,'order_id' =>'','service_name' =>'Tyre Fitment'));
	}else{
	$update = $wpdb->update('th_cart_item_services',array('tyre' => $cart_item_total),
	array( 'session_id' => $session_id,'product_id' => $product_id,'order_id' =>'','service_name' =>'Tyre Fitment'));
	}
	//$update = $wpdb->get_results($SQL);
	$services = "SELECT *
				FROM th_cart_item_services
				WHERE product_id = '$product_id' and session_id = '$session_id' and order_id = ''";
	$row = $wpdb->get_results($services);
	$service_name = '';
	$service_list = [];
	$amount = '';
	$total_amout = 0;
	foreach ($row as $key => $service)
	{
		$tyre_count = $service->tyre;
		$service_name = $service->service_name;
		$rate = $service->rate;
		$service_list[$service_name] = $tyre_count;
		if($service_name == 'Wheel alignment'){
			$amount = $rate;
			echo '<div>'.$service_name.' '.get_woocommerce_currency_symbol().$rate.' - '.get_woocommerce_currency_symbol().$amount.'</div>';
		}
		else{
			$amount = $tyre_count * $rate;
			echo '<div>'.$service_name.' '.get_woocommerce_currency_symbol().$rate.' x '.$tyre_count.' - '.get_woocommerce_currency_symbol().$amount.'</div>';
		}
		$total_amout = $total_amout + $amount;
	}
	echo $woocommerce->cart->get_cart_contents_count();
	die();
}
add_action('wp_ajax_update_servicecharge_after_update_cart', 'update_servicecharge_after_update_cart');
add_action('wp_ajax_nopriv_update_servicecharge_after_update_cart', 'update_servicecharge_after_update_cart');
function update_servicecharge_after_update_cart()
{
	$product_id = $_POST['product_id'];
	$session_id = WC()->session->get_customer_id();
	global $wpdb , $woocommerce;
	$user_id = get_current_user_id();
	$SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
	$franchise=$wpdb->get_row($SQL);
	if(empty($franchise)){
		$services = "SELECT *
			FROM th_cart_item_services
			WHERE product_id = '$product_id' and session_id = '$session_id' and order_id = ''";
	}else{
		$services = "SELECT *
			FROM th_franchise_cart_item_services
			WHERE product_id = '$product_id' and order_id = ''";
	}
	$row = $wpdb->get_results($services);
	$service_name = '';
	$service_list = [];
	$amount = '';
	$total_amout = 0;
	$home_delivery_charge = 0;
	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item )
	{
		$cart_item_qty = $cart_item['quantity'];
		$installer = "SELECT *
						FROM th_cart_item_installer
						WHERE cart_item_key = '$cart_item_key' and session_id = '$session_id' and order_id = ''";
		$row = $wpdb->get_results($installer);
		$destination = '';
		$installer_table_id = '';
		foreach ($row as $key => $installer)
		{
			$destination = $installer->destination;
			if($destination == '0')
			{
				$current_prd_charge = 100 * $cart_item_qty;
				//$home_delivery_charge = $home_delivery_charge + $current_prd_charge;
				$home_delivery_charge = 0;
			}
		}
	}
	foreach(WC()->cart->get_cart() as $cart_key => $cart_item )
	{
		$cart_item_qty = $cart_item['quantity'];
		if(empty($franchise)){
		$destination_data = "SELECT *
					FROM th_cart_item_installer
					WHERE cart_item_key = '$cart_key' and session_id = '$session_id' and order_id = ''";
		}else{
			$destination_data = "SELECT *
					FROM th_franchise_cart_item
					WHERE cart_item_key = '$cart_key' and order_id = ''";
		}
		$row = $wpdb->get_results($destination_data);
		if(!empty($row))
		{
			foreach ($row as $key => $data)
			{
				$destination = $data->destination;
			}
			if($destination == 1)
			{
				if(empty($franchise)){
				$services = "SELECT *
						FROM th_cart_item_services
						WHERE cart_item_key = '$cart_key' and session_id = '$session_id' and order_id = ''";
				}else{
					$services = "SELECT *
						FROM th_franchise_cart_item_services
						WHERE cart_item_key = '$cart_key' and order_id = ''";
				}
				$row = $wpdb->get_results($services);
				foreach ($row as $key => $service)
				{
					$service_name = $service->service_name;
					$tyre_count = $service->tyre;
					$rate = $service->rate;
					if($service_name == 'Wheel alignment'){
						$amount =  $rate;
					}
					else
					{
						$amount = $tyre_count * $rate;
					}
					$total = $total + $amount;
				}
			}
			if($destination == 0){
			   $total = $total + (100 * $cart_item_qty);
			}
		}
	}
	$service_charge = '<span class="woocommerce-Price-currencySymbol">'.get_woocommerce_currency_symbol().'</span>'.number_format($total,2,'.','');
	$content_total = $woocommerce->cart->cart_contents_total;
	$subtotal = $total+$content_total;
	 $subtotal = '<span class="woocommerce-Price-currencySymbol">'.get_woocommerce_currency_symbol().'</span>'.number_format($subtotal,2,'.','');
	$data1['service_charge'] = $service_charge;
	$data1['subtotal'] = $subtotal;
	$data1['qty_total'] = $woocommerce->cart->get_cart_contents_count();
	
  $myJSON = json_encode($data1);
  echo $myJSON;
die();
}
// Return the number of products you wanna show per page.
add_filter( 'loop_shop_per_page', 'new_loop_shop_per_page', 20 );
function new_loop_shop_per_page( $cols )
{
  $cols = 12;
  return $cols;
}
add_action( 'woocommerce_cart_calculate_fees', 'add_service_fee', 10, 1 );
function add_service_fee( $cart_object )
{
	if ( is_admin() && ! defined( 'DOING_AJAX' ) ) return;
	global $wpdb;
	global $woocommerce;
	$user_id = get_current_user_id();
	$SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
	$franchise=$wpdb->get_row($SQL);
	/*foreach(WC()->cart->get_cart() as $cart_item_key => $cart_item )
	{
	   $cart_item_key_arr[] = $cart_item_key;
	}*/
	$total = 0;
	$session_id = WC()->session->get_customer_id();
	$subtotal = $cart_object->subtotal;
	foreach(WC()->cart->get_cart() as $cart_key => $cart_item )
	{
		$cart_item_qty = $cart_item['quantity'];
		if(empty($franchise)){
				$destination_data = "SELECT *
					FROM th_cart_item_installer
					WHERE cart_item_key = '$cart_key' and session_id = '$session_id' and order_id = ''";
		}else{
			$destination_data = "SELECT *
					FROM th_franchise_cart_item
					WHERE cart_item_key = '$cart_key' and order_id = ''";
		}
		$row = $wpdb->get_results($destination_data);
		if(!empty($row))
		{
			foreach ($row as $key => $data)
			{
				$destination = $data->destination;
			}
			if($destination == 1)
			{
				if(empty($franchise)){
				$services = "SELECT *
						FROM th_cart_item_services
						WHERE cart_item_key = '$cart_key' and session_id = '$session_id' and order_id = ''";
				}else{
					$services = "SELECT *
						FROM th_franchise_cart_item_services
						WHERE cart_item_key = '$cart_key' and order_id = ''";
				}
				 $row = $wpdb->get_results($services);
				 foreach ($row as $key => $service)
				{
				   $service_name = $service->service_name;
					$tyre_count = $service->tyre;
					$rate = $service->rate;
					if($service_name == 'Wheel alignment'){
						$amount =  $rate;
					}
					else{
						$amount = $tyre_count * $rate;
					}
				$total = $total + $amount;
				}
			}
			if($destination == 0){
				$product_id = $cart_item['variation_id'];
				$product_variation_new = wc_get_product( $product_id );
					$prd_attr_vehicle = '';
					$variation_data = $product_variation_new->get_data();
						if($variation_data['attributes']['pa_vehicle-type'] != 'car-tyre'){
						   $prd_attr_vehicle = $variation_data['attributes']['pa_vehicle-type'];
					}
					if($prd_attr_vehicle != ''){
						if($cart_item_qty >= 2 && $cart_item_qty <= 5 ){
							$home_delivery_charge = 200;
						}else if($cart_item_qty >= 6){
							$home_delivery_charge = 300;
						}else{
							$home_delivery_charge = 100;
						}
					}else{
						if($cart_item_qty >= 2 && $cart_item_qty <= 5 ){
							 $home_delivery_charge = 250;
						}else if($cart_item_qty >= 6){
							$home_delivery_charge = 400;
						}else{
							$home_delivery_charge = 150;
						}
					}
					$home_delivery_charge=0;
			   $total = $total + $home_delivery_charge;
			}
		}
	}
		$subtotal = $subtotal + $total;
		$cart_object->add_fee( __( "Service Charges", "woocommerce" ), $total, false );
		$cart_object->set_subtotal($subtotal);
}
add_action( 'pre_get_posts', 'custom_pre_get_posts_query' );
function custom_pre_get_posts_query( $q ) {
  if ( ! $q->is_main_query() ) return;
  if ( ! $q->is_post_type_archive() ) return;
  if ( ! is_admin() && is_shop() )
  {
	$q->set( 'post__not_in', array(125) );
  }
  remove_action( 'pre_get_posts', 'custom_pre_get_posts_query' );
}
add_action( 'woocommerce_product_query', 'so_27975262_product_query' );
function so_27975262_product_query( $q ){
	$q->set( 'post_type', array('product','product_variation' ) );
	return $q;
}
add_action('wp_ajax_remove_service', 'remove_service');
add_action('wp_ajax_nopriv_remove_service', 'remove_service');
function remove_service()
{
	global $woocommerce , $wpdb;
	$cart_key = $_POST['cart_key'];
	$session_id = $_POST['session_id'];
	$installer_table_id = $_POST['installer_table_id'];
	$user_id = get_current_user_id();
	$SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
	$franchise=$wpdb->get_row($SQL);
	$franchise_id=$franchise->installer_data_id; // code add for delete addition service in invoice after some day. change in delete query
	if($franchise){
		$delete_installer = $wpdb->query("DELETE from th_franchise_cart_item WHERE cart_item_key = '$cart_key' AND installer_id='".$franchise_id."' AND order_id=''");
		$delete_service = $wpdb->query("DELETE from th_franchise_cart_item_services WHERE cart_item_key = '$cart_key' AND installer_id='".$franchise_id."' AND order_id=''");
	}else{
		$delete_installer = $wpdb->get_results("DELETE from th_cart_item_installer WHERE cart_item_installer_id = '$installer_table_id'");
		$delete_service = $wpdb->get_results("DELETE from th_cart_item_services WHERE cart_item_key = '$cart_key' and session_id = '$session_id' and order_id = ''");
	}
   //die();
}
add_action( 'woocommerce_before_calculate_totals', 'action_cart_calculate_totals', 10, 1 );
function action_cart_calculate_totals( $cart_object ) {
		global $woocommerce , $wpdb;
		$session_id = WC()->session->get_customer_id();
	if ( is_admin() && ! defined( 'DOING_AJAX' ) )
		return;
	if ( !WC()->cart->is_empty() ):
		$subtotal = $cart_object->subtotal;
		$total = $cart_object->total;
		$amount = '';
		$total_amout = 0;
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item)
		{
				$services = "SELECT *
							FROM th_cart_item_services
							WHERE cart_item_key = '$cart_item_key' and session_id = '$session_id' and order_id = ''";
							$row = $wpdb->get_results($services);
							$service_name = '';
							$service_list = [];
							foreach ($row as $key => $service)
							{
								$tyre_count = $service->tyre;
								$service_name = $service->service_name;
								$rate = $service->rate;
								if($service_name == 'Wheel alignment'){
									$amount = $rate;
								}
								else{
									$amount = $tyre_count * $rate;
								}
								$total_amout = $total_amout + $amount;
							}
		}
	/*		global $wpdb;
			$user_id = get_current_user_id();
			$SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
			$franchise=$wpdb->get_row($SQL);
		  if($franchise){
		  	$SQLSHIV="SELECT * FROM `th_supplier_products_final` where product_id='".$cart_item['variation_id']."' AND updated_date>DATE_SUB(NOW(),INTERVAL 1 YEAR)  GROUP by id HAVING min(tyre_price+tube_price) ORDER BY (tyre_price+tube_price) ASC LIMIT 0,1";
		    $productsshiv=$wpdb->get_row($SQLSHIV);
		    $tube_price = $productsshiv->tube_price;
		    $tyre_price = $productsshiv->tyre_price;
		    $product_price=($tube_price +$tyre_price) + (($tube_price +$tyre_price)*0)/100;
		  	$subtotal = $product_price + $total_amout;
			$total = $total + $total_amout;
		  }else{
		  	$subtotal = $subtotal + $total_amout;
			$total = $total + $total_amout;
		  }*/
	endif;
}
add_action('woocommerce_thankyou', 'enroll_student', 10, 1);
function enroll_student( $order_id )
{
	global $wpdb;
	$order = wc_get_order( $order_id );
	$session_id = WC()->session->get_customer_id();
	$order_items = $order->get_items();
	$item_count = count($order_items);
	$sku = 'service_voucher';
	$service_voucher_prd = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );
	foreach ( $order->get_items() as $item_id => $item )
	{
		 if($item['variation_id'] != ''){
			$product_id = $item['variation_id'];
		 }
		 else{
			$product_id = $item['product_id'];
		 }
		$update = $wpdb->get_results("UPDATE th_cart_item_installer SET order_id = '$order_id' WHERE session_id = '$session_id' and product_id = '$product_id' and order_id = ''");
		$update = $wpdb->get_results("UPDATE th_cart_item_services SET order_id = '$order_id' WHERE session_id = '$session_id' and product_id = '$product_id' and order_id = ''");
	}
	$update_voucher = $wpdb->get_results("UPDATE th_cart_item_service_voucher SET order_id = '$order_id' WHERE session_id = '$session_id' and voucher_name != '' and order_id = ''");
	if( ! $order_id ) return;
	$order = wc_get_order( $order_id );

	if($item_count>=1 && $product_id == $service_voucher_prd){
		if( $order->get_status() != 'failed' && $order->get_status() != 'pending' )
		{

			$order->update_status('processing', 'order_note');
		}
		
	}else{
		if( $order->get_status() == 'processing' )
		{
			$order->update_status( 'on-hold' );
		}
	}

}
add_action('wp_ajax_save_home_or_installer', 'save_home_or_installer');
add_action('wp_ajax_nopriv_save_home_or_installer', 'save_home_or_installer');
function save_home_or_installer()
{
	global $woocommerce , $wpdb;
	$cart_key = $_POST['cart_key'];
	$session_id = WC()->session->get_customer_id();
	$product_id = $_POST['product_id'];
	$pincode = $_POST['pincode'];
	$address = $pincode;
	$url ='https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($pincode) . '&sensor=true&key='.GOOGLE_API_KEY;
  //  $coordinates = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($pincode) . '&sensor=true&key=AIzaSyCmMCGIlJKPcPn_hjlgo6j-RMfN4ZtOH70');
   // var_dump($coordinates);
 // var_dump($coordinates);
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, $url );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	$response = curl_exec( $ch );
   // var_dump($response);
	$coordinates = json_decode($response);
   $lat = $coordinates->results[0]->geometry->location->lat;
   $lng = $coordinates->results[0]->geometry->location->lng;
	$url = "https://maps.googleapis.com/maps/api/distancematrix/json?key=".GOOGLE_API_KEY."&origins=".$lat.",".$lng."&destinations=23.0314594,72.56410770&mode=driving&language=pl-PL";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			$response = curl_exec($ch);
			curl_close($ch);
			$response_a = json_decode($response, true);
			$dist = $response_a['rows'][0]['elements'][0]['distance']['value'];
			if($dist != "" && $dist != null)
			{
				if($dist > 20500){
					$url1 = "https://maps.googleapis.com/maps/api/distancematrix/json?key=".GOOGLE_API_KEY."&origins=".$lat.",".$lng."&destinations=23.2283124,72.636920&mode=driving&language=pl-PL";
					$ch1 = curl_init();
					curl_setopt($ch1, CURLOPT_URL, $url1);
					curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch1, CURLOPT_PROXYPORT, 3128);
					curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, 0);
					curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, 0);
					$response1 = curl_exec($ch1);
					curl_close($ch1);
					$response_a1 = json_decode($response1, true);
					$dist_gandhinagar = intval($response_a1['rows'][0]['elements'][0]['distance']['value']);
						if($dist_gandhinagar > 10500)
						{
							 echo "0";
						}else{
							  echo "1";
							  if(!isset($_SESSION['current_pincode'])){
									$_SESSION['current_pincode'] = $pincode;
								 }
							  $insert = $wpdb->insert('th_cart_item_installer', array(
											'cart_item_key' => $cart_key,
											'session_id' => $session_id,
											'product_id' => $product_id,
											'destination' => 0,
											));
						}
				}else{
					echo "1";
					if(!isset($_SESSION['current_pincode'])){
								$_SESSION['current_pincode'] = $pincode;
							 }
					$insert = $wpdb->insert('th_cart_item_installer', array(
											'cart_item_key' => $cart_key,
											'session_id' => $session_id,
											'product_id' => $product_id,
											'destination' => 0,
											));
				}
			}
			else{
				echo "0";
			}
		die();
}
add_action('wp_ajax_delivery_eligible_check', 'delivery_eligible_check');
add_action('wp_ajax_nopriv_delivery_eligible_check', 'delivery_eligible_check');
function delivery_eligible_check()
{
	global $woocommerce , $wpdb;
	$pincode = $_POST['pincode'];
	$address = $pincode;
	$url ='https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($pincode) . '&sensor=true&key='.GOOGLE_API_KEY;
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, $url );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	$response = curl_exec( $ch );
	$coordinates = json_decode($response);
   $lat = $coordinates->results[0]->geometry->location->lat;
   $lng = $coordinates->results[0]->geometry->location->lng;
$qry ='SELECT *,(((acos(sin(('.$lat.'*pi()/180)) * sin((`latitude`*pi()/180))+cos(('.$lat.'*pi()/180)) * cos((`latitude`*pi()/180)) * cos((('.$lng.'- `longitude`)*pi()/180))))*180/pi())*60*1.1515*1.609344) as distance
FROM `th_city_delivery_range` WHERE status =1 HAVING (distance < km_range OR distance < km_distance)
ORDER BY km_range';
$results=$wpdb->get_results($qry);
	$total_result_rows = $wpdb->num_rows;
	if($total_result_rows){
		 echo "1";
		  if(!isset($_SESSION['current_pincode'])){
				$_SESSION['current_pincode'] = $pincode;
			 }
	}else{
	   echo "0";
	}
	die();
}
add_filter( 'woocommerce_cart_item_removed_title', 'removed_from_cart_title', 12, 2);
function removed_from_cart_title( $message, $cart_item )
{
	$product = wc_get_product( $cart_item['product_id'] );
	if( $product )
		$message = sprintf( __('Product %s has been'), $product->get_name() );
	return $message;
}
add_filter('gettext', 'cart_undo_translation', 35, 3 );
function cart_undo_translation( $translation, $text, $domain ) {
	if( $text === 'Undo?' ) {
		$translation =  __( '', $domain );
	}
	return $translation;
}
add_action('wp_ajax_service_product_add_to_cart', 'service_product_add_to_cart');
add_action('wp_ajax_nopriv_service_product_add_to_cart', 'service_product_add_to_cart');
function service_product_add_to_cart()
{
	global $wpdb , $woocommerce;
	extract($_POST);

	$product_id   = ($vehicle_id==4)? get_option("balancing_alignment") : get_option("car_wash");
	if($woocommerce->cart->cart_contents_count > 0){
		
		foreach($woocommerce->cart->get_cart() as $cart_item_key => $val )
		{
				$_product = $val['product_id'];
                $vehicle_id_from_db = $val['custom_data']['vehicle_type'];
                $service_data_id_from_db = $val['services_name'];
                $SQL="SELECT * FROM th_cart_item_service_voucher WHERE cart_item_key='$cart_item_key' AND service_data_id='$service_data_id_from_db'";
                $getVouchr=$wpdb->get_row($SQL);
                $voucher_name_db =$getVouchr->voucher_name;  

				$SQL1="SELECT * FROM th_vehicle_type WHERE vehicle_id='$vehicle_id_from_db'";
                $getVehicl=$wpdb->get_row($SQL1);
                $voucher_name_db =$getVehicl->vehicle_type;

                $vehical_name_db = $val['variation']['vehicle_name'];
                 //    if($_product==$product_id && $vehicle_id_from_db==$service_id && $vehicle_id==$vehicle_id_from_db){
                    		
		}
				$total_qty = $woocommerce->cart->cart_contents_count;
				if($vehicle_id == $vehicle_id_from_db && $service_data_id_from_db == $service_id ){
                    	$qty = $val['quantity'];
                    	$total_qty = $total_qty + 1;

                    	if($service_id==4){
                    		$total_no_qty=1;
                    		$serv_name='Alignment and Balancing';
                    	}else{
                    		$total_no_qty=5;
                    		$serv_name='Car Wash';
                    	}

	                    if($total_no_qty <$total_qty){
	                        $valid ='graterpro';
	                        $msg ='Once Invoice can max '.$total_no_qty.' '.$serv_name.' Services. Individual Alignment and Service is considered under separate Invoice.';
	                    }else{
	                        //$total_qty = $quantity;
	                        //echo $total_qty;
	                        //$woocommerce->cart->set_quantity($cart_item_key,$total_qty); // Change quantity
	                        $cart_item_data = array(
								'services_name'=>$_POST['service_id'],
								'services_price'=>$_POST['rate']
							);
							 $product_id=3550;
							 $cartd=WC()->cart->add_to_cart($product_id,1,0,'',$cart_item_data);
							 if(empty($cartd)){
							 	$cartd=$cart_key;
							 }
								//$session_id = WC()->session->get_customer_id();
					            //WC()->cart->add_to_cart($service_prd_id, $quantity,$variation_id,$variation,$cart_item_data);

	                        $valid ='insert';
	                        //$cartd=$cart_item_key;
	                    }
	                }else{
	                        $valid ='notinsert';
	                        $msg ='You have multiple Service in your cart! Please generate One Invoice par Car/Bike!';
	                }

	}else{		
		$cart_item_data = array(
				'services_name'=>$_POST['service_id'],
				'services_price'=>$_POST['rate']
			);
		$product_id=3550;
			 $cartd=WC()->cart->add_to_cart($product_id,1,0,'',$cart_item_data);
			 if(empty($cartd)){
			 	$cartd=$cart_key;
			 }
			//$session_id = WC()->session->get_customer_id();
            //WC()->cart->add_to_cart($service_prd_id, $quantity,$variation_id,$variation,$cart_item_data);
            $valid ='insert';
	}
		$session_id = WC()->session->get_customer_id();
        echo json_encode(array('status'=>$valid,'msg'=>$msg,'cart_key'=>$cartd,'session_id'=>$session_id,'redirect_url'=>site_url('/cart/')));
        die();



}


add_action('wp_ajax_service_voucher_product_price', 'service_voucher_product_price');
add_action('wp_ajax_nopriv_service_voucher_product_price', 'service_voucher_product_price');
function service_voucher_product_price()
{
	global $wpdb;
	$rate = $_POST['rate'];
	$current_user = get_current_user_id();
	$service_voucher = $current_user.'_service_voucher';
	update_option($service_voucher , $rate);
	$service_id = $_POST['service_id'];
	$installer_id = $_POST['installer_id'];
	$vehicle_id = $_POST['vehicle_id'];
	$qty = $_POST['qty'];
	$cart_item_key = $_POST['cart_item_key'];
	$sku = 'service_voucher';
	$service_prd_id = $wpdb->get_var("SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='service_voucher' LIMIT 1");
	$session_id = $_POST['session_id'];
	date_default_timezone_set('Asia/Kolkata');
	$date = date('ymdhis', time());
	//$barcode_string = $date.$service_prd_id.$installer_id.$vehicle_id;
	$barcode_string =barcode_generate();
	/*$SQL="DELETE from th_cart_item_service_voucher WHERE cart_item_key='$cart_item_key' AND product_id = '$service_prd_id' and order_id = '' and session_id = '$session_id' and vehicle_id = '$vehicle_id' AND service_data_id=".$service_id." and installer_id = '$installer_id'";
	$delete_voucher = $wpdb->get_results($SQL);*/
	$SQL="SELECT * FROM th_cart_item_service_voucher WHERE cart_item_key='$cart_item_key' AND product_id = '$service_prd_id' and order_id = '' and session_id = '$session_id' and vehicle_id = '$vehicle_id' AND service_data_id=".$service_id." and installer_id = '$installer_id'";
	$exist_voucher = $wpdb->get_row($SQL);
	if(empty($exist_voucher)){
		$SQLSer="SELECT * FROM `th_service_data` WHERE service_data_id='".$service_id."'";
		$service_name = $wpdb->get_row($SQLSer);
		$insert = $wpdb->insert('th_cart_item_service_voucher', array(
											'product_id' => $service_prd_id,
											'session_id' => $session_id,
											'cart_item_key'=>$cart_item_key,
											'voucher_name' => $service_name->service_name,
											'vehicle_id' => $vehicle_id,
											'service_data_id' => $service_id,
											'qty' => $qty,
											'rate' => $rate,
											'installer_id' => $installer_id,
											'barcode' => $barcode_string,
											));

		$last_id = $wpdb->insert_id;
	}else{
		/*$wpdb->update('th_cart_item_service_voucher', array(
											'qty' =>$exist_voucher->qty+1,
											),array('service_voucher_id' =>$exist_voucher->service_voucher_id));
		$last_id=$exist_voucher->service_voucher_id;
		$barcode_string=$exist_voucher->barcode_string;*/
	}
    $result_arr = array($last_id,$barcode_string);
	echo json_encode($result_arr);
	die();
}
add_action('wp_ajax_remove_service_voucher', 'remove_service_voucher');
add_action('wp_ajax_nopriv_remove_service_voucher', 'remove_service_voucher');
function remove_service_voucher()
{
	global $woocommerce , $wpdb;
	$session_id = WC()->session->get_customer_id();
	$voucher_id = $_POST['voucher_id'];
	$cart_key = $_POST['cart_key'];
	$sku = 'service_voucher';
	$service_prd_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );
	$delete_voucher = $wpdb->get_results("DELETE from th_cart_item_service_voucher WHERE service_voucher_id = '$voucher_id'");
	/*$voucher_info = "SELECT *
					FROM th_cart_item_service_voucher
					WHERE product_id = '$service_prd_id' and session_id = '$session_id' and order_id = ''";
	$row = $wpdb->get_results($voucher_info);*/
  	WC()->cart->remove_cart_item($cart_key);
	/*if(count($row) == 0)
	{
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item )
		{
		    $product_id = $cart_item['product_id'];
			if($product_id == $service_prd_id){
				echo 'hi';
				WC()->cart->remove_cart_item($cart_item_key);
			}
		}

	}*/
	die();
}
add_action('wp_ajax_search_by_currentlocation', 'search_by_currentlocation');
add_action('wp_ajax_nopriv_search_by_currentlocation', 'search_by_currentlocation');
function search_by_currentlocation()
{
	$url = "https://maps.googleapis.com/maps/api/distancematrix/json?key=".GOOGLE_API_KEY."&origins=".$select_lat.",".$select_lng."&destinations=".$inst_lat.",".$inst_lng."&mode=driving&language=pl-PL";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	$response = curl_exec($ch);
	curl_close($ch);
	$response_a = json_decode($response, true);
	$dist = $response_a['rows'][0]['elements'][0]['distance']['text'];
	$time = $response_a['rows'][0]['elements'][0]['duration']['text'];
	$installer_km[$installer_id] = $dist;
}
add_action('wp_ajax_load_installler', 'load_installler');
add_action('wp_ajax_nopriv_load_installler', 'load_installler');
function load_installler()
{
	global $wpdb;
	$_POST['vehicle_type'];
	$row = $wpdb->get_results("SELECT * FROM th_installer_data");
	foreach ($row as $data) {
		?>
		<div class="single-list" data-id="<?php echo $data->installer_data_id; ?>">
			<div class="left">
				<div class="col-md-2 col-sm-2">
					<img class="aligncenter size-full wp-image-74" src="<?php echo get_site_url(); ?>/wp-content/uploads/2018/09/icons8-car-service-filled-100.png" alt="" width="100" height="100" />
				</div>
				<div class="col-md-4 col-sm-4">
					<div class="address" hidden><strong><?php echo $data->business_name.'</strong><br>'.$data->address.'<br>'.$data->state.'<br>'.$data->city.'-'.$data->pincode; ?>
					</div>
					<h4><?php echo $data->business_name; ?></h4>
					<div><?php echo $data->address; ?></div>
					<div><?php echo $data->city.'-'.$data->pincode; ?></div>
					<div><?php echo $data->state; ?></div>
				</div>
			</div>
			<div class="right">
				<div class="col-md-2 col-sm-2">
					<img class="aligncenter size-full wp-image-76" src="<?php echo get_site_url(); ?>/wp-content/uploads/2018/09/icons8-map-marker-100.png" alt="" width="100" height="100" />
				</div>
				<div class="col-md-3 col-sm-3">
					<span class="lattitude" hidden=""><?php echo $data->location_lattitude; ?></span>
					<span class="longitude" hidden=""><?php echo $data->location_longitude; ?></span>
					<i class="fa fa-map-marker"></i>&nbsp;<span class="km"></span>
					<div><i class="fa fa-calendar"></i>&nbsp;10 am to 9 pm (<?php echo $data->available_days; ?> )</div>
					<!-- <div><i class="fa fa-clock-o"></i>&nbsp;<?php echo $data->available_time ?></div> -->
					<div><i class="fa fa-phone"></i>&nbsp;<?php echo $data->contact_no; ?></div>
				</div>
				<?php
			 if(isset($_POST['vehicle_type']) && $_POST['vehicle_type'] != '')
				{ ?>
				<div class="col-md-2 col-sm-2 installer-btn">
					<!-- <button class="button installer_btn use_this_installer
" data-toggle="modal" data-target="#service_modal">USE THIS INSTALLER</button> -->
<button class="button installer_btn confirm_installer">USE THIS INSTALLER</button>
				</div>
				<?php } ?>
			</div>
			<ul class="installer-type">
				<!-- <li><i class="fas fa fa-sort-amount-up"></i><i class="fas fa fa-wifi"></i>Wifi Services</li>
				<li><i class="fas fa fa-car"></i></i>Car Pickup-Dropoff service</li>
				<li><i class="fa fa-wrench"></i>Shuttle Service</li> -->
				<li><i class="fas fa fa-tint"></i>Water Cooler </li>
				<li><i class="fas fa fa-coffee"></i>Free Tea/Coffee</li>
			</ul>
		</div>
<?php
	}
	die();
}
add_action('wp_ajax_search_by_pincode', 'search_by_pincode');
add_action('wp_ajax_nopriv_search_by_pincode', 'search_by_pincode');
function search_by_pincode()
{
	$vehicle_id = $_POST['vehicle_type'];
	$product_id = $_POST['product_id'];
	$services = $_POST['services'];
	if(empty($services)){
		$services=1;
	}
	$attr_vehicle_typre='';
	if($product_id){
		$product_variation = wc_get_product( $product_id );		
		$variation_data = $product_variation->get_data();
		$attr_vehicle_typre = trim($variation_data['attributes']['pa_vehicle-type']);
	}
	$sas_sql_arr = '';
	$sfc_sql_arr = '';
	if(isset($_POST['current_lon']) && isset($_POST['current_lat']))
	{
		$select_lat = $_POST['current_lat'];
		$select_lng = $_POST['current_lon'];
	}
	else
	{
		$postal_code = $_POST['postal_code'];
		$url = "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($postal_code)."&sensor=false&key=".GOOGLE_API_KEY;
		$result_string = file_get_contents($url);
		$result = json_decode($result_string, true);
		//print_r($result );
		$val = $result['results'][0]['geometry']['location'];
		$pincode = $result['results'][0]['address_components'][0]['long_name'];
		$select_lat = $val['lat'];
		$select_lng = $val['lng'];
		 session_start();
		$_SESSION['current_pincode']=$pincode;
	}
	global $wpdb;
	if($vehicle_id != '' && $attr_vehicle_typre == 'car-tyre')
	{
		//$row = $wpdb->get_results("SELECT * FROM th_installer_data where visibility = 1");
		$qry ='SELECT DISTINCT id.installer_data_id, id.*,(((acos(sin(('.$select_lat.'*pi()/180)) * sin((id.`location_lattitude`*pi()/180))+cos(('.$select_lat.'*pi()/180)) * cos((id.`location_lattitude`*pi()/180)) * cos((('.$select_lng.'- id.`location_longitude`)*pi()/180))))*180/pi())*60*1.1515*1.609344) as distance
FROM `th_installer_data` id LEFT JOIN th_installer_addi_service as ids ON ids.installer_id=id.installer_data_id';
/*if(count($services)>0){
	$where_qry='';
	foreach ($services as $key => $value) {
		# code...
		$where_qry.=' ids.service_data_id='.$value.' OR';
	}
	$newstring = substr($where_qry,0,-2);
	$where='AND ('.$newstring.')';
}*/
if(!empty($services)){
	$where_qry='';
	$where_qry.=' ids.service_data_id='.$services.' OR';
	$newstring = substr($where_qry,0,-2);
	$where='AND ('.$newstring.')';
}
$qry.= ' WHERE id.visibility =1 '.$where.' HAVING (distance < 15)
ORDER BY distance LIMIT 0,10';
	}elseif ($attr_vehicle_typre == 'car-tyre'){
		/*$qry ='SELECT *,(((acos(sin(('.$select_lat.'*pi()/180)) * sin((`location_lattitude`*pi()/180))+cos(('.$select_lat.'*pi()/180)) * cos((`location_lattitude`*pi()/180)) * cos((('.$select_lng.'- `location_longitude`)*pi()/180))))*180/pi())*60*1.1515*1.609344) as distance
FROM `th_installer_data` WHERE visibility =1 HAVING (distance < 15)
ORDER BY distance LIMIT 0,10';
$row = $wpdb->get_results($qry);*/
$qry ='SELECT DISTINCT id.installer_data_id, id.*,(((acos(sin(('.$select_lat.'*pi()/180)) * sin((id.`location_lattitude`*pi()/180))+cos(('.$select_lat.'*pi()/180)) * cos((id.`location_lattitude`*pi()/180)) * cos((('.$select_lng.'- id.`location_longitude`)*pi()/180))))*180/pi())*60*1.1515*1.609344) as distance
FROM `th_installer_data` id LEFT JOIN th_installer_addi_service as ids ON ids.installer_id=id.installer_data_id';
/*if(count($services)>0){
	$where_qry='';
	foreach ($services as $key => $value) {
		# code...
		$where_qry.=' ids.service_data_id='.$value.' OR';
	}
	$newstring = substr($where_qry,0,-2);
	$where='AND ('.$newstring.')';
}*/
if(!empty($services)){
	$where_qry='';
	$where_qry.=' ids.service_data_id='.$services.' OR';
	
	$newstring = substr($where_qry,0,-2);
	$where='AND ('.$newstring.')';
}
$qry.= ' WHERE id.visibility =1 '.$where.' HAVING (distance < 15)
ORDER BY distance LIMIT 0,10';
	}elseif($attr_vehicle_typre == 'two-wheeler' || $attr_vehicle_typre == 'three-wheeler'){
		;
		//$row = $wpdb->get_results("SELECT * FROM th_installer_data where user_id = 55 || user_id = 61");
		/*$qry ='SELECT *,(((acos(sin(('.$select_lat.'*pi()/180)) * sin((`location_lattitude`*pi()/180))+cos(('.$select_lat.'*pi()/180)) * cos((`location_lattitude`*pi()/180)) * cos((('.$select_lng.'- `location_longitude`)*pi()/180))))*180/pi())*60*1.1515*1.609344) as distance
FROM `th_installer_data` WHERE visibility =1 AND (user_id = 55 || user_id = 61) HAVING (distance < 15)
ORDER BY distance LIMIT 0,10';
$row = $wpdb->get_results($qry);*/
$qry ='SELECT DISTINCT id.installer_data_id, id.*,(((acos(sin(('.$select_lat.'*pi()/180)) * sin((id.`location_lattitude`*pi()/180))+cos(('.$select_lat.'*pi()/180)) * cos((id.`location_lattitude`*pi()/180)) * cos((('.$select_lng.'- id.`location_longitude`)*pi()/180))))*180/pi())*60*1.1515*1.609344) as distance
FROM `th_installer_data` id LEFT JOIN th_installer_addi_service as ids ON ids.installer_id=id.installer_data_id';
/*if(count($services)>0){
	$where_qry='';
	foreach ($services as $key => $value) {
		# code...
		$where_qry.=' ids.service_data_id='.$value.' OR';
	}
	$newstring = substr($where_qry,0,-2);
	$where='AND ('.$newstring.')';
}*/
if(!empty($services)){
	$where_qry='';
	$where_qry.=' ids.service_data_id='.$services.' OR';
	
	$newstring = substr($where_qry,0,-2);
	$where='AND ('.$newstring.')';
}
$qry.= ' WHERE id.visibility =1 AND (id.user_id = 55 OR id.user_id = 61 OR id.user_id = 25 OR id.user_id = 21 OR id.is_franchise="yes") '.$where.' HAVING (distance < 15)
ORDER BY distance LIMIT 0,10';
	}else{
		//$row = $wpdb->get_results("SELECT * FROM th_installer_data where visibility = 1 OR visibility = 2");
		/*$qry ='SELECT *,(((acos(sin(('.$select_lat.'*pi()/180)) * sin((`location_lattitude`*pi()/180))+cos(('.$select_lat.'*pi()/180)) * cos((`location_lattitude`*pi()/180)) * cos((('.$select_lng.'- `location_longitude`)*pi()/180))))*180/pi())*60*1.1515*1.609344) as distance
FROM `th_installer_data` WHERE (visibility =1 OR visibility =2) HAVING (distance < 15)
ORDER BY distance LIMIT 0,10';
//echo $qry;
$row = $wpdb->get_results($qry);*/
$qry ='SELECT DISTINCT id.installer_data_id, id.*,(((acos(sin(('.$select_lat.'*pi()/180)) * sin((id.`location_lattitude`*pi()/180))+cos(('.$select_lat.'*pi()/180)) * cos((id.`location_lattitude`*pi()/180)) * cos((('.$select_lng.'- id.`location_longitude`)*pi()/180))))*180/pi())*60*1.1515*1.609344) as distance
FROM `th_installer_data` id LEFT JOIN th_installer_addi_service as ids ON ids.installer_id=id.installer_data_id';
/*if(count($services)>0){
	$where_qry='';
	foreach ($services as $key => $value) {
		# code...
		$where_qry.=' ids.service_data_id='.$value.' OR';
	}
	$newstring = substr($where_qry,0,-2);
	$where='AND ('.$newstring.')';
}*/
if(!empty($services)){
	$where_qry='';
	$where_qry.=' ids.service_data_id='.$services.' OR';
	$newstring = substr($where_qry,0,-2);
	$where='AND ('.$newstring.')';
}
$qry.= ' WHERE (id.visibility =1 OR id.visibility =2) '.$where.' HAVING (distance < 15)
ORDER BY distance LIMIT 0,10';
	}
		//echo $qry;
		//die;
	$row = $wpdb->get_results($qry);
	if($postal_code != '' || $select_lng != '')
	{
		$installer_km = [];
		$i=0;
	foreach ($row as $data)
	{
		$installer_id = $data->installer_data_id;
		
		$inst_postcode = $data->pincode;
		$inst_lat = $data->location_lattitude;
		$inst_lng = $data->location_longitude;
		if($inst_lat == '' || $inst_lng == '')
		{
			$url = "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($inst_postcode)."&sensor=false&key=".GOOGLE_API_KEY;
			$result_string = file_get_contents($url);
			$result = json_decode($result_string, true);
			$val = $result['results'][0]['geometry']['location'];
			$inst_lat = $val['lat'];
			$inst_lng = $val['lng'];
		}
		 $url = "https://maps.googleapis.com/maps/api/distancematrix/json?key=".GOOGLE_API_KEY."&origins=".$select_lat.",".$select_lng."&destinations=".$inst_lat.",".$inst_lng."&mode=driving&language=pl-PL";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			$response = curl_exec($ch);
			curl_close($ch);
			$response_a = json_decode($response, true);
			$dist = $response_a['rows'][0]['elements'][0]['distance']['text'];
			$time = $response_a['rows'][0]['elements'][0]['duration']['text'];
			$dist = str_replace(' km', '', $dist);
			$dist = str_replace(',', '.', $dist);
			$installer_km[$installer_id] = $dist;
			$install_data[$i]['id']=$installer_id;
			$install_data[$i]['is_franchise']=$data->is_franchise;
			$install_data[$i]['product_id']=$product_id;
			$install_data[$i]['business_name']=$data->business_name;
			$install_data[$i]['address']=$data->address;
			$install_data[$i]['state']=$data->state;
			$install_data[$i]['city']=$data->city;
			$install_data[$i]['pincode']=$data->pincode;
			$install_data[$i]['available_days']=$data->available_days;
			$install_data[$i]['available_time']=$data->available_time;
			$install_data[$i]['lattitude']=$data->location_lattitude;
			$install_data[$i]['longitude']=$data->location_longitude;
			$install_data[$i]['select_lat']=$select_lat;
			$install_data[$i]['select_lng']=$select_lng;
			$install_data[$i]['km']=$dist;
						global $wpdb;
						$sas_sql_arr = '';
						$sfc_sql_arr = '';
						$fc_sql = "SELECT * from th_installer_facilities where type = 'f'";
						$fc_data = $wpdb->get_results($fc_sql);
						$SQL="SELECT meta_value from th_installer_meta where installer_id = '$installer_id' and meta_name = 'facilities'";
						$sfc_sql = $wpdb->get_var($SQL);
						$facilities=array();
						if($sfc_sql){
							$sfc_sql_arr = unserialize($sfc_sql);
							foreach ($fc_data as $key => $fc_row)
							{
								$name = $fc_row->name;
								$icon = $fc_row->icon;
								$f_id = $fc_row->f_id;
								if(in_array($f_id, $sfc_sql_arr)){
									//echo '<li>'.$name.'</li>';
									$facilities[$key]['name']=$name;
									$facilities[$key]['icon']=$icon;
								}
							}
						}
			$install_data[$i]['facilities']=$facilities;
					/*$as_sql = "SELECT * from th_installer_facilities where type = 'as'";
					$as_data = $wpdb->get_results($as_sql);
					$SQL="SELECT meta_value from th_installer_meta where installer_id = '$installer_id' and meta_name = 'additional_services'";
					 $sas_sql = $wpdb->get_var( $SQL);*/
					 $as_data='';
					 $SQL="SELECT * from th_installer_addi_service ias LEFT JOIN th_service_data as sd ON sd.service_data_id=ias.service_data_id where ias.installer_id = '$installer_id' AND ias.service_data_id<>1 AND sd.status=1";
					 $as_data = $wpdb->get_results($SQL);
						if($as_data){
						   // echo '<ul class="installer-type additional_services">';
							$j=0;
							$additional_services=array();
							foreach ($as_data as $key2 => $as_row)
							{
								 $additional_services[$key2]['name']=$as_row->service_name;
								 $additional_services[$key2]['icon']=$as_row->icon;
							}
							//echo '</ul>';
							$install_data[$i]['additional_services']=array();
							$install_data[$i]['additional_services']=$additional_services;
						}else{
							$install_data[$i]['additional_services']=array();
						}
			 ?>
			<?php
			$i++;
	}
				$km = array_column($install_data, 'km');
				array_multisort($km, SORT_ASC, $install_data);
			  //array_column($install_data, 'km');
	echo json_encode($install_data);
	die;
}
else{
	foreach ($row as $data)
		{
			$water = $data->water_service;
			$wifi = $data->wifi_service;
			$tea = $data->tea_service;
			$pickup = $data->car_pickup_service;
			$puncture = $data->puncture;
			$car_wash = $data->car_wash;
	?>
		<div class="single-list" data-id="<?php echo $data->installer_data_id; ?>">
			<div class="left">
				<div class="col-md-2 col-sm-2">
					<img class="aligncenter size-full wp-image-74" src="<?php echo get_site_url(); ?>/wp-content/uploads/2018/09/icons8-car-service-filled-100.png" alt="" width="100" height="100" />
				</div>
				<div class="col-md-4 col-sm-4">
					<div class="address" hidden><strong><?php echo $data->business_name.'</strong><br>'.$data->address.'<br>'.$data->state.'<br>'.$data->city.'-'.$data->pincode; ?>
								</div>
					<h4><?php echo $data->business_name; ?></h4>
					<div><?php echo $data->address; ?></div>
					<div><?php echo $data->city.'-'.$data->pincode; ?></div>
					<div><?php echo $data->state; ?></div>
					<div><?php //echo $data->state; ?></div>
				</div>
			</div>
			<div class="right">
				<div class="col-md-2 col-sm-2">
					<img class="aligncenter size-full wp-image-76" src="<?php echo get_site_url(); ?>/wp-content/uploads/2018/09/icons8-map-marker-100.png" alt="" width="100" height="100" />
				</div>
				<div class="col-md-3 col-sm-3">
					<span class="lattitude" hidden=""><?php echo $data->location_lattitude; ?></span>
					<span class="longitude" hidden=""><?php echo $data->location_longitude; ?></span>
					<div><i class="fa fa-calendar"></i>&nbsp;10 am to 9 pm (<?php echo $data->available_days; ?> )</div>
					<!-- <div><i class="fa fa-clock-o"></i>&nbsp;<?php echo $data->available_time ?></div> -->
					<!-- <div><i class="fa fa-phone"></i>&nbsp;<?php echo $data->contact_no; ?></div> -->
				</div>
				<?php
				   if($vehicle_id != '')
					 {
					  ?>
						<div class="col-md-2 col-sm-2 installer-btn">
							<button class="button installer_btn service-voucher-prd">USE THIS INSTALLER</button>
						</div>
					<?php }
					elseif($product_id != ''){
						?>
						<div class="col-md-2 col-sm-2 installer-btn">
						   <!--  <button class="button installer_btn use_this_installer "data-toggle="modal" data-target="#service_modal">USE THIS INSTALLER</button> -->
							 <button class="button installer_btn confirm_installer ">USE THIS INSTALLER</button>
						</div>
					<?php
					} ?>
			</div>
			<ul class="installer-type">
				<?php
					if($wifi == 'yes'){
						?>
							<li><i class="fas fa fa-sort-amount-up"></i><i class="fas fa fa-wifi"></i>Wifi Services</li>
						<?php
					}
					if($water == 'yes'){
						?>
						<li><i class="fas fa fa-tint"></i>Water Cooler </li>
						<?php
					}
					if($pickup == 'yes'){
						?>
						<li><i class="fas fa fa-car"></i></i>Car Pickup-Dropoff service</li>
						<?php
					}
					if($tea == 'yes'){
						?>
						<li><i class="fas fa fa-coffee"></i>Free Tea/Coffee</li>
						<?php
					}
				?>
			</ul>
			<ul class="installer-type">
				<?php
					if($car_wash == 'yes'){
						?>
							<li><i class="fas fa-car-wash"></i>Car Wash</li>
						<?php
					}
					if($puncture == 'yes'){
						?>
						<li><i class="fas fa-tire"></i>Puncture</li>
						<?php
					}
				?>
			</ul>
		</div>
	<?php
}
}
die();
}
add_action('wp_ajax_search_by_location', 'search_by_location');
add_action('wp_ajax_nopriv_search_by_location', 'search_by_location');
function search_by_location()
{
	$vehicle_id = $_POST['vehicle_type'];
	$product_id = $_POST['product_id'];
	$product_variation = wc_get_product( $product_id );
	$variation_data = $product_variation->get_data();
	$attr_vehicle_typre = $variation_data['attributes']['pa_vehicle-type'];
	$location = $_POST['location'];
	global $wpdb;
	if($vehicle_id != '')
	{
		$row = $wpdb->get_results("SELECT * FROM th_installer_data where visibility = 1");
	}
	elseif ($attr_vehicle_typre == 'car-tyre')
	{
		$row = $wpdb->get_results("SELECT * FROM th_installer_data where visibility = 1");
	}
	elseif($attr_vehicle_typre == 'two-wheeler' || $attr_vehicle_typre == 'three-wheeler')
	{
		$row = $wpdb->get_results("SELECT * FROM th_installer_data where user_id = 55 || user_id = 61");
	}
	else
	{
		$row = $wpdb->get_results("SELECT * FROM th_installer_data where visibility = 1 OR visibility = 2");
	}
	foreach ($row as $data)
	{
		$installer_id = $data->installer_data_id;
		$inst_postcode = $data->pincode;
		$inst_lat = $data->location_lattitude;
		$inst_lng = $data->location_longitude;
		$inst_add = $data->add_for_dis;
		if($inst_lat == '' || $inst_lng == '')
		{
			$url = "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($inst_postcode)."&sensor=false&key=".GOOGLE_API_KEY;
			$result_string = file_get_contents($url);
			$result = json_decode($result_string, true);
			$val = $result['results'][0]['geometry']['location'];
			$inst_lat = $val['lat'];
			$inst_lng = $val['lng'];
		}
		$url="https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$location."&destinations=".$inst_lat.",".$inst_lng."&key=".GOOGLE_API_KEY;
		$url = str_replace(' ', '%20', $url);
		 $result_string = file_get_contents($url);
			$result = json_decode($result_string, true);
		   // var_dump($result);
			$val = $result['rows'][0]['elements'][0]['distance']['text'];
			 $dist = str_replace(' km', '', $val);
			 $dist = str_replace(',', '.', $dist);
			$installer_km[$installer_id] = $dist;
	}
	//var_dump($installer_km);
	asort($installer_km);
   // var_dump($installer_km);
	foreach ($installer_km as $id => $km)
	{
		$sql = "SELECT * FROM th_installer_data where installer_data_id = '$id'";
		$row = $wpdb->get_results($sql);
		$km = str_replace(',', '.', $km);
		foreach ($row as $data)
		{
			$water = $data->water_service;
			$wifi = $data->wifi_service;
			$tea = $data->tea_service;
			$pickup = $data->car_pickup_service;
	?>
		<div class="single-list" data-id="<?php echo $data->installer_data_id; ?>">
			<div class="left">
				<div class="col-md-2 col-sm-2">
					<img class="aligncenter size-full wp-image-74" src="<?php echo get_site_url(); ?>/wp-content/uploads/2018/09/icons8-car-service-filled-100.png" alt="" width="100" height="100" />
				</div>
				<div class="col-md-4 col-sm-4">
					<div class="address" hidden><strong><?php echo $data->business_name.'</strong><br>'.$data->address.'<br>'.$data->state.'<br>'.$data->city.'-'.$data->pincode; ?>
					</div>
					<h4><?php echo $data->business_name; ?></h4>
					<div><?php echo $data->address; ?></div>
					<div><?php echo $data->city.'-'.$data->pincode; ?></div>
					<div><?php echo $data->state; ?></div>
				</div>
			</div>
			<div class="right">
				<div class="col-md-2 col-sm-2">
					<img class="aligncenter size-full wp-image-76" src="<?php echo get_site_url(); ?>/wp-content/uploads/2018/09/icons8-map-marker-100.png" alt="" width="100" height="100" />
				</div>
				<div class="col-md-3 col-sm-3">
					<span class="lattitude" hidden=""><?php echo $data->location_lattitude; ?></span>
					<span class="longitude" hidden=""><?php echo $data->location_longitude; ?></span>
					<div class="km"><i class="fa fa-map-marker"></i>&nbsp;<?php echo $km; ?> km</div>
					<div><i class="fa fa-calendar"></i>&nbsp;10 am to 9 pm (<?php echo $data->available_days; ?> )</div>
				   <!--  <div><i class="fa fa-clock-o"></i>&nbsp;<?php echo $data->available_time ?></div> -->
					<div><i class="fa fa-phone"></i>&nbsp;<?php echo $data->contact_no; ?></div>
				</div>
				<?php
				if($product_id != ''){
					?>
					<div class="col-md-2 col-sm-2 installer-btn">
						<button class="button installer_btn use_this_installer "data-toggle="modal" data-target="#service_modal">USE THIS INSTALLER</button>
					</div>
				<?php
				}else{?>
					<div class="col-md-2 col-sm-2 installer-btn">
						<button class="button installer_btn service-voucher-prd"data-toggle="modal" data-target="#service_modal">USE THIS INSTALLER</button>
					</div>
				<?php }?>
			</div>
			<ul class="installer-type">
				<!-- <li><i class="fas fa fa-sort-amount-up"></i><i class="fas fa fa-wifi"></i>Wifi Services</li>
				<li><i class="fas fa fa-car"></i></i>Car Pickup-Dropoff service</li>
				<li><i class="fa fa-wrench"></i>Shuttle Service</li> -->
				<?php
					if($wifi == 'yes'){
						?>
							<li><i class="fas fa fa-sort-amount-up"></i><i class="fas fa fa-wifi"></i>Wifi Services</li>
						<?php
					}
					if($water == 'yes'){
						?>
						<li><i class="fas fa fa-tint"></i>Water Cooler </li>
						<?php
					}
					if($pickup == 'yes'){
						?>
						<li><i class="fas fa fa-car"></i></i>Car Pickup-Dropoff service</li>
						<?php
					}
					if($tea == 'yes'){
						?>
						<li><i class="fas fa fa-coffee"></i>Free Tea/Coffee</li>
						<?php
					}
				?>
			</ul>
		</div>
<?php
		}
	}
die();
}
add_action('woocommerce_checkout_process', 'wh_phoneValidateCheckoutFields');
function wh_phoneValidateCheckoutFields()
{
	   global $wpdb;
		$billing_phone = filter_input(INPUT_POST, 'billing_phone');
		$billing_city = filter_input(INPUT_POST, 'billing_city');
		$billing_address_1 = filter_input(INPUT_POST, 'billing_address_1');
		$billing_postcode = filter_input(INPUT_POST, 'billing_postcode');
		$shipping_postcode = filter_input(INPUT_POST, 'shipping_postcode');
		$ship_to_different = filter_input(INPUT_POST, 'ship_to_different_address');
		if($ship_to_different){
		   $address = $shipping_postcode;
		}else{
			$address = $billing_postcode;
		}
		//echo  $address = $billing_postcode;
		if($address != "" && $address != null){
   /* $coordinates = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address) . '&sensor=true&key=AIzaSyCmMCGIlJKPcPn_hjlgo6j-RMfN4ZtOH70');
	$coordinates = json_decode($coordinates);*/
	$url ='https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address) . '&sensor=true&key='.GOOGLE_API_KEY;
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, $url );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	$response = curl_exec( $ch );
	$coordinates = json_decode($response);
   $lat = $coordinates->results[0]->geometry->location->lat;
   $lng = $coordinates->results[0]->geometry->location->lng;
$qry ='SELECT *,(((acos(sin(('.$lat.'*pi()/180)) * sin((`latitude`*pi()/180))+cos(('.$lat.'*pi()/180)) * cos((`latitude`*pi()/180)) * cos((('.$lng.'- `longitude`)*pi()/180))))*180/pi())*60*1.1515*1.609344) as distance
FROM `th_city_delivery_range` WHERE status =1 HAVING (distance < km_range OR distance < km_distance)
ORDER BY km_range';
$results=$wpdb->get_results($qry);
		   $total_result_rows = $wpdb->num_rows;
			if($total_result_rows){
			wc_clear_notices();
			}else{
			   wc_add_notice(__("Sorry! We are not providing free delivery to your area at this point,<br> If you are interested, we can deliver this Tyre by adding minimal transportation charges to your invoice! Interested please call 1800 233 5551"), 'error');
			}
}
	 //   die("hi");
  // wc_clear_notices();
	/*if($billing_address_1 == ''){
		//wc_add_notice(__('Fill all required fields, please check your input.'), 'error');
	}*/
}
add_filter('woocommerce_checkout_required_field_notice', 'misha_change_notice' );
function misha_change_notice( $notice ){
	if($notice != ''){
		return 'Fill all required fields';
	}
}
add_action( 'woocommerce_after_checkout_billing_form', 'custom_checkout_fields_before_billing_details', 20 );
function custom_checkout_fields_before_billing_details()
{
	$domain = 'woocommerce';
	$checkout = WC()->checkout;
	global $woocommerce,$wpdb;
	$user_id = get_current_user_id();
	$SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
	$franchise=$wpdb->get_row($SQL);
	$current_user = get_current_user_id();
	$gst_no = get_user_meta( $current_user, 'gst_no', true );
	$cmp_name = get_user_meta( $current_user, 'company_name', true );
	$cmp_add = get_user_meta( $current_user, 'company_add', true );
foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item ){ $flag=$cart_item['offline-purchase'];	}
	if(empty($franchise) || $flag=='yes'){
?>
	<div class="clearfix"></div>
	<div id="my_custom_checkout_field">
		<label>
			<?php
	if($gst_no || $cmp_name || $cmp_add)
	{
		if(current_user_can('shop_manager'))
		{
	?>
		<input type="checkbox" class="check-gst-no">&nbsp;
	<?php
		}
		else{
			?>
				<input type="checkbox" class="check-gst-no">&nbsp;
			<?php
		}
	}
	else
	{
		echo '<input type="checkbox" class="check-gst-no">';
	}?>
		You have a GST number?
	</label>
		<div class="gst-field-container">
		</div>
	</div>
		<?php
	}
}
// Custom checkout fields validation
add_action('woocommerce_checkout_process', 'my_custom_checkout_field_process');
function my_custom_checkout_field_process()
{
	 global $woocommerce , $wpdb;
    $user_id = get_current_user_id();
    $SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
    $franchise=$wpdb->get_row($SQL);
    $franchise_id=$franchise->installer_data_id;
    if($franchise){
    	$SQL="SELECT * FROM th_franchise_payment WHERE 1=1 AND franchise_id = '$franchise_id'  ORDER BY id DESC LIMIT 0,1";
    	$balance = $wpdb->get_row($SQL);
    	$cartTotal=WC()->cart->total;
    	if($cartTotal>$balance->close_balance){
    		wc_add_notice( __( 'You have not enough  wallet balance'), 'error' );	
    	}
    	//wc_add_notice( __( 'You have not enough  wallet balance.'), 'error' );
    }
	//wc_add_notice( __( 'You have not enough  wallet balance. bypass'), 'error' );
	$is_correct = preg_match('/^[0-9]{10,10}$/', $_POST['billing_phone']);
	if ( $_POST['billing_phone'] && !$is_correct) {
		wc_add_notice( __( 'The Phone field should be <strong>10 digits</strong>.' ), 'error' );
	}
	/*$is_correct1 = preg_match('/^[a-zA-Z]{10,30}$/', $_POST['billing_first_name']);
	if ( $_POST['billing_first_name'] && !$is_correct1) {
		wc_add_notice( __( 'Billing First name is too short' ), 'error' );
	}
	$is_correct2 = preg_match('/^[a-zA-Z]{10,30}$/', $_POST['billing_last_name']);
	if ( $_POST['billing_last_name'] && !$is_correct2) {
		wc_add_notice( __( 'Billing Last name is too short' ), 'error' );
	}*/
	if(isset($_POST['cmp_name']) || isset($_POST['cmp_add']) || isset($_POST['gst_no'])){
	   if ( empty($_POST['cmp_name']) || empty($_POST['cmp_add']) || empty($_POST['gst_no']) ){
			wc_add_notice( __( 'Fill All require fields' ), 'error' );
	   }
	   if(is_valid_gstin($_POST['gst_no'])==false){
		wc_add_notice( __( 'Please enter valid GST number' ), 'error' );
		}
	}
}
function is_valid_gstin($gstin) {
		$regex = "/^([0][1-9]|[1-2][0-9]|[3][0-5])([a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}[1-9a-zA-Z]{1}[zZ]{1}[0-9a-zA-Z]{1})+$/";
		return preg_match($regex, $gstin);
}
// Save custom checkout fields the data to the order
add_action('woocommerce_checkout_create_order','my_custom_checkout_field_update_meta');
function my_custom_checkout_field_update_meta( $order )
{
	global $woocommerce,$wpdb;
	/*$user_id = get_current_user_id();
	$SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
	$franchise=$wpdb->get_row($SQL);*/
	$current_user =$_POST['created_user_id'];
	if(empty($current_user)){
		$current_user = get_current_user_id();
	}
	$gst_no = get_user_meta( $current_user, 'gst_no', true );
	$cmp_name = get_user_meta( $current_user, 'company_name', true );
	$cmp_add = get_user_meta( $current_user, 'company_add', true );
	$gst_email = get_user_meta( $current_user, 'gst_email', true );
	 if( isset($_POST['gst_no']) && ! empty($_POST['gst_no']) )
		{
			//echo $_POST['gst_no'];
		    $order->update_meta_data( '_gst_no', sanitize_text_field( $_POST['gst_no'] ) );
			if($gst_no == '')
			{
				//echo 'user';
				update_user_meta( $current_user, 'gst_no',  $_POST['gst_no'] );
			}
		}else{
			$order->update_meta_data( '_gst_no', sanitize_text_field( $_POST['gst_no'] ) );
		}
	if( isset($_POST['cmp_name']) && ! empty($_POST['cmp_name']) ){
		   $order->update_meta_data( '_cmp_name', sanitize_text_field( $_POST['cmp_name'] ) );
		   if($cmp_name == ''){
				update_user_meta( $current_user, 'cmp_name', $_POST['cmp_name'] );
			}
	}
   if( isset($_POST['cmp_add']) && ! empty($_POST['cmp_add']) ){
	   $order->update_meta_data( '_cmp_add', sanitize_text_field( $_POST['cmp_add'] ) );
		if($cmp_add == ''){
			update_user_meta( $current_user, 'cmp_add', $_POST['cmp_add'] );
		}
   }
   if( isset($_POST['gst_email']) && ! empty($_POST['gst_email']) ){
	   $order->update_meta_data( '_gst_email', sanitize_text_field( $_POST['gst_email'] ) );
		if($gst_email == ''){
			update_user_meta( $current_user, 'gst_email', $_POST['gst_email'] );
		}
   }
}
add_action('wp_ajax_gst_fields', 'gst_fields');
add_action('wp_ajax_nopriv_gst_fields', 'gst_fields');
function gst_fields(){
	$domain = 'woocommerce';
	$checkout = WC()->checkout;
	global $woocommerce;
	global $wpdb;
	$user_id = get_current_user_id();
	$SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
	$franchise=$wpdb->get_row($SQL);	
	foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item ) { 
		$flag=$cart_item['offline-purchase']; 
	}
	$current_user = get_current_user_id();
	
	if($flag == 'yes' && isset($franchise)) { 
			$gst_no = '';
			$gst_email = '';
			$cmp_name = '';
			$cmp_add = '';
	} else {
			$gst_no = get_user_meta( $current_user, 'gst_no', true );
			$gst_email = get_user_meta( $current_user, 'gst_email', true );
			$cmp_name = get_user_meta( $current_user, 'company_name', true );
			$cmp_add = get_user_meta( $current_user, 'company_add', true );
	}		
	if(current_user_can('shop_manager')){
		$gst_no = '';
		$cmp_add = '';
		$cmp_name = '';
		$gst_email = '';
	}
	echo '<div>Invoice with GST will be available in your email once payment process successfully done</div>';
	woocommerce_form_field( 'cmp_name', array(
		'type'          => 'text',
		'label'         => __('Company Name', $domain ),
		'placeholder'   => __('Company Name', $domain ),
		'class'         => array('my-field-class form-row-first'),
		'required'      => true, // or false
		), $cmp_name);
	  woocommerce_form_field( 'cmp_add', array(
		'type'          => 'text',
		'label'         => __('Company Address', $domain ),
		'placeholder'   => __('Company Address', $domain ),
		'class'         => array('my-field-class form-row-last'),
		'required'      => true, // or false
		), $cmp_add);
	  woocommerce_form_field( 'gst_no', array(
		'type'          => 'text',
		'label'         => __('GST No', $domain ),
		'placeholder'   => __('GST No', $domain ),
		'class'         => array('my-field-class form-row-first'),
		'maxlength'		=> '15',
		'required'      => true, // or false
		), $gst_no);
	  woocommerce_form_field('gst_email', array(
		'type'          => 'text',
		'label'         => __('Email Address', $domain ),
		'placeholder'   => __('Email Address', $domain ),
		'class'         => array('my-field-class form-row-last'),
		'required'      => false, // or false
		), $gst_email);
	die();
}
// create custom end point
function my_custom_endpoints() {
	add_rewrite_endpoint( 'installer-login', EP_ROOT | EP_PAGES );
	add_rewrite_endpoint( 'registration', EP_ROOT | EP_PAGES );
	add_rewrite_endpoint( 'register', EP_ROOT | EP_PAGES );
}
add_action( 'init', 'my_custom_endpoints' );
function my_custom_query_vars( $vars ) {
	$vars[] = 'installer-login';
	$vars[] = 'registration';
	$vars[] = 'register';
	return $vars;
}
add_filter( 'query_vars', 'my_custom_query_vars', 0 );
function my_custom_flush_rewrite_rules() {
   // flush_rewrite_rules();
}
add_action( 'wp_loaded', 'my_custom_flush_rewrite_rules' );
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields', 30);
function custom_override_checkout_fields( $fields )
{
	global $wpdb , $woocommerce;
	$current_user = get_current_user_id();
	$session_id = WC()->session->get_customer_id();
	$destination = [];
	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item )
	{
		$services = "SELECT *
					FROM th_cart_item_installer
					WHERE cart_item_key = '$cart_item_key' and session_id = '$session_id'
					and order_id = ''";
					 $row = $wpdb->get_results($services);
		foreach ($row as $data)
		{
			$destination[] = $data->destination;
		}
	}
	if(in_array('0', $destination))
	{
		//$fields['billing']['billing_postcode']['default'] = $_SESSION['current_pincode'];
		//$fields['billing']['shipping_postcode']['default'] = $_SESSION['current_pincode'];
		//add_filter( 'woocommerce_ship_to_different_address_checked', '__return_true' );
		//unset($fields['billing']['billing_postcode']);
		//unset($fields['billing']['billing_city']);
		//unset($fields['billing']['billing_state']);
		//unset($fields['billing']['billing_postcode']);
		//$fields['billing']['billing_city']['default'] = $_SESSION['current_city'];
		//$fields['billing']['billing_state']['default'] = $_SESSION['current_state'];
		//$fields['billing']['billing_postcode']['default'] = $_SESSION['current_pincode'];
		 //unset($fields['billing']['billing_postcode']);
		 $fields['billing']['billing_address_2']['label'] = 'Street address 2';
		$fields['billing']['billing_address_1']['class'][0] = 'form-row-first';
		$fields['billing']['billing_address_2']['class'][0] = 'form-row-last';
		$fields['billing']['billing_city']['class'][0] = 'form-row-first';
		$fields['billing']['billing_postcode']['class'][0] = 'form-row-last';
		 $fields['billing']['billing_phone']['class'][0] = 'form-row-first';
		 $fields['billing']['billing_email']['class'][0] = 'form-row-last';
		 $fields['shipping']['shipping_address_2']['label'] = 'Street address 2';
		$fields['shipping']['shipping_address_1']['class'][0] = 'form-row-first';
		$fields['shipping']['shipping_address_2']['class'][0] = 'form-row-last';
		$fields['shipping']['shipping_city']['class'][0] = 'form-row-first';
		$fields['shipping']['shipping_postcode']['class'][0] = 'form-row-last';
		//$fields['shipping']['shipping_phone']['class'][0] = 'form-row-first';
		//$fields['shipping']['shipping_email']['class'][0] = 'form-row-last';
		//unset($fields['billing']['billing_address_1']);
		//unset($fields['billing']['billing_address_2']);
	}
	else
	{
			global $wpdb;
			$user_id = get_current_user_id();
			$SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
			$franchise=$wpdb->get_row($SQL);
				unset($fields['billing']['billing_city']);
				//unset($fields['billing']['billing_state']);
				//unset($fields['billing']['billing_postcode']);
				//$fields['billing']['billing_city']['default'] = $_SESSION['current_city'];
				//$fields['billing']['billing_state']['default'] = $_SESSION['current_state'];
				//$fields['billing']['billing_postcode']['default'] = $_SESSION['current_pincode'];
				 unset($fields['billing']['billing_postcode']);
				 $fields['billing']['billing_phone']['class'][0] = 'form-row-first';
				 $fields['billing']['billing_email']['class'][0] = 'form-row-last';
				 unset($fields['billing']['billing_address_1']);
				 unset($fields['billing']['billing_address_2']);
		$user_meta=get_userdata($current_user);
		$user_roles=$user_meta->roles;
		if($user_roles[0] == "Installer")
		{
			if(empty($franchise)){
				unset($fields['billing']['billing_last_name']);
			}
		}
	}
	unset($fields['billing']['billing_company']);
	unset($fields['billing']['billing_country']);
	unset($fields['shipping']['shipping_company']);
	unset($fields['shipping']['shipping_country']);
	unset($fields['shipping']['shipping_state']);
	unset($fields['billing']['billing_state']);
			global $wpdb;
			$user_id = get_current_user_id();
			$SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
			$franchise=$wpdb->get_row($SQL);
			foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item ){ $flag=$cart_item['offline-purchase'];	}
			if(!empty($franchise) && $flag!='yes'){
				$fields['billing']['billing_first_name']['custom_attributes'] = array('readonly'=>'readonly');
   				$fields['billing']['billing_last_name']['custom_attributes'] = array('readonly'=>'readonly');
   				$fields['billing']['billing_phone']['custom_attributes'] = array('readonly'=>'readonly');
   				$fields['billing']['billing_email']['custom_attributes'] = array('readonly'=>'readonly');
			}
	return $fields;
}
add_filter( 'woocommerce_billing_fields', 'filter_billing_fields', 30, 1 );
function filter_billing_fields( $billing_fields ) {
	// Only on checkout page
	if( ! is_checkout() ) return $billing_fields;
	$billing_fields['billing_city']['required'] = false;
	$billing_fields['billing_state']['required'] = false;
	//$billing_fields['billing_postcode']['required'] = false;
	$role=get_user_role();
	$role_array=array('administrator','shop_manager','btobpartner');
	if(in_array($role,$role_array)){
		$billing_fields['billing_email']['required'] = false;
		$billing_fields['billing_postcode']['required'] = false;
	}else{
		$billing_fields['billing_email']['required'] = false;
		$billing_fields['billing_postcode']['required'] = true;
	}
	return $billing_fields;
}
//make shipping fields not required in checkout
/*add_filter( 'woocommerce_shipping_fields',
'wc_npr_filter_shipping_fields', 10, 1 );*/
function wc_npr_filter_shipping_fields( $address_fields ) {
  //  $address_fields['shipping_first_name']['required'] = false;
  //  $address_fields['shipping_last_name']['required'] = false;
	return $address_fields;
}
add_filter( 'woocommerce_checkout_get_value', 'populating_checkout_fields', 110, 2 );
function populating_checkout_fields ( $value, $input )
{
	$token = ( ! empty( $_GET['token'] ) ) ? $_GET['token'] : '';
	global $wpdb , $woocommerce;
	$current_user = get_current_user_id();
	$user_meta=get_userdata($current_user);
	//var_dump($user_meta);
	$user_roles=$user_meta->roles;
	if($user_roles[0] == "Installer")
	{
	   // echo $name = $user_meta->display_name;
		//echo $input;
		$user_id = get_current_user_id();
		$SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
		$franchise=$wpdb->get_row($SQL);
		if($franchise){
			if($_SESSION['cust_type']=='offline'){
					$customer_id=$_SESSION['fran_user_id'];
					$mobile_no=$_SESSION['mobile_no'];
						$first_name=(empty(get_user_meta($customer_id, 'billing_first_name', true ))) ? get_user_meta($customer_id, 'first_name', true ) : get_user_meta($customer_id, 'billing_first_name', true );
					$last_name=(empty(get_user_meta($customer_id, 'billing_last_name', true ))) ? get_user_meta($customer_id, 'last_name', true ) : get_user_meta($customer_id, 'billing_last_name', true );
			}else{
				$customer_id=$user_id;
				$mobile_no=$franchise->contact_no;
				$fullname=explode(' ',$franchise->contact_person);
				$first_name=$fullname[0];
				$last_name=$fullname[1];
			}
				
			
					$email=get_user_by('login',$_SESSION['mobile_no']);
					$emailid=(empty(get_user_meta($customer_id, 'billing_email', true ))) ? $email->user_email : get_user_meta($customer_id, 'billing_email', true );
					$checkout_fields = array(
					'billing_first_name'    =>(empty($first_name)) ? '' : $first_name,
					'billing_last_name'    =>(empty($last_name)) ? '' : $last_name,
					'billing_phone'         =>$mobile_no,
					'billing_email'         =>(empty($emailid)) ? '' : $emailid,
							);
						foreach( $checkout_fields as $key_field => $field_value ){
							if( $input == $key_field && ! empty( $field_value ) ){
								$value = $field_value;
							}
						}
		}else{
			$checkout_fields = array(
				'billing_first_name'    => $user_meta->display_name,
				'billing_phone'         => $user_meta->user_nicename,
			);
			foreach( $checkout_fields as $key_field => $field_value ){
				if( $input == $key_field && ! empty( $field_value ) ){
					$value = $field_value;
				}
			}
		}
	}
	/*echo '<pre>';
	print_r($value);
	echo '</pre>';*/
	return $value;
}
// Installer Must login with installer-login page
/*add_filter('wp_authenticate_user', function($user)
{
	$role = $user->roles[0];
	if($role == 'Installer')
	{
		global $wp_query;
		 $current_url="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		 $installer_login_url = get_site_url().'/my-account/';
		if($current_url == $installer_login_url)
		{
			 throw new Exception( '<strong>' . __( 'Error', 'woocommerce' ) . ':</strong> ' . __( 'You are installer Please login with <a href="'.get_site_url().'/my-account/installer-login/">Installer Page </a>', 'woocommerce' ) );
		}
		else{
			return $user;
		}
	}
	elseif($role == 'customer')
	{
		$user_id = $user->ID;
		$user_info = get_user_meta($user_id);
		$active_status = $user_info['_active'][0];
		if($active_status == 1)
		{
			return $user;
		}
		else{
			throw new Exception( '<strong>' . __( 'Error', 'woocommerce' ) . ':</strong> ' . __( 'You are not active user! Please verify your mobile no', 'woocommerce' ) );
		}
	}
	else
	{
		return $user;
	}
}, 10, 2);*/
function register_my_menu() {
  register_nav_menu('installer-menu',__( 'Installer Menu' ));
}
add_action( 'init', 'register_my_menu' );
add_action( 'woocommerce_before_order_itemmeta', 'admin_custom_order_itemmeta', 10, 3 );
function admin_custom_order_itemmeta( $item_id, $item, $_product )
{
	global $wpdb , $woocommerce , $post;
	$order = new WC_Order($post->ID);
	$order_id = $order->get_id();
	$item_data = $item->get_data();
	$item_id = $item_data['product_id'];
	if($item_data['variation_id'] != ''){
		$item_id = $item_data['variation_id'];
	}
	$item_qty = $item_data['quantity'];
	$sku = 'service_voucher';
	$service_voucher_prd = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );
	if($item_id == $service_voucher_prd)
	{
		$voucher_info = "SELECT *
					FROM th_cart_item_service_voucher
					WHERE product_id = '$service_voucher_prd' and order_id = '$order_id'";
		$voucher_row = $wpdb->get_results($voucher_info);
		foreach ($voucher_row as $key => $voucher)
		{
			$voucher_id = $voucher->service_voucher_id;
			$voucher->voucher_name;
			$installer_id = $voucher->installer_id;
			$vehicle_id = $voucher->vehicle_id;
			$installer_name = $wpdb->get_var( $wpdb->prepare( "SELECT business_name FROM th_installer_data WHERE installer_data_id='%s' LIMIT 1" , $installer_id) );
			$rate = $voucher->rate;
			$voucher_qty = $voucher->qty;
			$amount = $rate * $voucher_qty;
			$vehicle_name = $wpdb->get_var( $wpdb->prepare( "SELECT vehicle_type FROM th_vehicle_type WHERE vehicle_id='%s' LIMIT 1" , $vehicle_id) );
?>
			<div>
				<b>Service Voucher</b>-<?php if($vehicle_id == 11){echo 'Promotional';}else{ echo $vehicle_name;} ?>
				<p><?php echo $installer_name; ?></p>
			</div>
			<div>
				<?php echo $voucher->voucher_name; ?>
				<strong> x <?php echo $voucher->qty.' - '; ?></strong><?php echo get_woocommerce_currency_symbol().number_format($amount,2,'.',''); ?>
			</div>
<?php
		}
	}
else{
	$installer = "SELECT *
				FROM th_cart_item_installer
				WHERE order_id = '$order_id' and product_id = '$item_id'";
	$row = $wpdb->get_results($installer);
	/*$supplier = "SELECT *
				FROM th_suuplier_product_order
				WHERE order_id = '$order_id' and product_id = '$item_id'";
	$rowSupplier = $wpdb->get_row($supplier);*/
	$SQL="SELECT idata.business_name FROM th_suuplier_product_order cii
			LEFT JOIN th_supplier_data as idata ON idata.supplier_data_id=cii.supplier_id
			WHERE cii.order_id='".$order_id."' and product_id = '".$item_id."'";
			$supplier_name=$wpdb->get_row($SQL);
	if(!empty($row))
	{
		foreach ($row as $key => $installer)
		{
			$destination = $installer->destination;
			$installer_table_id = $installer->cart_item_installer_id;
			$installer_id = $installer->installer_id;
			$vehicle_id = $installer->vehicle_id;
			$installer_name = $wpdb->get_var( $wpdb->prepare( "SELECT business_name FROM th_installer_data WHERE installer_data_id='%s' LIMIT 1" , $installer_id) );
			$vehicle_name = $wpdb->get_var( $wpdb->prepare( "SELECT vehicle_type FROM th_vehicle_type WHERE vehicle_id='%s' LIMIT 1" , $vehicle_id) );
			$selected_vehicle_id = $installer->vehicle_id;
			//$selected_tyre = $installer->no_of_tyre;
		}
		echo '<div class="supplier-name"><b>Supplier : </b>'.$supplier_name->business_name.'</div>';
		if($destination == '0')
		{
			$home_delivery_charge = 100 * $item_qty;
			$charge_html = get_woocommerce_currency_symbol().number_format($home_delivery_charge,2,'.','');
				echo '<div>Deliver To Home - '.$charge_html.'</div>';
		}
		else
		{
	?>
			<div class="installer-name"><?php echo '<b>'.$installer_name.'</b>'; ?></div>
	<?php
				if($vehicle_name !='')
				{
				?>
					<div class="vehicle-typre"><b>Vehicle Type : </b><?php echo $vehicle_name; ?></div>
	<?php       }
	?>
				<div class="product-service-list">
	<?php
				$services = "SELECT *
						FROM th_cart_item_services
						WHERE order_id = '$order_id' and product_id = '$item_id'";
				$row = $wpdb->get_results($services);
				$service_name = '';
				$service_list = [];
				$amount = '';
				$total_amout = 0;
				foreach ($row as $key => $service)
				{
					$tyre_count = $service->tyre;
					$service_name = $service->service_name;
					$rate = $service->rate;
					$service_list[$service_name] = $tyre_count;
					if($service_name == 'Wheel alignment'){
						$amount = $rate;
						echo '<div>'.$service_name.' '.get_woocommerce_currency_symbol().$amount.'</div>';
					}
					elseif($service_name == 'Tyre Fitment'){
						$amount = $rate;
						if($rate == 0){
							echo '<div>'.$service_name.'- free</div>';
						}else{
							echo '<div>'.$service_name.' '.get_woocommerce_currency_symbol().$amount.'</div>';
						}
					}
					else{
						$amount = $tyre_count * $rate;
						echo '<div>'.$service_name.' '.$tyre_count.' - '.get_woocommerce_currency_symbol().$amount.'</div>';
					}
					$total_amout = $total_amout + $amount;
				}
				?>
			</div>
	<?php
		}
	}
	}
}
add_action( 'woocommerce_admin_order_items_after_line_items','admin_order_details');
function admin_order_details()
{
	global $wpdb , $woocommerce , $post;
	$order = new WC_Order($post->ID);
	$order_id = $order->get_id();
	$order_items = $order->get_items();
	foreach ($order_items as $key => $item)
	{
		$item_data = $item->get_data();
		$item_id = $item_data['product_id'];
		if($item_data['variation_id'] != ''){
			$item_id = $item_data['variation_id'];
		}
		$item_qty = $item_data['quantity'];
		$installer = "SELECT *
				FROM th_cart_item_installer
				WHERE order_id = '$order_id' and product_id = '$item_id'";
		$row = $wpdb->get_results($installer);
		if(!empty($row))
		{
			foreach ($row as $key => $installer)
			{
			   $destination = $installer->destination;
				$installer_table_id = $installer->cart_item_installer_id;
				$installer_id = $installer->installer_id;
				$vehicle_id = $installer->vehicle_id;
				$installer_name = $wpdb->get_var( $wpdb->prepare( "SELECT business_name FROM th_installer_data WHERE installer_data_id='%s' LIMIT 1" , $installer_id) );
				$vehicle_name = $wpdb->get_var( $wpdb->prepare( "SELECT vehicle_type FROM th_vehicle_type WHERE vehicle_id='%s' LIMIT 1" , $vehicle_id) );
				$selected_vehicle_id = $installer->vehicle_id;
				//$selected_tyre = $installer->no_of_tyre;
			}
			if($destination == '0')
			{
				$home_delivery_charge = 100 * $item_qty;
				$charge_html = get_woocommerce_currency_symbol().number_format($home_delivery_charge,2,'.','');
			?>
			<tr class="custom-order-line">
				<td></td>
				<td><?php echo get_the_title($item_id ).'<div>Deliver To Home</div>'; ?></td>
				<td></td>
				<td></td>
				<td><?php echo $charge_html; ?></td>
			</tr>
			<?php
			}
			else
			{
		?>
				<tr>
					<td></td>
					<td><?php echo get_the_title($item_id ).'<div>Deliver To Installer</div>'; ?></td>
					<div class="product-service-list">
		<?php
					$services = "SELECT *
							FROM th_cart_item_services
							WHERE order_id = '$order_id' and product_id = '$item_id'";
					$row = $wpdb->get_results($services);
					$service_name = '';
					$service_list = [];
					$amount = '';
					$total_amout = 0;
					foreach ($row as $key => $service)
					{
						$tyre_count = $service->tyre;
						$service_name = $service->service_name;
						$rate = $service->rate;
						$service_list[$service_name] = $tyre_count;
						if($service_name == 'Wheel alignment')
						{
							$amount = $rate;
						}
						else{
							$amount = $tyre_count * $rate;
						}
						$total_amout = $total_amout + $amount;
					}
					?>
					<td></td>
					<td></td>
				<td><?php echo get_woocommerce_currency_symbol().number_format($total_amout,2,'.',''); ?></td>
				</tr>
		<?php
			}
		}
		}
}
// add the action
/*add_action( 'woocommerce_new_order', 'action_woocommerce_new_order', 10, 3 );
function action_woocommerce_new_order( $orderID )
{
	$current_user = wp_get_current_user();
	$mobile_no = $current_user->user_login;
	$order = new WC_Order( $orderID );
	$order_data = $order->get_data();
	$order_id = $order_data['id'];
	if($mobile_no == ''){
		$order = new WC_Order( $orderID );
		$order_data = $order->get_data();
		$mobile_no = $order_data['billing']['phone'];
		$order_id = $order_data['id'];
	}
}*/
add_action( 'woocommerce_checkout_order_processed', 'action_woocommerce_checkout_order_processed', 10, 1 );
function action_woocommerce_checkout_order_processed( $order_id ) {
	global $wpdb,$woocommerce;
		$SQL="SELECT * FROM th_installer_data WHERE is_franchise='yes' AND user_id='".get_current_user_id()."'";
	    $franchise=$wpdb->get_row($SQL);
		
	 $order = wc_get_order( $order_id );
	 $session_id = WC()->session->get_customer_id();
	 $customer_id=$session_id;
	 $tyreGST = get_option('tyre_gst');
	 $tyreGSTD = (100 + $tyreGST);
	 $serviceGST = get_option('service_gst');
	 $serviceGSTD = (100 + $serviceGST);
	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item )
	{
			$line_total=$cart_item['line_total'];
			$product_id=$cart_item['variation_id'];
			if(empty($product_id)){
				$product_id=$cart_item['product_id'];	
			}
			
			$SQL="SELECT * FROM th_cart_item_installer  WHERE  cart_item_key='".$cart_item_key."' AND order_id = 0";
			$result=$wpdb->get_row($SQL);
			$installer_id=$result->installer_id;
			$vehicle_id=$result->vehicle_id;
			$SQLSER="SELECT * FROM th_cart_item_service_voucher  WHERE  cart_item_key='".$cart_item_key."' AND order_id = 0";
			$resultSER=$wpdb->get_row($SQLSER);
			if(empty($installer_id)){
				$installer_id=$resultSER->installer_id;	
			}
			if(empty($vehicle_id)){
				$vehicle_id=$resultSER->vehicle_id;
				$line_total=0;
			}
			$SQL="SELECT * FROM th_installer_data WHERE is_franchise='yes' AND user_id='".$customer_id."'";
	    	$franchise1=$wpdb->get_row($SQL);
	    	if(empty($franchise1->user_id) && empty($franchise)){
				/*$SQLSHIV="SELECT * FROM `th_supplier_products_final` where product_id='$product_id' AND updated_date>DATE_SUB(NOW(),INTERVAL 1 YEAR)  GROUP by id HAVING min(tyre_price+tube_price) ORDER BY (tyre_price+tube_price) ASC LIMIT 0,1";*/
			    $SQLSHIV="SELECT * FROM `th_supplier_products_final` where product_id='$product_id'  ORDER BY (tyre_price+tube_price) ASC LIMIT 0,1";
			    $productsshiv=$wpdb->get_row($SQLSHIV);
			    $tube_price = $productsshiv->tube_price;
			    $tyre_price = $productsshiv->tyre_price;
				
				
				
				$cart_item_key=$cart_item_key;
				$product_id=$product_id;
				$vehicle_id=$vehicle_id;
				$franchise_id=$installer_id;
				$tyre_billing_amount_gst=$line_total;
				$purchase_with_gst= ($tube_price + $tyre_price) * $cart_item['quantity'];
				$customer_pay_type='CC';
				$qty=$cart_item['quantity'];
				 $SQLProcedure="CALL FranchiseProfit('".$customer_id."','".$cart_item_key."',$product_id,$vehicle_id,$franchise_id,$tyre_billing_amount_gst,$purchase_with_gst,'".$customer_pay_type."',$qty,$order_id)";
				 
				$wpdb->query($SQLProcedure);
				
			}
	}	
	foreach ( $order->get_items() as $item_id => $item )
	{
		if($item['variation_id'] != ''){
			$product_id = $item['variation_id'];
		}
		else{
			$product_id = $item['product_id'];
		}
		$update = $wpdb->get_results("UPDATE th_cart_item_installer SET order_id = '$order_id' WHERE session_id = '$session_id' and product_id = '$product_id' and order_id = 0");
		$update = $wpdb->get_results("UPDATE th_cart_item_services SET order_id = '$order_id' WHERE session_id = '$session_id' and product_id = '$product_id' and order_id = 0");
	}
	$update_voucher = $wpdb->get_results("UPDATE th_cart_item_service_voucher SET order_id = '$order_id' WHERE session_id = '$session_id' and voucher_name != '' and order_id = 0");
	// Save order item meta //
		foreach ( $order->get_items() as $item_id => $item_values)
		{
			$item_values['line_subtotal'];
			if($item_values['variation_id'] != ''){
				$product_id = $item_values['variation_id'];
			}
			else{
				$product_id = $item_values['product_id'];
			}
			$parent_id = wp_get_post_parent_id($product_id);
			$guarantee_text = get_post_meta($parent_id, '_guarantee_cart', true );
			$user = $order->get_user();
			$user_role = $user->roles[0];
			$product_variation = wc_get_product( $product_id );
			$variation_data = $product_variation->get_data();
			$variation_des = $product_variation->get_description();
			$price = $product_variation->get_price();
			$quantity = $item_values['quantity'];
			$cart_item_qty = $item_values['quantity'];
			 $tyre_type = $variation_data['attributes']['pa_tyre-type'];
			 $pa_brand = $variation_data['attributes']['pa_brand'];
			 $sku = 'service_voucher';
			$service_prd_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );
			if($product_id == $service_prd_id)
			{
			   $service_voucher = "SELECT *
								FROM th_cart_item_service_voucher
								WHERE order_id = '$order_id'  and product_id = '$product_id'";
				$row = $wpdb->get_results($service_voucher);
				
				if(!empty($row))
				{
					foreach ($row as $key => $voucher)
					{
						$voucher_id = $voucher->service_voucher_id;
						$rate = $voucher->rate;
						$qty = $voucher->qty;
						$amount = $rate * $qty;
						//$gst = $amount * 18 / 118;
						$gst = $amount * $serviceGST / $serviceGSTD;
						$service_taxable = $amount - $gst;
						$service_sgst = $gst / 2;
						wc_update_order_item_meta($item_id,'guarantee_text', $guarantee_text);
						wc_update_order_item_meta($item_id,'tyre_gst', $tyreGST);
						wc_update_order_item_meta($item_id,'service_gst', $serviceGST);
						wc_update_order_item_meta($item_id, $voucher_id.'_service_sgst', $service_sgst);
						wc_update_order_item_meta($item_id, $voucher_id.'_service_cgst', $service_sgst);
						wc_update_order_item_meta($item_id, $voucher_id.'_service_taxable', $service_taxable);
						wc_update_order_item_meta($item_id,'pa_brand',$pa_brand);
						
					}
				}
			}
			else
			{
				if($tyre_type == 'tubeless')
				{
					$line_subtotal = $item_values['line_subtotal'];
					if($user_role == 'Installer')
					{	
						if(empty($franchise)){
							$discount = $line_subtotal * 0.02;
							$line_subtotal = $line_subtotal - $discount;
						}
						
					}
					
					//$gst = round($line_subtotal * 28 / 128);
					$gst = round($line_subtotal * $tyreGST / $tyreGSTD);
				  //  echo "--------------------------------</br>";
				   $taxable_value = round($line_subtotal - $gst);
					$sgst = $gst / 2;
				}
				if($tyre_type == 'tubetyre')
					
				{
					$line_subtotal = $item_values['line_subtotal'];
					$tyre_price = get_post_meta($product_id, 'tyre_price', true );
					$tube_price = get_post_meta($product_id, 'tube_price', true );
					if($user_role == 'Installer')
					{
						if(empty($franchise)){
							$discount = $line_subtotal * 0.02;
							$line_subtotal = $line_subtotal - $discount;
							$tyre_price = $tyre_price - $discount;
						}
					}
					if($tyre_price == 0 && $tube_price == 0)
					{
							//$gst = $line_subtotal * 28 / 128; 
						$gst = $line_subtotal * $tyreGST / $tyreGSTD;
					}else{
						// $tyre_gst = $tyre_price * 28 / 128;
						// $tube_gst = $tube_price * 28 / 128;
						$line_subtotal = $item_values['line_subtotal'];
						//$gst = round($line_subtotal * 28 / 128);
					$gst = round($line_subtotal * $tyreGST / $tyreGSTD);
				  //  echo "--------------------------------</br>";
				   $taxable_value = round($line_subtotal - $gst);
					$sgst = $gst / 2;
						/*$tyre_gst = $tyre_price * $tyreGST / $tyreGSTD;
						$tube_gst = $tube_price * $tyreGST / $tyreGSTD;
						$gst = $tyre_gst + $tube_gst;
						$gst = $quantity * $gst;*/
					}
					//$taxable_value = $line_subtotal - $gst;
				    //$sgst = $gst / 2;
					//$cart_item['data']->set_price($new_price);
				}
				// service charge gst
				$destination_data = "SELECT *
							FROM th_cart_item_installer
							WHERE product_id = '$product_id' and order_id = '$order_id'";
				$row = $wpdb->get_results($destination_data);
				$service_taxable = 0;
				$service_sgst = 0;
				if(!empty($row))
				{
					foreach ($row as $key => $data)
					{
						$destination = $data->destination;
					}
					if($destination == 1)
					{
						$services = "SELECT *
								FROM th_cart_item_services
								WHERE product_id = '$product_id' and order_id = '$order_id'";
						 $row = $wpdb->get_results($services);
						 $total_service_charge = 0;
						 $total_home_charge = 0;
						 foreach ($row as $key => $service)
						{
						   $service_name = $service->service_name;
							$tyre_count = $service->tyre;
							$rate = $service->rate;
							$amount = $tyre_count * $rate;
							$total_service_charge = $total_service_charge + $amount;
						}
					}
					elseif($destination == 0)
					{
						$product_variation_new = wc_get_product( $product_id );
						$prd_attr_vehicle = '';
						$variation_data = $product_variation_new->get_data();
							if($variation_data['attributes']['pa_vehicle-type'] != 'car-tyre'){
								$prd_attr_vehicle = $variation_data['attributes']['pa_vehicle-type'];
						}
						if($prd_attr_vehicle != ''){
							if($cart_item_qty >= 2 && $cart_item_qty <= 5 ){
								$home_delivery_charge = 200;
							}else if($cart_item_qty >= 6){
								$home_delivery_charge = 300;
							}else{
								$home_delivery_charge = 100;
							}
						}else{
							if($cart_item_qty >= 2 && $cart_item_qty <= 5 ){
								 $home_delivery_charge = 250;
							}else if($cart_item_qty >= 6){
								$home_delivery_charge = 400;
							}else{
								$home_delivery_charge = 150;
							}
						}
					   //$total_home_charge =  $home_delivery_charge;
					   $total_home_charge =  0;
					   wc_update_order_item_meta($item_id, 'delivery_charge', $total_home_charge);
					}
					if($total_home_charge != 0){
						$service_gst = round($total_home_charge * $serviceGST / $serviceGSTD);
						$service_sgst = $service_gst / 2;
						$service_taxable = round($total_home_charge - $service_gst);
					}else{
						$service_gst = round($total_service_charge * $serviceGST / $serviceGSTD);
						$service_sgst = $service_gst / 2;
						$service_taxable = round($total_service_charge - $service_gst);
					}
				}
					
					wc_update_order_item_meta($item_id, 'taxable_value', $taxable_value);
					wc_update_order_item_meta($item_id, 'sgst', $sgst);
					wc_update_order_item_meta($item_id, 'cgst', $sgst);
					if(empty($franchise)){
						wc_update_order_item_meta($item_id, 'discount', $discount);
					}
					wc_update_order_item_meta($item_id, 'service_sgst', $service_sgst);
					wc_update_order_item_meta($item_id, 'service_cgst', $service_sgst);
					wc_update_order_item_meta($item_id, 'service_taxable', $service_taxable);
					wc_update_order_item_meta($item_id,'tyre_gst', $tyreGST);
					wc_update_order_item_meta($item_id,'service_gst', $serviceGST);
					wc_update_order_item_meta($item_id,'guarantee_text', $guarantee_text); 
					wc_update_order_item_meta($item_id,'pa_brand',$pa_brand);
			}
	 }
	 // Save order item meta //
};
add_filter( 'woocommerce_available_payment_gateways', 'bbloomer_paypal_enable_manager' );
function bbloomer_paypal_enable_manager( $available_gateways )
{
	global $woocommerce;
	if(current_user_can('administrator') || current_user_can("shop_manager") )
	{
	}elseif(current_user_can('Installer')){
		global $wpdb;
		$SQL="SELECT * FROM th_installer_data WHERE is_franchise='yes' AND user_id='".get_current_user_id()."'";
	    $franchise=$wpdb->get_row($SQL);
		if($franchise){
		  unset( $available_gateways['bacs'] );
		}else{
			unset( $available_gateways['wallet'] );
		}
		//unset( $available_gateways['ccavenue'] );
		unset( $available_gateways['paytm'] );
		unset( $available_gateways['cod'] );
		unset( $available_gateways['pos'] );
	}elseif(current_user_can('btobpartner')){
		//unset($available_gateways['ccavenue']);
		unset($available_gateways['paytm']);
		unset($available_gateways['cod']);
		unset($available_gateways['pos']);
	}else{
		unset( $available_gateways['pos'] );
		unset( $available_gateways['bacs'] );
		unset( $available_gateways['cod'] );
		unset( $available_gateways['wallet'] );
	}
	//unset( $available_gateways['paytm'] );
return $available_gateways;
}
function wooc_extra_register_fields() {?>
	   <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
		   <label for="reg_mobile_no"><?php _e( 'Mobile No', 'woocommerce' ); ?><span class="required">*</span></label>
		   <input type="text" class="input-text" name="mobile_no" id="reg_mobile_no" value="<?php if ( ! empty( $_POST['billing_first_name'] ) ) esc_attr_e( $_POST['mobile_no'] ); ?>" />
	   </p>
	   <div class="clear"></div>
	   <?php
 }
add_action( 'woocommerce_register_form_start', 'wooc_extra_register_fields' );
add_action( 'woocommerce_created_customer', 'wooc_save_extra_register_fields' );
function wooc_validate_extra_register_fields( $username, $email, $validation_errors )
{
	if ( isset( $_POST['mobile_no'] ) && empty( $_POST['mobile_no'] ) )
	{
		$validation_errors->add( 'mobile_no_error', __( '<strong>Error</strong>: Mobile Number  is required!', 'woocommerce' ) );
	}
	return $validation_errors;
}
add_action( 'woocommerce_created_customer', 'wooc_save_extra_register_fields' );
function wooc_save_extra_register_fields( $customer_id )
{
	if ( isset( $_POST['mobile_no'] ) )
	{
		//First name field which is by default
		update_user_meta( $customer_id, 'mobile_no', sanitize_text_field( $_POST['mobile_no'] ) );
		// First name field which is used in WooCommerce
		update_user_meta( $customer_id, 'mobile_no', sanitize_text_field( $_POST['mobile_no'] ) );
	}
}
add_action( 'show_user_profile', 'extra_user_profile_fields' );
add_action( 'edit_user_profile', 'extra_user_profile_fields' );
function extra_user_profile_fields( $user )
{ ?>
	<table class="form-table">
	<tr>
	<th><label for="mobile_no"><?php _e("mobile_no",'shabatkeeper'); ?> <span class="description"><?php _e('(required)'); ?></span></label></th>
	<td>
	<input type="text" name="mobile_no" id="mobile_no" value="<?php echo esc_attr( get_the_author_meta( 'mobile_no', $user->ID ) ); ?>" class="regular-text"  /><br />
	<span class="description"><?php _e("Please enter your mobile no.",'shabatkeeper'); ?>   </span>
	</td>
	</tr>
<?php
}
add_action( 'personal_options_update', 'save_extra_user_profile_fields' );
add_action( 'edit_user_profile_update', 'save_extra_user_profile_fields' );
function save_extra_user_profile_fields( $user_id ) {
if ( !current_user_can( 'edit_user', $user_id ) ) { return false; }
{
  if (!isset($_POST['mobile_no']) || empty($_POST['mobile_no']))  {
	 // wp_die( __('Error: you must fill all fields') );
  } else {
	  // field was set and contains a value
	  // do your action
	  exit;
  }
}
}
add_action('wp_ajax_custom_registartion', 'custom_registartion');
add_action('wp_ajax_nopriv_custom_registartion', 'custom_registartion');
function custom_registartion()
{
	global $woocommerce , $wpdb;
	$otp = rand(100000,999999);
	$mobile = $_POST['mobile_no'];
	//$pass = $_POST['pass'];
	//$email = $_POST['email'];
	$first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];
	$mobile_whatsapp = $_POST['mobile_whatsapp'];
	if ( !username_exists( $mobile ))
	{
		$userdata = array (
						'user_login' => $mobile,
						'user_pass' =>$mobile,
						'role' => 'customer',
						'user_nicename' =>$first_name.' '.$last_name,
						'first_name' =>$first_name,
						'last_name'=>$last_name,
						'display_name' =>$first_name.' '.$last_name,
						'nickname' =>$first_name.' '.$last_name,
						'otp' => $otp,
					);
		$new_user_id = wp_insert_user( $userdata );
		update_user_meta( $new_user_id, '_active', 0 );
		update_user_meta( $new_user_id, 'mobile_whatsapp', $mobile_whatsapp );
		update_user_meta( $new_user_id, 'franchise_id',0);
		update_user_meta( $new_user_id, 'referral_type','organic');
		update_user_meta( $new_user_id, 'custom_mobile', sanitize_text_field( $mobile ) );
		$ch1 = curl_init();
		$message = "We have received your request for registration. Your OTP is ".$otp." Thank you Tyrehub Team.";
		 
		$message = str_replace(' ', '%20', $message);
		sms_send_to_customer($message,$mobile);
		$update = $wpdb->get_results("UPDATE `wp_users` SET otp = '$otp' WHERE ID = '$new_user_id'");
			$result = array('result' => 'ok', 'user_id' => $new_user_id );
			echo json_encode($result);
	}
	else{
		$result = array('result' => 'error', 'message' => 'Mobile number is already registered.');
			echo json_encode($result);
	}
	die();
}
add_action('wp_ajax_campaign_register', 'campaign_register');
add_action('wp_ajax_nopriv_campaign_register', 'campaign_register');
function campaign_register()
{
	global $woocommerce , $wpdb;
	extract($_POST);
	if (!username_exists($mobile_no))
	{
		$userdata = array (
						'user_login' => $mobile_no,
						'user_pass' =>$mobile_no,
						'user_email' =>$email,
						'role' => 'customer',
						'user_nicename' =>$first_name.' '.$last_name,
						'first_name' =>$first_name,
						'last_name'=>$last_name,
						'display_name' =>$first_name.' '.$last_name,
						'nickname' =>$first_name.' '.$last_name,
					);
		$new_user_id = wp_insert_user( $userdata );
		update_user_meta( $new_user_id, '_active', 0 );
		update_user_meta( $new_user_id, 'franchise_id',0);
		update_user_meta( $new_user_id, 'referral_type','campaign');
		update_user_meta( $new_user_id, 'vehicle_type',implode(',',$vehicle_type));
		$wpdb->insert('th_campaing_users',array (
				'user_id' => $new_user_id,
				'first_name' =>$first_name,
				'last_name'=>$last_name,
				'mobile' =>$mobile_no,
				'email' =>$email,
				'campaing_name' =>'springoffer',
				'vehicle_type'=>implode(',',$vehicle_type)
				));
		$ch1 = curl_init();
$message = "Thank you for your registration with tyrehub.com, Here is your promo code FIRST100. Login to our website tyrehub.com and use this promo code during checkout.";
		$message = str_replace(' ', '%20', $message);
		sms_send_to_customer($message,$mobile_no);
			$result = array('result' => 'ok', 'user_id' => $new_user_id );
			echo json_encode($result);
	}
	else{
		$result = array('result' => 'error', 'message' => 'Mobile / Email already exist!');
		echo json_encode($result);
	}
	die();
}
add_action('wp_ajax_login_check', 'login_check');
add_action('wp_ajax_nopriv_login_check', 'login_check');
function login_check()
{
	global $woocommerce , $wpdb;
	extract($_POST);
	if (username_exists($username))
	{
		$the_user = get_user_by('login',$username);
		$user_id = $the_user->ID;
		// Get the user object.
		$user = get_userdata( $user_id );
		// Get all the user roles for this user as an array.
		$user_roles = $user->roles;
		// Check if the specified role is present in the array.
		if ( in_array('Installer', $user_roles, true ) ) {
		    $role='installer';
		    $login_with='pass';
		} elseif ( in_array( 'administrator', $user_roles, true ) ) {
		    $role='administrator';
		    $login_with='pass';
		} elseif ( in_array( 'account-manager', $user_roles, true ) ) {
		     $role='account-manager';
		     $login_with='pass';
		} elseif ( in_array( 'btobpartner', $user_roles, true ) ) {
		     $role='btobpartner';
		     $login_with='pass';
		} elseif ( in_array( 'customer', $user_roles, true ) ) {
		     $role='customer';
		     $login_with='otp';
		}elseif ( in_array( 'shop_manager', $user_roles, true ) ) {
		     $role='shop_manager';
		     $login_with='pass';
		}elseif ( in_array( 'supervisor-l1', $user_roles, true ) ) {
		     $role='supervisor-l1';
		     $login_with='pass';
		}elseif ( in_array( 'supervisor-l3', $user_roles, true ) ) {
		     $role='supervisor-l3';
		     $login_with='pass';
		}elseif ( in_array( 'supplier', $user_roles, true ) ) {
		     $role='supplier';
		     $login_with='pass';
		}
		if($login_with=='otp'){
			$otp = rand(100000,999999);
			$ch1 = curl_init();
			$message = "Dear Customer, ".$otp." is OTP for tyrehub.com login, OTPs are SECRET. DO NOT disclose it to anyone.";
			$message = str_replace(' ', '%20', $message);
			$mobile=$username;
			sms_send_to_customer($message,$mobile);
			
			$update = $wpdb->get_results("UPDATE `wp_users` SET otp = '$otp' WHERE ID = '$user_id'");
		}
		$result = array('result' => 'ok','login_with'=>$login_with, 'user_id' =>$user_id );
		echo json_encode($result);
	}
	else{
		$result = array('result' => 'error', 'message' => 'Your mobile number is not registered with us!');
		echo json_encode($result);
	}
	die();
}
add_action('wp_ajax_login_with_otp', 'login_with_otp');
add_action('wp_ajax_nopriv_login_with_otp', 'login_with_otp');
function login_with_otp()
{
	 $otp = $_POST['otp'];
	$mobile = $_POST['mobile'];
	global $woocommerce , $wpdb;
	$result = $wpdb->get_row("SELECT * from `wp_users` where otp = '$otp' AND user_login = '$mobile'");
	//print_r($result);
	if($result){
			// Automatic login //
			$user = get_user_by('login', $mobile );
			// Redirect URL //
			if (!is_wp_error( $user ) )
			{
			    wp_clear_auth_cookie();
			    wp_set_current_user ( $user->ID );
			    wp_set_auth_cookie  ( $user->ID );
			}
		$result = array('result' => 'ok','login_with'=>$login_with, 'user_id' =>$user->ID);
		echo json_encode($result);
	}else{
		$result = array('result' => 'error', 'message' => 'Your OTP is not valid!');
		echo json_encode($result);
	}
die();
}
add_action('wp_ajax_login_with_pass', 'login_with_pass');
add_action('wp_ajax_nopriv_login_with_pass', 'login_with_pass');
function login_with_pass()
{
	$password = $_POST['pass'];
	$username = $_POST['mobile'];
	global $woocommerce , $wpdb;
	$user = get_user_by('login', $username);
if ($user && wp_check_password( $password, $user->data->user_pass, $user->ID)) {
        $creds = array('user_login' => $user->data->user_login, 'user_password' => $password);
        $user = wp_signon( $creds, $secure_cookie );
        $result = array('result' => 'ok','login_with'=>$login_with, 'user_id' =>$user->ID);
		echo json_encode($result);
}else{
	$result = array('result' => 'error', 'message' => 'The password does not match');
		echo json_encode($result);
}
die();
}
add_action('wp_ajax_check_email', 'check_email');
add_action('wp_ajax_nopriv_check_email', 'check_email');
function check_email()
{
	global $woocommerce , $wpdb;
	extract($_POST);
	if (!email_exists($custom_email))
	{
		  echo "true";  //good to register
	}else{
		 echo "false"; //already registered
	}
die();
}
add_action('wp_ajax_check_mobile', 'check_mobile');
add_action('wp_ajax_nopriv_check_mobile', 'check_mobile');
function check_mobile()
{
	global $woocommerce , $wpdb;
	extract($_POST);
	if (!username_exists($custom_mobile))
	{
		  echo "true";  //good to register
	}else{
		 echo "false"; //already registered
	}
die();
}
add_action('wp_ajax_check_emailid', 'check_emailid');
add_action('wp_ajax_nopriv_check_emailid', 'check_emailid');
function check_emailid()
{
	global $woocommerce , $wpdb;
	extract($_POST);
	if (!email_exists($custom_email))
	{
		  echo "true";  //good to register
	}else{
		 echo "false"; //already registered
	}
die();
}
add_action('wp_ajax_verify_otp', 'verify_otp');
add_action('wp_ajax_nopriv_verify_otp', 'verify_otp');
function verify_otp()
{
	$otp = $_POST['otp'];
	$user_id = $_POST['user_id'];
	global $woocommerce , $wpdb;
	$result = $wpdb->get_row("SELECT * from `wp_users` where otp = '$otp' AND ID = '$user_id'");
	//print_r($result);
	if($result){
		 echo 1;
		 update_user_meta( $user_id, '_active',1);
	$ch1 = curl_init();
	$message = "Dear ".$result->display_name.", Thank you for Registering with tyrehub.com";
	$message = str_replace(' ', '%20', $message);
	$mobile=$result->user_login;
	sms_send_to_customer($message,$mobile);

	}
	else{
		echo 0;
	}
die();
}
add_action('wp_ajax_resend_otp', 'resend_otp');
add_action('wp_ajax_nopriv_resend_otp', 'resend_otp');
function resend_otp()
{
	$user_id = $_POST['user_id'];
	 global $woocommerce , $wpdb;
	$otp = rand(100000,999999);
	$user_info = get_userdata($user_id);
    $mobile_no = $user_info->user_login;
	$ch1 = curl_init();
	$message = "We have receive your request for registration your otp is ".$otp." Thank You Tyrehub Team";
	$message = str_replace(' ', '%20', $message);
	sms_send_to_customer($message,$mobile_no);
	$update = $wpdb->get_results("UPDATE `wp_users` SET otp = '$otp' WHERE ID = '$user_id'");
	die();
}
//add_action( 'wp', 'redirect' );
function redirect() {
 if ( is_front_page() && is_user_logged_in() ) {
	 $user = wp_get_current_user();
	   $role = $user->roles[0];
	   if($role == 'Installer'){
		   wp_redirect(get_site_url().'/installer-home');
	   }
	 die();
 }
}
function custom_my_account_order()
{		global $wpdb;
	 $user = wp_get_current_user();
	 $role = $user->roles[0];
		if($role == 'Installer')
		{
			$myorder = array(
				'service-request'   => __( 'Service Request', 'woocommerce' ),
				'dashboard'          => __( 'My account', 'woocommerce' ),
				'orders'             => __( 'Order tracking', 'woocommerce' ),
				'purchase'          => __( 'Purchase', 'woocommerce' ),
				'edit-account'       => __( 'Settings', 'woocommerce' ),
				'customer-register'    => __( 'Customer Register', 'woocommerce' ),
				'customer-logout'    => __( 'Logout', 'woocommerce' ),
			);
			$SQL="SELECT * FROM th_installer_data WHERE is_franchise='yes' AND user_id='".get_current_user_id()."'";
			$franchise=$wpdb->get_row($SQL);
			if($franchise){
				$myorder['franchise-home'] = 'Franchise Home';
				$myorder['customer-store-order-history'] = 'Customer Store Order History';
				$myorder['franchise-tyre-purchase'] = 'Franchise Tyre Purchase';
				$myorder['franchise-twotyre-purchase'] = 'Franchise Two Tyre Purchase';
				$myorder['payout-process'] = 'Payout Process';
				$myorder['payout-history'] = 'Payout History';
				$myorder['franchise-payout'] = 'Franchise Payout';
				$myorder['wallet-history'] = 'Wallet History';
				$myorder['deleted-orders'] = 'Deleted Orders';
				
				$myorder['offline-alignment-balancing'] = 'Alignment Balancing';
				$myorder['offline-car-wash'] = 'Car Wash';
			}
				$myorder['offline-cartyre-purchase'] = 'Car Tyre Purchase';
				$myorder['offline-twotyre-purchase'] = 'Two Tyre Purchase';
				$myorder['offline-wishlist'] = 'Wishlist';
	   }elseif($role == 'btobpartner')
		{
			$myorder = array(
				'dashboard'          => __( 'My account', 'woocommerce' ),
				'orders'             => __( 'Order tracking', 'woocommerce' ),
				/*'supplier-purchase'          => __( 'Purchase', 'woocommerce' ),*/
				'edit-address'       => __( 'Addresses', 'woocommerce' ),
				'edit-account'       => __( 'Settings', 'woocommerce' ),
				'customer-logout'    => __( 'Logout', 'woocommerce' ),
			);
	   }
	   elseif($role == 'administrator')
		{
			$myorder = array(
				'service-request'   => __( 'Service Request', 'woocommerce' ),
				'dashboard'          => __( 'My account', 'woocommerce' ),
				'orders'             => __( 'Order tracking', 'woocommerce' ),
				'edit-address'       => __( 'Addresses', 'woocommerce' ),
				'edit-account'       => __( 'Settings', 'woocommerce' ),
				'customer-logout'    => __( 'Logout', 'woocommerce' ),
			);
	   }elseif($role == 'supplier')
		{
			$myorder = array(
				'dashboard'          => __( 'My account', 'woocommerce' ),
				'tyre-products'   => __( 'Product Price', 'woocommerce' ),
				'deals-discount'   => __( 'Deals and Discount', 'woocommerce' ),
				'new-discount'   => __( 'New Discount', 'woocommerce' ),
				'update-discount'   => __( 'Update Discount', 'woocommerce' ),
				'edit-account'       => __( 'Settings', 'woocommerce' ),
				'customer-logout'    => __( 'Logout', 'woocommerce' ),
			);
	   }
	   else
	   {
			$myorder = array(
				'dashboard'          => __( 'My account', 'woocommerce' ),
				'orders'             => __( 'Order tracking', 'woocommerce' ),
				'store-walking-orders' => __( 'Store Walking Orders', 'woocommerce' ),
				'edit-address'       => __( 'Addresses', 'woocommerce' ),
				'edit-account'       => __( 'Settings', 'woocommerce' ),
				'customer-logout'    => __( 'Logout', 'woocommerce' ),
			);
	   }
	return $myorder;
}
add_filter ( 'woocommerce_account_menu_items', 'custom_my_account_order' );
add_action('init', 'installer_purchase_endpoint');
function installer_purchase_endpoint()
{
	global $wp_rewrite,$wpdb;
	add_rewrite_endpoint('purchase', EP_PAGES);
	add_rewrite_endpoint('supplier-purchase', EP_PAGES);
	add_rewrite_endpoint('service-request', EP_PAGES);
	add_rewrite_endpoint('customer-register', EP_PAGES);
	add_rewrite_endpoint('tyre-products', EP_PAGES);
	add_rewrite_endpoint('deals-discount', EP_PAGES);
	add_rewrite_endpoint('new-discount', EP_PAGES);
	add_rewrite_endpoint('update-discount', EP_PAGES);
	$SQL="SELECT * FROM th_installer_data WHERE is_franchise='yes' AND user_id='".get_current_user_id()."'";
	$franchise=$wpdb->get_row($SQL);
	if($franchise){
		add_rewrite_endpoint('franchise-home', EP_PAGES);
		add_rewrite_endpoint('customer-store-order-history', EP_PAGES);
		add_rewrite_endpoint('franchise-tyre-purchase', EP_PAGES);
		add_rewrite_endpoint('franchise-twotyre-purchase', EP_PAGES);
		add_rewrite_endpoint('offline-alignment-balancing', EP_PAGES);
		add_rewrite_endpoint('offline-car-wash', EP_PAGES);
		add_rewrite_endpoint('payout-process', EP_PAGES);
		add_rewrite_endpoint('payout-history', EP_PAGES);
		add_rewrite_endpoint('franchise-payout', EP_PAGES);
		add_rewrite_endpoint('wallet-history', EP_PAGES);
		add_rewrite_endpoint('deleted-orders', EP_PAGES);
		
		
	}
		add_rewrite_endpoint('offline-cartyre-purchase', EP_PAGES);
		add_rewrite_endpoint('offline-twotyre-purchase', EP_PAGES);
		add_rewrite_endpoint('offline-wishlist', EP_PAGES);
		add_rewrite_endpoint('store-walking-orders', EP_PAGES);
		
	$wp_rewrite->flush_rules();
}
// page template
add_filter( 'page_template', 'giftlist_page_template' );
function giftlist_page_template( $page_template )
{
	global $wp_query, $wpdb;
	if(isset($wp_query->query['purchase'])){
		$page_template = dirname( __FILE__ ).'/woocommerce/myaccount/purchase.php' ;
	}
	$SQL="SELECT * FROM th_installer_data WHERE is_franchise='yes' AND user_id='".get_current_user_id()."'";
	$franchise=$wpdb->get_row($SQL);
	if($franchise){
		if(isset($wp_query->query['franchise-home'])){
			$page_template = dirname( __FILE__ ).'/woocommerce/myaccount/franchise-home.php';
		}
		if(isset($wp_query->query['customer-store-order-history'])){
			$page_template = dirname( __FILE__ ).'/woocommerce/myaccount/customer-store-order-history.php';
		}
		if(isset($wp_query->query['franchise-tyre-purchase'])){
			$page_template = dirname( __FILE__ ).'/woocommerce/myaccount/franchise-tyre-purchase.php' ;
		}
		if(isset($wp_query->query['franchise-twotyre-purchase'])){
			$page_template = dirname( __FILE__ ).'/woocommerce/myaccount/franchise-twotyre-purchase.php' ;
		}
		if(isset($wp_query->query['offline-alignment-balancing'])){
			$page_template = dirname( __FILE__ ).'/woocommerce/myaccount/offline-alignment-balancing.php' ;
		}
		if(isset($wp_query->query['offline-car-wash'])){
			$page_template = dirname( __FILE__ ).'/woocommerce/myaccount/offline-car-wash.php' ;
		}
		if(isset($wp_query->query['payout-process'])){
			$page_template = dirname( __FILE__ ).'/woocommerce/myaccount/payout-process.php' ;
		}
		if(isset($wp_query->query['payout-history'])){
			$page_template = dirname( __FILE__ ).'/woocommerce/myaccount/payout-history.php' ;
		}
		if(isset($wp_query->query['franchise-payout'])){
			$page_template = dirname( __FILE__ ).'/woocommerce/myaccount/franchise-payout.php' ;
		}
		if(isset($wp_query->query['wallet-history'])){
			$page_template = dirname( __FILE__ ).'/woocommerce/myaccount/wallet-history.php' ;
		}
		if(isset($wp_query->query['deleted-orders'])){
			$page_template = dirname( __FILE__ ).'/woocommerce/myaccount/deleted-orders.php' ;
		}
		
	}
	if(isset($wp_query->query['offline-cartyre-purchase'])){
			$page_template = dirname( __FILE__ ).'/woocommerce/myaccount/offline-cartyre-purchase.php' ;
		}
		if(isset($wp_query->query['offline-twotyre-purchase'])){
			$page_template = dirname( __FILE__ ).'/woocommerce/myaccount/offline-twotyre-purchase.php' ;
		}
	if(isset($wp_query->query['offline-wishlist'])){
			$page_template = dirname( __FILE__ ).'/woocommerce/myaccount/offline-wishlist.php' ;
		}
	if(isset($wp_query->query['store-walking-orders'])){
			$page_template = dirname( __FILE__ ).'/woocommerce/myaccount/store-walking-orders.php' ;
		}
	if(isset($wp_query->query['supplier-purchase'])){
		$page_template = dirname( __FILE__ ).'/woocommerce/myaccount/supplier-purchase.php' ;
	}
	if(isset($wp_query->query['service-request'])){
		$page_template = dirname( __FILE__ ).'/woocommerce/myaccount/service-request.php' ;
	}
	if(isset($wp_query->query['customer-register'])){
		$page_template = dirname( __FILE__ ).'/woocommerce/myaccount/customer-register.php' ;
	}
	if(isset($wp_query->query['tyre-products'])){
		$page_template = dirname( __FILE__ ).'/woocommerce/myaccount/tyre-products.php' ;
	}
	if(isset($wp_query->query['deals-discount'])){
		$page_template = dirname( __FILE__ ).'/woocommerce/myaccount/deals-discount.php' ;
	}
	if(isset($wp_query->query['new-discount'])){
		$page_template = dirname( __FILE__ ).'/woocommerce/myaccount/new-discount.php' ;
	}
	if(isset($wp_query->query['update-discount'])){
		$page_template = dirname( __FILE__ ).'/woocommerce/myaccount/update-discount.php' ;
	}
	return $page_template;
}
add_action( 'woocommerce_before_shop_loop_item', 'bbloomer_custom_action123', 15 );
function bbloomer_custom_action123()
{
	echo '<div class="prd_img">';
}
add_action( 'woocommerce_before_shop_loop_item_title', 'bbloomer_custom_action', 15 );
function bbloomer_custom_action()
{
	echo '</div>';
}
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
add_action( 'woocommerce_shop_loop_item_title', 'bbloomer_custom_action4', 15 );
function bbloomer_custom_action4() {
	global $product;
	echo '<h2 class="woocommerce-loop-product__title">'.$product->get_description().'</h2>';
}
add_action( 'wp_footer', 'tyrehub_single_product_variation_name' );
function tyrehub_single_product_variation_name()
{
 ?>
	<script>
		 (function($) {
		 $(document).on( 'found_variation', function()
		 {
			var desc = $( '.woocommerce-variation.single_variation' ).find( '.woocommerce-variation-description p' ).text();
			var $entry_summary = $( '.product_title.entry-title' ), $wc_var_desc = $entry_summary.find( '.woocommerce-variation-description' );
			 if ( $wc_var_desc.length == 0 )
			 {
				$entry_summary.html( ' <span class="des"></span>' );
			 }
			$entry_summary.find( '.des' ).html( desc );
			var variation_id = $('.variation_id').val();
			//alert(variation_id);
			// for contact us product list
			var prd_list = $('.contact-us-prd-list').text();
			var contact_prd_arr = $.trim(prd_list).split(',');
			console.log(contact_prd_arr);
			if($.inArray( variation_id, contact_prd_arr ) != -1)
			{
				console.log('contact-us');
				$('.single_add_to_cart_button').attr('type','button');
				$('.single_add_to_cart_button').html('<a href="#">Please call us </a>');
			}
			else
			{
				// for sold out product list
				var soldout_prd_list = $('.soldout-prd-list').text();
				var soldout_prd_arr = $.trim(soldout_prd_list).split(',');
				if($.inArray( variation_id, soldout_prd_arr ) != -1)
				{
					//console.log('contact-us');
					$('.single_add_to_cart_button').attr('type','button');
					$('.single_add_to_cart_button').removeClass('alt');
					$('.single_add_to_cart_button').css('background-color','gray !important');
					$('.single_add_to_cart_button').html('Sold Out');
				}
				else
				{
					$('.single_add_to_cart_button').attr('type','submit');
					$('.single_add_to_cart_button').html('Add to cart');
				}
			}
		 });
		 })( jQuery );
	</script>
	<style>
		form.variations_form .woocommerce-variation-description {
			 display: none;
		}
	</style>
 <?php
   global $wpdb , $woocommerce;
	$user = get_current_user_id();
	$role = $user->roles[0];
	if($role == "Installer"){
		?>
	<style>
	#wpadminbar{
		display: none;
	}
	</style>
		<?php
	}
 ?>
<?php }
function iconic_login_redirect( $redirect, $user )
{
	$redirect_page_id = url_to_postid( $redirect );
	$role = $user->roles[0];
	  if($role == 'customer')
		{
			$url = get_site_url();
			wp_redirect($url);
			exit;
		}
		if($role == 'Installer')
		{
			$url = get_site_url().'/my-account/service-request/';
			wp_redirect($url);
			exit;
		}
		if($role == 'supplier')
		{
			$url = get_site_url().'/my-account/tyre-products/';
			wp_redirect($url);
			exit;
		}
		if($role == 'btobpartner')
		{
			$url = get_site_url();
			wp_redirect($url);
			exit;
		}
	return $redirect;
}
add_filter( 'woocommerce_login_redirect', 'iconic_login_redirect',10,2 );
function wc_cart_item_name_hyperlink( $link_text, $product_data ) {
	$variation_ID = $product_data['variation_id'];
	if(empty($variation_ID)){
		$variation_ID = $product_data['product_id'];
	}
	$vehicle_type = $product_data['custom_data'];
	$product_variation = wc_get_product( $variation_ID );
	$variation_des = $product_variation->get_description();
	$permalink = $product_variation->get_permalink();
	$title = get_the_title($product_data['variation_id']);
	if($product_data['product_id']==get_option("balancing_alignment") || $product_data['product_id']==get_option("car_wash")){
		$title = $product_data['variation']['voucher_name'];
	}else{
		$title = $variation_des;
	}
   return sprintf( '<a href="%s">%s </a>',$permalink,$title );
}
/* Filter to override cart_item_name */
add_filter( 'woocommerce_cart_item_name', 'wc_cart_item_name_hyperlink', 10, 2 );
/**
 *
 * Posts per page for category (test-category) under CPT archive
 *
*/
add_filter( 'woocommerce_product_categories_widget_dropdown_args', 'rv_exclude_wc_widget_categories' );
//* Used when the widget is displayed as a list
add_filter( 'woocommerce_product_categories_widget_args', 'rv_exclude_wc_widget_categories' );
function rv_exclude_wc_widget_categories( $cat_args )
{
	$cat_args['exclude'] = array('16'); // Insert the product category IDs you wish to exclude
	return $cat_args;
}
// add custom field in variations
// -----------------------------------------
// 1. Add custom field input @ Product Data > Variations > Single Variation
add_action( 'woocommerce_variation_options_pricing', 'bbloomer_add_custom_field_to_variations', 10, 3 );
function bbloomer_add_custom_field_to_variations( $loop, $variation_data, $variation ) {
	woocommerce_wp_select(
	array(
		'id'          => 'tyrehub_visible[' . $loop . ']',
		'label'       => __( 'Visible', 'woocommerce' ),
		'description' => __( 'Choose a value.', 'woocommerce' ),
		'value'       => get_post_meta( $variation->ID, 'tyrehub_visible', true ),
		'options' => array(
			'yes'   => __( 'Yes', 'Yes' ),
			'no'   => __( 'No', 'No' ),
			'contact-us'   => __( 'Contact-us', 'Contact-us' ),
			)
		)
	);
	woocommerce_wp_text_input( array(
	'id' => 'rcp_price[' . $loop . ']',
	'class' => 'short',
	'label' => __( 'RCP Price', 'woocommerce' ),
	'value' => get_post_meta( $variation->ID, 'rcp_price', true )
	)
	);
	 woocommerce_wp_text_input( array(
	'id' => 'net_lending_price[' . $loop . ']',
	'class' => 'short',
	'label' => __( 'NET LENDING', 'woocommerce' ),
	'value' => get_post_meta( $variation->ID, 'net_lending_price', true )
	)
	);
	 woocommerce_wp_text_input( array(
	'id' => 'point[' . $loop . ']',
	'class' => 'short',
	'label' => __( 'Point', 'woocommerce' ),
	'value' => get_post_meta( $variation->ID, 'point', true )
	)
	);
	 woocommerce_wp_text_input( array(
	'id' => 'company_name[' . $loop . ']',
	'class' => 'short',
	'label' => __( 'Company Name', 'woocommerce' ),
	'value' => get_post_meta( $variation->ID, 'company_name', true )
	)
	);
	 woocommerce_wp_text_input( array(
	'id' => 'tyre_price[' . $loop . ']',
	'class' => 'short',
	'label' => __( 'Tyre Price', 'woocommerce' ),
	'value' => get_post_meta( $variation->ID, 'tyre_price', true )
	)
	);
	 woocommerce_wp_text_input( array(
	'id' => 'tube_price[' . $loop . ']',
	'class' => 'short',
	'label' => __( 'Tube Price', 'woocommerce' ),
	'value' => get_post_meta( $variation->ID, 'tube_price', true )
	)
	);
}
// -----------------------------------------
// 2. Save custom field on product variation save
add_action( 'woocommerce_save_product_variation', 'bbloomer_save_custom_field_variations', 10, 2 );
function bbloomer_save_custom_field_variations( $variation_id, $i ) {
/*	echo '<pre>';
	print_r($_POST);
	echo '</pre>';*/
	$custom_field = $_POST['RCP_price'][$i];
	$net_lending = $_POST['net_lending_price'][$i];
	$point = $_POST['point'][$i];
	$company_name = $_POST['company_name'][$i];
	$tyre_price = $_POST['tyre_price'][$i];
	$tube_price = $_POST['tube_price'][$i];
	 $tyrehub_visible = $_POST['tyrehub_visible'][$i];
	if ( ! empty( $custom_field ) ) {
		update_post_meta( $variation_id, 'rcp_price', esc_attr( $custom_field ) );
	} else delete_post_meta( $variation_id, 'rcp_price' );
	if ( ! empty( $net_lending ) )
	{
		update_post_meta( $variation_id, 'net_lending_price', esc_attr( $net_lending ) );
	}
	else{
		delete_post_meta( $variation_id, 'net_lending_price' );
	}
	if ( ! empty( $point ) )
	{
		update_post_meta( $variation_id, 'point', esc_attr( $point ) );
	}
	else{
		delete_post_meta( $variation_id, 'point' );
	}
	if ( ! empty( $company_name ) )
	{
		update_post_meta( $variation_id, 'company_name', esc_attr( $company_name ) );
	}
	else{
		delete_post_meta( $variation_id, 'company_name' );
	}
	if ( ! empty( $tyre_price ) )
	{
		update_post_meta( $variation_id, 'tyre_price', esc_attr( $tyre_price ) );
	}
	else{
		delete_post_meta( $variation_id, 'tyre_price' );
	}
	if ( ! empty( $tube_price ) )
	{
		update_post_meta( $variation_id, 'tube_price', esc_attr( $tube_price ) );
	}
	else{
		delete_post_meta( $variation_id, 'tube_price' );
	}
	if ( ! empty( $tyrehub_visible ) )
	{
		update_post_meta( $variation_id, 'tyrehub_visible', esc_attr( $tyrehub_visible ) );
	}
	else{
		delete_post_meta( $variation_id, 'tyrehub_visible' );
	}
	global $wpdb;
}
// -----------------------------------------
// 3. Store custom field value into variation data
add_filter( 'woocommerce_available_variation', 'bbloomer_add_custom_field_variation_data' );
function bbloomer_add_custom_field_variation_data( $variations ) {
	$variations['rcp_price'] = '<div class="woocommerce_custom_field">Custom Field: <span>' . get_post_meta( $variations[ 'variation_id' ], 'rcp_price', true ) . '</span></div>';
	return $variations;
}
//-------------- SMS ---------------//
function register_shipped_order_status()
{
	register_post_status( 'wc-deltoinstaller', array(
		'label'                     => '4 Order Ready to Install',
		'public'                    => true,
		'exclude_from_search'       => false,
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'label_count'               => _n_noop( '4 Order Ready to Install <span class="count">(%s)</span>', 'Order Ready to Install <span class="count">(%s)</span>' )
	) );
	register_post_status( 'wc-customprocess', array(
		'label'                     => '2 Order Processing',
		'public'                    => true,
		'exclude_from_search'       => false,
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'label_count'               => _n_noop( '2 Order Processing <span class="count">(%s)</span>', 'Order Processing <span class="count">(%s)</span>' )
	) );
}
add_action( 'init', 'register_shipped_order_status' );
// If payment methos cash on delivery status changes to custom processing
/*add_filter( 'woocommerce_cod_process_payment_order_status', 'change_cod_payment_order_status' );
function change_cod_payment_order_status( $order_status, $order )
{
	return 'on-hold';
}*/
add_filter( 'woocommerce_cod_process_payment_order_status', 'set_cod_process_payment_order_status_on_hold', 10, 2 );
function set_cod_process_payment_order_status_on_hold( $status, $order ) {
    /*$user_data = get_userdata( $order->get_customer_id() );
    if( ! in_array( 'administrator', $user_data->roles ) )*/
        return 'on-hold';
    //return $status;
}
add_filter( 'wc_order_statuses', 'custom_order_status');
function custom_order_status( $order_statuses )
{
	foreach ($order_statuses as $key => $value)
	{
		if($value == 'Pending payment')
		{
			$order_statuses['wc-customprocess'] = _x( '2 Order Processing', 'Order status', 'woocommerce' );
		}
		if($value == 'Processing')
		{
			$order_statuses[$key] = '3 Order Dispatched';
			 $order_statuses['wc-deltoinstaller'] = _x( '4 Order Ready to Install', 'Order status', 'woocommerce' );
		}
		if($value == 'Completed')
		{
			$order_statuses[$key] = '5 Order Complete';
		}
		if($value == 'On hold')
		{
			$order_statuses[$key] = '1 Order Received';
		}
	}
	return $order_statuses;
}
add_action( 'woocommerce_order_status_processing',  'my_custom_function');
function my_custom_function($order_id)
{
	global $wpdb;
	$order = new WC_Order( $order_id );
   // die();
	$order_data = $order->get_data();
	$item_data = $order->get_items();
	$mobile_no = $order_data['billing']['phone'];
	$first_name = $order_data['billing']['first_name'];
	 $last_name = $order_data['billing']['last_name'];
	$total_item = count($item_data);
	$sku = 'service_voucher';
	$service_voucher_prd = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );
	date_default_timezone_set('Asia/Kolkata');
	$dispatch_date = date('d-m-Y h:i:s a', time());
	update_post_meta( $order_id, 'dispatch_date', $dispatch_date );
		$home_delivery_total = 0;
		$installer_delivery_total = 0;
		foreach ($item_data as $item_key => $item_values)
		{
			if($item_values['variation_id'] != '')
			{
				$item_id = $item_values['variation_id'];
			}
			else
			{
				$item_id = $item_values['product_id'];
			}
			$quantity = $item_values['quantity'];
			$product_variation = wc_get_product( $item_id );
			$variation_des = $product_variation->get_description();
			$item_name = substr($variation_des, 0, 25);
			$installer = "SELECT * FROM th_cart_item_installer WHERE order_id = '$order_id' and product_id = '$item_id'";
			$row = $wpdb->get_results($installer);
			if(!empty($row))
			{
				foreach ($row as $key => $installer)
				{
					$destination = $installer->destination;
					$installer_id = $installer->installer_id;
					 $installer_mobile = $wpdb->get_var( $wpdb->prepare( "SELECT contact_no FROM th_installer_data WHERE installer_data_id='%s' LIMIT 1" , $installer_id) );
					if($destination == 0)
					{
						$home_delivery_total = $home_delivery_total + 1;
						$home_delivery_name = substr($variation_des, 0, 25);
					}
					if($destination == 1)
					{
						$installer_delivery_total = $installer_delivery_total + 1;
						$installer_delivery_name = $item_name;
						$installer_text = "Dispatched: Tyrehub Order No ".$order_id." with ".$item_name." and ".$quantity." item is on time and delivered to your store.";
						$installer_text = trim(preg_replace('/\s+/', ' ', $installer_text));
						$installer_text = str_replace(' ', '%20', $installer_text);
						sms_send_to_customer($installer_text,$installer_mobile);

					}
				}
			}
		}
		/* -------------------- Customer SMS ------------------*/
		if($home_delivery_total != 0 && $home_delivery_name != '')
		{
			if($mobile_no != '')
			{
				//echo $home_delivery_name;
			   // echo $home_delivery_total;
				$customer_text = "Dispatched: Your Tyrehub Order with Tyre ".$home_delivery_name." and ".$home_delivery_total." item is on time and delivered to your Home.";
				//die();
				
				$customer_text = str_replace(' ', '%20', $customer_text);
				sms_send_to_customer($customer_text,$mobile_no);

			}
		}
		if($installer_delivery_total != 0 && $installer_delivery_name != '')
		{
			if($mobile_no != '')
			{
				$customer_text = "Dispatched: Your Tyrehub Order with Tyre ".$installer_delivery_name."and ".$installer_delivery_total." item is on time and delivered to your selected Installer.";
				$customer_text = str_replace(' ', '%20', $customer_text);
				sms_send_to_customer($customer_text,$mobile_no);

			}
		}
		/* -------------------- Customer SMS End ------------------*/
		/* -------------------- Tyrehub SMS ------------------*/
		if($variation_des != '')
		{
			$tyrehub_text = "Dispatched: Tyrehub Order No ".$order_id." for Tyre ".$variation_des." and ".$total_item." item is Dispatched to Installer/Customer.";
			$tyrehub_text = str_replace(' ', '%20', $tyrehub_text);
			$mobile=9978619860;
			sms_send_to_customer($tyrehub_text,$mobile);

		}
		/* -------------------- Tyrehub SMS ------------------*/
  //  die();
}
add_action( 'woocommerce_order_status_deltoinstaller',  'sms_after_deltoinstaller');
function sms_after_deltoinstaller($order_id)
{
	global $wpdb;
	$order = new WC_Order( $order_id );
	$order_data = $order->get_data();
	$item_data = $order->get_items();
	$mobile_no = $order_data['billing']['phone'];
	$first_name = $order_data['billing']['first_name'];
	$last_name = $order_data['billing']['last_name'];
	$total_item = count($item_data);
	$sku = 'service_voucher';
	$service_voucher_prd = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );
		$home_delivery_total = 0;
		$installer_delivery_total = 0;
		foreach ($item_data as $item_key => $item_values)
		{
			if($item_values['variation_id'] != '')
			{
				$item_id = $item_values['variation_id'];
			}
			else
			{
				$item_id = $item_values['product_id'];
			}
			$quantity = $item_values['quantity'];
			$product_variation = wc_get_product( $item_id );
			$variation_des = $product_variation->get_description();
			$item_name = substr($variation_des, 0, 25);
			$installer = "SELECT * FROM th_cart_item_installer WHERE order_id = '$order_id' and product_id = '$item_id'";
			$row = $wpdb->get_results($installer);
			if(!empty($row))
			{
				foreach ($row as $key => $installer)
				{
					$destination = $installer->destination;
					$installer_id = $installer->installer_id;
					$installer_data_id = $installer->cart_item_installer_id;
					$installer_mobile = $wpdb->get_var( $wpdb->prepare( "SELECT contact_no FROM th_installer_data WHERE installer_data_id='%s' LIMIT 1" , $installer_id) );
					if($destination == 0)
					{
						$home_delivery_total = $home_delivery_total + 1;
						$home_delivery_name = substr($variation_des, 0, 25);
					}
					if($destination == 1)
					{
						$installer_name = $wpdb->get_var( $wpdb->prepare( "SELECT business_name FROM th_installer_data WHERE installer_data_id='%s' LIMIT 1" , $installer_id) );
						$installer_no = $wpdb->get_var( $wpdb->prepare( "SELECT contact_no FROM th_installer_data WHERE installer_data_id='%s' LIMIT 1" , $installer_id) );
						$installer_delivery_total = $installer_delivery_total + 1;
						$installer_delivery_name = $variation_des;
						// sms to installer
						$installer_text = "Contact Customer: Tyrehub order for ".$item_name." and ".$quantity." item has been successfully delivered. Please contact customer ".$first_name.' '.$last_name." on cell ".$mobile_no." to book the appointment.";
						$installer_text = str_replace(' ', '%20', $installer_text);
						sms_send_to_customer($installer_text,$installer_mobile);

						// sms to customer if select installer
						$customer_text = "Ready to Install: Your Tyrehub order for ".$item_name." and ".$quantity." item has been successfully delivered to your Selected Service Partner ".$installer_name." Please call ".$installer_no." and book your appointment. More info call 18002335551.";
						$customer_text = trim(preg_replace('/\s+/', ' ', $customer_text));
						$customer_text = str_replace(' ', '%20', $customer_text);
						sms_send_to_customer($customer_text,$installer_mobile);
						
					/* -------------------- Tyrehub SMS ------------------*/
							$tyrehub_text = "Delivered: Tyrehub order No ".$order_id." for ".$item_name." and ".$quantity." item has been successfully delivered to Installer ".$installer_name;
							$tyrehub_text = trim(preg_replace('/\s+/', ' ', $tyrehub_text));
							$tyrehub_text = str_replace(' ', '%20', $tyrehub_text);
							$mobile=9978619860;
							sms_send_to_customer($tyrehub_text,$mobile);
					/* -------------------- Tyrehub SMS ------------------*/
					}
				}
			}
		}
		if($installer_delivery_total != 0 && $installer_delivery_name != '')
		{
			if($mobile_no != '')
			{
			}
		}
		/* -------------------- Customer SMS End ------------------*/
   // die();
}
add_action( 'woocommerce_order_status_completed',  'sms_after_order_complete');
function sms_after_order_complete($order_id)
{
	global $wpdb;
	$order = new WC_Order( $order_id );
	$order_data = $order->get_data();
	$item_data = $order->get_items();
	$mobile_no = $order_data['billing']['phone'];
	$first_name = $order_data['billing']['first_name'];
	$last_name = $order_data['billing']['last_name'];
	$total_item = count($item_data);
	$sku = 'service_voucher';
	$service_voucher_prd = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );
	date_default_timezone_set('Asia/Kolkata');
	$complete_date = date('d-m-Y h:i:s a', time());
	update_post_meta( $order_id, 'complete_date', $complete_date );
	$update_service_status = $wpdb->get_results('UPDATE th_cart_item_installer set status = "completed" , completed_date = "'.$complete_date.'" where order_id = "'.$order_id.'"');
	$update_service_status = $wpdb->get_results('UPDATE th_cart_item_service_voucher set status = "completed" , completed_date = "'.$complete_date.'" where order_id = "'.$order_id.'"');
		$home_delivery_total = 0;
		$installer_delivery_total = 0;
		foreach ($item_data as $item_key => $item_values)
		{
			if($item_values['variation_id'] != '')
			{
				$item_id = $item_values['variation_id'];
			}
			else
			{
				$item_id = $item_values['product_id'];
			}
			$quantity = $item_values['quantity'];
			$product_variation = wc_get_product( $item_id );
			$variation_des = $product_variation->get_description();
			$item_name = substr($variation_des, 0, 25);
			$installer = "SELECT * FROM th_cart_item_installer WHERE order_id = '$order_id' and product_id = '$item_id'";
			$row = $wpdb->get_results($installer);
			if(!empty($row))
			{
				foreach ($row as $key => $installer)
				{
					$destination = $installer->destination;
					$installer_id = $installer->installer_id;
					$installer_data_id = $installer->cart_item_installer_id;
					 $installer_mobile = $wpdb->get_var( $wpdb->prepare( "SELECT contact_no FROM th_installer_data WHERE installer_data_id='%s' LIMIT 1" , $installer_id) );
					if($destination == 0)
					{
						$home_delivery_total = $home_delivery_total + 1;
						$home_delivery_name = $item_name;
					}
					if($destination == 1)
					{
						$installer_name = $wpdb->get_var( $wpdb->prepare( "SELECT business_name FROM th_installer_data WHERE installer_data_id='%s' LIMIT 1" , $installer_id) );
						$installer_delivery_total = $installer_delivery_total + 1;
						$installer_delivery_name = $item_name;
		/* -------------------- Tyrehub SMS ------------------*/
				   $tyrehub_text = "Process Completed: Tyerehub order No ".$order_id." for ".$item_name." and ".$quantity." item has been completed by Installer ".$installer_name.".";
			  //  echo $variation_des;
					$prd_name = trim($installer_delivery_name,' ');
					$tyrehub_text = "Process Completed: Tyerehub order No ".$order_id." for ".$item_name." and ".$quantity." item has been completed by Installer ".$installer_name;
					$tyrehub_text = trim(preg_replace('/\s+/', ' ', $tyrehub_text));
					$tyrehub_text = trim($tyrehub_text,' ');
					$tyrehub_text = str_replace(' ', '%20', $tyrehub_text);
					$mobile=9978619860;
					sms_send_to_customer($tyrehub_text,$mobile);
			/* -------------------- Tyrehub SMS ------------------*/
			// sms to installer
					$installer_text = "Dear Service Partner, thank you for serving our customer for installation of the tyres. We will collect feedback form the customer and share with you to help you serve better.";
					$installer_text = trim(preg_replace('/\s+/', ' ', $installer_text));
					$installer_text = str_replace(' ', '%20', $installer_text);
					sms_send_to_customer($installer_text,$installer_mobile);

					}
				}
			}
		}
		if($installer_delivery_total >= 1)
		{
			// sms to customer if select installer
			 $customer_text = "Dear Customer, thank you for choosing Tyrehub.com to buy your car tyres and get it installed from our trusted service partners. Hope you have a good experience with us if you like please leave your feedback here to help improve our services.";
		  //  die();
			$customer_text = trim(preg_replace('/\s+/', ' ', $customer_text));
			$customer_text = str_replace(' ', '%20', $customer_text);
			sms_send_to_customer($customer_text,$mobile_no);

		}
		/* -------------------- Customer SMS ------------------*/
		if($home_delivery_total != 0 && $home_delivery_name != '')
		{
			if($mobile_no != '')
			{
				$customer_text = "Delivered:Your Tyrehub order for ".$home_delivery_name." and ".$home_delivery_total." item has been successfully delivered. More info call 18002335551.";
				$ch1 = curl_init();
				$customer_text = str_replace(' ', '%20', $customer_text);
				sms_send_to_customer($customer_text,$mobile_no);

			}
		}
}
add_action( 'woocommerce_order_status_cancelled',  'sms_after_order_cancelled');
function sms_after_order_cancelled($order_id)
{
	global $wpdb;
	$order = new WC_Order( $order_id );
	$order_data = $order->get_data();
	$item_data = $order->get_items();
	$mobile_no = $order_data['billing']['phone'];
   // $mobile_no = 9662703553;
	$first_name = $order_data['billing']['first_name'];
	$last_name = $order_data['billing']['last_name'];
	$total_item = count($item_data);
	$sku = 'service_voucher';
	$service_voucher_prd = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );
		$home_delivery_total = 0;
		$installer_delivery_total = 0;
		foreach ($item_data as $item_key => $item_values)
		{
			if($item_values['variation_id'] != '')
			{
				$item_id = $item_values['variation_id'];
			}
			else
			{
				$item_id = $item_values['product_id'];
			}
			$quantity = $item_values['quantity'];
			$product_variation = wc_get_product( $item_id );
			$variation_des = $product_variation->get_description();
			$item_name = substr($variation_des, 0, 25);
			$installer = "SELECT * FROM th_cart_item_installer WHERE order_id = '$order_id' and product_id = '$item_id'";
			$row = $wpdb->get_results($installer);
			if(!empty($row))
			{
				foreach ($row as $key => $installer)
				{
					$destination = $installer->destination;
					$installer_id = $installer->installer_id;
					$installer_data_id = $installer->cart_item_installer_id;
					 $installer_mobile = $wpdb->get_var( $wpdb->prepare( "SELECT contact_no FROM th_installer_data WHERE installer_data_id='%s' LIMIT 1" , $installer_id) );
					if($destination == 0)
					{
						$home_delivery_total = $home_delivery_total + 1;
						$home_delivery_name = $item_name;
					}
					if($destination == 1)
					{
						$installer_name = $wpdb->get_var( $wpdb->prepare( "SELECT business_name FROM th_installer_data WHERE installer_data_id='%s' LIMIT 1" , $installer_id) );
						$installer_delivery_total = $installer_delivery_total + 1;
						$installer_delivery_name = $item_name;
		/* -------------------- Tyrehub SMS ------------------*/
				   $tyrehub_text = "Tyerehub order No ".$order_id." for ".$item_name." and ".$quantity." item has been Cancelled.";
			  //  echo $variation_des;
					$prd_name = trim($installer_delivery_name,' ');
					$tyrehub_text = "Process Completed: Tyerehub order No ".$order_id." for ".$item_name." and ".$quantity." item has been item has been Cancelled.";
					$tyrehub_text = trim(preg_replace('/\s+/', ' ', $tyrehub_text));
					$tyrehub_text = trim($tyrehub_text,' ');
					$tyrehub_text = str_replace(' ', '%20', $tyrehub_text);
					$mobile=9978619860;
					sms_send_to_customer($tyrehub_text,$mobile_no);

			/* -------------------- Tyrehub SMS ------------------*/
			// sms to installer
					$installer_text = "Dear Service Partner, Tyerehub order No ".$order_id." for ".$item_name." and ".$quantity." item has been Cancelled for more info call to 18002335551";
					$installer_text = trim(preg_replace('/\s+/', ' ', $installer_text));
					$installer_text = str_replace(' ', '%20', $installer_text);
					sms_send_to_customer($installer_text,$installer_mobile);

					}
				}
			}
		}
			 $customer_text = "Dear Customer, Tyerehub order No ".$order_id." for ".$item_name." and ".$quantity." item has been Cancelled for more info call to 18002335551";
		  //  die();
			$customer_text = trim(preg_replace('/\s+/', ' ', $customer_text));
			$customer_text = str_replace(' ', '%20', $customer_text);
			sms_send_to_customer($customer_text,$mobile_no);

}
add_action( 'woocommerce_order_status_failed',  'sms_after_order_failed');
function sms_after_order_failed($order_id)
{
	global $wpdb;
	$order = new WC_Order( $order_id );
	$order_data = $order->get_data();
	$item_data = $order->get_items();
	$mobile_no = $order_data['billing']['phone'];
	$first_name = $order_data['billing']['first_name'];
	$last_name = $order_data['billing']['last_name'];
	$total_item = count($item_data);
	$sku = 'service_voucher';
	$service_voucher_prd = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );
		$home_delivery_total = 0;
		$installer_delivery_total = 0;
		foreach ($item_data as $item_key => $item_values)
		{
			if($item_values['variation_id'] != '')
			{
				$item_id = $item_values['variation_id'];
			}
			else
			{
				$item_id = $item_values['product_id'];
			}
			$quantity = $item_values['quantity'];
			$product_variation = wc_get_product( $item_id );
			$variation_des = $product_variation->get_description();
			$item_name = substr($variation_des, 0, 25);
			$installer = "SELECT * FROM th_cart_item_installer WHERE order_id = '$order_id' and product_id = '$item_id'";
			$row = $wpdb->get_results($installer);
			if(!empty($row))
			{
				foreach ($row as $key => $installer)
				{
					$destination = $installer->destination;
					$installer_id = $installer->installer_id;
					$installer_data_id = $installer->cart_item_installer_id;
					 $installer_mobile = $wpdb->get_var( $wpdb->prepare( "SELECT contact_no FROM th_installer_data WHERE installer_data_id='%s' LIMIT 1" , $installer_id) );
					if($destination == 0)
					{
						$home_delivery_total = $home_delivery_total + 1;
						$home_delivery_name = $item_name;
					}
					if($destination == 1)
					{
						$installer_name = $wpdb->get_var( $wpdb->prepare( "SELECT business_name FROM th_installer_data WHERE installer_data_id='%s' LIMIT 1" , $installer_id) );
						$installer_delivery_total = $installer_delivery_total + 1;
						$installer_delivery_name = $item_name;
		/* -------------------- Tyrehub SMS ------------------*/
				   $tyrehub_text = "Tyerehub order No ".$order_id." for ".$item_name." and ".$quantity." item Payment failed by customer.";
			  //  echo $variation_des;
					$prd_name = trim($installer_delivery_name,' ');
					$tyrehub_text = "Process Completed: Tyerehub order No ".$order_id." for ".$item_name." and ".$quantity." item Payment failed by customer.";
					$tyrehub_text = trim(preg_replace('/\s+/', ' ', $tyrehub_text));
					$tyrehub_text = trim($tyrehub_text,' ');
					$tyrehub_text = str_replace(' ', '%20', $tyrehub_text);
					$mobile=9978619860;
					sms_send_to_customer($tyrehub_text,$mobile);

			/* -------------------- Tyrehub SMS ------------------*/
					}
				}
			}
		}
			$customer_text = "Dear Customer, Tyerehub order No ".$order_id." for ".$item_name." and ".$quantity." item Payment failed. please order place again for more info call to 18002335551";
			$customer_text = trim(preg_replace('/\s+/', ' ', $customer_text));
			$customer_text = str_replace(' ', '%20', $customer_text);

			sms_send_to_customer($customer_text,$mobile_no);

}
add_filter('woocommerce_placeholder_img_src', 'custom_woocommerce_placeholder_img_src');
function custom_woocommerce_placeholder_img_src( $src ) {
	$upload_dir = wp_upload_dir();
	$uploads = untrailingslashit( $upload_dir['baseurl'] );
	// replace with path to your image
	$src =  get_stylesheet_directory_uri().'/images/no_img1.png';
	return $src;
}
add_filter('woocommerce_placeholder_img', 'downloadclub_woocommerce_placeholder_img', 10, 3);
function downloadclub_woocommerce_placeholder_img($image_html, $size, $dimensions){
	$image      = wc_placeholder_img_src( $size );
	$src =  get_stylesheet_directory_uri().'/images/no_img1.png';
	$image_html = '<img src="' . esc_attr($src) . '" alt="' . esc_attr__( 'Placeholder', 'woocommerce' ) . '" width="' . esc_attr( $dimensions['width'] ) . '" class="woocommerce-placeholder wp-post-image" height="' . esc_attr( $dimensions['height'] ) . '" />';
	return $image_html;
}
function remove_menus()
{
	   $user = wp_get_current_user();
	   $role = $user->roles[0];
	   if($role == 'shop_manager')
	   {
		  remove_menu_page( 'index.php' );                  //Dashboard
		  remove_menu_page( 'jetpack' );                    //Jetpack*
		  remove_menu_page( 'edit.php' );                   //Posts
		  remove_menu_page( 'upload.php' );                 //Media
		  remove_menu_page( 'edit.php?post_type=page' );
		  remove_menu_page( 'edit.php?post_type=product' );    //Pages
		  remove_menu_page( 'edit-comments.php' );          //Comments
		  remove_menu_page( 'themes.php' );                 //Appearance
		  remove_menu_page( 'plugins.php' );                //Plugins
		  remove_menu_page( 'users.php' );                  //Users
		  remove_menu_page( 'tools.php' );                  //Tools
		  remove_menu_page( 'options-general.php' );        //Settings
		  remove_menu_page( 'wpcf7' );
		  remove_menu_page( 'duplicator');
	   }
	   if($role == 'supervisor-l1')
	   {
		  remove_menu_page( 'index.php' );                  //Dashboard
		  remove_menu_page( 'jetpack' );                    //Jetpack*
		  remove_menu_page( 'edit.php' );                   //Posts
		  remove_menu_page( 'upload.php' );                 //Media
		  remove_menu_page( 'edit.php?post_type=page' );
		  remove_menu_page( 'edit.php?post_type=product' );    //Pages
		  remove_menu_page( 'edit-comments.php' );          //Comments
		  remove_menu_page( 'themes.php' );                 //Appearance
		  remove_menu_page( 'plugins.php' );                //Plugins
		  remove_menu_page( 'users.php' );                  //Users
		  remove_menu_page( 'tools.php' );                  //Tools
		  remove_menu_page( 'options-general.php' );        //Settings
		  remove_menu_page( 'wpcf7' );
		  remove_menu_page( 'duplicator');
	   }
  /**/
}
add_action('admin_menu','remove_menus');
add_action( 'woocommerce_email_header', 'email_header_before', 1, 2 );
function email_header_before( $email_heading, $email ){
	$GLOBALS['email'] = $email;
}
add_action('wp_ajax_tracking_order_send_otp', 'tracking_order_send_otp');
add_action('wp_ajax_nopriv_tracking_order_send_otp', 'tracking_order_send_otp');
function tracking_order_send_otp()
{
	global $wpdb;
	echo $mobile_no = $_POST['mobile_no'];
	$otp = rand(100000,999999);
	$ch1 = curl_init();
	$message = "Your OTP for tracking your order is ".$otp;
	$message = str_replace(' ', '%20', $message);
	sms_send_to_customer($message,$mobile_no);

	$delete_otp = $wpdb->get_results("DELETE from th_tracking_order WHERE mobile_no = '$mobile_no'");
	 $insert = $wpdb->insert('th_tracking_order', array(
										'otp' => $otp,
										'mobile_no' => $mobile_no,
										));
	die();
}
add_action('wp_ajax_tracking_order_verify_otp', 'tracking_order_verify_otp');
add_action('wp_ajax_nopriv_tracking_order_verify_otp', 'tracking_order_verify_otp');
function tracking_order_verify_otp()
{
	global $wpdb;
	$mobile_no = $_POST['mobile_no'];
	$otp = $_POST['otp'];
	$installer = "SELECT * FROM th_tracking_order WHERE mobile_no = '$mobile_no' and otp = '$otp'";
	$row = $wpdb->get_results($installer);
	if(empty($row)){
		echo 'false';
	}
	else
	{
		echo 'true';
	}
	die();
}
add_action('wp_ajax_installer_product_by_attribute', 'installer_product_by_attribute');
add_action('wp_ajax_nopriv_installer_product_by_attribute', 'installer_product_by_attribute');
function installer_product_by_attribute()
{
	$width1 = explode('.',$_POST['width']);
	if($width1[1]){
		$width=$width1[0].'-'.$width1[1];
	}else{
		$width=$_POST['width'];
	}
	
	$diameter = $_POST['diameter'];
	$ratio = $_POST['ratio'];
	if($ratio=='R'){
		$ratio='r';
	}
	$name = $_POST['name'];
	$name = strtolower($name);
		$meta_query[]=array(
						'key'       => 'tyrehub_visible',
						'value'     => array('yes','contact-us'),
						'compare'   => 'IN',
					   );
		/*$meta_query[]=array(
						'key'       => 'attribute_pa_brand',
						'value'     => array('mrf'),
						'compare'   => 'NOT IN',
					);*/
		if($width){
			$meta_query[]=array(
				'key' => 'attribute_pa_width',
				'value' => $width,
				'compare' => 'IN',
			);
		}
		if($diameter){
			$meta_query[]=array(
								'key' => 'attribute_pa_diameter',
								'value' => $diameter,
								'compare' => 'IN',
							);
		}
		if($ratio){
			$meta_query[]=array(
								'key' => 'attribute_pa_ratio',
								'value' =>$ratio,
								'compare' => 'IN',
							);
		}
		if($name || $catslug){
			if($catslug && empty($name)){
				$name=$catslug;
			}
			$meta_query[]=array(
								'key' => 'attribute_pa_brand',
								'value' => $name,
								'compare' => 'IN',
							);
		}
	$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
	$args = array(
			'post_type' => 'product_variation',
			'paged' => $paged,
			'posts_per_page' => -1,
			'orderby'       => 'menu_order',
			'order'         => 'asc',
			'meta_query'=> array(
						'relation' => 'AND',$meta_query
			 ),
			);
	$variations = get_posts( $args );
	
	if($name != '')
	{
		$message .= ' Category: '.$name;
		if(!empty($variations))
		{
			foreach ( $variations as $variation )
			{
				$variation_ID = $variation->ID;
				$product_variation = wc_get_product( $variation_ID );
				$variation_des = $product_variation->get_description();
				$variation_des = strtolower($variation_des);
				if (strpos($variation_des, $name) !== false)
				{
					$product_arr[] = $variation_ID;
				}
			}
			if(!empty($product_arr))
			{
				$args = array(
					'post__in' => $product_arr,
					'post_type' => 'product_variation',
					'posts_per_page' => -1,
					'numberposts'   => -1,
					'orderby'       => 'menu_order',
					'order'         => 'asc',
				);
			}
			else{
				$args = array(
					 'post__in' => 'No Product Found',
					'post_type' => 'product_variation',
					'posts_per_page' => 0,
					'numberposts'   => 0,
				);
			}
		}
	}
	$variations = get_posts( $args );
	if(empty($variations))
	{
		echo $message;
	}else{
			// for discount price calculation
			date_default_timezone_set('Asia/Kolkata');
			$today_date = date('Y-m-d G:i');
			$today_date = strtotime($today_date);
			global $woocommerce ,$wpdb;
			$user_id = get_current_user_id();
			$SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
			$franchise=$wpdb->get_row($SQL);
			if(empty($franchise)){
			$sql = "SELECT * FROM th_discount_rule where status = 'on'";
			$rule_data = $wpdb->get_results($sql);
			$prd_discount_arr = [];
			foreach ($rule_data as $key => $rule_row)
			{
				$start_date = strtotime($rule_row->start_date);
				$end_date = strtotime($rule_row->end_date);
				if($today_date > $start_date && $today_date < $end_date)
				{
					$rule_row->name;
					$rule_id = $rule_row->rule_id;
					$rule_img = $rule_row->rule_img;
					$list_sql = "SELECT * FROM th_discount_product_list where rule_id = $rule_id and status = 'on'";
					$list_result = $wpdb->get_results($list_sql);
					if(!empty($list_result))
					{
						foreach ($list_result as $key => $list_row)
						{
							$list_prd_id = $list_row->product_id;
							$list_prd_amount = $list_row->amount;
							if(array_key_exists($list_prd_id, $prd_discount_arr))
							{
								$old_value = $prd_discount_arr[$list_prd_id];
								if($list_prd_amount > $old_value)
								{
									$prd_discount_arr[$list_prd_id] = $list_prd_amount;
								}
							}
							else{
								$prd_discount_arr[$list_prd_id] = array($list_prd_amount , $rule_img);
							}
						}
					}
				}
			}
		}
		  //  var_dump($prd_discount_arr);
		foreach ( $variations as $variation )
		{
			
			$variation_ID = $variation->ID;
			 $visiblity = get_post_meta($variation_ID, 'tyrehub_visible', true  );
			$product_variation = wc_get_product( $variation_ID );
			$variation_des = $product_variation->get_description();
			$_sale_price=get_post_meta($variation_ID,'_sale_price',true);
		if(!empty($franchise)){
/*$SQLSHIV="SELECT * FROM `th_supplier_products_final` where product_id='$variation_ID' AND updated_date>DATE_SUB(NOW(),INTERVAL 1 YEAR)  GROUP by id HAVING min(tyre_price+tube_price) ORDER BY (tyre_price+tube_price) ASC LIMIT 0,1";*/
$supplier_id=get_post_meta($variation_ID,'active_supplier',true);
$SQLSHIV="SELECT * FROM `th_supplier_products_final` where supplier_id='$supplier_id' AND product_id='$variation_ID' ORDER BY (tyre_price+tube_price) ASC LIMIT 0,1";
		    $productsshiv=$wpdb->get_row($SQLSHIV);
		    $tube_price = $productsshiv->tube_price;
		    $tyre_price = $productsshiv->tyre_price;
		    $variation_price=($tube_price +$tyre_price) + (($tube_price +$tyre_price)*0)/100;
		}else{
			if($product_variation->get_price()<=$_sale_price){
				$variation_price = $_sale_price;
			}else{
				$variation_price = $product_variation->get_price();
			}
		}
			$args = array(
				'ex_tax_label'       => false,
				'currency'           => '',
				'decimal_separator'  => wc_get_price_decimal_separator(),
				'thousand_separator' => wc_get_price_thousand_separator(),
				'decimals'           => wc_get_price_decimals(),
				'price_format'       => get_woocommerce_price_format(),
				);
			$parent_id = $product_variation->get_parent_id();
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $parent_id ),'single-post-thumbnail');
			$sku = get_post_meta($id,'_sku',true);
			$variation = wc_get_product($variation_ID);
			$variation_price1 = wc_price($variation_price,$args);
			$variation = wc_get_product($variation_ID);
			//if($variation->attributes['pa_brand'] != falken){	
			?>
				<div class="single-product demo" data-id="<?php echo $variation_ID; ?>">
					<div class="image">
						<img src="<?php  if($image[0] != ''){ echo $image[0]; }else{ echo bloginfo('template_url').'/images/no_img1.png'; } ?>" data-id="<?php echo $loop->post->ID; ?>">
					</div>
					<div class="name"><?php echo $variation_des; ?></div>
					<div class="price" id="price<?php echo $variation_ID; ?>" data-price="<?=$variation_price?>"><?php echo $variation_price1; ?></div>
					<div class="qty">
						<select name="fran_quantity[]" id="fran_quantity<?php echo $variation_ID; ?>" data-proid="<?php echo $variation_ID; ?>">
							<option value="0">0</option>
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
							<option value="7">7</option>
							<option value="8">8</option>
							<option value="9">9</option>
							<option value="10">10</option>
						</select>
					</div>
					<?php if($visiblity == "contact-us"){ ?>
							<a href="#" class="btn btn-invert button product_type_simple add_to_cart_button ajax_add_to_cart" rel="nofollow"><span>Please call us</span></a>
						<?php
							}else{ ?>
								<input type="checkbox" class="frabaddtocart" id="addcart<?php echo $variation_ID; ?>" name="franaddtocart[]" value="<?php echo $variation_ID; ?>">
						<?php } ?>
					<div class="price-total"><i class="fa fa-inr" aria-hidden="true"></i> <span class="price-total<?php echo $variation_ID; ?>">00.00</span></div>
				</div>
				<?php
			//}
		}
	}
	die();
}
add_action('wp_ajax_franchise_product_by_customer', 'franchise_product_by_customer');
add_action('wp_ajax_nopriv_franchise_product_by_customer', 'franchise_product_by_customer');
function franchise_product_by_customer()
{
		
		$width = str_replace(".","-",$_POST['width']);
		$diameter = $_POST['diameter'];
		if($_POST['ratio']==0){
			$ratio = 'r';
		}else{
			$ratio = $_POST['ratio'];
		}
		$vehicle_type = $_POST['vehicle_type'];
		//$name = strtolower($name);
		$name = "falken";
		if($width!='' AND $diameter!='' AND $ratio!=''){
				$meta_query[]=array(
						'key'       => 'tyrehub_visible',
						'value'     => array('yes','contact-us'),
						'compare'   => 'IN',
					   );
		/*$meta_query[]=array(
						'key'       => 'attribute_pa_brand',
						'value'     => array('mrf'),
						'compare'   => 'NOT IN',
					);*/
		if($width){
			$meta_query[]=array(
				'key' => 'attribute_pa_width',
				'value' => $width,
				'compare' => 'IN',
			);
		}
		if($diameter){
			$meta_query[]=array(
								'key' => 'attribute_pa_diameter',
								'value' => $diameter,
								'compare' => 'IN',
							);
		}
		if($ratio){
			$meta_query[]=array(
								'key' => 'attribute_pa_ratio',
								'value' => $ratio,
								'compare' => 'IN',
							);
		}
		/*if($name || $catslug){
			if($catslug && empty($name)){
				$name=$catslug;
			}
			$meta_query[]=array(
								'key' => 'attribute_pa_brand',
								'value' => $name,
								'compare' => 'NOT IN',
							);
		}*/
	$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
	$args = array(
			'post_type' => 'product_variation',
			'paged' => $paged,
			'posts_per_page' => -1,
			'orderby'       => 'menu_order',
			'order'         => 'asc',
			'meta_query'=> array(
						'relation' => 'AND',$meta_query
			 ),
			);
	$variations = get_posts( $args );
	$products = new WP_Query( $args );
	//post_count$count = $products->post_count;
	//$variations = get_posts( $args );
	if ( $products->have_posts() ) {
					echo '<div class="top-bar-search">'.$products->post_count.' products found</div>';
					do_action( 'woocommerce_before_shop_loop');
					//wp_reset_postdata();
					woocommerce_product_loop_start();
					while ( $products->have_posts() ) : $products->the_post();
							
							//echo "<pre>";
							//print_r($products->posts);
						/**
						 * Hook: woocommerce_shop_loop.
						 *
						 * @hooked WC_Structured_Data::generate_product_data() - 10
						 */
						do_action( 'woocommerce_shop_loop' );
						wc_get_template_part('content', 'offline-product');
					endwhile;
					wp_reset_postdata();
					woocommerce_product_loop_end();
					/**
					 * Hook: woocommerce_after_shop_loop.
					 *
					 * @hooked woocommerce_pagination - 10
					 */
					do_action( 'woocommerce_after_shop_loop' );
				}else {
					/**
					 * Hook: woocommerce_no_products_found.
					 *
					 * @hooked wc_no_products_found - 10
					 */
					do_action('woocommerce_no_products_found');
				}
		}else{
			do_action('woocommerce_no_products_found');
		}
		
	die();
}
function woo_custom_ajax_variation_threshold( $qty, $product ) {
	return 200;
}
add_filter( 'woocommerce_ajax_variation_threshold', 'woo_custom_ajax_variation_threshold', 10, 2 );
add_action('woocommerce_cart_calculate_fees' , 'add_custom_fees');
function add_custom_fees( WC_Cart $cart )
{
	global $woocommerce , $wpdb;
	$session_id = WC()->session->get_customer_id();
	// Calculate the amount to reduce
	$user = wp_get_current_user();
	$role = $user->roles[0];
	if($role == 'Installer')
	{
		$user_id = get_current_user_id();
		$SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
		$franchise=$wpdb->get_row($SQL);
		
		$minifra="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_minifranchise='yes'";
		$minifranchise=$wpdb->get_row($minifra);
		
		if(empty($franchise) && empty($minifranchise)){
			$discount = round($cart->subtotal * 0.02);
			$cart->add_fee( 'Installer Discount(2%)', -$discount);
		}
		
		if($minifranchise){
			$discount = round($cart->subtotal * 0.04);
			$cart->add_fee( 'Installer Discount(4%)', -$discount);
		}
	}
	$total_gst = 0;
	$total_service_charge = 0;
	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item )
	{
		$product_id = $cart_item['product_id'];
		$proarray[]=$product_id;
		$cart_item_qty = $cart_item['quantity'];
		if($cart_item['variation_id'])
		{
			$product_id = $cart_item['variation_id'];
		}
		$product_variation = wc_get_product( $product_id );
		$variation_data = $product_variation->get_data();
		//$price = $product_variation->get_price();
		$price = get_post_meta($product_id, '_sale_price', true );
		 $tyre_type = $variation_data['attributes']['pa_tyre-type'];
		if($tyre_type == 'tubeless')
		{
			$gst = round($price * 28 / 128);
			$gst_qty = $cart_item_qty * $gst;
			$total_gst = $total_gst + $gst_qty;
			//$new_price = $price - $gst;
			$new_price = $price*$cart_item_qty;
		}
		if($tyre_type == 'tubetyre')
		{
			$tyre_price = get_post_meta($product_id, 'tyre_price', true );
			$tube_price = get_post_meta($product_id, 'tube_price', true );
			$tyre_gst = round($tyre_price * 28 / 128);
			//$tube_gst = round($tube_price * 18 / 118);
			$tube_gst = round($tube_price * 28 / 128);
			$gst = $tyre_gst + $tube_gst;
			$gst_qty = $cart_item_qty * $gst;
			$total_gst = $total_gst + $gst_qty;
			//$new_price = $price - $gst;
			$new_price = $price;
			$new_price =($new_price*$cart_item_qty);
		}
		// Service charges
			$destination_data = "SELECT *
						FROM th_cart_item_installer
						WHERE cart_item_key = '$cart_item_key' and session_id = '$session_id' and order_id = ''";
			$row = $wpdb->get_results($destination_data);
			if(!empty($row))
			{
				foreach ($row as $key => $data)
				{
					$destination = $data->destination;
				}
				if($destination == 1)
				{
					$services = "SELECT *
							FROM th_cart_item_services
							WHERE cart_item_key = '$cart_item_key' and session_id = '$session_id' and order_id = ''";
					 $row = $wpdb->get_results($services);
					 foreach ($row as $key => $service)
					{
					   $service_name = $service->service_name;
						$tyre_count = $service->tyre;
						$rate = $service->rate;
					   $amount = $tyre_count * $rate;
						$total_service_charge = $total_service_charge + $amount;
					}
				}
				if($destination == 0){
					$product_variation_new = wc_get_product( $product_id );
					$prd_attr_vehicle = '';
					$variation_data = $product_variation_new->get_data();
						if($variation_data['attributes']['pa_vehicle-type'] != 'car-tyre'){
							$prd_attr_vehicle = $variation_data['attributes']['pa_vehicle-type'];
					}
					if($prd_attr_vehicle != ''){
						if($cart_item_qty >= 2 && $cart_item_qty <= 5 ){
							$home_delivery_charge = 200;
						}else if($cart_item_qty >= 6){
							$home_delivery_charge = 300;
						}else{
							$home_delivery_charge = 100;
						}
					}else{
						if($cart_item_qty >= 2 && $cart_item_qty <= 5 ){
							 $home_delivery_charge = 250;
						}else if($cart_item_qty >= 6){
							$home_delivery_charge = 400;
						}else{
							$home_delivery_charge = 150;
						}
					}
					 $home_delivery_charge=0;
				  $total_service_charge = $total_service_charge + $home_delivery_charge;
				}
			}
		//$new_price=0;
		//if($new_price<=0){
			$new_price1=$new_price1+$cart_item['line_subtotal'];
		//}
		//line_subtotal
	}
	$new_price=$new_price1;
	if($role == 'btobpartner')
	{   $user_id=get_current_user_id();
		global $wpdb;
		$SQL="SELECT * FROM th_business_partner_data WHERE user_id='".$user_id."'";
		$results=$wpdb->get_row($SQL);
		$voucher_gst = round($total_service_charge * 18 / 118);
		//$voucher_price=$total_service_charge-$voucher_gst;
		$voucher_price=$total_service_charge;
		$new_price=$voucher_price+$new_price;
		$discount = round($new_price * $results->percentage)/100;
		$cart->add_fee( 'BTOB Partner Discount('.$results->percentage.'%)', round(-$discount));
		/*if(count($proarray)==1 && in_array(3550,$proarray)){
		}else{
		   $cart->add_fee( 'BTOB Partner Discount('.$results->percentage.'%)', -$discount);
		}*/
	}
	$total_service_charge;
	$service_gst = $total_service_charge * 18 / 118;
	$total_gst = $total_gst + $service_gst;
	$sgst = $total_gst /2;
}
add_filter( 'wc_add_to_cart_message_html', 'empty_wc_add_to_cart_message');
	function empty_wc_add_to_cart_message() {
		return '';
	};
	add_filter( 'woocommerce_add_to_cart_redirect', 'custom_redirect_function' );
	function custom_redirect_function()
	{
		global $woocommerce;
		$user = wp_get_current_user();
		$role = $user->roles[0];
		if(isset($_POST['variation_id'])){
			$product_id = $_POST['variation_id'];
		}
		else{
			$product_id = $_GET['variation_id'];
		}
		foreach ($woocommerce->cart->get_cart() as $cart_item_key => $cart_item )
		{
			 if($cart_item['variation_id'] == $product_id ){
				  $cart_item_id = $cart_item_key;
				  $vehicle_type=$cart_item['custom_data']['vehicle_type'];
			 }
			 $qty = $cart_item['quantity'];
				$total_qty = $quantity + $qty;
		}
		if($role == 'Installer')
		{
			$myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' );
			//return get_permalink( $myaccount_page_id ).'/offline-purchase';
		}else{
			return get_site_url().'/online-tyre-services-partner/?product_id='.$product_id.'&cart_item_id='.$cart_item_id.'&total_qty='.$total_qty;
		}
	}
 add_filter('woocommerce_show_variation_price',      function() { return TRUE;});
add_filter('woocommerce_bacs_account_fields','custom_bacs_fields');
function custom_bacs_fields()
{
	global $wpdb;
	global $wp;
	$order_id  = absint( $wp->query_vars['order-received'] );
	$account_details = get_option( 'woocommerce_bacs_accounts',
				array(
					array(
						'account_name'   => get_option( 'account_name' ),
						'account_number' => get_option( 'account_number' ),
						'sort_code'      => get_option( 'sort_code' ),
						'bank_name'      => get_option( 'bank_name' ),
						'iban'           => get_option( 'iban' ),
						'bic'            => get_option( 'bic' )
					)
				)
			);
	$account_fields = array(
		'account_name'   => array(
			'label' => 'Beneficiary Name',
			'value' => $account_details[0]['account_name']
		),
		'account_number' => array(
			'label' => __( 'Beneficiary Account number', 'woocommerce' ),
			'value' => $account_details[0]['account_number']
		),
		'sort_code' => array(
			'label' => __( 'Beneficiary IFSC Code', 'woocommerce' ),
			'value' => $account_details[0]['sort_code']
		),
		'bank_name' => array(
			'label' => __( 'Beneficiary Bank name', 'woocommerce' ),
			'value' => $account_details[0]['bank_name']
		),
		'bic'            => array(
			'label' => __( 'Branch name', 'woocommerce' ),
			'value' => $account_details[0]['bic']
		),
		'iban'      => array(
			'label' => 'Nature of payment/ Purpose',
			'value' => $account_details[0]['iban'].$order_id
		)
	);
	return $account_fields;
}
add_action( 'wp_footer', 'bbloomer_cart_refresh_update_qty' );
function bbloomer_cart_refresh_update_qty() {
	if (is_cart()) {
		?>
		
		<script type="text/javascript">
			jQuery(document).on('change', '.qty', function(){
				//alert('in cart change qty');
				var qty = jQuery(this).val();
				jQuery(this).parent().find('input.qty').val(qty);
				var product_id = jQuery(this).attr('data-product-id');
				jQuery('.update-item').text(product_id);
				jQuery("[name='update_cart']").prop("disabled", false);
				jQuery("[name='update_cart']").trigger("click");

			//});

			//jQuery( document.body ).on( 'updated_cart_totals', function(){
				//re-do your jquery
				//alert('in update cart totals');
				var product_id = jQuery('.update-item').text();
				var temp = '.'+product_id+'-change-service';
				var admin_url = jQuery('.admin_url').text();
				console.log(temp);
				jQuery.ajax({
							type: "POST",
							url: admin_url,
							data: {
								action: 'update_tyrefitment_qty',
								product_id : product_id,
								qty : qty,
							},
							success: function (data)
							{
								jQuery("[name='update_cart']").trigger("click");

								//jQuery(temp).trigger('click');
								jQuery(temp).parents('.product-name').find('.product-service-list').html(data);
								jQuery.ajax({
									type: "POST",
									url: admin_url,
									data: {
										action: 'update_servicecharge_after_update_cart',
										product_id : product_id,
									},
									success: function (data)
									{
										var data = JSON.parse(data);
										jQuery('.head-cart .cart-contents-count').html(data.qty_total);
										
										jQuery(temp).parents('.product-name').find('.total').html(data.service_charge);
									  //  $('.cart_totals table .cart-subtotal .woocommerce-Price-amount').html(data.subtotal);
										jQuery('.cart_totals table .order-total .woocommerce-Price-amount').html(data.subtotal);
									 //    jQuery( 'html, body' ).stop();
									},
								});
							},
						});
			});
		</script>
		<?php
	}
	if(is_checkout())
	{
		 ?>
		<script type="text/javascript">
			jQuery(document).ready(function($)
			{
				var admin_url = $('.admin_url').text();
				if($('.check-gst-no').prop("checked") == true)
				{
					 $.ajax({
							type: "POST",
							url: admin_url,
							data: {
								action: 'gst_fields',
							},
							success: function (data)
							{
								$('.gst-field-container').html(data);
								$('.gst-field-container').css('display','block');
							},
						});
				}
				else{
					$('.gst-field-container').css('display','none');
					$('.gst-field-container').html('');
				}
			});
		</script>
		<?php
	}
}
function action_woocommerce_save_account_details( $user_id )
{
	global $wpdb;
	$store_add = $_POST['store_add'];
	$store_name = $_POST['store_name'];
	$state = $_POST['state'];
	$city = $_POST['city'];
	$pincode = $_POST['pincode'];
	$gst_no = $_POST['gst_no'];
	$wifi_service = 'no';
	$tea = 'no';
	$pickup = 'no';
	$water = 'no';
	$installer_id = $_POST['installer_id'];
	$supplier_id = $_POST['supplier_id'];
	$cmp_name = $_POST['cmp_name'];
	$cmp_add = $_POST['cmp_add'];
	$contact_person = $_POST['account_display_name'];
	$store_phone = $_POST['store_phone'];
	$pay_method = $_POST['pay_method'];
if(current_user_can('Installer')){
			if(!empty($_POST['fc-check'])) {
				$facility_arr = serialize($_POST['fc-check']);
			}
			if(!empty($_POST['as-check'])) {
				$as_arr = serialize($_POST['as-check']);
			}
		global $wpdb;
		$SQL="SELECT * FROM th_installer_data WHERE is_franchise='yes' AND user_id='".get_current_user_id()."'";
	    $franchise=$wpdb->get_row($SQL);
	    if($franchise){
            $wpdb->query('DELETE  FROM wp_franchises_choose_pmethod WHERE franchise_id = "'.$installer_id.'"');            
            $pay_method=$_POST['pay_method'];
            foreach ($pay_method as $key => $value) {
                # code...
                $insert = $wpdb->insert('wp_franchises_choose_pmethod', array( 
                    'franchise_id' => $installer_id,
                    'payment_id' =>$value,
                    'status'=>1
                ));
            }
	    }
	$update = $wpdb->get_results("UPDATE th_installer_data SET address = '$store_add' , business_name = '$store_name' , city = '$city' , state = '$state' , pincode = '$pincode' , gst_no = '$gst_no' , company_name = '$cmp_name' , company_add = '$cmp_add' , store_phone = '$store_phone', contact_person = '$contact_person' WHERE installer_data_id = '$installer_id'");
	$update = $wpdb->get_results("UPDATE th_installer_meta SET meta_value = '$facility_arr' WHERE installer_id = '$installer_id' and meta_name = 'facilities'");
	$update = $wpdb->get_results("UPDATE th_installer_meta SET meta_value = '$as_arr' WHERE installer_id = '$installer_id' and meta_name = 'additional_services'");
   }
   if(current_user_can('supplier')){
	$update = $wpdb->get_results("UPDATE th_supplier_data SET address = '$store_add' , business_name = '$store_name' , city = '$city' , state = '$state' , pincode = '$pincode' , gst_no = '$gst_no' , company_name = '$cmp_name' , company_add = '$cmp_add' , store_phone = '$store_phone', contact_person = '$contact_person' WHERE supplier_data_id = '$supplier_id'");
   }
   $user_id=get_current_user_id();
   if(isset($user_id))
	{
		//$user_id = $_POST['user_id'];
		$gst_no = $_POST['gst_no'];
		$cmp_name = $_POST['cmp_name'];
		$cmp_add = $_POST['cmp_add'];
		update_user_meta( $user_id, 'first_name', $contact_person );
		update_user_meta( $user_id, 'gst_no', $gst_no );
		update_user_meta( $user_id, 'company_name', $cmp_name );
		update_user_meta( $user_id, 'company_add', $cmp_add );
	}
}
// add the action
add_action( 'woocommerce_save_account_details', 'action_woocommerce_save_account_details', 10, 1 );
add_filter('woocommerce_save_account_details_required_fields', 'remove_required_fields');
function remove_required_fields( $required_fields ) {
	// if(current_user_can('Installer') || current_user_can('supplier')){
	// 	unset($required_fields['account_first_name']);
	// 	unset($required_fields['account_last_name']);
	// }
	return $required_fields;
}
function wpse_my_custom_script() {
	?>
	<script type="text/javascript">
		jQuery(document).ready( function($) {
			$( "ul#wp-admin-bar-site-name-default a[href='https://www.tyrehub.com/']" ).attr( 'target', '_blank' );
		});
	</script>
	<?php
}
add_action( 'admin_head', 'wpse_my_custom_script' );
add_action('wp_ajax_lost_pass_send_otp', 'lost_pass_send_otp');
add_action('wp_ajax_nopriv_lost_pass_send_otp', 'lost_pass_send_otp');
function lost_pass_send_otp()
{
	global $wpdb, $woocommerce;
	$mobile_no = $_POST['mobile_no'];
	$otp = rand(100000,999999);
	$user = get_userdatabylogin($mobile_no);
	if($user)
	{
		$user_id = $user->ID;
	}
	if($user_id)
	{
		$delete_service = $wpdb->get_results("DELETE from th_lost_password WHERE mobile_no = '$mobile_no'");
		$insert = $wpdb->insert('th_lost_password', array(
										'mobile_no' => $mobile_no,
										'otp' => $otp,
										));
		$ch1 = curl_init();
		$reset_pass_msg = "Tyrehub reset your account password using OTP ".$otp." Thank You Tyrehub Team";
		$reset_pass_msg = str_replace(' ', '%20', $reset_pass_msg);
		sms_send_to_customer($reset_pass_msg,$mobile_no);

		echo 'Done';
	}
	else{
		echo 'You are not registered with this number';
	}
	die();
}
add_action('wp_ajax_lost_pass_verify_otp', 'lost_pass_verify_otp');
add_action('wp_ajax_nopriv_lost_pass_verify_otp', 'lost_pass_verify_otp');
function lost_pass_verify_otp()
{
	global $woocommerce , $wpdb;
	$otp = $_POST['otp'];
	$mobile_no = $_POST['mobile_no'];
	$user = get_userdatabylogin($mobile_no);
	if($user)
	{
		$user_id = $user->ID;
	}
	$result = $wpdb->get_results("SELECT * from `th_lost_password` where otp = '$otp' AND mobile_no = '$mobile_no'");
	if($result){
		echo 1;
		$_SESSION['reset_password_user'] = $user_id;
	}
	else{
		echo 0;
	}
	die();
}
add_filter( 'woocommerce_min_password_strength', 'reduce_min_strength_password_requirement' );
function reduce_min_strength_password_requirement( $strength ) {
	// 3 => Strong (default) | 2 => Medium | 1 => Weak | 0 => Very Weak (anything).
	return 2;
}
add_filter( 'woocommerce_checkout_get_value' , 'clear_checkout_fields' );
function clear_checkout_fields($input)
{
	global $woocommerce,$wpdb;
	if(current_user_can('shop_manager'))
	{
		return '';
	}
	if(current_user_can('Installer'))
		{
			$user_id = get_current_user_id();
			$SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
			$franchise=$wpdb->get_row($SQL);
			if($franchise){
				if($_SESSION['cust_type']=='offline'){
						$customer_id=$_SESSION['fran_user_id'];
						$first_name=(empty(get_user_meta($customer_id, 'billing_first_name', true ))) ? get_user_meta($customer_id, 'first_name', true ) : get_user_meta($customer_id, 'billing_first_name', true );
						$last_name=(empty(get_user_meta($customer_id, 'billing_last_name', true ))) ? get_user_meta($customer_id, 'last_name', true ) : get_user_meta($customer_id, 'billing_last_name', true );
						$email=get_user_by('login',$_SESSION['mobile_no']);
						$emailid=(empty(get_user_meta($customer_id, 'billing_email', true ))) ? $email->user_email : get_user_meta($customer_id, 'billing_email', true );
						if($first_name==''){
						 return '';
						}
						if($last_name==''){
						 return '';
						}
						if($emailid==''){
						 return '';
						}
					}
			}
		}
}
add_filter( 'default_checkout_billing_state', 'change_default_checkout_state' );
function change_default_checkout_state() {
  return 'GJ'; // state code
}
add_filter( 'woocommerce_states', 'bbloomer_custom_woocommerce_states' );
function bbloomer_custom_woocommerce_states( $states ) {
$states['IN'] = array(
'GJ' => 'Gujarat',
);
return $states;
}
function wpdesk_fcf_validate_number( $field_label, $value ) {
	if ( ! ( ( is_numeric( $value ) ? intval( $value ) == $value : false ) ) ) {
		wc_add_notice( sprintf( '%s is not a valid number.', '<strong>' . $field_label . '</strong>' ), 'error' );
	}
}
add_filter( 'flexible_checkout_fields_custom_validation', 'wpdesk_fcf_custom_validation_number' );
function wpdesk_fcf_custom_validation_number( $custom_validation ) {
	$custom_validation['number'] = array(
		'label'     => 'Number',
		'callback'  => 'wpdesk_fcf_validate_number'
	);
	return $custom_validation;
}
add_filter( 'woocommerce_product_data_tabs', 'my_custom_tab' );
function my_custom_tab( $tabs ) {
  $tabs['custom_tab'] = array(
	'label'  => __( 'Guarantee/Warranty', 'textdomain' ),
	'target' => 'the_custom_panel',
	'class'  => array(),
  );
  return $tabs;
}
add_action( 'woocommerce_product_data_panels', 'custom_tab_panel' );
function custom_tab_panel() {
  ?>
  <div id="the_custom_panel" class="panel woocommerce_options_panel">
	<div class="options_group">
	  <?php
		$field = array(
		  'id' => 'guarantee',
		  'label' => __( 'G/W Tyre profile info.', 'textdomain' ),
		  'value' => get_post_meta( get_the_ID(), '_guarantee', true ),
		  'style' => 'width:330px;height:150px;',
		);
		woocommerce_wp_textarea_input( $field );
		$field1 = array(
		  'id' => 'guarantee_cart_invoice',
		  'label' => __( 'G/W Cart & Invoice info.', 'textdomain' ),
		  'value' => get_post_meta( get_the_ID(), '_guarantee_cart', true ),
		);
		woocommerce_wp_textarea_input( $field1 );
	  ?>
	</div>
  </div>
<?php
}
add_action( 'woocommerce_process_product_meta', 'save_custom_field' );
function save_custom_field( $post_id ) {
  $custom_field_value = isset( $_POST['guarantee'] ) ? $_POST['guarantee'] : '';
  $cart_invoice_text = isset( $_POST['guarantee_cart_invoice'] ) ? $_POST['guarantee_cart_invoice'] : '';
  $product = wc_get_product( $post_id );
  $product->update_meta_data( '_guarantee', $custom_field_value );
  $product->update_meta_data( '_guarantee_cart', $cart_invoice_text );
  $product->save();
}
add_filter( 'woocommerce_product_tabs', 'woo_new_product_tab' );
function woo_new_product_tab( $tabs ) {
	$tabs['warranty'] = array(
		'title'     => __( 'Guarantee/Warranty', 'woocommerce' ),
		'priority'  => 50,
		'callback'  => 'woo_warranty_product_tab_content'
	);
	return $tabs;
}
function woo_warranty_product_tab_content()
{
	global $product;
	$product_id = $product->get_ID();
	echo get_post_meta($product_id, '_guarantee', true );
}
/* ---------- Installer Module ---------------*/
add_action('wp_ajax_update_service_status', 'update_service_status');
add_action('wp_ajax_nopriv_update_service_status', 'update_service_status');
function update_service_status()
{
	echo $order_id = $_POST['order_id'];
	global $woocommerce, $wpdb;
	$services = "UPDATE th_cart_item_services set status = 'completed' WHERE order_id = '$order_id'";
	$row = $wpdb->get_results($services);
die();
}
add_action('wp_ajax_update_installer_status', 'update_installer_status');
add_action('wp_ajax_nopriv_update_installer_status', 'update_installer_status');
function update_installer_status()
{
	global $woocommerce, $wpdb;
	$tyre_installer_id = $_POST['tyre_installer_id'];
	$tyre_status = $_POST['tyre_status'];
	$barcode_text = $_POST['barcode_text'];
	$user_mobile = $_POST['user_mobile'];
	extract($_POST);
	
	date_default_timezone_set('Asia/Kolkata');
	$date = date('d-m-Y h:i:s a', time());
   $table_barcode_text = $wpdb->get_var( $wpdb->prepare( "SELECT barcode FROM th_cart_item_installer WHERE cart_item_installer_id='%s' LIMIT 1", $tyre_installer_id ) );
	if($barcode_text == $table_barcode_text)
	{
		$services = "UPDATE th_cart_item_installer set status = '$tyre_status' , completed_date = '$date' WHERE cart_item_installer_id = '$tyre_installer_id'";
		$row = $wpdb->query($services);
		
			$table = 'th_vehicle_details';

			$SQL="SELECT * FROM th_vehicle_details WHERE order_id='".$order_id."'";
			$vehicle=$wpdb->get_row($SQL);
			if($vehicle){
				$data = array('order_id' =>$order_id,'product_id' =>$product_id,
				 'user_id' => $user_id,
				 'make' => $make,
				 'model' =>$model,
				 'submodel' =>$sub_modal,
				 'car_number' => $car_number,
				 'odo_meter' => $odo_meter,
				 'insert_date' => date('Y-m-d'));
				$wpdb->update($table,$data,array('order_id' => $order_id));
				
				$my_id = $vehicle->id;
			}else{
				$data = array('order_id' =>$order_id,'product_id' =>$product_id,
				 'user_id' => $user_id,
				 'make' => $make,
				 'model' =>$model,
				 'submodel' =>$sub_modal,
				 'car_number' => $car_number,
				 'odo_meter' => $odo_meter,
				 'insert_date' => date('Y-m-d'));
				$wpdb->insert($table,$data);
				
				$my_id = $wpdb->insert_id;
			}
			
			$SQL="SELECT * FROM th_vehicle_tyre_information WHERE order_id='".$order_id."'";
			$tyreInfo=$wpdb->get_results($SQL);
			if($tyreInfo){
				foreach ($serial_number as $key => $value) {
					$data = array(
					 'vehicle_details_id' =>$my_id,
					 'order_id' =>$order_id,
					 'user_id' =>$user_id,
					 'serial_number' => $value,
					 'insert_date' => date('Y-m-d'));
					$wpdb->update('th_vehicle_tyre_information',$data,array('id' =>$tyre_info_id[$key]));
					
				}		
			}else{
				foreach ($serial_number as $key => $value) {
				$data = array(
				 'vehicle_details_id' =>$my_id,
				 'order_id' => $order_id,
				 'user_id' =>$user_id,
				 'serial_number' =>$value,
				 'insert_date' => date('Y-m-d'));
				$wpdb->insert('th_vehicle_tyre_information',$data);
				}
			}

		
		$installer_id = $wpdb->get_var( $wpdb->prepare( "SELECT installer_id FROM th_cart_item_installer WHERE cart_item_installer_id='%s' LIMIT 1", $tyre_installer_id ) );
		$product_id = $wpdb->get_var( $wpdb->prepare( "SELECT product_id FROM th_cart_item_installer WHERE cart_item_installer_id='%s' LIMIT 1", $tyre_installer_id ) );
		$product_variation = wc_get_product( $product_id );
		$variation_des = $product_variation->get_description();
		$variation_des = trim(preg_replace('/\s+/', ' ', $variation_des));
		$variation_des = substr($variation_des, 0, 25);
		$installer_name = $wpdb->get_var( $wpdb->prepare( "SELECT business_name FROM th_installer_data WHERE installer_data_id='%s' LIMIT 1", $installer_id ) );
		$service_complete_msg = "Tyrehub.com your service for ".$variation_des." is completed by ".$installer_name." Thank You Tyrehub Team";
		$ch1 = curl_init();
		$reset_pass_msg = str_replace(' ', '%20', $reset_pass_msg);
		sms_send_to_customer($reset_pass_msg,$mobile_no);

		echo 'true';
	}
	else
	{
		echo 'false';
	}
	die();
}

add_action('wp_ajax_only_services_update_installer_status', 'only_services_update_installer_status');
add_action('wp_ajax_nopriv_only_services_update_installer_status', 'only_services_update_installer_status');
function only_services_update_installer_status()
{
	global $woocommerce, $wpdb;
	$tyre_installer_id = $_POST['tyre_installer_id'];
	$tyre_status = $_POST['tyre_status'];
	$barcode_text = $_POST['barcode_text'];
	$user_mobile = $_POST['user_mobile'];
	extract($_POST);
	date_default_timezone_set('Asia/Kolkata');
	$date = date('d-m-Y h:i:s a', time());
   $table_barcode_text = $wpdb->get_var( $wpdb->prepare( "SELECT barcode FROM th_cart_item_installer WHERE cart_item_installer_id='%s' LIMIT 1", $tyre_installer_id ) );
	if($barcode_text == $table_barcode_text)
	{
		$services = "UPDATE th_cart_item_installer set status = '$tyre_status' , completed_date = '$date' WHERE cart_item_installer_id = '$tyre_installer_id'";
		$row = $wpdb->get_results($services);
		
			$table = 'th_vehicle_details';


		echo 'true';
		$installer_id = $wpdb->get_var( $wpdb->prepare( "SELECT installer_id FROM th_cart_item_installer WHERE cart_item_installer_id='%s' LIMIT 1", $tyre_installer_id ) );
		$product_id = $wpdb->get_var( $wpdb->prepare( "SELECT product_id FROM th_cart_item_installer WHERE cart_item_installer_id='%s' LIMIT 1", $tyre_installer_id ) );
		$product_variation = wc_get_product( $product_id );
		$variation_des = $product_variation->get_description();
		$variation_des = trim(preg_replace('/\s+/', ' ', $variation_des));
		$variation_des = substr($variation_des, 0, 25);
		$installer_name = $wpdb->get_var( $wpdb->prepare( "SELECT business_name FROM th_installer_data WHERE installer_data_id='%s' LIMIT 1", $installer_id ) );
		$service_complete_msg = "Tyrehub.com your service for ".$variation_des." is completed by ".$installer_name." Thank You Tyrehub Team";
		$reset_pass_msg = str_replace(' ', '%20', $reset_pass_msg);
		sms_send_to_customer($reset_pass_msg,$mobile_no);

	}
	else
	{
		echo 'false';
	}
	die();
}

add_action('wp_ajax_update_voucher_status', 'update_voucher_status');
add_action('wp_ajax_nopriv_update_voucher_status', 'update_voucher_status');
function update_voucher_status()
{
	global $woocommerce, $wpdb;
	extract($_POST);
	$voucher_id = $_POST['voucher_id'];
	$tyre_status = 'completed';
	$barcode_text = $_POST['barcode_text'];
	date_default_timezone_set('Asia/Kolkata');
	$date = date('d-m-Y h:i:s a', time());
   $table_barcode_text = $wpdb->get_var( $wpdb->prepare( "SELECT barcode FROM th_cart_item_service_voucher WHERE service_voucher_id='%s' LIMIT 1", $voucher_id ) );
	if($barcode_text == $table_barcode_text)
	{
		$services = "UPDATE th_cart_item_service_voucher set status = '$tyre_status' , completed_date = '$date' WHERE service_voucher_id = '$voucher_id'";
		$row = $wpdb->get_results($services);

				$table = 'th_vehicle_details';

				$SQL="SELECT * FROM th_vehicle_details WHERE order_id='".$order_id."'";
				$vehicle=$wpdb->get_row($SQL);
				if($vehicle){
					$data = array('order_id' =>$order_id,'product_id' =>$product_id,
					 'user_id' => $user_id,
					 'make' => $make,
					 'model' =>$model,
					 'submodel' =>$sub_modal,
					 'car_number' => $car_number,
					 'odo_meter' => $odo_meter,
					 'franchise_id' => $franchise_id,
					 'order_type' =>1,
					 'insert_date' => date('Y-m-d'));
					$wpdb->update($table,$data,array('order_id' => $order_id,'product_id' =>$product_id));
					$my_id = $vehicle->id;
				}else{
					$data = array('order_id' =>$order_id,'product_id' =>$product_id,
					 'user_id' => $user_id,
					 'make' => $make,
					 'model' =>$model,
					 'submodel' =>$sub_modal,
					 'car_number' => $car_number,
					 'odo_meter' => $odo_meter,
					 'franchise_id' => $franchise_id,
					 'order_type' =>1,
					 'insert_date' => date('Y-m-d'));
					$wpdb->insert($table,$data);
					$my_id = $wpdb->insert_id;
				}
			
		echo 1;
	}
	else
	{
		echo 0;
	}
	die();
}
add_action('wp_ajax_filter_order_for_installer_services', 'filter_order_for_installer_services');
add_action('wp_ajax_nopriv_filter_order_for_installer_services', 'filter_order_for_installer_services');
function filter_order_for_installer_services()
{
	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];
	global $woocommerce, $wpdb;
		$current_user = wp_get_current_user();
		$mobile_no = $current_user->user_login;
		$role = $current_user->roles[0];
		$current_inst_id = $wpdb->get_var( $wpdb->prepare( "SELECT installer_data_id FROM th_installer_data WHERE contact_no ='%s' LIMIT 1", $mobile_no ) );
		$order_arr = [];
		if($role == 'Installer'){
			$installer = "SELECT * FROM th_cart_item_installer WHERE installer_id = '$current_inst_id'";
		}
		elseif($role == 'administrator'){
			$installer = "SELECT * FROM th_cart_item_installer";
		}
		$row = $wpdb->get_results($installer);
		if(!empty($row))
		{
			foreach ($row as $key => $installer)
			{
				if($installer->order_id != 0){
					$order_arr[] = $installer->order_id;
				}
			}
		}
		$order_arr = array_unique($order_arr);
	 $order_query = array(
			'post__in' => $order_arr,
			'post_type' => 'shop_order',
			'numberposts'   => -1,
			'posts_per_page' => -1,
			'post_status' => 'any',
			'date_query' => array(
								  array(
										'after'     => $start_date,
										'before'    => $end_date,
										),
								 'column' => 'post_date',
									'relation' => 'AND',
								),
		);
	 $loop = new WP_Query($order_query);
	   // var_dump($loop);
	 if($loop->have_posts()){
		while ($loop->have_posts())
		{
			$loop->the_post();
			$order_id = $loop->post->ID;
			$order = wc_get_order($order_id);
			$order_data = $order->get_data();
			$order_items = $order->get_items();
			$order_date = $order->order_date;
			$order_status = $order->get_status();
			$order_status_name = esc_html( wc_get_order_status_name( $order->get_status() ) );
			// customer
			$user = $order->get_user();
			$user_id = $user->ID;
			$user_login = $user->user_login;
			$first_name = $order_data['billing']['first_name'];
				$last_name = $order_data['billing']['last_name'];
			$mobile_no = $order_data['billing']['phone'];
			 foreach ($order_items as $item_id => $item_data)
			{
				if($item_data['variation_id'] != ''){
					$order_prd_id = $item_data['variation_id'];
				}
				else{
					$order_prd_id = $item_data['product_id'];
				}
				$product_variation = wc_get_product( $order_prd_id );
				$variation_des = $product_variation->get_description();
				$parent_id = $product_variation->get_parent_id();
				$image = wp_get_attachment_image_src( get_post_thumbnail_id( $parent_id ), 'single-post-thumbnail' );
				if($role == 'Installer'){
					$service_data = "SELECT *
									FROM th_cart_item_installer
									WHERE product_id = '$order_prd_id' and order_id = '$order_id' and status = 'completed'";
				}
				elseif($role == 'administrator'){
					$service_data = "SELECT *
									FROM th_cart_item_installer
									WHERE product_id = '$order_prd_id' and order_id = '$order_id' and status = 'completed'";
				}
				$row = $wpdb->get_results($service_data);
				$tyre_status = '';
				if(!empty($row))
				{
					foreach ($row as $key => $data)
					{
						$destination = $data->destination;
						$item_installer = $data->cart_item_installer_id;
						$tyre_status = $data->status;
						$completed_date = $data->completed_date;
						$barcode_text = $data->barcode;
					}
				}
				if($tyre_status == 'completed'){
				?>
				<div class="single-service service-<?php echo $item_installer; ?>">
					<div class="inner">
						<div class="first-row">
							<div class="order-id"><strong>Order #<?php echo $order_id; ?></strong></div>
							<div class="date"><i class="fa fa-calendar"></i>
								<?php
								date_default_timezone_set("Asia/Kolkata");
								$order_date;
								echo $newDate = date("d-m-Y H:i a", strtotime($order_date));
								?>
							</div>
						</div>
						<div class="image-block"><img src="<?php  if($image[0] != ''){ echo $image[0]; }else{ echo get_site_url().'/wp-content/themes/demo/images/no_img1.png'; } ?>" data-id="<?php echo $loop->post->ID; ?>">
						</div>
						<div class="data-block">
							<div class="tyre-name"><strong><?php echo $variation_des; ?> (<?php echo $prd_qty; ?>Tyre)</strong></div>
							<div class="customer-name"><i class="fa fa-user"></i><?php echo $first_name.' '.$last_name; ?></div>
							<div class="mobile-no">
								<i class="fa fa-mobile" aria-hidden="true"></i>
								<?php echo $mobile_no; ?>
								<i class="fa fa-phone"></i>
								<!-- <i class="fa fa-comments-o"></i> -->
							</div>
							<div class="service-details">
								<strong>Services:</strong>
							<ul class="service-list">
							<?php
								$services = "SELECT *
								FROM th_cart_item_services
								WHERE product_id = '$order_prd_id' and order_id = '$order_id'";
								$row = $wpdb->get_results($services);
								foreach ($row as $key => $service)
								{
									$service_id = $service->cart_item_services_id;
									$service_name = $service->service_name;
									?>
									<li>
									<?php
									if($service_name == 'Tyre Fitment')
									{
										echo '<img class="service-img" src="'.get_site_url().'/wp-content/themes/demo/images/tyre_fitting.png"></img>';
										echo '<div>Fitment</div>';
									}
									if($service_name == 'Wheel Balancing')
									{
										echo '<img class="service-img" src="'.get_site_url().'/wp-content/themes/demo/images/tyre_balancing.png"></img>';
										echo '<div>Balancing</div>';
									}
									if($service_name == 'Wheel alignment')
									{
										echo '<img class="service-img" src="'.get_site_url().'/wp-content/themes/demo/images/wheel-alignment.png"></img>';
										echo '<div>Alignment</div>';
									}
									?>
									<?php
									echo '</li>';
								}
							?>
							</ul>
					</div>
					<div style="float: right;font-size: 16px;color: #008327;background: #eee; margin: 5px; padding: 5px;color: green;">Completed Date: <?php echo $completed_date; ?></div>
					<?php
					if($role == 'administrator'){?>
						<button type="button" class="btn-info btn-lg" data-toggle="modal" data-target="#service_modal_<?php echo $item_installer; ?>">Change Status</button>
					<div class="modal fade admin-change-status" id="service_modal_<?php echo $item_installer; ?>" role="dialog" data-service-id="<?php echo $item_installer; ?>" data-order-id="<?php echo $order_id; ?>">
						<div class="modal-dialog modal-sm">
						  <!-- Modal content-->
						  <div class="modal-content" style="text-align: center;">
							<div class="modal-body">
							  <p>Sure you want to change service status from completed to pending?</p>
							  <p><?php echo $barcode_text; ?></p>
								<input type="text" name="" class="service-barcode" placeholder="Enter barcode">
							</div>
							<div class="message"></div>
							<div class="modal-footer" style="text-align: center;">
							  <button class="admin update-service-status btn btn-invert" style="min-width: 100px; padding: 10px;"><span>Update</span></button>
							  <button type="button" class="btn btn-invert" data-dismiss="modal" style="min-width: 100px; padding: 10px;"><span>Cancle</span></button>
							</div>
						  </div>
						</div>
					</div>
				<?php } ?>
					</div>
					</div>
				</div>
				<?php
			}
			}
		}
	}else{
		echo 'No order Found!';
	}
	die();
}
add_action('wp_ajax_update_order_status', 'update_order_status');
add_action('wp_ajax_nopriv_update_order_status', 'update_order_status');
function update_order_status()
{
	$order_id = $_POST['order_id'];
	global $woocommerce, $wpdb;
	$flag = 0;
	$tyre_status = $wpdb->get_results("SELECT * FROM th_cart_item_installer WHERE order_id='$order_id'");
	foreach ($tyre_status as $key => $tyre_installer)
	{
		$id=$tyre_installer->cart_item_installer_id;
		$installer_id=$tyre_installer->installer_id;
		$status = $tyre_installer->status;
		if($status == 'pending' || $status == ''){
			$flag = 1;
		}
	}
	$voucher_status = $wpdb->get_results("SELECT * FROM th_cart_item_service_voucher WHERE order_id='$order_id'");
	foreach ($voucher_status as $key => $data)
	{
		$id=$tyre_installer->cart_item_installer_id;
		$installer_id=$data->installer_id;
		$status = $data->status;
		if($status == 'pending' || $status == ''){
			$flag = 1;
		}
	}
	if($flag == 0)
	{
		$installer = $wpdb->get_row("SELECT * FROM th_installer_data WHERE installer_data_id='$installer_id' AND is_franchise='yes'");
		
		$order = new WC_Order($order_id);
		$order->update_status( 'completed' );
		if($installer){
			 order_status_change($order_id,'completed');
		}
	}
	echo site_url().'/my-account/service-request/';
}
add_action('wp_ajax_update_order_status_for_voucher', 'update_order_status_for_voucher');
add_action('wp_ajax_nopriv_update_order_status_for_voucher', 'update_order_status_for_voucher');
function update_order_status_for_voucher()
{
	echo $order_id = $_POST['order_id'];
	global $woocommerce, $wpdb;
	$order = new WC_Order($order_id);
	$order->update_status( 'completed' );
}
add_action('wp_ajax_generate_wpo_wcpdf1', 'generate_wpo_wcpdf1');
add_action('wp_ajax_nopriv_generate_wpo_wcpdf1', 'generate_wpo_wcpdf1');
function generate_wpo_wcpdf1(){
 $document_type = sanitize_text_field( $_GET['document_type'] );
 $order_ids = (array) array_map( 'absint', explode( 'x', $_GET['order_ids'] ) );
   try {
			$document = wcpdf_get_document( $document_type, $order_ids, true );
			if ( $document ) {
				$output_format = WPO_WCPDF()->settings->get_output_format( $document_type );
				switch ( $output_format ) {
					case 'html':
						add_filter( 'wpo_wcpdf_use_path', '__return_false' );
						$document->output_html();
						break;
					case 'pdf':
					default:
						if ( has_action( 'wpo_wcpdf_created_manually' ) ) {
							do_action( 'wpo_wcpdf_created_manually', $document->get_pdf(), $document->get_filename() );
						}
						//$output_mode = WPO_WCPDF()->settings->get_output_mode( $document_type );
						//$document->output_pdf1('');
						$output_mode = WPO_WCPDF()->settings->get_output_mode($document_type);
						$document->output_pdf1( $output_mode );
						break;
				}
			} else {
				wp_die( sprintf( __( "Document of type '%s' for the selected order(s) could not be generated", 'woocommerce-pdf-invoices-packing-slips' ), $document_type ) );
			}
		} catch ( \Dompdf\Exception $e ) {
			$message = 'DOMPDF Exception: '.$e->getMessage();
			wcpdf_log_error( $message, 'critical', $e );
			wcpdf_output_error( $message, 'critical', $e );
		} catch ( \Exception $e ) {
			$message = 'Exception: '.$e->getMessage();
			wcpdf_log_error( $message, 'critical', $e );
			wcpdf_output_error( $message, 'critical', $e );
		} catch ( \Error $e ) {
			$message = 'Fatal error: '.$e->getMessage();
			wcpdf_log_error( $message, 'critical', $e );
			wcpdf_output_error( $message, 'critical', $e );
		}
		exit;
}
add_filter( 'woocommerce_admin_order_actions', 'add_custom_order_status_actions_button', 100, 3 );
function add_custom_order_status_actions_button( $actions, $order ) {
	// Display the button for all orders that have a 'processing' status
	 $order_id = method_exists( $order, 'get_id' ) ? $order->get_id() : $order->id;
	global $wpdb, $woocommerce;
	$service_data = "SELECT *
					FROM th_cart_item_installer
					WHERE order_id = '$order_id'";
	$row = $wpdb->get_results($service_data);
	$destination_status = 0;
	if(!empty($row))
	{
		foreach ($row as $key => $data)
		{
			$destination = $data->destination;
			if($destination == 1){
				$destination_status = 1;
			}
			$item_installer = $data->cart_item_installer_id;
		}
	}
	$service_voucher = "SELECT *
					FROM th_cart_item_service_voucher
					WHERE order_id = '$order_id'";
	$rowa = $wpdb->get_results($service_voucher);
	$voucher_status = 0;
	if(!empty($rowa))
	{
		$voucher_status = 1;
	}
	$promotion_voucher = "SELECT * FROM th_promotion_voucher_info";
	$rowp = $wpdb->get_results($promotion_voucher);
	$order_id_arr = [];
	if(!empty($rowp))
	{
		foreach ($rowp as $key => $data)
		{
		   $order_ids = unserialize($data->order_ids);
		   if(in_array($order_id, $order_ids)){
				$voucher_type = 'promotion';
			}
		}
	}
		// Get Order ID (compatibility all WC versions)
		// Set the action button
		if($destination_status == 1 || $voucher_status == 1)
		{
			if($voucher_type == 'promotion'){
				$url = wp_nonce_url( admin_url( 'admin-ajax.php?action=generate_wpo_wcpdf2&document_type=invoice&order_ids='.$order_id.'&my-account'), 'generate_wpo_wcpdf2' );
			}else{
				$url = wp_nonce_url( admin_url( 'admin-ajax.php?action=generate_wpo_wcpdf1&document_type=invoice&order_ids='.$order_id.'&my-account'), 'generate_wpo_wcpdf1' );
			}
			$actions['parcial'] = array(
				'url'       => $url,
				'name'      => __( 'Voucher', 'woocommerce' ),
				'target' => '_blank',
			);
		?>
			<a href="<?php echo $url; ?>" target="_blank" name="Voucher" data-tip="Voucher" class="view parcial button tips wpo_wcpdf exists invoice"></a>
		<?php
		}
	return $actions;
}
// Set Here the WooCommerce icon for your action button
add_action( 'admin_head', 'add_custom_order_status_actions_button_css' );
function add_custom_order_status_actions_button_css() {
	echo '<style>.view.parcial::after { font-family: woocommerce; content: "\f497" !important; position: unset; left: 0px!important; }</style>';
}
add_action('wp_ajax_admin_service_status_pending', 'admin_service_status_pending');
add_action('wp_ajax_nopriv_admin_service_status_pending', 'admin_service_status_pending');
function admin_service_status_pending()
{
	global $wpdb, $woocommerce;
	$service_id = $_POST['service_id'];
	$barcode_text = $_POST['barcode_text'];
	$order_id = $_POST['order_id'];
	$table_barcode_text = $wpdb->get_var( $wpdb->prepare( "SELECT barcode FROM th_cart_item_installer WHERE cart_item_installer_id='%s' LIMIT 1", $service_id ) );
	if($barcode_text == $table_barcode_text)
	{
		$services = "UPDATE th_cart_item_installer set status = 'pending' , completed_date = '' WHERE cart_item_installer_id = '$service_id'";
		$row = $wpdb->get_results($services);
		$order = new WC_Order($order_id);
		$order->update_status( 'processing' );
		echo 'yes';
	}
	else{
		echo 'no';
	}
	die();
}
// quantity dropdown in shop page
// this functionality add to woocommerce/loop/price.php
function custom_quantity_field_archive() {
	$product = wc_get_product( get_the_ID() );
	if ( ! $product->is_sold_individually() && 'variable' != $product->product_type && $product->is_purchasable() )
	{
		$min = 1;
		  $max = 5;
		  $step = 1;
		  $options = '';
		  for ( $count = $min; $count <= $max; $count = $count+$step ) {
			$options .= '<option value="' . $count . '">' . $count . '</option>';
		  }
	?>
			<div class="quantity"><strong>Qty</strong><select name="quantity" class="product-qty"><?php echo $options; ?></select></div>
	<?php
	}
}
//add_action( 'woocommerce_after_shop_loop_item', 'custom_quantity_field_archive', 0, 9 );
function custom_add_to_cart_quantity_handler() {
	wc_enqueue_js( '
		jQuery( ".post-type-archive-product" ).on( "change", ".quantity .product-qty", function() {
			var add_to_cart_button = jQuery( this ).parents( ".product" ).find( ".add_to_cart_button" );
			// For AJAX add-to-cart actions
			add_to_cart_button.data( "quantity", jQuery( this ).val() );
			// For non-AJAX add-to-cart actions
			//add_to_cart_button.attr( "href", "?add-to-cart=" + add_to_cart_button.attr( "data-product_id" ) + "&variation_id="+add_to_cart_button.attr( "data-product_id" )+"&quantity=" + jQuery( this ).val() );

			//add_to_cart_button.attr( "data-product_id",add_to_cart_button.attr("data-product_id));
		});
	' );
}
add_action( 'init', 'custom_add_to_cart_quantity_handler' );
/*function namespace_force_individual_cart_items( $cart_item_data, $product_id ) {
  $unique_cart_item_key = md5( microtime() . rand() );
  $cart_item_data['unique_key'] = $unique_cart_item_key;
  return $cart_item_data;
}
add_filter( 'woocommerce_add_cart_item_data', 'namespace_force_individual_cart_items', 10, 2 );*/
function is_product_in_same_cat($valid, $product_id, $quantity) { 
	global $woocommerce;
	//if($woocommerce->cart->cart_contents_count == 0){ return true;}
	 $product_id = $_GET['variation_id'];
	if($woocommerce->cart->cart_contents_count > 0){
		$valid = true;
		foreach($woocommerce->cart->get_cart() as $key => $val )
		{
			$_product = $val['data'];
			if($product_id == $_product->get_id() )
			{
				$qty = $val['quantity'];
				$total_qty = $quantity + $qty;
				//$total_qty = $quantity;
				$woocommerce->cart->set_quantity($key,$total_qty); // Change quantity
				//if($total_qty > 5)
				//{
					$url = get_site_url().'/online-tyre-services-partner/?product_id='.$product_id.'&cart_item_id='.$key.'&total_qty='.$total_qty;
					 wp_redirect($url);
					 exit;
				//}
			$valid = true;
			}else{
				
				$valid = false;
			}
		}
		/*echo '<pre>';
		print_r($woocommerce->cart->get_cart());
		die;*/
	}
	return $valid;
}
add_filter('woocommerce_add_to_cart_validation', 'is_product_in_same_cat',11,3);
add_action('wp_ajax_save_current_pincode', 'save_current_pincode');
add_action('wp_ajax_nopriv_save_current_pincode', 'save_current_pincode');
function save_current_pincode()
{
	global $woocommerce , $wpdb;
	$pincode = $_POST['pincode'];
	$fullname = $_POST['fullname'];
	$mobile = $_POST['mobile'];
	/*Rathod Savaji 10-05-2019 : 4:50*/
	$wpdb->insert('th_visitor_pincode_track', array(
		'fullname' => $fullname,
		'pincode' => $pincode,
		'mobile' => $mobile, // ... and so on
	));
	$address = $pincode;
	$url ='https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($pincode) . '&sensor=true&key='.GOOGLE_API_KEY;
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, $url );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	$response = curl_exec($ch);
   // var_dump($response);
	$coordinates = json_decode($response);
   $lat = $coordinates->results[0]->geometry->location->lat;
   $lng = $coordinates->results[0]->geometry->location->lng;
	$url = "https://maps.googleapis.com/maps/api/distancematrix/json?key=".GOOGLE_API_KEY."&origins=".$lat.",".$lng."&destinations=23.0314594,72.56410770&mode=driving&language=pl-PL";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			$response = curl_exec($ch);
			curl_close($ch);
			$response_a = json_decode($response, true);
			$dist = $response_a['rows'][0]['elements'][0]['distance']['value'];
			if($dist != "" && $dist != null)
			{
				if($dist > 20500){
					$url1 = "https://maps.googleapis.com/maps/api/distancematrix/json?key=".GOOGLE_API_KEY."&origins=".$lat.",".$lng."&destinations=23.2283124,72.636920&mode=driving&language=pl-PL";
					$ch1 = curl_init();
					curl_setopt($ch1, CURLOPT_URL, $url1);
					curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch1, CURLOPT_PROXYPORT, 3128);
					curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, 0);
					curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, 0);
					$response1 = curl_exec($ch1);
					curl_close($ch1);
					$response_a1 = json_decode($response1, true);
					$dist_gandhinagar = intval($response_a1['rows'][0]['elements'][0]['distance']['value']);
						if($dist_gandhinagar > 10500)
						{
							 echo "0";
						}else{
							$_SESSION['current_pincode'] = $pincode;
							$url ='https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($pincode) . '&sensor=true&key='.GOOGLE_API_KEY.'&libraries';
							$ch = curl_init();
							curl_setopt( $ch, CURLOPT_URL, $url );
							curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
							$response = curl_exec( $ch );
							$coordinates = json_decode($response);
							echo $add = $coordinates->results[0]->formatted_address;
						}
				}else{
					$_SESSION['current_pincode'] = $pincode;
					 $url ='https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($pincode) . '&sensor=true&key='.GOOGLE_API_KEY.'&libraries';
							$ch = curl_init();
							curl_setopt( $ch, CURLOPT_URL, $url );
							curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
							$response = curl_exec( $ch );
							$coordinates = json_decode($response);
							echo $add = $coordinates->results[0]->formatted_address;
				}
			}
			else{
				echo "0";
			}
		die();
}
add_action( 'woocommerce_after_shop_loop_item', 'bbloomer_custom_action1232', 15 );
function bbloomer_custom_action1232() {
echo '<div class="border"></div>';
}
add_action('wp_ajax_generate_wpo_wcpdf2', 'generate_wpo_wcpdf2');
add_action('wp_ajax_nopriv_generate_wpo_wcpdf2', 'generate_wpo_wcpdf2');
function generate_wpo_wcpdf2(){
 $document_type = sanitize_text_field( $_GET['document_type'] );
 $order_ids = (array) array_map( 'absint', explode( 'x', $_GET['order_ids'] ) );
   try {
			$document = wcpdf_get_document( $document_type, $order_ids, true );
			if ( $document ) {
				$output_format = WPO_WCPDF()->settings->get_output_format( $document_type );
				switch ( $output_format ) {
					case 'html':
						add_filter( 'wpo_wcpdf_use_path', '__return_false' );
						$document->output_html();
						break;
					case 'pdf':
					default:
						if ( has_action( 'wpo_wcpdf_created_manually' ) ) {
							do_action( 'wpo_wcpdf_created_manually', $document->get_pdf(), $document->get_filename() );
						}
						//$output_mode = WPO_WCPDF()->settings->get_output_mode( $document_type );
						$document->output_pdf2('');
						break;
				}
			} else {
				wp_die( sprintf( __( "Document of type '%s' for the selected order(s) could not be generated", 'woocommerce-pdf-invoices-packing-slips' ), $document_type ) );
			}
		} catch ( \Dompdf\Exception $e ) {
			$message = 'DOMPDF Exception: '.$e->getMessage();
			wcpdf_log_error( $message, 'critical', $e );
			wcpdf_output_error( $message, 'critical', $e );
		} catch ( \Exception $e ) {
			$message = 'Exception: '.$e->getMessage();
			wcpdf_log_error( $message, 'critical', $e );
			wcpdf_output_error( $message, 'critical', $e );
		} catch ( \Error $e ) {
			$message = 'Fatal error: '.$e->getMessage();
			wcpdf_log_error( $message, 'critical', $e );
			wcpdf_output_error( $message, 'critical', $e );
		}
		exit;
}
add_action('wp_ajax_car_tyre_page_result', 'car_tyre_page_result');
add_action('wp_ajax_nopriv_car_tyre_page_result', 'car_tyre_page_result');
function car_tyre_page_result()
{
	$cat = $_POST['cat'];
	$args = array(
			'post_type' => 'product_variation',
			'posts_per_page' => -1,
			'paged' => get_query_var( 'paged' ),
			'meta_query'=> array(
						'relation' => 'AND',
						array(
							'key' => 'attribute_pa_brand',
							'value' => $cat,
							'compare' => 'IN',
						),
						array(
							'key'       => 'tyrehub_visible',
							'value'     => array('yes','contact-us'),
							'compare'   => 'IN',
						)
			 ),
			);
	$products = new WP_Query( $args );
				$num = $products->found_posts;
				wc_set_loop_prop('total', $num);
				wc_set_loop_prop('total_pages', 1);
				if ( $products->have_posts() )
				{
					woocommerce_product_loop_start();
					if ( wc_get_loop_prop( 'total' ) )
					{
						while ( $products->have_posts() ) : $products->the_post();
						do_action( 'woocommerce_shop_loop' );
						wc_get_template_part( 'content', 'product' );
						endwhile;
					}
					wp_reset_postdata();
					woocommerce_product_loop_end();
					do_action( 'woocommerce_after_shop_loop' );
				}
				else {
					do_action( 'woocommerce_no_products_found' );
				}
	die();
}
/**
 * Savaji Rathod CSS and JS Add
 */
function services_vehicle_scripts() {
	wp_enqueue_style( 'bootstrap-dialog', get_template_directory_uri() . '/assest/css/bootstrap-dialog.css' );
	wp_enqueue_script( 'script-name', get_template_directory_uri() . '/assest/js/bootstrap-dialog.js', array(), rand(111,999), true );
	wp_enqueue_style( 'font-awesome','//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css',array(), rand(111,999), true );
}
add_action( 'wp_enqueue_scripts', 'services_vehicle_scripts' );
// ADDING 2 NEW COLUMNS WITH THEIR TITLES (keeping "Total" and "Actions" columns at the end)
add_filter( 'manage_edit-shop_order_columns', 'custom_shop_order_column', 20 );
function custom_shop_order_column($columns)
{
	$reordered_columns = array();
	// Inserting columns to a specific location
	foreach( $columns as $key => $column){
		$reordered_columns[$key] = $column;
		if($key =='order_status'){
			// Inserting after "Status" column
			$reordered_columns['installer_name'] = __( 'Installer','theme_domain');
			$reordered_columns['supplier_name'] = __( 'Supplier','theme_domain');
		}
	}
	return $reordered_columns;
}
// Adding custom fields meta data for each new column (example)
add_action( 'manage_shop_order_posts_custom_column' , 'custom_orders_list_column_content', 20, 2 );
function custom_orders_list_column_content( $column, $post_id )
{ global $wpdb;
	switch ($column)
	{
		case 'installer_name' :
			// Get custom post meta data
			$SQL="SELECT idata.business_name FROM th_cart_item_installer cii
			LEFT JOIN th_installer_data as idata ON idata.installer_data_id=cii.installer_id
			WHERE cii.order_id='".$post_id."'";
			$business_name=$wpdb->get_var($SQL);
			if(empty($business_name)){
			   $SQL="SELECT idata.business_name FROM th_cart_item_service_voucher cii
			LEFT JOIN th_installer_data as idata ON idata.installer_data_id=cii.installer_id
			WHERE cii.order_id='".$post_id."'";
			$business_name=$wpdb->get_var($SQL);
			}
			if(!empty($business_name)){
				echo '<a href="'.site_url('/wp-admin/admin.php?page=order-installer-change&order_id='.$post_id).'" target="_blank">'.$business_name.'</a>';
			}else{
				global $woocommerce, $post;
				$order = new WC_Order($post_id);
			    $user_id = $order->get_user_id( );
			    $SQL="SELECT business_name FROM th_installer_data WHERE user_id='".$user_id."'";
				$business_name=$wpdb->get_var($SQL);
				echo '<a href="'.site_url('/wp-admin/admin.php?page=order-installer-change&order_id='.$post_id).'" target="_blank">'.$business_name.'</a>';
				//echo '<small>(<em>no value</em>)</small>';
			}
			break;
		case 'supplier_name' :
		// Get custom post meta data
			 $SQL="SELECT idata.business_name FROM th_suuplier_product_order cii
			LEFT JOIN th_supplier_data as idata ON idata.supplier_data_id=cii.supplier_id
			WHERE cii.order_id='".$post_id."'";
			$business_name=$wpdb->get_results($SQL);
			//echo "<pre>";
			//print_r($business_name);
			$business_name1=array();
			foreach ($business_name as $key => $value) {
				# code...
				$business_name1[]=$value->business_name;
			}
			$business_name1 = array_unique($business_name1);
			if($business_name1){
			echo implode(', ',$business_name1);
			}
		break;
	}
}
add_action( 'restrict_manage_posts', 'shop_order_user_role_filter' );
function shop_order_user_role_filter() {
	global $typenow, $wp_query;
	if ( in_array( $typenow, wc_get_order_types( 'order-meta-boxes' ) ) ) :
		$user_role  = '';
		// Get all user roles
		$user_roles = array();
		foreach ( get_editable_roles() as $key => $values ) :
			$user_roles[ $key ] = $values['name'];
		endforeach;
		// Set a selected user role
		if ( ! empty( $_GET['_user_role'] ) ) {
			$user_role  = sanitize_text_field( $_GET['_user_role'] );
		}
		// Display drop down
		?><select name='_user_role'>
			<option value=''><?php _e( 'Select a user role', 'woocommerce' ); ?></option>
			<?php
			foreach ( $user_roles as $key => $value ) :
				?><option <?php selected( $user_role, $key ); ?> value='<?php echo $key; ?>'><?php echo $value; ?></option>
			<?php endforeach; ?>
			<option  <?php if($user_role=='guest'){ echo 'selected';} ?> value='guest'><?php _e('Guest', 'woocommerce' ); ?></option>
			</select><?php
	endif;
}
add_filter( 'pre_get_posts', 'shop_order_user_role_posts_where' );
function shop_order_user_role_posts_where( $query ) {
	if ( ! $query->is_main_query() || ! isset( $_GET['_user_role'] ) ) {
		return;
	}
	if($_GET['_user_role']=='guest'){
	$ids=array(0);
	}else{
	 $ids    = get_users( array( 'role' => sanitize_text_field( $_GET['_user_role'] ), 'fields' => 'ID' ) );
	$ids    = array_map( 'absint', $ids );
	}
	$query->set( 'meta_query', array(
		array(
			'key' => '_customer_user',
			'compare' => 'IN',
			'value' => $ids,
		)
	) );
	if ( empty( $ids ) ) {
		$query->set( 'posts_per_page', 0 );
	}
}
add_filter( 'woocommerce_terms_is_checked_default', 'apply_default_check' );
function apply_default_check()
{
	return 1;
}
//gets the data from a URL
function get_short_url($url)  {
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch,CURLOPT_URL,'http://tinyurl.com/api-create.php?url='.urlencode($url));
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
/*	$long_url=$url;
 	$apiKey = GOOGLE_API_KEY;
 $data = array('longUrl' => $long_Url, 'key' => $apiKey);
 $jsonData = json_encode($data);
 $curlObj = curl_init();
 curl_setopt($curlObj, CURLOPT_URL, 'https://www.googleapis.com/urlshortener/v1/url');
 curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
 curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
 curl_setopt($curlObj, CURLOPT_HEADER, 0);
 curl_setopt($curlObj, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
 curl_setopt($curlObj, CURLOPT_POST, 1);
 curl_setopt($curlObj, CURLOPT_POSTFIELDS, $jsonData);
 $response = curl_exec($curlObj);
 // Change the response json string to object
 $json = json_decode($response);
 curl_close($curlObj);
 return $json->id;*/
}
function get_user_role() {
	global $current_user;
	$user_roles = $current_user->roles;
	$user_role = array_shift($user_roles);
	return $user_role;
}
add_filter('gettext', 'change_backend_product_regular_price', 100, 3 );
function change_backend_product_regular_price( $translated_text, $text, $domain ) {
	global $pagenow;
	if(is_admin() && 'Regular price (%s)' == $text && 'product' === get_post_type( $_GET['post'] )){
		$translated_text =  __( 'M.R.P. price (%s)', $domain );
	}
	return $translated_text;
}
function barcode_generate(){
	$num="";
	for ($i = 0; $i<8; $i++)
	{
		$num .= mt_rand(0,9);
	}
 return $num;
}
// check for empty-cart get param to clear the cart
add_action( 'init', 'woocommerce_clear_cart_url');
function woocommerce_clear_cart_url() {
	global $woocommerce, $wpdb;
	if ( isset( $_GET['empty-cart'] ) ) {
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item )
			{
				$cart_key = $cart_item_key;
				$delete_installer = $wpdb->query("DELETE from th_cart_item_installer WHERE cart_item_key = '".$cart_key."'");
				$delete_service = $wpdb->query("DELETE from th_cart_item_services WHERE cart_item_key = '".$cart_key."'");
			}
		WC()->cart->empty_cart();
		die;
	}
}
add_action('wp_ajax_installer_populate', 'installer_populate');
add_action('wp_ajax_nopriv_installer_populate', 'installer_populate');
function installer_populate()
{
	session_start();
	$vehicle_id = $_POST['vehicle_type'];
	$product_id = $_POST['product_id'];
	$product_variation = wc_get_product($product_id);
	$variation_data = $product_variation->get_data();
	$attr_vehicle_typre = $variation_data['attributes']['pa_vehicle-type'];
	$sas_sql_arr = '';
	$sfc_sql_arr = '';
	if(isset($_POST['current_lon']) && isset($_POST['current_lat']))
	{
		$select_lat = $_POST['current_lat'];
		$select_lng = $_POST['current_lon'];
	}else{
		$postal_code = $_POST['postal_code'];
		$url = "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($postal_code)."&sensor=false&key=".GOOGLE_API_KEY;
		$result_string = file_get_contents($url);
		$result = json_decode($result_string, true);
		$val = $result['results'][0]['geometry']['location'];
		$pincode = $result['results'][0]['address_components'][0]['long_name'];
		session_start();
		$_SESSION['current_pincode']=$pincode;
		$select_lat = $val['lat'];
		$select_lng = $val['lng'];
	}
	global $wpdb;
	if($vehicle_id != '')
	{
		$row = $wpdb->get_results("SELECT * FROM th_installer_data where visibility = 1");
	}
	elseif ($attr_vehicle_typre == 'car-tyre')
	{
		$row = $wpdb->get_results("SELECT * FROM th_installer_data where visibility = 1");
	}
	elseif($attr_vehicle_typre == 'two-wheeler' || $attr_vehicle_typre == 'three-wheeler')
	{
		$row = $wpdb->get_results("SELECT * FROM th_installer_data where user_id = 55 || user_id = 61");
	}
	else
	{   $SQL="SELECT  DISTINCT(installer_data_id),ins.*,insmt.meta_value as facilities,insmt1.meta_value as services FROM th_installer_data ins
			LEFT JOIN th_installer_meta as insmt ON (insmt.installer_id=ins.installer_data_id AND insmt.meta_name='facilities')
			LEFT JOIN th_installer_meta as insmt1 ON (insmt1.installer_id=ins.installer_data_id  AND insmt1.meta_name='additional_services')
			WHERE ins.visibility = 1 OR ins.visibility = 2";
		$row = $wpdb->get_results($SQL);
	}
	if($postal_code != '' || $select_lng != '')
	{
		 $installer_km = [];
		 $fc_sql = "SELECT * from th_installer_facilities where type = 'f'";
		 $fc_data = $wpdb->get_results($fc_sql);
		 $as_sql = "SELECT * from th_installer_facilities where type = 'as'";
		 $as_data = $wpdb->get_results($as_sql);
		$sas_sql_arr = '';
		$sfc_sql_arr = '';
	foreach ($row as $key=>$data)
	{
		$installer_id = $data->installer_data_id;
		$inst_postcode = $data->pincode;
		$inst_lat = $data->location_lattitude;
		$inst_lng = $data->location_longitude;
		if($inst_lat == '' || $inst_lng == '')
		{
			$url = "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($inst_postcode)."&sensor=false&key=".GOOGLE_API_KEY;
			$result_string = file_get_contents($url);
			$result = json_decode($result_string, true);
			$val = $result['results'][0]['geometry']['location'];
			$inst_lat = $val['lat'];
			$inst_lng = $val['lng'];
		}
			$url = "https://maps.googleapis.com/maps/api/distancematrix/json?key=".GOOGLE_API_KEY."&origins=".$select_lat.",".$select_lng."&destinations=".$inst_lat.",".$inst_lng."&mode=driving&language=pl-PL";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			$response = curl_exec($ch);
			curl_close($ch);
			$response_a = json_decode($response, true);
			$dist = $response_a['rows'][0]['elements'][0]['distance']['text'];
			$time = $response_a['rows'][0]['elements'][0]['duration']['text'];
			$dist = str_replace(' km', '', $dist);
			$dist = str_replace(',', '.', $dist);
			$installer_km[$installer_id]['dist'] = $dist;
			$installer_km[$installer_id]['business_name'] = $data->business_name;
			$installer_km[$installer_id]['contact_no'] = $data->contact_no;
			$installer_km[$installer_id]['address'] = $data->address;
			$installer_km[$installer_id]['city'] = $data->city;
			$installer_km[$installer_id]['state'] = $data->state;
			$installer_km[$installer_id]['pincode'] = $data->pincode;
			$installer_km[$installer_id]['available_days'] = $data->available_days;
			$installer_km[$installer_id]['pincode'] = $data->pincode;
						if($data->facilities){
						$sfc_sql_arr = unserialize($data->facilities);
						//var_dump($sfc_sql_arr);
						$facilities=array();
						foreach ($fc_data as $key1 => $fc_row)
						{
							$name = $fc_row->name;
							$icon = $fc_row->icon;
							$f_id = $fc_row->f_id;
							if(in_array($f_id, $sfc_sql_arr)){
							//echo '<li><i class="fas fa '.$icon.'"></i>'.$name.'</li>';
							$facilities[$key1]['icon']=$icon;
							$facilities[$key1]['faci_name']=$name;
							}
						}
						$installer_km[$installer_id]['facilities'] =$facilities;
					}
					if($data->services){
						$services=array();
						$sas_sql_arr = unserialize($data->services);
							foreach ($as_data as $key2 => $as_row)
							{
								$name = $as_row->name;
								$icon = $as_row->icon;
								$f_id = $as_row->f_id;
								if(in_array($f_id, $sas_sql_arr)){
								 $services[$key2]['icon']=$icon;
								 $services[$key2]['faci_name']=$name;
								}
							}
					  $installer_km[$installer_id]['services'] =$services;
					}
		}
	asort($installer_km);
global $wp_session;
$wp_session['382481']=$installer_km;
	die();
}
}
// Store the custom data to cart object
add_filter( 'woocommerce_add_cart_item_data', 'save_custom_product_data', 10, 2 );
function save_custom_product_data( $cart_item_data, $product_id ) {
	$bool = false;
	$data = array();
	if( isset($_SESSION['vehicle_type'] ) ) {
		$cart_item_data['custom_data']['vehicle_type'] = $_SESSION['vehicle_type'];
		$data['vehicle_type'] = $_SESSION['vehicle_type'];
		$bool = true;
	}
	if( $bool ) {
		// below statement make sure every add to cart action as unique line item
		$cart_item_data['custom_data']['unique_key'] = md5( microtime().rand() );
		WC()->session->set( 'custom_variations', $data);
	}
	return $cart_item_data;
}
// Remove default link around product entries
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
// Re-add links and check for affiliate link
function myprefix_woocommerce_template_loop_product_link_open() {
	$affiliate_link = get_post_meta( get_the_ID(), '_product_url', true );
	if ( $affiliate_link ) {
		echo '<a href="'. esc_url( $affiliate_link ) .'" class="woocommerce-LoopProduct-link" target="_blank">';
	} else {
		if($_SESSION['vehicle_type']!=''){
		   $vurl='&vehicle_type='.$_SESSION['vehicle_type'];
		}
		 global $woocommerce,$wpdb;
     $user_id = get_current_user_id();
     $SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
     $franchise=$wpdb->get_row($SQL);
	     if(empty( $franchise)){
	     	echo '<a href="'. get_the_permalink().'" class="woocommerce-LoopProduct-link">';
	     }
	}
}
add_action( 'woocommerce_before_shop_loop_item', 'myprefix_woocommerce_template_loop_product_link_open', 10 );
function getIndianCurrency($number)
{
	$decimal = round($number - ($no = floor($number)), 2) * 100;
	$hundred = null;
	$digits_length = strlen($no);
	$i = 0;
	$str = array();
	$words = array(0 => '', 1 => 'One', 2 => 'Two',
		3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
		7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
		10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
		13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
		16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
		19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
		40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
		70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');
	$digits = array('', 'Hundred','Thousand','Lakh', 'Crore');
	while( $i < $digits_length ) {
		$divider = ($i == 2) ? 10 : 100;
		$number = floor($no % $divider);
		$no = floor($no / $divider);
		$i += $divider == 10 ? 1 : 2;
		if ($number) {
			$plural = (($counter = count($str)) && $number > 9) ? 's' : null;
			$hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
			$str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
		} else $str[] = null;
	}
	$Rupees = implode('', array_reverse($str));
	$paise = ($decimal) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
	return ($Rupees ? $Rupees . 'Rupees Only ' : '') . $paise;
}
if(!isset($_GET['attribute_pa_width'])){
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
}
function sv_add_my_account_order_actions( $actions, $order ) {
	/*echo '<pre>';
	print_r($actions);
	echo '</pre>';*/
	global $wpdb;
	$order_id=$order->get_id();
	$status=$order->get_status();
	$statusArr=array('pending','failed','cancelled','refunded');
	$pdf_url1 = wp_nonce_url( admin_url( 'admin-ajax.php?action=generate_wpo_wcpdf1&document_type=invoice&order_ids='.$order_id. '&my-account'), 'generate_wpo_wcpdf1' );
	if(!in_array($status,$statusArr)){
		$SQL="SELECT * FROM th_cart_item_services WHERE order_id='".$order_id."'";
		$wpdb->get_results($SQL);
		$rowcount = $wpdb->num_rows;
		$rowcount1 = $wpdb->get_var("SELECT COUNT(*) FROM th_cart_item_services WHERE order_id='".$order_id."'");
		if($rowcount || $rowcount1){
		 $actions['voucher'] = array(
		'url'  => $pdf_url1,
		'name' => 'Download Voucher',
		);
		}
	}else{
		//unset($actions['invoice']);
	}
	return $actions;
}
add_filter( 'woocommerce_my_account_my_orders_actions', 'sv_add_my_account_order_actions', 10, 2 );
	// A callback function to add a custom field to our "presenters" taxonomy
	function pa_width_taxonomy_custom_fields($tag) {
	   // Check for existing taxonomy meta for the term you're editing
		global $wpdb;
		$SQLTWO="SELECT * FROM th_width_display WHERE w_cat_id='".$tag->term_id."' AND two_wheeler='yes'";
		$twoRowCount = $wpdb->get_var($SQLTWO);
		$SQLFOUR="SELECT COUNT(*) FROM th_width_display WHERE w_cat_id='".$tag->term_id."' AND four_wheeler='yes'";
		$fourRowCount = $wpdb->get_var($SQLFOUR);
		//$t_id = $tag->term_id; // Get the ID of the term you're editing
		///$term_meta = get_option( "taxonomy_term_$t_id" ); // Do the check
	?>
	<tr class="form-field">
		<th scope="row" valign="top">
			<label for="width_id"><?php _e('Display'); ?></label>
		</th>
		<td>
			<input type="checkbox" name="four_wheeler" id="four_wheeler" value="yes" <?php if($fourRowCount>0){ echo "checked";}?>>Car Wheeler
			<input type="checkbox" name="two_wheeler" id="two_wheeler" value="yes" <?php if($twoRowCount>0){ echo "checked";}?>>Two Wheeler
			<div style="margin-bottom: 30px;"></div>
		</td>
	</tr>
	<?php
	}
		// A callback function to save our extra taxonomy field(s)
	function save_taxonomy_custom_fields( $term_id ) {
		global $wpdb;
		$SQLTWO="SELECT COUNT(*) FROM th_width_display WHERE w_cat_id='".$term_id."'";
		$twoRowCount = $wpdb->get_var($SQLTWO);
		if($twoRowCount>0){
		   $wpdb->update('th_width_display',array('two_wheeler' =>$_POST['two_wheeler'],'four_wheeler' =>$_POST['four_wheeler']),
					array('w_cat_id' =>$term_id)
				);
		}else{
			  $wpdb->insert(
					'th_width_display',
					array(
						'two_wheeler' =>$_POST['two_wheeler'],
						'four_wheeler' =>$_POST['four_wheeler'],
						'w_cat_id' =>$term_id
					)
				);
		}
	}
	// Add the fields to the "presenters" taxonomy, using our callback function
	add_action( 'pa_width_add_form_fields', 'pa_width_taxonomy_custom_fields', 10, 2 );
	add_action( 'pa_width_edit_form_fields', 'pa_width_taxonomy_custom_fields', 10, 2 );
	// Save the changes made on the "presenters" taxonomy, using our callback function
	add_action( 'edited_pa_width', 'save_taxonomy_custom_fields', 10, 2 );
add_action( 'woocommerce_before_calculate_totals', 'add_custom_price123' );
function add_custom_price123( $cart_object ) {
	$custom_price = 100; // This will be your custome price
	foreach ( $cart_object->cart_contents as $key => $value ) {
		$regular_price=$value['data']->get_regular_price();
		$sale_price=$value['data']->get_sale_price();
		if($regular_price<$sale_price){
		   //$value['data']->set_regular_price($sale_price);
			//$value['data']->regular_price = $sale_price;
			$value['data']->set_price($sale_price);
			$value['data']->set_regular_price($sale_price);
		}
			global $wpdb;
			$user_id = get_current_user_id();
			 $SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
			$franchise=$wpdb->get_row($SQL);
		if($franchise){
			if($value['offline-purchase']=='yes'){
				//$value['variation']['services_price'];
				$product_array= array(get_option("balancing_alignment"), get_option("car_wash"));
    				if(in_array($value['product_id'],$product_array)){
				$value['data']->set_price($value['variation']['services_price']);
					}
			}
		}
		//$value['data']->price = $custom_price;
		// for WooCommerce version 3+ use:
		//$value['data']->set_price($custom_price);
	}
}
// Reset invoice counter each april 1st
//add_action( 'wpo_wcpdf_reset_invoice_number_yearly', 'wpo_wcpdf_reset_invoice_number_monthly', 10, 3 );
add_action( 'wpo_wcpdf_before_sequential_number_increment', 'webtual_wcpdf_reset_invoice_number_yearly', 10, 3 );
function webtual_wcpdf_reset_invoice_number_yearly( $number_store, $order_id, $date ) {
	$current_fiscal_year = wpo_wcpdf_get_fiscal_year( time() );
	$last_number_fiscal_year = wpo_wcpdf_get_fiscal_year( $number_store->get_last_date('U') );
	//error_log( $current_fiscal_year . " " . date('Y-m-d') );
	//error_log( $last_number_fiscal_year . " " . $number_store->get_last_date('Y-m-d') );
	// check if we need to reset
	//$number_store->set_next(1);
	if ( $current_fiscal_year != $last_number_fiscal_year ) {
		$number_store->set_next(1);
	}
   //die;
}
function wpo_wcpdf_get_fiscal_year( $timestamp ) {
	$fiscal_year_start_date = '04-01'; // m-d
	$year = date_i18n( 'Y',  $timestamp );
	$fiscal_year_start = strtotime("{$year}-{$fiscal_year_start_date}");  //2019-07-01
	if ($timestamp > $fiscal_year_start ) {
		//echo $fiscal_year = (int) $year - 1;
		return $fiscal_year = (int) $year - 1;
	} else {
	   return $fiscal_year = (int) $year;
	   //echo $fiscal_year = (int) $year;
	}
}
add_action( 'woocommerce_thankyou', 'custom_woocommerce_auto_complete_paid_order', 10, 1 );
function custom_woocommerce_auto_complete_paid_order( $order_id ) {
	global $woocommerce, $wpdb;
	$order = new WC_Order( $order_id );
	$order_status = $order->get_status();
	$customer_id = get_current_user_id();
	// Get the user object.
	$user = get_userdata($customer_id);
	// Get all the user roles as an array.
	$user_roles = $user->roles;
	if(isset($user_roles)){
				// Check if the role you're interested in, is present in the array.
	if ( in_array( 'btobpartner', $user_roles, true ) ) {
			// Do something.
			$SQL="SELECT * FROM th_business_partner_data WHERE user_id='".$customer_id."'";
			$business_row = $wpdb->get_row($SQL);
			$order_total = $order->get_total();
				$price=round(($order_total*$business_row->commission_percentage)/100);
				$discount_price=round(($order_total*$business_row->percentage)/100);
			 $data = array(
				'order_id'  =>$order_id,
				'user_id'  =>$customer_id,
				'percentage'  =>$business_row->percentage,
				'discount_price'  =>$discount_price,
				'commission_percentage'  =>$business_row->commission_percentage,
				'commission_price'  =>$price,
				'order_total'  =>$order_total,
				'is_paid'  =>0,
				'order_status'  =>$order_status,
				'update_date'      =>date( 'Y-m-d H:i:s'),
			);
			$wpdb->insert('th_business_commission',$data);
	}
	}

/*	$current_fiscal_year = wpo_wcpdf_get_fiscal_year( time() );
	$last_number_fiscal_year = wpo_wcpdf_get_fiscal_year(webtual_get_credit_notes_last_date('U') );
	if ( $current_fiscal_year != $last_number_fiscal_year ) {
			$highest_number = 0;
			$data = array(
				'order_id'  => 0,
				'date'      =>date( 'Y-m-d H:i:s'),
			);
			$data['calculated_number'] = $highest_number;
			// after this insert, AUTO_INCREMENT will be equal to $number
			$wpdb->insert('wp_wcpdf_credit_notes_number', $data );
	}
	if ('cancelled' == $order_status || 'refunded' == $order_status ) {
			$SQL="SELECT * FROM wp_wcpdf_credit_notes_number WHERE id = ( SELECT MAX(id) from wp_wcpdf_credit_notes_number)";
			$last_row = $wpdb->get_row($SQL);
			if($last_row->order_id!=$order_id){
			if (empty( $last_row) ) {
				$next = 1;
			} elseif (!empty($last_row->calculated_number) || $last_row->calculated_number==0) {
				$next = (int) $last_row->calculated_number + 1;
			} else {
				$next = (int) $last_row->id + 1;
			}
			$data = array(
				'order_id'  =>$order_id,
				'date'      =>date( 'Y-m-d H:i:s' ),
			);
			$data['calculated_number'] = $next;
			$wpdb->insert('wp_wcpdf_credit_notes_number', $data );
		}
		
	}*/
	
}
add_action('woocommerce_order_status_failed', 'webtual_credit_note_rest_and_increment');
//add_action( 'woocommerce_order_status_pending', 'the_dramatist_woocommerce_auto_delete_order' );
add_action('woocommerce_order_status_cancelled', 'webtual_credit_notes_rest_and_increment');
add_action('woocommerce_order_status_refunded', 'webtual_credit_notes_rest_and_increment');
function webtual_credit_notes_rest_and_increment( $order_id ) {
	global $woocommerce, $wpdb;
	$order = new WC_Order( $order_id );
	$order_status = $order->get_status();
	$current_fiscal_year = wpo_wcpdf_get_fiscal_year( time() );
	$last_number_fiscal_year = wpo_wcpdf_get_fiscal_year(webtual_get_credit_notes_last_date('U') );
	if ( $current_fiscal_year != $last_number_fiscal_year ) {
			$highest_number = 0;
			$data = array(
				'order_id'  => 0,
				'date'      =>date( 'Y-m-d H:i:s'),
			);
			$data['calculated_number'] = $highest_number;
			// after this insert, AUTO_INCREMENT will be equal to $number
			$wpdb->insert('wp_wcpdf_credit_notes_number', $data );
	}
	if ('cancelled' == $order_status || 'refunded' == $order_status ) {
			$SQL="SELECT * FROM wp_wcpdf_credit_notes_number WHERE id = ( SELECT MAX(id) from wp_wcpdf_credit_notes_number)";
			$last_row = $wpdb->get_row($SQL);
			if($last_row->order_id!=$order_id){
			if (empty( $last_row) ) {
				$next = 1;
			} elseif (!empty($last_row->calculated_number) || $last_row->calculated_number==0) {
				$next = (int) $last_row->calculated_number + 1;
			} else {
				$next = (int) $last_row->id + 1;
			}
			$data = array(
				'order_id'  =>$order_id,
				'date'      =>date( 'Y-m-d H:i:s' ),
			);
			$data['calculated_number'] = $next;
			$wpdb->insert('wp_wcpdf_credit_notes_number', $data );
			// store settings in order
			if (!empty($order_id)) {
				//WPO_WCPDF()->settings->get_output_format( $document_type )
				$common_settings = WPO_WCPDF()->settings->get_common_document_settings();
				$document_settings = get_option('wpo_wcpdf_documents_settings_credit-notes');
				$settings = (array) $document_settings + (array) $common_settings;
				update_post_meta($order_id, "_wcpdf_credit_notes_settings", $settings );
			}
			if ( ! add_post_meta($order_id,'_wcpdf_credit_notes_number','CN-'.date('Ym').'-'.$next) ) {
				update_post_meta($order_id,'_wcpdf_credit_notes_number','CN-'.date('Ym').'-'.$next);
				$formatted_number = $next;
				$number = (int) preg_replace('/\D/', '', $credit_notes_number);
				$credit_notes_number = compact( 'number', 'formatted_number' );
			}
			//2019-07-09 12:17:31
			if ( ! add_post_meta($order_id,'_wcpdf_credit_note_date_formatted',date('Y-m-d h:i:s', time())) ) {
				update_post_meta($order_id,'_wcpdf_credit_note_date_formatted',date('Y-m-d h:i:s', time()));
			}
			return $next;
		}
	}
	return false;
}
function webtual_get_credit_notes_last_date($format = 'Y-m-d H:i:s') {
		global $wpdb;
		$SQL="SELECT * FROM wp_wcpdf_credit_notes_number WHERE id = ( SELECT MAX(id) from wp_wcpdf_credit_notes_number )";
		$row = $wpdb->get_row($SQL);
		$date = isset( $row->date ) ? $row->date : 'now';
		$formatted_date = date( $format, strtotime( $date ) );
		return $formatted_date;
}
function webtual_credit_notes_hide_status($status=array()) {
$status=array('cancelled','refunded');
	return $status;
}
add_filter('webtual_credit_notes_wcpdf_myaccount_allowed_order_statuses', 'webtual_credit_notes_hide_status');
add_filter( 'woocommerce_my_account_my_orders_columns', 'webtual_my_account_order_action_name_change' );
function webtual_my_account_order_action_name_change( $columns ){
	// filter...
	$columns['order-actions']='Download';
	return $columns;
}
function webtual_action_remove_from_my_orders_actions( $actions, $order ) {
	global $woocommerce;
	unset($actions['view']);
	return $actions;
}
add_filter( 'woocommerce_my_account_my_orders_actions', 'webtual_action_remove_from_my_orders_actions', 50, 2 );
function webtual_remove_order_statuses( $wc_statuses_arr ){
	// Cancelled
	if( isset( $wc_statuses_arr['wc-cancelled'] ) ){
		unset( $wc_statuses_arr['wc-cancelled'] );
	}
	// Refunded
	if( isset( $wc_statuses_arr['wc-refunded'] ) ){
		$wc_statuses_arr['wc-refunded']='Cancel & Refund';
	}
	return $wc_statuses_arr; // return result statuses
}
add_filter( 'wc_order_statuses', 'webtual_remove_order_statuses' );
	function cloudways_save_extra_checkout_fields( $order_id, $posted ){
		// don't forget appropriate sanitization if you are using a different field type
		add_post_meta($order_id,'ship_to_different_address', $posted['ship_to_different_address']);
	}
	add_action( 'woocommerce_checkout_update_order_meta', 'cloudways_save_extra_checkout_fields', 10, 2 );
// Defer Javascripts
// Defer jQuery Parsing using the HTML5 defer property
if (!(is_admin() )) {
function defer_parsing_of_js ( $url ) {
if ( FALSE === strpos( $url, '.js' ) ) return $url;
if ( strpos( $url, 'jquery.js' ) ) return $url;
// return "$url' defer ";
return "$url' defer onload='";
}
add_filter( 'clean_url', 'defer_parsing_of_js', 11, 1 );
}
function product_price_change_by_admin_and_supplier($spid,$tube_price='',$tyre_price='',$percentage='',$margin_price='',$mrp='', $total='',$flag){
	global $wpdb, $woocommerce;
	$supp_pro_id=$spid;
	$flat_percentage=$percentage;
	$margin_price=$margin_price;
	$SQL = "SELECT * FROM th_supplier_products_list where id = '$supp_pro_id'";
	$supplier_pro = $wpdb->get_row($SQL);
	$product_id=$supplier_pro->product_id;
	$supplier_id=$supplier_pro->supplier_id;
	$SQL1 = "SELECT * FROM th_supplier_data where supplier_data_id = '$supplier_pro->supplier_id'";
	$supplier_data = $wpdb->get_row($SQL1);
	$auto_approve=$supplier_data->auto_approve;
		if($flag=='aproveprice'){
				$SQL = "SELECT * FROM th_supplier_products_list where id != '$supp_pro_id' AND product_id='$product_id' AND common_status=2 AND status=2";
	    		$price_results = $wpdb->get_results($SQL);
	    		foreach ($price_results as $key => $value) {
	    			# code...
	    			$update_data['common_status']=2;
	    			$update_data['status']=7;
					$update_data['updated_date']=date('Y-m-d H:i:s');
    				$wpdb->update('th_supplier_products_list',$update_data, array('id' =>$value->id));
	    		}
				$percentage=$flat_percentage;
				if($supplier_pro->new_mrp!=$mrp){
					$mrp=$mrp;
				}
				if($supplier_pro->new_mrp!=$mrp){
					$update_data['new_mrp'] = $mrp;
				}
				$new_tube_price =$supplier_pro->new_tube_price;
				$old_tube_price =$supplier_pro->old_tube_price;
				if($new_tube_price){
					$tube_price=$new_tube_price;
				}else{
					$tube_price=$old_tube_price;
				}
				$new_tyre_price =$supplier_pro->new_tyre_price;
				$old_tyre_price =$supplier_pro->old_tyre_price;
				if($new_tyre_price){
					$tyre_price=$new_tyre_price;
				}else{
					$tyre_price=$old_tyre_price;
				}
				//echo $tube_price;
				//echo '<br>';
				//echo $percentage;
				//echo '<br>';
				$perce_price=(($tyre_price+$tube_price)*$percentage)/100;
				$margin=$margin_price;
				//echo '<br>';
				$tyre_total=round(($tyre_price+$tube_price+$perce_price+$margin));
					update_post_meta($product_id,'_regular_price',$mrp);
					update_post_meta($product_id,'_sale_price',$tyre_total);
					update_post_meta($product_id,'_price',$mrp);
					update_post_meta($product_id,'tyre_price',$tyre_price);
					update_post_meta($product_id,'tube_price',$tube_price);
					update_post_meta($product_id,'active_supplier',$supplier_id);
					update_post_meta($product_id,'active_date',date('d-m-Y'));
				if($supplier_pro->flat_percentage!=$flat_percentage){
					$update_data['flat_percentage'] = $flat_percentage;
				}
				if($supplier_pro->margin_price!=$margin_price){
					$update_data['margin_price'] = $margin_price;
				}
				$update_data['new_total_price'] =$tyre_total;
				$update_data['updated_date'] =date('Y-m-d H:i:s');
				$update_data['status'] =1;
				 $wpdb->update('th_supplier_products_list',$update_data,
						array('id' =>$supp_pro_id)
					);
				$SQL2 = "SELECT * FROM th_supplier_products_log where supp_pro_id = '$supp_pro_id'";
				$pro_log = $wpdb->get_row($SQL2);
				if($pro_log->flat_percentage!=$flat_percentage){
					$log_data['flat_percentage'] = $flat_percentage;
				}
				if($pro_log->margin_price!=$margin_price){
					$log_data['margin_price'] = $margin_price;
				}
				if($supplier_pro->new_mrp!=$mrp){
					$log_data['new_mrp'] = $mrp;
				}
				$log_data['new_total_price'] = $tyre_total;
				$log_data['user_id'] =get_current_user_id();
				$log_data['status'] =1;
				$log_data['updated_date'] = date('Y-m-d H:i:s');
				/*echo '<pre>';
				print_r($log_data);
				die;*/
				/*echo $supp_pro_id.'-'.$product_id;
				echo '<br>';*/
		$wpdb->update('th_supplier_products_log',$log_data,array('supp_pro_id'=>$supp_pro_id,'product_id'=>$product_id,'status'=>2));
		}
	  if($flag=='supplierprice'){
			  $update = 'no';
			  //$update_data='';
			  $perce_price=(($tyre_price+$tube_price)*$supplier_pro->flat_percentage)/100;
			  $margin=$supplier_pro->margin_price;
			  $tyre_total=($tyre_price+$tube_price);
			  $total=$tyre_total+$perce_price+$margin;
			  $log_total=$total;
			  if($supplier_pro->new_mrp){
				$mrpdb=$supplier_pro->new_mrp;
			  }else{
				$mrpdb=$supplier_pro->old_mrp;
			  }
			  $update_data=array();
			  if($mrp){
			  	 $update_data['new_mrp'] = $mrp;
			  	 $update_data['old_mrp'] = $supplier_pro->new_mrp;
			  }
			  if($tube_price){
			  	 $update_data['new_tube_price'] = $tube_price;
			  	 $update_data['old_tube_price'] = $supplier_pro->new_tube_price;
			  }
			  if($tyre_price){
			  	 $update_data['new_tyre_price'] = $tyre_price;
			  	 $update_data['old_tyre_price'] = $supplier_pro->new_tyre_price;
			  }
			if($supplier_pro->new_tube_price){
			 $old_tube_price=$supplier_pro->new_tube_price;
			}else{
			 $old_tube_price=$supplier_pro->old_tube_price;
			}
			if($tube_price){
			 //$update_data['new_tube_price'] = $tube_price;
			 $log_data['old_tube_price'] =$old_tube_price;
			 $log_data['new_tube_price'] = $tube_price;
			 $tube_price=$old_tube_price;
			 $log_data['old_tube_price'] =$old_tube_price;
			}
			if($supplier_pro->new_tyre_price){
			 $old_tyre_price=$supplier_pro->new_tyre_price;
			}else{
			 $old_tyre_price=$supplier_pro->old_tyre_price;
			}
			if($tyre_price){
			 //$update_data['new_tyre_price'] = $tyre_price;
			 $log_data['old_tyre_price'] = $old_tyre_price;
			 $log_data['new_tyre_price'] = $tyre_price;
			}else{
				$tyre_price=$old_tyre_price;
				$log_data['old_tyre_price'] = $old_tyre_price;
			}
			$log_data['old_percentage'] = $supplier_pro->flat_percentage;
			$log_data['old_margin_price'] = $supplier_pro->margin_price;
			if($supplier_pro->new_mrp){
			 $old_mrp=$supplier_pro->new_mrp;
			}else{
			 $old_mrp=$supplier_pro->old_mrp;
			}
			if($mrp){
			 $update_data['new_mrp'] = $mrp;
			 $log_data['old_mrp'] = $old_mrp;
			 $log_data['new_mrp'] = $mrp;
			 $log_data['product_id'] = $product_id;
			 $log_data['supp_pro_id'] = $supp_pro_id;
			}else{
			  //$update_data['new_mrp'] = $mrp;
			  $log_data['old_mrp'] = $supplier_pro->old_mrp;
			  $log_data['product_id'] = $product_id;
			  $log_data['supp_pro_id'] = $supp_pro_id;
			}
			if($total>0 || $tyre_price>0 || $tube_price>0){
			 //  $perce_price=(($tyre_price+$tube_price)*$supplier_pro->flat_percentage)/100;
			 //  $margin=$supplier_pro->margin_price;
			 //  $tyre_total=($tyre_price+$tube_price);
			 // $total=$tyre_total+$perce_price+$margin;
			  $update_data['new_total_price'] = $total;
			  $update_data['old_total_price'] = $supplier_pro->new_total_price;
			  $log_data['old_total_price'] = $supplier_pro->old_total_price;
			  $log_data['new_total_price'] =$total;
			  $log_data['product_id'] = $product_id;
			  $log_data['supp_pro_id'] = $supp_pro_id;
			}
			$update_data['status'] = 2;
			$log_data['status'] = 2;
			$update_data['common_status'] = 2;
			$log_data['supplier_id']=$supplier_id;
			$log_data['updated_date']=date('Y-m-d H:i:s');
			$update_data['updated_date']=date('Y-m-d H:i:s');
			$wpdb->update('th_supplier_products_list',$update_data,array('id' =>$supp_pro_id));
			$insert = $wpdb->insert('th_supplier_products_log',$log_data);
	  	}
}
function product_price_change_notice(){
	global $wpdb;
	$rowcount = $wpdb->get_var("SELECT COUNT(*) FROM th_supplier_products_list WHERE status=2 AND common_status=2");
	return $rowcount;
}
function supplier_price_change_admin_notice__error() {
	$class = 'notice notice-error';
	$message = __('Supplier has submitted a new price list, please click here to review and approve the new price.', 'sample-text-domain' );
	printf( '<div class="%1$s" style="background:red;color:#fff;"><p style="font-size:17px;">%2$s  &nbsp;&nbsp;<a href="'.site_url('wp-admin/admin.php?page=supplier-product-price-change-list&status=2').'"><button class="search-btn" id="">Go Price Approve</button></a></p></div>', esc_attr( $class ), esc_html( $message ) );
}
if(product_price_change_notice()>0 && is_admin()){
	add_action( 'admin_notices', 'supplier_price_change_admin_notice__error' );
}
add_action('wp_ajax_supplier_search_product_data', 'supplier_search_product_data');
add_action('wp_ajax_nopriv_supplier_search_product_data', 'supplier_search_product_data');
function supplier_search_product_data()
{
		global $wpdb;
		$SQL1="SELECT * FROM th_supplier_data WHERE user_id='".get_current_user_id()."'";
		$supplierData=$wpdb->get_results($SQL1);
		$supplier_id=$supplierData[0]->supplier_data_id;
		$all_product_access=$supplierData[0]->all_product_access;
		$width =$_POST['width'];
		$width=str_replace(".","-",$width);
		$ratio = $_POST['ratio'];
		$diameter =$_POST['diameter'];
		$name = $_POST['category'];
		$status = $_POST['status'];
		$visiblity = 'yes';
		$name = strtolower($name);
		$search=$_POST['search'];
		$srch=$search['value'];
	  $SQL="SELECT   sp.*,sd.business_name FROM th_supplier_products AS sp ";
	  $SQL.=" LEFT JOIN wp_posts ON ( wp_posts.ID = sp.product_id )";
	  if($width){
			$SQL.=" LEFT JOIN wp_postmeta ON ( wp_posts.ID = wp_postmeta.post_id ) ";
		}
	  if($diameter){
	  $SQL.=" LEFT JOIN wp_postmeta AS mt1 ON ( wp_posts.ID = mt1.post_id )";
	  }
	  if($ratio){
	  $SQL.=" LEFT JOIN wp_postmeta AS mt2 ON ( wp_posts.ID = mt2.post_id )";
	  }
	 if($name){
		$SQL.=" LEFT JOIN wp_postmeta AS mt3 ON ( wp_posts.ID = mt3.post_id )";
	 }
	 if($srch){
		$SQL.=" LEFT JOIN wp_postmeta AS mt5 ON ( wp_posts.ID = mt5.post_id )";
	 }
	 $SQL.=" LEFT JOIN wp_postmeta AS mt4 ON ( wp_posts.ID = mt4.post_id )";
	 $SQL.=" LEFT JOIN th_supplier_data AS sd ON (sp.supplier_id = sd.supplier_data_id)";
	$WHERE="WHERE 1=1 ";
	if($width){
		$WHERE.=" AND ( wp_postmeta.meta_key = 'attribute_pa_width' AND wp_postmeta.meta_value IN ('".$width."') )";
		}
	if($diameter){
	$WHERE.=" AND ( mt1.meta_key = 'attribute_pa_diameter' AND mt1.meta_value IN ('".$diameter."') )";
	}
	if($ratio){
		$WHERE.=" AND ( mt2.meta_key = 'attribute_pa_ratio' AND mt2.meta_value IN ('".$ratio."') ) ";
	}
	if($name){
		$WHERE.=" AND ( mt3.meta_key = 'attribute_pa_brand' AND mt3.meta_value IN ('".$name."') )";
	}
	if($srch){
		$WHERE.=" AND ( mt5.meta_key = '_variation_description' AND mt5.meta_value LIKE '%".$srch."%')";
	}
	$WHERE.=" AND ( mt4.meta_key = 'tyrehub_visible' AND mt4.meta_value IN ('yes','contact-us')) ";
	 if($supplier_id && $all_product_access==0){
		$WHERE.=" AND (sp.supplier_id =".$supplier_id.")";
		}
	if($status=='pending'){
		$WHERE.=" AND (sp.status =2)";
	}
	$WHERE.=" AND wp_posts.post_type = 'product_variation' AND (wp_posts.post_status = 'publish' OR wp_posts.post_status = 'wc-deltoinstaller' OR wp_posts.post_status = 'wc-customprocess' OR wp_posts.post_status = 'future' OR wp_posts.post_status = 'draft' OR wp_posts.post_status = 'pending' OR wp_posts.post_status = 'private') GROUP BY wp_posts.ID ORDER BY sp.updated_date desc";
	$SQL=$SQL.$WHERE;
	$supplierProductData=$wpdb->get_results($SQL);
	if($supplierProductData){
		$product_array=array();
		foreach ($supplierProductData as $key => $value)
				{
					//$product_variation = wc_get_product($value->product_id);
					$tyre_type=get_post_meta($value->product_id,'attribute_pa_tyre-type',true);
					$user_info = get_userdata($value->user_id);
					$product_name= get_post_meta($value->product_id,'_variation_description',true);
					if($value->new_mrp && $value->status!=4){
						$regular_price=$value->new_mrp;
					}else{
						$regular_price=$value->old_mrp;
					}
					$new_tube_price =$value->new_tube_price;
					$old_tube_price =$value->old_tube_price;
					if($new_tube_price && $value->status!=4){
						$tube_price=$new_tube_price;
					}else{
						$tube_price=$old_tube_price;
					}
					$new_tyre_price =$value->new_tyre_price;
					$old_tyre_price =$value->old_tyre_price;
					if($new_tyre_price && $value->status!=4){
						$tyre_price=$new_tyre_price;
					}else{
						$tyre_price=$old_tyre_price;
					}
					$sale_price=($tube_price+$tyre_price);
					$product='';
					$tubetyre='';
					$tyreprice='';
					$new_mrp_price='';
					$total_price='';
					$status='';
					$product='<input type=""hidden class="product-row" data-id="'.$value->id.'">';
					$product.=$product_name;
						if($tyre_type == 'tubetyre')
						{
							$tubetyre= wc_price( $tube_price);
							$tubetyre.='<input type="number" value="" class="tube-price-real" name="tube_price_real" data-price="'.$tube_price.'">';
						}else{
							$tubetyre='-';
							$tubetyre.='<input type="hidden" value="-" class="tube-price-real" name="tube_price_real">';
						}
					$tyreprice=wc_price( $tyre_price);
					$tyreprice.= '<input type="number" value="" class="tyre-price-real" name="tyre_price_real" data-price="'.$tyre_price.'">';
						$new_mrp_price=wc_price( $regular_price);
						$new_mrp_price.='<div class="price regular-price" data-price="'.$regular_price.'">';
						$new_mrp_price.= '<input type="hidden" value="'.$regular_price.'" name="mrp_price" class="mrp-price">';
						$new_mrp_price.='<input type="number" name="new_mrp_price" class="new-mrp-price">';
						$total_price=wc_price( $sale_price);
						$total_price.= '<input type="number"  value="" class="sale-price-real" name="sale_price_real" data-price="" readonly>';
						if($value->status==1){
							$status='A';
							$class='status status-a';
							$titletool='Approve';
						}elseif($value->status==2){
							$status='P';
							$class='status status-p';
							$titletool='Pending';
						}elseif($value->status==3){
							$status='AA';
							$class='status status-aa';
							$titletool='Auto Approve';
						}elseif($value->status==4){
						   $status='C';
							$class='status status-c';
							$titletool='Cancel';
						}else{
							/*$status='Cancel';
							$class='btn btn-warning';*/
						}
						$status='<a class="'.$class.'" data-toggle="tooltip" title="'.$titletool.'">'.$status.'</a>';
					$product_array[$key]['product']=$product;
					$product_array[$key]['tube_price']=$tubetyre;
					$product_array[$key]['tyre_price']=$tyreprice;
					$product_array[$key]['mrp_price']=$new_mrp_price;
					$product_array[$key]['total_price']=$total_price;
					$product_array[$key]['status']=$status;
				}
			}else{
				//echo '<tr><td colspan="5">Product not found!</td></tr>';
			}
					$data_return['draw']=0;
					$data_return['recordsTotal']=count($supplierProductData);
					$data_return['recordsFiltered']=count($supplierProductData);
					$data_return['data']=$product_array;
					echo json_encode($data_return);
			die;
}
add_action('wp_ajax_supplier_search_product_data1', 'supplier_search_product_data1');
add_action('wp_ajax_nopriv_supplier_search_product_data1', 'supplier_search_product_data1');
function supplier_search_product_data1()
{
		global $wpdb;
		$SQL1="SELECT * FROM th_supplier_data WHERE user_id='".get_current_user_id()."'";
		$supplierData=$wpdb->get_results($SQL1);
		$supplier_id=$supplierData[0]->supplier_data_id;
		$all_product_access=$supplierData[0]->all_product_access;
		$width =$_POST['width'];
		$width=str_replace(".","-",$width);
		$ratio = $_POST['ratio'];
		$diameter =$_POST['diameter'];
		$name = $_POST['category'];
		$status = $_POST['status'];
		$visiblity = 'yes';
		$name = strtolower($name);
	  $SQL="SELECT   sp.*,sd.business_name FROM th_supplier_products AS sp ";
	  $SQL.=" LEFT JOIN wp_posts ON ( wp_posts.ID = sp.product_id )";
	  if($width){
			$SQL.=" LEFT JOIN wp_postmeta ON ( wp_posts.ID = wp_postmeta.post_id ) ";
		}
	  if($diameter){
	  $SQL.=" LEFT JOIN wp_postmeta AS mt1 ON ( wp_posts.ID = mt1.post_id )";
	  }
	  if($ratio){
	  $SQL.=" LEFT JOIN wp_postmeta AS mt2 ON ( wp_posts.ID = mt2.post_id )";
	  }
	 if($name){
		$SQL.=" LEFT JOIN wp_postmeta AS mt3 ON ( wp_posts.ID = mt3.post_id )";
	 }
	 $SQL.=" LEFT JOIN wp_postmeta AS mt4 ON ( wp_posts.ID = mt4.post_id )";
	 $SQL.=" LEFT JOIN th_supplier_data AS sd ON (sp.supplier_id = sd.supplier_data_id)";
	$WHERE="WHERE 1=1 ";
	if($width){
		$WHERE.=" AND ( wp_postmeta.meta_key = 'attribute_pa_width' AND wp_postmeta.meta_value IN ('".$width."') )";
		}
	if($diameter){
	$WHERE.=" AND ( mt1.meta_key = 'attribute_pa_diameter' AND mt1.meta_value IN ('".$diameter."') )";
	}
	if($ratio){
		$WHERE.=" AND ( mt2.meta_key = 'attribute_pa_ratio' AND mt2.meta_value IN ('".$ratio."') ) ";
	}
	if($name){
		$WHERE.=" AND ( mt3.meta_key = 'attribute_pa_brand' AND mt3.meta_value IN ('".$name."') )";
	}
	$WHERE.=" AND ( mt4.meta_key = 'tyrehub_visible' AND mt4.meta_value IN ('yes','contact-us')) ";
	 if($supplier_id && $all_product_access==0){
		$WHERE.=" AND (sp.supplier_id =".$supplier_id.")";
		}
	if($status=='pending'){
		$WHERE.=" AND (sp.status =2)";
	}
	$WHERE.=" AND wp_posts.post_type = 'product_variation' AND (wp_posts.post_status = 'publish' OR wp_posts.post_status = 'wc-deltoinstaller' OR wp_posts.post_status = 'wc-customprocess' OR wp_posts.post_status = 'future' OR wp_posts.post_status = 'draft' OR wp_posts.post_status = 'pending' OR wp_posts.post_status = 'private') GROUP BY wp_posts.ID ORDER BY sp.updated_date desc";
	$SQL=$SQL.$WHERE;
	$supplierProductData=$wpdb->get_results($SQL);
	if($supplierProductData){
		foreach ($supplierProductData as $key => $value)
				{
					//$product_variation = wc_get_product($value->product_id);
					$tyre_type=get_post_meta($value->product_id,'attribute_pa_tyre-type',true);
					$user_info = get_userdata($value->user_id);
					$product= get_post_meta($value->product_id,'_variation_description',true);
					if($value->new_mrp && $value->status!=4){
						$regular_price=$value->new_mrp;
					}else{
						$regular_price=$value->old_mrp;
					}
					$new_tube_price =$value->new_tube_price;
					$old_tube_price =$value->old_tube_price;
					if($new_tube_price && $value->status!=4){
						$tube_price=$new_tube_price;
					}else{
						$tube_price=$old_tube_price;
					}
					$new_tyre_price =$value->new_tyre_price;
					$old_tyre_price =$value->old_tyre_price;
					if($new_tyre_price && $value->status!=4){
						$tyre_price=$new_tyre_price;
					}else{
						$tyre_price=$old_tyre_price;
					}
					$sale_price=($tube_price+$tyre_price);
					echo '<tr class="product-row" data-id="'.$value->id.'">';
					echo '<td>('.$value->product_id.') '.$product.'</td>';
					echo '<td>';
						if($tyre_type == 'tubetyre')
						{
							echo wc_price( $tube_price, $args ); ?>
							<input type="number" value="" class="tube-price-real" name="tube_price_real" data-price="<?php  echo $tube_price; ?>">
						<?php  }
						else{
							echo '-';
							?>
							 <input type="hidden" value="-" class="tube-price-real" name="tube_price_real">
					<?php
						}
					echo '</td>';
					echo '<td>';
						echo wc_price( $tyre_price);
						echo '<input type="number" value="" class="tyre-price-real" name="tyre_price_real" data-price="'.$tyre_price.'">';
					echo '</td>';
					echo '<td>';
						echo wc_price( $regular_price);
						echo '<div class="price regular-price" data-price="'.$regular_price.'">';
						echo '<input type="hidden" value="'.$regular_price.'" name="mrp_price" class="mrp-price">';
						echo '<input type="number" name="new_mrp_price" class="new-mrp-price">';
					echo '</div>';
					echo '</td>';
					echo '<td>';
						echo wc_price( $sale_price);
						echo '<input type="number"  value="" class="sale-price-real" name="sale_price_real" data-price="" readonly>';
					echo '</td>';
					echo '<td>';
						if($value->status==1){
							$status='A';
							$class='status status-a';
							$titletool='Approve';
						}elseif($value->status==2){
							$status='P';
							$class='status status-p';
							$titletool='Pending';
						}elseif($value->status==3){
							$status='AA';
							$class='status status-aa';
							$titletool='Auto Approve';
						}elseif($value->status==4){
						   $status='C';
							$class='status status-c';
							$titletool='Cancel';
						}else{
							/*$status='Cancel';
							$class='btn btn-warning';*/
						}
						echo '<a class="'.$class.'" data-toggle="tooltip" title="'.$titletool.'">'.$status.'</a>';
					echo '</td>';
					echo '</tr>';
				}
			}else{
				echo '<tr><td colspan="5">Product not found!</td></tr>';
			}
			die;
}
add_action('wp_ajax_supplier_change_product_price', 'supplier_change_product_price');
add_action('wp_ajax_nopriv_supplier_change_product_price', 'supplier_change_product_price');
function supplier_change_product_price()
{
	global $woocommerce , $wpdb;
	$prd_list = $_POST['prd_list'];
	$currentTime = date("Y-m-d h:i:sa");
	$result = [];
foreach ($prd_list as $id => $list)
		{
			$update = 'no';
			$spid = $list['spid'];
			$total = $list['price_list']['sale_price'];
			$tyre_price = $list['price_list']['tyre_price'];
			$tube_price = $list['price_list']['tube_price'];
			$mrp = $list['price_list']['mrp_price_new'];
			//$mrp_price = $list['price_list']['mrp_price'];
		   if($mrp!='' || $total!=''){
product_price_change_by_admin_and_supplier($spid,$tube_price,$tyre_price,$percentage,$margin_price,$mrp,$total,'supplierprice');
		   }
		}
	echo json_encode($result);
	die();
}
// define the woocommerce_reset_password_notification callback
function action_woocommerce_reset_password_notification( $user_login, $key ) {
	// make action magic happen here...
	$_SESSION['pass_reset_key'] = $key;
};
// add the action
add_action( 'woocommerce_reset_password_notification','action_woocommerce_reset_password_notification', 100,2);
 function generateSeoURL($string, $wordLimit = 0){
	$separator = '-';
	if($wordLimit != 0){
		$wordArr = explode(' ', $string);
		$string = implode(' ', array_slice($wordArr, 0, $wordLimit));
	}
	$quoteSeparator = preg_quote($separator, '#');
	$trans = array(
		'&.+?;'                    => '',
		'[^\w\d _-]'            => '',
		'\s+'                    => $separator,
		'('.$quoteSeparator.')+'=> $separator
	);
	$string = strip_tags($string);
	foreach ($trans as $key => $val){
		$string = preg_replace('#'.$key.'#i'.(UTF8_ENABLED ? 'u' : ''), $val, $string);
	}
	$string = strtolower($string);
	return trim(trim($string, $separator));
}
function supplier_product_count($supplier_id) {
	global $wpdb;
	$rowcount = $wpdb->get_var("SELECT COUNT(*) FROM th_supplier_products_list WHERE supplier_id =$supplier_id");
	return $rowcount;
}
function get_supplier_data($id){
			global $wpdb, $woocommerce;
			$SQL="SELECT * FROM th_supplier_data WHERE supplier_data_id='".$id."'";
			$results=$wpdb->get_results($SQL);
			return $results;
}
add_action('wp_ajax_product_data_supplier', 'product_data_supplier');
add_action('wp_ajax_nopriv_product_data_supplier', 'product_data_supplier');
function product_data_supplier()
{
    /*$width = $_POST['width'];
    $ratio = $_POST['ratio'];
    $diameter = $_POST['diameter'];
    $name = $_POST['cat'];
    $name = strtolower($name);*/
    global $wpdb;
		$SQL1="SELECT * FROM th_supplier_data WHERE user_id='".get_current_user_id()."'";
		$supplierData=$wpdb->get_results($SQL1);
		$supplier_id=$supplierData[0]->supplier_data_id;
		$all_product_access=$supplierData[0]->all_product_access;
		$width =$_POST['width'];
		$width=str_replace(".","-",$width);
		$ratio = $_POST['ratio'];
		$diameter =$_POST['diameter'];
		$name = $_POST['cat'];
		$status = $_POST['status'];
		$visiblity = 'yes';
		$name = strtolower($name);
	  $SQL="SELECT   sp.*,sd.business_name FROM th_supplier_products_list AS sp ";
	  $SQL.=" LEFT JOIN wp_posts ON ( wp_posts.ID = sp.product_id )";
	  if($width){
			$SQL.=" LEFT JOIN wp_postmeta ON ( wp_posts.ID = wp_postmeta.post_id ) ";
		}
	  if($diameter){
	  $SQL.=" LEFT JOIN wp_postmeta AS mt1 ON ( wp_posts.ID = mt1.post_id )";
	  }
	  if($ratio){
	  $SQL.=" LEFT JOIN wp_postmeta AS mt2 ON ( wp_posts.ID = mt2.post_id )";
	  }
	 if($name){
		$SQL.=" LEFT JOIN wp_postmeta AS mt3 ON ( wp_posts.ID = mt3.post_id )";
	 }
	 $SQL.=" LEFT JOIN wp_postmeta AS mt4 ON ( wp_posts.ID = mt4.post_id )";
	 $SQL.=" LEFT JOIN th_supplier_data AS sd ON (sp.supplier_id = sd.supplier_data_id)";
	$WHERE="WHERE 1=1 ";
	if($width){
		$WHERE.=" AND ( wp_postmeta.meta_key = 'attribute_pa_width' AND wp_postmeta.meta_value IN ('".$width."') )";
		}
	if($diameter){
	$WHERE.=" AND ( mt1.meta_key = 'attribute_pa_diameter' AND mt1.meta_value IN ('".$diameter."') )";
	}
	if($ratio){
		$WHERE.=" AND ( mt2.meta_key = 'attribute_pa_ratio' AND mt2.meta_value IN ('".$ratio."') ) ";
	}
	if($name){
		$WHERE.=" AND ( mt3.meta_key = 'attribute_pa_brand' AND mt3.meta_value IN ('".$name."') )";
	}
	$WHERE.=" AND ( mt4.meta_key = 'tyrehub_visible' AND mt4.meta_value IN ('yes','contact-us')) ";
	 if($supplier_id){
		$WHERE.=" AND (sp.supplier_id =".$supplier_id.")";
		}
	if($status=='pending'){
		$WHERE.=" AND (sp.status =2)";
	}
	$WHERE.=" AND wp_posts.post_type = 'product_variation' AND (wp_posts.post_status = 'publish' OR wp_posts.post_status = 'wc-deltoinstaller' OR wp_posts.post_status = 'wc-customprocess' OR wp_posts.post_status = 'future' OR wp_posts.post_status = 'draft' OR wp_posts.post_status = 'pending' OR wp_posts.post_status = 'private') GROUP BY wp_posts.ID ORDER BY sp.updated_date desc";
	$SQL=$SQL.$WHERE;
	$supplierProductData=$wpdb->get_results($SQL);
           //   var_dump($variations);
    //echo '<h2>Search Product List</h2>';
    if(empty($supplierProductData))
    {
        echo 'No Product Found';
    }
    else
    {
        ?>
        <?php
        foreach ( $supplierProductData as $key=> $value )
        {
            //$product_variation = wc_get_product($value->product_id);
				$tyre_type=get_post_meta($value->product_id,'attribute_pa_tyre-type',true);
				$user_info = get_userdata($value->user_id);
				$product= get_post_meta($value->product_id,'_variation_description',true);
				/*if($value->new_mrp && $value->status!=4){
					$regular_price=$value->new_mrp;
				}else{
					$regular_price=$value->old_mrp;
				}*/
				$new_tube_price =$value->new_tube_price;
				$old_tube_price =$value->old_tube_price;
				if($new_tube_price && $value->status!=4){
					$tube_price=$new_tube_price;
				}else{
					$tube_price=$old_tube_price;
				}
				$new_tyre_price =$value->new_tyre_price;
				$old_tyre_price =$value->old_tyre_price;
				if($new_tyre_price && $value->status!=4){
					$tyre_price=$new_tyre_price;
				}else{
					$tyre_price=$old_tyre_price;
				}
				$tubtyre=($tube_price+$tyre_price);
					if($tube_price>0)
					{
						$tyre_gst = $tyre_price * 28 / 128;
						$tube_gst = $tube_price * 28 / 128;
						//$gst = $tyre_gst + $tube_gst;
						$tube_price=$tube_price-$tube_gst;
						$tyre_price=$tyre_price-$tyre_gst;
					}else{
						$tube_price='-';
						$tyre_price=$tyre_price-$tyre_gst;
					}
				$sale_price=($tube_price+$tyre_price);
            $args = array(
                    'ex_tax_label'       => false,
                    'currency'           => '',
                    'decimal_separator'  => wc_get_price_decimal_separator(),
                    'thousand_separator' => wc_get_price_thousand_separator(),
                    'decimals'           => wc_get_price_decimals(),
                    'price_format'       => get_woocommerce_price_format(),
                  );
            if($sale_price == ''){
                $sale_price = '-';
            }else{
            }
    ?>
            <div class="single-product" data-id="<?php echo $value->product_id; ?>" id='<?php echo $value->product_id; ?>'>
                <div class="name"><?php echo $product; ?></div>
                <div class="price tube-price"><?php echo wc_price($tube_price, $args ); ?></div>
                <div class="price tyre-price"><?php echo wc_price($tyre_price, $args ); ?></div>
                <div class="price sale-price" data-sale-price="<?=$sale_price;?>"><?php echo wc_price($sale_price); ?></div>
                <div class="amount">
                    <input type="text" name="discount_amount" class="discount_amount">
                </div>
                <div class="send"><span>>></span></div>
            </div>
                <?php
        }
    }
die();
}
add_action('wp_ajax_direct_vehicle_type_add', 'direct_vehicle_type_add');
add_action('wp_ajax_nopriv_direct_vehicle_type_add', 'direct_vehicle_type_add');
function direct_vehicle_type_add() {
	global $woocommerce,$wpdb;
	session_start();
	$vehicle_type = $_POST['vehicle_type'];
	 $_SESSION['vehicle_type']=$_POST['vehicle_type'];
	$cart = $woocommerce->cart->cart_contents;
	// cycle the cart replace the meta of the correspondant key
	echo '<pre>';
	print_r($cart);
	foreach ($cart as $key => $item) {
	            // Update the content of the kart
			$woocommerce->cart->cart_contents[$key]['custom_data']['vehicle_type'] = $vehicle_type;
					$wpdb->update(
						'th_cart_item_services',
						array(
							'vehicle_id' => $vehicle_type,	// string
						),
						array( 'cart_item_key' => $key)
					);
					$wpdb->update(
						'th_cart_item_installer',
						array(
							'vehicle_id' => $vehicle_type,	// string
						),
						array('cart_item_key' => $key)
					);
	}
	// This is the magic: With this function, the modified object gets saved.
	$woocommerce->cart->set_session();
die;
}
add_action('wp_dashboard_setup', 'my_custom_dashboard_widgets');
function my_custom_dashboard_widgets() {
global $wp_meta_boxes;
		$current_user = get_current_user_id();
		$user_meta=get_userdata($current_user);
		$user_roles=$user_meta->roles;
		if($user_roles[0] == "Installer" || $user_roles[0] == "Customer" || $user_roles[0] == "btobpartner")
		{
			wp_redirect(site_url());
		}
if($user_roles[0] == "shop_manager"){
}elseif($user_roles[0] == "Installer"){
}elseif($user_roles[0] == "Installer") {
}elseif($user_roles[0] == "Installer"){
}
wp_add_dashboard_widget('custom_order_widget', 'Orders', 'custom_dashboard_order');
wp_add_dashboard_widget('custom_reports_widget', 'Reports', 'custom_dashboard_reports');
wp_add_dashboard_widget('custom_discount_widget', 'Discount/Coupons Manage', 'custom_dashboard_discount');
wp_add_dashboard_widget('custom_prrice_change_widget', 'Product Price Change', 'custom_dashboard_product_price_change');
wp_add_dashboard_widget('custom_supplier_widget', 'Suppliers', 'custom_dashboard_supplier');
wp_add_dashboard_widget('custom_installer_widget', 'Installers', 'custom_dashboard_installer');
wp_add_dashboard_widget('custom_franchise_widget', 'Franchise', 'custom_dashboard_franchise');
wp_add_dashboard_widget('custom_portal_settings_widget', 'Portal Settings', 'custom_dashboard_portal_settings');
wp_add_dashboard_widget('custom_btob_widget', 'Business Partners', 'custom_dashboard_btob');
wp_add_dashboard_widget('custom_carservice_widget', 'Car Service', 'custom_dashboard_carservice');
}
function custom_dashboard_carservice()
{ ?>
	<ul>
		<li><a href="<?=admin_url()?>admin.php?page=flattyre-manage">Flat Tyre / Jump Start Management</a></li>
		<li><a href="<?=admin_url()?>admin.php?page=campaign-users">Campaign Users Management</a></li>
		<li><a href="<?=admin_url()?>admin.php?page=inspection-manage">Free Tyre inspection Management</a></li>
		<li><a href="<?=admin_url()?>admin.php?page=Towing-manage">Towing service Management</a></li>
	</ul>
<?php }
function custom_dashboard_order() {?>
	<ul>
		<li><a href="<?=admin_url()?>edit.php?post_type=shop_order">All Orders</a></li>
		<li><a href="<?=admin_url()?>admin.php?page=sold_out">Product Sold Out</a></li>
	</ul>
<?php }
function custom_dashboard_reports() {?>
	<ul>
		<li><a href="<?=admin_url()?>admin.php?page=ca_order_reports_csv_export_list">CA Order Reports</a></li>
	</ul>
	<ul>
		<li><a href="<?=admin_url()?>edit.php?post_type=product&page=products_csv_export_list">Products CSV Export</a></li>
	</ul>
<?php }
function custom_dashboard_discount() {?>
	<ul>
		<li><a href="<?=admin_url()?>post-new.php?post_type=shop_coupon"> Add Coupon</a></li>
		<li><a href="<?=admin_url()?>edit.php?post_type=shop_coupon"> Coupons List</a></li>
	</ul>
	<hr>
	<ul>
		<li><a href="<?=admin_url()?>admin.php?page=new-discount"> Add Discount</a></li>
		<li><a href="<?=admin_url()?>admin.php?page=discount_rule"> Discount List</a></li>
	</ul>
	<hr>
	<ul>
		<li><a href="<?=admin_url()?>admin.php?page=promotions_voucher">Promotions Voucher</a></li>
	</ul>
<?php }
function custom_dashboard_product_price_change() {?>
	<ul>
		<li><a href="<?=admin_url()?>edit.php?post_type=product&page=change_price"> Price Change</a></li>
		<li><a href="<?=admin_url()?>admin.php?page=supplier-product-price-change-list">Price Approval</a></li>
		<li><a href="<?=admin_url()?>admin.php?page=change_price_log"> Change Price Log</a></li>
	</ul>
<?php }
function custom_dashboard_supplier() {?>
	<ul>
		<li><a href="<?=admin_url()?>admin.php?page=supplier-add-new">Add Supplier</a></li>
		<li><a href="<?=admin_url()?>admin.php?page=supplier-manage"> Supplier List</a></li>
		<li><a href="<?=admin_url()?>admin.php?page=supplier-product-price-change-list">Price Approval</a></li>
		<li><a href="<?=admin_url()?>admin.php?page=change_price_log"> Change Price Log</a></li>
	</ul>
<?php }
function custom_dashboard_installer() {?>
	<ul>
		<li><a href="<?=admin_url()?>admin.php?page=installer-add-new">Add Installer</a></li>
		<li><a href="<?=admin_url()?>admin.php?page=installer-manage"> Manage Installer</a></li>
		<li><a href="<?=admin_url()?>admin.php?page=installer-report">Payout Process</a></li>
		<li><a href="<?=admin_url()?>admin.php?page=installer-invoice-report"> Payout Invoice</a></li>
	</ul>
<?php }
function custom_dashboard_franchise() {?>
	<ul>
		<li><a href="<?=admin_url()?>admin.php?page=franchise-orders-list">Orders</a></li>
		<li><a href="<?=admin_url()?>admin.php?page=ofline-ordres">Walking Orders</a></li>
		<li><a href="<?=admin_url()?>admin.php?page=installer-add-new">Add Franchise</a></li>
		<li><a href="<?=admin_url()?>admin.php?page=installer-manage&franchise=yes"> Manage Franchise</a></li>
		<li><a href="<?=admin_url()?>admin.php?page=franchise-report">Payout Process</a></li>		
		<li><a href="<?=admin_url()?>admin.php?page=franchise-invoice-report">Payout Listing</a></li>
		<li><a href="<?=admin_url()?>admin.php?page=franchise-payout-history"> Payout History</a></li>
	</ul>
<?php }
function custom_dashboard_portal_settings() {?>
	<ul>
		<li><a href="<?=admin_url()?>admin.php?page=installer-cities-add&action=add">Add City</a></li>
		<li><a href="<?=admin_url()?>admin.php?page=installer-cities"> Manage Cities </a></li>
		<hr>
		<li><a href="<?=admin_url()?>admin.php?page=installer-fitment-charges">Installer Fitting Charges</a></li>
		<li><a href="<?=admin_url()?>admin.php?page=installer-fitment-charges&action=add"> Add Installer Fitting Charges</a></li>
		<hr>
		<li><a href="<?=admin_url()?>admin.php?page=theme_settings"> Theme Settings</a></li>
		<hr>
		<li><a href="<?=admin_url()?>admin.php?page=delivery-range-add&action=add"> Add Delivery Range</a></li>
		<li><a href="<?=admin_url()?>admin.php?page=delivery-range"> Delivery Range</a></li>
	</ul>
<?php }
function custom_dashboard_btob() {?>
	<ul>
		<li><a href="<?=admin_url()?>admin.php?page=bpartner-add-new">Add Business Partner</a></li>
		<li><a href="<?=admin_url()?>admin.php?page=bpartner-manage"> Manage Business Partner</a></li>
		<li><a href="<?=admin_url()?>admin.php?page=commission-history"> Commission History</a></li>
	</ul>
<?php }
function mysite_pending($order_id) {
    //error_log("$order_id set to PENDING");
    order_status_change($order_id,'pending');
}
function mysite_failed($order_id) {
    //error_log("$order_id set to FAILED");
    order_status_change($order_id,'failed');
}
function mysite_hold($order_id) {
    //error_log("$order_id set to ON HOLD");
    order_status_change($order_id,'on-hold');
}
function mysite_processing($order_id) {
    //error_log("$order_id set to PROCESSING");
    order_status_change($order_id,'customprocess');
}
function mysite_completed($order_id) {
    //error_log("$order_id set to COMPLETED");
    order_status_change($order_id,'completed');
}
function mysite_refunded($order_id) {
    //error_log("$order_id set to REFUNDED");
    order_status_change($order_id,'refunded');
}
function mysite_cancelled($order_id) {
    //error_log("$order_id set to CANCELLED");
    order_status_change($order_id,'refunded');
}
add_action( 'woocommerce_order_status_pending', 'mysite_pending', 100, 1);
add_action( 'woocommerce_order_status_failed', 'mysite_failed', 100, 1);
add_action( 'woocommerce_order_status_on-hold', 'mysite_hold', 100, 1);
// Note that it's woocommerce_order_status_on-hold, and NOT on_hold.
add_action( 'woocommerce_order_status_processing', 'mysite_processing', 100, 1);
add_action( 'woocommerce_order_status_completed', 'mysite_completed', 100, 1);
add_action( 'woocommerce_order_status_refunded', 'mysite_refunded', 100, 1);
add_action( 'woocommerce_order_status_cancelled', 'mysite_cancelled', 100, 1);
function order_status_change($order_id,$status){
	global $wpdb;
	$wpdb->update(
		'th_business_commission',
		array(
			'order_status' =>$status,	// string
		),
		array('order_id' => $order_id)
	);
}
add_action('wp_ajax_get_pickup_price', 'get_pickup_price');
add_action('wp_ajax_nopriv_get_pickup_price', 'get_pickup_price');
function get_pickup_price()
{
	session_start();
	global $woocommerce , $wpdb;
	$pic_address = $_POST['pic_address'];
	$installer_id = $_POST['installer_id'];
	$service_id = $_POST['service_id'];
	$city_id = $_POST['city_id'];
	$_SESSION['pic_address']=$pic_address;
//unset($_SESSION['pic_address']);
	$SQL="SELECT * FROM th_installer_data WHERE installer_data_id='".$installer_id."'";
	$insta_result=$wpdb->get_row($SQL);
	$inst_lat=$insta_result->location_lattitude;
	$inst_lng=$insta_result->location_longitude;
	$url ='https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($pic_address) . '&sensor=true&key='.GOOGLE_API_KEY;
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, $url );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	$response = curl_exec( $ch );
   // var_dump($response);
	$coordinates = json_decode($response);
    $pic_lat = $coordinates->results[0]->geometry->location->lat;
    $pic_lng = $coordinates->results[0]->geometry->location->lng;
	$url = "https://maps.googleapis.com/maps/api/distancematrix/json?key=".GOOGLE_API_KEY."&origins=".$inst_lat.",".$inst_lng."&destinations=".$pic_lat.",".$pic_lng."&mode=driving&language=pl-PL";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			$response = curl_exec($ch);
			curl_close($ch);
			$response_a = json_decode($response, true);
			$dist = $response_a['rows'][0]['elements'][0]['distance']['text'];
			$dist = str_replace(' km', '', $dist);
			$dist_km = str_replace(',', '.', $dist);
			$SQL="SELECT * FROM th_installer_service_price WHERE installer_id='".$installer_id."' AND city_id='".$city_id."' AND service_data_id='".$service_id."'";
			$get_insta_price=$wpdb->get_row($SQL);
			if(count($get_insta_price)>0){
				$get_km_price=$get_insta_price;
			}else{
				$SQL="SELECT * FROM th_installer_service_price WHERE installer_id=0 AND city_id='".$city_id."' AND service_data_id='".$service_id."'";
				$get_km_price=$wpdb->get_row($SQL);
			}
			//echo '<pre>';
			//print_r($get_km_price);
			if($dist_km<=$get_km_price->upto_km){
				$km_price=$get_km_price->rate;
			}elseif($dist_km>=$get_km_price->from_km && $dist_km<=$get_km_price->to_km){
				$km=$dist_km-$get_km_price->upto_km;
				$km_price=$get_km_price->rate+($km*$get_km_price->per_km_price);
			}else{
				$km_price=500;
			}
			$returnData=array();
			$returnData['pic_address']=$pic_address;
			$returnData['currency_symbol']=get_woocommerce_currency_symbol();
			$returnData['base_price']=$get_km_price->rate;
			$returnData['per_km_price']=$get_km_price->per_km_price;
			$returnData['price']=$km_price;
			$returnData['km']=$dist_km;
			$_SESSION['price']=$km_price;
			$_SESSION['km']=$km;
			echo json_encode($returnData);
		die();
}
function supplier_price_sync_to_product($products,$supplier_id=0,$product_id=0){
		global $wpdb;
	
		foreach ($products as $key => $value) {
		    			 $SQL2="SELECT count(*) as id FROM th_supplier_products_final WHERE  supplier_id='$value->supplier_id' AND product_id='$value->product_id'";

		    				$procnt=$wpdb->get_row($SQL2);
		    				if($value->new_total_price){
							$tyre_total=$value->new_total_price;
							}else{
								$tyre_total=$value->old_total_price;
							}
				    		if($value->new_mrp){
								$mrp=$value->new_mrp;
							}else{
								$mrp=$value->old_mrp;
							}
							if($value->new_tube_price){
					   			$tube_price=$value->new_tube_price;
					   		}else{
					   			$tube_price=$value->old_tube_price;
					   		}
					   		if($value->new_tyre_price){
					   			$tyre_price=$value->new_tyre_price;
					   		}else{
					   			$tyre_price=$value->old_tyre_price;
					   		}
					   		$SQLSHIV="SELECT spf.* FROM `th_supplier_products_final` as spf LEFT JOIN th_supplier_data as sd ON sd.supplier_data_id = spf.supplier_id where spf.product_id='$value->product_id' AND sd.visibility=1 AND spf.updated_date>DATE_SUB(NOW(),INTERVAL 1 YEAR)  GROUP by spf.id HAVING min(spf.tyre_price+spf.tube_price) ORDER BY (spf.tyre_price+spf.tube_price) ASC LIMIT 0,1";
		    			$productsshiv=$wpdb->get_row($SQLSHIV);
		    			$is_active_supplier=get_post_meta($productsshiv->product_id,'active_supplier',true);
			    			if($is_active_supplier ==$productsshiv->supplier_id){
			    				$log_flag='update';
			    			}else{
			    				$log_flag='insert';
			    			}
		    				$update_data['product_id']=$value->product_id;
		    				$update_data['user_id']=get_current_user_id();
		    				$update_data['supplier_id']=$value->supplier_id;
			    			$update_data['tube_price']=$tube_price;
							$update_data['tyre_price']=$tyre_price;
							$update_data['flat_percentage']=$value->flat_percentage;
							$update_data['margin_price']=$value->margin_price;
							$update_data['mrp']=$mrp;
							$update_data['total_price']=$tyre_total;
							$update_data['status']=1;
							$update_data['common_status']=1;
							$update_data['price_approved']=0;
							$update_data['visiblity']=0;
							$update_data['updated_date']=date('Y-m-d H:i:s');
							
			    			if($procnt->id > 0 ){
			    				//supplier_id='$value->supplier_id' AND product_id='$value->product_id'
			    				$wpdb->update('th_supplier_products_final',$update_data, array('supplier_id' =>$value->supplier_id,'product_id'=>$value->product_id));
			    			}else{
			    				$wpdb->insert('th_supplier_products_final',$update_data);
			    			}
			    			/*echo $wpdb->last_query;
			    			die;*/
		    			$update_data1['common_status']=2;
		    			$update_data1['status']=1;
						$update_data1['updated_date']=date('Y-m-d H:i:s');
	    				$wpdb->update('th_supplier_products_list',$update_data1, array('supplier_id' =>$value->supplier_id,'product_id'=>$value->product_id));
		    				$wpdb->update('th_supplier_products_log',
							array(
								'user_id' =>get_current_user_id(),
								'status'=>1,'updated_date'=>date('Y-m-d H:i:s')
							),array('supplier_id' =>$value->supplier_id,'product_id'=>$value->product_id)
						);
	    			}
					if($supplier_id>0 && $product_id>0){
							$SQL1="SELECT spf.* FROM `th_supplier_products_final` as spf LEFT JOIN th_supplier_data as sd ON sd.supplier_data_id=spf.supplier_id where spf.product_id='$product_id' AND spf.supplier_id='$supplier_id' AND sd.visibility=1 AND spf.updated_date>=DATE_SUB(NOW(),INTERVAL 1 YEAR)";
	    			}else{
	    				$SQL1="SELECT spf.* FROM `th_supplier_products_final` as spf LEFT JOIN th_supplier_data as sd ON sd.supplier_data_id=spf.supplier_id  where spf.product_id='$product_id' AND sd.visibility=1 AND spf.updated_date>DATE_SUB(NOW(),INTERVAL 1 YEAR)  GROUP by spf.id HAVING min(spf.tyre_price+spf.tube_price) ORDER BY (spf.tyre_price+spf.tube_price) ASC LIMIT 0,1";
	    			}

	    			$products_meta=$wpdb->get_row($SQL1);
	    			
	    			$product_id=$products_meta->product_id;
					update_post_meta($product_id,'_regular_price',$products_meta->mrp);
					update_post_meta($product_id,'_sale_price',round($products_meta->total_price));
					update_post_meta($product_id,'_price',$products_meta->mrp);
					update_post_meta($product_id,'tyre_price',$products_meta->tyre_price);
					update_post_meta($product_id,'tube_price',$products_meta->tube_price);
					update_post_meta($product_id,'active_supplier',$products_meta->supplier_id);
					if($log_flag=='update'){
						update_post_meta($product_id,'update_date',date('d-m-Y'));
						$log_flag='insert';
					}else{
						update_post_meta($product_id,'active_date',date('d-m-Y'));
						update_post_meta($product_id,'update_date',date('d-m-Y'));
					}
					$wpdb->update('th_supplier_products_final',
							array(
								'price_approved'=>1,'updated_date'=>date('Y-m-d H:i:s')
							),
							array('supplier_id'=>$products_meta->supplier_id,'product_id'=>$products_meta->product_id)
						);
					$SQLLOG="SELECT count(*) as count FROM th_supplier_products_final_log WHERE product_id='".$products_meta->product_id."' AND supplier_id='".$products_meta->supplier_id."' AND total_price='".$products_meta->total_price."' AND mrp='".$products_meta->mrp."'";
					$synclogs=$wpdb->get_row($SQLLOG);
					if($synclogs->count<=0){
						$insert_data['product_id']=$products_meta->product_id;
	    				$insert_data['user_id']=1;
	    				$insert_data['supplier_id']=$products_meta->supplier_id;
		    			$insert_data['tube_price']=$products_meta->tube_price;
						$insert_data['tyre_price']=$products_meta->tyre_price;
						$insert_data['flat_percentage']=$products_meta->flat_percentage;
						$insert_data['margin_price']=$products_meta->margin_price;
						$insert_data['mrp']=$products_meta->mrp;
						$insert_data['total_price']=$products_meta->total_price;
						$insert_data['status']=1;
						$insert_data['common_status']=1;
						$insert_data['price_approved']=1;
						$insert_data['visiblity']=1;
						$insert_data['updated_date']=date('Y-m-d H:i:s');
		    			$wpdb->insert('th_supplier_products_final_log',$insert_data);
					}
			    		//echo $wpdb->last_query;
	}
add_filter( 'woocommerce_catalog_orderby', 'bbloomer_rename_sorting_option_woocommerce_shop' );
function bbloomer_rename_sorting_option_woocommerce_shop( $options ) {
   unset($options['popularity']);
   unset($options['rating']);
   unset($options['date']);
   return $options;
}
add_action( 'restrict_manage_posts', 'wpse45436_admin_posts_filter_restrict_manage_posts' );
function wpse45436_admin_posts_filter_restrict_manage_posts(){
    global $post_type;
    if( $post_type == 'shop_order' ) {
        //change this to the list of values you want to show
        //in 'label' => 'value' format
         $current_v = isset($_GET['orderid'])? $_GET['orderid']:'';
        ?>
        <input type="text" name="orderid" value="<?=$current_v;?>" placeholder="Order ID">
        <?php
    }
}
add_action( 'pre_get_posts', 'apply_my_custom_product_filters' );
function apply_my_custom_product_filters( $query ) {
    global $pagenow;
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    if ( $query->is_admin && $pagenow == 'edit.php' && isset( $_GET['orderid'] ) && $_GET['orderid'] != '' && $_GET['post_type'] == 'shop_order' ) {
      $query->set( 'post__in',array($_GET['orderid']) );
    }
}
add_action('wpcf7_mail_sent', 'after_sent_mail');
function after_sent_mail($wpcf7)
 {
 		$form_ID = $wpcf7->id;
 		if($form_ID == 1805)
 		{
	 		$submission = WPCF7_Submission::get_instance();
	 		$posted_data = $submission->get_posted_data();
	 		$name = $posted_data['your-name'];
	 		$mobile = $posted_data['mobile'];
	 		$email = $posted_data['your-email'];
	        $message = "Thank you for getting in touch! We appreciate your interest for franchise with Tyre Hub. Our team will contact you soon. Have a great day!";
	        $message = str_replace(' ', '%20', $message);
	        sms_send_to_customer($message,$mobile);
	    }
}
require_once( get_stylesheet_directory() . '/franchisee_opportunity_form.php' );
require_once( get_stylesheet_directory() . '/franchisee_cart_ajex.php' );
add_action('wp_ajax_customer_register', 'customer_register');
add_action('wp_ajax_nopriv_customer_register', 'customer_register');
function customer_register()
{
	global $woocommerce , $wpdb;
	extract($_POST);
	$otp = rand(100000,999999);
	if ( !username_exists( $mobile_no ))
	{
		if(!email_exists($email)){
			$userdata = array (
			'user_login' =>$mobile_no,
			'user_pass' =>$mobile_no,
			'user_email' =>$email,
			'role' => 'customer',
			'user_nicename' =>$first_name.' '.$last_name,
			'first_name' =>$first_name,
			'last_name'=>$last_name,
			'display_name' =>$first_name.' '.$last_name,
			'nickname' =>$first_name.' '.$last_name,
		);
		$new_user_id = wp_insert_user( $userdata );
		update_user_meta( $new_user_id, '_active',1 );
		update_user_meta( $new_user_id, 'vehicle_type',implode(',',$vehicle_type));
		update_user_meta( $new_user_id, 'franchise_id',get_current_user_id());
		update_user_meta( $new_user_id, 'referral_type','installer');
		update_user_meta( $new_user_id, 'custom_mobile', sanitize_text_field( $mobile_no ) );
		$message = "Dear ".$result->display_name.", Thank you for Registering with tyrehub.com";
		$message = str_replace(' ', '%20', $message);
		sms_send_to_customer($message,$mobile_no);

		//$update = $wpdb->get_results("UPDATE `wp_users` SET otp = '$otp' WHERE ID = '$new_user_id'");
		$SQL="SELECT * FROM th_installer_data WHERE user_id='".get_current_user_id()."'";
		$update = $wpdb->get_results("UPDATE `th_customer_register` SET is_verify =1 WHERE user_id = '".get_current_user_id()."'");
		$insta=$wpdb->get_row($SQL);
		$wpdb->insert('th_customer_register',array (
				'user_id' => $new_user_id,
				'parent_id' => get_current_user_id(),
				'installer_id' =>$insta->installer_data_id,
				'first_name' =>$first_name,
				'last_name'=>$last_name,
				'mobile' =>$mobile_no,
				'email' =>$email,
				'campaing_name' =>'installer',
				'vehicle_type'=>implode(',',$vehicle_type)
				));
			$result = array('result' => 'ok', 'user_id' => $new_user_id );
			echo json_encode($result);
		}else{
			$result = array('result' => 'error', 'message' => 'Email already exist!');
			echo json_encode($result);
		}
	}else{
		$result = array('result' => 'error', 'message' => 'Mobile number is already registered.');
			echo json_encode($result);
	}
	die();
}
add_action('wp_ajax_customer_register_otp_verify', 'customer_register_otp_verify');
add_action('wp_ajax_nopriv_customer_register_otp_verify', 'customer_register_otp_verify');
function customer_register_otp_verify()
{
	$otp = $_POST['verify_otp'];
	$user_id = $_POST['user_id'];
	global $woocommerce , $wpdb;
	$result = $wpdb->get_row("SELECT * from `wp_users` where otp = '$otp'");
	//print_r($result);
	if($result){
		 
	 $update = $wpdb->get_results("UPDATE `th_customer_register` SET is_verify =1 WHERE user_id = '$result->ID'");
	 update_user_meta($result->ID, '_active',1);
	 $message = "Dear ".$result->display_name.", Thank you for Registering with tyrehub.com";
	 $message = str_replace(' ', '%20', $message);
	 sms_send_to_customer($message,$result->user_login);
		echo 1;
	}else{
		echo 0;
	}
die();
}
add_action('wp_ajax_customer_share_link', 'customer_share_link');
add_action('wp_ajax_nopriv_customer_share_link', 'customer_share_link');
function customer_share_link()
{
	global $woocommerce , $wpdb;
	$share_mobile = $_POST['share_mobile'];
	$SQL="SELECT * FROM th_installer_data WHERE user_id='".get_current_user_id()."'";
    $insta=$wpdb->get_row($SQL);
	$shareLink=site_url('create-new-account/?uid='.$insta->user_id.'&instaid='.$insta->installer_data_id);
	$shorlink=get_short_url($shareLink);
	$message = "Hurry! Register with Gujarats NO.1 Car Tyre and Service selling portal www.Tyrehub.com Claim your discount voucher worth of Rs.100. to buy any brand Tyre or Alignment and Balancing services on discounted price. To Register Click ".$shorlink;
	$message = str_replace(' ', '%20', $message);
	sms_send_to_customer($message,$share_mobile);

die();
}
require_once(get_stylesheet_directory().'/store_created_user.php' );
	add_filter( 'woocommerce_coupon_is_valid','validate_coupons_before_checkout1', 10, 2 );
	function validate_coupons_before_checkout1( $valid, $coupon ) {
		$customer_restriction_type = $coupon->get_meta( 'customer_restriction_type', true );
 	// Pre-checkout validation can be disabled using this filter.
		$validate = apply_filters( 'woocommerce_coupon_restrictions_validate_before_checkout', true );
		if ( false === $validate ) {
			return true;
		}
		// If coupon is already marked invalid, no need for further validation.
		if ( ! $valid ) {
			return false;
		}
		if (!is_user_logged_in() ) {
		    if ($customer_restriction_type=='existing') {
			// add the filter
			add_filter( 'woocommerce_coupon_error', 'filter_woocommerce_coupon_error', 10, 3 );
		    return false;
			}else{
			  return true;
			}
		} else {
		   return true;
		}
		return true;
	}
function filter_woocommerce_coupon_error( $err, $err_code, $instance ) {
			    // make filter magic happen here...
	 $err='Please login or register first to use the coupon code.';
 return $err;
};
add_action('wp_ajax_free_tyre_inspection', 'free_tyre_inspection');
add_action('wp_ajax_nopriv_free_tyre_inspection', 'free_tyre_inspection');
function free_tyre_inspection()
{
	global $woocommerce , $wpdb;
	extract($_POST);
	$wpdb->insert('th_free_tyre_inspection',array (
				'name' => $fullname,
				'mobile_number' =>$mobile,
				'vehicle_location' =>$vehiclelocation,
				'preferred_date' =>date('Y-m-d',strtotime($preferred_date)),
				'preferred_timing'=>$preferred_time,
				'status' =>1
				));
		$ch1 = curl_init();
		$message = "We have received your request for free tyre inspection, Our team will contact you soon. Thank You Tyrehub Team";
		$message = str_replace(' ', '%20', $message);
		sms_send_to_customer($message,$mobile,1);

		$message = "Dear Admin, Free Tyre Inspection New Inquiry";
		$message = str_replace(' ', '%20', $message);
		$mobile=7575041221;
		sms_send_to_customer($message,$mobile,1);

	die();
}
add_action('wp_ajax_towing_services', 'towing_services');
add_action('wp_ajax_nopriv_towing_services', 'towing_services');
function towing_services()
{
	global $woocommerce , $wpdb;
	extract($_POST);
	
	$wpdb->insert('th_towing_services',array (
				'name' => $fullname,
				'mobile_number' =>$mobile,
				'vehicle_location' =>$vehiclelocation,
				'status' =>1
				));
		/*$ch1 = curl_init();
		$message = "We have received your request for towing services, Our team will contact you soon. Thank You Tyrehub Team";
		$message = str_replace(' ', '%20', $message);
		sms_send_to_customer($message,$mobile,1);

		$message = "Dear Admin, Towing Services New Inquiry";
		$message = str_replace(' ', '%20', $message);
		$mobile=7575041221;
		sms_send_to_customer($message,$mobile,1);*/
		
		$ch1 = curl_init();
		$message = "We have received your request for tow truck services Our team will contact you soon. Thank You Tyrehub Team";
		$message = str_replace(' ', '%20', $message);
		sms_send_to_customer($message,$mobile,1);

		$message = "Dear Admin, tow truck Service Inquiry";
		$message = str_replace(' ', '%20', $message);
		$mobile=7575041221;
		sms_send_to_customer($message,$mobile,1);
		
		
		
		
	die();
}
add_action('wp_ajax_flat_tyre', 'flat_tyre');
add_action('wp_ajax_nopriv_flat_tyre', 'flat_tyre');
function flat_tyre()
{
	global $woocommerce , $wpdb;
	extract($_POST);
	$wpdb->insert('th_flat_tyre_inquiry',array (
				'name' => $fullname,
				'mobile_number' =>$mobile,
				'vehicle_location' =>$vehiclelocation,
				'type'=>$type,
				'status' =>1
				));
		$ch1 = curl_init();
		$message = "We have received your request for ".$type.", Our team will contact you soon. Thank You Tyrehub Team";
		$message = str_replace(' ', '%20', $message);
		sms_send_to_customer($message,$mobile,1);

		$message = "Dear Admin, ".$type." New Inquiry";
		$message = str_replace(' ', '%20', $message);
		$mobile=7575041221;
		sms_send_to_customer($message,$mobile,1);

	die();
}
/*Price Symbol Change - Start*/
//add_filter( 'woocommerce_currencies', 'add_inr_currency' );
//add_filter( 'woocommerce_currency_symbol', 'add_inr_currency_symbol' );
function add_inr_currency( $currencies ) {
    $currencies['INR'] = 'INR';
    return $currencies;
}
function add_inr_currency_symbol( $symbol ) {
	$currency = get_option( 'woocommerce_currency' );
	switch( $currency ) {
		case 'INR': $symbol = '<i class="fa fa-rupee"></i>'; break;
	}
	return $symbol;
}
/*Price Symbol Change - End*/
add_action('wp_ajax_offline_product_add_to_wishlist', 'offline_product_add_to_wishlist');
add_action('wp_ajax_nopriv_offline_product_add_to_wishlist', 'offline_product_add_to_wishlist');
function offline_product_add_to_wishlist()
{
	global $wpdb , $woocommerce;
	extract($_POST);
	$user_id = get_current_user_id();
	$SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
	$franchise=$wpdb->get_row($SQL);
	$rowcount=$wpdb->get_row("SELECT SUM(qty) as qty FROM  th_franchise_wishlist WHERE product_id = '$variation_id' and franchise_id = '$franchise->installer_data_id'");

	if($rowcount->qty<10){
		// delete already save installer
	$wpdb->query("DELETE from th_franchise_wishlist WHERE product_id = '$variation_id' and franchise_id = '$franchise->installer_data_id'");
	// insert new record
	$insert = $wpdb->insert('th_franchise_wishlist', array(
										'qty' =>($rowcount->qty + $quantity),
										'product_id' =>$variation_id,
										'franchise_id' =>$franchise->installer_data_id,
										'status' =>1,
										));
	
	//$session_id = WC()->session->get_customer_id();
	//echo site_url('/cart/');
	$rowcount=$wpdb->get_row("SELECT SUM(qty) as qty FROM  th_franchise_wishlist WHERE product_id = '$variation_id' and franchise_id = '$franchise->installer_data_id'");
	$qty=$rowcount->qty;
	}else{
	$qty=$rowcount->qty + 1;
	}

	echo json_encode(array('qty'=>$qty));
	//echo $rowcount->qty;
	die();
}
add_action('wp_ajax_offline_product_add_to_cart', 'offline_product_add_to_cart');
add_action('wp_ajax_nopriv_offline_product_add_to_cart', 'offline_product_add_to_cart');
function offline_product_add_to_cart()
{

	/*  code by vb check for product already in cart or not */
	global $wpdb , $woocommerce;
	extract($_POST);
	$user_id = get_current_user_id();
	$SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
	$franchise=$wpdb->get_row($SQL);
	if($woocommerce->cart->cart_contents_count > 0){		
		foreach($woocommerce->cart->get_cart() as $key => $val )
		{
			$_product = $val['data'];
			 $variation_des = $_product->get_description();
			if(!empty($variation_id)){
			$product_id=$variation_id;
			}else{
				$product_id=$product_id;
			}
			
			$tyre_type = get_post_meta($product_id,'attribute_pa_vehicle-type',true);
			if($product_id == $_product->get_id() )
			{
				$qty = $val['quantity'];
				$total_qty = $quantity + $qty;
				if($tyre_type =='two-wheeler' || $tyre_type =='three-wheeler'){
					$total_no_qty = 2;
				}else{
					$total_no_qty = 5;
				}
				if($total_qty > $total_no_qty){
					$valid ='graterpro';
					$msg ='Once Invoice can max '.$total_no_qty.' Tyre with Alignment and Balancing Services. Individual Alignment and Service is considered under separate Invoice.';
				}else{
					//$total_qty = $quantity;
					$woocommerce->cart->set_quantity($key,$total_qty); // Change quantity
					$valid ='update';
				}
				
			}else{				
				$valid ='notinsert';
				$msg ='You have multiple Tyre and Service in your cart! Please generate One Invoice par Car/Bike!';
			}
		}

	}else{
		
	
	if(!empty($variation_id)){
			$product_id=$variation_id;
		}else{
			$product_id=$product_id;
		}

		$tyre_type = get_post_meta($product_id,'attribute_pa_vehicle-type',true);
		$cart_item_data = array('offline-purchase'=>'yes','vehicle_type'=>$vehicle_type,'tyre_type'=>$tyre_type,'custom_data'=>array('vehicle_type'=>$vehicle_type),'unique_key'=>md5( microtime().rand() ));
		 //$cart_item_data['unique_key'] = md5( microtime().rand() );// unique key add by vb 02/04/2021
		if($tyre_type =='two-wheeler' || $tyre_type =='three-wheeler'){
			$total_no_qty = 2;
		}else{
			$total_no_qty = 5;
		}
			if($quantity> $total_no_qty){
				$valid ='graterpro';
				$msg ='Once Invoice can max '.$total_no_qty.' Tyre with Alignment and Balancing Services. Individual Alignment and Service is considered under separate Invoice.';	
			}else{
				
				$cart_key = WC()->cart->add_to_cart($product_id,$quantity,0,'',$cart_item_data);
				$valid ='insert';
			// delete already save installer
				
				$delete_service = $wpdb->get_results("DELETE from th_franchise_cart_item WHERE cart_item_key = '$cart_key' and customer_id = '' and order_id = ''");
		
					// insert new record
					$insert = $wpdb->insert('th_franchise_cart_item', array(
														'cart_item_key' => $cart_key,
														'customer_id' => 0,
														'product_id' =>$variation_id,
														'destination' => 1,
														'installer_id' =>$franchise->installer_data_id,
														'vehicle_id' => $vehicle_type,
														));
					$th_franchise_cart_item_services = 'th_franchise_cart_item_services';
					$SQL="DELETE from $th_franchise_cart_item_services WHERE cart_item_key = '$cart_key' and order_id = ''";
					$delete_service = $wpdb->query($SQL);
					$insert = $wpdb->insert($th_franchise_cart_item_services, array(
													   'cart_item_key' => $cart_key,
													   'customer_id' =>0,
													   'product_id' => $variation_id,
													   'vehicle_id' => $vehicle_type,
													   'service_data_id' =>1,
													   'service_name' =>'Tyre Fitment',
													   'order_id' =>0,
													   'tyre' => $quantity,
													   'rate' =>0,
													));
					
			}
	  }
	$url = site_url().'/cart/';
	echo json_encode(array('status'=>$valid,'msg'=>$msg,'redirect_url'=>$url));
	//$session_id = WC()->session->get_customer_id();
	//echo site_url('/cart/');
	die();
	
}
add_action('wp_ajax_proceed_to_checkout', 'proceed_to_checkout');
add_action('wp_ajax_nopriv_proceed_to_checkout', 'proceed_to_checkout');
function proceed_to_checkout()
{
	session_start();
	global $wpdb , $woocommerce;
	extract($_POST);
	$user = get_user_by('login',$mobile_no);
	if($user)
	{
	   $_SESSION['fran_user_id']=$user->ID;
	}else{
	  $_SESSION['fran_user_id']='';
	}
	$_SESSION['cust_type']='offline';
	$_SESSION['mobile_no']=$mobile_no;
	$data=array('user_id'=>$user->ID,'checkout'=>get_permalink( wc_get_page_id( 'checkout' ) ));
	echo json_encode($data);
	die();
}
function cart_clear(){
	global $wpdb , $woocommerce;
	foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item )
	{
		$flag=$cart_item['offline-purchase'];
	}
	if($flag!='yes' || $flag==''){
		WC()->cart->empty_cart();
	}
	
}
function cart_clear_franchise(){
	global $wpdb , $woocommerce;
	foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item )
	{
		$flag=$cart_item['offline-purchase'];
	}
	if($flag=='yes'){
		WC()->cart->empty_cart();
	}
	unset($_SESSION['cust_type']);
	unset($_SESSION['fran_user_id']);
	unset($_SESSION['mobile_no']);
}
//franchise_admin_access();
function franchise_admin_access(){
    session_start();
    $timeout = 1800; // Number of seconds until it times out.
    // Check if the timeout field exists.
    if(isset($_SESSION['admin_access'])) {
        // See if the number of seconds since the last
        // visit is larger than the timeout period.
        $duration = time() - (int)$_SESSION['admin_access'];
        if($duration > $timeout) {
            // Destroy the session and restart it.
            //session_destroy();
            //session_start();
            unset($_SESSION['admin_access']);
        }
    }
    // Update the timout field with the current time.
    //$_SESSION['timeout'] = time();
}
add_action('wp_ajax_discount_price_apply', 'discount_price_apply');
add_action('wp_ajax_nopriv_discount_price_apply', 'discount_price_apply');
function discount_price_apply()
{
	global $woocommerce,$wpdb;
	extract($_POST);
	update_post_meta(23695,'coupon_amount',$discount_price);
	WC()->cart->add_discount(sanitize_text_field('SPDCASH'));
	die();
}
add_filter( 'woocommerce_cart_totals_coupon_label', 'filter_function_name_4281', 10, 2 );
function filter_function_name_4281( $sprintf, $coupon ){
    // filter...
	if($sprintf=='Coupon: spdcash'){
		$sprintf = "Discount :";
	}
    return $sprintf;
}
add_action('wp_ajax_offline_carservices', 'offline_carservices');
add_action('wp_ajax_nopriv_offline_carservices', 'offline_carservices');
function offline_carservices(){
    global $woocommerce,$wpdb;
    extract($_POST);
   
    $SQL="SELECT * FROM th_service_data sd LEFT JOIN th_installer_service_price as isp ON sd.service_data_id=isp.service_data_id LEFT JOIN th_vehicle_type as vt ON vt.vehicle_id=isp.vehicle_id WHERE isp.vehicle_id=".$vehicle_id." AND sd.service_data_id=".$service_id." AND city_id=1";
        $service_data=$wpdb->get_row($SQL);
        $product_id   = ($service_id==4)? get_option("balancing_alignment") : get_option("car_wash");
        $quantity=1;
        $variation    = array(
            'vehicle_id' =>$vehicle_id,
            'service_data_id'  =>$service_id,
            'voucher_name'=>$service_data->service_name,
            'vehicle_name'=>$service_data->vehicle_type,
            'services_price'=>$service_data->rate,
            'offline-purchase'=>'yes'
        );
        $cart_item_data = array('offline-purchase'=>'yes');
        if($woocommerce->cart->cart_contents_count > 0){
            foreach($woocommerce->cart->get_cart() as $key => $val )
            {
   
                $_product = $val['product_id'];
                $vehicle_id_from_db = $val['variation']['vehicle_id'];
                $service_data_id_from_db = $val['variation']['service_data_id'];
                $voucher_name_db = $val['variation']['voucher_name'];   
                $vehical_name_db = $val['variation']['vehicle_name'];   
               
               
         
            }
			$total_qty = WC()->cart->get_cart_contents_count();
			   //    if($_product==$product_id && $vehicle_id_from_db==$service_id && $vehicle_id==$vehicle_id_from_db){
                    if($vehicle_id == $vehicle_id_from_db && $service_data_id_from_db == $service_id ){
                  
                    	$total_qty = $total_qty + 1;
	                    if($service_id==4){
	                		$total_no_qty=1;
	                		$serv_name='Alignment and Balancing';
	                	}else{
	                		$total_no_qty=5;
	                		$serv_name='Car Wash';
	                	}

	                   if($total_qty > $total_no_qty){
	                        $valid ='graterpro';
	                        $msg ='Once Invoice can max '.$total_no_qty.' '.$serv_name.' Services. Individual Alignment and Service is considered under separate Invoice.';
                    }else{
                        //$total_qty = $quantity;
                        $woocommerce->cart->set_quantity($key,$total_qty); // Change quantity
                        $valid ='update';
                    }
                }else{
                        $valid ='notinsert';
                        $msg ='You have multiple Tyre and Service in your cart! Please generate One Invoice par Car/Bike!';
                }
               
       
        }else{
            WC()->cart->add_to_cart($product_id, $quantity,$variation_id,$variation,$cart_item_data);
            $valid ='insert';
        }
        echo json_encode(array('status'=>$valid,'msg'=>$msg,'redirect_url'=>site_url('/cart/')));
        die();
   
   
   
}

function get_payment_method($id){
	global $wpdb , $woocommerce;
	extract($_POST);
	$user_id = get_current_user_id();
	$SQL="SELECT payment_method FROM `wp_franchises_payment_method` WHERE id='".$id."' AND status=1";
	$pmethod=$wpdb->get_row($SQL);
	return $pmethod->payment_method;
}
function myplugin_register_options_page() {
  add_options_page('Page Title', 'GST Setting', 'manage_options', 'myplugin', 'myplugin_options_page');
}
add_action('admin_menu', 'myplugin_register_options_page');
function myplugin_options_page()
{ ?>
	<div>
  <?php screen_icon(); ?>
  <h2>My Plugin Page Title</h2>
  <form method="post" >
  <?php 
  	if (isset($_POST['tyre_gst'])) {
        $value = $_POST['tyre_gst'];
        update_option('tyre_gst', $value);
    }
    if (isset($_POST['service_gst'])) {
        $value = $_POST['service_gst'];
        update_option('service_gst', $value);
    }
    //$value = get_option('awesome_text', 'hey-ho');
  		//settings_fields( 'myplugin_option_name' ); 
  	?>
  <h3>This Setting for GST Caluclation</h3>
  <p></p>
  <table>
  <tr valign="top">
  <th scope="row"><label for="tyre_gst">Tyre GST</label></th>
  <td><input type="text" id="tyre_gst" name="tyre_gst" value="<?php echo get_option('tyre_gst'); ?>" /></td>
  </tr>
  <tr valign="top">
  <th scope="row"><label for="service_gst">Service GST</label></th>
  <td><input type="text" id="service_gst" name="service_gst" value="<?php echo get_option('service_gst'); ?>" /></td>
  </tr>
  
  </table>
  <?php  submit_button(); ?>
  </form>
  </div>
  
<?php }
add_action( 'user_profile_update_errors','wooc_validate_custom_field', 10, 1 );
// or
add_action( 'woocommerce_save_account_details_errors','wooc_validate_custom_field', 10, 1 );
// with something like:
function wooc_validate_custom_field( $args )
{
    if ( isset( $_POST['gst_no'] ) ) // Your custom field
    {
        	if(empty($_POST['gst_no'])) // condition to be adapted
        	{
        		$args->add( 'error', __( 'Please enter GST Number', 'woocommerce' ),'');
        	}
    }
    if (empty($_POST['cmp_name'])) // Your custom field
    {
        $args->add( 'error', __( 'Please enter Compnay Name', 'woocommerce' ),'');
    }
    if (empty($_POST['cmp_add'])) // Your custom field
    {
        $args->add( 'error', __( 'Please enter Company Address', 'woocommerce' ),'');
    }
    if (empty($_POST['account_email'])) // Your custom field
    {
        $args->add( 'error', __( 'Please enter email Address', 'woocommerce' ),'');
    }
}
// Get cart item from guest user to Login user 
 
function callback_for_update_cart() {
    
      global $wpdb , $woocommerce;

	if (is_user_logged_in())
	{
		if( current_user_can('customer') ) {
      	  $cart = $woocommerce->cart->get_cart();
	      $current_user = get_current_user_id(); 
	      
	      foreach ( $cart as $cart_item_key => $cart_item ){ 
	          //echo "UPDATE th_cart_item_installer SET session_id= ".$current_user." WHERE cart_item_key= '".$cart_item_key."'";      
	           $wpdb->query("UPDATE th_cart_item_installer SET session_id= ".$current_user." WHERE cart_item_key= '".$cart_item_key."'");	          
	           $wpdb->query("UPDATE th_cart_item_services SET session_id= ".$current_user." WHERE cart_item_key= '".$cart_item_key."'");
	           $wpdb->query("UPDATE th_cart_item_service_voucher  SET session_id= ".$current_user." WHERE cart_item_key= '".$cart_item_key."'");
	           
	      }
	  }
	}
          
}
add_action( 'init', 'callback_for_update_cart' );
add_action( 'wp_ajax_store_walking_orders', 'store_walking_orders' );
add_action( 'wp_ajax_nopriv_store_walking_orders', 'store_walking_orders' );
function store_walking_orders() {
       
    global $woocommerce , $wpdb;
	$customer_id = get_current_user_id();
/*	$user_id = get_current_user_id();
	$SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
	$franchise=$wpdb->get_row($SQL);
	$franchise_id=$franchise->installer_data_id;*/
   	$msg = '';
   
    if( isset( $_POST['data']['page'] ) ){
        // Always sanitize the posted fields to avoid SQL injections
        $page = sanitize_text_field($_POST['data']['page']); // The page we are currently at
        //$name = sanitize_text_field($_POST['data']['th_name']); // The name of the column name we want to sort
        $name = 'billing_first_name';
        $sort = sanitize_text_field($_POST['data']['th_sort']); // The order of our sort (DESC or ASC)
        $cur_page = $page;
        $page -= 1;
        $per_page = 10; // Number of items to display per page
        $previous_btn = true;
        $next_btn = true;
        $first_btn = true;
        $last_btn = true;
        $start = $page * $per_page;
       	// The table we are querying from  
        $posts = $wpdb->prefix . "franchises_order";
       
        $where_search = '';
       
      	if(!empty( $_POST['data']['startdate']) && !empty( $_POST['data']['enddate']) )
        {
            $where_search .= " AND  DATE_FORMAT(date_completed, '%Y-%m-%d') BETWEEN '".date('Y-m-d',strtotime($_POST['data']['startdate']))."' AND '".date('Y-m-d',strtotime($_POST['data']['enddate']))."'";
        }
     	
  
        $SQL="SELECT * FROM $posts where is_deleted=0 AND customer_id = '$customer_id' $where_search ORDER BY order_id $sort LIMIT $start, $per_page";
        $all_posts = $wpdb->get_results($SQL);
       
        $SQL1="SELECT COUNT(order_id) FROM " . $posts . " where is_deleted=0 AND customer_id = '$customer_id' $where_search";
       	$count = $wpdb->get_var($SQL1);
       
       	if( $all_posts ):
            $msg .= '<table class = "table table-striped table-hover table-file-list shop_table shop_table_responsive my_account_orders">';
            $msg .= '<tr>
						<th>Order.No</th>
						<th>Store</th>
						<th>Payment</th>
						<th>Date</th>
						<th>Status</th>
						<th>Total</th>
						<th class="action">Invoice</th>
					</tr>';
           foreach( $all_posts as $key => $post ):
           	$date_done = date("d-M-Y", strtotime($post->date_completed));
           	$siturl = site_url('/thank-you/?order_id='.base64_encode($post->order_number));
           	$pdfurl= admin_url().'/admin-ajax.php?action=offline_order_pdf&document_type=customer-offline-invoice&order_ids='.$post->order_id.'&service_id='.$post->order_id.'&_wpnonce=04e74a5779';
           	$selected = '';
           	//if($post->status == 1 || $post->status == 0){ $selected = "selected"; } 
           	//if ($post->status == 2) { $selected = "selected"; } 
           	$status=($post->status==1)? 'Pending' :'Completed';
			$SQL="SELECT * FROM th_installer_data WHERE installer_data_id='".$post->franchise_id."' AND is_franchise='yes'";
			$franchise=$wpdb->get_row($SQL);
			$SQL="SELECT * FROM wp_franchises_payment_method WHERE id='".$post->payment_method."'";
			$pay_method=$wpdb->get_row($SQL);
                $msg .= '
                <tr class="order">
                    <td>' .$post->order_number.'</a></td>
                    <td>' .$franchise->business_name.'</td>
                    <td>' .$pay_method->payment_method.'</td>
                    <td>' .$date_done.'</td>
                    <td>'.$status.'</td>
                    <td>' .$post->total.'</td>
                    <td class="action"><a href="'.$pdfurl.'" target="_blank">
								<i class="fa fa-file-pdf-o" aria-hidden="true"></i>
								</a>
								</td>
                </tr>';        
            endforeach;
         	$msg .= '</table>';
       	// If the query returns nothing, we throw an error message
        else:
            $msg .= '<p class = "bg-danger">No posts matching your search criteria were found.</p>';
        endif;
		$msg = "<div class='cvf-universal-content'>" . $msg . "</div><br class = 'clear' />";
       	$no_of_paginations = ceil($count / $per_page);
		if ($cur_page >= 7) {
            $start_loop = $cur_page - 3;
            if ($no_of_paginations > $cur_page + 3)
                $end_loop = $cur_page + 3;
            else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) {
                $start_loop = $no_of_paginations - 6;
                $end_loop = $no_of_paginations;
            } else {
                $end_loop = $no_of_paginations;
            }
        } else {
            $start_loop = 1;
            if ($no_of_paginations > 7)
                $end_loop = 7;
            else
                $end_loop = $no_of_paginations;
        }
        if($count > 10) {
        $pag_container .= "
        <div class='cvf-universal-pagination'>
            <ul>";
        if ($first_btn && $cur_page > 1) {
            $pag_container .= "<li p='1' class='active'>First</li>";
        } else if ($first_btn) {
            $pag_container .= "<li p='1' class='inactive'>First</li>";
        }
        if ($previous_btn && $cur_page > 1) {
            $pre = $cur_page - 1;
            $pag_container .= "<li p='$pre' class='active'>Previous</li>";
        } else if ($previous_btn) {
            $pag_container .= "<li class='inactive'>Previous</li>";
        }
        for ($i = $start_loop; $i <= $end_loop; $i++) {
            if ($cur_page == $i)
                $pag_container .= "<li p='$i' class = 'selected' >{$i}</li>";
            else
                $pag_container .= "<li p='$i' class='active'>{$i}</li>";
        }
       
        if ($next_btn && $cur_page < $no_of_paginations) {
            $nex = $cur_page + 1;
            $pag_container .= "<li p='$nex' class='active'>Next</li>";
        } else if ($next_btn) {
            $pag_container .= "<li class='inactive'>Next</li>";
        }
        if ($last_btn && $cur_page < $no_of_paginations) {
            $pag_container .= "<li p='$no_of_paginations' class='active'>Last</li>";
        } else if ($last_btn) {
            $pag_container .= "<li p='$no_of_paginations' class='inactive'>Last</li>";
        }
        $pag_container = $pag_container . "
            </ul>
        </div>";
    }
       
        echo
        '<div class = "cvf-pagination-content">' . $msg . '</div>' .
        '<div class = "cvf-pagination-nav">' . $pag_container . '</div>';
       }
  	   exit();
 }
/* add_filter( 'wpo_wcpdf_tmp_path', function( $tmp_base ) {
	if ( class_exists('S3_Uploads') && is_callable( array( 'S3_Uploads','get_instance' ) ) ) {
		// unregister S3 path override filters
		S3_Uploads::get_instance()->tear_down();
		// get unfiltered path
		$upload_dir = wp_upload_dir();
		if (!empty($upload_dir['error'])) {
			$tmp_base = false;
		} else {
			$upload_base = trailingslashit( $upload_dir['basedir'] );
			$tmp_base = $upload_base . 'wpo_wcpdf/';
		}
		// reboot S3 filters
		S3_Uploads::get_instance()->setup();
	}
	return $tmp_base;
} );*/

add_action('wp_ajax_car_details_save', 'car_details_save');
add_action('wp_ajax_nopriv_car_details_save', 'car_details_save');
function car_details_save()
{
	global $woocommerce , $wpdb;	
		extract($_POST);
	$table = 'th_vehicle_details';
	$data = array('order_id' =>$order_id,
	 'user_id' => $user_id,
	 'make' => $make,
	 'model' =>$model,
	 'submodel' =>$sub_modal,
	 'car_number' => $car_number,
	 'insert_date' => date('Y-m-d'));
	$wpdb->insert($table,$data);
	$my_id = $wpdb->insert_id;

	echo $my_id;
	die();
}
add_action('wp_ajax_car_details_page_save', 'car_details_page_save');
add_action('wp_ajax_nopriv_car_details_page_save', 'car_details_page_save');
function car_details_page_save()
{
	global $woocommerce , $wpdb;	
		extract($_POST);
	$table = 'th_vehicle_details';

	$SQL="SELECT * FROM th_vehicle_details WHERE order_id='".$order_id."'";
	$vehicle=$wpdb->get_row($SQL);
	if($vehicle){
		$data = array('order_id' =>$order_id,
		 'user_id' => $user_id,
		 'make' => $make,
		 'model' =>$model,
		 'submodel' =>$sub_modal,
		 'car_number' => $car_number,
		 'odo_meter' => $odo_meter,
		 'insert_date' => date('Y-m-d'));
		$wpdb->update($table,$data,array('order_id' => $order_id));
		$my_id = $vehicle->id;
	}else{
		$data = array('order_id' =>$order_id,
		 'user_id' => $user_id,
		 'make' => $make,
		 'model' =>$model,
		 'submodel' =>$sub_modal,
		 'car_number' => $car_number,
		 'odo_meter' => $odo_meter,
		 'insert_date' => date('Y-m-d'));
		$wpdb->insert($table,$data);
		$my_id = $wpdb->insert_id;
	}
	
	$SQL="SELECT * FROM th_vehicle_tyre_information WHERE order_id='".$order_id."'";
	$tyreInfo=$wpdb->get_results($SQL);
	if($tyreInfo){
		foreach ($serial_number as $key => $value) {
			$data = array(
			 'vehicle_details_id' =>$my_id,
			 'order_id' =>$order_id,
			 'user_id' =>$user_id,
			 'serial_number' => $value,
			 'insert_date' => date('Y-m-d'));
			$wpdb->update('th_vehicle_tyre_information',$data,array('id' =>$tyre_info_id[$key]));
			
		}		
	}else{
		foreach ($serial_number as $key => $value) {
		$data = array(
		 'vehicle_details_id' =>$my_id,
		 'order_id' => $order_id,
		 'user_id' =>$user_id,
		 'serial_number' =>$value,
		 'insert_date' => date('Y-m-d'));
		$wpdb->insert('th_vehicle_tyre_information',$data);
		}
	}
	//echo $wpdb->last_query;
echo 1;
	die();
}
use WPO\WC\PDF_Invoices\Compatibility\Order as WCX_Order;
add_filter( 'woocommerce_email_attachments', 'woocommerce_emails_attach_services_voucher', 10, 3 );
function woocommerce_emails_attach_services_voucher ( $attachments, $email_id, $order ) {
		// check if all variables properly set
		if ( !is_object( $order ) || !isset( $email_id ) ) {
			return $attachments;
		}

		// Skip User emails
		if ( get_class( $order ) == 'WP_User' ) {
			return $attachments;
		}

		$order_id = WCX_Order::get_id( $order );

		if ( get_class( $order ) !== 'WC_Order' && $order_id == false ) {
			return $attachments;
		}

		// WooCommerce Booking compatibility
		if ( get_post_type( $order_id ) == 'wc_booking' && isset($order->order) ) {
			// $order is actually a WC_Booking object!
			$order = $order->order;
		}

		// do not process low stock notifications, user emails etc!
		if ( in_array( $email_id, array( 'no_stock', 'low_stock', 'backorder', 'customer_new_account', 'customer_reset_password' ) ) || get_post_type( $order_id ) != 'shop_order' ) {
			return $attachments;
		}

		//$tmp_path = get_tmp_path('attachments');

		$upload_dir = wp_upload_dir();
		if (!empty($upload_dir['error'])) {
			$tmp_base = false;
		} else {
			$upload_base = trailingslashit( $upload_dir['basedir'] );
			$tmp_base = $upload_base . 'wpo_wcpdf/';
		}

		$tmp_path = $tmp_base . 'attachments/';
		

		// clear pdf files from temp folder (from http://stackoverflow.com/a/13468943/1446634)
		// array_map('unlink', ( glob( $tmp_path.'*.pdf' ) ? glob( $tmp_path.'*.pdf' ) : array() ) );

		// disable deprecation notices during email sending
		add_filter( 'wcpdf_disable_deprecation_notices', '__return_true' );

		// reload translations because WC may have switched to site locale (by setting the plugin_locale filter to site locale in wc_switch_to_site_locale())
		WPO_WCPDF()->translations();

		//$attach_to_document_types = get_documents_for_email( $email_id, $order );
		$attach_to_document_types[]='invoice';
		
		foreach ( $attach_to_document_types as $document_type ) {
			do_action( 'wpo_wcpdf_before_attachment_creation', $order, $email_id, $document_type );

			try {
				// prepare document
				$document = wcpdf_get_document( $document_type, (array) $order_id, true );
				if ( !$document ) { // something went wrong, continue trying with other documents
					continue;
				}

				 global $woocommerce,$wpdb;
			     $user_id = get_current_user_id();
			     $SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
			     $franchise=$wpdb->get_row($SQL);
				     if(empty( $franchise)){
				     	
						$pdf_data1 = $document->get_pdf1();
						$filename1 = $document->get_filename1();
						$pdf_path1 = $tmp_path . $filename1;
						file_put_contents ( $pdf_path1, $pdf_data1 );
						$attachments[] = $pdf_path1;
						do_action( 'wpo_wcpdf_email_attachment', $pdf_path1, $document_type, $document );
				     }
				

			} catch ( \Exception $e ) {
				wcpdf_log_error( $e->getMessage(), 'critical', $e );
				continue;
			} catch ( \Dompdf\Exception $e ) {
				wcpdf_log_error( 'DOMPDF exception: '.$e->getMessage(), 'critical', $e );
				continue;
			} catch ( \Error $e ) {
				wcpdf_log_error( $e->getMessage(), 'critical', $e );
				continue;
			}
		}

		remove_filter( 'wcpdf_disable_deprecation_notices', '__return_true' );

		return $attachments;
	}


function wh_alter_pro_cat_desc() {
    if (is_product()) {
    	global $post, $product;

    	$tyre_type = $product->get_attribute( 'pa_tyre-type');
    	$pa_brand = $product->get_attribute( 'pa_brand');
    	$vehicle_type = $product->get_attribute( 'pa_vehicle-type');

    	$pa_width = $product->get_attribute( 'pa_width');
    	$pa_ratio = $product->get_attribute( 'pa_ratio');
    	$pa_diameter = $product->get_attribute( 'pa_diameter');
		
		$saleprice = $product->get_price();
    	//echo'<pre>';
    	//print_r($product);
     ?>
		<title>Buy <?=$pa_brand?> <?=$pa_width?> / <?=$pa_ratio?> R<?=$pa_diameter?> <?=$vehicle_type?>  <?=$saleprice?></title>
        <meta name="description" content="Buy and Compare <?=$pa_brand?> <?=$pa_width?> / <?=$pa_ratio?> R<?=$pa_diameter?> <?=$vehicle_type?> Price with Other Tyres and Select The Best Tyre Online at TyreHub at The Best Price">
        <?php
    }
    if (is_shop()) {
    	
    	$pa_brand=$_GET['filter_brand'];
    	
    	//echo'<pre>';
    	//print_r($product);
     ?>
		<title><?=$pa_brand?> Tyres | Buy <?=$pa_brand?> Tyres at the Best Price</title>
        <meta name="description" content="<?=$pa_brand?> Tyre Prices | Buy <?=$pa_brand?> Tyres Online on The Best Prices and Compare with Different Activa, Bike and Car Tyre Prices to Choose Among Our Various Models">
        <?php
    }
     
}
add_action('wp_head', 'wh_alter_pro_cat_desc', 5);

function remove_yoast_metabox_product(){
    remove_meta_box('wpseo_meta', 'product', 'normal');
}
add_action( 'add_meta_boxes', 'remove_yoast_metabox_product',11 );

add_action('wp_ajax_offline_car_details_save', 'offline_car_details_save');
add_action('wp_ajax_nopriv_offline_car_details_save', 'offline_car_details_save');
function offline_car_details_save()
{
	global $woocommerce , $wpdb;	
	extract($_POST);

    $table_name  = $wpdb->prefix."franchises_order";
	$wpdb->query("UPDATE $table_name  SET status =2 WHERE  order_number = '".$order_id."'");
	$table = 'th_vehicle_details';

	$SQL="SELECT * FROM th_vehicle_details WHERE order_id='".$order_id."'";
	$vehicle=$wpdb->get_row($SQL);
	if($vehicle){
		$data = array('order_id' =>$order_id,'product_id' =>$product_id,
		 'user_id' => $user_id,
		 'services_id' => $services_id,
		 'make' => $make,
		 'model' =>$model,
		 'submodel' =>$sub_modal,
		 'car_number' => $car_number,
		 'odo_meter' => $odo_meter,
		 'franchise_id' => $franchise_id,
		 'order_type' =>1,
		 'insert_date' => date('Y-m-d'));
		$wpdb->update($table,$data,array('order_id' => $order_id,'product_id' =>$product_id));
		$my_id = $vehicle->id;
	}else{
		$data = array('order_id' =>$order_id,'product_id' =>$product_id,
		 'user_id' => $user_id,
		 'services_id' => $services_id,
		 'make' => $make,
		 'model' =>$model,
		 'submodel' =>$sub_modal,
		 'car_number' => $car_number,
		 'odo_meter' => $odo_meter,
		 'franchise_id' => $franchise_id,
		 'order_type' =>1,
		 'insert_date' => date('Y-m-d'));
		$wpdb->insert($table,$data);
		$my_id = $wpdb->insert_id;
	}
	
	$SQL="SELECT * FROM th_vehicle_tyre_information WHERE order_id='".$order_id."'";
	$tyreInfo=$wpdb->get_results($SQL);
	if($tyreInfo){
		foreach ($serial_number as $key => $value) {
			$data = array(
			 'vehicle_details_id' =>$my_id,
			 'order_id' =>$order_id,
			 'user_id' =>$user_id,
			 'serial_number' => $value,
			 'insert_date' => date('Y-m-d'));
			$wpdb->update('th_vehicle_tyre_information',$data,array('id' =>$tyre_info_id[$key]));
			
		}		
	}else{
		foreach ($serial_number as $key => $value) {
		$data = array(
		 'vehicle_details_id' =>$my_id,
		 'order_id' => $order_id,
		 'user_id' =>$user_id,
		 'serial_number' =>$value,
		 'insert_date' => date('Y-m-d'));
		$wpdb->insert('th_vehicle_tyre_information',$data);
		}
	}

	$SQL="SELECT * FROM $table_name  WHERE  order_number = '".$order_id."'";
	$GetRes=$wpdb->get_row($SQL);
	offline_order_mail_and_invoice_send($GetRes->order_id);

	//echo $wpdb->last_query;
	die();
}


function offline_order_mail_and_invoice_send($order_id){
	global $woocommerce , $wpdb;
		date_default_timezone_set('Asia/Kolkata');

		global $woocommerce,$wpdb;
	     $user_id = get_current_user_id();
	     //$SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."'";
	     $SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
	     $franchise=$wpdb->get_row($SQL);

		$franchise_id = $franchise->installer_data_id;
		
		$serviceTotal=0;

		 $SQL="SELECT SUM(rate) as servi_total FROM `th_franchise_cart_item_services` WHERE order_id='".$order_id."'";
		 $add_servi=$wpdb->get_row($SQL);
		 $serviceTotal=$serviceTotal + $add_servi->servi_total;

		//echo "<pre>";
		//$items = WC()->cart->get_cart();
		//$subtotal = WC()->cart->subtotal + $serviceTotal;
		//$total = WC()->cart->total + $serviceTotal;
		//print_r($total);
		$table = $wpdb->prefix.'franchises_order';
		$table2 = $wpdb->prefix.'franchise_order_items';
		$table3 = $wpdb->prefix.'francise_order_itemmeta';

		
		$SQL="SELECT * FROM $table  WHERE order_id='$order_id'";
		$row=$wpdb->get_row($SQL);



			$ch1 = curl_init();			
			$upload_dir = wp_get_upload_dir();			
			$upload_base = trailingslashit($upload_dir['baseurl']);			
			$big_invo_url = $upload_base.'wpo_wcpdf/attachments/offline-invoice-'.$row->order_number.'.pdf';

			$invo_short_url = get_short_url($big_invo_url);

	$message = "Confirmed: Order is successfully completed, your order number: ".$row->order_number.", you will receive the Invoice in your email. You can also request a printed copy at our Frenchies Counter and your invoice download click this link ".$invo_short_url.". If you need more help call to 18002335551";

	
			$message = trim(preg_replace('/\s+/', ' ', $message));
			$message = str_replace( array('&'), 'and', $message);
			$message = str_replace(' ', '%20', $message);
			$mobile=$row->billing_phone;
			sms_send_to_customer($message,$mobile,1);

			$to1 = $row->billing_email;
			$from1 = 'sales@tyrehub.com';
			$subject1 = 'Tyrehub Place order';
		   

					$order_number=$row->order_number;
					
					$limit =1;
					$user_id = get_current_user_id();
				    $SQL="SELECT * FROM th_installer_data WHERE user_id='".$user_id."' AND is_franchise='yes'";
				    $franchise=$wpdb->get_row($SQL);
					$franchise_id = $franchise->installer_data_id;
					$row = $wpdb->get_row("SELECT *, foi.order_item_id as itemid FROM wp_franchises_order as fo,wp_franchise_order_items as foi  where fo.order_id = foi.order_id AND fo.order_number='$order_number' AND franchise_id = '$franchise_id' ORDER BY fo.order_id DESC LIMIT 0,$limit");

					$SQL="SELECT * FROM wp_franchises_payment_method WHERE id='$row->payment_method'";
						$payment=$wpdb->get_row($SQL);
						$payment_title=$payment->payment_method;

					$od_meta_id = $row->itemid;
					$od_order_id = $row->order_id;
					$total = $row->total;
					$payment_method = $row->payment_method;
					

					$SQL="SELECT * FROM wp_franchise_order_items as oi, wp_francise_order_itemmeta as om where oi.order_id = '$od_order_id' and om.order_item_id = oi.order_item_id";

					$order_meta_product = $wpdb->get_results($SQL);
					$p_count = count($order_meta_product);

					$product_array = array();
					$qty_array = array();
						foreach ($order_meta_product as $key => $value) {

						if($value->meta_key == '_product_id')
						{
							$product_array[$value->order_item_id]['product_id'] = $value->meta_value;
						}
						if($value->meta_key == '_qty')
						{
							$product_array[$value->order_item_id]['qty'] = $value->meta_value;
						}
						if($value->meta_key == '_line_subtotal')
						{
							$product_array[$value->order_item_id]['_line_subtotal'] = $value->meta_value;
						}
						if($value->meta_key == '_line_total')
						{
							$product_array[$value->order_item_id]['_line_total'] = $value->meta_value;
						}
						if($value->meta_key == '_sgst')
						{
							$product_array[$value->order_item_id]['_sgst'] = $value->meta_value;
						}
						if($value->meta_key == '_cgst')
						{
							$product_array[$value->order_item_id]['_cgst'] = $value->meta_value;
						}

					}
					
					$product_array = array_values($product_array);

				$SQL="SELECT * FROM th_franchise_cart_item_services WHERE order_id='".$order_number."'";
				$services=$wpdb->get_results($SQL);
				ob_start();
				 
 				$headers = "From: Tyrehub Completed your order <sales@tyrehub.com>" . "\r\n";
 				$headers .= "MIME-Version: 1.0\r\n";
        		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

				 include(get_stylesheet_directory() . '/templates/offline_email_template-completed.php'); //Template File Path

				 	$document_type = sanitize_text_field('offline-invoice');
				    $local_order=$order_number;
				 	$order_ids = (array) array_map( 'absint', explode( 'x', $local_order ) );
				    $order_ids = array_reverse( $order_ids );
				    $filename=$document_type.'-'.$local_order.'.pdf';
    
			   try {
			            $document = wcpdf_get_document( $document_type, $order_ids, true );

			            if ( $document ) {
			                $output_format = WPO_WCPDF()->settings->get_output_format( $document_type );

			                switch ( $output_format ) {
			                    case 'html':
			                        add_filter( 'wpo_wcpdf_use_path', '__return_false' );
			                        $document->output_html();
			                        break;
			                    case 'pdf':

			                    default:
			                        if ( has_action( 'wpo_wcpdf_created_manually' ) ) {
			                            do_action( 'wpo_wcpdf_created_manually', $document->get_pdf(), $filename);
			                        }
			                        $output_mode = WPO_WCPDF()->settings->get_output_mode($document_type);
			                        $output_mode ='inline';
			                        $upload_dir = wp_upload_dir();
			                        $upload_base = trailingslashit( $upload_dir['basedir'] );
			                        $tmp_base = $upload_base . 'wpo_wcpdf/attachments/';

			                        $tmp_path = $tmp_base;
			                        // get pdf data & store
			                        $pdf_data = $document->get_pdf();
			                        $filename = $filename;
			                       $pdf_path = $tmp_path . $filename;
			                       
			                        file_put_contents ($pdf_path, $pdf_data );

			                       //$document->output_offline_pdf($output_mode,$filename);

			                }
			            } else {
			                wp_die( sprintf( __( "Document of type '%s' for the selected order(s) could not be generated", 'woocommerce-pdf-invoices-packing-slips' ), $document_type ) );
			            }
			        } catch ( \Dompdf\Exception $e ) {
			            $message = 'DOMPDF Exception: '.$e->getMessage();
			            wcpdf_log_error( $message, 'critical', $e );
			            wcpdf_output_error( $message, 'critical', $e );
			        } catch ( \Exception $e ) {
			            $message = 'Exception: '.$e->getMessage();
			            wcpdf_log_error( $message, 'critical', $e );
			            wcpdf_output_error( $message, 'critical', $e );
			        } catch ( \Error $e ) {
			            $message = 'Fatal error: '.$e->getMessage();
			            wcpdf_log_error( $message, 'critical', $e );
			            wcpdf_output_error( $message, 'critical', $e );
			        }
      

					$upload_dir = wp_get_upload_dir();			
					$upload_base = trailingslashit($upload_dir['basedir']);
			
				 $tmp_base = $upload_base.'wpo_wcpdf/attachments/offline-invoice-'.$order_number.'.pdf';

				 $attachments = array($tmp_base);
				 ob_get_contents();
				 ob_end_clean();

				 //wp_mail($to1,$subject1,$message1,$headers);
				 wp_mail($to1,$subject1,$message1,$headers,$attachments);
			
			
}


add_action('wp_ajax_organic_product_details_add_to_cart', 'organic_product_details_add_to_cart');
add_action('wp_ajax_nopriv_organic_product_details_add_to_cart', 'organic_product_details_add_to_cart');
function organic_product_details_add_to_cart()
{
	global $wpdb , $woocommerce;
	extract($_POST);
	if($woocommerce->cart->cart_contents_count > 0){		
		foreach($woocommerce->cart->get_cart() as $key => $val )
		{
			$_product = $val['data'];
			 $variation_des = $_product->get_description();
			if(!empty($variation_id)){
			$product_id=$variation_id;
			}else{
				$product_id=$product_id;
			}
			if($product_id == $_product->get_id() )
			{
				$qty = $val['quantity'];
				$total_qty = $quantity + $qty;
				if($vehicle_type == "two-wheeler" || $two_wheel == "two-wheeler"){
					$total_no_qty = 2;
					$msgDesc='Once Invoice can max '.$total_no_qty.' Tyres.';
				}else{
					$total_no_qty = 5;
					$msgDesc ='Once Invoice can max '.$total_no_qty.' Tyre with Alignment and Balancing Services. Individual Alignment and Service is considered under separate Invoice.';
				}

				if($total_qty > $total_no_qty){
					$valid ='graterpro';
					$msg =$msgDesc;
				}else{
					//$total_qty = $quantity;
					$woocommerce->cart->set_quantity($key,$total_qty); // Change quantity
					$valid ='update';
				}
				
			}else{				
				$valid ='notinsert';
				$msg ='You have multiple Tyre and Service in your cart! Please generate One Invoice par Car/Bike!';
			}
		}

	}else{
		$cart_item_data = array('vehicle_type'=>$vehicle_type,'custom_data'=>array('vehicle_type'=>$vehicle_type));
		if(!empty($variation_id)){
			$product_id=$variation_id;
		}else{
			$product_id=$product_id;
		}
		$cart_key = WC()->cart->add_to_cart($product_id,$quantity,0,'',$cart_item_data);
		$valid ='insert';
	}
	foreach($woocommerce->cart->get_cart() as $key => $val )
		{
		$url = get_site_url().'/online-tyre-services-partner/?product_id='.$product_id.'&cart_item_id='.$key.'&total_qty='.$val['quantity'];
		}
	
	echo json_encode(array('status'=>$valid,'msg'=>$msg,'redirect_url'=>$url));
	//$session_id = WC()->session->get_customer_id();
	//echo site_url('/cart/');
	die();
}


add_action('wp_ajax_organic_product_add_to_cart', 'organic_product_add_to_cart');
add_action('wp_ajax_nopriv_organic_product_add_to_cart', 'organic_product_add_to_cart');
function organic_product_add_to_cart()
{
	global $wpdb , $woocommerce;
	extract($_POST);
	if($woocommerce->cart->cart_contents_count > 0){		
		foreach($woocommerce->cart->get_cart() as $key => $val )
		{
			$_product = $val['data'];
			 $variation_des = $_product->get_description();
			if(!empty($variation_id)){
			$product_id=$variation_id;
			}else{
				$product_id=$product_id;
			}
			if($product_id == $_product->get_id() )
			{
				$qty = $val['quantity'];
				$total_qty = $quantity + $qty;
				if($vehicle_type == "two-wheeler"){
					$total_no_qty = 2;
					$msgDesc='Once Invoice can max '.$total_no_qty.' Tyres.';
				}else{
					$total_no_qty = 5;
					$msgDesc ='Once Invoice can max '.$total_no_qty.' Tyre with Alignment and Balancing Services. Individual Alignment and Service is considered under separate Invoice.';
				}
				if($total_qty > $total_no_qty){
					$valid ='graterpro';
					$msg =$msgDesc;
				}else{
					//$total_qty = $quantity;
					$woocommerce->cart->set_quantity($key,$total_qty); // Change quantity
					$valid ='update';
				}
				
			}else{				
				$valid ='notinsert';
				$msg ='You have multiple Tyre and Service in your cart! Please generate One Invoice par Car/Bike!';
			}
		}

	}else{
		
		if(!empty($variation_id)){
			$product_id=$variation_id;
		}else{
			$product_id=$product_id;
		}
		$tyre_type = get_post_meta($product_id,'attribute_pa_vehicle-type',true);
		$cart_item_data = array('tyre_type'=>$tyre_type,'vehicle_type'=>$vehicle_type,'custom_data'=>array('vehicle_type'=>$vehicle_type));
		$cart_key = WC()->cart->add_to_cart($product_id,$quantity,0,'',$cart_item_data);
		$valid ='insert';
	}
	foreach($woocommerce->cart->get_cart() as $key => $val )
		{
		$url = get_site_url().'/online-tyre-services-partner/?product_id='.$product_id.'&cart_item_id='.$key.'&total_qty='.$val['quantity'];
		}
	
	echo json_encode(array('status'=>$valid,'msg'=>$msg,'redirect_url'=>$url));
	//$session_id = WC()->session->get_customer_id();
	//echo site_url('/cart/');
	die();
}

add_action('wp_ajax_organic_services_check_in_cart', 'organic_services_check_in_cart');
add_action('wp_ajax_nopriv_organic_services_check_in_cart', 'organic_services_check_in_cart');
function organic_services_check_in_cart()
{
	global $wpdb , $woocommerce;
	extract($_POST);
	$product_id   = ($vehicle_type==4)? get_option("balancing_alignment") : get_option("car_wash");
	if(	$woocommerce->cart->cart_contents_count > 0){
		
		foreach($woocommerce->cart->get_cart() as $key => $val )
		{
				$_product = $val['product_id'];
                $vehicle_id_from_db = $val['custom_data']['vehicle_type'];
                $service_data_id_from_db = $val['services_name'];
                $SQL="SELECT * FROM th_cart_item_service_voucher WHERE cart_item_key='$key' AND service_data_id='$service_data_id_from_db'";
                $getVouchr=$wpdb->get_row($SQL);
                $voucher_name_db =$getVouchr->voucher_name;  

				$SQL1="SELECT * FROM th_vehicle_type WHERE vehicle_id='$vehicle_id_from_db'";
                $getVehicl=$wpdb->get_row($SQL1);
                $voucher_name_db =$getVehicl->vehicle_type;

                $vehical_name_db = $val['variation']['vehicle_name'];


                 //    if($_product==$product_id && $vehicle_id_from_db==$service_id && $vehicle_id==$vehicle_id_from_db){
                    		
		}	
			$total_qty = $woocommerce->cart->cart_contents_count;
			if($vehicle_type == $vehicle_id_from_db && $service_data_id_from_db == $service_id ){
            	//$qty = $val['quantity'];
            	$total_qty = $total_qty + 1;
                if($service_id == 4){
					$total_no_qty = 1;
				}else{
					$total_no_qty = 5;
				}
				
				if($total_no_qty < $total_qty){
                    $valid ='graterpro';
                    $msg ='Once Invoice can max '.$total_no_qty.' Tyre with Alignment and Balancing Services. Individual Alignment and Service is considered under separate Invoice.';
                }else{
                    //$total_qty = $total_qty+1;
                    //$woocommerce->cart->set_quantity($key,$total_qty); // Change quantity
                    $valid ='insert';
                }
            }else{
                    $valid ='notinsert';
                    $msg ='You have multiple Tyre and Service in your cart! Please generate One Invoice par Car/Bike!';
            }

	}else{		
		$valid ='insert';
	}
	
	echo json_encode(array('status'=>$valid,'msg'=>$msg,'redirect_url'=>''));
	
	die();
}
add_filter( 'woocommerce_checkout_fields', 'bbloomer_checkout_fields_custom_attributes', 9999 );
 
function bbloomer_checkout_fields_custom_attributes( $fields ) {
   $fields['billing']['billing_first_name']['custom_attributes']['maxlength'] = 30;
   $fields['billing']['billing_last_name']['custom_attributes']['maxlength'] = 30;
   return $fields;
}
//SMS send Function (nimbus API)
function sms_send_to_customer($message,$mobile,$templateID=1){

		$ch1 = curl_init();
       $url_string="http://nimbusit.co.in/api/swsend.asp?username=t1ankitshah&password=81957032&sender=TYREHB&templateID=".$templateID."&sendto=91".$mobile."&message=".$message;
        curl_setopt($ch1, CURLOPT_URL, $url_string);
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch1, CURLOPT_CUSTOMREQUEST, "GET");
        $result1 = curl_exec($ch1);
        curl_close ($ch1);
}

	
add_action( 'add_meta_boxes_shop_order', 'add_meta_boxes_in_order_vehicle_info');

function add_meta_boxes_in_order_vehicle_info() {
		

		// make model admin side
		add_meta_box(
			'vehicle-information-data-input-box',
			__( 'Vehicle Information', 'woocommerce-pdf-invoices-packing-slips' ),'data_input_box_content_vehicle_info',
			'shop_order',
			'normal',
			'default'
		);

	}
function data_input_box_content_vehicle_info ( $post ) {
		global $wpdb , $woocommerce;
		$order = wc_get_order($post->ID);
		// The loop to get the order items which are WC_Order_Item_Product objects since WC 3+
		foreach( $order->get_items() as $item_id => $item ){
		    
		    if($item['variation_id'] != ''){
		            $product_id = $item['variation_id'];
		         }
		         else{
		            $product_id = $item['product_id'];
		         }
		   $item_quantity = $order->get_item_meta($item_id, '_qty', true);
		}
		
                     //  echo $item_id;
        $vehicle_type=get_post_meta($product_id, 'attribute_pa_vehicle-type', true );
        if($vehicle_type!='car-tyre'){
            $title='Please enter bike details';
            $numbtitle='Bike Number';
            $vehicletype=2;
        }else{
            $title='Please enter car details';
            $numbtitle='Car Number';
            $vehicletype=1;
        }
        
        $SQL="SELECT * FROM th_vehicle_details WHERE order_type=0 AND order_id='".$post->ID."'";
		$vehicle_details=$wpdb->get_row($SQL);
		

		if ($invoice = wcpdf_get_invoice( $order ) ) {
			
			?>
			            <p>Please fill out the car detail for which you are installing this Tyre,  the provided car detail will be registered for Tyre Guarantee and warranty purpose.</p>
						
						<?php if($vehicle_details->make!='' && $vehicle_details->model!='' && $vehicle_details->submodel!=''){?>
						<p> 
							<input type="text" name="make_name" value="<?php echo make_name($vehicle_details->make);?>" />
							<input type="text" name="model_name" value="<?php echo model_name($vehicle_details->make,$vehicle_details->model);?>" />
							<input type="text" name="sub_model_name" value="<?php echo sub_model_name($vehicle_details->model,$vehicle_details->submodel);?>" />
							
							<input type="hidden" name="select-car-cmp" value="<?=$vehicle_details->make;?>" />
							<input type="hidden" name="select1" value="<?=$vehicle_details->model;?>" />
							<input type="hidden" name="select3" value="<?=$vehicle_details->submodel;?>" />
						</p>
						<?php }else{?>
			            <p class="form-field form-field-wide">
			            	<select name="select-car-cmp" class="input-custom select-car-cmp" required>
                                        <option value="" disabled selected="">Make</option>
                                    <?php                                   
                                    $make_data = $wpdb->get_results("SELECT * FROM th_make where vehicle_type = '$vehicletype' AND status =1 order by make_name asc");
    
                                    foreach ($make_data as $data) {
                                        $make_id = $data->make_id;
                                        $make_name = $data->make_name;
                                    ?>    
                                        <option value="<?php echo $make_id; ?>" <?php if($vehicle_details->make == $make_id){ echo 'selected'; }?>><?php echo $make_name; ?></option>
                                    <?php } ?>
                            </select>
                            <select disabled="disabled" name="select1" class="input-custom select-model" required>
                                        <option value="" selected="">Model</option>
                            </select>
                            <select name="select3" disabled="disabled" class="input-custom select-sub-model" required>
                                <option value="" disabled selected>Sub Model</option>
                            </select>
			            </p>
						<?php }?>
			            <p class="form-field form-field-wide">
			            	<?=$numbtitle;?>
                            <input type="text" class="short" name="car_number" id="car_number" value="<?=$vehicle_details->car_number?>" placeholder="<?=$numbtitle;?>" maxlength="12" size="12" style="width: 20%;">
                            Odo Meter(KM)
							<input type="text" class="short" name="odo_meter" id="odo_meter" 
					 value="<?php echo $vehicle_details->odo_meter;?>" placeholder="Odo Meter(KM)" maxlength="7" size="7" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" style="width: 20%;">

                             <input type="hidden" name="product_id" value="<?=$product_id?>" id="product_id">
                             <input type="hidden" name="tyre_count" value="<?=$item_quantity?>" id="tyre_count">
			            </p>
						 <p class="form-field form-field-wide">
						 	<?php 
						 		$SQL="SELECT * FROM `th_vehicle_tyre_information` WHERE order_id='".$post->ID."'";
						 		$tyre_details= $wpdb->get_results($SQL);
						 		if($tyre_details){
						 						$j=1;
				                            foreach ($tyre_details as $key => $value) {
				                                 ?>
				                                <div class="col-md-4">
				                                    <div class="form-group ">
				                                        <label for="" class="col-form-label">Tyre <?=$j;?> Serial Number</label>
				                                        <input type="text" class="form-control input-custom serial_number" name="serial_number[]" id="serial_number_<?=$j?>" placeholder="Serial Number" value="<?=$value->serial_number?>" maxlength="4" size="4" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
				                                        <input type="hidden" name="tyre_info_id[]" value="<?=$value->id;?>" id="tyre_info_id">
				                                    </div>
				                                </div>

				                                <?php $j++;
				                            }
						 		}else{
						 			$j=1; 

			                            for ($i=0; $i < $item_quantity; $i++)  {?>
			                            <div class="col-md-4">
			                                <div class="form-group">
			                                    <label for="" class="col-form-label">Tyre <?=$j;?> Serial Number</label>
			                                    <input type="text" class="form-control input-custom serial_number" name="serial_number[]" id="serial_number_<?=$i?>" placeholder="Serial Number" maxlength="4" size="4" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
			                                </div>
			                            </div>

			                            <?php $j++; }

			                            $disabled='disabled';
						 		}
						 		
						 	?>
						 </p>
			            	
			         <script type="text/javascript">
			         	jQuery(document).on('change','.select-car-cmp',function()
						    {
						        jQuery('.select-model').html('<option value="" disabled selected>Model</option>');
						        jQuery('.select-year').html('<option value="" disabled selected>Year</option>');
						         jQuery('.select-error').css('display','none');
						        var car_cmp = jQuery(this).val();
						        var loader = "<span class='new-loader'><img src='https://tyrehub.com/loading.gif' width='20' height='20' />";
						        jQuery(".year-wrapper").append(loader);
						        jQuery.ajax({
						                    type: "POST",
						                    url: ajaxurl ,
						                    data: {
						                        action: 'select_model',
						                        car_cmp : car_cmp,
						                    },
						                    success: function (data) {
						                      jQuery('.select-model').html(data);
						                      jQuery('.select-model').removeAttr('disabled');
						                      jQuery(".year-wrapper").find(".new-loader").remove();
						                    },
						                    error: function (errorThrown) {
						                    }
						                });
						    });

			         	// When select car name dropdown
					    jQuery(document).on('change','.select-model',function(){
					        var year = jQuery(this).val();
					       var model = jQuery('.select-model').val();
					        jQuery('.select-error').css('display','none');
					        var loader = "<span class='new-loader'><img src='https://tyrehub.com/loading.gif' width='20' height='20' />";
					        jQuery(".model-wrapper").append(loader);
					        jQuery.ajax({
					                    type: "POST",
					                    url: ajaxurl,
					                    data: {
					                        action: 'select_sub_modal',
					                        model : model,
					                    },
					                    success: function (data) {
					                      jQuery('.select-sub-model').html(data);
					                      jQuery('.select-sub-model').removeAttr('disabled');
					                       jQuery(".model-wrapper").find(".new-loader").remove();
					                    },
					                    error: function (errorThrown) {
					                    }
					                });
					    });
					jQuery(document).ready(function($)
					{
						var admin_url = $('.admin_url').text();
						 var car_cmp = $('.select-car-cmp').val();
						 var model_id='<?=$vehicle_details->model;?>';
						 var sub_model_id='<?=$vehicle_details->submodel;?>';
					    $.ajax({
					            type: "POST",
					            url: ajaxurl,
					            data: {
					                action: 'select_model',
					                car_cmp : car_cmp,
					                model_id : model_id,

					            },
					            success: function (data) {
					                $('.select-model').html(data);
					                $('.select-model').removeAttr('disabled');
					                $(".year-wrapper").find(".new-loader").remove();
					                 var model = $('.select-model').val();
					                 $.ajax({
							            type: "POST",
							            url: ajaxurl,
							            data: {
							                action: 'select_sub_modal',
							                model: model_id,
							                sub_model_id: sub_model_id,
							            },
							            success: function (data) {
							              $('.select-sub-model').html(data);
							              $('.select-sub-model').removeAttr('disabled');
							               $(".model-wrapper").find(".new-loader").remove();
							            },
							            error: function (errorThrown) {
							            }
							        });
					            },
					            error: function (errorThrown) {
					            }
					        });
					    });

			         </script>  
		
			<?php
		}

	}
/* create function by vb for name of make, model,submodel */
function make_name($make_id){
	global $wpdb;
	$make_name = $wpdb->get_var("SELECT make_name FROM th_make where make_id='$make_id' and status =1");
	return $make_name;
}
function model_name($make_id,$model_id){
	global $wpdb;
	$model_name = $wpdb->get_var("SELECT model_name FROM th_model where model_id='$model_id' and make_rid='$make_id' and status =1");
	return $model_name;
}
function sub_model_name($model_id,$sub_modal_id){
	global $wpdb;
	$sub_model_name = $wpdb->get_var("SELECT submodel_name FROM th_submodel where submodel_id = '$sub_modal_id' and model_rid='$model_id' and status =1");
	return $sub_model_name;
}	
// Save the data of the Meta field
add_action( 'save_post', 'mv_save_wc_order_other_fields', 100, 1 );
if ( ! function_exists( 'mv_save_wc_order_other_fields' ) )
{

    function mv_save_wc_order_other_fields( $post_id ) {
    	global $errors,$wpdb;

        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }

        // Check the user's permissions.
        if ( 'shop_order' == $_POST[ 'post_type' ] ) {

            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return $post_id;
            }
        } else {

            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }
        }
        $order_id=$post_id;
        $product_id=$_POST['product_id'];
        $make=$_POST['select-car-cmp'];
        $model=$_POST['select1'];
        $sub_modal=$_POST['select3'];
        $car_number=$_POST['car_number'];
		$odo_meter=$_POST['odo_meter'];
		$tyre_info_id=$_POST['tyre_info_id'];
		$serial_number=$_POST['serial_number'];
       //
        $table = 'th_vehicle_details';
			$SQL="SELECT * FROM th_vehicle_details WHERE order_id='".$order_id."'";
			$vehicle=$wpdb->get_row($SQL);
			if($vehicle){
				$data = array('order_id' =>$order_id,'product_id' =>$product_id,
				 'user_id' => $user_id,
				 'make' => $make,
				 'model' =>$model,
				 'submodel' =>$sub_modal,
				 'car_number' => $car_number,
				 'odo_meter' => $odo_meter,
				 'insert_date' => date('Y-m-d'));
				
				$wpdb->update($table,$data,array('order_id' => $order_id));
				
				$my_id = $vehicle->id;
			}else{
				$data = array('order_id' =>$order_id,'product_id' =>$product_id,
				 'user_id' => $user_id,
				 'make' => $make,
				 'model' =>$model,
				 'submodel' =>$sub_modal,
				 'car_number' => $car_number,
				 'odo_meter' => $odo_meter,
				 'insert_date' => date('Y-m-d'));
				$wpdb->insert($table,$data);
				
				$my_id = $wpdb->insert_id;
			}
			
			$SQL="SELECT * FROM th_vehicle_tyre_information WHERE order_id='".$order_id."'";
			$tyreInfo=$wpdb->get_results($SQL);
			if($tyreInfo){
				foreach ($serial_number as $key => $value) {
					$data = array(
					 'vehicle_details_id' =>$my_id,
					 'order_id' =>$order_id,
					 'user_id' =>$user_id,
					 'serial_number' => $value,
					 'insert_date' => date('Y-m-d'));
					$wpdb->update('th_vehicle_tyre_information',$data,array('id' =>$tyre_info_id[$key]));
					
				}		
			}else{
				foreach ($serial_number as $key => $value) {
				$data = array(
				 'vehicle_details_id' =>$my_id,
				 'order_id' => $order_id,
				 'user_id' =>$user_id,
				 'serial_number' =>$value,
				 'insert_date' => date('Y-m-d'));
				$wpdb->insert('th_vehicle_tyre_information',$data);
				}
			}
       
    }
}

add_action( 'wp_footer', 'action_function_name_248' );
function action_function_name_248(){?>
	<div class="modal" id="duplicate_product" role="dialog">
    <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content"  style="pointer-events: auto;">
         <div class="modal-header">       
          </div>
        <div class="modal-body">
            <p id="pro_msg"></p>                               
        </div>
        <div class="modal-footer">
            <a href="<?php echo get_site_url().'/cart';?>" id="cartlink" style="display: none" class="btn btn-invert"><span>Cart</span></a>
             <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
	    </div>
	</div>
	</div>

<?php }?>
<?php 
function filter_plugin_updates( $value ) {
  unset( $value->response['woocommerce-pdf-invoices-packing-slips/woocommerce-pdf-invoices-packingslips.php'] );
   unset( $value->response['woocommerce-pdf-invoices-packing-slips-old/woocommerce-pdf-invoices-packingslips.php'] );
    unset( $value->response['paytm-payments/woo-paytm.php'] );
    unset( $value->response['ccavanue-woocommerce-payment-getway/index.php'] );
	unset( $value->response['woocommerce-shop-as-customer/shop-as-customer.php'] );
	unset( $value->response['js_composer/js_composer.php'] );
   return $value;
}
add_filter( 'site_transient_update_plugins', 'filter_plugin_updates' );

add_action('wp_ajax_add_money_in_wallet', 'add_money_in_wallet');
add_action('wp_ajax_nopriv_add_money_in_wallet', 'add_money_in_wallet');
function add_money_in_wallet()
{
	$checkSum = "";
	$paramList = array();
	$ORDER_ID = $_POST["ORDER_ID"];
	$CUST_ID = $_POST["CUST_ID"];
	$INDUSTRY_TYPE_ID ='Retail';
	$CHANNEL_ID = 'WEB';
	$TXN_AMOUNT = $_POST["TXN_AMOUNT"];

	// Create an array having all required parameters for creating checksum.
	$paramList["MID"] = PAYTM_MERCHANT_MID;
	$paramList["ORDER_ID"] = $ORDER_ID;
	$paramList["CUST_ID"] = $CUST_ID;
	$paramList["INDUSTRY_TYPE_ID"] = $INDUSTRY_TYPE_ID;
	$paramList["CHANNEL_ID"] = $CHANNEL_ID;
	$paramList["TXN_AMOUNT"] = $TXN_AMOUNT;
	$paramList["WEBSITE"] = PAYTM_MERCHANT_WEBSITE;
	$paramList["CALLBACK_URL"] = site_url('/my-account/wallet-history/');
	/*
	$paramList["CALLBACK_URL"] = "http://localhost/PaytmKit/pgResponse.php";
	$paramList["MSISDN"] = $MSISDN; //Mobile number of customer
	$paramList["EMAIL"] = $EMAIL; //Email ID of customer
	$paramList["VERIFIED_BY"] = "EMAIL"; //
	$paramList["IS_USER_VERIFIED"] = "YES"; //

	*/

	//Here checksum string will return by getChecksumFromArray() function.
	$checkSum = getChecksumFromArray($paramList,PAYTM_MERCHANT_KEY);
	$form='';
	$form.='<form method="post" action="'.PAYTM_TXN_URL.'" name="f1">';
		$form.='<table border="1">';
		$form.='<tbody>';
			
			foreach($paramList as $name => $value) {
				$form.='<input type="hidden" name="' . $name .'" value="' . $value . '">';
			}
			
			$form.='<input type="hidden" name="CHECKSUMHASH" value="'.$checkSum.'">';
		$form.='</tbody>';
	$form.='</table>';
	$form.='</form>';

	echo $form;
	die;
}

add_action( 'woocommerce_order_status_completed',  'order_complete_invoice_number_generate');
function order_complete_invoice_number_generate($order_id)
{	
	 	$document_type = sanitize_text_field('invoice');
		 $order_ids = (array) array_map( 'absint', explode( 'x',$order_id) );
		   try {
					$document = wcpdf_get_document( $document_type, $order_ids, true,true);
					
				} catch ( \Dompdf\Exception $e ) {
					$message = 'DOMPDF Exception: '.$e->getMessage();
					wcpdf_log_error( $message, 'critical', $e );
					wcpdf_output_error( $message, 'critical', $e );
				} catch ( \Exception $e ) {
					$message = 'Exception: '.$e->getMessage();
					wcpdf_log_error( $message, 'critical', $e );
					wcpdf_output_error( $message, 'critical', $e );
				} catch ( \Error $e ) {
					$message = 'Fatal error: '.$e->getMessage();
					wcpdf_log_error( $message, 'critical', $e );
					wcpdf_output_error( $message, 'critical', $e );
				}

}

function franchise_wallet_recharge($paramList,$franchise_id,$user_id,$balance){
	global $wpdb;
		if(empty($paramList['STATUS'])) return false;
		
		
	if($paramList){
		$status 			= (!empty($paramList['STATUS']) && $paramList['STATUS'] =='TXN_SUCCESS') ? 1 : 0;
		$paytm_order_id 	= (!empty($paramList['ORDERID'])? $paramList['ORDERID']:'');
		$transaction_id 	= (!empty($paramList['TXNID'])? $paramList['TXNID']:'');

	    $paytmChecksum = "";    
	    $isValidChecksum = "FALSE";
	    $paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; //Sent by Paytm pg
	    //Verify all parameters received from Paytm pg to your application. Like MID received from paytm pg is same as your application’s MID, TXN_AMOUNT and ORDER_ID are same as what was sent by you to Paytm PG for initiating transaction etc.
	    $isValidChecksum = verifychecksum_e($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum); //will return TRUE or FALSE string.
	 
	    if($isValidChecksum == "TRUE") {
	        //echo "<b>Checksum matched and following are the transaction details:</b>" . "<br/>";
	        if ($paramList["STATUS"] == "TXN_SUCCESS") {
	            //echo "<b>Transaction status is success</b>" . "<br/>";
	            //TXNAMOUNT

	            $data=array(
	            	'transaction_details'=>'Payment deposite using paytm and TXNID is '.$paramList["TXNID"],
	            	'order_id'=>0,
	            	'franchise_id'=>$franchise_id,
	            	'user_id'=>$user_id,
	            	'amount'=>$paramList['TXNAMOUNT'],
	            	'tran_type'=>'cr',
	            	'close_balance'=>($balance + $paramList['TXNAMOUNT']),
	            	'status'=>1,
	            	'TXNID'=>$paramList["TXNID"]
	            );
	            $wpdb->insert('th_franchise_payment',$data);

	            //Process your transaction here as success transaction.
	            //Verify amount & order id received from Payment gateway with your application's order id and amount.
	        }else{
	            //echo "<b>Transaction status is failure</b>" . "<br/>";
	        }

	        $sql =  "INSERT INTO `" . $wpdb->prefix . "paytm_wallet_order_data` SET `order_id` = '" . $order_id . "', `paytm_order_id` = '" . $paytm_order_id . "', `transaction_id` = '" . $transaction_id . "', `status` = '" . (int)$status . "', `paytm_response` = '" . json_encode($paramList) . "', `date_added` = NOW(), `date_modified` = NOW()";
			$wpdb->query($sql);

	        /*if (isset($_POST) && count($_POST)>0 )
	        { 
	            foreach($_POST as $paramName => $paramValue) {
	                    echo "<br/>" . $paramName . " = " . $paramValue;
	            }
	        }*/
	        

	    }else {
	        //echo "<b>Checksum mismatched.</b>";
	        //Process transaction as suspicious.
	    }
	     if ($paramList["STATUS"] == "TXN_SUCCESS") {
	     		 return ($balance + $paramList['TXNAMOUNT']);
	     }else{
	     	 return ($balance);
	     }
	   
	}
}
add_action('wp_default_scripts', function ($scripts) {
    if (!empty($scripts->registered['jquery'])) {
        $scripts->registered['jquery']->deps = array_diff($scripts->registered['jquery']->deps, ['jquery-migrate']);
    }
});