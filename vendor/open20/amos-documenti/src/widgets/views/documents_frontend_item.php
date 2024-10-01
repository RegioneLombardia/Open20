<?php

use open20\amos\core\helpers\Html;
use open20\amos\documenti\AmosDocumenti;

?>
<?php /** @var $model \open20\amos\documenti\models\Documenti */ ?>
<?php
    $file = $model->hasOneFile('documentMainFile');
    ?>
    <div class="col-xs-12">
        <div class="col-xs-12 document-icon">
            <?php
            if (!is_null($model->getDocument())) {
                $url = $model->getDocument()->getWebUrl('square_medium', true);
            }
            echo \yii\helpers\Html::a($model->getDocumentImage(), $url);
            ?>
        </div>
        <div class="col-xs-12 document-title">
            <h1><?= $model->titolo ?></h1>
        </div>
        <div class="col-xs-12 document-subtitle">
            <h2><?= $model->sottotitolo ?></h2>
        </div>
        <div class="col-xs-12 document-abstract">
            <p><?= $model->descrizione_breve ?></p>
        </div>
        <div class="col-xs-12 document-description">
            <p><?= $model->descrizione ?></p>
        </div>
        <div class="col-xs-12 document-download">
            <?php if (!empty($file)) { ?>
                <a href="<?= $file->one()->getWebUrl() ?>" title="<?= AmosDocumenti::t('content', 'Download') ?>">
                    <?= AmosDocumenti::t('content', 'Download') ?>
                </a>
            <?php } ?>
        </div>

    </div>
