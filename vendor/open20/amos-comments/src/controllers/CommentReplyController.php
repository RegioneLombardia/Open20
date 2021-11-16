<?php

/**
 * Lombardia Informatica S.p.A.
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
use open20\amos\comments\models\CommentReply;
use open20\amos\comments\models\search\CommentReplySearch;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use Yii;
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
                            'create-ajax'
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
                    'msg' => AmosComments::t('amoscomments', 'Validation errors! Check the data entered.')
                ],
            ];
        }

        if ($this->model->save()) {
            /** @var AmosComments $commentsModule */
            $commentsModule = Yii::$app->getModule(AmosComments::getModuleName());
            if (!$commentsModule->enableUserSendMailCheckbox || ($commentsModule->enableUserSendMailCheckbox && isset($post['send_reply_notify_mail']) && $post['send_reply_notify_mail'])) {
                $partecipantsnotify = $this->getParticipantsNotificationInstance();
                $partecipantsnotify->partecipantAlert($this->model);
            }
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
     */
    public function actionUpdate($id)
    {
        $this->setUpLayout('form');

        $this->model = $this->findModel($id);

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
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
}
