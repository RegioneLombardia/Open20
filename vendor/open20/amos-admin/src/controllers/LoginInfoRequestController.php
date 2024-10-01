<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\controllers
 * @category   CategoryName
 */

namespace open20\amos\admin\controllers;

use yii\helpers\Url;
use yii\web\Controller;

/**
 * Class UserProfileController
 * @package open20\amos\admin\controllers
 */
class LoginInfoRequestController extends Controller
{

    public function init()
    {
        parent::init();
    }

    
    /**
     * @param null $q
     * @param null $id
     * @return array
     */
    public function actionActivateUser() {
        
        return $this->redirect(['/login']);
   
    }

}