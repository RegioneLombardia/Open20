<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\utility
 * @category   CategoryName
 */

namespace open20\amos\news\utility;

use open20\amos\news\models\News;
use open20\amos\news\models\NewsCategorie;
use open20\amos\news\models\NewsCategoryRolesMm;
use open20\amos\core\utilities\Email;
use open20\amos\admin\models\UserProfile;

use Yii;
use yii\base\BaseObject;
use yii\db\ActiveQuery;

class NewsUtility extends BaseObject
{

    /**
     * @return ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public static function getNewsCategories()
    {
        /** @var ActiveQuery $query */
        $query = NewsCategorie::find();
        if (\Yii::$app->getModule('news')->filterCategoriesByRole) {
            //check enabled role for category active - user can publish under
            //a category if there's at least one match betwwn category and user roles
            $query->joinWith('newsCategoryRolesMms')->innerJoin('auth_assignment',
                'item_name='.NewsCategoryRolesMm::tableName().'.role and user_id ='.\Yii::$app->user->id);
        }
        if (\Yii::$app->getModule('news')->enableCategoriesForCommunity) {
            $moduleCwh       = \Yii::$app->getModule('cwh');
            $moduleCommunity = \Yii::$app->getModule('community');

            if ($moduleCwh && $moduleCommunity) {
                $scope = $moduleCwh->getCwhScope();
                //INSIDE A COMMUNITY
                if (!empty($scope) && isset($scope['community'])) {
                    $isCommunityManager = NewsUtility::isCommunityManager($scope['community']);
                    //SHOWALLCATEGORIES = TRUE
                    if (\Yii::$app->getModule('news')->showAllCategoriesForCommunity) {
                        $query->joinWith('newsCategoryCommunityMms')->andWhere([
                            'OR',
                            ['community_id' => null],
                            ['community_id' => $scope['community']]
                        ]);
                        // filter for  particiapants
                        if (!$isCommunityManager) {
                            $query->andWhere(
                                ['OR',
                                    ['community_id' => null],
                                    ['visible_to_participant' => true]
                            ]);
                        }
                        //SHOWALLCATEGORIES = FALSE - show only categories that belongs to the community
                    } else {
                        $query2 = clone $query;
                        $count  = $query2->joinWith('newsCategoryCommunityMms')
                                ->andWhere(['community_id' => $scope['community']])->count();

                        // if you have at least a category for this community show only them
                        if ($count > 0) {
                            $query->joinWith('newsCategoryCommunityMms')
                                ->andWhere(['community_id' => $scope['community']]);
                            // filter for  participants
                            if (!$isCommunityManager) {
                                $query->andWhere(['visible_to_participant' => true]);
                            }
                        } else {
                            // If you don't have categories for this specific community, show all the categories the the aren't assigned to some community
                            $query->joinWith('newsCategoryCommunityMms')
                                ->andWhere(['IS', 'community_id', NULL]);
                        }
                    }
                } else {
                    // IF YOU ARE ON DASHBOARD
                    $query->joinWith('newsCategoryCommunityMms')->andWhere(['IS', 'community_id', null]);
                }
            }
            //check enabled role for category active - user can publish under a category if there's at least one match betwwn category and user roles
        }
        return $query;
    }

    /**
     * @param $community_id
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public static function isCommunityManager($community_id)
    {
        $count = \open20\amos\community\models\CommunityUserMm::find()
            ->andWhere(['community_id' => $community_id])
            ->andWhere(['user_id' => \Yii::$app->user->id])
            ->andWhere(['role' => \open20\amos\community\models\Community::ROLE_COMMUNITY_MANAGER])
            ->count();

        return ($count > 0);
    }

    /**
     *
     * @return ActiveQuery
     */
    public static function getAllNewsCategories()
    {
        /** @var ActiveQuery $query */
        $query = NewsCategorie::find();
        return $query;
    }

    /**
     *
     * @param type $whoCanPublishIds
     * @param type $controller
     * @param type $news
     * @return boolean
     */
    public function sendEmailsForPublishOnHomePageRequest($whoCanPublishIds, $model)
    {
        // check if request to publish on hp is set on
        if (is_array($whoCanPublishIds)) {
            $emailBasePath = '@vendor/open20/amos-news/src/views/email/';

            $userProfiles = UserProfile::find()
                ->andWhere(['user_id' => $whoCanPublishIds])
				->andWhere(['attivo' => UserProfile::STATUS_ACTIVE])
                ->all();
            if (!empty($userProfiles)) {
                $controller = \Yii::$app->controller;

                $community_name = null;
                $validatori = $model->validatori;
                if (!empty($validatori)) {
                    list($validatore, $community_id) = explode("-", array_shift($validatori));

                    if (!empty($community_id)) {
                        $community = \open20\amos\community\models\Community::find()
                            ->andWhere(['id' => $community_id])
                            ->one();
                        if (!empty($community)) {
                            $community_name = $community->name;
                        }
                    }
                }

                $requestedByUser = \open20\amos\admin\models\UserProfile::find()
                    ->andWhere(['user_id' => $model->updated_by])
                    ->one();

                $user_request = !empty($requestedByUser) ? $requestedByUser->getNomeCognome() : 'n./a.';
                $news_url = Yii::$app->urlManager->createAbsoluteUrl([
                    '/news/news/update',
                    'id' => $model->id,
                ]);
                $news_title = $model->titolo;
                $news_descr = $model->getDescription();
                $news_categoria = NewsCategorie::find()
                    ->andWhere(['id' => $model->news_categorie_id])
                    ->one();

                $tags = \open20\amos\tag\utility\TagUtility::findTagsByModel(
                    \open20\amos\news\models\News::class,
                    $model->id
                );

                if (!empty($tags)) {
                    $tmp = [];
                    foreach($tags as $tag) {
                        $tmp[] = $tag->nome;
                    }
                    $tags_list = implode(", ", $tmp);
                }

                // Create SUBJECT
                $subject = $controller->renderMailPartial($emailBasePath . '/publish_on_homepage_request_subject');
                $from = Yii::$app->params['supportEmail'];
                foreach($userProfiles as $user) {
                    $to = $user->user->email;

                    // Create TEXT
                    $text = $controller->renderMailPartial($emailBasePath . '/publish_on_homepage_request_text', [
                        'whocan_name' => $user->getNomeCognome(),
                        'community' => $community_name,
                        'user_request' => $user_request,
                        'news_url' => $news_url,
                        'news_title' => $news_title,
                        'news_categoria' => !empty($news_categoria) ? $news_categoria->titolo : 'n./a.',
                        'news_descr' => $news_descr,
                        'tags' => $tags_list,
                    ]);

                    // SEND EMAIL
                    $ok = Email::sendMail(
                        $from,
                        $to,
                        $subject,
                        $text
                    );
                }
            }
        }

        return true;
    }
}
