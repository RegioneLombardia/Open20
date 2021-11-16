<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni
 * @category   CategoryName
 */

use open20\amos\discussioni\AmosDiscussioni;
use open20\amos\core\utilities\ViewUtility;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var open20\amos\discussioni\models\DiscussioniCommenti $model
 */
$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => AmosDiscussioni::t('amosdiscussioni', 'Discussioni Commenti'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="discussioni-commenti-view">
<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        'testo:ntext',
        'discussioni_risposte_id',
        ['attribute' => 'created_at', 'format' => ['datetime', ViewUtility::formatDateTime()]],
        ['attribute' => 'updated_at', 'format' => ['datetime', ViewUtility::formatDateTime()]],
        ['attribute' => 'deleted_at', 'format' => ['datetime', ViewUtility::formatDateTime()]],
        'created_by',
        'updated_by',
        'deleted_by',
        'version',
    ]
])
?>
</div>
