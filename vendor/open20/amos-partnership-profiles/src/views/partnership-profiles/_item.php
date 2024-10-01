<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\views\partnership-profiles
 * @category   CategoryName
 */

use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\forms\ItemAndCardHeaderWidget;
use open20\amos\core\forms\PublishedByWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\views\toolbars\StatsToolbar;
use open20\amos\notificationmanager\forms\NewsWidget;
use open20\amos\partnershipprofiles\Module;

/**
 * @var \open20\amos\partnershipprofiles\models\PartnershipProfiles $model
 */

$statesCounter = $model->getExpressionsOfInterestStatesCounter();

$module = \Yii::$app->getModule('partnershipprofiles');
$moduleCwh = \Yii::$app->getModule('cwh');
$communityConfigurationsId = null;
if (isset($moduleCwh) && !empty($moduleCwh->getCwhScope())) {
    $scope = $moduleCwh->getCwhScope();
    if (isset($scope['community'])) {
        $communityConfigurationsId = 'communityId-' . $scope['community'];
    }
}

$enabledFields = !empty($module->fieldsCommunityConfigurations[$communityConfigurationsId]['fields']) ? $module->fieldsCommunityConfigurations[$communityConfigurationsId]['fields'] : (!empty($module->fieldsConfigurations['fields']) ? $module->fieldsConfigurations['fields'] : []);

?>
<div class="listview-container">
    <div class="<?= Yii::$app->controller->id ?> col-xs-12">
        <div class="row row-d-flex">
            <div class="col-sm-3 info-proposte-collaborazione">
                <div class="flexbox">
                    <div class="col-auto">
                        <div class="tipo-collaborazione small text-warning">
                            <span class="mdi mdi-layers"></span> <span><?= Module::t('amospartnershipprofiles', 'Dalla piattaforma') ?></span>
                        </div>
                        <div class="date-end bg-secondary">
                            <small><?= Module::t('amospartnershipprofiles', 'Scadenza') . ': ' ?></small><strong><?= Yii::$app->formatter->asDate($model->calculateExpiryDate(), 'long') ?></strong>
                        </div>
                    </div>
                </div>


                <div class="author">
                    <?= ItemAndCardHeaderWidget::widget([
                        'model' => $model,
                        'publicationDateField' => 'updated_at'
                    ]) ?>
                </div>
                <div class="other-info">
                    <small><?= Module::t('amospartnershipprofiles', 'Proposta il') . ': ' ?></small><strong><?= Yii::$app->formatter->asDate($model->partnership_profile_date) ?></strong>
                    <?= PublishedByWidget::widget([
                        'model' => $model,
                        'layout' => $pubblicationDate . '{targetAdv}{status}'
                    ]) ?>
                </div>
            </div>
            <div class="col-sm-9 border-left">
                <div class="content-proposte-collaborazione">
                    <div class="title">
                        <h3><?= Html::a(
                                $model->title,
                                $model->getFullViewUrl(),
                                [
                                    'class' => 'link-list-title',
                                    'title' =>  $model->title,
                                ]
                            ); ?></h3>
                        <div class="ml-auto">
                            <?= NewsWidget::widget(['model' => $model]); ?>
                            <?= ContextMenuWidget::widget([
                                'model' => $model,
                                'actionModify' => "/partnershipprofiles/partnership-profiles/update?id=" . $model->id,
                                'actionDelete' => "/partnershipprofiles/partnership-profiles/delete?id=" . $model->id
                            ]) ?>
                        </div>

                    </div>

                    <p class="title-three-line">
                        <?php
                        $shortDesc = strip_tags($model->short_description);
                        if (strlen($shortDesc) > 800) {
                            $stringCut = substr($shortDesc, 0, 800);
                            $shortDesc = substr($stringCut, 0, strrpos($stringCut, ' ')) . '... ';
                        }
                        ?>
                        <?= $shortDesc ?>

                    </p>
                    <div class="footer-item">
                        <div class="box-interesse">
                            <?php if (isset($statsToolbar) && $statsToolbar) : ?>
                                <?= StatsToolbar::widget(['model' => $model]); ?>
                            <?php endif; ?>
                            <?php
                            $statesCounter = $model->getExpressionsOfInterestStatesCounter();


                            ?>
                            <div class="num-interesse">
                                <?= $statesCounter['notdraft'] ?>
                            </div>
                            <strong>
                                <?= (($statesCounter['notdraft'] == 1) ?
                                    Module::tHtml('amospartnershipprofiles', '#expression_of_interest_sidebar') :
                                    Module::tHtml('amospartnershipprofiles', '#expressions_of_interest_sidebar')) ?>
                            </strong>
                        </div>
                        <?= Html::a(Module::tHtml('amospartnershipprofiles', 'Read all'), $model->getFullViewUrl(), [
                            'class' => 'readmore',
                            'title' => Module::t('amospartnershipprofiles', 'Read the partnership profile')
                        ]) ?>
                    </div>


                </div>
            </div>
        </div>

    </div>
</div>