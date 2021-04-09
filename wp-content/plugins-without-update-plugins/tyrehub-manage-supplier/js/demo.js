/*jQuery(document).ready(function($) {
    $('#upload_button').click(function() {
        tb_show('Upload a logo', 'media-upload.php?type=image&TB_iframe=true&post_id=0', false);
        return false;
    });

    // Display the Image link in TEXT Field
    window.send_to_editor = function(html) {
        var image_url = $('img',html).attr('src');
        $('#logo_url').val(image_url);
        tb_remove();
    }
});*/


jQuery(document).ready(function($){
    $('#upload_button').click(function(e) {
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
            $('#logo_url').val(image_url);
        });
    });
});