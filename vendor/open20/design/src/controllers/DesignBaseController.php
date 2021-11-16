<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    DesignPackage
 */

namespace open20\design\controllers;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use open20\amos\core\controllers\BackendController;
use open20\amos\tag\models\Tag;
use open20\design\models\TestModelCheckBox;
use open20\design\models\TestModelCheckBoxIcon;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * Class DesignBaseController
 * @package open20\design\controllers
 */
class DesignBaseController extends BackendController
{
    /**
     * @var AmosAdmin $adminModule
     */
    public $adminModule = null;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->adminModule = AmosAdmin::instance();

        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'components',
                            'configurations',
                            'agid-components'
                        ],
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get'],
                ],
            ],
        ];
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionComponents()
    {
        $model = new TestModelCheckBox();
        $choices = [
            new TestModelCheckBoxIcon('Label1', 'Description1', 'icon1', '1'),
            new TestModelCheckBoxIcon('Label2', 'Description2', 'icon2', '2'),
            new TestModelCheckBoxIcon('Label3', 'Description3', 'icon3', '3'),
            new TestModelCheckBoxIcon('Label4', 'Description4', 'icon4', '4'),
            new TestModelCheckBoxIcon('Label5', 'Description5', 'icon5', '5'),
            new TestModelCheckBoxIcon('Label6', 'Description6', 'icon6', '6'),
            new TestModelCheckBoxIcon('Label7', 'Description7', 'icon7', '7'),
            new TestModelCheckBoxIcon('Label8', 'Description8', 'icon8', '8'),
        ];

        $tags = [
            new Tag([
                'nome' => 'Label 1 Label 1 Label 1 Label 1 Label 1',
                'descrizione' => 'Descrizione Label 1',
                'created_at' => date("Y-m-d H:i:s"),
                'created_by' => 1,
                'removable' => false,
            ]),
            new Tag([
                'nome' => 'Label 2',
                'descrizione' => 'Descrizione Label 2',
                'created_at' => date("Y-m-d H:i:s"),
                'created_by' => 2,
                'removable' => false,
            ]),
            new Tag([
                'nome' => 'Label 3',
                'descrizione' => 'Descrizione Label 3',
                'created_at' => date("Y-m-d H:i:s"),
                'created_by' => 3,
                'removable' => false,
            ]),
            new Tag([
                'nome' => 'Label 4',
                'descrizione' => 'Descrizione Label 4',
                'created_at' => date("Y-m-d H:i:s"),
                'created_by' => 4,
                'removable' => false,
            ]),
        ];


        $this->setUpLayout('bi-main-layout');
        $this->view->params['fluidContainerHeader'] = true;

        $avatars = [];
        /** @var UserProfile $userProfileClass */
        $userProfileClass = $this->adminModule->createModel('UserProfile');
        $usrs = $userProfileClass::find()->limit(6)->all();

        foreach ($usrs as $usr) {
            $avatars[] = $usr;
        }

        return $this->render("components", [
            'model' => $model,
            'choices' => $choices,
            'tags' => $tags,
            'userProfiles' => $avatars,
        ]);
    }

    /**
     * @return string
     */
    public function actionAgidComponents()
    {
        $this->setUpLayout('bi-main-layout');
        return $this->render("agid-components");
    }

    /**
     * @return string
     */
    public function actionConfigurations()
    {
        $this->setUpLayout('bi-main-layout');
        //$this->view->params['customClassMainContent'] = 'container';
        return $this->render("configurations");
    }
}
