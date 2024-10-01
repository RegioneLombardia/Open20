<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\controllers
 * @category   CategoryName
 */

namespace open20\amos\organizzazioni\controllers;

use open20\amos\organizzazioni\controllers\base\ProfiloController;

/**
 * Class DefaultController
 * @package open20\amos\organizzazioni\controllers
 */
class OrganizzazioniController extends ProfiloController
{

    /**
     * 
     * @param type $id
     */
    public function actionView($id){
        return $this->redirect(['/organizzazioni/profilo/view', 'id' => $id]);
    }
}
