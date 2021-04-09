jQuery(document).ready(function(){
	
	jQuery('.franchise-data .installer-list').change(function(){
		
		var insta_id = jQuery(this).val();
		var order_id = jQuery(this).attr('data-order');
		var cur_instlr_id = jQuery(this).attr('data-cur-instlr-id');
		var proid = jQuery(this).attr('data-proid');
		
		
		jQuery.confirm({
			'title'		: 'Installer Change Confirmation',
			'message'	: 'Are you sure you want to change the Installer for this order? Yes, Cancel',
			'buttons'	: {
				'Yes'	: {
					'class'	: 'blue',
					'action': function(){
						jQuery('#cover-spin').show();

							jQuery.ajax({
				                type: "POST", 
				                url: ajaxurl,
				                data: {
				                    action: 'installer_change_from_admin',
				                    insta_id : insta_id,
				                    cur_instlr_id : cur_instlr_id,
				                    order_id : order_id,
				                    proid : proid
				                },
				                success: function (data)
				                {
				                	location.reload();
				                  jQuery('#cover-spin').hide();  
				                                      
				                },
			            });
					}
				},
				'Cancel'	: {
					'class'	: 'gray',
					'action': function(){}	// Nothing to do in this case. You can as well omit the action property.
				}
			}
		});
		
	});
	
});