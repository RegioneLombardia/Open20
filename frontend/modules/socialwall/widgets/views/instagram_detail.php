<?php

use frontend\modules\socialwall\utility\SocialWallItem;
use open20\amos\core\module\BaseAmosModule;

//\yii\helpers\VarDumper::dump($media,4,true);
?>

<div class="col-12 instagram-wall">
    <div class="it-grid-item-wrapper p-2">
        <div class="p-3">
            <div class="insta_logo text-right p-3">
                <span class="icon icon-lg icon-white mdi mdi-instagram"></span>
            </div>
            <div class="img-responsive-wrapper">
                <div class="img-responsive">
                    <div class="img-wrapper">
                        <?php
                        if ($media->getItem_type() == SocialWallItem::INSTAGRAM_TYPE) {

                            switch ($media->media_type) {
                                case "IMAGE":
                                    echo '<img src="' . $media->media_url . '">';
                                    break;
                                case "VIDEO":
                                    echo '<video width="100%" controls loop><source src="' . $media->media_url . '" type="video/mp4"/></video>';
                                    break;
                                default:
                                    echo '<img src="' . $media->media_url . '">';
                                    break;
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div class="avatar-wrapper avatar-extra-text px-4 pt-3">
                <div class="avatar size-lg d-none">
                    <?php
                    echo '<img src="' . $media->userinfo["profile_image_url"] . '">';
                    ?><!-- <img src="https://randomuser.me/api/portraits/women/33.jpg" alt="Giulia Neri"> -->
                </div>
                <div class="extra-text m-0">
                    <a href="https://www.instagram.com/<?= $media->username ?>/" target="_blank"><?= BaseAmosModule::t('app', "") ?> <?= $media->username ?></a>
                    <p class="public_date"><?= BaseAmosModule::t('app', "") ?> <?= $media->publication_date() ?></p>
                </div>
            </div>

            <div class="insta-description truncate pb-1 px-4">
                <p class="m-0"><?= nl2br($media->description()) ?></p>
            </div>

            <?php
            if (isset($media->permalink) && !empty($media->permalink)) :
            ?>
                <div class="text-right px-2 pb-2">
                    <a href="<?= $media->permalink ?>" title="Vai al post" target="_blank"><span class="icon icon-sm mdi mdi-open-in-new"></span></a>
                </div>
            <?php
            endif;
            ?>
        </div>
    </div>
</div>

<style>
    a.moreless {
        font-weight: bold;
        color: #000 !important;
        white-space: nowrap;
    }
</style>

<?php
$js = <<<JS

        $(document).ready(function() {
  (function() {
    var showChar = 350;
    var ellipsestext = "...";

    $(".truncate").each(function() {
      var content = $(this).html();
      if (content.length > showChar) {
        var c = content.substr(0, showChar);
        var h = content;
        var html =
          '<div class="truncate-text" style="display:block">' +
          c +
          '<span class="moreellipses">' +
          ellipsestext +
          '&nbsp;&nbsp;<a href="" class="moreless more">Leggi tutto</a></span></span></div><div class="truncate-text" style="display:none">' +
          h +
          '<a href="" class="moreless less">Chiudi</a></span></div>';

        $(this).html(html);
      }
    });

    $(".moreless").click(function() {
      var thisEl = $(this);
      var cT = thisEl.closest(".truncate-text");
      var tX = ".truncate-text";

      if (thisEl.hasClass("less")) {
        cT.prev(tX).toggle();
        cT.toggle();
      } else {
        cT.toggle();
        cT.next(tX).fadeToggle();
      }
      return false;
    });
    /* end iffe */
  })();

  /* end ready */
});

JS;
$this->registerJs($js);
?>