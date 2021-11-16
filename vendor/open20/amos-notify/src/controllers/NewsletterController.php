<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\controllers
 */

namespace open20\amos\notificationmanager\controllers;

use open20\amos\core\forms\editors\m2mWidget\controllers\M2MWidgetControllerTrait;
use open20\amos\core\forms\editors\m2mWidget\M2MEventsEnum;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\interfaces\ModelGrammarInterface;
use open20\amos\core\interfaces\ModelLabelsInterface;
use open20\amos\core\interfaces\NewsletterInterface;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\core\record\Record;
use open20\amos\core\utilities\SortModelsUtility;
use open20\amos\notificationmanager\AmosNotify;
use open20\amos\notificationmanager\base\builder\NewsletterBuilder;
use open20\amos\notificationmanager\exceptions\NewsletterException;
use open20\amos\notificationmanager\models\Newsletter;
use open20\amos\notificationmanager\models\NewsletterContents;
use open20\amos\notificationmanager\models\NewsletterContentsConf;
use open20\amos\notificationmanager\utility\NewsletterUtility;
use yii\db\ActiveQuery;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

/**
 * Class NewsletterController
 * This is the class for controller "NewsletterController".
 * @package backend\amos\notify\controllers
 */
class NewsletterController extends \open20\amos\notificationmanager\controllers\base\NewsletterController
{
    /**
     * M2MWidgetControllerTrait
     */
    use M2MWidgetControllerTrait;
    
    /**
     * @var NewsletterContentsConf|null $newsletterConf
     */
    protected $newsletterConf = null;
    
    /**
     * @var int $confId
     */
    protected $confId = 0;
    
    /**
     * @var int $saveNewsletterInitialOrder
     */
    protected $newsletterSaveOrder = 0;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        $this->setMmTableName($this->notifyModule->model('NewsletterContents'));
        $this->setStartObjClassName($this->notifyModule->model('Newsletter'));
        $this->setMmStartKey('newsletter_id');
        $this->setMmTargetKey('content_id');
        $this->setRedirectAction('update');
        $this->setModuleClassName(AmosNotify::className());
        $this->setCustomQuery(true);
        $this->on(M2MEventsEnum::EVENT_BEFORE_ASSOCIATE_M2M, [$this, 'beforeAssociateM2m']);
        $this->on(M2MEventsEnum::EVENT_AFTER_ASSOCIATE_M2M, [$this, 'afterAssociateM2m']);
        $this->on(M2MEventsEnum::EVENT_BEFORE_INTERCECT_M2M, [$this, 'beforeIntercectM2m']);
        $this->on(M2MEventsEnum::EVENT_AFTER_FIND_START_OBJ_M2M, [$this, 'afterFindStartObjM2m']);
        
        $this->btnAssociaLabelPrefix = AmosNotify::t('amosnotify', '#manage');
        
