<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\widgets\graphics\views\fullsize
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\DocumentiCategorie;
use open20\amos\documenti\widgets\graphics\WidgetGraphicsDocumentsByCategory;

/**
 * @var yii\web\View $this
 * @var WidgetGraphicsDocumentsByCategory $widget
 * @var DocumentiCategorie $documentCategory
 */

$documentsByCategoryUrl = $widget->makeDocumentsByCategoryUrl($documentCategory->id);
$categoryImage = Html::img($documentCategory->getAvatarUrl('square_small'), [
    'class' => 'gridview-image',
    'alt' => AmosDocumenti::t('amosdocumenti', 'Immagine della categoria')
]);

?>

<div class="widget-listbox-option" role="option">
    <article class="wrap-item-box">
        <div class="item-box_header">
            <div class="item-box_img"><?= Html::a($categoryImage, $documentsByCategoryUrl); ?></div>
            <div class="item-box_title"><?= Html::a($documentCategory->titolo, $documentsByCategoryUrl); ?></div>
        </div>
        <!-- <div class="item-box_desc"><p>< ?= $documentCategory->descrizione_breve; ?></p></div> -->
        <div class="item-box_desc"><?= Html::a($documentCategory->descrizione_breve, $documentsByCategoryUrl); ?></p></div>
        <div class="item-box_count"><p>Documenti Presenti: <strong><?= $widget->documentsCountByCategories[$documentCategory->id]; ?></strong></p></div>
    </article>
</div>
