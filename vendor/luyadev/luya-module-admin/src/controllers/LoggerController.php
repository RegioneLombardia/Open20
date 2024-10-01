<?php

namespace luya\admin\controllers;

use luya\admin\Module;
use luya\admin\ngrest\base\Controller;

/**
 * NgRest Logger Controller
 *
 * @since 1.0.0
 */
class LoggerController extends Controller
{
    /**
     * {@inheritDoc}
     */
    public $modelClass = 'luya\admin\models\Logger';

    /**
     * {@inheritDoc}
     */
    public $clearButton = true;

    /**
     * {@inheritDoc}
     */
    public function getDescription()
    {
        return Module::t('logger_controller_description');
    }
}
