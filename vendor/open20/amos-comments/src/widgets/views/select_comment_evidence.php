<?php

use open20\amos\comments\AmosComments;

/**
 * @var AmosComments $module
 * @var \open20\amos\comments\models\Comment $comment
 */

$hidePin = false;
if (isset(Yii::$app->view->params['hideCommentEvidencePin'])) {
    $hidePin = Yii::$app->view->params['hideCommentEvidencePin'];
}
?>

<?php if ($module->enableCommentInEvidence && !$hidePin) {
    $iconClass = 'ic_pin-outline';
    if($comment->in_evidence){
        $iconClass = 'ic_pin';
    }?>
    <a data-pjax="0" data-key="<?= $comment->id ?>" title="<?= AmosComments::t('amoscomments', 'Metti in evidenza') ?>"
       class="add-remove-pin btn btn-xs float-right" href="#" data-toggle="tooltip">
        <svg class="icon icon-xs icon-secondary">
            <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#<?=$iconClass?>"></use>
        </svg>
    </a>
<?php } ?>
