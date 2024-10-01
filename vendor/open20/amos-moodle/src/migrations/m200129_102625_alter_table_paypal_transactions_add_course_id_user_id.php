<?php
use open20\amos\core\migration\AmosMigration;
use open20\amos\moodle\models\PayPalTransactions;

/**
* Class m180209_104824_alter_table_moodle_course*/
class m200129_102625_alter_table_paypal_transactions_add_course_id_user_id extends AmosMigration
{

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(
            PayPalTransactions::tableName(), 
            'course_id', 
            $this->integer()->notNull()->defaultValue(null)->comment('ID paypal course')->after('user_id')
        );
        
        $this->addColumn(
            PayPalTransactions::tableName(), 
            'student_id', 
            $this->integer()->notNull()->defaultValue(null)->comment('ID paypal course')->after('course_id')
        );
        
        return true;
    }
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn(PayPalTransactions::tableName(), 'course_id');
        $this->dropColumn(PayPalTransactions::tableName(), 'student_id');
        
        return true;
    }
}
