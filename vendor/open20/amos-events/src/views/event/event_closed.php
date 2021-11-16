<?php

?>
<h3><?= \open20\amos\events\AmosEvents::txt("Registrations closed"); ?></h3>

<?php if(\Yii::$app->request->referrer){
    echo \yii\helpers\Html::a(\open20\amos\events\AmosEvents::t('amosevents','Close'),\Yii::$app->request->referrer,['class' => 'btn btn-secondary']);
}?>
