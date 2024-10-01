<?php

namespace app\modules\cms\admin\apis;

use Yii;
use yii\data\ActiveDataProvider;
use app\modules\cms\components\AdminUser;

/**
 * User API, provides ability to manager and list all administration users.
 *
 * @since 1.0.0
 */
class UserController extends \luya\admin\apis\UserController
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
            $find->leftJoin('admin_user_group g','g.user_id = admin_user.id');
            $find->andWhere(['<>','g.group_id',1]);
        }
        return new ActiveDataProvider([
            'query' => $find->with($this->getWithRelation('search')),
            'pagination' => $this->pagination,
            'sort' => [
                'attributes' => $this->generateSortAttributes($this->model->getNgRestConfig()),
            ]
        ]);
    }
}
