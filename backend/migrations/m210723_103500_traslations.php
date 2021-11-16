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
class m210723_103500_traslations extends AmosMigrationTranslations
{
    /**
     * @inheritdoc
     */
    protected function setTranslations()
    {
        return [
            self::LANG_IT => [
                [
                    'category' => 'amossondaggi' ,
                    'source' => 'Info questionario' ,
                    'newTranslation' => 'Dati generali',
                    'oldTranslation' => 'Info questionario',
                    'update' => true,
                    'updateWithUserField' => true,
                ],
            ]
        ];
    }
}
