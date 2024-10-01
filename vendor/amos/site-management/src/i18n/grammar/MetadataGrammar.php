<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\sitemanagement\i18n\grammar
 * @category   CategoryName
 */

namespace amos\sitemanagement\i18n\grammar;

use amos\sitemanagement\Module;
use open20\amos\core\interfaces\ModelGrammarInterface;

/**
 * Class MetadataGrammar
 * @package amos\sitemanagement\i18n\grammar
 */
class MetadataGrammar implements ModelGrammarInterface
{
    /**
     * @inheritdoc
     */
    public function getModelSingularLabel()
    {
        return Module::t('amossitemanagement', '#metadata_singular');
    }

    /**
     * @inheritdoc
     */
    public function getModelLabel()
    {
        return Module::t('amossitemanagement', '#metadata_plural');
    }

    /**
     * @inheritdoc
     */
    public function getArticleSingular()
    {
        return Module::t('amossitemanagement', '#metadata_article_singular');
    }

    /**
     * @inheritdoc
     */
    public function getArticlePlural()
    {
        return Module::t('amossitemanagement', '#metadata_article_plural');
    }

    /**
     * @inheritdoc
     */
    public function getIndefiniteArticle()
    {
        return Module::t('amossitemanagement', '#metadata_indefinite_article');
    }
}
