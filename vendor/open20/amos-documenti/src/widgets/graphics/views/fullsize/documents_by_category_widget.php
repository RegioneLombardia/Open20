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
use open20\amos\core\icons\AmosIcons;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\assets\ModuleDocumentiAsset;
use open20\amos\documenti\widgets\graphics\WidgetGraphicsDocumentsByCategory;

/**
 * @var yii\web\View $this
 * @var WidgetGraphicsDocumentsByCategory $widget
 */

ModuleDocumentiAsset::register($this);

?>

<div class="box-widget-header">
    <div class="box-widget-wrapper">
        <h2 class="box-widget-title">
            <?= AmosIcons::show('file-text-o', [], AmosIcons::DASH) ?>
            <span class="language-item"><?= AmosDocumenti::t('amosdocumenti', '#widget_graphic_documents_by_category_label') ?></span>
        </h2>
    </div>
    <div class="read-all"><?= Html::a(
            AmosDocumenti::t('amosdocumenti', 'Visualizza Tutti') . AmosIcons::show('chevron-right'),
            $widget->getBasicUrl(),
            ['class' => ''],
            false
        ); ?></div>
</div>
<div class="box-widget box-widget-column documents-by-category-widget">
    <section>
        <div class="list-items">
            <?php if (count($widget->documentsCategories) == 0): ?>
                <div class="list-items list-empty">
                    <p><?= AmosDocumenti::t('amosdocumenti', '#widget_graphic_documents_by_category_list_no_categories') ?></p>
                </div>
            <?php endif; ?>
            <?php foreach ($widget->documentsCategories as $documentCategory): ?>
                <?= $this->render('_list_element', [
                    'widget' => $widget,
                    'documentCategory' => $documentCategory,
                ]) ?>
            <?php endforeach; ?>
        </div>
    </section>
</div>
