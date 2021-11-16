<?php
namespace open20\agid\person;

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\agid\person
 * @category   CategoryName
 */

use open20\amos\core\interfaces\CmsModuleInterface;
use open20\amos\core\interfaces\SearchModuleInterface;
use open20\amos\core\module\AmosModule;
use open20\amos\core\module\ModuleInterface;
use open20\agid\person\models\AgidPerson;
use open20\agid\person\models\AgidPersonType;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * Class Module
 * @package 
 */
class Module extends AmosModule implements ModuleInterface, SearchModuleInterface, CmsModuleInterface
{
    
    public static $CONFIG_FOLDER = 'config';

    /**
     * @inheritdoc
     */
    public static function getModuleName()
    {
        return 'person';
    }

    public function getWidgetIcons()
    {
        return [];
    }

    public function getWidgetGraphics(){
        return [];
    }

    /**
     * Get default model classes
     */
    protected function getDefaultModels()
    {
        return [
            'AgidPerson' => __NAMESPACE__.'\\'.'models\AgidPerson',
            'AgidPersonContentType' => __NAMESPACE__.'\\'.'models\AgidPersonContentType',
            'AgidPersonOrganizationalUnitMm' => __NAMESPACE__.'\\'.'models\AgidPersonOrganizationalUnitMm',
            'AgidPersonType' => __NAMESPACE__.'\\'.'models\AgidPersonType',
            'AgidPersonContentTypeSearch' => __NAMESPACE__.'\\'.'models\search\AgidPersonContentTypeSearch',
            'AgidPersonSearch' => __NAMESPACE__.'\\'.'models\search\AgidPersonSearch',
            'AgidPersonTypeSearch' => __NAMESPACE__.'\\'.'models\search\AgidPersonTypeSearch',
        ];
    }
    

    public static function getModelClassName() {
        return Module::instance()->model('AgidPerson');
    }

    public static function getModelSearchClassName() {
        return Module::instance()->model('AgidPersonSearch');
    }

    public static function getModuleIconName() {
        return null;
    }
    

    // /**
    //  * @inheritdoc
    //  */
     public function init()
     {
         parent::init();

         //Configuration: merge default module configurations loaded from config.php with module configurations set by the application
         $config = require(__DIR__ . DIRECTORY_SEPARATOR . self::$CONFIG_FOLDER . DIRECTORY_SEPARATOR . 'config.php');
         Yii::configure($this, ArrayHelper::merge($config, $this));
     }
    
    /**
     *
     * @return string
     */
    public function getFrontEndMenu($dept = 1)
    {
        $menu = parent::getFrontEndMenu();
        $app  = Yii::$app;
        if (!$app->user->isGuest&& (Yii::$app->user->can('ADMIN')||Yii::$app->user->can('AGID_PERSON_ADMIN')||Yii::$app->user->can('REDACTOR_PERSON'))) {
            $menu .= $this->addFrontEndMenu(Module::t('amosperson','#menu_front_person'), Module::toUrlModule('/agid-person/index'),$dept);
        }
        return $menu;
    }
    
    /**
     * This method returns all the validated person models
     * @param bool $onlyQuery
     * @return ActiveQuery|AgidPerson[]
     * @throws \yii\base\InvalidConfigException
     */
    public function getValidatedPersons($onlyQuery = false)
    {
        /** @var AgidPerson $personModel */
        $personModel = $this->createModel('AgidPerson');
        /** @var ActiveQuery $query */
        $query = $personModel::find();
        $query->andWhere(['status' => $personModel::AGID_PERSON_STATUS_VALIDATED]);
        if ($onlyQuery) {
            return $query;
        } else {
            /** @var AgidPerson[] $persons */
            $persons = $query->all();
            return $persons;
        }
    }
    
    /**
     * This method returns all the politic validated person models
     * @param bool $onlyQuery
     * @return ActiveQuery|AgidPerson[]
     * @throws \yii\base\InvalidConfigException
     */
    public function getPoliticValidatedPersons($onlyQuery = false)
    {
        /** @var AgidPersonType $personTypeModel */
        $personTypeModel = $this->createModel('AgidPersonType');
        /** @var ActiveQuery $query */
        $query = $this->getValidatedPersons(true);
        $query->andWhere(['agid_person_type_id' => $personTypeModel::POLITIC]);
        if ($onlyQuery) {
            return $query;
        } else {
            /** @var AgidPerson[] $persons */
            $persons = $query->all();
            return $persons;
        }
    }
    
    /**
     * This method returns all the administrative validated person models
     * @param bool $onlyQuery
     * @return ActiveQuery|AgidPerson[]
     * @throws \yii\base\InvalidConfigException
     */
    public function getAdministrativeValidatedPersons($onlyQuery = false)
    {
        /** @var AgidPersonType $personTypeModel */
        $personTypeModel = $this->createModel('AgidPersonType');
        /** @var ActiveQuery $query */
        $query = $this->getValidatedPersons(true);
        $query->andWhere(['agid_person_type_id' => $personTypeModel::ADMINISTRATIVE]);
        if ($onlyQuery) {
            return $query;
        } else {
            /** @var AgidPerson[] $persons */
            $persons = $query->all();
            return $persons;
        }
    }
}
