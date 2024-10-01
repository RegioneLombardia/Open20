<?php

namespace open20\amos\utility\controllers;

use open20\amos\core\controllers\BackendController;
use open20\amos\core\module\ModuleInterface;
use open20\amos\core\utilities\ClassUtility;
use open20\amos\core\utilities\RbacUtility;
use open20\amos\utility\Module;
use open20\amos\admin\models\UserProfile;
use Yii;
use open20\amos\core\user\User;
use open20\amos\utility\utility\CreateUsersUtility;
use open20\amos\admin\utility\UserProfileUtility;
use yii\filters\AccessControl;
use yii\db\ActiveRecord;

/**
 * Class DefaultController
 * @package open20\amos\utility\controllers
 */
class CreateUsersController extends BackendController {

    public $layout = "main";

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'create'
                        ],
                        'roles' => ['ACCESS_THE_BASEMENT']
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        $this->setUpLayout();
        // custom initialization code goes here
    }

    /**
     * The method create a new standard account. It creates a new User and new UserProfile only with name, surname
     * ,email, username and passoword. The email must be unique in the database! It assign the role based on the parameter
     * This method redirect to utility homepage at the end of the process, with a info flash message
     * @param string $user_type
     * @return redirect
     */
    public function actionCreate($user_type) {
        $password = CreateUsersUtility::DEFAULT_PASSWORD;

        $array_users = [
            'utente' => [
                'name' => 'Utente 1',
                'surname' => 'Utente 1',
                'email' => 'utente1@example.com',
                'roleName' => '',
            ],
            'facilitatore' => [
                'name' => 'Facilitatore 1',
                'surname' => 'Facilitatore 1',
                'email' => 'facilitatore1@example.com',
                'roleName' => 'FACILITATOR',
            ],
            'validatore' => [
                'name' => 'Validatore 1',
                'surname' => 'Validatore 1',
                'email' => 'validatore1@example.com',
                'roleName' => 'VALIDATOR',
            ],
            'superuser' => [
                'name' => 'Super User 1',
                'surname' => 'Super User 1',
                'email' => 'super_user1@example.com',
                'roleName' => 'SUPERUSER',
            ],
        ];
        if ($user_type != CreateUsersUtility::USER_ALL) {
            switch ($user_type) {
                case CreateUsersUtility::USER_BASE:
                    $key = 'utente';
                    break;
                case CreateUsersUtility::USER_FACILITATOR:
                    $key = 'facilitatore';
                    break;
                case CreateUsersUtility::USER_VALIDATOR:
                    $key = 'validatore';
                    break;
                case CreateUsersUtility::USER_SUPER_USER:
                    $key = 'superuser';
                    break;
            }
            $name = $array_users[$key]['name'];
            $surname = $array_users[$key]['surname'];
            $email = $array_users[$key]['email'];
            $roleName = $array_users[$key]['roleName'];

            $commit = CreateUsersUtility::generateUserByParams($name, $surname, $email, $roleName, $password);

            if ($commit === true) {
                Yii::$app->getSession()->addFlash('info', Module::t('amosutility', 'Utente creato con successo'));
            } else {
                Yii::$app->getSession()->addFlash('info', Module::t('amosutility', 'Errore nella creazione dell\'Utente'));
            } 
        } else {

            $message = '';
            foreach ($array_users as $user) {
                $commit = CreateUsersUtility::generateUserByParams($user['name'], $user['surname'], $user['email'], $user['roleName'], $password);
                if ($commit === true) {
                    $message .= ' User ' . $user['name'] . ' creato con successo, ';
                } else {
                    $message .= ' Errore nella creazione utente ' . $user['name'] . ', ';
                }
            }
            $message = substr($message, 0, -2);
            Yii::$app->getSession()->addFlash('info', Module::t('amosutility', $message));
        }
        
        return $this->redirect(Yii::$app->request->referrer);
    }

}
