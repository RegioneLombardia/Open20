<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\components
 * @category   CategoryName
 */

namespace open20\amos\organizzazioni\components;

use open20\amos\core\exceptions\AmosException;
use open20\amos\core\utilities\SpreadSheetFactory;
use open20\amos\organizzazioni\Module;
use yii\base\BaseObject;
use yii\helpers\BaseFileHelper;

/**
 * Class ImportManager
 * @package open20\amos\organizzazioni\components
 */
class ImportManager extends BaseObject
{
    const IMPORT_TABLE = 'profilo_importazione';
    
    /**
     * @var bool $checkOk
     */
    protected $checkOk = false;
    
    /**
     * @var bool $importerEnabled
     */
    protected $importerEnabled = false;
    
    /**
     * @var string $importFileName
     */
    protected $importFileName = 'import';
    
    /**
     * @var string $worksheetName
     */
    protected $worksheetName = 'Foglio1';
    
    /**
     * @var array $importOrganizationsConf Configuration array for the organization importer. See README for the array structure.
     */
    public $importOrganizationsConf = [];
    
    /**
     * @var string[] $makeUniqueFields Fields to pass to array_unique
     */
    protected static $makeUniqueFields = [
        'mapHeaderImport',
        'requiredMapHeaderImport',
    ];
    
    /**
     * @var array $fieldsTypes This is internal configurations useful to check the integrity of the array content.
     */
    protected static $fieldsTypes = [
        'keyForImport' => 'STRING',
        'mapHeaderImport' => 'ARRAY',
        'requiredMapHeaderImport' => 'ARRAY',
        'mapHeaderImportFields' => 'STRING', // For internal use only
        'requiredMapHeaderImportFields' => 'STRING', // For internal use only
        'importFileName' => 'STRING',
        'worksheetName' => 'STRING',
    ];
    
    /**
     * @var string[] $fieldsTypeMapInternal For internal use only
     */
    protected static $fieldsTypeMapInternal = [
        'mapHeaderImport' => 'mapHeaderImportFields',
        'requiredMapHeaderImport' => 'requiredMapHeaderImportFields',
    ];
    
    /**
     * @var string[] $requiredFieldsConfKeys
     */
    protected static $requiredFieldsConfKeys = [
        'keyForImport',
        'mapHeaderImport',
    ];
    
