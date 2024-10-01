<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\mobile\bridge\modules\v1\controllers
 * @category   CategoryName
 */

namespace open20\amos\mobile\bridge\modules\v1\controllers;

use DateTime;
use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use open20\amos\admin\models\UserProfileAgeGroup;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\comuni\models\IstatComuni;
use open20\amos\comuni\models\IstatNazioni;
use open20\amos\comuni\models\IstatProvince;
use open20\amos\core\models\ModelsClassname;
use open20\amos\core\record\CachedActiveQuery;
use open20\amos\core\user\User;
use open20\amos\events\AmosEvents;
use open20\amos\events\models\EventInvitation;
use open20\amos\events\models\EventLanding;
use open20\amos\events\models\Event;
use open20\amos\events\models\EventCategory;
use open20\amos\events\models\EventLocation;
use open20\amos\admin\models\UserOtpCode;
use open20\amos\events\models\EventType;
use open20\amos\events\utility\EventsUtility;
use open20\amos\mobile\bridge\models\RegisterUserEventsAria;
use open20\amos\mobile\bridge\Module;
use open20\amos\mobile\bridge\modules\v1\utility\EventUtility;
use open20\amos\notificationmanager\models\NotificationConf;
use open20\amos\tag\models\EntitysTagsMm;
use open20\amos\tag\models\Tag;
use Exception;
use Yii;
use yii\db\Expression;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\log\Logger;
use open20\amos\core\controllers\AmosController;
use yii\web\Response;

class EventsAriaController extends DefaultController
{

