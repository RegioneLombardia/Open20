<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\views\profilo
 * @category   CategoryName
 */

use open20\amos\core\forms\ActiveForm;
use open20\amos\core\views\DataProviderView;
use open20\amos\organizzazioni\Module;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\organizzazioni\models\search\ProfiloSearch $model
 */
$this->title = Module::t('amosorganizzazioni', 'Organizzazioni');
$this->params['breadcrumbs'][] = $this->title;

/** @var \open20\amos\organizzazioni\models\Profilo $profiloModel */
$profiloModel = Module::instance()->createModel('Profilo');

$modelGrammar = $model->getGrammar();
$articleSingular = $modelGrammar->getArticleSingular();
$spaceAfterArticleSingular = (substr($articleSingular, -1) == "'" ? '' : ' ');
$altText = Module::t('amosorganizzazioni', '#go_to') . $articleSingular . $spaceAfterArticleSingular . strtolower($modelGrammar->getModelSingularLabel());

?>
<?php
$form = ActiveForm::begin([
    'options' => [
        'enctype' => 'multipart/form-data'
    ]
]);

$dataProvider->pagination->pageSize = 21;

?>
<?= $this->render('_modal', ['form' => $form, 'model' => $model]) ?>

<?php ActiveForm::end(); ?>

<div class="are-profilo-index">
    <?= $this->render('_search', ['model' => $model]); ?>
    <?=
    DataProviderView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $model,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => $profiloModel->getGridViewColumns()
        ],
        'listView' => [
            'itemView' => '_item',
            'masonry' => false,
            'masonrySelector' => '.grid',
            'masonryOptions' => [
                'itemSelector' => '.grid-item',
                'columnWidth' => '.grid-sizer',
                'percentPosition' => 'true',
                'gutter' => 30
            ],
            'showItemToolbar' => false,
        ],
        'iconView' => [
            'itemView' => '_icon',
            'viewParams' => [
                'altText' => $altText
            ]
        ],
    ]);
    ?>
</div>
