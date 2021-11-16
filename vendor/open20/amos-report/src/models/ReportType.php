<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos-report
 * @category   CategoryName
 */


namespace open20\amos\report\models;

use yii\helpers\ArrayHelper;

class ReportType extends \open20\amos\report\models\base\ReportType
{
    /**
     */
    public function init()
    {
        parent::init();
    }

    public function afterFind()
    {
        parent::afterFind();
    }
    /**
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
        ]);
    }
}