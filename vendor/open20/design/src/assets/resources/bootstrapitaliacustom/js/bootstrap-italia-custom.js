/* ATTIVAZIONE TOOLTIP E POPOVER */
$(function () {
    if ($('[data-toggle="tooltip"]').length && (typeof $('[data-toggle="tooltip"]').tooltip === 'function')) {
        $('[data-toggle="tooltip"]').tooltip();
    }
    if ($('[data-toggle-second="tooltip"]').length && (typeof $('[data-toggle-second="tooltip"]').tooltip === 'function')) {
        $('[data-toggle-second="tooltip"]').tooltip();
    }
});
$(function () {
    if ($('[data-toggle="popover"]').length && (typeof $('[data-toggle="popover"]').popover === 'function')) {
        $('[data-toggle="popover"]').popover();
    }
});
if ($('.popover-dismiss').length && (typeof $('.popover-dismiss').popover === 'function')) {
    $('.popover-dismiss').popover({
        trigger: 'focus'
    });
}


/* EFFETTO SHRINK HEADER */
$(document).on("scroll", function () {
    var headerHeight = $('#headerContent').outerHeight();
    if
            ($(document).scrollTop() > 200) {
        $("body").addClass("shrink");
        $('#mainContent > #sidebarLeftRedattore > .sidebar-nav.affix-top').css('top', Number(headerHeight) + (Number(20)));
    } else {
        $("body").removeClass("shrink");
    }
});

/* DATE PICKER */
$(document).ready(function () {
    if ($('.it-date-datepicker').length && (typeof $('.it-date-datepicker').datepicker === 'function')) {
        $('.it-date-datepicker').datepicker({
            inputFormat: ["dd/MM/yyyy"],
            outputFormat: 'dd/MM/yyyy'
        });
    }
    if ($('.it-datepicker-narrow').length && (typeof $('.it-datepicker-narrow').datepicker === 'function')) {
        $('.it-datepicker-narrow').datepicker({
            inputFormat: ["dd/MM/yyyy"],
            outputFormat: 'dd/MM/yyyy',
            weekDayFormat: 'narrow'
        });
    }

    if ($('.it-date-datepicker').length && (typeof $('.it-date-datepicker').datepicker === 'function')) {
        $('.it-date-datepicker').datepicker({
            inline: 'calendar'
        });
    }


});


/* PUSH UP ASSISTANNCE */
$(document).on("scroll", function () {
    if ($(document).scrollTop() >= 99) {
        $(".bi-assistance").addClass("push-up");
    } else {
        $(".bi-assistance").removeClass("push-up");
    }
});

/**
 * Google Chrome fix autocomplete input field
 */
/*$(document).ready(function () {
 var isChrome = /Chrome/.test(navigator.userAgent) && /Google Inc/.test(navigator.vendor);
 if (isChrome) {
 var allInputInPage = $('input:not([type="hidden"]):not([type="checkbox"])');
 var classFakeRequiredInput = 'js-fake-not-readonly';
 var allInputsFakeRequired = $('.' + classFakeRequiredInput);
 allInputInPage.each(function () {
 $(this).prop('readonly',true);
 $(this).addClass(classFakeRequiredInput);
 $(this).bind('focus', function() {
 $(this).removeClass(classFakeRequiredInput);
 $(this).prop('readonly',false);
 });
 });
 }
 }); */

$(document).ready(function () {
    window.setTimeout(function () {
        var isChrome = /Chrome/.test(navigator.userAgent) && /Google Inc/.test(navigator.vendor);
        var isEdge = /Edg/.test(navigator.userAgent);
        if ((isChrome) || (isEdge)) {
            var allInputInPage = $('input:not([type="hidden"]):not([type="checkbox"])');
            $('input:-webkit-autofill').each(function () {
                $('label[for="' + $(this).attr('id') + '"').addClass('active');
            });
        }
    }, 1);
});


function toggleSidebar() {
    var element = document.getElementById("sidebarLeftRedattore");
    element.classList.toggle("sidebar-small");
}

// Animations trigger
window.addEventListener("load", () => {
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate');
                entry.target.classList.remove('to-animate');
                return; // if we added the class, exit the function
            }

            // We're not intersecting, so remove the class!
            // entry.target.classList.remove('animate');
        });
    });

    document.querySelectorAll('.to-animate').forEach((i) => {
        if (i)
            observer.observe(i);
    });
});