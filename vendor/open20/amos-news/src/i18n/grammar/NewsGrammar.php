<?php

namespace open20\amos\news\i18n\grammar;

use open20\amos\core\interfaces\ModelGrammarInterface;
use open20\amos\news\AmosNews;

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    piattaforma-openinnovation
 * @category   CategoryName
 */

class NewsGrammar implements ModelGrammarInterface
{

    /**
     * @return string
     */
    public function getModelSingularLabel()
    {
        return AmosNews::t('amosnews', '#news_singular');
    }

    /**
     * @inheritdoc
     */
    public function getModelLabel()
    {
        return AmosNews::t('amosnews', '#news_plural');
    }

    /**
     * @return mixed
     */
    public function getArticleSingular()
    {
        return AmosNews::t('amosnews', '#article_singular');
    }

    /**
     * @return mixed
     */
    public function getArticlePlural()
    {
        return AmosNews::t('amosnews', '#article_plural');
    }

    /**
     * @return string
     */
    public function getIndefiniteArticle()
    {
        return AmosNews::t('amosnews', '#article_indefinite');
    }
}