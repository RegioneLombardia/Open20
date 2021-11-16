<?php

namespace common\models;

use Yii;

//use backend\modules\cwh\behaviors\CwhNetworkBehaviors;
//use backend\modules\eventi\models\query\EventiQuery;
//use raoul2000\workflow\base\SimpleWorkflowBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "log_azioni_cron".
 */
class LogAzioniCron extends \common\models\base\LogAzioniCron
{

    public function representingColumn()
    {
        return [
//inserire il campo o i campi rappresentativi del modulo
        ];
    }

    public function attributeHints()
    {
        return [
        ];
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
        ]);
    }

    public function attributeLabels()
    {
        return
            ArrayHelper::merge(
                parent::attributeLabels(),
                [
                ]);
    }


}
