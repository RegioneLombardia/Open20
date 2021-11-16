<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\privileges\utility
 * @category   CategoryName
 */

namespace open20\amos\privileges\utility;

use open20\amos\admin\AmosAdmin;
use open20\amos\core\user\AmosUser;
use open20\amos\core\user\User;
use open20\amos\cwh\models\CwhAuthAssignment;
use open20\amos\privileges\AmosPrivileges;
use open20\amos\privileges\models\Privilege;
use Yii;
use yii\base\BaseObject;
use yii\data\ArrayDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\rbac\DbManager;
use yii\rbac\Item;

/**
 * Class PrivilegesUtility
 * @package open20\amos\privileges\utility
 */
class PrivilegesUtility extends BaseObject
{
    const CWH_PERMISSION_PREFIX = 'CWH_PERMISSION';

    /**
     * @var string the name of the table storing authorization items. Defaults to "auth_item".
     */
    const ITEM_TABLE = '{{%auth_item}}';

    /**
     * @var string the name of the table storing authorization item hierarchy. Defaults to "auth_item_child".
     */
    const ITEM_CHILD_TABLE = '{{%auth_item_child}}';

    /**
     * @var int $userId to check privileges
     */
    public $userId;

    /**
     * @var Item[] $items - array of auth items
     */
    public $items = [];

    /**
     * @var array $directAssignments - name of the authitems directly assigned to user (via table auth_assignments)
     */
    public $directAssignments = [];

    /**
     * @var AmosUser $amosUser - user with id = $userId
     */
    public $amosUser;

    /**
     * @var AmosPrivileges $privilegesModule - module instance to get whitelists and blacklists
     */
    public $privilegesModule;

    /** @var Privilege[] $privileges  */
    public $privileges = [];

    /** @var array $modulesPrivileges - array of names of privileges related to modules (not platform) */
    public $modulesPrivileges = [];

    public function init()
    {
        parent::init();

        $this->privilegesModule = AmosPrivileges::getInstance();

        if (!empty($this->privilegesModule->whiteListRoles)) {

            $rows = (new Query())->from(self::ITEM_TABLE)->andWhere([
                'name',
                $this->privilegesModule->whiteListRoles
            ]);
        } else {
            $rows = (new Query())->from(self::ITEM_TABLE);
            if (!empty($this->privilegesModule->blackListRoles)) {
                $rows->andWhere([
                    'not in',
                    'name',
                    $this->privilegesModule->blackListRoles
                ]);
            }
        }

        $this->items = $rows->all();

        /** @var DbManager $authManager */
        $authManager             = Yii::$app->authManager;
        $assignments             = $authManager->getAssignments($this->userId);
        $this->directAssignments = ArrayHelper::map($assignments, 'roleName', 'roleName');

        $user           = User::findOne($this->userId);
        $this->amosUser = new AmosUser(['identityClass' => User::className()]);
        $this->amosUser->setIdentity($user);

        foreach ($this->items as $item) {
            if ($item['type'] != Item::TYPE_PERMISSION || strstr($item['name'], self::CWH_PERMISSION_PREFIX)) {
                $privilege                          = $this->getPrivilegeByAuthItem($item);
                $this->privileges[$privilege->name] = $privilege;
            }
        }
    }

    /**
     * @return array
     */
    public function getPrivilegesArray($isView = false)
    {

        $modules         = Yii::$app->getModules();
        $moduleNameArray = array_keys($modules);

        sort($moduleNameArray, SORT_ASC);

        $array             = [];
        $array['platform'] = [];

        foreach ($moduleNameArray as $moduleName) {
            $privileges = $this->getModulePrivileges($moduleName, $isView);
            if (!empty($privileges)) {
                $arrayModule['allModels'] = $privileges;
                if (!empty($arrayModule['allModels'])) {
                    $arrayModule['pagination'] = false;
                    $dataProviderModule = new ArrayDataProvider($arrayModule);
                    $array[$moduleName] = $dataProviderModule;
                }
            }
        }

        $platformPrivileges         = $this->privileges;
        $arrayPlatform['allModels'] = [];
        foreach ($platformPrivileges as $name => $privilege) {
            if (!in_array($name, $this->modulesPrivileges) && !($privilege->isChild && $privilege->type == Item::TYPE_PERMISSION)) {
                array_push($arrayPlatform['allModels'], $privilege->toArray());
            }
        }
        $arrayPlatform['pagination'] = false;
        $dataProviderPlatform = new ArrayDataProvider($arrayPlatform);
        $array['platform']    = $dataProviderPlatform;

        return $array;
    }

