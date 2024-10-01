<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\rules
 * @category   CategoryName
 */

namespace open20\amos\community\rules;

use open20\amos\community\models\Bookmarks;
use open20\amos\community\models\Community;
use open20\amos\core\record\Record;
use open20\amos\core\rules\DefaultOwnContentRule;
use open20\amos\community\utilities\CommunityUtil;

/**
 * Class UpdateBookmarksRule
 * @package open20\amos\community\rules
 */
class UpdateBookmarksRule extends DefaultOwnContentRule
{
    /**
     * @inheritdoc
     */
    public $name = 'updateBookmarks';

    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        if (isset($params['model'])) {
            /** @var Record $model */
            $model = $params['model'];
            if (!$model->id) {
                $post = \Yii::$app->getRequest()->post();
                $get  = \Yii::$app->getRequest()->get();
                if (isset($get['id'])) {
                    $model = $this->instanceModel($model, $get['id']);
                } elseif (isset($post['id'])) {
                    $model = $this->instanceModel($model, $post['id']);
                }
            }
            $community = Community::findOne($model->community_id);
            return (((!$community->force_workflow || $model->status !== Bookmarks::BOOKMARKS_STATUS_PUBLISHED) && $model->creatore_id == $user) || CommunityUtil::loggedUserIsCommunityManager($model->community_id));
        } else {
            return false;
        }
    }
}
