<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @frontend/modules/luoghi/views
 */

use open20\amos\core\forms\ActiveForm;
//use yii\widgets\ActiveForm;
use open20\amos\core\helpers\Html;
use Yii;

/** @var yii\web\View $this */
/** @var open20\onlyoffice\models\OnlyofficeFiles $model */
/** @var array $tipiGestibili */

$this->title = 'Carica file in OnlyOffice';
$this->params['breadcrumbs'][] = ['label' => 'Elenco file', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="onlyoffice-files-create">
    <?php $form = ActiveForm::begin([
        'options' => [
//            'id' => 'luoghi_' . ((isset($fid)) ? $fid : 0),
//            'data-fid' => (isset($fid)) ? $fid : 0,
//            'data-field' => ((isset($dataField)) ? $dataField : ''),
//            'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
//            'class' => ((isset($class)) ? $class : '')
        ]
    ]); ?>

    <?= Html::activeHiddenInput($model, 'link_diretto') ?>

    <?= $form->field($model, 'nome_file')->textInput([]) ?>
    
    <?= $form->field($model, 'file')->fileInput([]) ?>
    
    <div class="col-xs-12">
        <?= Html::submitButton('Carica', [
            'class' => 'btn btn-primary'
        ]); ?>
    </div>
    
    <?php ActiveForm::end(); ?>
</div>