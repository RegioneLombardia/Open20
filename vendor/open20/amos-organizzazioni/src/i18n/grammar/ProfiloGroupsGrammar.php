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
 * Class ProfiloGroupsGrammar
 * @package open20\amos\organizzazioni\i18n\grammar
 */
class ProfiloGroupsGrammar implements ModelGrammarInterface
{
    /**
     * @return string
     */
    public function getModelSingularLabel()
    {
        return Module::t('amosorganizzazioni', '#organizzazioni_groups_singular');
    }

    /**
     * @inheritdoc
     */
    public function getModelLabel()
    {
        return Module::t('amosorganizzazioni', '#organizzazioni_groups_plural');
    }

    /**
     * @inheritdoc
     */
    public function getArticleSingular()
    {
        return Module::t('amosorganizzazioni', '#groups_article_singular');
    }

    /**
     * @inheritdoc
     */
    public function getArticlePlural()
    {
        return Module::t('amosorganizzazioni', '#groups_article_plural');
    }

    /**
     * @inheritdoc
     */
    public function getIndefiniteArticle()
    {
        return Module::t('amosorganizzazioni', '#groups_article_indefinite');
    }
}
