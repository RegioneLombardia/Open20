<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\controllers
 * @category   CategoryName
 */

namespace open20\amos\admin\controllers;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\assets\ModuleAdminAsset;
use open20\amos\admin\exceptions\AdminException;
use open20\amos\admin\models\CambiaPasswordForm;
use open20\amos\admin\models\search\UserProfileAreaSearch;
use open20\amos\admin\models\search\UserProfileRoleSearch;
use open20\amos\admin\models\UserProfile;
use open20\amos\core\forms\editors\m2mWidget\controllers\M2MWidgetControllerTrait;
use open20\amos\core\forms\editors\m2mWidget\M2MEventsEnum;
use open20\amos\core\helpers\Html;
use open20\amos\core\utilities\ArrayUtility;
use raoul2000\workflow\base\WorkflowException;
use Yii;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use open20\amos\admin\interfaces\OrganizationsModuleInterface;
use yii\web\Controller;

/**
 * Class UserProfileController
 * @package open20\amos\admin\controllers
 */
class UserProfileAjaxController extends Controller
{

    public function init()
    {
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $result = ArrayHelper::merge(parent::behaviors(),
                [
                'access' => [
                    'class' => AccessControl::className(),
                    'ruleConfig' => [
                        'class' => AccessRule::className(),
                    ],
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => [
                                'ajax-user-list',
                                'ajax-contact-list',
                                'ajax-share-with'
                            ],
                            'roles' => ['@']
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['post', 'get']
                    ]
                ]
        ]);

        return $result;
    }


    /**
     * @param null $q
     * @param null $id
     * @return array
     */
    public function actionAjaxUserList($q = null, $id = null) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query();
            $query->select(new Expression("user_id as id, CONCAT(nome, ' ', cognome) AS text"))
                ->from('user_profile')
                ->andWhere(['or',
                    ['like', 'cognome', $q],
                    ['like', 'nome', $q],
                    ['like', "CONCAT( nome , ' ', cognome )", $q],
                    ['like', "CONCAT( cognome , ' ', nome )", $q],
                ])
                ->andWhere(['is', 'deleted_at', null])
                ->limit(20);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }

        return $out;
    }


    /**
     * @return string  used by the modal in social share (share with your network)
     */
    public function actionAjaxContactList($classname, $content_id, $searchName = null){
        $searchName = isset($_GET['searchName']) ? $_GET['searchName'] : '';
        $classname = urldecode($classname);
        $this->layout= '@vendor/open20/amos-core/views/layouts/main' ;

        $modelContent = $classname::findOne($content_id);
        $availableUserProfileIds = $modelContent->getRecipientsQuery()->select('user_profile.id')->asArray()->column();
        $loggedUser = UserProfile::findOne(['user_id' => \Yii::$app->user->id]);
        if($loggedUser){
            $query  = $loggedUser->getUserNetworkQuery($loggedUser->id);
            if (!empty($searchName)) {
                $query->andWhere(['or',
                    ['like', 'cognome', $searchName],
                    ['like', 'nome', $searchName],
                    ['like', "CONCAT( nome , ' ', cognome )", $searchName],
                    ['like', "CONCAT( cognome , ' ', nome )", $searchName],
                ]);
            }

            $dataProvider = new ActiveDataProvider([
                'query' => $query
            ]);
            return $this->renderAjax('_modal_contacts', ['dataProvider' => $dataProvider, 'availableUserProfileIds' => $availableUserProfileIds]);
        }
    }

    /**
     * @return string  used by the modal in social share (share with your network)
     */
    public function actionAjaxShareWith(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = \Yii::$app->request->post();
        $loggedUserId = \Yii::$app->user->id;
        $moduleMessaggi= \Yii::$app->getModule('chat');
        if($moduleMessaggi){
            if(!empty($request)){
                $textBase = ' questo contenuto ti potrebbe interessare: ' . Html::a($request['url'], $request['url'])  . '<p>' .$request['text'] .'</p>';
                $request['selected_users'] = array_unique($request['selected_users']);
                foreach($request['selected_users'] as $user_profile_id){
                    $profile = UserProfile::findOne($user_profile_id);
                    if($profile) {
                        $text = '<strong>'. $profile->nomeCognome. '</strong>' . $textBase;
                        $message = new \open20\amos\chat\models\Message();
                        $message->text = $text;
                        $message->sender_id = $loggedUserId;
                        $message->receiver_id = $profile->user_id;
                        $message->save();
                    }
                }
                return 'true';
            }
        }
        return 'false';
    }

    /**
     * @param null $layout
     * @return bool
     */
    public function setUpLayout($layout = null)
    {
        if ($layout === false) {
            $this->layout = false;
            return true;
        }
        $this->layout = (!empty($layout)) ? $layout : $this->layout;
        $module = \Yii::$app->getModule('layout');
        if (empty($module)) {
            if (strpos($this->layout, '@') === false) {
                $this->layout = '@vendor/open20/amos-core/views/layouts/' . (!empty($layout) ? $layout : $this->layout);
            }
            return true;
        }
        return true;
    }



}