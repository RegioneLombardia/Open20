<?php

use open20\amos\core\views\DataProviderView;
use yii\helpers\Html;

/** @var $dataProviderAccordion \open20\prodottiservizi\models\search\ProdottiServiziAccordionSearch */
/** @var $currentViewAccordion Array */
?>
<div class="col-xs-12">
    <?= DataProviderView::widget([
        'dataProvider' => $dataProviderAccordion,
        //'filterModel' => $model,
        'currentView' => $currentViewAccordion,
        'gridView' => [
            'columns' => [
                'title',
                'description' => [
                    'attribute' => 'description',
                    'format' => 'html',
                    'value' => function($model) {
                        return (strlen(strip_tags($model->description)) > 200) ? substr(strip_tags($model->description), 0 ,200) . '...' :
                        strip_tags($model->description);
                    }
                ],
                'is_visible' => [
                    'attribute' => 'is_visible',
                    'value' => function($model) {
                        return ($model->is_visible) ? Yii::t('prodottiservizi', 'Si') :
                            Yii::t('prodottiservizi', 'No');
                    }
                ],
                'position' => [
                    'attribute' => 'position',
                    'label' => Yii::t('prodottiservizi', 'Spostamento'),
                    'format' => 'raw',
                    'value' => function($model) {
                        /** @var $model \open20\prodottiservizi\models\ProdottiServiziAccordion */
                        return $model->getButtonsPosition();
                    }
                ],
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                    'template' => '{update}{delete}',
                    'buttons' => [
                        'update' => function($url, $model) {
                            return Html::a('update', ['#'], [
                                'title' => Yii::t('amoscore', 'Modifica'),
                                'data-accordion-update' => $model->id
                            ]);
                        },
                        'delete' => function($url, $model) {
                            $options = [
                                'id' => 'testD_' . $model->id,
                                'modalHeader' => '<div style="color:#a61919">' . Yii::t('amoscore', '#confirm') . '</div>',
                                'modalDescriptionText' => Yii::t('amoscore', 'Sei sicuro di voler cancellare questo elemento?'),
                                'confirmLink' => ['delete'],
                                'confirmBtnOptions' => ['class' => 'btn btn-navigation-primary', 'data-accordion-delete' => $model->id, 'data-dismiss' => 'modal']
                            ];
                            \open20\amos\core\utilities\ModalUtility::createConfirmModal($options);
                            return Html::a('delete', ['#'], ['data-toggle' => 'modal', 'data-target' => '#testD_' . $model->id, 'title' => Yii::t('amoscore', 'Delete')]);
                        }
                    ]
                ],
            ],
        ],
    ]); ?>
</div>