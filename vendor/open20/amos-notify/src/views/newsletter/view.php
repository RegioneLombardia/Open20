<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\views\newsletter
 * @category   CategoryName
 */

use open20\amos\core\forms\CloseButtonWidget;
use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\interfaces\ModelGrammarInterface;
use open20\amos\core\interfaces\ModelLabelsInterface;
use open20\amos\core\interfaces\NewsletterInterface;
use open20\amos\core\record\Record;
use open20\amos\core\views\AmosGridView;
use open20\amos\notificationmanager\controllers\NewsletterController;
use open20\amos\notificationmanager\models\Newsletter;
use open20\amos\notificationmanager\models\NewsletterContents;
use open20\amos\notificationmanager\models\NewsletterContentsConf;
use yii\base\NotSupportedException;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

/**
 * @var yii\web\View $this
 * @var \open20\amos\notificationmanager\models\Newsletter $model
 */

$this->title = $model->subject;
$this->params['breadcrumbs'][] = $this->title;

/** @var NewsletterController $appController */
$appController = Yii::$app->controller;

/** @var NewsletterContentsConf $newsletterContentsConfModel */
$newsletterContentsConfModel = $appController->notifyModule->createModel('NewsletterContentsConf');
$newsletterContentsConfs = $newsletterContentsConfModel::find()->orderBy(['order' => SORT_ASC])->all();

?>
<div class="event-room-view col-xs-12 m-t-5">
    <div class="row">
        <div class="col-xs-12 m-b-5">
            <?= ContextMenuWidget::widget([
                'model' => $model,
                'actionModify' => $model->getFullUpdateUrl(),
                'actionDelete' => $model->getFullDeleteUrl()
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="header col-xs-12 nop">
            <div class="title col-xs-12">
                <h2 class="title-text"><?= $model->subject ?></h2>
            </div>
        </div>
    </div>
    <div class="row">
        <?php foreach ($newsletterContentsConfs as $conf): ?>
            <?php
            /** @var NewsletterContentsConf $conf */
            /** @var Record $contentConfModel */
            $contentConfModel = Yii::createObject($conf->classname);
            if (!($contentConfModel instanceof NewsletterInterface)) {
                throw new NotSupportedException("La classe " . get_class($contentConfModel) . " non implementa la NewsletterInterface");
            }
            $contentConfModelTable = $contentConfModel::tableName();
            $modelLabel = '';
            if (($contentConfModel instanceof ModelLabelsInterface) && (($modelGrammar = $contentConfModel->getGrammar()) instanceof ModelGrammarInterface)) {
                $modelLabel = $modelGrammar->getModelLabel();
            }
            
            /** @var Newsletter $newsletterModel */
            $newsletterModel = $appController->notifyModule->createModel('Newsletter');
            $newsletterTable = $newsletterModel::tableName();
            
            /** @var NewsletterContents $newsletterContentsModel */
            $newsletterContentsModel = $appController->notifyModule->createModel('NewsletterContents');
            $newsletterContentsTable = $newsletterContentsModel::tableName();
            
            /** @var ActiveQuery $queryContent */
            $queryContent = $contentConfModel::find();
            $queryContent->innerJoin($newsletterContentsTable, $newsletterContentsTable . '.content_id = ' . $contentConfModelTable . '.id');
            $queryContent->innerJoin($newsletterTable, $newsletterTable . '.id = ' . $newsletterContentsTable . '.newsletter_id');
            $queryContent->andWhere([$newsletterContentsTable . '.deleted_at' => null]);
            $queryContent->andWhere([$newsletterTable . '.deleted_at' => null]);
            $queryContent->andWhere([$newsletterContentsTable . '.newsletter_contents_conf_id' => $conf->id]);
            $queryContent->andWhere([$newsletterContentsTable . '.newsletter_id' => $model->id]);
            $queryContent->orderBy([$newsletterContentsTable . '.order' => SORT_ASC]);
            $dataProvider = new ActiveDataProvider(['query' => $queryContent, 'sort' => false]);
            ?>
            <div class="col-xs-12 m-b-20">
                <hr>
                <h3><?= ucfirst(strtolower($modelLabel)) ?></h3>
                <?= AmosGridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => $contentConfModel->newsletterContentGridViewColumns()
                ]) ?>
            </div>
            <?php
            unset($queryContent);
            ?>
        <?php endforeach; ?>
    </div>
    <?= CloseButtonWidget::widget([
        'urlClose' => $appController->getUrlClose()
    ]); ?>
</div>
