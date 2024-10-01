var refreshSelectElement = function (idToRefresh = '') {
    var selectElement = $('#' + idToRefresh);
    $(selectElement)
            .selectpicker('refresh')
            .on('changed.bs.select', onSelectChange);
};

var disableSelectElement = function (elementId = '') {
    var selectElement = $('#' + elementId);
    var wrapperElement = $('#' + elementId + '-wrapper');
    selectElement.prop('disabled', 'disabled');
    wrapperElement.addClass('disabled');
    refreshSelectElement(elementId);
};

var enableSelectElement = function (elementId = '') {
    var selectElement = $('#' + elementId);
    var wrapperElement = $('#' + elementId + '-wrapper');
    $(selectElement).prop('disabled', false);
    wrapperElement.removeClass('disabled');
    refreshSelectElement(elementId);
};

let onSelectChange = function () {
    $('.dropdown-menu li.selected')
            .find('input[type="checkbox"]')
            .prop('checked', true);
    $('.dropdown-menu li:not(.selected)')
            .find('input[type="checkbox"]')
            .prop('checked', false)
};


/* Allows to rewrite options dynamically with an object in the form of */
jQuery.fn.setOptionsToSelect = function (optionsData) {
    var selectElement = $(this).find('select');

    /* Destroying selectElement */
    $(selectElement)
            .off('changed.bs.select')
            .selectpicker('destroy')
            .empty();

    /* Appending options according to the optionsData object */
    optionsData.forEach(function (x) {
        $(selectElement).append(
                $('<option>', {
                    value: x.value,
                    text: x.text,
                    selected: x.selected,
                })
                )
    });

    /* Refreshing selectElement */
    $(selectElement)
            .selectpicker('refresh')
            .on('changed.bs.select', onSelectChange);

    return this
};


var addOptionsByDataActiion = function (owner) {

    var inputId = owner.attr('id');
    var action = owner.attr('data-action');
    var relatedId = owner.attr('data-releted');
    var related = jQuery('#' + relatedId);

    var mydata = {
        related_value: related ? related.val() : ''
    };

    /*CSRF Workaround*/
    mydata[csrfParam] = csrValue;

    if (!action) {
        return false;
    }

    jQuery('#spinner-' + inputId).toggle();

    jQuery.post({
        url: action,
        type: 'post',
        data: mydata,
        success: function (data, textStatus, jqXHR) {

            if (textStatus == 'success') {
                var options = [];
                jQuery.each(data, function (index, element) {
                    var myValue = element.value;
                    var label = element.label;
                    if (myValue == owner.attr('data-valued')) {
                        options.push({value: myValue, text: label, selected: true});
                    } else {
                        options.push({value: myValue, text: label, selected: false});
                    }
                });
                jQuery('#' + inputId + '-wrapper').setOptionsToSelect(options);
                enableSelectElement(inputId);
            }

        },
        complete: function () {
            jQuery('#spinner-' + inputId).toggle();
        },
        error: function (data, textStatus, jqXHR) {
            /* 	console.log(data);
             console.log(textStatus);
             console.log(jqXHR);*/
        }

    });

};

jQuery('.rellable-drop').each(function (k, v) {
    var reledSelector = '#' + jQuery(this).attr('data-releted');
    var relatedElement = jQuery(reledSelector);
    if (relatedElement.length) {
        if (relatedElement.val() && relatedElement.val().length) {
            addOptionsByDataActiion(jQuery(this));
        } else if(relatedElement.val()) {
            disableSelectElement(relatedElement.attr('id')); 
        }

        var that = jQuery(this);

        relatedElement.change(function () {
            addOptionsByDataActiion(that);
        });
    }/* else {
     addOptionsByDataActiion();
     }*/
});