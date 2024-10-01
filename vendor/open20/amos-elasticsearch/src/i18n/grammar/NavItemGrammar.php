<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\sondaggi\i18n\grammar
 * @category   CategoryName
 */

namespace open20\elasticsearch\i18n\grammar;

use open20\amos\core\interfaces\ModelGrammarInterface;
use open20\elasticsearch\Module;

/**
 * Class NavItemGrammar
 * @package open20\amos\sondaggi\i18n\grammar
 */
class NavItemGrammar implements ModelGrammarInterface
{
    /**
     * @inheritdoc
     */
    public function getModelSingularLabel()
    {
        return Module::t('amoselasticsearch', '#navitem_singular');
    }

    /**
     * @inheritdoc
     */
    public function getModelLabel()
    {
        return Module::t('amoselasticsearch', '#navitem_plural');
    }

    /**
     * @inheritdoc
     */
    public function getArticleSingular()
    {
        return Module::t('amoselasticsearch', '#navitem_article_singular');
    }

    /**
     * @inheritdoc
     */
    public function getArticlePlural()
    {
        return Module::t('amoselasticsearch', '#navitem_article_plural');
    }

    /**
     * @inheritdoc
     */
    public function getIndefiniteArticle()
    {
        return Module::t('amoselasticsearch', '#navitem_indefinite_article');
    }
}
