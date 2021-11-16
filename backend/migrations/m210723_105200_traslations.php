<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\attachments\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationTranslations;

/**
 * Class m210723_103500_traslations
 */
class m210723_105200_traslations extends AmosMigrationTranslations
{
    /**
     * @inheritdoc
     */
    protected function setTranslations()
    {
        return [
            self::LANG_IT => [
                [
                    'category' => 'amossondaggi',
                    'source' => 'Gestisci questionari' ,
                    'newTranslation' => 'Gestisci sondaggi',
                    'oldTranslation' => 'Gestisci questionari',
                    'update' => true,
                    'updateWithUserField' => true,
                ],
            ]
        ];
    }
}
