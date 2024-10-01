<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\widgets\graphics\views\hierarchical-documents
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\documenti\utility\DocumentsUtility;
use open20\amos\documenti\widgets\graphics\WidgetGraphicsHierarchicalDocuments;

/**
 * @var yii\web\View $this
 * @var \open20\amos\documenti\models\Documenti $model
 */

$moduleDocuments = \Yii::$app->getModule(\open20\amos\documenti\AmosDocumenti::getModuleName());
$hidePubblicationDate = $moduleDocuments->hidePubblicationDate;
?>

<?= Html::beginTag('a', WidgetGraphicsHierarchicalDocuments::getLinkOptions($model)) ?>
<div class="card-container col-xs-12 nop<?= (!$model->is_folder ? ' file' : '') ?>">
    <div class="widget-listbox-option" role="option">
        <article class="col-xs-12 nop">
            <div class="container-icon col-xs-12">
                <?= DocumentsUtility::getDocumentIcon($model) ?>
            </div>
            <div class="icon-body col-xs-12">
                <p class="date">
                    <?= WidgetGraphicsHierarchicalDocuments::getDocumentDate($model) ?></p>
                <p class="directory-title"><?= WidgetGraphicsHierarchicalDocuments::getIconDescription($model) ?></p>
            </div>
        </article>
    </div>
</div>
<?= Html::endTag('a') ?>
