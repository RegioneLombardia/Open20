<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-community/src/views
 */

use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\community\models\Community;
use open20\amos\community\models\Bookmarks;
use open20\amos\community\rules\UpdateBookmarksRule;
use yii\helpers\Html;
use open20\amos\community\assets\AmosCommunityAsset;

/*
 * Personalizzare a piacimento la vista
 * $model è il model legato alla tabella del db
 * $buttons sono i tasti del template standard {view}{update}{delete}
 */

$community_id = Yii::$app->request->getQueryParam('id');

$dataPubblicazione = Yii::$app->getFormatter()->asDate($model->data_pubblicazione, 'dd/MM/yyyy');
$testoPubblicazione = BaseAmosModule::t('amoscommunity', 'il {date}', ['date' => $dataPubblicazione]);
if ($model->status === Bookmarks::BOOKMARKS_STATUS_PUBLISHED) {
    $testo = BaseAmosModule::t('amoscommunity', '#bookmarkPublished');
}
elseif ($model->status === Bookmarks::BOOKMARKS_STATUS_TOVALIDATE) {
    $testo = BaseAmosModule::t('amoscommunity', '#bookmarkToValidate');
}
else {
    $testo = BaseAmosModule::t('amoscommunity', '#bookmarkDraft');
}
AmosCommunityAsset::register($this);

?>

<div class="item-link-list">
    <div class="item-link-header">
        <p><strong><?= Html::a($model->titolo, $model->link, ['target' => '_blank']) ?></strong></p>
        <?php
        echo ContextMenuWidget::widget([
            'model' => $model,
            'actionModify' => "/community/bookmarks/update?id=" . $model->id,
            'actionDelete' => "/community/bookmarks/delete?id=" . $model->id . "&community=" . $community_id . '&redirect=1',
            'labelDeleteConfirm' => BaseAmosModule::t('amoscommunity', 'Sei sicuro di voler cancellare questo bookmark?')
        ]); ?>
    </div>
    <div class="list-element-body">
        <p>
            <span><b><?= $testo ?> </b><?= $model->userNames ?> <?= $testoPubblicazione ?></span>
        </p>
    </div>
</div>