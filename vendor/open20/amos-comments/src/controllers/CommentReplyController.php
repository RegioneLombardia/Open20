<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comments\controllers
 * @category   CategoryName
 */

namespace open20\amos\comments\controllers;

use open20\amos\comments\AmosComments;
use open20\amos\comments\base\PartecipantsNotification;
use open20\amos\comments\exceptions\CommentsException;
use open20\amos\comments\models\Comment;
use open20\amos\comments\models\CommentDashboard;
use open20\amos\comments\models\CommentReply;
use open20\amos\comments\models\search\CommentReplySearch;
use open20\amos\comments\utility\CommentsUtility;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use Yii;
use yii\base\InvalidConfigException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Class CommentReplyController
 *
 * @property \open20\amos\comments\models\CommentReply $model
 *
 * @package open20\amos\comments\controllers
 */
class CommentReplyController extends CrudController
{
    /**
     * @var string $layout
     */
    public $layout = 'list';

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setModelObj(new CommentReply());
        $this->setModelSearch(new CommentReplySearch());

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt') . Html::tag('p', AmosComments::t('amoscomments', 'Table')),
                'url' => '?currentView=grid'
            ]
        ]);

        parent::init();
        $this->setUpLayout();
    }

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
                            //'create-ajax'
                        ],
                        'roles' => ['COMMENTS_ADMINISTRATOR', 'COMMENTS_CONTRIBUTOR']
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

    /**
     * This method returns a new instance of the PartecipantsNotification object.
     * @return PartecipantsNotification
     */
    protected function getParticipantsNotificationInstance()
    {
        return new PartecipantsNotification();
    }

    /**
     * @param string $layout
     * @return string
     */
    public function actionIndex($layout = NULL)
    {
        Url::remember();
        $this->setDataProvider($this->getModelSearch()->search(Yii::$app->request->getQueryParams()));
        return parent::actionIndex();
    }

    /**
     * @param int $id
     * @return string|\yii\web\Response
     */
    public function actionView($id)
    {
        $this->model = $this->findModel($id);

        if ($this->model->load(Yii::$app->request->post()) && $this->model->save()) {
            return $this->redirect(['view', 'id' => $this->model->id]);
        } else {
            return $this->render('view', ['model' => $this->model]);
        }
    }

    /**
     * @return CommentReply|\open20\amos\core\record\Record|string|\yii\web\Response
     */
    public function actionCreate()
    {
        $this->setUpLayout('form');
        $this->model = new CommentReply();
        $post = Yii::$app->request->post();

        /** @var AmosComments $commentsModule */
        $commentsModule = Yii::$app->getModule(AmosComments::getModuleName());

        if ($this->model->load($post) && $this->model->save()) {
            if (!$commentsModule->enableUserSendMailCheckbox || ($commentsModule->enableUserSendMailCheckbox && isset($post['send_reply_notify_mail']) && $post['send_reply_notify_mail'])) {
                $partecipantsnotify = $this->getParticipantsNotificationInstance();
                $partecipantsnotify->partecipantAlert($this->model);
            }
            if (Yii::$app->request->isAjax) {
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return $this->model;
            }
            return $this->redirect(Url::previous());
        } else {
            return $this->render('create', [
                'model' => $this->model,
            ]);
        }
    }

    /**
     * @return array|CommentReply|\open20\amos\core\record\Record
     * @throws CommentsException
     * @throws InvalidConfigException
     */
    public function actionCreateAjax()
    {
        $this->setUpLayout('form');
        $this->model = new CommentReply();

        if (!Yii::$app->request->isAjax) {
            throw new CommentsException(AmosComments::t('amoscomments', 'The request is not AJAX.'));
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->post();
        // Set initial status
        $this->model->status = Comment::STATUS_PUBLISHED;

        if (!$this->model->load($post)) {
            return [
                'error' => [
                    'msg' => AmosComments::t('amoscomments', 'Error loading parameters in the model.')
                ],
            ];
        }

        if (!$this->model->validate()) {
            return [
                'error' => [
                    'msg' => reset($this->model->getFirstErrors())//AmosComments::t('amoscomments', 'Validation errors! Check the data entered.')
                ],
            ];
        }

        if ($this->model->save()) {
            /** @var AmosComments $commentsModule */
            $commentsModule = Yii::$app->getModule(AmosComments::getModuleName());
            if ($commentsModule->enableModeration) {
                $commentDashboard =  CommentDashboard::find()->one();
                $contextModel = $this->model->comment->contextModel;
                $isCommunityContent = false;
                if (method_exists($contextModel, 'isNetworkContent')) {
                    $isCommunityContent = $contextModel->isNetworkContent('community');
                }
                if ((!$isCommunityContent && $commentDashboard->moderation_platform) || ($isCommunityContent && $commentDashboard->moderation_communities)) {
                    if ($this->model->comment->commentModeration->moderation) {
                        $this->model->status = Comment::STATUS_TOVALIDATE;
                        if ($this->model->save(false)) {
                            CommentsUtility::notifyMyActivities($isCommunityContent, $contextModel);
                        }
                    }
                }
            }

            if (!$commentsModule->enableUserSendMailCheckbox || ($commentsModule->enableUserSendMailCheckbox && isset($post['send_reply_notify_mail']) && $post['send_reply_notify_mail'])) {
                if ($this->model->status == Comment::STATUS_PUBLISHED) {
                    $partecipantsnotify = $this->getParticipantsNotificationInstance();
                    $partecipantsnotify->partecipantAlert($this->model);
                }
            }

            $this->enableCommentNotification($this->model->comment->context, $this->model->comment->context_id);

            return $this->model;
        } else {
            return [
                'error' => [
                    'msg' => AmosComments::t('amoscomments', 'Error during save comment reply.')
                ],
            ];
        }
    }

    /**
     * @param int $id
     * @return string|\yii\web\Response
     * @throws InvalidConfigException
     */
    public function actionUpdate($id)
    {
        $this->setUpLayout('form');

        $this->model = $this->findModel($id);

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                /** @var AmosComments $moduleComments */
                $moduleComments = Yii::$app->getModule(AmosComments::getModuleName());
                // If moderation is active put the comment in "to validate" status
                if ($moduleComments->enableModeration) {
                    $contextModel = $this->model->comment->contextModel;
                    $isCommunityContent = false;
                    if (method_exists($contextModel, 'isNetworkContent')) {
                        $isCommunityContent = $contextModel->isNetworkContent('community');
                    }
                    $commentDashboard = CommentDashboard::find()->one();
                    if ((!$isCommunityContent && $commentDashboard->moderation_platform) || ($isCommunityContent && $commentDashboard->moderation_communities)) {
                        if ($this->model->comment->commentModeration->moderation && $this->model->status == Comment::STATUS_PUBLISHED) {
                            $this->model->status = Comment::STATUS_TOVALIDATE;
                            if ($this->model->save(false)) {
                                CommentsUtility::notifyMyActivities($isCommunityContent, $contextModel);
                            }
                        }
                    }
                }
                Yii::$app->getSession()->addFlash('success', AmosComments::t('amoscomments', 'Comment reply successfully updated.'));
                return $this->redirect(Url::previous());
            } else {
                Yii::$app->getSession()->addFlash('danger', AmosComments::t('amoscomments', 'Comment reply not updated, check the data entered.'));
                return $this->render('update', [
                    'model' => $this->model,
                    'fid' => NULL,
                    'dataField' => NULL,
                    'dataEntity' => NULL,
                ]);
            }
        } else {
            return $this->render('update', [
                'model' => $this->model,
                'fid' => NULL,
                'dataField' => NULL,
                'dataEntity' => NULL,
            ]);
        }
    }

    /**
     * @param int $id
     * @return \yii\web\Response
     */
    public function actionDelete($id)
    {
        $this->model = $this->findModel($id);
        if ($this->model) {
            $this->model->delete();
            if (!$this->model->getErrors()) {
                Yii::$app->getSession()->addFlash('success', AmosComments::t('amoscomments', 'Comment reply successfully deleted.'));
            } else {
                Yii::$app->getSession()->addFlash('danger', AmosComments::t('amoscomments', 'Errors while deleting comment reply.'));
            }
        } else {
            Yii::$app->getSession()->addFlash('danger', AmosComments::t('amoscomments', 'Comment reply not found.'));
        }
        return $this->redirect(Url::previous());
    }

    /**
     * Method to enable comment notification for auth user
     * Enable only if comment notification user not is set!
     *
     * @param string|null $model_context_classname
     * @param int|null $model_context_id
     * @return void
     */
    private function enableCommentNotification(string $model_context_classname, int $model_context_id)
    {
        CommentsUtility::saveCommentNotificationUser($model_context_classname, $model_context_id);
    }
}
