<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\widgets\graphics\views
 * @category   CategoryName
 */

/**
 * @var View $this
 * @var ActiveDataProvider $listaDocumenti
 * @var WidgetGraphicsUltimeDocumenti $widget
 * @var string $toRefreshSectionId
 */

use open20\amos\core\forms\WidgetGraphicsActions;
use open20\amos\core\icons\AmosIcons;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\Documenti;
use open20\amos\documenti\widgets\graphics\WidgetGraphicsUltimeDocumenti;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Pjax;

?>

<div class="box-widget">
    <div class="box-widget-toolbar row nom">
        <h1 class="box-widget-title col-xs-10 nop"><?= AmosDocumenti::tHTml('amosdocumenti', 'Ultimi documenti') ?></h1>
        <?= WidgetGraphicsActions::widget([
            'widget' => $widget,
            'tClassName' => AmosDocumenti::className(),
            'actionRoute' => '/documenti/documenti/create',
            'toRefreshSectionId' => $toRefreshSectionId
        ]); ?>
    </div>
    <section>
        <h2 class="sr-only"><?= AmosDocumenti::t('amosdocumenti', 'Ultimi documenti') ?></h2>
        <?php Pjax::begin(['id' => $toRefreshSectionId]); ?>
        <div role="listbox">
            <?php
            if (count($listaDocumenti->getModels()) == 0): ?>
            <div class="list-items">
                <h2 class="box-widget-subtitle"><?= AmosDocumenti::tHtml('amosdocumenti', 'Nessun documento'); ?></h2>
            </div>
            <?php
            else:
            ?>
            <div class="list-items">
                <?php
                foreach ($listaDocumenti->getModels() as $documenti):
                    /** @var Documenti $documenti */
                    ?>
                    <div class="widget-listbox-option row" role="option">
                        <article class="col-xs-12 nop">
                            <div class="container-icon">
                                <?= AmosIcons::show('download-general',['class' => 'icon_widget_graph'], 'dash') ?>
                            </div>
                            <div class="container-text row">
                                <div class="col-xs-12 nopl">
                                    <p><?= Yii::$app->getFormatter()->asDatetime($documenti->created_at); ?></p>
                                    <h2 class="box-widget-subtitle">
                                        <?php
                                        if (strlen($documenti->titolo) > 100) {
                                            $stringCut = substr($documenti->titolo, 0, 100);
                                            echo substr($stringCut, 0, strrpos($stringCut, ' ')) . '... ';
                                        } else {
                                            echo $documenti->titolo;
                                        }
                                        ?>
                                    </h2>
                                </div>
<!--                                <div class="col-xs-12 nopl">-->
<!--                                    <p class="box-widget-text">< ?= $documenti->sottotitolo; ?></p>-->
<!--                                </div>-->
                            </div>
                            <div class="col-xs-12 m-t-5 nop">
                                <span class="pull-right">
                                    <?= Html::a(AmosDocumenti::t('amosdocumenti', 'VISUALIZZA'), ['/documenti/documenti/view', 'id' => $documenti->id], ['class' => 'btn btn-navigation-primary']); ?>
                                </span>
                            </div>
                        </article>
                    </div>
                    <?php
                endforeach;
                ?>
                </div>
                    <?= Html::a(AmosDocumenti::t('amosdocumenti', 'Visualizza Elenco Documenti'), ['/documenti'], ['class' => 'read-all']); ?>
                <?php
            endif;
            ?>
        </div>
        <?php Pjax::end(); ?>
    </section>
</div>