    public function behaviors()
    {
        $behaviours = parent::behaviors();

        $behaviours = ArrayHelper::merge($behaviours,
            [
                'verbFilter' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'events-list' => ['get'],
                        'events-search' => ['get'],
                        'event-detail' => ['get'],
                        'event-categories' => ['get'],
                        'form-fields' => ['get'],
                        'cities-from-contry' => ['get'],
                        'select-values' => ['get'],
                        'countries' => ['get'],
                        'preference-tags' => ['get'],
                        'states' => ['get'],
                        'user-datas' => ['get'],
                        'register-user' => ['post'],
                        'is-registered-to-event' => ['get'],
                        'unsubscribe' => ['get'],
                        'update-user-profile' => ['post'],
                        'update-notification-conf' => ['post'],
                        'notification-conf' => ['get'],
                        'event-children' => ['get'],
                        'periods-by-date' => ['get'],
                        'event-landing' => ['get'],
                        'modify-email' => ['post'],
                    ],
                ],
            ]);

        $behaviours['pageCache'] = EventUtility::mobileCacheConfigs();
        return $behaviours;

    }

    /**
     * @param \yii\base\Action $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        if (\Yii::$app->request->get('language')) {
            \Yii::$app->language = \Yii::$app->request->get('language');
        } else {
            \Yii::$app->language = 'it-IT';
        }
        return parent::beforeAction($action);
    }


    /**
     * @param null $offset
     * @param null $limit
     * @param null $from_date
     * @param null $to_date
     * @param null $text
     * @param null $category
     * @param string $order
     * @param bool $highlighted
     * @param string $informative_tags
     * @return array
     */
    public function actionEventsList(
        $offset = null, $limit = null, $from_date = null, $to_date = null,
        $text = null, $category = null, $order = 'ASC', $highlighted = false, $informative_tags = ''
    )
    {

        if (\Yii::$app->request->get('language')) {
            \Yii::$app->language = \Yii::$app->request->get('language');
        }
        $list = [];
        $list_size = 0;
        try {
            $query = Event::find();
            $query->joinWith('eventLocation');
            $query->andWhere(['publish_on_prl' => 1]);
            $query->andWhere(['status' => Event::EVENTS_WORKFLOW_STATUS_PUBLISHED]);
            $query->andWhere(['or', ['event.event_id' => 0], ['event.event_id' => null]]);
            if (!is_null($offset)) {
                $query->offset($offset);
            }
            if (!is_null($limit)) {
                $query->limit($limit);
            }
            $date_cond = "";
            if (!empty($to_date) || !empty($from_date)) {
                if (empty($to_date)) {
                    $to_date = $from_date;
                } elseif (empty($from_date)) {
                    $from_date = $to_date;
                }
                $date_cond = ['>',
                    Event::tableName() . '.begin_date_hour',
                    DateTime::createFromFormat('d-m-Y', $to_date)->modify("+1 day")->format('Y-m-d')];
                $end_cond = ['<',
                    Event::tableName() . '.end_date_hour',
                    DateTime::createFromFormat('d-m-Y', $from_date)->format('Y-m-d')];

                $date_cond = ['not', ['or', $date_cond, $end_cond]];
            }
            if (!empty($date_cond)) {
                $query->andFilterWhere($date_cond);
            }
            if (!empty($category)) {
                $cat = $this->getEventCategoryByDescription($category);
                $query->andFilterWhere(['event_category_id' => $cat->id]);
            }
            if (!empty($text)) {
                $query->andFilterWhere(['or',
                    ['like', 'title', $text],
                    ['like', 'summary', $text],
                    ['like', Event::tableName() . '.description', $text],
                    ['like', EventLocation::tableName() . '.name', $text],
                ]);
            }

            if (!empty($informative_tags)) {
                $tagsPreference = explode(',', $informative_tags);
                $query->leftJoin("entitys_tags_mm as tag_preference", 'tag_preference.record_id = event.id')
                    ->andWhere(['tag_preference.classname' => Event::className()])
                    ->andWhere(['tag_preference.deleted_at' => null])
                    ->andWhere(['tag_preference.tag_id' => $tagsPreference]);
            }

            if ($highlighted) {
                $query->select(new Expression('event.*, IF(n_order is NULL, 9999999, n_order) as n'));
                $query->innerJoin('event_highlights', 'event_highlights.event_id = event.id')
                    ->orderBy("n, event.begin_date_hour $order");
//                print_r($query->createCommand()->rawSql); die;
            } else {
                $query->orderBy(['begin_date_hour' => $order == 'ASC' ? SORT_ASC : SORT_DESC]);
            }

            $cachedQuery = CachedActiveQuery::instance($query);
            $cachedQuery->cache();
            $listModel = $cachedQuery->all();
            foreach ($listModel as $event) {
                $the_event = $this->parseEvent($event);
                $list[] = $the_event;
            }
            if (!is_null($limit)) {
                $query->limit(null);
                $list_size = $query->count();
            } else {
                $list_size = count($list);
            }
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
        }
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return ['size' => $list_size, 'list' => $list];
    }

    /**
     * @param null $offset
     * @param null $limit
     * @param null $from_date
     * @param null $to_date
     * @param null $text
     * @param null $category
     * @param string $order
     * @param bool $highlighted
     * @param string $informative_tags
     * @return array
     */
    public function actionEventsSearch(
        $offset = null, $limit = null, $from_date = null, $to_date = null,
        $text = null, $category = null, $order = 'ASC', $highlighted = false, $informative_tags = ''
    )
    {
        $list = [];
        $list_size = 0;
        try {
            $query = Event::find();
            $query->joinWith('eventLocation');
            $query->andWhere(['publish_on_prl' => 1]);
            $query->andWhere(['status' => Event::EVENTS_WORKFLOW_STATUS_PUBLISHED]);
            if (!is_null($offset)) {
                $query->offset($offset);
            }
            if (!is_null($limit)) {
                $query->limit($limit);
            }
            $date_cond = "";
            if (!empty($to_date) || !empty($from_date)) {
                if (empty($to_date)) {
                    $to_date = $from_date;
                } elseif (empty($from_date)) {
                    $from_date = $to_date;
                }
                $date_cond = ['>',
                    Event::tableName() . '.begin_date_hour',
                    DateTime::createFromFormat('d-m-Y', $to_date)->modify("+1 day")->format('Y-m-d')];
                $end_cond = ['<',
                    Event::tableName() . '.end_date_hour',
                    DateTime::createFromFormat('d-m-Y', $from_date)->format('Y-m-d')];

                $date_cond = ['not', ['or', $date_cond, $end_cond]];
            }
            if (!empty($date_cond)) {
                $query->andFilterWhere($date_cond);
            }
            if (!empty($category)) {
                $cat = $this->getEventCategoryByDescription($category);
                $query->andFilterWhere(['event_category_id' => $cat->id]);
            }
            if (!empty($text)) {
                $query->andFilterWhere(['or',
                    ['like', 'title', $text],
                    ['like', 'summary', $text],
                    ['like', Event::tableName() . '.description', $text],
                    ['like', EventLocation::tableName() . '.name', $text],
                ]);
            }

            if (!empty($informative_tags)) {
                $tagsPreference = explode(',', $informative_tags);
                $query->leftJoin("entitys_tags_mm as tag_preference", 'tag_preference.record_id = event.id')
                    ->andWhere(['tag_preference.classname' => Event::className()])
                    ->andWhere(['tag_preference.deleted_at' => null])
                    ->andWhere(['tag_preference.tag_id' => $tagsPreference]);
            }

            if ($highlighted) {
                $query->select(new Expression('event.*, IF(n_order is NULL, 9999999, n_order) as n'));
                $query->innerJoin('event_highlights', 'event_highlights.event_id = event.id')
                    ->orderBy("n, event.begin_date_hour $order");
            } else {
                $query->orderBy(['begin_date_hour' => $order == 'ASC' ? SORT_ASC : SORT_DESC]);
            }
            $listModel = $query->all();
            foreach ($listModel as $event) {
                $the_event = $this->parseEvent($event);
                $list[] = $the_event;
            }
            if (!is_null($limit)) {
                $query->limit(null);
                $list_size = $query->count();
            } else {
                $list_size = count($list);
            }
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
        }

        \Yii::$app->response->format = Response::FORMAT_JSON;
        return ['size' => $list_size, 'list' => $list];
    }

    /**
     *
     * @param integer $id
     */
    public function actionEventDetail($id)
    {
        $the_event = [];
        try {
            $event = Event::findOne($id);
            if (!is_null($event)) {
                $the_event = $this->parseEvent($event);
            }
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
        }
        return $the_event;
    }

    /**
     *
     * @param mixed $event
     */
    protected function parseEvent($event)
    {
        $formatter = \Yii::$app->formatter;
        $eventLocation = $event->eventLocation;
        $eventPlace = $eventLocation->eventPlaces;
        $highlight = \open20\amos\events\models\EventHighlights::find()->andWhere(['event_id' => $event->id])->orderBy('id DESC')->one();
        $highlights = 0;
        if ($highlight) {
            $highlights = $highlight->n_order;
        }

        //get event tagspreference
        $preferenceTags = $this->getPreferenceTags($event);

        $element = [];
        $element['id'] = $event->id;
        $element['begin_date_hour'] = $formatter->asDatetime($event->begin_date_hour,
            "dd-MM-yyyy HH:mm:ss");
        $element['end_date_hour'] = $formatter->asDatetime($event->end_date_hour,
            "dd-MM-yyyy HH:mm:ss");
        $element['title'] = $event->title;
        $element['programme'] = $event->eventLanding->schedule;
        $element['summary'] = $event->summary;
        $element['category'] = $event->eventCategory->description;
        $element['description'] = $event->description;
        $element['event_location'] = $eventLocation->name;
        $element['event_address'] = $eventPlace->address;
        $element['event_address_house_number'] = $eventPlace->street_number;
        $element['event_address_cap'] = $eventPlace->postal_code;
        $element['city'] = $eventPlace->city;
        $element['province'] = $eventPlace->province;
        $element['country'] = $eventPlace->country;
        $element['highlights'] = $highlights;
        $element['informative_tags'] = $preferenceTags;
        $element['created_at'] = $formatter->asDatetime($event->created_at,
            "dd-MM-yyyy HH:mm:ss");
        $element['created_by'] = $event->created_by;
        $element['updated_at'] = $formatter->asDatetime($event->updated_at,
            "dd-MM-yyyy HH:mm:ss");
        $element['updated_by'] = $event->updated_by;
        $element['deleted_at'] = $formatter->asDatetime($event->deleted_at,
            "dd-MM-yyyy HH:mm:ss");
        $element['deleted_by'] = $event->deleted_by;
        $logo = $event->getEventLogo();
        $element['eventImageUrl'] = (is_null($logo) ? null : $logo->getWebUrl('original',
            true));
        $element['landingUrl'] = $event->getEventUrl();
        $element['linked_events'] = $this->getEvntSons($event);
        return $element;
    }

    /**
     * @param $event
     * @return array
     */
    protected function getPreferenceTags($event)
    {
        $root = Tag::find()->andWhere(['codice' => Event::ROOT_TAG_PREFERENCE_CENTER])->one();
        $preferenceTags = [];
        $tags = Tag::find()
            ->innerJoin('entitys_tags_mm', 'entitys_tags_mm.tag_id = tag.id')
            ->andWhere(['root_id' => $root->id])
            ->andWhere(['record_id' => $event->id])
            ->all();
        foreach ($tags as $tag) {
            $preferenceTags [] = ['id' => $tag->id, 'name' => $tag->nome];
        }
        return $preferenceTags;
    }

    /**
     *
     */
    public function actionEventCategories()
    {
        $categories = EventCategory::find()->select('id , description')->all();
        return Json::encode($categories);
    }

    /**
     *
     * @param type $desription
     * @return EventCategory
     */
    protected function getEventCategoryByDescription($desription)
    {
        $category = EventCategory::find()->andWhere(['description' => $desription])->one();
        return $category;
    }

    /**
     *
     * @param Event $event
     * @return array
     */
    protected function getEvntSons($event)
    {
        $list = [];
        try {
            $query = Event::find();
            $query->andWhere(['publish_on_prl' => 1]);
            $query->andWhere(['event_id' => $event->id]);
            $query->andWhere(['event.status' => Event::EVENTS_WORKFLOW_STATUS_PUBLISHED]);
            $order = 'ASC';

            $query->orderBy(['begin_date_hour' => $order == 'ASC' ? SORT_ASC : SORT_DESC]);
            $listModel = $query->all();
            foreach ($listModel as $event) {
                $the_event = $this->parseEvent($event);
                $list[] = $the_event;
            }
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
        }
        return $list;
    }

    /**
     * @param $event_id
     * @param $user_id
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     *
     * "code" => 1, "message" => "Registrazioni aperte",
     * "code" => 2, "message" => "Registrazioni chiuse",
     * "code" => 3, "message" => "Registrazioni chiuse, i posti sono esauriti",
     * "code" => 4, "message" => "Sei già registrato all'evento.",
     * "code" => 5, "message" => "Disabilitato.",
     * ]
     */
    public function actionFormFields($event_id, $user_id = null)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        if(empty($user_id)){
            $user_id = \Yii::$app->user->id;
        }
        $event = Event::findOne($event_id);
        $valuesAges = [];
        foreach (UserProfileAgeGroup::find()->all() as $age) {
            $valuesAges [$age->id] = $age->age_group;
        }
        $valuesSex = [
            'Maschio' => AmosEvents::t('amosevents', 'Maschio'),
            'Femmina' => AmosEvents::t('amosevents', 'Femmina')
        ];
        $valuesProvince = [];
        foreach (IstatProvince::find()->andWhere(['istat_regioni_id' => 3])->orderBy('nome ASC')->all() as $provincia) {
            $valuesProvince[$provincia->id] = $provincia->nome;
        }
        //preferene tags
        $preferencesTags = EventsUtility::getPreferenceCenterTags();
        $tagsValues = [];
        foreach ($preferencesTags as $tag) {
            $tagsValues [$tag->id] = AmosEvents::t('amosevents', $tag->nome);
        }
        $selectedCompanions = count(EventsUtility::getCompanions($event_id, $user_id));
        $dataCompanions = $event->getListNcompanions();


        $data = [];
        try {
            $event = Event::find()
                ->andWhere(['id' => $event_id])->one();
            if ($event) {
                /** @var  $landing EventLanding */
                $landing = $event->eventLanding;
                $valuesComuni = [];
                // if you have only comune e tyou don't have province , show directly the list of province
                if ($landing->ask_city && !$landing->ask_county) {
                    foreach (IstatComuni::find()->andWhere(['istat_regioni_id' => 3])->orderBy('nome ASC')->all() as $comune) {
                        $valuesComuni[$comune->id] = $comune->nome;
                    }
                }
                $config['isOk'] = true;
                $data ['enable_user_reg'] = $landing->user_name_reg;
                $data ['user_name_reg'] ['name'] = ['visible' => 1, 'required' => 1, 'type' => 'string'];
                $data ['user_name_reg'] ['surname'] = ['visible' => 1, 'required' => 1, 'type' => 'string'];
                $data ['user_name_reg'] ['email'] = ['visible' => 1, 'required' => 1, 'type' => 'email'];
                $data ['user_name_reg'] ['company'] = ['visible' => $landing->ask_company, 'required' => $landing->ask_company_required, 'type' => 'string'];
                $data ['user_name_reg'] ['sex'] = [
                    'visible' => $landing->ask_sex,
                    'required' => $landing->ask_sex_required,
                    'type' => 'select',
                    'values' => $valuesSex
                ];
                $data ['user_name_reg'] ['age'] = [
                    'visible' => $landing->ask_age,
                    'required' => $landing->ask_age_required,
                    'type' => 'select',
                    'values' => $valuesAges
                ];
                $data ['user_name_reg'] ['fiscal_code'] = ['visible' => $landing->ask_fiscal_code, 'required' => $landing->ask_fiscal_code_required, 'type' => 'string'];
                $data ['user_name_reg'] ['telefon'] = ['visible' => $landing->ask_telefon, 'required' => $landing->ask_telefon_required, 'type' => 'string'];
                $data ['user_name_reg'] ['country'] = [
                    'visible' => $landing->ask_county,
                    'required' => $landing->ask_county_required,
                    'type' => 'select',
                    'values' => $valuesProvince,

                ];
                $data ['user_name_reg'] ['city'] = [
                    'visible' => $landing->ask_city,
                    'required' => $landing->ask_city_required,
                    'type' => 'select',
                    'values' => $valuesComuni,
                    'depends' => [
                        'from' => 'country',
                        'api-call' => 'cities-from-contry'
                    ]
                ];
                $data ['enable_social_reg'] = $landing->social_reg;
                $data ['social_reg']['facebook_reg'] = $landing->facebook_reg;
                $data ['social_reg']['twitter_reg'] = $landing->twitter_reg;
                $data ['social_reg']['linkedin_reg'] = $landing->linkedin_reg;
                $data ['social_reg']['google_reg'] = $landing->goolge_reg;
                $data ['social_reg']['spid_cns_reg'] = $landing->spid_cns_reg;
                $data ['preference_tags'] = $tagsValues;


                $data ['companions']['n_companions'] = [
                    'visible' => $event->enable_companions,
                    'type' => 'select',
                    'values' => $dataCompanions,
                    'n_selected_companions' => $selectedCompanions
                ];
                $data ['companions']['enable_companions'] = [
                    'visible' => $event->enable_companions,
                    'type' => 'select',
                    'values' => [1 => AmosEvents::t('amosevents', 'Si'), 0 => AmosEvents::t('amosevents', 'No')]
                ];

                $data ['status'] = $this->getStatusForm($event);
                $config['data'] = $data;
                return $config;
            }

        } catch (Exception $ex) {
            $config['isOk'] = false;
            $config['errors'] = ['message' => $ex->getMessage()];
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
        }

