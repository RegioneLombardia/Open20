<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\controllers 
 */

namespace open20\amos\admin\controllers;
use Yii;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use open20\amos\admin\AmosAdmin;
use open20\amos\core\helpers\Html;

/**
 * Class UserProfileClassesController 
 * This is the class for controller "UserProfileClassesController".
 * @package open20\amos\admin\controllers 
 */
class UserProfileClassesController extends \open20\amos\admin\controllers\base\UserProfileClassesController
{
    /**
     * @inheritdoc
     */
    
    public function behaviors()
    {
        $result = ArrayHelper::merge(
            parent::behaviors(), 
            [
                'access' => [
                    'class' => AccessControl::class,
                    'ruleConfig' => [
                        'class' => AccessRule::class,
                    ],
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => [
                                'clone',
                            ],
                            'roles' => ['ADMIN', 'AMMINISTRATORE_UTENTI']
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'remove-prevalent-partnership' => ['post'],
                        'delete' => ['post', 'get']
                    ]
                ]
            ]
        );

        return $result;
    }
    public function beforeAction($action)
    {
        if (\Yii::$app->user->isGuest) {
            $titleSection = AmosAdmin::t('amosadmin', 'Utenti');
            $urlLinkAll   = '';

            $labelSigninOrSignup = AmosAdmin::t('amosadmin', '#beforeActionCtaLoginRegister');
            $titleSigninOrSignup = AmosAdmin::t(
                    'amosadmin', '#beforeActionCtaLoginRegisterTitle', ['platformName' => \Yii::$app->name]
            );
            $labelSignin         = AmosAdmin::t('amosadmin', '#beforeActionCtaLogin');
            $titleSignin         = AmosAdmin::t(
                    'amosadmin', '#beforeActionCtaLoginTitle', ['platformName' => \Yii::$app->name]
            );

            $labelLink        = $labelSigninOrSignup;
            $titleLink        = $titleSigninOrSignup;
            $socialAuthModule = \Yii::$app->getModule('socialauth');
            if ($socialAuthModule && ($socialAuthModule->enableRegister == false)) {
                $labelLink = $labelSignin;
                $titleLink = $titleSignin;
            }

            $ctaLoginRegister = Html::a(
                    $labelLink,
                    isset(\Yii::$app->params['linkConfigurations']['loginLinkCommon']) ? \Yii::$app->params['linkConfigurations']['loginLinkCommon']
                        : \Yii::$app->params['platform']['backendUrl'].'/'.AmosAdmin::getModuleName().'/security/login',
                    [
                    'title' => $titleLink
                    ]
            );
            $subTitleSection  = Html::tag(
                    'p',
                    AmosAdmin::t(
                        'amosadmin', 'Unisciti a {platformName}!, {ctaLoginRegister}',
                        ['platformName' => \Yii::$app->name, 'ctaLoginRegister' => $ctaLoginRegister]
                    )
            );
        } else {
            $titleSection = AmosAdmin::t('amosadmin', 'Profili');
            $labelLinkAll = '';
            $urlLinkAll   = '';
            $titleLinkAll = '';

            $subTitleSection = Html::tag('p', AmosAdmin::t('amosadmin', ''));
        }

        $labelCreate        = AmosAdmin::t('amosadmin', 'Nuovo');
        $titleCreate        = AmosAdmin::t('amosadmin', 'Crea un nuovo profilo');
        $labelManage        = '';
        $titleManage        = '';
        $urlCreate          = '/'.AmosAdmin::getModuleName().'/user-profile-classes/create';
        $urlManage          = null;
        $this->view->params = [
            'isGuest' => \Yii::$app->user->isGuest,
            'modelLabel' => 'profili',
            'titleSection' => $titleSection,
            'subTitleSection' => $subTitleSection,
            'urlLinkAll' => $urlLinkAll,
            'labelLinkAll' => $labelLinkAll,
            'titleLinkAll' => $titleLinkAll,
            'labelCreate' => $labelCreate,
            'titleCreate' => $titleCreate,
            'labelManage' => $labelManage,
            'titleManage' => $titleManage,
            'urlCreate' => $urlCreate,
            'urlManage' => $urlManage,
        ];

        if (!parent::beforeAction($action)) {
            return false;
        }

        return true;
    }

}