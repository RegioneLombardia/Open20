<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-comments/src/views
 */
/**
 * @var yii\web\View $this
 * @var open20\amos\comments\models\CommentBlacklist $model
 */

use open20\amos\comments\AmosComments;

$this->title = AmosComments::t('amoscomments', 'Aggiungi');
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/comments']];
$this->params['breadcrumbs'][] = ['label' => 'Comment Blacklist', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['forceBreadcrumbs'][] = ['label' => AmosComments::t('amoscomments', 'Netiquette'), 'url' => ['/comments/comment-dashboard/netiquette']];
$this->params['forceBreadcrumbs'][] = ['label' => AmosComments::t('amoscomments', 'Aggiungi')];
?>
<div class="comment-blacklist-create">
    <?= $this->render('_form', [
        'model' => $model,
        'fid' => NULL,
        'dataField' => NULL,
        'dataEntity' => NULL,
    ]) ?>

</div>
