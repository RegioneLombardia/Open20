<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\utility\drivers
 * @category   CategoryName
 */

namespace open20\amos\utility\controllers;

use open20\amos\admin\models\UserProfile;
use open20\amos\core\user\User;
use open20\amos\admin\models\search\UserProfileSearch;
use open20\amos\dashboard\models\search\AmosUserDashboardsSearch;
use open20\amos\core\models\ModelsClassname;
use open20\amos\utility\models\UpdateContents;
use open20\amos\notificationmanager\AmosNotify;
use Exception;
use Yii;
use yii\console\Controller;
use yii\filters\AccessControl;
use yii\db\Query;
use yii\db\Expression;
use yii\helpers\Console;
use yii\log\Logger;
use yii\db\ActiveRecord;

/**
 * 
 */
class BulletCountersController extends Controller
{
    /**
     * 
     */
    protected $pidFile;

    /**
     *
     * @var type 
     */
    protected $isRunning;

    /**
     * Something somewhere is changed?
     * @var type 
     */
    protected $updatesModule;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $this->_write('Start DateTime: '.date('Y-m-d H:i:s'));
        if ($this->checkAlreadyRun() == true) {
            $this->_write('Script already running! Exit');
            exit(0);
        }

        parent::init();
        $this->updatesModule = [];

