<div class="<?= isset($capConfig['class']) ? $capConfig['class'] : 'col-md-' . $colMdRow; ?>">
<?php
    //id del campo: se specificato nelle option uso quello, altrimenti sarà nel formato 'campo_db-id'
    $id = isset($capConfig['options']['id']) ? $capConfig['options']['id'] : $capConfig['attribute'].'-id';
    $id_comune = isset($comuneConfig['options']['id']) ? $comuneConfig['options']['id'] : $comuneConfig['attribute'].'-id';

    echo $form->field($model, $capConfig['attribute'])->widget(\kartik\depdrop\DepDrop::classname(), [
        'type' => \kartik\depdrop\DepDrop::TYPE_SELECT2,
        'data' => \yii\helpers\ArrayHelper::map(\open20\amos\comuni\models\IstatComuniCap::find()->andWhere(['comune_id' => $model->$comuneConfig['attribute']])->orderBy('cap')->asArray()->all(), 'id', 'cap'),
        'options' => array_merge(
            [
                'id' => $id,
            ], !empty($capConfig['options']) ? $capConfig['options'] : []
        ),
        'select2Options' => array_merge(
            [
                'pluginOptions' => ['allowClear' => true]
            ], !empty($capConfig['select2Options']) ? $capConfig['select2Options'] : []
        ),
        'pluginOptions' => array_merge(
            [
                'depends' => [$id_comune],
                'initDepends' => $id_comune,
                'placeholder' => Yii::t('app', 'Digita il cap ...'),
                'url' => \yii\helpers\Url::to(['/comuni/default/caps-by-comune']),
                'params' => [$id],
            ], !empty($capConfig['pluginOptions']) ? $capConfig['pluginOptions'] : []
        ),
        'pluginEvents' => [
            //il change viene chiamato al cambio del padre: comune
            "depdrop:afterChange"=>"function(event, id, value, count) { 
                clearValueIfParentEmpty($(this));
            }",
        ],
    ]);

?>
</div>