    /**
     * @var string[] $allowedFieldsConfKeys
     */
    protected static $allowedFieldsConfKeys = [
        'keyForImport',
        'mapHeaderImport',
        'requiredMapHeaderImport',
        'importFileName',
        'worksheetName',
    ];
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $emptyConfigurations = empty($this->importOrganizationsConf);
        $this->checkOk = ($emptyConfigurations || $this->checkFieldsConfigurationsStructure());
        $this->importerEnabled = ($this->checkOk && !$emptyConfigurations);
        $this->setImportFileName();
        $this->setWorksheetName();
    }
    
    /**
     * @return bool
     * @throws AmosException
     */
    protected function checkFieldsConfigurationsStructure()
    {
        $ok = $this->checkRequiredConfigurationsKeys();
        if (!$ok) {
            throw new AmosException('ImportManager: fields configuration check failed. Missing required keys.');
        }
        
        foreach (self::$makeUniqueFields as $confElementKey) {
            if (isset($this->importOrganizationsConf[$confElementKey])) {
                $this->importOrganizationsConf[$confElementKey] = array_unique($this->importOrganizationsConf[$confElementKey]);
            }
        }
        
        foreach ($this->importOrganizationsConf as $confElementKey => $confElementValue) {
            
            // If configuration key is not allowed break the configuration checks.
            if (!in_array($confElementKey, self::$allowedFieldsConfKeys)) {
                $ok = false;
                break;
            }
            
            if (($confElementKey == 'mapHeaderImport') || ($confElementKey == 'requiredMapHeaderImport')) {
                $ok = $this->checkMapHeaderStructure($confElementKey, $confElementValue);
                if ($ok) {
                    $keyForImport = $this->getImportKey();
                    if (!in_array($keyForImport, $confElementValue)) {
                        throw new AmosException('ImportManager: Import key must be set in ' . $confElementKey . '.');
                    }
                }
            } else {
                $ok = $this->checkFieldType($confElementKey, $confElementValue);
            }
            
            if (!$ok) {
                break;
            }
        }
        
        if (!$ok) {
            throw new AmosException('ImportManager: fields configuration check failed. Check the module configuration.');
        }
        
        return $ok;
    }
    
    /**
     * @return bool
     */
    protected function checkRequiredConfigurationsKeys()
    {
        $confKeys = array_keys($this->importOrganizationsConf);
        foreach (self::$requiredFieldsConfKeys as $requiredConfKey) {
            if (!in_array($requiredConfKey, $confKeys)) {
                return false;
            }
        }
        return true;
    }
    
    /**
     * @param string $confElementKey
     * @param mixed $confElementValue
     * @return bool
     */
    protected function checkMapHeaderStructure($confElementKey, $confElementValue)
    {
        if (!$this->checkFieldType($confElementKey, $confElementValue)) {
            return false;
        }
        $ok = true;
        foreach ($confElementValue as $confName => $confValue) {
            if (!$this->checkFieldType(self::$fieldsTypeMapInternal[$confElementKey], $confValue)) {
                $ok = false;
                break;
            }
        }
        return $ok;
    }
    
    /**
     * Method that checks the correct type of a field value.
     * @param string $confElementKey Name of an internal array field.
     * @param string $confElementValue Value type of an internal array field.
     * @return bool Returns true if everything goes well. False otherwise.
     */
    protected function checkFieldType($confElementKey, $confElementValue)
    {
        $fieldType = self::$fieldsTypes[$confElementKey];
        switch ($fieldType) {
            case 'STRING':
                $ok = is_string($confElementValue);
                break;
            case 'BOOL':
                $ok = is_bool($confElementValue);
                break;
            case 'ARRAY':
                $ok = is_array($confElementValue);
                break;
            default:
                $ok = false;
                break;
        }
        return $ok;
    }
    
    /**
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    public function createImportTemplate()
    {
        $path = $this->getImportTemplatePath();
        
        if (!file_exists($path)) {
            $objPHPExcel = new \PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->setTitle($this->getWorksheetName());
            $this->addHeaderCellValue($objPHPExcel);
            $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
            $objWriter->save($path);
        }
    }
    
    /**
     * @return string
     * @throws \yii\base\Exception
     */
    public function getImportTemplatePath()
    {
        $fileName = $this->getImportFileName() . '.xlsx';
        $storePath = \Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . 'organizzazioni' . DIRECTORY_SEPARATOR . 'import';
        
        if (!is_dir($storePath)) {
            BaseFileHelper::createDirectory($storePath, 0775, true);
        }
        
        return $storePath . DIRECTORY_SEPARATOR . $fileName;
    }
    
    /**
     * @param \PHPExcel $objPHPExcel
     */
    protected function addHeaderCellValue($objPHPExcel)
    {
        $mapHeaderImport = $this->getMapHeaderImport();
        $mapHeaderImport[] = 'importato';
        $i = 'A';
        foreach ($mapHeaderImport as $fieldName) {
            $objPHPExcel->getActiveSheet()->SetCellValue($i . '1', $fieldName);
            $i++;
        }
    }
    
    /**
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function importOrganizationsFromExcel()
    {
        $submitImport = \Yii::$app->request->post('submit-import');
        if (!empty($submitImport)) {
            if ((isset($_FILES['import-file']['tmp_name']) && (!empty($_FILES['import-file']['tmp_name'])))) {
                $inputFileName = $_FILES['import-file']['tmp_name'];
                $inputFileType = \PHPExcel_IOFactory::identify($inputFileName);
                $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($inputFileName);
                
                $sheet = $objPHPExcel->getSheet(0);
                $highestColumn = $sheet->getHighestColumn();
                
                $headings = $sheet->rangeToArray('A1:' . $highestColumn . '1', null, true, false);
                $headings = reset($headings);
                
                SpreadSheetFactory::createImportAndSaveDynamic(
                    $this->getWorksheetName(),
                    $inputFileName,
                    ImportManager::IMPORT_TABLE,
                    $headings
                );
                
                \Yii::$app->getSession()->addFlash('success', Module::t('amosorganizzazioni', '#import_organizations_success'));
            }
        }
    }
    
    /**
     * @return bool
     */
    public function isImporterEnabled()
    {
        return $this->importerEnabled;
    }
    
    public function setWorksheetName()
    {
        if ($this->isImporterEnabled() && isset($this->importOrganizationsConf['worksheetName'])) {
            $this->worksheetName = $this->importOrganizationsConf['worksheetName'];
        }
    }
    
    /**
     * @return string
     */
    public function getWorksheetName()
    {
        return $this->worksheetName;
    }
    
    public function setImportFileName()
    {
        if ($this->isImporterEnabled() && isset($this->importOrganizationsConf['importFileName'])) {
            $this->importFileName = $this->importOrganizationsConf['importFileName'];
        }
    }
    
    /**
     * @return string
     */
    public function getImportFileName()
    {
        return $this->importFileName;
    }
    
    /**
     * @return string
     */
    public function getImportKey()
    {
        return $this->importOrganizationsConf['keyForImport'];
    }
    
    /**
     * @return array
     */
    public function getMapHeaderImport()
    {
        return $this->importOrganizationsConf['mapHeaderImport'];
    }
    
    /**
     * @return array
     */
    public function getRequiredFields()
    {
        return $this->importOrganizationsConf['requiredMapHeaderImport'];
    }
}
