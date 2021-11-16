<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/modules/operators/views
 */

/**
 * @var yii\web\View $this
 * @var backend\modules\operators\models\Operator $model
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

use open20\amos\core\helpers\Html;

$this->title = 'Importa operatore';
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/operators']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Operator'), 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Importa operatore';

?>

<div class="import-operator">

    <?php $form = \yii\bootstrap4\ActiveForm::begin([
        'options' => [
            'id' => 'import_operator_' . ((isset($fid)) ? $fid : 0),
            'data-fid' => (isset($fid)) ? $fid : 0,
            'data-field' => ((isset($dataField)) ? $dataField : ''),
            'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
            'class' => ((isset($class)) ? $class : '')
        ]
    ]);
    ?>
    <div class="col-md-6 form-group">
        <?php
        echo $form->field($model, 'id')->widget(\kartik\select2\Select2::classname(), [
            'options' => [
                'placeholder' => '',
                'disabled' => false,
                'id' => 'fiscalcode-id',
            ],
            'data' => \yii\helpers\ArrayHelper::map($dataProvider->getModels(), 'id', 'codice_fiscale')
        ])->label('Codice fiscale');
        ?>
    </div>

    <div class="col-md-6 form-group">
        <?php
        echo Html::submitButton('Importa', ['class' => 'btn btn-primary']);
        ?>
    </div>

    <?php \yii\bootstrap4\ActiveForm::end(); ?>

</div>
