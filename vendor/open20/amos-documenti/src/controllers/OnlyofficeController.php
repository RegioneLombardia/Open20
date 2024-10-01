<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\onlyoffice\controllers
 * @category   CategoryName
 */

namespace open20\amos\documenti\controllers;

use open20\amos\attachments\models\File;
use open20\amos\core\controllers\BackendController;
use open20\amos\core\controllers\BaseController;
use open20\onlyoffice\models\OnlyofficeFiles;
use Yii;
use yii\base\InvalidArgumentException;
use yii\filters\AccessControl;
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
                'actions' => ['callback-api'],
//                'roles' => ['@'],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {

        $rules = $this->getRules();

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
        //if (strcmp($action, 'callback-api') == 0)
        //{
            $this->enableCsrfValidation = false;
        //}
        
        return parent::beforeAction($action);
    }



    /**
     * Gestisce i dati di callback provenienti dalle API del server di
     * OnlyOffice
     */
    public function actionCallbackApi()
    {        
        Yii::$app->response->format = Response::FORMAT_JSON;
        $command = \Yii::$app->db->createCommand();
        
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
            $hashCode = $dati['key'];
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
        $modelFile = File::findOne(['hash'=>$hashCode]);
        if (empty($modelFile))
        {
            Yii::error('Dati di OnlyOffice legati all\'id ' . $dati['key']
                . ' non presenti');
            return ['error' => 1];
        }
         
        $class = $modelFile->model;
        $model = $class::findOne(['id'=>$modelFile->item_id]);
 
        if (empty($model))
        {
            Yii::error('modello ' . $class . 'con id '. $modelFile->item_id
                . ' non presenti');
            return ['error' => 1];
        }
        $errore = false;
		
 
        switch ($dati['status']) {
            case 0:
                //not found???
                Yii::warning('Lo stato 0 non dovrebbe essere possibile');
                break;
            case 1:
                //documento in modifica appena si entra in edit mode
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
                             
                $command->update($model->tableName(),[                      
                        'updated_at' => date('Y-m-d H:i:s'),
                    ],['id'=>$model->id])->execute();

                if ((!empty($dati['users'])) && (is_array($dati['users'])))
                {
                    $idUser = array_shift($dati['users']);
                       
                    if (preg_match('/^[0-9]+$/', $idUser) == 1)
                    { 
                        $command->update($model->tableName(),[                      
                            'updated_by' => $idUser,
                        ],['id'=>$model->id])->execute();
                    }
                                  
                }
      
                break;
          /*  case 2:
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

                           
                
                break;*/  
            case 3:
                //errore nel salvataggio

                break;
            case 4:
                //documento chiuso senza modifiche               
                break;
			case 2:
            case 6:
                //documento in modifica, ma nello stato 'is saved'
             
                if (empty($dati['url']))
                {
                    $errore = true;                 
                    Yii::error('Documento con id ' . $dati['key'] . ' pronto'
                        . ' per essere salvato, ma URL assente nei dati forniti'
                        . ' alla callback');
                    break;
                }
                
                /**
                 * recupero i dati dell'attachment associato ai dati di
                 * OnlyOffice
                 */              
                if (empty($modelFile))
                {
                    $errore = true;                   
                    Yii::error('Dati del file (da sostituire) collegato ai dati'
                        . ' di Onlyoffice con id ' . $dati['key'] . ' non'
                        . ' recuperabili');
                    break;
                }
                
                $filePath = $modelFile->getPath();
               
                if (!file_exists($filePath)) {
                    $errore = true;                   
                    Yii::error('il file '.$filePath.' non esiste');
                    break;
                }
                
                if (file_put_contents($filePath,file_get_contents($dati['url'])) === false)
                {
                    $errore = true;
                    Yii::error('Errore nel recupero del file dal server'
                        . ' inerente al documento con id ' . $dati['key']
                        . ' pronto per essere salvato');
                    break;
                }
				
				
				$modelFileOld = $modelFile;
				
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
                    Yii::error('Errore nel recupero del model a cui e\' legato'
                        . ' il file (da sostituire) collegato ai dati'
                        . ' di Onlyoffice con id ' . $dati['key']);
                    break;
                }
                $modelFileOldRes = $modelFileOld->attachFileRefs;
                if (empty($modelFileOldRes))
                {
                    $errore = true;
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
                    Yii::error('Modulo attachments non presente');
                    break;
                }
                /** @var \open20\amos\attachments\FileModule $moduleAttach */
				//Cancello i dati del vecchio file e il file dal server
                if (!$moduleAttach->detachFile($modelFileOld->id))
                {
                    $errore = true;
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
                        Yii::error(json_encode($modelFile->getErrors()));
                        Yii::error('Errore nel rinominare il file inerente al'
                            . ' documento con id ' . $dati['key']);
                        Yii::error('filesize: '.filesize($filePath));
                        break;
                    }
                } catch (\Exception $exc) {
                    $errore = true;                   
                    Yii::error('Eccezione nel rinominare il file inerente al'
                        . ' documento con id ' . $dati['key'] . ': '
                        . $exc->getMessage());
                    break;
                }
               
                $command->update($model->tableName(),[                      
                        'updated_at' => date('Y-m-d H:i:s'),
                    ],['id'=>$model->id])->execute();
                               
                if ((!empty($dati['users'])) && (is_array($dati['users'])))
                {
                    $idUser = array_shift($dati['users']);
                    if (preg_match('/^[0-9]+$/', $idUser) == 1)
                    {
                        $command->update($model->tableName(),[                      
                            'updated_by' => $idUser,
                        ],['id'=>$model->id])->execute();
                    }
                }
                
                break;
            case 7:
                //errore durante il salvataggio 'forzato' del documento
                break;
            default:
                Yii::warning('Stato non gestito: ' . $dati['status']);
                break;
        }
        
        /*try {
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
        }*/
             
        if ($errore)
        {
            return ['error' => 1];
        }
        return ['error' => 0];
    }

    
}