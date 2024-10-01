<?php

namespace open20\amos\collaborations\models\base;

use open20\amos\collaborations\Module;
use open20\amos\core\record\AmosRecordAudit;
use open20\amos\core\record\ContentModel;
use open20\amos\core\record\Record;
use Yii;

/**
 * This is the base-model class for table "collaboration_expressions_of_interest".
 *
 * @property integer $id
 * @property integer $collaboration_proposal_id
 * @property string $proposal_title
 * @property string $proposer_first_name
 * @property string $proposer_last_name
 * @property string $proposer_email
 * @property string $company
 * @property string $country
 * @property string $city
 * @property string $province
 * @property string $municipality
 * @property string $address
 * @property string $postal_code
 * @property string $manifestazioneinteresse
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 * @property integer $deleted_by
 * @property string $deleted_at
 *
 * @property-read \yii\db\ActiveQuery $collaborationProposals
 * @property \open20\amos\collaborations\models\CollaborationProposals $collaborationProposal
 */
class CollaborationExpressionsOfInterest extends AmosRecordAudit
{
    public $isSearch = false;
    public $collaborationProposalsModule = null;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->collaborationProposalsModule = Module::instance();
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'collaboration_expressions_of_interest';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[
                'collaboration_proposal_id', 'proposal_title', 'proposer_first_name', 'proposer_last_name', 'country',
                'proposer_email', 'proposer_phone', 'proposer_first_name', 'proposer_last_name',
            ], 'required'],
            [[
                'collaboration_proposal_id', 'created_by', 'updated_by', 'deleted_by', 'country','city', 'province',
            ], 'integer'],
            [[
                'created_at', 'updated_at', 'deleted_at','company','manifestazioneinteresse'
            ], 'safe'],
            
            [[
                'proposal_title', 'proposer_first_name', 'proposer_last_name', 'proposer_email', 'company',
                'proposer_phone', 'manifestazioneinteresse'
            ], 'string', 'max' => 256],
            ['proposer_email', 'email'],
            [[
                'collaboration_proposal_id'
            ], 'exist', 'skipOnError' => true, 'targetClass' => '\open20\amos\collaborations\models\CollaborationProposals', 'targetAttribute' => ['collaboration_proposal_id' => 'id']],
            [[
                'province', 'city', 'municipality', 'address', 'postal_code'
            ], 'required', 'when' => function ($model) {
                return $model->country == 1;
            }, 'whenClient' => "function (attribute, value) {
                        return $('#country').val() == 1;
            }"],
           
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amoscollaborations', 'ID'),
            'collaboration_proposal_id' => Yii::t('amoscollaborations', 'ID Proposta di collaborazione'),
            'proposal_title' => Yii::t('amoscollaborations', 'Titolo proposta'),
            'proposer_first_name' => Yii::t('amoscollaborations', 'Nome'),
            'proposer_last_name' => Yii::t('amoscollaborations', 'Cognome'),
            'proposer_email' => Yii::t('amoscollaborations', 'E-mail'),
            'proposer_phone' => Yii::t('amoscollaborations', 'Telefono'),
            'company' => Yii::t('amoscollaborations', 'Azienda / Ente'),
            'company' => Yii::t('amoscollaborations', 'Azienda / Ente'),
            'country' => Yii::t('amoscollaborations', 'Nazione'),
            'city' => Yii::t('amoscollaborations', 'Città'),
            'province' => Yii::t('amoscollaborations', 'Provincia'),
            'municipality' => Yii::t('amoscollaborations', 'Comune'),
            'address' => Yii::t('amoscollaborations', 'Indirizzo'),
            'postal_code' => Yii::t('amoscollaborations', 'CAP'),
            'manifestazioneinteresse' => Yii::t('amoscollaborations', 'Manifestazione di interesse'),
            'created_by' => Yii::t('amoscollaborations', 'Created By'),
            'created_at' => Yii::t('amoscollaborations', 'Created At'),
            'updated_by' => Yii::t('amoscollaborations', 'Updated By'),
            'updated_at' => Yii::t('amoscollaborations', 'Updated At'),
            'deleted_by' => Yii::t('amoscollaborations', 'Deleted By'),
            'deleted_at' => Yii::t('amoscollaborations', 'Deleted At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollaborationProposals()
    {
        return $this->hasOne(\open20\amos\collaborations\models\CollaborationProposals::className(), ['id' => 'collaboration_proposal_id']);
    }
}
