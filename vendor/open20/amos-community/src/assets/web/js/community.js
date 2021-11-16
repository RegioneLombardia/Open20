function checkSelect2Init(modalId, selectId){
    if($("#"+modalId).find(".select2").length == 0){
        var $el = $("#"+selectId);
        if($el.length){
            var settings = $el.attr('data-krajee-select2');
            settings = window[settings];
            $el.select2(settings);

            $("#"+modalId).find(".kv-plugin-loading").remove();
        }
    }
}
