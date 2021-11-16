<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\models\search
 * @category   CategoryName
 */

namespace open20\amos\notificationmanager\models\search;

use open20\amos\core\interfaces\NewsletterInterface;
use open20\amos\core\record\Record;
use open20\amos\notificationmanager\AmosNotify;
use open20\amos\notificationmanager\exceptions\NewsletterException;
use open20\amos\notificationmanager\models\Newsletter;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

/**
 * Class NewsletterSearch
 * NewsletterSearch represents the model behind the search form about `backend\amos\notify\models\Newsletter`.
 * @package open20\amos\notificationmanager\models\search
 */
class NewsletterSearch extends Newsletter
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'subject', 'send_date_begin', 'send_date_end', 'created_at'], 'safe'],
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
        /** @var Newsletter $newsletterModel */
        $newsletterModel = $this->notifyModule->createModel('Newsletter');

        /** @var ActiveQuery $query */
        $query = $newsletterModel::find();

        $this->initOrderVars(); // Init the default search values
        $this->setOrderVars($params); // Check params to get orders value

        return $query;
    }

    /**
     * Search sort.
     * @param ActiveDataProvider $dataProvider
     */
    protected function setSearchSort($dataProvider)
    {
        // Check if can use the custom module order
        if ($this->canUseModuleOrder()) {
            $dataProvider->setSort([
                'attributes' => [
                    'subject' => [
                        'asc' => [self::tableName() . '.subject' => SORT_ASC],
                        'desc' => [self::tableName() . '.subject' => SORT_DESC],
                    ],
                ]
            ]);
        }
    }

    /**
     * Base filter.
     * @param ActiveQuery $query
     * @return mixed
     */
    public function baseFilter($query)
    {
        $query->andFilterWhere([
            'id' => $this->id,
            'send_date_begin' => $this->send_date_begin,
            'send_date_end' => $this->send_date_end,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'subject', $this->subject]);

        return $query;
    }

    public function search($params)
    {
        $query = $this->baseSearch($params);
        $dataProvider = new ActiveDataProvider(['query' => $query]);
        $this->setSearchSort($dataProvider);
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $this->baseFilter($query);
        return $dataProvider;
    }

    public function searchCreatedBy($params)
    {
        $query = $this->baseSearch($params);
        $query->andWhere(['created_by' => \Yii::$app->user->id]);
        $dataProvider = new ActiveDataProvider(['query' => $query]);
        $this->setSearchSort($dataProvider);
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $this->baseFilter($query);
        return $dataProvider;
    }
    
    /**
     * @param Record|NewsletterInterface $contentModel
     * @return ActiveQuery
     * @throws NewsletterException
     * @throws \yii\base\InvalidConfigException
     */
    public function getAssociaM2mQuery($contentModel, $post)
    {
        if (!($contentModel instanceof NewsletterInterface)) {
            throw new NewsletterException(AmosNotify::t('amosnotify', '#associa_m2m_query_no_newsletter_interface'));
        }
        
        /** @var ActiveQuery $query */
        $query = $contentModel::find();
        $query->andWhere(['status' => $contentModel->newsletterPublishedStatus()]);
        $query->orderBy([$contentModel->newsletterOrderByField() => SORT_DESC]);
        
        if (isset($post['genericSearch'])) {
            $contentModel->newsletterSearchFilter($post['genericSearch'], $query);
        }
        
        return $query;
    }
}
