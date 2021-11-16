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
 * Class m210729_110200_traslations
 */
class m210729_110200_traslations extends AmosMigrationTranslations
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
                    'source' => 'Grazie per aver compilato il questionario.' ,
                    'newTranslation' => 'Grazie per aver compilato il sondaggio.',
                    'oldTranslation' => 'Grazie per aver compilato il questionario.',
                    'update' => true,
                    'updateWithUserField' => true,
                ],
                [
                    'category' => 'amossondaggi',
                    'source' => 'Inserisci un\'altra risposta' ,
                    'newTranslation' => 'Salva',
                    'oldTranslation' => 'Inserisci un\'altra risposta',
                    'update' => true,
                    'updateWithUserField' => true,
                ],
            ]
        ];
    }
}
