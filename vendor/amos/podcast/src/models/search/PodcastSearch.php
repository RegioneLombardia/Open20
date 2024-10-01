<?php

namespace amos\podcast\models\search;

use amos\podcast\models\PodcastEpisode;
use open20\amos\core\interfaces\CmsModelInterface;
use open20\amos\core\record\CmsField;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use amos\podcast\models\Podcast;
use yii\db\ActiveQuery;

/**
 * PodcastSearch represents the model behind the search form about `amos\podcast\models\Podcast`.
 */
class PodcastSearch extends Podcast implements CmsModelInterface
{

//private $container; 

    public function __construct(array $config = [])
    {
        $this->isSearch = true;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['id', 'podcast_category_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['title', 'description', 'image_description', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            ['PodcastCategory', 'safe'],
        ];
    }

    public function scenarios()
    {
// bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * @param $params
     * @param null $queryType
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function search($params, $queryType = null)
    {
        $query = Podcast::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        switch ($queryType) {
            case 'published':
                $query->andWhere(['status' => self::PODCAST_WORKFLOW_STATUS_PUBLISHED]);
                break;
            case 'to-validate':
                $query->andWhere(['status' => self::PODCAST_WORKFLOW_STATUS_REQUESTPUBLICATION]);
                break;
            case 'to-validate-plus-episodes':
                $query->leftJoin('podcast_episode', 'podcast_episode.podcast_id = podcast.id');
                $query->andWhere(['OR',
                        ['podcast_episode.status' => PodcastEpisode::PODCASTEP_WORKFLOW_STATUS_REQUESTPUBLICATION],
                        ['podcast.status' => self::PODCAST_WORKFLOW_STATUS_REQUESTPUBLICATION]
                    ]
                );
                break;
            case 'own':
                $query->andWhere(['podcast.created_by' => \Yii::$app->user->id]);
                break;
            case 'admin':
                break;
        }

        $moduleCwh = \Yii::$app->getModule('cwh');
        isset($moduleCwh) ? $scope = $moduleCwh->getCwhScope() : null;
        if ($scope && $scope['community']) {
            $query->andWhere(['podcast.community_id' => $scope['community']]);
        }else {
            $query->andWhere(['podcast.community_id' => null]);

        }

        $query->joinWith('podcastCategory');
        $dataProvider = $this->setSortPodcast($dataProvider);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $this->addFilter($query);

        return $dataProvider;
    }

    /**
     * @param $dataProvider
     * @return mixed
     */
    public function setSortPodcast($dataProvider)
    {
        $dataProvider->setSort([
            'attributes' => [
                'title' => [
                    'asc' => ['podcast.title' => SORT_ASC],
                    'desc' => ['podcast.title' => SORT_DESC],
                ],
                'description' => [
                    'asc' => ['podcast.description' => SORT_ASC],
                    'desc' => ['podcast.description' => SORT_DESC],
                ],
                'image_description' => [
                    'asc' => ['podcast.image_description' => SORT_ASC],
                    'desc' => ['podcast.image_description' => SORT_DESC],
                ],
                'podcast_category_id' => [
                    'asc' => ['podcast.podcast_category_id' => SORT_ASC],
                    'desc' => ['podcast.podcast_category_id' => SORT_DESC],
                ],
                'podcastCategory' => [
                    'asc' => ['podcast_category.name' => SORT_ASC],
                    'desc' => ['podcast_category.name' => SORT_DESC],
                ],]]);
        return $dataProvider;
    }

    /**
     * @param $query ActiveQuery
     */
    public function addFilter($query)
    {
        $query->andFilterWhere([
            'id' => $this->id,
            'podcast_category_id' => $this->podcast_category_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'image_description', $this->image_description]);
        $query->andFilterWhere(['like', new \yii\db\Expression('podcast_category.name'), $this->PodcastCategory]);
    }


    /**
     * @param $params
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function searchPublished($params)
    {
        return $this->search($params, 'published');
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function searchToValidate($params)
    {
        return $this->search($params, 'to-validate');
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function searchToValidatePlusEpisodes($params)
    {
        return $this->search($params, 'to-validate-plus-episodes');
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function searchOwn($params)
    {
        return $this->search($params, 'own');
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function searchAdmin($params)
    {
        return $this->search($params, 'admin');
    }


    /**
     * Search method useful to retrieve news to show in frontend (with cms)
     *
     * @param $params
     * @param int|null $limit
     * @return ActiveDataProvider
     */
    public function cmsSearch($params, $limit = null)
    {
        $params = array_merge($params, Yii::$app->request->get());
        $this->load($params);
        $query = $this->searchPublished($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'published_at' => SORT_DESC,
                ],
            ],
        ]);

        if (!empty($params["withPagination"])) {
            $dataProvider->setPagination(['pageSize' => $limit]);
            $query->limit(null);
        } else {
            $query->limit($limit);
        }

        if (!empty($params["conditionSearch"])) {
            $commands = explode(";", $params["conditionSearch"]);
            foreach ($commands as $command) {
                $query->andWhere(eval("return " . $command . ";"));
            }
        }

        return $dataProvider;
    }

    /**
     * Search method useful to retrieve news to show in frontend (with cms)
     *
     * @param $params
     * @param int|null $limit
     * @return ActiveDataProvider
     */
    public function cmsSearchPodcastEpisodes($params, $limit = null)
    {
        $params = array_merge($params, Yii::$app->request->get());
        $this->load($params);
        $query = PodcastEpisode::find()
            ->andWhere(['status' => PodcastEpisode::PODCASTEP_WORKFLOW_STATUS_PUBLISHED]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'published_at' => SORT_ASC,
                ],
            ],
        ]);

        if (!empty($params["withPagination"])) {
            $dataProvider->setPagination(['pageSize' => $limit]);
            $query->limit(null);
        } else {
            $query->limit($limit);
        }

        if (!empty($params["conditionSearch"])) {
            $commands = explode(";", $params["conditionSearch"]);
            foreach ($commands as $command) {
                $query->andWhere(eval("return " . $command . ";"));
            }
        }

        return $dataProvider;
    }

    /**
     *
     * @return array
     */
    public function cmsViewFields()
    {
        $viewFields = [];

//    array_push($viewFields, new CmsField("titolo", "TEXT", 'amosnews', $this->attributeLabels()["titolo"]));
//    array_push($viewFields, new CmsField("descrizione_breve", "TEXT", 'amosnews', $this->attributeLabels()['descrizione_breve']));
//    array_push($viewFields, new CmsField("newsImage", "IMAGE", 'amosnews', $this->attributeLabels()['newsImage']));
//    array_push($viewFields, new CmsField("data_pubblicazione", "DATE", 'amosnews', $this->attributeLabels()['data_pubblicazione']));

        $viewFields[] = new CmsField("title", "TEXT", 'amospodcast', $this->attributeLabels()["title"]);
        $viewFields[] = new CmsField("description", "TEXT", 'amospodcast', $this->attributeLabels()["description"]);
        $viewFields[] = new CmsField("abstract", "TEXT", 'amospodcast', $this->attributeLabels()['abstract']);
        $viewFields[] = new CmsField("mainImage", "IMAGE", 'amospodcast', $this->attributeLabels()['mainImage']);
        $viewFields[] = new CmsField("published_at", "DATE", 'amospodcast', $this->attributeLabels()['published_at']);

        return $viewFields;
    }

    /**
     *
     * @return array
     */
    public function cmsSearchFields()
    {
        $searchFields = [];

//    array_push($searchFields, new CmsField("titolo", "TEXT"));
//    array_push($searchFields, new CmsField("sottotitolo", "TEXT"));
//    array_push($searchFields, new CmsField("descrizione_breve", "TEXT"));
//    array_push($searchFields, new CmsField("data_pubblicazione", "DATE"));

        $searchFields[] = new CmsField("title", "TEXT");
        $searchFields[] = new CmsField("description", "TEXT");
        $searchFields[] = new CmsField("abstract", "TEXT");
        $searchFields[] = new CmsField("published_at", "DATE");

        return $searchFields;
    }

    /**
     *
     * @param int $id
     * @return boolean
     */
    public function cmsIsVisible($id)
    {
        $retValue = true;

//        if (isset($id)) {
//            $md = $this->findOne($id);
//            if (!is_null($md)) {
//                $retValue = $md->primo_piano;
//            }
//        }

        return $retValue;
    }

}
