<?php
/**
 */

namespace yii\db\sqlite;

use yii\db\ColumnSchemaBuilder as AbstractColumnSchemaBuilder;

/**
 * ColumnSchemaBuilder is the schema builder for Sqlite databases.
 *
 * @since 2.0.8
 */
class ColumnSchemaBuilder extends AbstractColumnSchemaBuilder
{
    /**
     * {@inheritdoc}
     */
    protected function buildUnsignedString()
    {
        return $this->isUnsigned ? ' UNSIGNED' : '';
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        switch ($this->getTypeCategory()) {
            case self::CATEGORY_PK:
                $format = '{type}{check}{append}';
                break;
            case self::CATEGORY_NUMERIC:
                $format = '{type}{length}{unsigned}{notnull}{unique}{check}{default}{append}';
                break;
            default:
                $format = '{type}{length}{notnull}{unique}{check}{default}{append}';
        }

        return $this->buildCompleteString($format);
    }
}
