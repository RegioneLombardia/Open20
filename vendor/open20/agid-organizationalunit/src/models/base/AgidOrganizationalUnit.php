<?php

namespace open20\agid\organizationalunit\models\base;

use Yii;
use yii\helpers\ArrayHelper;
use \open20\agid\person\models\AgidPerson;
use open20\agid\organizationalunit\Module;
use open20\agid\organizationalunit\models\AgidOrganizationalUnitServiceMm;
use open20\agid\organizationalunit\models\AgidOrganizationalOrganizationalUnitMm;
use open20\amos\attachments\behaviors\FileBehavior;
use open20\amos\documenti\models\Documenti;
use open20\agid\organizationalunit\models\AgidOrganizationalUnit as AgidOrganizationalUnitModel;

use open20\agid\service\models\AgidService;

/**
 * This is the base-model class for table "agid_organizational_unit".
 *
 * @property integer $id
 * @property integer $agid_organizational_unit_content_type_id
 * @property integer $agid_organizational_unit_type_id
 * @property string $name
 * @property string $short_description
 * @property string $skills
 * @property string $headquarters_name
 * @property string $address
 * @property string $public_hours
 * @property string $cap
 * @property string $telephone_reference
 * @property string $mail_reference
 * @property string $pec_reference
 * @property string $further_information
 * @property string $help_box
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 * @property integer $agid_person_president_id
 * @property integer $agid_person_vice_president_id
 *
 * @property \open20\agid\organizationalunit\models\AgidOrganizationalUnitType $agidOrganizationalUnitType
 */
