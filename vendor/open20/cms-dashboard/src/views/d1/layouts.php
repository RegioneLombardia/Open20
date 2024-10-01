<?php

use open20\amos\core\views\AmosGridView;
use open20\cms\dashboard\utilities\Utility;
use open20\cms\dashboard\Module;
use open20\amos\core\helpers\Html;
use app\modules\cms\models\NavItem;
use luya\admin\models\User;

$this->title = Module::txt('Modelli');

$this->params['breadcrumbs'][] = $this->title;
$this->params['forceBreadcrumbs'][] = ['label' => $this->title];
$this->registerJs('     
       $(".btn-group.btn.show-hide-element.btn-secondary").hide();
        ', \yii\web\View::POS_READY);

$canPublish = \Yii::$app->user->can('CMS_PUBLISH_PAGE');
?>

<?=
$this->render('_search', [
    'model' => $modelContainer,
    'originAction' => Yii::$app->controller->action->id
]);
?>

<div class="modelli-index m-b-30">
    <?php
    echo AmosGridView::widget([
        'dataProvider' => $dataProvider,
        'showPageSummary' => false,
        'columns' => [
            'immagine' => [
                'label' => Module::txt('Anteprima'),
                'format' => 'html',
                'value' => function ($model) {
                    $navItem = NavItem::findOne($model['id0']);
                    $url = '/img/img_default.jpg';
                    $image = $navItem->hasOneFile('seo_image')->one();
                    if (!is_null($image)) {
                        $url = $image->getWebUrl('table_small', false, true);
                    }
                    $contentImage = Html::img($url, ['class' => 'gridview-image', 'alt' => Module::txt('Anteprima del modello')]);

                    return $contentImage;
                },
                'width' => '5%',
            ],
            [
                'width' => '45%',
                'attribute' => 'title',
                'label' => Module::txt('Modelli di pagina'),
                'value' => function ($model) {
                    return '<strong>' . $model['title'] . '</strong>';
                },
                'format' => 'html',
            ],
            [
                'width' => '15%',
                'attribute' => 'update_user_id',
                'label' => Module::txt('Ultima modifica'),
                'value' => function ($model) {
                    if (empty($model['update_user_id'])) {
                        return User::findOne($model['create_user_id'])->firstname
                                . ' '
                                . User::findOne($model['create_user_id'])->lastname;
                    }
                    return User::findOne($model['update_user_id'])->firstname
                    . ' '
                    . User::findOne($model['update_user_id'])->lastname;
                }
            ],
            [
                'width' => '15%',
                'attribute' => 'timestamp_update',
                'label' => Module::txt('Data ultima modifica'),
                'value' => function ($model) {
                    if (empty($model['timestamp_update']))
                        return \Yii::$app->formatter->asDatetime($model['timestamp_create'], 'php:d/m/Y H:i');
                    return \Yii::$app->formatter->asDatetime($model['timestamp_update'], 'php:d/m/Y H:i');
                }
            ],
            [
                'class' => 'open20\amos\core\views\grid\ActionColumn',
                'template' => '{preview}{modello}{update}{delete}',
                'buttons' => [
                    'preview' => function ($url, $model) {
                        $url = \yii\helpers\Url::current();
                        $link = Utility::getLinkTarget($model);
                        $onlineVersion = Utility::getOnlineVersion($model['id0']);
                        $target = ['/cms-page-preview', 'itemId' => $model['id0'], 'version' => (!empty($onlineVersion['id']) ? $onlineVersion['id'] : 1)];
                        if (!empty($link)) {
                            $target = $link;
                        }
                        return Html::a(
                                '<span class="mdi mdi-eye"></span>',
                                $target,
                                [
                                    'title' => Module::txt('Visualizza l\'anteprima'),
                                    'class' => 'btn btn-tool-secondary',
                                    'target' => '_blank',
                                    'style' => ''
                                ]
                        );
                    },
                    'update' => function ($url, $model) {
                        return Html::a(
                                '<span class="mdi mdi-pencil"></span>',
                                ['/admin#!/template/cmsadmin~2Fdefault~2Findex/update/' . $model['nav_id']],
                                [
                                    'title' => Module::txt('Modifica contenuti'),
                                    'class' => 'btn btn-tool-secondary',
                                    'style' => ''
                                ]
                        );
                    },
                    'modello' => function ($url, $model) {
                        $url = \yii\helpers\Url::current();
                        return Html::a(
                                '<span class="mdi mdi-cog"></span>',
                                ['/' . Module::getModuleName() . '/d1/aggiorna-modello', 'nav_id' => $model['nav_id'], 'url' => $url],
                                [
                                    'title' => Module::txt('Modifica proprietà'),
                                    'class' => 'btn btn-tool-secondary',
                                ]
                        );
                    },
                    'delete' => function ($url, $model) {
                        $url = \yii\helpers\Url::current();
                        return Html::a('<span class="mdi mdi-delete"></span>', Yii::$app->urlManager->createUrl([
                                    '/' . Module::getModuleName() . '/d1/delete-page',
                                    'id' => $model['nav_id'],
                                    'item_id' => $model['id0'],
                                    'container' => '',
                                    'url' => $url]),
                                [
                                    'title' => Module::txt('Elimina'),
                                    'class' => 'btn btn-danger-inverse',
                                    'style' => 'color:#a61919; border-top: 1px solid #ccc; ',
                                    'data-confirm' => Module::txt('Sei sicuro di voler eliminare il modello di pagina <strong>{nomeModello}</strong>? L\'operazione non è reversibile.', [
                                        'nomeModello' => $model['title']
                                    ]),
                                ]
                        );
                    },
                ],
            ],
        ]
    ]);
    ?>
</div>