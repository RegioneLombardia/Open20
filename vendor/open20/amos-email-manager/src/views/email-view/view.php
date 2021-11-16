<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-email-manager/src/views
 */

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var open20\amos\emailmanager\models\EmailView $model
 */

$this->title = strip_tags($model);
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['/emailmanager']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('amoscore', 'Email View'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="email-view-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'module',
            'view',
            [
                'attribute' => 'content',
                'format' => 'raw',
            ],
        ],
    ]) ?>

</div>

<div id="form-actions" class="bk-btnFormContainer pull-right">
    <?= Html::a(Yii::t('amoscore', 'Chiudi'), Url::previous(), ['class' => 'btn btn-secondary']); ?></div>
