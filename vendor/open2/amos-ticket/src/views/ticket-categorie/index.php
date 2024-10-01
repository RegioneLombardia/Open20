<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\views\ticket-categorie
 * @category   CategoryName
 */

use open20\amos\core\views\AmosGridView;
use open2\amos\ticket\AmosTicket;
use open2\amos\ticket\models\TicketCategorie;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open2\amos\ticket\models\search\TicketCategorieSearch $model
 */

$this->title = AmosTicket::t('amosticket', 'Categorie');
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => '/ticket/ticket-categorie/index'];
//$this->params['breadcrumbs'][] = $this->title;

/** @var AmosTicket $module */
$module = \Yii::$app->getModule('ticket');
$fielsdToHide = (!empty($module) && is_array($module->categoryFieldsHide)) ? $module->categoryFieldsHide : [];
?>
<div class="news-categorie-index">
    <?php echo $this->render('_search', ['model' => $model]); ?>
    <?php
    
    $columns = [];
    
    if ($module->enableCategoryIcon) {
        $columns[] = [
            'label' => $model->getAttributeLabel('categoryIcon'),
            'format' => 'html',
            'value' => function ($model) {
                /** @var TicketCategorie $model */
                $url = $model->getCategoryIconUrl('table_small');
                $contentImage = Html::img($url, ['class' => 'gridview-image', 'alt' => $model->getAttributeLabel('categoryIcon')]);
                return $contentImage;
            }
        ];
    }
    
    $columns[] = 'titolo';
    
    $columns[] = 'descrizione:html';
    
    if (!$module->oneLevelCategories) {
        $columns['categoria_padre_id'] = [
            'attribute' => 'categoriaPadre.nomeCompleto',
            'label' => AmosTicket::t('amosticket', 'Categoria padre')
        ];
    }

    if (!in_array('tecnica', $fielsdToHide) || !in_array('administrative', $fielsdToHide)) {
        if ($module->enableAdministrativeTicketCategory && !in_array('tecnica', $fielsdToHide) && !in_array('administrative', $fielsdToHide)) {
            $columns[] = [
                'label' => AmosTicket::t('amosticket', '#category_type'),
                'value' => function ($model) {
                    /** @var TicketCategorie $model */
                    if ($model->tecnica) {
                        return AmosTicket::t('amosticket', '#external_technical');
                    } elseif ($model->administrative) {
                        return AmosTicket::t('amosticket', '#external_administrative');
                    } else {
                        return AmosTicket::t('amosticket', '#category_type_empty');
                    }
                }
            ];
        } elseif ($module->enableAdministrativeTicketCategory && in_array('tecnica', $fielsdToHide) && !in_array('administrative', $fielsdToHide)) {
            $columns[] = 'administrative:boolean';
        } elseif (!in_array('tecnica', $fielsdToHide)) {
            $columns[] = 'tecnica:boolean';
        }
    }
    
    $columns[] = [
        'class' => 'open20\amos\core\views\grid\ActionColumn'
    ];
    
    echo AmosGridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $model,
        'columns' => $columns,
    ]);
    ?>
</div>
