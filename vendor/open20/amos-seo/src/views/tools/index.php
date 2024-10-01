<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = \open20\amos\seo\AmosSeo::t('amosseo', 'Genera Pretty Url mancanti');
$moduleName = \open20\amos\seo\AmosSeo::getModuleName()

?>
<div class="default-index">
    <div class="row">
        <?php
        foreach ($modules as $module) {
            $moduleName = $module::getModuleName();
            ?>
            <div class="generator col-lg-4">
                <h3><?= strtoupper($moduleName) ?></h3>
                <p><?= Html::a('Genera', Url::toRoute(['/'.$moduleName.'/tools/generate-missing-pretty-urls', 'moduleName' => $moduleName]), ['class' => 'btn btn-default']) ?></p>
            </div>
        <?php } ?>

    </div>
</div>




