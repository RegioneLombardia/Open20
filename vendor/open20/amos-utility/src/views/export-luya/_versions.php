<?php
/**
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $navItem \luya\cms\models\NavItem
 */

use yii\grid\GridView;
use open20\amos\utility\Module;

$currentVersion = $navItem->nav_item_type_id;
echo \open20\amos\core\views\AmosGridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'label' => '',
            'value' => function($model)use($currentVersion){
                if($currentVersion == $model->id){
                    return \open20\amos\core\icons\AmosIcons::show('check-circle',[
                        'class' => 'text-success',
                        'title' => Module::t('luyaconfigs',"Versione corrente"),
                        'data-toggle' => 'tooltip'
                    ]);
                }
                return '';
            },
            'format' => 'raw'
        ],
        [
            'class' => 'yii\grid\SerialColumn',
            'header' => Module::t('luyaconfigs', "Versione")
        ],
        [
            'attribute' => 'version_alias',
            'label' => Module::t('luyaconfigs', "Nome")
        ],
        [
            'attribute' => 'timestamp_create',
            'value' => function ($model) {
                return date('d/m/Y H:i', $model->timestamp_create);
            },
            'label' => Module::t('luyaconfigs', "Creata il")
        ],
        [
            'class' => 'open20\amos\core\views\grid\ActionColumn',
            'template' => '{export}',
            'buttons' => [
                'export' => function ($url, $model) {
                    $navItem = $model->forceNavItem;
                    if($navItem) {
                        return \yii\helpers\Html::a(\open20\amos\core\icons\AmosIcons::show('square-right'), ['/utility/export-luya/export-page', 'nav_id' => $navItem->nav_id, 'version' => $model->id], [
                            'class' => 'btn btn-tools-secondary',
                            'title' => \open20\amos\utility\Module::t('luyaconfigs', "Esporta versione pagina"),
                            'data-confirm' => Module::t('luyaconfigs', "Sei sicuro di esportare la versione <strong>{title}</strong>?", ['title' => $model->version_alias])
                        ]);
                    }
                    return '';
                },
            ]
        ]
    ]
])
?>

