<?php

namespace open20\agid\person\i18n\grammar;

use open20\amos\core\interfaces\ModelGrammarInterface;
use open20\agid\person\Module;

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    piattaforma-openinnovation
 * @category   CategoryName
 */
class AgidPersonGrammar implements ModelGrammarInterface {

    /**
     * @return string
     */
    public function getModelSingularLabel() {
        return Module::t('amosperson', '#person');
    }

    /**
     * @inheritdoc
     */
    public function getModelLabel() {
        return Module::t('amosperson', '#documents');
    }

    /**
     * @return mixed
     */
    public function getArticleSingular() {
        return Module::t('amosperson', '#article_singular');
    }

    /**
     * @return mixed
     */
    public function getArticlePlural() {
        return Module::t('amosperson', '#article_plural');
    }

    /**
     * @return string
     */
    public function getIndefiniteArticle() {
        return Module::t('amosperson', '#article_indefinite');
    }

}
