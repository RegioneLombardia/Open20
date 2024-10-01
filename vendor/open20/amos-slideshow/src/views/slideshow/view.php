<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\slideshow
 * @category   CategoryName
 */

use open20\amos\slideshow\AmosSlideshow;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var open20\amos\slideshow\models\Slideshow $model
 */
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => AmosSlideshow::t('amosslideshow', 'Slideshow'), 'url' => ['/slideshow']];
$this->params['breadcrumbs'][] = ['label' => AmosSlideshow::t('amosslideshow', 'Elenco'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="slideshow-view col-xs-12">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'description:ntext',
            'slideshowRoutes.already_view:statosino',
            'slideshowRoutes.route',
            'slideshowRoutes.role',
            /*[
                'attribute' => 'created_at',
                'format' => ['datetime', (isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'],
            ],
            [
                'attribute' => 'updated_at',
                'format' => ['datetime', (isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'],
            ],
            [
                'attribute' => 'deleted_at',
                'format' => ['datetime', (isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'],
            ],
            'created_by',
            'updated_by',
            'deleted_by',*/
        ],
    ]) ?>
</div>
