<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\views\event
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\interfaces\CmsModuleInterface;
use open20\amos\core\views\AmosGridView;
use open20\amos\core\views\grid\ActionColumn;
use open20\amos\events\AmosEvents;
use yii\data\ActiveDataProvider;

/**
 * @var yii\web\View $this
 * @var open20\amos\events\models\Event $model
 * @var AmosEvents $moduleEvents
 * @var \amos\sitemanagement\Module|AmosModule|CmsModuleInterface|null $siteManagementModule
 * @var \amos\sitemanagement\models\SiteManagementSlider|null $imageSlider
 * @var ActiveDataProvider|null $dataProviderImageSlider
 */

$addImageSliderBtnTitle = AmosEvents::t('amosevents', '#agid_add_gallery_image');
$urlRedirect = urlencode($model->getFullUpdateUrl());

?>
<div class="col-md-6 col-xs-12">
    <h3><?= AmosEvents::t('amosevents', '#agid_image_gallery') ?></h3>
    <?= Html::a($addImageSliderBtnTitle,
        [
            '/sitemanagement/site-management-slider-elem/create',
            'id' => $imageSlider->id,
            'slider_type' => \amos\sitemanagement\models\SiteManagementSliderElem::TYPE_IMG,
            'urlRedirect' => $urlRedirect,
            'useCrop' => true,
            'cropRatio' => 1.7
        ],
        [
            'class' => 'btn btn-navigation-primary',
            'title' => $addImageSliderBtnTitle,
            'data-confirm' => AmosEvents::t('amosevents', '#agid_add_slider_item_exit_page_confirm')
        ]
    ); ?>
    <?php
    $columnsImage = [
        'order',
        'title' => [
            'attribute' => 'title',
            'enableSorting' => false
        ],
        [
            'class' => ActionColumn::className(),
            'controller' => 'site-management-slider-elem',
            'template' => '{update}{delete}',
            'buttons' => [
                'update' => function ($url, $model) use ($urlRedirect) {
                    return Html::a(AmosIcons::show('edit'),
                        [
                            '/sitemanagement/site-management-slider-elem/update',
                            'id' => $model->id,
                            'urlRedirect' => $urlRedirect
                        ],
                        [
                            'class' => 'btn btn-navigation-primary'
                        ]
                    );
                },
                'delete' => function ($url, $model) use ($urlRedirect) {
                    return Html::a(AmosIcons::show('delete'),
                        [
                            '/sitemanagement/site-management-slider-elem/delete',
                            'id' => $model->id,
                            'urlRedirect' => $urlRedirect
                        ],
                        [
                            'class' => 'btn btn-danger-inverse'
                        ]
                    );
                }
            ]
        ]
    ];
    ?>
    <?= AmosGridView::widget([
        'dataProvider' => $dataProviderImageSlider,
        'columns' => $columnsImage
    ]); ?>
</div>
