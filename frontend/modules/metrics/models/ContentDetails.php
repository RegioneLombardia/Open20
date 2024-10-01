<?php

namespace frontend\modules\metrics\models;

use app\modules\cms\models\NavItem;
use open20\amos\admin\models\UserProfile;
use open20\amos\news\models\News;
use frontend\modules\metrics\Module;
use http\Exception\InvalidArgumentException;
use yii\base\Model;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
/**
 * Non UTILIZZATO! NON UTILIZZARE!
 */
class ContentDetails extends Model
{

    public function init()
    {
        // MODEL NON USATO ! LO BLOCCO!
        die;
        parent::init(); // TODO: Change the autogenerated stub
    }

    /**
     * @var string $startDate
     */
    public $startDate;
    public $uid;
    /**
     * @var string $endDate
     */
    public $endDate;

    public function rules()
    {
        return [
            [['startDate', 'endDate'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'startDate' => Module::t('metrics', 'Da'),
            'endDate' => Module::t('metrics', 'A'),
        ];
    }

    /**
     * @param $params
     * @param $userProfile UserProfile
     * @return void
     * @throws \yii\base\InvalidConfigException
     */
    public function statsContentsCreatedDetails($params, $type, $contents)
    {

        $query = new Query();
        $query->select(new Expression('user_profile.nome
        , user_profile.cognome, user_profile.codice_fiscale, user.email as email, user.id as user_id, user_profile.ultimo_accesso
        , user_profile_classes.name as profile
        , user_profile_classes.code as profile_code
        , user_profile.start_service_date as data_inizio_servizio
        , user_profile.end_service_date as data_fine_servizio
        , user_profile.serial_number as matricola
        , count(content.id) as content_number 
        '));

        $query->from('user');
        $query->innerJoin('user_profile', 'user_profile.user_id = user.id');
        $query->innerJoin('user_profile_classes_user_mm', 'user.id = user_profile_classes_user_mm.user_id');
        $query->innerJoin('user_profile_classes', 'user_profile_classes_user_mm.user_profile_classes_id = user_profile_classes.id');
        $query->andWhere(['user_profile_classes.code' => ['redattore', 'caporedattore']]);

        switch ($type){
            case 'notizie':
                $query->innerJoin('news as content', 'content.created_by = user.id');
                $query->andWhere(['content.deleted_at' => null]);
                $query->andWhere(['content.id' => $contents]);
                break;
            case 'pagine':
                $query->innerJoin('admin_user', 'user.email = admin_user.email');
                $query->innerJoin('cms_nav_item', 'admin_user.id = cms_nav_item.create_user_id');
                $query->innerJoin('cms_nav as content', 'content.id = cms_nav_item.nav_id');
                $query->andWhere(['content.is_deleted' => 0]);
                $query->andWhere(['cms_nav_item.id' => $contents]);
                break;
            case 'immagini':
            case 'allegati':
                $query->innerJoin('attach_file as content', 'content.created_by = user.id');
                $query->andWhere(['content.id' => $contents]);
                break;
            default:
                throw new InvalidArgumentException('Type not accepted');
        }

        $query->groupBy('user.id');

        return $query->all();
    }

    /**
     * @param $params
     * @param $userProfile UserProfile
     * @return void
     * @throws \yii\base\InvalidConfigException
     */
    public function statsContentsPublishedDetails($params, $type, $contents)
    {

        $query = new Query();
        $query->select(new Expression('user_profile.nome
        , user_profile.cognome, user_profile.codice_fiscale, user.email as email, user.id as user_id, user_profile.ultimo_accesso
        , user_profile_classes.name as profile
        , user_profile_classes.code as profile_code
        , user_profile.start_service_date as data_inizio_servizio
        , user_profile.end_service_date as data_fine_servizio
        , user_profile.serial_number as matricola
        , count(content.id) as content_number 
        '));

        $query->from('user');
        $query->innerJoin('user_profile', 'user_profile.user_id = user.id');
        $query->innerJoin('user_profile_classes_user_mm', 'user.id = user_profile_classes_user_mm.user_id');
        $query->innerJoin('user_profile_classes', 'user_profile_classes_user_mm.user_profile_classes_id = user_profile_classes.id');
        $query->andWhere(['user_profile_classes.code' => ['redattore', 'caporedattore']]);
        $query->andWhere(['NOT',['user.id'=>[1]]]);

        switch ($type){
            case 'notizie':
                $sq = new Query();
                /**
                 * (SELECT max(id) as id
                from amos_workflow_transitions_log
                where classname = \'open20\\\\amos\\\\news\\\\models\\\\News\' and end_status = '.Operators::NEWS_PUBBLISHED_STATUS.'
                group by owner_primary_key)
                 */
                $sq->from('amos_workflow_transitions_log');
                $sq->select(new Expression('max(id) as id'));
                $sq->andWhere(['deleted_at' => null]);
                $sq->andWhere(['classname' => 'open20\\amos\\news\\models\\News']);
                $sq->andWhere(['end_status' => Operators::NEWS_PUBBLISHED_STATUS]);
                $sq->groupBy('owner_primary_key');

                $query->innerJoin('amos_workflow_transitions_log', 'amos_workflow_transitions_log.created_by = user.id');
                // query per avere l'ultimo stato messo in pubblicato per contenuto
                 $query->innerJoin(['max_log' => $sq], 'max_log.id = amos_workflow_transitions_log.id');
                 $query->innerJoin('news content', 'content.id = amos_workflow_transitions_log.owner_primary_key');
                $query->andWhere(['content.deleted_at' => null]);
                $query->andWhere(['content.id' => $contents]);
                break;
            case 'pagine':
                $query->innerJoin('admin_user', 'user.email = admin_user.email');
                $query->innerJoin('cms_nav_item', 'admin_user.id = cms_nav_item.create_user_id');
                $query->innerJoin('cms_nav as content', 'content.id = cms_nav_item.nav_id');
                $query->innerJoin('cms_wf_request', 'cms_wf_request.nav_id = content.id 
                    AND cms_wf_request.nav_item_id = cms_nav_item.id
                    AND cms_wf_request.processed_by_user = admin_user.id');
                $query->andWhere(['content.is_deleted' => 0]);
                $query->andWhere(['cms_nav_item.id' => $contents]);
                break;
            case 'immagini':
            case 'allegati':
                $query->innerJoin('attach_file content', 'content.created_by = user.id');
                $query->andWhere(['content.id' => -1]);
                break;
            default:
                throw new InvalidArgumentException('Type not accepted');
        }

        $query->groupBy('user.id');

//        print_r($query->createCommand()->rawSql); die;
        return $query->all();
    }

}