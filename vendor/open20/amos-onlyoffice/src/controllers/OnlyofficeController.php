<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\onlyoffice\controllers
 * @category   CategoryName
 */

namespace open20\onlyoffice\controllers;

use open20\amos\attachments\models\File;
use open20\amos\core\controllers\BackendController;
use open20\amos\core\controllers\BaseController;
//use open20\amos\core\module\BaseAmosModule;
use open20\onlyoffice\models\OnlyofficeFiles;
use Yii;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\base\InvalidValueException;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
//use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;


/**
 * Class OnlyofficeController
 * 
 * @package open20\onlyoffice\controllers
 */
//class OnlyofficeController extends Controller
class OnlyofficeController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $this->setModelObj(new OnlyofficeFiles());

        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    protected function getRules() {
        return [
            [
                'allow' => true,
                'actions' => [
                    'index', 'view', 'create', 'edit', 'create-ajax', 'delete',
                    'update', 'associa', 'upload', 'callback-api', 'index-only'
                ],
//                'roles' => ['@'],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
//        $rules = [];
//        if ($this->module != null && $this->module instanceof BaseAmosModule) {
//            if ($this->module->rbacEnabled === false) {
//                $rules = [
//                    [
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ],
//                ];
//            }
//        }
//
//        $rules = array_merge($rules, $this->getRules());
        $rules = $this->getRules();

//        $behaviors = \yii\helpers\ArrayHelper::merge(parent::behaviors(), [
//            'access' => [
//                'class' => AccessControl::className(),
//                'rules' => $rules,
//            ],
////            'verbs' => [
////                'class' => VerbFilter::className(),
////                'actions' => [
////                    'logout' => ['post']
////                ]
////            ]
//        ]);
//        $behaviors = [
//            'access' => [
//                'class' => AccessControl::className(),
//                'rules' => $rules,
//            ],
////            'verbs' => [
////                'class' => VerbFilter::className(),
////                'actions' => [
////                    'logout' => ['post']
////                ]
////            ]
//        ];
        $behaviors = \yii\helpers\ArrayHelper::merge(BackendController::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => $rules,
            ],
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'logout' => ['post']
//                ]
//            ]
        ]);
