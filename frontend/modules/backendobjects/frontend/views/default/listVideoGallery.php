<?php

use app\modules\backendobjects\frontend\Module;
use kartik\datecontrol\DateControl;
use luya\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


use open20\amos\layout\assets\BootstrapItaliaCustomSpriteAsset;
$spriteAsset = BootstrapItaliaCustomSpriteAsset::register($this);

?>
<?php
$jsYoutube = <<<JS
    /* PLAYLIST YOUTUBE */
    $('.wrap-playlist .wrap-video-thumbs .wrap-video').click(function () {
      var videoId = $(this).find('img').data('video');
      $('.wrap-active-video iframe').attr('src', 'https://www.youtube.com/embed/' + videoId);
      $('.wrap-playlist .wrap-video-thumbs .wrap-video').removeClass('active');
      $(this).addClass('active');
  });

JS;
$this->registerJs($jsYoutube);
?>


<div class="<?= $cssClass ?> py-5">
    <?php if (!$withoutSearch) { ?>
        <?php
        $form = ActiveForm::begin([
            'action' => Url::toRoute(['/backendobjects']),
            'method' => 'get',
            'options' => [
                'id' => 'element_form_' . $model->id,
                'class' => 'form wrap-search',
                'enctype' => 'multipart/form-data',
            ]
        ]);
        ?>

        <?php
        foreach ($searchFields as $field) {
            switch ($field->type) {
                case "TEXT":
        ?>
                    <div class="col-xs-12 col-sm-6 col-lg-4">
                        <?= $form->field($model, $field->name) ?>
                    </div>
                <?php
                    break;
                case "DATE":
                ?>
                    <div class="col-xs-12 col-sm-6 col-lg-4">
                        <?=
                            $form->field($model, $field->name)->widget(DateControl::className(), [
                                'type' => DateControl::FORMAT_DATE
                            ])
                        ?>
                    </div>
        <?php
                    break;
            }
        }
        ?>

        <div class="col-xs-12 wrap-btn">
            <?= Html::a(Module::t('Annulla'), [Yii::$app->controller->action->id, 'currentView' => Yii::$app->request->getQueryParam('currentView')], ['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton(Module::t('Cerca'), ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    <?php } // !$withoutSearch 
    ?>

    <div class="clearfix"></div>
    <h3 class="mb-4 h1">
        <span class="mdi mdi-video-box"></span>
        Video</h3>
    <?php
    if (!is_null($dataProvider)) {
        if ($dataProvider->getTotalCount() > 0) {

            //echo $dataProvider->getModels();
            $models = $dataProvider->getModels();

            $firstVideo = $models[0];
            $i = 0;
    ?>

            <div class="wrap-playlist" id="video-active">
                <div class="wrap-active-video">
                    <iframe src="<?= $firstVideo->getUrlEmbeddedVideo() ?>?rel=0" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <div class="wrap-video-thumbs">
                    <?php foreach ($models as $model) : ?>
                        <?php
                        $match = [];
                        $idVideo = '';
                        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $model->url_video, $match);
                        $idVideo = $match[1];
                        ?>
                        <a class="wrap-video" href="#video-active" uk-scroll="offset: 100">


                            <div class="uk-inline">
                                <div class="img-video">


                                    <img src="https://img.youtube.com/vi/<?= $idVideo ?>/mqdefault.jpg" alt="<?= $model->title ?>" data-video="<?= $idVideo ?>" />
                                </div>
                                <div class="uk-overlay uk-overlay-default uk-position-bottom py-2">
                                    <p><?= $model->title ?></p>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

        <?php } else {
            $js = <<<JS
    $('.section-video').attr('style',"display:none !important");
    $('#menu-video').remove();

JS;
            $this->registerJs($js);
        } ?>
    <?php } else {
        $js = <<<JS
    $('.section-video').attr('style',"display:none !important");


JS;
        $this->registerJs($js);
    } ?>
</div>