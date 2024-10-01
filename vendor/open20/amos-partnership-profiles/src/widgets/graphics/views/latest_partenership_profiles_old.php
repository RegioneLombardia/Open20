<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\widgets\graphics\views
 * @category   CategoryName
 */

use open20\amos\partnershipprofiles\Module;
use open20\amos\core\forms\WidgetGraphicsActions;
use open20\amos\core\helpers\Html;
use yii\data\ActiveDataProvider;
use yii\web\View;
use yii\widgets\Pjax;

/**
 * @var View $this
 * @var ActiveDataProvider $communitiesList
 * @var \open20\amos\partnershipprofiles\widgets\graphics\WidgetGraphicsLatestPartnershipProfiles $widget
 * @var string $toRefreshSectionId
 */

?>


<div class="box-widget">
    <div class="box-widget-toolbar row nom">
        <h2 class="box-widget-title col-xs-10 nop"><?= Module::tHtml('Module', 'Ultime Proposte di collaborazione') ?></h2>
        <?= WidgetGraphicsActions::widget([
            'widget' => $widget,
            'tClassName' => Module::className(),
            'actionRoute' => '/partnershipprofiles/partnership-profiles/create',
            'toRefreshSectionId' => $toRefreshSectionId
        ]); ?>
    </div>
    <section>
        <?php Pjax::begin(['id' => $toRefreshSectionId]); ?>
        <div role="listbox">
            <?php if (count($listaPartnership->getModels()) == 0): ?>
                <div class="list-items"><h2 class="box-widget-subtitle"><?= Module::tHtml('Module', 'Nessuna Proposta di collaborazione'); ?></h2></div>
            <?php else:
                ?>
                <div class="list-items">
                    <?php
                    foreach ($listaPartnership->getModels() as $partnership):
                        /** @var \open20\amos\partnershipprofiles\models\PartnershipProfiles $partnership */
                        ?>
                        <div class="widget-listbox-option row" role="option">
                            <article class="col-xs-12 nop">
                                <div class="container-text row">
                                    <?= \open20\amos\notificationmanager\forms\NewsWidget::widget(['model' => $partnership]); ?>
                                    <div class="col-xs-12">
                                        <div class="col-xs-12 nop">
                                            <p><?= Yii::$app->getFormatter()->asDatetime($partnership->created_at); ?></p>
                                        </div>
                                        <h2 class="box-widget-subtitle">
                                            <?php
                                            if (strlen($partnership->title) > 100) {
                                                $stringCut = substr($partnership->title, 0, 100);
                                                echo substr($stringCut, 0, strrpos($stringCut, ' ')) . '... ';
                                            } else {
                                                echo $partnership->title;
                                            }
                                            ?>
                                        </h2>
                                    </div>
                                     <div class="col-xs-12">
                                      <p class="box-widget-text">
                                    <?php
                                    if (strlen($partnership->short_description) > 120) {
                                        $stringCut = substr($partnership->short_description, 0, 120);
                                        echo substr($stringCut, 0, strrpos($stringCut, ' ')) . '... ';
                                    } else {
                                        echo $partnership->short_description;
                                    }
                                    ?>
                                      </p>
                                     </div>
                                </div>
                                <div class="col-xs-6 nop">
                                    <p><strong><?= Module::t('amospartnershipprofiles', 'scadenza: ')?><?= $partnership->expiredDate ?></strong></p>
                                </div>
                                <div class="col-xs-6 nop">
                                    <span class="pull-right">
                                    <?= Html::a(Module::t('amospartnershipprofiles', 'LEGGI'), ['../partnershipprofiles/partnership-profiles/view', 'id' => $partnership->id], ['class' => 'btn btn-navigation-primary']); ?>
                                    </span>
                                </div>


                                <div class="clearfix"></div>
                            </article>
                        </div>
                        <?php
                    endforeach;
                    ?>
                </div>
                <?= Html::a(Module::t('Module', 'Visualizza Elenco Proposte di collaborazione'), ['/partnershipprofiles'], ['class' => 'read-all']); ?>
                <?php
            endif;
            ?>
        </div>
        <?php Pjax::end(); ?>
    </section>
</div>