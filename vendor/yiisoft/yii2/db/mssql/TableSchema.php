<?php
/**
 */

namespace yii\db\mssql;

/**
 * TableSchema represents the metadata of a database table.
 *
 * @since 2.0
 */
class TableSchema extends \yii\db\TableSchema
{
    /**
     * @var string name of the catalog (database) that this table belongs to.
     * Defaults to null, meaning no catalog (or the current database).
     */
    public $catalogName;
}
