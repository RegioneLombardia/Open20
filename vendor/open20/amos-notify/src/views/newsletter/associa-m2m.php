<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\views\profilo
 * @category   CategoryName
 */

use open20\amos\core\forms\editors\m2mWidget\M2MWidget;
use open20\amos\core\interfaces\NewsletterInterface;
use open20\amos\core\record\Record;
use open20\amos\notificationmanager\AmosNotify;
use open20\amos\notificationmanager\controllers\NewsletterController;

/**
 * @var yii\web\View $this
 * @var \open20\amos\notificationmanager\models\Newsletter $model
 */

/** @var NewsletterController $appController */
$appController = Yii::$app->controller;

$newsletterConf = $appController->getNewsletterConf();
$confClassname = $newsletterConf->classname;

/** @var Record|NewsletterInterface $contentConfModel */
$contentConfModel = $newsletterConf->getContentConfModel();

$this->title = $appController->makeManageContentsTitle($contentConfModel);
$this->params['breadcrumbs'][] = $this->title;

?>

<?= M2MWidget::widget([
    'model' => $model,
    'modelId' => $model->id,
    'modelData' => $model->getNewsletterContentsByContentConfIdQuery($newsletterConf->id), // query degli elementi selezionati
    'modelDataArrFromTo' => [
        'from' => 'content_id',
        'to' => 'content_id'
    ],
    'modelTargetSearch' => [
        'class' => $confClassname,
        'query' => $appController->getAssociaM2mQuery($contentConfModel), // query generale di tutti gli elementi
    ],
    'gridId' => 'newsletter-grid',
    'viewSearch' => (isset($viewM2MWidgetGenericSearch) ? $viewM2MWidgetGenericSearch : false),
    'targetUrlController' => 'newsletter',
    'moduleClassName' => AmosNotify::className(),
    'targetColumnsToView' => $contentConfModel->newsletterSelectContentsGridViewColumns(),
]);
