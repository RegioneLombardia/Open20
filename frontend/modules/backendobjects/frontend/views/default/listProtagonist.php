<?php

use app\modules\backendobjects\frontend\Module;
use kartik\datecontrol\DateControl;
use luya\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;
use open20\amos\layout\assets\IsotopeAsset;

IsotopeAsset::register($this);
?>
<div class="<?= $cssClass ?>  bg-card-preference-bg py-5">
<div class="container">


    <?php if (!$withoutSearch) { ?>
        <?php
        $form = ActiveForm::begin([
            'action' => Url::toRoute(['/backendobjects']),
            'method' => 'get',
            'options' => [
                'id' => 'element_form_' . $model->id,
                'class' => 'form wrap-search',
                'enctype' => 'multipart/form-data',
            ]
        ]);
        ?>

        <?php
        foreach ($searchFields as $field) {
            switch ($field->type) {
                case "TEXT":
        ?>
                    <div class="col-xs-12 col-sm-6 col-lg-4">
                        <?= $form->field($model, $field->name) ?>
                    </div>
                <?php
                    break;
                case "DATE":
                ?>
                    <div class="col-xs-12 col-sm-6 col-lg-4">
                        <?=
                            $form->field($model, $field->name)->widget(DateControl::className(), [
                                'type' => DateControl::FORMAT_DATE
                            ])
                        ?>
                    </div>
        <?php
                    break;
            }
        }
        ?>

        <div class="col-xs-12 wrap-btn">
            <?= Html::a(Module::t('Annulla'), [Yii::$app->controller->action->id, 'currentView' => Yii::$app->request->getQueryParam('currentView')], ['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton(Module::t('Cerca'), ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    <?php } // !$withoutSearch 
    ?>

    <div class="clearfix"></div>
        <h3 class="mb-5 h1">I protagonisti</h3>

        <?php
        if(!is_null($dataProvider)){

            if ($dataProvider->getTotalCount() > 0) {

                echo ListView::widget([
                    'dataProvider' => $dataProvider,
                    'itemView' => '_itemProtagonist',
                    'viewParams' => [
                        'detailPage' => $detailPage,
                        'viewFields' => $viewFields,
                        'blockItemId' => $blockItemId,
                    ],
                    'options' => [
                        'class' => 'list-view d-flex align-items-center justify-content-start flex-wrap',
                    ],
                ]);
            }
            else {
                $js = <<<JS
    $('.section-protagonisti').attr('style',"display:none !important");
    $('.cta-protagonisti').attr('style',"display:none !important");
 
JS;
                $this->registerJs($js);
            }
        }

        ?>
</div>
</div>
