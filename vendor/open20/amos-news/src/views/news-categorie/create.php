<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\views\news-categorie
 * @category   CategoryName
 */

use open20\amos\news\AmosNews;

/**
 * @var yii\web\View $this
 * @var open20\amos\news\models\NewsCategorie $model
 */

$this->title = AmosNews::t('amosnews', 'Crea categoria', [
    'modelClass' => 'News Categorie',
]);
$this->params['breadcrumbs'][] = ['label' => AmosNews::t('amosnews', 'Notizie'), 'url' => '/news'];
$this->params['breadcrumbs'][] = ['label' => AmosNews::t('amosnews', 'Categorie notizie'), 'url' => ['index']];
$this->params['breadcrumbs'][] = AmosNews::t('amosnews', 'Crea');
?>

<div class="news-categorie-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
