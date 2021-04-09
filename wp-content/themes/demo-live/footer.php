<?php include_once('templates/service-partner/modal.php');?>
<?php include_once('templates/home-page-location-modal.php');?>
<div class="page-footer">
	<div class="newsletter">
		<div class="container">
			<div class="row">
				<div class="newsletter-inner">
					<?php echo do_shortcode('[mc4wp_form id="1820"]');?>
				</div>
			</div>
		</div>
	</div>
	<div class="footer-part">
		<div class="container">
			<div class="row">
				<?php
				  	if ( is_active_sidebar( 'first-footer-widget-area' )
					    && is_active_sidebar( 'second-footer-widget-area' )
					    && is_active_sidebar( 'third-footer-widget-area'  )
					    /*&& is_active_sidebar( 'fourth-footer-widget-area' )*/
					) :
				?>
				<div class="col-xs-12 col-sm-4 col-md-4 box">
				    <div class="footer-data p_font_set">
				        <?php dynamic_sidebar( 'first-footer-widget-area' ); ?>
				    </div>
			 	</div>
			 	<div class="col-xs-12 col-sm-4 col-md-4  box">
				    <div class="footer-data">
				        <?php dynamic_sidebar( 'third-footer-widget-area' ); ?>
				    </div>
			 	</div>
				<div class="col-xs-12 col-sm-4 col-md-4 box">
				    <div class="footer-data">
				        <?php dynamic_sidebar( 'second-footer-widget-area' ); ?>
				    </div>
			 	</div>
			 	 <?php /*?><div class="col-xs-12 col-sm-3 col-md-3 box">
				    <div class="footer-data">
				        <?php dynamic_sidebar( 'fourth-footer-widget-area' ); ?>
				    </div>
				</div>	<?php */?>
 			<?php endif; ?>
			</div>
		</div>
	</div>

	<?php /*?><div class="footer-bottom">
		<div class="container">
			<div class="footer_card_images">
				<span><img src="<?php echo bloginfo('template_url') ?>/images/mastercard.png" alt=""></span>
				<span><img src="<?php echo bloginfo('template_url') ?>/images/visa.png" alt=""></span>
				<span><img src="<?php echo bloginfo('template_url') ?>/images/paytm_wallet.png" alt=""></span>
				<span><img src="<?php echo bloginfo('template_url') ?>/images/rupay.png" alt=""></span>
				<span><img src="<?php echo bloginfo('template_url') ?>/images/bhim.png" alt=""></span>
				</span>
			</div>
			<div class="copyrights_last">
				<span>Bill Payments Powered By</span>
				<span><a href="https://www.ccavenue.com/" target="_blank"> CCAvenue.com </a></span>
			</div>
		</div>
	</div><?php */?>

	<div class="footer-bottom copyright-section">
		<div class="container">
			<div class="row">
				<div class="col-md-6 copyright">
					<p>Copyright © <?=date('Y');?> <img src="<?php echo bloginfo('template_directory').'/assest/images/favicon.png'?>" style="" /><a href="<?=site_url();?>">TyreHub</a>. All Rights Reserved.</p>
				</div>
				<div class="col-md-6 trch-partner">
					<p>Powered By : <a href="https://www.webtual.com">Webtual Technologies PVT LTD</a></p>
				</div>
			</div>
		</div>
	</div>

</div>
		<div class="darkout-menu"></div>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "AutoRepair",
  "name": "TyreHub",
  "image": "https://www.tyrehub.com/wp-content/uploads/2018/12/2018-12-03.png",
  "@id": "",
  "url": "https://www.tyrehub.com/",
  "telephone": "1800-233-5551",
  "priceRange": "$$",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "3rd Eye Residency, Motera Stadium Rd, Motera",
    "addressLocality": "Ahmedabad",
    "postalCode": "380005",
    "addressCountry": "IN"
  },
