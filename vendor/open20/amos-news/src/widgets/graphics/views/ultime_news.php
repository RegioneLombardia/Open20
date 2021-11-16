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
use open20\amos\news\AmosNews;
use open20\amos\news\assets\ModuleNewsAsset;
use open20\amos\news\models\News;
use open20\amos\news\widgets\graphics\WidgetGraphicsUltimeNews;
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
<div class="grid-item grid-item--width2 grid-item--height2">
    <div class="box-widget latest-news">
        <div class="box-widget-toolbar">
            <h1 class="box-widget-title col-xs-10 nop"><?= AmosNews::t('amosnews', 'Ultime notizie') ?></h1>
            <?php
            if (isset($moduleNews) && !$moduleNews->hideWidgetGraphicsActions) {
                WidgetGraphicsActions::widget([
                    'widget' => $widget,
                    'tClassName' => AmosNews::className(),
                    'actionRoute' => '/news/news/create',
                    'toRefreshSectionId' => $toRefreshSectionId
                ]);
            }
            ?>
        </div>

        <section class="clearfixplus">
            <h2 class="sr-only"><?= AmosNews::t('amosnews', 'Ultime notizie') ?></h2>
            <?php
            $alt = AmosNews::t('amosnews', 'Immagine della notizia');

            Pjax::begin(['id' => $toRefreshSectionId]);
            if (count($listaModels) == 0) :
                $textReadAll  = AmosNews::t('amosnews', '#addNews');
                $linkReadAll  = '/news/news/create';
                $checkPermNew = true;
                $out          = '<div class="list-items list-empty"><h2 class="box-widget-subtitle">'
                    .AmosNews::t('amosnews', 'Nessuna notizia')
                    .'</h2></div>';
                echo $out;
            else:
                $textReadAll  = AmosNews::t('amosnews', 'Visualizza Elenco News');
                $linkReadAll  = ['/news/news/all-news'];
                $checkPermNew = false;
                ?>
                <div class="list-items">
                    <?php foreach ($listaModels as $news) : /** @var News $news */ ?>
                        <div class="col-xs-12 col-sm-4 col-md-4 widget-listbox-option" role="option">
                            <article class="col-xs-12 nop">
                                <div class="container-img">
                                    <?php
                                    $url = '/img/img_default.jpg';
                                    if (!is_null($news->newsImage)) {
                                        $url = $news->newsImage->getUrl('dashboard_news_old', false, true);
                                    }

                                    echo Html::img($url, ['class' => 'img-responsive', 'alt' => $alt]);
                                    ?>
                                </div>

                                <div class="container-text clearfixplus">
                                    <div class="col-xs-12 listbox-date">
                                        <?php if (isset($moduleNews) && !$moduleNews->hidePubblicationDate) : ?>
                                            <p><?= Yii::$app->getFormatter()->asDate($news->data_pubblicazione); ?></p>
                                        <?php endif; ?>
                                    </div>

                                    <div class="col-xs-12">
                                        <h2 class="box-widget-subtitle">
                                            <?php
                                            if (strlen($news->titolo) > 55) {
                                                $stringCut = substr($news->titolo, 0, 55);
                                                echo substr($stringCut, 0, strrpos($stringCut, ' ')).'... ';
                                            } else {
                                                echo $news->titolo;
                                            }
                                            ?>
                                        </h2>

                                        <p class="box-widget-text">
                                            <?php
                                            if (strlen($news->descrizione_breve) > 80) {
                                                $stringCut = substr($news->descrizione_breve, 0, 80);
                                                echo substr($stringCut, 0, strrpos($stringCut, ' ')).'... ';
                                            } else {
                                                echo $news->descrizione_breve;
                                            }
                                            ?>
                                        </p>
                                    </div>
                                </div>

                                <div class="footer-listbox col-xs-12 m-t-5 nop">
                                    <?= Html::a(AmosNews::t('amosnews', 'LEGGI TUTTO'),
                                        ['/news/news/view', 'id' => $news->id],
                                        ['class' => 'btn btn-navigation-primary']);
                                    ?>
                                </div>
                            </article>
                        </div>
                <?php endforeach; ?>
                </div>
<?php endif; ?>
<?php Pjax::end(); ?>
        </section>
        <div class="read-all"><?= Html::a($textReadAll, $linkReadAll, ['class' => ''], $checkPermNew); ?></div>
    </div>
</div>
