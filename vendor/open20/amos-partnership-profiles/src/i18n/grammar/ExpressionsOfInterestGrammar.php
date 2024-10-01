<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\i18n\grammar
 * @category   CategoryName
 */

namespace open20\amos\partnershipprofiles\i18n\grammar;

use open20\amos\core\interfaces\ModelGrammarInterface;
use open20\amos\partnershipprofiles\Module;

/**
 * Class ExpressionsOfInterestGrammar
 * @package open20\amos\partnershipprofiles\i18n\grammar
 */
class ExpressionsOfInterestGrammar implements ModelGrammarInterface
{
    /**
     * @inheritdoc
     */
    public function getModelSingularLabel()
    {
        return Module::t('amospartnershipprofiles', '#expressions_of_interest_singular');
    }
    
    /**
     * @inheritdoc
     */
    public function getModelLabel()
    {
        return Module::t('amospartnershipprofiles', '#expressions_of_interest_plural');
    }
    
    /**
     * @inheritdoc
     */
    public function getArticleSingular()
    {
        return Module::t('amospartnershipprofiles', '#expressions_of_interest_article_singular');
    }
    
    /**
     * @inheritdoc
     */
    public function getArticlePlural()
    {
        return Module::t('amospartnershipprofiles', '#expressions_of_interest_article_plural');
    }
    
    /**
     * @inheritdoc
     */
    public function getIndefiniteArticle()
    {
        return Module::t('amospartnershipprofiles', '#expressions_of_interest_indefinite_article');
    }
}