"review": {
        "@type": "Review",
        "reviewRating": {
          "@type": "Rating",
          "ratingValue": "4",
          "bestRating": "5"
        },
        "author": {
          "@type": "Person",
          "name": "Amit Sharma"
        }
      },

  "geo": {
    "@type": "GeoCoordinates",
    "latitude": 23.0970191,
    "longitude": 72.5963869
  },
  "openingHoursSpecification": {
    "@type": "OpeningHoursSpecification",
    "dayOfWeek": [
      "Monday",
      "Tuesday",
      "Wednesday",
      "Thursday",
      "Friday"
    ],
    "opens": "09:30",
    "closes": "20:30"
  },
  "sameAs": [
    "https://www.facebook.com/Tyrehub-323758851594844/",
    "https://twitter.com/tyrehub",
    "https://www.linkedin.com/company/tyrehubindia/about/?viewAsMember=true",
    "https://www.instagram.com/tyrehub_tyre_services/"
  ]
}
</script>
		<!-- External JavaScripts -->

		<?php /*?><script src="<?php echo bloginfo('template_directory').'/assest/js/jquery.js?ver='.rand(111,999)?>" defer onload></script><?php */?>

		<script src="<?php echo bloginfo('template_directory').'/assest/js/plugins/bootstrap.min.js?ver='.rand(111,999)?>" defer onload></script>
		<script src="<?php echo bloginfo('template_directory').'/assest/js/plugins/slick.min.js?ver='.rand(111,999)?>" defer onload></script>
		<script src="<?php echo bloginfo('template_directory').'/assest/js/plugins/jquery.form.js?ver='.rand(111,999)?>" defer onload></script>
		<script src="<?php echo bloginfo('template_directory').'/assest/js/plugins/jquery.validate.min.js?ver='.rand(111,999)?>" defer onload></script>
   		<script src="<?php echo bloginfo('template_directory').'/assest/js/plugins/moment.js?ver='.rand(111,999)?>" defer onload></script>
		<script src="<?php echo bloginfo('template_directory').'/assest/js/plugins/bootstrap-datetimepicker.js?ver='.rand(111,999)?>" defer onload></script>
		<script src="<?php echo bloginfo('template_directory').'/assest/js/plugins/jquery.waypoints.min.js?ver='.rand(111,999)?>" defer onload></script>
		<script src="<?php echo bloginfo('template_directory').'/assest/js/plugins/jquery.countTo.js?ver='.rand(111,999)?>"defer onload ></script>

		<!-- Custom JavaScripts -->
		<script src="<?php echo bloginfo('template_directory').'/assest/js/custom.js?ver='.rand(111,999)?>" defer onload></script>
		<script src="<?php echo bloginfo('template_directory').'/assest/js/forms.js?ver='.rand(111,999)?>" defer onload></script>
		<script async defer src="https://maps.googleapis.com/maps/api/js?components=country:IN&v=3.exp&sensor=false&key=<?php echo GOOGLE_API_KEY?>&libraries=places&callback=initAutocomplete"
 ></script>
 <script src="<?php echo bloginfo('template_directory').'/assest/js/wow.min.js?ver='.rand(111,999)?>" defer onload></script>

 <!-- <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script> -->
        <script type="text/javascript">
        	 jQuery(document).ready(function() {
        	 	if(performance.navigation.type == 2){
				   location.reload(true);
				}
			     jQuery(".add_to_cart_button").removeClass("ajax_add_to_cart");
			 });
        </script>

		<script type="text/javascript">
        	 jQuery(document).ready(function() {
				 jQuery('.testimonials').slick({
					slidesToShow: 1,
					slidesToScroll: 1,
					dots: false,
					infinite: true,
					cssEase: 'linear',
					slide: 'li',
					arrows: true,
				});
			 });
        </script>
		<?php

			$current_url = get_permalink();
			$service_partner = get_site_url().'/online-tyre-services-partner/';
			if($current_url == $service_partner){

		?>
		<script type="text/javascript">
			// for location search
			jQuery('#myInput').attr("autocomplete","new-password");
				jQuery("#myInput").keyup(function()
				{
					var post_code = jQuery('#postal_code').val();
					var search_text = jQuery('#myInput').val();

					if(search_text != '')
					{
						if(post_code != '')
						{
						}
					}
					else{
						jQuery('#postal_code').val('');
					}
				});

				function initAutocomplete() {

		       var placeSearch, autocomplete;
		          var componentForm = {
		            street_number: 'short_name',
		            route: 'long_name',
		            locality: 'long_name',
		            administrative_area_level_1: 'short_name',
		            country: 'long_name',
		            postal_code: 'short_name'
		          };

    	var lat = -33.8688;
      	var lng = 151.2195;
        var latlng = new google.maps.LatLng(lat,lng);
        var image = 'http://www.google.com/intl/en_us/mapfiles/ms/micons/blue-dot.png';

    var input = document.getElementById('myInput');
    var autocomplete = new google.maps.places.Autocomplete(input, {
        types: ["geocode"]
    });
    // autocomplete.addListener('place_changed', fillInAddress);

    var infowindow = new google.maps.InfoWindow();

    google.maps.event.addListener(autocomplete, 'place_changed', function(){
    	autocomplete.bindTo('bounds', map);
        infowindow.close();
        var place = autocomplete.getPlace();

	        console.log(place);
	        for (var component in componentForm) {
	          document.getElementById(component).value = '';
	          document.getElementById(component).disabled = false;
	        }
	        for (var i = 0; i < place.address_components.length; i++) {
	          var addressType = place.address_components[i].types[0];
	          if (componentForm[addressType]) {
	            var val = place.address_components[i][componentForm[addressType]];
	            document.getElementById(addressType).value = val;
	          }
	        }
    });
}


		      jQuery(function(){



    jQuery("input").focusin(function () {
        jQuery(document).keypress(function (e) {
            if (e.which == 13) {
                infowindow.close();
                var firstResult = jQuery(".pac-container .pac-item:first").text();

                var geocoder = new google.maps.Geocoder();
                geocoder.geocode({"address":firstResult }, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        var lat = results[0].geometry.location.lat(),
                            lng = results[0].geometry.location.lng(),
                            placeName = results[0].address_components[0].long_name,
                            latlng = new google.maps.LatLng(lat, lng);

                      //  moveMarker(placeName, latlng);

                        jQuery(".filter-section input").val(firstResult);
                    }
                });
            }
        });
    });


    jQuery("input").focusin(function () {
    jQuery(document).keypress(function (e) {
        if (e.which == 13) {
            var firstResult = jQuery(".pac-container .pac-item:first").text();

            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({"address":firstResult }, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    var lat = results[0].geometry.location.lat(),
                        lng = results[0].geometry.location.lng(),
                        placeName = results[0].address_components[0].long_name,
                        latlng = new google.maps.LatLng(lat, lng);

                        $(".pac-container .pac-item:first").addClass("pac-selected");
                        $(".pac-container").css("display","none");
                        $("#searchTextField").val(firstResult);
                        $(".pac-container").css("visibility","hidden");

                   // moveMarker(placeName, latlng);

                }
            });
        } else {
            jQuery(".pac-container").css("visibility","visible");
        }
    });
});
});







		</script>
	<?php } ?>


	<!-- <?php if(!is_home()){?>
	<script type="text/javascript">
		window.onload = function() {
		  // your code
		  setTimeout(function(){
				$.ajax({
			        type: "POST",
			        url:'<?=admin_url( 'admin-ajax.php' );?>',
			        data: {
			            action: 'installer_populate',
			            postal_code :'382481',
			            vehicle_type : '',
						product_id :'',
			            prd_attr_vehicle :'',
			        },
			        success: function (data)
			        {
			            //$('.installer-list').html(data);
			        },
			     });


		 }, 3000);
		};
	</script>
<?php }?> -->

<script type="text/javascript">
	
var get_stylesheet_directory_uri = '<?php echo get_stylesheet_directory_uri(); ?>';
</script>


		<?php wp_footer(); ?>
	</body>
</html>