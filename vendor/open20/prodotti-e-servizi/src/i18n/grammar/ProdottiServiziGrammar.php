<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 */

namespace open20\prodottiservizi\i18n\grammar;

use open20\amos\core\interfaces\ModelGrammarInterface;
use open20\prodottiservizi\Module;

/**
 * Class ProdottiServiziGrammar
 */
class ProdottiServiziGrammar implements ModelGrammarInterface
{
    /**
     * @inheritdoc
     */
    public function getArticlePlural()
    {
        return Module::t('prodottiservizi', '#prod_serv_plural');
    }
    
    /**
     * @inheritdoc
     */
    public function getArticleSingular()
    {
        return Module::t('prodottiservizi', '#prod_serv_singular');
    }
    
    /**
     * @inheritdoc
     */
    public function getIndefiniteArticle()
    {
        return Module::t('prodottiservizi', '#prod_serv_indefinite');
    }
    
    /**
     * @inheritdoc
     */
    public function getModelLabel()
    {
        return Module::t('prodottiservizi', '#prod_serv_model_label');
    }
    
    /**
     * @inheritdoc
     */
    public function getModelSingularLabel()
    {
        return Module::t('prodottiservizi',
            '#prod_serv_model_label_singular');
    }
}