<?php

namespace open20\amos\sondaggi\models\search;

use open20\amos\admin\models\UserProfile;
use open20\amos\admin\models\UserProfileAgeGroup;
use open20\amos\admin\models\UserProfileClasses;
use open20\amos\admin\utility\UserProfileUtility;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\core\user\User;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use open20\amos\sondaggi\models\SondaggiInvitations;
use open20\amos\sondaggi\models\SondaggiInvitationMm;
use open20\amos\sondaggi\models\SondaggiRisposteSessioni;
use open20\amos\sondaggi\models\Sondaggi;
use open20\amos\tag\models\Tag;
use open20\amos\tag\models\EntitysTagsMm;
use open20\amos\sondaggi\AmosSondaggi;
use open20\amos\organizzazioni\models\Profilo;
use open20\amos\organizzazioni\models\ProfiloGroups;
use open20\amos\organizzazioni\models\ProfiloTypesPmi;
use yii\helpers\Console;

/**
 * SondaggiDomandeSearch represents the model behind the search form about `open20\amos\sondaggi\models\SondaggiDomande`.
 */
class SondaggiInvitationsSearch extends SondaggiInvitations {

    public function rules() {
        return SondaggiInvitations::rules();
    }

    public function scenarios() {
// bypass scenarios() implementation in the parent class
        return SondaggiInvitations::scenarios();
    }

    public static function getListOfAttributes()
    {
        return [
            'type' => AmosSondaggi::t('AmosSondaggi', 'Tipologia'),
            'name' => AmosSondaggi::t('AmosSondaggi', "Denominazione")
        ];
    }

    /**
     * @param $target
     * @param $type
     * @param $query
     * @return array|UserProfileAgeGroup[]|Profilo[]|ProfiloTypesPmi[]
     * @throws InvalidConfigException
     */
    public static function getAttributesValues($target, $type, $query = null) {
        $data = [];
        if ($target == 'users') {
            if ($type == 'age_group') {
                $data = UserProfileAgeGroup::find()->select(['id', 'name' => 'age_group'])->asArray()->all();
            }
            else if ($type == 'gender') {
                $genderValues = UserProfileUtility::getGenderValues();
                foreach ($genderValues as $id => $name) {
                    $data[] = ['id' => $id, 'name' => $name];
                }
            }
            else if ($type == 'profile_class') {
                $data = UserProfileClasses::find()->select(['id', 'name'])->all();
            }
        }
        else if ($target == 'organizations') {
            if ($type == 'type') {
                $data = ProfiloTypesPmi::find()->select(['id', 'name'])->andFilterWhere(['like', 'name', $query])->all();
            }
            if ($type == 'name') {
                $data = Profilo::find()->select(['id', 'name'])->andFilterWhere(['like', 'name', $query])->all();
            }
        }

        return $data;
    }

    public static function getPollTags() {
        return Tag::find();
    }

    public static function getPollGroups() {
        return ProfiloGroups::find();
    }

