<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */

use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        } 

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->defaultValue(null)->unique(),
            'auth_key' => $this->string(32)->notNull()->defaultValue(''),
            'password_hash' => $this->string()->notNull()->defaultValue(''),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull()->defaultValue(0),
            'updated_at' => $this->integer()->notNull()->defaultValue(0),
            'deleted_at' => $this->dateTime()->defaultValue(null)->comment('Cancellato il'),
            'created_by' => $this->integer(11)->defaultValue(null)->comment('Creato da'),
            'updated_by' => $this->integer(11)->defaultValue(null)->comment('Aggiornato da'),
            'deleted_by' => $this->integer(11)->defaultValue(null)->comment('Cancellato da')
        ], $tableOptions);        
        
        // Aggiungo l'amministratore
        $this->batchInsert('{{%user}}',['id', 'username', 'auth_key', 'password_hash', 'password_reset_token', 'email', 'status'], [
            [1, 'admin', '8uSwKw27mKv-SprmqWzK8K5gvlpGnr8v', '$2y$13$ab216g/Nd6qwBowDuYDnNOTsYvrEwzcXsiSxc1zQGYaG5H2wnTNY.', 'bqDiF8NgsMNjHtGnSSxX4qg1ezrrJ7xm_1469790400', 'nome.cognome@example.com', 10]
        ]);
        return true;
    }

    public function safeDown()
    {
        $this->dropTable('{{%user}}');
        return true;
    }
}
