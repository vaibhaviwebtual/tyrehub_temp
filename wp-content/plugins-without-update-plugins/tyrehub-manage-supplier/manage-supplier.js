jQuery(document).ready(function($) 
{
	$(document).on('click','.open-fc-modal',function(e){
		
		e.preventDefault();
		$('#add_new_facility').css('display','block');
	});

	

	$(document).on('click','.fc-save',function(e){
		e.preventDefault();
		var au = $('.admin-url').text();
		var name = $('.fc-name').val();
		var installer_id = $('.installer_id').val();
		$.ajax({    
                type: "POST", 
                url: au,
                data: {
                    action: 'save_facilities',
                    name: name,
                    installer_id: installer_id,
                },
           
                success: function (data) {
                   	$('#add_new_facility').css('display','none');
                    $('.fc-list').html(data);
                },
            });
	});

	
	$(document).on('click','.open-as-modal',function(e){
		
		e.preventDefault();
		$('#add_new_as').css('display','block');
	});

	
	$(document).on('click','.custom-modal .close',function(e){

		e.preventDefault();
		$('#add_new_as').css('display','none');

	});

	$('#upload_img').click(function(e) {
        e.preventDefault();
        var image = wp.media({ 
            title: 'Upload Image',
            // mutiple: true if you want to upload multiple files at once
            multiple: false
        }).open()
        .on('select', function(e){
            // This will return the selected image from the Media Uploader, the result is an object
            var uploaded_image = image.state().get('selection').first();
            // We convert uploaded_image to a JSON object to make accessing it easier
            // Output to the console uploaded_image
            console.log(uploaded_image);
            var image_url = uploaded_image.toJSON().url;
            // Let's assign the url value to the input field
            $('.installer_img').val(image_url);
        });
    });

});