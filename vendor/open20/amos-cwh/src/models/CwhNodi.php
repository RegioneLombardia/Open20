<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\cwh
 * @category   CategoryName
 */

namespace open20\amos\cwh\models;

use open20\amos\cwh\models\base\CwhNodiView;
use yii\base\Exception;

/**
 * This is the model class for table "cwh_nodi".
 */
class CwhNodi extends \open20\amos\cwh\models\base\CwhNodi
{
    private $text;

    /*
      public static function find()
      {
      return new \open20\amos\cwh\models\query\CwhNodiQuery(get_called_class());
      }
     */

    public static function primaryKey()
    {
        return [
            'id'
        ];
    }

    /**
     * @return mixed
     */
    public function getText()
    {

        if (!$this->text) {
            if (isset($this->record_id)) {
                $NodeRecordClass = $this->classname;
                $model = $NodeRecordClass::findOne($this->record_id);
                if (!(is_null($model))) {
                    $this->text = $model->__toString();
                }
            }
        }

        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     *
     */
    public static function mustReset()
    {

        try {
            $maxdate = CwhNodi::find()->max('updated_at');
            $maxdate_view = CwhNodiView::find()->max('updated_at');
            if ($maxdate != $maxdate_view) {
                \Yii::$app->db->createCommand()->truncateTable(CwhNodi::tableName())->execute();
                \Yii::$app->db->createCommand('INSERT ' . CwhNodi::tableName() . ' SELECT * FROM ' . CwhNodiView::tablename())->execute();
            }
        } catch (Exception $ex) {
            \Yii::getLogger()->log($ex->getTraceAsString(), \yii\log\Logger::LEVEL_ERROR);
        }
    }

}
