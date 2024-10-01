<?php

use open20\socialwall\utility\SocialWallItem;
use open20\amos\core\module\BaseAmosModule;

$postContainerId = !empty($postContainerId) ? $postContainerId : null;
$showDeleteButton = !empty($showDeleteButton) ? $showDeleteButton : false;

//\yii\helpers\VarDumper::dump($media,4,true);
//\yii\helpers\VarDumper::dump(htmlspecialchars_decode($post['post_image_url']),4,true);die;
$modelOrTemplate = function ($item) use ($post) {
    if (!empty($post))
        return $post[$item];
    return '${' . $item . '}';
};
?>

<div class="col-12 instagram-wall" <?= !empty($postContainerId) ? "data-socialwall-post-id=\"{$postContainerId}\"" : ""; ?>>
    <div class="it-grid-item-wrapper p-2">
        <div class="p-3">
            <?php if($showDeleteButton) : ?>
                <div class="socialwall-post-action-element m-b-5">
                    <?=
                    \yii\helpers\Html::button(
                        'x',
                        [
                            'class' => 'btn btn-danger',
                            'onClick' => "window.removeSocialwallPostAction({$postContainerId})",
                        ]
                    );
                    ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($showPostCheckboxes)) : ?>
                <?php if (!empty($post)) : ?>
                    <div class="select-social-post m-b-20 btn" data-post-id="<?= $key; ?>">
                        <?= \open20\amos\core\icons\AmosIcons::show('plus', [
                            'style' => "font-size: 30px;",
                            'class' => "icon-check",
                        ]); ?>
                        <?php if(!empty($postCheckboxesLabel)) : ?>
                            <span class="label-checkboxes">
                                <?= $postCheckboxesLabel ?>
                            </span>
                        <?php endif; ?>
                    </div>
                <?php else : ?>
                    ${key !== "" && key !== null ?
                    '<div class="select-social-post m-b-20 btn" data-post-id="'+ key +'">' +
                        '<?= \open20\amos\core\icons\AmosIcons::show('plus', [
                            'style' => "font-size: 30px;",
                            'class' => "icon-check",
                        ]); ?>' +
                        '</div>' : ''}
                <?php endif; ?>
            <?php endif; ?>
            <div class="facebook_logo text-right pb-3 px-0">
                <span class="icon icon-lg icon-white mdi mdi-36px  mdi-facebook"></span>
            </div>
            <div class="img-responsive-wrapper">
                <div class="img-responsive">
                    <div class="img-wrapper">
<!--                        --><?php
//                        if ($media->getItem_type() == SocialWallItem::INSTAGRAM_TYPE) {
//
//                            switch ($media->media_type) {
//                                case "IMAGE":
//                                    echo '<img src="' . $media->media_url . '">';
//                                    break;
//                                case "VIDEO":
//                                    echo '<video width="100%" controls loop><source src="' . $media->media_url . '" type="video/mp4"/></video>';
//                                    break;
//                                default:
//                                    echo '<img src="' . $media->media_url . '">';
//                                    break;
//                            }
//                        }
//                        ?>
                        <?php if(!empty($modelOrTemplate('post_image_url'))) : ?>
<!--                        <img src="--><?php //= $modelOrTemplate('post_image_url'); ?><!--">-->
                        <img src="<?= htmlspecialchars_decode($modelOrTemplate('post_image_url')); ?>">
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="avatar-wrapper avatar-extra-text px-0 pt-3">
                <div class="avatar size-lg">
                    <?php
//                    echo '<img src="' . $modelOrTemplate("profile_picture_url") . '">';
                    echo '<img src="' . htmlspecialchars_decode($modelOrTemplate('profile_picture_url')) . '" width="100%">';
                    ?><!-- <img src="https://randomuser.me/api/portraits/women/33.jpg" alt="Giulia Neri"> -->
                </div>
                <div class="extra-text">
                    <a href="<?= $modelOrTemplate('profile_url'); ?>" target="_blank"><?= BaseAmosModule::t('app', "") ?> <?= $modelOrTemplate('name'); ?></a>
                    <p class="public_date"><?= BaseAmosModule::t('app', "") ?> <?= $modelOrTemplate('post_nice_datetime'); ?></p>
                </div>
            </div>

            <div class="insta-description truncate pb-1 px-0">
                <p class="m-0"><?= nl2br($modelOrTemplate('post_text')); ?></p>
            </div>

            <?php
            if (!empty($modelOrTemplate('permalink'))) :
            ?>
                <div class="text-right px-2 pb-2">
                    <a href="<?= $modelOrTemplate('permalink'); ?>" title="Vai al post" target="_blank"><span class="icon icon-sm mdi mdi-open-in-new mdi-24px"></span></a>
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