<?php

use yii\helpers\Html;
use yii\helpers\Url;
$moduleName = \open20\amos\seo\AmosSeo::getModuleName();
$this->title = \open20\amos\seo\AmosSeo::t('amosseo', 'Genera Pretty Url mancanti');
?>
<div class="default-index">
    <h3><?= \open20\amos\seo\AmosSeo::t('amosseo', 'Dati SEO creati:') ?> <?= $modelsCount ?></h3>
    <?php if ($errorMessage): ?>
        <div class="alert alert-danger"><?= $errorMessage ?></div>
    <?php endif; ?>
</div>

<?= Html::a('Indietro',Url::toRoute('/'.$moduleName.'/tools'),['class' => 'btn btn-default']) ?>




