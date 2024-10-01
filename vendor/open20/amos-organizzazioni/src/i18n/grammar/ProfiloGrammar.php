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
 * Class ProfiloGrammar
 * @package open20\amos\organizzazioni\i18n\grammar
 */
class ProfiloGrammar implements ModelGrammarInterface
{
    /**
     * @return string
     */
    public function getModelSingularLabel()
    {
        return Module::t('amosorganizzazioni', '#organizzazioni_singular');
    }

    /**
     * @inheritdoc
     */
    public function getModelLabel()
    {
        return Module::t('amosorganizzazioni', '#organizzazioni_plural');
    }

    /**
     * @inheritdoc
     */
    public function getArticleSingular()
    {
        return Module::t('amosorganizzazioni', '#article_singular');
    }

    /**
     * @inheritdoc
     */
    public function getArticlePlural()
    {
        return Module::t('amosorganizzazioni', '#article_plural');
    }

    /**
     * @inheritdoc
     */
    public function getIndefiniteArticle()
    {
        return Module::t('amosorganizzazioni', '#article_indefinite');
    }

    /**
     * @return mixed
     */
    public function getArticleInvitation()
    {
        return Module::t('amosorganizzazioni', '#article_invitation');
    }

}
