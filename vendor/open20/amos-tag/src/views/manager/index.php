<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\tag
 * @category   CategoryName
 */

use open20\amos\tag\models\Tag;
use kartik\tree\TreeView;
use kartik\tree\Module;
use open20\amos\tag\AmosTag;

$this->title = AmosTag::t('amostag', 'Gestione Tag');
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    /**
        @todo Add all AM ICONS to iconEditSettings listData property
    */
    .field-tag-icon_type {
        display: none;
    }
</style>
<div class="tag-form">

    <?= TreeView::widget([
        'headerTemplate' => '<div class="row">
                                    <div class="col-sm-12">
                                        {heading}
                                        {search}
                                    </div>
                                </div>',
        'query' => Tag::find()->addOrderBy('root, lft'),
        'headingOptions' => ['label' => AmosTag::tHtml('amostag', 'Liste tag')],
        'rootOptions' => ['label' => '<span class="text-primary">' . AmosTag::t('amostag', 'Radice tag') . '</span>'],
        'fontAwesome' => false,
        'isAdmin' => Yii::$app->user->can('AMMINISTRATORE_TAG')? true: false,
        'displayValue' => 1,
        'iconEditSettings' => [
            'show' => 'text',
            'type' => 1,
            /*'listData' => [
                'home' => 'Appartamento',
                'building-o' => 'Condominio',
            ]*/
        ],
        'softDelete' => false,
        'cacheSettings' => ['enableCache' => true],
        'nodeAddlViews' => [
//            Module::VIEW_PART_1 => '',
            Module::VIEW_PART_2 => '@vendor/open20/amos-tag/src/views/manager/custom_fields',
//            Module::VIEW_PART_3 => '',
            Module::VIEW_PART_4 => '@vendor/open20/amos-tag/src/views/manager/roots_advanced',
            //\yii\helpers\Url::to(['rootsAdvanced']),
//            Module::VIEW_PART_5 => '',
        ],
//        'nodeActions' => [
//            \kartik\tree\Module::NODE_SAVE => Url::to(['/tag/amos-node/save']),
//        ]
    ]);
    ?>
</div>