        $this->setUpLayout('main');
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
                            'stop-send-newsletter'
                        ],
                        'roles' => ['NEWSLETTER_ADMINISTRATOR']
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'elimina-m2m',
                            'annulla-m2m',
                            'associa-m2m',
                            'send-newsletter',
                            'send-test-newsletter',
                            're-send-newsletter',
                            'created-by',
                            'order-content'
                        ],
                        'roles' => ['NEWSLETTER_ADMINISTRATOR', 'NEWSLETTER_MANAGER']
                    ],
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
     * @return NewsletterContentsConf
     */
    public function getNewsletterConf()
    {
        return $this->newsletterConf;
    }
    
    /**
     * @param NewsletterContentsConf $newsletterConf
     */
    public function setNewsletterConf($newsletterConf)
    {
        $this->newsletterConf = $newsletterConf;
    }
    
    /**
     * @param Record $model
     * @return ActiveQuery
     * @throws NewsletterException
     * @throws \yii\base\InvalidConfigException
     */
    public function getAssociaM2mQuery($model)
    {
        return $this->modelSearch->getAssociaM2mQuery($model, \Yii::$app->request->post());
    }
    
    /**
     * @param \yii\base\Event $event
     * @return \yii\web\Response|void
     * @throws \yii\base\InvalidConfigException
     */
    public function beforeAssociateM2m($event)
    {
        if (!$this->confId) {
            $this->confId = \Yii::$app->request->get('confId');
            if (!$this->confId) {
                \Yii::$app->getSession()->addFlash('danger', AmosNotify::t('amosnotify', '#error_associa_m2m_conf_id_not_present'));
                return $this->redirect(['/notify/newsletter/update', 'id' => \Yii::$app->request->get('id')]);
            }
            /** @var NewsletterContentsConf $newsletterConfModel */
            $newsletterConfModel = $this->notifyModule->createModel('NewsletterContentsConf');
            $newsletterConf = $newsletterConfModel::findOne(['id' => $this->confId]);
            $this->setNewsletterConf($newsletterConf);
        }
        
        $this->setMmTableAttributesDefault(['newsletter_contents_conf_id' => $this->confId]);
        $this->setMmTableAdditionalAttributesToSearch(['newsletter_contents_conf_id' => $this->confId]);
        $this->setTargetObjClassName($newsletterConf->classname);
        $this->setTargetUrl('associa-m2m');
    }
    
    /**
     * @param \yii\base\Event $event
     * @return \yii\web\Response|void
     * @throws \yii\base\InvalidConfigException
     */
    public function afterAssociateM2m($event)
    {
        $save = \Yii::$app->request->post('save');
        if (!is_null($save) && ($save == '0')) {
            return;
        }
        /** @var NewsletterContents $newsletterContentsModel */
        $newsletterContentsModel = $this->notifyModule->createModel('NewsletterContents');
        $notInTargets = $event->sender['notInTargets'];
        $targets = $newsletterContentsModel::find()
            ->andWhere(['newsletter_id' => \Yii::$app->request->get('id')])
            ->andWhere(['newsletter_contents_conf_id' => $this->confId])
            ->andWhere(['not in', 'content_id', $notInTargets])->all();
        foreach ($targets as $singleTarget) {
            $singleTarget->delete();
        }
    }
    
    /**
     * @param \yii\base\Event $event
     * @return \yii\web\Response|void
     * @throws \yii\base\InvalidConfigException
     */
    public function afterFindStartObjM2m($event)
    {
        $this->model = $event->sender['startObj'];
    }
    
    /**
     * @param \yii\base\Event $event
     * @return \yii\web\Response|void
     * @throws \yii\base\InvalidConfigException
     */
    public function beforeIntercectM2m($event)
    {
        if (\Yii::$app->request->post()) {
            if (!$this->newsletterSaveOrder) {
                $maxOrderBeforeSave = $this->model->getNewsletterContentsByContentConfIdQuery($this->confId)->max(\Yii::$app->db->quoteColumnName('order'));
                $this->newsletterSaveOrder = $maxOrderBeforeSave;
            }
            $this->newsletterSaveOrder++;
            $mmTableAttributesDefault = $this->getMmTableAttributesDefault();
            $mmTableAttributesDefault['order'] = $this->newsletterSaveOrder;
            $this->setMmTableAttributesDefault($mmTableAttributesDefault);
        }
    }
    
    /**
     * This method returns the model label
     * @param Record $contentConfModel
     * @param string $prefix
     * @return string
     */
    public function makeModelLabel($contentConfModel)
    {
        if (($contentConfModel instanceof ModelLabelsInterface) && (($modelGrammar = $contentConfModel->getGrammar()) instanceof ModelGrammarInterface)) {
            $modelLabel = $modelGrammar->getModelLabel();
        } else {
            $modelLabel = AmosNotify::t('amosnotify', '#contents');
        }
        return $modelLabel;
    }
    
    /**
     * This method returns the manage content title used even as association button label
     * @param Record $contentConfModel
     * @param string $prefix
     * @return string
     */
    public function makeManageContentsTitle($contentConfModel, $prefix = '', $modelLabel = '')
    {
        if (!$modelLabel) {
            $modelLabel = $this->makeModelLabel($contentConfModel);
        }
        if (!$prefix) {
            $prefix = AmosNotify::t('amosnotify', '#manage');
        }
        $contentTitle = $prefix . ' ' . $modelLabel;
        return $contentTitle;
    }
    
    /**
     * This methods returns all the configured models as an array of NewsletterContentsConf objects.
     * @return NewsletterContentsConf[]
     * @throws \yii\base\InvalidConfigException
     */
    public function getAllNewsletterContentsConfs()
    {
        /** @var NewsletterContentsConf $newsletterContentsConfModel */
        $newsletterContentsConfModel = $this->notifyModule->createModel('NewsletterContentsConf');
        /** @var NewsletterContentsConf[] $newsletterContentsConfs */
        $newsletterContentsConfs = $newsletterContentsConfModel::find()->orderBy(['order' => SORT_ASC])->all();
        return $newsletterContentsConfs;
    }
    
    /**
     * @return string
     */
    public function actionCreatedBy()
    {
        $this->setDataProvider($this->modelSearch->searchCreatedBy(\Yii::$app->request->getQueryParams()));
        
        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Table')),
                'url' => '?currentView=grid'
            ],
        ]);
        $this->setCurrentView($this->getAvailableView('grid'));
        
        return $this->baseListsAction(AmosNotify::txt('#created_by_me_newsletters'));
    }
    
    /**
     * This is the main action for the newsletters because this action send the real newsletter.
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionSendNewsletter($id)
    {
        $this->model = $this->findModel($id);
        
        if ($this->model->isSentNewsletter()) {
            \Yii::$app->getSession()->addFlash('danger', AmosNotify::txt('#error_newsletter_already_sent'));
            return $this->baseActionsRedirect();
        }
        
        if ($this->model->isWaitSendNewsletter()) {
            \Yii::$app->getSession()->addFlash('danger', AmosNotify::txt('#error_newsletter_already_wait_send'));
            return $this->baseActionsRedirect();
        }
        
        if ($this->model->isWaitReSendNewsletter()) {
            \Yii::$app->getSession()->addFlash('danger', AmosNotify::txt('#error_newsletter_already_wait_re_send'));
            return $this->baseActionsRedirect();
        }
        
        if (!count($this->model->newsletterContents)) {
            \Yii::$app->getSession()->addFlash('danger', AmosNotify::txt('#error_send_newsletter_without_contents'));
            return $this->baseActionsRedirect();
        }
        
        if ($this->model->setWaitSendNewsletter()) {
            $ok = NewsletterUtility::addNewsletterNotification($this->notifyModule, $this->model);
            if ($ok) {
                \Yii::$app->getSession()->addFlash('success', AmosNotify::txt('#newsletter_success_to_be_sent'));
            } else {
                $this->model->status = Newsletter::WORKFLOW_STATUS_DRAFT;
                $this->model->save();
                \Yii::$app->getSession()->addFlash('danger', AmosNotify::txt('#error_cannot_save_newsletter_notification'));
            }
        } else {
            \Yii::$app->getSession()->addFlash('danger', AmosNotify::txt('#error_saving_to_send_status_newsletter'));
        }
        
        return $this->baseActionsRedirect();
    }
    
    /**
     * This action sends a test newsletter which means it's a complete newsletter with all the contents chosen by the creator.
     * @return \yii\web\Response
     */
    public function actionSendTestNewsletter()
    {
        if (!\Yii::$app->request->getIsAjax()) {
            \Yii::$app->getSession()->addFlash('danger', AmosNotify::txt('#error_send_test_newsletter_not_ajax_request'));
            return $this->baseActionsRedirect();
        }
        
        if (!\Yii::$app->request->getIsPost()) {
            return $this->asJson([
                'success' => 0,
                'message' => AmosNotify::txt('#error_send_test_newsletter_not_post_request')
            ]);
        }
        
        $post = \Yii::$app->request->post();
        
        try {
            $this->model = $this->findModel($post['id']);
        } catch (NotFoundHttpException $exception) {
            return $this->asJson([
                'success' => 0,
                'message' => AmosNotify::txt('#error_send_test_newsletter_not_found')
            ]);
        }
        
        // Create and send test newsletter
        $newsletterBuilder = new NewsletterBuilder(['newsletter' => $this->model]);
        $ok = $newsletterBuilder->sendTestEmail($post['testEmail']);
        
        if ($ok) {
            return $this->asJson([
                'success' => 1,
                'message' => AmosNotify::txt('#send_test_newsletter_success')
            ]);
        } else {
            return $this->asJson([
                'success' => 0,
                'message' => AmosNotify::txt('#send_test_newsletter_failed')
            ]);
        }
    }
    
    /**
     * This action resend an already sent newsletter.
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionReSendNewsletter($id)
    {
        $this->model = $this->findModel($id);
        
        if (!$this->model->isSentNewsletter()) {
            \Yii::$app->getSession()->addFlash('danger', AmosNotify::txt('#error_newsletter_not_yet_sent'));
            return $this->baseActionsRedirect();
        }
        
        if ($this->model->isWaitReSendNewsletter()) {
            \Yii::$app->getSession()->addFlash('danger', AmosNotify::txt('#error_newsletter_already_wait_re_send'));
            return $this->baseActionsRedirect();
        }
        
        if ($this->model->setWaitReSendNewsletter()) {
            $ok = NewsletterUtility::addNewsletterNotification($this->notifyModule, $this->model);
            if ($ok) {
                \Yii::$app->getSession()->addFlash('success', AmosNotify::txt('#newsletter_success_to_be_re_sent'));
            } else {
                $this->model->setSentNewsletter(true);
                \Yii::$app->getSession()->addFlash('danger', AmosNotify::txt('#error_cannot_save_newsletter_notification'));
            }
        } else {
            \Yii::$app->getSession()->addFlash('danger', AmosNotify::txt('#error_saving_to_resend_status_newsletter'));
        }
    
        return $this->baseActionsRedirect();
    }
    
    /**
     * This action stop the sending of newsletter when it's in to send o to resend statuses.
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionStopSendNewsletter($id)
    {
        $this->model = $this->findModel($id);
        $isWaitSend = $this->model->isWaitSendNewsletter();
        $isWaitReSend = $this->model->isWaitReSendNewsletter();
        
        if (!$isWaitSend && !$isWaitReSend) {
            \Yii::$app->getSession()->addFlash('danger', AmosNotify::txt('#error_stop_send_newsletter_not_permitted'));
            return $this->baseActionsRedirect();
        }
        
        if ($isWaitSend) {
            $ok = $this->model->setDraftNewsletter();
        } elseif ($isWaitReSend) {
            $ok = $this->model->setSentNewsletter(true);
        }
        
        if ($ok) {
            $ok = NewsletterUtility::removeNewsletterNotification($this->notifyModule, $this->model);
            if ($ok) {
                \Yii::$app->getSession()->addFlash('success', AmosNotify::txt('#stop_send_newsletter_success'));
            } else {
                \Yii::$app->getSession()->addFlash('danger', AmosNotify::txt('#stop_send_newsletter_error'));
            }
        } else {
            \Yii::$app->getSession()->addFlash('danger', AmosNotify::txt('#error_saving_stop_send_status_newsletter'));
        }
        
        return $this->baseActionsRedirect();
    }
    
    /**
     * This action order the content provided accordingly with direction.
     * @param int $newsletterId
     * @param int $confId
     * @param int $id
     * @param string $direction
     * @return bool|\yii\web\Response
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    public function actionOrderContent($newsletterId, $confId, $id, $direction)
    {
        $isAjaxRequest = \Yii::$app->request->getIsAjax();
        if ($isAjaxRequest && !\Yii::$app->request->isPost) {
            throw new BadRequestHttpException(AmosNotify::txt('The request must be post'));
        }
        
        /** @var NewsletterContents $newsletterContentsModel */
        $newsletterContentsModel = $this->notifyModule->createModel('NewsletterContents');
        
        // Find the model to be ordered.
        /** @var NewsletterContents $model */
        $model = $newsletterContentsModel::findOne([
            'newsletter_id' => $newsletterId,
            'newsletter_contents_conf_id' => $confId,
            'content_id' => $id,
        ]);
        
        if (is_null($model)) {
            throw new NotFoundHttpException(BaseAmosModule::t('amoscore', 'The requested page does not exist.'));
        }
        
        // Find models to be reordered with the actual model.
        $orderList = $model->getNewsletterContentWithBrothers()->select(['id'])->orderBy(['order' => SORT_ASC])->column();
        
        $sortUtility = new SortModelsUtility([
            'model' => $model,
            'modelSortField' => 'order',
            'direction' => $direction,
            'orderList' => $orderList
        ]);
        $ok = $sortUtility->reorderModels();
        
        if ($isAjaxRequest) {
            return $ok;
        }
        
        if ($ok) {
            \Yii::$app->getSession()->addFlash('success', AmosNotify::txt('#element_moved'));
        } else {
            \Yii::$app->getSession()->addFlash('danger', AmosNotify::txt('#element_not_moved'));
        }
        
        return $this->redirect(['update', 'id' => $model->newsletter->id]);
    }
}
