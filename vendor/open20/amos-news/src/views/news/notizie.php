<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news
 * @category   CategoryName
 */

use open20\amos\core\views\DataProviderView;
use open20\amos\news\AmosNews;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\news\models\search\NewsSearch $searchModel
 */
$this->title = AmosNews::t('amosnews', 'Notizie');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

    <?php
    echo DataProviderView::widget([
        'dataProvider' => $dataProvider,
        'currentView' => $currentView,
        'gridView' => [
            //'filterModel' => $model,
            'columns' => [
                'titolo',
                'sottotitolo',
                'descrizione_breve',
                //'descrizione:ntext',
//            'metakey:ntext', 
//            'metadesc:ntext', 
//                'primo_piano:statosino', 
//            'hits', 
//            'abilita_pubblicazione:statosino', 
                'news_categorie_id' => [
                    'attribute' => 'newsCategorie.titolo',
                    'label' => 'Categoria'
                ],
                ['attribute' => 'data_pubblicazione', 'format' => ['date', (isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y']],
                /*'condominio' => [
                    'label' => 'Pubblicata per',
                    'value' => function ($model) {
                        return $model->getNetworkPubblicazione();
                    }
                ],*/
//            ['attribute'=>'data_rimozione','format'=>['date',(isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y']], 

                /*            'news_stati_id' => [
                  'attribute' => 'newsStati.nome',
                  'label' => 'Stato'
                  ], */
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                ],
            ],
        ],
        'listView' => [
            'itemView' => '_itemUtenti'
        ],
    ]);
    ?>

</div>
