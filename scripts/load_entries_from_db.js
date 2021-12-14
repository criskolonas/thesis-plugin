/*
    AJAX is used to call the load_entries() function and output its return value to a div
*/
jQuery(document).ready(function () {
    jQuery("#table_form").submit(function (e) { 
        e.preventDefault();
        
        jQuery.ajax({
            type: 'POST',
            url: ajax_object.ajax_url,
            data: {
                action:'load_entries_ajax',
                table:jQuery('#tables :selected').val()
            },
            success: function (response) {
                jQuery('#output-area').html("");
                jQuery('#output-area').append(response);
            }
        });
    });
});

