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
 * Class PartnershipProfilesGrammar
 * @package open20\amos\partnershipprofiles\i18n\grammar
 */
class PartnershipProfilesGrammar implements ModelGrammarInterface
{
    /**
     * @inheritdoc
     */
    public function getModelSingularLabel()
    {
        return Module::t('amospartnershipprofiles', '#partnership_profiles_singular');
    }
    
    /**
     * @inheritdoc
     */
    public function getModelLabel()
    {
        return Module::t('amospartnershipprofiles', '#partnership_profiles_plural');
    }
    
    /**
     * @inheritdoc
     */
    public function getArticleSingular()
    {
        return Module::t('amospartnershipprofiles', '#partnership_profiles_article_singular');
    }
    
    /**
     * @inheritdoc
     */
    public function getArticlePlural()
    {
        return Module::t('amospartnershipprofiles', '#partnership_profiles_article_plural');
    }
    
    /**
     * @inheritdoc
     */
    public function getIndefiniteArticle()
    {
        return Module::t('amospartnershipprofiles', '#partnership_profiles_indefinite_article');
    }
}
