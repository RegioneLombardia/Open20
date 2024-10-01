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
<?php if($object->isVisible()) {
    if(\Yii::$app->controller->module->id == 'community' && $model['classname'] == "open20\amos\admin\widgets\icons\WidgetIconUserProfile"){
        $object->setLabel(\open20\amos\community\AmosCommunity::tHtml('amoscommunity', 'Partecipanti'));
        $object->setDescription(\open20\amos\community\AmosCommunity::t('amoscommunity', "Elenco dei partecipanti alla community"));
    }
    ?>

    <div class="card-widget">
        <div class="chechbox-widget pull-right">
            <label for="<?=\yii\helpers\StringHelper::basename($model['classname']);?>" class="sr-only"><?= $object->getDescription(); ?></label>
            <input id="<?=\yii\helpers\StringHelper::basename($model['classname']);?>" type="checkbox" name="amosWidgetsIds[]" value="<?=$model['id'];?>" <?= (empty($this->params['widgetSelected']) && $checkedByDefault) ? 'checked' : (in_array($model['id'], $this->params['widgetSelected'])? 'checked' : '') ?> />
        </div>
        <div class="dashboard-item">
            <?php
                $object->setUrl('');
            ?>
            <?= $object->run(); ?>
        </div>
        <p><?= $object->getDescription(); ?></p>
    </div>
<?php } ?>
