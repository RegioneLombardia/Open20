<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comments\models\base
 * @category   CategoryName
 */

namespace open20\amos\comments\models\base;

use open20\amos\comments\AmosComments;
use open20\amos\core\validators\StringHtmlValidator;
use open20\amos\notificationmanager\record\NotifyRecord;
use yii\helpers\ArrayHelper;

/**
 * Class Comment
 * This is the base-model class for table "comment".
 *
 * @property integer $id
 * @property string $comment_text
 * @property string $context
 * @property integer $context_id
 * @property integer $public
 * @property string $status
 * @property integer $in_evidence
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\comments\models\CommentReply[] $commentReplies
 * @property \open20\amos\comments\models\CommentModeration $commentModeration
 *
 * @package open20\amos\comments\models\base
 */
class Comment extends NotifyRecord
{
    private $enableModerator = false;
    private $moderator       = false;

    public function init()
    {
        parent::init();
        $module = \Yii::$app->getModule(AmosComments::getModuleName());
        if($module) {
            $this->setEnableModerator($module->enableModerator);
        }
        $this->isModerator();

    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {        
        return [
            [['comment_text', 'context', 'context_id'], 'required'],
            [['comment_text'], StringHtmlValidator::className()],
            [['comment_text', 'status'], 'string'],
            [['context'], 'string', 'max' => 255],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['in_evidence','context_id', 'created_by', 'updated_by', 'deleted_by', 'public'], 'integer'],
            ['public', 'default', 'value' => ($this->getEnableModerator() ? ($this->getModerator() == true ? 1 : 0) : 1)],
        ];
    }

    /**
     *
     * @return boolean
     */
    public function isModerator()
    {
        try {
            if ($this->getEnableModerator()) {
                $moduleCwh       = \Yii::$app->getModule('cwh');
                $moduleCommunity = \Yii::$app->getModule('community');
                if (!empty($moduleCwh) && !empty($moduleCommunity)) {
                    $scope         = $moduleCwh->getCwhScope();
                    $communityUtil = 'open20\amos\community\utilities\CommunityUtil';
                    if (!empty($scope) && isset($scope['community'])) {
                        if (class_exists($communityUtil)) {
                            $obj       = $moduleCommunity::instance()->createModel('Community');
                            $community = $obj::findOne($scope['community']);
                            if (!empty($community)) {
                                $this->setModerator($communityUtil::isManagerUser($community, 'id'));
                            }
                        }
                    } else {
                        return true;
                    }
                }
            }
            return false;
        } catch (Exception $ex) {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(),
                [
                'id' => AmosComments::t('amoscomments', 'ID'),
                'comment_text' => AmosComments::t('amoscomments', 'Comment Text'),
                'context' => AmosComments::t('amoscomments', 'Context'),
                'context_id' => AmosComments::t('amoscomments', 'Context ID'),
                'created_at' => AmosComments::t('amoscomments', 'Created At'),
                'updated_at' => AmosComments::t('amoscomments', 'Updated At'),
                'deleted_at' => AmosComments::t('amoscomments', 'Deleted At'),
                'created_by' => AmosComments::t('amoscomments', 'Created By'),
                'updated_by' => AmosComments::t('amoscomments', 'Updated By'),
                'deleted_by' => AmosComments::t('amoscomments', 'Deleted By')
        ]);
    }

    /**
     * @return string
     */
    public function getToValidateStatus(): string
    {
        return \open20\amos\comments\models\Comment::STATUS_TOVALIDATE;
    }

    /**
     * @param $status
     * @return int
     */
    public function getStatusLastUpdateUser($status): int
    {
        return $this->updated_by;
    }

    /**
     * @param $status
     * @return string
     */
    public function getStatusLastUpdateTime($status): string
    {
        return $this->updated_at;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCommentReplies()
    {
        return $this->hasMany(\open20\amos\comments\models\CommentReply::className(), ['comment_id' => 'id']);
    }

    public function getUserProfile()
    {
        return $this->hasOne(\open20\amos\admin\models\UserProfile::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCommentModeration()
    {
        return $this->hasOne(\open20\amos\comments\models\CommentModeration::className(), [
            'content_id' => 'context_id',
            'classname' => 'context'
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContextModel(): \yii\db\ActiveQuery
    {
        return $this->hasOne($this->context::className(), ['id' => 'context_id']);
    }

    public function setModerator($moderator)
    {
        $this->moderator = $moderator;
    }

    public function getModerator()
    {
        return $this->moderator;
    }

    public function setEnableModerator($enableModerator)
    {
        $this->moderator = $enableModerator;
    }

    public function getEnableModerator()
    {
        return $this->moderator;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->comment_text;
    }
}