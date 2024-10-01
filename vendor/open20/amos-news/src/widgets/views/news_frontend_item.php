<?php
use open20\amos\core\helpers\Html;
use open20\amos\news\AmosNews;
?>
<?php /** @var $model \open20\amos\news\models\base\News */?>

    <div class="col-xs-12">
        <div class="col-xs-12 news-image">
            <?php
            $url = '/img/img_default.jpg';
            if (!is_null($model->newsImage)) {
                $url = $model->newsImage->getWebUrl('square_medium', false, true);
            }
            $contentImage = Html::img($url, [
                'class' => 'img-responsive',
                'alt' => 'image'
            ]);
            ?>
            <?= $contentImage ?>
        </div>
        <div class="col-xs-12 news-title">
            <h1><?= $model->titolo?></h1>
        </div>
        <div class="col-xs-12 news-subtitle">
            <h2><?= $model->sottotitolo?></h2>
        </div>
        <div class="col-xs-12 news-abstract">
            <p><?= $model->descrizione_breve?></p>
        </div>
        <div class="col-xs-12 news-description">
            <p><?= $model->descrizione?></p>
        </div>
        <?php if(\Yii::$app->getModule('sitemanagement') ){
            $url = \amos\sitemanagement\widgets\SMContainerWidget::getUrlContentModel($model)?>
            <div class="col-xs-12 news-read-all">
                <p><?= Html::a(AmosNews::t('amosnews', 'Leggi tutto'), $url) ?></p>
            </div>
        <?php } else { ?>
            <div class="col-xs-12 news-read-all">
                <p><?= Html::a(AmosNews::t('amosnews', 'Leggi tutto'), $model->getFullViewUrl()) ?></p>
            </div>
        <?php } ?>
    </div>
