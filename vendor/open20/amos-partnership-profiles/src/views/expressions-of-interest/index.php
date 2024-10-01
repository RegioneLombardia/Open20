<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\views\expressions-of-interest
 * @category   CategoryName
 */

use open20\amos\core\forms\CloseButtonWidget;
use open20\amos\core\views\DataProviderView;
use open20\amos\partnershipprofiles\Module;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\partnershipprofiles\models\search\ExpressionsOfInterestSearch $model
 * @var string $currentView
 */

$this->title = Module::t('amospartnershipprofiles', 'Expressions Of Interest');
$this->params['breadcrumbs'][] = $this->title;

?>

<?php if (isset(Yii::$app->request->getQueryParams()['partnership_profile_id'])): ?>
    <?= CloseButtonWidget::widget([
        'urlClose' => Yii::$app->session->get(Module::beginCreateNewSessionKeyPartnershipProfiles()),
        'title' => Module::t('amospartnershipprofiles', 'Go back')
    ]); ?>
<?php endif; ?>

<div class="<?= Yii::$app->controller->id ?>-index">
    <?= $this->render('_search', ['model' => $model]); ?>
    <?= $this->render('_order', ['model' => $model]); ?>
    <?= DataProviderView::widget([
        'dataProvider' => $dataProvider,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => $model->getGridViewColumns()
        ],
        'listView' => [
            'itemView' => '_item'
        ]
    ]); ?>
</div>
