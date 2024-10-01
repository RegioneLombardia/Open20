<?php

$landscapeColsNumber = (isset($landscapeColsNumber)) ? $landscapeColsNumber : 'one';

?>
<div class="it-carousel-wrapper it-carousel-landscape-abstract-<?= $landscapeColsNumber ?>-cols owl-carousel-design owl-carousel-design-news">
  <div class="it-carousel-all owl-carousel">
    <div class="it-single-slide-wrapper">
      <!--start card-->
      <?=
          $this->render(
            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-news',
            [
              'category' => 'ULTIME DALLA P.A.',
              'image' => 'https://picsum.photos/1600/900?random=6',
              'nameSurname' => 'Paolo Bianchi',
              
              'showAvatar' => true,
              'itemDirection' => 'row',
              'carouselClass' =>'newsInsideCarousel',
              'avatarWrapperSize' => 'md',
              'categorySize'=> 'lead',
              'titleSize'=> 'h3',
              'title' =>'Lorem ipsum dolor sit amet',
              'date' => '2020-12-12',
              'widthColumn' => 'col-12'
            ]
          );
        ?>
      <!--end card-->
    </div>
    <div class="it-single-slide-wrapper">
      <!--start card-->
      <?=
          $this->render(
            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-news',
            [
              'category' => 'ULTIME DALLA P.A.',
              'image' => 'https://picsum.photos/1600/900?random=7',
              'nameSurname' => 'Paolo Bianchi',
              'title' =>'Lorem ipsum dolor sit amet ipsum dolor sit amet',
              'showAvatar' => true,
              'itemDirection' => 'row',
              'carouselClass' =>'news-inside-carousel',
              'date' => '2020-12-12',
              'widthColumn' => 'col-12'
            ]
          );
        ?>
      <!--end card-->
    </div>
    <div class="it-single-slide-wrapper">
      <!--start card-->
      <?=
          $this->render(
            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-news',
            [
              'category' => 'ULTIME DALLA P.A.',
              'image' => 'https://picsum.photos/1600/900?random=8',
              'nameSurname' => 'Paolo Bianchi',
              'title' =>'Io sono una stringa da 75 caratteri totali e da qui in poi non so cosa fare',
              'showAvatar' => true,
              'itemDirection' => 'row',
              'carouselClass' =>'news-inside-carousel',
              'date' => '2020-12-12',
              'widthColumn' => 'col-12'
            ]
          );
        ?>
      <!--end card-->
    </div>

    <div class="it-single-slide-wrapper">
      <!--start card-->
      <?=
          $this->render(
            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-news',
            [
              'category' => 'ULTIME DALLA P.A.',
              'image' => 'https://picsum.photos/1600/900?random=9',
              'nameSurname' => 'Paolo Bianchi',
              'title' =>'Io sono una stringa da 75 caratteri totali e da qui in poi non so cosa fare',
              'showAvatar' => true,
              'itemDirection' => 'row',
              'carouselClass' =>'news-inside-carousel',
              'date' => '2020-12-12',
              'widthColumn' => 'col-12'
            ]
          );
        ?>
      <!--end card-->
    </div>
    <div class="it-single-slide-wrapper">
      <!--start card-->
      <?=
          $this->render(
            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-news',
            [
              'category' => 'ULTIME DALLA P.A.',
              'image' => 'https://picsum.photos/1600/900?random=10',
              'nameSurname' => 'Paolo Bianchi',
              'title' =>'Io sono una stringa da 75 caratteri totali e da qui in poi non so cosa fare',
              'showAvatar' => true,
              'itemDirection' => 'row',
              'carouselClass' =>'news-inside-carousel',
              'date' => '2020-12-12',
              'widthColumn' => 'col-12'
            ]
          );
        ?>
      <!--end card-->
    </div>
    <div class="it-single-slide-wrapper">
      <!--start card-->
      <?=
          $this->render(
            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-news',
            [
              'category' => 'ULTIME DALLA P.A.',
              'image' => 'https://picsum.photos/1600/900?random=11',
              'nameSurname' => 'Paolo Bianchi',
              'title' =>'Io sono una stringa da 75 caratteri totali e da qui in poi non so cosa fare',
              'showAvatar' => true,
              'itemDirection' => 'row',
              'carouselClass' =>'news-inside-carousel',
              'date' => '2020-12-12',
              'widthColumn' => 'col-12'
            ]
          );
        ?>
      <!--end card-->
    </div>
  </div>
</div>