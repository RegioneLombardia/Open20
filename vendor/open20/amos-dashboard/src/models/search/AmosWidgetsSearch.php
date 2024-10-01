<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\dashboard
 * @category   CategoryName
 */

namespace open20\amos\dashboard\models\search;

use open20\amos\dashboard\models\AmosWidgets;
use yii\data\ArrayDataProvider;
use yii\db\ActiveQuery;

class AmosWidgetsSearch extends AmosWidgets
{
    /**
     * @param int $subDashboard
     * @param string|null $module
     * @param bool $forceAll
     * @param bool $configureCommunityDashboard
     * @return ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public static function selectableIcon($subDashboard = 0, $module = null, $forceAll = false, $configureCommunityDashboard = false)
    {
        $count = 0;
        if(\Yii::$app->getModule('community') && !$configureCommunityDashboard){
            $moduleCwh = \Yii::$app->getModule('cwh');
            if (isset($moduleCwh) && !empty($moduleCwh->getCwhScope())) {
                $scope = $moduleCwh->getCwhScope();
                if (isset($scope['community'])) {
                    $count = \open20\amos\community\models\CommunityAmosWidgetsMm::find()->andWhere(['community_id' => $scope['community']])->count();
                }
            }
        }

        $selectable = self::selectable()
            ->andWhere(['type' => AmosWidgets::TYPE_ICON]);
        if($count > 0){
            $selectable
                ->innerJoin('community_amos_widgets_mm', 'amos_widgets.id = community_amos_widgets_mm.amos_widgets_id')
                ->andWhere(['community_id' => $scope['community']]);
        }else {
            $selectable->andWhere(['sub_dashboard' => $subDashboard]);
            if ($module !== null) {
                $selectable = $selectable->andWhere(['module' => $module]);
            }
            if($forceAll == false){
                $selectable = $selectable->andWhere(['dashboard_visible' => 1]);
            }
        }

        $selectable->orderBy(['default_order' => SORT_ASC]);
        return $selectable;
    }
    
    /**
     * @return ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public static function selectable()
    {
        $permissions = \Yii::$app->authManager->getPermissionsByUser(\Yii::$app->getUser()->getId());
        $modules = array_keys(\Yii::$app->getModules());
        
        $ret = parent::find()
            ->andWhere([
                'status' => AmosWidgets::STATUS_ENABLED,
                'classname' => array_keys($permissions),
                'module' => $modules,
            ]);
       
        return $ret;
    }

    /**
     * @param string $type
     * @param boolean $checkPermission
     * @param string|null $module
     * @return array Array of AmosWidgets
     */
    public static function getAllSubdashWidgets($type = AmosWidgets::TYPE_ICON, $checkPermission = false, $module = null)
    {
        if ($type == AmosWidgets::TYPE_GRAPHIC) {
            $widgetArray = self::selectableGraphic(1, $module, true);
        } else {
            $widgetArray = self::selectableIcon(1, $module, true);
        }

        $modulesSubdashboard = (!empty(\Yii::$app->getModule('dashboard')->modulesSubdashboard) ? \Yii::$app->getModule('dashboard')->modulesSubdashboard
                : null);
        if (!empty($modulesSubdashboard)) {
            $widgetArray->andWhere(['module' => $modulesSubdashboard]);
        }

        if ($checkPermission === true) {
            $permissions = \Yii::$app->authManager->getPermissionsByUser(\Yii::$app->getUser()->getId());
            $widgetArray->andWhere(['classname' => array_keys($permissions)]);
        }

        $widgetArray->orderBy('module, classname ASC');

        $widgetsToShow = [];

        foreach ($widgetArray->all() as $widget) {
            if (!empty($widget->classname) && !empty($widget->type) && !empty($widget->module)) {
                if ($checkPermission === false) {
                    $widgetsToShow[] = $widget;
                } else if ($checkPermission === true && \Yii::$app->user->can($widget->classname)) {
                    $widgetsToShow[] = $widget;
                }
            }
        }

        return $widgetsToShow;
    }
    
    /**
     * @param int $subDashboard
     * @param string|null $module
     * @param bool $forceAll
     * @param bool $configureCommunityDashboard
     * @return ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public static function selectableGraphic($subDashboard = 0, $module = null, $forceAll = false, $configureCommunityDashboard = false)
    {
        $count = 0;
        if(\Yii::$app->getModule('community') && !$configureCommunityDashboard){
            $moduleCwh = \Yii::$app->getModule('cwh');
            if (isset($moduleCwh) && !empty($moduleCwh->getCwhScope())) {
                $scope = $moduleCwh->getCwhScope();
                if (isset($scope['community'])) {
                    $count = \open20\amos\community\models\CommunityAmosWidgetsMm::find()
                        ->andWhere(['community_id' => $scope['community']])->count();
                }
            }
        }

        $selectable = self::selectable()
            ->andWhere(['type' => AmosWidgets::TYPE_GRAPHIC]);
        if($count > 0){
            $selectable
                ->innerJoin('community_amos_widgets_mm', 'amos_widgets.id = community_amos_widgets_mm.amos_widgets_id')
                ->andWhere(['community_id' => $scope['community']]);
        }else {
            $selectable->andWhere(['sub_dashboard' => $subDashboard]);
            if ($module !== null) {
                $selectable = $selectable->andWhere(['module' => $module]);
            }
            if((\Yii::$app->getModule('dashboard')->useWidgetGraphicDashboardVisible == true) && ($forceAll == false)){
                $selectable = $selectable->andWhere(['dashboard_visible' => 1]);
            }
        }
        $selectable->orderBy(['default_order' => SORT_ASC]);
        return $selectable;
    }

    public function widgetIcons($params)
    {
        $permissions = \Yii::$app->authManager->getPermissionsByUser(\Yii::$app->getUser()->getId());
        $modules = array_keys(\Yii::$app->getModules());
        $widgetArray = AmosWidgets::find()
            ->andWhere(['type' => AmosWidgets::TYPE_ICON])
            ->andWhere(['classname' => array_keys($permissions)])
            ->andWhere(['dashboard_visible' => 1])
            ->andWhere(['status' =>  AmosWidgets::STATUS_ENABLED ])
            ->andWhere(['module' => $modules])
            ->orderBy('module, classname ASC')
            ->asArray()->all()
        ;

        $widgetsToShow = [];

        foreach ($widgetArray as $widget) {
            if (\Yii::$app->user->can($widget['classname'])) {
                $widgetsToShow[] = $widget;
            }
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $widgetsToShow,
            'pagination' => false,
            'sort' => false,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        /*  $query->andFilterWhere([
          'created_at' => $this->created_at,
          'updated_at' => $this->updated_at,
          'deleted_at' => $this->deleted_at,
          'created_by' => $this->created_by,
          'updated_by' => $this->updated_by,
          'deleted_by' => $this->deleted_by,
          'version' => $this->version,
          ]); */

        /*  $query->andFilterWhere(['like', 'titolo', $this->titolo])
          ->andFilterWhere(['like', 'testo', $this->testo]); */

        return $dataProvider;
    }

//    public static function getAllFatherWidgets(){
//        $ret = parent::find()
//            ->andWhere([
//                'status' => AmosWidgets::STATUS_ENABLED,
//              ['is not', 'child_of', null],
//            ]);
//
//        return $ret;
//    }
}