<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\models
 * @category   CategoryName
 */

namespace open20\amos\notificationmanager\models;

use open20\amos\core\record\Record;
use open20\amos\notificationmanager\AmosNotify;
use open20\amos\notificationmanager\utility\NewsletterUtility;
use open20\amos\workflow\behaviors\WorkflowLogFunctionsBehavior;
use raoul2000\workflow\base\SimpleWorkflowBehavior;
use yii\db\ActiveQuery;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * Class Newsletter
 * This is the model class for table "notification_newsletter".
 *
 * @method \open20\amos\core\workflow\ContentDefaultWorkflowDbSource getWorkflowSource()
 *
 * @package open20\amos\notificationmanager\models
 */
class Newsletter extends \open20\amos\notificationmanager\models\base\Newsletter
{
    // Workflow constants
    const WORKFLOW_ID = 'NewsletterWorkflow';
    const WORKFLOW_INITIAL_STATUS_ID = 'NewsletterWorkflow/DRAFT';
    const WORKFLOW_STATUS_DRAFT = 'NewsletterWorkflow/DRAFT';
    const WORKFLOW_STATUS_WAIT_SEND = 'NewsletterWorkflow/WAITSEND';
    const WORKFLOW_STATUS_SENDING = 'NewsletterWorkflow/SENDING';
    const WORKFLOW_STATUS_SENT = 'NewsletterWorkflow/SENT';
    const WORKFLOW_STATUS_WAIT_RESEND = 'NewsletterWorkflow/WAITRESEND';
    
