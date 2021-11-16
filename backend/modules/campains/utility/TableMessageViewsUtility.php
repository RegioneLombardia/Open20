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
class TableMessageViewsUtility extends TableDynamicUtility
{
    protected static $tablePrefix = 'preference_stats';
    protected static $tableSuffix = 'message_views';
    protected static $tableStringSeparator = '_';

    protected static $tableFields = [
        1 => 'count',
        2 => 'email',
        3 => 'id_message',
        4 => 'id_recipient',

        100 => 'created_at',
        101 => 'communication_id',
        102 => 'mailup_group_id',
    ];

    protected static $tableFieldsTypes = [
        1 => 'integer',
        2 => 'varchar(255)',
        3 => 'integer',
        4 => 'integer',

        100 => 'datetime',
        101 => 'integer',
        102 => 'integer',
    ];
    
}
