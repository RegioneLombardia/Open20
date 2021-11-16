<?php

namespace open20\amos\discussioni\i18n\grammar;

use open20\amos\core\interfaces\ModelGrammarInterface;
use open20\amos\discussioni\AmosDiscussioni;

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    piattaforma-openinnovation
 * @category   CategoryName
 */

class DiscussionTopicGrammar implements ModelGrammarInterface
{

    /**
     * @return string
     */
    public function getModelSingularLabel()
    {
        return AmosDiscussioni::t('amosdiscussioni', '#discussion_topic');
    }

    /**
     * @inheritdoc
     */
    public function getModelLabel()
    {
        return AmosDiscussioni::t('amosdiscussioni', '#discussions_topic');
    }

    /**
     * @return mixed
     */
    public function getArticleSingular()
    {
        return AmosDiscussioni::t('amosdiscussioni', '#article_singular');
    }

    /**
     * @return mixed
     */
    public function getArticlePlural()
    {
        return AmosDiscussioni::t('amosdiscussioni', '#article_plural');
    }

    /**
     * @return string
     */
    public function getIndefiniteArticle()
    {
        return AmosDiscussioni::t('amosdiscussioni', '#article_indefinite');
    }
}