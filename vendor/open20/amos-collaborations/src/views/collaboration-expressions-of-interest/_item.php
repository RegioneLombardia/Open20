<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\collaborations\views\collaboration-expressions-of-interest
 * @category   CategoryName
 */

use open20\amos\collaborations\models\CollaborationExpressionsOfInterest;
use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\forms\ItemAndCardHeaderWidget;
use open20\amos\core\helpers\Html;
use open20\amos\collaborations\controllers\CollaborationExpressionsOfInterestController;
use open20\amos\collaborations\Module;
use open20\amos\collaborations\utility\CollaborationProposalsUtility;
use yii\helpers\Url;

/** @var CollaborationExpressionsOfInterest $model */

$appController = Yii::$app->controller;
$isArchived = $model->collaborationProposals->end_date < date('Y-m-d');
?>
<div class="collaboration-interest-received">
    <div class="row">
        <div class="col-md-12">
            <div class="title-proposta h4">
                <?= $model->collaborationProposals->title ?>
                <a tabindex="0" role="button" data-toggle="popover" data-trigger="focus" data-html="true" title="<?= Module::t('amoscollaborations', 'Proposta di collaborazione') ?>" data-content="
                <strong><?= \Yii::t('amoscollaborations', 'Categoria') ?>:</strong> <?= $model->collaborationProposals->getCategoryLabel()[$model->collaborationProposals->category_id] ?><br>
                <strong><?= \Yii::t('amoscollaborations', 'Creatore') ?>:</strong> <?= $model->collaborationProposals->createdUserProfile->nomeCognome ?>
                "><span class="mdi mdi-information-outline mdi-24px"></span></a>
                <!-- <strong>< ?= \Yii::t('amoscollaborations', 'Stato') ?>:</strong> < ?= $isArchived ? \Yii::t('amoscollaborations', 'Archiviata') : $model->collaborationProposals->getWorkflowStatusLabel()[$model->collaborationProposals->status] ?><br> -->
                <?= ContextMenuWidget::widget([
                    'model' => $model,
                    'actionModify' => ['update', 'id' => $model->id],
                    'disableModify' => !($model->userCanUpdate()),
                    'actionDelete' => ['delete', 'id' => $model->id],
                    'disableDelete' => !($model->userCanDelete()),
                ]) ?>
            </div>
        </div>
        <div class="col-md-12">
            <div class="author">
                <div class="date-interest m-r-5">
                    <?php if (strpos(Url::current(), 'received')) {
                        echo Module::tHtml('amoscollaborations', 'Interesse ricevuto');
                    } else {
                        echo Module::tHtml('amoscollaborations', 'Interesse manifestato');
                    } ?>
                    <strong><?= \Yii::$app->getFormatter()->asDatetime($model->created_at) ?></strong>
                </div>
                <?php if (!strpos(Url::current(), 'created-by-me')) { ?>
                    <div class="m-r-5">
                        <?= \Yii::t('amoscollaborations', 'da') ?>
                    </div>
                    <div class="author-interest">
                        <?= ItemAndCardHeaderWidget::widget([
                            'model' => $model,
                            'publicationDateField' => 'created_at',
                            'publicationDateNotPresent' => true
                        ]) ?>
                    </div>
                <?php } ?>
                <div class="cta">
                <?php if ($appController->viewReadAllBtn($model)) : ?>
                <?= Html::a(
                    \Yii::t('amoscollaborations', 'Vai alla manifestazione di interesse'),
                    $model->getFullViewUrl(),
                    [
                        'class' => 'readmore',
                        'title' => \Yii::t('amoscollaborations', 'Vedi manifestazione interesse')
                    ]
                ) ?>
                 <?php endif; ?>
            </div>
            </div>
            
        </div>
    </div>
</div>
