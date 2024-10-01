<?php

namespace app\modules\cms\admin\apis;

use Yii;
use yii\data\ActiveDataProvider;
use luya\admin\ngrest\render\RenderActiveWindowCallback;
use luya\admin\ngrest\NgRest;
use luya\helpers\ObjectHelper;
use luya\helpers\ArrayHelper;
use luya\admin\models\UserAuthNotification;
use luya\admin\models\UserOnline;
use app\modules\cms\components\AdminUser;

/**
 * API to manage, create, udpate and delete all System Groups.
 *
 * @since 1.0.0
 */
class GroupController extends \luya\admin\apis\GroupController
{          
    public function actions()
    {
        
        $actions = parent::actions();       
        $actions['list'] = [ // for ngrest list
                'class' => 'app\modules\cms\admin\ngrest\base\actions\IndexAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'prepareActiveDataQuery' => [$this, 'prepareListQuery'],
                'dataFilter' => $this->getDataFilter(),
            ];
        
        return $actions;
    }
    
    public function actionSearch($query = null)
    {
        
        $isAdmin = AdminUser::isAdmin();
        
        if (empty($query)) {
            $query = Yii::$app->request->post('query');
        }
        
        $find = $this->model->ngRestFullQuerySearch($query);
        if(!$isAdmin){   
            $find->andWhere(['<>','id',1]);
        }
        return new ActiveDataProvider([
            'query' => $find->with($this->getWithRelation('search')),
            'pagination' => $this->pagination,
            'sort' => [
                'attributes' => $this->generateSortAttributes($this->model->getNgRestConfig()),
            ]
        ]);
    }

    public function actionActiveWindowCallback()
    {       
        $isAdmin = AdminUser::isAdmin();
        
        $config = $this->model->getNgRestConfig();       
        $render = new RenderActiveWindowCallback();
        $ngrest = new NgRest($config);
  
        $return = $ngrest->render($render);
        
        if(!$isAdmin){
            if(isset($return['auths']) && isset($return['auths']['admin'])){
                foreach($return['auths']['admin'] as $id=>$line){
                    if($line['id'] != 3 && $line['id'] != 1 && $line['id'] != 17)
                        unset($return['auths']['admin'][$id]);
                }
               
            }
            
            if(isset($return['auths']) && isset($return['auths']['cmsadmin'])){
                foreach($return['auths']['cmsadmin'] as $id=>$line){
                    if($line['id'] == 24 || $line['id'] == 23 || $line['id'] == 29 || $line['id'] == 21 || $line['id'] == 31 || $line['id'] == 22)
                        unset($return['auths']['cmsadmin'][$id]);
                }
               
            }
            
            if(isset($return['auths']) && isset($return['auths']['crawleradmin'])){
                unset($return['auths']['crawleradmin']);     
            }
        }
        
        return $return;
    }
    
    public function actionServices()
    {
        $isAdmin = AdminUser::isAdmin();
        
        $settings = [];
        $apiEndpoint = $this->model->ngRestApiEndpoint();
        $userSortSettings = Yii::$app->adminuser->identity->setting->get('ngrestorder.admin/'.$apiEndpoint, false);
        
        if ($userSortSettings && is_array($userSortSettings)) {
            if ($userSortSettings['sort'] == SORT_DESC) {
                $order = '-'.$userSortSettings['field'];
            } else {
                $order = '+'.$userSortSettings['field'];
            }
            
            $settings['order'] = $order;
        }
        
        $userFilter = Yii::$app->adminuser->identity->setting->get('ngrestfilter.admin/'.$apiEndpoint, false);
        if ($userFilter) {
            $settings['filterName'] = $userFilter;
        }
        
        $modelClass = $this->modelClass;

        // check if taggable exists, if yes return all used tags for the
        if (ObjectHelper::isTraitInstanceOf($this->model, TaggableTrait::class)) {
            $tags = ArrayHelper::toArray($this->model->findTags(), [
                Tag::class => ['id', 'name']
            ]);
        } else {
            $tags = false;
        }

        $notificationMuteState = false;

        $userAuthNotificationModel = UserAuthNotification::find()->where(['user_id' => Yii::$app->adminuser->id, 'auth_id' => $this->authId])->one();
        if ($userAuthNotificationModel) {
            $notificationMuteState = $userAuthNotificationModel->is_muted;
        }

        $service = $this->model->getNgRestServices();    
        if(!$isAdmin){
            if(isset($service['users']) && isset($service['users']['relationdata']) && isset($service['users']['relationdata']['items'])){
                $filterUsers = [];
                foreach($service['users']['relationdata']['items'] as $id=>$item){                    
                    $query = (new \yii\db\Query)->from('admin_user_group')->where(['user_id'=>$item['value']])->one();             
                    if($query && $query['group_id'] == 1) 
                        continue;
                    
                    $filterUsers[] = $item;
                }
                
                $service['users']['relationdata']['items'] = $filterUsers;
            }
        }
        
        return [
            'service' => $service,
            '_authId' => $this->authId,
            '_tags' => $tags,
            '_hints' => $this->model->attributeHints(),
            '_settings' => $settings,
            '_notifcation_mute_state' => $notificationMuteState,
            '_locked' => [
                'data' => UserOnline::find()
                    ->select(['user_id', 'lock_pk', 'last_timestamp', 'firstname', 'lastname', 'admin_user.id'])
                    ->where(['lock_table' => $modelClass::tableName()])
                    ->joinWith('user')
                    ->asArray()
                    ->all(),
                'userId' => Yii::$app->adminuser->id,
            ],
        ];
    }
     
}
