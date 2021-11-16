<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\views\community
 * @category   CategoryName
 */

use open20\amos\community\AmosCommunity;
use open20\amos\community\models\Community;
use open20\amos\community\widgets\CommunityCardWidget;
use open20\amos\community\widgets\JoinCommunityWidget;
use open20\amos\core\forms\editors\m2mWidget\M2MWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\user\User;
use yii\bootstrap\Modal;

/**
 * @var \open20\amos\community\models\Community $model
 */

$this->title = AmosCommunity::t('amoscommunity', 'Add communities');
$this->params['breadcrumbs'][] = AmosCommunity::t('amoscommunity', 'Add communities');

$userId = Yii::$app->request->get("id");

$community = new Community();
$query = $community->getUserNetworkAssociationQuery($userId);

$post = Yii::$app->request->post();
if (isset($post['genericSearch'])) {
    $query->andFilterWhere(['like', 'community.name', $post['genericSearch']]);
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
        'class' => Community::className(),
        'query' => $query,
    ],
    'targetFooterButtons' => Html::a(AmosCommunity::t('amoscommunity', 'Close'), Yii::$app->urlManager->createUrl([
        '/community/community/annulla-m2m',
        'id' => $userId
    ]), ['class' => 'btn btn-secondary', 'AmosCommunity' => Yii::t('amoscommunity', 'Close')]),
    'renderTargetCheckbox' => false,
    'viewSearch' => (isset($viewM2MWidgetGenericSearch) ? $viewM2MWidgetGenericSearch : false),
//    'relationAttributesArray' => ['status', 'role'],
    'targetUrlController' => 'community',
    'targetActionColumnsTemplate' => '{joinCommunity}',
    'moduleClassName' => AmosCommunity::className(),
    'postName' => 'Community',
    'postKey' => 'community',
    'targetColumnsToView' => [
        'logo_id' => [
            'headerOptions' => [
                'id' => AmosCommunity::t('amoscommunity', 'Logo'),
            ],
            'contentOptions' => [
                'headers' => AmosCommunity::t('amoscommunity', 'Logo'),
            ],
            'label' => AmosCommunity::t('amoscommunity', 'Logo'),
            'format' => 'html',
            'value' => function ($model) {
                return CommunityCardWidget::widget(['model' => $model]);
            }
        ],
        [
            'attribute' => 'name',
            'format' => 'html',
            'value' => function ($model) {
                /** @var Community $model */
                return Html::a($model->name, ['/community/community/view', 'id' => $model->id], [
                    'title' => AmosCommunity::t('amoscommunity', 'Apri il profilo della community {community_name}', ['community_name' => $model->name])
                ]);
            }
        ],
        'communityType' => [
            'attribute' => 'communityType',
            'format' => 'html',
            'value' => function ($model) {
                /** @var Community $model */
                if (!is_null($model->community_type_id)) {
                    return AmosCommunity::t('amoscommunity', $model->communityType->name);
                } else {
                    return '-';
                }
            }
        ],
        'created_by' => [
            'attribute' => 'created_by',
            'format' => 'html',
            'value' => function ($model) {
                /** @var Community $model */
                $name = '-';
                if (!is_null($model->created_by)) {
                    $creator = User::findOne($model->created_by);
                    if (!empty($creator)) {
                        return $creator->getProfile()->getNomeCognome();
                    }
                }
                return $name;
            }
        ],
        [
            'class' => 'open20\amos\core\views\grid\ActionColumn',
            'template' => '{info}{view}{joinCommunity}',
            'buttons' => [
                'joinCommunity' => function ($url, $model) {
                    $btn = JoinCommunityWidget::widget(['model' => $model, 'isGridView' => true]);
                    return $btn;
                },
                'info' => function ($url, $model) {
                    Modal::begin([
                        'id' => 'community-info-' . $model->id,
                        'header' => AmosCommunity::t('amoscommunity', 'Additional information')
                    ]);
                    echo $this->render('@vendor/open20/amos-community/src/views/community/boxes/registry',
                        ['model' => $model]);
                    echo Html::tag('div',
                        Html::a(AmosCommunity::t('amoscommunity', 'Close'), null,
                            [
                                'class' => 'btn btn-secondary',
                                'data-dismiss' => 'modal'
                            ]),
                        ['class' => 'pull-right m-15-0']
                    );
                    Modal::end();

                    $btn = Html::a(AmosIcons::show('info', ['class' => '']) .
                        Html::tag('span', AmosCommunity::t('amoscommunity', 'Additional information'), ['class' => 'sr-only']),
                        null, [
                            'data-toggle' => 'modal',
                            'data-target' => '#community-info-' . $model->id,
                            'title' => AmosCommunity::t('amoscommunity', 'Additional information'),
                            'class' => 'btn btn-tools-secondary'
                        ]
                    );

                    return $btn;
                }
            ]
        ]
    ],
]);
?>
