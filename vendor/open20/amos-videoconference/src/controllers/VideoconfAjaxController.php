<?php
/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 19/05/2022
 * Time: 17:28
 */

namespace open20\amos\videoconference\controllers;


use open20\amos\admin\models\UserProfile;
use open20\amos\core\controllers\BackendController;
use open20\amos\videoconference\models\base\Videoconf;
use open20\amos\videoconference\models\search\UserMeetingSearch;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

class VideoconfAjaxController extends BackendController
{

    public $model;

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
                            'search-users-webmeeting',
                            'users-invited-webmeeting',
                            'more-info'
                        ],
                        'roles' => ['@']
                    ]
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
     * @param $name
     * @return string
     */
    public function actionSearchUsersWebmeeting()
    {
        $modelSearch = new UserMeetingSearch();
        $modelSearch->name = \Yii::$app->request->get('name');
        $modelSearch->char = \Yii::$app->request->get('char');
        $dataProvider = $modelSearch->search(\Yii::$app->request->get());
        $participants_user_ids = [];
        $ids_deleted = [];
        $ids_selected = [];

        // prendo gli id degli utenti selezionati
        $id = \Yii::$app->request->get('id');
        $user_profile_ids_deleted = \Yii::$app->request->get('deleted_users');
        if (!empty($user_profile_ids_deleted)) {
            $ids_deleted = $user_profile_ids_deleted;
        }

        $user_profile_ids_selected = \Yii::$app->request->get('selected_users');
        if (!empty($user_profile_ids_selected)) {
            $ids_selected = $user_profile_ids_selected;
        }

        $this->model = Videoconf::findOne($id);;
        $users = $this->model->getUserProfileParticipants()
            ->andWhere(['NOT IN', 'ID', $ids_deleted])
            ->all();

        foreach ($users as $model) {
            $participants_user_ids [] = $model->user_id;
        }

        $participants_user_profile_ids = ArrayHelper::merge(
            $participants_user_ids,
            $ids_selected
        );

        return $this->renderAjax(
            '@vendor/open20/amos-videoconference/src/views/videoconf/parts/_result_search',
            [
                'dataProvider' => $dataProvider,
                'participants_user_profile_ids' => $participants_user_profile_ids
            ]
        );
    }

    /**
     * @param $id
     * @param $selected_users
     * @param $deleted_users
     * @return string
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionUsersInvitedWebmeeting($id, $selected_users, $deleted_users)
    {
        $user_profile_ids_selected = explode(',', $selected_users);
        $user_profile_ids_deleted = explode(',', $deleted_users);

        $this->model = Videoconf::findOne($id);
        $users = $this->model->getUserProfileParticipants()
            ->andWhere(['NOT IN', 'id', $user_profile_ids_deleted])
            ->all();

        //aggiungi utenti selezionati
        foreach ($user_profile_ids_selected as $user_profile_id) {
            $profile = UserProfile::find()
                ->andWhere(['id' => $user_profile_id])
                ->one();
            if ($profile) {
                $users [] = $profile;
            }
        }
        $dataProviderInvitationUsers = new ArrayDataProvider([
            'allModels' => $users
        ]);

        return $this->renderAjax('@vendor/open20/amos-videoconference/src/views/videoconf/parts/_result_search', [
            'dataProvider' => $dataProviderInvitationUsers,
            'participants' => true
        ]);
    }

    /**
     * @return string
     */
    public function actionMoreInfo(){
        $participants = null;
        $expandRowKey = \Yii::$app->request->post('expandRowKey');
        /** @var  $model WebMeetingModel*/
        $model = Videoconf::findOne($expandRowKey);
        if($model) {
            $participants = $model->userProfileParticipants;
        }
        return $this->renderPartial('more-info',[
            'model' => $model,
            'participants' => $participants
        ]);



    }


}