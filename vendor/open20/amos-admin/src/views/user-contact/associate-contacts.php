<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\views\user-contact
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use open20\amos\core\forms\editors\m2mWidget\M2MWidget;
use open20\amos\core\helpers\Html;

/**
 * @var \open20\amos\admin\models\UserProfile $model
 */

$this->title = AmosAdmin::t('amosadmin', 'Add contacts');
$this->params['breadcrumbs'][] = AmosAdmin::t('amosadmin', 'Add contacts');

$userProfileId = Yii::$app->request->get("id");
$model = UserProfile::findOne($userProfileId);

/**
 * @var \yii\db\ActiveQuery $query UserProfiles to invite or with pending invitation
 */
$query = $model->getUserNetworkAssociationQuery();

$query->orderBy([
        'user_profile.cognome' => SORT_ASC,
        'user_profile.nome' => SORT_ASC,
    ]);

$post = Yii::$app->request->post();
if (isset($post['genericSearch'])) {
    $searchName = $post['genericSearch'];
    $query->andFilterWhere(['or',
        ['like', 'user_profile.nome',$searchName],
        ['like', 'user_profile.cognome', $searchName],
        ['like', "CONCAT( user_profile.nome , ' ', user_profile.cognome )", $searchName],
        ['like', "CONCAT( user_profile.cognome , ' ', user_profile.nome )", $searchName]
    ]);
}

?>
<?= M2MWidget::widget([
    'model' => $model,
    'modelId' => $model->id,
    'modelData' => $query,
    'modelDataArrFromTo' => [
        'from' => 'id',
        'to' => 'id'
    ],
    'modelTargetSearch' => [
        'class' => AmosAdmin::instance()->createModel('UserProfile')->className(),
        'query' => $query,
    ],
    'targetFooterButtons' => Html::a(AmosAdmin::t('amosadmin', 'Close'), Yii::$app->urlManager->createUrl([
        '/'.AmosAdmin::getModuleName().'/user-contact/annulla-m2m',
        'id' => $userProfileId
    ]), ['class' => 'btn btn-secondary', 'AmosAdmin' => Yii::t('amosadmin', 'Close')]),
    'renderTargetCheckbox' => false,
    'viewSearch' => (isset($viewM2MWidgetGenericSearch) ? $viewM2MWidgetGenericSearch : false),
    'targetUrlController' => 'user-contact',
    'targetActionColumnsTemplate' => '{googleContact}{connect}',
    'moduleClassName' => \open20\amos\admin\AmosAdmin::className(),
    'postName' => 'UserContact',
    'postKey' => 'user-contact',
    'targetColumnsToView' => [
        'photo' => [
            'headerOptions' => [
                'id' => AmosAdmin::t('amosadmin', 'Photo'),
            ],
            'contentOptions' => [
                'headers' => AmosAdmin::t('amosadmin', 'Photo'),
            ],
            'label' => AmosAdmin::t('amosadmin', 'Photo'),
            'format' => 'raw',
            'value' => function ($model) {
                /** @var UserProfile $model */
                return \open20\amos\admin\widgets\UserCardWidget::widget(['model' => $model, 'onlyAvatar'=> true]);
            }
        ],
        'name' => [
            'attribute' => 'surnameName',
            'headerOptions' => [
                'id' => AmosAdmin::t('amosadmin', 'Name'),
            ],
            'contentOptions' => [
                'headers' => AmosAdmin::t('amosadmin', 'Name'),
            ],
            'label' => AmosAdmin::t('amosadmin', 'Name'),
            'value' => function($model){
                /** @var UserProfile $model */
                return Html::a($model->surnameName, ['/'.AmosAdmin::getModuleName().'/user-profile/view', 'id' => $model->id ], [
                    'title' => AmosAdmin::t('amosnews', 'Apri il profilo di {nome_profilo}', ['nome_profilo' => $model->surnameName])
                ]);
            },
            'format' => 'html'
        ],
        [
            'class' => 'open20\amos\core\views\grid\ActionColumn',
            'template' => '{googleContact}{connect}',
            'buttons' => [
                'googleContact' => function($url, $model){
                    /** @var UserProfile $model */
                    return \open20\amos\admin\widgets\GoogleContactWidget::widget(['model' => $model]).'&nbsp;';
                },
                'connect' =>  function ($url, $model) {
                    /** @var UserProfile $model */
                    return \open20\amos\admin\widgets\ConnectToUserWidget::widget([ 'model' => $model, 'isGridView' => true ]);
                }
            ]
        ]
    ],
]);
?>
