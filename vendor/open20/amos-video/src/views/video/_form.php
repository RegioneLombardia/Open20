<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    frontend\modules\formazione/views
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\forms\ActiveForm;
use open20\video\Module;
use open20\amos\core\forms\CloseSaveButtonWidget;

/**
 * @var yii\web\View $this
 * @var frontend\modules\formazione\models\Formazione $model
 * @var yii\widgets\ActiveForm $form
 */

?>
<div class="formazione-form col-xs-12 nop">

    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'video_' . ((isset($fid)) ? $fid : 0),
            'data-fid' => (isset($fid)) ? $fid : 0,
            'data-field' => ((isset($dataField)) ? $dataField : ''),
            'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
            'class' => ((isset($class)) ? $class : '')
        ]
    ]);
    ?>

    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true,'placeholder'=>Module::t('amosvideo', 'Inserisci un titolo')]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'url')->textInput(['maxlength' => true,'placeholder'=>Module::t('amosvideo', 'Inserisci l\'url nel formato "https://www.youtube.com/watch?v=a1b2c3" oppure "https://vimeo.com/123456789"')]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <?= $form->field($model, 'description')->widget(\open20\amos\core\forms\TextEditorWidget::className(), [
                'options' => [
                    'placeholder' => Module::t('amosvideo', 'Inserisci...'),
                    'maxlength' => 250,
                ],
                'clientOptions' => [
                    'lang' => substr(Yii::$app->language, 0, 2)
                ]
            ]);
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 form-group m-b-15" style="display: flex; flex-direction: row; align-items: center">
            <?=
            Html::label(Module::t('amosvideo', 'In evidenza'),
                'in_evidenza',
                [
                    'class' => 'control-label m-b-0 m-r-15',
                ]
            );
            ?>
            <div>
                <?=
                $form->field($model, 'in_evidenza')->widget(\kartik\widgets\SwitchInput::classname(), [
                    'pluginOptions' => [
                        //                                        'size' => 'large',
                        'onText' => Module::t('amosvideo', 'Yes'),
                        'offText' => Module::t('amosvideo', 'No'),
                        'onColor' => 'success'
                    ],
                    'options' => [
                        'id' => 'in_evidenza',
                    ]
                ])->label(false);
                ?>
            </div>
        </div>
    </div>
    <?= CloseSaveButtonWidget::widget(['model' => $model]); ?>
    <?php ActiveForm::end(); ?>
</div>