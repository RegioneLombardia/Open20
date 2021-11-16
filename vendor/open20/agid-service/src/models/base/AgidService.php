<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\agid\service\models\base
 * @category   CategoryName
 */

namespace open20\agid\service\models\base;

use open20\amos\core\record\ContentModel;
use open20\agid\service\models\AgidServiceContentType;
use open20\agid\service\models\AgidServiceDocumentiMm;
use open20\agid\service\models\AgidServiceStatus;
use open20\agid\service\models\AgidServiceType;
use open20\agid\service\Module;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use \open20\amos\news\models\News;
use \open20\amos\news\models\NewsRelatedAgidServiceMm;
use open20\agid\organizationalunit\models\AgidOrganizationalUnit;
use open20\agid\service\models\AgidService as AgidServiceModel;
use open20\amos\documenti\models\Documenti;

/**
 * Class AgidService
 *
 * This is the base-model class for table "agid_service".
 *
 * @property integer $id
 * @property integer $agid_content_type_service_id
 * @property integer $agid_service_type_id
 * @property integer $agid_service_status_id
 * @property integer $agid_uo_manager_id
 * @property integer $agid_uo_area_id
 * @property string $name
 * @property string $service_status_motivation
 * @property string $subtitle
 * @property string $description
 * @property string $long_description
 * @property string $recipients_description
 * @property string $persons_apply
 * @property string $geographical_apply
 * @property string $procedure_apply
 * @property string $output
 * @property string $outcome_procedure_apply
 * @property string $digital_channel_url
 * @property string $authentication_way
 * @property string $physical_channel
 * @property string $physical_channel_reservation
 * @property string $instructions
 * @property string $costs
 * @property string $constrains
 * @property string $phases_deadline
 * @property string $special_case
 * @property string $external_links
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property AgidServiceContentType $agidServiceContentType
 * @property AgidServiceStatus $agidServiceStatus
 * @property AgidServiceType $agidServiceType
 *
 * @package open20\agid\service\models\base
 */
