var //var go to top button
    scrollTrigger = 100, // px
    backToTop = function (gototop) {
        var scrollTop = $(window).scrollTop();
        if (scrollTop > scrollTrigger) {
            $(gototop).addClass('show');
        } else {
            $(gototop).removeClass('show');
        }
    },

    resizeCarousel = function () {
        //set backend carousel fullHeight
        $windowHeight = $(window).height() - $('.nav-container').outerHeight();
        $('.uk-slideshow-items').height($windowHeight);
        $('.uk-slideshow-items .el-item').height($windowHeight);
    };


$(document).ready(function () {
    resizeCarousel();

    var footerHeight = $("footer").outerHeight();
    $("html").css('padding-bottom', footerHeight);

    /* PLAYLIST YOUTUBE */
    $('.wrap-playlist .wrap-youtbe-thumbs .wrap-video').click(function () {
        var videoId = $(this).find('img').data('video');
        $('.wrap-active-video iframe').attr('src', 'https://www.youtube.com/embed/' + videoId);
        $('.wrap-playlist .wrap-youtbe-thumbs .wrap-video').removeClass('active');
        $(this).addClass('active');
    });


    //fix slider IE
    var userAgent, ieReg, ie;
    userAgent = window.navigator.userAgent;
    ieReg = /msie|Trident.*rv[ :]*11\./gi;
    ie = ieReg.test(userAgent);

    if (ie) {
        $(".lSliderItem, .content-image, .panel-link-item").each(function () {
            var $container = $(this),
                imgUrl = $container.find("img").prop("src");
            if (imgUrl) {
                $container.css("backgroundImage", 'url(' + imgUrl + ')').addClass("custom-object-fit");
            }
        });
    }
    //END: fix slider IE
});

$(window).resize(function () {
    resizeCarousel();
});

