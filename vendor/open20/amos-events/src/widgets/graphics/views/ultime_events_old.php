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
use open20\amos\events\AmosEvents;
use open20\amos\events\models\Events;
use open20\amos\news\widgets\graphics\WidgetGraphicsUltimeNews;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Pjax;

/**
 * @var View $this
 * @var ActiveDataProvider $listaNews
 * @var WidgetGraphicsUltimeNews $widget
 * @var string $toRefreshSectionId
 */

$moduleEvents = \Yii::$app->getModule(AmosEvents::getModuleName());
?>

<div class="box-widget">
    <div class="box-widget-toolbar row nom">
        <h1 class="box-widget-title col-xs-10 nop"><?= AmosEvents::t('amosevents', 'Ultime notizie') ?></h1>
        <?php
        if(isset($moduleNews) && !$moduleNews->hideWidgetGraphicsActions) {
            echo WidgetGraphicsActions::widget([
                'widget' => $widget,
                'tClassName' => AmosEvents::className(),
                'actionRoute' => '/events/event/create',
                'toRefreshSectionId' => $toRefreshSectionId
            ]);
        } ?>
    </div>
    <section>
        <h2 class="sr-only"><?=  AmosEvents::t('amosevents', 'Ultimi Eventi') ?></h2>
        <?php Pjax::begin(['id' => $toRefreshSectionId]); ?>
        <div role="listbox">
            <?php
            if (count($listaEvents>getModels()) == 0):
//                echo '<div class="list-items"><h2 class="box-widget-subtitle">Nessuna notizia</h2></div>';
                $out  = '<div class="list-items"><h2 class="box-widget-subtitle">';
                $out .= AmosEvents::t('amosevents', 'Nessuna notizia');
                $out .= '</h2></div>';
                echo $out;
            else:
                ?>
                <div class="list-items">
                    <?php
                     foreach ($listaEvents->getModels() as $event):
                        ?>
                    <div class="widget-listbox-option row" role="option">
                                <article class="col-xs-12 nop">
                                    <div class="container-img">
                                        <?php
                                        $url = '/img/img_default.jpg';
                                        if (!is_null($event->eventLogo)) {
                                            $url = $event->eventLogo->getUrl('original', false, true);
                                        }
                                        ?>
                                        <?=
                                        Html::img($url, [
                                            'title' => $event->getAttributeLabel('eventLogo'),
                                            'class' => 'img-responsive img-round'
                                        ]);
                                        ?>
                                    </div>
                                    <div class="container-text clearfixplus">
                                        <div class="col-xs-9">
                                            <p class="media-heading">
                                                <?= AmosEvents::t('amosevents', 'Event'); ?>
                                            </p>
                                            <p class="media-heading">
                                                <?= $event->title ?>
                                            </p>
                                        </div>


                                        <div class="col-xs-12 nop m-t-15">
                                            <div class="col-sm-4 col-xs-12">
                                                <?= $event->getAttributeLabel('eventType') ?>
                                                <br/>
                                                <span class="bold"><?= !is_null($event->eventType) ? $event->eventType->title : '-' ?></span>
                                            </div>
                                            <div class="col-sm-4 col-xs-12">
                                                <?= AmosEvents::t('amosevents', 'Country') ?>
                                                <br/>
                                                <span class="bold"><?= ($event->countryLocation) ? $event->countryLocation->nome : '-' ?></span>
                                            </div>
                                            <div class="col-sm-4 col-xs-12">
                                                <?= AmosEvents::t('amosevents', 'City') ?>
                                                <br/>
                                                <span class="bold"><?= ($event->cityLocation) ? $event->cityLocation->nome : '-' ?>
                                                    <?= ($event->provinceLocation) ? ' (' . $event->provinceLocation->sigla . ')' : '' ?></span>
                                            </div>
                                        </div>

                                </div>
                                <div class="footer-listbox col-xs-12 m-t-5 nop">
                                    <?= Html::a(AmosEvents::t('amosevents', 'LEGGI TUTTO'), ['/events/event/view', 'id' => $event->id], ['class' => 'btn btn-navigation-primary']); ?>
                                </div>
                            </article>
                        </div>
                        <?php
                    endforeach;
                    ?>
                </div>
                <?= Html::a(AmosEvents::t('amosevents', 'Visualizza Elenco News'), ['/events/event/all-events'], ['class' => 'read-all']); ?>
                <?php
            endif;
            ?>
        </div>
        <?php Pjax::end(); ?>
    </section>
</div>