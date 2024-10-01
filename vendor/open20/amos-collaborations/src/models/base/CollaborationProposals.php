<?php

namespace open20\amos\collaborations\models\base;

use open20\amos\admin\AmosAdmin;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\core\record\ContentModel;
use open20\amos\tag\models\EntitysTagsMm;
use Yii;
use open20\amos\core\record\Record;
use yii\helpers\Url;

/**
 * This is the base-model class for table "collaboration_proposals".
 *
 * @property integer $id
 * @property string $title
 * @property integer $category_id
 * @property integer $collaboration_type_id
 * @property string $short_summary
 * @property string $full_description
 * @property string $advantages
 * @property string $technical_specifications
 * @property integer $stage_of_development_id
 * @property string $partner_role
 * @property string $caption
 * @property string $short_abstract
 * @property string $video_pitch
 * @property string $video_title
 * @property string $start_date
 * @property string $end_date
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 * @property integer $deleted_by
 * @property string $deleted_at
 *
 * @property \open20\amos\collaborations\models\CollaborationIprStatusMm[] $collaborationIprStatusMms
 * @property \open20\amos\collaborations\models\CollaborationMarketKeywordsMm[] $collaborationMarketKeywordsMms
 * @property \open20\amos\collaborations\models\CollaborationPartnershipTypeMm[] $collaborationPartnershipTypeMms
 * @property \open20\amos\collaborations\models\CollaborationStageOfDevelopment $stageOfDevelopment
 * @property \open20\amos\collaborations\models\CollaborationType $collaborationType
 * @property-read \yii\db\ActiveQuery $collaborationStageOfDevelopment
 * @property \open20\amos\collaborations\models\CollaborationTargetCountriesMm[] $collaborationTargetCountriesMms
 */
