<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\i18n\grammar
 * @category   CategoryName
 */

namespace open20\amos\documenti\i18n\grammar;

use open20\amos\core\interfaces\ModelGrammarInterface;
use open20\amos\documenti\AmosDocumenti;

/**
 * Class FoldersGrammar
 * @package open20\amos\documenti\i18n\grammar
 */
class FoldersGrammar implements ModelGrammarInterface
{
    /**
     * @return string
     */
    public function getModelSingularLabel()
    {
        return AmosDocumenti::t('amosdocumenti', '#folder');
    }

    /**
     * @inheritdoc
     */
    public function getModelLabel()
    {
        return AmosDocumenti::t('amosdocumenti', '#folders');
    }

    /**
     * @return mixed
     */
    public function getArticleSingular()
    {
        return AmosDocumenti::t('amosdocumenti', '#folder_article_singular');
    }

    /**
     * @return mixed
     */
    public function getArticlePlural()
    {
        return AmosDocumenti::t('amosdocumenti', '#folder_article_plural');
    }

    /**
     * @return string
     */
    public function getIndefiniteArticle()
    {
        return AmosDocumenti::t('amosdocumenti', '#folder_article_indefinite');
    }
}
