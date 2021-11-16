<?php

namespace open20\agid\organizationalunit\i18n\grammar;

use open20\amos\core\interfaces\ModelGrammarInterface;
use open20\agid\organizationalunit\Module;


/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    piattaforma-openinnovation
 * @category   CategoryName
 */
class AgidOrganizationalUnitGrammar implements ModelGrammarInterface {

    /**
     * @return string
     */
    public function getModelSingularLabel() {
        return Module::t('amosorganizationalunit', '#person');
    }

    /**
     * @inheritdoc
     */
    public function getModelLabel() {
        return Module::t('amosorganizationalunit', '#documents');
    }

    /**
     * @return mixed
     */
    public function getArticleSingular() {
        return Module::t('amosorganizationalunit', '#article_singular');
    }

    /**
     * @return mixed
     */
    public function getArticlePlural() {
        return Module::t('amosorganizationalunit', '#article_plural');
    }

    /**
     * @return string
     */
    public function getIndefiniteArticle() {
        return Module::t('amosorganizationalunit', '#article_indefinite');
    }

}
