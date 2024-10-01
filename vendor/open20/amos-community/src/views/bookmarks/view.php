<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    @vendor/open20/amos-community/src/views
 */

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;
use open20\amos\community\models\Community;

/**
 * @var yii\web\View $this
 * @var open20\amos\community\models\Bookmarks $model
 */

$this->title = Yii::t('amoscore', 'tutti i bookmarks', [
    'modelClass' => 'Bookmarks',
]);

$this->params['forceBreadcrumbs'][] = ['label' => 'Community', 'url' => ['/community/community/index']];
$this->params['forceBreadcrumbs'][] = ['label' => $model->community->name, 'url' => ['/community/join/open-join', 'id' => $model->community->id]];
$this->params['forceBreadcrumbs'][] = ['label' => 'Bookmarks', 'url' => ['/community/bookmarks/index', 'id' => $model->community->id]];
$this->params['forceBreadcrumbs'][] = ['label' => $this->title];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="bookmarks-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'titolo',
            [
                'attribute' => 'link',
                'format' => 'url'
            ],
            [
                'attribute' => 'data_pubblicazione',
                'format' => ['datetime', (isset(Yii::$app->getModule('datecontrol')->displaySettings['data'])) ? Yii::$app->getModule('datecontrol')->displaySettings['data'] : 'dd/MM/yyyy'],
            ],
            [
                'attribute' => 'community_id',
                'label' => 'Community',
                'value' => $model->getCommunityName()
            ],
            [
                'attribute' => 'creatore_id',
                'label' => 'Creatore',
                'value' => $model->getUserNames()
            ],
        ],
    ]) ?>

</div>

<div id="form-actions" class="bk-btnFormContainer pull-right">
    <?= Html::a(Yii::t('amoscore', 'Chiudi'), Url::previous(), ['class' => 'btn btn-secondary']); ?></div>
