<?php

use app\modules\backendobjects\frontend\Module;
use kartik\datecontrol\DateControl;
use luya\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;

?>
<?php if (!is_null($dataProvider)) {
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
            <section class="section-news-timeline neutral-1-bg-a1 py-5">
            <div class="news-timeline container">

            




                        <?php


                        echo ListView::widget([
                            'summary' => false,
                            'dataProvider' => $dataProvider,
                            'itemView' => '_itemNewsTimeline',
                            'viewParams' => [
                                'detailPage' => $detailPage,
                                'viewFields' => $viewFields,
                                'blockItemId' => $blockItemId,
                            ],
                            'options' => [
                                'class' => 'news-list-timeline',
                            ]
                        ]);
                        ?>

                        
            </div>
            </section>
        </div>
        <?php
    } else {
        $js = <<<JS
    $('.section-news-timeline').attr('style',"display:none !important");

JS;
        $this->registerJs($js);
    }
}
?>