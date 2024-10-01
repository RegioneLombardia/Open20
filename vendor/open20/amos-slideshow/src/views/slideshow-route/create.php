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

/**
 * @var yii\web\View $this
 * @var backend\modules\slideshow\models\SlideshowRoute $model
 */

$this->title = AmosSlideshow::t('amosslideshow', 'Create {modelClass}', [
    'modelClass' => 'Slideshow Route',
]);
$this->params['breadcrumbs'][] = ['label' => AmosSlideshow::t('amosslideshow', 'Slideshow Route'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="slideshow-route-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
