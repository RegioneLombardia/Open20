<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\views\profilo
 * @category   CategoryName
 */

use open20\amos\community\models\Community;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\core\forms\editors\m2mWidget\M2MWidget;
use open20\amos\core\helpers\Html;
use open20\amos\events\AmosEvents;
use open20\amos\events\models\Event;
use open20\amos\events\models\search\EventSearch;
use open20\amos\events\widgets\JoinEventWidget;
use yii\db\ActiveQuery;
use yii\db\Expression;

/**
 * @var yii\web\View $this
 * @var \open20\amos\events\models\Event $model
 */

$this->title = AmosEvents::t('amosevents', '#add_to_event');
$this->params['breadcrumbs'][] = $this->title;

$userId = Yii::$app->request->get("id");
$communityTable = Community::tableName();
$communityUserMmTable = CommunityUserMm::tableName();
$eventTable = Event::tableName();

/** @var AmosEvents $eventsModule */
$eventsModule = AmosEvents::instance();
/** @var Event $eventModel */
$eventModel = $eventsModule->createModel('Event');
/** @var EventSearch $eventSearchModel */
$eventSearchModel = $eventsModule->createModel('EventSearch');

/** @var ActiveQuery $queryJoined */
$queryJoined = $eventModel::find();
$queryJoined->select([$eventTable . '.id']);
$queryJoined->innerJoin($communityTable, new Expression('`' . $communityTable . '`.`id` = `' . $eventTable . '`.`community_id` AND `' . $communityTable . '`.`deleted_at` IS NULL'));
$queryJoined->innerJoin($communityUserMmTable, '`' . $communityTable . '`.`id` = `' . $communityUserMmTable . '`.`community_id` AND `' . $communityUserMmTable . '`.`deleted_at` IS NULL');
$queryJoined->andWhere([$communityUserMmTable . '.user_id' => $userId]);
//pr($queryJoined->createCommand()->getRawSql());
$alreadyJoinedEventIds = $queryJoined->column();

/** @var ActiveQuery $query */
$query = $eventSearchModel->searchAllQuery([]);
$query->andWhere(new Expression('`' . $eventTable . '`.`community_id` IS NOT NULL'));
$query->andWhere(['not in', $eventTable . '.id', $alreadyJoinedEventIds]);
//pr($query->createCommand()->getRawSql());

$post = Yii::$app->request->post();
if (isset($post['genericSearch'])) {
    $query->andFilterWhere(['like', Event::tableName() . '.title', $post['genericSearch']]);
}
//pr($query->createCommand()->getRawSql());die();
$eventLogoLabel = $eventModel->getAttributeLabel('eventLogo');

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
        'class' => $eventsModule->model('Event'),
        'query' => $query,
    ],
    'targetFooterButtons' => Html::a(AmosEvents::t('amosevents', 'Close'), Yii::$app->urlManager->createUrl([
        '/events/event/annulla-m2m',
        'id' => $userId
    ]), ['class' => 'btn btn-secondary', 'AmosOrganizzazioni' => AmosEvents::t('amosevents', 'Close')]),
    'renderTargetCheckbox' => false,
    'viewSearch' => (isset($viewM2MWidgetGenericSearch) ? $viewM2MWidgetGenericSearch : false),
    'targetUrlController' => 'event',
    'targetActionColumnsTemplate' => '{joinOrganization}',
    'moduleClassName' => AmosEvents::className(),
    'postName' => 'Event',
    'postKey' => 'event',
    'targetColumnsToView' => [
        [
            'label' => $eventLogoLabel,
            'format' => 'html',
            'value' => function ($model) use ($eventLogoLabel) {
                /** @var Event $model */
                $url = $model->getEventsImageUrl('square_large');
                $contentImage = Html::img($url, ['class' => 'gridview-image', 'alt' => $eventLogoLabel]);
                return $contentImage;
            }
        ],
        'title',
        [
            'class' => 'open20\amos\core\views\grid\ActionColumn',
            'template' => '{info}{view}{joinEvent}',
            'buttons' => [
                'joinEvent' => function ($url, $model) use ($userId) {
                    return JoinEventWidget::widget(['model' => $model, 'userId' => $userId, 'isGridView' => true]);
                }
            ]
        ]
    ]
]);
?>
