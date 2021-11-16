<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comments\models
 * @category   CategoryName
 */

namespace open20\amos\comments\models;

use open20\amos\attachments\behaviors\FileBehavior;
use open20\amos\attachments\models\File;
use open20\amos\comments\AmosComments;
use open20\amos\core\models\ModelsClassname;
use yii\helpers\ArrayHelper;
use open20\amos\notificationmanager\behaviors\NotifyBehavior;
use yii\web\Application;

/**
 * Class Comment
 * This is the model class for table "comment".
 *
 * @method \yii\db\ActiveQuery hasOneFile($attribute = 'file', $sort = 'id')
 * @method \yii\db\ActiveQuery hasMultipleFiles($attribute = 'file', $sort = 'id')
 *
 * @package open20\amos\comments\models
 */
class Comment extends \open20\amos\comments\models\base\Comment
{
    const VIEW_TYPE_POSITION = 'comment';

    public $publicatedByLabel;

    /**
     * @var File[] $commentAttachments
     */
    private $commentAttachments;
    
    /**
     * @var File[] $commentAttachmentsForItemView
     */
    public $commentAttachmentsForItemView;
    

    /**
     */
    public function init()
    {
        parent::init();
        $this->publicatedByLabel = AmosComments::t('amoscomments', 'Pubblicato da');


    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'fileBehavior' => [
                'class' => FileBehavior::className()
            ],
            'NotifyBehavior' => [
                'class' => NotifyBehavior::className(),
                'conditions' => [],
            ],
        ]);
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        $maxCommentAttachments = 0;

        /** @var AmosComments $commentsModule */
        $commentsModule = \Yii::$app->getModule(AmosComments::getModuleName());
        if(isset($commentsModule)) {
            $maxCommentAttachments = $commentsModule->maxCommentAttachments;
        }
        return ArrayHelper::merge(parent::rules(), [
            [['commentAttachments'], 'file', 'maxFiles' => $maxCommentAttachments],
        ]);
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'commentAttachments' => AmosComments::t('amoscomments', '#COMMENT_ATTACHMENTS'),
        ]);
    }
    
    /**
     * Getter for $this->attachments;
     *
     */
    public function getCommentAttachments()
    {
        if(empty($this->commentAttachments)){
            $this->commentAttachments = $this->hasMultipleFiles('commentAttachments')->one();
        }
        return $this->commentAttachments;
    }

    /**
     * @param $attachments
     */
    public function setCommentAttachments($attachments){
        $this->commentAttachments = $attachments;
    }

    /**
     * @return array|File[]|\yii\db\ActiveRecord[]
     */
    public function getCommentAttachmentsForItemView(){
        if(empty($this->commentAttachmentsForItemView)){
            $this->commentAttachmentsForItemView = $this->hasMultipleFiles('commentAttachments')->all();
        }
        return $this->commentAttachmentsForItemView;
    }
    
    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        parent::afterFind();

    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
        
        $module = \Yii::$app->getModule('comments');
        if (!empty($module->enableNotifyCommentForDiscussions) && $module->enableNotifyCommentForDiscussions == true) {
            if ($insert) {
                $this->saveNotificationForContextModel();
            }
        }
    }

    /**
     * notify the context model if is created a comment (only on contents inside a scope)
     * @return bool
     */
    public function saveNotificationForContextModel(){
        $moduleNotify = \Yii::$app->getModule('notify');
        if(!empty($moduleNotify)) {
            /** @var \open20\amos\core\record\Record $contextModelClassName */
            $contextModelClassName = $this->context;
            /** @var \open20\amos\core\record\Record $contextModel */
            $contextModel = $contextModelClassName::findOne($this->context_id);
            if($contextModel && get_class($contextModel) == 'open20\amos\discussioni\models\DiscussioniTopic') {
                $notifyComment = new \open20\amos\notificationmanager\models\Notification();
                $notifyComment->content_id = $this->id;
                $notifyComment->user_id = $this->created_by;
                $notifyComment->channels = \open20\amos\notificationmanager\models\NotificationChannels::CHANNEL_MAIL;
                $notifyComment->class_name = get_class($this);
                $notifyComment->save(false);

                $notify = \open20\amos\notificationmanager\models\Notification::find()
                    ->leftJoin('notificationread', 'notificationread.notification_id = notification.id')
                    ->andWhere([
                        'content_id' => $contextModel->id,
                        'channels' => \open20\amos\notificationmanager\models\NotificationChannels::CHANNEL_MAIL,
                        'class_name' => $contextModelClassName
                    ])
                    ->andWhere(['notificationread.notification_id' => null])
                    ->andWhere(['IS NOT', 'notification.models_classname_id', null ])
                    ->one();


                if (empty($notify)) {
                    $notify = new \open20\amos\notificationmanager\models\Notification();
                    $notify->user_id = $contextModel->created_by;

                    //create notification for a network
                    $notify->content_id = $contextModel->id;
                    $notify->channels = \open20\amos\notificationmanager\models\NotificationChannels::CHANNEL_MAIL;
                    $notify->class_name = $contextModelClassName;
                }

                // ---  set the network for the contenxtmodel notification and for the comment notification
                $validatori = $contextModel->validatori;
                if ($validatori) {
                    if (is_array($validatori)) {
                        $validatori = reset($validatori);
                    }
                    if (strpos($validatori, 'user') === false) {
                        $exploded = explode('-', $validatori);
                        if (count($exploded) == 2) {
                            $modelsClassname = ModelsClassname::find()->andWhere(['module' => $exploded[0]])->one();
                            if ($modelsClassname) {
                                $notify->models_classname_id = $modelsClassname->id;
                                $notify->record_id = $exploded[1];

                                $notifyComment->models_classname_id = $modelsClassname->id;
                                $notifyComment->record_id = $exploded[1];
                            }
                        }
                    }
                }

                if (!empty($notify) && !empty($notifyComment)) {
                    $notifyComment->save(false);
                    return $notify->save(false);
                }
            }
        }
        return false;
    }
}
