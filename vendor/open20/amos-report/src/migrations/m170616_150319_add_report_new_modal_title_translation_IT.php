<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\report\migrations
 * @category   Migration
 */

use open20\amos\core\migration\AmosMigrationTranslations;

/**
 * Class m170616_150319_add_report_new_modal_title_translation_IT
 */
class m170616_150319_add_report_new_modal_title_translation_IT extends AmosMigrationTranslations
{
    /**
     * @inheritdoc
     */
    protected function setTranslations()
    {
        return [
            self::LANG_IT => [
                [
                    'category' => 'amosreport',
                    'source' => 'You are sending a report for',
                    'translation' => 'Stai inviando una segnalazione per'
                ],
            ]
        ];
    }
}
