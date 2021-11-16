<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\models
 * @category   CategoryName
 */

namespace open20\amos\documenti\models;

use open20\amos\attachments\models\EmptyContentModel;
use open20\amos\attachments\behaviors\FileBehavior;
use open20\amos\attachments\models\File;
use open20\amos\comments\models\CommentInterface;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\interfaces\ContentModelInterface;
use open20\amos\core\interfaces\ModelDocumentInterface;
use open20\amos\core\interfaces\ModelImageInterface;
use open20\amos\core\interfaces\NewsletterInterface;
use open20\amos\core\interfaces\ViewModelInterface;
use open20\amos\core\interfaces\WorkflowMetadataInterface;
use open20\amos\core\utilities\DuplicateContentUtility;
use open20\amos\core\views\toolbars\StatsToolbarPanels;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\i18n\grammar\DocumentsGrammar;
use open20\amos\documenti\i18n\grammar\FoldersGrammar;
use open20\amos\documenti\utility\DocumentsUtility;
use open20\amos\documenti\widgets\icons\WidgetIconDocumentiDashboard;
use open20\amos\notificationmanager\behaviors\NotifyBehavior;
use open20\amos\seo\behaviors\SeoContentBehavior;
use open20\amos\workflow\behaviors\WorkflowLogFunctionsBehavior;
use raoul2000\workflow\base\SimpleWorkflowBehavior;
use raoul2000\workflow\base\WorkflowException;
use Yii;
use yii\base\Exception;
use yii\db\ActiveQuery;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\log\Logger;
use open20\amos\seo\interfaces\SeoModelInterface;
use yii\helpers\Inflector;

/**
 * Class Documenti
 *
 * This is the model class for table "documenti".
 *
 * @method \cornernote\workflow\manager\components\WorkflowDbSource getWorkflowSource()
 * @method \yii\db\ActiveQuery hasOneFile($attribute = 'file', $sort = 'id')
 * @method \yii\db\ActiveQuery hasMultipleFiles($attribute = 'file', $sort = 'id')
 * @method string|null getRegolaPubblicazione()
 * @method array getTargets()
 *
 * @property \open20\amos\documenti\models\Documenti[] $allParents
 * @property \open20\amos\documenti\models\Documenti[] $allDocumentVersions
 * @property string $versionInfo
 *
 * @package open20\amos\documenti\models
 */
