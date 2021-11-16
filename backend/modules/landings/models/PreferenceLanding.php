<?php

namespace backend\modules\landings\models;

use backend\modules\landings\Module;
use open20\amos\attachments\behaviors\FileBehavior;
use open20\amos\sondaggi\models\Sondaggi;
use open20\amos\sondaggi\utility\SondaggiUtility;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "preference_landing".
 */
class PreferenceLanding extends \backend\modules\landings\models\base\PreferenceLanding
{

    const PREFERENCE_LANDING_STATUS_DRAFT = 'DRAFT';
    const PREFERENCE_LANDING_STATUS_PUBLISHED = 'PUBLISHED';


    public function representingColumn()
    {
        return [
            'title'
        ];
    }

    public function init()
    {
        parent::init();
        if($this->isNewRecord){
            $this->status = self::PREFERENCE_LANDING_STATUS_DRAFT;
        }
    }

    public function attributeHints()
    {
        return [
        ];
    }

    /**
     * Returns the text hint for the specified attribute.
     * @param string $attribute the attribute name
     * @return string the attribute hint
     */
    public function getAttributeHint($attribute)
    {
        $hints = $this->attributeHints();
        return isset($hints[$attribute]) ? $hints[$attribute] : null;
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['mainImage','mainImageMobile'], 'file', 'extensions' => 'jpeg, jpg, png, gif'],
            ['url', 'checkAvailabilityUrl'],


        ]);
    }

    public
    function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'fileBehavior' => [
                    'class' => FileBehavior::className()
                ],
            ]
        );
    }

    public function attributeLabels()
    {
        return
            ArrayHelper::merge(
                parent::attributeLabels(),
                [
                ]);
    }


    public static function getEditFields()
    {
        $labels = self::attributeLabels();

        return [
            [
                'slug' => 'preference_structure_id',
                'label' => $labels['preference_structure_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'status',
                'label' => $labels['status'],
                'type' => 'string'
            ],
            [
                'slug' => 'title',
                'label' => $labels['title'],
                'type' => 'string'
            ],
            [
                'slug' => 'description',
                'label' => $labels['description'],
                'type' => 'text'
            ],
            [
                'slug' => 'schedule',
                'label' => $labels['schedule'],
                'type' => 'text'
            ],
            [
                'slug' => 'image_slider_id',
                'label' => $labels['image_slider_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'video_slider_id',
                'label' => $labels['video_slider_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'luya_page_id',
                'label' => $labels['luya_page_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'luya_template_id',
                'label' => $labels['luya_template_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'url',
                'label' => $labels['url'],
                'type' => 'string'
            ],
        ];
    }

    /**
     * @return string marker path
     */
    public function getIconMarker()
    {
        return null; //TODO
    }

    /**
     * If events are more than one, set 'array' => true in the calendarView in the index.
     * @return array events
     */
    public function getEvents()
    {
        return NULL; //TODO
    }

    /**
     * @return url event (calendar of activities)
     */
    public function getUrlEvent()
    {
        return NULL; //TODO e.g. Yii::$app->urlManager->createUrl([]);
    }

    /**
     * @return color event
     */
    public function getColorEvent()
    {
        return NULL; //TODO
    }

    /**
     * @return title event
     */
    public function getTitleEvent()
    {
        return NULL; //TODO
    }

    public function beforeSave($insert)
    {
        $sondaggiModule = Yii::$app->getModule('sondaggi');
        // prima di salvare setto il campo per la chiusura del sondaggio... se esiste...
        if (!empty($sondaggiModule) && !empty($this->sondaggio)) {
            /** @var Sondaggi $sondaggio */
            $sondaggio = $this->sondaggio;
            // javascript:window.close(); non funzione a partire dalla seconda pagina in poi...
            // $sondaggio->url_chiudi_sondaggio = 'javascript:window.close();';
            if ($sondaggio->url_chiudi_sondaggio != \backend\modules\landings\utility\PreferenceLandingUtility::getUrlLanding($this)){
                $sondaggio->url_chiudi_sondaggio = \backend\modules\landings\utility\PreferenceLandingUtility::getUrlLanding($this);
                $sondaggio->save(false);

//              // Rigenero il sondaggio da compilare
                SondaggiUtility::regenerateSondaggioPagesModelAndViews($sondaggio->id);
            }
        }

        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $template_changed = false;
        if(!empty($changedAttributes['luya_template_id']) && $changedAttributes['luya_template_id'] != $this->luya_template_id){
            $template_changed = true;
        }
        $this->createLandingPage($this, $template_changed);
    }

    /**
     * @param $model PreferenceLanding
     * @param $form
     */
    public function createLandingPage($model, $template_changed = false)
    {
        $url_Image = '';
        /** @var  $location EventLocation */
        $beginDate = null;
        $endDate = null;
        if ($model->created_at) {
            $beginDate = new \DateTime($model->created_at);
        }

        $utility = new \amos\cmsbridge\utility\CmsUtility();
        $page = new \amos\cmsbridge\models\PostCmsCreatePage();
        // CONFIGURATION LANDING PAGE
        $page->nav_item_type = 1;
        $page->parent_nav_id = 0;
        $page->is_draft = 0;
        $page->nav_container_id = 1;
        $page->lang_id = 1;
        $page->use_draft = 1;
        $page->layout_id = 0;
        $page->from_draft_id = $this->luya_template_id;
        $page->title = $model->title; // Titolo pagina
        $page->alias = $this->url; // alias pagina

        //        if (!is_null($type) && $type->event_type == EventType::TYPE_UPON_INVITATION) {
        //            $page->with_login = 1;
        //        } else {
        //            $page->with_login = 0;
        //        }


        // DYNAMIC FIELD LANDING PAGE
        $page->event_data->event_id = $model->id; // id dell'evento
        $page->event_data->title = $model->title;
        //        $page->event_data->event_date = $dateString;
        $page->event_data->presentation = $this->description;
        $page->event_data->program = $this->schedule;
        $page->event_data->description = $model->description;


        //DYNAMIC IMAGE LANDING PAGE
       if (!empty($model->mainImage)) {
        //           pr($model->mainImage->getWebUrl());die;
            $page->event_data->url_image = Yii::$app->params['platform']['backendUrl'] . $model->mainImage->getWebUrl(); //Web Url
        } else {
            $page->event_data->url_image = '';
        }

        // PUBLIS LANDING PAGE
        $maxDate = new \DateTime('2038-01-19 04:14:00');
        if ($model->status != self::PREFERENCE_LANDING_STATUS_PUBLISHED && $maxDate) {
            $page->event_data->opening_date = $maxDate->format('Y-m-d H:i:s');
        } else {
            $page->event_data->opening_date = (new \DateTime($model->created_at))->format('Y-m-d H:i:s');
        }
        $page->cms_user_id = $utility->loginCms();

        // MAKE THE API CALL FOR CREATE OR UPDATE
        if (!empty($this->luya_page_id)) {
            $page->nav_id = $this->luya_page_id;
            if($template_changed){
                $resultDelete = $utility->deleteCmsPage($page);
                $page->nav_id = null;
                if (!empty($resultDelete->status) && $resultDelete->status == 1) {
                    $result = $utility->createCmsPage($page);
                    if (!empty($result->status) && $result->status == 1) {
                        $this->luya_page_id = $result->nav_id;
                        $this->save(false);
                    }
                }
            }else{
                $result = $utility->updateCmsPage($page);
            }

        } else {
            $result = $utility->createCmsPage($page);
            if (!empty($result->status) && $result->status == 1) {
                $this->luya_page_id = $result->nav_id;
                $this->save(false);
            }
        }
    }

    /**
     *
     */
    public function checkAvailabilityUrl(){

        $utility = new \amos\cmsbridge\utility\CmsUtility();
        $ok = $utility->isUrlOk($this->url, $this->luya_page_id) ;
        
        if(!$ok){
            $this->addError('url', "L'url della landing esiste già");
        }
    }

    /**
     * @return string
     */
    public function getLabelStatus(){
        if($this->status == self::PREFERENCE_LANDING_STATUS_DRAFT){
            $label = Module::t('preferencelandings',"Bozza");
        }else if($this->status == self::PREFERENCE_LANDING_STATUS_PUBLISHED){
            $label = Module::t('preferencelandings',"Pubblicata");
        }else {
            $label =  '-';
        }
        return $label;
    }

}
