$(document).ready(function(){
    
       
    if(typeof fileExplorerWidgetParams == 'undefined')
        updateCarousel(4);
    else
        updateCarousel(fileExplorerWidgetParams.defaultFolderColumns);
    
    initPagination();
    showPage(1);
        
    
   

});

$(document).on('change', '.file-explorer-container .krajee-datepicker', function() {
    
    var val = $(this).val(); // ottieni il nuovo valore del campo
    var id = $(this).attr('id').replace("-disp", "");
    var convertedDate = convertDateFormat(val);
    $("#"+id).val(convertedDate);
    
});

$(document).on('click', '.file-explorer-container .kv-date-remove', function() {
    
    var input = $(this).closest('.input-group').find('input');
    input.kvDatepicker('clearDates');
    
});

function convertDateFormat(inputDate) {
    var parts = inputDate.split('-');
    if (parts.length === 3) {
        var day = parts[0];
        var month = parts[1];
        var year = parts[2];
        return year + '-' + month + '-' + day;
    }
    return inputDate; // Ritorna la data senza formattazione se il formato non è riconosciuto
}

function sendData(url,id,filters = []){
   
    $filter_array = [];
    $params_array = [];
    
    for (const [key, value] of filters) {
        const regex = /^(DynamicModel)\[([^\]]+)\]/;
        const match = key.match(regex);

        if (match) {
          const attribute = match[2];
          if(value != ''){
              $filter_array.push({attribute:attribute,value:value});
          }
        }
    }
    
    for (var attribute in fileExplorerWidgetParams) {
        if (fileExplorerWidgetParams.hasOwnProperty(attribute)) {
            var value = fileExplorerWidgetParams[attribute];
            $params_array.push({attribute:attribute,value:value});
        }
    }
  

    $('.file-explorer-container .loading').show();
    $.post(url,{id:id,filters:$filter_array,params:$params_array},function(data){       
        updateWidget(data.data);
    },'json');

}

function updateWidget(content){

    var container = $('.file-explorer-container').parent();
    container.empty();

    container.html(content);
   
    $('.tooltip.show').remove();
    
    $('.file-explorer-container [data-toggle="tooltip"]').tooltip();
    
    $('.file-explorer-container .form-select2').each(function() {
        var settings = $(this).attr('data-krajee-select2');       
        settings = window[settings];
        // reinitialize plugin
        $(this).select2(settings);

    });
    
    $('.file-explorer-container .krajee-datepicker').each(function() {
        var settings = $(this).attr('data-krajee-kvdatepicker');  
        console.log(settings);
        settings = window[settings];
        // reinitialize plugin
        console.log(window);
        console.log(settings);
        $(this).kvDatepicker(settings);

    });
    
    // reinitialize select design 
    $('.file-explorer-container .bootstrap-select-wrapper').each(function() {
        var el = $(this).find('select');
        var id = el.attr('id');      
        refreshSelectElement(id);
    });
    
    $('.file-explorer-container .kv-plugin-loading').hide();
    
    if(typeof fileExplorerWidgetParams == 'undefined')       
        updateCarousel(4);
    else
        updateCarousel(fileExplorerWidgetParams.defaultFolderColumns);
    
    initPagination();
    showPage(1);

}

function updateCarousel(cols){
        
    var responsive;
    
    switch(cols){
        case 1:
            responsive = {
                0: {
                  items: 1,
                },
            };
        break;
        case 2:
            responsive = {
                0: {
                  items: 1,
                },
                550: {
                  items: 2,
                },
            };
        break;
        case 3:
            responsive = {
                0: {
                  items: 1,
                },
                550: {
                  items: 2,
                },
                767: {
                  items: 2,
                },
                992: {
                  items: 3,
                },
                1200: {
                  items: 3,
                }
            }
        break;
        case 4:
            responsive = {
                0: {
                  items: 1,
                },
                550: {
                  items: 2,
                },
                992: {
                  items: 3,
                },
                1200: {
                  items: 4,
                },
            };
        break;
    }
 
    var carousel = $('.file-explorer-container .owl-carousel');
    carousel.owlCarousel('destroy'); 
    carousel.owlCarousel({
        margin: 40,
        items: cols,
        responsiveClass: true,
        nav: true,
        stagePadding: 30,
        loop: false,
        responsive: responsive

    });
    
    $(".file-explorer-container .owl-prev").html('<span class="mdi mdi-chevron-left"></span><span class="sr-only">Slider precedenti</span>');
    $(".file-explorer-container .owl-next").html('<span class="mdi mdi-chevron-right"></span><span class="sr-only">Slider successive</span>');
    $(".file-explorer-container .owl-dot").html('<span></span><span class="sr-only">Slide</span>');
    
}

