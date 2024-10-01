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
 * This is the base-model class for table "amos_user_dashboards_widget_mm".
 *
 * @property integer $amos_user_dashboards_id
 * @property string $amos_widgets_classname
 * @property integer $order
 */
class AmosUserDashboardsWidgetMm extends AmosRecordAudit
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'amos_user_dashboards_widget_mm';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['amos_user_dashboards_id', 'amos_widgets_classname', 'id'], 'required'],
            [['amos_user_dashboards_id', 'order', 'id'], 'integer'],
            [['amos_widgets_classname', 'created_at'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'amos_user_dashboards_id' => AmosDashboard::t('amosdashboard', 'Amos User Dashboards ID'),
            'amos_widgets_classname' => AmosDashboard::t('amosdashboard', 'Amos Widgets Classname'),
            'order' => AmosDashboard::t('amosdashboard', 'Order'),
            'created_at' => AmosDashboard::t('amosdashboard', 'Created at'),
        ]);
    }
}
