<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comuni
 * @category   CategoryName
 */

namespace open20\amos\comuni\models\base;

use Yii;
use open20\amos\comuni\AmosComuni;

/**
 * This is the base-model class for table "istat_comuni_cap".
 *
    * @property integer $id
    * @property integer $comune_id
    * @property string $cap
    * @property string $sospeso
    *
    * @property \app\models\IstatComuni $comune
    */

    class IstatComuniCap extends \open20\amos\core\record\Record
    {


        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'istat_comuni_cap';
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [['comune_id','cap'],'required'],
                [['comune_id'], 'integer'],
                [['cap', 'sospeso'], 'string', 'max' => 255],
                [['comune_id'], 'exist', 'skipOnError' => true, 'targetClass' => IstatComuni::className(), 'targetAttribute' => ['comune_id' => 'id']],
            ];
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'id' => Yii::t('app', 'ID'),
                'comune_id' => Yii::t('app', 'Comune ID'),
                'cap' => Yii::t('app', 'Cap'),
                'sospeso' => Yii::t('app', 'Sospeso'),
            ];
        }

        /**
        * @return \yii\db\ActiveQuery
        */
        public function getComune()
        {
            return $this->hasOne(\open20\amos\comuni\models\IstatComuni::className(), ['id' => 'comune_id']);
        }

}
