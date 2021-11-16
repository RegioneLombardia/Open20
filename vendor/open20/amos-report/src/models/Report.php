<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos-report
 * @category   Model
 */


namespace open20\amos\report\models;

use open20\amos\notificationmanager\behaviors\NotifyBehavior;
use yii\helpers\ArrayHelper;

class Report extends \open20\amos\report\models\base\Report
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
            'NotifyBehavior' => [
                'class' => NotifyBehavior::className(),
                'conditions' => [],
            ]
        ]);
    }

}