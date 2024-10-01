<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\utility\drivers
 * @category   CategoryName
 */

namespace open20\amos\utility\drivers\base;

use open20\amos\notificationmanager\models\NotificationChannels;
use open20\amos\utility\interfaces\bcDriverInterface;
use open20\amos\utility\models\UpdateContents;
use open20\amos\utility\models\BulletCounters;
use Yii;
use yii\db\Query;
use yii\db\Expression;
use yii\helpers\Console;
use yii\log\Logger;
use yii\base\BaseObject;
use Exception;

class bcDriver extends \yii\base\BaseObject implements bcDriverInterface
{
    /**
     * @var
     */
    public $widgetIconNames;

    /**
     *
     * @var type 
     */
    public $user_id;

    /**
     * @var type 
     */
    public $module;

    /**
     *
     * @var type 
     */
    public $modelClassName;

    /**
     *
     * @var type 
     */
    public $query;

    /**
     *
     * @var type 
     */
    public $cwhActiveQuery;

    /**
     *
     * @var type 
     */
    public $counter;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->modelClassName  = null;
        $this->query           = null;
        $this->widgetIconNames = [];
        $this->counter         = 0;

        $tableName = $this->module;
        if ($this->module == 'admin') {
            $tableName = 'user';
        } else if ($this->module == 'partnershipprofiles') {
            $tableName = 'partnership_profiles';
        } else if ($this->module == 'events') {
            $tableName = 'event';
        } else if ($this->module == 'discussioni') {
            $tableName = 'discussioni_topic';
        } else if ($this->module == 'organizzazioni') {
            $tableName = 'profilo';
        } else if ($this->module == 'news') {
            $tableName = 'news';
        } else if ($this->module == 'een') {
            $tableName = 'een_partnership_proposal';
        } else if ($this->module == 'collaborations') {
            $tableName = 'collaboration_proposals';
        }


        $modelsEnabled = \open20\amos\cwh\models\CwhConfigContents::find()
            ->addSelect('tablename, classname')
            ->andWhere(['tablename' => $tableName])
            ->asArray()
            ->one();


        if (!(empty($modelsEnabled))) {
            $this->cwhActiveQuery               = new \open20\amos\cwh\query\CwhActiveQuery(
                $modelsEnabled['classname'],
                [
                'queryBase' => $modelsEnabled['classname']::find(),
                'userId' => $this->user_id
                ]
            );
        }
    }

    /**
     * @inheritdoc
     */
    public function calculateBulletCounters()
    {
//        $logMessage = 'User: ' . $this->user_id . ' - Macro area: ' . $this->module;
        $i = count($this->widgetIconNames);
        $a = 0;
        foreach ($this->widgetIconNames as $widget => $namespace) {
            try {
                $a++;
                call_user_func_array([$this, 'search'.$widget], []);

                $this->counter += $this->updateBulletCounters($widget, $namespace, ($i == $a ? true : false));
            } catch (Exception $ex) {
                Yii::getLogger()->log($ex->getMessage(), \yii\log\Logger::LEVEL_ERROR);
            }
        }
    }

    /**
     * 
     * @param type $widget
     */
    public function updateBulletCounters($widget = null, $namespace = null, $reset = false)
    {
        // Nothing to do!
        if ($this->query == null) {
            return 0;
        }

        $counter  = 0;
        $notifier = Yii::$app->getModule('notify');
        if ($notifier) {
            $counter = $notifier->countNotRead(
                $this->user_id, $this->modelClassName, $this->query
            );

            /**
             * Something was changed?
             */
            if ($counter > 0 && $reset == true) {
                // Turn off counter
                $notifier->notificationOff(
                    $this->user_id, $this->modelClassName, $this->query, NotificationChannels::CHANNEL_READ
                );
            }
            static::updateBulletCountersTable($this->user_id, $this->module, $namespace, $counter);
        }

        return $counter;
    }

    /**
     * Update bullet counter table for specified user
     * @param type $namespace
     */
    public static function updateBulletCountersTable($user_id, $moduleName, $namespace = null, $counter = 0,
                                                     $forceValue = false)
    {
        if ($counter < 0) {
            $counter = 0;
        }

        $wid = BulletCounters::getAmosWidgetsIconNameID($moduleName, $namespace);

        $modelObj = BulletCounters::find()
            ->andWhere([
                'widget_icon_id' => $wid['id'],
                'user_id' => $user_id
            ])
            ->one();

        if (empty($modelObj)) {
            $modelObj = new BulletCounters();
        }

        if ($forceValue == true) {
            $modelObj->user_id        = $user_id;
            $modelObj->widget_icon_id = $wid['id'];
            $modelObj->counter        = $counter; // Check if it's correct or not
            $modelObj->pre_counter    = $counter; // Check if it's correct or not          
            $modelObj->microtime      = 0;
        } else {
            $modelObj->user_id        = $user_id;
            $modelObj->widget_icon_id = $wid['id'];
            $modelObj->counter        = $modelObj->counter + $counter; // Check if it's correct or not
            $modelObj->pre_counter    = $modelObj->counter; // Check if it's correct or not
            $modelObj->microtime      = 0;
        }

        $transaction = \Yii::$app->db->beginTransaction();
        $modelObj->save();
        $transaction->commit();
    }
}