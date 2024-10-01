<?php

use yii\db\Migration;

class m220530_122500_create_table_collaboration_expressions_of_interest extends Migration
{
    const TABLE_COLLABORATION_PROPOSALS        = '{{%collaboration_proposals}}';
    const TABLE_COLLABORATION_EXPR_OF_INTEREST = '{{%collaboration_expressions_of_interest}}';

    public function safeUp()
    {
        if ($this->db->schema->getTableSchema(self::TABLE_COLLABORATION_EXPR_OF_INTEREST, true) === null) {
            $this->createTable(self::TABLE_COLLABORATION_EXPR_OF_INTEREST, [
                'id' => $this->primaryKey(),
                'collaboration_proposal_id' => $this->integer()->notNull()->comment('Collaboration Proposal ID'),
                'proposal_title' => $this->string()->notNull()->comment('Proposal Title'),
                'proposer_first_name' => $this->string()->notNull()->comment('Proposer First Name'),
                'proposer_last_name' => $this->string()->notNull()->comment('Proposer Last Name'),
                'proposer_email' => $this->string()->notNull()->comment('Proposer E-mail'),
                'company' => $this->string()->notNull()->comment('Company / Entity'),
                'country' => $this->string()->notNull()->comment('Country'),
                'city' => $this->string()->notNull()->comment('City'),
                'province' => $this->string()->notNull()->comment('Province'),
                'municipality' => $this->string()->notNull()->comment('Municipality'),
                'address' => $this->string()->notNull()->comment('Address'),
                'postal_code' => $this->string()->notNull()->comment('Postal Code'),

                'created_by' => $this->integer()->null()->defaultValue(null),
                'created_at' => $this->dateTime()->null()->defaultValue(null),
                'updated_by' => $this->integer()->null()->defaultValue(null),
                'updated_at' => $this->dateTime()->null()->defaultValue(null),
                'deleted_by' => $this->integer()->null()->defaultValue(null),
                'deleted_at' => $this->dateTime()->null()->defaultValue(null),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
        } else {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
        }

        $this->addForeignKey('fk_expressions_of_interest_collaboration_proposal', self::TABLE_COLLABORATION_EXPR_OF_INTEREST, 'collaboration_proposal_id', self::TABLE_COLLABORATION_PROPOSALS, 'id');
    }

    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS=0');
        $this->dropTable(self::TABLE_COLLABORATION_EXPR_OF_INTEREST);
        $this->execute('SET FOREIGN_KEY_CHECKS=1');


        return true;
    }
}
