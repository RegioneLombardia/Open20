<?php

namespace amos\podcast\i18n\grammar;

use open20\amos\core\interfaces\ModelGrammarInterface;
use amos\podcast\Module;

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    piattaforma-openinnovation
 * @category   CategoryName
 */

class PodcastEpisodeGrammar implements ModelGrammarInterface
{

    /**
     * @return string
     */
    public function getModelSingularLabel()
    {
        return Module::t('amospodcast', '#news_singular');
    }

    /**
     * @inheritdoc
     */
    public function getModelLabel()
    {
        return Module::t('amospodcast', '#news_plural');
    }

    /**
     * @return mixed
     */
    public function getArticleSingular()
    {
        return Module::t('amospodcast', '#article_singular');
    }

    /**
     * @return mixed
     */
    public function getArticlePlural()
    {
        return Module::t('amospodcast', '#article_plural');
    }

    /**
     * @return string
     */
    public function getIndefiniteArticle()
    {
        return Module::t('amospodcast', '#article_indefinite');
    }
}