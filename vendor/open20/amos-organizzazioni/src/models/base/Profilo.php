<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\models\base
 * @category   CategoryName
 */

namespace open20\amos\organizzazioni\models\base;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use open20\amos\admin\utility\UserProfileUtility;
use open20\amos\community\AmosCommunity;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\core\helpers\Html;
use open20\amos\core\record\NetworkModel;
use open20\amos\organizzazioni\Module;
use yii\helpers\ArrayHelper;

/**
 * Class Profilo
 *
 * This is the base-model class for table "profilo".
 *
 * @property integer $id
 * @property string $status
 * @property string $name
 * @property string $unique_secret_code
 * @property string $partita_iva
 * @property string $codice_fiscale
 * @property string $istat_code
 * @property string $presentazione_della_organizzaz
 * @property string $principali_ambiti_di_attivita_
 * @property string $ambiti_tecnologici_su_cui_siet
 * @property string $tipologia_di_organizzazione
 * @property string $forma_legale
 * @property string $sito_web
 * @property string $facebook
 * @property string $twitter
 * @property string $linkedin
 * @property string $google
 * @property string $indirizzo
 * @property string $telefono
 * @property string $fax
 * @property string $email
 * @property string $pec
 * @property string $la_sede_legale_e_la_stessa_del
 * @property string $sede_legale_indirizzo
 * @property string $sede_legale_telefono
 * @property string $sede_legale_fax
 * @property string $sede_legale_email
 * @property string $sede_legale_pec
 * @property string $responsabile
 * @property string $rappresentante_legale
 * @property string $rappresentante_legale_text
 * @property string $referente_operativo
 * @property string $contatto_referente_operativo
 * @property string $imported_at
 * @property string $parent_id
 * @property string $profilo_enti_type_id
 * @property integer $tipologia_struttura_id
 * @property integer $community_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\admin\models\UserProfile $rappresentanteLegale
 * @property \open20\amos\admin\models\UserProfile $referenteOperativo
 * @property \open20\amos\organizzazioni\models\ProfiloTypesPmi $tipologiaDiOrganizzazione
 * @property \open20\amos\organizzazioni\models\ProfiloLegalForm $formaLegale
 * @property \open20\amos\organizzazioni\models\Profilo $parent
 * @property \open20\amos\organizzazioni\models\Profilo $children
 * @property \open20\amos\organizzazioni\models\ProfiloSedi[] $profiloSedi
 * @property \open20\amos\organizzazioni\models\ProfiloSedi[] $otherHeadquarters
 * @property \open20\amos\organizzazioni\models\ProfiloSedi[] $otherActiveHeadquarters
 * @property \open20\amos\organizzazioni\models\ProfiloEntiType $profiloEntiType
 * @property \open20\amos\organizzazioni\models\ProfiloUserMm[] $profiloUserMms
 * @property \open20\amos\core\user\User[] $profiloUsers
 * @property \open20\amos\organizzazioni\models\ProfiloUserMm[] $employeesMms
 * @property \open20\amos\core\user\User[] $employees
 * @property \open20\amos\organizzazioni\models\ProfiloTipoStruttura $tipologiaStruttura
 * @property \open20\amos\organizzazioni\models\ProfiloGroupsMm[] $profiloGroupsMms
 * @property \open20\amos\organizzazioni\models\ProfiloGroups[] $profiloGroups
 *
 * @package open20\amos\organizzazioni\models\base
 */
abstract class Profilo extends NetworkModel
{
    /**
     * @var Module $organizzazioniModule
     */
    protected $organizzazioniModule;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profilo';
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->organizzazioniModule = Module::instance();
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        /** @var \open20\amos\organizzazioni\models\ProfiloEntiType $profiloEntiTypeModel */
        $profiloEntiTypeModel = $this->organizzazioniModule->model('ProfiloEntiType');
        $typeMunicipalityId = $profiloEntiTypeModel::TYPE_MUNICIPALITY;
        $disableFieldChecks = isset($organizzazioniModule->disableFieldChecks) ? $this->organizzazioniModule->disableFieldChecks : false;
    
