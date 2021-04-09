jQuery(document).ready(function($) 
{
    // When select car compny dropdown
    /*$(document).on('change','.variation-supported-car',function()
    {
    	var value = $(this).val();
    	$( ".variation-supported-car option:selected" ).attr('disabled','disabled')
    	
    	var html = '<li style="width: auto; float: left; padding: 5px; border: 1px solid; border-radius: 5px; margin: 5px;">'+value+'</li>';
    	$('.car-list').append(html);
    });*/
    setTimeout(function(){
		console.log("$('.js-example-basic-multiple')---",$(document).find('.js-example-basic-multiple').html())
	   $('.js-example-basic-multiple').select2();
    },5000)
    	
	
});