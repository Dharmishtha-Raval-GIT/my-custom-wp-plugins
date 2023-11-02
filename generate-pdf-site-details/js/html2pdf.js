/* start Ajax script for generate PDF of Site Details */
jQuery(document).ready(function() {
    jQuery('.pdf-generate').click(function() {
        jQuery.ajax({
            type: 'POST',
            url: jsData.ajaxUrl,
            data: {
                'action': 'get_data',
            },
            success: function(data){
                window.open("../pdf/"+data);
            },
        });
    });
});
/* End Ajax script for generate PDF of Site Details */