<?php

namespace amos\userauth\admin\apis;

use Stripe\Error\Api;
use yii\web\User;

/**
 * User Controller.
 *
 * File has been created with `crud/create` command.
 */
class UserController extends Api
{
    /**
     * @var string The path to the model which is the provider for the rules and fields.
     */
    public $modelClass = User::className;

}