function archiveFilter(el,stato) {
    
    var list = $('.file-explorer-container .files-list > div.item');
    var badges = $('.file-explorer-container .list .prelist-content .archiving-search > .badge:not(.badge-danger)');
    $('.file-explorer-container .list .prelist-content .archiving-search > .badge.badge-danger').show();
 
    badges.removeClass('badge-primary').addClass('badge-light');
    
    if(stato < 0){
        list.show().removeClass("archiving"); 
        $(el).hide();
    }else{
        $(el).removeClass('badge-light').addClass('badge-primary');
        list.hide().removeClass("archiving");
        
        var sel_el = list.filter(function() {
            return $(this).data('archivio') === stato;
        });
        sel_el.show().addClass("archiving");
        
    }
    
    var tot_element = list.filter(":visible").length;
    
    var $textNormal = $('.file-explorer-container .list .prelist-content .tot-elements .text-normal');
    var $textSingular = $('.file-explorer-container .list .prelist-content .tot-elements .text-singular');
    
    if(tot_element == 1){
        if($textSingular.length){
            $textNormal.hide();
            $textSingular.show();
        }else
            $textNormal.show();
    }else if(tot_element > 1){       
        $textNormal.show();
        $textNormal.text($textNormal.text().replace(/\d+/, tot_element));
        $textSingular.hide();
    }
    
    if(tot_element > 0){  
        $('.file-explorer-container .list .pagination').show();
        $('.file-explorer-container .list .prelist-content .tot-elements').show();
        $('.file-explorer-container .files-list .no-doc').hide();
        initPagination();
        showPage(1);
    }else{
        $('.file-explorer-container .list .pagination').hide();
        $('.file-explorer-container .list .prelist-content .tot-elements').hide();
        $('.file-explorer-container .files-list .no-doc').show();

    }
    
}

function initPagination() {
    
    if(typeof fileExplorerWidgetParams == 'undefined')       
        var pageSize = 10;
    else
        var pageSize = fileExplorerWidgetParams.pageSize;
    
    var items = $('.file-explorer-container .files-list > div.item');
    if(items.hasClass('archiving'))
        var items = $('.file-explorer-container .files-list > div.item.archiving');
    
    var tot_items = items.length;
    var totalPages = Math.ceil(tot_items / pageSize);
    
    var container = $('.file-explorer-container .list .pagination-container .pagination');
    container.empty();

    container.append($('<li>').attr('class','prev').html('<a href="#" onclick="pagination(this,0,event)"><span>«</span></a>'));
    for (var i = 1; i <= totalPages; i++) {
        var $class = i == 1 ? 'active' : '';
      
        var li = $('<li>').attr('class',$class).html('<a href="#" data-page="' + i +'" onclick="pagination(this,'+ i + ',event)">' + i + '</a>');       
        container.append(li);
    }
    container.append($('<li>').attr('class','next').html('<a href="#" onclick="pagination(this,0,event)"><span>»</span></a>'));

    
}

function showPage(page) {
    
    if(typeof fileExplorerWidgetParams == 'undefined')       
        var elementiPerPage = 10;
    else
        var elementiPerPage = fileExplorerWidgetParams.pageSize;
    
    var el = $('.file-explorer-container .files-list > div.item');
    if(el.hasClass('archiving'))
        var el = $('.file-explorer-container .files-list > div.item.archiving');
    
    var startIndex = (page - 1) * elementiPerPage;
    var endIndex = startIndex + elementiPerPage;
    el.hide().slice(startIndex, endIndex).show();
  
}

function pagination(el,page,event) {
    
    event.preventDefault();
    
    var container = $(el).closest(".pagination");
    var li = $(el).closest("li");

    var tot = container.find("li").length - 1;

    if(li.hasClass("next")){

        var sel = container.find("li.active");

        if(sel.index() == tot - 1)
            return;

        sel.removeClass("active");
        var next = sel.next();

        next.addClass("active");
        
        page = next.find("a").data("page");

    }else if(li.hasClass("prev")){

        var sel = container.find("li.active");

        if(sel.index() == 1)
            return;

        sel.removeClass("active");
        var prev = sel.prev();

        prev.addClass("active");
      
        page = prev.find("a").data("page");

    }else{
       
        container.find("li").removeClass("active");
        li.addClass("active");

    }

    showPage(page);
  
}
