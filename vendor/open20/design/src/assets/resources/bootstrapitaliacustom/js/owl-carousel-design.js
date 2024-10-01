$('.it-carousel-landscape-abstract-four-cols .owl-carousel').owlCarousel({
  margin: 40,
  items: 4,
  responsiveClass: true,
  nav: true,
  stagePadding: 30,
  loop: true,
  dots: true,
  onInitialized: counter, /* Aggiungi un conteggio iniziale*/
  onChanged: counter, /* Aggiorna il conteggio quando cambiano gli elementi*/
  responsive: {
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
      items: 4,
    }
  }

});
$('.it-carousel-landscape-abstract-three-cols .owl-carousel').owlCarousel({
  margin: 40,
  items: 3,
  responsiveClass: true,
  stagePadding: 30,
  nav: true,
  loop: true,
  dots: true,
  onInitialized: counter, /* Aggiungi un conteggio iniziale*/
  onChanged: counter, /* Aggiorna il conteggio quando cambiano gli elementi*/
  responsive: {
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

});

$('.it-carousel-landscape-abstract-two-cols .owl-carousel').owlCarousel({
  margin: 40,
  items: 2,
  responsiveClass: true,
  stagePadding: 30,
  nav: true,
  loop: true,
  dots: true,
  onInitialized: counter, /* Aggiungi un conteggio iniziale*/
  onChanged: counter, /* Aggiorna il conteggio quando cambiano gli elementi*/
  responsive: {
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
      items: 2,
    },
    1200: {
      items: 2,
    }
  }

});

$('.it-carousel-landscape-abstract-one-cols .owl-carousel').owlCarousel({
  margin: 60,
  items: 1,
  responsiveClass: true,
  stagePadding: 30,
  nav: true,
  loop: true,
  dots: true,
  onInitialized: counter, /* Aggiungi un conteggio iniziale*/
  onChanged: counter, /* Aggiorna il conteggio quando cambiano gli elementi*/
  responsive: {
    0: {
      items: 1,
    },
    550: {
      items: 1,
    },
    767: {
      items: 1,
    },
    992: {
      items: 1,
    },
    1200: {
      items: 1,
    }
  }

});

$('.owl-carousel-herobanner-design .owl-carousel').owlCarousel({
  margin: 0,
  items: 1,
  responsiveClass: true,
  stagePadding: 0,
  nav: true,
  loop: true,
  responsive: {
    0: {
      items: 1,
    },
    550: {
      items: 1,
    },
    767: {
      items: 1,
    },
    992: {
      items: 1,
    },
    1200: {
      items: 1,
    }
  }

});

$('.it-carousel-landscape-abstract.autoplay .owl-carousel').owlCarousel({
  autoplay: true,
  autoplayTimeout: 10000,
  items: 1,
  loop: true,
});

$(".owl-prev").html('<span class="mdi mdi-chevron-left"></span><span class="sr-only">Slider precedenti</span>');
$(".owl-next").html('<span class="mdi mdi-chevron-right"></span><span class="sr-only">Slider successive</span>');
function counter(event) {
  var element = event.target; /* Ottieni l'elemento Owl Carousel */
  var items = event.item.count; /* Ottieni il numero totale di elementi */
  var item = event.item.index + 1; /* Ottieni l'indice corrente */
  /* Aggiungi la numerazione invisibile ai punti */
  $('.owl-dots').find('.owl-dot').each(function(index){
    $(this).html('<span></span><span class="sr-only">Slide ' + (index + 1) + '</span>');
  });
}
