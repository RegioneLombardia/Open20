<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\cwh
 * @category   CategoryName
 */

namespace open20\amos\cwh\utility;

use open20\amos\admin\AmosAdmin;
use open20\amos\core\record\Record;
use open20\amos\cwh\AmosCwh;
use open20\amos\cwh\base\ModelNetworkInterface;
use open20\amos\cwh\exceptions\CwhException;
use open20\amos\cwh\models\base\CwhNodiView;
use open20\amos\cwh\models\CwhAuthAssignment;
use open20\amos\cwh\models\CwhConfig;
use open20\amos\cwh\models\CwhConfigContents;
use open20\amos\cwh\models\CwhNodi;
use open20\amos\cwh\models\CwhPubblicazioni;
use open20\amos\cwh\models\CwhPubblicazioniCwhNodiEditoriMm;
use open20\amos\cwh\models\CwhPubblicazioniCwhNodiValidatoriMm;
use open20\amos\cwh\models\CwhRegolePubblicazione;
use open20\amos\cwh\models\CwhTagOwnerInterestMm;
use open20\amos\cwh\query\CwhActiveQuery;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * Class CwhUtil
 * @package open20\amos\cwh\utility
 */
class CwhUtil
{
    /**
     * Given the list of CwhNodes id (as array o string with comma separator), get the list as domains name separeted by comma
     *
     * @param mixed $cwhNodeIds
     * @return string
     */
    public static function getDomainNames($cwhNodeIds)
    {
        $domainNames = '';
        if (!empty($cwhNodeIds)) {
            if (is_array($cwhNodeIds)) {
                $domains = ArrayHelper::map(CwhNodi::find()->andWhere(['in', 'id', $cwhNodeIds])->all(),
                    'id', 'text');
            } else {
                $domains = ArrayHelper::map(CwhNodi::find()->andWhere("id in ('" . $cwhNodeIds . "')")->all(),
                    'id', 'text');
            }
            $domainNames = implode(', ', $domains);
        }
        return $domainNames;
    }

    /**
     * @param array|string $tagIds - array of tag ids or string containing tag ids separated by ','
     * @return string - list of tag names separated by ','
     */
    public static function getTagNames($tagIds)
    {

        $tagNames = '';
        $moduleTag = \Yii::$app->getModule('tag');
        if (isset($moduleTag) && !empty($tagIds)) {
            if (is_array($tagIds)) {
                $tags = ArrayHelper::map(\open20\amos\tag\models\Tag::find()->andWhere([
                    'in',
                    'id',
                    $tagIds
                ])->all(),
                    'id', 'nome');
            } else {
                $tags = ArrayHelper::map(\open20\amos\tag\models\Tag::find()->andWhere('id in (' . $tagIds . ')')->all(),
                    'id', 'nome');
            }
            $tagNames = implode(', ', $tags);
        }
        return $tagNames;
    }

    /**
     * @param int $publicationRuleId
     * @return string - publication rule label in translation
     */
    public static function getPublicationRuleLabel($publicationRuleId)
    {

        $name = '';
        $publicationRule = CwhRegolePubblicazione::findOne($publicationRuleId);
        if (!empty($publicationRule)) {
            $name = $publicationRule->nome;
        }
        return $name;
    }

