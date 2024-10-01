<?php

namespace amos\podcast\models;

use amos\podcast\i18n\grammar\PodcastEpisodeGrammar;
use open20\amos\attachments\behaviors\FileBehavior;
use open20\amos\core\interfaces\ContentModelInterface;
use open20\amos\notificationmanager\behaviors\NotifyBehavior;
use open20\amos\seo\behaviors\SeoContentBehavior;
use open20\amos\seo\interfaces\SeoModelInterface;
use open20\amos\tag\models\EntitysTagsMm;
use open20\amos\tag\models\Tag;
use open20\amos\workflow\behaviors\WorkflowLogFunctionsBehavior;
use raoul2000\workflow\base\SimpleWorkflowBehavior;
use Yii;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "podcast_episode".
 */
class PodcastEpisode extends \amos\podcast\models\base\PodcastEpisode implements ContentModelInterface, SeoModelInterface
{
    public $regola_pubblicazione;
    public $destinatari;
    public $validatori;

    const PLAYER_SPREAKER = 1;
    const PLAYER_SOUNDHOUND = 2;
    const PLAYER_APPLEPODCAST = 3;
    const PLAYER_SPOTIFY = 4;
    const PLAYER_GOOGLEPODCAST = 5;

    // Workflow ID
    const PODCASTEP_WORKFLOW = 'PodcastEpisodeWorkflow';
    // Workflow states IDS
    const PODCASTEP_WORKFLOW_STATUS_DRAFT = 'PodcastEpisodeWorkflow/DRAFT';
    const PODCASTEP_WORKFLOW_STATUS_REQUESTPUBLICATION = 'PodcastEpisodeWorkflow/REQUESTPUBLICATION';
    const PODCASTEP_WORKFLOW_STATUS_REJECTED = 'PodcastEpisodeWorkflow/REJECTED';
    const PODCASTEP_WORKFLOW_STATUS_PUBLISHED = 'PodcastEpisodeWorkflow/PUBLISHED';

    /**
     * @return array
     */
    public static function getPodcastTypeList()
    {
        return [
            self::PLAYER_SPREAKER => 'url_spreaker',
//            self::PLAYER_SOUNDHOUND => 'url_soundhound',
//            self::PLAYER_APPLEPODCAST => 'url_apple_podcast',
//            self::PLAYER_SPOTIFY => 'url_spotify',
//            self::PLAYER_GOOGLEPODCAST => 'url_google_podcast',
        ];
    }

    /**
     * @return array
     */
    public static function getPodcastTypeListLabel()
    {
        return [
            self::PLAYER_SPREAKER  => 'Spreaker',
//            self::PLAYER_SOUNDHOUND  => 'Soundhound',
//            self::PLAYER_APPLEPODCAST  => 'Apple podcast',
//            self::PLAYER_SPOTIFY  => 'Spotify',
//            self::PLAYER_GOOGLEPODCAST  => 'Google podcast',
        ];
    }

    public function init()
    {
        if ($this->isNewRecord) {
            $this->status = $this->getWorkflowSource()->getWorkflow(self::PODCASTEP_WORKFLOW)->getInitialStatusId();
        }
        parent::init();
    }

    /**
     * @return mixed|null
     */
    public function getMainPodcastPlayer()
    {
        $podcastTypes = self::getPodcastTypeList();
        if (!empty($this->main_player_id)) {
            if (!empty($podcastTypes[$this->main_player_id])) {
                $attribute_url = $podcastTypes[$this->main_player_id];
                return $this->$attribute_url;
            }
        }
        return null;
    }

