<?php
/**
 */

namespace yii2tech\spreadsheet;

/**
 * SerialColumn displays a column of row numbers (1-based).
 *
 * To add a SerialColumn to the {@see Spreadsheet}, add it to the {@see Spreadsheet::$columns} configuration as follows:
 *
 * ```php
 * 'columns' => [
 *     [
 *         'class' => \yii2tech\spreadsheet\SerialColumn::class,
 *     ],
 *     // ...
 * ]
 * ```
 *
 * @since 1.0
 */
class SerialColumn extends Column
{
    /**
     * {@inheritdoc}
     */
    public $header = '#';


    /**
     * {@inheritdoc}
     */
    public function renderDataCellContent($model, $key, $index)
    {
        return $index + 1;
    }
}