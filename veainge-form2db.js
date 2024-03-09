jQuery(document).ready(function($) {
    const getData = {
        _ajax_nonce: frontend_ajax_object.nonce,
        action: 'save_data_form',
        locale: frontend_ajax_object.locale
    }
    sendingForm('#miFormulario, .plug-form', getData);
    loadDataTable('#table-from-plugin', getData); 
});

function sendingForm(selector='#miFormulario', getData={}) {
    let form_fields = jQuery(selector).find('input,textarea,select').not('input[type="submit"]');
        form_fields.attr('required', 'required');
    jQuery(selector).on('submit', function(e) {
        e.preventDefault();
        let form_data = jQuery(this).serializeArray();
          
        getData.formData = form_data; 

        jQuery.post(frontend_ajax_object.ajaxurl, getData,
            function(response) {
                if (response.status == 'success') {
                    alert(response.message);
                    window.location.reload();
                }
            }
		);
    });
}

function loadDataTable(selector='#table-from-plugin', getData={}) {
    let locale_url = "//cdn.datatables.net/plug-ins/1.13.4/i18n/en-GB.json"; 
    if (getData.locale) {
        getData.locale = getData.locale.replace('_', '-');
        locale_url = "//cdn.datatables.net/plug-ins/1.13.4/i18n/"+getData.locale+".json";
    }

    new DataTable(selector, {
        language: {
            url: locale_url,
        },
    });
}