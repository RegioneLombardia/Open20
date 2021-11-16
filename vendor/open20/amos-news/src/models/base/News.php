<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\models\base
 * @category   CategoryName
 */

namespace open20\amos\news\models\base;

use amos\sitemanagement\models\SiteManagementSlider;
use open20\amos\core\module\AmosModule;
use open20\amos\core\record\ContentModel;
use open20\amos\documenti\models\Documenti;
use open20\amos\news\AmosNews;
use open20\amos\news\models\NewsContentType;
use open20\agid\organizationalunit\models\AgidOrganizationalUnit;
use yii\helpers\ArrayHelper;
use open20\amos\admin\models\base\UserProfile;

/**
 * Class News
 *
 * This is the base-model class for table "news".
 *
 * @property integer $id
 * @property string $slug
 * @property string $titolo
 * @property string $sottotitolo
 * @property string $descrizione_breve
 * @property string $descrizione
 * @property string $metakey
 * @property string $metadesc
 * @property integer $primo_piano
 * @property integer $immagine
 * @property integer $hits
 * @property integer $abilita_pubblicazione
 * @property integer $in_evidenza
 * @property string $data_pubblicazione
 * @property string $data_rimozione
 * @property integer $news_categorie_id
 * @property string $status
 * @property integer $comments_enabled
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 * @property integer $version
 * @property string $date_news
 * @property string $news_expiration_date
 * @property integer $edited_by_agid_organizational_unit_id
 * @property integer $news_content_type_id
 * @property integer $news_groups_id
 *
 *
 * @property \open20\amos\news\models\NewsCategorie $newsCategorie
 * @property \open20\amos\upload\models\FilemanagerMediafile $immagineNews
 *
 * @package open20\amos\news\models\base
 */
abstract class News extends ContentModel
{
    public $news_agid_person_mm;
    public $news_related_documenti_mm;
    public $news_related_news_mm;
    public $news_related_agid_service_mm;
    
    /**
     * @var AmosNews|null $newsModule
     */
    public $newsModule = null;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->newsModule = AmosNews::instance();
        parent::init();
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = ArrayHelper::merge(parent::rules(), [
            [['descrizione', 'metakey', 'metadesc'], 'string'],
            [['primo_piano', 'immagine', 'hits', 'abilita_pubblicazione', 'in_evidenza', 'news_categorie_id', 'created_by', 'updated_by', 'deleted_by', 'version', 'comments_enabled', 'news_groups_id'], 'integer'],
            [['slug', 'data_pubblicazione', 'data_rimozione', 'created_at', 'updated_at', 'deleted_at', 'status', 'comments_enabled'], 'safe'],
            [['titolo', 'sottotitolo'], 'string', 'max' => 100],
            [['descrizione_breve'], 'string', 'max' => 250],
//            [['news_content_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => NewsContentType::className(), 'targetAttribute' => ['news_content_type_id' => 'id']],
//            [['edited_by_agid_organizational_unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => AgidOrganizationalUnit::className(), 'targetAttribute' => ['edited_by_agid_organizational_unit_id' => 'id']],
//            [['news_documento_id'], 'exist', 'skipOnError' => true, 'targetClass' => Documenti::className(), 'targetAttribute' => ['news_documento_id' => 'id']],
//            [['news_content_type_id','date_news', 'descrizione_breve'], 'required'],
//            [['newsImage'], 'file', 'extensions' => 'jpeg, jpg, png, gif', 'maxFiles' => 1],
//            /**
//             * SiteManagementSlider
//             * image slider
//             * video slider
//             */
//            [['image_site_management_slider_id'], 'exist', 'skipOnError' => true, 'targetClass' => SiteManagementSlider::className(), 'targetAttribute' => ['image_site_management_slider_id' => 'id']],
//            [['video_site_management_slider_id'], 'exist', 'skipOnError' => true, 'targetClass' => SiteManagementSlider::className(), 'targetAttribute' => ['video_site_management_slider_id' => 'id']],
        ]);
    