    /**
     * Query for network models in user network, given the network type configuration.
     *
     * @param int $cwhConfigId - id of network configuration (table cwh_config)
     * @param int|null $userId - if null logged userId is considered
     * @param bool|true $checkActive - if true check for only ACTIVE status in network-user relation table
     * @return ActiveQuery|null $networksQuery
     */
    public static function getUserNetworkQuery($cwhConfigId, $userId = null, $checkActive = true)
    {
        $cwhConfig = CwhConfig::findOne($cwhConfigId);
        if (!is_null($cwhConfig)) {
            if (is_null($userId)) {
                $userId = Yii::$app->user->id;
            }
            $network = Yii::createObject($cwhConfig->classname);
            $mmTable = $network->getMmTableName();
            if ($network->hasMethod('getUserNetworkQuery')) {
                $networksQuery = $network->getUserNetworkQuery($userId);
            } else {
                $networksQuery = $network->find()->innerJoin($mmTable,
                    $mmTable . '.' . $network->getMmUserIdFieldName() . '=' . $userId
                    . " AND " . $mmTable . '.' . $network->getMmNetworkIdFieldName() . '=' . $cwhConfig->tablename . '.id')
                    ->andWhere($mmTable . '.deleted_at IS NULL')
                    ->andWhere($cwhConfig->tablename . '.deleted_at IS NULL');
            }
            if ($checkActive) {
                $mmTableSchema = Yii::$app->db->schema->getTableSchema($mmTable);
                if (isset($mmTableSchema->columns['status'])) {
                    $networksQuery->andWhere([$mmTable . '.status' => 'ACTIVE']);
                }
            }
            return $networksQuery;
        }
        return null;
    }


    /**
     * Query for publication rules enabled for logged user
     * @return \open20\amos\cwh\models\query\CwhRegolePubblicazioneQuery|mixed $publicationRulesQuery
     */
    public static function getPublicationRulesQuery()
    {
        /** @var AmosCwh $cwhModule */
        $cwhModule = AmosCwh::getInstance();
        $scope = $cwhModule->getCwhScope();

        //if we are working under a specific network scope (eg. community dashboard)
        $scopeFilter = (empty($scope) ? false : true);

        $publicationRulesQuery = CwhRegolePubblicazione::find();
        //If module tag is not active, exclude publication rule based on tag
        $moduleTag = \Yii::$app->getModule('tag');
        if (!isset($moduleTag)) {
            $publicationRulesQuery->excludeTag();
        }
        //if filter on publication rules by role is active
        // (rule public - to all users - is visible only to users having a specific role)
        if ($cwhModule->regolaPubblicazioneFilter) {
            $publicationRulesQuery->filterByRole();
        }
        //if working in a network scope only rules based on the network membership are available
        if ($scopeFilter) {
            $publicationRulesQuery->onlyNetwork();
        }
        return $publicationRulesQuery;
    }

    /**
     * Get the contents published for the specified network as an array
     * structure array[configContentInfo] => ContentModel[] - config content info to use as array key is configurable with parameter $arrayKeyFromConfigContentsField
     * array key not present if no content of that content type has been published for the network
     *
     * @param int - $configId - Id of Network CwhConfig
     * @param int $networkId - record if of the network in its own table (eg. community->id)
     * @param string $arrayKeyFromConfigContentsField - field of CwhConfigContents to use as array key. Default is tablename
     * @return array
     * eg.or returned array
     * [
     *    ['news'] => News[],
     *    ['documenti'] => Documenti[]
     * ]
     */
    public static function getNetworkContents($configId, $networkId, $arrayKeyFromConfigContentsField = 'tablename')
    {

        $cwhConfigContents = CwhConfigContents::find()->all();
        $contentArray = [];
        /** @var CwhConfigContents $cwhConfigContent */
        foreach ($cwhConfigContents as $cwhConfigContent) {
            $query = new CwhActiveQuery($cwhConfigContent->classname);
            $query->filterByPublicationNetwork($configId, $networkId);
            if ($query->count()) {
                $contentArray[$cwhConfigContent->$arrayKeyFromConfigContentsField] = $query->all();
            }
        }
        return $contentArray;
    }

