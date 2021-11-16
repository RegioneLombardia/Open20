<?php

namespace backend\modules\landings\models\search;

use backend\modules\landings\models\PreferenceLandingLink;
use open20\amos\core\interfaces\CmsModelInterface;
use open20\amos\core\record\CmsField;
use open20\amos\news\models\News;
use open20\amos\documenti\models\Documenti;
use open20\amos\news\models\search\NewsSearch;
use Yii;
use yii\base\BaseObject;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\landings\models\PreferenceLanding;
use yii\helpers\ArrayHelper;

use open20\amos\admin\models\UserProfile;
use yii\helpers\VarDumper;

/**
 * PreferenceLandingSearch represents the model behind the search form about `backend\modules\landings\models\PreferenceLanding`.
 */
class PreferenceLandingSearch extends PreferenceLanding implements CmsModelInterface
{

    public $isSearch = true;
    public $statusSearch;
    public $PreferenceStructure;
    public $creatore;
    public $sort;
    //private $container;

    public function __construct(array $config = [])
    {
        $this->isSearch = true;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['id', 'preference_structure_id', 'image_slider_id', 'video_slider_id', 'luya_page_id', 'luya_template_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['status', 'title', 'description', 'schedule', 'url', 'created_at', 'updated_at', 'deleted_at','creatore'], 'safe'],
            [['PreferenceStructure', 'statusSearch'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {

        $query = PreferenceLanding::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith('preferenceStructure');

        // aggiungo il filtro per pc_structure_id user_profile authenticato  escludentro il filtro per gli utenti con ruolo PC_ADMIN e PC_EDITOR
        if ( ! \Yii::$app->user->can('PC_ADMIN') || ! \Yii::$app->user->can('PC_EDITOR') ) {
                        
            $current_user_profile = UserProfile::find()->where(['user_id' => \Yii::$app->user->id ])->one();

            if( null != $current_user_profile){
                $query->andFilterWhere(['preference_landing.preference_structure_id' => $current_user_profile->pc_structure_id]);
            }
        }

        $dataProvider->setSort([
            'defaultOrder' => [
                'preference_landing.created_at' => SORT_DESC
            ],
            'attributes' => [
                'title' => [
                    'asc' => ['preference_landing.title' => SORT_ASC],
                    'desc' => ['preference_landing.title' => SORT_DESC],
                ],
                'preference_landing.created_at' => [
                    'asc' => ['preference_landing.created_at' => SORT_ASC],
                    'desc' => ['preference_landing.created_at' => SORT_DESC],
                ],
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
//            VarDumper::dump($dataProvider->query->createCommand()->rawSql);
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'preference_structure_id' => $this->preference_structure_id,
            'image_slider_id' => $this->image_slider_id,
            'video_slider_id' => $this->video_slider_id,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->joinWith('createdUserProfile');

        $query->andFilterWhere(['like', 'CONCAT(user_profile.nome," ",user_profile.cognome)', $this->creatore]);

        $query->andFilterWhere( ['like', 'preference_landing.created_at', $this->created_at]);
        $query->andFilterWhere(['preference_landing.status' => $this->statusSearch])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'schedule', $this->schedule])
            ->andFilterWhere(['like', 'url', $this->url]);
        $query->andFilterWhere(['like', new \yii\db\Expression('preference_structure.name'), $this->PreferenceStructure]);




        return $dataProvider;
    }

    /**
     *
     * @param array $params
     * @param int $limit
     * @return ActiveDataProvider
     */
    public function cmsSearchNews($params, $limit = null)
    {
        $queryParams = [];
        $dataProvider = null;
        if (!empty($params["conditionSearch"])) {
            $commands = explode(";", $params["conditionSearch"]);
            foreach ($commands as $command) {
                $queryParams = ArrayHelper::merge($queryParams,
                    eval("return " . $command . ";"));
            }
        }
        $model = $this->findOne(['id' => $queryParams['preference_landing_id']]);
        if (!is_null($model)) {
            unset($queryParams["preference_landing_id"]);
            $dataProvider= new ActiveDataProvider([
                'query' => $model->getNews()
                    ->andWhere(['status' => News::NEWS_WORKFLOW_STATUS_VALIDATO])
                    ->andWhere(['news_groups_id' => 1])
            ]);
            $query = $dataProvider->query;
            if (count($queryParams) > 0) {
                foreach ($queryParams as $key => $param) {
                    $query->andWhere([$key => $param]);
                }
            }
        }

        return $dataProvider;
    }

    /**
     *
     * @param array $params
     * @param int $limit
     * @return ActiveDataProvider
     */
    public function cmsSearchSondaggio($params, $limit = null)
    {
        $queryParams = [];
        $dataProvider = null;
        if (!empty($params["conditionSearch"])) {
            $commands = explode(";", $params["conditionSearch"]);
            foreach ($commands as $command) {
                $queryParams = ArrayHelper::merge($queryParams,
                    eval("return " . $command . ";"));
            }
        }
        $model = $this->findOne(['id' => $queryParams['preference_landing_id']]);
        if (!is_null($model)) {
            unset($queryParams["preference_landing_id"]);
            $dataProvider= new ActiveDataProvider([
                'query' => $model->getSondaggio()
            ]);
            $query = $dataProvider->query;
            if (count($queryParams) > 0) {
                foreach ($queryParams as $key => $param) {
                    $query->andWhere([$key => $param]);
                }
            }
        }

        return $dataProvider;
    }

    /**
     *
     * @param array $params
     * @param int $limit
     * @return ActiveDataProvider
     */
    public function cmsSearchForRating($params, $limit = null)
    {
        $queryParams = [];
        $dataProvider = null;
        if (!empty($params["conditionSearch"])) {
            $commands = explode(";", $params["conditionSearch"]);
            foreach ($commands as $command) {
                $queryParams = ArrayHelper::merge($queryParams,
                    eval("return " . $command . ";"));
            }
        }
        $model = $this->findOne(['id' => $queryParams['preference_landing_id']]);
        if (!is_null($model)) {
            $dataProvider= new ActiveDataProvider([
                'query' => $this->find()->andWhere(['id' => $queryParams['preference_landing_id']])
            ]);
            unset($queryParams["preference_landing_id"]);
            $query = $dataProvider->query;
            if (count($queryParams) > 0) {
                foreach ($queryParams as $key => $param) {
                    $query->andWhere([$key => $param]);
                }
            }
        }

        return $dataProvider;
    }

    /**
     *
     * @param array $params
     * @param int $limit
     * @return ActiveDataProvider
     */
    public function cmsSearchForPlaylistYoutube($params, $limit = null)
    {
        $queryParams = [];
        $dataProvider = null;
        if (!empty($params["conditionSearch"])) {
            $commands = explode(";", $params["conditionSearch"]);
            foreach ($commands as $command) {
                $queryParams = ArrayHelper::merge($queryParams,
                    eval("return " . $command . ";"));
            }
        }
        $model = $this->findOne(['id' => $queryParams['preference_landing_id']]);
        if (!is_null($model)) {
            $dataProvider= new ActiveDataProvider([
                'query' => $this->find()->andWhere(['id' => $queryParams['preference_landing_id']])
            ]);
            unset($queryParams["preference_landing_id"]);
            $query = $dataProvider->query;
            if (count($queryParams) > 0) {
                foreach ($queryParams as $key => $param) {
                    $query->andWhere([$key => $param]);
                }
            }
        }

        return $dataProvider;
    }

    /**
     *
     * @param array $params
     * @param int $limit
     * @return ActiveDataProvider
     */
    public function cmsSearchDocumenti($params, $limit = null)
    {
        $queryParams = [];
        $dataProvider = null;
        if (!empty($params["conditionSearch"])) {
            $commands = explode(";", $params["conditionSearch"]);
            foreach ($commands as $command) {
                $queryParams = ArrayHelper::merge($queryParams,
                    eval("return " . $command . ";"));
            }
        }
        $model = $this->findOne(['id' => $queryParams['preference_landing_id']]);
        if (!is_null($model)) {
            unset($queryParams["preference_landing_id"]);
            $dataProvider= new ActiveDataProvider([
                'query' => $model->getDocumenti()
                    ->andWhere(['status' => Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO])
                    ->innerJoin('preference_landing_documenti', 'documenti.id = preference_landing_documenti.documenti_id')
                    ->orderBy('preference_landing_documenti.sort_value')
            ]);
            $query = $dataProvider->query;
            if (count($queryParams) > 0) {
                foreach ($queryParams as $key => $param) {
                    $query->andWhere([$key => $param]);
                }
            }
        }

        return $dataProvider;
    }
    /**
     *
     * @param array $params
     * @param int $limit
     * @return ActiveDataProvider
     */
    public function cmsSearchNewsTimeline($params, $limit = null)
    {
        $queryParams = [];
        $dataProvider = null;
        if (!empty($params["conditionSearch"])) {
            $commands = explode(";", $params["conditionSearch"]);
            foreach ($commands as $command) {
                $queryParams = ArrayHelper::merge($queryParams,
                    eval("return " . $command . ";"));
            }
        }
        $model = $this->findOne(['id' => $queryParams['preference_landing_id']]);
        if (!is_null($model)) {
            unset($queryParams["preference_landing_id"]);
            $dataProvider= new ActiveDataProvider([
                'query' => $model->getNews()
                    ->andWhere(['status' => News::NEWS_WORKFLOW_STATUS_VALIDATO])
                    ->andWhere(['news_groups_id' => 2])
            ]);
            $query = $dataProvider->query;
            if (count($queryParams) > 0) {
                foreach ($queryParams as $key => $param) {
                    $query->andWhere([$key => $param]);
                }
            }
        }

        return $dataProvider;
    }
    /**
     *
     * @param array $params
     * @param int $limit
     * @return ActiveDataProvider
     */
    public function cmsSearchProtagonists($params, $limit = null)
    {
        //        var_dump($params);die;
        $queryParams = [];
        $dataProvider = null;
        if (!empty($params["conditionSearch"])) {
            $commands = explode(";", $params["conditionSearch"]);
            foreach ($commands as $command) {
                $queryParams = ArrayHelper::merge($queryParams,
                    eval("return " . $command . ";"));
            }
        }

        $model = $this->find()->andWhere(['id' => $queryParams['preference_landing_id']])->one();
        if (!is_null($model)) {
            unset($queryParams["preference_landing_id"]);
            $protagonists = new PreferenceLandingProtagonistSearch();
            $dataProvider = $protagonists->search($params);
            $query = $dataProvider->query;
            $query->andWhere(['preference_landing_id' => $model->id]);
            if (count($queryParams) > 0) {
                foreach ($queryParams as $key => $param) {
                    $query->andWhere([$key => $param]);
                }
            }

        }
        return $dataProvider;
    }

    /**
     *
     * @param array $params
     * @param int $limit
     * @return ActiveDataProvider
     */
    public function cmsSearchExternalLink($params, $limit = null)
    {
        //        var_dump($params);die;
        $queryParams = [];
        $dataProvider = null;
        if (!empty($params["conditionSearch"])) {
            $commands = explode(";", $params["conditionSearch"]);
            foreach ($commands as $command) {
                $queryParams = ArrayHelper::merge($queryParams,
                    eval("return " . $command . ";"));
            }
        }

        $model = $this->find()->andWhere(['id' => $queryParams['preference_landing_id']])->one();
        if (!is_null($model)) {
            unset($queryParams["preference_landing_id"]);
            $modelSearch = new PreferenceLandingLinkSearch();
            $dataProvider = $modelSearch->search($params);
            $query = $dataProvider->query;
            $query->andWhere(['preference_landing_id' => $model->id]);
            if (count($queryParams) > 0) {
                foreach ($queryParams as $key => $param) {
                    $query->andWhere([$key => $param]);
                }
            }

        }
        return $dataProvider;
    }

    /**
     *
     * @param array $params
     * @param int $limit
     * @return ActiveDataProvider
     */
    public function cmsSearchGallery($params, $limit = null)
    {
        $queryParams = [];
        $dataProviderSliderElemImage = null;
        if (!empty($params["conditionSearch"])) {
            $commands = explode(";", $params["conditionSearch"]);
            foreach ($commands as $command) {
                $queryParams = ArrayHelper::merge($queryParams,
                    eval("return " . $command . ";"));
            }
        }
        $model = $this->findOne(['id' => $queryParams['preference_landing_id']]);
        if (!is_null($model)) {
            unset($queryParams["preference_landing_id"]);
            $slider = $model->imageSlider;
            if (!is_null($slider)) {
                $dataProviderSliderElemImage = new ActiveDataProvider(['query' => $slider->getSliderElems()->orderBy('order ASC')]);
            }
        }

        return $dataProviderSliderElemImage;
    }

    /**
     *
     * @param array $params
     * @param int $limit
     * @return ActiveDataProvider
     */
    public function cmsSearchVideo($params, $limit = null)
    {
        $queryParams = [];
        $dataProviderSliderElemVideo = null;
        if (!empty($params["conditionSearch"])) {
            $commands = explode(";", $params["conditionSearch"]);
            foreach ($commands as $command) {
                $queryParams = ArrayHelper::merge($queryParams,
                    eval("return " . $command . ";"));
            }
        }
        $model = $this->findOne(['id' => $queryParams['preference_landing_id']]);
        if (!is_null($model)) {
            unset($queryParams["preference_landing_id"]);
            $sliderVideo = $model->videoSlider;
            if (!is_null($sliderVideo)) {
                $dataProviderSliderElemVideo = new ActiveDataProvider(['query' => $sliderVideo->getSliderElems()->orderBy('order ASC')]);
            }
        }

        return $dataProviderSliderElemVideo;
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

        return null;
    }

    /**
     *
     * @return array
     */
    public function cmsViewFields()
    {
        $viewFields = [];
        array_push($viewFields,
            new CmsField("title", "TEXT", 'preferencelandings',
                $this->attributeLabels()["title"]));
        array_push($viewFields,
            new CmsField("description", "TEXT", 'preferencelandings',
                $this->attributeLabels()['summary']));


        return $viewFields;
    }

    /**
     *
     * @return array
     */
    public function cmsSearchFields()
    {
        $searchFields = [];

        array_push($searchFields, new CmsField("title", "TEXT"));
        array_push($searchFields, new CmsField("description", "TEXT"));

        return $searchFields;
    }

    /**
     *
     * @param type $id
     * @return boolean
     */
    public function cmsIsVisible($id)
    {
        $retValue = true;
        //        if (isset($id)) {
        //            $md = $this->findOne($id);
        //            if (!is_null($md)) {
        //                $retValue = $md->visible_in_the_calendar;
        //            }
        //        }

        return $retValue;
    }


}
