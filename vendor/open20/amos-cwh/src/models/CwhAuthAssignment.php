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
use open20\amos\admin\models\UserProfile;
use yii\db\Expression;

/**
 * This is the model class for table "cwh_auth_assignment".
 */
class CwhAuthAssignment extends \open20\amos\cwh\models\base\CwhAuthAssignment
{
    public function representingColumn()
    {

        if($this->getIsNewRecord()){
            return parent::representingColumn();
        }

        return $this->user->username . ' - ' . $this->item_name;
    }

    public function getUtenti() {
        $exp = new Expression("CONCAT_WS(' ', nome, cognome) as nome_cognome");
        $utenti = \open20\amos\core\user\User::find()->innerJoinWith('userProfile')->select(['user.id as id', $exp])->asArray()->all();
        return $utenti;
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        //check if cwh config id field exists for retrocompatibility
        $table = \Yii::$app->db->schema->getTableSchema(self::tableName());
        //if exists and it's not set in model, calculate and set it before save
        if(isset($table->columns['cwh_config_id'])) {
            if (!isset($this->cwh_config_id)) {
                $networkKey = $this->cwh_nodi_id;
                $networkArrayId = explode('-', $networkKey);
                $tablename = $networkArrayId[0];
                $cwhConfig = CwhConfig::findOne(['tablename' => $tablename]);
                if (!is_null($cwhConfig)) {
                    $this->cwh_config_id = $cwhConfig->id;
                }
                $this->cwh_network_id = $networkArrayId[1];
            }
        }
        return parent::beforeSave($insert);
    }


}
