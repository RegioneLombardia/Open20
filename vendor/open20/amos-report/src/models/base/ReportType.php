<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos-report
 * @category   Model
 */

namespace open20\amos\report\models\base;

use open20\amos\report\AmosReport;
use yii\helpers\ArrayHelper;

/**
 * Class reportType
 *
 * This is the base-model class for table "report_type".
 *
 * @property    integer $id
 * @property    string $name
 * @property    string $description
 * @property    string $created_at
 * @property    string $updated_at
 * @property    string $deleted_at
 * @property    integer $created_by
 * @property    integer $updated_by
 * @property    integer $deleted_by
 *
 *
 * @package open20\amos\report\models\base
 */
class ReportType  extends \open20\amos\core\record\Record
{
    /**
     */
    public static function tableName()
    {
        return 'report_type';
    }

    /**
     */
    public function rules()
    {
        return [
            [['name','description'], 'string'],
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['name'], 'required']
        ];
    }

    /**
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(),
            [
                'id' => AmosReport::t('amosreport', 'Id'),
                'name' => AmosReport::t('amosreport', 'Report Type name'),
                'description' => AmosReport::t('amosreport', 'Report Type description'),
                'created_at' => AmosReport::t('amosreport', 'Created at'),
                'updated_at' => AmosReport::t('amosreport', 'Modified at'),
                'deleted_at' => AmosReport::t('amosreport', 'Deleted at'),
                'created_by' => AmosReport::t('amosreport', 'Created by'),
                'updated_by' => AmosReport::t('amosreport', 'Modified by'),
                'deleted_by' => AmosReport::t('amosreport', 'Deleted by')
            ]
        );
    }
}