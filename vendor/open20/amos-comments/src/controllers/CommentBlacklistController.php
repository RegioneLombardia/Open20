<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comments\controllers\base
 */

namespace open20\amos\comments\controllers;

use open20\amos\comments\AmosComments;
use open20\amos\comments\models\Comment;
use open20\amos\comments\models\CommentDashboard;
use open20\amos\comments\models\search\CommentBlacklistSearch;
use Yii;
use open20\amos\comments\models\CommentBlacklist;
use yii\data\ActiveDataProvider;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\module\BaseAmosModule;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\helpers\Html;
use open20\amos\core\helpers\T;
use yii\helpers\Url;


/**
 * Class CommentBlacklistController
 * CommentBlacklistController implements the CRUD actions for CommentBlacklist model.
 *
 * @property CommentBlacklist $model
 * @property object $modelSearch
 *
 * @package open20\amos\comments\controllers\base
 */
class CommentBlacklistController extends CrudController
{

    /**
     * @var string $layout
     */
    public $layout = 'main';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'import',
                            'export',
                        ],
                        'roles' => ['COMMENTBLACKLIST_ADMINISTRATOR']
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'scan-comment',
                        ],
                        'roles' => ['@']
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post', 'get']
                ]
            ]
        ]);
    }

    public function init()
    {
        $this->setModelObj(new CommentBlacklist());
        $this->setModelSearch(new CommentBlacklistSearch());

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Table')),
                'url' => '?currentView=grid'
            ],
            /*'list' => [
                'name' => 'list',
                'label' => AmosIcons::show('view-list') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'List')),         
                'url' => '?currentView=list'
            ],
            'icon' => [
                'name' => 'icon',
                'label' => AmosIcons::show('grid') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Icons')),           
                'url' => '?currentView=icon'
            ],
            'map' => [
                'name' => 'map',
                'label' => AmosIcons::show('map') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Map')),      
                'url' => '?currentView=map'
            ],
            'calendar' => [
                'name' => 'calendar',
                'intestazione' => '', //codice HTML per l'intestazione che verrà caricato prima del calendario,
                                      //per esempio si può inserire una funzione $model->getHtmlIntestazione() creata ad hoc
                'label' => AmosIcons::show('calendar') . Html::tag('p', BaseAmosModule::tHtml('amoscore', 'Calendari')),                                            
                'url' => '?currentView=calendar'
            ],*/
        ]);

        parent::init();
        $this->setUpLayout();
    }

    /**
     * Lists all CommentBlacklist models.
     * @return mixed
     */
    public function actionIndex($layout = NULL)
    {
        return $this->redirect(['/comments/comment-dashboard/netiquette']);
//        Url::remember();
//        $this->setDataProvider($this->modelSearch->search(Yii::$app->request->getQueryParams()));

//        return parent::actionIndex($layout);
    }

    /**
     * Creates a new CommentBlacklist model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->setUpLayout('form');
        $this->model = new CommentBlacklist();

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                Yii::$app->getSession()->addFlash('success', BaseAmosModule::t('amoscore', 'Item created'));
                return $this->redirect(['/comments/comment-dashboard/netiquette']);
            } else {
                Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('amoscore', 'Item not created, check data'));
            }
        }

        return $this->render('create', [
            'model' => $this->model,
            'fid' => NULL,
            'dataField' => NULL,
            'dataEntity' => NULL,
        ]);
    }

    /**
     * Creates a new CommentBlacklist model by ajax request.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateAjax($fid, $dataField)
    {
        $this->setUpLayout('form');
        $this->model = new CommentBlacklist();

        if (\Yii::$app->request->isAjax && $this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
//Yii::$app->getSession()->addFlash('success', BaseAmosModule::t('amoscore', 'Item created'));
                return json_encode($this->model->toArray());
            } else {
//Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('amoscore', 'Item not created, check data'));
            }
        }

        return $this->renderAjax('_formAjax', [
            'model' => $this->model,
            'fid' => $fid,
            'dataField' => $dataField
        ]);
    }

    /**
     * Updates an existing CommentBlacklist model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->setUpLayout('form');
        $this->model = $this->findModel($id);

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                Yii::$app->getSession()->addFlash('success', BaseAmosModule::t('amoscore', 'Item updated'));
                return $this->redirect(['/comments/comment-dashboard/netiquette']);
            } else {
                Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('amoscore', 'Item not updated, check data'));
            }
        }

        return $this->render('update', [
            'model' => $this->model,
            'fid' => NULL,
            'dataField' => NULL,
            'dataEntity' => NULL,
        ]);
    }

    /**
     * Deletes an existing CommentBlacklist model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->model = $this->findModel($id);
        if ($this->model) {
            $this->model->delete();
            if (!$this->model->hasErrors()) {
                Yii::$app->getSession()->addFlash('success', BaseAmosModule::t('amoscore', 'Element deleted successfully.'));
            } else {
                Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('amoscore', 'You are not authorized to delete this element.'));
            }
        } else {
            Yii::$app->getSession()->addFlash('danger', BaseAmosModule::tHtml('amoscore', 'Element not found.'));
        }
        return $this->redirect(['/comments/comment-dashboard/netiquette']);
    }

    /**
     * @return string|\yii\web\Response
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function actionImport()
    {
        if (Yii::$app->request->post() && !empty($_FILES['import-file']['tmp_name'])) {
            $inputFileName = $_FILES['import-file']['tmp_name'];
            $path = $_FILES['import-file']['name'];
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            $allowedExts = ['csv', 'xlsx', 'xls'];
            if(in_array($ext, $allowedExts)) {
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
                $worksheet = $spreadsheet->getActiveSheet();
                $highestRow = $worksheet->getHighestDataRow();
                for ($r = 2; $r <= $highestRow; ++$r) { // Skip first row
                    if (empty($worksheet->getCellByColumnAndRow(1, $r)->getValue()) || $worksheet->getCellByColumnAndRow(1, $r)->getValue() == '') {
                        break;
                    }

                    $wordToImport = trim($worksheet->getCellByColumnAndRow(1, $r)->getValue());
                    $wordExists = CommentBlacklist::findOne(['word' => $wordToImport]);
                    if (!$wordExists) {
                        $commentBlacklist = new CommentBlacklist();
                        $commentBlacklist->word = $wordToImport;
                        $commentBlacklist->save(false);
                    }
                }
                Yii::$app->getSession()->addFlash('success', AmosComments::t('amoscomments', 'Termini importati correttamente'));
                return $this->redirect(['/comments/comment-dashboard/netiquette']);
            } else {
                Yii::$app->getSession()->addFlash('danger', AmosComments::t('amoscomments', 'Estensione file non supportata'));
                return $this->render('import');
            }

        } else {
            return $this->render('import');
        }
    }

    /**
     * @return \yii\console\Response|\yii\web\Response
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     * @throws \PHPExcel_Writer_Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function actionExport()
    {
        $filePath = '/tmp/Blacklist.xls';

        // Elimina il file se esiste già
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Array per il file
        $xlsData = [];
        // Header
        $xlsData[] = [
            'Termine',
        ];

        // Recupera i dati
        $words = CommentBlacklist::find()->all();
        foreach ($words as $word) {
            $data = [
                $word->word,
            ];
            $xlsData[] = $data;
        }

        // Inizializza l'oggetto PHPExcel
        $objPHPExcel = new \PHPExcel();

        // Imposta lo stile per la cella "Termine"
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $borderStyle = [
            'borders' => [
                'bottom' => [
                    'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
                ],
            ],
        ];
        $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($borderStyle);

        // Pone i dati nella tabella attuale del file XLS
        $objPHPExcel->getActiveSheet()->fromArray($xlsData, NULL, 'A1');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save($filePath);

        return \Yii::$app->response->sendFile($filePath);
    }

    /**
     * @return bool[]
     * @throws \yii\base\InvalidConfigException
     */
    public function actionScanComment()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $params = Yii::$app->request->post();
        $isBlacklistEnabled = AmosComments::instance()->enableBlacklistWordsFilter;
        $scanComment = false;
        if (!empty($params['isReply']) && !empty($params['commentId'])) {
            $comment = Comment::findOne($params['commentId']);
            if ($comment) {
                $params['contextClassname'] = $comment->context;
                $params['contextId'] = $comment->context_id;
            }
        }

        if ($isBlacklistEnabled) {
            if (!empty($params['contextClassname']) && !empty($params['contextId'])) {
                $contextClassname = $params['contextClassname'];
                $modelsEnabled = AmosComments::instance()->blacklistModelsEnabled;
                $isContextModelEnabled = in_array($contextClassname, $modelsEnabled);
                if ($isContextModelEnabled) {
                    /** @var CommentDashboard $commentDashboard */
                    $commentDashboard = CommentDashboard::find()->one();
                    $contextId = $params['contextId'];
                    $contextModel = $contextClassname::findOne($contextId);
                    $isCommunityContent = false;
                    if ($contextModel && method_exists($contextModel, 'isNetworkContent')) {
                        $isCommunityContent = $contextModel->isNetworkContent('community');
                    }
                    if ($isCommunityContent) {
                        $scanComment = $commentDashboard->netiquette_communities;
                    } else {
                        $scanComment = $commentDashboard->netiquette_platform;
                    }
                }
            } else if ($params['isChat']) {
                /** @var \open20\amos\cwh\AmosCwh $cwhModule */
                $cwhModule = \Yii::$app->getModule('cwh');
                if (!empty($cwhModule)) {
                    $scope = $cwhModule->getCwhScope();
                    if (!empty($scope['community'])) {
                        /** @var CommentDashboard $commentDashboard */
                        $commentDashboard = CommentDashboard::find()->one();
                        $scanComment = $commentDashboard->netiquette_communities;
                    }
                }
            }
        }

        // If the blacklist words filter is disabled, skip the check
        if (!$scanComment) {
            return [
                'containsBlacklistedWords' => false,
            ];
        } else {
            $commentText = $params['commentText'];

            $blacklistWords = CommentBlacklist::find()->select('word')->column();
            $containsBlacklistedWords = false;

            $modalCommentText = $commentText;
            $lowercaseCommentText = strtolower($commentText);
            $highlightedWordsMap = [];

            // Check if the comment contains blacklisted words
            foreach ($blacklistWords as $word) {
                $lowercaseWord = strtolower($word);
                // If the comment contains a blacklisted word, highlight it
                if (stripos($lowercaseCommentText, $lowercaseWord) !== false) {
                    $containsBlacklistedWords = true;

                    $originalWord = substr($modalCommentText, stripos($lowercaseCommentText, $lowercaseWord), strlen($word));
                    $highlightedWord = '<span class="text-danger">' . $originalWord . '</span>';
                    // Build the map of the original words and the highlighted ones
                    $highlightedWordsMap[$originalWord] = $highlightedWord;
                }
            }

            // Replace the original words with the highlighted ones
            $modalCommentText = str_replace(array_keys($highlightedWordsMap), array_values($highlightedWordsMap), $modalCommentText);

            $modalBody = '';
            $modalFooter = '';

            if ($containsBlacklistedWords) {
                $modalBody = AmosComments::t('amoscomments', '#comment_blacklist_modal_message');
                $modalBody .= '<br><br>';
                $modalBody .= $modalCommentText;
                $modalFooterBtn = Html::button(AmosComments::t('amoscomments', 'Chiudi'), [
                    'class' => 'btn btn-secondary',
                    'data-dismiss' => 'modal'
                ]);
                $modalFooter = Html::tag('div', $modalFooterBtn, ['class' => 'modal-footer']);
            }

            return [
                'containsBlacklistedWords' => $containsBlacklistedWords,
                'modalBody' => $modalBody,
                'modalFooter' => $modalFooter
            ];
        }
    }

    /**
     * Before action
     * @param $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {

        $titleSection = AmosComments::t('amoscomments', 'Netiquette');
//        $labelLinkAll = '';
//        $urlLinkAll = '';
//        $titleLinkAll = '';

//        $subTitleSection = Html::tag('p', \Yii::t('open_challenge', 'Elenco Challenge'));
//        $subTitleSection = null;

//        $labelCreate = AmosComments::t('amoscomments', 'Aggiungi termine');
//        $titleCreate = AmosComments::t('amoscomments', 'Aggiungi un nuovo termine alla blacklist');
//        $labelManage = \Yii::t('amoscollaborations', 'Gestisci');
//        $titleManage = \Yii::t('amoscollaborations', 'Gestisci proposte di collaborazione');
//        $urlCreate = '/comments/comment-blacklist/create';
//        $urlManage = null;

        $this->view->params = [
            'isGuest' => \Yii::$app->user->isGuest,
            'modelLabel' => 'commentBlacklist',
            'titleSection' => $titleSection,
//            'subTitleSection' => $subTitleSection,
//            'urlLinkAll' => $urlLinkAll,
//            'labelLinkAll' => $labelLinkAll,
//            'titleLinkAll' => $titleLinkAll,
//            'labelManage' => $labelManage,
//            'titleManage' => $titleManage,
//            'labelCreate' => $labelCreate,
//            'titleCreate' => $titleCreate,
//            'urlCreate' => $urlCreate,
//            'urlManage' => $urlManage,
//            'hideCreate' => false,

        ];
        if (!parent::beforeAction($action)) {
            return false;
        }

        // other custom code here

        return true;
    }
}
