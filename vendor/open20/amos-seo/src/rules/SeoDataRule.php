<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\moodle\rules
 * @category   CategoryName
 */

namespace open20\amos\seo\rules;

use open20\amos\seo\models\SeoData;
use Yii;
use yii\log\Logger;
use yii\rbac\Rule;

/**
 * Class ShowWidgetIconMoodleRankingRule
 * @package open20\amos\moodle\rules
 */
class SeoDataRule extends Rule
{
    public $name = 'SeoDataRule';
    
    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        /**
         * @var $model SeoData
         */
        $model = $params['model'];

        //The base class name
        $baseClassName = \yii\helpers\StringHelper::basename($model->classname);
        
        $permissionType = $item->name;
        switch ($permissionType) {
            case 'SEODATA_READ':
                $modulePremission = strtoupper($baseClassName . '_READ');
                break;

            case 'SEODATA_CREATE':
                $modulePremission = strtoupper($baseClassName . '_CREATE');
                break;

            case 'SEODATA_UPDATE':
                $modulePremission = strtoupper($baseClassName . '_UPDATE');
                break;

            case 'SEODATA_DELETE':
                $modulePremission = strtoupper($baseClassName . '_DELETE');
                break;

            default:
                $modulePremission = null;
        }

        if (!is_null($modulePremission)) {
            $retVal = \Yii::$app->user->can($modulePremission, ['model' => $model->owner]);
        } else {
            $retVal = false;
        }

        return $retVal;
    }
}