//        \Yii::$app->response->statusCode = 404;
        return $config;
    }


    public function isNotOverflowCompanions($model){

        if($model->enable_companions) {
            $n_companions = \Yii::$app->request->post('n_companions');
            $enable_companions = \Yii::$app->request->post('enable_companions');
            $max_seats = $model->seats_available;
//            echo '-'.$max_seats.'=>max_seats-';
//            echo '-'.$n_companions.'=>n_companions-';
//            echo '-'.$enable_companions.'=>enable_companions-';

            if (!empty($max_seats)) {
                $now_seats = $model->checkParticipantsQuantity();

                if ($enable_companions) {
                    if (!empty($n_companions)) {
                        $nCompanions = $n_companions;
                        $remainingSeats = $max_seats - $now_seats;
                        if (($remainingSeats) >= ($nCompanions + 1)) {
                            return true;
                        }
                        return false;
                    }
                }
            }
            return true;
        }
        return true;
    }


    /**
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function actionSelectValues()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $preferencesTags = EventsUtility::getPreferenceCenterTags();
        $tagsValues = [];
        foreach ($preferencesTags as $tag) {
            $tagsValues [$tag->id] = AmosEvents::t('amosevents', $tag->nome);
        }

        $valuesAges = [];
        foreach (UserProfileAgeGroup::find()->all() as $age) {
            $valuesAges [$age->id] = $age->age_group;
        }
        $valuesSex = [
            'Maschio' => AmosEvents::t('amosevents', 'Maschio'),
            'Femmina' => AmosEvents::t('amosevents', 'Femmina')];
        $valuesProvince = [];
        foreach (IstatProvince::find()->orderBy('nome ASC')->all() as $provincia) {
            $valuesProvince[$provincia->id] = $provincia->nome;
        }

        $valuesNazioni = [];
        $nazioni = IstatNazioni::find()->all();
        foreach ($nazioni as $nazione) {
            $valuesNazioni[$nazione->id] = $nazione->nome;
        }

        $data['isOk'] = true;
        $data['data'] = [
            'preference_tags' => $tagsValues,
            'sex' => $valuesSex,
            'age' => $valuesAges,
            'country' => $valuesProvince,
            'nascita_nazioni_id' => $valuesNazioni
        ];
        return $data;

    }

    /**
     * @return array
     */
    public function actionPreferenceTags()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $preferencesTags = EventsUtility::getPreferenceCenterTags();

        $tagsValues = [];
        foreach ($preferencesTags as $tag) {
            $tagsValues [$tag->id] = AmosEvents::t('amosevents', $tag->nome);
        }

        return [
            'isOk' => true,
            'data' => $tagsValues
        ];
    }

    /**
     * @param $id
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCitiesFromCountry($id)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $data = [];
        try {
            $data['isOk'] = true;
            $valuesComuni = [];
            $comuni = IstatComuni::find()
                ->andWhere(['istat_province_id' => $id])
                ->orderBy('nome ASC')->all();
            foreach ($comuni as $comune) {
                $valuesComuni[$comune->id] = $comune->nome;
            }
        } catch (Exception $ex) {
            $data['isOk'] = false;
            $data['errors'] = ['message' => $ex->getMessage()];
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
        }
        $data['data'] = $valuesComuni;
        return $valuesComuni;
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCountries()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $province = IstatProvince::find()->all();
        foreach ($province as $provincia) {
            $valuesProv[$provincia->id] = $provincia->nome;
        }
        $data['isOk'] = true;
        $data['data'] = $valuesProv;
        return $data;
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function actionStates()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $nazioni = IstatNazioni::find()->all();
        foreach ($nazioni as $nazione) {
            $values[$nazione->id] = $nazione->nome;
        }
        $data['isOk'] = true;
        $data['data'] = $values;
        return $data;

    }


    /**
     * @param $user_id
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function getUserProfileDataFromUserid($user_id)
    {
        $arrayProfile = UserProfile::find()
            ->select('user_profile.id, user_id, nome, cognome, user.email,
                        sesso, user_profile_age_group_id,azienda, telefono, codice_fiscale,
                        nascita_nazioni_id, nascita_province_id, nascita_comuni_id,
                        privacy, privacy_2')
            ->innerJoin('user', 'user_profile.user_id = user.id')
            ->andWhere(['user_id' => $user_id])->asArray()->one();

        $profile = UserProfile::find()->andWhere(['user_id' => $user_id])->one();
        $preferencesTags = \backend\modules\eventsadmin\controllers\UserProfileController::loadTagPreferences($profile);
        $arrayProfile['preference_tags'] = $preferencesTags;
        $urlImage = '';
        if (!empty($profile->userProfileImage)) {
            $urlImage = $profile->userProfileImage->getWebUrl();
        }
        $arrayProfile['urlUserProfileImage'] = \Yii::$app->params['platform']['backendUrl'] . $urlImage;

        // parse attribute
        $parsedArrayProfile = [];
        foreach ($arrayProfile as $attribute => $val) {
            $parsedArrayProfile[self::getParsedAttribute($attribute)] = $val;
        }

        return $parsedArrayProfile;
    }

    /**
     * @return array
     */
    public function actionUserDatas($user_id = null)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $data = [];
        if (empty($user_id)) {
            $user_id = \Yii::$app->user->id;
        }

        try {
            if (!\Yii::$app->user->isGuest) {
                $data['isOk'] = true;
                $arrayProfile = $this->getUserProfileDataFromUserid($user_id);
                $data['data']['UserProfile'] = $arrayProfile;
                return $data;
            }
        } catch (Exception $ex) {
            $data['isOk'] = false;
            $data['errors'] = ['message' => $ex->getMessage()];
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
        }

        return [];
    }


    /**
     * @param $event_id
     * @throws \yii\base\InvalidConfigException
     *
     *  [RegisterUserEventsAria] => [
     *
     * [userSocial] => {"identifier":null,"webSiteURL":null,"profileURL":null,"photoURL":null,"displayName":null,"description":null,"firstName":"John","lastName":"Fergieman","gender":null,"language":null,"age":null,"birthDay":null,"birthMonth":null,"birthYear":null,"email":"michele.lafrancesca+mirror_360_117@open20.it","emailVerified":null,"phone":null,"address":null,"country":null,"region":null,"city":null,"zip":null,"job_title":null,"organization_name":null}
     * [datiRecuperatiDaSocial] => 1
     * [socialScelto] => facebook
     * [associaNuovoAccountSocial] =>
     * [name] => Mario
     * [surname] => Rossi
     * [email] => michele.lafrancesca+mariorossi@open20.it
     * [sex] => Maschio
     * [age] => 4
     * [country] => 97
     * [city] => 97002
     * [telefon] => 235425
     * [fiscal_code] => CODICE_FISCALE
     * [company] => XCVVXC
     * [privacy] => 1
     * [privacy_2] => 1
     * ]
     *
     * [preferences_tags] => [
     * [0] => 3793
     * ]
     */
    public function actionRegisterUser()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        try {
            $result = [];
            $data = [
                'send_mail' => true,
                'from_email' => null,
                'ccn_email' => [],
                'email_subject' => '',
                'email_text' => '',
                'email_waiting_list_text' => ''
            ];

            $register = $this->buildRegisterUser();
            $event = Event::findOne($register->event_id);

            if (!empty($event)) {
                $enableCompanions = \Yii::$app->request->post('enable_companions');
                if($event->enable_companions && !empty($enableCompanions)){
                   if(!$this->isNotOverflowCompanions($event)){
                       $result['isOk'] = false;
                       $result['errors'] = ['n_companions' => Module::t('amosmobilebridge', 'IL numero dei posti è stato superato.')];
                       return $result;
                   }
                }
                $isWaiting = $this->isSeatOverflow($event);
                if ($register->isAlreadyPresent($event)) {
                    $result['isOk'] = false;
                    $result['errors'] = ['email' => Module::t('amosmobilebridge', 'Already registered')];
                    return $result;
                }

                if ($register->validate()) {
                    $user = $register->registerToPlatform($event, $data, $isWaiting);
                    if (!empty($user)) {
                        $result['isOk'] = true;
                        $result['data'] ['UserProfile'] = $this->getUserProfileDataFromUserid($user->id);
                        $result['data'] ['event_id'] = $event->id;
                        $result['data'] ['is_waiting'] = $isWaiting;
                    }
                } else {
                    $result['isOk'] = false;
                    $result['errors'] = $this->formatErrorsStringValidation($register->getErrors());
                }
            } else {
                $result['isOk'] = false;
                $result['errors'] = 'event_id - ' . Module::t('amosmobilebridge', "L'evento non esiste");
            }
        } catch (Exception $ex) {
            $data['isOk'] = false;
            $data['errors'] = ['message' => $ex->getMessage()];
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
        }
        return $result;
    }

    /** $event Event */
    public function isSeatOverflow($event)
    {
        $max_seats = $event->seats_available;
        if (!empty($max_seats)) {
            $now_seats = $event->checkParticipantsQuantity();
            if ($now_seats >= $max_seats) {
                return true;
            }
        }
//        echo ($now_seats.' seats occupati');
//        echo ($max_seats.' massimo posti');
        return false;
    }


    /**
     * @param $event
     * @param $register_on_platform
     * @return RegisterUserEventsAria
     */
    public function buildRegisterUser($register_on_platform = true)
    {
        $register = new RegisterUserEventsAria();

        //parse data
        $postParsed = [];
        $post = \Yii::$app->request->post();

        $postParsed['RegisterUserEventsAria'] = $post['UserProfile'];
        $postParsed['RegisterUserEventsAria']['event_id'] = $post['event_id'];
        $postParsed['RegisterUserEventsAria']['preference_tags'] = $post['preference_tags'];
        $register->load($postParsed);

//        $register->setCommunitID($event->community_id);
        $register->setFacilitatorID(1);
        $register->setSendCredential(false);
        $register->setEmail_after_login_text(null);
        $register->setEmail_layout_after_login(null);
        $register->setEmail_subject_after_login(null);
        $register->setFrom_email(\Yii::$app->params['supportEmail']);
        $register->setEmail_after_login(null);
        $register->setCreate_account_field($register_on_platform);
        $register->setEmail_text_new_account(null);


        return $register;
    }


    /**
     * @param $event
     * @return array
     */
    public function getStatusForm($event)
    {

        $status = [];
        $currentPariticipants = $event->checkParticipantsQuantity();
        if ($event->eventType->event_type == EventType::TYPE_INFORMATIVE) {
            $status ['code'] = 5;
            $status ['message'] = AmosEvents::t('amosevents', "Disabilitato");
        } else if (EventsUtility::isEventRegisteredToEvent($event->id, \Yii::$app->user->id)) {
            $status ['code'] = 4;
            $status ['message'] = AmosEvents::t('amosevents', "Sei già registrato all'evento");
        } else if (!$event->isSubscribtionsOpened()) {
            $status ['code'] = 2;
            $status ['message'] = \Yii::t('amosevents', "Registrazioni chiuse");
        } else if ($event->eventType->limited_seats == true
            && ($currentPariticipants >= $event->seats_available)
            && !$event->manage_waiting_list) {
            $status ['code'] = 3;
            $status ['message'] = AmosEvents::t('amosevents', "Registrazioni chiuse, i posti sono esauriti");
        } else {
            $status ['code'] = 1;
            $status ['message'] = AmosEvents::t('amosevents', "Registrazioni aperte");
        }
        return $status;

    }
    //social user json
    //{"identifier":"113792637134122","webSiteURL":"","profileURL":"","photoURL":"https:\/\/graph.facebook.com\/113792637134122\/picture?width=150&height=150","displayName":"Bob+Aleeajaeiajba+Changsky","description":"","firstName":"Bob","lastName":"Changsky","gender":"","language":"","age":null,"birthDay":null,"birthMonth":null,"birthYear":null,"email":"qjxobedvtz_1600156536@tfbnw.net","emailVerified":"qjxobedvtz_1600156536@tfbnw.net","phone":null,"address":null,"country":null,"region":"","city":null,"zip":null,"job_title":null,"organization_name":null}

    /**
     * @param $event_id
     * @param null $user_id
     * @return mixed
     */
    public function actionUnsubscribe($event_id, $user_id = null)
    {
        if(empty($user_id)) {
            $user_id = \Yii::$app->user->id;
        }
        \Yii::$app->response->format = Response::FORMAT_JSON;
        try {
            $event = Event::findOne($event_id);
            if ($event) {
                $member = CommunityUserMm::find()
                    ->andWhere(['community_id' => $event->community_id])
                    ->andWhere(['user_id' => $user_id])->one();
                if ($member) {
                    $member->delete();
                }
                $invitation = EventInvitation::find()->andWhere(['event_id' => $event->id, 'user_id' => $user_id])->one();
                if ($invitation) {
                    $companions = $invitation->companions;
                    foreach ($companions as $companion){
                        $companion->delete();
                    }
                    $invitation->delete();
                    $result['isOk'] = true;
                    \open20\amos\core\models\UserActivityLog::registerLog(\Yii::t('app', 'Disiscrizione da evento'), $event, Event::LOG_TYPE_UNSUBSCRIBE_EVENT);
                    return $result;
                }
            }
            $result['isOk'] = false;
        } catch (Exception $ex) {
            $data['isOk'] = false;
            $data['errors'] = ['message' => $ex->getMessage()];
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
        }
        return $result;
    }


    /**
     * @param $user_id
     * @return mixed
     */
    public function actionUpdateUserProfile($user_id = null)
    {
        if (is_null($user_id)) {
            $user_id = \Yii::$app->user->id;
        }
        \Yii::$app->response->format = Response::FORMAT_JSON;
        try {
            $result['isOk'] = false;
            $post = Yii::$app->request->post();

            //parse data
            $postParsed = [];
            foreach ($post['UserProfile'] as $attribute => $val) {
                if ($attribute == 'email') {
                    $postParsed['User'] = $val;
                } else {
                    $postParsed['UserProfile'][self::getParsedAttribute($attribute, true)] = $val;
                }
            }

            $profile = UserProfile::findOne($user_id);
            $user = $profile->user;

            if (\Yii::$app->user->can('USERPROFILE_UPDATE', ['model' => $profile])) {
                if ($profile->load($postParsed) && $user->load($postParsed)) {
                    $preferencesTags = $_POST['preference_tags'];
                    $profile->azienda = $postParsed['UserProfile']['azienda'];
                    $profile->privacy_2 = $postParsed['UserProfile']['privacy_2'];

                    if ($profile->validate() && $profile->validate()) {
                        $user->save(false);
                        $profile->save(false);
                        \backend\modules\eventsadmin\utility\EventsAdminUtility::savePreferencesTags($profile, $preferencesTags);
                        $result['isOk'] = true;
                        $result['data']['UserProfile'] = $this->getUserProfileDataFromUserid($user_id);
                    } else {
                        $result['isOk'] = false;
                        $errors = $profile->getErrors();
                        $errors = ArrayHelper::merge($errors, $user->getErrors());
                        $result['errors'] = $this->formatErrorsStringValidation($errors, 2);
                    }
                }
            } else {
                $data['isOk'] = false;
                $data['errors'] = ['message' => Module::t('amosmobilebridge', "Forbidden")];
            }
        } catch (Exception $ex) {
            $data['isOk'] = false;
            $data['errors'] = ['message' => $ex->getMessage()];
            Yii::getLogger()->log($ex->getTraceAsString(), Logger::LEVEL_ERROR);
        }
        return $result;
    }


    /**
     * @return array
     */
    public static function mappingUserProfile()
    {
        return [
            'azienda' => 'company',
            'codice_fiscale' => 'fiscal_code',
            'telefono' => 'telefon',
            'nascita_comuni_id' => 'city',
            'nascita_province_id' => 'country',
            'user_profile_age_group_id' => 'age',
            'sesso' => 'sex',
            'nome' => 'name',
            'cognome' => 'surname',
        ];
    }


    /**
     * @param $profileAttr
     * @param bool $reverse
     * @return mixed
     */
    public static function getParsedAttribute($profileAttr, $reverse = false)
    {
        $map = self::mappingUserProfile();
        if ($reverse) {
            $map = array_flip($map);
        }
        if (!empty($map[$profileAttr])) {
            return $map[$profileAttr];
        }
        return $profileAttr;
    }

    /**
     * @param $user_id
     * @return array
     */
    public function getNotificationConfContent($user_id)
    {
        $data = [];
        $user = User::findOne($user_id);

        //defualt values
        $data['notifications_enabled'] = 1;
        $data['open20\amos\news\models\News']['email'] = 1;
        $data['open20\amos\news\models\News']['push'] = 1;
        $data['open20\amos\discussioni\models\DiscussioniTopic']['email'] = 1;
        $data['open20\amos\discussioni\models\DiscussioniTopic']['push'] = 1;
        $data['open20\amos\documenti\models\Documenti']['push'] = 1;
        $data['open20\amos\documenti\models\Documenti']['email'] = 1;
        $data['open20\amos\sondaggi\models\base\Sondaggi']['push'] = 1;
        $data['open20\amos\sondaggi\models\base\Sondaggi']['email'] = 1;

        if ($user) {
            $notificationConf = NotificationConf::find()->andWhere(['user_id' => $user_id])->one();
            if ($notificationConf) {
                $confContents = $notificationConf->notificationConfContents;
                foreach ($confContents as $conf) {
                    $models_classname = $conf->modelsClassname;
                    $data[$models_classname->module]['email'] = $conf->email;
                    $data[$models_classname->module]['push'] = $conf->push_notification;
                }
                $data['notifications_enabled'] = $notificationConf->notifications_enabled;
            }

        }
        return $data;
    }

    /**
     * @param null $user_id
     * @return array
     */
    public function actionNotificationConf($user_id = null)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $data = [];
        if (is_null($user_id)) {
            $user_id = \Yii::$app->user->id;
        }
        $data = $this->getNotificationConfContent($user_id);
        if (!empty($data)) {
            return [
                'isOk' => true,
                'data' => $data
            ];
        }


        return [
            'isOk' => false,
        ];
    }

    /**
     * @param null $user_id
     */
    public function actionUpdateNotificationConf($user_id = null)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $post = \Yii::$app->request->post();
        $parsedConfigs = [];
        foreach ($post as $content => $config) {
            $modelsClassname = ModelsClassname::find()->andWhere(['module' => $content])->one();
            if ($modelsClassname) {
                $parsedConfigs[$modelsClassname->id]['email'] = $config['email'];
                $parsedConfigs[$modelsClassname->id]['push'] = $config['push'];
            }
        }

        if (is_null($user_id)) {
            $user_id = \Yii::$app->user->id;
        }
        $user = User::findOne($user_id);
        if ($user) {
            $ok = \backend\modules\eventsadmin\controllers\UserProfileController::saveNotificationConf($user, $parsedConfigs, $post['notifications_enabled']);
            if ($ok) {
                return [
                    'isOk' => true,
                    'data' => $this->getNotificationConfContent($user_id)
                ];
            }
        }


        return [
            'isOk' => false
        ];
    }

    /**
     * @param $event_id
     * @param null $user_id
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIsRegisteredToEvent($event_id, $user_id = null)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $event = Event::findOne($event_id);
        if (empty($user_id)) {
            $user_id = \Yii::$app->user->id;
        }
        $count = 0;
        if ($event) {
            $count = CommunityUserMm::find()
                ->andWhere(['community_id' => $event->community_id])
                ->andWhere(['user_id' => $user_id])
                ->andWhere(['status' => CommunityUserMm::STATUS_ACTIVE])->count();

        }
        return $count > 0;


    }

    /**
     * @param $errors
     * @param int $type
     * @return string
     */
    public function formatErrorsStringValidation($errors, $type = 1)
    {
        $model = new RegisterUserEventsAria();
        $modelUserProfile = new UserProfile();
        $stringerror = [];
        foreach ($errors as $attribute => $errs) {
            $labelattribute = '';
            if ($type == 1) {
                $labelattribute = Module::t('amosmbilebridge', $model->getAttributeLabel($attribute)) . ' ';
            } else if ($type == 2) {
                $labelattribute = $modelUserProfile->getAttributeLabel($attribute) . ' ';
            }
            $stringerror[] = $labelattribute . '- ' . implode(';', $errs);
        }
        $implode = implode(" \n ", $stringerror);

//        pr($errors);die;
        return $implode;
    }

    /**
     * @param $id
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function actionEventChildren($event_id)
    {
        /** @var  $model Event */
        $model = Event::findOne($event_id);
        $childrens = [];
        $dates = [];
        if (!is_null($model) && $model->is_father) {
            // children
            $eventsChildren = Event::find()
                ->andWhere(['event_id' => $model->id])
                ->andWhere(['is_time_period' => 0])
                ->andWhere(['status' => Event::EVENTS_WORKFLOW_STATUS_PUBLISHED])
                ->orderBy('begin_date_hour ASC')->all();
            foreach ($eventsChildren as $event) {
                $imageUrl = $event->getMainImageEvent();
                $url = $imageUrl;
                if (strpos($imageUrl, 'https') === false) {
                    $url = Yii::$app->getUrlManager()->createAbsoluteUrl($imageUrl);
                }
                $childrens[] = [
                    'id' => $event->id,
                    'title' => $event->title,
                    'description' => $event->description,
                    'begin_date_hour' => $event->begin_date_hour,
                    'end_date_hour' => $event->end_date_hour,
                    'eventImageUrl' => $url ? $url : null,
                    'urlLanding' => EventsUtility::getUrlLanding($event)
                ];
            }
            //time tables

            $eventsDateArray = Event::find()
                ->select('event.id, begin_date_hour')
                ->andWhere(['event_id' => $model->id])
                ->andWhere(['is_time_period' => 1])
                ->andWhere(['status' => Event::EVENTS_WORKFLOW_STATUS_PUBLISHED])
                ->orderBy('begin_date_hour ASC')
                ->groupBy('event.id, begin_date_hour')->asArray()->all();

            $datesEu = [];
            foreach ($eventsDateArray as $date) {
                $explode = explode(' ', $date['begin_date_hour']);
//                pr($dates, $explode[0]);
                if (!in_array($explode[0], $datesEu)) {
                    $beginDate = new \DateTime($explode[0]);
                    $datesEu[$date['id']] = $beginDate->format('Y-m-d');
                    $dates[$date['id']] = $beginDate->format('d/m/Y');

                }
            }

            return [
                'isOk' => true,
                'children' => $childrens,
                'timePeriods' => $dates
            ];
        }

        return [
            'isOk' => false
        ];
    }


    /**
     * @param $id
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function actionPeriodsByDate($id)
    {
        $event = Event::findOne($id);
        if ($event) {
            $explode = explode(' ', $event->begin_date_hour);
            $events = Event::find()
                ->andWhere(['event_id' => $event->event_id])
                ->andWhere(['is_time_period' => 1])
                ->andWhere(['status' => Event::EVENTS_WORKFLOW_STATUS_PUBLISHED])
                ->andWhere(['like', 'begin_date_hour', $explode[0]])->all();

            foreach ($events as $ev) {
                $url = EventsUtility::getUrlLanding($ev);
                $beginDate = new \DateTime($ev->begin_date_hour);
                $endDate = new \DateTime($ev->end_date_hour);
                $out[] = [
                    'id' => $ev->id,
                    'period' => $beginDate->format('H:i') . ' - ' . $endDate->format('H:i')];
            }
        }
        return [
            'isOk' => true,
            'data' => $out
        ];
    }

    /**
     * @param $event_id
     * @param bool $isGuest
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function actionEventLanding($event_id, $isGuest = false)
    {
        $event = Event::findOne($event_id);
        if ($event) {
            $eventLanding = $event->eventLanding;
            if ($eventLanding) {
                if ($eventLanding->streaming_url) {
                    $currentTime = new \DateTime('now', new \DateTimeZone("Europe/Rome"));
                    $isAlwaysVisible = false;
                    if (empty($eventLanding->date_begin_streaming)) {
                        $isAlwaysVisible = true;
                    }
                    $beginStreamingDate = new \DateTime($eventLanding->date_begin_streaming, new \DateTimeZone("Europe/Rome"));

                    $show = false;
                    $showcountdown = '';
                    if ($currentTime >= $beginStreamingDate || $isAlwaysVisible) {
                        $show = true;
                        $showcountdown = false;
                    } else {
                        $show = false;
                        $showcountdown = true;
                    }


                    $data = [
                        'enableStreamingSection' => true,
                        'showStreaming' => $show,
                        'showCountdown' => $showcountdown,
                        'streaming_url' => $this->getFormattedUrlStreaming($eventLanding),
                        'streaming_type' => $eventLanding->streaming_type,
                        'date_begin_streaming' => $eventLanding->date_begin_streaming,
                        'status' => $this->getStatusStreaming($eventLanding, $isGuest)
                    ];

                } else {
                    $data = ['enableStreamingSection' => false];
                }

                return $result = [
                    'isOk' => true,
                    'data' => $data
                ];
            }
        }
        return $result = [
            'isOk' => false,
        ];
    }


    /**
     * @param $eventLanding
     * @param bool $isGuest
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getStatusStreaming($eventLanding, $isGuest = false)
    {
        // login_or_register  = a)b) + show_countdown/show_streaming
        // subscribtion_opened  = c)d) show_countdown/show_streaming
        // streaming  = e)f) show_countdown/show_streaming
        // subscribtion_closed  = g)

        // USER IS GUEST
        if ($isGuest) {
            $status = 'login_or_register';
            // USER IS LOGGED
        } else {
            // REGISTERED TO EVENT OR INFORMATIVE EVENT
            if (EventsUtility::isEventParticipant($eventLanding->event_id, \Yii::$app->user->id)
                || $eventLanding->event->eventType->event_type == EventType::TYPE_INFORMATIVE
            ) {
                $status = 'streaming';
            } else {
                if ($eventLanding->event->isSubscribtionsOpened()) {
                    $status = 'subscribtion_opened';
                } else {
                    $status = 'subscribtion_closed';
                }

            }

        }
        return $status;
    }

    /**
     * @param $eventLanding
     * @return mixed
     */
    public function getFormattedUrlStreaming($eventLanding)
    {
        switch ($eventLanding->streaming_type) {
            case EventLanding::STREAMING_TYPE_YOUTUBE:
                $url = \amos\sitemanagement\models\SiteManagementSliderElem::getUrlEmbedVideoStatic($eventLanding->streaming_url);
                break;
            case EventLanding::STREAMING_TYPE_FACEBOOK:
                $url = $eventLanding->streaming_url;
                break;
            case EventLanding::STREAMING_TYPE_MEDIAPORTAL:
                $url = $eventLanding->getUrlMediaPortalFormatted();
                break;
        }
        return $url;
    }


    /**
     * @param null $user_id
     * @return array
     */
    public function actionSendOTPEmail($user_id = null)
    {
        if (empty($user_id)) {
            $user_id = \Yii::$app->user->id;
        }
        $user = User::findOne($user_id);
        if ($user) {
            $ok = $this->sendOtpCode($user);
            return [
                'isOk' => $ok
            ];
        }
        return [
            'isOk' => false
        ];
    }

    /**
     * @param $user
     * @return boolean
     */
    public function sendOtpCode($user)
    {
        $subject = AmosAdmin::t('amosadmin', "Modifica indirizzo email per l'utente {nome} {cognome}",
            [
                'nome' => $user->userProfile->nome,
                'cognome' => $user->userProfile->cognome,
            ]);
        $text = "<p>" . AmosAdmin::t('amosadmin',
                "È stato richiesto il cambio dell'indirizzo email per l'utente <strong>{nome}</strong> <strong>{cognome}</strong> iscritto alla piattaforma <strong>{appname}</strong>.",
                [
                    'nome' => $user->userProfile->nome,
                    'cognome' => $user->userProfile->cognome,
                    'appname' => \Yii::$app->name,
                ]) . "</p>";

        $ok = \open20\amos\admin\models\UserOtpCode::sendEmailAuthentication($user->email, $subject, $text, $user);
        return $ok;
    }

    /**
     * [
     *      'user_id' => 120002,
     *      'action' => 'send-token',
     *      'email' => 'aaa@bbb.it',
     *      'auth_code' => '13465767'
     *
     * ]
     * @return array
     */
    public function actionModifyEmail()
    {
        $post = \Yii::$app->request->post();
        $user = User::findOne($post['user_id']);
        $errorString = '';
        $error = false;
        $action = !empty($post['action']) ? $post['action'] : '';
        $code = !empty($post['auth_code']) ? $post['auth_code'] : null;

        if ($user) {
            $oldEmail = $user->email;
            $user->email = $post['email'];
            if ($oldEmail == $user->email) {
                $errorString = AmosAdmin::t('amosadmin', "L'email inserita deve essere diversa dalla precedente.");
                $error = true;
            }


            if (!$error) {
                if ($post['action'] == 'send-token') {
                    $action = 'send-token';
                    $ok = $this->sendOtpCode($user);
                    if (!$ok) {
                        $errorString = AmosAdmin::t('amosadmin', "Errore nell'invio del codice OTP");
                    }
                } else {
                    $action = 'save-email';
                    if (UserOtpCode::isValidCodice($code, UserOtpCode::TYPE_AUTH_EMAIL, $user->id)) {
                        if (!UserOtpCode::isExpired($code, UserOtpCode::TYPE_AUTH_EMAIL, $user->id)) {
                            if ($user->validate('email')) {
                                $user->save(false);
                            }
                        } else {
                            $errorString = AmosAdmin::t('amosadmin', 'Expired code');
                            $error = true;

                        }
                    } else {
                        $errorString = AmosAdmin::t('amosadmin', 'Il codice OTP inserito non è valido, inserire quello corretto oppure richiederne uno nuovo');
                        $error = true;
                    }
                }
            }
        }

        $result = [
            'isOk' => !$error,
            'action' => $action,
            'error' => $errorString,
        ];

        return $result;

    }

    /**
     * @param null $offset
     * @param null $limit
     * @param null $from_date
     * @param null $to_date
     * @param null $text
     * @param null $category
     * @param string $order
     * @return array
     */
    public function actionHighlightedEvents(
        $offset = null, $limit = null, $from_date = null, $to_date = null, $text = null, $category = null, $order = 'ASC')
    {

        return $this->actionEventsList($offset, $limit, $from_date, $to_date, $text, $category, $order, true);
    }


}
