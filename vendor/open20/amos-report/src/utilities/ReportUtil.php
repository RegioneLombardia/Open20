<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\report
 * @category   CategoryName
 */

namespace open20\amos\report\utilities;

use open20\amos\report\AmosReport;
use open20\amos\report\models\Report;

/**
 * Class ReportUtil
 * @package open20\amos\report\utilities
 */
class ReportUtil
{

    public static function translateArrayValues($arrayValues)
    {
        $translatedArrayValues = [];
        foreach ($arrayValues as $key => $title) {
            $translatedArrayValues[$key] = $title;
        }
        return $translatedArrayValues;
    }

    /**
     * @param string $className
     * @param integer $context_id
     * @return \yii\db\ActiveRecord
     */
    public static function retrieveReportsQuery($className, $context_id){
        return Report::find()
                ->andWhere([
                    'classname' => $className,
                    'context_id' => $context_id
                ]);
    }

    /**
     * @param string $className
     * @param integer $context_id
     * @return array
     */
    public static function retrieveReports($className, $context_id){
        return self::retrieveReportsQuery($className, $context_id)
            ->asArray()
            ->all();
    }

    /**
     * @param string $className
     * @param integer $context_id
     * @return array
     */
    public static function retrieveUnreadReports($className, $context_id){
        return self::retrieveReportsQuery($className, $context_id)
            ->andWhere([
                'read_at' => null,
                'read_by' => 0
            ])
            ->asArray()
            ->all();
    }

    /**
     * @param string $className
     * @param integer $context_id
     * @return integer
     */
    public static function retrieveReportsCount($className, $context_id){
        return self::retrieveReportsQuery($className, $context_id)
            ->count();
    }

}
