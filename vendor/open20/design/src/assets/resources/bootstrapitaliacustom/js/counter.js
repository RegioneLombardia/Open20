var a = 0;
$(window).scroll(function() {

  var oTop = $('.counter-container').offset().top - window.innerHeight;
  if (a == 0 && $(window).scrollTop() > oTop) {
    $('.counter-value').each(function() {
      
        var $this = $(this);
        jQuery({
          Counter: 0
        }).animate({
          Counter: $this.text().replace(/,/g, '')
        }, {
          duration: 1500,
          easing: 'swing',
          step: function() {
            $this.text(commaSeparateNumber(Math.floor(this.Counter)));
          },
          complete: function() {
            $this.text(commaSeparateNumber(this.Counter));
            //alert('finished');
          }
        });
      });
  
      function commaSeparateNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
      }
    a = 1;
  }

});