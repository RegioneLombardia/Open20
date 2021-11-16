<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\translation\migrations
 * @category   CategoryName
 */

/**
 * Class m170612_150031_translation_workflow
 */
class m170612_150031_translation_workflow extends \yii\db\Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->execute("SET FOREIGN_KEY_CHECKS=0;");

        $this->execute("
            INSERT INTO `sw_workflow` (`id`, `initial_status_id`) VALUES
            ('AmosTranslationWorkflow', 'DRAFT');
            
            INSERT INTO `sw_transition` (`workflow_id`, `start_status_id`, `end_status_id`) VALUES
            ('AmosTranslationWorkflow', 'APPROVED', 'DRAFT'),
            ('AmosTranslationWorkflow', 'APPROVED', 'TOBEREVIEWED'),
            ('AmosTranslationWorkflow', 'DRAFT', 'TRANSLATED'),
            ('AmosTranslationWorkflow', 'TOBEREVIEWED', 'TRANSLATED'),
            ('AmosTranslationWorkflow', 'TRANSLATED', 'APPROVED'),
            ('AmosTranslationWorkflow', 'TRANSLATED', 'TOBEREVIEWED');
            
            INSERT INTO `sw_status` (`id`, `workflow_id`, `label`, `sort_order`) VALUES
            ('APPROVED', 'AmosTranslationWorkflow', 'Approved', 4),
            ('DRAFT', 'AmosTranslationWorkflow', 'Draft', 1),
            ('TOBEREVIEWED', 'AmosTranslationWorkflow', 'To be reviewed', 3),
            ('TRANSLATED', 'AmosTranslationWorkflow', 'Translated', 2);

            INSERT INTO `sw_metadata` (`workflow_id`, `status_id`, `key`, `value`) VALUES
            ('AmosTranslationWorkflow', 'APPROVED', 'label', 'Approved'),
            ('AmosTranslationWorkflow', 'DRAFT', 'label', 'Draft');
                ");

        $this->execute("SET FOREIGN_KEY_CHECKS=1;");
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "No migration dows available for m170612_150031_translation_workflow";
        return true;
    }
}
