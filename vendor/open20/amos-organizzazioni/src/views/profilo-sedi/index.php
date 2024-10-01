<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\views\profilo-sedi
 * @category   CategoryName
 */

use open20\amos\core\views\DataProviderView;
use open20\amos\organizzazioni\Module;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\organizzazioni\models\search\ProfiloSediSearch $model
 */

$this->title = Module::t('amosorganizzazioni', 'Profilo Sedi');
$this->params['breadcrumbs'][] = $this->title;

/** @var \open20\amos\organizzazioni\models\ProfiloSedi $profiloSediModel */
$profiloSediModel = Module::instance()->createModel('ProfiloSedi');

?>
<div class="profilo-sedi-index">
    <?= $this->render('_search', ['model' => $model]); ?>
    <?= DataProviderView::widget([
        'dataProvider' => $dataProvider,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => $profiloSediModel->getGridViewColumns()
        ],
    ]); ?>
</div>
