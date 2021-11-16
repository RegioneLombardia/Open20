<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    Open20Package
 * @category   CategoryName
 */

use app\modules\backendobjects\frontend\Module;
use kartik\datecontrol\DateControl;
use luya\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;
use open20\amos\layout\assets\IsotopeAsset;

IsotopeAsset::register($this);
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
    <?php } // !$withoutSearch ?>

    <div class="clearfix"></div>

    <?php
    if ($dataProvider->getTotalCount() > 0) {

        echo ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '_item',
            'viewParams' => ['detailPage' => $detailPage,
                'viewFields' => $viewFields,
                'blockItemId' => $blockItemId,
                ],
        ]);
    }
    ?>
</div>   