<?php

use app\modules\backendobjects\frontend\Module;
use kartik\datecontrol\DateControl;
use luya\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;

?>
<?php
if (!is_null($dataProvider)) {
    if ($dataProvider->getTotalCount() > 0) {
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

            <div class="links-section container py-5 border-top border-light">
                <h3 class="mb-4 h1"><span class="mdi mdi-web"></span> Link</h3>


                <?php


                echo ListView::widget([
                    'summary' => false,
                    'dataProvider' => $dataProvider,
                    'itemView' => '_itemExternalLink',
                    'viewParams' => [
                        'detailPage' => $detailPage,
                        'viewFields' => $viewFields,
                        'blockItemId' => $blockItemId,
                    ],
                    'options' => [
                        'class' => 'document-list',
                    ]
                ]);
                ?>


            </div>
        </div>
        <?php
    } else {
        $js = <<<JS
    $('.links-section').attr('style',"display:none !important");
JS;
        $this->registerJs($js);
    }
} else {
    $js = <<<JS
    $('.links-section').attr('style',"display:none !important");
JS;
    $this->registerJs($js);
}
?>