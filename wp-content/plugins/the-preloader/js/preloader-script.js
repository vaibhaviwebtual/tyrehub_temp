//jQuery(window).load(function() { 
/* add by vb 31-03-21 1st line*/
jQuery(window).on('load', function(){
	jQuery('#wptime-plugin-preloader').delay(250).fadeOut("slow");
	
	setTimeout(wptime_plugin_remove_preloader, 2000);
	function wptime_plugin_remove_preloader() {	
		jQuery('#wptime-plugin-preloader').remove();
	}

});