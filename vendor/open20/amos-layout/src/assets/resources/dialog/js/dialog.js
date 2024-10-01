// krajee-dialog configuration for all data-confirm link
var krajeeModalOptions = {
    "cssClass": "krajee-amos-modal"
}
var krajeeDefaultValues = {
    "alert": {
        "type": "type-info",
        "title": "Informazione",
        "buttonLabel": lajax.t('Conferma')
    },
    "confirm": {
        "type": "type-warning",
        "title": lajax.t('Conferma'),
        "btnOKClass": "btn-warning",
        "btnOKLabel": lajax.t('Conferma'),
        "btnCancelLabel": lajax.t('Annulla'),
    },
    "prompt": {
        "draggable": false,
        "title": "Informazione",
        "buttons": [{"label": lajax.t('Annulla')}, {
            "label": lajax.t('Conferma'),
            // "icon": "glyphicon glyphicon-ok",
            "cssClass": "btn-primary"
        }],
        "closable": false
    },
    "dialog": {
        "draggable": true,
        "title": lajax.t('Informazione'),
        "buttons": [
            {
                "label": lajax.t('Annulla'),
                // "icon": "glyphicon glyphicon-ban-circle"
            },
            {
                "label": lajax.t('Conferma'),
                // "icon": "glyphicon glyphicon-ok",
                "cssClass": "btn-primary"
            }
        ]
    }
};
var krajeeDialog = new KrajeeDialog(true, krajeeModalOptions, krajeeDefaultValues);
krajeeYiiConfirm('krajeeDialog');


/**
 * 
 * Bug: Html a with data-confirm inside a form should not submit a form #17624
 * 
 */
$(document).on('click', 'a[data-url-confirm]', function (e){
    var link                = $(this);
    var address             = link.attr('href');

    krajeeDialog.confirm($(e.currentTarget).data('urlConfirm'),function (result)
                    {
                            if(result){
                                window.location.href = address;
                                return true;
                            }else{
                                return true;
                            }
                    });
	e.preventDefault();
	return false;
    }
);