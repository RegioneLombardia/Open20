<?php

namespace open20\amos\videoconference\i18n\grammar;

use open20\amos\core\interfaces\ModelGrammarInterface;
use open20\amos\videoconference\AmosVideoconference;

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    piattaforma-openinnovation
 * @category   CategoryName
 */

class VideoconferenceGrammar implements ModelGrammarInterface
{

    /**
     * @return string
     */
    public function getModelSingularLabel()
    {
        return AmosVideoconference::t('amosvideoconference', '#videoconference_singular');
    }

    /**
     * @inheritdoc
     */
    public function getModelLabel()
    {
        return AmosVideoconference::t('amosvideoconference', '#videoconference_plural');
    }

    /**
     * @return mixed
     */
    public function getArticleSingular()
    {
        return AmosVideoconference::t('amosvideoconference', '#article_singular');
    }

    /**
     * @return mixed
     */
    public function getArticlePlural()
    {
        return AmosVideoconference::t('amosvideoconference', '#article_plural');
    }

    /**
     * @return string
     */
    public function getIndefiniteArticle()
    {
        return AmosVideoconference::t('amosvideoconference', '#article_indefinite');
    }
}