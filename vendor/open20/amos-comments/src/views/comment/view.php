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
use open20\amos\core\forms\CloseButtonWidget;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var open20\amos\comments\models\Comment $model
 */

$this->title = strip_tags(substr($model->comment_text, 0, 15) . '...');
$this->params['breadcrumbs'][] = ['label' => AmosComments::t('amoscomments', 'Comments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comment-reply-view">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'comment_text'
        ],
    ]) ?>
</div>
 
<?= CloseButtonWidget::widget([
    'title' => AmosComments::t('amoscomments', 'Close'),
    'layoutClass' => 'pull-right'
]) ?>