    /**
     * Used for view action.
     */
    const SCENARIO_VIEW = 'scenario_view';
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        if ($this->isNewRecord) {
            $this->status = self::WORKFLOW_INITIAL_STATUS_ID;
        }
    }
    
    /**
     * @inheritdoc
     */
    public function representingColumn()
    {
        return [
            'subject'
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'workflow' => [
                'class' => SimpleWorkflowBehavior::className(),
                'defaultWorkflowId' => self::WORKFLOW_ID,
                'propagateErrorsToModel' => true
            ],
            'workflowLog' => [
                'class' => WorkflowLogFunctionsBehavior::className()
            ]
        ]);
    }
    
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return ArrayHelper::merge(parent::scenarios(), [
            self::SCENARIO_VIEW => [
                'status'
            ]
        ]);
    }
    
    /**
     * @inheritdoc
     */
    public function getModelModuleName()
    {
        return AmosNotify::getModuleName();
    }
    
    /**
     * @inheritdoc
     */
    public function getWorkflowStatusLabel()
    {
        $status = parent::getWorkflowStatusLabel();
        return ((strlen($status) > 0) ? AmosNotify::t('amosnotify', $status) : '-');
    }
    
    /**
     * @param int $confId
     * @return \yii\db\ActiveQuery
     */
    public function getNewsletterContentsByContentConfIdQuery($confId)
    {
        return $this->getNewsletterContents()->andWhere(['newsletter_contents_conf_id' => $confId]);
    }
    
    /**
     * @param int $confId
     * @return NewsletterContents[]
     */
    public function getNewsletterContentsByContentConfId($confId)
    {
        return $this->getNewsletterContentsByContentConfIdQuery($confId)->all();
    }
    
    /**
     * @param string $className
     * @return \yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getNewsletterContentsByContentClassnameQuery($className, $orderContents = false)
    {
        /** @var NewsletterContents $newsletterContentsModel */
        $newsletterContentsModel = $this->notifyModule->createModel('NewsletterContents');
        $newsletterContentsTable = $newsletterContentsModel::tableName();
        
        /** @var NewsletterContentsConf $newsletterContentsConfModel */
        $newsletterContentsConfModel = $this->notifyModule->createModel('NewsletterContentsConf');
        $newsletterContentsConfTable = $newsletterContentsConfModel::tableName();
        
        $query = $this->getNewsletterContents()->innerJoinWith('newsletterContentsConf')->andWhere([$newsletterContentsConfTable . '.classname' => $className]);
        $query->innerJoinWith('newsletterContentsConf');
        $query->andWhere([$newsletterContentsConfTable . '.classname' => $className]);
        
        if ($orderContents) {
            $query->orderBy([$newsletterContentsTable . '.order' => SORT_ASC]);
        }
        
        return $query;
    }
    
    /**
     * @param string $className
     * @return NewsletterContents[]
     * @throws \yii\base\InvalidConfigException
     */
    public function getNewsletterContentsByContentClassname($className)
    {
        return $this->getNewsletterContentsByContentClassnameQuery($className)->all();
    }
    
    /**
     * @param NewsletterContentsConf $newsletterContentsConf
     * @return ActiveQuery|null
     * @throws \yii\base\InvalidConfigException
     */
    public function getContentsModelsByConfQuery($newsletterContentsConf)
    {
        if (!$this->id) {
            return null;
        }
        
        /** @var Record $contentConfModel */
        $contentConfModel = \Yii::createObject($newsletterContentsConf->classname);
        $contentConfModelTable = $contentConfModel::tableName();
        
        /** @var Newsletter $newsletterModel */
        $newsletterModel = $this->notifyModule->createModel('Newsletter');
        $newsletterTable = $newsletterModel::tableName();
        
        /** @var NewsletterContents $newsletterContentsModel */
        $newsletterContentsModel = $this->notifyModule->createModel('NewsletterContents');
        $newsletterContentsTable = $newsletterContentsModel::tableName();
        
        /** @var ActiveQuery $queryContent */
        $queryContent = $contentConfModel::find();
        $queryContent->innerJoin($newsletterContentsTable, $newsletterContentsTable . '.content_id = ' . $contentConfModelTable . '.id');
        $queryContent->innerJoin($newsletterTable, $newsletterTable . '.id = ' . $newsletterContentsTable . '.newsletter_id');
        $queryContent->andWhere([$newsletterContentsTable . '.deleted_at' => null]);
        $queryContent->andWhere([$newsletterTable . '.deleted_at' => null]);
        $queryContent->andWhere([$newsletterContentsTable . '.newsletter_contents_conf_id' => $newsletterContentsConf->id]);
        $queryContent->andWhere([$newsletterContentsTable . '.newsletter_id' => $this->id]);
        $queryContent->orderBy([$newsletterContentsTable . '.order' => SORT_ASC]);
        
        return $queryContent;
    }
    
    /**
     * @param NewsletterContentsConf $newsletterContentsConf
     * @return array|\yii\db\ActiveRecord[]
     * @throws \yii\base\InvalidConfigException
     */
    public function getContentsModels($newsletterContentsConf)
    {
        return $this->getContentsModelsByConfQuery($newsletterContentsConf)->all();
    }
    
    /**
     * This method returns the total contents count for this newsletter.
     * The method returns false in case of missing id.
     * @return int|false
     */
    public function getTotalContentsCount()
    {
        if (!$this->id) {
            return false;
        }
        return $this->getNewsletterContents()->count();
    }
    
    /**
     * This method returns the total contents count by type for this newsletter.
     * @param string $className
     * @return int
     * @throws \yii\base\InvalidConfigException
     */
    public function getTotalContentsCountByContentClassname($className)
    {
        if (!$this->id) {
            return false;
        }
        return $this->getNewsletterContentsByContentClassnameQuery($className)->count();
    }
    
    /**
     * This method returns true if the newsletter is in draft status.
     * @return bool
     */
    public function isDraftNewsletter()
    {
        return ($this->status == Newsletter::WORKFLOW_STATUS_DRAFT);
    }
    
    /**
     * This method returns true if the newsletter is in wait send status.
     * @return bool
     */
    public function isWaitSendNewsletter()
    {
        return ($this->status == Newsletter::WORKFLOW_STATUS_WAIT_SEND);
    }
    
    /**
     * This method returns true if the newsletter is in sending status.
     * @return bool
     */
    public function isSendingNewsletter()
    {
        return ($this->status == Newsletter::WORKFLOW_STATUS_SENDING);
    }
    
    /**
     * This method returns true if the newsletter is already sent.
     * @return bool
     */
    public function isSentNewsletter()
    {
        return ($this->status == Newsletter::WORKFLOW_STATUS_SENT);
    }
    
    /**
     * This method returns true if the newsletter is already sent.
     * @return bool
     */
    public function isWaitReSendNewsletter()
    {
        return ($this->status == Newsletter::WORKFLOW_STATUS_WAIT_RESEND);
    }
    
    /**
     * This method is useful to check if this newsletter can be sent by the script.
     * It checks the newsletter status directly on db to have the correct
     * actual status. If the status is allowed returns true.
     * @return bool
     */
    public function canBeSent()
    {
        if (!$this->id) {
            return false;
        }
        $query = new Query();
        $query->select(['status']);
        $query->from(self::tableName());
        $query->andWhere([
            'id' => $this->id,
            'deleted_at' => null
        ]);
        $status = $query->scalar();
        if (!is_string($status)) {
            return false;
        }
        return (
            ($status == Newsletter::WORKFLOW_STATUS_WAIT_SEND) ||
            ($status == Newsletter::WORKFLOW_STATUS_WAIT_RESEND)
        );
    }
    
    /**
     * This method set put the newsletter in draft status and update the send begin date.
     * @return bool
     */
    public function setDraftNewsletter()
    {
        $this->status = Newsletter::WORKFLOW_STATUS_DRAFT;
        return $this->save(false);
    }
    
    /**
     * This method set put the newsletter in sending status and update the send begin date.
     * @param bool $withoutDatetime
     * @return bool
     */
    public function setSendingNewsletter($withoutDatetime = false)
    {
        $this->status = Newsletter::WORKFLOW_STATUS_SENDING;
        if (!$withoutDatetime) {
            $this->send_date_begin = date('Y-m-d H:i:s');
        }
        return $this->save(false);
    }
    
    /**
     * This method set put the newsletter in wait send status and update the send begin date.
     * @return bool
     */
    public function setWaitSendNewsletter()
    {
        $this->status = Newsletter::WORKFLOW_STATUS_WAIT_SEND;
        return $this->save(false);
    }
    
    /**
     * This method set put the newsletter in sent status and update the send end date.
     * @param bool $withoutDatetime
     * @return bool
     */
    public function setSentNewsletter($withoutDatetime = false)
    {
        $this->status = Newsletter::WORKFLOW_STATUS_SENT;
        if (!$withoutDatetime) {
            $this->send_date_end = date('Y-m-d H:i:s');
        }
        return $this->save(false);
    }
    
    /**
     * This method set put the newsletter in wait resend status and update the send begin date.
     * @return bool
     */
    public function setWaitReSendNewsletter()
    {
        $this->status = Newsletter::WORKFLOW_STATUS_WAIT_RESEND;
        return $this->save(false);
    }
    
    /**
     * This method is for internal use and checks if the user can update or delete the newsletter.
     * If the userId param is passed, the method checks if that user can update or delete the newsletter.
     * @param int $userId
     * @param string $permissionName
     * @return bool
     */
    protected function userCanUpdateOrDeleteNewsletter($userId, $permissionName)
    {
        if (!$userId) {
            return \Yii::$app->user->can($permissionName, ['model' => $this]);
        } else {
            return \Yii::$app->authManager->checkAccess($userId, $permissionName, ['model' => $this]);
        }
    }
    
    /**
     * This method checks if the user can update the newsletter.
     * If the userId param is passed, the method checks if that user can update the newsletter.
     * @param int $userId
     * @return bool
     */
    public function userCanUpdateThisNewsletter($userId = 0)
    {
        return $this->userCanUpdateOrDeleteNewsletter($userId, 'NEWSLETTER_UPDATE');
    }
    
    /**
     * This method checks if the user can delete the newsletter.
     * If the userId param is passed, the method checks if that user can delete the newsletter.
     * @param int $userId
     * @return bool
     */
    public function userCanDeleteThisNewsletter($userId)
    {
        return $this->userCanUpdateOrDeleteNewsletter($userId, 'NEWSLETTER_DELETE');
    }
    
    /**
     * This method checks if all the contents are published in this newsletter.
     * Useful to check if the newsletter can be sent or to show this problem to the user.
     * If you set the param "confId" the method checks only for the specified configuration.
     * @param int $confId
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function checkAllContentsPublished($confId = 0)
    {
        if (!$this->id) {
            return false;
        }
        
        if ($confId > 0) {
            $newsletterContents = $this->getNewsletterContentsByContentConfId($confId);
        } else {
            $newsletterContents = $this->newsletterContents;
        }
        
        $statuses = NewsletterUtility::getAllConfModelsPublishedStatuses($this->notifyModule, false);
        $allPublished = true;
        
        foreach ($newsletterContents as $newsletterContent) {
            $contentModel = $newsletterContent->getContentModel();
            $statusField = $contentModel->newsletterContentStatusField();
            if ($contentModel->{$statusField} != $statuses[$newsletterContent->newsletter_contents_conf_id]) {
                $allPublished = false;
            }
        }
        
        return $allPublished;
    }
}
