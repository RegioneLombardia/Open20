<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationTranslations;

/**
 * Class m180803_132452_update_admin_workflow_translations_1
 */
class m180803_132452_update_admin_workflow_translations_1 extends AmosMigrationTranslations
{
    const CATEGORY = 'amosadmin';

    /**
     * @inheritdoc
     */
    protected function setTranslations()
    {
        return [
            self::LANG_IT => [
                [
                    'update' => true,
                    'category' => self::CATEGORY,
                    'source' => '#NOTVALIDATED_message',
                    'oldTranslation' => 'Il profilo verrà rimesso in bozza per apportare le modifiche. Confermi?',
                    'newTranslation' => 'Confermi di rifiutare la validazione del profilo?'
                ]
            ]
        ];
    }
}
