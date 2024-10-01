<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\models\search
 * @category   CategoryName
 */

namespace open20\amos\notificationmanager\models\search;

use open20\amos\core\interfaces\NewsletterInterface;
use open20\amos\core\record\Record;
use open20\amos\core\user\User;
use open20\amos\notificationmanager\AmosNotify;
use open20\amos\notificationmanager\exceptions\NewsletterException;
use open20\amos\notificationmanager\models\Newsletter;
use open20\amos\notificationmanager\models\NotificationSchedule;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Expression;

/**
 * Class NewsletterSearch
 * NewsletterSearch represents the model behind the search form about `backend\amos\notify\models\Newsletter`.
 * @package open20\amos\notificationmanager\models\search
 */
class NotificationScheduleSearch extends NotificationSchedule
{
    public $status_schedule;
    public $content_id;
    public $send_date;
    public $plugin;

    public $user_id;
    public $notification_id;
    public $nome;
    public $cognome;
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nome', 'cognome','email','user_id', 'notification_id', 'status_schedule', 'content_id', 'send_date', 'plugin'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * This is the base search.
     * @param array $params
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function baseSearch($params)
    {
        $query = NotificationSchedule::find()
            ->leftJoin('notification_schedule_content', 'notification_schedule.id = notification_schedule_id')
            ->leftJoin('notification', 'notification.id = notification_schedule_content.notification_id')
            ->orderBy('notification_schedule.id DESC');


        return $query;
    }


    /**
     * Base filter.
     * @param ActiveQuery $query
     * @return mixed
     */
    public function baseFilter($query)
    {

        $query->andFilterWhere(['notification.content_id' => $this->content_id]);
        $query->andFilterWhere(['notification_schedule_content.classname' => $this->plugin]);
        $query->andFilterWhere(['notification_schedule.status' => $this->status_schedule]);
        if (!empty($this->send_date)) {
            $query->andWhere(["DATE_FORMAT(notification_schedule.created_at, '%Y-%m-%d')" => $this->send_date]);
        }
//        pr($query->createCommand()->rawSql);
        return $query;
    }

    public function search($params)
    {
        $query = $this->baseSearch($params);
        $dataProvider = new ActiveDataProvider(['query' => $query]);
//        $this->setSearchSort($dataProvider);
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $this->baseFilter($query);
//        pr($query->createCommand()->rawSql);die;
        return $dataProvider;
    }


    public function searchUsers($params)
    {
        $query = User::find()
            ->innerjoin('user_profile', 'user.id=user_profile.user_id')
            ->innerJoin('notificationread', 'user.id = notificationread.user_id')
            ->innerJoin('notification_schedule_content', 'notification_schedule_content.notification_id =notificationread.notification_id ')
            ->andWhere(['notification_schedule_content.notification_id' => $this->notification_id])
            ->andWhere(['notificationread.notification_id' => $this->notification_id])
            ->andWhere(['notificationread.notification_type' => 2]);

        $this->load($params);
//        $query->andFilterWhere(['user.id' => $this->user_id]);
        $query->andFilterWhere(['like','user_profile.nome' , $this->nome]);
        $query->andFilterWhere(['like','user_profile.cognome', $this->cognome]);
        $query->andFilterWhere(['like','user_profile.email', $this->email]);
//        $query->andFilterWhere(['user_profile.nome' => $this->user_id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);
//        pr($query->createCommand()->rawSql); die;
        return $dataProvider;
    }

}
