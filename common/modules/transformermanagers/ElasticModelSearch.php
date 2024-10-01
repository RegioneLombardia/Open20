<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\base
 * @category   CategoryName
 */

namespace common\modules\transformermanagers;

use open20\amos\community\models\Community;
use open20\amos\core\record\CmsField;
use open20\amos\core\utilities\CurrentUser;
use open20\amos\cwh\utility\CwhUtil;
use open20\elasticsearch\base\ElasticDataProvider;
use open20\elasticsearch\models\ElasticQuery;
use open20\elasticsearch\transformer\AbstractTransformer;
use open20\elasticsearch\base\ElasticConst;
use Yii;

class ElasticModelSearch extends \open20\elasticsearch\models\search\ElasticModelSearch {

    public function cmsIsVisible($id) {
        $retValue = true;
        return $retValue;
    }

    public function cmsSearch($params, $limit) {
        $dataProvider = null;
        $weights = ["title^5", "sottotitolo^1", "descrizione_breve^1", "descrizione^1", "elasticSourceText^5"];

        $params = array_merge($params, Yii::$app->request->get());
        if (isset($params['searchtext'])) {
            if (empty($params['searchtext'])) {
                return $dataProvider; // Non ritorno risultati
            }
            $moduleCwh = \Yii::$app->getModule('cwh');
            if ($moduleCwh) {
                $moduleCwh->resetCwhScopeInSession();
            }
            $query = new ElasticQuery();
            $query->boolQuery();
            if (CurrentUser::isPlatformGuest()) {
                $query->addFilter('public', "1");
                $query->addFilter(AbstractTransformer::STATUS_INDEX, AbstractTransformer::VALIDATED_VALUE);
            } else {
                if(\Yii::$app->adminuser->isGuest){
                     $query->addFilter(AbstractTransformer::STATUS_INDEX, AbstractTransformer::VALIDATED_VALUE);
                }
                $query = $this->addCwhConditions($query);
            }

            if (!empty($params['searchtext'])) {
                $query->addMust("query", ElasticConst::WILDCARD_MORE . $this->getModule()->folding->folding(self::purifyText($params['searchtext'])),
                        "query_string", "open20_italian", $weights, true);
            }

            $functions = [
                [
                    'gauss' => ['start_publication' => ['scale' => '365d']],
                ]
            ];

            $query->functionScoreQuery('bool', $functions, 'sum');

            $dataProvider = new ElasticDataProvider([
                'query' => $query,
            ]);
            if ($params["withPagination"]) {
                $dataProvider->setPagination(['pageSize' => $limit]);
                $query->limit(null);
            } else {
                $query->limit($limit);
            }
        }
        return $dataProvider;
    }

    public function cmsSearchFields() {
        $searchFields = [];

        array_push($searchFields, new CmsField("title", "TEXT"));
        array_push($searchFields, new CmsField("description", "TEXT"));

        return $searchFields;
    }

    public function cmsViewFields() {
        return [
            new CmsField('title', 'TEXT', 'amoselasticsearch', $this->attributeLabels()['title']),
            new CmsField('description', 'TEXT', 'amoselasticsearch', $this->attributeLabels()['description']),
        ];
    }

    /**
     * @param $model
     * @param $primoPiano
     * @return int
     * @throws \yii\base\InvalidConfigException
     */
    public static function guestCanViewContentCommunity($model, $primoPiano) {
        $cwh = \Yii::$app->getModule('cwh');
        if (!$cwh) {
            $pubblicazione = CwhUtil::getCwhPubblicazione($model); //pr($pubblicazione->cwhPubblicazioniCwhNodiValidatoriMms);die;
            if ($pubblicazione) {
                $nodiValidatori = $pubblicazione->cwhPubblicazioniCwhNodiValidatoriMms;
                if ($nodiValidatori) {
                    $scopeContent = array_pop($nodiValidatori)->cwh_nodi_id;
                    if ($scopeContent) {
                        $scopeContent = explode('-', $scopeContent);
                        if ($scopeContent[0] == 'community' && \Yii::$app->getModule('community')) {
                            $type = Community::findOne($scopeContent[1])->community_type_id;
                            return ($primoPiano && $type == 1) ? 1 : 0;
                        }
                    }
                }
            }
        }
        return $primoPiano;
    }

    /**
     * @param $text
     * @return array|string|string[]
     */
    public static function purifyText($text) {
        $retValue = urldecode(html_entity_decode(strip_tags($text)));
        $retValue = str_replace(['-', ',', "'", '  ', '.', ';', ':', '(', ')', '[', ']', '{', '}', '!', '?', '£', '$', "\\", '/',
            '%', '&', '=', '^', '*', '§', '°', '#', '@', 'ç', '>', '<', '¿', '"', '“', '”'], '', $retValue);
        $retValue = str_replace(['  '], '', $retValue);
        return $retValue;
    }
}
