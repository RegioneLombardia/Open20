<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comments\controllers\base
 */

namespace open20\amos\comments\controllers;

use open20\amos\comments\AmosComments;
use open20\amos\comments\models\Comment;
use open20\amos\comments\models\CommentDashboard;
use open20\amos\comments\models\CommentNotificationUsers;
use open20\amos\comments\models\search\CommentBlacklistSearch;
use open20\amos\comments\models\search\CommentNotificationUsersSearch;
use open20\amos\comments\utility\CommentsUtility;
use Yii;
use open20\amos\comments\models\CommentBlacklist;
use yii\data\ActiveDataProvider;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\module\BaseAmosModule;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\helpers\Html;
use open20\amos\core\helpers\T;
use yii\helpers\Url;


/**
 * Class CommentNotificationUsersController
 *
 * @property CommentBlacklist $model
 * @property object $modelSearch
 *
 * @package open20\amos\comments\controllers\base
 */
class CommentNotificationUsersController extends CrudController
{

    /**
     * @var string $layout
     */
    public $layout = 'main';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'update-ajax',
                            'get-notification-options',
                        ],
                        'roles' => ['@']
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post', 'get']
                ]
            ]
        ]);
    }

    public function init()
    {
        $this->setModelObj(new CommentNotificationUsers());
        $this->setModelSearch(new CommentNotificationUsersSearch());

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Table')),
                'url' => '?currentView=grid'
            ],
            /*'list' => [
                'name' => 'list',
                'label' => AmosIcons::show('view-list') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'List')),
                'url' => '?currentView=list'
            ],
            'icon' => [
                'name' => 'icon',
                'label' => AmosIcons::show('grid') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Icons')),
                'url' => '?currentView=icon'
            ],
            'map' => [
                'name' => 'map',
                'label' => AmosIcons::show('map') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Map')),
                'url' => '?currentView=map'
            ],
            'calendar' => [
                'name' => 'calendar',
                'intestazione' => '', //codice HTML per l'intestazione che verrà caricato prima del calendario,
                                      //per esempio si può inserire una funzione $model->getHtmlIntestazione() creata ad hoc
                'label' => AmosIcons::show('calendar') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Calendari')),
                'url' => '?currentView=calendar'
            ],*/
        ]);

        parent::init();
        $this->setUpLayout();
    }

    /**
     * @return void
     */
    public function actionUpdateAjax()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        $modelClassname = $post['modelClassname'];
        $modelId = $post['modelId'];
        $value = $post['value'];
        $userId = Yii::$app->user->id;

        $model = CommentNotificationUsers::findOne([
            'context_model_class_name' => $modelClassname,
            'context_model_id' => $modelId,
            'user_id' => $userId
        ]);
        if (!empty($model)) {
            $model->enable = $value;
            $model->save(false);
        } else {
            $model = new CommentNotificationUsers();
            $model->context_model_class_name = $modelClassname;
            $model->context_model_id = $modelId;
            $model->user_id = $userId;
            $model->enable = $value;
            $model->save(false);
        }
    }

    /**
     * @return array
     */
    public function actionGetNotificationOptions()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $post = Yii::$app->request->post();
        $commentId = $post['commentId'];
        $contextModelClassname = $post['context'];
        $contextModelId = $post['contextId'];
        if (empty($contextModelClassname) && empty($contextModelId) && !empty($commentId)) {
            $comment = Comment::findOne($commentId);
            if ($comment) {
                $contextModelClassname = $comment->context;
                $contextModelId = $comment->context_id;
            }
        }

        $commentNotificationUser = CommentsUtility::getCommentNotificationUser($contextModelClassname, $contextModelId, Yii::$app->user->id);

        $options = [];
        /** @var AmosComments $moduleComments */
        $moduleComments = AmosComments::instance();
        if ($moduleComments->showBellIconInContentView && $commentNotificationUser->enable) {
            $options['activateViewBellIcon'] = true;
            $options['titleNotificationBellEnabled'] = CommentsUtility::getTitleNotificationBell()['enabled'];
//            $options['titleNotificationBellDisabled'] = CommentsUtility::getTitleNotificationBell()['disabled'];
        }

        return $options;
    }

}
