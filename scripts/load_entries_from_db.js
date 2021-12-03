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

