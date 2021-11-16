<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\views\documenti-categorie
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\views\AmosGridView;
use open20\amos\documenti\AmosDocumenti;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\documenti\models\search\DocumentiCategorieSearch $searchModel
 */

//$this->title = AmosDocumenti::t('amosdocumenti', '#page_title_documents_categories');
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="documenti-categorie-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php echo AmosGridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $model,
        'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => ''],
        'columns' => [
            'filemanager_mediafile_id' => [
                'label' => AmosDocumenti::t('amosdocumenti', 'Icona'),
                'format' => 'html',
                'value' => function ($model) {
                    $url = '/img/img_default.jpg';
                    if (!is_null($model->documentCategoryImage)) {
                        $url = $model->documentCategoryImage->getUrl('square_small', false, true);
                    }
                    return Html::img($url, ['class' => 'gridview-image', 'alt' => AmosDocumenti::t('amosdocumenti', 'Immagine della categoria')]);
                }
            ],
            'titolo',
            'sottotitolo',
            'descrizione_breve',
            'descrizione:ntext',
            [
                'class' => 'open20\amos\core\views\grid\ActionColumn',
            ],
        ],
    ]); ?>
</div>
