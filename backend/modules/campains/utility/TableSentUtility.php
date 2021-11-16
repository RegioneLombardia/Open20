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
 * Class TableSentUtility
 * @package backend\modules\campains\utility
 */
class TableSentUtility extends TableDynamicUtility
{
    protected static $tablePrefix = 'preference';
    protected static $tableSuffix = 'communication_sent';
    protected static $tableStringSeparator = '_';

    protected static $tableFields = [
        1 => 'gender',
        2 => 'birth_date',
        3 => 'email',
        4 => 'phone',
        5 => 'municipality',
        6 => 'province',
        7 => 'user_id',
        8 => 'preference_campain_channel_mm_id',
        9 => 'user_profile_age_group_id',
        10 => 'municipality_residence_id',
        11 => 'province_residence_id',
        12 => 'created_at',
        13 => 'communication_id',
        14 => 'mailup_group_id',
        15 => 'mailup_import_id',
        16 => 'name',
        17 => 'surname',
    ];

    protected static $tableFieldsTypes = [
        1 => 'varchar(255)',
        2 => 'date',
        3 => 'varchar(255)',
        4 => 'varchar(255)',
        5 => 'varchar(255)',
        6 => 'varchar(255)',
        7 => 'integer',
        8 => 'integer',
        9 => 'integer',
        10 => 'integer',
        11 => 'integer',
        12 => 'datetime',
        13 => 'integer',
        14 => 'integer',
        15 => 'integer',
        16 => 'varchar(255)',
        17 => 'varchar(255)',
    ];
    
}
