<?php

use kartik\select2\Select2;
use open20\amos\sondaggi\models\search\SondaggiInvitationsSearch;
use open20\amos\sondaggi\AmosSondaggi;
use open20\amos\core\icons\AmosIcons;

if ($count == 0) {
    $model->include_exclude = null;
    echo $form->field($model, 'include_exclude')->hiddenInput()->label(false);
    $model->field = null;
    echo $form->field($model, 'field')->hiddenInput()->label(false);
    $model->value = null;
    echo $form->field($model, 'value')->hiddenInput()->label(false);
}
?>
<?php for ($i = 1; $i <= $count; $i++) { ?>
    <div id="row-search-<?= $i ?>-organizations" class="row d-flex flex-wrap align-item-center">
        <div class="col-xs-12 col-sm-12 col-md-3">
            <?= $form->field($model, "include_exclude[$i]")->widget(Select2::className(), [
                'data' => [
                    SondaggiInvitationsSearch::FILTER_INCLUDE => AmosSondaggi::t('amossondaggi', '#invite'),
                    SondaggiInvitationsSearch::FILTER_EXCLUDE => AmosSondaggi::t('amossondaggi', "#no_invite")
                ],
                'options' => [
                    //                'placeholder' => AmosSondaggi::t('AmosSondaggi', "Select...")

                ]
            ])->label(AmosSondaggi::t('amossondaggi', '#rule')) ?>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-4">
            <?= $form->field($model, "field[$i]")->widget(Select2::className(), [
                'data' => SondaggiInvitationsSearch::getListOfAttributes(),
                'options' => [
                    'class' => 'filter-field',
                    'id' => 'field-' . $i . '-organizations',
                    'placeholder' => AmosSondaggi::t('amossondaggi', "#select")
                ]
            ])->label(AmosSondaggi::t('amossondaggi', '#field')) ?>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-4">
            <?php
                $typeValues = $model->field[$i] ? SondaggiInvitationsSearch::getAttributesValues('organizations', $model->field[$i])  : null;
                $output = null;
                if (!empty($typeValues))
                    foreach($typeValues as $item) {
                        $output[$item->id] = $item->name;
                    }
                $data = !empty($output) ? $output : null;
            ?>
            <?= $form->field($model, "value[$i]")->widget(\kartik\depdrop\DepDrop::className(), [
                'type' => \kartik\depdrop\DepDrop::TYPE_SELECT2,
                'data' => $data,
                'options' => [
                    'class' => 'filter-value',
                    'id' => 'value-' . $i,
                    //'multiple' => true
                ],
                'pluginOptions' => [
                    'url' => \yii\helpers\Url::to(['/sondaggi/dashboard-invitations/get-values', 'target' => 'organizations']),
                    'depends' => ['field-' . $i . '-organizations'],
                    'placeholder' => AmosSondaggi::t('amossondaggi', "#select"),
                ],
                'select2Options' => [
                    // 'options' => [
                    //     'multiple' => true,
                    // ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        // 'minimumInputLength' => 1,
                        // 'ajax' => [
                        //     'url' => \yii\helpers\Url::to(['/sondaggi/dashboard-invitations/get-values']),
                        //     'dataType' => 'json',
                        //     'data' => new \yii\web\JsExpression('function(params) { return {q:params.term}; }')
                        // ],
                    ],
                ]
            ])->label(AmosSondaggi::t('amossondaggi', '#value')); ?>
        </div>

        <div class="col-xs-2 col-sm-2 col-md-1 text-right">
            <?= \yii\helpers\Html::a(Amosicons::show('delete'), '', [
                'class' => 'btn btn-danger-inverse m-t-15',
                'id' => 'btn-remove-rule-organizations',
                'data' => $i
            ]) ?>

        </div>
    </div>
    <?= $form->field($model, 'sondaggi_id')->hiddenInput()->label(false) ?>
<?php } ?>