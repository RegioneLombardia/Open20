<?php

namespace open20\amos\videoconference\models\base;

use open20\amos\admin\models\UserContact;
use open20\amos\admin\models\UserProfile;
use yii\helpers\ArrayHelper;
use open20\amos\admin\AmosAdmin;

/**
 * Class UserProfileForm
 * @package open20\amos\videoconference\models\base
 * Classe "cuscinetto" per permettere il caricamento dei partecipanti delle videoconferenze
 */
class UserProfileForm extends \yii\base\Model
{
    public $ids = [];
    public $videoconf_id;
    public $nome;
    public $cognome;

    public function rules()
    {
        return [
            ['ids', 'safe'],
            ['videoconf_id', 'required'],
            ['ids', 'each', 'rule' => [
                'exist', 'targetClass' => UserProfile::className(), 'targetAttribute' => 'id'
            ]],
            // define validation rules here
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'ids' => 'IDS',
            'videoconf_id' => 'Videoconferenza id',
            'nome' => 'Nome',
            'cognome' => 'Cognome',
        ];
    }

    public function attributeHints()
    {
        return null;
    }

    public function getModelName()
    {
        return \yii\helpers\StringHelper::basename(self::className());
    }

    /**
     * Returns the text hint for the specified attribute.
     * @param string $attribute the attribute name
     * @return string the attribute hint
     */
    public function getAttributeHint($attribute)
    {
        return null;
        $hints = $this->attributeHints();
        return isset($hints[$attribute]) ? $hints[$attribute] : null;
    }

    /**
     * carica i partecipanti di una videoconferenza
     */
    public function loadUsers()
    {
        $this->ids = [];
        //cerco gli user_profile partecipanti ad una videoconferenza
        $q = UserProfile::find()
            ->innerJoin('videoconf_users_mm', 'videoconf_users_mm.user_profile_id = user_profile.id')
            ->andWhere(['videoconf_users_mm.deleted_by' => null])
            ->andWhere(['videoconf_users_mm.videoconf_id' => $this->videoconf_id]);
        /* pr($q->createCommand()->rawSql, "query get user stored"); */
        $storedUser = $q->all();

        //ritorno un array con gli ID degli user_profile partecipanti
        foreach ($storedUser as $userRecord) {
            $this->ids[] = $userRecord->id;
        }
    }

    /**
     * salva i partecipanti di una videoconferenza nella tabella di associazione videoconf_users_mm
     * @return bool
     */
    public function saveUser2Videoconference($users_deleted_ids = null)
    {
        if (empty($this->videoconf_id)) {
            return false;
        }

        //cancello tutti partecipanti eventualmente presenti
		VideoconfUsersMm::deleteAll(['videoconf_id' => $this->videoconf_id,'user_profile_id'=>$users_deleted_ids]);
        

        if (is_array($this->ids)) {
			
            //scorro tutti gli id user profile che sono da associare
            foreach ($this->ids as $user_profile_id) {
                if($user_profile_id != '') {
                    //controllo esistenza record già presente per i valori da inserire
                    $q = VideoconfUsersMm::find()
                        ->andWhere(['videoconf_id' => $this->videoconf_id, 'user_profile_id' => $user_profile_id,
                            'deleted_by' => null]);
                    $exist = ($q->count() > 0) ? true : false;

                    //se non esiste: salvo associazione
                    if (!$exist) {
                        $mm = new VideoconfUsersMm();
                        $mm->videoconf_id = $this->videoconf_id;
                        $mm->user_profile_id = $user_profile_id;
                        //pr($mm->toArray(), "salverei");
                        if ($mm->save()) {
                            $userProfile = AmosAdmin::instance()->createModel('UserProfile');
                            $profile = $userProfile::findOne($mm->user_profile_id);
                            if (isset($profile->user_id)) {
                                $permissions = \Yii::$app->authManager->getPermissionsByUser($profile->user_id);
                                if (!isset($permissions['JoinOwnVideoconference'])) {
                                    $perm = \Yii::$app->authManager->getPermission('JoinOwnVideoconference');
                                    if (!empty($perm)) {
                                        \Yii::$app->authManager->assign($perm, $profile->user_id);
                                    }
                                }
                            }
                        }

                        //controllo se esiste già almeno una notifica per videoconferenza_id e user_id
                        $has_notification = \open20\amos\videoconference\models\Videoconf::checkHasNotificationByVideoconfAndUser($this->videoconf_id,
                            $user_profile_id);
                        //se non esiste: lo creo in modo che venga poi inviata
                        if ($has_notification === false) {
                            \open20\amos\videoconference\models\Videoconf::createNotificationByVideoconfAnduser($this->videoconf_id,
                                $user_profile_id);
                        }
                    }
                }
            }
            return true;
        }

        return true;
    }

    /**
     * @return array Tutti gli utenti del sistema, prelevati dalla tabella user_profile
     */
    public static function getAvailableUsers()
    {
        $moduleVideoconf = \Yii::$app->getModule('videoconference');
        $userProfile = AmosAdmin::instance()->createModel('UserProfile');
        $loggedUserId = \Yii::$app->user->id;
        $contacts = UserContact::find()->andWhere(['or',
            ['user_contact.user_id' => $loggedUserId],
            ['user_contact.contact_id' => $loggedUserId]
        ])->andWhere(['<>', 'user_contact.status', UserContact::STATUS_REFUSED])->all();

        $listContact = [];
        foreach ($contacts as $userContact) {
            $listContact [] = $userContact->user_id;
            $listContact [] = $userContact->contact_id;
        }


        $query = $userProfile::find();
        if (!empty($moduleVideoconf) && $moduleVideoconf->onlyNetworkUsers) {
            $query->andWhere(['in', 'user_profile.user_id', $listContact]);
        }
//            ->innerJoin('user_contact', 'user_profile.user_id = user_contact.contact_id')
        $cwh = \Yii::$app->getModule("cwh");
        $community = \Yii::$app->getModule("community");
        // if we are navigating users inside a sprecific entity
        // see users filtered by entity-user association table
        if (isset($cwh)) {
            $cwh->setCwhScopeFromSession();
            if (!empty($cwh->userEntityRelationTable)) {
                $mmTable = $cwh->userEntityRelationTable['mm_name'];
                $mmTableAlis = 'u2';
                $entityField = $cwh->userEntityRelationTable['entity_id_field'];
                $entityId = $cwh->userEntityRelationTable['entity_id'];
                $query
                    ->innerJoin($mmTable . ' ' . $mmTableAlis, $mmTableAlis . '.user_id = user_profile.user_id ')
                    ->andWhere([
                        $mmTableAlis . '.' . $entityField => $entityId
                    ])->andWhere($mmTableAlis . '.deleted_at is null');

                $mmTableSchema = \Yii::$app->db->schema->getTableSchema($mmTable);
                if (isset($mmTableSchema->columns['status'])) {
                    $query->andWhere([$mmTableAlis . '.status' => 'ACTIVE']);
                }
            }
        }
//pr($query->createCommand()->rawSql);
        $users = $query->asArray()->all();
        $items = [];
        foreach ($users as $value) {
            $items[$value['id']] = $value['nome'] . ' ' . $value['cognome'] . (!empty($value['codice_fiscale']) ? (' - ' . $value['codice_fiscale'])
                    : '');
        }
        return $items;
    }
}