        if ($this->newsModule->enableAgid) {
            $rules[] = [['body_news'], 'string'];
            $rules[] = [['news_documento_id', 'edited_by_agid_organizational_unit_id', 'news_content_type_id'], 'integer'];
            $rules[] = [['date_news', 'news_expiration_date'], 'safe'];
            $rules[] = [['news_content_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => NewsContentType::className(), 'targetAttribute' => ['news_content_type_id' => 'id']];
            $rules[] = [['edited_by_agid_organizational_unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => AgidOrganizationalUnit::className(), 'targetAttribute' => ['edited_by_agid_organizational_unit_id' => 'id']];
            $rules[] = [['news_documento_id'], 'exist', 'skipOnError' => true, 'targetClass' => Documenti::className(), 'targetAttribute' => ['news_documento_id' => 'id']];
            $rules[] = [['news_content_type_id','date_news', 'descrizione_breve'], 'required'];
    
            /** @var \amos\sitemanagement\Module|AmosModule $siteManagementModule */
            $siteManagementModule = \Yii::$app->getModule('sitemanagement');
            if (!is_null($siteManagementModule)) {
                /**
                 * SiteManagementSlider
                 * image slider
                 * video slider
                 */
                $rules[] = [['image_site_management_slider_id'], 'exist', 'skipOnError' => true, 'targetClass' => SiteManagementSlider::className(), 'targetAttribute' => ['image_site_management_slider_id' => 'id']];
                $rules[] = [['video_site_management_slider_id'], 'exist', 'skipOnError' => true, 'targetClass' => SiteManagementSlider::className(), 'targetAttribute' => ['video_site_management_slider_id' => 'id']];
            }
        }
        
        return $rules;
    }
    
    /**
     * @inheritdoc
     */
    public function afterValidate()
    {
        if ($this->newsModule->enableAgid) {
            // caso update del model news
            if (null != $this->id) {
        
                // controllo se il model ha gia l'immagine
                $news_image = (new \yii\db\Query())
                    ->from('attach_file')
                    ->where(['LIKE', 'model', 'open20\amos\news\models\News'])
                    ->andWhere([
                        'itemId' => $this->id
                    ])
                    ->one();
        
                // se il model new non ha l'immagine salvata controllo se è stato caricato in post
                if (null == $news_image) {
            
                    // non esiste in post
                    if (null == \Yii::$app->request->post('newsImage_data', '')) {
                        $this->addError("newsImage");
                
                        return false;
                    }
                }
        
                return true;
        
            } else {
                // caso create del model news
        
                // controllo se esiste l'immagine del model news in post
                if ((null == \Yii::$app->request->post('newsImage_data', '') && null == $this->id)) {
                    $this->addError("newsImage");
                    return false;
                }
        
                return true;
            }
        }
    }

    /**
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(),
            [
                'id' => AmosNews::t('amosnews', 'Id'),
                'titolo' => AmosNews::t('amosnews', '#title_field'),
                'sottotitolo' => AmosNews::t('amosnews', '#subtitle_field'),
                'descrizione_breve' => AmosNews::t('amosnews', '#abstract_field'),
                'descrizione' => AmosNews::t('amosnews', '#description_field'),
                'metakey' => AmosNews::t('amosnews', 'Meta key'),
                'metadesc' => AmosNews::t('amosnews', 'Meta descrizione'),
                'primo_piano' => AmosNews::t('amosnews', 'Pubblica sul sito'),
                'in_evidenza' => AmosNews::t('amosnews', 'In evidenza'),
                'hits' => AmosNews::t('amosnews', 'Visualizzazioni'),
                'abilita_pubblicazione' => AmosNews::t('amosnews', 'Abilita pubblicazione'),
                'data_pubblicazione' => AmosNews::t('amosnews', '#start_publication_date'),
                'data_rimozione' => AmosNews::t('amosnews', '#end_publication_date'),
                'news_categorie_id' => AmosNews::t('amosnews', 'Categoria'),
                'status' => AmosNews::t('amosnews', 'Stato'),
                'comments_enabled' => AmosNews::t('amosnews', '#comments_enabled'),
                'created_at' => AmosNews::t('amosnews', 'Creato il'),
                'updated_at' => AmosNews::t('amosnews', 'Aggiornato il'),
                'deleted_at' => AmosNews::t('amosnews', 'Cancellato il'),
                'created_by' => AmosNews::t('amosnews', 'Creato da'),
                'updated_by' => AmosNews::t('amosnews', 'Aggiornato da'),
                'deleted_by' => AmosNews::t('amosnews', 'Cancellato da'),
                'version' => AmosNews::t('amosnews', 'Versione numero'),
                "news_content_type_id" => AmosNews::t('amosnews', 'Content Type'),
                "edited_by_agid_organizational_unit_id" => AmosNews::t('amosnews', 'A cura di'),
                "date_news" => AmosNews::t('amosnews', 'Data della news'),
                "news_expiration_date" => AmosNews::t('amosnews', 'Data di scadenza'),
                "body_news" => AmosNews::t('amosnews', 'Corpo della news'),
                "news_related_documenti_mm" => AmosNews::t('amosnews', 'Correlati: documenti'),
                "news_related_news_mm" => AmosNews::t('amosnews', 'Correlati: novità'),
                "news_related_agid_service_mm" => AmosNews::t('amosnews', 'Correlati: servizi'),
                "news_documento_id" => AmosNews::t('amosnews', 'Documenti allegati'),
                'video_site_management_slider_id' => AmosNews::t('project_cards', 'video_site_management_slider_id'),
                'image_site_management_slider_id' => AmosNews::t('project_cards', 'image_site_management_slider_id'),
            ]
        );
    }

    /**
     * @return mixed
     */
    public function attributeHints()
    {
        return ArrayHelper::merge(parent::attributeHints(),
            [
                'id' => AmosNews::t('amosnews', ''),
                'titolo' => AmosNews::t('amosnews', '#title_field_hint'),
                'sottotitolo' => AmosNews::t('amosnews', '#subtitle_field_hint'),
                'descrizione_breve' => AmosNews::t('amosnews', '#abstract_field_hint'),
                'descrizione' => AmosNews::t('amosnews', ''),
                'metakey' => AmosNews::t('amosnews', ''),
                'metadesc' => AmosNews::t('amosnews', ''),
                'primo_piano' => AmosNews::t('amosnews', ''),
                'in_evidenza' => AmosNews::t('amosnews', ''),
                'hits' => AmosNews::t('amosnews', ''),
                'abilita_pubblicazione' => AmosNews::t('amosnews', ''),
                'data_pubblicazione' => AmosNews::t('amosnews', '#start_publication_date_hint'),
                'data_rimozione' => AmosNews::t('amosnews', '#end_publication_date_hint'),
                'news_categorie_id' => AmosNews::t('amosnews', ''),
                'status' => AmosNews::t('amosnews', ''),
                'comments_enabled' => AmosNews::t('amosnews', ''),
                'created_at' => AmosNews::t('amosnews', ''),
                'updated_at' => AmosNews::t('amosnews', ''),
                'deleted_at' => AmosNews::t('amosnews', ''),
                'created_by' => AmosNews::t('amosnews', ''),
                'updated_by' => AmosNews::t('amosnews', ''),
                'deleted_by' => AmosNews::t('amosnews', ''),
                'version' => AmosNews::t('amosnews', ''),
                "news_content_type_id" => AmosNews::t('amosnews', 'news_content_type_id'),
                "edited_by_agid_organizational_unit_id" => AmosNews::t('amosnews', 'edited_by_agid_organizational_unit_id'),
                "date_news" => AmosNews::t('amosnews', 'Data della news'),
                "news_expiration_date" => AmosNews::t('amosnews', 'Data di scadenza'),
                "body_news" => AmosNews::t('amosnews', 'Corpo della news'),
                "news_related_documenti_mm" => AmosNews::t('amosnews', 'Correlati: documenti'),
                "news_related_news_mm" => AmosNews::t('amosnews', 'Correlati: novità'),
                "news_related_agid_service_mm" => AmosNews::t('amosnews', 'Correlati: servizi'),
                "news_documento_id" => AmosNews::t('amosnews', 'Documenti allegati'),
            ]
        );
    }


    /**
     * Validation of $attribute if the attribute publication date of the module is true
     * @param string $attribute
     * @param array $params
     */
    public function checkDate($attribute, $params)
    {
        $isValid = true;
        if ($this->isNewRecord && \Yii::$app->getModule('news')->validatePublicationDate == true) {
            if ($this->$attribute < date('Y-m-d')) {
                $isValid = false;
            }
        }
        if (!$isValid) {
            $this->addError($attribute, $this->getAttributeLabel($attribute) . AmosNews::t('amosnews', ' non può essere inferiore alla data odierna'));
        }
    }

    /**
     * This is the relation between the news and the related category.
     * Return an ActiveQuery related to NewsCategorie model.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNewsCategorie()
    {
        return $this->hasOne(\open20\amos\news\models\NewsCategorie::className(), ['id' => 'news_categorie_id']);
    }
    
    /**
     * This is the relation between the news and the single related picture.
     * Return an ActiveQuery related to FilemanagerMediafile model.
     *
     * @return \yii\db\ActiveQuery
     * @deprecated since version 1.5
     */
    public function getImmagineNews()
    {
        return $this->hasOne(\open20\amos\upload\models\FilemanagerMediafile::className(), ['id' => 'immagine']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNewsContentType(){

        return $this->hasOne(\open20\amos\news\models\NewsContentType::className(), ['id' => 'news_content_type_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNewsGroups(){

        return $this->hasOne(\open20\amos\news\models\NewsGroups::className(), ['id' => 'news_groups_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEditedByAgidOrganizationalUnit(){

        return $this->hasOne(\open20\agid\organizationalunit\models\AgidOrganizationalUnit::className(), ['id' => 'edited_by_agid_organizational_unit_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNewsDocumento(){

        return $this->hasOne(\open20\amos\documenti\models\Documenti::className(), ['id' => 'news_documento_id']);
    }

    /**
     * news agid person
     */
    public function getNewsAgidPersonMm(){
        return $this->hasMany(\open20\amos\news\models\NewsAgidPersonMm::className(), ['news_id' => 'id']);
    }

    /**
     * news related documenti
     */
    public function getNewsRelatedDocumentiMm(){
        return $this->hasMany(\open20\amos\news\models\NewsRelatedDocumentiMm::className(), ['news_id' => 'id']);
    }

    /**
     * news related news
     */
    public function getNewsRelatedNewsMm(){
        return $this->hasMany(\open20\amos\news\models\NewsRelatedNewsMm::className(), ['news_id' => 'id']);
    }

    /**
     * news related agid service
     */
    public function getNewsRelatedAgidServiceMm(){
        return $this->hasMany(\open20\amos\news\models\NewsRelatedAgidServiceMm::className(), ['news_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery|null
     */
    public function getSliderImage()
    {
        /** @var \amos\sitemanagement\Module|AmosModule $siteManagementModule */
        $siteManagementModule = \Yii::$app->getModule('sitemanagement');
        if (is_null($siteManagementModule)) {
            return null;
        }
        return $this->hasOne(\amos\sitemanagement\models\SiteManagementSlider::className(), ['id' => 'image_site_management_slider_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery|null
     */
    public function getSliderVideo()
    {
        /** @var \amos\sitemanagement\Module|AmosModule $siteManagementModule */
        $siteManagementModule = \Yii::$app->getModule('sitemanagement');
        if (is_null($siteManagementModule)) {
            return null;
        }
        return $this->hasOne(\amos\sitemanagement\models\SiteManagementSlider::className(), ['id' => 'video_site_management_slider_id']);
    }

    /**
     * Method to return UserProfile by user_id
     *
     * @param int $id
     * @return void
     */
    public function getUserProfileByUserId($id = null){

        return UserProfile::find()->andWhere(['user_id' => $id])->one();
    }

    /**
     * Method to get all workflow status for model
     *
     * @return array
     */
    public function getAllWorkflowStatus(){

        return ArrayHelper::map(
                ArrayHelper::getColumn(
                    (new \yii\db\Query())->from('sw_status')
                    ->where(['workflow_id' => $this::NEWS_WORKFLOW])
                    ->orderBy(['sort_order' => SORT_ASC])
                    ->all(),

                    function ($element) {
                        $array['status'] = $element['workflow_id'] . "/" . $element['id'];
                        $array['label'] = $element['label'];
                        return $array;
                    }
                ),
            'status', 'label');
    }
}
