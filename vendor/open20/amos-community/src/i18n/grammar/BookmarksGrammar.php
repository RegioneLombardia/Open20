<?php

namespace  open20\amos\community\i18n\grammar;

use open20\amos\community\AmosCommunity;
use open20\amos\core\interfaces\ModelGrammarInterface;

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    piattaforma-openinnovation
 * @category   CategoryName
 */

class BookmarksGrammar implements ModelGrammarInterface
{

    /**
     * @return string
     */
    public function getModelSingularLabel()
    {
        return 'Bookmark';//AmosCommunity::t('amoscommunity', 'Bookmark');
    }

    /**
     * @return string The model name in translation label
     */
    public function getModelLabel()
    {
        return 'Bookmark';//\Yii::t('amoscommunity','Bookmark');
    }

    /**
     * @return mixed
     */
    public function getArticleSingular()
    {
        return AmosCommunity::t('amoscommunity', '#article_singular_bookmark');
    }

    /**
     * @return mixed
     */
    public function getArticlePlural()
    {
        return AmosCommunity::t('amoscommunity', '#article_plural_bookmark');
    }

    /**
     * @return mixed
     */
    public function getArticleInvitation()
    {
        return '';
    }

    /**
     * @return string
     */
    public function getIndefiniteArticle()
    {
        return AmosCommunity::t('amoscommunity', '#article_indefinite_bookmark');
    }
}