<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\views\event
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\utilities\ModalUtility;
use open20\amos\core\views\DataProviderView;
use open20\amos\sondaggi\AmosSondaggi;

use yii\web\View;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\events\models\search\EventSearch $model
 * @var string $currentView
 */


$this->title = $title;
$this->params['breadcrumbs'][] = $this->title;
if (!AmosSondaggi::instance()->enableBreadcrumbs) $this->params['breadcrumbs'] = [];
$userProfile = \Yii::$app->user->identity->userProfile;

 echo $this->render('@vendor/open20/amos-sondaggi/src/views/sondaggi-domande-pagine/index', [
                'dataProvider' => $dataProvider,
                'model' => $model,
                'currentView' => $currentView,
                'availableViews' => $availableViews,
                'url' => $url,
                'parametro' => $parametro,
                'moduleName' => $moduleName,
                'contextModelId' => $contextModelId,
]);
