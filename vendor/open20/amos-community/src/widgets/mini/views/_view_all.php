<?php
/** @var $dataProvider \yii\data\ActiveDataProvider*/

use open20\amos\community\AmosCommunity;
use open20\amos\admin\widgets\UserCardWidget;
use open20\amos\core\helpers\Html;



\yii\bootstrap\Modal::begin([
    'id' => 'view-all-members',
    'header' => "<h3>" .AmosCommunity::t('amoscommunity', 'Participants') . "</h3>",
    'size' => 'modal-lg',
]);
    \yii\widgets\Pjax::begin([
        'id' => 'pjax-container-view-all',
        'timeout' => 2000,
        'enablePushState' => false,
        'enableReplaceState' => false,
        'clientOptions' => ['data-pjax-container' => 'grid-view-all-members', 'method' => 'POST' ]
    ]);
    echo \open20\amos\core\views\AmosGridView::widget([
        'dataProvider' => $dataProvider,
        'id' => 'grid-view-all-members',
        'columns' => [
            'Photo' => [
                'headerOptions' => [
                    'id' => AmosCommunity::t('amoscommunity', 'Photo'),
                ],
                'contentOptions' => [
                    'headers' => AmosCommunity::t('amoscommunity', 'Photo'),
                ],
                'label' => AmosCommunity::t('amoscommunity', 'Photo'),
                'format' => 'raw',
                'value' => function ($model) {
                    /** @var \open20\amos\admin\models\UserProfile $userProfile */
                    $userProfile = $model->user->getProfile();
                    return UserCardWidget::widget(['model' => $userProfile]);
                }
            ],
            'name' => [
                'attribute' => 'user.userProfile.surnameName',
                'label' => AmosCommunity::t('amoscommunity', 'Name'),
                'headerOptions' => [
                    'id' => AmosCommunity::t('amoscommunity', 'name'),
                ],
                'contentOptions' => [
                    'headers' => AmosCommunity::t('amoscommunity', 'name'),
                ],
                'value' => function($model){
                    return Html::a($model->user->userProfile->surnameName, ['/admin/user-profile/view', 'id' => $model->user->userProfile->id ], [
                        'title' => AmosCommunity::t('amoscommunity', 'Apri il profilo di {nome_profilo}', ['nome_profilo' => $model->user->userProfile->surnameName])
                    ]);
                },
                'format' => 'html'
            ],
            'status' => [
                'attribute' => 'status',
                'label' => AmosCommunity::t('amoscommunity', 'Status'),
                'headerOptions' => [
                    'id' => AmosCommunity::t('amoscommunity', 'Status'),
                ],
                'contentOptions' => [
                    'headers' => AmosCommunity::t('amoscommunity', 'Status'),
                ],
                'value' => function($model){
                    return $model->status;
                }
            ],
            'role' => [
                'attribute' => 'role',
                'label' => AmosCommunity::t('amoscommunity', 'Role'),
                'headerOptions' => [
                    'id' => AmosCommunity::t('amoscommunity', 'Role'),
                ],
                'contentOptions' => [
                    'headers' => AmosCommunity::t('amoscommunity', 'Role'),
                ],
                'value' => function($model){
                    return $model->role;
                }
            ],
        ]
    ]);
    \yii\widgets\Pjax::end();


\yii\bootstrap\Modal::end();
