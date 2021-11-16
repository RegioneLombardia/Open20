<?php

/**
 * Handles the creation for table `post`.
 * Has foreign keys to the tables:
 *
 * - `user`
 * - `category`
 */
class m171031_150000_create_table_istat_comuni_cap extends \yii\db\Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('istat_comuni_cap', [
            'id' => $this->primaryKey(),
            'comune_id' => $this->integer()->notNull(),
            'cap' => $this->string()->notNull(),
            'sospeso' => $this->string(),
        ]);

        // creates index for column `comune_id`
        $this->createIndex(
            'idx-istat-comuni-cap-comune_id',
            'istat_comuni_cap',
            'comune_id'
        );

        // add foreign key for table `istat_comuni`
        $this->addForeignKey(
            'fk-istat-comuni-cap-comune_id',
            'istat_comuni_cap',
            'comune_id',
            'istat_comuni',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        //drops index for column `author_id`
        $this->dropIndex(
            'idx-istat-comuni-cap-comune_id',
            'istat_comuni_cap'
        );
        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-istat-comuni-cap-comune_id',
            'istat_comuni_cap'
        );

        $this->dropTable('istat_comuni_cap');

    }
}