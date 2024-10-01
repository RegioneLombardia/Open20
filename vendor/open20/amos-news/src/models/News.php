<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\models
 * @category   CategoryName
 */

namespace open20\amos\news\models;

use open20\amos\attachments\models\File;
use open20\amos\attachments\behaviors\FileBehavior;
use open20\amos\comments\models\CommentInterface;
use open20\amos\community\models\Community;
use open20\amos\comments\models\CommentModeration;
use open20\amos\core\helpers\Html;
use open20\amos\core\interfaces\CustomUrlModelInterface;
use open20\amos\core\interfaces\ModelImageInterface;
use open20\amos\core\interfaces\ContentModelInterface;
use open20\amos\core\interfaces\PublicationDateFieldsInterface;
use open20\amos\core\interfaces\ViewModelInterface;
use open20\amos\core\views\toolbars\StatsToolbarPanels;
use open20\amos\news\AmosNews;
use open20\amos\news\i18n\grammar\NewsGrammar;
use open20\amos\news\models\base\NewsRelatedEventMm;
use open20\amos\news\models\NewsRelatedDocumentiMm;
use open20\amos\news\models\NewsRelatedNewsMm;
use open20\amos\news\utility\NewsUtility;
use open20\amos\news\models\NewsRelatedAgidServiceMm;
use open20\amos\news\models\NewsAgidPersonMm;
use open20\amos\news\widgets\icons\WidgetIconNewsDashboard;
use open20\amos\notificationmanager\behaviors\NotifyBehavior;
use open20\amos\report\utilities\ReportUtil;
use open20\amos\seo\behaviors\SeoContentBehavior;
use open20\amos\seo\interfaces\SeoModelInterface;
use open20\amos\workflow\behaviors\WorkflowLogFunctionsBehavior;
use kartik\datecontrol\DateControl;
use raoul2000\workflow\base\SimpleWorkflowBehavior;
use open20\amos\core\interfaces\ContentPublicationInteraface;
use Yii;
use yii\log\Logger;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\behaviors\SluggableBehavior;

/**
 * Class News
 *
 * @method \cornernote\workflow\manager\components\WorkflowDbSource getWorkflowSource()
 * @method \yii\db\ActiveQuery hasOneFile($attribute = 'file', $sort = 'id')
 * @method \yii\db\ActiveQuery hasMultipleFiles($attribute = 'file', $sort = 'id')
 * @method string|null getRegolaPubblicazione()
 * @method array getTargets()
 *
 * @package open20\amos\news\models
 */
