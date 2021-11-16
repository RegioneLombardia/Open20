<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-email-manager/src/views
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\forms\ActiveForm;
use kartik\datecontrol\DateControl;
use open20\amos\core\forms\Tabs;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\RequiredFieldsTipWidget;
use yii\helpers\Url;
use open20\amos\core\forms\editors\Select;
use yii\helpers\ArrayHelper;
use open20\amos\core\icons\AmosIcons;
use yii\bootstrap\Modal;
use yii\redactor\widgets\Redactor;
use yii\helpers\Inflector;
use open20\amos\core\forms\TextEditorWidget;

/**
 * @var yii\web\View $this
 * @var open20\amos\emailmanager\models\EmailView $model
 * @var yii\widgets\ActiveForm $form
 */

$params = json_decode($model->params);
?>
<div class="email-view-form col-xs-12 nop">

    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'email-view_' . ((isset($fid)) ? $fid : 0),
            'data-fid' => (isset($fid)) ? $fid : 0,
            'data-field' => ((isset($dataField)) ? $dataField : ''),
            'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
            'class' => ((isset($class)) ? $class : '')
        ]
    ]);
    ?>
    <?php // $form->errorSummary($model, ['class' => 'alert-danger alert fade in']); ?>

    <div class="row">
        <div class="col-xs-12">

            <div class="col-lg-6 col-sm-6">
                <?= $form->field($model, 'type')->textInput() ?>
            </div>
            <div class="col-lg-6 col-sm-6">
                <?= $form->field($model, 'description')->textArea() ?>
            </div>

            <div class="col-md-8 col-xs-12"><!-- module string -->
                <?= $form->field($model, 'content')->widget(TextEditorWidget::className(),
                    [
                        'clientOptions' => [
                            'lang' => substr(Yii::$app->language, 0, 2),
                            'plugins' => 'code',
                            'toolbar' => "undo redo | backcolor forecolor | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | code"
                        ]
                    ]) ?>
            </div>
            <div class="col-md-4 col-xs-12">
                <br/>
                <label><?= Yii::t('emailmanager', 'Parametri Disponibili') ?></label>
                <?php foreach ($params as $param=>$type) {
                    $typeTranslate = open20\amos\emailmanager\AmosEmail::t('amosemail', $model->view.'_'.$param);

                    echo "<br/><b>{{$param}}</b>: {$typeTranslate}";
                } ?>
            </div>
            <div class="col-xs-12">
                <?= RequiredFieldsTipWidget::widget(); ?><?= CloseSaveButtonWidget::widget(['model' => $model]); ?><?php ActiveForm::end(); ?>
            </div>
        </div>
        <div class="clearfix"></div>

    </div>
</div>