abstract class CollaborationProposals extends ContentModel
{
    public $isSearch = false;
    public $collaborationProposalsModule = null;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'collaboration_proposals';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[
                'title', 'category_id', 'collaboration_type_id', 'short_summary', 'full_description',
                'partner_role', 'start_date', 'end_date'
            ], 'required'],
            [[
                'category_id', 'collaboration_type_id', 'stage_of_development_id', 'created_by', 'updated_by', 'deleted_by',
                'visible_to_guest', 'in_evidence'
            ], 'integer'],
            [[
                'created_at', 'updated_at', 'deleted_at', 'ipr_status_mm', 'partnership_type_mm',
                'partner_type_and_size_mm', 'status', 'target_countries_mm', 'stage_of_development_id'
            ], 'safe'],
            [[
                'title', 'status'
            ], 'string', 'max' => 256],
            [[
                'caption', 'short_abstract'
            ], 'string', 'max' => 100],
            ['advantages', \open20\amos\core\validators\StringHtmlValidator::className(), 'max' => \open20\amos\collaborations\models\CollaborationProposals::ADVANTAGES_MAX_LENGTH],
            ['technical_specifications', \open20\amos\core\validators\StringHtmlValidator::className(), 'max' => \open20\amos\collaborations\models\CollaborationProposals::TECHNICAL_SPECIFICATIONS_MAX_LENGTH],
            ['short_summary', \open20\amos\core\validators\StringHtmlValidator::className(), 'max' => \open20\amos\collaborations\models\CollaborationProposals::SHORT_SUMMARY_MAX_LENGTH],
            ['full_description', \open20\amos\core\validators\StringHtmlValidator::className(), 'max' => \open20\amos\collaborations\models\CollaborationProposals::FULL_DESCRIPTION_MAX_LENGTH],
            ['partner_role', \open20\amos\core\validators\StringHtmlValidator::className(), 'max' => \open20\amos\collaborations\models\CollaborationProposals::PARTNER_ROLE_MAX_LENGTH],
            [[
                'video_pitch'
            ], 'url'],
            [[
                'video_title'
            ], 'string', 'max' => 250],
            [[
                'stage_of_development_id'
            ], 'exist', 'skipOnError' => true, 'targetClass' => CollaborationStageOfDevelopment::className(), 'targetAttribute' => ['stage_of_development_id' => 'id']],
            [[
                'collaboration_type_id'
            ], 'exist', 'skipOnError' => true, 'targetClass' => CollaborationType::className(), 'targetAttribute' => ['collaboration_type_id' => 'id']],
            ['advantages', 'advantagesValidator', 'skipOnEmpty' => false, 'skipOnError' => false],
            ['technical_specifications', 'technicalSpecificationsValidator', 'skipOnEmpty' => false, 'skipOnError' => false],
            ['video_title', 'videoTitleValidator', 'skipOnEmpty' => false, 'skipOnError' => false]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amoscollaborations', 'ID'),
            'title' => Yii::t('amoscollaborations', 'Titolo'),
            'status' => Yii::t('amoscollaborations', 'Stato'),
            'category_id' => Yii::t('amoscollaborations', 'Categoria'),
            'collaboration_type_id' => Yii::t('amoscollaborations', 'Tipo di collaborazione ricercata'),
            'short_summary' => Yii::t('amoscollaborations', 'Sintesi'),
            'full_description' => Yii::t('amoscollaborations', 'Descrizione'),
            'advantages' => Yii::t('amoscollaborations', 'Vantaggi / Aspetti innovativi'),
            'technical_specifications' => Yii::t('amoscollaborations', 'Specifiche tecniche/ competenze cercate'),
            'stage_of_development_id' => Yii::t('amoscollaborations', 'Stadio di sviluppo'),
            'partner_role' => Yii::t('amoscollaborations', 'Ruolo / Contributo del partner cercato'),
            'caption' => Yii::t('amoscollaborations', 'Didascalia immagine'),
            'short_abstract' => Yii::t('amoscollaborations', 'Sommario documento'),
            'video_pitch' => Yii::t('amoscollaborations', 'Presentazione video'),
            'video_title' => Yii::t('amoscollaborations', 'Titolo del video'),
            'start_date' => Yii::t('amoscollaborations', 'Data della proposta'),
            'end_date' => Yii::t('amoscollaborations', 'Data di scadenza'),
            'visible_to_guest' => Yii::t('amoscollaborations', 'Visibile a Guest'),
            'in_evidence' => Yii::t('amoscollaborations', 'In evidenza'),
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
    public function getCollaborationIprStatusMms()
    {
        return $this->hasMany(\open20\amos\collaborations\models\CollaborationIprStatusMm::className(), ['collaboration_proposals_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollaborationMarketKeywordsMms()
    {
        return $this->hasMany(\open20\amos\collaborations\models\CollaborationMarketKeywordsMm::className(), ['collaboration_proposals_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollaborationPartnershipTypeMms()
    {
        return $this->hasMany(\open20\amos\collaborations\models\CollaborationPartnershipTypeMm::className(), ['collaboration_proposals_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollaborationPartnerTypeAndSizeMms()
    {
        return $this->hasMany(\open20\amos\collaborations\models\CollaborationPartnerTypeAndSizeMm::className(), ['collaboration_proposals_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollaborationTechnologyKeywordsMms()
    {
        return $this->hasMany(\open20\amos\collaborations\models\CollaborationTechnologyKeywordsMm::className(), ['collaboration_proposals_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStageOfDevelopment()
    {
        return $this->hasOne(\open20\amos\collaborations\models\CollaborationStageOfDevelopment::className(), ['id' => 'stage_of_development_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollaborationStageOfDevelopment()
    {
        return $this->hasOne(\open20\amos\collaborations\models\CollaborationStageOfDevelopment::className(), ['id' => 'stage_of_development_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollaborationType()
    {
        return $this->hasOne(\open20\amos\collaborations\models\CollaborationType::className(), ['id' => 'collaboration_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollaborationTargetCountriesMms()
    {
        return $this->hasMany(\open20\amos\collaborations\models\CollaborationTargetCountriesMm::className(), ['collaboration_proposals_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntitysTagsMms()
    {
        return $this->hasMany(EntitysTagsMm::className(), ['record_id' => 'id']);
    }

    public function advantagesValidator()
    {
        if ($this->collaboration_type_id == 1 || $this->collaboration_type_id == 3 || $this->collaboration_type_id == 5) {
            if (empty($this->advantages)) {
                if (strpos(Url::current(), 'create')) {
                    Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('amoscore', 'Item not created, check data'));
                } else {
                    Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('amoscore', 'Item not updated, check data'));
                }
                $this->addError('advantages', \Yii::t('amoscollaborations', 'Vantaggi / Aspetti innovativi non può essere vuoto'));
            }
        }
    }

    public function technicalSpecificationsValidator()
    {
        if ($this->collaboration_type_id == 2 || $this->collaboration_type_id == 4 || $this->collaboration_type_id == 5) {
            if (empty($this->technical_specifications)) {
                if (strpos(Url::current(), 'create')) {
                    Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('amoscore', 'Item not created, check data'));
                } else {
                    Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('amoscore', 'Item not updated, check data'));
                }
                $this->addError('technical_specifications', \Yii::t('amoscollaborations', 'Specifiche tecniche / Competenze cercate non può essere vuoto'));
            }
        }
    }

    public function videoTitleValidator()
    {
        if (!empty($this->video_pitch)) {
            if (empty($this->video_title)) {
                if (strpos(Url::current(), 'create')) {
                    Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('amoscore', 'Item not created, check data'));
                } else {
                    Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('amoscore', 'Item not updated, check data'));
                }
                $this->addError('video_title', \Yii::t('amoscollaborations', 'Titolo del video non può essere vuoto se viene inserita una Presentazione video'));
            }
        }
    }
}
