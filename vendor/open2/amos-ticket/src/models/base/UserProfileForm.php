<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\models\base
 * @category   CategoryName
 */

namespace open2\amos\ticket\models\base;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use open20\amos\admin\utility\UserProfileUtility;
use open20\amos\community\models\Community;
use yii\db\ActiveQuery;

/**
 * Class UserProfileForm
 * @package open2\amos\ticket\models\base
 * Classe "cuscinetto" per permettere il caricamento dei referenti delle categorie
 */
class UserProfileForm extends \yii\base\Model
{
    public $ids = [];
    public $ticket_categoria_id;
    public $nome;
    public $cognome;
    
    public function rules()
    {
        return [
            ['ids', 'safe'],
            ['ticket_categoria_id', 'required'],
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
            'ticket_categoria_id' => 'Categoria id',
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
     * carica i referenti di una categoria
     */
    public function loadUsers()
    {
        $this->ids = [];
        //cerco gli user_profile referenti di una categoria
        $q = UserProfile::find()
            ->innerJoin('ticket_categorie_users_mm', 'ticket_categorie_users_mm.user_profile_id = user_profile.id')
            ->andWhere(['ticket_categorie_users_mm.deleted_by' => null])
            ->andWhere(['ticket_categorie_users_mm.ticket_categoria_id' => $this->ticket_categoria_id])
            ->andWhere([UserProfile::tableName() . '.attivo' => UserProfile::STATUS_ACTIVE])
            ->andWhere(['!=', UserProfile::tableName() . '.nome', UserProfileUtility::DELETED_ACCOUNT_NAME]);
        /* pr($q->createCommand()->rawSql, "query get user stored"); */
        $storedUser = $q->all();
        
        //ritorno un array con gli ID degli user_profile referenti
        foreach ($storedUser as $userRecord) {
            $this->ids[] = $userRecord->id;
        }
    }
    
    /**
     * salva i refereneti di una categoria nella tabella di associazione ticket_categorie_users_mm
     * @return bool
     */
    public function saveUser2TicketCategoria()
    {
        if (empty($this->ticket_categoria_id)) {
            return false;
        }
        
        //cancello tutti referenti eventualmente presenti
        TicketCategorieUsersMm::deleteAll(['ticket_categoria_id' => $this->ticket_categoria_id]);
        
        if (is_array($this->ids)) {
            //scorro tutti gli id user profile che sono da associare
            foreach ($this->ids as $user_profile_id) {
                //controllo esistenza record già presente per i valori da inserire
                $q = TicketCategorieUsersMm::find()->andWhere(['ticket_categoria_id' => $this->ticket_categoria_id, 'user_profile_id' => $user_profile_id,
                    'deleted_by' => null]);
                // pr($q->createCommand()->rawSql); 
                $exist = ($q->count() > 0) ? true : false;
                
                //se non esiste: salvo associazione
                if (!$exist) {
                    $mm = new TicketCategorieUsersMm();
                    $mm->ticket_categoria_id = $this->ticket_categoria_id;
                    $mm->user_profile_id = $user_profile_id;
                    //pr($mm->toArray(), "salverei");
                    if ($mm->save()) {
                        /* $userProfile = AmosAdmin::instance()->createModel('UserProfile');
                         $profile = $userProfile::findOne($mm->user_profile_id);
                         if (isset($profile->user_id)) {
                             $permissions = \Yii::$app->authManager->getPermissionsByUser($profile->user_id);
                             if (!isset($permissions['JoinOwnVideoconference'])) {
                                 $perm = \Yii::$app->authManager->getPermission('JoinOwnVideoconference');
                                 if (!empty($perm)) {
                                     \Yii::$app->authManager->assign($perm, $profile->user_id);
                                 }
                             }
                         }*/
                    }
                }
            }
            return true;
        }
        
        return true;
    }
    
    /**
     * @return array Tutti gli utenti del sistema con ruolo REFERENTE_TICKET, prelevati dalla tabella user_profile
     */
    public static function getAvailableUsers()
    {
        $userProfile = AmosAdmin::instance()->createModel('UserProfile');
        $loggedUserId = \Yii::$app->user->id;
        $referentsUserIds = null;
        
        // If scope set, filter categories for cwh
        $moduleCwh = \Yii::$app->getModule('cwh');
        if (!is_null($moduleCwh)) {
            $scope = $moduleCwh->getCwhScope();
            if (!empty($scope) && isset($scope['community'])) {
                $community = Community::findOne(['id' => $scope['community']]);
                if (!empty($community)) {
                    $communityManagers = $community->getCommunityManagers()->asArray()->all();
                    foreach ($communityManagers as $communityManager) {
                        $referentsUserIds[] = $communityManager['id'];
                    }
                }
            }
        }
        if (empty($referentsUserIds)) {
            $referentsUserIds = \Yii::$app->getAuthManager()->getUserIdsByRole('REFERENTE_TICKET');
        }
        
        /** @var ActiveQuery $query */
        $query = $userProfile::find()
            /*->andWhere(['user_profile.status' => UserProfile::USERPROFILE_WORKFLOW_STATUS_VALIDATED])*/
            ->andWhere(['user_profile.user_id' => $referentsUserIds])
            ->orderBy(['user_profile.cognome' => SORT_ASC, 'user_profile.nome' => SORT_ASC]);
        $query->andWhere([UserProfile::tableName() . '.attivo' => UserProfile::STATUS_ACTIVE]);
        $query->andWhere(['!=', UserProfile::tableName() . '.nome', UserProfileUtility::DELETED_ACCOUNT_NAME]);
        
        
        $cwh = \Yii::$app->getModule("cwh");
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
        $users = $query->asArray()->all();
        $items = [];
        foreach ($users as $value) {
            $items[$value['id']] = $value['nome'] . ' ' . $value['cognome'] . (!empty($value['codice_fiscale']) ? (' - ' . $value['codice_fiscale']) : '');
        }
        return $items;
    }
}
