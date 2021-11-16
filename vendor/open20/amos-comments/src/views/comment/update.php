<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comments\views\comment
 * @category   CategoryName
 */

use open20\amos\comments\AmosComments;

/**
 * @var yii\web\View $this
 * @var open20\amos\comments\models\Comment $model
 */

$this->title = AmosComments::t('amoscomments', 'Update');
$this->params['breadcrumbs'][] = ['label' => AmosComments::t('amoscomments', 'Comments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comment-update">
    <?= $this->render('_form', [
        'no_attach' => $no_attach,
        'model' => $model,
        'url' => $url,
        'fid' => NULL,
        'dataField' => NULL,
        'dataEntity' => NULL,
    ]) ?>
</div>
