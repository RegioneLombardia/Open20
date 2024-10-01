<?php

namespace amos\chrono\i18n\grammar;

use open20\amos\core\interfaces\ModelGrammarInterface;

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    piattaforma-openinnovation
 * @category   CategoryName
 */
class ChronoTaskGrammar implements ModelGrammarInterface
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