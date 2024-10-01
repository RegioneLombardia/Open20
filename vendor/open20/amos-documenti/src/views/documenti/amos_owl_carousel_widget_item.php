<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\views\documenti
 * @category   CategoryName
 */

use open20\amos\core\forms\ItemAndCardHeaderWidget;
use open20\amos\core\icons\AmosIcons;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\utility\DocumentsUtility;
use yii\helpers\Html;

/**
 * @var \open20\amos\documenti\models\Documenti $model
 * @var \open20\amos\documenti\widgets\DocumentsOwlCarouselWidget $widget
 */

$documentIcon = DocumentsUtility::getDocumentIcon($model);
$titleUrl = [Yii::$app->controller->action->id, 'parentId' => $model->id];
$iconUrl = $titleUrl;
if (!$model->is_folder) {
    $titleUrl = $model->getFullViewUrl();
    $iconUrl = $model->getDocumentMainFileUrl();
}

?>

<div class="owl-item-content">
    <div class="col-xs-3 col-md-3 nop">
        <span class="date"><?= AmosDocumenti::t('amosdocumenti', '#carousel_update_at') ?></span>
        <span class="date"><?= \Yii::$app->formatter->asDate($model->data_pubblicazione) ?></span>
        <div>
            <?= Html::a($documentIcon, $iconUrl, [
                'title' => AmosDocumenti::t('amosdocumenti', '#carousel_download'),
                'class' => ($model->is_folder) ? 'is-folder' : 'is-file'
            ]); ?>
            <?php if (!$model->is_folder): ?>
                <?= Html::a(AmosDocumenti::t('amosdocumenti', '#carousel_download'), $iconUrl, [
                    'title' => AmosDocumenti::t('amosdocumenti', '#carousel_download'),
                    'class' => 'download-file',
                ]); ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-xs-9 col-md-9 nop">
        <?= ItemAndCardHeaderWidget::widget([
            'model' => $model,
            'publicationDateNotPresent' => true,
            'showPrevalentPartnershipAndTargets' => true,
            'truncateLongWords' => true,
        ]) ?>
        <div class="col-xs-12 nop title">
            <?= Html::a($model->titolo, $titleUrl); ?>
        </div>
        <?php if (!is_null($model->parent)): ?>
            <div class="col-xs-12 nop directory">
                <?= AmosDocumenti::t('amosdocumenti', 'in') . ' "' . $model->parent->titolo . '"'; ?>
            </div>
        <?php endif; ?>
        <div class="col-xs-12 nop read-more">
            <?= Html::a(AmosDocumenti::t('amosdocumenti', '#carousel_details') . AmosIcons::show('chevron-right'), $titleUrl); ?>
        </div>
    </div>
</div>
