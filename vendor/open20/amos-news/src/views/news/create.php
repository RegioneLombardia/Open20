<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news
 * @category   CategoryName
 */

use open20\amos\news\AmosNews;

/**
 * @var yii\web\View $this
 * @var open20\amos\news\models\News $model
 */

/** @var \open20\amos\news\controllers\NewsController $controller */
$controller = Yii::$app->controller;
$controller->setNetworkDashboardBreadcrumb();
$this->title = AmosNews::t('amosnews', 'Inserisci notizia');
$this->params['breadcrumbs'][] = ['label' => Yii::$app->session->get('previousTitle'), 'url' => Yii::$app->session->get('previousUrl')];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="news-create">
<?= $this->render(
  '_form', [
    'model' => $model,
    'moduleCwh' => $moduleCwh,
    'scope' => $scope,
    'siteManagementModule' => (isset($siteManagementModule) ? $siteManagementModule : null)
  ]) 
?>
</div>