    public function search($params) {
        $query = SondaggiInvitations::find();
        if(isset($params['idSondaggio'])){
            $query->andWhere(['sondaggi_id' => $params['idSondaggio']]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        // if (!($this->load($params) && $this->validate())) {
        //     return $dataProvider;
        // }

        $query->andFilterWhere([
            'id' => $this->id,
            'name' => $this->name,
            'sondaggi_id' => $this->sondaggi_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);

        return $dataProvider;
    }

    public static function searchOrganizations($params) {
        $query = Profilo::find();
        if ($params['type'] == SondaggiInvitations::SEARCH_FILTER) {
            if ($params['filter_type'] == SondaggiInvitations::FILTER_GROUPS) {
                $query = $query->joinWith('profiloGroups')->andFilterWhere(['profilo_groups.id' => $params['search_groups']]);
            }
            if ($params['filter_type'] == SondaggiInvitations::FILTER_INVITED_TAG) {
                $tags = $params['search_tags'];
                $tagged = EntitysTagsMm::find()
                    ->select('record_id')
                    ->andWhere(['classname' => Sondaggi::className()])
                    ->andWhere(['tag_id' => $tags])->all();

                // Converting result to a list of poll IDs, and removing current poll to avoid loops...
                $polls = ArrayHelper::getColumn($tagged, 'record_id');
                if (($key = array_search($params['sondaggi_id'], $polls)) !== false) {
                    unset($polls[$key]);
                }

                $invitations = SondaggiInvitationMm::find()->select('to_id')->distinct()->andWhere(['sondaggi_id' => $polls])->all();
                $invited = ArrayHelper::getColumn($invitations, 'to_id');
                $query = $query->andWhere(['id' => $invited]);
            }
            if ($params['filter_type'] == SondaggiInvitations::FILTER_COMPILED_TAG) {
                $tags = $params['search_tags'];
                $tagged = EntitysTagsMm::find()
                    ->select('record_id')
                    ->andWhere(['classname' => Sondaggi::className()])
                    ->andWhere(['tag_id' => $tags])->all();

                // Converting result to a list of poll IDs, and removing current poll to avoid loops...
                $polls = ArrayHelper::getColumn($tagged, 'record_id');
                if (($key = array_search($params['sondaggi_id'], $polls)) !== false) {
                    unset($polls[$key]);
                }

                $answers = SondaggiRisposteSessioni::find()
                    ->select('organization_id')->distinct()
                    ->andWhere(['sondaggi_id' => $polls]);
                \Yii::debug($answers->createCommand()->rawSql, 'sondaggi');
                $answers = $answers->all();
                $answeredOrg = ArrayHelper::getColumn($answers, 'organization_id');
                $query = $query->andWhere(['id' => $answeredOrg]);
            }
        }
        for ($i = 1; $i <= count($params['field']); $i++) {
            $operator = '=';
            if (isset($params['include_exclude'][$i])) {
                if ($params['include_exclude'][$i] == SondaggiInvitations::FILTER_EXCLUDE) {
                    $operator = '!=';
                }
            }

            if (isset($params['field'][$i])) {
                if ($params['field'][$i] == 'type') {
                    $query = $query->joinWith('tipologiaDiOrganizzazione')->andFilterWhere([$operator, 'profilo_types_pmi.id', $params['value'][$i]]);
                }
                if ($params['field'][$i] == 'name') {
                    $query = $query->andFilterWhere([$operator, 'profilo.id', $params['value'][$i]]);
                }
            }
        }
        $query->groupBy('id');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $dataProvider;
    }

    /**
     * @param $params
     * @return mixed
     * @throws InvalidConfigException
     */
    public static function searchInvitedUsers($params)
    {
        //          pr($params);
        $query = User::find()
            ->innerJoin('user_profile', 'user_profile.user_id = user.id')
            ->andWhere(['user_profile.attivo' => true])
            ->distinct();

        if (isset($params['community_id'])) {
            $query->innerJoin('community_user_mm', 'community_user_mm.user_id = user.id')
                ->andWhere(['community_user_mm.community_id' => $params['community_id']])
                ->andWhere(['community_user_mm.status' => CommunityUserMm::STATUS_ACTIVE])
                ->andWhere(['community_user_mm.deleted_at' => null]);
        }

//        if (class_exists('open20\amos\admin\models\UserProfileClasses')) {
//            $query->leftJoin('user_profile_classes_user_mm as profile_class', 'profile_class.user_id = user.id')
//                ->andWhere(['profile_class.deleted_at' => null]);
//        }

        if (!empty($params['tagValues']) && $params['type'] == self::SEARCH_FILTER) {
            $query->leftJoin('cwh_tag_owner_interest_mm as user_tag', 'user_tag.record_id = user.id')
                ->andWhere([
                    'user_tag.deleted_at' => null,
                    'user_tag.classname' => 'open20\amos\admin\models\UserProfile',
                    'user_tag.interest_classname' => 'simple-choice'
                ]);
            $searchTags = [];
            foreach ($params['tagValues'] as $root => $tag_ids) {
                $explodedTags = explode(',', $tag_ids);
                foreach ($explodedTags as $tag_id) {
                    $searchTags[] = $tag_id;
                }
            }
            $query->andFilterWhere(['in', 'user_tag.tag_id', $searchTags]);
        }

        if ($params['type'] == self::SEARCH_FILTER) {

            $query->andFilterWhere(['in', 'user_profile.user_id', $params['users']]);

            if ($params['field']) {
                foreach ($params['field'] as $key => $field) {
                    if ($field == 'age_group') {
                        if ($params['include_exclude'][$key] == 1) {
                            $query->andWhere(['user_profile.user_profile_age_group_id' => $params['value'][$key]]);
                        } else {
                            $query->andWhere(['!=', 'user_profile.user_profile_age_group_id', $params['value'][$key]])
                                ->andWhere(['is not', 'user_profile.user_profile_age_group_id', null]);
                        }
                    }
                    if ($field == 'gender') {
                        if ($params['include_exclude'][$key] == 1) {
                            $query->andWhere(['user_profile.sesso' => $params['value'][$key]]);
                        } else {
                            $query->andWhere(['!=', 'user_profile.sesso', $params['value'][$key]])
                                ->andWhere(['is not', 'user_profile.sesso', null])
                                ->andWhere(['!=', 'user_profile.sesso', '']);
                        }
                    }
                    if ($field == 'profile_class') {
                        if ($params['include_exclude'][$key] == 1) {
                            $query->andWhere(['in', 'profile_class.user_profile_classes_id', $params['value'][$key]]);
                        } else {
                            // TODO
                            $query->andWhere(['not in', 'profile_class.user_profile_classes_id', $params['value'][$key]]);
                        }
                    }
                }
            }
        }

        return $query;
    }

    /**
     * @param $text string
     * @return UserProfile[]
     * @throws InvalidConfigException
     */
    public static function searchUsersAll($text)
    {
        return UserProfile::find()
            ->innerJoin('user', 'user.id = user_profile.user_id')
            ->andWhere(['or',
                ['like', 'user_profile.nome', $text],
                ['like', 'user_profile.cognome', $text],
                ['like', 'CONCAT(user_profile.nome, " ", user_profile.cognome)', $text],
                ['like', 'CONCAT(user_profile.cognome, " ", user_profile.nome)', $text],
                ['like', 'user.email', $text],
                ['like', 'user_profile.codice_fiscale', $text],
            ])
            ->andWhere(['user_profile.attivo' => true])
//                ->andWhere(['not in', 'user_id', SondaggiInvitations::find()->select('user_id')->andWhere(['sondaggio_id' => $id])])
            ->limit(20)
            ->all();
    }

    /**
     * @param $text string
     * @param $communityId integer
     * @return UserProfile[]
     * @throws InvalidConfigException
     */
    public static function searchUsersCommunity($text, $communityId)
    {
        return UserProfile::find()
            ->innerJoin('user', 'user.id = user_profile.user_id')
            ->innerJoin('community_user_mm', 'community_user_mm.user_id = user.id')
            ->andWhere(['community_user_mm.community_id' => $communityId])
            ->andWhere(['community_user_mm.status' => CommunityUserMm::STATUS_ACTIVE])
            ->andWhere(['community_user_mm.deleted_at' => null])
            ->andWhere(['or',
                ['like', 'user_profile.nome', $text],
                ['like', 'user_profile.cognome', $text],
                ['like', 'CONCAT(user_profile.nome, " ", user_profile.cognome)', $text],
                ['like', 'CONCAT(user_profile.cognome, " ", user_profile.nome)', $text],
                ['like', 'user.email', $text],
                ['like', 'user_profile.codice_fiscale', $text],
            ])
            ->andWhere(['user_profile.attivo' => true])
//                ->andWhere(['not in', 'user_id', SondaggiInvitations::find()->select('user_id')->andWhere(['sondaggio_id' => $id])])
            ->limit(20)
            ->all();
    }

}
