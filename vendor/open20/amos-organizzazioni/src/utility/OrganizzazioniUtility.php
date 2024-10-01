<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\utility
 * @category   CategoryName
 */

namespace open20\amos\organizzazioni\utility;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use open20\amos\admin\models\UserProfileArea;
use open20\amos\admin\models\UserProfileRole;
use open20\amos\admin\utility\UserProfileUtility;
use open20\amos\community\AmosCommunity;
use open20\amos\community\exceptions\CommunityException;
use open20\amos\community\models\CommunityType;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\core\exceptions\AmosException;
use open20\amos\core\interfaces\OrganizationsModelInterface;
use open20\amos\core\record\Record;
use open20\amos\core\user\User;
use open20\amos\organizzazioni\models\Profilo;
use open20\amos\organizzazioni\models\ProfiloEntiType;
use open20\amos\organizzazioni\models\ProfiloSedi;
use open20\amos\organizzazioni\models\ProfiloSediTypes;
use open20\amos\organizzazioni\models\ProfiloSediUserMm;
use open20\amos\organizzazioni\models\ProfiloUserMm;
use open20\amos\organizzazioni\Module;
use Throwable;
use Yii;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\db\StaleObjectException;
use yii\helpers\ArrayHelper;
use yii\log\Logger;

/**
 * Class OrganizzazioniUtility
 * @package open20\amos\organizzazioni\utility
 */
class OrganizzazioniUtility extends BaseObject
{
    /**
     * This method returns all platform organizations ready for select.
     * @param Profilo $model
     * @return array
     * @throws InvalidConfigException
     */
    public static function getMembershipOrganizationsReadyForSelect($model)
    {
        /** @var Profilo $modelProfilo */
        $modelProfilo = Module::instance()->createModel('Profilo');
        /** @var ActiveQuery $query */
        $query = $modelProfilo::find();
        if ($model->id) {
            $query->andWhere(['<>', 'id', $model->id]);
        }
        $organizations = $query->all();
        $readyForSelect = ArrayHelper::map($organizations, 'id', 'name');
        return $readyForSelect;
    }

    /**
     * This method returns all profilo sedi types ready for select.
     * @return array
     * @throws InvalidConfigException
     */
    public static function getProfiloSediTypesReadyForSelect()
    {
        /** @var Module $organizzazioniModule */
        $organizzazioniModule = \Yii::$app->getModule(Module::getModuleName());
        /** @var ProfiloSediTypes $modelProfiloSediTypes */
        $modelProfiloSediTypes = Module::instance()->createModel('ProfiloSediTypes');
        /** @var ActiveQuery $query */
        $query = $modelProfiloSediTypes::find();
        if (!$organizzazioniModule->enableAddOtherLegalHeadquarters) {
            $query->andWhere(['<>', 'id', ProfiloSediTypes::TYPE_LEGAL_HEADQUARTER]);
        }
        $query->andWhere(['active' => 1]);
        $query->orderBy(['order' => SORT_ASC]);
        $readyForSelect = ArrayHelper::map($query->all(), 'id', 'name');
        return $readyForSelect;
    }

    /**
     * This method copy the operative headquarter object field values to the legal
     * headquarter object fields. It returns the legal headquarter object.
     * @param ProfiloSedi $operativeHeadquarter
     * @param ProfiloSedi $legalHeadquarter
     * @param array $skipColumns
     * @return ProfiloSedi
     */
    public static function copyOperativeToLegalHeadquarterValues($operativeHeadquarter, $legalHeadquarter, $skipColumns = ['profilo_sedi_type_id', 'id'])
    {
        $sedeColumns = $operativeHeadquarter->attributes();
        foreach ($sedeColumns as $sedeColumn) {
            if (!in_array($sedeColumn, $skipColumns)) {
                $legalHeadquarter->{$sedeColumn} = $operativeHeadquarter->{$sedeColumn};
            }
        }
        return $legalHeadquarter;
    }


