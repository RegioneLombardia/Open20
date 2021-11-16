<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\models\search
 * @category   CategoryName
 */

namespace open20\amos\socialauth\models\search;

use conquer\oauth2\models\Client;
use open20\amos\core\interfaces\CmsModelInterface;
use open20\amos\core\interfaces\ContentModelSearchInterface;
use open20\amos\core\interfaces\SearchModelInterface;
use open20\amos\core\record\CmsField;
use open20\amos\news\models\News;
use Yii;
use yii\data\ActiveDataProvider;
use yii\di\Container;

/**
 * NewsSearch represents the model behind the search form about `open20\amos\news\models\News`.
 */
class ClientSearch extends Client
{

    /** @var  Container $container - used by ContentModel do not remove */
    private $container;

    public $isSearch;
    public $modelClassName;

    /**
     * @inheritdoc
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        /** @var bool $isSearch - it is the content model search class */
        $this->isSearch = true;
        parent::__construct($config);

        $this->modelClassName = Client::className();
    }

    /**
     */
    public function rules()
    {
        return [
            [['created_by', 'updated_by', 'created_at','updated_at'], 'integer'],
            [['client_id', 'client_secret', 'redirect_uri', 'grant_type', 'scope'], 'safe'],
        ];
    }

    /**
     *
     * @return type
     */
    public function searchFieldsMatch()
    {
        return [
            'client_id',
            'client_secret',
            'redirect_uri',
            'grant_type',
            'scope'
        ];
    }

    /**
     * Array of fields to search with >= condition in search method
     *
     * @return array
     */
    public function searchFieldsGreaterEqual()
    {
        return [
        ];
    }

    /**
     *
     * @return type
     */
    public function searchFieldsLike()
    {
        return [
            'client_id',
            'client_secret',
            'redirect_uri',
            'grant_type',
            'scope'
        ];
    }

    /**
     *
     */
    public function searchFieldsGlobalSearch()
    {
        return [
        ];
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
     * // Check if can use the custom module order
     *
     * @inheritdoc
     */
    public function searchDefaultOrder($dataProvider)
    {

        if ($this->canUseModuleOrder()) {
            $dataProvider->setSort($this->createOrderClause());
        } else {
            // For widget graphic last news, order is incorrect without this else
            $dataProvider->setSort([
                'defaultOrder' => [
                    'data_pubblicazione' => SORT_DESC
                ]
            ]);
        }

        return $dataProvider;
    }

    /**
     * @inheritdoc
     */
    public function searchAllQuery($params)
    {
        return $this->buildQuery($params, 'all');
    }

    /**
     * @inheritdoc
     */
    public function searchCreatedByMeQuery($params)
    {
        return $this->buildQuery($params, 'created-by');
    }

}