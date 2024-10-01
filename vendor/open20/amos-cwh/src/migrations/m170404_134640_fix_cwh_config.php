<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\cwh
 * @category   CategoryName
 */
use yii\db\Migration;
use open20\amos\cwh\models\CwhPubblicazioni;
use open20\amos\cwh\models\CwhConfig;
use open20\amos\cwh\models\CwhNodi;

class m170404_134640_fix_cwh_config extends Migration
{
    const RAW_SQL_COMMUNITY = 'select 
concat(\'community-\',`community`.`id`) AS `id`,
3 AS `cwh_config_id`,
`community`.`id` AS `record_id`,
\'open20\\\\amos\\\\community\\\\models\\\\Community\' AS `classname`,
(CASE `community`.`community_type_id` WHEN 1 THEN 1 ELSE  0 END) AS `visibility`,
`community`.`created_at` AS `created_at`,
`community`.`updated_at` AS `updated_at`,
`community`.`deleted_at` AS `deleted_at`,
`community`.`created_by` AS `created_by`,
`community`.`updated_by` AS `updated_by`,
`community`.`deleted_by` AS `deleted_by` 

from `community`';
    const CLASSNAME_COMMUNITY = 'open20\amos\community\models\Community';
    const RAW_SQL_ENTI_BACKEND = 'select 
concat(\'enti-\',`enti`.`id`) AS `id`,
1 AS `cwh_config_id`,
`enti`.`id` AS `record_id`,
\'backend\\\\modules\\\\enti\\\\models\\\\Enti\' AS `classname`,
1 AS `visibility`,
`enti`.`created_at` AS `created_at`,
`enti`.`updated_at` AS `updated_at`,
`enti`.`deleted_at` AS `deleted_at`,
`enti`.`created_by` AS `created_by`,
`enti`.`updated_by` AS `updated_by`,
`enti`.`deleted_by` AS `deleted_by` 

from `enti`';
    const CLASSNAME_ENTI_BACKEND = 'backend\modules\enti\models\Enti';
    const RAW_SQL_ENTI_AMOS_ORGAN =
        'select 
concat(\'enti-\',`enti`.`id`) AS `id`,
1 AS `cwh_config_id`,
`enti`.`id` AS `record_id`,
\'open20\\\\amos\\\\organizzazioni\\\\models\\\\Enti\' AS `classname`,
1 AS `visibility`,
`enti`.`created_at` AS `created_at`,
`enti`.`updated_at` AS `updated_at`,
`enti`.`deleted_at` AS `deleted_at`,
`enti`.`created_by` AS `created_by`,
`enti`.`updated_by` AS `updated_by`,
`enti`.`deleted_by` AS `deleted_by` 

from `enti`';
    const CLASSNAME_ENTI_ORG = 'open20\amos\organizzazioni\models\Enti';

    const RAW_SQL_USER = 'select 
concat(\'user-\',`user`.`id`) AS `id`,
2 AS `cwh_config_id`,
`user`.`id` AS `record_id`,
\'common\\\\models\\\\User\' AS `classname`,
1 AS `visibility`,
`user`.`created_at` AS `created_at`,
`user`.`updated_at` AS `updated_at`,
`user`.`deleted_at` AS `deleted_at`,
`user`.`created_by` AS `created_by`,
`user`.`updated_by` AS `updated_by`,
`user`.`deleted_by` AS `deleted_by` 
from (`user` join `user_profile` on((`user`.`id` = `user_profile`.`user_id`)))';
    const CLASSNAME_USER = 'common\models\User';

    public function safeUp()
    {
        $fixConfigIds = true;
        if(Yii::$app->db->schema->getTableSchema('enti') !== null) { // IF enti table exists
            //fix enti and cwh config ids
            $fixConfigIds = false;
            $cwhConfigEnti = CwhConfig::findOne(['tablename' => 'enti']);
            $raw_sql_enti = self::RAW_SQL_ENTI_AMOS_ORGAN;
            if (!is_null($cwhConfigEnti)) {
                if (!class_exists(self::CLASSNAME_ENTI_BACKEND)) {

                    $this->update('cwh_config', ['classname' => self::CLASSNAME_ENTI_ORG, 'raw_sql' => $raw_sql_enti],
                        ['tablename' => 'enti']);

                } else {
                    //I have backend/models/enti, just add visibility
                    $raw_sql = self::RAW_SQL_ENTI_BACKEND;
                    $this->update('cwh_config', ['classname' => self::CLASSNAME_ENTI_BACKEND, 'raw_sql' => $raw_sql],
                        ['tablename' => 'enti']);
                }
            } else {
                //configuration not exists, insert
                $newConfigEnti = new CwhConfig();
                $newConfigEnti->id = 1;
                $newConfigEnti->classname = self::CLASSNAME_ENTI_ORG;
                $newConfigEnti->raw_sql = $raw_sql_enti;
                $newConfigEnti->tablename = 'enti';
                $newConfigEnti->detachBehaviors();
                $newConfigEnti->save(false);
                //do insert configuration anyway if module not yet present/active just soft delete the record
                if(!class_exists(self::CLASSNAME_ENTI_ORG)){
                    $newConfigEnti->delete();
                }
            }
        } else {
            $cwhConfigEnti = CwhConfig::findOne(['tablename' => 'enti']);
            if(!empty($cwhConfigEnti)){
                $cwhConfigEnti->detachBehaviors();
                $cwhConfigEnti->delete();
            }
        }

        //fix community

        $cwhConfigCommunity = CwhConfig::findOne(['tablename' => 'community']);

        $raw_sql_community = self::RAW_SQL_COMMUNITY;
        if (!is_null($cwhConfigCommunity)) {
            //configuration exists , do update
            $this->update('cwh_config', ['raw_sql' => $raw_sql_community, 'classname' => self::CLASSNAME_COMMUNITY], ['tablename' => 'community']);
        } else {
            //configuration not exists, insert
            $newConfigCommunity = new CwhConfig();
            $newConfigCommunity->id = 3;
            $newConfigCommunity->classname = self::CLASSNAME_COMMUNITY;
            $newConfigCommunity->raw_sql = $raw_sql_community;
            $newConfigCommunity->tablename = 'community';
            $newConfigCommunity->detachBehaviors();
            $newConfigCommunity->save(false);
            //do insert configuration anyway if module not yet present/active just soft delete the record
            if(!class_exists(self::CLASSNAME_COMMUNITY)){
                $newConfigCommunity->delete();
            }
        }

        //fix user
        $cwhConfigUser = CwhConfig::findOne(['tablename' => 'user']);
        $raw_sql_user = self::RAW_SQL_USER;
        if (!is_null($cwhConfigUser)) {
            //configuration exists , do update
            $this->update('cwh_config', ['raw_sql' => $raw_sql_user, 'classname' => self::CLASSNAME_USER], ['tablename' => 'user']);
        } else {
            //configuration not exists, insert
            $newConfigUser = new CwhConfig();
            $newConfigUser->id = 2;
            $newConfigUser->classname = self::CLASSNAME_USER;
            $newConfigUser->raw_sql = $raw_sql_user;
            $newConfigUser->tablename = 'user';
            $newConfigUser->detachBehaviors();
            $newConfigUser->save(false);
        }

        if($fixConfigIds){
            $cwhPublications = CwhPubblicazioni::find()->andWhere(['cwh_config_id' => 1])->all();
            foreach($cwhPublications as $cwhPublication){

                $cwhConfigEditori = CwhNodi::find()->innerJoin('cwh_pubblicazioni_cwh_nodi_editori_mm', "cwh_pubblicazioni_cwh_nodi_editori_mm.cwh_nodi_id = cwh_nodi_view.id AND cwh_pubblicazioni_cwh_nodi_editori_mm.cwh_pubblicazioni_id = '".$cwhPublication->id."'")
                    ->addSelect('cwh_config_id')->column();
                if(!empty($cwhConfigEditori)){
                    $cwhConfigId = $cwhConfigEditori[0];
                }else{
                    $cwhConfigValidatori = CwhNodi::find()->innerJoin('cwh_pubblicazioni_cwh_nodi_validatori_mm', "cwh_pubblicazioni_cwh_nodi_validatori_mm.cwh_nodi_id = cwh_nodi_view.id AND cwh_pubblicazioni_cwh_nodi_validatori_mm.cwh_pubblicazioni_id = '".$cwhPublication->id."'")
                        ->addSelect('cwh_config_id')->column();
                    if(!empty($cwhConfigValidatori)) {
                        $cwhConfigId = $cwhConfigValidatori[0];
                    } else {
                        $cwhConfigId = 3;
                    }
                }
                $cwhPublication->cwh_config_id = $cwhConfigId;
                $cwhPublication->detachBehaviors();
                $cwhPublication->save(false);
            }
        }

        return true;
    }

    public function safeDown()
    {
        return true;
    }

}
