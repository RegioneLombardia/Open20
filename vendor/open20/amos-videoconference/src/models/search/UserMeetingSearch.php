<?php

namespace open20\amos\videoconference\models\search;


use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserContact;
use open20\amos\admin\models\UserProfile;
use open20\amos\admin\utility\UserProfileUtility;
use open20\amos\chat\DataProvider;
use open20\amos\community\models\CommunityUserMm;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;


class UserMeetingSearch extends Model
{
    public $name;
    public $char;

    public $invitationName;

    const SCENARIO_NOMINAL_INVITATION = 'scenario_nominal_invitation';


    public function init()
    {
        parent::init();
    }

    /**
     * @return array
     */
    public function scenarios()
    {
        $scenarios = ArrayHelper::merge(
            parent::scenarios(), [
                self::SCENARIO_NOMINAL_INVITATION => [
                    'name', 'char', 'invitationName'
                ],
            ]
        );
        return $scenarios;

    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['invitationName', 'required', 'on' => self::SCENARIO_NOMINAL_INVITATION],
            [['name', 'char', 'invitationName'], 'safe']
        ];
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function search($params)
    {

        $moduleVideoconf = \Yii::$app->getModule('videoconference');
        $userProfile = AmosAdmin::instance()->createModel('UserProfile');
        $loggedUserId = \Yii::$app->user->id;
        $contacts = UserContact::find()->andWhere(['or',
            ['user_contact.user_id' => $loggedUserId],
            ['user_contact.contact_id' => $loggedUserId]
        ])->andWhere(['<>', 'user_contact.status', UserContact::STATUS_REFUSED])->all();

        $listContact =[];
        foreach ($contacts as $userContact){
            $listContact []= $userContact->user_id;
            $listContact []= $userContact->contact_id;
        }


        $query = $userProfile::find()
            ->innerJoinWith('user')
            ->andWhere(['attivo' => 1])
            ->andWhere(['NOT LIKE', 'user_profile.nome', UserProfileUtility::DELETED_ACCOUNT_NAME]);

        if(!empty($moduleVideoconf) && $moduleVideoconf->onlyNetworkUsers){
            $query->andWhere(['in', 'user_profile.user_id' , $listContact]);
        }
//            ->innerJoin('user_contact', 'user_profile.user_id = user_contact.contact_id')
        $cwh = \Yii::$app->getModule("cwh");
        // if we are navigating users inside a sprecific entity
        // see users filtered by entity-user association table
        if (isset($cwh)) {
            $cwh->setCwhScopeFromSession();
            if (!empty($cwh->userEntityRelationTable)) {
                $mmTable = $cwh->userEntityRelationTable['mm_name'];
                $mmTableAlis =  'u2';
                $entityField = $cwh->userEntityRelationTable['entity_id_field'];
                $entityId = $cwh->userEntityRelationTable['entity_id'];
                $query
                    ->innerJoin($mmTable . ' ' .$mmTableAlis, $mmTableAlis . '.user_id = user_profile.user_id ')
                    ->andWhere([
                        $mmTableAlis . '.' . $entityField => $entityId
                    ])->andWhere($mmTableAlis . '.deleted_at is null');

                $mmTableSchema = \Yii::$app->db->schema->getTableSchema($mmTable);
                if(isset($mmTableSchema->columns['status'])) {
                    $query->andWhere([$mmTableAlis . '.status' => 'ACTIVE']);
                }
            }
        }


        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $dataProvider->setSort([
            'attributes' => ['nome', 'cognome'],
            'defaultOrder' => [
                'cognome' => SORT_ASC,
                'nome' => SORT_ASC
            ]
        ]);


        $this->load($params);


        $query->andFilterWhere(['LIKE', 'cognome', $this->char . '%', false]);
        $query->andFilterWhere(
            ['or',
                ['like', 'cognome', $this->name],
                ['like', 'nome', $this->name],
                ['like', 'codice_fiscale', $this->name],
                ['like', 'email', $this->name],
                ['like', "CONCAT( nome , ' ', cognome )", $this->name],
                ['like', "CONCAT( cognome , ' ', nome )", $this->name],
            ]
        );

        return $dataProvider;
    }


}