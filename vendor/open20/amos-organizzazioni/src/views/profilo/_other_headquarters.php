<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\views\profilo
 * @category   CategoryName
 */

use open20\amos\core\forms\CreateNewButtonWidget;
use open20\amos\core\views\AmosGridView;
use open20\amos\organizzazioni\models\ProfiloSedi;
use open20\amos\organizzazioni\Module;
use kartik\alert\Alert;
use yii\data\ActiveDataProvider;
use yii\web\View;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\organizzazioni\models\Profilo $model
 * @var bool $isView
 */

/** @var ProfiloSedi $emptyProfiloSede */
$emptyProfiloSede = Module::instance()->createModel('ProfiloSedi');
$createBtnId = 'create-other-headquarter-btn-id';
$defaultDataConfirm = Module::t('amosorganizzazioni', '#create_profilo_sede_data_confirm_msg');
$deleteMsg = Module::t('amosorganizzazioni', '#delete_headquarter_from_profilo_form_msg');

$js = <<<JS
    $('#$createBtnId').on('click', function(event) {
        event.preventDefault();
        var ok = confirm('$defaultDataConfirm');
        if (ok) {
            window.location.href = $(this).attr('href');
        }
        return false;
    });
    $('.view-headquarter-btn').on('click', function(event) {
        event.preventDefault();
        var ok = confirm('$defaultDataConfirm');
        if (ok) {
            window.location.href = $(this).attr('href');
        }
        return false;
    });
    $('.update-headquarter-btn').on('click', function(event) {
        event.preventDefault();
        var ok = confirm('$defaultDataConfirm');
        if (ok) {
            window.location.href = $(this).attr('href');
        }
        return false;
    });
    $('.delete-headquarter-btn').on('click', function(event) {
        event.preventDefault();
        var ok = confirm("$deleteMsg");
        if (ok) {
            window.location.href = $(this).attr('href');
        }
        return false;
    });
JS;
$this->registerJs($js, View::POS_READY);

?>

<?php if ($model->isNewRecord): ?>
    <?= Alert::widget([
        'type' => Alert::TYPE_WARNING,
        'body' => Module::t('amosorganizzazioni', '#alert_new_headquarters'),
        'closeButton' => false
    ]); ?>
<?php else: ?>
    <?php if (!$isView && \Yii::$app->user->can('PROFILOSEDI_CREATE')): ?>
        <?php
        $createLink = ['/organizzazioni/profilo-sedi/create', 'profiloId' => $model->id];
        ?>
        <div>
            <?= CreateNewButtonWidget::widget([
                'createButtonId' => $createBtnId,
                'urlCreateNew' => $createLink,
                'createNewBtnLabel' => Module::t('amosorganizzazioni', '#create_other_headquarter'),
                'otherBtnClasses' => 'create-other-headquarter-btn-selector'
            ]); ?>
        </div>
    <?php endif; ?>
    <div>
        <?= AmosGridView::widget([
            'dataProvider' => new ActiveDataProvider([
                'query' => $model->getOtherHeadquarters()
            ]),
            'columns' => $emptyProfiloSede->getGridViewColumns(!$isView)
        ]); ?>
    </div>
<?php endif; ?>
