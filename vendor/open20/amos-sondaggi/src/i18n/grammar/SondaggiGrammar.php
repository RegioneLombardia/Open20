<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\sondaggi\i18n\grammar
 * @category   CategoryName
 */

namespace open20\amos\sondaggi\i18n\grammar;

use open20\amos\core\interfaces\ModelGrammarInterface;
use open20\amos\sondaggi\AmosSondaggi;

/**
 * Class SondaggiGrammar
 * @package open20\amos\sondaggi\i18n\grammar
 */
class SondaggiGrammar implements ModelGrammarInterface
{
    /**
     * @inheritdoc
     */
    public function getModelSingularLabel()
    {
        return AmosSondaggi::t('amossondaggi', '#sondaggi_singular');
    }

    /**
     * @inheritdoc
     */
    public function getModelLabel()
    {
        return AmosSondaggi::t('amossondaggi', '#sondaggi_plural');
    }

    /**
     * @inheritdoc
     */
    public function getArticleSingular()
    {
        return AmosSondaggi::t('amossondaggi', '#sondaggi_article_singular');
    }

    /**
     * @inheritdoc
     */
    public function getArticlePlural()
    {
        return AmosSondaggi::t('amossondaggi', '#sondaggi_article_plural');
    }

    /**
     * @inheritdoc
     */
    public function getIndefiniteArticle()
    {
        return AmosSondaggi::t('amossondaggi', '#sondaggi_indefinite_article');
    }
}
