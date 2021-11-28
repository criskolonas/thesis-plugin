jQuery(document).ready(function () {
    jQuery("#table_form").submit(function (e) { 
        e.preventDefault();
        const post_url = jQuery(this).attr("action"); //get form action url
	    const request_method = jQuery(this).attr("method"); //get form GET/POST method
	    const form_data = jQuery(this).serialize(); //Encode form elements for submission
        
        jQuery.ajax({
            type: request_method,
            url: post_url,
            data: form_data,
            success: function (response) {
                alert(form_data)
            }
        });
    });
});