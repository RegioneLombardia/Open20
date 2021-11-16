<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comments\views\comment-reply
 * @category   CategoryName
 */

use open20\amos\comments\AmosComments;

/**
 * @var yii\web\View $this
 * @var open20\amos\comments\models\CommentReply $model
 */

$this->title = AmosComments::t('amoscomments', 'Create');
$this->params['breadcrumbs'][] = ['label' => AmosComments::t('amoscomments', 'Comments Replies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comment-reply-create">
    <?= $this->render('_form', [
        'model' => $model,
        'fid' => NULL,
        'dataField' => NULL,
        'dataEntity' => NULL,
    ]) ?>
</div>
