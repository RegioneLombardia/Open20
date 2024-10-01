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
 * @var open20\amos\slideshow\models\Slideshow $model
 */
use open20\amos\slideshow\AmosSlideshow;

$this->title = AmosSlideshow::t('amosslideshow', 'Crea');
$this->params['breadcrumbs'][] = ['label' => AmosSlideshow::t('amosslideshow', 'Slideshow'), 'url' => ['/slideshow']];
$this->params['breadcrumbs'][] = ['label' => AmosSlideshow::t('amosslideshow', 'Elenco'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="slideshow-create">
    <?= $this->render('_form', [
        'model' => $model,
        'route' => $route
    ]) ?>

</div>
