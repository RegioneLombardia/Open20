<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-comments/src/views
 */

use open20\amos\comments\AmosComments;
use open20\amos\core\helpers\Html;
use open20\amos\core\forms\ActiveForm;
use open20\amos\core\module\BaseAmosModule;
use kartik\datecontrol\DateControl;
use open20\amos\core\forms\Tabs;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\RequiredFieldsTipWidget;
use kartik\widgets\DateTimePicker;
use yii\helpers\Url;
use open20\amos\core\forms\editors\Select;
use yii\helpers\ArrayHelper;
use open20\amos\core\icons\AmosIcons;
use yii\bootstrap\Modal;
use open20\amos\core\forms\TextEditorWidget;
use yii\helpers\Inflector;

/**
 * @var yii\web\View $this
 * @var open20\amos\comments\models\CommentBlacklist $model
 * @var yii\widgets\ActiveForm $form
 */


?>
<div class="comment-blacklist-form col-xs-12 nop">

    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'comment-blacklist_' . ((isset($fid)) ? $fid : 0),
            'data-fid' => (isset($fid)) ? $fid : 0,
            'data-field' => ((isset($dataField)) ? $dataField : ''),
            'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
            'class' => ((isset($class)) ? $class : '')
        ]
    ]);
    ?>
<!--    --><?php // $form->errorSummary($model, ['class' => 'alert-danger alert fade in']); ?>

    <div class="row">
        <div class="col-xs-6">
            <?= $form->field($model, 'word')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-12">
            <?= RequiredFieldsTipWidget::widget(); ?>
        </div>
        <div class="col-xs-6">
            <?= CloseSaveButtonWidget::widget([
                'model' => $model,
                'buttonNewSaveLabel' => AmosComments::t('amoscomments', 'Aggiungi')
            ]); ?>
        </div>
    </div>

    <div class="clearfix"></div>

    <?php ActiveForm::end(); ?>

</div>
