<?php

namespace frontend\modules\flnews\models\search;

use open20\amos\news\models\News;
use open20\amos\tag\models\EntitysTagsMm;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class NewsSearch extends \open20\amos\news\models\search\NewsSearch
{

    /**
     * @var
     */
    public $keyword;

    /**
     * @var
     */
    public $tagValuesFondi;

    /**
     * @var
     */
    public $tagValuesTematiche;

    /**
     * @return array|array[]
     */
    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                [['keyword', 'tagValuesFondi', 'tagValuesTematiche'], 'safe']
            ]
        );

    }

    /**
     * Show filtered news for Bandi e Servizi cms
     * @param $params
     * @param $limit
     * @return ActiveDataProvider
     */
    public function cmsSearchFiltered($params, $limit = null)
    {
        $dataProvider = $this->cmsSearch($params, $limit, 'all');

        $this->filterByKeywords($dataProvider);

        $this->filterByTags($dataProvider);

        $this->getPagination($dataProvider, $limit);

        return $dataProvider;
    }

    /**
     * @param $dataProvider
     * @return void
     */
    public function filterByKeywords($dataProvider)
    {
        $dataProvider->query->andFilterWhere([
            'OR',
            ['like', 'titolo', $this->keyword],
            ['like', 'descrizione_breve', $this->keyword],
            ['like', 'sottotitolo', $this->keyword],
            ['like', 'descrizione', $this->keyword],
        ]);
    }

    /**
     * Overwritten method from plugin news
     * get the query used by the related searchHomepageNews method
     * return just the query in case data provider/query itself needs editing
     *
     * @param array $params
     * @return \yii\db\ActiveQuery
     * @throws InvalidConfigException
     */
    public function homepageNewsQuery($params)
    {
        $now       = date('Y-m-d H:i:s');
        $tableName = $this->tableName();
        $query = $this->baseSearch($params)
            ->distinct()->leftJoin(EntitysTagsMm::tableName(), EntitysTagsMm::tableName() . ".classname = '".  str_replace('\\','\\\\',News::className()) . "' and ".EntitysTagsMm::tableName(). ".record_id = ". News::tableName() . ".id  and " . EntitysTagsMm::tableName(). ".deleted_at is NULL")
            ->andWhere([
                $tableName . '.status' => News::NEWS_WORKFLOW_STATUS_VALIDATO,
                $tableName . '.primo_piano' => 1
            ])
            ->andWhere(['<=', 'data_pubblicazione', $now])
            ->andWhere(['or',
                    ['>=', 'data_rimozione', $now],
                    ['data_rimozione' => null]]
            )
            ->andWhere(['or',
                    ['>=', 'news_expiration_date', $now],
                    ['news_expiration_date' => null]]
            );

        return $query;
    }

    /**
     * @param $dataProvider
     * @param $limit
     * @return void
     */
    public function getPagination($dataProvider, $limit)
    {
        if (!empty($params["withPagination"])) {
            $dataProvider->setPagination(['pageSize' => $limit]);
            $dataProvider->query->limit(null);
        } else {
            $dataProvider->query->limit($limit);
        }
    }

    public function filterByTags($dataProvider)
    {
        if ((!empty($this->tagValuesFondi) && empty($this->tagValuesTematiche)) ||
            (!empty($this->tagValuesTematiche) && empty($this->tagValuesFondi))) {
            $dataProvider->query->andFilterWhere(['OR',
                ['entitys_tags_mm.tag_id' => $this->tagValuesFondi],
                ['entitys_tags_mm.tag_id' => $this->tagValuesTematiche]
            ]);
        } else if (!empty($this->tagValuesFondi) && !empty($this->tagValuesTematiche)) {
            $dataProvider->query->andWhere(['OR',
                ['entitys_tags_mm.tag_id' => $this->tagValuesFondi],
                ['entitys_tags_mm.tag_id' => $this->tagValuesTematiche]
            ])->groupBy('id')->having(['=', 'count(*)', 2]);
        }
    }
}