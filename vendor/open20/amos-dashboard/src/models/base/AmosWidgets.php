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

use open20\amos\core\record\AmosRecordAudit;
use open20\amos\dashboard\AmosDashboard;
use yii\helpers\ArrayHelper;

/**
 * Class AmosWidgets
 *
 * This is the base-model class for table "amos_widgets".
 *
 * @property string $classname
 * @property string $type
 * @property string $module
 * @property integer $status
 * @property string $child_of
 * @property integer $default_order
 * @property integer $dashboard_visible
 * @property integer $sub_dashboard
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 * @property integer $deleted_by
 * @property string $deleted_at
 *
 * @property \open20\amos\dashboard\models\AmosUserDashboardsWidgetMm[] $amosUserDashboardsWidgetMms
 * @property \open20\amos\dashboard\models\AmosUserDashboards[] $amosUserDashboards
 *
 * @package open20\amos\dashboard\models\base
 */
class AmosWidgets extends \open20\amos\notificationmanager\record\NotifyAuditRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'amos_widgets';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['classname', 'type', 'module'], 'required'],
            [['status', 'created_by', 'updated_by', 'deleted_by', 'id', 'sub_dashboard'], 'integer'],
            [['default_order', 'dashboard_visible', 'sub_dashboard', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['classname', 'child_of'], 'string', 'max' => 255],
            [['type', 'module'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'classname' => AmosDashboard::t('amosdashboard', 'Classname'),
            'type' => AmosDashboard::t('amosdashboard', 'Type'),
            'module' => AmosDashboard::t('amosdashboard', 'Module'),
            'status' => AmosDashboard::t('amosdashboard', 'Status'),
            'child_of' => AmosDashboard::t('amosdashboard', 'Child Of'),
            'default_order' => AmosDashboard::t('amosdashboard', 'Default Order'),
            'dashboard_visible' => AmosDashboard::t('amosdashboard', 'Visible on dashboard'),
            'created_by' => AmosDashboard::t('amosdashboard', 'Created By'),
            'created_at' => AmosDashboard::t('amosdashboard', 'Created At'),
            'updated_by' => AmosDashboard::t('amosdashboard', 'Updated By'),
            'updated_at' => AmosDashboard::t('amosdashboard', 'Updated At'),
            'deleted_by' => AmosDashboard::t('amosdashboard', 'Deleted By'),
            'deleted_at' => AmosDashboard::t('amosdashboard', 'Deleted At')
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAmosUserDashboardsWidgetMms()
    {
        return $this->hasMany(\open20\amos\dashboard\models\AmosUserDashboardsWidgetMm::className(), [/*'amos_widgets_classname' => 'classname',*/ 'amos_widgets_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAmosUserDashboards()
    {
        return $this->hasMany(\open20\amos\dashboard\models\AmosUserDashboards::className(), ['id' => 'amos_user_dashboards_id'])->viaTable('amos_user_dashboards_widget_mm', [/*'amos_widgets_classname' => 'classname',*/ 'amos_widgets_id' => 'id']);
    }
}
