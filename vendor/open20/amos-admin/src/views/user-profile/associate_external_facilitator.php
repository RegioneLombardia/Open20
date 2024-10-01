<?php
use yii\helpers\Html;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\core\icons\AmosIcons;


echo $this->render('_search_external_facilitator', ['model' => $model]);

echo \open20\amos\core\views\ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => '@vendor/open20/amos-admin/src/views/user-profile/_item'
]);
