<?php

namespace preference\userprofile\models;

use open20\amos\cwh\models\CwhTagOwnerInterestMm;
use open20\amos\tag\models\Tag as BaseTag;
use open20\design\components\bootstrapitalia\interfaces\CheckBoxListTopicsIconInterface;
use yii\helpers\ArrayHelper;

/**
 * @property \open20\amos\cwh\models\CwhTagOwnerInterestMm[] $pcCwhTagOwnerInterestMm
 * @property PreferenceSendingFrequency $preferenceSendingFrequency
 * 
 * Model without table
 */
class Tag extends BaseTag implements CheckBoxListTopicsIconInterface
{


    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['pc_target_order', 'pc_topic_order','pc_active'], 'integer'],
            [['preference_sending_frequency_id'], 'exist', 'skipOnError' => true, 'targetClass' => PreferenceSendingFrequency::className(), 'targetAttribute' => ['preference_sending_frequency_id' => 'id']],
        ]);
    }

    /**
     * @return integer
     */
    public function getPcTargetOrder()
    {
        return $this->pc_target_order;
    }

    /**
     * @return integer
     */
    public function getPcTopicOrder()
    {
        return $this->pc_topic_order;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->nome;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->descrizione;
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPcCwhTagOwnerInterestMm()
    {
        $moduleCwh = \Yii::$app->getModule('cwh');
        if (isset($moduleCwh)) {
            return $this->hasMany(
                CwhTagOwnerInterestMm::className(),
                ['tag_id' => 'id']
            );
        }
        return null;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPreferenceSendingFrequency()
    {
        return $this->hasOne(PreferenceSendingFrequency::className(), ['id' => 'preference_sending_frequency_id']);
    }
}