        $requiredFields = [
            'name',
        ];
        
        if (!empty($this->organizzazioniModule->addRequired) && isset($this->organizzazioniModule->addRequired['Profilo'])) {
            $requiredFields = ArrayHelper::merge($requiredFields, $this->organizzazioniModule->addRequired['Profilo']);
            $requiredFields = array_unique($requiredFields);
        }

        $rules = [
            [$requiredFields, 'required'],
            [['imported_at', 'created_at', 'updated_at', 'deleted_at', 'rappresentante_legale'], 'safe'],
            [[
                'created_by',
                'updated_by',
                'deleted_by',
                'parent_id',
                'profilo_enti_type_id',
                'referente_operativo',
                'rappresentante_legale',
                'tipologia_struttura_id'
            ], 'integer'],
            [[
                'presentazione_della_organizzaz',
                'telefono',
                'fax',
                'sede_legale_telefono',
                'sede_legale_fax'
            ], 'string'],
            [[
                'name',
                'partita_iva',
                'codice_fiscale',
                'tipologia_di_organizzazione',
                'forma_legale',
                'sito_web',
                'facebook',
                'twitter',
                'linkedin',
                'google',
                'indirizzo',
                'email',
                'pec',
                'la_sede_legale_e_la_stessa_del',
                'sede_legale_indirizzo',
                'sede_legale_email',
                'sede_legale_pec',
                'responsabile',
                'rappresentante_legale_text',
                'contatto_referente_operativo',
                'status'
            ], 'string', 'max' => 255],
            [['istat_code'], 'string', 'max' => 10],
            [['unique_secret_code'], 'string', 'max' => 50],
            [['logoOrganization'], 'file', 'extensions' => 'jpeg, jpg, png, gif', 'maxFiles' => 1],
        ];

        if (!empty($this->organizzazioniModule->allowedFileExtensions)) {
            $extensions = join(', ', $this->organizzazioniModule->allowedFileExtensions);
            $rules[] = [['allegati'], 'file', 'extensions' => $extensions, 'maxFiles' => 0];
        } else {
            $rules[] = [['allegati'], 'file', 'maxFiles' => 0];
        }

