<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\i18n\grammar
 * @category   CategoryName
 */

namespace open20\amos\organizzazioni\i18n\grammar;

use open20\amos\core\interfaces\ModelGrammarInterface;
use open20\amos\organizzazioni\Module;

/**
 * Class ProfiloSediGrammar
 * @package open20\amos\organizzazioni\i18n\grammar
 */
class ProfiloSediGrammar implements ModelGrammarInterface
{
    /**
     * @return string
     */
    public function getModelSingularLabel()
    {
        return Module::t('amosorganizzazioni', '#sedi_singular');
    }

    /**
     * @inheritdoc
     */
    public function getModelLabel()
    {
        return Module::t('amosorganizzazioni', '#sedi_plural');
    }

    /**
     * @inheritdoc
     */
    public function getArticleSingular()
    {
        return Module::t('amosorganizzazioni', '#sedi_article_singular');
    }

    /**
     * @inheritdoc
     */
    public function getArticlePlural()
    {
        return Module::t('amosorganizzazioni', '#sedi_article_plural');
    }

    /**
     * @inheritdoc
     */
    public function getIndefiniteArticle()
    {
        return Module::t('amosorganizzazioni', '#sedi_article_indefinite');
    }
}
