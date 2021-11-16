<?php

namespace amos\userauth\admin\controllers;

use open20\amos\core\user\User;
use luya\admin\ngrest\base\Controller;


class UserController extends Controller
{
    /**
     * @var string The path to the model which is the provider for the rules and fields.
     */
    public $modelClass = User::className;
}
