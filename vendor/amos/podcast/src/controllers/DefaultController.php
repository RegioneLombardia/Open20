<?php
/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 23/10/2020
 * Time: 14:53
 */

namespace amos\podcast\controllers;


use open20\amos\comuni\controllers\DashboardController;
use open20\amos\core\controllers\BackendController;

class DefaultController extends DashboardController
{

    /**
     * @var string $layout Internal dashboard layout.
     */
    public $layout = 'dashboard_interna';

    /**
     * @inheritdoc
     */
    public function init() {

        parent::init();
        $this->setUpLayout();
        // custom initialization code goes here
    }

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->redirect(['/podcast/podcast/index']);

    }


}