<?php

namespace backend\modules\statistics\models\stats;

use backend\modules\campains\models\PreferenceCampainChannelMm;
use backend\modules\campains\utility\TableMessageBouncesUtility;
use backend\modules\campains\utility\TableMessageViewsUtility;
use backend\modules\campains\utility\TableSentUtility;
use backend\modules\statistics\Module;
use open20\amos\admin\models\UserProfileAgeGroup;
use preference\userprofile\models\PreferenceChannel;
use Yii;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use preference\userprofile\utility\TargetTagUtility;
use open20\amos\admin\models\UserProfile;
use yii\db\Expression;

/**
 * 
 */
class CampainsViewsStats extends Model
{
    public $channel_id;
    public $target_id;
    public $user_status_id;

    private $user_status_choices = [
        1 => 'Iscrizioni',
        2 => 'Disiscrizioni',
    ];

    public function getUserStatusChioces()
    {
        return $this->user_status_choices;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'channel_id' => Module::t('statistics', 'Mail inviata'),
            'target_id' => Module::t('statistics', 'Target'),
            'user_status_id' => Module::t('statistics', 'Iscrizioni'),
        ];
    }

    public function rules()
    {
        return [
            [['channel_id', 'target_id', 'user_status_id'], 'safe'],
        ];
    }

    /**
     * 
     */
    private function queryBase()
    {
        $communicationUserTable = TableSentUtility::getTableName($this->channel_id);
        $communicationViewsTable = TableMessageViewsUtility::getTableName($this->channel_id);

        if (Yii::$app->dbstats->schema->getTableSchema(TableMessageViewsUtility::getTableName($this->channel_id), true) === null) {
            return null;
        }
        if (Yii::$app->dbstats->schema->getTableSchema(TableSentUtility::getTableName($this->channel_id), true) === null) {
            return null;
        }

        $query  = new Query();
        $query->select('*')
            ->from($communicationUserTable . ' user')
            ->leftJoin($communicationViewsTable . ' opening', 'user.email = opening.email');

        return $query;
    }

    public function statsGender($params)
    {
        $toret = [
            'graf-data' => [],
            'data-provider' => []
        ];

        if (!($this->load($params) && $this->validate())) {
            return $toret;
        }

        $query = $this->queryBase();
        if (is_null($query)) {
            return $toret;
        }
        $queryMale = clone $query;
        $queryFemale = clone $query;

        $queryMale->andWhere(['gender' => 'Maschio']);
        $queryFemale->andWhere(['gender' => 'Femmina']);
        $query->andWhere(['gender' => null]);

        $numNd = $query->count('*', Yii::$app->dbstats);
        $numMale = $queryMale->count('*', Yii::$app->dbstats);
        $numFemale = $queryFemale->count('*', Yii::$app->dbstats);

        $arrayData =  [
            ['Maschi', intVal($numMale)],
            ['Femmine', intVal($numFemale)],
            ['ND', intVal($numNd)],
        ];

        $toret['graf-data'] = array_merge([0 => ['Element', 'Numero di persone']], $arrayData);
        $toret['data-provider'] = new ArrayDataProvider([
            'allModels' => $arrayData,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        // VarDumper::dump($toret, $depth = 10, $highlight = true);
        return $toret;
    }

    public function statsAgeGroups($params)
    {
        $toret = [
            'graf-data' => [],
            'data-provider' => []
        ];

        if (!($this->load($params) && $this->validate())) {
            return $toret;
        }

        $query = $this->queryBase();
        if (is_null($query)) {
            return $toret;
        }

        $query->groupBy(['user_profile_age_group_id']);
        $query->select('user_profile_age_group_id, count(*) as num');
        $arrayData = $this->ageGroupResultMap($query->all());

        $toret['graf-data'] = array_merge([0 => ['Element', 'Numero di persone']], $arrayData);
        $toret['data-provider'] = new ArrayDataProvider([
            'allModels' => $arrayData,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        // VarDumper::dump($toret, $depth = 10, $highlight = true);
        return $toret;
    }

    private function ageGroupResultMap($ageGroupArray)
    {
        $tmp = [];
        $ages = UserProfileAgeGroup::find()->andWhere(['enabled' => 1])->asArray()->all();
        $agesGroups = ArrayHelper::map($ages, 'id', 'age_group');
        $agesOrder = ArrayHelper::map($ages, 'id', 'order');

        foreach ($ageGroupArray as $stat) {
            $name = isset($agesGroups[$stat['user_profile_age_group_id']]) ? $agesGroups[$stat['user_profile_age_group_id']] : 'ND';
            $tmp[] = [$name, intval($stat['num']), $agesOrder[$stat['user_profile_age_group_id']] ? intval($agesOrder[$stat['user_profile_age_group_id']]) : 0];
        }
        ArrayHelper::multisort($tmp, [2], [SORT_ASC]);

        $toret = [];
        // Elimino la colonna usata per l'ordinamento
        foreach ($tmp as $value) {
            $toret[] = [$value[0], $value[1]];
        }

        return $toret;
    }

    public function statsProvinces($params)
    {
        $toret = [
            'graf-data' => [],
            'data-provider' => []
        ];

        if (!($this->load($params) && $this->validate())) {
            return $toret;
        }

        $query = $this->queryBase();
        if (is_null($query)) {
            return $toret;
        }

        $query->groupBy(['province_residence_id']);
        $query->select('province_residence_id, province, count(*) as num');
        $arrayData = $this->provincesResultMap($query->all());

        $toret['graf-data'] = array_merge([0 => ['Element', 'Numero di persone']], $arrayData);
        $toret['data-provider'] = new ArrayDataProvider([
            'allModels' => $arrayData,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        // VarDumper::dump($toret, $depth = 10, $highlight = true);
        return $toret;
    }

    private function provincesResultMap($provincesArray)
    {
        $toret = [];

        foreach ($provincesArray as $stat) {
            $name = !is_null($stat['province_residence_id']) ? $stat['province'] : 'ND';
            $toret[] = [$name, intval($stat['num'])];
        }
        ArrayHelper::multisort($toret, [1], [SORT_ASC]);
        return $toret;
    }



    public function statTargetsItems($params)
    {
        $toret = [
            'graf-data' => [],
            'data-provider' => []
        ];

        if (!($this->load($params) && $this->validate())) {
            return $toret;
        }

        $q  = new Query();
        $q
            ->from('preference_campain_channel_mm channel')  // preference_campain_id
            ->innerJoin('preference_campain campain', 'campain.id = channel.preference_campain_id')
            ->innerJoin('preference_campain_tag_mm tagmm', 'tagmm.preference_campain_id = campain.id')
            ->innerJoin('tag item', 'item.id = tagmm.tag_id')
            ->innerJoin('tag target', 'target.id = item.root')
            ->andWhere(['channel.deleted_at' => null])
            ->andWhere(['channel.status' => PreferenceCampainChannelMm::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW_STATUS_SENT])
            ->andWhere(['tagmm.deleted_at' => null])
            ->andWhere(['item.deleted_at' => null])
            ->andWhere(['campain.deleted_at' => null]);


        $q->select(new Expression("channel.mailup_stats_number_comunication_sent ei
            , (channel.mailup_stats_number_comunication_sent - IF(channel.mailup_stats_number_comunication_bounces IS NULL, 0, channel.mailup_stats_number_comunication_bounces)) rec
            , IF(channel.mailup_stats_number_comunication_bounces IS NULL, 0, channel.mailup_stats_number_comunication_bounces) bounces
            , IF(channel.mailup_stats_number_comunication_clicks IS NULL, 0, channel.mailup_stats_number_comunication_clicks) cu
            , IF(channel.mailup_stats_number_comunication_views IS NULL, 0, channel.mailup_stats_number_comunication_views) aui
            , channel.id 
            , channel.preference_campain_id 
            , item.nome item 
            , target.nome target 
            "));

        $query  = new Query();
        // VarDumper::dump( $q->createCommand()->rawSql, $depth = 10, $highlight = true); die;
        $query->from(['channel' => $q]);
        $query->select(new Expression("channel.*
            , target
            , item
            , ((channel.rec / channel.ei) * 100) AS 'tasso_recapito'
            , ((channel.aui / channel.rec) * 100) AS 'tasso_aperture'
            , ((channel.cu / channel.rec) * 100) AS 'tasso_click'
            , ((channel.cu / channel.aui) * 100) AS 'tasso_click_aperture'

            , SUM(((channel.rec / channel.ei) * 100)) AS 'sum_tasso_recapito'
            , SUM(((channel.aui / channel.rec) * 100)) AS 'sum_tasso_aperture'
            , SUM(((channel.cu / channel.rec) * 100)) AS 'sum_tasso_click'
            , SUM(((channel.cu / channel.aui) * 100)) AS 'sum_tasso_click_aperture'

            , count(*) tot_invii
            "));

        if ($this->target_id == 0) {
            $q->groupBy('channel.id, target.id');
            $query->groupBy('target');
            //  VarDumper::dump($query->createCommand()->rawSql, $depth = 10, $highlight = true); 
            $queryResult = $query->all();
            // VarDumper::dump($queryResult, $depth = 10, $highlight = true);
            $arrayData = [];
            $arrayDataTable = [];
            foreach ($queryResult as $value) {
                $t1 = bcdiv($value['sum_tasso_recapito'], $value['tot_invii'], 2);
                $t2 = bcdiv($value['sum_tasso_aperture'], $value['tot_invii'], 2);
                $t3= bcdiv($value['sum_tasso_click'], $value['tot_invii'], 2);
                $t4= bcdiv($value['sum_tasso_click_aperture'], $value['tot_invii'], 2);
                $arrayDataTable[] =  [$value['target'], floatVal($t1), floatVal($t2), floatVal($t3), floatVal($t4), intVal($value['tot_invii'])];
                $arrayData[] =  [$value['target'], floatVal($t1), floatVal($t2), floatVal($t3), floatVal($t4)];
            }
            // "Tasso medio di recapito", "Tasso medio di aperture"; Tasso medio di click", "Tasso medio di click su aperture"
            $toret['graf-data'] = array_merge([0 => ['Target', 'Tasso medio di recapito (%)', 'Tasso medio di aperture (%)', 'Tasso medio di click (%)', 'Tasso medio di click su aperture (%)']], $arrayData);
            $toret['data-provider'] = new ArrayDataProvider([
                'allModels' => $arrayDataTable,
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);
        } else {
            $q->andWhere(['item.root' => $this->target_id]);
            $q->groupBy('channel.id, item.id');
            $query->groupBy('item');

            // VarDumper::dump($query->createCommand()->rawSql, $depth = 10, $highlight = true);

            $queryResult = $query->all();
            // VarDumper::dump($queryResult, $depth = 10, $highlight = true);

            $arrayData = [];
            foreach ($queryResult as $value) {
                $t1 = bcdiv($value['sum_tasso_recapito'], $value['tot_invii'], 2);
                $t2 = bcdiv($value['sum_tasso_aperture'], $value['tot_invii'], 2);
                $t3= bcdiv($value['sum_tasso_click'], $value['tot_invii'], 2);
                $t4= bcdiv($value['sum_tasso_click_aperture'], $value['tot_invii'], 2);
                $arrayData[] =  [$value['item'], floatVal($t1), floatVal($t2), floatVal($t3), floatVal($t4), intVal($value['tot_invii'])];
            }

            $toret['graf-data'] = array_merge([0 => ['Tematica', 'Tasso di recapito (%)', 'Tasso su aperture (%)', 'Tasso di click (%)', 'Tasso di clic su aperture (%)', 'Num campagne']], $arrayData);
            $toret['data-provider'] = new ArrayDataProvider([
                'allModels' => $arrayData,
                'pagination' => [
                    'pageSize' => 25,
                ],
            ]);
        }

        // VarDumper::dump($toret, $depth = 10, $highlight = true);
        return $toret;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    private function queryBasePersonalDataTot()
    {

        $query  = new Query();
        // $query = UserProfile::find()
        $query->from('user_profile')

            ->innerJoin('user', 'user.id = user_profile.user_id')
            // Gli attributi sul targhet, la mail il telefono di contatto e se sono validati
            // ->innerJoin('preference_user_target_attribute attr', 'attr.target_code COLLATE utf8_general_ci = target_chosen.target_code and attr.user_id = user_profile.user_id')
            // Se sono stati abilitati i canali di contatto sulla tematica - colleganemto con l'utente e il items/tematiche
            ->innerJoin('auth_assignment', 'auth_assignment.user_id = user.id')
            ->andWhere(['user.status' => 10])
            ->andWhere(['auth_assignment.item_name' => 'PC_REGISTERD_USER'])
            ->andWhere(['user_profile.privacy' => 1])
            ;

        return $query;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    private function queryBasePersonalData()
    {

        $query  = new Query();
        // $query = UserProfile::find()
        $query->from('user_profile')
            ->innerJoin('cwh_tag_owner_interest_mm as interessi', '`interessi`.classname = \'' . addslashes(UserProfile::className()) . '\' AND `interessi`.record_id = user_profile.id')
            ->innerJoin('preference_target_chosen_for_user_view as target_chosen', '`target_chosen`.user_profile_id = user_profile.id AND interessi.root_id = `target_chosen`.root_id')
            ->innerJoin('tag item', 'item.id = interessi.tag_id')
            ->innerJoin('tag target', 'target.id = item.root')
            ->innerJoin('user', 'user.id = user_profile.user_id')
            // Gli attributi sul targhet, la mail il telefono di contatto e se sono validati
            // ->innerJoin('preference_user_target_attribute attr', 'attr.target_code COLLATE utf8_general_ci = target_chosen.target_code and attr.user_id = user_profile.user_id')
            // Se sono stati abilitati i canali di contatto sulla tematica - colleganemto con l'utente e il items/tematiche
            ->innerJoin('preference_topic_channel_mm canale_abilitato', 'canale_abilitato.topic_code COLLATE utf8_general_ci = item.codice AND canale_abilitato.user_id = user_profile.user_id')
            ->innerJoin('preference_channel canale', 'canale.id = canale_abilitato.preference_channel_id')
            ->where(['interessi.deleted_at' => null])
            ->andWhere(['canale.deleted_at' => null])
            ->andWhere(['canale_abilitato.deleted_at' => null])
            ->andWhere(['canale.active' => 1])
            ->andWhere(['item.pc_active' => 1])
            ->andWhere(['user.status' => 10])
            ->andWhere(['user_profile.privacy' => 1])
            ->andWhere(['canale_abilitato.preference_channel_id' => PreferenceChannel::NEWSLETTER_ID]);

        return $query;
    }

    public function statPersonalDataGender($params)
    {
        $toret = [
            'graf-data' => [],
            'data-provider' => []
        ];

        if (!($this->load($params) && $this->validate())) {
            return $toret;
        }

        /** @var Query $q */
        $q = $this->queryBasePersonalData();
        $q->select(new Expression("user_profile.*, target.nome AS target, item.nome AS item"));

        if (is_null($q)) {
            return $toret;
        }
        /** @var Query $query */
        $query  = new Query();
        $query->from(['people' => $q]);
        $query->select(new Expression(" * 
            ,  SUM(if(people.sesso = 'Maschio', 1, 0)) AS maschi
            ,  SUM(if(people.sesso = 'Femmina', 1, 0)) AS femmine
            ,  SUM(if(people.sesso IS NULL , 1, 0)) AS nd"));

        if ($this->target_id == -1) {
            /** @var Query $q */
            $q = $this->queryBasePersonalDataTot();
            $q->select(new Expression("user_profile.*"));
            $q->groupBy('user_profile.id');
            $query->from(['people' => $q]);
            // VarDumper::dump( $query->createCommand()->rawSql, $depth = 10, $highlight = true); die;
            $queryResult = $query->all();

            $arrayData = [];
            foreach ($queryResult as $value) {
                $arrayData[] =  ['Numero persone', intVal($value['maschi']),  intVal($value['femmine']),  intVal($value['nd'])];
            }

            $toret['graf-data'] = array_merge([0 => ['', 'Maschi', 'Femmine', 'ND']], $arrayData);
            $toret['data-provider'] = new ArrayDataProvider([
                'allModels' => $arrayData,
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);
        } elseif ($this->target_id == 0) {
            // $query->select(new Expression("people.target " . $selectString));
            $q->groupBy('user_profile.id, target.id');
            $query->groupBy('target');
            $queryResult = $query->all();

            // VarDumper::dump( $query->createCommand()->rawSql, $depth = 10, $highlight = true);  die;
            // VarDumper::dump( $query->all(), $depth = 10, $highlight = true); 

            $arrayData = [];
            foreach ($queryResult as $value) {
                $arrayData[] =  [$value['target'], intVal($value['maschi']),  intVal($value['femmine']),  intVal($value['nd'])];
            }

            $toret['graf-data'] = array_merge([0 => ['Target', 'Maschi', 'Femmine', 'ND']], $arrayData);
            $toret['data-provider'] = new ArrayDataProvider([
                'allModels' => $arrayData,
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);
        } else {
            // $query->select(new Expression("item.nome AS item  " . $selectString));
            $q->andWhere(['item.root' => $this->target_id]);
            $q->groupBy('user_profile.id, target.id, item.id');
            $query->groupBy('item');
            $queryResult = $query->all();

            //VarDumper::dump( $query->createCommand()->rawSql, $depth = 10, $highlight = true);  die;
            // VarDumper::dump( $query->all(), $depth = 10, $highlight = true); die;

            $arrayData = [];
            foreach ($queryResult as $value) {
                $arrayData[] =  [$value['item'], intVal($value['maschi']),  intVal($value['femmine']),  intVal($value['nd'])];
            }

            $toret['graf-data'] = array_merge([0 => ['Tematica', 'Maschi', 'Femmine', 'ND']], $arrayData);
            $toret['data-provider'] = new ArrayDataProvider([
                'allModels' => $arrayData,
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);
        }

        //VarDumper::dump($toret, $depth = 10, $highlight = true); die;
        return $toret;
    }

    public function statPersonalDataAgeGroup($params)
    {
        $toret = [
            'graf-data' => [],
            'data-provider' => []
        ];

        if (!($this->load($params) && $this->validate())) {
            return $toret;
        }

        /** @var Query $q */
        $q = $this->queryBasePersonalData();
        $q->select(new Expression("user_profile.*, target.nome AS target, item.nome AS item"));

        if (is_null($q)) {
            return $toret;
        }

        /** @var Query $query */
        $query  = new Query();
        $query->from(['people' => $q]);

        /* <18 18-25  26-35 36-45 46-55 56-65 >65 */
        $selectString = " *
        ,  SUM(if( (((DATE(people.nascita_data)) <= DATE(DATE_SUB(NOW(), INTERVAL 0 YEAR))) AND ((DATE(people.nascita_data)) >= DATE(DATE_SUB(NOW(), INTERVAL 17 YEAR))) ), 1, 0)) AS '<18'
        ,  SUM(if( (((DATE(people.nascita_data)) <= DATE(DATE_SUB(NOW(), INTERVAL 18 YEAR))) AND ((DATE(people.nascita_data)) >= DATE(DATE_SUB(NOW(), INTERVAL 25 YEAR))) ), 1, 0)) AS '18-25'
        ,  SUM(if( (((DATE(people.nascita_data)) <= DATE(DATE_SUB(NOW(), INTERVAL 26 YEAR))) AND ((DATE(people.nascita_data)) >= DATE(DATE_SUB(NOW(), INTERVAL 35 YEAR))) ), 1, 0)) AS '26-35'
        ,  SUM(if( (((DATE(people.nascita_data)) <= DATE(DATE_SUB(NOW(), INTERVAL 36 YEAR))) AND ((DATE(people.nascita_data)) >= DATE(DATE_SUB(NOW(), INTERVAL 45 YEAR))) ), 1, 0)) AS '36-45'
        ,  SUM(if( (((DATE(people.nascita_data)) <= DATE(DATE_SUB(NOW(), INTERVAL 46 YEAR))) AND ((DATE(people.nascita_data)) >= DATE(DATE_SUB(NOW(), INTERVAL 55 YEAR))) ), 1, 0)) AS '46-55'
        ,  SUM(if( (((DATE(people.nascita_data)) <= DATE(DATE_SUB(NOW(), INTERVAL 56 YEAR))) AND ((DATE(people.nascita_data)) >= DATE(DATE_SUB(NOW(), INTERVAL 65 YEAR))) ), 1, 0)) AS '56-65'
        ,  SUM(if( (((DATE(people.nascita_data)) <= DATE(DATE_SUB(NOW(), INTERVAL 66 YEAR))) AND ((DATE(people.nascita_data)) >= DATE(DATE_SUB(NOW(), INTERVAL 200 YEAR))) ), 1, 0)) AS '>65'
        ,  SUM(if(people.nascita_data IS NULL , 1, 0)) AS nd
        ";

        if ($this->target_id == -1) {
            /** @var Query $q */
            $q = $this->queryBasePersonalDataTot();
            $q->select(new Expression("user_profile.*"));
            $q->groupBy('user_profile.id');
            $query->from(['people' => $q]);
            $query->select(new Expression($selectString));
            // VarDumper::dump( $query->createCommand()->rawSql, $depth = 10, $highlight = true);  die;
            $queryResult = $query->all();

            $arrayData = [];
            foreach ($queryResult as $value) {
                $arrayData[] =  ['Numero persone', intVal($value['<18']),  intVal($value['18-25']), intVal($value['26-35']), intVal($value['36-45']), intVal($value['46-55']), intVal($value['56-65']), intVal($value['>65']), intVal($value['nd'])];
            }

            $toret['graf-data'] = array_merge([0 => ['Numero persone', '<18', '18-25', '26-35', '36-45', '46-55', '56-65', '>65', 'ND']], $arrayData);
            $toret['data-provider'] = new ArrayDataProvider([
                'allModels' => $arrayData,
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);
        } elseif ($this->target_id == 0) {
            $q->groupBy('user_profile.id, target.id');
            $query->groupBy('target');
            $query->select(new Expression($selectString));
            // VarDumper::dump( $query->createCommand()->rawSql, $depth = 10, $highlight = true);  die;
            $queryResult = $query->all();

            $arrayData = [];
            foreach ($queryResult as $value) {
                $arrayData[] =  [$value['target'], intVal($value['<18']),  intVal($value['18-25']), intVal($value['26-35']), intVal($value['36-45']), intVal($value['46-55']), intVal($value['56-65']), intVal($value['>65']), intVal($value['nd'])];
            }

            $toret['graf-data'] = array_merge([0 => ['Target', '<18', '18-25', '26-35', '36-45', '46-55', '56-65', '>65', 'ND']], $arrayData);
            $toret['data-provider'] = new ArrayDataProvider([
                'allModels' => $arrayData,
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);
        } else {
            $query->select(new Expression($selectString));
            $q->andWhere(['item.root' => $this->target_id]);
            $q->groupBy('user_profile.id, target.id, item.id');
            $query->groupBy('item');
            $queryResult = $query->all();

            $arrayData = [];
            foreach ($queryResult as $value) {
                $arrayData[] =  [$value['item'], intVal($value['<18']),  intVal($value['18-25']), intVal($value['26-35']), intVal($value['36-45']), intVal($value['46-55']), intVal($value['56-65']), intVal($value['>65']), intVal($value['nd'])];
            }

            $toret['graf-data'] = array_merge([0 => ['Tematica', '<18', '18-25', '26-35', '36-45', '46-55', '56-65', '>65', 'ND']], $arrayData);
            $toret['data-provider'] = new ArrayDataProvider([
                'allModels' => $arrayData,
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);
        }

        // VarDumper::dump($toret, $depth = 10, $highlight = true);
        return $toret;
    }

    public function statPersonalDataProvinces($params)
    {
        $toret = [
            'graf-data' => [],
            'data-provider' => []
        ];

        if (!($this->load($params) && $this->validate())) {
            return $toret;
        }

        /** @var Query $q */
        $q = $this->queryBasePersonalData();
        $q->select(new Expression("user_profile.*, target.nome AS target, item.nome AS item"));

        if (is_null($q)) {
            return $toret;
        }

         /** @var Query $query */
         $query  = new Query();
         $query->from(['people' => $q]);

        $selectString = " *
        , province.id provincia_id
        , province.nome provincia
        , count(people.id) num
        ";

        if ($this->target_id == -1) {

            /** @var Query $q */
            $q = $this->queryBasePersonalDataTot();
            $q->select(new Expression("user_profile.*"));
            $q->groupBy('user_profile.id');
            $query->from(['people' => $q]);
            $query->innerJoin('istat_province province', 'province.id = people.provincia_residenza_id');
            $query->select(new Expression($selectString));
            $query->groupBy('people.provincia_residenza_id');

            $queryResult = $query->all();
            ArrayHelper::multisort($queryResult, 'provincia', SORT_ASC);
           // $queryResult = ArrayHelper::map($queryResult, 'num', 'provincia');

            //VarDumper::dump( $queryResult, $depth = 10, $highlight = true);  die;

            $arrayData = [];
            foreach ($queryResult as $key => $value) {
                $num = isset($value['num']) ? $value['num'] : 0;
                $arrayData[] =  [$value['provincia'], intVal($num)];
            }

            $q->andWhere(['user_profile.provincia_residenza_id' => null]);
            $arrayData[] =  ['nd', count($q->all())];

            $toret['graf-data'] = array_merge([0 => ['Provincia', 'Numero persone']], $arrayData);
            $toret['data-provider'] = new ArrayDataProvider([
                'allModels' => $arrayData,
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);
        } elseif ($this->target_id == 0) {
            $q->groupBy('user_profile.id, target.id');
            $query->leftJoin('istat_province province', 'province.id = people.provincia_residenza_id');
            $query->select(new Expression($selectString));
            $query->groupBy('target, province.id');

            $queryResult = $query->all();
            ArrayHelper::multisort($queryResult, 'provincia', SORT_ASC);
            $queryResult = ArrayHelper::map($queryResult, 'target', 'num', 'provincia');

            $arrayData = [];
            $ndRow = ['ND', 0, 0, 0 /* , intVal($num4) */ ];;
            foreach ($queryResult as $key => $value) {
                $num1 = isset($value['Cittadino']) ? $value['Cittadino'] : 0;
                $num2 = isset($value['Impresa']) ? $value['Impresa'] : 0;
                $num3 = isset($value['Ente e Operatore']) ? $value['Ente e Operatore'] : 0;
//                $num4 = isset($value['nd']) ? $value['nd'] : 0;

                // questo per aggiungere la riga di ND in fondo...
                if (empty($key)) {
                    $ndRow =  ['ND', intVal($num1), intVal($num2), intVal($num3) /* , intVal($num4) */ ];
                } else {
                    $arrayData[] =  [$key, intVal($num1), intVal($num2), intVal($num3) /* , intVal($num4) */ ];
                }

            }
            // verrà sempre aggiunta una riga di ND al limite sarà tutta a 0...
            $arrayData[] = $ndRow;

            $toret['graf-data'] = array_merge([0 => ['Provincia', 'Cittadino', 'Impresa', 'Ente e Operatore' /*, 'ND' */]], $arrayData);
            $toret['data-provider'] = new ArrayDataProvider([
                'allModels' => $arrayData,
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);
        } else {
            $q->andWhere(['item.root' => $this->target_id]);
            $q->groupBy('user_profile.id, target.id, item.id');
            $query->leftJoin('istat_province province', 'province.id = people.provincia_residenza_id');
            $query->select(new Expression($selectString));
            $query->groupBy(['item', 'people.provincia_residenza_id']);
            $queryResult = $query->all();
            // VarDumper::dump( $query->createCommand()->rawSql, $depth = 10, $highlight = true); 
            // VarDumper::dump($queryResult , $depth = 10, $highlight = true); 
            ArrayHelper::multisort($queryResult, 'provincia', SORT_ASC);
            $queryResult = ArrayHelper::map($queryResult, 'item', 'num', 'provincia');
            // VarDumper::dump($queryResult , $depth = 10, $highlight = true); 

            $allTopicModels = \preference\userprofile\utility\TopicTagUtility::getAllTopicByTargetId($this->target_id);
            $allTopic = array_values(ArrayHelper::map($allTopicModels, 'id', 'nome'));
            //array_push($allTopic, 'nd');

            $arrayData = [];

            // inizializzo la riga di ND non disponibili a 0 per tutti i valori... verrà poi sostituita se esistono degli ND
            $ndRow = ['ND'];
            foreach ($allTopic as $k => $topicName) {
                array_push($ndRow, 0);
            }

            foreach ($queryResult as $key => $value) {
                $row = [(empty($key)? 'ND': $key)];
                foreach ($allTopic as $k => $topicName) {
                    $num = isset($value[$topicName]) ? intVal($value[$topicName]) : 0;
                    array_push($row, $num);
                }
                // questo per aggiungere la riga di ND in fondo
                if (empty($key)) {
                    $ndRow = $row;
                } else {
                    $arrayData[] = $row;
                }
            }
            // in questo modo aggiungo alla fine la riga di ND
            // verrà sempre aggiunta una riga di ND al limite sarà tutta a 0... come inizializzato...
            $arrayData[] = $ndRow;

            array_unshift($allTopic, 'Provincie');

            $toret['graf-data'] = array_merge([0 => $allTopic], $arrayData);
            $toret['data-provider'] = new ArrayDataProvider([
                'allModels' => $arrayData,
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);
        }

        // VarDumper::dump($toret, $depth = 10, $highlight = true);
        return $toret;
    }

    private function getInscriptionQuery()
    {
        Yii::$app->db->pdo->exec("SET lc_time_names = 'it_IT';");
        Yii::$app->db->pdo->exec("SET @rank=-1;");

        $query  = new Query();
        // SET lc_time_names = 'it_IT';
        $query->from('user_profile')
            ->innerJoin('user', 'user.id = user_profile.user_id')
            ->innerJoin('auth_assignment auth', 'auth.user_id = user.id')
            ->where(['user_profile.deleted_at' => null])
            ->andWhere(['user.deleted_at' => null])
            ->andWhere(['user_profile.attivo' => 1])
            ->andWhere(['user.status' => 10])
            ->andWhere(['user_profile.privacy' => 1])
            ->andWhere(['auth.item_name' => 'PC_REGISTERD_USER']);
        $query->select(new Expression("
                @rank:=@rank+1 AS rank
                , count(user.id) number
                , YEAR(user.created_at) year
                , MONTH(user.created_at) month
                , CONCAT(YEAR(user.created_at), \" - \", MONTHNAME(user.created_at)) AS name
            "));

        $query->orderBy('user.created_at');
        $query->groupBy('year, month');

        return $query;
    }

    private function getUninscriptionQuery()
    {
        Yii::$app->db->pdo->exec("SET lc_time_names = 'it_IT';");
        Yii::$app->db->pdo->exec("SET @rank=-1;");

        $query  = new Query();
        // SET lc_time_names = 'it_IT';
        $query->from('user_profile')
            ->innerJoin('user', 'user.id = user_profile.user_id')
            ->innerJoin('auth_assignment auth', 'auth.user_id = user.id')
            ->where(['user_profile.deleted_at' => null])
            ->andWhere(['user.deleted_at' => null])
            ->andWhere(['NOT', ['user_profile.pc_request_delete_date' =>  null]])
            ->andWhere(['auth.item_name' => 'PC_REGISTERD_USER']);
        $query->select(new Expression("
                @rank:=@rank+1 AS rank
                , count(user.id) number
                , YEAR(user.updated_at) year
                , MONTH(user.updated_at) month
                , CONCAT(YEAR(user.updated_at), \" - \", MONTHNAME(user.updated_at)) AS name
            "));

        $query->orderBy('user.updated_at');
        $query->groupBy('year, month');

        return $query;
    }


    public function statInscriptions($params)
    {
        $toret = [
            'graf-data' => [],
            'data-provider' => []
        ];

        if (!($this->load($params) && $this->validate())) {
            return $toret;
        }

        $query = null;
        $colName = null;
        if ($this->user_status_id == 1) {
            $query = $this->getInscriptionQuery();
            $colName = 'Numero iscrizioni';
        }

        if ($this->user_status_id == 2) {
            $query = $this->getUninscriptionQuery();
            $colName = 'Numero disiscrizioni';
        }

        if (is_null($query)) {
            return $toret;
        }

        // $query->groupBy('target.id, user_profile.provincia_residenza_id');
        $queryResult = $query->all();

        $data = ArrayHelper::map($queryResult, 'name', 'number');
        $arrayData = [];
        foreach ($data as $key => $value) {
            $arrayData[] =  [$key, intVal($value)];
        }
        $toret['graf-data'] = array_merge([0 => ['mesi', $colName]], $arrayData);
        $toret['data-provider'] = new ArrayDataProvider([
            'allModels' => $arrayData,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $toret;
    }

    public function statErrors()
    {
        $toret = null;
        $channels = PreferenceCampainChannelMm::findAll([
            'status' => PreferenceCampainChannelMm::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW_STATUS_SENT,
            'preference_channel_id' => PreferenceChannel::NEWSLETTER_ID,
        ]);

        // $queryString = [];
        $q = null;
        /** @var PreferenceCampainChannelMm $channel */
        foreach ($channels as $channel) {
            if (!is_null(Yii::$app->dbstats->schema->getTableSchema(TableMessageBouncesUtility::getTableName($channel->id), true))) {
                if (is_null($q)) { 
                    $q = new Query();                  
                    $q->from(TableMessageBouncesUtility::getTableName($channel->id));
                    $q->select(new Expression("*"));
                } else {
                    $q->union('SELECT * FROM ' . TableMessageBouncesUtility::getTableName($channel->id));
                }
            }
        }
        if (is_null($q)) {
            return $toret;
        }
        $query  = new Query();
        $query->from(['bounced' => $q]);
        $query->innerJoin('preference_user_target_attribute attribute', 'attribute.email = (bounced.email COLLATE utf8_unicode_ci)');
        $query->innerJoin('user', 'user.id = attribute.user_id');
        $query->innerJoin('user_profile', 'user_profile.user_id = user.id');
        $query->innerJoin('tag', 'tag.codice COLLATE utf8_unicode_ci = attribute.target_code COLLATE utf8_unicode_ci');
        $query->where(['attribute.deleted_at' => null]);
        $query->groupBy('email');
        $query->select(new Expression("user_profile.nome name, user_profile.cognome surname, user.email as registration_email, tag.nome target, bounced.*"));
        $queryResult = $query->all();
        // VarDumper::dump($queryResult, $depth = 10, $highlight = true);

        $toret = new ArrayDataProvider([
            'allModels' => $queryResult,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $toret;
    }
}
