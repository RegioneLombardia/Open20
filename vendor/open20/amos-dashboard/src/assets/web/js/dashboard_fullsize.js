// Set the name of the hidden property and the change event for visibility
var hidden, visibilityChange;

var reloadWidgets = null;
/*setInterval(function () {
   $.pjax.reload({container: '#widget-icons-pjax-block'});
}, 90000);*/  // run every 90 seconds

jQuery.fn.dashboardPlugin = function (options) {

    var defaults = {
        save_url: "",
        current_dashboard: "",
        edit_action_bar: "#dashboard-edit-toolbar",
        edit_selector: "#dashboard-edit-button",
        save_selector: "#dashboard-save-button",
        widget_icons_container: "",
        widget_graphics_container: "",
        errorHandler: ""
    };

    this.error = function (msg) {
        var msg = msg || "Errore di configurazione dashboard";
        alert(msg);
        console.error(this, msg);
    };

    this.settings = $.extend({}, defaults, options);

    if (!this.settings.save_url || !this.settings.edit_selector || !this.settings.widget_icons_container || !this.settings.widget_graphics_container
    ) {
        console.log(this.settings, '----');
        this.error("Parametri mancanti per una corretta configurazione della dashboard");
        return;
    }

    this.editable = function () {
        var buttonbar = $(this.settings.edit_action_bar);
        buttonbar.toggleClass('hidden show');
    };

    this.save = function () {

        var that = this;

        $.ajax({
            url: this.settings.save_url,
            data: {
                amosWidgetsClassnames: this.getMatrixDashboards(),
                currentDashboardId: this.settings.current_dashboard
            },
            type: 'post',
            dataType: 'json',
            async: false,
            success: function (data) {
                //attiva il sotable
                jQuery(that.settings.widget_icons_container).sortable('destroy');
                jQuery(that.settings.widget_graphics_container).sortable('destroy');
                //se il salvataggio non è andato a buon fine
                if (data.success == false) {
                    that.error("Errore di salvataggio dell'ordinamento");
                }

            },
            error: function () {
                that.error("Errore di comunicazione con il server");

            }
        });

        return true;
    };

    this.getMatrixDashboards = function () {
        var dashboardElements = [];

        if (jQuery(this.settings.widget_icons_container).length > 0) {
            $.each(jQuery(this.settings.widget_icons_container), function (indexDashboard, dashboard) {
                //oggetto della dashboard
                var dashboardObj = $(dashboard);

                //recupera tutti gli elementi
                var elements = dashboardObj.find('div');

                //per ciascuno, recupera l'ordine
                $.each(elements, function (indexElement, element) {
                    var code = $(element).attr('data-code');
                    var module_name = $(element).attr('data-module-name');

                    dashboardElements.push(code);
                });

            });
        }
        if (jQuery(this.settings.widget_graphics_container).length > 0) {
            //cicla le dashboards rilevate
            $.each(jQuery(this.settings.widget_graphics_container), function (indexDashboard, dashboard) {
                //oggetto della dashboard
                var dashboardObj = $(dashboard);

                //recupera tutti gli elementi
                var elements = dashboardObj.find('div');

                //per ciascuno, recupera l'ordine
                $.each(elements, function (indexElement, element) {
                    var code = $(element).attr('data-code');
                    var module_name = $(element).attr('data-module-name');
                    if (code) {
                        dashboardElements.push(code);
                    }

                });

            });
        }

        return dashboardElements;
    };


    var that = this;

    jQuery(this.settings.edit_selector).on("click", function (evt) {
        that.editable();

        jQuery(that.settings.widget_icons_container).sortable();
        jQuery(that.settings.widget_graphics_container).sortable();
    });

    jQuery(this.settings.save_selector).on("click", function (evt) {
        that.save();
        that.editable();
    });
};

$(document).ready(function () {

    if (typeof document.hidden !== "undefined") { // Opera 12.10 and Firefox 18 and later support
        hidden = "hidden";
        visibilityChange = "visibilitychange";
    } else if (typeof document.msHidden !== "undefined") {
        hidden = "msHidden";
        visibilityChange = "msvisibilitychange";
    } else if (typeof document.webkitHidden !== "undefined") {
        hidden = "webkitHidden";
        visibilityChange = "webkitvisibilitychange";
    }

    // Handle page visibility change
    document.addEventListener(visibilityChange, handleVisibilityChange, false);

    jQuery('body').dashboardPlugin({
        'save_url': $('#saveDashboardUrl').val(),
        'widget_icons_container': "#widgets-icon",
        'widget_graphics_container': "#widgets-graphic",
        "current_dashboard": $('#currentDashboardId').val(),
    });

});

// If the page is hidden, do no refresh widget block - unset the widget block refreshing function
// if the page is shown, refresh widget block and set function realoding block every 90 seconds
function handleVisibilityChange() {

    if (document[hidden]) {
        //clearInterval(reloadWidgets);
    } else {
        //$.pjax.reload({container: '#widget-icons-pjax-block'});
        /*reloadWidgets =  setInterval(function () {
            $.pjax.reload({container: '#widget-icons-pjax-block'});
        }, 90000);*/
    }
}

