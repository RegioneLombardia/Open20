<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\cwh
 * @category   CategoryName
 */

namespace open20\amos\cwh\controllers;

use open20\amos\core\user\User;
use open20\amos\admin\models\UserProfile;
use open20\amos\core\module\Module;
use open20\amos\cwh\AmosCwh;
use open20\amos\cwh\models\base\CwhNodi;
use open20\amos\cwh\models\CwhConfig;
use open20\amos\cwh\models\CwhRegolePubblicazione;
use open20\amos\cwh\query\CwhActiveQuery;
use open20\amos\cwh\utility\CwhUtil;
use Yii;
use yii\web\Controller;

class CwhAjaxController extends Controller
{
    /**
     * @return string
     */
    public function actionRecipientsCheck()
    {
        if(Yii::$app->request->isAjax) {

            $this->layout = false;
            $validators = isset($_POST['validators']) ?  $_POST['validators'] : [];
            $publicationRule = $_POST['publicationRule'];
            $tagValues = $_POST['tags'];
            $scopes = (isset($_POST['scopes']) ? $_POST['scopes'] : []);
            $className = $_POST['className'];
            $searchName = isset($_POST['searchName']) ? $_POST['searchName'] : '';
            $labelSuffix = $_POST['labelSuffix'];

            if(!empty($publicationRule)) {
                $publicationRuleLabel = CwhUtil::getPublicationRuleLabel($publicationRule);

                if ($publicationRule == CwhRegolePubblicazione::ALL_USERS_WITH_TAGS && empty($tagValues)) {
                    return AmosCwh::t('amoscwh', 'It is not possible to calculate recipients with rule')
                        . ' <strong>' . $publicationRuleLabel . '</strong> '
                        . AmosCwh::t('amoscwh', 'without specifying tags.');
                } elseif ($publicationRule == CwhRegolePubblicazione::ALL_USERS_IN_DOMAINS && empty($scopes)) {
                    return AmosCwh::t('amoscwh', 'It is not possible to calculate recipients with rule')
                        . ' <strong>' .$publicationRuleLabel . '</strong> '
                        . AmosCwh::t('amoscwh', 'without specifying publication scopes.');
                } elseif ($publicationRule == CwhRegolePubblicazione::ALL_USERS_IN_DOMAINS_WITH_TAGS && (empty($tagValues) ||  empty($scopes))){
                    return AmosCwh::t('amoscwh', 'It is not possible to calculate recipients with rule')
                        . ' <strong>' .$publicationRuleLabel . '</strong> '
                        . AmosCwh::t('amoscwh', 'without specifying both tags and publication scopes.');
                }

                $cwhActiveQuery = new CwhActiveQuery($className);
                $queryUsers = $cwhActiveQuery->getRecipients($publicationRule, $tagValues, $scopes);
                $query = UserProfile::find()->andWhere([
                    'in',
                    'user_id',
                    $queryUsers->select('user.id')->asArray()->column()
                ]);

                if (!empty($searchName)) {
                    $query->andWhere(['or',
                        ['like', 'cognome', $searchName],
                        ['like', 'nome', $searchName],
                        ['like', "CONCAT( nome , ' ', cognome )", $searchName],
                        ['like', "CONCAT( cognome , ' ', nome )", $searchName],
                    ]);
                }

                return $this->render("recipients-check", [
                    'validators' => CwhUtil::getDomainNames($validators),
                    'publicationRule' => $publicationRuleLabel,
                    'tagValues' => CwhUtil::getTagNames($tagValues),
                    'scopes' => CwhUtil::getDomainNames($scopes),
                    'searchName' => $searchName,
                    'query' => $query,
                    'labelSuffix' => $labelSuffix
                ]);
            }
        }
        return null;
    }
    
    
    /**
     * @return string
     */
    public function actionRecipientsCheckNew()
    {
        if(Yii::$app->request->isAjax) {

            $this->layout = false;
            $validators = isset($_POST['validators']) ?  $_POST['validators'] : [];
            $publicationRule = $_POST['publicationRule'];
            $tagValues = $_POST['tags'];
            $scopes = (isset($_POST['scopes']) ? $_POST['scopes'] : []);
            $className = $_POST['className'];
            $searchName = isset($_POST['searchName']) ? $_POST['searchName'] : '';
            $labelSuffix = $_POST['labelSuffix'];

            if (!empty($publicationRule)) {
                if (!empty($tagValues) && empty($scopes)) {
                    $publicationRule = CwhRegolePubblicazione::ALL_USERS_WITH_TAGS;
                } else if (empty($tagValues) && !empty($scopes)) {
                    $publicationRule = CwhRegolePubblicazione::ALL_USERS_IN_DOMAINS;
                } else if (!empty($tagValues) && !empty($scopes)) {
                    $publicationRule = CwhRegolePubblicazione::ALL_USERS_IN_DOMAINS_WITH_TAGS;
                }
                
                $publicationRuleLabel = CwhUtil::getPublicationRuleLabel($publicationRule);

                if ($publicationRule == CwhRegolePubblicazione::ALL_USERS_WITH_TAGS && empty($tagValues)) {
                    return AmosCwh::t('amoscwh', 'It is not possible to calculate recipients with rule')
                        . ' <strong>' . $publicationRuleLabel . '</strong> '
                        . AmosCwh::t('amoscwh', 'without specifying tags.');
                    
                } elseif ($publicationRule == CwhRegolePubblicazione::ALL_USERS_IN_DOMAINS && empty($scopes)) {
                    return AmosCwh::t('amoscwh', 'It is not possible to calculate recipients with rule')
                        . ' <strong>' .$publicationRuleLabel . '</strong> '
                        . AmosCwh::t('amoscwh', 'without specifying publication scopes.');
                    
                } elseif ($publicationRule == CwhRegolePubblicazione::ALL_USERS_IN_DOMAINS_WITH_TAGS && (empty($tagValues) ||  empty($scopes))){
                    return AmosCwh::t('amoscwh', 'It is not possible to calculate recipients with rule')
                        . ' <strong>' .$publicationRuleLabel . '</strong> '
                        . AmosCwh::t('amoscwh', 'without specifying both tags and publication scopes.');
                }

                $cwhActiveQuery = new CwhActiveQuery($className);
                $queryUsers = $cwhActiveQuery->getRecipients($publicationRule, $tagValues, $scopes);
                $query = UserProfile::find()
                    ->innerJoinWith('user')
                    ->andWhere([
                        User::tableName() . '.status' => User::STATUS_ACTIVE,
                        UserProfile::tableName() . '.deleted_at' => null,
                    ])
                    ->andWhere([
                        'not like', User::tableName() . '.username', ['#deleted_']
                    ])
                    ->andWhere([
                        'in', 'user_id', $queryUsers->select('user.id')->asArray()->column()
                    ]);

                if (!empty($searchName)) {
                    $query->andWhere(['or',
                        ['like', 'cognome', $searchName],
                        ['like', 'nome', $searchName],
                        ['like', "CONCAT( nome , ' ', cognome )", $searchName],
                        ['like', "CONCAT( cognome , ' ', nome )", $searchName],
                    ]);
                }

                return $this->render("recipients-check", [
                    'validators' => CwhUtil::getDomainNames($validators),
                    'publicationRule' => $publicationRuleLabel,
                    'tagValues' => CwhUtil::getTagNames($tagValues),
                    'scopes' => CwhUtil::getDomainNames($scopes),
                    'searchName' => $searchName,
                    'query' => $query,
                    'labelSuffix' => $labelSuffix
                ]);
            }
        }
        
        return null;
    }

    /**
     * @param $cwhNodIid
     * @return mixed
     */
    public function actionGetNetwork($cwhNodiId) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $cwhNode = CwhNodi::findOne($cwhNodiId);
        $targetString = '';
        if(!is_null($cwhNode)){
            $network  = $cwhNode->network;
                if (array_key_exists('open20\amos\community\models\CommunityContextInterface', class_implements($network))) {
                    $targetString .= Module::t('amoscore', 'community') . ' ';
                }
                if (array_key_exists('open20\amos\core\interfaces\OrganizationsModelInterface', class_implements($network))) {
                    $targetString .= Module::t('amoscore', 'organizzazione') . ' ';
                }
                /** if is USER */
                else return '';
            return Module::t('amoscore', 'dalla'). ' ' .$targetString . ' ' .$cwhNode->network->name;
        }

        return '';
    }
}