    /**
     * @param ProfiloUserMm $profiloUserMm
     * @return UserProfileArea[]
     * @throws InvalidConfigException
     */
    public static function getUserProfileAreas($profiloUserMm)
    {
        $moduleAdmin = AmosAdmin::instance();
        /** @var ActiveQuery $query */
        if ($moduleAdmin) {
            $query = UserProfileArea::find();
            if ($moduleAdmin->adminModule->roleAndAreaOnOrganizations && $moduleAdmin->adminModule->roleAndAreaFromOrganizationsWithTypeCat) {
                $query->andWhere(['type_cat' => [UserProfileArea::TYPE_CAT_GENERIC, $profiloUserMm->profilo->profilo_enti_type_id]]);
            }
            $query->orderBy(['order' => SORT_ASC]);
            $areas = $query->all();
            return $areas;
        }
        return null;
    }

    /**
     * @param ProfiloUserMm $profiloUserMm
     * @return UserProfileArea[]
     * @throws InvalidConfigException
     */
    public static function getUserProfileRoles($profiloUserMm)
    {
        $moduleAdmin = AmosAdmin::instance();
        /** @var ActiveQuery $query */
        if ($moduleAdmin) {
            $query = UserProfileRole::find();
            if ($moduleAdmin->adminModule->roleAndAreaOnOrganizations && $moduleAdmin->adminModule->roleAndAreaFromOrganizationsWithTypeCat) {
                $query->andWhere(['type_cat' => [UserProfileRole::TYPE_CAT_GENERIC, $profiloUserMm->profilo->profilo_enti_type_id]]);
            }
            $query->orderBy(['order' => SORT_ASC]);
            $areas = $query->all();
            return $areas;
        }
        return null;
    }

    /**
     * @param $userId
     * @param bool $onlyIds
     * @param bool $returnQuery
     * @return array|ActiveQuery|ActiveRecord[]
     * @throws AmosException
     */
    public static function getOrganizationsRepresentedOrReferredByUserId($userId, $onlyIds = false, $returnQuery = false)
    {
        if (!is_numeric($userId) || ($userId <= 0)) {
            throw new AmosException(Module::t('amosorganizzazioni', 'getOrganizationsRepresentedOrReferredByUserId: userId is not a number or is not positive'));
        }
        /** @var Profilo $profiloModel */
        $profiloModel = Module::instance()->createModel('Profilo');
        /** @var ActiveQuery $query */
        $query = $profiloModel::find();
        $query->andWhere(['or',
            ['rappresentante_legale' => $userId],
            ['referente_operativo' => $userId]
        ]);
        if ($returnQuery) {
            return $query;
        }
        if ($onlyIds) {
            $query->select([$profiloModel::tableName() . '.id']);
            $organizations = $query->column();
        } else {
            $organizations = $query->all();
        }
        return $organizations;
    }

    /**
     * This method returns an array of UserProfile objects that contains the
     * legal representative and the operative referee of the organization passed by param.
     * @param int $organizationId
     * @return UserProfile[]|bool
     */
    public static function getOrganizationReferees($organizationId, $onlyIds = false)
    {
        /** @var Module $organizationsModule */
        $organizationsModule = Module::instance();
        /** @var Profilo $profiloModel */
        $profiloModel = $organizationsModule->createModel('Profilo');
        $organization = $profiloModel::findOne($organizationId);
        if (is_null($organization)) {
            return false;
        }
        $organizationReferees = [];
        if (!$organizationsModule->enableRappresentanteLegaleText && !is_null($organization->rappresentanteLegale)) {
            if ($onlyIds) {
                $organizationReferees[] = $organization->rappresentanteLegale->user_id;
            } else {
                $organizationReferees[] = $organization->rappresentanteLegale;
            }
        }
        if (!is_null($organization->referenteOperativo)) {
            if ($onlyIds) {
                $organizationReferees[] = $organization->referenteOperativo->user_id;
            } else {
                $organizationReferees[] = $organization->referenteOperativo;
            }
        }
        return $organizationReferees;
    }

