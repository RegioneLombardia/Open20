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

$this->title = $model->titolo;
$this->params['breadcrumbs'][] = ['label' => AmosNews::t('amosnews', 'Notizie'), 'url' => '/news'];
$this->params['breadcrumbs'][] = ['label' => AmosNews::t('amosnews', 'Categorie notizie'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->titolo, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = AmosNews::t('amosnews', 'Aggiorna');
?>
<div class="news-categorie-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