class Documenti extends \open20\amos\documenti\models\base\Documenti implements ContentModelInterface, CommentInterface,
    ViewModelInterface, WorkflowMetadataInterface, ModelDocumentInterface, ModelImageInterface, NewsletterInterface, SeoModelInterface
{
    // Workflow ID
    const DOCUMENTI_WORKFLOW = 'DocumentiWorkflow';
    // Workflow states IDS
    const DOCUMENTI_WORKFLOW_STATUS_BOZZA       = 'DocumentiWorkflow/BOZZA';
    const DOCUMENTI_WORKFLOW_STATUS_DAVALIDARE  = 'DocumentiWorkflow/DAVALIDARE';
    const DOCUMENTI_WORKFLOW_STATUS_VALIDATO    = 'DocumentiWorkflow/VALIDATO';
    const DOCUMENTI_WORKFLOW_STATUS_NONVALIDATO = 'DocumentiWorkflow/NONVALIDATO';

    /**
     * Create Document scenario
     */
    const SCENARIO_CREATE = 'document_create';
    const SCENARIO_UPDATE = 'document_update';
    const SCENARIO_FOLDER = 'scenario_folder';

    /**
     * All the scenarios listed below are for the wizard.
     */
    const SCENARIO_INTRODUCTION = 'scenario_introduction';
    const SCENARIO_DETAILS      = 'scenario_details';
    const SCENARIO_PUBLICATION  = 'scenario_publication';
    const SCENARIO_SUMMARY      = 'scenario_summary';

    /** Secenarios for hide pubblication date */
    const SCENARIO_DETAILS_HIDE_PUBBLICATION_DATE = 'scenario_details_hide_pubblication_date';
    const SCENARIO_CREATE_HIDE_PUBBLICATION_DATE  = 'scenario_create_hide_pubblication_date';
    const SCENARIO_UPDATE_HIDE_PUBBLICATION_DATE  = 'scenario_update_hide_pubblication_date';
    // Is folder constants
    const IS_FOLDER   = 1;
    const IS_DOCUMENT = 0;
    //AGID roles

    const REDACTOR_ROLE = 'REDACTOR';

    /**
     * @var string $regola_pubblicazione Regola di pubblicazione
     */
    public $regola_pubblicazione;

    /**
     * @var string $destinatari Destinatari
     */
    public $destinatari;

    /**
     * @var string $validatori Validatori
     */
    public $validatori;

    /**
     * @var string $distance Distanza
     */
    public $distance;

    /**
     * @var string $destinatari_pubblicazione Destinatari pubblicazione
     */
    public $destinatari_pubblicazione;

    /**
     * @var string $destinatari_notifiche Destinatari notifiche
     */
    public $destinatari_notifiche;

    /**
     * @var mixed $file File
     */
    public $file;

    /**
     * @var File $documentMainFile
     */
    private $documentMainFile;

    /**
     * @var File[] $documentAttachments
     */
    private $documentAttachments;
    private static $categories;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if ($this->isNewRecord) {
            $this->is_folder = Documenti::IS_DOCUMENT;
            $this->status    = $this->getWorkflowSource()->getWorkflow(self::DOCUMENTI_WORKFLOW)->getInitialStatusId();
            if (!empty($this->documentsModule)) {
                if ($this->documentsModule->hidePubblicationDate) {
                    // the news will be visible forever
                    $this->data_rimozione = '9999-12-31';
                }
                $this->data_pubblicazione = date("Y-m-d");
            }
            if ($this->documentsModule && $this->documentsModule->enableDocumentVersioning && !$this->is_folder) {
                $this->version = 1;
            }
            if (($this->scenario == self::SCENARIO_CREATE) || ($this->scenario == self::SCENARIO_DETAILS) || ($this->scenario
                == self::SCENARIO_CREATE_HIDE_PUBBLICATION_DATE) || ($this->scenario == self::SCENARIO_DETAILS_HIDE_PUBBLICATION_DATE)) {
                $query = new Query();
                if (!self::$categories) {
                    self::$categories = $query->from(DocumentiCategorie::tableName())->all();
                }
                $countCategories = count(self::$categories);
                if ($countCategories == 1) {
                    $this->documenti_categorie_id = self::$categories[0]['id'];
                }
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = ArrayHelper::merge(parent::rules(),
                [
                [['destinatari_pubblicazione', 'destinatari_notifiche', 'count_link_download'], 'safe'],
                [['documentMainFile'],
                    'required',
                    'when' => function ($model) {
                        // pr($model->drive_file_id);die;
                        return (!$this->documentsModule->documentsOnlyText && (trim($model->link_document) == '' && empty($model->drive_file_id)));
                    },
                    'whenClient' => "function(attribute, value) {
                    return (".(!$this->documentsModule->documentsOnlyText ? "true" : "false")." && ($('#documenti-link_document').val() == '') && $('#drive-file-id').val() == '');
                }",
                    'message' => AmosDocumenti::t('amosdocumenti', '#main_document_required')
                ],
                [['documentAttachments'],
                    'file',
                    'extensions' => (!empty($this->documentsModule)) ? $this->documentsModule->whiteListFilesExtensions : '',
                    'checkExtensionByMimeType' => false,
                    'maxFiles' => 0
                ],
                [['documentMainFile'],
                    'file',
                    'skipOnEmpty' => true,
                    'extensions' => (!empty($this->documentsModule)) ? $this->documentsModule->whiteListFilesExtensions : '',
                    'checkExtensionByMimeType' => false,
                    'maxFiles' => 1,
                ],
                [['link_document'], 'url', 'skipOnEmpty' => function ($model) {
                        return $model->link_document == '';
                    }
                ],
        ]);

        if ($this->scenario != self::SCENARIO_DETAILS_HIDE_PUBBLICATION_DATE && $this->scenario != self::SCENARIO_CREATE_HIDE_PUBBLICATION_DATE
            && $this->scenario != self::SCENARIO_UPDATE_HIDE_PUBBLICATION_DATE) {
            $rules = ArrayHelper::merge($rules,
                    [
                    [['data_pubblicazione', /* 'data_rimozione' */], 'required'],
                    // ['data_pubblicazione', 'compare', 'compareAttribute' => 'data_rimozione', 'operator' => '<='],
                    // ['data_rimozione', 'compare', 'compareAttribute' => 'data_pubblicazione', 'operator' => '>='],
            ]);
        }

        if ($this->data_pubblicazione != '' && $this->data_rimozione != '') {
            $rules = ArrayHelper::merge($rules,
                    [
                    ['data_rimozione', 'compare', 'compareAttribute' => 'data_pubblicazione', 'operator' => '>='],
            ]);
        }

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(),
                [
                'documentMainFile' => AmosDocumenti::t('amosdocumenti', '#MAIN_DOCUMENT'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {

        $parentScenarios                       = parent::scenarios();
        $scenarios                             = ArrayHelper::merge(
                $parentScenarios,
                [
                self::SCENARIO_CREATE => $parentScenarios[self::SCENARIO_DEFAULT]
                ]
        );
        $scenarios[self::SCENARIO_DETAILS]     = [
            'documentMainFile',
            'titolo',
            'sottotitolo',
            'descrizione_breve',
            'descrizione',
            'documenti_categorie_id',
            'data_pubblicazione',
            //'data_rimozione',
            'comments_enabled',
            'status'
        ];
        $scenarios[self::SCENARIO_PUBLICATION] = [
            'destinatari_pubblicazione',
            'destinatari_notifiche'
        ];
        $scenarios[self::SCENARIO_SUMMARY]     = [
            'status'
        ];
        $scenarios[self::SCENARIO_FOLDER]      = [
            'titolo',
            'data_pubblicazione',
            //'data_rimozione',
            'drive_file_id',
            'drive_file_mofified_at',
            'status'
        ];

        $scenarios[self::SCENARIO_UPDATE] = $scenarios[self::SCENARIO_CREATE];

        /** @var AmosDocumenti $documentiModule */
        $documentiModule = Yii::$app->getModule(AmosDocumenti::getModuleName());
        if ($documentiModule && $documentiModule->params['site_publish_enabled']) {
            $scenarios[self::SCENARIO_DETAILS][] = 'primo_piano';
        }
        if ($documentiModule && $documentiModule->params['site_featured_enabled']) {
            $scenarios[self::SCENARIO_DETAILS][] = 'in_evidenza';
        }

        $scenarios[self::SCENARIO_DETAILS_HIDE_PUBBLICATION_DATE] = $scenarios[self::SCENARIO_DETAILS];
        $scenarios[self::SCENARIO_CREATE_HIDE_PUBBLICATION_DATE]  = $scenarios[self::SCENARIO_CREATE];

        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(),
                [
                'workflow' => [
                    'class' => SimpleWorkflowBehavior::className(),
                    'defaultWorkflowId' => self::DOCUMENTI_WORKFLOW,
                    'propagateErrorsToModel' => true
                ],
                'workflowLog' => [
                    'class' => WorkflowLogFunctionsBehavior::className()
                ],
                'NotifyBehavior' => [
                    'class' => NotifyBehavior::className(),
                    'conditions' => ['is_folder' => 0],
                ],
                'fileBehavior' => [
                    'class' => FileBehavior::className()
                ],
                'SeoContentBehavior' => [
                    'class' => SeoContentBehavior::className(),
                    'imageAttribute' => null,
                    'defaultOgType' => 'article',
                ],
//            'googleDrive' => [
//                'class' => GoogleDriveBehavior::className()
//            ],
                'SeoContentBehavior' => [
                    'class' => SeoContentBehavior::className(),
                    'titleAttribute' => 'titolo',
                    'descriptionAttribute' => 'extended_description',
                    'imageAttribute' => null,
                ]
        ]);
    }

    /**
     * @inheritdoc
     */
    public function representingColumn()
    {
        return [
            'titolo'
        ];
    }

    /**
     * The method returns true if this object is a folder
     * @return bool
     */
    public function isFolder()
    {
        return ($this->is_folder == static::IS_FOLDER);
    }

    /**
     * The method returns true if this object is a folder
     * @return bool
     */
    public function isDocument()
    {
        return ($this->is_folder == static::IS_DOCUMENT);
    }

    /**
     * @inheritdoc
     */
    public function getGridViewColumns()
    {
        return [
            'type' => [
                'label' => AmosDocumenti::t('amosdocumenti', '#type'),
                'format' => 'html',
                'value' => function ($model) {
                    $title = AmosDocumenti::t('amosdocumenti', 'Documenti');
                    if ($model->is_folder) {
                        $title = AmosDocumenti::t('amosdocumenti', '#folder');
                    } else {
                        $documentFile = $model->getDocumentMainFile();
                        if ($documentFile) {
                            $title = $documentFile->type;
                        }
                    }

                    $icon = DocumentsUtility::getDocumentIcon($model, true);
                    return AmosIcons::show($icon, ['title' => $title], 'dash');
                },
                'headerOptions' => [
                    'id' => AmosDocumenti::t('amosdocumenti', '#type'),
                ],
                'contentOptions' => [
                    'headers' => AmosDocumenti::t('amosdocumenti', '#type'),
                ]
            ],
            'titolo' => [
                'attribute' => 'titolo',
                'headerOptions' => [
                    'id' => 'titolo'
                ],
                'contentOptions' => [
                    'headers' => 'titolo'
                ]
            ],
//            'descrizione' => [
//                'attribute' => 'descrizione',
//                'format' => 'html',
//                'headerOptions' => [
//                    'id' => 'descrizione'
//                ],
//                'contentOptions' => [
//                    'headers' => 'descrizione'
//                ]
//            ],
            'created_by' => [
                'attribute' => 'createdUserProfile',
                'headerOptions' => [
                    'id' => AmosDocumenti::t('amosdocumenti', 'creato da'),
                ],
                'contentOptions' => [
                    'headers' => AmosDocumenti::t('amosdocumenti', 'creato da'),
                ]
            ],
            'data_pubblicazione' => [
                'attribute' => 'data_pubblicazione',
                //'label' => AmosDocumenti::t('amosdocumenti', '#label_start_publication_date'),
                'format' => 'date',
                'headerOptions' => [
                    'id' => AmosDocumenti::t('amosdocumenti', 'data pubblicazione'),
                ],
                'contentOptions' => [
                    'headers' => AmosDocumenti::t('amosdocumenti', 'data pubblicazione'),
                ]
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getToValidateStatus()
    {
        return self::DOCUMENTI_WORKFLOW_STATUS_DAVALIDARE;
    }

    /**
     * @inheritdoc
     */
    public function getValidatedStatus()
    {
        return self::DOCUMENTI_WORKFLOW_STATUS_VALIDATO;
    }

    /**
     * @inheritdoc
     */
    public function getDraftStatus()
    {
        return self::DOCUMENTI_WORKFLOW_STATUS_BOZZA;
    }

    /**
     * @inheritdoc
     */
    public function getValidatorRole()
    {
        return 'VALIDATORE_DOCUMENTI';
    }

    /**
     * @inheritdoc
     */
    public function getPluginWidgetClassname()
    {
        return WidgetIconDocumentiDashboard::className();
    }

    /**
     * @inheritdoc
     */
    public function getDocumentImage($onlyIconName = false)
    {
        return DocumentsUtility::getDocumentIcon($this, $onlyIconName);
    }

    /**
     * @inheritdoc
     */
    public function getDocument()
    {
        return $this->getDocumentMainFile();
    }

    /**
     * Getter for $this->documentMainFile;
     * @return File
     */
    public function getDocumentMainFile()
    {
        if (empty($this->documentMainFile)) {
            $this->documentMainFile = $this->hasOneFile('documentMainFile')->one();
        }

        return $this->documentMainFile;
    }

    /**
     * @param File $doc
     * @return File
     */
    public function setDocumentMainFile($doc)
    {
        return $this->documentMainFile = $doc;
    }

    /**
     * @param string $size
     * @param bool $protected
     * @param string $url
     * @param bool $absolute
     * @param bool $canCache
     * @return string
     */
    public function getDocumentMainFileUrl($size = 'original', $protected = true, $url = '/img/img_default.jpg',
                                           $absolute = false, $canCache = false)
    {
        $newsImage = $this->getDocumentMainFile();
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
     * @inheritdoc
     */
    public function getDocumentUrl($size = 'original', $protected = true, $url = '/img/img_default.jpg',
                                   $absolute = false, $canCache = false)
    {
        return $this->getDocumentMainFileUrl($size, $protected, $url, $absolute, $canCache);
    }

    /**
     * Getter for $this->documentAttachments;
     * @return File[]
     */
    public function getDocumentAttachments()
    {
        if (empty($this->documentAttachments)) {
            $this->documentAttachments = $this->hasMultipleFiles('documentAttachments', 'sort')->one();
        }
        return $this->documentAttachments;
    }

    /**
     * @param $attachments
     * @return File
     */
    public function setDocumentAttachments($attachments)
    {
        return $this->documentAttachments = $attachments;
    }

    /**
     * @inheritdoc
     */
    public function isCommentable()
    {
        return $this->comments_enabled;
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
        $panels         = [];
        $count_comments = 0;
        return $panels;
        try {
            $panels     = parent::getStatsToolbar($disableLink);
            $filescount = $this->getFileCount() - 1;
            $panels     = ArrayHelper::merge($panels,
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
     * @inheritdoc
     */
    public function getCategory()
    {
        return $this->hasOne(
                $this->documentsModule->model('DocumentiCategorie'), ['id' => 'documenti_categorie_id']
        );
    }

    /**
     * @return DocumentsGrammar|mixed
     */
    public function getGrammar()
    {
        if ($this->is_folder) {
            return new FoldersGrammar();
        } else {
            return new DocumentsGrammar();
        }
    }

    /**
     * @return array list of statuses that for cwh is validated
     */
    public function getCwhValidationStatuses()
    {
        return [$this->getValidatedStatus()];
    }

    /**
     * @return array
     */
    public function getAllParents()
    {
        $currentModel = $this;
        $parentsList  = [];
        while (!is_null($currentModel->parent)) {
            $parentsList  = array_merge(
                [$currentModel->parent], $parentsList
            );
            $currentModel = $currentModel->parent;
        }
        return $parentsList;
    }

    /**
     * Search all children recursively
     * @param array $children
     * @return array
     */
    public function getAllChildrens($children = [])
    {
        $currentModel = $this;
        $childrenList = $children;

        if (count($currentModel->children) == 0) {
            return [];
        }

        /** @var  $documento  Documenti */
        foreach ($currentModel->children as $documento) {
            $childrenList[] = $documento->id;
            $childrenList   = ArrayHelper::merge($childrenList, $documento->getAllChildrens());
        }

        $childrenList [] = $this->id;
        return $childrenList;
    }

    /**
     * Search all document children recursively
     * @return array
     */
    public function getAllDocumentChildrens()
    {
        $arrayChildren = [];
        $children      = $this->getAllChildrens();
        foreach ($children as $childId) {
            /** @var Documenti $documentiModel */
            $documentiModel = $this->documentsModule->createModel('Documenti');
            $child          = $documentiModel::findOne($childId);
            if (!$child->is_folder && $child->version_parent_id == null) {
                $arrayChildren[] = $child->id;
            }
        }
        return array_values($arrayChildren);
    }

    /**
     * Search all document in the first level
     * @return array
     */
    public function getDocumentChildrens()
    {
        $arrayChildren = [];
        $children      = $this->children;
        foreach ($children as $child) {
            if (!$child->is_folder && $child->version_parent_id == null) {
                $arrayChildren [] = $child->id;
            }
        }

        return $arrayChildren;
    }

    /**
     * This method checks if the model has children recursively.
     * It searches both documents and folders.
     * @return bool
     */
    public function hasChildren()
    {
        if ($this->isNewRecord || !$this->documentsModule->enableFolders) {
            return false;
        }
        $childrens   = $this->getAllChildrens();
        $hasChildren = (count($childrens) > 0);
        return $hasChildren;
    }

    /**
     * This method delete all document and folders recursively from this object to the tree leaves.
     * At the first error it returns false immediately and log the error in the application app.log.
     * @param bool $errorsByFlashMessages
     * @return bool
     * @throws \yii\db\StaleObjectException
     */
    public function deleteAllChildren($errorsByFlashMessages = true)
    {
        $children = $this->children;
        foreach ($children as $child) {
            if ($child->isFolder()) {
                $deleteChildrenOk = $child->deleteAllChildren($errorsByFlashMessages);
                if (!$deleteChildrenOk) {
                    return false;
                }
            }
            $childId    = $child->id;
            $childTitle = $child->titolo;
            $child->delete();
            if ($child->hasErrors()) {
                if ($errorsByFlashMessages) {
                    $errorMsg = ($child->isDocument() ? AmosDocumenti::t('amosdocumenti',
                            'Errore durante la cancellazione del documento') : AmosDocumenti::t('amosdocumenti',
                            'Errore durante la cancellazione della cartella')
                        )
                        ." '".$childTitle."'";
                    Yii::$app->getSession()->addFlash('danger', $errorMsg);
                } else {
                    Yii::getLogger()->log("Errore durante la cancellazione del documento con id '$childId'",
                        Logger::LEVEL_ERROR);
                    Yii::getLogger()->log($child->getErrors(), Logger::LEVEL_ERROR);
                }
                return false;
            }
        }
        return true;
    }

    /**
     * @return Documenti[]
     * @throws \yii\base\InvalidConfigException
     */
    public function getAllDocumentVersions()
    {
        /** @var Documenti $documentiModel */
        $documentiModel = $this->documentsModule->createModel('Documenti');

        /** @var ActiveQuery $query */
        $query = $documentiModel::find();
        if (is_null($this->version_parent_id)) {
            $query->andWhere(['or',
                ['version_parent_id' => $this->id],
                ['id' => $this->id]
            ]);
        } else {
            $query->andWhere(['or',
                ['version_parent_id' => $this->version_parent_id],
                ['id' => $this->version_parent_id]
            ]);
        }
        $query->orderBy(['version' => SORT_ASC]);
        $allModels = $query->all();
        return $allModels;
    }

    /**
     * @return Documenti
     */
    public function getLastOldDocumentVersion()
    {
        /** @var Documenti $documentiModel */
        $documentiModel = $this->documentsModule->createModel('Documenti');
        $query          = new Query();
        $query->from(self::tableName());
        $query->andWhere(['version_parent_id' => $this->id, 'deleted_at' => null]);
        $maxVersion     = $query->max('version');
        $document       = $documentiModel::find()->andWhere([
                'version_parent_id' => $this->id,
                'version' => $maxVersion
            ])->one();
        return $document;
    }

    /**
     * @return bool
     */
    public function makeNewDocumentVersion()
    {
        // If the document versioning is disabled do the standard operations.
        if (!$this->documentsModule->enableDocumentVersioning || $this->is_folder || ($this->version == -1)) {
            return true;
        }
        /** @var Documenti $documentiModel */
        $documentiModel                 = $this->documentsModule->createModel('Documenti');
        /** @var Documenti $newDocument */
        $newDocument                    = $this->documentsModule->createModel('Documenti');
        $newDocument->setAttributes($this->attributes);
        $newDocument->behaviors['workflow']->initStatus();
        $newDocument->version_parent_id = $this->id;
        $newDocument->version           = $this->version;
        $newDocument->detachBehavior('cwhBehavior');
        $ok                             = $newDocument->save(false);
        if ($ok) {
            $ok = $this->duplicateDocumentMainFile($newDocument);
        }
        if ($ok) {
            $ok = $this->duplicateDocumentAttachments($newDocument);
        }
        if ($ok) {
            $this->version = $this->getNextVersion();
            $ok            = $this->save(false);
        }
        return $ok;
    }

    /**
     * @return bool
     * @throws \Exception
     * @throws \yii\db\StaleObjectException
     */
    public function deleteNewDocumentVersion()
    {
        try {
            $lastOldDocument = $this->getLastOldDocumentVersion();
            if (is_null($lastOldDocument)) {
                return false;
            }
            // if you click su delete file before you click on "cancel version", you don't need to cancel the file because is already deleted
            $this->deleteThisDocumentMainFileRow();
            $ok = $lastOldDocument->duplicateDocumentMainFile($this);
            if ($ok) {
                $ok = $lastOldDocument->duplicateDocumentAttachments($this);
            }
            if ($ok) {
                $this->version = $lastOldDocument->version;
                $this->status  = $lastOldDocument->status;
                $this->behaviors['workflow']->initStatus();
                $ok            = $this->save(false);
            }
            if ($ok) {
                $lastOldDocument->delete();
                $ok = !$lastOldDocument->hasErrors();
            }
        } catch (Exception $e) {
            return false;
        }
        return $ok;
    }

    /**
     * @return false|int
     * @throws \Exception
     * @throws \yii\db\StaleObjectException
     */
    public function deleteThisDocumentMainFileRow()
    {
        /** @var Documenti $documentiModel */
        $documentiModel = $this->documentsModule->createModel('Documenti');
        $file           = File::findOne([
                'model' => $this->documentsModule->model('Documenti'),
                'attribute' => 'documentMainFile',
                'itemId' => $this->id
        ]);
        //if file
        $ok             = false;
        if ($file) {
            $ok = $file->delete();
        }
        return $ok;
    }

    /**
     * @return false|int
     * @throws \Exception
     * @throws \yii\db\StaleObjectException
     */
    public function deleteThisDocumentAttachmentRows()
    {
        $files = File::find()->andWhere([
                'model' => $this->documentsModule->model('Documenti'),
                'attribute' => 'documentMainFile',
                'itemId' => $this->id
            ])->all();
        if (count($files) == 0) {
            return true;
        }
        $allOk = true;
        foreach ($files as $file) {
            /** @var File $file */
            $ok = $file->delete();
            if (!$ok) {
                $allOk = false;
            }
        }
        return $allOk;
    }

    /**
     * @param Documenti $newDocument
     * @return bool
     */
    public function duplicateDocumentMainFile($newDocument)
    {
        $oldFile = File::findOne([
                'model' => $this->documentsModule->model('Documenti'),
                'attribute' => 'documentMainFile',
                'itemId' => $this->id
        ]);
        if (is_null($oldFile)) {
            return true;
        }
        $ok = $this->duplicateOldFile($oldFile, $newDocument->id);
        return $ok;
    }

    /**
     * @param Documenti $newDocument
     * @return bool
     */
    public function duplicateDocumentAttachments($newDocument)
    {
        $oldFiles = File::find()->andWhere([
                'model' => $this->documentsModule->model('Documenti'),
                'attribute' => 'documentAttachments',
                'itemId' => $this->id
            ])->all();
        if (count($oldFiles) == 0) {
            return true;
        }
        $allOk = true;
        foreach ($oldFiles as $oldFile) {
            /** @var File $oldFile */
            $ok = $this->duplicateOldFile($oldFile, $newDocument->id);
            if (!$ok) {
                $allOk = false;
            }
        }
        return $allOk;
    }

    /**
     * @param File $oldFile
     * @param int $newDocumentId
     * @return bool
     */
    private function duplicateOldFile($oldFile, $newDocumentId)
    {
        $file         = new File();
        $file->setAttributes($oldFile->attributes);
        $file->itemId = $newDocumentId;
        $ok           = $file->save(false);
        return $ok;
    }

    /**
     * @return int
     */
    public function getNextVersion()
    {
        $query = new Query();
        $query->from(self::tableName());
        $max   = $this->version;
        if (!is_null($this->version_parent_id)) {
            $query->andWhere(['version_parent_id' => $this->version_parent_id, 'deleted_at' => null]);
            $max = $query->max('version');
        }
        return (!$max ? 1 : ($max + 1));
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getVersionInfo()
    {
        return $this->getAttributeLabel('version').' '.$this->version.' - '.Yii::$app->formatter->asDatetime($this->updated_at);
    }

    /**
     *
     */
    public function setDetailScenario()
    {
        $moduleNews = \Yii::$app->getModule(AmosDocumenti::getModuleName());
        if ($moduleNews && $moduleNews->hidePubblicationDate == true) {
            $this->setScenario(Documenti::SCENARIO_DETAILS_HIDE_PUBBLICATION_DATE);
        } else {
            $this->setScenario(Documenti::SCENARIO_DETAILS);
        }
    }

    /**
     * @return bool
     */
    public function canValidate()
    {
        /** @var  $validatori array */
        $canValidate = false;
        $validatori  = $this->validatori;
        foreach ($validatori as $validatore) {
            $explode = explode('-', $validatore);
            if (count($explode) == 2 && $explode[0] == 'user') {
                if (\Yii::$app->user->id == $explode[1]) {
                    $canValidate = true;
                }
            }
        }
        return $canValidate;
    }

    /**
     * @inheritdoc
     */
    public function getMetadataLabelKey()
    {
        return ($this->is_folder ? 'labelFolder' : 'label');
    }

    /**
     * @inheritdoc
     */
    public function getMetadataButtonLabelKey()
    {
        return ($this->is_folder ? 'buttonLabelFolder' : 'buttonLabel');
    }

    /**
     * @inheritdoc
     */
    public function getMetadataDescriptionKey()
    {
        return ($this->is_folder ? 'descriptionFolder' : 'description');
    }

    /**
     * @inheritdoc
     */
    public function getMetadataButtonMessageKey()
    {
        return 'message';
    }

    /**
     * @return array
     */
    public function getStatusToRenderToHide()
    {

        $statusToRender     = [
            Documenti::DOCUMENTI_WORKFLOW_STATUS_BOZZA => AmosDocumenti::t('amosdocumenti', 'Modifica in corso'),
        ];
        $isCommunityManager = false;
        if (\Yii::$app->getModule('community')) {
            $isCommunityManager = \open20\amos\community\utilities\CommunityUtil::isLoggedCommunityManager();
            if ($isCommunityManager) {
                $isCommunityManager = true;
            }
        }

        // if you are a community manager a validator/facilitator or ADMIN you Can publish directly
        if (Yii::$app->user->can('DocumentValidate', ['model' => $this]) || Yii::$app->user->can('ADMIN') || $isCommunityManager) {
            $statusToRender  = ArrayHelper::merge(
                    $statusToRender,
                    [Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO => AmosDocumenti::t('amosnews', 'Pubblicata')]
            );
            $hideDraftStatus = [];
        } else {
            $statusToRender = ArrayHelper::merge(
                    $statusToRender,
                    [
                    Documenti::DOCUMENTI_WORKFLOW_STATUS_DAVALIDARE => AmosDocumenti::t('amosnews',
                        'Richiedi pubblicazione'),
                    ]
            );

            $hideDraftStatus[] = Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO;
        }

        return ['statusToRender' => $statusToRender, 'hideDraftStatus' => $hideDraftStatus];
    }

    /**
     * @inheritdoc
     */
    public function getModelImage()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getModelImageUrl($size = 'original', $protected = true, $url = '/img/img_default.jpg',
                                     $absolute = false, $canCache = false)
    {
        return "";
    }

    /**
     * @inheritdoc
     */
    public function sendCommunication()
    {
        return !$this->is_folder;
    }

    /**
     * @param $status
     */
    public function changeStatusFolderRecursive($status)
    {
        $children = $this->getAllChildrens();
        foreach ($children as $documentId) {
            $document = Documenti::findOne($documentId);
            if ($document->status != $status) {
                try {
                    $document->status = $status;
                    $document->save(false);
                } catch (WorkflowException $e) {
                    
                }
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function getViewUrl()
    {
        return 'documenti/documenti/view';
    }

    /**
     * @return array
     */
    public function getDuplicateContentAttachmentsAttributes()
    {
        return [
            'documentMainFile' => DuplicateContentUtility::ATTACHMENT_SINGLE,
            'documentAttachments' => DuplicateContentUtility::ATTACHMENT_MULTI
        ];
    }

    /**
     * @inheritdoc
     */
    public function getWorkflowBaseStatusLabel()
    {
        return AmosDocumenti::t('amosdocumenti', parent::getWorkflowBaseStatusLabel());
    }

    /**
     * @inheritdoc
     */
    public function getWorkflowStatusLabel()
    {
        return AmosDocumenti::t('amosdocumenti', parent::getWorkflowStatusLabel());
    }

    /**
     * @return string
     */
    public function newsletterOrderByField()
    {
        return 'data_pubblicazione';
    }

    /**
     * @return string
     */
    public function newsletterPublishedStatus()
    {
        return self::DOCUMENTI_WORKFLOW_STATUS_VALIDATO;
    }

    /**
     * @param string $searchParam
     * @param ActiveQuery $query
     * @return ActiveQuery
     */
    public function newsletterSearchFilter($searchParam, $query)
    {
        $query->innerJoinWith('documentiCategorie');

        if ($searchParam) {
            $query->andFilterWhere(['like', self::tableName().'.titolo', $searchParam]);
        }

        return $query;
    }

    /**
     * @return string
     */
    public function newsletterContentTitle()
    {
        return $this->titolo;
    }

    /**
     * @return string
     */
    public function newsletterContentTitleField()
    {
        return 'titolo';
    }

    /**
     * @return string
     */
    public function newsletterContentStatusField()
    {
        return 'status';
    }

    /**
     * @return array
     */
    public function newsletterContentGridViewColumns()
    {
        return [
            [
                'attribute' => 'titolo',
                'headerOptions' => [
                    'id' => $this->getAttributeLabel('titolo')
                ],
                'contentOptions' => [
                    'headers' => $this->getAttributeLabel('titolo')
                ],
                'format' => 'html',
                'value' => function ($model) {
                    /** @var Documenti $model */
                    /** @var \open20\amos\documenti\controllers\DocumentiController $controller */
                    $title = $model->titolo;
                    return $title;
                }
            ],
            'documentiCategorie.titolo' => [
                'attribute' => 'documentiCategorie.titolo',
                'label' => AmosDocumenti::t('amosdocumenti', 'Categoria')
            ],
            'status' => [
                'attribute' => 'status',
                'value' => function ($model) {
                    /** @var Documenti $model */
                    return $model->getWorkflowBaseStatusLabel();
                }
            ],
            'data_pubblicazione' => [
                'label' => AmosDocumenti::t('amosdocumenti', '#published_on'),
                'attribute' => 'data_pubblicazione',
                'value' => function ($model) {
                    /** @var Documenti $model */
                    return (is_null($model->data_pubblicazione)) ? AmosDocumenti::t('amosdocumenti', 'Subito') : Yii::$app->formatter->asDate($model->data_pubblicazione);
                }
            ]
        ];
    }

    /**
     * @return array
     */
    public function newsletterSelectContentsGridViewColumns()
    {
        return [
            [
                'attribute' => 'titolo',
                'headerOptions' => [
                    'id' => $this->getAttributeLabel('titolo')
                ],
                'contentOptions' => [
                    'headers' => $this->getAttributeLabel('titolo')
                ],
                'format' => 'html',
                'value' => function ($model) {
                    /** @var Documenti $model */
                    $title = $model->titolo;
                    return $title;
                }
            ],
        ];
    }

    /**
     * SeoModelInterface 
     */
    public function getSchema()
    {
        return null;
    }

    /**
     * 
     * @inheritdoc
     *
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($this->documentsModule->cmsSync) {
            $this->addStorageFile();
        }
    }

    /**
     * 
     * @inheritdoc
     */
    public function beforeDelete()
    {

        if ($this->documentsModule->cmsSync) {
            // find all attached files and delete them
            try {
                if (!$this->is_folder) {
                    $folderFiles = StorageFile::findOne(['hash_file' => $this->hasOneFile('documentMainFile')->one()->hash]);

                    if ($folderFiles) {

                    $folderFiles->is_deleted = true;
                    $folderFiles->update();
                    }

                    if (!empty($this->hasOneFile('documentMainFile')->one())) {
                        $file = File::findOne(['hash' => $this->hasOneFile('documentMainFile')->one()->hash]);
                    }
                    if (!empty($file)) {
                        $file->delete();
                    }
                } else {
                    $this->deleteStorageFolder($this->id);
                }
            } catch (\Exception $exception) {
                throw new Exception($exception->getMessage());
            }
        }

        return parent::beforeDelete();
    }

    /**
     * Add a new file based on the source to the storage system.

     * @param integer $folderId The id of the folder where the file should be stored in. (Allegati Redazionali, id = 13).
     * @param boolean $isHidden Should the file visible in the filemanager or not.
     * @throws Exception if an error happens an exception is thrown.
     */
    public function addStorageFile($folderId = 0, $isHidden = false)
    {
        try {
            $foldercms = $this->find()->where(['id' => $this->parent_id])->one();
            if (is_null($foldercms)) {
                $foldercms = $this->cmsFolderByConfigs();
            }
            if (!$this->is_folder) {
                $docMain = $this->hasOneFile('documentMainFile')->one();
                if (!is_null($docMain)) {

                    $modelstorage = StorageFile::findOne([
                            'hash_file' => $this->hasOneFile('documentMainFile')->one()->hash
                    ]);
                    if (is_null($modelstorage)) {
                        $modelstorage = new StorageFile();
                    }

                    $newName = implode([Inflector::slug($docMain->name).'_'.sprintf('%s',
                            hash('crc32b', uniqid($this->documentMainFile->name, true))), $docMain->type], '.');
                    $modelstorage->setAttributes([
                        'name_original' => $this->documentMainFile->name,
                        'name_new' => Inflector::slug($docMain->name),
                        'name_new_compound' => $newName,
                        'mime_type' => $docMain->mime, //$this->getDocumentMainFile()->mime,
                        'extension' => $docMain->type,
                        'folder_id' => !is_null($foldercms) ? $foldercms->folder_cms_id : (int) $folderId,
                        'hash_file' => $docMain->hash,
                        'hash_name' => sprintf('%s', hash('crc32b', uniqid($this->documentMainFile->name, true))),
                        'is_hidden' => $isHidden ? true : false,
                        'is_deleted' => false,
                        'file_size' => $this->documentMainFile->size,
                        'caption' => null,
                        'upload_timestamp' => time(),
                    ]);

                    $owner = new EmptyContentModel();
                    $file  = File::findOne([
                            'hash' => $this->hasOneFile('documentMainFile')->one()->hash,
                            'model' => $owner::className()
                    ]);

                    if (is_null($file)) {
                        $file = new File();
                    }

                    $attribute = 'file';
                    $filename  = substr($newName, 0, strrpos($newName, "."));

                    $file->name      = $filename;
                    $file->model     = $owner::className();
                    $file->itemId    = $owner->id;
                    $file->hash      = $docMain->hash;
                    $file->size      = $this->documentMainFile->size;
                    $file->type      = $docMain->type;
                    $file->mime      = $docMain->mime;
                    $file->attribute = $attribute;

                    if ($modelstorage->validate()) {
                        $modelstorage->save();
                        $file->save();
                    }


                    $this->file_cms_id = $modelstorage->id;
                    $this->updateAttributes(['file_cms_id' => $modelstorage->id]);
                }
            } else {
                $storageFolder = StorageFolder::findOne([
                        'id' => $this->folder_cms_id,
                ]);

                if (is_null($storageFolder)) {
                    $storageFolder = new StorageFolder();
                }

                $storageFolder->name             = $this->titolo;
                $storageFolder->parent_id        = !is_null($foldercms) ? $foldercms->folder_cms_id : $folderId;
                $storageFolder->timestamp_create = time();

                $storageFolder->save();

                $this->folder_cms_id = $storageFolder->id;
                $this->updateAttributes(['folder_cms_id' => $storageFolder->id]);
            }
        } catch (WorkflowException $e) {
            Yii::$app->session->addFlash('danger', $e->getMessage());
            throw new WorkflowException('danger', $e->getMessage());
        }
    }

    /**
     * 
     * @param string $name
     * @param int $folderId
     * @return \open20\amos\documenti\models\StorageFolder
     */
    public function addStorageFolder($name, $folderId = 0)
    {
        $condition = ['name' => $name, 'parent_id' => $folderId];

        $storageFolder = StorageFolder::findOne($condition);

        if (is_null($storageFolder)) {
            $storageFolder                   = new StorageFolder();
            $storageFolder->name             = $name;
            $storageFolder->parent_id        = $folderId;
            $storageFolder->timestamp_create = time();

            $storageFolder->save();
        }
        return $storageFolder;
    }

    /**
     * 
     * @param type $folderId
     * @return boolean
     */
    public function deleteStorageFolder($folderId)
    {
        // find all subfolders
        $matchingChildFolders = StorageFolder::find()->where(['parent_id' => $this->folder_cms_id])->asArray()->all();
        foreach ($matchingChildFolders as $matchingChildFolder) {
            $this->deleteStorageFolder($matchingChildFolder['id']);
        }

        // find all attached files and delete them
        $folderFiles = StorageFile::find()->where(['folder_id' => $this->folder_cms_id])->all();
        foreach ($folderFiles as $folderFile) {
            $files = File::find()->where(['hash' => $folderFile->hash_file])->all();
            foreach ($files as $file) {
                $file->delete();
            }
            $folderFile->is_deleted = true;
            $folderFile->update();
        }

        // delete folder
        $storageFolder = StorageFolder::findOne($this->folder_cms_id);
        if (!$storageFolder) {
            return false;
        }
        $storageFolder->is_deleted = true;
        return $storageFolder->update();
    }

    /**
     * 
     * @return Documenti | null
     */
    protected function cmsFolderByConfigs()
    {
        $folder = null;

        if ($this->documentsModule->cmsSync) {
            $documentiFolder   = $this->addStorageFolder($this->documentsModule->cmsBaseFolder);
            $contentTypeFolder = $this->addStorageFolder($this->documentiAgidContentType->name, $documentiFolder->id);
            $storageFolder     = $this->addStorageFolder($this->documentiAgidType->name, $contentTypeFolder->id);
            $folder            = new Documenti(['folder_cms_id' => $storageFolder->id]);
        }

        return $folder;
    }

    /**
     * Method to return UserProfile by user_id
     *
     * @param int $id
     * @return void
     */
    public function getUserProfileByUserId($id = null)
    {

        return \open20\amos\admin\models\UserProfile::find()->andWhere(['user_id' => $id])->one();
    }
}