<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\views\news-categorie
 * @category   CategoryName
 */

use open20\amos\core\views\AmosGridView;
use open20\amos\news\AmosNews;
use open20\amos\news\models\NewsCategorie;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\news\models\search\NewsCategorieSearch $searchModel
 */

//cta: tutte le notizie
$this->title ='Categoria notizie';
$this->params['breadcrumbs'][] = ['label' => AmosNews::t('amosnews', 'Categorie notizie') , 'url' => '/news'];
//$this->params['breadcrumbs'][] = $this->title;

?>
<div class="news-categorie-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php echo AmosGridView::widget([
        'dataProvider' => $dataProvider,
        'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
        //'filterModel' => $model,
        'columns' => [
            [
                'label' => $model->getAttributeLabel('categoryIcon'),
                'format' => 'html',
                'value' => function ($model) {
                    /** @var NewsCategorie $model */
                    $url = $model->getCategoryIconUrl();
                    $contentImage = Html::img($url, ['class' => 'gridview-image', 'alt' => $model->getAttributeLabel('categoryIcon')]);
                    return $contentImage;
                }
            ],
            'titolo',
            'sottotitolo',
            'descrizione_breve',
            'descrizione:html',
            [
                'class' => 'open20\amos\core\views\grid\ActionColumn'
            ]
        ]
    ]); ?>
</div>