abstract class AgidService extends ContentModel implements \open20\amos\seo\interfaces\SeoModelInterface,
 \open20\amos\core\interfaces\ContentModelInterface
{
    public $isSearch = false;
    public $documenti;
    public $agid_service_related_service_mm;
    public $agid_service_organizational_unit_mm;
    public $updated_from;
    public $updated_to;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'agid_service';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['agid_content_type_service_id', 'agid_service_type_id', 'agid_service_status_id', 'agid_uo_manager_id', 'agid_uo_area_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['service_status_motivation', 'subtitle', 'further_information', 'long_description', 'recipients_description', 'persons_apply', 'geographical_apply', 'procedure_apply', 'output', 'outcome_procedure_apply', 'digital_channel_url', 'authentication_way', 'physical_channel', 'physical_channel_reservation', 'instructions', 'costs', 'constrains', 'phases_deadline', 'special_case', 'external_links'], 'string'],
            [['created_at', 'updated_at', 'deleted_at', 'documenti'], 'safe'],
            [['name', 'status'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 160],
            [['agid_content_type_service_id'], 'exist', 'skipOnError' => true, 'targetClass' => AgidServiceContentType::className(), 'targetAttribute' => ['agid_content_type_service_id' => 'id']],
            [['agid_service_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => AgidServiceStatus::className(), 'targetAttribute' => ['agid_service_status_id' => 'id']],
            [['agid_service_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => AgidServiceType::className(), 'targetAttribute' => ['agid_service_type_id' => 'id']],
            [['agid_service_type_id', 'agid_content_type_service_id', 'name', 'description', 'long_description', 'recipients_description'], 'required'],
            [['digital_channel_url'], 'url'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('amosservice', 'ID'),
            'documenti' => Module::t('amosservice', 'Documenti'),
            'agid_content_type_service_id' => Module::t('amosservice', '#content_type_typology'),
            'agid_service_type_id' => Module::t('amosservice', '#service_type'),
            'agid_service_status_id' => Module::t('amosservice', '#service_status'),
            'agid_uo_manager_id' => Module::t('amosservice', 'Uff. Responsabile'),
            'agid_uo_area_id' => Module::t('amosservice', 'Area'),
            'name' => Module::t('amosservice', '#title'),
            'service_status_motivation' => Module::t('amosservice', 'Motivo dello stato'),
            'subtitle' => Module::t('amosservice', 'Titolo alternativo/Sottotitolo'),
            'description' => Module::t('amosservice', 'Descrizione (abstract)'),
            'long_description' => Module::t('amosservice', '#long_description'),
            'recipients_description' => Module::t('amosservice', '#recipients_description'),
            'further_information' => Module::t('amosservice', '#further_information'),
            'persons_apply' => Module::t('amosservice', 'Chi può presentare'),
            'geographical_apply' => Module::t('amosservice', 'Copertura geografica'),
            'procedure_apply' => Module::t('amosservice', 'Come si fa'),
            'output' => Module::t('amosservice', 'Output/Cosa si ottiene'),
            'outcome_procedure_apply' => Module::t('amosservice', 'Procedure collegate all\'esito'),
            'digital_channel_url' => Module::t('amosservice', 'Canale digitale'),
            'authentication_way' => Module::t('amosservice', 'Autenticazione'),
            'physical_channel' => Module::t('amosservice', 'Canale fisico'),
            'physical_channel_reservation' => Module::t('amosservice', 'Canale fisico - prenotazione'),
            'instructions' => Module::t('amosservice', 'Cosa serve (istruzioni per partecipare al servizio)'),
            'costs' => Module::t('amosservice', 'Costi'),
            'constrains' => Module::t('amosservice', 'Vincoli'),
            'phases_deadline' => Module::t('amosservice', 'Fasi e scadenze'),
            'special_case' => Module::t('amosservice', 'Casi particolari'),
            'external_links' => Module::t('amosservice', 'Link a siti esterni'),
            'status' => Module::t('amosservice', 'Stato'),
            'created_at' => Module::t('amosservice', 'Created at'),
            'updated_at' => Module::t('amosservice', 'Updated at'),
            'deleted_at' => Module::t('amosservice', 'Deleted at'),
            'created_by' => Module::t('amosservice', 'Created by'),
            'updated_by' => Module::t('amosservice', 'Updated by'),
            'deleted_by' => Module::t('amosservice', 'Deleted by'),
            'agid_service_related_service_mm' => Module::t('amosservice', '#agid_service_related_service_mm'),
            'updated_from' => Module::t('amosservice', '#updated_from'),
            'updated_to' => Module::t('amosservice', '#updated_to')

        ];
    }
    
    /**
     * @return ActiveQuery
     */
    public function getAgidServiceContentType()
    {
        return $this->hasOne(AgidServiceContentType::className(), ['id' => 'agid_content_type_service_id']);
    }
    
    /**
     * @return ActiveQuery
     */
    public function getAgidServiceStatus()
    {
        return $this->hasOne(AgidServiceStatus::className(), ['id' => 'agid_service_status_id']);
    }
    
    /**
     * @return ActiveQuery
     */
    public function getAgidServiceType()
    {
        return $this->hasOne(AgidServiceType::className(), ['id' => 'agid_service_type_id']);
    }
    
    /**
     * @return ActiveQuery
     */
    public function getAgidUoManager()
    {
        return $this->hasOne(\open20\agid\organizationalunit\models\AgidOrganizationalUnit::className(), ['id' => 'agid_uo_manager_id']);
    }
    
    /**
     * @return ActiveQuery
     */
    public function getAgidUoArea()
    {
        return $this->hasOne(\open20\agid\organizationalunit\models\AgidOrganizationalUnit::className(), ['id' => 'agid_uo_area_id']);
    }
    
    /**
     * @return ActiveQuery
     */
    public function getAgidServiceDocumentiMms()
    {
        return $this->hasMany(AgidServiceDocumentiMm::className(), ['agid_service_id' => 'id']);
    }

    
    /**
     * @return ActiveQuery
     */
    public function getAgidServiceRelatedServiceMm(){

        return $this->hasMany(\open20\agid\service\models\AgidServiceRelatedServiceMm::className(), ['agid_service_id' => 'id']);
    }


    /**
     * Method for extracting associations between agid services and agid organizational units
     *
     * @return model | AgidServiceOrganizationalUnitMm
     */
    public function getAgidServiceOrganizationalUnitMm(){
        
        return $this->hasMany(\open20\agid\service\models\AgidServiceOrganizationalUnitMm::className(), ['agid_service_id' => 'id']);
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
                    ->where(['workflow_id' => $this::AGID_SERVICE_WORKFLOW])
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

    
    /**
     * Method to get all related news validated
     *
     * @return array News $news_related
     */
     public function getNewsRelated(){

        // get all news_id from NewsRelatedAgidServiceMm
        $news_id_related_agid_service = ArrayHelper::getColumn(
            NewsRelatedAgidServiceMm::find()
                ->select('news_id')
                ->andWhere(['related_agid_service_id' => $this->id])
                ->asArray()
                ->all(),

            function ($element) {
                return $element['news_id'];
            }
        );

        // get all news related with AgidService, validated and not deleted 
        return $news_related = News::find()
                                ->andWhere(['id' => $news_id_related_agid_service])
                                ->andWhere(['status' => News::NEWS_WORKFLOW_STATUS_VALIDATO])
                                ->andWhere(['deleted_at' => null])
                                ->all();

    }

    /**
     * Method to get all validated AgidOrganizationalUnit associated to AgidService
     *
     * @return void
     */
    public function getAgidServiceOrganizationalUnitValidated(){

        // get all agid_organizational_unit_id AgidServiceOrganizationalUnitMm 
        $agid_organizational_unit_id = ArrayHelper::getColumn(
            $this->getAgidServiceOrganizationalUnitMm()
                    ->asArray()
                    ->all(),

            function ($element) {
                return $element['agid_organizational_unit_id'];
            }
        );

        return $agid_organizational_unit = AgidOrganizationalUnit::find()
                                    ->andWhere(['id' => $agid_organizational_unit_id])
                                    ->andWhere(['status' => AgidOrganizationalUnit::AGID_ORGANIZATIONAL_UNIT_WORKFLOW_STATUS_VALIDATED])
                                    ->andWhere(['deleted_at' => null])
                                    ->all();


    }


    /**
     * Method to get all agid service related service
     *
     * @param boolean $only_validated
     * @return array | model | AgidService
     */
    public function getAgidServiceRelatedService($only_validated = true){

        $agid_related_service_id = ArrayHelper::getColumn(
            $this->agidServiceRelatedServiceMm,

            function ($element) {
                return $element['agid_related_service_id'];
            }
        );

        $agid_service_related_service = AgidServiceModel::find()
                                    ->andWhere([ 'id' => $agid_related_service_id ]);
                               

        if($only_validated){

            $agid_service_related_service = $agid_service_related_service->andWhere([ 'status' => $this::AGID_SERVICE_STATUS_VALIDATED ]);
        }

        return $agid_service_related_service = $agid_service_related_service->andWhere([ 'deleted_at' => null ])->all();

    }


    /**
     * Method to obtain all documents associated with AgidService
     *
     * @param boolean $only_validated
     * @return array | model | Documenti
     */
    public function getAgidServiceDocumenti($only_validated = true){

        $agid_service_doumenti_mm = ArrayHelper::getColumn(
            $this->agidServiceDocumentiMms,

            function ($element) {
                return $element['documenti_id'];
            }
        );

        $agid_service_documenti = Documenti::find()
                                    ->andWhere([ 'id' => $agid_service_doumenti_mm ]);

        if($only_validated){
            $agid_service_documenti = $agid_service_documenti->andWhere(['status' => Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO]);
        }

        return $agid_service_documenti = $agid_service_documenti->andWhere([ 'deleted_at' => null ])->all();
        
    }

}
