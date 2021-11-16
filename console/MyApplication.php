<?php
/**
 * Created by PhpStorm.
 * User: michele.zucchini
 * Date: 26/04/2017
 * Time: 19:23
 */

namespace console;

use Yii;
use yii\console\Application;

class MyApplication extends Application
{
    public function init()
    {
        Yii::setAlias('@webroot', '@backend/web');
        Yii::setAlias('@web', '@backend/web');
        parent::init();
    }

    /**
     * Returns the user component.
     * @return User the user component.
     */
    public function getUser()
    {
        return $this->get('user');
    }

    public function getSession()
    {
        return $this->get('session');
    }
}