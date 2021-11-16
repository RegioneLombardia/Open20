<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\utility
 * @category   CategoryName
 */

namespace backend\modules\campains\utility;

use open20\amos\core\record\RecordDynamicModel;
use Yii;

/**
 * Class TableDynamicUtility
 * @package backend\modules\campains\utility
 */
class TableDynamicUtility
{
    protected static $tablePrefix = null;
    protected static $tableSuffix = null;
    protected static $tableStringSeparator = null;

    protected static $tableFields = [
    ];

    protected static $tableFieldsTypes = [
        
    ];

    /**
     * @param int $channelMmId
     * @return string
     */
    public static function getTableName($channelMmId = null)
    { 
        $stringChannel =  !is_null($channelMmId)? ($channelMmId . static::$tableStringSeparator) : '';
        return static::$tablePrefix . static::$tableStringSeparator . $stringChannel . static::$tableSuffix;
    }

    /**
     *
     * @param integer $channelMmId
     * @param string $db
     * @return RecordDynamicModel
     */
    public static function buildModel($channelMmId, string $db = null)
    {
        $tableName = self::getTableName($channelMmId);
        

        $model = new RecordDynamicModel();
        $model->setTableName($tableName);
        if(!is_null($db)){
            $model->setDb($db);
        }
        
        $fieldTypes = self::getTableFieldsTypes();
        foreach (self::getTableFields() as $key => $field) {
            $model->defineAttribute($field);
            $model->defineType($field, $fieldTypes[$key]);
        }

        return $model;
    }


    public static function getTableFields()
    {
        return static::$tableFields;
    }

    public static function getTableFieldsTypes()
    {
        return static::$tableFieldsTypes;
    }

}
