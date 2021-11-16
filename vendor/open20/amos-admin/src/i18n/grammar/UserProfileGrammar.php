<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\i18n\grammar
 * @category   CategoryName
 */

namespace open20\amos\admin\i18n\grammar;

use open20\amos\admin\AmosAdmin;
use open20\amos\core\interfaces\ModelGrammarInterface;

/**
 * Class UserProfileGrammar
 * @package open20\amos\admin\i18n\grammar
 */
class UserProfileGrammar implements ModelGrammarInterface
{
    /**
     * @return string The singular model name in translation label
     */
    public function getModelSingularLabel()
    {
        return AmosAdmin::t('amosadmin', '#user_profile_singular');
    }
    
    /**
     * @return string The model name in translation label
     */
    public function getModelLabel()
    {
        return AmosAdmin::t('amosadmin', '#user_profile_plural');
    }
    
    /**
     * @return string
     */
    public function getArticleSingular()
    {
        return AmosAdmin::t('amosadmin', '#article_singular');
    }
    
    /**
     * @return string
     */
    public function getArticlePlural()
    {
        return AmosAdmin::t('amosadmin', '#article_plural');
    }
    
    /**
     * @return string
     */
    public function getIndefiniteArticle()
    {
        return AmosAdmin::t('amosadmin', '#article_indefinite');
    }
}