        if ($this->organizzazioniModule->enableProfiloEntiType === true) {
            $rules[] = [['istat_code'], 'required', 'when' => function ($model) use ($typeMunicipalityId, $disableFieldChecks) {
                if ($this->organizzazioniModule->enableCodeIstatRequired == true) {
                    /** @var \open20\amos\organizzazioni\models\Profilo $model */
                    return ($model->profilo_enti_type_id == $typeMunicipalityId && !$disableFieldChecks);
                } else {
                    return false;
                }
            }, 'whenClient' => "function (attribute, value) {" .
                (($this->organizzazioniModule->enableCodeIstatRequired == true) ? ("
                return $('#" . Html::getInputId($this, 'profilo_enti_type_id') . "').val() == " . $typeMunicipalityId . " && " . !$disableFieldChecks ? 'true' : 'false' . ");") : ("return false;")) .
                "}"
            ];
            $rules[] = [[
                'profilo_enti_type_id',
            ], 'required'];
        }

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'id' => Module::t('amosorganizzazioni', 'ID'),
            'status' => AmosCommunity::t('amosorganizzazioni', 'Status'),
            'name' => Module::t('amosorganizzazioni', 'Denominazione'),
            'unique_secret_code' => Module::t('amosorganizzazioni', '#unique_secret_code'),
            'partita_iva' => Module::t('amosorganizzazioni', 'Partita Iva'),
            'codice_fiscale' => Module::t('amosorganizzazioni', 'Codice Fiscale'),
            'istat_code' => Module::t('amosorganizzazioni', 'Istat Code'),
            'presentazione_della_organizzaz' => Module::t('amosorganizzazioni', 'Presentazione'),
            'tipologia_di_organizzazione' => Module::t('amosorganizzazioni', 'Tipologia di organizzazione'),
            'forma_legale' => Module::t('amosorganizzazioni', 'Forma legale'),
            'sito_web' => Module::t('amosorganizzazioni', 'Sito web'),
            'facebook' => Module::t('amosorganizzazioni', 'Facebook'),
            'twitter' => Module::t('amosorganizzazioni', 'Twitter'),
            'linkedin' => Module::t('amosorganizzazioni', 'Linkedin'),
            'google' => Module::t('amosorganizzazioni', 'Google+'),
            'indirizzo' => Module::t('amosorganizzazioni', 'Indirizzo'),
            'addressField' => Module::t('amosorganizzazioni', 'Indirizzo'),
            'telefono' => Module::t('amosorganizzazioni', 'Telefono'),
            'fax' => Module::t('amosorganizzazioni', 'Fax'),
            'email' => Module::t('amosorganizzazioni', 'Email'),
            'pec' => Module::t('amosorganizzazioni', 'PEC'),
            'la_sede_legale_e_la_stessa_del' => Module::t('amosorganizzazioni', 'La sede legale è la stessa della sede operativa'),
            'sede_legale_indirizzo' => Module::t('amosorganizzazioni', '#sede_legale_indirizzo'),
            'sede_legale_telefono' => Module::t('amosorganizzazioni', '#sede_legale_telefono'),
            'sede_legale_fax' => Module::t('amosorganizzazioni', '#sede_legale_fax'),
            'sede_legale_email' => Module::t('amosorganizzazioni', '#sede_legale_email'),
            'sede_legale_pec' => Module::t('amosorganizzazioni', '#sede_legale_pec'),
            'responsabile' => Module::t('amosorganizzazioni', 'Responsabile'),
            'rappresentante_legale' => Module::t('amosorganizzazioni', 'Rappresentante legale'),
            'rappresentante_legale_text' => Module::t('amosorganizzazioni', 'Rappresentante legale'),
            'referente_operativo' => Module::t('amosorganizzazioni', 'Referente operativo'),
            'contatto_referente_operativo' => Module::t('amosorganizzazioni', 'Contatto referente operativo'),
            'parent_id' => Module::t('amosorganizzazioni', 'Membership organization'),
            'profilo_enti_type_id' => Module::t('amosorganizzazioni', 'Tipologia di ente'),
            'tipologia_struttura_id' => Module::t('amosorganizzazioni', 'Tipologia di Struttura'),
            'community_id' => Module::t('amosorganizzazioni', 'Community id'),
            'created_at' => Module::t('amosorganizzazioni', 'Creato il'),
            'updated_at' => Module::t('amosorganizzazioni', 'Aggiornato il'),
            'deleted_at' => Module::t('amosorganizzazioni', 'Cancellato il'),
            'created_by' => Module::t('amosorganizzazioni', 'Creato da'),
            'updated_by' => Module::t('amosorganizzazioni', 'Aggiornato da'),
            'deleted_by' => Module::t('amosorganizzazioni', 'Cancellato da'),
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRappresentanteLegale()
    {
        return $this->hasOne(AmosAdmin::instance()->model('UserProfile'), ['user_id' => 'rappresentante_legale']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReferenteOperativo()
    {
        return $this->hasOne(AmosAdmin::instance()->model('UserProfile'), ['user_id' => 'referente_operativo']);
    }

    /**
     * This method returns the organization referees user profiles.
     * @return array
     */
    public function getReferees()
    {
        $referees = [];
        if ($this->rappresentante_legale) {
            $rapprLeg = $this->rappresentanteLegale;
            if (!is_null($rapprLeg)) {
                $referees[] = $rapprLeg;
            }
        }
        if ($this->referente_operativo) {
            $refOp = $this->referenteOperativo;
            if (!is_null($refOp)) {
                $referees[] = $refOp;
            }
        }
        return $referees;
    }

    /**
     * This method returns the organization referees user ids.
     * @return array
     */
    public function getRefereesUserIds()
    {
        $referees = [];
        if ($this->rappresentante_legale) {
            $referees[] = $this->rappresentante_legale;
        }
        if ($this->referente_operativo) {
            $referees[] = $this->referente_operativo;
        }
        return $referees;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipologiaDiOrganizzazione()
    {
        return $this->hasOne($this->organizzazioniModule->model('ProfiloTypesPmi'), ['id' => 'tipologia_di_organizzazione']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFormaLegale()
    {
        return $this->hasOne($this->organizzazioniModule->model('ProfiloLegalForm'), ['id' => 'forma_legale']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne($this->organizzazioniModule->model('Profilo'), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany($this->organizzazioniModule->model('Profilo'), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfiloSedi()
    {
        return $this->hasMany($this->organizzazioniModule->model('ProfiloSedi'), ['profilo_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOtherHeadquarters()
    {
        return $this->getProfiloSedi()->andWhere(['is_main' => 0]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOtherActiveHeadquarters()
    {
        return $this->getOtherHeadquarters()->andWhere(['active' => 1]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfiloEntiType()
    {
        return $this->hasOne($this->organizzazioniModule->model('ProfiloEntiType'), ['id' => 'profilo_enti_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfiloUserMms()
    {
        return $this->hasMany($this->organizzazioniModule->model('ProfiloUserMm'), ['profilo_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfiloUsers()
    {
        return $this->hasMany(\open20\amos\core\user\User::className(), ['id' => 'user_id'])->via('profiloUserMms');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeesMms()
    {
        $refereesUserIds = [];
        if ($this->rappresentante_legale) {
            $refereesUserIds[] = $this->rappresentante_legale;
        }
        if ($this->referente_operativo) {
            $refereesUserIds[] = $this->referente_operativo;
        }
        /** @var ProfiloUserMm $profiloUserMmModel */
        $profiloUserMmModel = $this->organizzazioniModule->createModel('ProfiloUserMm');
        $profiloUserMmTable = $profiloUserMmModel::tableName();
        /** @var UserProfile $userProfileModel */
        $userProfileModel = AmosAdmin::instance()->createModel('UserProfile');
        $userProfileTable = $userProfileModel::tableName();
        $query = $this->getProfiloUserMms();
        $query->innerJoin($userProfileTable, $userProfileTable . '.user_id = ' . $profiloUserMmTable . '.user_id');
        $query->andWhere([$userProfileTable . '.deleted_at' => null]);
        $query->andWhere(['<>', $userProfileTable . '.nome', UserProfileUtility::DELETED_ACCOUNT_NAME]);
        if (!empty($refereesUserIds)) {
            $query->andWhere(['not in', \open20\amos\organizzazioni\models\ProfiloUserMm::tableName() . '.user_id', $refereesUserIds]);
        }
        return $query;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployees()
    {
        return $this->hasMany(\open20\amos\core\user\User::className(), ['id' => 'user_id'])->via('employeesMms');
    }

    /**
     * @inheritdoc
     */
    public function getCommunityId()
    {
        return $this->community_id;
    }

    /**
     * @inheritdoc
     */
    public function setCommunityId($communityId)
    {
        $this->community_id = $communityId;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCommunity()
    {
        $communityModule = AmosCommunity::instance();
        if (!is_null($communityModule)) {
            return $this->hasOne($communityModule->model('Community'), ['id' => 'community_id']);
        }
        return null;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCommunityUserMm()
    {
        $communityModule = AmosCommunity::instance();
        if (!is_null($communityModule)) {
            return $this->hasMany(CommunityUserMm::className(), ['community_id' => 'community_id']);
        }
        return null;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipologiaStruttura()
    {
        return $this->hasOne($this->organizzazioniModule->model('ProfiloTipoStruttura'), ['id' => 'tipologia_struttura_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfiloGroupsMms()
    {
        return $this->hasMany($this->organizzazioniModule->model('ProfiloGroupsMm'), ['profilo_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfiloGroups()
    {
        return $this->hasMany($this->organizzazioniModule->model('ProfiloGroups'), ['id' => 'profilo_group_id'])->via('profiloGroupsMms');
    }
}
