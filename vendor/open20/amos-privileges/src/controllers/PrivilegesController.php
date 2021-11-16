<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\privileges
 * @category   CategoryName
 */

namespace open20\amos\privileges\controllers;


use open20\amos\dashboard\utility\DashboardUtility;
use open20\amos\privileges\AmosPrivileges;
use open20\amos\privileges\events\PrivilegesEvent;
use open20\amos\privileges\utility\PrivilegesUtility;
use yii\base\Event;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\rbac\DbManager;
use yii\rbac\Item;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

use open20\amos\privileges\assets\AmosPrivilegesAsset;
use Yii;

/**
 * Class PrivilegesController
 * @package open20\amos\privileges\controllers
 */
class PrivilegesController extends Controller
{
    const BEFORE_ASSIGN_PRIVILEGE = 'before_assign_privilege';
    const AFTER_ASSIGN_PRIVILEGE = 'after_assign_privilege';
    const BEFORE_REVOKE_PRIVILEGE = 'before_revoke_privilege';
    const AFTER_REVOKE_PRIVILEGE = 'after_revoke_privilege';

    /**
     * @var string $layout Layout per la dashboard interna.
     */
    public $layout = 'main';

    /**
     * @var DbManager $authManager
     */
    protected $authManager = null;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {

        $rolesEnabled = [
            'PRIVILEGES_MANAGER'
        ];

        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'manage-privileges',
                            'enable',
                            'disable',
                            'save-domains',
                            'save-categorie-roles'
                        ],
                        'roles' => $rolesEnabled,
                    ],
                ],
                'denyCallback' => function ($rule) {
                    if (\Yii::$app->getUser()->isGuest) {
                        \Yii::$app->getSession()->addFlash('warning',
                            AmosPrivileges::t('amosprivileges', 'Session expired, please log in.'));
                        \Yii::$app->getUser()->loginRequired();
                    }
                    throw new ForbiddenHttpException(
                        AmosPrivileges::t('amosprivileges', 'You are not authorized to view this page')
                    );
                }
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->setUpLayout();

        AmosPrivilegesAsset::register(Yii::$app->view);

        $this->authManager = \Yii::$app->authManager;
    }

    /**
     * @param integer $id - The user id for whom we are managing privileges
     * @return string - the rendered view
     */
    public function actionManagePrivileges($id)
    {
        $cwhNodes = [];
        $utility = new PrivilegesUtility(['userId' => $id]);
        $array = $utility->getPrivilegesArray( false);

        $cwhModule = \Yii::$app->getModule('cwh');
        if($cwhModule) {
            $listNetworks = $cwhModule->getUserNetworks($id);
            if(!empty($listNetworks)) {
                $cwhNodes = ArrayHelper::map($listNetworks, 'id', 'text');
            }
        }
        return $this->render('manage-privileges', [
            'userId' => $id,
            'array' => $array,
            'cwhNodes' => $cwhNodes
        ]);

    }

    /**
     * @param $userId
     * @param $priv
     * @param $type
     * @param bool $isCwh
     * @param string $anchor
     * @return \yii\web\Response
     */
    public function actionEnable($userId, $priv, $type, $isCwh = false, $anchor = '')
    {
        if(!$isCwh) {
            $authManager = \Yii::$app->authManager;
            if ($type == Item::TYPE_ROLE) {
                $privilege = $authManager->getRole($priv);
                $flashError = '#assign_role_error';
            } else {
                $privilege = $authManager->getPermission($priv);
                $flashError = '#assign_permission_error';
            }
            $event = new PrivilegesEvent();
            $event->userId = $userId;
            $event->privilege = $priv;
            try {
                $this->trigger(self::BEFORE_ASSIGN_PRIVILEGE, $event);
            } catch (\Exception $exception) {
                Yii::$app->getSession()->addFlash('danger', AmosPrivileges::t('amosprivileges', '#error_before_assign_privilege', ['authItemName' => $priv]));
            }
            try {
                $authManager->assign($privilege, $userId);
                DashboardUtility::resetDashboardsByUser($userId);
                \Yii::$app->authManager->deleteAllCache();
            } catch (\Exception $exception) {
                Yii::$app->getSession()->addFlash('danger', AmosPrivileges::t('amosprivileges', $flashError, ['authItemName' => $priv]));
            }
            try {
                $this->trigger(self::AFTER_ASSIGN_PRIVILEGE, $event);
            } catch (\Exception $exception) {
                Yii::$app->getSession()->addFlash('danger', AmosPrivileges::t('amosprivileges', '#error_after_assign_privilege', ['authItemName' => $priv]));
            }
        }
        return $this->redirect(['manage-privileges', 'id' => $userId, '#' => $anchor]);
    }

    /**
     * @param $userId
     * @param $priv
     * @param $type
     * @param bool $isCwh
     * @param string $anchor
     * @return \yii\web\Response
     */
    public function actionDisable($userId, $priv, $type, $isCwh = false, $anchor = '')
    {
        if(!$isCwh) {
            $authManager = \Yii::$app->authManager;
            if($type == Item::TYPE_ROLE){
                $privilege = $authManager->getRole($priv);
                $flashError = '#revoke_role_error';
            } else {
                $privilege = $authManager->getPermission($priv);
                $flashError = '#revoke_permission_error';
            }
            $event = new PrivilegesEvent();
            $event->userId = $userId;
            $event->privilege = $priv;
            try {
                $this->trigger(self::BEFORE_REVOKE_PRIVILEGE, $event);
            } catch (\Exception $exception) {
                Yii::$app->getSession()->addFlash('danger', AmosPrivileges::t('amosprivileges', '#error_before_revoke_privilege', ['authItemName' => $priv]));
            }
            try {
                $revokeSuccessfull = $authManager->revoke($privilege, $userId);
                if (!$revokeSuccessfull) {
                    Yii::$app->getSession()->addFlash('danger', AmosPrivileges::t('amosprivileges', $flashError, ['authItemName' => $priv]));
                } else {
                    DashboardUtility::resetDashboardsByUser($userId);
                    \Yii::$app->authManager->deleteAllCache();
                }
            } catch (\Exception $exception) {
                Yii::$app->getSession()->addFlash('danger', AmosPrivileges::t('amosprivileges', $flashError, ['authItemName' => $priv]));
            }
            try {
                $this->trigger(self::AFTER_REVOKE_PRIVILEGE, $event);
            } catch (\Exception $exception) {
                Yii::$app->getSession()->addFlash('danger', AmosPrivileges::t('amosprivileges', '#error_after_revoke_privilege', ['authItemName' => $priv]));
            }
        } else {
            $cwhAuthAssigns = \open20\amos\cwh\models\CwhAuthAssignment::find()->andWhere(['item_name' => $priv, 'user_id' => $userId])->all();
            foreach ($cwhAuthAssigns as $cwhAuthAssign){
                $cwhAuthAssign->delete();
            }
        }
        return $this->redirect(['manage-privileges', 'id' => $userId, '#' => $anchor]);
    }

    /**
     * @param $userId
     * @param string $anchor
     * @return \yii\web\Response
     */
    public function actionSaveDomains($userId, $anchor = '')
    {
        $post = \Yii::$app->request->post();
        if(!empty($post['auth-assign'])){
            $authAssign = $post['auth-assign'];

            $newDomains = !empty($authAssign['newDomains'])? $authAssign['newDomains'] : [] ;
            $savedDomainsString = $authAssign['savedDomains'];
            $savedDomains =  !empty($savedDomainsString)? explode(',',$savedDomainsString) : [];
            $itemName = $authAssign['class_name'];
            //check if a domain is to delete or to be added or do nothing
            if(!empty($newDomains)){
                foreach ($newDomains as $newDomain){
                    if(empty($savedDomains) || !in_array($newDomain, $savedDomains)){
                        //if no domain is saved or if a domain has been added we save a new chw auth assignment row
                        $authAssignRow = new \open20\amos\cwh\models\CwhAuthAssignment([
                            'user_id' => $userId,
                            'item_name' => $itemName,
                            'cwh_nodi_id' => $newDomain
                        ]);
                        $authAssignRow->save(false);
                    }
                }
            }
            if(!empty($savedDomains)){
                foreach ($savedDomains as $savedDomain){
                    if(empty($newDomains) || !in_array($savedDomain, $newDomains)){
                        //if one or all domains have been removed we delete the cwh auth assignment row
                        $authAssignRow = \open20\amos\cwh\models\CwhAuthAssignment::findOne([
                            'user_id' => $userId,
                            'item_name' => $itemName,
                            'cwh_nodi_id' => $savedDomain
                        ]);
                        $authAssignRow->delete();
                    }
                }
            }
        }
        return $this->redirect(['manage-privileges', 'id' => $userId, '#' => $anchor]);
    }

    /**
     * @param null $layout
     * @return bool
     */
    public function setUpLayout($layout = null)
    {
        if ($layout === false) {
            $this->layout = false;
            return true;
        }
        $this->layout = (!empty($layout)) ? $layout : $this->layout;
        $module = \Yii::$app->getModule('layout');
        if (empty($module)) {
            if (strpos($this->layout, '@') === false) {
                $this->layout = '@vendor/open20/amos-core/views/layouts/'.(!empty($layout) ? $layout : $this->layout);
            }
            return true;
        }
        return true;
    }

    /**
     * @param $userId
     * @param string $anchor
     * @return \yii\web\Response
     */
    public function actionSaveCategorieRoles($userId, $anchor = '')
    {
        $post = \Yii::$app->request->post();
        if(!empty($post['auth-assign-roles'])){
            $authAssign = $post['auth-assign-roles'];
            $newDomains = !empty($authAssign['newDomains'])? $authAssign['newDomains'] : [] ;
            $modulo=\Yii::$app->getModule($authAssign['name']);
            $class=$modulo::getModelCategoryRole();
            $class::deleteAll('user_id = '.$userId);
            $attributeCategory=array_keys((new $class)->getAttributes())[1];
          foreach ($newDomains as $nd){
              $model=(new $class);
              $model->$attributeCategory=(int)$nd;
              $model->user_id=$userId;
              $model->role=$authAssign['rolename'];
              $model->save();
         
          }
        }
        return $this->redirect(['manage-privileges', 'id' => $userId, '#' => $anchor]);
    }
}