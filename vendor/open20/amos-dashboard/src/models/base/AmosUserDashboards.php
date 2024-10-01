<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\dashboard
 * @category   CategoryName
 */

namespace open20\amos\dashboard\models\base;

use open20\amos\dashboard\AmosDashboard;
use yii\helpers\ArrayHelper;

/**
 * This is the base-model class for table "amos_user_dashboards".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $module
 * @property integer $slide
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 * @property integer $deleted_by
 * @property string $deleted_at
 *
 * @property \open20\amos\dashboard\models\AmosUserDashboardsWidgetMm[] $amosUserDashboardsWidgetMms
 * @property \open20\amos\dashboard\models\AmosWidgets[] $amosWidgetsClassnames
 */
class AmosUserDashboards extends \open20\amos\core\record\AmosRecordAudit {

  /**
   * @inheritdoc
   */
  public static function tableName() {
    return 'amos_user_dashboards';
  }

  /**
   * @inheritdoc
   */
  public function rules() {
    return [
      [['user_id', 'slide', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
      [['module'], 'required'],
      [['created_at', 'updated_at', 'deleted_at'], 'safe'],
      [['module'], 'string', 'max' => 32]
    ];
  }

  /**
   * @inheritdoc
   */
  public function attributeLabels() {
    return ArrayHelper::merge(
      parent::attributeLabels(),
      [
        'id' => AmosDashboard::t('amosdashboard', 'ID'),
        'user_id' => AmosDashboard::t('amosdashboard', 'User ID'),
        'module' => AmosDashboard::t('amosdashboard', 'Module'),
        'slide' => AmosDashboard::t('amosdashboard', 'Slide'),
        'created_by' => AmosDashboard::t('amosdashboard', 'Created By'),
        'created_at' => AmosDashboard::t('amosdashboard', 'Created At'),
        'updated_by' => AmosDashboard::t('amosdashboard', 'Updated By'),
        'updated_at' => AmosDashboard::t('amosdashboard', 'Updated At'),
        'deleted_by' => AmosDashboard::t('amosdashboard', 'Deleted By'),
        'deleted_at' => AmosDashboard::t('amosdashboard', 'Deleted At'),
      ]
    );
  }

  /**
   * @return \yii\db\ActiveQuery
   */
  public function getAmosUserDashboardsWidgetMms() {
    return $this->hasMany(\open20\amos\dashboard\models\AmosUserDashboardsWidgetMm::className(), ['amos_user_dashboards_id' => 'id']);
  }

  /**
   * @return \yii\db\ActiveQuery
   */
  public function getAmosWidgetsClassnames($allModules = false) {
    $user_id = \Yii::$app->getUser()->getId();
    if ($allModules) {
      $query = \open20\amos\dashboard\models\AmosWidgets::find()
        ->leftJoin('amos_user_dashboards_widget_mm',
          'amos_user_dashboards_widget_mm.amos_widgets_classname = amos_widgets.classname AND amos_user_dashboards_widget_mm.amos_widgets_id = amos_widgets.id')
        ->andWhere(['amos_widgets.sub_dashboard' => 0])
        ->orderBy(['amos_user_dashboards_widget_mm.order' => 'ASC']);
    } else {
      $query = $this->hasMany(\open20\amos\dashboard\models\AmosWidgets::className(),
        ['classname' => 'amos_widgets_classname']);
      $query->select('amos_widgets.*, amos_user_dashboards_widget_mm.order as widget_order')
        ->viaTable('amos_user_dashboards_widget_mm', ['amos_user_dashboards_id' => 'id'])
        ->leftJoin('amos_user_dashboards_widget_mm',
          'amos_user_dashboards_widget_mm.amos_widgets_classname = amos_widgets.classname AND amos_user_dashboards_widget_mm.amos_widgets_id = amos_widgets.id')
        ->leftJoin('amos_user_dashboards',
          'amos_user_dashboards_widget_mm.amos_user_dashboards_id = amos_user_dashboards.id')
        ->andWhere(['amos_user_dashboards.user_id' => $user_id])
        ->andWhere(['amos_widgets.sub_dashboard' => 0])
        ->orderBy(['amos_user_dashboards_widget_mm.order' => 'ASC']);
      if ($this->module) {
        $query->andWhere(['amos_user_dashboards.module' => $this->module]);
      }
    }
    return $query;
  }

  /**
   * @return \yii\db\ActiveQuery
   */
  public function getAmosSubDashWidgetsClassnames() {
    $user_id = \Yii::$app->getUser()->getId();
    $query = $this->hasMany(\open20\amos\dashboard\models\AmosWidgets::className(),
        ['classname' => 'amos_widgets_classname'])
      ->select('amos_widgets.*, amos_user_dashboards_widget_mm.order as widget_order')
      ->viaTable('amos_user_dashboards_widget_mm', ['amos_user_dashboards_id' => 'id'])
      ->leftJoin('amos_user_dashboards_widget_mm',
        'amos_user_dashboards_widget_mm.amos_widgets_classname = amos_widgets.classname AND amos_user_dashboards_widget_mm.amos_widgets_id = amos_widgets.id')
      ->leftJoin('amos_user_dashboards',
        'amos_user_dashboards_widget_mm.amos_user_dashboards_id = amos_user_dashboards.id')
      ->andWhere(['amos_user_dashboards.user_id' => $user_id])
      ->andWhere(['amos_widgets.sub_dashboard' => 1])
      ->orderBy(['amos_user_dashboards_widget_mm.order' => 'ASC']);

    if ($this->module) {
      $query->andWhere(['amos_user_dashboards.module' => $this->module]);
    }

    return $query;
  }

}