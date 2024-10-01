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
use open20\amos\comments\base\PartecipantsNotification;
use open20\amos\comments\models\Comment;
use open20\amos\comments\models\CommentReply;
use open20\amos\comments\models\search\CommentBlacklistSearch;
use open20\amos\comments\models\search\CommentDashboardSearch;
use open20\amos\comments\models\search\CommentModerationSearch;
use open20\amos\comments\models\search\CommentReplySearch;
use open20\amos\comments\models\search\CommentSearch;
use open20\amos\comments\utility\CommentsUtility;
use Yii;
use open20\amos\comments\models\CommentDashboard;
use yii\base\InvalidConfigException;
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
use yii\web\Response;
use yii\web\UnauthorizedHttpException;


/**
 * Class CommentDashboardController
 * CommentDashboardController implements the CRUD actions for CommentDashboard model.
 *
 * @property \open20\amos\comments\models\CommentDashboard $model
 * @property \ $modelSearch
 *
 * @package open20\amos\comments\controllers\base
 */
class CommentDashboardController extends CrudController
{

    /**
     * @var string $layout
     */
    public $layout = 'main';

    public function init()
    {
        $this->setModelObj(new CommentDashboard());
        $this->setModelSearch(new CommentDashboardSearch());

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

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'netiquette',
                            'update-ajax',
                            'update-netiquette-grid-view',
                        ],
                        'roles' => ['COMMENTDASHBOARD_ADMINISTRATOR']
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'moderation',
                            'to-validate-comment-replies'
                        ],
                        'roles' => [CommentsUtility::commentsValidatorRole()]
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'validate-comment',
                            'reject-comment',
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

    /**
     * @param $layout
     * @return string|\yii\web\Response
     */
    public function actionIndex($layout = null)
    {
        return $this->redirect(['netiquette']);
    }

    /**
     * @return string|Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionNetiquette()
    {
        if (!AmosComments::instance()->enableBlacklistWordsFilter) {
            return $this->redirect(Yii::$app->getHomeUrl());
        }

        Url::remember();

        $this->setUpLayout('list');
        $this->setDataProvider($this->modelSearch->search(Yii::$app->request->getQueryParams()));
        $blacklistDataProvider = (new CommentBlacklistSearch())->search(Yii::$app->request->getQueryParams());

        return $this->render('netiquette', [
            'dataProvider' => $this->getDataProvider(),
            'blacklistDataProvider' => $blacklistDataProvider,
            'currentView' => $this->getCurrentView(),
        ]);
    }

    /**
     * @return string|Response
     * @throws InvalidConfigException
     */
    public function actionModeration()
    {
        if (!AmosComments::instance()->enableModeration) {
            return $this->redirect(Yii::$app->getHomeUrl());
        }

        Url::remember();

        $this->setUpLayout('list');
        // Dashboard
        $this->setDataProvider($this->modelSearch->search(Yii::$app->request->getQueryParams()));
        // To validate comments
        $commentsDataProvider = (new CommentSearch())->searchToValidate(Yii::$app->request->getQueryParams());
        // To validate comment replies
        $commentRepliesDataProvider = (new CommentReplySearch())->searchToValidate(Yii::$app->request->getQueryParams());
        $toValidateCommentReplies = $commentRepliesDataProvider->getModels();
        $commentsIds = [];
        /** @var CommentReply $reply */
        foreach ($toValidateCommentReplies as $reply) {
            $commentsIds[] = $reply->comment_id;
        }
        $commentFromRepliesDataProvider = (new CommentSearch())->search(Yii::$app->request->getQueryParams());
        $commentFromRepliesDataProvider->query->andWhere(['in', 'id', $commentsIds]);

        return $this->render('moderation', [
            'dataProvider' => $this->getDataProvider(),
            'commentsDataProvider' => $commentsDataProvider,
            'commentFromRepliesDataProvider' => $commentFromRepliesDataProvider,
            'currentView' => $this->getCurrentView(),
        ]);
    }

    /**
     * @return string
     * @throws InvalidConfigException
     * @throws \Exception
     */
    public function actionToValidateCommentReplies()
    {
        $expandRowKey = \Yii::$app->request->post('expandRowKey');
        $commentReply = CommentReply::find()
            ->andWhere(['comment_id' => $expandRowKey])
            ->andWhere(['status' => Comment::STATUS_TOVALIDATE]);

        $dataProviderReply = new ActiveDataProvider([
            'query' => $commentReply,
            'pagination' => false
        ]);

        \Yii::$app->request->setQueryParams(['aid' => $expandRowKey]);

        return $this->renderCommentRepliesGridView($dataProviderReply);
    }

    /**
     * @param $word
     * @param $page
     * @return string
     * @throws InvalidConfigException
     */
    public function actionUpdateNetiquetteGridView($word, $page)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $blacklistDataProvider = (new CommentBlacklistSearch())->search(Yii::$app->request->getQueryParams());
        $blacklistDataProvider->query->andFilterWhere(['like', 'word', $word]);
        $blacklistDataProvider->pagination->page = $page;

        return $this->renderAjax('@vendor/open20/amos-comments/src/views/comment-blacklist/parts/gridView', [
            'dataProvider' => $blacklistDataProvider,
            'currentView' => $this->getCurrentView()
        ]);
    }

    /**
     * @param integer $id
     * @param string $attribute
     * @param boolean $value
     * @return void
     */
    public function actionUpdateAjax($id, $attribute, $value)
    {
        $commentDashboard = CommentDashboard::findOne($id);
        if ($commentDashboard) {
            $commentDashboard->$attribute = Json::decode($value);
            if ($commentDashboard->save(false)) {
                if (Json::decode($value) == false) {
                    $partecipantsnotify = new PartecipantsNotification();

                    // Publish all comments
                    $comments = Comment::find()->andWhere(['status' => Comment::STATUS_TOVALIDATE])->all();
                    if ($comments) {
                        foreach ($comments as $comment) {
                            $contextModel = $comment->contextModel;
                            if ($contextModel) {
                                $isCommunityContent = false;
                                if (method_exists($contextModel, 'isNetworkContent')) {
                                    $isCommunityContent = $contextModel->isNetworkContent('community');
                                }
                                if (!$isCommunityContent) {
                                    // Platform contents
                                    if ($attribute == 'moderation_platform') {
                                        $comment->status = Comment::STATUS_PUBLISHED;
                                        $comment->save(false);
                                        // Notify new comment to participants
                                        $partecipantsnotify->partecipantAlert($comment);
                                    }
                                } else {
                                    // Community contents
                                    if ($attribute == 'moderation_community') {
                                        $comment->status = Comment::STATUS_PUBLISHED;
                                        $comment->save(false);
                                        // Notify new comment to participants
                                        $partecipantsnotify->partecipantAlert($comment);
                                    }
                                }
                            }
                        }
                    }
                    // Publish all replies
                    $commentReplies = CommentReply::find()->andWhere(['status' => Comment::STATUS_TOVALIDATE])->all();
                    if ($commentReplies) {
                        foreach ($commentReplies as $commentReply) {
                            $contextModel = $commentReply->comment->contextModel;
                            if ($contextModel) {
                                $isCommunityContent = false;
                                if (method_exists($contextModel, 'isNetworkContent')) {
                                    $isCommunityContent = $contextModel->isNetworkContent('community');
                                }
                                if (!$isCommunityContent) {
                                    // Platform contents
                                    if ($attribute == 'moderation_platform') {
                                        $commentReply->status = Comment::STATUS_PUBLISHED;
                                        $commentReply->save(false);
                                        // Notify new comment to participants
                                        $partecipantsnotify->partecipantAlert($commentReply);
                                    }
                                } else {
                                    // Community contents
                                    if ($attribute == 'moderation_community') {
                                        $commentReply->status = Comment::STATUS_PUBLISHED;
                                        $commentReply->save(false);
                                        // Notify new comment to participants
                                        $partecipantsnotify->partecipantAlert($commentReply);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * @param integer $id
     * @param bool $isCommentReply
     * @param string|array $redirectUrl
     * @return Response
     * @throws UnauthorizedHttpException
     */
    public function actionValidateComment(int $id, bool $isCommentReply = false, $redirectUrl = null)
    {
        if ($isCommentReply) {
            $comment = CommentReply::findOne($id);
            if ($comment) {
                $contextModel = $comment->comment->contextModel;
            }
        } else {
            $comment = Comment::findOne($id);
            if ($comment) {
                $contextModel = $comment->contextModel;
            }
        }
        if (!empty($contextModel) && !CommentsUtility::userIsCommentsValidator($contextModel)) {
            throw new UnauthorizedHttpException(AmosComments::t('amoscomments', 'Non sei autorizzato ad eseguire questa operazione.'));
        }

        if ($comment) {
            $comment->status = Comment::STATUS_PUBLISHED;
            if ($comment->save(false)) {
                CommentsUtility::notifyParticipants($comment);
                CommentsUtility::sendValidationNotification($comment);
                Yii::$app->getSession()->addFlash('success', AmosComments::t('amoscomments', 'Commento validato correttamente.'));
            } else {
                Yii::$app->getSession()->addFlash('danger', AmosComments::t('amoscomments', 'Si è verificato un errore durante la validazione del commento.'));
            }
        } else {
            Yii::$app->getSession()->addFlash('danger', AmosComments::t('amoscomments', 'Commento non trovato.'));
        }

        if ($redirectUrl) {
            return $this->redirect($redirectUrl);
        }
        return $this->redirect(Url::previous());
    }

    /**
     * @param integer $id
     * @param bool $isCommentReply
     * @param string|array $redirectUrl
     * @return Response
     * @throws UnauthorizedHttpException
     */
    public function actionRejectComment(int $id, bool $isCommentReply = false, $redirectUrl = null)
    {
        if ($isCommentReply) {
            $comment = CommentReply::findOne($id);
            if ($comment) {
                $contextModel = $comment->comment->contextModel;
            }
        } else {
            $comment = Comment::findOne($id);
            if ($comment) {
                $contextModel = $comment->contextModel;
            }
        }
        if (!empty($contextModel) && !CommentsUtility::userIsCommentsValidator($contextModel)) {
            throw new UnauthorizedHttpException(AmosComments::t('amoscomments', 'Non sei autorizzato ad eseguire questa operazione.'));
        }

        if ($comment) {
            $comment->status = Comment::STATUS_REJECTED;
            if ($comment->save(false)) {
                CommentsUtility::sendRejectionNotification($comment);
                Yii::$app->getSession()->addFlash('success', AmosComments::t('amoscomments', 'Commento rifiutato correttamente.'));
            } else {
                Yii::$app->getSession()->addFlash('danger', AmosComments::t('amoscomments', 'Si è verificato un errore durante il rifiuto del commento.'));
            }
        } else {
            Yii::$app->getSession()->addFlash('danger', AmosComments::t('amoscomments', 'Commento non trovato.'));
        }

        if ($redirectUrl) {
            return $this->redirect($redirectUrl);
        }
        return $this->redirect(Url::previous());
    }

    /**
     * Before action
     * @param $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        $titleSection = AmosComments::t('amoscomments', 'Dashboard commenti');
        if (Yii::$app->controller->action->id == 'netiquette') {
            $titleSection = AmosComments::t('amoscomments', 'Netiquette');
        } else if (Yii::$app->controller->action->id == 'moderation') {
            $titleSection = AmosComments::t('amoscomments', 'Moderazione commenti');
        }
//        $labelLinkAll = '';
//        $urlLinkAll = '';
//        $titleLinkAll = AmosComments::t('amoscomments', 'Visualizza tutti i termini della blacklist');

        $subTitleSection = Html::tag('p', AmosComments::t('amoscomments', 'Gestisci qui l’attivazione delle funzionalità di moderazione dei commenti'));

//        $labelCreate = AmosComments::t('amoscomments', 'Aggiungi termine');
//        $titleCreate = AmosComments::t('amoscomments', 'Aggiungi un nuovo termine alla blacklist');
//        $labelManage = \Yii::t('amoscollaborations', 'Gestisci');
//        $titleManage = \Yii::t('amoscollaborations', 'Gestisci proposte di collaborazione');
//        $urlCreate = '/comments/comment-blacklist/create';
//        $urlManage = null;

        $this->view->params = [
            'isGuest' => \Yii::$app->user->isGuest,
            'modelLabel' => 'commentBlacklist',
            'titleSection' => $titleSection,
            'subTitleSection' => $subTitleSection,
//            'urlLinkAll' => $urlLinkAll,
//            'labelLinkAll' => $labelLinkAll,
//            'titleLinkAll' => $titleLinkAll,
//            'labelManage' => $labelManage,
//            'titleManage' => $titleManage,
//            'labelCreate' => $labelCreate,
//            'titleCreate' => $titleCreate,
//            'urlCreate' => $urlCreate,
//            'urlManage' => $urlManage,
            'hideCreate' => true,

        ];
        if (!parent::beforeAction($action)) {
            return false;
        }

        // other custom code here

        return true;
    }

    /**
     * @param $dataProvider
     * @return string
     * @throws \Exception
     */
    public function renderCommentRepliesGridView($dataProvider)
    {
        return \kartik\grid\GridView::widget([
            'id' => 'comment-replies-gridview',
            'dataProvider' => $dataProvider,
            'responsive' => true,
            'export' => false,
            'pjax' => true,
            'pjaxSettings' => [
                'options' => [
                    'id' => 'product-grid',
                    'timeout' => (isset(\Yii::$app->params['timeout']) ? \Yii::$app->params['timeout'] : 20000),
                    'enablePushState' => false
                ]
            ],
            'columns' => [
                [
                    'label' => AmosComments::t('amoscomments', 'Risposta'),
                    'attribute' => 'comment_reply_text',
                    'value' => function ($model) {
                        /** @var CommentReply $model */
                        if (strlen($model->comment_reply_text) > 50) {
                            return substr($model->comment_reply_text, 0, 50) . '...';
                        } else {
                            return $model->comment_reply_text;
                        }
                    },
                    'format' => 'html',
                    'width' => '80%',
                ],
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                    'template' => '{view}{validate}{reject}',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            /** @var CommentReply $model */
                            $contextModel = $model->comment->contextModel;
                            $viewUrl = $contextModel->getFullViewUrl();
                            $anchor = 'comment-reply-' . $model->id;
                            /** @var AmosComments $moduleComments */
                            $moduleComments = \Yii::$app->getModule(AmosComments::getModuleName());
                            if (!$moduleComments->disablePagination) {
                                // Get page and page size of the parent comment
                                $pageSize = $moduleComments->paginationPageSize;
                                $commentsCount = Comment::find()
                                    ->andWhere(['context' => $model->comment->context])
                                    ->andWhere(['context_id' => $model->comment->context_id])
                                    ->count();
                                $commentPosition = Comment::find()
                                    ->andWhere(['context' => $model->comment->context])
                                    ->andWhere(['context_id' => $model->comment->context_id])
                                    ->andWhere(['<=', 'created_at', $model->comment->created_at])
                                    ->count();
                                $page = ceil(($commentsCount - $commentPosition + 1) / $pageSize);
                                if ($page > 1) {
                                    $viewUrl .= '&page=' . $page . '&per-page=' . $pageSize;
                                }
                            }

                            return Html::a(
                                '<span class="mdi mdi-eye"></span>',
                                [$viewUrl, '#' => $anchor],
                                [
                                    'title' => AmosComments::t('amoscomments', 'Visualizza risposta in una nuova scheda'),
                                    'class' => 'btn btn-tool-secondary',
                                    'target' => '_blank',
                                ]
                            );
                        },
                        'validate' => function ($url, $model) {
                            /** @var CommentReply $model */
                            return Html::a(
                                '<span class="mdi mdi-check-circle"></span>',
                                ['/comments/comment-dashboard/validate-comment',
                                    'id' => $model->id,
                                    'isCommentReply' => true,
                                    'redirectUrl' => '/comments/comment-dashboard/moderation'
                                ],
                                [
                                    'title' => AmosComments::t('amoscomments', 'Valida risposta'),
                                    'class' => 'btn btn-success',
//                                    'data-confirm' => AmosComments::t('amoscore', 'Sei sicuro di voler cancellare questo elemento?'),
                                ]
                            );
                        },
                        'reject' => function ($url, $model) {
                            /** @var CommentReply $model */
                            return Html::a(
                                '<span class="mdi mdi-close-circle"></span>',
                                ['/comments/comment-dashboard/reject-comment',
                                    'id' => $model->id,
                                    'isCommentReply' => true,
                                    'redirectUrl' => '/comments/comment-dashboard/moderation'
                                ],
                                [
                                    'title' => AmosComments::t('amoscomments', 'Rifiuta risposta'),
                                    'class' => 'btn btn-danger',
                                    'data-confirm' => AmosComments::t('amoscomments', 'Sei sicuro di voler rifiutare questa risposta?'),
                                ]
                            );
                        },
                    ]
                ],
            ],
            'panel' => [
                'before' => false,
                'heading' => '<h3 class="panel-title">' . AmosComments::t('amoscomments', 'Risposte') . '</h3>',
//                'type' => 'success',
                'after' => false,
                //Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset Grid', ['index'], ['class' => 'btn btn-info']),
                'footer' => false
            ],
        ]);
    }

}
