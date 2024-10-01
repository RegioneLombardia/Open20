<?php

namespace frontend\modules\metrics\models;

use app\modules\cms\models\NavItem;
use open20\amos\news\models\News;
use frontend\modules\metrics\Module;
use Mpdf\Tag\Q;
use yii\base\Model;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

class Operators extends Model
{
    const NEWS_PUBBLISHED_STATUS = 'NewsWorkflow/VALIDATO';
    const NEWS_TO_PUBLISH_STATUS = 'NewsWorkflow/DAVALIDARE';
    const IMAGES_EXTENSIONS = ['png','jpg','jpeg','svg'];
    const ATTACHMENTS_EXTENSIONS = ['doc','docx','pdf','xls','txt','xlsx','zip'];
    const OPERATORS_FILTER_PARAMS = [
        'redattore' => [
            'code' => 'redattore',
            'label' => 'Redattore',
        ],
        'caporedattore' => [
            'code' => 'caporedattore',
            'label' => 'Caporedattore',
        ],
    ];
    const TYPES_LABELING = [
        'notizie' => [
            'singular' => 'Notizia',
            'plural' => 'Notizie',
            'pluralCreationLabel' => 'notizie create',
            'singularCreationLabel' => 'notizia creata',
        ],
        'pagine' => [
            'singular' => 'Pagina',
            'plural' => 'Pagine',
            'pluralCreationLabel' => 'pagine create',
            'singularCreationLabel' => 'pagina creata',
        ],
        'immagini' => [
            'singular' => 'Immagine',
            'plural' => 'Immagini',
            'pluralCreationLabel' => 'immagini create',
            'singularCreationLabel' => 'immagine creata',
        ],
        'allegati' => [
            'singular' => 'Allegato',
            'plural' => 'Allegati',
            'pluralCreationLabel' => 'allegati create',
            'singularCreationLabel' => 'allegato creato',
        ],
    ];
    const TYPES_CLASSMAP = [
        'notozie' => 'open20\amos\news\models\News',
        'pagine' => 'open20\amos\news\models\News',
        'immagini' => 'open20\amos\news\models\News',
        'allegati' => 'open20\amos\news\models\News',
    ];

    /**
     * @var string $start_operators_date
     */
    public $start_operators_date;
    /**
     * @var string $end_operators_date
     */
    public $end_operators_date;

    /**
     * @var string $start_contents_date
     */
    public $start_contents_date;

    /**
     * @var string $end_contents_date
     */
    public $end_contents_date;

    /**
     * @var
     */
    public $content_text;

    /**
     * @var
     */
    public $content_tag;

    /**
     * @var string $operator_type
     */
    public $operator_type;

    /**
     * @var string $content_type
     */
    public $content_type;
    /**
     * @var array $operators
     */
    public $operators;

    private $operatorsToSelect= [];

    public function rules()
    {
        return [
            [['operator_type', 'operators', 'start_operators_date', 'end_operators_date','start_contents_date'
                , 'end_contents_date','content_type', 'content_tag', 'content_text'], 'safe'],

            ['end_operators_date', 'compare', 'compareAttribute' => 'start_operators_date', 'operator' => '>=', 'when' => function($model) {
                return $model->start_operators_date != null && $model->end_operators_date != null;
            }, 'whenClient' => "function (attribute, value) {
                return $('#start_operators_date').val() != '' && $('#end_operators_date').val() != '';
            }", 'message' => 'Per favore, inserisci una data di inizio ricerca antecedente alla data di fine ricerca'],

            ['end_contents_date', 'compare', 'compareAttribute' => 'start_contents_date', 'operator' => '>=', 'when' => function($model) {
                return $model->start_contents_date != null && $model->end_contents_date != null;
            }, 'whenClient' => "function (attribute, value) {
                return $('#start_contents_date').val() != '' && $('#end_contents_date').val() != '';
            }", 'message' => 'Per favore, inserisci una data di inizio ricerca antecedente alla data di fine ricerca'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'operator_type' => Module::t('metrics', 'Ruolo'),
            'operators' => Module::t('metrics', 'nominativo'),
            'start_operators_date' => Module::t('metrics', 'Da'),
            'end_operators_date' => Module::t('metrics', 'A'),
            'start_contents_date' => Module::t('metrics', 'Da'),
            'end_contents_date' => Module::t('metrics', 'A'),
            'content_text' => Module::t('metrics', 'Testo'),
            'content_tag' => Module::t('metrics', 'Tag'),
            'content_type' => Module::t('metrics', 'Tipologia'),
        ];
    }


    /**
     * @return string[]
     */

    public function getOperatoreTypeChoices()
    {
        return ArrayHelper::map(self::OPERATORS_FILTER_PARAMS, 'code','label');
    }

    /**
     * @return string[]
     */

    public function getContentTypeChoices()
    {
        return [
            'notizie' => 'Notizie',
            'pagine' => 'Pagine',
            'immagini' => 'Immagini',
            'allegati' => 'Allegati',
        ];
    }

    private function mergeAll(array ...$arrays) {
        $toret = [];
        foreach ($arrays as $content) {
            foreach ($content as $contentValue) {
                if (empty($toret[$contentValue['user_id']])) {
                    $toret[$contentValue['user_id']] = $contentValue;
                } else {
                    // gli attivi li sommo!
                    $toret[$contentValue['user_id']]['active'] += $contentValue['active'];
                }

                // se trovo colonne nuove le aggiungo
                foreach (array_keys($contentValue) as $key) {
                    if (!isset($toret[$contentValue['user_id']][$key])) {
                        $toret[$contentValue['user_id']][$key] = $contentValue[$key];
                    }
                }

            }
        }
        return $toret;
    }



