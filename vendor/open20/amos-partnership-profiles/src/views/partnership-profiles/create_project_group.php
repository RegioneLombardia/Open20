<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\views\partnership-profiles
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\views\AmosGridView;
use open20\amos\partnershipprofiles\Module;

/**
 * @var \yii\web\View $this
 * @var yii\data\ArrayDataProvider $dataProvider
 * @var string $currentView
 */

$this->title = Module::t('amospartnershipprofiles', 'Create project group');
$this->params['breadcrumbs'][] = $this->title;

?>

<?= Html::beginForm('', 'post'); ?>
<div class="<?= Yii::$app->controller->id ?>-create-project-group">
    <?= AmosGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\CheckboxColumn',
                'name' => 'selectedUsers',
                'checkboxOptions' => function ($model, $key, $index, $column) {
                    $checkboxOptions = [
                        'value' => $model['id'],
                        'checked' => true,
                    ];
                    return $checkboxOptions;
                }
            ],
            'userProfile.nomeCognome'
        ]
    ]);
    ?>
    <div class="m-t-30 italic">
        <?= Module::t('amospartnershipprofiles', '#create_project_group_end') ?>
    </div>
    <div class="bk-btnFormContainer">
        <?= Html::submitButton(Module::t('amospartnershipprofiles', 'Create work group'), ['class' => 'btn btn-navigation-primary']); ?>
    </div>
</div>
<?= Html::endForm(); ?>