    /**
     * Delete the contents published in the specified network scope.
     * In case more more scopes are set, only the specified network publication scope is deleted, not the content itself.
     *
     * @param int - $configId - Id of Network CwhConfig
     * @param int $networkId - record if of the network in its own table (eg. community->id)
     */
    public static function deleteNetworkContents($configId, $networkId)
    {

        $contentArray = self::getNetworkContents($configId, $networkId, 'id');

        foreach ($contentArray as $configContentId => $contents) {
            /** @var Record $content */
            foreach ($contents as $content) {
                //the content has more than 1 publication scope
                // Don't delete le content itself, but only the publication scope of selected network
                if (count($content->destinatari) > 1) {
                    $publication = CwhPubblicazioni::findOne(['cwh_config_contents_id' => $configContentId, 'content_id' => $content->id]);
                    if (!is_null($publication)) {
                        $editorNode = CwhPubblicazioniCwhNodiEditoriMm::findOne(['cwh_pubblicazioni_id' => $publication->id, 'cwh_config_id' => $configId, 'cwh_network_id' => $networkId]);
                        if (!is_null($editorNode)) {
                            $editorNode->delete();
                        }
                    }
                } else {
                    //published only in the network scope. Delete le content itself
                    $content->delete();
                }
            }
        }
        //there may be contents published with network validation scope but not publication scope.
        //eg. news published by a community but to all users (no specific publication scope)
        //we don't know if the news must be deleted, we just change to user validation scope. If to be deleted, will be user choose.
        $validatorNodes = CwhPubblicazioniCwhNodiValidatoriMm::find()->andWhere(['cwh_config_id' => $configId, 'cwh_network_id' => $networkId])->all();
        if (count($validatorNodes)) {
            //get user scope configurations
            $cwhConfigUser = CwhConfig::findOne(['tablename' => 'user']);
            $cwhNodiIdPrefix = $cwhConfigUser->tablename . '-';
            $cwhConfigIdUser = $cwhConfigUser->id;
            /** @var CwhPubblicazioniCwhNodiValidatoriMm $validatorNode */
            foreach ($validatorNodes as $validatorNode) {
                $userId = $validatorNode->cwhPubblicazioni->created_by;
                if (!is_null($userId)) {
                    $validatorNode->cwh_config_id = $cwhConfigIdUser;
                    $validatorNode->cwh_network_id = $userId;
                    $validatorNode->cwh_nodi_id = $cwhNodiIdPrefix . $userId;
                    $validatorNode->save();
                }
            }
        }
    }

    /**
     * create cwh nodi view if not exists or regenerate it
     * regenerate the table cwh nodi (materialization of the view
     * Cwh nodi view contains all network records based on the queries of cwh config table.
     */
    public static function createCwhView()
    {
        $listaConf = CwhConfig::find()->all();

        $sqlSelect = '( ';
        $numeroConf = count($listaConf);
        $i = 1;
        foreach ($listaConf as $conf) {
            $sqlSelect .= $conf->getRawSql();
            if ($i < $numeroConf) {
                $sqlSelect .= ' ) UNION ( ';
            }
            $i++;
        }
        $sqlSelect .= ' );';

        $sql = 'CREATE OR REPLACE VIEW cwh_nodi_view AS ' . $sqlSelect;

        $db = Yii::$app->getDb();

        $db->createCommand($sql)->execute();
        $db->createCommand()->truncateTable(CwhNodi::tableName())->execute();
        $db->createCommand('INSERT ' . CwhNodi::tableName() . ' SELECT * FROM ' . CwhNodiView::tablename())->execute();
    }

    /**
     * This method returns an array with the cwh publication rule ids for all users and all users with tag.
     * @return array
     */
    public static function getPlatformCwhRuleIds()
    {
        return [CwhRegolePubblicazione::ALL_USERS, CwhRegolePubblicazione::ALL_USERS_WITH_TAGS];
    }

    /**
     * This method returns an array with the cwh publication rule ids for all users in network and all users with tag in network.
     * @return array
     */
    public static function getNetworkCwhRuleIds()
    {
        return [CwhRegolePubblicazione::ALL_USERS_IN_DOMAINS, CwhRegolePubblicazione::ALL_USERS_IN_DOMAINS_WITH_TAGS];
    }