    public function statsOperators($params)
    {
        $contentTypes = array_keys($this->getContentTypeChoices());

        if (!($this->load($params) && $this->validate())) {
            // non controllo i tipi di dato, li carico tutti
            // ------------------ NEWS
            $query = $this->operatorsQueryForNews(array_keys(self::OPERATORS_FILTER_PARAMS));
            $news = $query->all();
            // ------------------ NEWS

            // ------------------ PAGES
            $query = $this->operatorsQueryForPages(array_keys(self::OPERATORS_FILTER_PARAMS));
            $pages = $query->all();
            // ------------------ PAGES

            // ------------------ IMAGES
            $query = $this->operatorsQueryForImages(array_keys(self::OPERATORS_FILTER_PARAMS));
            $images = $query->all();
            // ------------------ IMAGES

            // ------------------ ATTACHMENTS
            $query = $this->operatorsQueryForAttachments(array_keys(self::OPERATORS_FILTER_PARAMS));
            $attachments = $query->all();
            // ------------------ ATTACHMENTS

            $toret = $this->mergeAll($news,$pages,$images,$attachments);

//            VarDumper::dump($toret,2,1); die;

            $this->operatorsToSelect = array_column($toret,'user_id');
            return $toret;
        }

        if (!empty($this->content_type)){
            $contentTypes = $this->content_type;
        }

        $news = [];
        if (in_array('notizie', $contentTypes)) {
            // ------------------ NEWS
            $query = $this->operatorsQueryForNews($this->operator_type, $this->start_operators_date,
                $this->end_operators_date, $this->operators, $this->start_contents_date, $this->end_contents_date,
                $this->content_text);
            $news = $query->all();
            // ------------------ NEWS
        }
        $pages = [];
        if (in_array('pagine', $contentTypes)) {
            // ------------------ PAGINE
            $query = $this->operatorsQueryForPages($this->operator_type, $this->start_operators_date,
                $this->end_operators_date, $this->operators, $this->start_contents_date, $this->end_contents_date,
                $this->content_text);
            $pages = $query->all();
            // ------------------ PAGINE
        }

        $images = [];
        if (in_array('immagini', $contentTypes)) {
            // ------------------ IMMAGINI
            $query = $this->operatorsQueryForImages($this->operator_type, $this->start_operators_date,
                $this->end_operators_date, $this->operators, $this->start_contents_date, $this->end_contents_date,
                $this->content_text);
            $images = $query->all();
            // ------------------ IMMAGINI
        }

        $attachments = [];
        if (in_array('allegati', $contentTypes)) {
            // ------------------ IMMAGINI
            $query = $this->operatorsQueryForAttachments($this->operator_type, $this->start_operators_date,
                $this->end_operators_date, $this->operators, $this->start_contents_date, $this->end_contents_date,
                $this->content_text);
            $attachments = $query->all();
            // ------------------ IMMAGINI
        }

        \Yii::$app->session->set('metrics-search', $this->attributes);

        $toret = $this->mergeAll($news,$pages,$images,$attachments);
        $this->operatorsToSelect = array_column($toret,'user_id');
        return $toret;
    }


    public function getOperatorsForSelect()
    {
        $query = new Query();
        $query->select(new Expression('user_profile.user_id, user_profile.nome, user_profile.cognome, CONCAT(\'\',if(serial_number IS NULL,\'\',serial_number),if(serial_number IS NULL,\'\',\' - \'),nome,\' \', cognome) as nome_cognome'));
        $query->from(['user_profile']);
        $query->innerJoin('user', 'user.id = user_profile.user_id');
        $query->groupBy(['user.id']);
        $query->andWhere(['user.id' => $this->operatorsToSelect]);
        $allUser = $query->all();
        ArrayHelper::multisort($allUser, ['cognome', 'nome'], [SORT_ASC, SORT_ASC]);
        return ArrayHelper::map($allUser, 'user_id', 'nome_cognome');
    }

    public function userContents($users)
    {
        $toret[] = ['Contenuto', 'Numero'];
        /** @var ActiveQuery $query */
        $queryNews = News::find();
        $queryNews->andWhere(['created_by' => $users]);
        $toret[] = ['notizie', intval($queryNews->count())];

        /** @var ActiveQuery $queryPages */
        $queryPages = NavItem::find();
        $queryPages->innerJoin('cms_nav', 'cms_nav.id = cms_nav_item.nav_id');
        $queryPages->innerJoin('admin_user', 'admin_user.id = cms_nav_item.create_user_id');
        $queryPages->innerJoin('user', 'user.email = admin_user.email');
        $queryPages->andWhere(['cms_nav.is_deleted' => 0]);
        $queryPages->andWhere(['user.status' => 10]);
        $queryPages->andWhere(['user.id' => $users]);
        $toret[] = ['pagine', intval($queryPages->count())];

        return $toret;
    }

    public function userContentGraphData($users)
    {
        $toret[] = ['Contenuto', 'Numero'];
        /** @var ActiveQuery $query */

        //controllo sulla scelta nel filtro utente della tipologia di contenuto
        $contentTypes = [];
        if (!empty($this->content_type)){
            $contentTypes = $this->content_type;
        } else {
            $contentTypes = array_keys($this->getContentTypeChoices());
        }

        if (in_array('notizie', $contentTypes)){
            $queryNews = $this->contentNewsQuery($users);
            $queryNews->groupBy('news.id');
            $toret[] = ['notizie', intval($queryNews->count())];
        }

        if (in_array('pagine', $contentTypes)) {
            $queryPage = $this->contentPageQuery($users);
            $queryPage->groupBy('cms_nav_item.id');
            $toret[] = ['pagine', intval($queryPage->count())];
        }

        if (in_array('immagini', $contentTypes)){
            $queryImages = $this->contentImagesQuery($users);
            $queryImages->groupBy(['hash']);
            $toret[] = ['immagini', intval($queryImages->count())];
        }

        if (in_array('allegati', $contentTypes)){
            $queryAttachments = $this->contentAttachmentsQuery($users);
            $queryAttachments->groupBy(['hash']);
            $toret[] = ['allegati', intval($queryAttachments->count())];
        }

        return $toret;
    }

