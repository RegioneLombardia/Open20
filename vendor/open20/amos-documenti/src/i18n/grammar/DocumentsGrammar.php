<?php

namespace open20\amos\documenti\i18n\grammar;

use open20\amos\core\interfaces\ModelGrammarInterface;
use open20\amos\documenti\AmosDocumenti;

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    piattaforma-openinnovation
 * @category   CategoryName
 */

class DocumentsGrammar implements ModelGrammarInterface
{

    /**
     * @return string
     */
    public function getModelSingularLabel()
    {
        return AmosDocumenti::t('amosdocumenti', '#document');
    }

    /**
     * @inheritdoc
     */
    public function getModelLabel()
    {
        return AmosDocumenti::t('amosdocumenti', '#documents');
    }

    /**
     * @return mixed
     */
    public function getArticleSingular()
    {
        return AmosDocumenti::t('amosdocumenti', '#article_singular');
    }

    /**
     * @return mixed
     */
    public function getArticlePlural()
    {
        return AmosDocumenti::t('amosdocumenti', '#article_plural');
    }

    /**
     * @return string
     */
    public function getIndefiniteArticle()
    {
        return AmosDocumenti::t('amosdocumenti', '#article_indefinite');
    }
}