    public function getModulePrivileges($moduleName = null, $isView = false)
    {

        $privileges = [];
        $keys       = [];

        if (!is_null($moduleName)) {
            /** @var string $moduleName */
            if ($moduleName != 'cwh' && $moduleName != AmosAdmin::getModuleName()) {
                $keys[] = strtoupper($moduleName);
            }
            if (strstr($moduleName, 'project')) {
                $keys[] = 'ACTIVIT';
                $keys[] = 'TASK';
                $keys[] = 'PARTNER';
                $keys[] = 'PROJECT';
            }
            if (strstr($moduleName, 'progetti')) {
                $keys[] = 'PROGRAMMI';
                $keys[] = 'INTERMEDIARI';
            }
            if (($moduleName == 'organizzazioni')) {
                $keys[] = 'INTERMEDIARI';           
                $keys[] = 'ORGANIZATION';
                $keys[] = '_ORGANIZZAZIONI';
                $keys[] = 'OPERATING_REFERENT';
                $keys[] = 'PROFILO';
            }
            if ($moduleName == 'proposte_collaborazione') {
                $keys[] = 'PROPRIETA_INTELLETTUALI';
                $keys[] = 'MANIFESTAZIONI_INTERESSE';
                $keys[] = 'LINGUE_DI_LAVORO';
                $keys[] = 'STADI_DI_SVILUPPO';
                $keys[] = 'TIPOLOGIE_PROPOSTE';
            }
            if ($moduleName == 'proposte_collaborazione_een') {
                $keys[] = 'PROP_COLLAB';
            }
            if ($moduleName == 'sondaggi') {
                $keys[] = 'RISULTATI';
            }
            if ($moduleName == AmosAdmin::getModuleName()) {
                $keys[] = 'USER_';
                $keys[] = 'USERPROFILE';
            }
            if ($moduleName == 'cwh') {
                $keys[] = '_CWH';
            }
        }
        foreach ($this->privileges as $name => $privilege) {
            if (!empty($moduleName) && $moduleName != 'cwh' && strstr($name, $moduleName) && strstr($name, self::CWH_PERMISSION_PREFIX)) {
                $privilege->name        = str_replace(substr($privilege->name, strpos($privilege->name, 'open20')),
                    $moduleName, $privilege->name);
                $privilege->description = str_replace(substr($privilege->description,
                        strpos($privilege->description, 'open20')), $moduleName, $privilege->description);
                $privilege->text        = $privilege->name.'<br/>'.$privilege->description;
                if (!in_array($moduleName, $this->privilegesModule->blackListModules) && (!$isView || $privilege->can)) {
                    $privileges[$privilege->name]               = $privilege->toArray();
                    $privileges[$privilege->name]['class_name'] = $name;
                }
                $this->modulesPrivileges[] = $name;
            } else {
                foreach ($keys as $key) {
                    if (!empty($moduleName) && strstr($name, $key)) {
                        if (!in_array($moduleName, $this->privilegesModule->blackListModules) && (!$isView || $privilege->can)) {
                            $privileges[$privilege->name]               = $privilege->toArray();
                            $privileges[$privilege->name]['class_name'] = $name;
                        }
                        $this->modulesPrivileges[] = $name;
                    }
                }
            }
        }

        return $privileges;
    }

    /**
     * @param Item $item
     * @return Privilege
     */
    public function getPrivilegeByAuthItem($item)
    {

        $privilege = new Privilege([
            'name' => $item['name'],
            'description' => $item['description'],
            'tooltip' => $item['description'],
            'text' => $item['name'].'<br/>'.$item['description'],
            'type' => $item['type'],
        ]);

        $privilege->can    = $this->amosUser->can($privilege->name);
        $privilege->active = in_array($privilege->name, $this->directAssignments);

        $privilege->isChild = true;
        if ($privilege->name == 'ADMIN' || strstr($privilege->name, 'BASIC_USER')) {
            $privilege->isPlatformUserClass = true;
        }

        if ($privilege->type == Item::TYPE_PERMISSION) {
            if (strstr($privilege->name, self::CWH_PERMISSION_PREFIX) !== false) {
                $privilege->isCwh = true;
                $domainsArray     = CwhAuthAssignment::find()->select('cwh_nodi_id')
                        ->andWhere(['item_name' => $privilege->name])
                        ->andWhere(['not like', 'cwh_nodi_id', 'user'])
                        ->andWhere(['user_id' => $this->userId])->asArray()->all();

                $domains = [];
                if (!empty($domainsArray)) {
                    foreach ($domainsArray as $domain) {
                        array_push($domains, $domain['cwh_nodi_id']);
                    }
                    $privilege->active = true;
                    $privilege->can    = true;
                }
                $privilege->domains = implode(',', $domains);
            }
        } else {
            $parents = (new Query)->from(self::ITEM_CHILD_TABLE)->where(['child' => $privilege->name])->select('parent')->all();
            if (empty($parents)) {
                $privilege->isChild = false;
            } else {
                foreach ($parents as $parent) {
                    array_push($privilege->parents, $parent['parent']);
                }
            }
        }

        return $privilege;
    }
}