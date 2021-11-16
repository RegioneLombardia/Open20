<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos-basic-template
 * @category   CategoryName
 */

namespace open20\amos\utility\commands;

use open20\amos\core\utilities\ClassUtility;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;

class UtilityController extends Controller
{

    public
        $moduleName = null;

    /**
     * 
     * @param type $actionID
     * @return type
     */
    public function options($actionID)
    {
        return ['moduleName', 'bulletCounters'];
    }

    /**
     * 
     */
    public function actionBulletCounters()
    {
        $classname = 'open20\amos\utility\controllers\BulletCountersController';
        
        try {
            if (ClassUtility::classExist($classname)) {
                $bulletCounters = new $classname('_cbc', null);
                Console::stdout($bulletCounters->actionIndex());
            } else {
                Console::stdout('Object not found');
            }
        } catch (Exception $ex) {
            
        }
    }

    /**
     *
     */
    public function actionResetDashboardByModule()
    {
        $classname = 'open20\amos\dashboard\utility\DashboardUtility';

        try {
            if (ClassUtility::classExist($classname)) {
                if (!empty($this->moduleName)) {
                    Console::stdout('Reset dashboard for:' . $this->moduleName);
                    $classname::resetDashboardsByModule($this->moduleName);
                } else {
                    Console::stdout('Missing moduleName param.');
                }
            }
        } catch (\yii\base\Exception $ex) {
            
        }
    }

}