        if (isset(\Yii::$app->params['disableBulletCounters']) && (\Yii::$app->params['disableBulletCounters'] === true)) {
            $this->_write('Bullet Counters are disabled on this platform');
            exit(0);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function __destruct()
    {
        $this->_write('End DateTime: '.date('Y-m-d H:i:s'));

        if (!$this->isRunning && file_exists($this->pidFile) && is_writeable($this->pidFile)) {
            unlink($this->pidFile);
        }
    }

    /**
     * List of possible modules - 2019-11-06
     * 
     * admin                Yes     --> user
     * challenge            ---     --> ???
     * community            Yes     --> dashboard della community
     * dashboard            ---     --> dashboard i widgets leggono dai vari widgets che compongono la relativa sezione
     * discussioni          Yes     --> discussion_topic
     * documenti            Yes     --> 
     * een                  Yes     --> 
     * email                ---     -->
     * events               Yes     -->
     * landing              ---     -->
     * landing-config       ---     -->
     * news                 Yes     -->
     * opportunity          ---     -->
     * organizations        Yes     -->
     * partnership_profiles Yes     -->
     * projects             Yes     -->
     * result               Yes     -->
     * showcase_project     Yes     -->
     * sondaggi             Yes     -->
     * ticket               ---     -->
     * translation          ---     -->
     * 
     * @return type
     */
    public function actionIndex()
    {
        $this->updatesModule = UpdateContents::find()
            ->select('id, module, updates')
            ->andWhere(['>', 'updates', 0])
            ->asArray()
            ->all();

        $organizzazioni = false;
        if (!empty($this->updatesModule)) {
            // RAW fix mapping between module and macro area updates
            foreach ($this->updatesModule as $key => $module) {
                if ($module['module'] == 'user') {
                    $module['module']          = 'admin';
                    $this->updatesModule[$key] = $module;
                } else if ($module['module'] == 'partnership_profiles') {
                    $module['module']          = 'partnershipprofiles';
                    $this->updatesModule[$key] = $module;
                } else if ($module['module'] == 'event') {
                    $module['module']          = 'events';
                    $this->updatesModule[$key] = $module;
                } else if ($module['module'] == 'discussioni_topic') {
                    $module['module']          = 'discussioni';
                    $this->updatesModule[$key] = $module;
                } else if ($module['module'] == 'profilo') {
                    $module['module']          = 'organizzazioni';
                    $this->updatesModule[$key] = $module;
                    $organizzazioni            = true;
                }
            }

            // in bootstrap
            $this->updatesModule[] = ['id' => null, 'module' => 'MyActivities', 'updates' => 1];

            $notify = AmosNotify::getInstance();
            $this->loadUsersDashboards($organizzazioni);
        }
    }

    /**
     * TBD
     * Attivare transaction?
     * 
     * try {
     *      $connection = \Yii::$app->db;
     *      $transaction = null;
     * 
     *      . . . 
     * 
     *      $transaction->commit();
     *      $transaction = null;
     *      gc_collect_cycles();
     * 
     *  catch (\Throwable $e) {
     *      if(!is_null($transaction))
     *          {
     *              $transaction->rollBack();
     *          }
     *      throw $e;
     *  }
     * 
     * 
     * SELECT *
     * FROM amos_user_dashboards AS AUD
     * INNER JOIN amos_user_dashboards_widget_mm AS AUDWMM ON AUDWMM.amos_user_dashboards_id = AUD.id
     * INNER JOIN user_profile AS UP ON UP.user_id = AUD.user_id
     * INNER JOIN amos_widgets AS AW ON AW.id = AUDWMM.amos_widgets_id AND AW.type = 'ICON'
     * WHERE
     *      UP.id = 1
     *      AND UP.attivo = 1
     *      AND AUD.slide = 1
     *      AND AW.type = 'ICON'
     * 
     * @param type $users
     */
    public function loadUsersDashboards($organizzazioni = false)
    {
        $users = $this->loadUsers();

        $amosUserDashboardsSearch = new AmosUserDashboardsSearch();
        $dashTable                = $amosUserDashboardsSearch::tableName();
        $modelsTable              = ModelsClassname::tableName();

        // Resets models after all counting
        $resetModelsIds = [];

        $transaction = ActiveRecord::getDb()->beginTransaction();
        $commit      = true;

        foreach ($users as $user) {
            $uid = $user['user_id'];

            /** @var AmosUserDashboards $dashboard */
            $dashboards = $amosUserDashboardsSearch
                ->find()
                ->select([$dashTable.'.module', $modelsTable.'.classname'])
                ->leftJoin(
                    $modelsTable, $modelsTable.'.module'.' = '.$dashTable.'.module'
                )
                ->andWhere([
                    'user_id' => $uid,
                    $dashTable.'.deleted_at' => null,
                ])
                ->asArray()
                ->all()
            ;

            $dashboards[] = [
                'module' => 'MyActivities',
                'classname' => 'open20\amos\myactivities\widgets\icons\WidgetIconMyActivities'
            ];

            if ($organizzazioni == true) {
                $dashboards[] = [
                    'module' => 'Organizzazioni',
                    'classname' => 'open20\amos\organizzazioni\widgets\icons\WidgetIconProfilo'
                ];
            }

            $tmp = [];
           
            foreach ($dashboards as $dashboard) {
                $module       = $dashboard['module'];
                $tmp[$module] = $dashboard;
            }
            $dashboards = $tmp;

            foreach ($this->updatesModule as $module) {
                if (isset($dashboards[$module['module']])) {
                    $resetModelsIds[$module['id']] = $module['id'];

                    $dashboard = $dashboards[$module['module']];

                    Yii::$app->user->setIdentity(
                        User::findOne(['id' => $uid])
                    );

                    if (isset($dashboard['classname'])) {
                        $className = $dashboard['classname'];
                        $bcModule  = 'open20\amos\utility\drivers\bcDriver'.ucfirst($dashboard['module']);

                        try {
                            $this->_write('Start working on user '.$uid.' for '.$bcModule.' module');

                            $driver = \Yii::createObject([
                                    'class' => $bcModule,
                                    'module' => $module['module'],
                                    'user_id' => $uid
                            ]);

                            $driver->calculateBulletCounters();
                        } catch (Exception $ex) {
                            Yii::getLogger()->log($ex->getMessage(), \yii\log\Logger::LEVEL_ERROR);
                            $this->_write($ex->getMessage().' - '.$bcModule.' - '.$ex->getFile().' - '.$ex->getLine());
                            $commit = false;
                        }
                    }
                }
            }
        }


        $sections = UpdateContents::find()->andWhere(['id' => $resetModelsIds])->all();
        if (!empty($sections)) {
            foreach ($sections as $section) {
                $section->updates = 0;
                $section->save();
            }
        }


        // commit all or not, or just commit/rollback for single user?
        // All is fine?
        if ($commit === true) {
            $transaction->commit();
//            $transaction->rollBack();
            $this->_write('Commit all changes');
        } else {
            $transaction->rollBack();
            $this->_write('Something was wrong, check logs, rollBack all changes');
        }
    }

    /**
     * Find all users id and return them as an array
     *  
     * @return type
     */
    public function loadUsers()
    {
        $userTable        = User::tableName();
        $userProfileTable = UserProfile::tableName();

        $userProfileModel = new UserProfileSearch();

        $today   = date('Y-m-d');
        $lastDay = date('Y-m-d', strtotime($today." - 3 days"));
        try {
            $users = $userProfileModel
                ->find()
                ->select([$userProfileTable.'.user_id'])
                ->innerJoinWith(['user'], false)
                ->andWhere([
                    $userProfileTable.'.validato_almeno_una_volta' => UserProfile::STATUS_ACTIVE,
                    $userProfileTable.'.deleted_at' => null
                ])
                ->andWhere(['>', 'ultimo_accesso', $lastDay])
                ->asArray()
                ->all()
            ;
        } catch (Exception $ex) {
            \Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
            $users = [];
        }

        return $users;
    }

    /**
     * Return true is class is a Console instance
     * 
     * @return type
     */
    public function isConsoleMode()
    {
        return Yii::$app instanceof \yii\console\Application;
    }

    /**
     * Return true is class is a Web instance
     * 
     * @return type
     */
    public function isWebMode()
    {
        return Yii::$app instanceof \yii\web\Application;
    }

    /**
     * 
     * @param type $msg
     */
    public function _write($msg = null)
    {
        if ($this->isConsoleMode()) {
            Console::stdout($msg.PHP_EOL);
        } else {
            pr($msg);
        }
    }

    /**
     * 
     * @return boolean
     */
    public function checkAlreadyRun()
    {
        $tmpDir        = \Yii::$app->runtimePath;
        $this->pidFile = $tmpDir.'/bc.pid';

        $this->isRunning = false;

        if (is_writable($this->pidFile) || is_writable($tmpDir)) {
            if (file_exists($this->pidFile)) {
                $pid             = (int) trim(file_get_contents($this->pidFile));
                $this->isRunning = true;
            }
        } else {
            $this->_write('Cannot write bc pid lock file. Exit script');
            exit(0);
        }

        if ($this->isRunning === false) {
            $pid = getmygid();
            file_put_contents($this->pidFile, $pid);
        }

        return $this->isRunning;
    }
}