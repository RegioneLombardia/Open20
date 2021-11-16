<?php

namespace backend\modules\userimporter\i18n\grammar;

use open20\amos\core\interfaces\ModelGrammarInterface;

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    piattaforma-openinnovation
 * @category   CategoryName
 */

class UserImportTaskGrammar implements ModelGrammarInterface
{

    /**
     * @return string
     */
    public function getModelSingularLabel()
    {
        return '';
    }

    /**
     * @inheritdoc
     */
    public function getModelLabel()
    {
         return '';
    }

    /**
     * @return mixed
     */
    public function getArticleSingular()
    {
         return '';
    }

    /**
     * @return mixed
     */
    public function getArticlePlural()
    {
        return '';
    }

    /**
     * @return string
     */
    public function getIndefiniteArticle()
    {
         return '';
    }
}