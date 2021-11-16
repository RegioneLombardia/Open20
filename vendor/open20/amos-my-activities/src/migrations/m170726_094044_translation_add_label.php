<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\myactivities\migrations
 * @category   CategoryName
 */

class m170726_094044_translation_add_label extends \open20\amos\core\migration\AmosMigrationTranslations
{
    const CATEGORY = 'amosmyactivities';

    /**
     * @inheritdoc
     */
    protected function setTranslations()
    {
        return [
            self::LANG_IT => [
                [
                    'category' => self::CATEGORY,
                    'source' => 'User validation request',
                    'translation' => 'Richiesta di validazione utente'
                ],//
            ]
        ];
    }
}