//        $behaviors['access']['denyCallback'] = null;
//        pr($behaviors); die;

        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    public function beforeAction($action)
    {
        if (strcmp($action, 'callback-api') == 0)
        {
            $this->enableCsrfValidation = false;
        }
        
        return parent::beforeAction($action);
    }

    /**
     * Recupera il nome della classe del model che gestisce gli alleggati (File,
     * relativo alla tabella 'attach_file')
     * @return string Nome della classe
     * @throws InvalidConfigException Se il model 'attachments' non e' presente
     * o se non restituisce il nome del model che gestisce gli alleggati
     */
    protected function getModelFileClassName()
    {
        $modelAttach = Yii::$app->getModule('attachments');
        if (empty($modelAttach)) {
            throw new InvalidConfigException('Modulo attachments non presente');
        }
        /** @var \open20\amos\attachments\FileModule $modelAttach */
        $nomeClasse = $modelAttach->getDefaultModels();
        if (empty($nomeClasse)) {
            throw new InvalidConfigException('Nome della classe del model che'
                . ' gestisce gli allegati non presente nel modulo attachments');
        }
        return $nomeClasse;
    }
    
    public function actionIndex()
    {
//        $this->setUpLayout('list');

        //recupero il modulo di OnlyOffice
        $modulo = $this->module;

        $modelFileClassName = $this->getModelFileClassName();
        //il conrollo avviene gia' nel metodo chiamato
//        if (empty($modelFileClassName))
//        {
//            throw new InvalidConfigException('Model di default del modulo'
//                . ' attachments non presente');
//        }
        /** @var \yii\db\ActiveQuery $query */
        $query = $modelFileClassName::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $query->andWhere(['in', 'type', $modulo->getAllExtension()]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionCreate()
    {
        $model = new OnlyofficeFiles();
        $model->setScenario($model::SCENARIO_CREATE);

//        $modulo = Yii::$app->getModule('onlyoffice');
        $modulo = $this->module;
        if (empty($modulo))
        {
            throw new InvalidConfigException('Modulo di OnlyOffice non'
                . ' presente');
        }
        /** @var \open20\onlyoffice\Module $modulo */

        if (($model->load(Yii::$app->request->post())) && ($model->validate()))
        {
            //recupero il file di esempio da clonare per generare quello nuovo
            $nomeFile = $modulo->getFileSampleByType($model->tipo_file, false);
            if (empty($nomeFile)) {
                Yii::$app->getSession()->addFlash('warning', 'Tipo di file non'
                    . ' creabile');
                Yii::warning('Il tipo di file ' . $model->tipo_file . ' non ha'
                    . ' un file di esempio valido');
                return $this->render('create', [
                    'model' => $model,
                    'tipiGestibili' => $modulo->tipiGestibili,
                ]);
                
            }
            $nomeFileWithPath = Yii::getAlias($modulo->samplesPath) . $nomeFile;
            if (!file_exists($nomeFileWithPath))
            {
                Yii::$app->getSession()->addFlash('warning', 'Tipo di file non'
                    . ' creabile');
                Yii::warning('Il tipo di file ' . $model->tipo_file . ' non ha'
                    . ' un file di esempio presente nel server');
                return $this->render('create', [
                    'model' => $model,
                    'tipiGestibili' => $modulo->tipiGestibili,
                ]);
            }
            
            $transaction = Yii::$app->db->beginTransaction();

            /**
             * salvo i dati per OnlyOffice (se no non e' possibile 'collegare'
             * il file)
             */
            try {
                if (!$model->save()) {
                    $transaction->rollBack();
                    Yii::$app->getSession()->addFlash('danger', 'Errore nella'
                        . ' creazione dei dati (iniziale)');
                    return $this->render('create', [
                        'model' => $model,
                        'tipiGestibili' => $modulo->tipiGestibili,
                    ]);
                }
            } catch (\Exception $exc) {
                $transaction->rollBack();
                Yii::$app->getSession()->addFlash('danger', 'Eccezione nella'
                    . ' creazione dei dati (iniziale)');
                Yii::error('Eccezione nella creazione dei dati (iniziale): '
                    . $exc->getMessage());
                return $this->render('create', [
                    'model' => $model,
                    'tipiGestibili' => $modulo->tipiGestibili,
                ]);
            }

            //'Attacco' il file di esempio al model
            $moduleAttach = Yii::$app->getModule('attachments');
            if (empty($moduleAttach))
            {
                $transaction->rollBack();
                throw new InvalidConfigException('Modulo attachments non'
                    . ' presente');
            }
            /** @var \open20\amos\attachments\FileModule $moduleAttach */
            $modelFile = $moduleAttach->attachFile($nomeFileWithPath, $model,
                'file', false, false);
            if (empty($modelFile))
            {
                $transaction->rollBack();
                Yii::$app->getSession()->addFlash('danger', 'Errore nella'
                    . ' creazione del file');
                Yii::error('Errore nella copia/collegamento del file di'
                    . ' esempio per il tipo' . $model->tipo_file);
                return $this->render('create', [
                    'model' => $model,
                    'tipiGestibili' => $modulo->tipiGestibili,
                ]);
            }
            /** @var File $modelFile */
            
            //verifico se assegnare un nome diverso al file
            if ((!empty($model->nome_file))
                && (strcmp($model->nome_file, $modelFile->name) != 0))
            {
                $modelFile->name = $model->nome_file;
                try {
                    if (!$modelFile->save())
                    {
                        $transaction->rollBack();
                        Yii::$app->getSession()->addFlash('danger', 'Errore nel'
                            . ' rinominare il file');
                        return $this->render('create', [
                            'model' => $model,
                            'tipiGestibili' => $modulo->tipiGestibili,
                        ]);
                    }
                } catch (\Exception $exc) {
                    $transaction->rollBack();
                    Yii::$app->getSession()->addFlash('danger', 'Eccezione nel'
                        . ' rinominare il file');
                    Yii::error('Eccezione nel rinominare il file: '
                        . $exc->getMessage());
                    return $this->render('create', [
                        'model' => $model,
                        'tipiGestibili' => $modulo->tipiGestibili,
                    ]);
                }
            }
            
            //aggiorno i dati del model di OnlyOffice e li salvo
            $model->attach_file_id = $modelFile->id;
            $model->link_diretto = 1;
            $model->creato_il = date('Y-m-d H:i:s');
            $model->creato_da = ArrayHelper::getValue(Yii::$app, 'user.id');
            try {
                if (!$model->save()) {
                    $transaction->rollBack();
                    Yii::$app->getSession()->addFlash('danger', 'Errore nella'
                        . ' creazione dei dati');
                    return $this->render('create', [
                        'model' => $model,
                        'tipiGestibili' => $modulo->tipiGestibili,
                    ]);
                }
            } catch (\Exception $exc) {
                $transaction->rollBack();
                    Yii::$app->getSession()->addFlash('danger', 'Eccezione'
                        . ' nella creazione dei dati');
                    Yii::error('Eccezione nella creazione dei dati: '
                        . $exc->getMessage());
                    return $this->render('create', [
                        'model' => $model,
                        'tipiGestibili' => $modulo->tipiGestibili,
                    ]);
            }
            
            Yii::$app->getSession()->addFlash('success', 'File creato'
                . ' correttamente');
            $transaction->commit();

            return $this->redirect(['edit', 'id' => $model->id]);
//            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
            'tipiGestibili' => $modulo->tipiGestibili,
        ]);
    }

    public function actionEdit($id)
//    public function actionUpdate($id)
    {
        //recupero i dati inerenti ad OnlyOffice
//        $idModel = Yii::$app->request->get('id');
//        if (empty($idModel)) {
//            throw new NotFoundHttpException('Id del model non presente');
//        }
        $model = OnlyofficeFiles::findOne($id);
        if (empty($model))
        {
            throw new NotFoundHttpException('Dati non presenti');
        }

//        $modelFile = $model->file;
//        if (empty($modelFile)) {
//            if (empty($model->attach_file_id))
//            {
//                throw new InvalidValueException('Collegamento al file'
//                    . ' mancante');
//            }
//            //recupero il nome della classe che gestisce gli allegati
//            $modelClassName = $this->getModelFileClassName();
//            //il conrollo avviene gia' nel metodo chiamato
//    //        if (empty($modelClassName))
//    //        {
//    //            throw new InvalidConfigException('Model di default del modulo'
//    //                . ' attachments non presente');
//    //        }
//            $modelFile = $modelClassName::findOne($model->attach_file_id);
//            if (empty($modelFile)) {
//                throw new InvalidValueException('Dati del file non presenti');
//            }
//        }
        $modelFile = $model->getModelFile();
        if (empty($modelFile))
        {
            throw new InvalidValueException('Collegamento al file mancante');
        }

//        if (!empty($model->in_modifica))
//        {
//            Yii::$app->getSession()->addFlash('warning', 'Il file e\''
//                . ' attualmente in modifica da un altro utente ed e\' quindi'
//                . ' stato aperto in visualizzazione');
//            return $this->render('view', [
//                'model' => $model,
//                'modelFile' => $modelFile,
//            ]);
//        }

        return $this->render('edit', [
            'model' => $model,
            'modelFile' => $modelFile,
        ]);
    }

    /**
     * Crea un'associazione fra i dati del file (della tabella 'attach_file') il
     * cui di e' passato al metodo con i dati di OnlyOffice e una volta
     * terminata l'operazione visualizza il file (con la possibilita' di
     * modificarlo) nell'interfaccia di OnlyOffice
     * @param int $id Id dei dati del file (della tabella 'attach_file') con cui
     * creare l'associazione
     * @throws InvalidArgumentException Se i parametri passati sono assenti o
     * non associati a dei dati validi o se si incorre in un problema durante
     * l'associazione
     */
    public function actionAssocia($id)
    {
        if (empty($id))
        {
            throw new InvalidArgumentException('Id del file non presente');
        }
        $modelAttach = File::findOne($id);
        if (empty($modelAttach))
        {
            throw new InvalidArgumentException('Dati del file non presenti');
        }
        $modelO = OnlyofficeFiles::searchByModelFile($modelAttach);
        if (empty($modelO))
        {
            try {
                $modelO = OnlyofficeFiles::associa($modelAttach, false);
                if (empty($modelO))
                {
                    throw new InvalidArgumentException('Errore'
                        . ' nell\'associazione');
                }
            } catch (\Exception $exc) {
                Yii::error('Eccezione nell\'associazione: '
                    . $exc->getMessage());
                throw new InvalidArgumentException('Eccezione'
                        . ' nell\'associazione');
            }
        }
        return $this->redirect(['edit', 'id' => $modelO->id]);
    }

    public function actionView($id)
    {
        $model = OnlyofficeFiles::findOne($id);
        if (empty($model))
        {
            throw new NotFoundHttpException('Dati non presenti');
        }

        $modelFile = $model->getModelFile();
        if (empty($modelFile))
        {
            throw new InvalidValueException('Collegamento al file mancante');
        }

        if (!empty($model->in_modifica))
        {
            Yii::$app->getSession()->addFlash('warning', 'Il file e\''
                . ' attualmente in modifica da un altro utente e\' quindi'
                . ' il suo contenuto potrebbe non essere aggiornato');
        }
        
        return $this->render('view', [
            'model' => $model,
            'modelFile' => $modelFile,
        ]);
    }

    public function actionUpload()
    {
        $model = new OnlyofficeFiles();
        $model->setScenario($model::SCENARIO_UPLOAD);
        $model->link_diretto = 1;

////        $modulo = Yii::$app->getModule('onlyoffice');
//        $modulo = $this->module;
//        if (empty($modulo))
//        {
//            throw new InvalidConfigException('Modulo di OnlyOffice non'
//                . ' presente');
//        }
//        /** @var \open20\onlyoffice\Module $modulo */

        if (($model->load(Yii::$app->request->post())) && ($model->validate()))
        {
            $transaction = Yii::$app->db->beginTransaction();
            
            //salvo i dati per OnlyOffice (se no non si 'collega'il file)
            try {
                if (!$model->save()) {
                    $transaction->rollBack();
                    Yii::$app->getSession()->addFlash('danger', 'Errore nella'
                        . ' creazione dei dati (iniziale)');
                    return $this->render('upload', [
                        'model' => $model,
                    ]);
                }
            } catch (\Exception $exc) {
                $transaction->rollBack();
                Yii::$app->getSession()->addFlash('danger', 'Eccezione nella'
                    . ' creazione dei dati (iniziale)');
                Yii::error('Eccezione nella creazione dei dati (iniziale): '
                    . $exc->getMessage());
                return $this->render('upload', [
                    'model' => $model,
                ]);
            }

            /**
             * recupero il model del file per poter inserire il suo id nei dati
             * di OnlyOffice
             */
            /**
             * il recupero dei dati con una query non funziona se i dati non
             * sono prima committati
             */
//            $modelFile = $model->attach;
            $modelFile = $model->file;
            if (empty($modelFile))
            {
                $transaction->rollBack();
                Yii::$app->getSession()->addFlash('danger', 'Errore nel'
                    . ' recupero dei dati del file');
                Yii::error('Errore nel recupero dei dati del file legato ai'
                    . ' dati di OnlyOffice con id' . $model->id);
                return $this->render('upload', [
                    'model' => $model,
                ]);
            }
            $model->attach_file_id = $modelFile->id;
            $model->creato_il = date('Y-m-d H:i:s');
            $model->creato_da = ArrayHelper::getValue(Yii::$app, 'user.id');
            
            if (!empty($model->nome_file))
            {
                //assegno un nome diverso al file
                $modelFile->name = $model->nome_file;
                try {
                    if (!$modelFile->save())
                    {
                        $transaction->rollBack();
                        Yii::$app->getSession()->addFlash('danger', 'Errore nel'
                            . ' rinominare il file');
                        return $this->render('upload', [
                            'model' => $model,
                        ]);
                    }
                } catch (\Exception $exc) {
                    $transaction->rollBack();
                    Yii::$app->getSession()->addFlash('danger', 'Eccezione nel'
                        . ' rinominare il file');
                    Yii::error('Eccezione nel rinominare il file: '
                        . $exc->getMessage());
                    return $this->render('upload', [
                        'model' => $model,
                    ]);
                }
            }
            
            //salvo i dati aggiornati di OnlyOffice
            try {
                if (!$model->save()) {
                    $transaction->rollBack();
                    Yii::$app->getSession()->addFlash('danger', 'Errore nella'
                        . ' creazione dei dati');
                    return $this->render('upload', [
                        'model' => $model,
                    ]);
                }
            } catch (\Exception $exc) {
                $transaction->rollBack();
                    Yii::$app->getSession()->addFlash('danger', 'Eccezione'
                        . ' nella creazione dei dati');
                    Yii::error('Eccezione nella creazione dei dati: '
                        . $exc->getMessage());
                    return $this->render('upload', [
                        'model' => $model,
                    ]);
            }
            
            Yii::$app->getSession()->addFlash('success', 'File caricato'
                . ' correttamente');
            $transaction->commit();

            return $this->redirect(['edit', 'id' => $model->id]);
//            return $this->redirect(['index']);
        }

        return $this->render('upload', [
            'model' => $model,
        ]);
    }

    /**
     * Gestisce i dati di callback provenienti dalle API del server di
     * OnlyOffice
     */
    public function actionCallbackApi()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $dati = Yii::$app->request->post();

        if (empty($dati))
        {
            Yii::error('Nessun dato');
            return ['error' => 1];
        }
        if (empty($dati['key']))
        {
            Yii::error('Id dei dati di OnlyOffice non presente');
            return ['error' => 1];
        }

        //estraggo l'id del record dei dati di Onlyoffice dalla chiave univoca
        try {
            $idRecord = OnlyofficeFiles::extractIdFromKey($dati['key']);
        } catch (InvalidArgumentException $exc) {
            Yii::error('Eccezione nell\'estrazione dell\'id del record dei dati'
                . ' di OnlyOffice dalla chiave univoca: ' . $exc->getMessage());
            return ['error' => 1];
        } catch (\Exception $exc) {
            Yii::error('Eccezione (di tipo non previsto) nell\'estrazione'
                . 'dell\'id del record dei dati di OnlyOffice dalla chiave'
                . ' univoca: ' . $exc->getMessage());
            return ['error' => 1];
        }
        
        //recupero i dati del record dei dati di Onlyoffice
        $model = OnlyofficeFiles::findOne($idRecord);
        if (empty($model))
        {
            Yii::error('Dati di OnlyOffice legati all\'id ' . $dati['key']
                . ' non presenti');
            return ['error' => 1];
        }
        
        Yii::warning($dati);

        $errore = false;

        $model->last_status = $dati['status'];
        switch ($dati['status']) {
            case 0:
                //not found???
                Yii::warning('Lo stato 0 non dovrebbe essere possibile');
                break;
            case 1:
                //documento in modifica
                /*[
    'key' => '1',
    'status' => 1,
    'users' => [
        '1',
    ],
    'actions' => [
        [
            'type' => 1,
            'userid' => '1',
        ],
    ],
    'token' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJrZXkiOiIxIiwic3RhdHVzIjoxLCJ1c2VycyI6WyIxIl0sImFjdGlvbnMiOlt7InR5cGUiOjEsInVzZXJpZCI6IjEifV0sImlhdCI6MTY2OTc5NjczNiwiZXhwIjoxNjY5Nzk3MDM2fQ.8OFfXzFJf5YPxwZyIYn385lMUQVRhnQ5hHhkdRRuJH4',
]*/
                $model->in_modifica = 1;
                $model->open_in_mod_at = date('Y-m-d H:i:s');
                if ((!empty($dati['users'])) && (is_array($dati['users'])))
                {
                    $idUser = array_shift($dati['users']);
                    if (preg_match('/^[0-9]+$/', $idUser) == 1)
                    {
                        $model->open_in_mod_by = $idUser;
                    }
                }
                break;
            case 2:
                //documento pronto per essere salvato
                /*
                 * 'key' => '2003738887',
    'status' => 2,
    'url' => 'https://ds.demotestwip.it/cache/files/data/2003738887_273/output.docx/output.docx?md5=NqhWf2sVLcB86N9Ch_YhXw&expires=1669290215&filename=output.docx',
    'changesurl' => 'https://ds.demotestwip.it/cache/files/data/2003738887_273/changes.zip/changes.zip?md5=w84zNYZnHvh3BsSklzRxbg&expires=1669290215&filename=changes.zip',
    'history' => [
        'serverVersion' => '7.2.1',
        'changes' => [
            [
                'created' => '2022-11-24 11:27:58',
                'user' => [
                    'id' => 'uid-1669132082977',
                    'name' => 'Anonymous',
                ],
            ],
        ],
    ],
    'users' => [
        'uid-1669132082977',
    ],
    'actions' => [
        [
            'type' => 0,
            'userid' => 'uid-1669132082977',
        ],
    ],
    'lastsave' => '2022-11-24T11:27:58.000Z',
    'notmodified' => false,
    'token' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJrZXkiOiIyMDAzNzM4ODg3Iiwic3RhdHVzIjoyLCJ1cmwiOiJodHRwczovL2RzLmRlbW90ZXN0d2lwLml0L2NhY2hlL2ZpbGVzL2RhdGEvMjAwMzczODg4N18yNzMvb3V0cHV0LmRvY3gvb3V0cHV0LmRvY3g_bWQ1PU5xaFdmMnNWTGNCODZOOUNoX1loWHcmZXhwaXJlcz0xNjY5MjkwMjE1JmZpbGVuYW1lPW91dHB1dC5kb2N4IiwiY2hhbmdlc3VybCI6Imh0dHBzOi8vZHMuZGVtb3Rlc3R3aXAuaXQvY2FjaGUvZmlsZXMvZGF0YS8yMDAzNzM4ODg3XzI3My9jaGFuZ2VzLnppcC9jaGFuZ2VzLnppcD9tZDU9dzg0ek5ZWm5IdmgzQnNTa2x6UnhiZyZleHBpcmVzPTE2NjkyOTAyMTUmZmlsZW5hbWU9Y2hhbmdlcy56aXAiLCJoaXN0b3J5Ijp7InNlcnZlclZlcnNpb24iOiI3LjIuMSIsImNoYW5nZXMiOlt7ImNyZWF0ZWQiOiIyMDIyLTExLTI0IDExOjI3OjU4IiwidXNlciI6eyJpZCI6InVpZC0xNjY5MTMyMDgyOTc3IiwibmFtZSI6IkFub255bW91cyJ9fV19LCJ1c2VycyI6WyJ1aWQtMTY2OTEzMjA4Mjk3NyJdLCJhY3Rpb25zIjpbeyJ0eXBlIjowLCJ1c2VyaWQiOiJ1aWQtMTY2OTEzMjA4Mjk3NyJ9XSwibGFzdHNhdmUiOiIyMDIyLTExLTI0VDExOjI3OjU4LjAwMFoiLCJub3Rtb2RpZmllZCI6ZmFsc2UsImZpbGV0eXBlIjoiZG9jeCIsImlhdCI6MTY2OTI4OTMxNCwiZXhwIjoxNjY5Mjg5NjE0fQ.cnED5GBse2jwN8FX-VO4nRkPW46wsLML8fWjwAIPs34',
    'filetype' => 'docx',

                 */
                
                /**
                 * verifico che fra i dati forniti alla callback sia presente
                 * l'URL per scaricare il file
                 */
                if (empty($dati['url']))
                {
                    $errore = true;
                    $model->in_modifica = 0;
                    Yii::error('Documento con id ' . $dati['key'] . ' pronto'
                        . ' per essere salvato, ma URL assente nei dati forniti'
                        . ' alla callback');
                    break;
                }
                
                /**
                 * recupero i dati dell'attachment associato ai dati di
                 * OnlyOffice
                 */
                $modelFileOld = $model->getModelFile();
                if (empty($modelFileOld))
                {
                    $errore = true;
                    $model->in_modifica = 0;
                    Yii::error('Dati del file (da sostituire) collegato ai dati'
                        . ' di Onlyoffice con id ' . $dati['key'] . ' non'
                        . ' recuperabili');
                    break;
                }
                /**
                 * memorizzo in un array i dati del model file in modo da non
                 * perderli dopo la sua cancellazione che avverra' quando viene
                 * chiamato il metodo 'detachFile'
                 */
                $modelFileOldArr = $modelFileOld->toArray();
                $modelOwnerFileOld = $modelFileOld->owner;
                if (empty($modelOwnerFileOld))
                {
                    $errore = true;
                    $model->in_modifica = 0;
                    Yii::error('Errore nel recupero del model a cui e\' legato'
                        . ' il file (da sostituire) collegato ai dati'
                        . ' di Onlyoffice con id ' . $dati['key']);
                    break;
                }
                $modelFileOldRes = $modelFileOld->attachFileRefs;
                if (empty($modelFileOldRes))
                {
                    $errore = true;
                    $model->in_modifica = 0;
                    Yii::error('Errore nel recupero dei dati della tabella'
                        . ' \'attach_file_refs\' legati al file (da sostituire)'
                        . ' collegato ai dati di Onlyoffice con id '
                        . $dati['key']);
                    break;
                }
                
                /**
                 * creo un file temporaneo e gli salvo il file proveniente dal
                 * server
                 */
                $tempFileNameNoEst = tempnam(sys_get_temp_dir(), 'onlyoffice_');
                if ($tempFileNameNoEst === false)
                {
                    $errore = true;
                    $model->in_modifica = 0;
                    Yii::error('Errore nella creazione del file temporaneo per'
                        . ' il nuovo file collegato ai dati di Onlyoffice con'
                        . ' id ' . $dati['key']);
                    break;
                }
                /**
                 * visto che il file viene generato senza estensione e in
                 * seguito sara' indispensabile, rinomino il file aggiungendola
                 */
                $tempFileName = $tempFileNameNoEst . '.'
                    . $modelFileOldArr['type'];
                if (!rename($tempFileNameNoEst, $tempFileName))
                {
                    $errore = true;
                    $model->in_modifica = 0;
                    Yii::error('Errore nel rinominare il file temporaneo per'
                        . ' il nuovo file collegato ai dati di Onlyoffice con'
                        . ' id ' . $dati['key']);
                    break;
                }
                Yii::warning('Nome del file temporaneo: ' . $tempFileName);
                if (file_put_contents($tempFileName,
                    file_get_contents($dati['url'])) === false)
                {
                    $errore = true;
                    $model->in_modifica = 0;
                    Yii::error('Errore nel recupero del file dal server'
                        . ' inerente al documento con id ' . $dati['key']
                        . ' pronto per essere salvato');
                    break;
                }
                Yii::warning('Hash del file temporaneo: ' . hash_file(
                    'sha256', $tempFileName));
                
                //recupero il modulo 'attachments'
                $moduleAttach = Yii::$app->getModule('attachments');
                if (empty($moduleAttach))
                {
                    $errore = true;
                    $model->in_modifica = 0;
                    Yii::error('Modulo attachments non presente');
                    break;
                }
                /** @var \open20\amos\attachments\FileModule $moduleAttach */

                //Cancello i dati del vecchio file e il file dal server
                if (!$moduleAttach->detachFile($modelFileOld->id))
                {
                    $errore = true;
                    $model->in_modifica = 0;
                    Yii::error('Errore nella cancellazione del file dal server'
                        . ' inerente al documento con id ' . $dati['key']);
                    break;
                }
                /**
                 * visto che al momento il metodo 'detachFile' non cancella i
                 * dati della tabella 'attach_file_refs', lo faccio manualmente.
                 * N.B. In teoria, visto che questo model non ha una soft-delete
                 * il risultato dell'operazione e' veritiero
                 */
                if (!$modelFileOldRes->delete())
                {
                    $errore = true;
                    $model->in_modifica = 0;
                    Yii::error('Errore nella cancellazione dei dati della'
                        . ' tabella \'attach_file_refs\' legati al file'
                        . ' (da sostituire) collegato ai dati di Onlyoffice con'
                        . ' id ' . $dati['key']);
                    break;
                }

                /**
                 * 'attacco' il file di esempio al 'vecchio' model e al
                 * 'vecchio' attributo che non sono necessariamente relativi ai
                 * dati di OnlyOffice
                 */
                $modelFile = $moduleAttach->attachFile($tempFileName,
                    $modelOwnerFileOld, $modelFileOldArr['attribute'], true,
                    false);
                if (empty($modelFile))
                {
                    $errore = true;
                    $model->in_modifica = 0;
                    Yii::error('Errore nella copia/collegamento del file'
                        . ' proveniente dal server inerente al documento con'
                        . ' id ' . $dati['key']);
                    break;
                }
                /** @var File $modelFile */

                //assegnare il nome 'originale' al file
                $modelFile->name = $modelFileOldArr['name'];
//                $modelFile->type = $modelFileOldArr['type'];
                try {
                    if (!$modelFile->save())
                    {
                        $errore = true;
                        $model->in_modifica = 0;
                        Yii::error('Errore nel rinominare il file inerente al'
                            . ' documento con id ' . $dati['key']);
                        break;
                    }
                } catch (\Exception $exc) {
                    $errore = true;
                    $model->in_modifica = 0;
                    Yii::error('Eccezione nel rinominare il file inerente al'
                        . ' documento con id ' . $dati['key'] . ': '
                        . $exc->getMessage());
                    break;
                }
                $model->attach_file_id = $modelFile->id;
                $model->in_modifica = 0;
                $model->modified_at = date('Y-m-d H:i:s');
                if ((!empty($dati['users'])) && (is_array($dati['users'])))
                {
                    $idUser = array_shift($dati['users']);
                    if (preg_match('/^[0-9]+$/', $idUser) == 1)
                    {
                        $model->modified_by = $idUser;
                    }
                }
                break;
            case 3:
                //errore nel salvataggio

                break;
            case 4:
                //documento chiuso senza modifiche
                $model->in_modifica = 0;
                break;
            case 6:
                //documento in modifica, ma nello stato 'is saved'

                break;
            case 7:
                //errore durante il salvataggio 'forzato' del documento
                break;
            default:
                Yii::warning('Stato non gestito: ' . $dati['status']);
                break;
        }
        
        try {
            if (!$model->save())
            {
                Yii::error('Errore nell\'aggiornamento (callback) dei dati di'
                    . ' OnlyOffice con id ' . $model->id . ': '
                    . print_r($model->errors, true));
                return ['error' => 1];
            }
        } catch (\Exception $exc) {
            Yii::error('Eccezione nell\'aggiornamento (callback) dei dati di'
                . ' OnlyOffice con id ' . $model->id . ': '
                . $exc->getMessage());
            return ['error' => 1];
        }
        if (!empty($errore))
        {
            return ['error' => 1];
        }
        return ['error' => 0];
    }

    public function actionIndexOnly()
    {
//        $this->setUpLayout('list');

        //recupero il modulo di OnlyOffice
//        $modulo = $this->module;

        /** @var \yii\db\ActiveQuery $query */
        $query = OnlyofficeFiles::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $this->render('index_only', [
            'dataProvider' => $dataProvider,
        ]);
    }
}