abstract class AgidOrganizationalUnit extends \open20\amos\core\record\ContentModel implements \open20\amos\seo\interfaces\SeoModelInterface,
 \open20\amos\core\interfaces\ContentModelInterface
{
    public $isSearch = false;
    public $agid_organizational_organizational_unit_mm;
    public $updated_from;
    public $updated_to;
    
    // TODO remove
    public $attachments;
    public $agid_organizational_unit_documenti_mm;
    public $agid_organizational_unit_service_mm;
    private $logo_image;


	/**
	 * @inheritdoc
	 */
    public static function tableName()
    {
        return 'agid_organizational_unit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['agid_person_president_id', 'agid_person_vice_president_id', 'agid_organizational_unit_content_type_id', 'agid_organizational_unit_type_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['short_description', 'skills', 'headquarters_name', 'address', 'public_hours', 'telephone_reference', 'mail_reference', 'pec_reference', 'further_information', 'help_box', 'website_url'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['name', 'status'], 'string', 'max' => 255],
            [['cap'], 'string', 'min' => 5, 'max' => 5],
            [['telephone_reference'], 'string', 'max' => 25],
            [['agid_organizational_unit_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => AgidOrganizationalUnitType::className(), 'targetAttribute' => ['agid_organizational_unit_type_id' => 'id']],
            [['agid_organizational_unit_type_id', 'agid_organizational_unit_content_type_id', 'name', 'short_description', 'skills' ], 'required'],
            [['agid_person_president_id'], 'exist', 'skipOnError' => true, 'targetClass' => AgidPerson::className(), 'targetAttribute' => ['agid_person_president_id' => 'id']],
            [['agid_person_vice_president_id'], 'exist', 'skipOnError' => true, 'targetClass' => AgidPerson::className(), 'targetAttribute' => ['agid_person_vice_president_id' => 'id']],
     
            ['logo_image', /*'maxFiles' => 1,*/ 'file', 'extensions' => 'jpeg, jpg, png, gif'],
            [['mail_reference', 'pec_reference'], 'email'],
            [['website_url'], 'url', 'defaultScheme' => 'https'],
        ];
    }

	/**
	 * @inheritdoc
	 */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'id' => Module::t('amosorganizationalunit', 'ID'),
            'agid_organizational_unit_content_type_id' => Module::t('amosorganizationalunit', 'agid_organizational_unit_content_type_id'),
            'agid_organizational_unit_type_id' => Module::t('amosorganizationalunit', 'agid_organizational_unit_type_id'),
            'agid_person_president_id' => Module::t('amosorganizationalunit', 'agid_person_president_id'),
            'agid_person_vice_president_id' => Module::t('amosorganizationalunit', 'agid_person_vice_president_id'),
            'name' => Module::t('amosorganizationalunit', 'name'),
            'logo_image' => Module::t('amosorganizationalunit', 'logo_image'),
            'short_description' => Module::t('amosorganizationalunit', 'short_description'),
            'skills' => Module::t('amosorganizationalunit', 'skills'),
            'headquarters_name' => Module::t('amosorganizationalunit', 'headquarters_name'),
            'address' => Module::t('amosorganizationalunit', 'address'),
            'public_hours' => Module::t('amosorganizationalunit', 'public_hours'),
            'cap' => Module::t('amosorganizationalunit', 'cap'),
            'telephone_reference' => Module::t('amosorganizationalunit', 'telephone_reference'),
            'mail_reference' => Module::t('amosorganizationalunit', 'mail_reference'),
            'pec_reference' => Module::t('amosorganizationalunit', 'pec_reference'),
            'further_information' => Module::t('amosorganizationalunit', 'further_information'),
            'help_box' => Module::t('amosorganizationalunit', 'help_box'),
            'website_url' => Module::t('amosorganizationalunit', '#website_url'),
            'status' => Module::t('amosorganizationalunit', 'status'),
            'created_at' => Module::t('amosorganizationalunit', 'Created at'),
            'updated_at' => Module::t('amosorganizationalunit', 'Updated at'),
            'deleted_at' => Module::t('amosorganizationalunit', 'Deleted at'),
            'created_by' => Module::t('amosorganizationalunit', 'Created by'),
            'updated_by' => Module::t('amosorganizationalunit', 'Updated by'),
            'deleted_by' => Module::t('amosorganizationalunit', 'Deleted by'),
            'updated_from' => Module::t('amosorganizationalunit', '#updated_from'),
            'updated_to' => Module::t('amosorganizationalunit', '#updated_to'),
        ]);
    }
    
    /**
     * @return File[]
     */
    public function getLogo_image()
    {
        if (empty($this->logo_image)) {
            $query = $this->hasOneFile('logo_image');
            $this->logo_image = $query->one();
        }
        return $this->logo_image;
    }
    
    /**
     * 
     * @param File $logo
     */
    public function setLogo_image($logo)
    {
        $this->logo_image = $logo;
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgidOrganizationalUnitType()
    {
        return $this->hasOne(\open20\agid\organizationalunit\models\AgidOrganizationalUnitType::className(), ['id' => 'agid_organizational_unit_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgidOrganizationalUnitContentType()
    {
        return $this->hasOne(\open20\agid\organizationalunit\models\AgidOrganizationalUnitContentType::className(), ['id' => 'agid_organizational_unit_content_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgidOrganizationalOrganizationalUnitMm(){

        return $this->hasMany(\open20\agid\organizationalunit\models\AgidOrganizationalOrganizationalUnitMm::className(), ['agid_organizational_unit_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgidOrganizationalUnitDocumentiMm(){

        return $this->hasMany(\open20\agid\organizationalunit\models\AgidOrganizationalUnitDocumentiMm::className(), ['agid_organizational_unit_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgidPersonPresident()
    {

        return $this->hasOne(\open20\agid\person\models\AgidPerson::className(), ['id' => 'agid_person_president_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgidPersonVicePresident()
    {

        return $this->hasOne(\open20\agid\person\models\AgidPerson::className(), ['id' => 'agid_person_vice_president_id']);
    }

    /**
     * 
     * @param bool $truncate
     * @return string
     */
    public function getDescription($truncate) 
    {
        $ret = $this->name;
        if ($truncate) {
            $ret = $this->__shortText($this->name, 200);
        }
        return $ret;
    }

    /**
     * 
     * @return array
     */
    public function getGridViewColumns() 
    {
        return [];
    }

    /**
     * 
     * @return string
     */
    public function getTitle() 
    {
        return $this->name;
    }
    
    /**
     * Method for extracting associations between organizational units and services 
     *
     * @return model | AgidOrganizationalUnitServiceMm
     */
    public function getAgidOrganizationalUnitServicesMm(){
        return $this->hasMany(\open20\agid\organizationalunit\models\AgidOrganizationalUnitServiceMm::className(), ['agid_organizational_unit_id' => 'id']);
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
                    ->where(['workflow_id' => $this::AGID_ORGANIZATIONAL_UNIT_WORKFLOW])
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
     * Method to get all services associated with agid organizational unit
     *
     * @param boolean $only_validated
     * @return array | model | AgidService
     */
    public function getAgidOrganizationalUnitServices($only_validated = true){

        $agid_organizational_unit_services_id = ArrayHelper::getColumn(
            $this->agidOrganizationalUnitServicesMm,

            function ($element) {
                return $element['agid_service_id'];
            }
        );

        $agid_organizational_unit_services = AgidService::find()
                                    ->andWhere([ 'id' => $agid_organizational_unit_services_id ]);

        if($only_validated){
            $agid_organizational_unit_services = $agid_organizational_unit_services->andWhere(['status' => AgidService::AGID_SERVICE_STATUS_VALIDATED]);
        }

        return $agid_organizational_unit_services = $agid_organizational_unit_services->andWhere([ 'deleted_at' => null ])->all();
        
    }

    /**
     * Method to get all documenti associated with AgidOrganizationalUnit
     *
     * @param boolean $only_validated
     * @return array | model | Documenti
     */
    public function getAgidOrganizationalUnitDocumenti($only_validated = true){

        $documenti_id = ArrayHelper::getColumn(
            $this->agidOrganizationalUnitDocumentiMm,

            function ($element) {
                return $element['documenti_id'];
            }
        );

        $agid_organizational_unit_documenti = Documenti::find()
                                    ->andWhere([ 'id' => $documenti_id ]);

        if($only_validated){
            $agid_organizational_unit_documenti = $agid_organizational_unit_documenti->andWhere(['status' => Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO]);
        }

        return $agid_organizational_unit_documenti = $agid_organizational_unit_documenti->andWhere([ 'deleted_at' => null ])->all();
        
    }

    /**
     * Method for obtaining the presidense only if it is validated 
     *
     * @return void
     */
    public function getAgidPersonPresidentValidated(){

        $president = $this->getAgidPersonPresident()
                        ->andWhere(['status' => AgidPerson::AGID_PERSON_STATUS_VALIDATED])
                        ->andWhere(['deleted_at' => null])
                        ->one();

        return $president ?? null;
    }

    /**
     * Method for obtaining the vice presidense only if it is validated 
     *
     * @return void
     */
    public function getAgidPersonVicePresidentValidated(){

        $vice_president = $this->getAgidPersonVicePresident()
                        ->andWhere(['status' => AgidPerson::AGID_PERSON_STATUS_VALIDATED])
                        ->andWhere(['deleted_at' => null])
                        ->one();

        return $vice_president ?? null;
    }
  
    /**
     * Method to get all AgidOrganizationalUnit associated with AgidOrganizationalUnit
     *
     * @param boolean $only_validated
     * @return array | model | AgidOrganizationalUnit
     */
    public function getAgidOrganizationalOrganizationalUnit($only_validated = true){

        $agid_organizational_unit_father_id = ArrayHelper::getColumn(
            $this->agidOrganizationalOrganizationalUnitMm,

            function ($element) {
                return $element['agid_organizational_unit_father_id'];
            }
        );

        $agid_organizational_unit = AgidOrganizationalUnitModel::find()
                                    ->andWhere([ 'id' => $agid_organizational_unit_father_id ]);

        if($only_validated){
            $agid_organizational_unit = $agid_organizational_unit->andWhere(['status' => AgidOrganizationalUnitModel::AGID_ORGANIZATIONAL_UNIT_WORKFLOW_STATUS_VALIDATED]);
        }

        return $agid_organizational_unit = $agid_organizational_unit->andWhere([ 'deleted_at' => null ])->all();

    }

}
