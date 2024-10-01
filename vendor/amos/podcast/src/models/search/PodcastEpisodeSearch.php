<?php

namespace amos\podcast\models\search;

use open20\amos\core\interfaces\SearchModelInterface;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use amos\podcast\models\PodcastEpisode;

/**
 * PodcastEpisodeSearch represents the model behind the search form about `amos\podcast\models\PodcastEpisode`.
 */
class PodcastEpisodeSearch extends PodcastEpisode
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
            [['id', 'podcast_id', 'main_player_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['status', 'title', 'image_description', 'abstract', 'description', 'url_spreaker', 'url_soundhound', 'url_apple_podcast', 'url_spotify', 'url_google_podcast', 'published_at', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            ['Podcast', 'safe'],
        ];
    }

    public function scenarios()
    {
// bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     *
     */
    public function searchFieldsGlobalSearch()
    {
        return [
            'title',
            'description',
            'abstract',
        ];
    }

//    /**
//     * @param $params
//     * @return mixed|ActiveDataProvider
//     * @throws \yii\base\InvalidConfigException
//     */
//    public function search($params)
//    {
//        $query = PodcastEpisode::find();
//
//        $dataProvider = new ActiveDataProvider([
//            'query' => $query,
//        ]);
//
//        $query->joinWith('podcast');
//
//
//        $dataProvider = $this->setSortPodcast($dataProvider);
//        if (!($this->load($params) && $this->validate())) {
//            return $dataProvider;
//        }
//
//
//
//        $this->addFilter($query);
//        return $dataProvider;
//    }


    /**
     * @param $dataProvider
     * @return mixed
     */
    public function setSortPodcast($dataProvider){
        $dataProvider->setSort([
            'attributes' => [
                'podcast_id' => [
                    'asc' => ['podcast_episode.podcast_id' => SORT_ASC],
                    'desc' => ['podcast_episode.podcast_id' => SORT_DESC],
                ],
                'title' => [
                    'asc' => ['podcast_episode.title' => SORT_ASC],
                    'desc' => ['podcast_episode.title' => SORT_DESC],
                ],
                'image_description' => [
                    'asc' => ['podcast_episode.image_description' => SORT_ASC],
                    'desc' => ['podcast_episode.image_description' => SORT_DESC],
                ],
                'abstract' => [
                    'asc' => ['podcast_episode.abstract' => SORT_ASC],
                    'desc' => ['podcast_episode.abstract' => SORT_DESC],
                ],
                'description' => [
                    'asc' => ['podcast_episode.description' => SORT_ASC],
                    'desc' => ['podcast_episode.description' => SORT_DESC],
                ],
                'main_player_id' => [
                    'asc' => ['podcast_episode.main_player_id' => SORT_ASC],
                    'desc' => ['podcast_episode.main_player_id' => SORT_DESC],
                ],
                'url_spreaker' => [
                    'asc' => ['podcast_episode.url_spreaker' => SORT_ASC],
                    'desc' => ['podcast_episode.url_spreaker' => SORT_DESC],
                ],
            ]]);
        return $dataProvider;
    }

    /**
     * @param $query ActiveQuery
     */
    public function addFilter($query){
        $query->andFilterWhere([
            'id' => $this->id,
            'podcast_id' => $this->podcast_id,
            'main_player_id' => $this->main_player_id,
            'published_at' => $this->published_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'image_description', $this->image_description])
            ->andFilterWhere(['like', 'abstract', $this->abstract])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'url_spreaker', $this->url_spreaker])
            ->andFilterWhere(['like', 'url_soundhound', $this->url_soundhound])
            ->andFilterWhere(['like', 'url_apple_podcast', $this->url_apple_podcast])
            ->andFilterWhere(['like', 'url_spotify', $this->url_spotify])
            ->andFilterWhere(['like', 'url_google_podcast', $this->url_google_podcast]);

    }

    /**
     * Method that searches all the news validated.
     *
     * @param array $params
     * @param int $limit
     * @return ActiveDataProvider
     */
    public function searchOwnInterest($params, $limit = null)
    {
        return $this->search($params, "own-interest", $limit);
    }

    /**
     * Search method useful to retrieve all non-deleted news.
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function searchAll($params, $limit = null)
    {
        return $this->search($params, "all", $limit);
    }

    /**
     * @param $params
     * @param null $limit
     * @return ActiveDataProvider
     */
    public function searchAdminAll($params, $limit = null)
    {
        return $this->search($params, "admin-all", $limit);
    }
    /**
     * @inheritdoc
     */
    public function searchToValidateQuery($params)
    {
        return $this->search($params, 'to-validate');
    }

    /**
     * @inheritdoc
     */
    public function searchCreatedByMeQuery($params)
    {
        return $this->search($params, 'created-by');
    }
}
