<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\rules
 * @category   CategoryName
 */

namespace open20\amos\community\rules;

use open20\amos\community\models\Community;
use open20\amos\core\rules\DefaultOwnContentRule;

/**
 * Class CreateSubcommunitiesRule
 * @package open20\amos\community\rules
 */
class CreateSubcommunitiesRule extends DefaultOwnContentRule
{
    /**
     * @inheritdoc
     */
    public $name = 'createSubcommunities';

    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        $cwhModule = \Yii::$app->getModule('cwh');
        if (isset($params['model'])) {
            /** @var Community $model */
            $model = $params['model'];
            if (!isset($model->id)) {
                $post = \Yii::$app->getRequest()->post();
                $get = \Yii::$app->getRequest()->get();
                if (isset($get['id'])) {
                    $model = $this->instanceModel($model, $get['id']);
                } elseif (isset($post['id'])) {
                    $model = $this->instanceModel($model, $post['id']);
                }
            }
            if (isset($cwhModule) && $model instanceof Community) {
                if (!is_null($model->id)) {
                    return ($model->isCommunityManager($user));
                } else {
                    $parentId = \Yii::$app->request->getQueryParam('parentId');
                    if($parentId){
                       $parent = Community::findOne($parentId);
                       if($parent){
                           return $parent->isCommunityManager($user);
                       }
                   }
                }
            }
        }
        return false;
    }
}
