<?php

namespace open20\amos\events\i18n\grammar;

use open20\amos\core\interfaces\ModelGrammarInterface;
use open20\amos\events\AmosEvents;

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    piattaforma-openinnovation
 * @category   CategoryName
 */

class EventGrammar implements ModelGrammarInterface
{

    /**
     * @return string
     */
    public function getModelSingularLabel()
    {
        return AmosEvents::t('amosevents', '#event');
    }

    /**
     * @inheritdoc
     */
    public function getModelLabel()
    {
        return AmosEvents::t('amosevents', '#events');
    }

    /**
     * @return mixed
     */
    public function getArticleSingular()
    {
        return AmosEvents::t('amosevents', '#article_singular');
    }

    /**
     * @return mixed
     */
    public function getArticlePlural()
    {
        return AmosEvents::t('amosevents', '#article_plural');
    }

    /**
     * @return string
     */
    public function getIndefiniteArticle()
    {
        return AmosEvents::t('amosevents', '#article_indefinite');
    }

    /**
     * @return mixed
     */
    public function getArticleInvitation()
    {
        return AmosEvents::t('amosevents', '#article_invitation');
    }
}