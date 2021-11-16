<?php

namespace open20\agid\person\models\base;

use open20\agid\person\models\AgidPersonContentType;
use open20\agid\person\models\AgidPersonOrganizationalUnitMm;
use open20\agid\person\models\AgidPersonType;
use open20\agid\person\Module;
use yii\db\ActiveQuery;
use open20\agid\organizationalunit\models\AgidOrganizationalUnit;
use yii\helpers\ArrayHelper;

/**
 * This is the base-model class for table "agid_person".
 *
 * @property integer $id
 * @property integer $agid_person_content_type_id
 * @property integer $agid_person_type_id
 * @property integer $agid_document_cv_id
 * @property integer $agid_document_import_id
 * @property integer $agid_document_other_posts_id
 * @property integer $agid_document_nomination_id
 * @property integer $agid_document_balance_sheet_id
 * @property integer $agid_document_tax_return_id
 * @property integer $agid_document_election_expenses_id
 * @property integer $agid_document_changes_balance_sheet_id
 * @property string $name
 * @property string $surname
 * @property string $role
 * @property string $role_description
 * @property string $date_end_assignment
 * @property string $skills
 * @property string $delegation
 * @property string $date_start_settlement
 * @property string $bio
 * @property string $telephone
 * @property string $email
 * @property string $other_info
 * @property string $person_function
 * @property string $status
 * @property string $manager_org
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * 
 * @property string $person_function_1
 * @property string $person_function_2
 * @property string $person_function_3
 * @property string $person_function_4
 * @property string $person_function_5
 * @property integer $agid_organizational_unit_1_id
 * @property integer $agid_organizational_unit_2_id
 * @property integer $agid_organizational_unit_3_id
 * @property integer $agid_organizational_unit_4_id
 * @property integer $agid_organizational_unit_5_id
 * 
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property AgidPersonContentType $agidPersonContentType
 * @property AgidPersonType $agidPersonType
 */
