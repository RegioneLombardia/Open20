<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\views\newsletter
 * @category   CategoryName
 */

use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\CreatedUpdatedWidget;
use open20\amos\core\forms\editors\m2mWidget\M2MWidget;
use open20\amos\core\forms\RequiredFieldsTipWidget;
use open20\amos\core\forms\SortModelsWidget;
use open20\amos\core\forms\Tabs;
use open20\amos\core\interfaces\NewsletterInterface;
use open20\amos\core\record\Record;
use open20\amos\notificationmanager\AmosNotify;
use open20\amos\notificationmanager\controllers\NewsletterController;
use open20\amos\notificationmanager\models\NewsletterContents;
use open20\amos\notificationmanager\models\NewsletterContentsConf;
use kartik\alert\Alert;
use yii\base\NotSupportedException;
use yii\db\ActiveQuery;

/**
 * @var yii\web\View $this
 * @var open20\amos\notificationmanager\models\Newsletter $model
 * @var yii\widgets\ActiveForm $form
 */

/** @var NewsletterController $appController */
$appController = Yii::$app->controller;

?>

<div class="newsletter-form col-xs-12 nop">
    <?php
    $form = ActiveForm::begin([
        'options' => [
            'id' => 'newsletter_form_id',
            'enctype' => 'multipart/form-data', // important
        ]
    ]);
    ?>
    
    <?php $this->beginBlock('general'); ?>
    <div class="row">
        <div class="col-md-10 col xs-12">
            <?= $form->field($model, 'subject')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <?php if ($model->isNewRecord): ?>
            <div class="col-xs-12">
                <?= Alert::widget([
                    'type' => Alert::TYPE_WARNING,
                    'body' => AmosNotify::t('amosnotify', '#alert_new_newsletter'),
                    'closeButton' => false
                ]); ?>
            </div>
        <?php else: ?>
            <?php
            $newsletterContentsConfs = $appController->getAllNewsletterContentsConfs();
            $btnAssociaLabelPrefix = AmosNotify::t('amosnotify', '#manage');
            $newsletterId = $model->id;
            ?>
            <?php foreach ($newsletterContentsConfs as $conf): ?>
                <?php
                /** @var NewsletterContentsConf $conf */
                $confId = $conf->id;
                
                /** @var Record $contentConfModel */
                $contentConfModel = Yii::createObject($conf->classname);
                if (!($contentConfModel instanceof NewsletterInterface)) {
                    throw new NotSupportedException("La classe " . $conf->classname . " non implementa la NewsletterInterface");
                }
                $contentConfModelTable = $contentConfModel::tableName();
                
                /** @var NewsletterContents $newsletterContentsModel */
                $newsletterContentsModel = $appController->notifyModule->createModel('NewsletterContents');
                $newsletterContentsTable = $newsletterContentsModel::tableName();
                
                $queryContent = $model->getContentsModelsByConfQuery($conf);
                
                /** @var ActiveQuery $queryIndexes */
                $queryIndexes = clone $queryContent;
                $queryIndexes->select([$contentConfModelTable . '.id']);
                $queryIndexes->indexBy($newsletterContentsTable . '.order');
                $indexes = $queryIndexes->column();
                $firstItem = reset($indexes);
                $lastItem = end($indexes);
                
                $modelLabel = $appController->makeModelLabel($contentConfModel);
                $btnAssociaLabel = $appController->makeManageContentsTitle($contentConfModel, $btnAssociaLabelPrefix, $modelLabel);
                
                $actionColumnButtons = [
                    'sortButtons' => function ($url, $model, $key) use ($firstItem, $lastItem, $confId, $newsletterId) {
                        /** @var Record $model */
                        $isFirst = ($key == $firstItem);
                        $isLast = ($key == $lastItem);
                        return SortModelsWidget::widget([
                            'model' => $model,
                            'sortUrl' => [
                                '/notify/newsletter/order-content',
                                'newsletterId' => $newsletterId,
                                'confId' => $confId
                            ],
                            'sortPermissionToCheck' => 'NEWSLETTER_UPDATE',
                            'isFirst' => $isFirst,
                            'isLast' => $isLast
                        ]);
                    }
                ];
                ?>
                <div class="col-xs-12 m-b-20">
                    <h3><?= ucfirst(strtolower($modelLabel)) ?></h3>
                    <?= M2MWidget::widget([
                        'model' => $model,
                        'modelId' => $newsletterId,
                        'modelData' => $queryContent,
                        'overrideModelDataArr' => true,
                        'targetUrlParams' => [
                            'viewM2MWidgetGenericSearch' => true,
                            'confId' => $confId,
                        ],
                        'gridId' => 'm2m-grid-' . $contentConfModelTable,
                        'btnAssociaLabel' => $btnAssociaLabel,
                        'targetUrl' => '/notify/newsletter/associa-m2m',
                        'moduleClassName' => AmosNotify::className(),
                        'targetUrlController' => 'newsletter',
                        'permissions' => [
                            'add' => 'NEWSLETTER_MANAGER',
                        ],
                        'actionColumnsTemplate' => '{sortButtons}{deleteRelation}',
                        'actionColumnsButtons' => $actionColumnButtons,
                        'itemsMittente' => $contentConfModel->newsletterContentGridViewColumns(),
                        'itemMittenteDisableColumnsOrder' => true
                    ]); ?>
                </div>
                <?php
                unset($queryContent);
                ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div class="clearfix"></div>
    <?php $this->endBlock(); ?>
    
    <?php
    $itemsTab[] = [
        'label' => AmosNotify::t('amosnotify', 'General'),
        'content' => $this->blocks['general'],
    ];
    ?>
    
    <?= Tabs::widget([
        'encodeLabels' => false,
        'items' => $itemsTab
    ]); ?>
    
    <?= RequiredFieldsTipWidget::widget() ?>
    <?= CreatedUpdatedWidget::widget(['model' => $model]) ?>
    <?= CloseSaveButtonWidget::widget([
        'model' => $model
    ]); ?>
    <?php ActiveForm::end(); ?>
</div>