    public function representingColumn()
    {
        return [
            'title'
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
            [['mainImage', 'attachments'], 'file']
        ]);
    }



    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if(empty($this->published_at) && $this->status == PodcastEpisode::PODCASTEP_WORKFLOW_STATUS_PUBLISHED){
            $this->published_at = date('Y-m-d H:i:s');
            $this->save(false);
        }
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(),
            [

                'workflow' => [
                    'class' => SimpleWorkflowBehavior::className(),
                    'defaultWorkflowId' => self::PODCASTEP_WORKFLOW,
                    'propagateErrorsToModel' => true
                ],
                'NotifyBehavior' => [
                    'class' => NotifyBehavior::className(),
                    'conditions' => [],
                ],
                'fileBehavior' => [
                    'class' => FileBehavior::className()
                ],
                'WorkflowLogFunctionsBehavior' => [
                    'class' => WorkflowLogFunctionsBehavior::className(),
                ],
                'SeoContentBehavior' => [
                    'class' => SeoContentBehavior::className(),
                    'imageAttribute' => 'mainImage',
                    'defaultOgType' => 'article',
                    'schema' => 'NewsArticle',
                    'titleAttribute' => 'title',
                    'descriptionAttribute' => 'abstract'
                ]
            ]);
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


    public function attributeLabels()
    {
        return
            ArrayHelper::merge(
                parent::attributeLabels(),
                [
                ]);
    }


    public function getEditFields()
    {
        $labels = $this->attributeLabels();

        return [
            [
                'slug' => 'podcast_id',
                'label' => $labels['podcast_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'title',
                'label' => $labels['title'],
                'type' => 'string'
            ],
            [
                'slug' => 'image_description',
                'label' => $labels['image_description'],
                'type' => 'text'
            ],
            [
                'slug' => 'abstract',
                'label' => $labels['abstract'],
                'type' => 'text'
            ],
            [
                'slug' => 'description',
                'label' => $labels['description'],
                'type' => 'text'
            ],
            [
                'slug' => 'main_player_id',
                'label' => $labels['main_player_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'url_spreaker',
                'label' => $labels['url_spreaker'],
                'type' => 'string'
            ],
            [
                'slug' => 'url_soundhound',
                'label' => $labels['url_soundhound'],
                'type' => 'string'
            ],
            [
                'slug' => 'url_apple_podcast',
                'label' => $labels['url_apple_podcast'],
                'type' => 'string'
            ],
            [
                'slug' => 'url_spotify',
                'label' => $labels['url_spotify'],
                'type' => 'string'
            ],
            [
                'slug' => 'url_google_podcast',
                'label' => $labels['url_google_podcast'],
                'type' => 'string'
            ],
            [
                'slug' => 'published_at',
                'label' => $labels['published_at'],
                'type' => 'datetime'
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




    /**
     * @return string
     */
    public function getTitle(){
        return $this->title;
    }

    /**
     * @inheritdoc
     */
    public function getShortDescription()
    {
        return $this->abstract;
    }

    /**
     * @return string
     */
    public function getDescription($truncate = false){
        $ret = $this->description;
        if ($truncate) {
            $ret = $this->__shortText($this->description, 200);
        }
        return $ret;
    }

    /**
     * @return array
     */
    public function getGridViewColumns(){
        return [
            'title'
        ];
    }

    /**
     * @inheritdoc
     */
    public function getToValidateStatus()
    {
        return self::PODCASTEP_WORKFLOW_STATUS_REQUESTPUBLICATION;
    }

    /**
     * @inheritdoc
     */
    public function getValidatedStatus()
    {
        return self::PODCASTEP_WORKFLOW_STATUS_PUBLISHED;
    }

    /**
     * @inheritdoc
     */
    public function getDraftStatus()
    {
        return self::PODCASTEP_WORKFLOW_STATUS_DRAFT;
    }

    /**
     * @inheritdoc
     */
    public function getValidatorRole()
    {
        return 'PODCAST_VALIDATOR';
    }

    public function getPluginWidgetClassname()
    {
        return null;
//        return WidgetIconNewsDashboard::className();
    }

    /**
     * @inheritdoc
     */
    public function getPublicatedFrom()
    {
        return $this->published_at;
    }

    /**
     * @inheritdoc
     */
    public function getPublicatedAt()
    {
        return $this->published_at;
    }

    public function getGrammar()
    {
        return new PodcastEpisodeGrammar();
    }

    /**
     * @return array list of statuses that for cwh is validated
     */
    public function getCwhValidationStatuses()
    {
        return [$this->getValidatedStatus()];
    }

    /**
     * @return string|\yii\db\ActiveQuery
     */
    public function getCategory()
    {
        $category = $this->podcast->podcastCategory;
        if($category){
            return $category->name;
        }
        return '';
    }

    /**
     * @inheritdoc
     */
    public function getSchema()
    {

    }


    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getTags(){
        $tags = Tag::find()
            ->innerJoin('entitys_tags_mm', 'entitys_tags_mm.tag_id = tag.id')
            ->andWhere(['classname' => self::className(), 'record_id' => $this->id])->all();
        return $tags;
    }

    /**
     * @return array
     */
    public function getTagsString(){
        $tagNames = [];
        $tags = $this->getTags();
        foreach ($tags as $tag){
            $tagNames []= $tag->nome;
        }
        return implode(',', $tagNames);
    }

    /**
     *
     */
    public function registerMetatagSeo(){
        if ($this->getOgTitle()) {
            \Yii::$app->controller->view->registerMetaTag(['name' => 'og:title', 'content' => $this->getOgTitle()], 'ogTitle');
            \Yii::$app->controller->view->registerMetaTag(['property' => 'og:title', 'content' => $this->getOgTitle()], 'fbTitle');
        }
        if ($this->getOgDescription()) {
            \Yii::$app->controller->view->registerMetaTag(['name' => 'og:description', 'content' => $this->getOgDescription()],
                'ogDescription');
            \Yii::$app->controller->view->registerMetaTag(['property' => 'og:description', 'content' => $this->getOgDescription()],
                'fbDescription');
        }
        if ($this->getOgType()) {
            \Yii::$app->controller->view->registerMetaTag(['name' => 'og:type', 'content' => $this->getOgType()], 'ogType');
            \Yii::$app->controller->view->registerMetaTag(['property' => 'og:type', 'content' => $this->getOgType()], 'fbType');
        }
        if ($this->getOgImageUrl()) {
            \Yii::$app->controller->view->registerMetaTag(['name' => 'og:image', 'content' => $this->getOgImageUrl()], 'ogImage', false);
            \Yii::$app->controller->view->registerMetaTag(['property' => 'og:image', 'content' => $this->getOgImageUrl()], 'fbImage',
                false);
        }
        if ($this->getMetaRobots()) {
            \Yii::$app->controller->view->registerMetaTag(['name' => 'robots', 'content' => $this->getMetaRobots()], 'metaRobots');
            \Yii::$app->controller->view->registerMetaTag(['property' => 'robots', 'content' => $this->getMetaRobots()], 'fbmetaRobots');
        }
        if ($this->getMetaGooglebot()) {
            \Yii::$app->controller->view->registerMetaTag(['name' => 'googlebot', 'content' => $this->getMetaGooglebot()],
                'metaGooglebot');
            \Yii::$app->controller->view->registerMetaTag(['property' => 'googlebot', 'content' => $this->getMetaGooglebot()],
                'fbmetaGooglebot');
        }
        if ($this->getMetaDescription()) {
            \Yii::$app->controller->view->registerMetaTag(['name' => 'description', 'content' => $this->getMetaDescription()],
                'metaDescription');
            \Yii::$app->controller->view->registerMetaTag(['property' => 'description', 'content' => $this->getMetaDescription()],
                'fbmetaDescription');
        }
        if ($this->getMetaKeywords()) {
            \Yii::$app->controller->view->registerMetaTag(['name' => 'keywords', 'content' => $this->getMetaKeywords()], 'metyKeywords');
            \Yii::$app->controller->view->registerMetaTag(['property' => 'keywords', 'content' => $this->getMetaKeywords()],
                'fbmetyKeywords');
        }
        if ($this->getMetaTitle()) {
            \Yii::$app->controller->view->title = $this->getMetaTitle();
        }
    }
}