class News
    extends
    \open20\amos\news\models\base\News
    implements
    ContentModelInterface, CommentInterface, ViewModelInterface,
    ModelImageInterface, SeoModelInterface, CustomUrlModelInterface,
    ContentPublicationInteraface, PublicationDateFieldsInterface
{
    // Workflow ID
    const NEWS_WORKFLOW = 'NewsWorkflow';
    // Workflow states IDS
    const NEWS_WORKFLOW_STATUS_BOZZA = 'NewsWorkflow/BOZZA';
    const NEWS_WORKFLOW_STATUS_DAVALIDARE = 'NewsWorkflow/DAVALIDARE';
    const NEWS_WORKFLOW_STATUS_VALIDATO = 'NewsWorkflow/VALIDATO';
    const NEWS_WORKFLOW_STATUS_NONVALIDATO = 'NewsWorkflow/NONVALIDATO';

    /**
     * Create news scenario
     */
    const SCENARIO_CREATE = 'news_create';

    /**
     * All the scenarios listed below are for the wizard.
     */
    const SCENARIO_INTRODUCTION = 'scenario_introduction';
    const SCENARIO_DETAILS = 'scenario_details';
    const SCENARIO_PUBLICATION = 'scenario_publication';
    const SCENARIO_SUMMARY = 'scenario_summary';
    const SCENARIO_DETAILS_HIDE_PUBBLICATION_DATE = 'scenario_details_hide_pubblication_date';
    const SCENARIO_CREATE_HIDE_PUBBLICATION_DATE = 'scenario_create_hide_pubblication_date';
    const SCENARIO_UPDATE_HIDE_PUBBLICATION_DATE = 'scenario_update_hide_pubblication_date';
    const NEWS_CLUSTERCAT_AGRIFOOD_ID = '4';
    const NEWS_CLUSTERCAT_AEROSPAZIO_ID = '5';
    const NEWS_CLUSTERCAT_CHIMICAVERDE_ID = '6';
    const NEWS_CLUSTERCAT_MOBILITA_ID = '9';
    const NEWS_CLUSTERCAT_FABBRICAINTELLGENTE_ID = '8';
    const NEWS_CLUSTERCAT_ENERGIA_ID = '7';
    const NEWS_CLUSTERCAT_SMARTCOMUNITIESTEC_ID = '11';
    const NEWS_CLUSTERCAT_SCIENZEVITA_ID = '10';
    const NEWS_CLUSTERCAT_AMBIENTIVITATEC_ID = '12';
    const NEWS_CLUSTERCAT_STORIEINNOVAZIONE_ID = '13';
    const NEWS_CLUSTERCAT_LABLOMBARDIA_ID = '14';
    const NEWS_CLUSTERCAT_CAMPUSPARTY_ID = '15';
    const NEWS_CLUSTERCAT_STATIGENERALI_ID = '16';
    const NEWS_CLUSTERCAT_PREMIO_ID = '17';
    const NEWS_CLUSTERCAT_FOROREGIONALE_ID = '18';
    const NEWS_CLUSTERCAT_LEGGEREGIONALE_ID = '19';
    const NEWS_CLUSTERCAT_REDAZIONE_ID = '20';

    /**
     * @var string $distance Distanza
     */
    public $distance;

    public $tag_free;

    /**
     * @var File $newsImage
     */
    private $newsImage;

    /**
     * @var File[] $attachments
     */
    public $attachments;

    /**
     * @var File[] $attachmentsForItemView
     */
    private $attachmentsForItemView;

    /**
     * @var
     */
    public $otherCategories;

    /**
     * @var $period
     */
    public $period;

    /**
     * @var $data_pubblicazione_from
     */
    public $data_pubblicazione_from;

    /**
     * @var $data_pubblicazione_to
     */
    public $data_pubblicazione_to;

    /**
     */
    public function init()
    {
        parent::init();

        if ($this->isNewRecord) {
            $this->status = $this->getWorkflowSource()->getWorkflow(self::NEWS_WORKFLOW)->getInitialStatusId();

            if (!is_null($this->newsModule)) {
                if ($this->newsModule->hidePubblicationDate) {
                    // the news will be visible forever
                    $this->data_rimozione = '9999-12-31';
                }
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        $moduleNews = \Yii::$app->getModule(AmosNews::getModuleName());

        if ($this->status == self::NEWS_WORKFLOW_STATUS_VALIDATO) {
            if ($this->data_pubblicazione == '') {
                if ($moduleNews->dateFormatNews == DateControl::FORMAT_DATETIME) {
                    $this->data_pubblicazione = date('Y-m-d H:i');
                } else {
                    $this->data_pubblicazione = date('Y-m-d');
                }
            } else {
                if ($moduleNews->autoUpdatePublicationDate === true) {
                    if ($moduleNews->dateFormatNews == DateControl::FORMAT_DATETIME) {
                        if (strtotime($this->data_pubblicazione) < strtotime(date('Y-m-d H:i'))) {
                            $this->data_pubblicazione = date('Y-m-d H:i');
                        }
                    } else {
                        if (strtotime($this->data_pubblicazione) < strtotime(date('Y-m-d'))) {
                            $this->data_pubblicazione = date('Y-m-d');
                        }
                    }
                }
            }
        }

        if ($this->data_pubblicazione && !$this->data_rimozione) {
            $this->data_rimozione = '9999-12-31';
        }

        return parent::beforeSave($insert);
    }

    /**
     * Getter for $this->newsImage;
     */
    public function getNewsImage()
    {
        if (empty($this->newsImage)) {
            $this->newsImage = $this->hasOneFile('newsImage')->one();
        }

        return $this->newsImage;
    }

    /**
     * @inheritdoc
     */
    public function getModelImage()
    {
        return $this->getNewsImage();
    }

    /**
     * @param $image
     */
    public function setNewsImage($image)
    {
        $this->newsImage = $image;
    }

    public function getGalleria()
    {
        if (empty($this->galleria)) {
            $query = $this->hasMultipleFiles('news_gallery_attachment');
            $query->multiple = false;
            $this->news_gallery_attachment = $query->all();
        }

        return $this->news_gallery_attachment;
    }

    /**
     * @param string $size
     * @param bool $protected
     * @param string $url
     * @param bool $absolute
     * @param bool $canCache
     * @return string
     */
    public function getGalleriaUrl(
        $size = 'original',
        $protected = false,
        $url = [],
        $absolute = false,
        $canCache = false
    )
    {
        $immagini = $this->getGalleria();
        foreach($immagini as $immagine) {
            if ($protected) {
                $url[] = $immagine->getUrl($size, $absolute, $canCache);
            } else {
                $url[] = $immagine->getWebUrl($size, $absolute, $canCache);
            }
        }

        return $url;
    }

    /**
     * @param string $size
     * @param bool $protected
     * @param string $url
     * @param bool $absolute
     * @param bool $canCache
     * @return string
     */
    public function getNewsImageUrl(
        $size = 'original',
        $protected = true,
        $url = '/img/img_default.jpg',
        $absolute = false,
        $canCache = false
    )
    {
        $newsImage = $this->getNewsImage();
        if (!is_null($newsImage)) {
            if ($protected) {
                $url = $newsImage->getUrl($size, $absolute, $canCache);
            } else {
                $url = $newsImage->getWebUrl($size, $absolute, $canCache);
            }
        }

        return $url;
    }

    /**
     * @param $size
     * @param $protected
     * @param $url
     * @param $absolute
     * @param $canCache
     * @return string
     */
    public function getModelImageUrl(
        $size = 'original',
        $protected = true,
        $url = '/img/img_default.jpg',
        $absolute = false,
        $canCache = false
    )
    {
        return $this->getNewsImageUrl($size, $protected, $url, $absolute, $canCache);
    }

    /**
     * Getter for $this->attachments;
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getAttachments()
    {
        if (empty($this->attachments)) {
            $query = $this->hasMultipleFiles('attachments');
            $query->multiple = false;
            $this->attachments = $query->all();
        }

        return $this->attachments;
    }

    /**
     * @param $attachments
     */
    public function setAttachments($attachments)
    {
        $this->attachments = $attachments;
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getAttachmentsForItemView()
    {
        if (empty($this->attachmentsForItemView)) {
            $query = $this->hasMultipleFiles('attachments');
            $query->multiple = false;
            $this->attachmentsForItemView = $query->all();
        }

        return $this->attachmentsForItemView;
    }

    /**
     * @param $attachments
     */
    public function setAttachmentsForItemView($attachments)
    {
        $this->attachmentsForItemView = $attachments;
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $parentScenarios = parent::scenarios();
        $scenarios = ArrayHelper::merge(
            $parentScenarios,
            [
                self::SCENARIO_CREATE => $parentScenarios[self::SCENARIO_DEFAULT]
            ]
        );
        $scenarios[self::SCENARIO_INTRODUCTION] = [];
        $scenarios[self::SCENARIO_DETAILS] = [
            'titolo',
            'sottotitolo',
            'descrizione_breve',
            'descrizione',
            'news_categorie_id',
            'newsImage',
            'comments_enabled',
            'status',
        ];
        $scenarios[self::SCENARIO_PUBLICATION] = [
            'destinatari_pubblicazione',
            'destinatari_notifiche'
        ];
        $scenarios[self::SCENARIO_SUMMARY] = [
            'status'
        ];
        /** @var AmosNews $newsModule */
        $newsModule = Yii::$app->getModule(AmosNews::getModuleName());
        if ($newsModule->params['site_publish_enabled']) {
            $scenarios[self::SCENARIO_DETAILS][] = 'primo_piano';
        }
        if ($newsModule->params['site_featured_enabled']) {
            $scenarios[self::SCENARIO_DETAILS][] = 'in_evidenza';
        }
        $scenarios[self::SCENARIO_DETAILS_HIDE_PUBBLICATION_DATE] = $scenarios[self::SCENARIO_DETAILS];
        $scenarios[self::SCENARIO_CREATE_HIDE_PUBBLICATION_DATE] = $scenarios[self::SCENARIO_CREATE];

        if ($this->newsModule->request_publish_on_hp) {
            $scenarios[self::SCENARIO_CREATE][] = 'request_publish_on_hp';
        }

        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $modelNews = \Yii::$app->getModule('news');
        $requiredArray = [];
        if (!empty($modelNews)) {
            $requiredArray = $modelNews->newsRequiredFields;
        }
        $rules = ArrayHelper::merge(
            parent::rules(),
            [
                [$requiredArray, 'required'],
                [['tag_free'], 'safe'],
                [['otherCategories', 'slug', 'destinatari_pubblicazione', 'destinatari_notifiche'], 'safe'],
                [['attachments'], 'file', 'maxFiles' => 0],
                [['newsImage'], 'file', 'extensions' => 'jpeg, jpg, png, gif', 'maxFiles' => 1],
                ['newsImage', 'checkImageRequired','skipOnEmpty' => false]
            ]
        );

        if (
            $this->scenario != self::SCENARIO_DETAILS_HIDE_PUBBLICATION_DATE
            && $this->scenario != self::SCENARIO_CREATE_HIDE_PUBBLICATION_DATE
            && $this->scenario != self::SCENARIO_UPDATE_HIDE_PUBBLICATION_DATE
        ) {
            if($this->newsModule->requirePubblicationDate)
            {
                $rules = ArrayHelper::merge(
                    $rules,
                    [
                        [['data_pubblicazione', 'data_rimozione'], 'required'],
                    ]);
            }
            $rules = ArrayHelper::merge(
                $rules,
                [
                    ['data_pubblicazione', 'compare', 'compareAttribute' => 'data_rimozione', 'operator' => '<=',
                        'when' => function($model) {
                            if(!empty($model->data_pubblicazione) && !empty($model->data_rimozione)){
                                return true;
                            }
                            return false;
                        },
                        'whenClient' => "function (attribute, value) {
                            return Boolean($('#news-data_pubblicazione').val() && $('#news-data_rimozione').val());

                        }"],
                    ['data_rimozione', 'compare', 'compareAttribute' => 'data_pubblicazione', 'operator' => '>=',
                        'when' => function($model) {
                            if(!empty($model->data_pubblicazione) && !empty($model->data_rimozione)){
                                return true;
                            }
                            return false;
                        },
                        'whenClient' => "function (attribute, value) {
                            return Boolean($('#news-data_pubblicazione').val() && $('#news-data_rimozione').val());

                        }"],

                    ['date_news', 'compare', 'compareAttribute' => 'news_expiration_date', 'operator' => '<=',
                        'when' => function($model) {
                            return !empty($model->news_expiration_date);
                        },
                        'whenClient' => "function (attribute, value) {
                            return Boolean($('news_expiration_date').val());

                        }"
                    ],

                    ['news_expiration_date', 'compare', 'compareAttribute' => 'date_news', 'operator' => '>=',
                        'when' => function($model) {
                            return !empty($model->date_news);
                        },
                        'whenClient' => "function (attribute, value) {
                            return Boolean($('date_news').val());

                        }"
                    ],

                    ['data_pubblicazione', 'checkDate'],
                ]
            );
        }

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'slug' => [
                    'class' => SluggableBehavior::class,
                    'attribute' => 'titolo',
                    'ensureUnique' => true
                ],
                'workflow' => [
                    'class' => SimpleWorkflowBehavior::class,
                    'defaultWorkflowId' => self::NEWS_WORKFLOW,
                    'propagateErrorsToModel' => true
                ],
                'NotifyBehavior' => [
                    'class' => NotifyBehavior::class,
                    'conditions' => [],
                ],
                'fileBehavior' => [
                    'class' => FileBehavior::class
                ],
                'WorkflowLogFunctionsBehavior' => [
                    'class' => WorkflowLogFunctionsBehavior::class,
                ],
                'SeoContentBehavior' => [
                    'class' => SeoContentBehavior::class,
                    'imageAttribute' => 'newsImage',
                    'defaultOgType' => 'article',
                    'schema' => 'NewsArticle'
                ]
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(
            parent::attributeLabels(),
            [
                'newsImage' => AmosNews::t('amosnews', 'News image'),
                'tag_free' => AmosNews::t('amosnews', 'Tag'),
                'period' => AmosNews::t('amosnews', 'Intervallo di tempo'),
                'data_pubblicazione_from' => AmosNews::t('amosnews', 'Data pubblicazione da'),
                'data_pubblicazione_to' => AmosNews::t('amosnews', 'Data pubblicazione a'),
            ]
        );
    }

    /**
     * @see\open20\amos\core\record\Record::representingColumn() or more info.
     */
    public function representingColumn()
    {
        return [
            'titolo'
        ];
    }

    /**
     * @return string
     * TODO Serve ancora
     */
    public function getImageUrl($dimension = 'original')
    {
        $url = '/img/img_default.jpg';
        if ($this->immagine) {
            $mediafile = FilemanagerMediafile::findOne($this->immagine);
            if ($mediafile) {
                $url = $mediafile->getThumbUrl($dimension);
            }
        }

        return $url;
    }

    /**
     * @inheritdoc
     */
    public function getGridViewColumns()
    {
        return [
            'immagine' => [
                'label' => AmosNews::t('amosnews', '#label_image'),
                'format' => 'html',
                'value' => function ($model) {
                    $url = '/img/img_default.jpg';
                    if (!is_null($model->newsImage)) {
                        $url = $model->newsImage->getUrl('original');
                    }
                    return Html::img($url,
                        [
                            'class' => 'gridview-image'
                        ]);
                },
                'headerOptions' => [
                    'id' => AmosNews::t('amosnews', 'immagine'),
                ],
                'contentOptions' => [
                    'headers' => AmosNews::t('amosnews', 'immagine'),
                ]
            ],
            'titolo' => [
                'attribute' => 'titolo',
                'headerOptions' => [
                    'id' => AmosNews::t('amosnews', 'titolo'),
                ],
                'contentOptions' => [
                    'headers' => AmosNews::t('amosnews', 'titolo'),
                ],
            ],
            'created_by' => [
                'attribute' => 'createdUserProfile',
                'headerOptions' => [
                    'id' => AmosNews::t('amosnews', 'creato da'),
                ],
                'contentOptions' => [
                    'headers' => AmosNews::t('amosnews', 'creato da'),
                ]
            ],
            'data_pubblicazione' => [
                'attribute' => 'data_pubblicazione',
                'format' => 'date',
                'headerOptions' => [
                    'id' => AmosNews::t('amosnews', 'data pubblicazione'),
                ],
                'contentOptions' => [
                    'headers' => AmosNews::t('amosnews', 'data pubblicazione'),
                ]
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getViewUrl()
    {
        if (!empty($this->usePrettyUrl) && ($this->usePrettyUrl == true) && $this->hasMethod('getPrettyUrl')) {
            return 'news/news';
        }

        return 'news/news/view';
    }

    /**
     * @inheritdoc
     */
    public function getFrontendViewUrl()
    {
        return \Yii::$app->params['urlFrontend']['NewsModel'];
    }

    /**
     * @inheritdoc
     */
    public function getToValidateStatus()
    {
        return self::NEWS_WORKFLOW_STATUS_DAVALIDARE;
    }

    /**
     * @inheritdoc
     */
    public function getValidatedStatus()
    {
        return self::NEWS_WORKFLOW_STATUS_VALIDATO;
    }

    /**
     * @inheritdoc
     */
    public function getDraftStatus()
    {
        return self::NEWS_WORKFLOW_STATUS_BOZZA;
    }

    /**
     * @inheritdoc
     */
    public function getValidatorRole()
    {
        return 'VALIDATORE_NEWS';
    }

    /**
     * @inheritdoc
     */
    public function getPluginWidgetClassname()
    {
        return WidgetIconNewsDashboard::class;
    }

    /**
     * @inheritdoc
     */
    public function isCommentable()
    {
        return $this->comments_enabled;
    }

    /**
     * Verifica la presenza dell'immagine.
     *
     * @param   $url
     * @return  bool
     */
    protected function image_exists($url)
    {
        try {
            if (getimagesize(Yii::$app->getBasePath() . '/web' . $url)) {
                return true;
            }
        } catch (\Exception $e) {
            ;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return $this->titolo;
    }

    /**
     * @inheritdoc
     */
    public function getShortDescription()
    {
        return $this->descrizione_breve;
    }

    /**
     * @inheritdoc
     */
    public function getDescription($truncate)
    {
        $ret = $this->descrizione;

        if ($truncate) {
            $ret = $this->__shortText($this->descrizione, 200);
        }

        return $ret;
    }

    /**
     * @inheritdoc
     */
    public function getStatsToolbar($disableLink = false)
    {
        $panels = [];
        $count_comments = 0;
        return $panels;
        try {
            $panels = parent::getStatsToolbar($disableLink);
            $filescount = !is_null($this->newsImage) ? $this->getFileCount() - 1 : $this->getFileCount();
            $panels = ArrayHelper::merge($panels,
                StatsToolbarPanels::getDocumentsPanel($this, $filescount, $disableLink));
            if ($this->isCommentable()) {
                $commentModule = \Yii::$app->getModule('comments');
                if ($commentModule) {
                    /** @var \open20\amos\comments\AmosComments $commentModule */
                    $count_comments = $commentModule->countComments($this);
                }
                $panels = ArrayHelper::merge($panels,
                    StatsToolbarPanels::getCommentsPanel($this, $count_comments, $disableLink));
            }
            $reportCount = ReportUtil::retrieveReportsCount(get_class($this), $this->id);
            $panels = ArrayHelper::merge($panels,
                StatsToolbarPanels::getReportsPanel($this, $reportCount, $disableLink));
        } catch (\Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
        }
        return $panels;
    }

    /**
     * @inheritdoc
     */
    public function getPublicatedFrom()
    {
        return $this->data_pubblicazione;
    }

    /**
     * @inheritdoc
     */
    public function getPublicatedAt()
    {
        return $this->data_rimozione;
    }

    /**
     * This is the relation between the news and the related category.
     * Return an ActiveQuery related to NewsCategorie model.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(\open20\amos\news\models\NewsCategorie::class, ['id' => 'news_categorie_id']);
    }

    /**
     * @return string The url to view of this model
     */
    public function getFullViewUrl()
    {
        if (!empty($this->usePrettyUrl) && ($this->usePrettyUrl == true) && $this->hasMethod('getPrettyUrl')) {
            return Url::toRoute(["/" . $this->getViewUrl() . "/" . $this->id . "/" . $this->getPrettyUrl()]);
        } else if (!empty($this->useFrontendView) && ($this->useFrontendView == true) && $this->hasMethod('getBackendobjectsUrl')) {
            return $this->getBackendobjectsUrl();
        } else {
            return Url::toRoute(["/" . $this->getViewUrl(), "id" => $this->id]);
        }
    }

    /**
     * @inheritdoc
     */
    public function getFullFrontendViewUrl()
    {
        $url = $this->getFrontendViewUrl();
        if (strpos($url, '{Id}')) {
            $url = str_replace("{Id}", $this->id, $url);
        }
        if (strpos($url, '{Slug}')) {
            $url = str_replace("{Slug}", $this->slug, $url);
        }

        return Url::toRoute(["/" . $url]);
    }

    /**
     * @return mixed
     */
    public function getGrammar()
    {
        return new NewsGrammar();
    }

    /**
     * @return array list of statuses that for cwh is validated
     */
    public function getCwhValidationStatuses()
    {
        return [$this->getValidatedStatus()];
    }

    /**
     * @inheritdoc
     */
    public function setDetailScenario()
    {
        $moduleNews = \Yii::$app->getModule(AmosNews::getModuleName());
        if ($moduleNews->hidePubblicationDate == true) {
            $this->setScenario(News::SCENARIO_DETAILS_HIDE_PUBBLICATION_DATE);
        } else {
            $this->setScenario(News::SCENARIO_DETAILS);
        }
    }

    /**
     * @return array
     */
    public function getStatusToRenderToHide()
    {
        $statusToRender = [
            News::NEWS_WORKFLOW_STATUS_BOZZA => AmosNews::t('amosnews', 'Modifica in corso'),
        ];
        $isCommunityManager = false;
        if (\Yii::$app->getModule('community')) {
            $isCommunityManager = \open20\amos\community\utilities\CommunityUtil::isLoggedCommunityManager();
            if ($isCommunityManager) {
                $isCommunityManager = true;
            }
        }
        // if you are a community manager a validator/facilitator or ADMIN you Can publish directly
        if (Yii::$app->user->can('NewsValidate', ['model' => $this]) || Yii::$app->user->can('ADMIN') || $isCommunityManager) {
            $statusToRender = ArrayHelper::merge(
                $statusToRender,
                [
                    News::NEWS_WORKFLOW_STATUS_VALIDATO => AmosNews::t('amosnews', 'Pubblicata')
                ]
            );
            $hideDraftStatus = [];
        } else {
            $statusToRender = ArrayHelper::merge(
                $statusToRender,
                [
                    News::NEWS_WORKFLOW_STATUS_DAVALIDARE => AmosNews::t('amosnews', 'Richiedi pubblicazione'),
                ]
            );
            $hideDraftStatus[] = News::NEWS_WORKFLOW_STATUS_VALIDATO;
        }

        return [
            'statusToRender' => $statusToRender,
            'hideDraftStatus' => $hideDraftStatus
        ];
    }

    /**
     * @return type
     */
    public function getSchema()
    {
        $news = new \simialbi\yii2\schemaorg\models\NewsArticle();
        $publisher = new \simialbi\yii2\schemaorg\models\Organization();
        $author = new \simialbi\yii2\schemaorg\models\Person();
        $userProfile = $this->createdUserProfile;
        if (!is_null($userProfile)) {
            $logo = new \simialbi\yii2\schemaorg\models\ImageObject();
            $publisher->name = $userProfile->nomeCognome;
            $img = $userProfile->userProfileImage;
            if (!is_null($img)) {
                $logo->url = $img->getWebUrl(false, true);
            }
            $publisher->logo = $logo;
            $author->name = $userProfile->nomeCognome;
        }
        $image = new \simialbi\yii2\schemaorg\models\ImageObject();
        $newsImage = $this->getNewsImage();
        if (!empty($newsImage)) {
            $image->url = $newsImage->getWebUrl(false, true);
        }
        $news->author = $author;
        $news->datePublished = $this->data_pubblicazione;
        $news->headline = substr($this->getShortDescription(), 0, 110);
        $news->image = $image;
        $news->publisher = $publisher;

        \simialbi\yii2\schemaorg\helpers\JsonLDHelper::add($news);

        return \simialbi\yii2\schemaorg\helpers\JsonLDHelper::render();
    }

    /**
     * @return string
     */
    public function getModelUrl()
    {
        if ($this->primo_piano) {
            return \Yii::$app->params['platform']['frontendUrl'] . $this->getFullFrontendViewUrl();
        }

        return \Yii::$app->params['platform']['backendUrl'] . $this->getFullViewUrl();
    }

    /**
     * @inheritdoc
     */
    public function getFieldVisibleByGuest()
    {
        return $this->tableName() . '.primo_piano';
    }

    /**
     * *** SiteManagementSlider
     * @return string
     */
    public function getTitleSlider()
    {
        return 'News ' . $this->id;
    }

    /**
     * Method to create relationship between News and related News
     *
     * @return void
     */
    public function createNewsRelatedNewsMm()
    {
        $post_request = \Yii::$app->request->post('News', '');

        if (isset($post_request['news_related_news_mm'])) {
            foreach ($post_request['news_related_news_mm'] as $key => $value) {
                $news_related_news_mm = new NewsRelatedNewsMm;
                $news_related_news_mm->news_id = $this->id;
                $news_related_news_mm->related_news_id = $value;
                $news_related_news_mm->save();
            }
        }
    }

    /**
     * Method to update relationship between News and related News
     *
     * @return void
     */
    public function updateNewsRelatedNewsMm()
    {
        $post_request = \Yii::$app->request->post('News', '');
        if (isset($post_request['news_related_news_mm'])) {
            $this->deleteNewsRelatedNewsMm();
            $this->createNewsRelatedNewsMm();
        }
    }

    /**
     * Method to delete the relationship between News and related News
     *
     * @return void
     */
    public function deleteNewsRelatedNewsMm()
    {
        $news_related_news_mm = $this->newsRelatedNewsMm;
        foreach ($news_related_news_mm as $key => $value) {
            $value->delete();
        }
    }

    /**
     * Method to create the relationship between News and related Documenti
     *
     * @return void
     */
    public function createNewsRelatedDocumentiMm()
    {
        $post_request = \Yii::$app->request->post('News', '');
        if (isset($post_request['news_related_documenti_mm'])) {
            foreach ($post_request['news_related_documenti_mm'] as $key => $value) {
                $news_related_documenti_mm = new NewsRelatedDocumentiMm;
                $news_related_documenti_mm->news_id = $this->id;
                $news_related_documenti_mm->related_documenti_id = $value;
                $news_related_documenti_mm->save();
            }
        }
    }

    /**
     * Method to update the relationship between News and related Documenti
     *
     * @return void
     */
    public function updateNewsRelatedDocumentiMm()
    {
        $post_request = \Yii::$app->request->post('News', '');
        if (isset($post_request['news_related_documenti_mm'])) {
            $this->deleteNewsRelatedDocumentiMm();
            $this->createNewsRelatedDocumentiMm();
        }
    }

    /**
     * Method to delete the relationship between News and related Documenti
     *
     * @return void
     */
    public function deleteNewsRelatedDocumentiMm()
    {
        $news_related_documenti_mm = $this->newsRelatedDocumentiMm;
        foreach ($news_related_documenti_mm as $key => $value) {
            $value->delete();
        }
    }

    /**
     * Method to delete the relationship between News and related Event
     *
     * @return void
     */
    public function createNewsRelatedEventMm()
    {
        if (!empty($this->newsRelatedEventMmAttribute)) {
            foreach ($this->newsRelatedEventMmAttribute as $value) {
                $news_related_event_mm = new NewsRelatedEventMm;
                $news_related_event_mm->news_id = $this->id;
                $news_related_event_mm->event_id = $value;
                $news_related_event_mm->save();
            }
        }
    }

    /**
     * Method to update the relationship between News and related Documenti
     *
     * @return void
     */
    public function updateNewsRelatedEventMm()
    {
        $this->deleteNewsRelatedEventMm();
        $this->createNewsRelatedEventMm();
    }

    /**
     * Method to delete the relationship between News and related Documenti
     *
     * @return void
     */
    public function deleteNewsRelatedEventMm()
    {
        $newsToDelete = NewsRelatedEventMm::find()->andWhere(['news_id' => $this->id])->all();
        foreach ($newsToDelete as $news) {
            $news->delete();
        }
    }

    /**
     * Method to create the realationship between News and Agid Service
     *
     * @return void
     */
    public function createNewsRelatedAgidServiceMm()
    {
        $post_request = \Yii::$app->request->post('News', '');
        if (isset($post_request['news_related_agid_service_mm'])) {
            foreach ($post_request['news_related_agid_service_mm'] as $key => $value) {
                $news_related_agid_service_mm = new NewsRelatedAgidServiceMm;
                $news_related_agid_service_mm->news_id = $this->id;
                $news_related_agid_service_mm->related_agid_service_id = $value;
                $news_related_agid_service_mm->save();
            }
        }
    }

    /**
     * Method to update the retalionship between News and Agid Service
     *
     * @return void
     */
    public function updateNewsRelatedAgidServiceMm()
    {
        $post_request = \Yii::$app->request->post('News', '');
        if (isset($post_request['news_related_agid_service_mm'])) {
            $this->deleteNewsRelatedAgidServiceMm();
            $this->createNewsRelatedAgidServiceMm();
        }
    }

    /**
     * Method to delete the relationship between Newsand related Documenti
     *
     * @return void
     */
    public function deleteNewsRelatedAgidServiceMm()
    {
        $news_related_agid_service_mm = $this->newsRelatedAgidServiceMm;
        foreach ($news_related_agid_service_mm as $key => $value) {
            $value->delete();
        }
    }

    /**
     * Method to crete relationship between News and Agid Person
     *
     * @return void
     */
    public function createNewsAgidPersonMm()
    {
        $post_request = \Yii::$app->request->post('News', '');
        if (isset($post_request['news_agid_person_mm'])) {
            foreach ($post_request['news_agid_person_mm'] as $key => $value) {
                $news_agid_person_mm = new NewsAgidPersonMm;
                $news_agid_person_mm->news_id = $this->id;
                $news_agid_person_mm->agid_person_id = $value;
                $news_agid_person_mm->save();
            }
        }
    }

    /**
     * Method to update the relationship between News and Agid Person
     *
     * @return void
     */
    public function updateNewsAgidPersonMm()
    {
        $post_request = \Yii::$app->request->post('News', '');
        if (isset($post_request['news_agid_person_mm'])) {
            $this->deleteNewsAgidPersonMm();
            $this->createNewsAgidPersonMm();
        }
    }

    public function saveOtherNewsCategories(){
        $otherCategories = $this->otherCategories;
        NewsCategorieMm::deleteAll(['news_id' => $this->id]);
        foreach ($otherCategories as $category_id){
            $categoryMm = new NewsCategorieMm();
            $categoryMm->news_categorie_id = $category_id;
            $categoryMm->news_id = $this->id;
            $categoryMm->save(false);
        }
    }

    /**
     *
     */
    public function loadOtherNewsCategories(){
        $otherCategories = $this->otherNewsCategories;
        $this->otherCategories = $otherCategories;

    }

    /**
     * Method to delete the relationship between News and Agid Person
     *
     * @return void
     */
    public function deleteNewsAgidPersonMm()
    {
        $news_agid_person_mm = $this->newsAgidPersonMm;
        foreach ($news_agid_person_mm as $key => $value) {
            $value->delete();
        }
    }

    /**
     * @return bool
     */
    public function sendNotification()
    {
        return $this->newsModule->newsModelsendNotification;
    }

    /**
     *
     * @param type $insert
     * @param type $changedAttributes
     * @return type
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($this->newsModule->enableRelateEvents){
            $this->updateNewsRelatedEventMm();
        }

        if (!empty($this->request_publish_on_hp) && $this->request_publish_on_hp == 1) {
            if (
                $this->status == self::NEWS_WORKFLOW_STATUS_VALIDATO
                && $this->isCommunityManagerLoggedUserInThisNews()
            ) {
                $whoCanPublishIds = \yii\helpers\ArrayHelper::merge(
                    \Yii::$app->authManager->getUserIdsByRole('ADMIN'),
                    \Yii::$app->authManager->getUserIdsByRole('NewsPublishOnHomePage')
                );

                NewsUtility::sendEmailsForPublishOnHomePageRequest($whoCanPublishIds, $this);
            }
        }
    }

    /**
     * @return bool
     */
    public function isCommunityManagerLoggedUserInThisNews()
    {
        $ret = false;

        $moduleCwh = \Yii::$app->getModule('cwh');
        if ($moduleCwh && $this->isNewRecord) {
            $scope = $moduleCwh->getCwhScope();
            if (isset($scope['community']) && !empty($scope['community'])) {
                $communityScoped = $scope['community'];
                $community = new Community();
                $community->id = $communityScoped;
                $ret = \open20\amos\community\utilities\CommunityUtil::hasRole($community);
            }
        }

        if (!$ret) {
            $targets = $this->getTargets();
            if (!empty($targets) && is_array($targets)) {
                $communityArray = explode('-', reset($targets));
                if (isset($communityArray[0]) && $communityArray[0] == 'community') {
                    if (isset($communityArray[1])) {
                        $comm = Community::findOne(['id' => $communityArray[1]]);
                        $moduleCommunity = Yii::$app->getModule('community');
                        if (!empty($comm) && !empty($moduleCommunity)) {
                            $ret = \open20\amos\community\utilities\CommunityUtil::isManagerLoggedUser($comm);
                        }
                    }
                }
            }
        }

        return $ret;
    }



    /**
     * @param $attribute
     * @throws \ReflectionException
     */
    public function checkImageRequired($attribute){
        if($this->newsModule->enableAgid) {

            $csrfParam = \Yii::$app->request->csrfParam;
            $csrf = \Yii::$app->request->post($csrfParam);
            $dataImage = \Yii::$app->session->get($csrf);

            $fileDataBankExist = false;
            if(!empty($dataImage)) {
                $fileDataBankExist = true;
            }

            if (!$fileDataBankExist) {
                $reflectionClass = new \ReflectionClass($this);
                $classname = $reflectionClass->getShortName();
                foreach ((array)$_FILES[$classname]['name'] as $attributeName => $filename) {
                    if ($attribute == $attributeName) {
                        if (empty($filename)) {
                            if (empty($this->$attribute)) {
                                $this->addError($attribute, AmosNews::t('amosnews', "Il campo immagine è obbligatorio."));
                            }
                        }
                    }
                }
            }

        }
    }

    /**
     * Check if module comments supports moderation
     * @return bool
     */
    public function checkCommentsModeration()
    {
        $moduleComments = \Yii::$app->getModule('comments');
        if ($moduleComments) {
            if (method_exists($moduleComments, 'checkModerationStatus')) {
                return $moduleComments->checkModerationStatus($this);
            }
        }

        return false;
    }

    /**
     * If true, can render CommentsBellNotificationWidget.
     * @return bool
     */
    public function canShowCommentsNotificationBellWidget()
    {
        /** @var \open20\amos\comments\AmosComments $moduleComments */
        $moduleComments = Yii::$app->getModule('comments');

        return $moduleComments && class_exists('open20\amos\comments\widgets\CommentsBellNotificationWidget') && $this->comments_enabled;
    }

    /**
     * Show if the content is visible
     * used in particular to know if attachments file are visible
     * @return boolean
     */
    public function isContentPublic(){
        // isContentPublished si trova nel contentModel
        $ok = $this->isContentPublished();
        if($this->primo_piano && $ok){
            return true;
        }
        return false;
    }

    /**
     * This method returns the name of the publication date begin field
     * @return string
     */
    public function getPublicatedFromField() {
        return self::tableName() . '.data_pubblicazione';
    }

    /**
     * This method returns the name of the publication date end field
     * @return string
     */
    public function getPublicatedAtField() {
        return self::tableName() . '.data_rimozione';
    }

    /**
     * This method returns true if the publication date fields are datetime instead of only date fields
     * @return bool
     */
    public function theDatesAreDatetime() {
        if (AmosNews::instance()->dateFormatNews == DateControl::FORMAT_DATETIME) {
            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function getValidatorUsersId()
    {
        $validatori = \Yii::$app->getAuthManager()->getUserIdsByRole('AMMINISTRATORE_NEWS');

        return $validatori;
    }
}
