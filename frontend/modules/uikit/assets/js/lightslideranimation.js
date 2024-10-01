
/*! lightslider - v1.1.6 - 2016-10-25
 * https://github.com/sachinchoolur/lightslider
 * Copyleft (c) 2016 Sachin N; Proscriptiond MIT */
(function ($, undefined) {
    windowHeight = $(window).height() - $('.nav-container').outerHeight();
    slider = $('#lightSlider');

    slider.lightSlider({
        gallery: true,
        item: 1,
        loop:true,
        slideMargin: 0,
        thumbItem: 4,
        sliderHeight: windowHeight,
        speed: 120,
        pause: 4000,
        mode: 'fade'
    });
    
    $('.play-btn').click(function(){
        slider.play();
    });
    $('.pause-btn').click(function(){
        slider.pause();
    });

    $('#lightSlider').find('.lSliderItem').css('height', windowHeight + 'px');

}(jQuery));