abstract class AgidPerson extends \open20\amos\core\record\ContentModel implements \open20\amos\seo\interfaces\SeoModelInterface,
 \open20\amos\core\interfaces\ContentModelInterface
{
    public $isSearch = false;
    public $manager_org;
    public $nomecognome;
    const ROLE_MANAGER='MANAGER';
    const ROLE_USER='USER';

    public $updated_from;
    public $updated_to;
    public $organizational_unit_of_reference;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'agid_person';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['agid_person_content_type_id', 'agid_person_type_id', 'agid_document_cv_id', 'agid_document_import_id', 'agid_document_other_posts_id', 'agid_document_nomination_id', 'agid_document_balance_sheet_id', 'agid_document_tax_return_id', 'agid_document_election_expenses_id', 'agid_document_changes_balance_sheet_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['role_description', 'skills', 'delegation', 'bio', 'other_info', 'person_function'], 'string'],
            [['manager_org','date_end_assignment', 'date_start_settlement', 'created_at', 'updated_at', 'deleted_at','nomecognome'], 'safe'],
            [['name', 'surname', 'role', 'email', 'status'], 'string', 'max' => 255],
            [['email'], 'email'],
            [['telephone'], 'string',  'max' => 11],
            //[['telephone'], 'match', 'pattern' => '^[0-9]$'],
            [['agid_person_content_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => AgidPersonContentType::className(), 'targetAttribute' => ['agid_person_content_type_id' => 'id']],
            [['agid_person_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => AgidPersonType::className(), 'targetAttribute' => ['agid_person_type_id' => 'id']],
            [['photo'], 'file'],
            [['name', 'surname', 'role','agid_person_content_type_id', 'agid_person_type_id'], 'required'],


            
            [['person_function_1', 'person_function_2', 'person_function_3', 'person_function_4', 'person_function_5'], 'string'],
            [['agid_organizational_unit_1_id'], 'exist', 'skipOnError' => true, 'targetClass' => AgidOrganizationalUnit::className(), 'targetAttribute' => ['agid_organizational_unit_1_id' => 'id']],
            [['agid_organizational_unit_2_id'], 'exist', 'skipOnError' => true, 'targetClass' => AgidOrganizationalUnit::className(), 'targetAttribute' => ['agid_organizational_unit_1_id' => 'id']],
            [['agid_organizational_unit_3_id'], 'exist', 'skipOnError' => true, 'targetClass' => AgidOrganizationalUnit::className(), 'targetAttribute' => ['agid_organizational_unit_1_id' => 'id']],
            [['agid_organizational_unit_4_id'], 'exist', 'skipOnError' => true, 'targetClass' => AgidOrganizationalUnit::className(), 'targetAttribute' => ['agid_organizational_unit_1_id' => 'id']],
            [['agid_organizational_unit_5_id'], 'exist', 'skipOnError' => true, 'targetClass' => AgidOrganizationalUnit::className(), 'targetAttribute' => ['agid_organizational_unit_1_id' => 'id']],
            [['agid_organizational_unit_1_id', 'agid_organizational_unit_2_id', 'agid_organizational_unit_3_id', 'agid_organizational_unit_4_id', 'agid_organizational_unit_5_id'], 'integer']
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('amosperson', 'ID'),
            'agid_person_content_type_id' => Module::t('amosperson', 'Tipologia content type'),
            'reference_org' => Module::t('amosperson', 'Organizzazione di riferimento'),
            'photo' => Module::t('amosperson', 'Foto'),
            'manager_org' => Module::t('amosperson', 'Responsabile di '),
            'agid_person_type_id' => Module::t('amosperson', 'Tipologia persona'),
            'agid_document_cv_id' => Module::t('amosperson', 'Curriculum Vitae'),
            'agid_document_import_id' => Module::t('amosperson', 'Importi di viaggio e/o servizio'),
            'agid_document_other_posts_id' => Module::t('amosperson', 'Altre cariche'),
            'agid_document_nomination_id' => Module::t('amosperson', 'Atto di nomina'),
            'agid_document_balance_sheet_id' => Module::t('amosperson', 'Situazione patrimoniale'),
            'agid_document_tax_return_id' => Module::t('amosperson', 'Dichiarazione dei redditi'),
            'agid_document_election_expenses_id' => Module::t('amosperson', 'Spese elettorali'),
            'agid_document_changes_balance_sheet_id' => Module::t('amosperson', 'Variazioni situazione patrimoniale'),
            'name' => Module::t('amosperson', 'Nome'),
            'surname' => Module::t('amosperson', 'Cognome'),
            'role' => Module::t('amosperson', 'Ruolo'),
            'role_description' => Module::t('amosperson', 'Descrizione ruolo'),
            'date_end_assignment' => Module::t('amosperson', 'Data conclusione incarico'),
            'skills' => Module::t('amosperson', 'Competenze'),
            'delegation' => Module::t('amosperson', 'Deleghe'),
            'date_start_settlement' => Module::t('amosperson', 'Data insediamento'),
            'bio' => Module::t('amosperson', 'Biografia'),
            'telephone' => Module::t('amosperson', 'Numero di telefono'),
            'email' => Module::t('amosperson', 'Indirizzo email'),
            'other_info' => Module::t('amosperson', 'Ulteriori informazioni'),
            'person_function' => Module::t('amosperson', '#person_function'),
            'status' => Module::t('amosperson', 'Stato'),
            'created_at' => Module::t('amosperson', 'Created at'),
            'updated_at' => Module::t('amosperson', 'Updated at'),
            'deleted_at' => Module::t('amosperson', 'Deleted at'),
            'created_by' => Module::t('amosperson', 'Created by'),
            'updated_by' => Module::t('amosperson', 'Updated by'),
            'deleted_by' => Module::t('amosperson', 'Deleted by'),
            'agid_organizational_unit_1_id' => Module::t('amosperson', '#agid_organizational_unit_1_id'),
            'agid_organizational_unit_2_id' => Module::t('amosperson', '#agid_organizational_unit_2_id'),
            'agid_organizational_unit_3_id' => Module::t('amosperson', '#agid_organizational_unit_3_id'),
            'agid_organizational_unit_4_id' => Module::t('amosperson', '#agid_organizational_unit_4_id'),
            'agid_organizational_unit_5_id' => Module::t('amosperson', '#agid_organizational_unit_5_id'),
            'person_function_1' => Module::t('amosperson', '#person_function_1'),
            'person_function_2' => Module::t('amosperson', '#person_function_2'),
            'person_function_3' => Module::t('amosperson', '#person_function_3'),
            'person_function_4' => Module::t('amosperson', '#person_function_4'),
            'person_function_5' => Module::t('amosperson', '#person_function_5'),

        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getAgidPersonContentType()
    {
        return $this->hasOne(AgidPersonContentType::className(), ['id' => 'agid_person_content_type_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getAgidPersonType()
    {
        return $this->hasOne(AgidPersonType::className(), ['id' => 'agid_person_type_id']);
    }
    /**
     * @return ActiveQuery
     */
    public function getAgidPersonOrganizationalUnitMmsManager()
    {
        return $this->hasMany(AgidPersonOrganizationalUnitMm::className(), ['agid_person_id' => 'id']) ->andOnCondition(['role' => self::ROLE_MANAGER])
                ->andOnCondition(['not' ,['agid_organizational_unit_id' => null]]);
    }
    /**
     * @return ActiveQuery
     */
    public function getAgidPersonOrganizationalUnitMmsUser()
    {
        return $this->hasMany(AgidPersonOrganizationalUnitMm::className(), ['agid_person_id' => 'id']) ->andOnCondition(['role' => self::ROLE_USER])
            ->andOnCondition(['not' ,['agid_organizational_unit_id' => null]]);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCvDocumento(){

        return $this->hasOne(\open20\amos\documenti\models\Documenti::className(), ['id' => 'agid_document_cv_id']);
    }

    /**
     * Method to get Documenti (agid_document_cv_id) for AgidPerson
     *
     * @return model | Documenti
     */
    public function getCvDocumentoValidated(){

        return $this->getCvDocumento()
            ->andWhere(['status' => \open20\amos\documenti\models\Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO])
            ->andWhere(['deleted_at' => null])
            ->one();
    }

    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNominationDocumento(){

        return $this->hasOne(\open20\amos\documenti\models\Documenti::className(), ['id' => 'agid_document_nomination_id']);
    }

    /**
     * Method to get Documenti (agid_document_nomination_id) for AgidPerson
     *
     * @return model | Documenti
     */
    public function getNominationDocumentoValidated(){

        return $this->getNominationDocumento()           
            ->andWhere(['status' => \open20\amos\documenti\models\Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO])
            ->andWhere(['deleted_at' => null])
            ->one();
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImportDocumento(){

        return $this->hasOne(\open20\amos\documenti\models\Documenti::className(), ['id' => 'agid_document_import_id']);
    }


    /**
     * Method to get Documenti (agid_document_import_id) for AgidPerson
     *
     * @return model | Documenti
     */
    public function getImportDocumentoValidated(){

        return $this->getImportDocumento()
                        ->andWhere(['status' => \open20\amos\documenti\models\Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO])
                        ->andWhere(['deleted_at' => null])
                        ->one();

    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOtherPostsDocumento(){

        return $this->hasOne(\open20\amos\documenti\models\Documenti::className(), ['id' => 'agid_document_other_posts_id']);
    }

    /**
     * Method to get Documenti (agid_document_other_posts_id) for AgidPerson
     *
     * @return model | Documenti
     */
    public function getOtherPostsDocumentoValidated(){

        return $this->getOtherPostsDocumento()
                ->andWhere(['status' => \open20\amos\documenti\models\Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO])
                ->andWhere(['deleted_at' => null])
                ->one();
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBalanceSheetDocumento(){

        return $this->hasOne(\open20\amos\documenti\models\Documenti::className(), ['id' => 'agid_document_balance_sheet_id']);
    }


    /**
     * Method to get Documenti (agid_document_balance_sheet_id) for AgidPerson
     *
     * @return model | Documenti
     */
    public function getBalanceSheetDocumentoValidated(){

        return $this->getBalanceSheetDocumento()
                        ->andWhere(['status' => \open20\amos\documenti\models\Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO])
                        ->andWhere(['deleted_at' => null])
                        ->one();
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaxReturnDocumento(){

        return $this->hasOne(\open20\amos\documenti\models\Documenti::className(), ['id' => 'agid_document_tax_return_id']);
    }

    /**
     * Method to get Documenti (agid_document_tax_return_id) for AgidPerson
     *
     * @return model | Documenti
     */
    public function getTaxReturnDocumentoValidated(){

        return $this->getTaxReturnDocumento()
                    ->andWhere(['status' => \open20\amos\documenti\models\Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO])
                    ->andWhere(['deleted_at' => null])
                    ->one();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElectionExpensesDocumento(){

        return $this->hasOne(\open20\amos\documenti\models\Documenti::className(), ['id' => 'agid_document_election_expenses_id']);
    }

    /**
     * Method to get Documenti (agid_document_election_expenses_id) for AgidPerson
     *
     * @return model | Documenti
     */
    public function getElectionExpensesDocumentoValidated(){

        return $this->getElectionExpensesDocumento()
                    ->andWhere(['status' => \open20\amos\documenti\models\Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO])
                    ->andWhere(['deleted_at' => null])
                    ->one();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChangesBalanceSheetDocumento(){

        return $this->hasOne(\open20\amos\documenti\models\Documenti::className(), ['id' => 'agid_document_changes_balance_sheet_id']);
    }


    /**
     * Method to get Documenti (agid_document_changes_balance_sheet_id) for AgidPerson
     *
     * @return model | Documenti
     */
    public function getChangesBalanceSheetDocumentoValidated(){

        return $this->getChangesBalanceSheetDocumento()
            ->andWhere(['status' => \open20\amos\documenti\models\Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO])
            ->andWhere(['deleted_at' => null])
            ->one();
    }


    public function getDescription($truncate) 
    {
        $ret = $this->name . " " . $this->surname;
        if ($truncate) {
            $ret = $this->__shortText($ret, 200);
        }
        return $ret;
    }

    public function getGridViewColumns() 
    {
        return [];
    }

    public function getTitle()
    {
        return $this->name;
    } 


    /**
     * Method to get all AgidOrganizzationalUnit with status validated associated to AgidPerson
     *
     * @return array | model | AgidOrganizzationalUnit
     */
    public function getAgidOrganizationalUnit()
    {
        return AgidOrganizationalUnit::find()
                    ->andWhere(
                        [
                            'id' => [
                                    $this->agid_organizational_unit_1_id,
                                    $this->agid_organizational_unit_2_id,
                                    $this->agid_organizational_unit_3_id,
                                    $this->agid_organizational_unit_4_id,
                                    $this->agid_organizational_unit_5_id
                            ]
                        ])
                    ->andWhere(['status' => AgidOrganizationalUnit::AGID_ORGANIZATIONAL_UNIT_WORKFLOW_STATUS_VALIDATED])
                    ->andWhere(['deleted_at' => null])
                    ->all();
    
    }
    
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgidOrganizationalUnitRef_1(){

        return $this->hasOne(AgidOrganizationalUnit::className(), ['id' => 'agid_organizational_unit_1_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgidOrganizationalUnitRef_2(){

        return $this->hasOne(AgidOrganizationalUnit::className(), ['id' => 'agid_organizational_unit_2_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgidOrganizationalUnitRef_3(){

        return $this->hasOne(AgidOrganizationalUnit::className(), ['id' => 'agid_organizational_unit_3_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgidOrganizationalUnitRef_4(){

        return $this->hasOne(AgidOrganizationalUnit::className(), ['id' => 'agid_organizational_unit_4_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgidOrganizationalUnitRef_5(){

        return $this->hasOne(AgidOrganizationalUnit::className(), ['id' => 'agid_organizational_unit_5_id']);
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
                    ->where(['workflow_id' => $this::AGID_PERSON_WORKFLOW])
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
     * Method to get all AgidOrganizationalUnit associated with AgidPerson on condition  'role' => self::ROLE_MANAGER
     *
     * @param boolean $only_validated
     * @return array | model | AgidOrganizationalUnit
     */
    public function getAgidPersonOrganizationalUnitsManager($only_validated = true){

        $agid_organizational_unit_id = ArrayHelper::getColumn(
            $this->agidPersonOrganizationalUnitMmsManager,

            function ($element) {
                return $element['agid_organizational_unit_id'];
            }
        );

        $agid_organizational_unit = AgidOrganizationalUnit::find()
                                    ->andWhere([ 'id' => $agid_organizational_unit_id ]);

        if($only_validated){
            $agid_organizational_unit = $agid_organizational_unit->andWhere(['status' => AgidOrganizationalUnit::AGID_ORGANIZATIONAL_UNIT_WORKFLOW_STATUS_VALIDATED]);
        }

        return $agid_organizational_unit = $agid_organizational_unit->andWhere([ 'deleted_at' => null ])->all();

    }

}
