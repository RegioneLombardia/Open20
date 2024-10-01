<?php
use open20\amos\dashboard\AmosDashboard;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
?>

<div class="card-widget">
    <div class="chechbox-widget pull-right">
        <label for="<?=\yii\helpers\StringHelper::basename($model['classname']);?>" class="sr-only"><?= Yii::createObject($model['classname'])->getDescription(); ?></label>
        <input id="<?=\yii\helpers\StringHelper::basename($model['classname']);?>" type="checkbox" name="amosWidgetsClassnames[]" value="<?=$model['classname'];?>" <?= (in_array($model['classname'], $this->params['widgetSelected'])? 'checked' : '') ?> />
    </div>
    <div class="dashboard-item">
        <?php
            $object = \Yii::createObject($model['classname']);
            $object->setUrl('');
        ?>
        <?= $object->run(); ?>
    </div>
    <p><?= Yii::createObject($model['classname'])->getDescription(); ?></p>
</div>
