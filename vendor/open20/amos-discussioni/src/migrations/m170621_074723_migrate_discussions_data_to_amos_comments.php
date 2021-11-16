<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni\migrations
 * @category   CategoryName
 */

use open20\amos\comments\models\Comment;
use open20\amos\comments\models\CommentReply;
use open20\amos\core\migration\libs\common\MigrationCommon;
use open20\amos\discussioni\AmosDiscussioni;
use open20\amos\discussioni\models\DiscussioniTopic;
use yii\db\Migration;
use yii\db\Query;

/**
 * Class m170621_074723_migrate_discussions_data_to_amos_comments
 */
class m170621_074723_migrate_discussions_data_to_amos_comments extends Migration
{
    private $createdUpdatedDeletedFields = [
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
    
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        return $this->migrateDiscussioniRisposte();
    }
    
    /**
     * @return bool
     */
    private function migrateDiscussioniRisposte()
    {
        $ok = true;
        $query = new Query();
        $query->from('discussioni_risposte');
        $discussionsReplies = $query->all();
        if (!count($discussionsReplies)) {
            MigrationCommon::printConsoleMessage(AmosDiscussioni::t('amosdiscussioni', 'Nothing to migrate.'));
            return $ok;
        }
        foreach ($discussionsReplies as $discussionReply) {
            $comment = new Comment();
            $comment->detachBehaviors();
            $comment->comment_text = $discussionReply['testo'];
            $comment->context = DiscussioniTopic::className();
            $comment->context_id = $discussionReply['discussioni_topic_id'];
            $comment->comment_text = $discussionReply['testo'];
            foreach ($this->createdUpdatedDeletedFields as $fieldName) {
                $comment->{$fieldName} = $discussionReply[$fieldName];
            }
            $ok = $comment->save(false);
            if (!$ok) {
                MigrationCommon::printCheckStructureError($discussionReply, AmosDiscussioni::t('amosdiscussioni', 'Error while migrate discussion reply'));
                break;
            }
            if ($ok) {
                $ok = $this->migrateDiscussioniCommenti($discussionReply['id'], $comment->id);
            }
        }
        return $ok;
    }
    
    /**
     * Migrate all comments to a single reply.
     * @param int $discussionReplyId
     * @param int $commentId
     */
    private function migrateDiscussioniCommenti($discussionReplyId, $commentId)
    {
        $ok = true;
        $query = new Query();
        $query->from('discussioni_commenti')->andWhere(['discussioni_risposte_id' => $discussionReplyId]);
        $discussionComments = $query->all();
        foreach ($discussionComments as $discussionComment) {
            $commentReply = new CommentReply();
            $commentReply->detachBehaviors();
            $commentReply->comment_reply_text = $discussionComment['testo'];
            $commentReply->comment_id = $commentId;
            foreach ($this->createdUpdatedDeletedFields as $fieldName) {
                $commentReply->{$fieldName} = $discussionComment[$fieldName];
            }
            $ok = $commentReply->save(false);
            if (!$ok) {
                MigrationCommon::printCheckStructureError($discussionComment, AmosDiscussioni::t('amosdiscussioni', 'Error while migrate discussion comment'));
                break;
            }
        }
        return $ok;
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        return $this->removeDiscussioniRisposteFromComment();
    }
    
    private function removeDiscussioniRisposteFromComment()
    {
        $ok = true;
        $query = new Query();
        $query->select(['discussioni_topic_id']);
        $query->from('discussioni_risposte');
        $query->groupBy(['discussioni_topic_id']);
        $discussionsTopicIds = $query->column();
        $query = new Query();
        $query->select(['id']);
        $query->from(Comment::tableName());
        $query->andWhere(['context' => DiscussioniTopic::className(), 'context_id' => $discussionsTopicIds]);
        $commentIds = $query->column();
        foreach ($commentIds as $commentId) {
            /** @var int $commentId */
            $ok = $this->removeDiscussioniCommentiFromCommentReply($commentId);
            if (!$ok) {
                break;
            }
            try {
                $this->delete(Comment::tableName(), ['id' => $commentId]);
            } catch (\Exception $exception) {
                $ok = false;
                MigrationCommon::printConsoleMessage(AmosDiscussioni::t('amosdiscussioni', 'Error while deleting discussion comment. ID: ' . $commentId));
                break;
            }
        }
        return $ok;
    }
    
    /**
     * @param int $commentId
     * @return bool
     */
    private function removeDiscussioniCommentiFromCommentReply($commentId)
    {
        $ok = true;
        $query = new Query();
        $query->select(['id']);
        $query->from(CommentReply::tableName());
        $query->andWhere(['comment_id' => $commentId]);
        $commentRepliesIds = $query->column();
        foreach ($commentRepliesIds as $commentReplyId) {
            try {
                $this->delete(CommentReply::tableName(), ['id' => $commentReplyId]);
            } catch (\Exception $exception) {
                $ok = false;
                MigrationCommon::printConsoleMessage(AmosDiscussioni::t('amosdiscussioni', 'Error while deleting discussion comment reply. ID: ' . $commentReplyId));
                break;
            }
        }
        return $ok;
    }
}
