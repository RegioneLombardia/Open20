<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @backend/modules/landings/views
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
use open20\amos\core\forms\TextEditorWidget;
use yii\helpers\Inflector;

/**
 * @var yii\web\View $this
 * @var backend\modules\landings\models\PreferenceLandingLink $model
 * @var yii\widgets\ActiveForm $form
 */


?>
<div class="preference-landing-link-form col-xs-12 nop">

    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'preference-landing-link_' . ((isset($fid)) ? $fid : 0),
            'data-fid' => (isset($fid)) ? $fid : 0,
            'data-field' => ((isset($dataField)) ? $dataField : ''),
            'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
            'class' => ((isset($class)) ? $class : '')
        ]
    ]);
    ?>
    <?php // $form->errorSummary($model, ['class' => 'alert-danger alert fade in']); ?>

    <div class="row">

            <div class="col-md-12 col xs-12"><!-- title string -->
                <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?><!-- url string -->
                <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

                <?= RequiredFieldsTipWidget::widget(); ?>

                <?= CloseSaveButtonWidget::widget([
                        'model' => $model,
                        'urlClose' => Url::to([
                            '/landings/preference-landing/update',
                            'id' => $model->preference_landing_id,
                            '#' => 'pos-links',
                            ]),

                    ]); ?>


            </div>
            <div class="col-md-12 col xs-12"></div>
        </div>
        <div class="clearfix"></div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
