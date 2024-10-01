<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    common/console/migrations
 * @category
 */

use yii\db\Migration;

class m230721_114111_add_column_expression_of_interests extends Migration
{

    /**
     * Bullet counter table
     * @var type 
     */
    protected $tableName;

    /**
     *
     * @var type 
     */
    protected $tableOptions;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->tableName = '{{%collaboration_expressions_of_interest}}';
        $this->tableOptions = null;
    }

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(
            $this->tableName,
            'manifestazioneinteresse',
            $this->text(512)->defaultValue(null)
                ->comment('Manifestazione di Interesse')->after('id')
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'manifestazioneinteresse');
    }

}