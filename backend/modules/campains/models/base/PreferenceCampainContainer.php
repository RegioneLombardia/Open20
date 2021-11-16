<?php

namespace backend\modules\campains\models\base;

use Yii;
use yii\helpers\ArrayHelper;
use backend\modules\campains\Module;
use backend\modules\landings\models\PreferenceLanding;
use open20\amos\attachments\behaviors\FileBehavior;
use yii\helpers\VarDumper;
use open20\amos\core\validators\StringHtmlValidator;

/**
 * This is the base-model class for table "preference_campain_container".
 *
 * @property integer $id
 * @property string $separator_title
 * @property string $content_image_path
 * @property string $content_align
 * @property integer $content_highlight_flag
 * @property string $content_title
 * @property string $content_text
 * @property string $content_news_link
 * @property string $content_facebook_link
 * @property string $content_twitter_link
 * @property string $content_linkedin_link
 * @property string $content_image_alt
 * @property string $content_news_link_title
 * @property string $content_news_link_scope
 * @property integer $preference_campain_section_id
 * @property integer $preference_campain_container_type_id
 * @property integer $preference_landing_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 * @property integer $social_sharing
 *
 * @property \backend\modules\campains\models\PreferenceCampainContainerType $preferenceCampainContainerType
 * @property \backend\modules\campains\models\PreferenceCampainSection $preferenceCampainSection
 */
class PreferenceCampainContainer extends \open20\amos\core\record\Record {

    public $isSearch = false;
    public $preference_campain_id;
    public $content_to_insert, $external_source, $type_content_align, $external_source_category;

    const SCENARIO_DEFAULT = 'default';

    const SCENARIO_CONTENUTO = 'contenuto';
    const SCENARIO_CONTENUTO_ESTERNO = 'contenuto_esterno';
    const SCENARIO_SEPARATORE = 'separatore';


    public function scenarios()
    {
        return [

            self::SCENARIO_DEFAULT => ['content_highlight_flag', 'preference_campain_section_id', 'preference_campain_container_type_id', 
                                        'content_text', 'separator_title', 'content_image_path', 'content_align', 
                                        'content_title', 'content_news_link', 'content_facebook_link', 'content_twitter_link',
                                        'content_linkedin_link', 'preference_campain_container_type_id', 'preference_campain_section_id', 'preference_landing_id',
                                        'social_sharing' ],

            self::SCENARIO_CONTENUTO => ['content_image_path', 'content_align', 'preference_campain_container_type_id',
                                            'preference_campain_section_id', 'content_title', 'content_text', 'content_news_link',
                                            'content_facebook_link', 'content_linkedin_link', 'content_twitter_link', 'preference_landing_id',
                                            'social_sharing'
                                        ],

            self::SCENARIO_CONTENUTO_ESTERNO => [
                /*'separator_title',*/ 'preference_campain_container_type_id', 'preference_campain_section_id', 
                'content_title', 'content_text', 'content_align',
                'content_news_link', 'content_facebook_link', 'content_linkedin_link', 'content_twitter_link',
                'social_sharing'
            ],

            self::SCENARIO_SEPARATORE => [
                'separator_title', 'preference_campain_section_id', 'preference_campain_container_type_id'
            ]
        ];
    }


    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'preference_campain_container';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['content_highlight_flag', 'preference_campain_section_id', 'preference_campain_container_type_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['content_text','content_image_alt', 'content_news_link_title', 'content_news_link_scope'], 'string'],
           // [['content_news_link', 'content_title' ], 'required', 'on' => self::SCENARIO_CONTENUTO_ESTERNO],
            [['separator_title'], 'required', 'on' => self::SCENARIO_SEPARATORE],

            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['separator_title', 'content_image_path', 'content_align', 'content_title', /*'content_news_link',*/ 'content_facebook_link', 'content_twitter_link', 'content_linkedin_link'], 'string', 'max' => 255],
            [['preference_campain_container_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => PreferenceCampainContainerType::className(), 'targetAttribute' => ['preference_campain_container_type_id' => 'id']],
            [['preference_campain_section_id'], 'exist', 'skipOnError' => true, 'targetClass' => PreferenceCampainSection::className(), 'targetAttribute' => ['preference_campain_section_id' => 'id']],
            [['preference_landing_id'],'exist', 'skipOnError' => true, 'targetClass' => PreferenceLanding::className(), 'targetAttribute' => ['preference_landing_id' => 'id']],

            [['preference_campain_section_id', 'preference_campain_container_type_id'], 'required'],

            //  TODO aggiungere la validazione degli url lato client
            [['content_news_link', 'content_linkedin_link', 'content_facebook_link', 'content_twitter_link'],'url', 'defaultScheme' => 'https'],
            [['contentImage'], 'file', 'extensions' => 'jpeg, jpg, png, gif'],
            [['social_sharing'], 'safe', 'on' => 'checked'],
            // limiti di caratteri
            [['content_text'], StringHtmlValidator::className(), 'max' => 425],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Module::t('campains', 'ID'),
            'separator_title' => Module::t('campains', 'Titolo Separatore'),
            'content_image_path' => Module::t('campains', 'Content image path'),
            'content_align' => Module::t('campains', 'Content align'),
            'content_highlight_flag' => Module::t('campains', 'Content highlight flag'),
            'content_title' => Module::t('campains', 'Titolo'),
            'content_text' => Module::t('campains', 'Testo (abstract)'),
            'content_news_link' => Module::t('campains', 'Content news link'),
            'content_facebook_link' => Module::t('campains', 'Content facebook link'),
            'content_twitter_link' => Module::t('campains', 'Content twitter link'),
            'content_linkedin_link' => Module::t('campains', 'Content linkedIn link'),
            'social_sharing' => Module::t('campains', 'Abilita la condivisione del contenuto su Facebook, Twitter, Linkedin'),
            'preference_campain_section_id' => Module::t('campains', 'Preference campain section'),
            'preference_campain_container_type_id' => Module::t('campains', 'Preference campain section type'),
            'preference_landing_id' => Module::t('campains', 'Preference Landing'),
            'created_at' => Module::t('campains', 'Created at'),
            'updated_at' => Module::t('campains', 'Updated at'),
            'deleted_at' => Module::t('campains', 'Deleted at'),
            'created_by' => Module::t('campains', 'Created by'),
            'updated_by' => Module::t('campains', 'Updated by'),
            'deleted_by' => Module::t('campains', 'Deleted by'),
        ];
    }

    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'fileBehavior' => [
                    'class' => FileBehavior::className()
                ],
            ]
        );
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPreferenceCampainContainerType() {
        return $this->hasOne(\backend\modules\campains\models\PreferenceCampainContainerType::className(), ['id' => 'preference_campain_container_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPreferenceCampainSection() {
        return $this->hasOne(\backend\modules\campains\models\PreferenceCampainSection::className(), ['id' => 'preference_campain_section_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPreferenceLanding() {
        return $this->hasOne(\backend\modules\landings\models\PreferenceLanding::className(), ['id' => 'preference_landing_id']);
    }

    public function getContentLink()
    {
        if (!empty($this->preference_landing_id)) {
            /** @var PreferenceLanding $landing */
            $landing = $this->preferenceLanding;
            return \backend\modules\landings\utility\PreferenceLandingUtility::getUrlLanding($this->preferenceLanding);
        } else {
            return $this->content_news_link;
        }
        return null;
    }
}
