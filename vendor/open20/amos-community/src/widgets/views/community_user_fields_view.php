<?php
/**
 * @var $dynamicModel \yii\base\DynamicModel
 * @var $form \open20\amos\core\forms\ActiveForm
 * @var $modelFieldImages
 */

$fields = \open20\amos\community\utilities\CommunityUserFieldUtility::getCommunityUserFieldValues();
foreach ($fields as $field){
    $value = $field->getCommunityUserFieldVals($model->user_id)->one();
    if(!empty($value->value)) {
        ?>
        <?php
        $type = null;
        if(!empty($value->userField->fieldType)) {
            $type = $value->userField->fieldType;
        }
        if($type->type == 'select_single'){
            $fieldValDef = \open20\amos\community\models\CommunityUserFieldDefaultVal::find()->andWhere(['id' => $value->value])->one();
            ?>
            <div class="row">
                <div class="col-md-3 col-sm-4 col-xs-12 bold"><?= $field->description ?></div>
                <div class="col-md-9 col-sm-8 col-xs-12"><?= (!empty($fieldValDef) ? $fieldValDef->value : '')?></div>
            </div>
        <?php } else if($type->type == 'date'){ ;?>
            <div class="row">
                <div class="col-md-3 col-sm-4 col-xs-12 bold"><?= $field->description ?></div>
                <div class="col-md-9 col-sm-8 col-xs-12"><?= (!empty($value->value) ? \Yii::$app->formatter->asDate($value->value) : '')?></div>
            </div>
       <?php  } else { ?>
            <div class="row">
                <div class="col-md-3 col-sm-4 col-xs-12 bold"><?= $field->description ?></div>
                <div class="col-md-9 col-sm-8 col-xs-12"><?= $value->value ?></div>
            </div>
            <?php
        }
    }
}
