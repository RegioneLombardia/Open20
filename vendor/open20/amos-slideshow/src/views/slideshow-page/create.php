<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\slideshow
 * @category   CategoryName
 */

/**
 * @var yii\web\View $this
 * @var open20\amos\slideshow\models\SlideshowPage $model
 */

use open20\amos\slideshow\AmosSlideshow;

$this->title = AmosSlideshow::t('amosslideshow', 'Crea');/*
$this->params['breadcrumbs'][] = ['label' => AmosSlideshow::t('amosslideshow', 'Slideshow'), 'url' => ['/slideshow']];
if (isset($model->slideshow_id)) {
    $this->params['breadcrumbs'][] = ['label' => AmosSlideshow::t('amosslideshow', 'Elenco'), 'url' => ['index', 'slideshowId' => $model->slideshow_id]];
    $this->params['breadcrumbs'][] = ['label' => AmosSlideshow::t('amosslideshow', 'Pagine'), 'url' => ['/slideshow/slideshow-page/index', 'slideshowId' => $model->slideshow_id]];
}
$this->params['breadcrumbs'][] = AmosSlideshow::t('amosslideshow', 'Crea');*/
?>
<div class="slideshow-pages-create">
    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
