<?php

use app\assets\NewsAsset;
use app\modules\backendobjects\frontend\Module;
use kartik\datecontrol\DateControl;
use luya\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;


?>
<div class="<?= $cssClass ?>">
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
    <div class="uk-container py-5 border-light border-top">
        <div>
           <h3 class="h1">Immagini</h3> 
        </div>
        
    </div>

    <div class="content-gallery uk-container it-grid-list-wrapper pb-5 mb-5">

    <?php
    if (!is_null($dataProvider)) {
        if ($dataProvider->getTotalCount() > 0) {
//            var_dump( $dataProvider->pagination->pageParam);
//            $dataProvider->pagination->params = \yii\helpers\ArrayHelper::merge(['#' => 'list-gallery'], $dataProvider->pagination->params);
//             \yii\widgets\Pjax::begin(['id' => 'pjax-all-user', 'timeout' => 2000]);
            echo ListView::widget([
                    'id' => 'list-gallery',
                'summary' => false,
//                'pager' => [
//                    'maxButtonCount'=>0,
//                ],
                'dataProvider' => $dataProvider,
                'itemView' => '_itemImageGallery',
                'viewParams' => [
                    'detailPage' => $detailPage,
                    'viewFields' => $viewFields,
                    'blockItemId' => $blockItemId,
                ],
                'options' => [
                    
                    'class' => 'list-view grid-row',
                ],
            ]);
//             \yii\widgets\Pjax::end();
        } else {
            $js = <<<JS
    $('.section-gallery').attr('style',"display:none !important");
    $('#menu-gallery').remove();

JS;
            $this->registerJs($js);
        }
    } else {
        $js = <<<JS
    $('.section-gallery').attr('style',"display:none !important");


JS;
        $this->registerJs($js);
    }
    ?>
    </div>
</div>