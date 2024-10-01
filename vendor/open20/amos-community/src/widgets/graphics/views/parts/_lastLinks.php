<?php

use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\community\models\Bookmarks;
use open20\amos\community\rules\UpdateBookmarksRule;

?>

<div id="container-links">
    <?php
    if (count($data) > 0) {
        $i = 0;
        foreach ($data as $element) {
            if (isset($limit)) {
                if ($i >= $limit) {
                    break;
                }
                $i++;
            }
            $link = $element->link;
            $titolo = $element->titolo;
            $utente = $element->userNames;
            $dataPubblicazione = Yii::$app->getFormatter()->asDate($element->data_pubblicazione, 'dd/MM/yyyy');
            $testoPubblicazione = BaseAmosModule::t('amoscommunity', 'il {date}', ['date' => $dataPubblicazione]);
            if ($element->status === Bookmarks::BOOKMARKS_STATUS_PUBLISHED) {
                $testo = BaseAmosModule::t('amoscommunity', '#bookmarkPublished');
            }
            elseif ($element->status === Bookmarks::BOOKMARKS_STATUS_TOVALIDATE) {
                $testo = BaseAmosModule::t('amoscommunity', '#bookmarkToValidate');
            }
            else {
                $testo = BaseAmosModule::t('amoscommunity', '#bookmarkDraft');
            } ?>
            <div class="item-link-list">
                <div class="item-link-header">
                    <p class="m-b-0"><strong><a href="<?= $link ?>" data-toggle="tooltip" title="<?= $link ?>" target="_blank"><?= $titolo ?></a></strong></p>


                    <?php
                    echo ContextMenuWidget::widget([
                        'model' => $element,
                        'actionModify' => "/community/bookmarks/update?id=" . $element->id,
                        'actionDelete' => "/community/bookmarks/delete?id=" . $element->id . "&community=" . $model->id,
                        'labelDeleteConfirm' => BaseAmosModule::t('community', 'Sei sicuro di voler cancellare questo bookmark?')
                    ]); ?>
                </div>


                <small><strong><?= $testo ?></strong> <?= $utente ?> <?= $testoPubblicazione ?></small>
            </div>
    <?php
        }
    } else {
        echo 'Nessun bookmark presente';
    } ?>
</div>