<?php


$checkedByDefault = false;
if(!empty($this->params['checkedByDefault']) && $this->params['checkedByDefault'] == true ){
    $checkedByDefault = true;
}
?>
<?php $object = \Yii::createObject($model['classname']);?>
<?php if($object->isVisible()) { ?>

    <div class="py-2">
        <div class="form-check">
            <input id="<?=\yii\helpers\StringHelper::basename($model['classname']);?>" type="checkbox" name="amosWidgetsIds[]" value="<?=$model['id'];?>" <?= (empty($this->params['widgetSelected']) && $checkedByDefault) ? 'checked' : (in_array($model['id'], $this->params['widgetSelected'])? 'checked' : '') ?> />
            <label for="<?=\yii\helpers\StringHelper::basename($model['classname']);?>"><?= \Yii::createObject($model['classname'])->getDescription(); ?></label>
        </div>
        
    </div>
<?php } ?>
