<?php
use open20\amos\dashboard\AmosDashboard;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;

$checkedByDefault = false;
if(!empty($this->params['checkedByDefault']) && $this->params['checkedByDefault'] == true ){
    $checkedByDefault = true;
}
?>
<?php $object = \Yii::createObject($model['classname']);?>
<?php if($object->isVisible()) { ?>
    <div class="card-widget">
        <div class="chechbox-widget pull-right">
            <label for="<?=\yii\helpers\StringHelper::basename($model['classname']);?>" class="sr-only"><?= \Yii::createObject($model['classname'])->getDescription(); ?></label>
            <input id="<?=\yii\helpers\StringHelper::basename($model['classname']);?>" type="checkbox" name="amosWidgetsIds[]" value="<?=$model['id'];?>" <?= (empty($this->params['widgetSelected']) && $checkedByDefault) ? 'checked' : (in_array($model['id'], $this->params['widgetSelected'])? 'checked' : '') ?> />
        </div>
        <div class="dashboard-item">
            <?php
                $object->setUrl('');
            ?>
            <?= $object->run(); ?>
        </div>
        <p><?= \Yii::createObject($model['classname'])->getDescription(); ?></p>
    </div>
<?php } ?>
