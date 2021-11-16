<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\widgets\graphics\views
 * @category   CategoryName
 */
use open20\amos\core\forms\WidgetGraphicsActions;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\news\AmosNews;
use open20\amos\news\assets\ModuleNewsAsset;
use open20\amos\news\widgets\graphics\WidgetGraphicsUltimeNews;
use kv4nt\owlcarousel\OwlCarouselWidget;
use yii\data\ActiveDataProvider;
use yii\web\View;
use yii\widgets\Pjax;

ModuleNewsAsset::register($this);

/**
 * @var View $this
 * @var ActiveDataProvider $listaNews
 * @var WidgetGraphicsUltimeNews $widget
 * @var string $toRefreshSectionId
 */
$moduleNews  = \Yii::$app->getModule(AmosNews::getModuleName());
$listaModels = $listaNews->getModels();
?>
<div class="box-widget-header">
    <?php
    if (isset($moduleNews) && !$moduleNews->hideWidgetGraphicsActions) {
        echo WidgetGraphicsActions::widget([
            'widget' => $widget,
            'tClassName' => AmosNews::className(),
            'actionRoute' => '/news/news/create',
            'toRefreshSectionId' => $toRefreshSectionId
        ]);
    }
    ?>

    <div class="box-widget-wrapper">
        <h2 class="box-widget-title">
            <?= AmosIcons::show('news', ['class' => 'am-2'], AmosIcons::IC) ?>
            <?= AmosNews::tHtml('amosnews', 'Ultime notizie') ?>
        </h2>
    </div>


    <?php
    if (count($listaModels) == 0) {
        $textReadAll  = AmosNews::t('amosnews', '#addNews');
        $linkReadAll  = '/news/news/create';
        $checkPermNew = true;
    } else {
        $textReadAll  = AmosNews::t('amosnews', '#showAll').AmosIcons::show('chevron-right');
        $linkReadAll  = ['/news/news/all-news'];
        $checkPermNew = false;
    }
    ?>
    <div class="read-all"><?= Html::a($textReadAll, $linkReadAll, ['class' => ''], $checkPermNew); ?></div>
</div>

<div class="box-widget latest-news">
    <section class="<?= (count($listaNews->getModels()) == 0) ? '' : 'list-news-full' ?>">
        <?php Pjax::begin(['id' => $toRefreshSectionId]); ?>
        <?php if (count($listaModels) == 0): ?>
            <div class="list-items list-empty"><h3><?= AmosNews::t('amosnews', 'Nessuna notizia') ?> </h3></div>
            <?php
        else:
            $configuration = [
                'containerOptions' => [
                    'id' => 'newsOwlCarousel'
                ],
                'pluginOptions' => [
                    'autoplay' => false,
                    'items' => 1,
                    'loop' => false,
                    'rewind' => true,
                    'nav' => true,
                    'dots' => true
                ]
            ];

            OwlCarouselWidget::begin($configuration);
            ?>

            <?php
            $news       = $listaModels;
            $lenghtNews = count($news);
            $moduleOf2 = $lenghtNews < 2 ? $lenghtNews + 1 : 2 * floor($lenghtNews / 2);

            for ($i = 1; $i < $moduleOf2; $i += 2) :
                ?>
                <div class="wrap-slide-carousel-box">
                    <?php
                    for ($a = 0; $a < 2; $a++) :
                        if (isset($news[($i + $a - 1)])) :
                            $newsSingola = $news[($i + $a - 1)];
                            ?>
                            <div class="wrap-item-carousel-box" data-index="<?= ($i + $a - 1) ?>">
                                <?php
                                $url         = '/img/img_default.jpg';
                                if (!is_null($newsSingola->newsImage)) {
                                    $url = $newsSingola->newsImage->getUrl('dashboard_news', false, true);
                                }

                                echo Html::img($url,
                                    ['class' => 'img-responsive', 'alt' => AmosNews::t('amosnews',
                                        'Immagine della notizia')]);
                                ?>
                                <div class="abstract">
                                    <div class="box-widget-info-top">
                                        <div class="listbox-label"><?= $newsSingola->category->titolo; ?></div>
                                        <?php if (isset($moduleNews) && !$moduleNews->hidePubblicationDate): ?>
                                            <p><?= Yii::$app->getFormatter()->asDate($newsSingola->data_pubblicazione); ?></p>
                                        <?php endif; ?>
                                    </div>

                                    <?=
                                    Html::a('<h2 class="box-widget-subtitle">'.$newsSingola->titolo.'</h2>',
                                        ['../news/news/view', 'id' => $newsSingola->id]);
                                    ?>

                                    <p class="box-widget-text">
                                        <?php
                                        if (strlen($newsSingola->descrizione_breve) > 200) {
                                            $stringCut = substr($newsSingola->descrizione_breve, 0, 200);
                                            echo substr($stringCut, 0, strrpos($stringCut, ' ')).'... ';
                                        } else {
                                            echo $newsSingola->descrizione_breve;
                                        }
                                        ?>
                                    </p>
                                </div>
                            </div>
                            <?php
                        endif;
                    endfor;
                    ?>
                </div>
                <?php
            endfor;

            OwlCarouselWidget::end();


            $configuration['containerOptions']['id']    = 'newsOwlCarouselTouch';
            $configuration['pluginOptions']['nav']      = false;
            $configuration['pluginOptions']['items']    = 2;
            $configuration['pluginOptions']['dotsEach'] = 1;
            $configuration['pluginOptions']['margin']   = 10;
            OwlCarouselWidget::begin($configuration);

            for ($i = 0; $i < $lenghtNews; $i++) :
                ?>
                <div class="wrap-slide-carousel-box touch">
                        <?php $newsSingola = $news[$i]; ?>
                    <div class="wrap-item-carousel-box" data-index="<?= ($i) ?>">
                        <?php
                        $url         = '/img/img_default.jpg';
                        if (!is_null($newsSingola->newsImage)) {
                            $url = $newsSingola->newsImage->getUrl('dashboard_news', false, true);
                        }
                        ?>
                        <?=
                        Html::img($url,
                            ['class' => 'img-responsive', 'alt' => AmosNews::t('amosnews', 'Immagine della notizia')]);
                        ?>

                        <div class="abstract">
                            <div class="box-widget-info-top">
                                <div class="listbox-label"><?= $newsSingola->category->titolo; ?></div>
                                <?php if (isset($moduleNews) && !$moduleNews->hidePubblicationDate): ?>
                                    <p><?= Yii::$app->getFormatter()->asDate($newsSingola->data_pubblicazione); ?></p>
                            <?php endif; ?>
                            </div>

                            <?=
                            Html::a('<h2 class="box-widget-subtitle">'.$newsSingola->titolo.'</h2>',
                                ['../news/news/view', 'id' => $newsSingola->id]);
                            ?>

                            <p class="box-widget-text">
                                <?php
                                if (strlen($newsSingola->descrizione_breve) > 200) {
                                    $stringCut = substr($newsSingola->descrizione_breve, 0, 200);
                                    echo substr($stringCut, 0, strrpos($stringCut, ' ')).'... ';
                                } else {
                                    echo $newsSingola->descrizione_breve;
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endfor; ?>
    <?php OwlCarouselWidget::end(); ?>
<?php endif; ?>
<?php Pjax::end(); ?>
    </section>
</div>