    /**
     * Given a user_network_mm record set the correct cwh auth assignment permission for content models enabled in cwh
     * Specify $modelClassName if only permissions for specified content model type must be set
     *
     * @param ModelNetworkInterface|null $network - the network record , if left null, specify $cwhConfig
     * @param $networkUserMmRow - record on user-network-mm table
     * @param bool|false $delete - set to true if it is a deletion process
     * @param string|null $modelClassName
     * @param CwhConfig|null $cwhConfig
     */
    public static function setCwhAuthAssignments($network = null, $networkUserMmRow, $delete = false, $modelClassName = null, $cwhConfig = null)
    {
        $cwhModule = Yii::$app->getModule('cwh');
        if (is_null($network)) {
            $networkClassName = $cwhConfig->classname;
            $networkObj = Yii::createObject($networkClassName);
            $networkId = $networkUserMmRow->{$networkObj->getMmUserIdFieldName()};
            $network = $networkObj->findOne($networkId);
        } else {
            $networkId = $network->id;
        }
        if (!is_null($network)) {
            $cwhNodeId = $network->tableName() . '-' . $networkId;
            $cwhConfigId = !is_null($cwhConfig) ? $cwhConfig->id : $network->getCwhConfigId();
            $userId = $networkUserMmRow->{$network->getMmUserIdFieldName()};

            $cwhPermissionsQuery = CwhAuthAssignment::find()->andWhere([
                'user_id' => $userId,
                'cwh_config_id' => $cwhConfigId,
                'cwh_network_id' => $networkId
            ]);
            if (!is_null($modelClassName)) {
                $cwhPermissionsQuery->andWhere([
                    'item_name' => [
                        $cwhModule->permissionPrefix . "_CREATE_" . $modelClassName,
                        $cwhModule->permissionPrefix . "_VALIDATE_" . $modelClassName
                    ]
                ]);
            };
            $cwhPermissions = $cwhPermissionsQuery->all();

            if ($delete) {
                if (!empty($cwhPermissions)) {
                    /** @var CwhAuthAssignment $cwhPermission */
                    foreach ($cwhPermissions as $cwhPermission) {
                        $cwhPermission->delete();
                    }
                }
            } else {
                $existingPermissions = [];
                foreach ($cwhPermissions as $item) {
                    $existingPermissions[$item->item_name] = $item;
                }

                if ($networkUserMmRow->hasAttribute('role')) {
                    if ($network->hasMethod('getRolePermissions')) {
                        if ($network->hasAttribute('context') && !is_null($network->context) & strcmp($network->context,
                                get_class($network))
                        ) {
                            $callingModel = Yii::createObject($network->context);
                            /** @var array $rolePermissions */
                            $rolePermissions = $callingModel->getRolePermissions($networkUserMmRow->role);
                        } else {
                            $rolePermissions = $network->getRolePermissions($networkUserMmRow->role);
                        }
                    }
                }
                if (!isset($rolePermissions)) {
                    $rolePermissions = [$cwhModule->permissionPrefix . '_CREATE'];
                }
                $permissionsToAdd = [];
                if (!is_null($rolePermissions) && count($rolePermissions)) {
                    $modelsEnabled = [];
                    if (is_null($modelClassName)) {
                        // for each enabled Content model in Cwh
                        $modelsEnabled = $cwhModule->modelsEnabled;
                    } else {
                        $modelsEnabled[] = $modelClassName;
                    }
                    foreach ($modelsEnabled as $modelClassname) {
                        foreach ($rolePermissions as $permission) {
                            $cwhAuthAssignment = new CwhAuthAssignment();
                            $cwhAuthAssignment->user_id = $userId;
                            $cwhAuthAssignment->item_name = $permission . '_' . $modelClassname;
                            $cwhAuthAssignment->cwh_nodi_id = $cwhNodeId;
                            $cwhAuthAssignment->cwh_config_id = $cwhConfigId;
                            $cwhAuthAssignment->cwh_network_id = $networkId;
                            $permissionsToAdd[$cwhAuthAssignment->item_name] = $cwhAuthAssignment;
                        }
                    }
                }
                if (!empty($permissionsToAdd)) {
                    /** @var CwhAuthAssignment $permissionToAdd */
                    foreach ($permissionsToAdd as $key => $permissionToAdd) {
                        //if user has not already the permission for the community , add it to cwh auth assignment
                        if (!array_key_exists($key, $existingPermissions)) {
                            $permissionToAdd->detachBehaviors();
                            $permissionToAdd->save(false);
                        }
                    }
                }
                // check if there are permissions to remove
                if (!empty($existingPermissions)) {
                    /** @var CwhAuthAssignment $cwhPermission */
                    foreach ($existingPermissions as $key => $cwhPermission) {
                        if (!array_key_exists($key, $permissionsToAdd)) {
                            $cwhPermission->detachBehaviors();
                            $cwhPermission->delete();
                        }
                    }
                }
            }
        }
    }

