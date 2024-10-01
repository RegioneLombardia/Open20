<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    frontend\modules\formazione/views
 */

use open20\amos\core\helpers\Html;
use yii\widgets\ActiveForm;
use open20\video\Module;

/**
 * @var yii\web\View $this
 * @var frontend\modules\formazione\models\search\FormazioneSearch $model
 * @var yii\widgets\ActiveForm $form
 */


?>
<div class="formazione-search element-to-toggle" data-toggle-element="form-search">

    <?php $form = ActiveForm::begin([
        'action' => (isset($originAction) ? [$originAction] : ['index']),
        'method' => 'get',
        'options' => [
            'class' => 'default-form'
        ]
    ]);
    ?>

    <!-- id --> <?php // echo $form->field($model, 'id') ?>

    <!-- title -->
    <div class="col-md-4"> <?=
        $form->field($model, 'title')->textInput(['placeholder' => 'ricerca per titolo'])->label('Titolo'); ?>

    </div>

    <!-- description -->
    <div class="col-md-4"> <?=
        $form->field($model, 'description')->textInput(['placeholder' => 'ricerca per descrizione']) ?>

    </div>

    <!-- in_evidenza -->
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

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::a(Module::t('amosvideo', 'Annulla'), [Yii::$app->controller->action->id, 'currentView' => Yii::$app->request->getQueryParam('currentView')],
                ['class' => 'btn btn-outline-primary']) ?>
            <?= Html::submitButton('Cerca', ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>

    <?php ActiveForm::end(); ?>
</div>
