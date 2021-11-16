<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\agid\service\i18n\grammar
 * @category   CategoryName
 */

namespace open20\agid\service\i18n\grammar;

use open20\amos\core\interfaces\ModelGrammarInterface;
use open20\agid\service\Module;

/**
 * Class AgidServiceGrammar
 * @package open20\agid\service\i18n\grammar
 */
class AgidServiceGrammar implements ModelGrammarInterface
{
    /**
     * @inheridoc
     */
    public function getModelSingularLabel()
    {
        return Module::t('amosservice', '#grammar_model_singular');
    }
    
    /**
     * @inheridoc
     */
    public function getModelLabel()
    {
        return Module::t('amosservice', '#grammar_model_plural');
    }
    
    /**
     * @inheridoc
     */
    public function getArticleSingular()
    {
        return Module::t('amosservice', '#grammar_article_singular');
    }
    
    /**
     * @inheridoc
     */
    public function getArticlePlural()
    {
        return Module::t('amosservice', '#grammar_article_plural');
    }
    
    /**
     * @inheridoc
     */
    public function getIndefiniteArticle()
    {
        return Module::t('amosservice', '#grammar_article_indefinite');
    }
}