    /**
     * @return Record|null
     */
    public static function getNetworkFromScope()
    {
        $cwhModule = Yii::$app->getModule('cwh');
        if (!empty($cwhModule->getCwhScope())) {
            foreach ($cwhModule->getCwhScope() as $tablename => $networkId) {
                $cwhConfigId = CwhConfig::findOne(['tablename' => $tablename])->id;
                $cwhNode = CwhNodi::findOne([
                    'cwh_config_id' => $cwhConfigId,
                    'record_id' => $networkId
                ]);
                if (!is_null($cwhNode)) {
                    return $cwhNode->network;
                }
            }
        }
        return null;
    }

    /**
     * @return CwhNodi|null
     */
    public static function getCwhNodeFromScope()
    {
        $cwhModule = Yii::$app->getModule('cwh');
        if (!empty($cwhModule->getCwhScope())) {
            foreach ($cwhModule->getCwhScope() as $tablename => $networkId) {
                $cwhConfigId = CwhConfig::findOne(['tablename' => $tablename])->id;
                $cwhNode = CwhNodi::findOne([
                    'cwh_config_id' => $cwhConfigId,
                    'record_id' => $networkId
                ]);
                return $cwhNode;
            }
        }
        return null;
    }

    /**
     * @param $idNode
     * @return Record|null
     */
    public static function getNetworkFromId($idNode)
    {

        $cwhModule = Yii::$app->getModule('cwh');
        if (!empty($cwhModule)) {
            $cwhNode = CwhNodi::findOne($idNode);
            if ($cwhNode) {
                return $cwhNode->network;
            }
        }
        return null;
    }

    /**
     * This method returns all tag ids selected by a user. The param is the user profile id, not the user id!!!
     * @param int $userProfileId
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public static function findInterestTagIdsByUser($userProfileId)
    {
        $query = new Query();
        $query->select(['tag_id'])->distinct();
        $query->from(CwhTagOwnerInterestMm::tableName());
        $query->andWhere(['deleted_at' => null]);
        $query->andWhere(['classname' => AmosAdmin::instance()->createModel('UserProfile')->className()]);
        $query->andWhere(['record_id' => $userProfileId]);
        $tagIds = $query->column();
        return $tagIds;
    }

    /**
     * This method a user interest. The param is the user profile id, not the user id!!!
     * @param \open20\amos\tag\models\Tag $organizationTag
     * @param int $userProfileId
     * @param string $interestClassname
     * @param string|null $className
     * @return bool
     * @throws CwhException
     * @throws \yii\base\InvalidConfigException
     */
    public static function addNewUserInterest($organizationTag, $userProfileId, $interestClassname = 'simple-choice', $className = null)
    {
        if (!($organizationTag instanceof \open20\amos\tag\models\Tag)) {
            throw new CwhException('Param organizationTag must be an instance of \open20\amos\tag\models\Tag');
        }
        if (!is_integer($userProfileId) && !is_numeric($userProfileId)) {
            throw new CwhException('Param userId must be an integer');
        }
        if (is_null($className)) {
            $className = AmosAdmin::instance()->createModel('UserProfile')->className();
        }
        $interest = new CwhTagOwnerInterestMm();
        $interest->interest_classname = $interestClassname;
        $interest->classname = $className;
        $interest->record_id = $userProfileId;
        $interest->tag_id = $organizationTag->id;
        $interest->root_id = $organizationTag->root;
        $ok = $interest->save();
        return $ok;
    }
}