    public function userContentDetail($users)
    {
        $toret = [];

        //controllo sulla scelta nel filtro utente della tipologia di contenuto
        $contentTypes = [];
        if (!empty($this->content_type)){
            $contentTypes = $this->content_type;
        } else {
            $contentTypes = array_keys($this->getContentTypeChoices());
        }
        if (in_array('notizie', $contentTypes)) {
            $queryNews = $this->contentNewsQuery($users);
            $queryNews->select(new Expression('\'notizie\' as type
                , news.created_at as created_at
                , news.created_by as created_by
                , news.titolo as title
                , news.id as content_id
                '));
            $toret = array_merge($toret, $queryNews->all());
        }

        if (in_array('pagine', $contentTypes)) {
            $queryPage = $this->contentPageQuery($users);
            $queryPage->groupBy('cms_nav_item.id');
            $queryPage->select(new Expression('\'pagine\' as type
                , (from_unixtime(cms_nav_item.timestamp_create)) as created_at
                , user.id as created_by
                , cms_nav_item.title as title
                , cms_nav_item.id as content_id
                '));
            $toret = array_merge($toret, $queryPage->all());
        }

        if (in_array('immagini', $contentTypes)) {
            $queryImages = $this->contentImagesQuery($users);
            $queryImages->groupBy('hash');
            $queryImages->select(new Expression('\'immagini\' as type
                , (from_unixtime(attach_file.date_upload)) as created_at
                , attach_file.created_by as created_by
                , attach_file.name as title
                , attach_file.id as content_id
                '));
            $toret = array_merge($toret, $queryImages->all());
        }

        if (in_array('immagini', $contentTypes)) {
            $queryAttachemnts = $this->contentAttachmentsQuery($users);
            $queryAttachemnts->groupBy('hash');
            $queryAttachemnts->select(new Expression('\'allegati\' as type
                , (from_unixtime(attach_file.date_upload)) as created_at
                , attach_file.created_by as created_by
                , attach_file.name as title
                , attach_file.id as content_id
                '));
            $toret = array_merge($toret, $queryAttachemnts->all());
        }

        ArrayHelper::multisort($toret,['created_at'],[SORT_DESC]);
        return $toret;
    }

    /**
     * @param $users
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function contentNewsQuery($users)
    {
        $queryNews = new Query();
        $queryNews->from('news');
        $queryNews->andWhere(['deleted_at' => null]);
        $queryNews->andWhere(['status' => self::NEWS_PUBBLISHED_STATUS]);
        $queryNews->andWhere(['created_by' => $users]);

        // riapplico il filtro se impostato il parametro di ricerca... non è il massimo applicarlo due volte anche se i contenuti sono già calcolati dalla funzione di ricerca...
        // vedere di portare il risultato delle news in una var globale per poi filtrare in maniera puntuale! va impostata sulla ricerca delle news in questo caso...
        if (!empty($this->start_contents_date)) {
            $queryNews->andWhere(['>=', new Expression('DATE(news.created_at)'),  new Expression('DATE(\''.$this->start_contents_date.'\')')]);
        }
        if (!empty($this->end_contents_date)) {
            $queryNews->andWhere(['<=', new Expression('DATE(news.created_at)'),  new Expression('DATE(\''.$this->end_contents_date.'\')')]);
        }
        if (!empty($this->content_text)) {
            $queryNews->andWhere(['LIKE', 'news.titolo', $this->content_text]);
        }

        return $queryNews;
    }

    /**
     * @param $users
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function contentImagesQuery($users)
    {
        $queryImages = new Query();
        $queryImages->from('attach_file');
        $queryImages->andWhere(['created_by' => $users]);
        $queryImages->andWhere(['attach_file.type' => self::IMAGES_EXTENSIONS]);


        // riapplico il filtro se impostato il parametro di ricerca... non è il massimo applicarlo due volte anche se i contenuti sono già calcolati dalla funzione di ricerca...
        // vedere di portare il risultato delle news in una var globale per poi filtrare in maniera puntuale! va impostata sulla ricerca delle news in questo caso...
        if (!empty($this->start_contents_date)) {
            $queryImages->andWhere(['>=', new Expression('DATE(from_unixtime(attach_file.date_upload))'),  new Expression('DATE(\''.$this->start_contents_date.'\')')]);
        }
        if (!empty($this->end_contents_date)) {
            $queryImages->andWhere(['<=', new Expression('DATE(from_unixtime(attach_file.date_upload))'),  new Expression('DATE(\''.$this->end_contents_date.'\')')]);
        }
        if (!empty($this->content_text)) {
            $queryImages->andWhere(['LIKE', 'attach_file.name', $this->content_text]);
        }

//        print_r($queryImages->createCommand()->rawSql); die;

        return $queryImages;
    }

    /**
     * @param $users
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function contentAttachmentsQuery($users)
    {
        $queryAttachemnts = new Query();
        $queryAttachemnts->from('attach_file');
        $queryAttachemnts->andWhere(['created_by' => $users]);
        $queryAttachemnts->andWhere(['attach_file.type' => self::ATTACHMENTS_EXTENSIONS]);


        // riapplico il filtro se impostato il parametro di ricerca... non è il massimo applicarlo due volte anche se i contenuti sono già calcolati dalla funzione di ricerca...
        // vedere di portare il risultato delle news in una var globale per poi filtrare in maniera puntuale! va impostata sulla ricerca delle news in questo caso...
        if (!empty($this->start_contents_date)) {
            $queryAttachemnts->andWhere(['>=', new Expression('DATE(from_unixtime(attach_file.date_upload))'),  new Expression('DATE(\''.$this->start_contents_date.'\')')]);
        }
        if (!empty($this->end_contents_date)) {
            $queryAttachemnts->andWhere(['<=', new Expression('DATE(from_unixtime(attach_file.date_upload))'),  new Expression('DATE(\''.$this->end_contents_date.'\')')]);
        }
        if (!empty($this->content_text)) {
            $queryAttachemnts->andWhere(['LIKE', 'attach_file.name', $this->content_text]);
        }

        return $queryAttachemnts;
    }


    public function contentPageQuery($users)
    {
        /** @var ActiveQuery $queryPages */
//        $queryPages = NavItem::find();
        $queryPages = new Query();
        $queryPages->select('cms_nav_item.*');
        $queryPages->from('cms_nav_item');
        $queryPages->innerJoin('cms_nav', 'cms_nav.id = cms_nav_item.nav_id');
        $queryPages->innerJoin('admin_user', 'admin_user.id = cms_nav_item.create_user_id');
        $queryPages->innerJoin('user', 'user.email = admin_user.email');
        $queryPages->andWhere(['cms_nav.is_deleted' => 0]);
        $queryPages->andWhere(['cms_nav.is_offline' => 0]);
        $queryPages->andWhere(['user.status' => 10]);
        $queryPages->andWhere(['NOT',['user.id'=>[1]]]); // NO ADMIN
        $queryPages->andWhere(['user.id' => $users]);

        if (!empty($this->start_contents_date)) {
            $queryPages->andWhere(['>=', new Expression('DATE(from_unixtime(cms_nav_item.timestamp_create))'),  new Expression('DATE(\''.$this->start_contents_date.'\')')]);
        }
        if (!empty($this->end_contents_date)) {
            $queryPages->andWhere(['<=', new Expression('DATE(from_unixtime(cms_nav_item.timestamp_create))'),  new Expression('DATE(\''.$this->end_contents_date.'\')')]);
        }

        if (!empty($this->content_text)) {
            $queryPages->andWhere(['LIKE', 'cms_nav_item.title', $this->content_text]);
        }

        return $queryPages;
    }

    private function operatorsQueryForNews($roles = null,
                                           $filterStartOperatorsDate = null,
                                           $filterEndOperatorsDate = null,
                                           $operators = null,
                                           $startContentsDate = null,
                                           $endContentsDate = null,
                                           $contentText = null
    )
    {


        if(empty($roles)) {
            $roles = array_keys(self::OPERATORS_FILTER_PARAMS);
        } else {
            foreach ($roles as $operatorType) {
                $types[] = $operatorType;
            }
            $roles = empty($types)? array_keys(self::OPERATORS_FILTER_PARAMS): $types;
        }

        /**
         * Utenti che hanno creato una news pubblicata
         * legame con transition log per definisce redattore attivo a caporedattore attivo
         * questa info è estraibili solo dividendo in due query separate per far match-are il fatto che sia un redattore ad aver messo in pubblicazione
         * oppure un caporedattore ad avere messo in pubblicazione il contenuto
         * (PARLO SEMPRE DELL'insieme di utenti che hanno creato un contenuto)
         */
        $query = new Query();
        $query->from('user');
        // (SE un giorno si voglio includere anche gli utenti che sono intervenuti sul WORKFLOW della news è da valutare una con dizione di ON in OR con il log)
        // tipo 'user.id = news.created_by OR user.id =log.created_by'
        // ma questa cambierebbe la segmentazione definita!
        $query->innerJoin('news', 'user.id = news.created_by'); // condizione di segmentazione
        $query->leftJoin('amos_workflow_transitions_log log', 'log.owner_primary_key = news.id');
        $query->innerJoin('user_profile', 'user_profile.user_id = user.id');
        $query->innerJoin('user_profile_classes_user_mm', 'user.id = user_profile_classes_user_mm.user_id');
        $query->innerJoin('user_profile_classes', 'user_profile_classes_user_mm.user_profile_classes_id = user_profile_classes.id');
        $query->andWhere(['user.deleted_at' => null]);
        $query->andWhere(['user_profile.deleted_at' => null]);
        $query->andWhere(['log.classname' => 'open20\\amos\\news\\models\\News']);
        $query->andWhere(['news.deleted_at' => null]);
        $query->andWhere(['news.status' => self::NEWS_PUBBLISHED_STATUS]); // condizione di segmentazione
        $query->andWhere(['OR',
            ['NOT', ['user_profile.start_service_date' => null]],
            ['NOT', ['user_profile.end_service_date' => null]]
        ]);
        $query->andWhere(['NOT',['user.id'=>[1]]]);

        $query->groupBy(['user.id']);

        if (!empty($operators)) {
            $query->andWhere(['user.id' => $operators]);
        }

        if (!empty($filterStartOperatorsDate)) {
            $query->andWhere(['>=', new Expression('DATE(user_profile.end_service_date)'),  new Expression('DATE(\''.$filterStartOperatorsDate.'\')')]);
        }
        if (!empty($filterEndOperatorsDate)) {
            $query->andWhere(['<=', new Expression('DATE(user_profile.start_service_date)'),  new Expression('DATE(\''.$filterEndOperatorsDate.'\')')]);
        }

        if (!empty($startContentsDate)) {
            $query->andWhere(['>=', new Expression('DATE(news.created_at)'),  new Expression('DATE(\''.$startContentsDate.'\')')]);
        }
        if (!empty($endContentsDate)) {
            $query->andWhere(['<=', new Expression('DATE(news.created_at)'),  new Expression('DATE(\''.$endContentsDate.'\')')]);
        }

        if (!empty($contentText)) {
            $query->andWhere(['LIKE', 'news.titolo', $contentText]);
        }

        $unionQueries = [];
        foreach ($roles as $code) {
            $uq = clone $query;

            switch ($code) {
                case 'caporedattore':
                    $uq->select(new Expression('COUNT(IF(log.end_status = \''.self::NEWS_PUBBLISHED_STATUS.'\',1,NULL)) as active
                        , user_profile.nome
                        , user_profile.cognome, user_profile.codice_fiscale, user.email as email, user.id as user_id, user_profile.ultimo_accesso
                        , user_profile_classes.name as profile
                        , user_profile_classes.code as profile_code
                        , user_profile.start_service_date as data_inizio_servizio
                        , user_profile.end_service_date as data_fine_servizio
                        , user_profile.serial_number as matricola
                        , GROUP_CONCAT(DISTINCT(news.id)) as notizie
                        , COUNT(DISTINCT(news.id)) as notizie_number
                        '));
                    break;
                case 'redattore': // redattore basato su
                    $uq->select(new Expression('COUNT(IF(log.end_status = \''.self::NEWS_TO_PUBLISH_STATUS.'\',1,NULL)) as active
                        , user_profile.nome
                        , user_profile.cognome, user_profile.codice_fiscale, user.email as email, user.id as user_id, user_profile.ultimo_accesso
                        , user_profile_classes.name as profile
                        , user_profile_classes.code as profile_code
                        , user_profile.start_service_date as data_inizio_servizio
                        , user_profile.end_service_date as data_fine_servizio
                        , user_profile.serial_number as matricola
                        , GROUP_CONCAT(DISTINCT(news.id)) as notizie
                        , COUNT(DISTINCT(news.id)) as notizie_number
                        '));
                    break;
            }
            $uq->andWhere(['user_profile_classes.code' => $code]);
            $unionQueries[] = clone $uq;
        }

        /** @var ActiveQuery[] $unionQuery */
        $unionQuery = null;
        foreach ($unionQueries as $q) {
            if (is_null($unionQuery)){
                $unionQuery = $q;
            } else {
                $unionQuery->union($q);
            }
        }
//        print_r($unionQuery->createCommand()->rawSql); die;
        return $unionQuery;
    }


    private function operatorsQueryForPages($roles = null,
                                           $filterStartOperatorsDate = null,
                                           $filterEndOperatorsDate = null,
                                           $operators = null,
                                           $startContentsDate = null,
                                           $endContentsDate = null,
                                           $contentText = null
    )
    {


        if(empty($roles)) {
            $roles = array_keys(self::OPERATORS_FILTER_PARAMS);
        } else {
            foreach ($roles as $operatorType) {
                $types[] = $operatorType;
            }
            $roles = empty($types)? array_keys(self::OPERATORS_FILTER_PARAMS): $types;
        }

        $query = new Query();
        $query->from('cms_nav_item');
        $query->innerJoin('cms_nav', 'cms_nav.id = cms_nav_item.nav_id');
        $query->innerJoin('admin_user', 'admin_user.id = cms_nav_item.create_user_id'); // condizione di segmentazione
        $query->innerJoin('user', 'user.email = admin_user.email');
        $query->leftJoin('cms_wf_request', 'cms_wf_request.nav_id = cms_nav.id AND cms_wf_request.nav_item_id = cms_nav_item.id');

        $query->innerJoin('user_profile', 'user_profile.user_id = user.id');
        $query->innerJoin('user_profile_classes_user_mm', 'user.id = user_profile_classes_user_mm.user_id');
        $query->innerJoin('user_profile_classes', 'user_profile_classes_user_mm.user_profile_classes_id = user_profile_classes.id');
        $query->andWhere(['user.deleted_at' => null]);
        $query->andWhere(['user_profile.deleted_at' => null]);
        $query->andWhere(['cms_nav.is_deleted' => 0]);
        $query->andWhere(['cms_nav.is_offline' => 0]); // condizione di segmentazione
        $query->andWhere(['user.status' => 10]);
        $query->andWhere(['OR',
            ['NOT', ['user_profile.start_service_date' => null]],
            ['NOT', ['user_profile.end_service_date' => null]]
        ]);
        $query->andWhere(['NOT',['user.id'=>[1]]]);

        $query->groupBy(['user.id']);

        if (!empty($operators)) {
            $query->andWhere(['user.id' => $operators]);
        }

        if (!empty($filterStartOperatorsDate)) {
            $query->andWhere(['>=', new Expression('DATE(user_profile.end_service_date)'),  new Expression('DATE(\''.$filterStartOperatorsDate.'\')')]);
        }
        if (!empty($filterEndOperatorsDate)) {
            $query->andWhere(['<=', new Expression('DATE(user_profile.start_service_date)'),  new Expression('DATE(\''.$filterEndOperatorsDate.'\')')]);
        }

        if (!empty($startContentsDate)) {
            $query->andWhere(['>=', new Expression('DATE(from_unixtime(cms_nav_item.timestamp_create))'),  new Expression('DATE(\''.$startContentsDate.'\')')]);
        }
        if (!empty($endContentsDate)) {
            $query->andWhere(['<=', new Expression('DATE(from_unixtime(cms_nav_item.timestamp_create))'),  new Expression('DATE(\''.$endContentsDate.'\')')]);
        }

        if (!empty($contentText)) {
            $query->andWhere(['LIKE', 'cms_nav_item.title', $contentText]);
        }

        $unionQueries = [];
        foreach ($roles as $code) {
            $uq = clone $query;

            switch ($code) {
                case 'caporedattore':
                    $uq->select(new Expression('COUNT(IF(cms_wf_request.processed_by_user = admin_user.id, 1, NULL)) as active
                        , user_profile.nome
                        , user_profile.cognome, user_profile.codice_fiscale, user.email as email, user.id as user_id, user_profile.ultimo_accesso
                        , user_profile_classes.name as profile
                        , user_profile_classes.code as profile_code
                        , user_profile.start_service_date as data_inizio_servizio
                        , user_profile.end_service_date as data_fine_servizio
                        , user_profile.serial_number as matricola
                        , GROUP_CONCAT(DISTINCT(cms_nav_item.id)) as pagine
                        , COUNT(DISTINCT(cms_nav_item.id)) as pagine_number
                        '));
                    break;
                case 'redattore': // redattore basato su
                    $uq->select(new Expression('COUNT(IF(cms_wf_request.from_user = admin_user.id, 1, NULL)) as active
                        , user_profile.nome
                        , user_profile.cognome, user_profile.codice_fiscale, user.email as email, user.id as user_id, user_profile.ultimo_accesso
                        , user_profile_classes.name as profile
                        , user_profile_classes.code as profile_code
                        , user_profile.start_service_date as data_inizio_servizio
                        , user_profile.end_service_date as data_fine_servizio
                        , user_profile.serial_number as matricola
                        , GROUP_CONCAT(DISTINCT(cms_nav_item.id)) as pagine
                        , COUNT(DISTINCT(cms_nav_item.id)) as pagine_number
                        '));
                    break;
            }
            $uq->andWhere(['user_profile_classes.code' => $code]);
            $unionQueries[] = clone $uq;
        }

        /** @var ActiveQuery[] $unionQuery */
        $unionQuery = null;
        foreach ($unionQueries as $q) {
            if (is_null($unionQuery)){
                $unionQuery = $q;
            } else {
                $unionQuery->union($q);
            }
        }
//        print_r($unionQuery->createCommand()->rawSql); die;
        return $unionQuery;
    }


    /**
     * IMMAGINI e ALLEGATI saranno MOLTO SIMILI, ma li tengo separati perchè mi aspetto che arrivino richiesta di
     * diversa natura
     * @param $roles
     * @param $filterStartOperatorsDate
     * @param $filterEndOperatorsDate
     * @param $operators
     * @param $startContentsDate
     * @param $endContentsDate
     * @param $contentText
     * @return ActiveQuery[]|Query
     */
    private function operatorsQueryForImages($roles = null,
                                           $filterStartOperatorsDate = null,
                                           $filterEndOperatorsDate = null,
                                           $operators = null,
                                           $startContentsDate = null,
                                           $endContentsDate = null,
                                           $contentText = null
    )
    {


        if(empty($roles)) {
            $roles = array_keys(self::OPERATORS_FILTER_PARAMS);
        } else {
            foreach ($roles as $operatorType) {
                $types[] = $operatorType;
            }
            $roles = empty($types)? array_keys(self::OPERATORS_FILTER_PARAMS): $types;
        }

        $query = new Query();
        $queryAttachFile = new Query();
        $queryAttachFile->from('attach_file');
        $queryAttachFile->select(new Expression('*'));
        $queryAttachFile->groupBy(['hash']);
        $query->from(['attach_file' => $queryAttachFile]);
        $query->innerJoin('user', 'user.id = attach_file.created_by');
        $query->innerJoin('user_profile', 'user_profile.user_id = user.id');
        $query->innerJoin('user_profile_classes_user_mm', 'user.id = user_profile_classes_user_mm.user_id');
        $query->innerJoin('user_profile_classes', 'user_profile_classes_user_mm.user_profile_classes_id = user_profile_classes.id');
        $query->andWhere(['attach_file.type' => self::IMAGES_EXTENSIONS]);
        $query->andWhere(['user.deleted_at' => null]);
        $query->andWhere(['user_profile.deleted_at' => null]);
        $query->andWhere(['OR',
            ['NOT', ['user_profile.start_service_date' => null]],
            ['NOT', ['user_profile.end_service_date' => null]]
        ]);
        $query->andWhere(['NOT',['user.id'=>[1]]]);

        $query->groupBy(['user.id']);

        if (!empty($operators)) {
            $query->andWhere(['user.id' => $operators]);
        }

        if (!empty($filterStartOperatorsDate)) {
            $query->andWhere(['>=', new Expression('DATE(user_profile.end_service_date)'),  new Expression('DATE(\''.$filterStartOperatorsDate.'\')')]);
        }
        if (!empty($filterEndOperatorsDate)) {
            $query->andWhere(['<=', new Expression('DATE(user_profile.start_service_date)'),  new Expression('DATE(\''.$filterEndOperatorsDate.'\')')]);
        }

        if (!empty($startContentsDate)) {
            $query->andWhere(['>=', new Expression('DATE(from_unixtime(attach_file.date_upload))'),  new Expression('DATE(\''.$startContentsDate.'\')')]);
        }
        if (!empty($endContentsDate)) {
            $query->andWhere(['<=', new Expression('DATE(from_unixtime(attach_file.date_upload))'),  new Expression('DATE(\''.$endContentsDate.'\')')]);
        }

        if (!empty($contentText)) {
            $query->andWhere(['LIKE', 'attach_file.name', $contentText]);
        }

        $unionQueries = [];
        foreach ($roles as $code) {
            $uq = clone $query;
            $uq->select(new Expression('1 as active
                        , user_profile.nome
                        , user_profile.cognome, user_profile.codice_fiscale, user.email as email, user.id as user_id, user_profile.ultimo_accesso
                        , user_profile_classes.name as profile
                        , user_profile_classes.code as profile_code
                        , user_profile.start_service_date as data_inizio_servizio
                        , user_profile.end_service_date as data_fine_servizio
                        , user_profile.serial_number as matricola
                        , GROUP_CONCAT(DISTINCT(attach_file.id)) as immagini
                        , COUNT(DISTINCT(attach_file.id)) as immagini_number
                        '));
            $uq->andWhere(['user_profile_classes.code' => $code]);
            $unionQueries[] = clone $uq;
        }

        /** @var ActiveQuery[] $unionQuery */
        $unionQuery = null;
        foreach ($unionQueries as $q) {
            if (is_null($unionQuery)){
                $unionQuery = $q;
            } else {
                $unionQuery->union($q);
            }
        }
//        print_r($unionQuery->createCommand()->rawSql); die;
        return $unionQuery;
    }

    /**
     * IMMAGINI e ALLEGATI saranno MOLTO SIMILI, ma li tengo separati perchè mi aspetto che arrivino richiesta di
     * diversa natura
     * @param $roles
     * @param $filterStartOperatorsDate
     * @param $filterEndOperatorsDate
     * @param $operators
     * @param $startContentsDate
     * @param $endContentsDate
     * @param $contentText
     * @return ActiveQuery[]|Query
     */
    private function operatorsQueryForAttachments($roles = null,
                                           $filterStartOperatorsDate = null,
                                           $filterEndOperatorsDate = null,
                                           $operators = null,
                                           $startContentsDate = null,
                                           $endContentsDate = null,
                                           $contentText = null
    )
    {


        if(empty($roles)) {
            $roles = array_keys(self::OPERATORS_FILTER_PARAMS);
        } else {
            foreach ($roles as $operatorType) {
                $types[] = $operatorType;
            }
            $roles = empty($types)? array_keys(self::OPERATORS_FILTER_PARAMS): $types;
        }

        $query = new Query();
        $queryAttachFile = new Query();
        $queryAttachFile->from('attach_file');
        $queryAttachFile->select(new Expression('*'));
        $queryAttachFile->groupBy(['hash']);
        $query->from(['attach_file' => $queryAttachFile]);
        $query->innerJoin('user', 'user.id = attach_file.created_by');
        $query->innerJoin('user_profile', 'user_profile.user_id = user.id');
        $query->innerJoin('user_profile_classes_user_mm', 'user.id = user_profile_classes_user_mm.user_id');
        $query->innerJoin('user_profile_classes', 'user_profile_classes_user_mm.user_profile_classes_id = user_profile_classes.id');
        $query->andWhere(['attach_file.type' => self::ATTACHMENTS_EXTENSIONS]);
        $query->andWhere(['user.deleted_at' => null]);
        $query->andWhere(['user_profile.deleted_at' => null]);
        $query->andWhere(['OR',
            ['NOT', ['user_profile.start_service_date' => null]],
            ['NOT', ['user_profile.end_service_date' => null]]
        ]);
        $query->andWhere(['NOT',['user.id'=>[1]]]);

        $query->groupBy(['user.id']);

        if (!empty($operators)) {
            $query->andWhere(['user.id' => $operators]);
        }

        if (!empty($filterStartOperatorsDate)) {
            $query->andWhere(['>=', new Expression('DATE(user_profile.end_service_date)'),  new Expression('DATE(\''.$filterStartOperatorsDate.'\')')]);
        }
        if (!empty($filterEndOperatorsDate)) {
            $query->andWhere(['<=', new Expression('DATE(user_profile.start_service_date)'),  new Expression('DATE(\''.$filterEndOperatorsDate.'\')')]);
        }

        if (!empty($startContentsDate)) {
            $query->andWhere(['>=', new Expression('DATE(from_unixtime(attach_file.date_upload))'),  new Expression('DATE(\''.$startContentsDate.'\')')]);
        }
        if (!empty($endContentsDate)) {
            $query->andWhere(['<=', new Expression('DATE(from_unixtime(attach_file.date_upload))'),  new Expression('DATE(\''.$endContentsDate.'\')')]);
        }

        if (!empty($contentText)) {
            $query->andWhere(['LIKE', 'attach_file.name', $contentText]);
        }

        $unionQueries = [];
        foreach ($roles as $code) {
            $uq = clone $query;
            $uq->select(new Expression('1 as active
                        , user_profile.nome
                        , user_profile.cognome, user_profile.codice_fiscale, user.email as email, user.id as user_id, user_profile.ultimo_accesso
                        , user_profile_classes.name as profile
                        , user_profile_classes.code as profile_code
                        , user_profile.start_service_date as data_inizio_servizio
                        , user_profile.end_service_date as data_fine_servizio
                        , user_profile.serial_number as matricola                        
                        , GROUP_CONCAT(attach_file.id) as allegati
                        , COUNT(attach_file.id) as allegati_number
                        '));
            $uq->andWhere(['user_profile_classes.code' => $code]);
            $unionQueries[] = clone $uq;
        }

        /** @var ActiveQuery[] $unionQuery */
        $unionQuery = null;
        foreach ($unionQueries as $q) {
            if (is_null($unionQuery)){
                $unionQuery = $q;
            } else {
                $unionQuery->union($q);
            }
        }
//        print_r($unionQuery->createCommand()->rawSql); die;
        return $unionQuery;
    }

    /**
     * @param $params
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function getMessageSearchByQParams($excludeType)
    {
//        VarDumper::dump($params,3,1);

        $contentsAttributes = [
            1 => ['name' => 'start_contents_date', 'label' => 'dal', 'type' => 'date'],
            2 => ['name' => 'end_contents_date', 'label' => 'al', 'type' => 'date'],
            3 => ['name' => 'content_type', 'label' => 'tipologia:', 'type' => 'array'],
            4 => ['name' => 'content_text', 'label' => 'testo:', 'type' => 'string'],
//            'content_tag',
        ];

        $contentString = '';

        foreach ($contentsAttributes as $attribute) {
            $attrName = $attribute['name'];

            if ($excludeType == $attribute['name']) continue;

            if (isset($this->$attrName) && !empty($this->$attrName)) {

                if (empty($contentString)) {
                    $contentString .= '<b>' . 'CONTENUTI:' . '</b>';
                }

                switch ($attribute['type']) {
                    case 'date':
                        $contentString .= ' <b>' . $attribute['label'] . '</b>';
                        $contentString .= ' ' . \Yii::$app->formatter->asDate($this->$attrName, 'php:d/m/Y');
                        break;
                    case 'array':
                        $contentString .= ' <b>' . $attribute['label'] . '</b>';
                        $contentString .= ' ' . implode(', ', $this->$attrName);
                        break;
                    case 'string':
                        $contentString .= ' <b>' . $attribute['label'] . '</b>';
                        $contentString .= ' ' . $this->$attrName;
                        break;
                }
            }


        }
        $toret['contents'] = $contentString;

        $operatorsAttributes = [
            1 => ['name' => 'start_operators_date', 'label' => 'dal', 'type' => 'date'],
            2 => ['name' => 'end_operators_date', 'label' => 'al', 'type' => 'date'],
            3 => ['name' => 'operator_type', 'label' => 'ruolo:', 'type' => 'array'],
            4 => ['name' => 'operators', 'label' => 'nominativi:', 'type' => 'userprofiles'],
//            'content_tag',
        ];
        $operatorString = '';
        foreach ($operatorsAttributes as $attribute) {

            if ($excludeType == $attribute['name']) continue;

            $attrName = $attribute['name'];
            if (isset($this->$attrName) && !empty($this->$attrName)) {

                if (empty($operatorString)) {
                    $operatorString .= '<b>' . 'OPERATORI:' . '</b>';
                }

                switch ($attribute['type']) {
                    case 'date':
                        $inServizioLabel = 'in servizio';
                        if (strpos($operatorString, $inServizioLabel) === false) {
                            $operatorString .=  ' ' . $inServizioLabel;
                        }
                        $operatorString .= ' <b>' . $attribute['label'] . '</b>';
                        $operatorString .= ' ' . \Yii::$app->formatter->asDate($this->$attrName, 'php:d/m/Y');
                        break;
                    case 'array':
                        $operatorString .= ' <b>' . $attribute['label'] . '</b>';
                        $operatorString .= ' ' . implode(', ', $this->$attrName);
                        break;
                    case 'string':
                        $operatorString .= ' <b>' . $attribute['label'] . '</b>';
                        $operatorString .= ' ' . $this->$attrName;
                        break;
                    case 'userprofiles':
                        $operatorString .= ' <b>' . $attribute['label'] . '</b>';
                        $names = [];
                        foreach ($this->$attrName as $userId) {
                            $up = \open20\amos\admin\models\UserProfile::findOne(['user_id' => $userId]);
                            if ($up) {
                                $names[] = $up->getNomeCognome();
                            }
                        }
                        $operatorString .= ' ' . implode(', ', $names);
                        break;
                }
            }
        }

        $toret['operators'] = $operatorString;

        return $toret;
    }

    public function exportUserAndContent(array $result, string $content)
    {
        $toret = [];
        if (!empty($result)) {
            foreach ($result as $item) {
                if (isset($item[$content]) && !empty($item[$content])) {
                    $ids = explode(',', $item[$content]);
                    $query = new Query();
                    switch ($content) {
                        case 'notizie':
                            $query->from('news');
                            $query->andWhere(['id' => $ids]);
                            $query->select(new Expression('\'' . $content . '\' as content_type, titolo as content_title, created_at as content_creation'));
                            break;

                        case 'pagine':
                            $query->from('cms_nav');
                            $query->innerJoin('cms_nav_item', 'cms_nav_item.nav_id = cms_nav.id');
                            $query->andWhere(['cms_nav.id' => $ids]);
                            $query
                                ->select(new Expression('\'' . $content . '\' as content_type, cms_nav_item.title as content_title, (from_unixtime(cms_nav_item.timestamp_create)) as content_creation'));
                            break;
                        case 'immagini':
                        case 'allegati':
                            $query->from('attach_file');
                            $query->andWhere(['id' => $ids]);
                            $query->select(new Expression('\'' . $content . '\' as content_type, name as content_title, (from_unixtime(date_upload)) as content_creation'));
                            break;

                    }
                    $contentResult = $query->all();

                    foreach ($contentResult as $contentValue) {
                        $toret[] = array_merge($item, $contentValue);
                    }
                }
            }
        }

        return $toret;
    }

}