    /**
     * @param int $userId
     * @param string $modelName
     * @param string $mmModelName
     * @param string $relationName
     * @return OrganizationsModelInterface[]
     * @throws InvalidConfigException
     */
    private static function getUserMainModels($userId, $modelName, $mmModelName, $relationName, $mmModelStatus)
    {
        /** @var Module $organizzazioniModule */
        $organizzazioniModule = Module::instance();
        /** @var Record $model */
        $model = $organizzazioniModule->createModel($modelName);
        /** @var Record $mmModel */
        $mmModel = $organizzazioniModule->createModel($mmModelName);
        /** @var ActiveQuery $query */
        $query = $model::find();
        $query->innerJoinWith($relationName);
        $query->andWhere([$mmModel::tableName() . '.user_id' => $userId]);
        $query->andWhere([$mmModel::tableName() . '.status' => $mmModelStatus]);
        $models = $query->all();
        return $models;
    }

    /**
     * This method returns all the organizations of an user.
     * @param int $userId
     * @return OrganizationsModelInterface[]
     * @throws InvalidConfigException
     */
    public static function getUserOrganizations($userId)
    {
        return static::getUserMainModels($userId, 'Profilo', 'ProfiloUserMm', 'profiloUserMms', ProfiloUserMm::STATUS_ACTIVE);
    }

    /**
     * This method returns all the headquarters of an user, if the module has headquarters.
     * @param int $userId
     * @return OrganizationsModelInterface[]
     * @throws InvalidConfigException
     */
    public static function getUserHeadquarters($userId)
    {
        return static::getUserMainModels($userId, 'ProfiloSedi', 'ProfiloSediUserMm', 'profiloSediUserMms', ProfiloSediUserMm::STATUS_ACTIVE);
    }

    /**
     * @return mixed|null
     */
    public static function getProfiloEntiTypeListReadyForSelect()
    {
        /** @var ProfiloEntiType $profiloEntiTypeModel */
        $profiloEntiTypeModel = Module::instance()->createModel('ProfiloEntiType');
        return ArrayHelper::map($profiloEntiTypeModel::find()->orderBy(['priority' => SORT_ASC])->all(), 'id', 'name');
    }

    /**
     * Create a community for the organization.
     * @param Profilo $model
     * @param string $managerStatus
     * @return bool
     */
    public static function createCommunity($model, $managerStatus = '', $type)
    {
        /** @var Module $organizationsModule */
        $organizationsModule = Module::instance();

        /** @var AmosCommunity $communityModule */
        $communityModule = Yii::$app->getModule('community');

        $title = ($model->title ? $model->title : '');
        $description = ($model->description ? $model->description : '');
        $context = $organizationsModule->model('Profilo');
        $managerRole = $model->getManagerRole();

        try {
            $model->community_id = $communityModule->createCommunity(
                $title,
                $type,
                $context,
                $managerRole,
                $description,
                $model,
                $managerStatus
            );

            $ok = $model->save(false);

            if ($ok) {
                // Add Rappresentante Legale e Referente Operativo come utenti di default
                if ($model->rappresentante_legale) {
                    $communityModule->createCommunityUser(
                        $model->community_id,
                        $managerStatus,
                        $managerRole,
                        $model->rappresentante_legale
                    );
                }

                if ($model->referente_operativo) {
                    $communityModule->createCommunityUser(
                        $model->community_id,
                        $managerStatus,
                        $managerRole,
                        $model->referente_operativo
                    );
                }
            }

        } catch (CommunityException $exception) {
            Yii::getLogger()->log($exception->getMessage(), Logger::LEVEL_ERROR);
            $ok = false;
        }

        return $ok;
    }

