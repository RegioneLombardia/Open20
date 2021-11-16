<?php

namespace  open20\amos\community\i18n\grammar;

use open20\amos\community\AmosCommunity;
use open20\amos\core\interfaces\ModelGrammarInterface;

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    piattaforma-openinnovation
 * @category   CategoryName
 */

class CommunityGrammar implements ModelGrammarInterface
{

    /**
     * @return string
     */
    public function getModelSingularLabel()
    {
        return AmosCommunity::t('amoscommunity', '#community');
    }

    /**
     * @return string The model name in translation label
     */
    public function getModelLabel()
    {
        return \Yii::t('amoscommunity','#Community');
    }

    /**
     * @return mixed
     */
    public function getArticleSingular()
    {
        return AmosCommunity::t('amoscommunity', '#article_singular');
    }

    /**
     * @return mixed
     */
    public function getArticlePlural()
    {
        return AmosCommunity::t('amoscommunity', '#article_plural');
    }

    /**
     * @return string
     */
    public function getIndefiniteArticle()
    {
        return AmosCommunity::t('amoscommunity', '#article_indefinite');
    }
}