    /**
     * @param Profilo $model
     * @param AmosCommunity $communityModule
     */
    public static function updateCommunity($model, $communityModule)
    {
        $model->community->name = ($model->title ? $model->title : '');
        $model->community->description = ($model->description ? $model->description : '');
        $managerStatus = CommunityUserMm::STATUS_ACTIVE;
        $managerRole = $model->getManagerRole();

        try {
            // Used by OrganizzazioniUtility::updateCommunity()
            $oldAttrs = $model->community->getOldAttributes();
            $ok = $model->community->save(false);
            $communityId = $model->getCommunityId();

            if ($ok) {
                // Update Rappresentante legale as community member and manager
                if ($oldAttrs['rappresentante_legale'] != $model->rappresentante_legale) {
                    $communityModule->deleteCommunityUser($communityId, $oldAttrs['rappresentante_legale']);
                    $communityModule->createCommunityUser(
                        $communityId,
                        $managerStatus,
                        $managerRole,
                        $model->rappresentante_legale
                    );
                }

                // Update Referente Operativo as community memember and manager
                if ($oldAttrs['referente_operativo'] != $model->referente_operativo) {
                    $communityModule->deleteCommunityUser($communityId, $model->referente_operativo);
                    $communityModule->createCommunityUser(
                        $communityId,
                        $managerStatus,
                        $managerRole,
                        $model->referente_operativo
                    );
                }
            }
        } catch (\Exception $exception) {
            \Yii::getLogger()->log($exception->getMessage(), Logger::LEVEL_ERROR);
        }
    }

    /**
     * Check if there is at least one confirmed event manager only if there is a community. If not it return true.
     * @param Profilo $model
     * @param string $status
     * @return array
     * @throws InvalidConfigException
     */
    public static function findOrganizzazioneManagers($model, $status = '')
    {
        if (!$model->community_id) {
            return [];
        }

        $where = [
            'community_id' => $model->getCommunityId(),
            'role' => $model->getManagerRole()
        ];

        if ($status) {
            $where['status'] = $status;
        }

        $managers = CommunityUserMm::find()->andWhere($where)->all();

        return $managers;
    }

    /**
     * This method returns the query used in the organization-employees view
     * or OrganizationsMembersWidget to view organization employees.
     * @param Profilo $model
     * @param bool $isUpdate
     * @param array $showRoles
     * @param Module|null $organizzazioniModule
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public static function getOrganizationEmployeesQuery($model, $isUpdate, $showRoles = [], $organizzazioniModule = null, $excludeReferees = false)
    {
        if (is_null($organizzazioniModule)) {
            $organizzazioniModule = Module::instance();
        }

        /** @var ProfiloUserMm $profiloUserMmModel */
        $profiloUserMmModel = $organizzazioniModule->createModel('ProfiloUserMm');
        $profiloUserMmTable = $profiloUserMmModel::tableName();
        $userProfileTable = UserProfile::tableName();
        $userTable = User::tableName();

        if (!$isUpdate) {
            $query = $model->getProfiloUserMms();
        } else {
            $query = !empty($showRoles)
                ? $model->getProfiloUserMms()->andWhere([$profiloUserMmTable . '.role' => $showRoles])
                : $model->getProfiloUserMms();
        }

        if (!$isUpdate) {
            $query->andWhere([$profiloUserMmTable . '.status' => ProfiloUserMm::STATUS_ACTIVE]);
        }
        $query->innerJoin($userTable, $profiloUserMmTable . '.user_id = ' . $userTable . '.id AND ' . $userTable . '.deleted_at IS NULL');
        $query->innerJoin($userProfileTable, $userProfileTable . '.user_id = ' . $userTable . '.id AND ' . $userProfileTable . '.deleted_at IS NULL');

        $query->andWhere([$userProfileTable . '.attivo' => UserProfile::STATUS_ACTIVE]);
        $query->andWhere([$userTable . '.status' => User::STATUS_ACTIVE]);
        $query->andWhere(['<>', $userProfileTable . '.nome', UserProfileUtility::DELETED_ACCOUNT_NAME]);
        
        if ($excludeReferees || $organizzazioniModule->excludeRefereesFromEployeesLists) {
            $refereesUserIds = [];
            if ($model->rappresentante_legale) {
                $refereesUserIds[] = $model->rappresentante_legale;
            }
            if ($model->referente_operativo) {
                $refereesUserIds[] = $model->referente_operativo;
            }
            if (!empty($refereesUserIds)) {
                $query->andWhere(['not in', $profiloUserMmTable . '.user_id', $refereesUserIds]);
            }
        }

        return $query;
    }

    /**
     * This methods adds a user to the organization community.
     * @param Profilo $organization
     * @param int $userId
     * @param string $role
     * @param AmosCommunity|null $communityModule
     * @return bool
     * @throws CommunityException
     */
    public static function addOrganizationCommunityUser($organization, $userId, $role, $communityModule = null)
    {
        if (is_null($communityModule)) {
            $communityModule = AmosCommunity::instance();
        }
        return $communityModule->createCommunityUser(
            $organization->community_id,
            CommunityUserMm::STATUS_ACTIVE,
            $role,
            $userId
        );
    }

    /**
     * This methods adds a participant to the organization community.
     * @param Profilo $organization
     * @param int $userId
     * @param AmosCommunity|null $communityModule
     * @return bool
     * @throws CommunityException
     */
    public static function addOrganizationCommunityManager($organization, $userId, $communityModule = null)
    {
        return self::addOrganizationCommunityUser(
            $organization,
            $userId,
            $organization->getManagerRole(),
            $communityModule
        );
    }

    /**
     * This methods adds a participant to the organization community.
     * @param Profilo $organization
     * @param int $userId
     * @param AmosCommunity|null $communityModule
     * @return bool
     * @throws CommunityException
     */
    public static function addOrganizationCommunityParticipant($organization, $userId, $communityModule = null)
    {
        return self::addOrganizationCommunityUser(
            $organization,
            $userId,
            $organization->getBaseRole(),
            $communityModule
        );
    }

    /**
     * This methods removes a user to the organization community.
     * @param int $organizationCommunityId
     * @param int $userId
     * @param AmosCommunity|null $communityModule
     * @return bool
     * @throws Throwable
     * @throws StaleObjectException
     */
    public static function removeOrganizationCommunityUser($organizationCommunityId, $userId, $communityModule = null)
    {
        if (is_null($communityModule)) {
            $communityModule = AmosCommunity::instance();
        }
        return $communityModule->deleteCommunityUser($organizationCommunityId, $userId);
    }

    /**
     * Checks if the community is an organization community.
     * @param $communityId
     * @return bool
     */
    public static function isAnOrganizationCommunity($communityId)
    {
        $query = new Query();
        $query->from(Profilo::tableName());
        $query->andWhere(['deleted_at' => null]);
        $query->andWhere(['community_id' => $communityId]);
        $communities = $query->all();
        return (!empty($communities));
    }
    
    /**
     * This method returns the register link for the old or new applications.
     * @return string
     */
    public static function getRegisterLink()
    {
        if (\Yii::$app->isCmsApplication()) {
            if (\Yii::$app->params['linkConfigurations']['registrationLinkCommon']) {
                $strPosRes = strpos(\Yii::$app->params['linkConfigurations']['registrationLinkCommon'], '/');
                return (($strPosRes === false) || ($strPosRes > 0) ? '/' : '') . \Yii::$app->params['linkConfigurations']['registrationLinkCommon'];
            } else {
                return '/' . \amos\userauth\frontend\Module::getModuleName() . '/default/register';
            }
        } else {
            return '/' . AmosAdmin::getModuleName() . '/security/register';
        }
    }
    
    /**
     * This method returns the login link for the old or new applications.
     * @return string
     */
    public static function getLoginLink()
    {
        if (\Yii::$app->isCmsApplication()) {
            if (\Yii::$app->params['linkConfigurations']['loginLinkCommon']) {
                $strPosRes = strpos(\Yii::$app->params['linkConfigurations']['loginLinkCommon'], '/');
                return (($strPosRes === false) || ($strPosRes > 0) ? '/' : '') . \Yii::$app->params['linkConfigurations']['loginLinkCommon'];
            } else {
                return '/site/login';
            }
        } else {
            return '/' . AmosAdmin::getModuleName() . '/security/login';
        }
    }
}
